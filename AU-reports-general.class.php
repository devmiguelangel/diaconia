<?php
require_once('sibas-db.class.php');
class ReportsGeneralAU{
	private $cx, $sql, $rs, $row, $sqlvh, $rsvh, $rowvh, $pr, $flag, $token, $nEF, $dataToken, $xls, $xlsTitle;
	protected $data = array();
	public $err;
	
	public function ReportsGeneralAU($data, $pr, $flag, $xls){
		$this->cx = new SibasDB();
		$this->pr = $this->cx->real_escape_string(trim(base64_decode($pr)));
		$this->flag = $this->cx->real_escape_string(trim($flag));
		$this->xls = $xls;
		
		$this->set_variable($data);
		$this->get_query_report();
		
	}
	
	private function set_variable($data){
		$this->data['ms'] = $this->cx->real_escape_string(trim($data['ms']));
		$this->data['page'] = $this->cx->real_escape_string(trim($data['page']));
		//$this->data[''] = $this->cx->real_escape_string(trim($data['']));
		
		$this->data['idef'] = $this->cx->real_escape_string(trim(base64_decode($data['idef'])));
		$this->data['nc'] = $this->cx->real_escape_string(trim($data['r-nc']));
		if(empty($this->data['nc']) === TRUE) $this->data['nc'] = '%%';
		$this->data['user'] = $this->cx->real_escape_string(trim($data['r-user']));
		$this->data['client'] = $this->cx->real_escape_string(trim($data['r-client']));
		$this->data['dni'] = $this->cx->real_escape_string(trim($data['r-dni']));
		$this->data['comp'] = $this->cx->real_escape_string(trim($data['r-comp']));
		$this->data['ext'] = $this->cx->real_escape_string(trim($data['r-ext']));
		$this->data['date-begin'] = $this->cx->real_escape_string(trim($data['r-date-b']));
		$this->data['date-end'] = $this->cx->real_escape_string(trim($data['r-date-e']));
		$this->data['policy'] = $this->cx->real_escape_string(trim(base64_decode($data['r-policy'])));
		$this->data['approved'] = $this->cx->real_escape_string(trim($data['r-approved']));
		$this->data['r-pendant'] = $this->cx->real_escape_string(trim($data['r-pendant']));
		$this->data['r-state'] = $this->cx->real_escape_string(trim($data['r-state']));
		$this->data['r-free-cover'] = $this->cx->real_escape_string(trim($data['r-free-cover']));
		$this->data['r-extra-premium'] = $this->cx->real_escape_string(trim($data['r-extra-premium']));
		$this->data['r-issued'] = $this->cx->real_escape_string(trim($data['r-issued']));
		$this->data['r-rejected'] = $this->cx->real_escape_string(trim($data['r-rejected']));
		$this->data['r-canceled'] = $this->cx->real_escape_string(trim($data['r-canceled']));
		
		$this->data['idUser'] = $this->cx->real_escape_string(trim(base64_decode($data['r-idUser'])));
		
		$this->data['ef'] = '';
		$this->nEF = 0;
		if(($rsEf = $this->cx->get_financial_institution_user(base64_encode($this->data['idUser']))) !== FALSE){
			$this->nEF = $rsEf->num_rows;
			$k = 0;
			while($rowEf = $rsEf->fetch_array(MYSQLI_ASSOC)){
				$k += 1;
				$this->data['ef'] .= 'sef.id_ef like "'.$rowEf['idef'].'"';
				if($k < $this->nEF) $this->data['ef'] .= ' or ';
			}
			$rsEf->free();
		}else
			$this->data['ef'] = 'sef.id_ef like "%%"';
	}
	
	private function get_query_report(){
		switch($this->flag){
			case md5('RG'): $this->token = 'RG'; $this->xlsTitle = 'Automotores - Reporte General'; break;
			case md5('RP'): $this->token = 'RP'; $this->xlsTitle = 'Automotores - Reporte Polizas Emitidas'; break;
			case md5('RQ'): $this->token = 'RQ'; $this->xlsTitle = 'Automotores - Reporte Cotizaciones'; break;
			
			case md5('IQ'): $this->token = 'IQ'; $this->xlsTitle = 'Automotores - Cotizaciones'; break;
			case md5('PA'): $this->token = 'PA'; $this->xlsTitle = 'Automotores - Solicitudes Preaprobadas'; break;
            case md5('SP'): $this->token = 'SP'; $this->xlsTitle = 'Automotores - Solicitudes Pendientes'; break;
			case md5('AP'): $this->token = 'AP'; $this->xlsTitle = 'Automotores - Pólizas Aprobadas'; break;
			case md5('AN'): $this->token = 'AN'; $this->xlsTitle = 'Automotores - Pólizas Emitidas'; break;
			
			case md5('IM'): $this->token = 'IM'; $this->xlsTitle = 'Automotores - Preaprobadas'; break;
			case md5('CP'): $this->token = 'CP'; $this->xlsTitle = 'Automotores - Certificados Provisionales'; break;
		}
		
		if($this->token === 'RG' 
            || $this->token === 'RP' 
            || $this->token === 'PA' 
            || $this->token === 'SP' 
            || $this->token === 'AN' 
            || $this->token === 'IM' 
            || $this->token === 'AP' 
            || $this->token === 'CP'){
			$this->set_query_au_report();
		}elseif($this->token === 'RQ' || $this->token === 'IQ'){
			$this->set_query_au_report_quote();
		}else
			$this->err = TRUE;
	}
	
