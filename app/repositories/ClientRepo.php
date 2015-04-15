<?php

class ClientRepo
{
	protected $cx;
	
	public function __construct($cx)
	{
		$this->cx = $cx;
	}

	public function verifyCustomer($dni, $ext, $idef, $pr = 'DE')
	{
		$table = '';
		
		switch($pr){
		case 'DE':
			$table = 's_de_cot_cliente as sc';
			break;
		case 'AU':
			$table = 's_au_cot_cliente as sc';
			break;
		case 'TRD':
			$table = 's_trd_cot_cliente as sc';
			break;
		case 'TRM':
			$table = 's_trm_cot_cliente as sc';
			break;
		case 'TH':
			$table = 's_th_cot_cliente as sc';
			break;
		}
		
		$sql = 'SELECT sc.id_cliente 
		FROM 
			' . $table . ' 
				INNER JOIN
			s_entidad_financiera as sef ON (sef.id_ef = sc.id_ef)
		WHERE 
			sc.ci = "' . $dni . '" 
				and sc.extension = ' . $ext . ' 
				and sef.id_ef = "' . $idef . '"
		;';

		if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT))) {
			if($rs->num_rows === 1){
				$row = $rs->fetch_array(MYSQLI_ASSOC);
				$rs->free();
				return [true, $row['id_cliente']];
			}
		}

		return [false, 0];
	}

	public function postClientData($data, &$data_cl)
	{
		$client = $this->verifyCustomer($data_cl['doc_id'], $data_cl['ext'], $data['idef']);

		if ($client[0] === true) {
			$data_cl['id'] = $client[1];

			if ($this->putClientData($data, $data_cl)) {
				PostDetail:
				if ($this->postDetail($data, $data_cl)) {
					return true;
				}
			}
		} else {
			$data_cl['id'] = uniqid('@S#1$2013', true);

			$sql = 'insert into s_de_cot_cliente
			(id_cliente, id_ef, tipo, razon_social, paterno,
				materno, nombre, ap_casada, fecha_nacimiento,
				lugar_nacimiento, ci, extension, complemento,
				tipo_documento, estado_civil, lugar_residencia,
				localidad, direccion, pais, id_ocupacion,
				desc_ocupacion, telefono_domicilio, telefono_oficina,
				telefono_celular, email, peso, estatura, genero,
				edad, saldo_deudor )
			values 
			("' . $data_cl['id'] . '", "' . $data['idef'] . '", 0, "", 
				"' . $data_cl['patern'] . '", "' . $data_cl['matern'] . '", 
				"' . $data_cl['name'] . '", "' . $data_cl['married'] . '",
                "' . $data_cl['birth'] . '", "' . $data_cl['place_birth'] . '", 
                "' . $data_cl['doc_id'] . '", "' . $data_cl['ext'] . '", 
                "' . $data_cl['comp'] . '", "' . $data_cl['type_doc'] . '",
                "' . $data_cl['status'] . '", ' . $data_cl['place_res'] . ', 
                "' . $data_cl['locality'] . '", "' . $data_cl['address'] . '", 
                "' . $data_cl['country'] . '", ' . $data_cl['occupation'] . ',
                "' . $data_cl['occ_desc'] . '", "' . $data_cl['phone_1'] . '", 
                "' . $data_cl['phone_office'] . '", "' . $data_cl['phone_2'] . '", 
                "' . $data_cl['email'] . '", "' . $data_cl['weight'] . '",
                "' . $data_cl['height'] . '", "' . $data_cl['gender'] . '",
                TIMESTAMPDIFF(YEAR, "' . $data_cl['birth'] . '", curdate()), 
                "' . $data_cl['amount'] . '")
			;';
			
			if ($this->cx->query($sql)) {
				goto PostDetail;
			}
		}

		return false;
	}

	public function putClientData($data, &$data_cl, $bc)
	{
		$sql = 'update s_de_cot_cliente as scl
        set scl.tipo = 0, scl.razon_social = "",
            scl.paterno 		= "' . $data_cl['patern'] . '",
            scl.materno 		= "' . $data_cl['matern'] . '",
            scl.nombre 			= "' . $data_cl['name'] . '",
            scl.ap_casada 		= "' . $data_cl['married'] . '",
            scl.fecha_nacimiento = "' . $data_cl['birth'] . '",
            scl.lugar_nacimiento = "' . $data_cl['place_birth'] . '",
            scl.ci 				= "' . $data_cl['doc_id'] . '",
            scl.extension 		= "' . $data_cl['ext'] . '",
            scl.complemento 	= "' . $data_cl['comp'] . '",
            scl.tipo_documento 	= "' . $data_cl['type_doc'] . '",
            scl.estado_civil 	= "' . $data_cl['status'] . '",
            scl.lugar_residencia = ' . $data_cl['place_res'] . ',
            scl.localidad 		= "' . $data_cl['locality'] . '",
            scl.direccion 		= "' . $data_cl['address'] . '",
            scl.pais 			= "' . $data_cl['country'] . '",
            scl.id_ocupacion 	= ' . $data_cl['occupation'] . ',
            scl.desc_ocupacion 	= "' . $data_cl['occ_desc'] . '",
            scl.telefono_domicilio = "' . $data_cl['phone_1'] . '",
            scl.telefono_oficina 	= "' . $data_cl['phone_office'] . '",
            scl.telefono_celular 	= "' . $data_cl['phone_2'] . '",
            scl.email 			= "' . $data_cl['email'] . '",
            scl.peso 			= "' . $data_cl['weight'] . '",
            scl.estatura 		= "' . $data_cl['height'] . '",
            scl.genero 			= "' . $data_cl['gender'] . '",
            scl.edad = TIMESTAMPDIFF(YEAR, "' . $data_cl['birth'] . '", curdate()),
            scl.saldo_deudor 	= "' . $data_cl['amount'] . '"
        where 
        	scl.id_cliente = "' . $data_cl['id'] . '"
        ;';

        if ($this->cx->query($sql)) {
        	if ($bc === true) {
        		if ($this->putDetail($data, $data_cl)) {
        			return true;
        		}
        	} else {
        		return true;
        	}
        }

        return false;
	}

	public function postDetail($data, &$data_cl)
	{
		$data_cl['idd'] = uniqid('@S#1$2013', true);

		$sql = 'insert into s_de_cot_detalle
        (id_detalle, id_cotizacion, id_cliente,
	        porcentaje_credito, tasa, monto_banca_comunal, titular)
		values
		("' . $data_cl['idd'] . '", "' . $data['idc'] . '", 
			"' . $data_cl['id'] . '", "' . $data_cl['percentage'] . '", 
			0, "' . $data_cl['amount_bc'] . '", "' . $data_cl['dc'] . '")
		;';

		if ($this->cx->query($sql)) {
			return true;
		}

		return false;
	}

	public function putDetail($data, &$data_cl)
	{
		$sql = 'update s_de_cot_detalle as sdd
		set monto_banca_comunal = "' . $data_cl['amount_bc'] . '"
		where sdd.id_cotizacion = "' . $data['idc'] . '"
			and sdd.id_cliente = "' . $data_cl['id'] . '"
		;';

		if ($this->cx->query($sql)) {
			return true;
		}

		return false;
	}

	public function getClientData($idc, $idef, $idcl)
	{
		$data = array();

		$sql = 'select 
			scl.id_cliente,
			scl.paterno,
			scl.materno,
			scl.nombre,
			scl.ap_casada,
			scl.fecha_nacimiento,
			scl.lugar_nacimiento,
			scl.ci,
			scl.extension,
			scl.complemento,
			scl.tipo_documento,
			scl.estado_civil,
			scl.lugar_residencia,
			scl.localidad,
			scl.direccion,
			scl.pais,
			scl.id_ocupacion,
			scl.desc_ocupacion,
			scl.telefono_domicilio,
			scl.telefono_oficina,
			scl.telefono_celular,
			scl.email,
			scl.peso,
			scl.estatura,
			scl.genero,
			scl.saldo_deudor as cl_saldo,
			sdd.monto_banca_comunal as cl_monto_bc
		from
			s_de_cot_cliente as scl
				inner join 
			s_de_cot_detalle as sdd on (sdd.id_cliente = scl.id_cliente)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = scl.id_ef)
		where
			scl.id_cliente = "' . $idcl . '"
				and sdd.id_cotizacion = "' . $idc . '"
				and sef.id_ef = "' . $idef . '"
				and sef.activado = true
		;';
		// echo $sql;
		if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($rs->num_rows === 1) {
				$row = $rs->fetch_array(MYSQLI_ASSOC);
				$rs->free();
				$data = $row;
			}
		}

		return $data;

	}

	public function getListClientData($idc, $idef, $max_item)
	{
		$data = array();

		$sql = 'select 
			scl.id_cliente,
			sdd.id_detalle,
			scl.nombre as cl_nombre,
			scl.paterno as cl_paterno,
			scl.materno as cl_materno,
			concat(scl.nombre,
				" ",
				scl.paterno,
				" ",
				scl.materno) as cl_name,
			concat(scl.ci, scl.complemento, " ", sde.codigo) as cl_dni,
			date_format(scl.fecha_nacimiento, "%d/%m/%Y") as cl_fn,
			(case scl.genero
				when "M" then "Hombre"
				when "F" then "Mujer"
			end) as cl_genero,
			sdd.porcentaje_credito as cl_pc,
			stc.valor_boliviano,
			(if(sdc.moneda = "BS",
			    sdc.monto,
			    (sdc.monto * stc.valor_boliviano))) as monto
		from
			s_de_cot_cabecera as sdc
				inner join
			s_de_cot_detalle as sdd ON (sdd.id_cotizacion = sdc.id_cotizacion)
				inner join
			s_de_cot_cliente as scl ON (scl.id_cliente = sdd.id_cliente)
				inner join
			s_departamento as sde ON (sde.id_depto = scl.extension)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = scl.id_ef)
				inner join
			s_tipo_cambio as stc ON (stc.id_ef = sdc.id_ef)
		where
			sdc.id_cotizacion = "' . $idc . '"
				and sef.id_ef = "' . $idef . '"
				and sef.activado = true
		order by sdd.id_detalle asc
		limit 0, ' . $max_item . '
		;';

		if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT)) !== false) {
			while ($row = $rs->fetch_array(MYSQLI_ASSOC)) {
				$data[] = $row;
			}
		}

		return $data;
	}


}

?>