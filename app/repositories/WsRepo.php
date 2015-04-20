<?php

class WsRepo
{
	protected 
		$cx,
		$data = array();
	
	public function __construct($cx)
	{
		$this->cx = $cx;
	}

	public function getData()
	{
		return $this->data;
	}

	public function dataClientDB($dni, $idef)
	{
		$client = array();

		$sql = 'select
			sc.id_cliente,
			sc.nombre as cl_nombre,
			sc.paterno as cl_paterno,
			sc.materno as cl_materno,
			sc.ap_casada as cl_ap_casada,
			sc.estado_civil as cl_estado_civil,
			sc.tipo_documento as cl_tipo_documento,
			sc.ci as cl_dni,
			sc.complemento as cl_complemento,
			sc.extension as cl_extension,
			sc.fecha_nacimiento as cl_fecha_nacimiento,
			sc.pais as cl_pais,
			sc.lugar_nacimiento as cl_lugar_nacimiento,
			sc.lugar_residencia as cl_lugar_residencia,
			sc.localidad as cl_localidad,
			sc.direccion as cl_direccion,
			sc.telefono_domicilio as cl_tel_domicilio,
			sc.telefono_celular as cl_tel_celular,
			sc.telefono_oficina as cl_tel_oficina,
			sc.email as cl_email,
			sc.id_ocupacion as cl_ocupacion,
			sc.desc_ocupacion as cl_desc_ocupacion,
			sc.genero as cl_genero,
			sc.saldo_deudor as cl_saldo,
			sdd.monto_banca_comunal as cl_monto_bc
		from
			s_de_cot_cliente as sc
				inner join
			s_de_cot_detalle as sdd on (sdd.id_cliente = sc.id_cliente)
				inner join
		    s_entidad_financiera as sef on (sef.id_ef = sc.id_ef)
		where
			sc.ci = "' . $dni . '"
				and sef.id_ef = "' . $idef . '"
				and sef.activado = true
		limit 0 , 1
		;';

		if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($rs->num_rows > 0) {
				$row = $rs->fetch_array(MYSQLI_ASSOC);
				$rs->free();

				$client['code'] 		= '';
				$client['name'] 		= $row['cl_nombre'];
				$client['patern'] 		= $row['cl_paterno'];
				$client['matern'] 		= $row['cl_materno'];
				$client['married'] 		= $row['cl_ap_casada'];
				$client['status'] 		= $row['cl_estado_civil'];
				$client['type_doc'] 	= $row['cl_tipo_documento'];
				$client['doc_id'] 		= $row['cl_dni'];
				$client['comp'] 		= $row['cl_complemento'];
				$client['ext'] 			= $row['cl_extension'];
				$client['country'] 		= $row['cl_pais'];
				$client['birth'] 		= $row['cl_fecha_nacimiento'];
				$client['place_birth'] 	= $row['cl_lugar_nacimiento'];
				$client['place_res'] 	= $row['cl_lugar_residencia'];
				$client['locality'] 	= $row['cl_localidad'];
				$client['address'] 		= $row['cl_direccion'];
				$client['phone_1'] 		= $row['cl_tel_domicilio'];
				$client['phone_2'] 		= $row['cl_tel_celular'];
				$client['phone_office'] = $row['cl_tel_oficina'];
				$client['email'] 		= $row['cl_email'];
				$client['occupation'] 	= $row['cl_ocupacion'];
				$client['occ_desc'] 	= $row['cl_desc_ocupacion'];
				$client['gender'] 		= $row['cl_genero'];
				$client['weight'] 		= '';
				$client['height'] 		= '';
				$client['amount'] 		= $row['cl_saldo'];

				$this->data[] = $client;
				
				return true;
			}
		}

		return false;
	}

	public function dataClientWS()
	{
		$clients = array();

		return false;
	}

}

?>