	private function set_query_au_report(){
		switch($this->token){
			case 'RG': $this->dataToken = 2; break;
			case 'RP': $this->dataToken = 2; break;
			case 'PA': $this->dataToken = 3; break;
            case 'SP': $this->dataToken = 7; break;
			case 'AN': $this->dataToken = 4; break;
			case 'IM': $this->dataToken = 5; break;
			case 'AP': $this->dataToken = 2; break;
			case 'CP': $this->dataToken = 6; break;
		}
		
		$this->sql = "select 
		    sae.id_emision as ide,
		    sae.id_cotizacion as idc,
		    count(sad.id_emision) as noVh,
		    sum(if((saf.id_facultativo is null
		            and sad.facultativo = true)
		            and sad.aprobado = false,
		        1,
		        0)) as pendiente,
		    sum(if(saf.aprobado = 'SI'
		            or sad.facultativo = false,
		        1,
		        0)) as aprobado_si,
		    sum(if(saf.aprobado = 'NO', 1, 0)) as aprobado_no,
		    sae.prefijo,
		    sae.no_emision,
		    sae.plazo as r_plazo,
			(case sae.tipo_plazo
				when 'Y' then 'Años'
				when 'M' then 'Meses'
				when 'W' then 'Semanas'
				when 'D' then 'Días'
			end) as r_tipo_plazo,
			sfp.forma_pago as r_forma_pago,
		    (case scl.tipo
		        when 0 then 'NATURAL'
		        when 1 then 'JURIDICO'
		    end) cl_tipo,
		    (case scl.tipo
		        when
		            0
		        then
		            concat(scl.nombre,
		                    ' ',
		                    scl.paterno,
		                    ' ',
		                    scl.materno)
		        when 1 then scl.razon_social
		    end) as cl_nombre,
		    scl.ci as cl_ci,
		    scl.complemento as cl_complemento,
		    if(scl.tipo = 0, sdep.codigo, '') as cl_extension,
		    sdep.departamento as cl_ciudad,
		    (case scl.genero
		        when 'M' then 'Hombre'
		        when 'F' then 'Mujer'
		    end) as cl_genero,
		    (case scl.tipo
		        when 0 then scl.telefono_domicilio
		        when 1 then scl.telefono_oficina
		    end) as cl_telefono,
		    scl.telefono_celular as cl_celular,
		    scl.email as cl_email,
		    su.nombre as u_nombre,
		    sdepu.departamento as u_sucursal,
		    sag.agencia as u_agencia,
		    date_format(sae.fecha_creacion, '%d/%m/%Y') as fecha_ingreso,
		    if(sae.fecha_emision != '0000-00-00',
				datediff(sae.fecha_emision, sae.fecha_creacion),
				0) as duracion_caso,
		    (case sae.anulado
				when 1 then 'SI'
				when 0 then 'NO'
			end) as a_anulado,
			if(sae.anulado = true, sua.nombre, '') as a_anulado_nombre,
			if(sae.anulado = true,
				date_format(sae.fecha_anulado, '%d/%m/%Y'),
				'') as a_anulado_fecha,
		    sef.nombre as ef_nombre,
		    sef.logo as ef_logo
		from
		    s_au_em_cabecera as sae
		        inner join
		    s_au_em_detalle as sad ON (sad.id_emision = sae.id_emision)
		        left join
		    s_au_facultativo as saf ON (saf.id_vehiculo = sad.id_vehiculo
		        and saf.id_emision = sae.id_emision)
				left join
			s_au_pendiente as sap ON (sap.id_vehiculo = sad.id_vehiculo
				and sap.id_emision = sae.id_emision)
				left join
			s_estado as sds ON (sds.id_estado = sap.id_estado)
		        inner join
		    s_cliente as scl ON (scl.id_cliente = sae.id_cliente)
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sae.id_ef)
		        inner join
		    s_departamento as sdep ON (sdep.id_depto = scl.extension)
		        inner join
		    s_usuario as su ON (su.id_usuario = sae.id_usuario)
		        inner join
		    s_departamento as sdepu ON (sdepu.id_depto = su.id_depto)
				left join
			s_agencia as sag ON (sag.id_agencia = su.id_agencia)
				inner join
			s_usuario as sua ON (sua.id_usuario = sae.and_usuario)
				inner join
    		s_forma_pago as sfp ON (sfp.id_forma_pago = sae.id_forma_pago)
		where
		    sef.id_ef = '".$this->data['idef']."'
		        and sae.no_emision like '%".$this->data['nc']."%'
		        and (".$this->data['ef'].")
		        and (su.usuario like '%".$this->data['user']."%'
		        or su.id_usuario = '".$this->data['user']."'
				or su.usuario like '%".$this->data['idUser']."%')
				and (case scl.tipo
			        when
			            0
			        then
			            concat(scl.nombre,
			                    ' ',
			                    scl.paterno,
			                    ' ',
			                    scl.materno)
			        when 1 then scl.razon_social
			    end) like '%".$this->data['client']."%'
		        and scl.ci like '%".$this->data['dni']."%'
		        and scl.complemento like '%".$this->data['comp']."%'
		        and scl.extension like '%".$this->data['ext']."%'
		        and sae.fecha_creacion between '".$this->data['date-begin']."' and '".$this->data['date-end']."' ";
		if ($this->token === 'RP') {
			// and sae.id_poliza like '%".$this->data['policy']."%'
			$this->sql .= "and sae.emitir = true
					and sae.anulado like '%".$this->data['r-canceled']."%'
					";
		}elseif($this->token === 'PA'){
			$this->sql .= "and sae.emitir = false
							and sae.facultativo = false
							and sae.anulado like '%".$this->data['r-canceled']."%'
							";
		}elseif($this->token === 'SP'){
			$this->sql .= "and sae.emitir = false
							and sae.aprobado = false
                            and sae.rechazado = false
							and sae.anulado like '%".$this->data['r-canceled']."%'
							";
		}elseif($this->token === 'AP'){
			$this->sql .= "and sae.emitir = false
					and (if(sae.aprobado = true and sae.rechazado = false,
			        'A',
			        if(sae.aprobado = false and sae.rechazado = true,
			            'R',
			            ''))) regexp '".$this->data['approved']."'
					and sae.anulado like '%".$this->data['r-canceled']."%'
					";
		}elseif($this->token === 'AN'){
			$this->sql .= "and sae.emitir = true
					and sae.anulado like '%".$this->data['r-canceled']."%'
					";
		}elseif($this->token === 'IM'){
			$idUser = base64_encode($this->data['idUser']);
			$idef = base64_encode($this->data['idef']);
			$sqlAg = '';
			if (($rsAg = $this->cx->get_agency_implant($idUser, $idef)) !== FALSE) {
				$sqlAg = ' and (';
				while ($rowAg = $rsAg->fetch_array(MYSQLI_ASSOC)) {
					$sqlAg .= 'sag.id_agencia = "'.$rowAg['ag_id'].'" or ';
				}
				$sqlAg = trim($sqlAg, 'or ').') ';
				$rsAg->free();
			}
			
			$this->sql .= $sqlAg."and sae.emitir = false
					and sae.anulado like '%".$this->data['r-canceled']."%'
					and sae.aprobado = false
					and sae.rechazado = false
					and not exists( select 
						saf2.id_emision, saf2.id_vehiculo
					from
						s_au_facultativo as saf2
					where
						saf2.id_emision = sae.id_emision
							and saf2.id_vehiculo = sad.id_vehiculo)
					";
		} elseif ($this->token === 'CP') {
			$this->sql .= "and sae.emitir = true
					and sae.aprobado = true
					and sae.rechazado = false
					and sae.anulado like '%".$this->data['r-canceled']."%'
					and sae.certificado_provisional = true
					";
		}
		
		$this->sql .= "group by sae.id_emision ";
		if ($this->token === 'AP') {
			$this->sql .= "having sum(if((saf.id_facultativo is null
				        and sad.facultativo = true)
				        and sad.aprobado = false,
				    1,
				    0)) = 0
							";
		}
		$this->sql .= "order by sae.id_emision desc
		;";
		//echo $this->sql;
		
		if(($this->rs = $this->cx->query($this->sql,MYSQLI_STORE_RESULT))){
			$this->err = FALSE;
		}else{
			$this->err = TRUE;
		}
	}
	
	private function set_query_au_report_quote(){
		switch($this->token){
			case 'RQ': $this->dataToken = 2; break;
			case 'IQ': $this->dataToken = 3; break;
		}
		
		$this->sql = "select 
		    sac.id_cotizacion as idc,
		    count(sac.id_cotizacion) as noVh,
		    sac.no_cotizacion,
		    sac.plazo as r_plazo,
		    (case sac.tipo_plazo
		        when 'Y' then 'Años'
		        when 'M' then 'Meses'
		        when 'W' then 'Semanas'
		        when 'D' then 'Días'
		    end) as r_tipo_plazo,
		    sfp.forma_pago as r_forma_pago,
		    (case scl.tipo
		        when 0 then 'NATURAL'
		        when 1 then 'JURIDICO'
		    end) cl_tipo,
		    (case scl.tipo
		        when
		            0
		        then
		            concat(scl.nombre,
		                    ' ',
		                    scl.paterno,
		                    ' ',
		                    scl.materno)
		        when 1 then scl.razon_social
		    end) as cl_nombre,
		    scl.ci as cl_ci,
		    scl.complemento as cl_complemento,
		    if(scl.tipo = 0, sdep.codigo, '') as cl_extension,
		    sdep.departamento as cl_ciudad,
		    (case scl.genero
		        when 'M' then 'Hombre'
		        when 'F' then 'Mujer'
		    end) as cl_genero,
		    (case scl.tipo
		        when 0 then scl.telefono_domicilio
		        when 1 then scl.telefono_oficina
		    end) as cl_telefono,
		    scl.telefono_celular as cl_celular,
		    scl.email as cl_email,
		    su.nombre as u_nombre,
		    sdepu.departamento as u_sucursal,
		    sag.agencia as u_agencia,
		    date_format(sac.fecha_creacion, '%d/%m/%Y') as fecha_ingreso,
		    sef.nombre as ef_nombre,
		    sef.logo as ef_logo
		from
		    s_au_cot_cabecera as sac
		        inner join
		    s_au_cot_detalle as sad ON (sad.id_cotizacion = sac.id_cotizacion)
		        inner join
		    s_au_cot_cliente as scl ON (scl.id_cliente = sac.id_cliente)
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sac.id_ef)
		        inner join
		    s_departamento as sdep ON (sdep.id_depto = scl.extension)
		        inner join
		    s_usuario as su ON (su.id_usuario = sac.id_usuario)
		        inner join
		    s_departamento as sdepu ON (sdepu.id_depto = su.id_depto)
		        left join
		    s_agencia as sag ON (sag.id_agencia = su.id_agencia)
				inner join
    		s_forma_pago as sfp ON (sfp.id_forma_pago = sac.id_forma_pago)
		where
		    sef.id_ef = '".$this->data['idef']."'
		        and sac.no_cotizacion like '".$this->data['nc']."'
		        and (".$this->data['ef'].")
		        and (su.usuario like '%".$this->data['user']."%'
		        or su.nombre like '%".$this->data['user']."%'
		        or su.usuario like '%".$this->data['idUser']."%')
		        and (case scl.tipo
			        when
			            0
			        then
			            concat(scl.nombre,
			                    ' ',
			                    scl.paterno,
			                    ' ',
			                    scl.materno)
			        when 1 then scl.razon_social
			    end) like '%".$this->data['client']."%'
		        and scl.ci like '%".$this->data['dni']."%'
		        and scl.complemento like '%".$this->data['comp']."%'
		        and scl.extension like '%".$this->data['ext']."%'
		        and sac.fecha_creacion between '".$this->data['date-begin']."' and '".$this->data['date-end']."'
		        and (exists( select 
		            sae1.id_cotizacion
		        from
		            s_au_em_cabecera as sae1
		        where
		            sae1.id_cotizacion = sac.id_cotizacion
		                and sae1.anulado = true
		                and sae1.emitir = true)
		        or not exists( select 
		            sae1.id_cotizacion
		        from
		            s_au_em_cabecera as sae1
		        where
		            sae1.id_cotizacion = sac.id_cotizacion))
		group by sac.id_cotizacion
		order by sac.id_cotizacion desc
		;";
		//echo $this->sql;
		if(($this->rs = $this->cx->query($this->sql,MYSQLI_STORE_RESULT))){
			$this->err = FALSE;
		}else{
			$this->err = TRUE;
		}
	}
	
	public function set_result(){
		if($this->xls === TRUE){
			header("Content-Type:   application/vnd.ms-excel; charset=iso-8859-1");
			header("Content-Disposition: attachment; filename=".$this->xlsTitle.".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		if($this->token === 'RG' 
            || $this->token === 'RP' 
            || $this->token === 'PA' 
            || $this->token === 'SP' 
            || $this->token === 'AN' 
            || $this->token === 'IM' 
            || $this->token === 'AP' 
            || $this->token === 'CP'){
			$this->set_result_au();
		}elseif($this->token === 'RQ' || $this->token === 'IQ'){
			$this->set_result_au_quote();
		}
	}
	
	//EMISION
	private function set_result_au(){
		//echo $this->token;
		//echo $this->data['idef'];
?>
<script type="text/javascript">
$(document).ready(function(e) {
    $(".row-au").reportCxt({
    	product: 'AU'
    });
});
</script>
<table class="result-list" id="result-de">
	<thead>
    	<tr>
    		<td>No. Certificado</td>
            <td>Entidad Financiera</td>
            <td>Cliente</td>
            <td>CI</td>
            <td>Ciudad</td>
            <td><?=htmlentities('Género', ENT_QUOTES, 'UTF-8');?></td>
            <td><?=htmlentities('Teléfono', ENT_QUOTES, 'UTF-8');?></td>
            <td>Celular</td>
            <td>Email</td>
            <td><?=htmlentities('Plazo Crédito', ENT_QUOTES, 'UTF-8');?></td>
            <td>Forma de Pago</td>
            <td><?=htmlentities('Tipo Vehículo', ENT_QUOTES, 'UTF-8');?></td>
            <td>Marca</td>
            <td>Modelo</td>
            <td><?=htmlentities('Año', ENT_QUOTES, 'UTF-8');?></td>
            <td>Placa</td>
            <td>Uso</td>
            <td><?=htmlentities('Tracción', ENT_QUOTES, 'UTF-8');?></td>
            <td>Cero Km.</td>
            <td>Valor Asegurado</td>
            <td>Creado Por</td>
            <td>Sucursal Registro</td>
            <td>Agencia</td>
            <td>Fecha de Ingreso</td>
            <td>Anulado</td>
            <td>Anulado Por</td>
            <td>Fecha Anulacion</td>
            <td><?=htmlentities('Estado Compañia', ENT_QUOTES, 'UTF-8');?></td>
            <td>Estado Banco</td>
            <td><?=htmlentities('Motivo Estado Compañia', ENT_QUOTES, 'UTF-8');?></td>
            <td>Porcentaje Extraprima</td>
            <td><?=htmlentities('Fecha Respuesta final Compañia', ENT_QUOTES, 'UTF-8');?></td>
            <td><?=htmlentities('Duración total del caso', ENT_QUOTES, 'UTF-8');?></td>
            <!--<td>Días de Ultima Modificación</td>-->
        </tr>
    </thead>
    <tbody>
<?php
		$swBG = FALSE;
		$arr_state = array('txt' => '', 'action' => '', 'obs' => '', 'link' => '', 'bg' => '');
		$bgCheck = '';
		while($this->row = $this->rs->fetch_array(MYSQLI_ASSOC)){
			$nVh = (int)$this->row['noVh'];
			$_PEN = (int)$this->row['pendiente'];
			$_APS = (int)$this->row['aprobado_si'];
			$_APN = (int)$this->row['aprobado_no'];
			
			if($swBG === FALSE){
				$bg = 'background: #EEF9F8;';
			}elseif($swBG === TRUE){
				$bg = 'background: #D1EDEA;';
			}
						
			$rowSpan = FALSE;
			if($nVh > 0)
				$rowSpan = TRUE;
			
			$this->sqlvh = "select 
				sae.id_emision as ide,
			    sad.id_vehiculo as idVh,
			    stv.vehiculo as v_tipo_vehiculo,
			    sma.marca as v_marca,
			    smo.modelo as v_modelo,
			    sad.anio as v_anio,
			    sad.placa as v_placa,
			    (case sad.uso
			        when 'PR' then 'Privado'
			        when 'PB' then 'Publico'
			    end) as v_uso,
			    (case sad.traccion
			        when '4X2' then '4x2'
			        when '4X4' then '4x4'
			        when 'VHP' then 'Vehiculo Pesado'
			    end) as v_traccion,
			    sad.km as v_km,
			    sad.valor_asegurado as v_valor_asegurado,
			    datediff(curdate(), sae.fecha_creacion) as dias_proceso,
			    @fum:=datediff(curdate(), sap.fecha_creacion) as fum,
			    if(@fum is not null, @fum, 0) as dias_ultima_modificacion,
			    if(saf.aprobado is null,
			        if(sap.id_pendiente is not null,
			            case sap.respuesta
			                when 1 then 'S'
			                when 0 then 'O'
			            end,
			            if(sae.emitir = true, 'A', 'P')),
			        case saf.aprobado
			            when 'SI' then 'A'
			            when 'NO' then 'R'
			        end) as estado,
			    case
			        when sds.codigo = 'ED' then 'E'
			        when sds.codigo != 'ED' then 'NE'
			        else null
			    end as observacion,
			    sds.id_estado,
			    sds.estado as estado_pendiente,
				sds.codigo as estado_codigo,
			    if(saf.porcentaje_recargo is not null,
					saf.porcentaje_recargo,
					0) as extra_prima,
				if(saf.fecha_creacion is not null,
					date_format(saf.fecha_creacion, '%d/%m/%Y'),
					'') as fecha_resp_final_cia,
				if(sae.anulado = 1,
					1,
					if(sae.emitir = 1, 2, 3)) as estado_banco,
				sad.facultativo as estado_facultativo,
			    sad.leido
			from
			    s_au_em_detalle as sad
			        inner join
			    s_au_em_cabecera as sae ON (sae.id_emision = sad.id_emision)
			        inner join
			    s_au_tipo_vehiculo as stv ON (stv.id_tipo_vh = sad.id_tipo_vh)
			        inner join
			    s_au_marca as sma ON (sma.id_marca = sad.id_marca)
			        inner join
			    s_au_modelo as smo ON (smo.id_modelo = sad.id_modelo)
					left join
			    s_au_facultativo as saf ON (saf.id_vehiculo = sad.id_vehiculo
			        and saf.id_emision = sae.id_emision)
			        left join
			    s_au_pendiente as sap ON (sap.id_vehiculo = sad.id_vehiculo
			        and sap.id_emision = sae.id_emision)
			        left join
			    s_estado as sds ON (sds.id_estado = sap.id_estado)
			where
			    sad.id_emision = '".$this->row['ide']."' ";
			if($this->token === 'RG'){
				$this->sqlvh .= "and if(saf.aprobado is null,
			        if(sap.id_pendiente is not null,
			            case sap.respuesta
			                when 1 then 'S'
			                when 0 then 'O'
			            end,
			            if(sae.emitir = false
			                    and sae.facultativo = true,
			                'P',
			                'R')),
			        'R') regexp '".$this->data['r-pendant']."'
			        and if(sds.id_estado is not null
						and sae.emitir = false
						and sad.facultativo = true,
					sds.id_estado,
					'0') regexp '".$this->data['r-state']."'
					and if(saf.aprobado is null,
					if(sae.emitir = true
							and sae.anulado = false,
						'FC',
						'R'),
					case saf.aprobado
						when 'SI' then 'NF'
						when 'NO' then 'R'
					end) regexp '".$this->data['r-free-cover']."'
					and if(saf.aprobado is not null,
					if(saf.aprobado = 'SI',
						if(saf.tasa_recargo = 'SI', 'EP', 'NP'),
						'R'),
					if(sae.emitir = true
						and sad.facultativo = false,
					'NP',
					'R')) regexp '".$this->data['r-extra-premium']."'
					and if(sae.emitir = true,
					'EM',
					if(saf.aprobado is not null,
						if(saf.aprobado = 'SI', 'NE', 'R'),
						'NE')) regexp '".$this->data['r-issued']."'
					and if(saf.aprobado is not null,
					if(saf.aprobado = 'NO', 'RE', 'R'),
					'R') regexp '".$this->data['r-rejected']."'
					and if(sae.anulado = true, 'AN', 'R') regexp '".$this->data['r-canceled']."'
					 ";
			} elseif ($this->token === 'AP') {
				$this->sqlvh .= 'and (sad.aprobado = true) ';
			}
			
			$this->sqlvh .= "order by sad.id_vehiculo asc ;";
			//echo $this->sqlvh;
			if(($this->rsvh = $this->cx->query($this->sqlvh,MYSQLI_STORE_RESULT))){
				if($this->rsvh->num_rows <= $nVh){
					$nVh = $this->rsvh->num_rows;
					
					if ($this->token === 'AP') {
						$nVh = $_APS;
					}
					
					while($this->rowvh = $this->rsvh->fetch_array(MYSQLI_ASSOC)){
						if($rowSpan === TRUE){
							$rowSpan = 'rowspan="'.$nVh.'"';
						}elseif($rowSpan === FALSE){
							$rowSpan = '';
						}elseif($rowSpan === 'rowspan="'.$nVh.'"'){
							$rowSpan = 'style="display:none;"';
						}
						if($this->xls === TRUE) {
							$rowSpan = '';
						}
						
						$arr_state['txt'] = '';		$arr_state['txt_bank'] = '';	$arr_state['action'] = '';
						$arr_state['obs'] = '';		$arr_state['link'] = '';	$arr_state['bg'] = '';
						
						$this->cx->get_state($arr_state, $this->rowvh, 2, 'AU', FALSE);
?>
		<tr style=" <?=$bg;?> " class="row-au" rel="0" data-nc="<?=base64_encode($this->row['ide']);?>" data-token="<?=$this->dataToken;?>" data-vh="<?=base64_encode($this->rowvh['idVh']);?>" data-issue="<?=base64_encode(0);?>">
        	<td <?=$rowSpan;?>>AU-<?=$this->row['no_emision'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['ef_nombre'];?></td>
            <td <?=$rowSpan;?>><?=htmlentities($this->row['cl_nombre'], ENT_QUOTES, 'UTF-8');?></td>
            <td <?=$rowSpan;?>><?=$this->row['cl_ci'].$this->row['cl_complemento'].' '.$this->row['cl_extension'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['cl_ciudad'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['cl_genero'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['cl_telefono'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['cl_celular'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['cl_email'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['r_plazo'].' '.htmlentities($this->row['r_tipo_plazo'], ENT_QUOTES, 'UTF-8');?></td>
            <td <?=$rowSpan;?>><?=$this->row['r_forma_pago'];?></td>
            <td><?=$this->rowvh['v_tipo_vehiculo'];?></td>
            <td><?=$this->rowvh['v_marca'];?></td>
            <td><?=$this->rowvh['v_modelo'];?></td>
            <td><?=(int)$this->rowvh['v_anio'];?></td>
            <td><?=$this->rowvh['v_placa'];?></td>
            <td><?=$this->rowvh['v_uso'];?></td>
            <td><?=$this->rowvh['v_traccion'];?></td>
            <td><?=$this->rowvh['v_km'];?></td>
            <td><?=number_format($this->rowvh['v_valor_asegurado'],2,'.',',');?> USD</td>
            <td><?=htmlentities($this->row['u_nombre'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$this->row['u_sucursal'];?></td>
            <td><?=htmlentities($this->row['u_agencia'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$this->row['fecha_ingreso'];?></td>
            <td><?=$this->row['a_anulado'];?></td>
            <td><?=htmlentities($this->row['a_anulado_nombre'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$this->row['a_anulado_fecha'];?></td>
            <td><?=htmlentities($arr_state['txt'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$arr_state['txt_bank'];?></td>
            <td><?=$arr_state['obs'];?></td>
            <td><?=$this->rowvh['extra_prima'];?></td>
            <td><?=$this->rowvh['fecha_resp_final_cia'];?></td>
            <td><?=htmlentities($this->row['duracion_caso'].' días', ENT_QUOTES, 'UTF-8');?></td>
            <!--<td>Días de Ultima Modificación</td>-->
        </tr>
<?php
					}
				}
			}
			if($swBG === FALSE)
				$swBG = TRUE;
			elseif($swBG === TRUE)
				$swBG = FALSE;
		}
		$this->rs->free();
?>
    </tbody>
    <tfoot>
    	<tr>
        	<td colspan="29" style="text-align:left;">
<?php
			if($this->xls === FALSE){
?>
				<a href="rp-records.php?data-pr=<?=base64_encode($this->pr);?>&flag=<?=$this->flag;?>&ms=<?=$this->data['ms'];?>&page=<?=$this->data['page'];?>&xls=<?=md5('TRUE');?>&idef=<?=base64_encode($this->data['idef']);?>&frp-policy=<?=$this->data['policy'];?>&frp-nc=<?=$this->data['nc'];?>&frp-user=<?=$this->data['user'];?>&frp-client=<?=$this->data['client'];?>&frp-dni=<?=$this->data['dni'];?>&frp-comp=<?=$this->data['comp'];?>&frp-ext=<?=$this->data['ext'];?>&frp-date-b=<?=$this->data['date-begin'];?>&frp-date-e=<?=$this->data['date-end'];?>&frp-id-user=<?=base64_encode($this->data['idUser']);?>&frp-approved-p=<?=$this->data['approved'];?>&frp-pendant=<?=$this->data['r-pendant'];?>&frp-state=<?=$this->data['r-state'];?>&frp-free-cover=<?=$this->data['r-free-cover'];?>&frp-extra-premium=<?=$this->data['r-extra-premium'];?>&frp-issued=<?=$this->data['r-issued'];?>&frp-rejected=<?=$this->data['r-rejected'];?>&frp-canceled=<?=$this->data['r-canceled'];?>" class="send-xls" target="_blank">Exportar a Formato Excel</a>
<?php
			}
?>
			</td>
        </tr>
    </tfoot>
</table>
<?php
	}
	
	//COTIZACION
	private function set_result_au_quote(){
?>
<script type="text/javascript">
$(document).ready(function(e) {
    $(".row").reportCxt({
		context: '',
		product: 'AU'
	});
});
</script>
<table class="result-list" id="result-de">
	<thead>
    	<tr>
        	<td><?=htmlentities('No. Cotización', ENT_QUOTES, 'UTF-8');?></td>
            <td>Entidad Financiera</td>
            <td>Cliente</td>
            <td>CI</td>
            <td><?=htmlentities('Género', ENT_QUOTES, 'UTF-8');?></td>
            <td>Ciudad</td>
            <td><?=htmlentities('Teléfono', ENT_QUOTES, 'UTF-8');?></td>
            <td>Celular</td>
            <td>Email</td>
            <td><?=htmlentities('Plazo Crédito', ENT_QUOTES, 'UTF-8');?></td>
            <td>Forma de Pago</td>
            <td><?=htmlentities('Tipo Vehículo', ENT_QUOTES, 'UTF-8');?></td>
            <td>Marca</td>
            <td>Modelo</td>
            <td><?=htmlentities('Año', ENT_QUOTES, 'UTF-8');?></td>
            <td>Placa</td>
            <td>Uso</td>
            <td><?=htmlentities('Tracción', ENT_QUOTES, 'UTF-8');?></td>
            <td>Cero Km.</td>
            <td>Valor Asegurado</td>
            <td>Creado Por</td>
            <td>Fecha de Ingreso</td>
            <td>Sucursal</td>
            <td>Agencia</td>
            <!--<td>&nbsp;</td>-->
        </tr>
    </thead>
    <tbody>
<?php
		$swBG = FALSE;
		$arr_state = array('txt' => '', 'action' => '', 'obs' => '', 'link' => '', 'bg' => '');
		$bgCheck = '';
		while($this->row = $this->rs->fetch_array(MYSQLI_ASSOC)){
			$nVh = (int)$this->row['noVh'];
			if($swBG === FALSE){
				$bg = 'background: #EEF9F8;';
			}elseif($swBG === TRUE){
				$bg = 'background: #D1EDEA;';
			}
						
			$rowSpan = FALSE;
			if($nVh > 0)
				$rowSpan = TRUE;
			
			$arr_state['txt'] = '';		$arr_state['txt_bank'] = '';	$arr_state['action'] = '';
			$arr_state['obs'] = '';		$arr_state['link'] = '';	$arr_state['bg'];
			
			//$this->cx->get_state($arr_state, $this->row, 2);
			
			$this->sqlvh = "select 
			    sac.id_cotizacion as idc,
			    sad.id_vehiculo as idVh,
			    stv.vehiculo as v_tipo_vehiculo,
			    sma.marca as v_marca,
			    smo.modelo as v_modelo,
			    sad.anio as v_anio,
			    sad.placa as v_placa,
			    (case sad.uso
			        when 'PR' then 'Privado'
			        when 'PB' then 'Publico'
			    end) as v_uso,
			    (case sad.traccion
			        when '4X2' then '4x2'
			        when '4X4' then '4x4'
			        when 'VHP' then 'Vehiculo Pesado'
			    end) as v_traccion,
			    sad.km as v_km,
			    sad.valor_asegurado as v_valor_asegurado
			from
			    s_au_cot_detalle as sad
			        inner join
			    s_au_cot_cabecera as sac ON (sac.id_cotizacion = sad.id_cotizacion)
			        inner join
			    s_au_tipo_vehiculo as stv ON (stv.id_tipo_vh = sad.id_tipo_vh)
			        inner join
			    s_au_marca as sma ON (sma.id_marca = sad.id_marca)
			        inner join
			    s_au_modelo as smo ON (smo.id_modelo = sad.id_modelo)
			where
			    sac.id_cotizacion = '".$this->row['idc']."'
			order by sad.id_vehiculo asc;";
			//echo $this->sqlvh;
			
			if(($this->rsvh = $this->cx->query($this->sqlvh, MYSQLI_STORE_RESULT))){
				if($this->rsvh->num_rows <= $nVh){
					while($this->rowvh = $this->rsvh->fetch_array(MYSQLI_ASSOC)){
						if($rowSpan === TRUE){
							$rowSpan = 'rowspan="'.$nVh.'"';
						}elseif($rowSpan === FALSE){
							$rowSpan = '';
						}elseif($rowSpan === 'rowspan="'.$nVh.'"'){
							$rowSpan = 'style="display:none;"';
						}
						
						if($this->xls === TRUE) {
							$rowSpan = '';
						}
?>
		<tr style=" <?=$bg;?> " class="row quote" rel="0" data-nc="<?=base64_encode($this->row['idc']);?>" data-token="<?=$this->dataToken;?>" data-vh="<?=base64_encode($rowVh['idVh']);?>" data-issue="<?=base64_encode(0);?>">
        	<td <?=$rowSpan;?>><?=$this->row['no_cotizacion'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['ef_nombre'];?></td>
            <td <?=$rowSpan;?>><?=htmlentities($this->row['cl_nombre'], ENT_QUOTES, 'UTF-8');?></td>
            <td <?=$rowSpan;?>><?=$this->row['cl_ci'].$this->row['cl_complemento'].' '.$this->row['cl_extension'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['cl_genero'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['cl_ciudad'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['cl_telefono'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['cl_celular'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['cl_email'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['r_plazo'].' '.htmlentities($this->row['r_tipo_plazo'], ENT_QUOTES, 'UTF-8');?></td>
            <td <?=$rowSpan;?>><?=$this->row['r_forma_pago'];?></td>
            <td><?=$this->rowvh['v_tipo_vehiculo'];?></td>
            <td><?=$this->rowvh['v_marca'];?></td>
            <td><?=$this->rowvh['v_modelo'];?></td>
            <td><?=$this->rowvh['v_anio'];?></td>
            <td><?=$this->rowvh['v_placa'];?></td>
            <td><?=$this->rowvh['v_uso'];?></td>
            <td><?=$this->rowvh['v_traccion'];?></td>
            <td><?=$this->rowvh['v_km'];?></td>
            <td><?=number_format($this->rowvh['v_valor_asegurado'],2,'.',',');?> USD.</td>
            <td><?=htmlentities($this->row['u_nombre'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$this->row['fecha_ingreso'];?></td>
            <td><?=$this->row['u_sucursal'];?></td>
            <td><?=htmlentities($this->row['u_agencia'], ENT_QUOTES, 'UTF-8');?></td>
            <!--<td><a href="detalle-cotizacion/detalle-certificado.php?idcotiza=<?=base64_encode($this->row['idc']);?>&cat=<?=base64_encode('DE');?>&type=PRINT" class="fancybox fancybox.ajax observation">Ver Slip de Cotización</a></td>-->
        </tr>
<?php
					}
				}
			}
			if($swBG === FALSE)
				$swBG = TRUE;
			elseif($swBG === TRUE)
				$swBG = FALSE;
		}
		$this->rs->free();
?>
    </tbody>
    <tfoot>
    	<tr>
        	<td colspan="29" style="text-align:left;">
<?php
			if($this->xls === FALSE){
?>
				<a href="rp-records.php?data-pr=<?=base64_encode($this->pr);?>&flag=<?=$this->flag;?>&ms=<?=$this->data['ms'];?>&page=<?=$this->data['page'];?>&xls=<?=md5('TRUE');?>&idef=<?=base64_encode($this->data['idef']);?>&frp-policy=<?=$this->data['policy'];?>&frp-nc=<?=$this->data['nc'];?>&frp-user=<?=$this->data['user'];?>&frp-client=<?=$this->data['client'];?>&frp-dni=<?=$this->data['dni'];?>&frp-comp=<?=$this->data['comp'];?>&frp-ext=<?=$this->data['ext'];?>&frp-date-b=<?=$this->data['date-begin'];?>&frp-date-e=<?=$this->data['date-end'];?>&frp-id-user=<?=base64_encode($this->data['idUser']);?>&frp-pendant=<?=$this->data['r-pendant'];?>&frp-state=<?=$this->data['r-state'];?>&frp-free-cover=<?=$this->data['r-free-cover'];?>&frp-extra-premium=<?=$this->data['r-extra-premium'];?>&frp-issued=<?=$this->data['r-issued'];?>&frp-rejected=<?=$this->data['r-rejected'];?>&frp-canceled=<?=$this->data['r-canceled'];?>" class="send-xls" target="_blank">Exportar a Formato Excel</a>
<?php
			}
?>
			</td>
        </tr>
    </tfoot>
</table>
<?php
	
	}
}
?>