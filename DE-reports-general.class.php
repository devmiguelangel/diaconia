<?php
require('sibas-db.class.php');
class ReportsGeneralDE{
	private $cx, $sql, $rs, $row, $sqlcl, $rscl, $rowcl,
            $pr, $flag, $token, $nEF, $dataToken, $xls, $xlsTitle;
	protected $data = array();
	public $err;
	
	public function ReportsGeneralDE($data, $pr, $flag, $xls){
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
		$this->data['prefix'] = $this->cx->real_escape_string(trim($data['r-prefix']));
		if(empty($this->data['nc']) === TRUE) $this->data['nc'] = '%%';
		$this->data['user'] = $this->cx->real_escape_string(trim($data['r-user']));
		$this->data['subsidiary'] = $this->cx->real_escape_string(trim(base64_decode($data['r-subsidiary'])));
		if (empty($this->data['subsidiary']) === true) {
			$this->data['subsidiary'] = '%' . $this->data['subsidiary'] . '%';
		}
		$this->data['agency'] = $this->cx->real_escape_string(trim(base64_decode($data['r-agency'])));

		$this->data['client'] = $this->cx->real_escape_string(trim($data['r-client']));
		$this->data['dni'] = $this->cx->real_escape_string(trim($data['r-dni']));
		$this->data['comp'] = $this->cx->real_escape_string(trim($data['r-comp']));
		$this->data['ext'] = $this->cx->real_escape_string(trim($data['r-ext']));
		$this->data['date-begin'] = $this->cx->real_escape_string(trim($data['r-date-b']));
		$this->data['date-end'] = $this->cx->real_escape_string(trim($data['r-date-e']));
		$this->data['policy'] = $this->cx->real_escape_string(trim(base64_decode($data['r-policy'])));
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
		if (($rsEf = $this->cx->get_financial_institution_user(base64_encode($this->data['idUser']))) !== FALSE) {
			$this->nEF = $rsEf->num_rows;
			$k = 0;
			while ($rowEf = $rsEf->fetch_array(MYSQLI_ASSOC)) {
				$k += 1;
				$this->data['ef'] .= 'sef.id_ef like "'.$rowEf['idef'].'"';
				if($k < $this->nEF) $this->data['ef'] .= ' or ';
			}
			$rsEf->free();
		} else {
			$this->data['ef'] = 'sef.id_ef like "%%"';
		}
	}
	
	private function get_query_report(){
		switch($this->flag){
			case md5('RG'): $this->token = 'RG'; $this->xlsTitle = 'Desgravamen - Reporte General'; break;
			case md5('RP'): $this->token = 'RP'; $this->xlsTitle = 'Desgravamen - Reporte Polizas Emitidas'; break;
			case md5('RQ'): $this->token = 'RQ'; $this->xlsTitle = 'Desgravamen - Reporte Cotizaciones'; break;
			
			case md5('IQ'): $this->token = 'IQ'; $this->xlsTitle = 'Desgravamen - Cotizaciones'; break;
			case md5('PA'): $this->token = 'PA'; $this->xlsTitle = 'Desgravamen - Solicitudes Preaprobadas'; break;
			case md5('AN'): $this->token = 'AN'; $this->xlsTitle = 'Desgravamen - Pólizas Emitidas'; break;
			
			case md5('IM'): $this->token = 'IM'; $this->xlsTitle = 'Automotores - Preaprobadas'; break;
		}
		
		if($this->token === 'RG' 
			|| $this->token === 'RP' 
			|| $this->token === 'PA' 
			|| $this->token === 'AN' 
			|| $this->token === 'IM' 
			|| $this->token === 'AP'){
			$this->set_query_de_report();
		}elseif($this->token === 'RQ' || $this->token === 'IQ'){
			$this->set_query_de_report_quote();
		}else{
			$this->err = TRUE;
		}
	}
	
	private function set_query_de_report(){
		switch($this->token){
			case 'RG': $this->dataToken = 2; break;
			case 'RP': $this->dataToken = 2; break;
			case 'PA': $this->dataToken = 3; break;
			case 'AN': $this->dataToken = 4; break;
			case 'IM': $this->dataToken = 5; break;
			case 'AP': $this->dataToken = 2; break;
		}
		$this->sql = "select 
			sde.id_emision as ide,
			count(sc.id_cliente) as no_cl,
			sum(if(sdd.facultativo = true, 1, 0)) as noFc,
			sum(if(sdd.aprobado = true and sdd.detalle_f = '', 1, 0)) as noAp,
			sum(if(sdd.aprobado = true and sdd.detalle_f != '', 1, 0)) as noFa,
			sum(if(sdd.aprobado = false and sdd.detalle_f != '', 1, 0)) as noPr,
			if(lower(spc.nombre) = 'banca comunal', 1, 0) as bc,
			sef.id_ef as idef,
			sef.nombre as ef_nombre,
			sde.no_emision as r_no_emision,
			sde.prefijo as r_prefijo,
			sde.id_compania,
			sde.monto_solicitado as r_monto_solicitado,
			(case sde.moneda
				when 'BS' then 'Bolivianos'
				when 'USD' then 'Dolares'
			end) as r_moneda,
			sde.plazo as r_plazo,
			(case sde.tipo_plazo
				when 'Y' then 'Años'
				when 'M' then 'Meses'
				when 'W' then 'Semanas'
				when 'D' then 'Días'
			end) as r_tipo_plazo,
			su.nombre as r_creado_por,
			date_format(sde.fecha_creacion, '%d/%m/%Y') as r_fecha_creacion,
			sde.fecha_creacion,
			sdep.departamento as r_sucursal,
			sag.agencia as r_agencia,
			(case sde.anulado
				when 1 then 'SI'
				when 0 then 'NO'
			end) as r_anulado,
			if(sde.anulado = true, sua.nombre, '') as r_anulado_nombre,
			if(sde.anulado = true,
				date_format(sde.fecha_anulado, '%d/%m/%Y'),
				'') as r_anulado_fecha,
			(select 
					count(sde1.id_emision)
				from
					s_de_em_cabecera as sde1
				where
					sde1.id_cotizacion = sde.id_cotizacion
						and sde1.anulado = true) as r_num_anulado,
			if(sdf.aprobado is null,
				if(sdp.id_pendiente is not null,
					case sdp.respuesta
						when 1 then 'S'
						when 0 then 'O'
					end,
					if((sde.emitir = 0 and sde.aprobado = 1)
                       or sde.facultativo = 1,
                       'P',
                       if(sde.emitir = 0 and sde.aprobado = 0
                          and sde.facultativo = 0,
                          'P',
                          'F')
                       )),
				case sdf.aprobado
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
			if(sde.anulado = 1,
				1,
				if(sde.emitir = true, 2, 3)) as estado_banco,
			sde.facultativo as estado_facultativo,
			if(sdf.porcentaje_recargo is not null,
				sdf.porcentaje_recargo,
				0) as extra_prima,
			if(sdf.fecha_creacion is not null,
				date_format(sdf.fecha_creacion, '%d/%m/%Y'),
				'') as fecha_resp_final_cia,
			if(sde.emitir = true,
				datediff(sde.fecha_emision, sde.fecha_creacion),
				datediff(curdate(), sde.fecha_creacion)) as duracion_caso,
			@fum:=(if(sdf.aprobado is null,
				datediff(curdate(), sdp.fecha_creacion),
				datediff(curdate(), sdf.fecha_creacion))) as fum,
			-- @fum:=datediff(curdate(), sdp.fecha_creacion) as fum,
			if(@fum is not null, @fum, 0) as dias_ultima_modificacion,
			if(sde.emitir = true,
				if(sdf.aprobado is not null,
					datediff(sdf.fecha_creacion, sde.fecha_creacion),
					datediff(sde.fecha_emision, sde.fecha_creacion)),
				if(sdf.aprobado is null,
					datediff(curdate(), sde.fecha_creacion),
					datediff(sdf.fecha_creacion, sde.fecha_creacion))) as dias_proceso,
			date_format(sdp.fecha_creacion, '%d/%m/%Y') as fecha_ultima_respuesta,
			if(sde.emitir = false, 
				if(sde.aprobado = true, 1, 0),
				1) as solicitud
		from
			s_de_em_cabecera as sde
				inner join
			s_de_em_detalle as sdd ON (sdd.id_emision = sde.id_emision)
				inner join
			s_cliente as sc ON (sc.id_cliente = sdd.id_cliente)
				left join
			s_de_facultativo as sdf ON (sdf.id_emision = sde.id_emision)
				left join
			s_de_pendiente as sdp ON (sdp.id_emision = sde.id_emision)
				left join
			s_estado as sds ON (sds.id_estado = sdp.id_estado)
				inner join
			s_usuario as su ON (su.id_usuario = sde.id_usuario)
				inner join
			s_departamento as sdep ON (sdep.id_depto = su.id_depto)
				left join
			s_agencia as sag ON (sag.id_agencia = su.id_agencia)
				inner join
			s_usuario as sua ON (sua.id_usuario = sde.and_usuario)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = sde.id_ef)
				inner join
    		s_producto_cia as spc on (spc.id_prcia = sde.id_prcia)
		where
			sef.id_ef = '".$this->data['idef']."'
				and sde.no_emision like '".$this->data['nc']."'
				and (".$this->data['ef'].")

				and (su.usuario like '%".$this->data['user']."%'
				or su.nombre like '%".$this->data['user']."%'
				or su.usuario like '%".$this->data['idUser']."%')
				and concat(sc.nombre,
					' ',
					sc.paterno,
					' ',
					sc.materno) like '%".$this->data['client']."%'
				and sc.ci like '%".$this->data['dni']."%'
				and sc.complemento like '%".$this->data['comp']."%'
				and sc.extension like '%".$this->data['ext']."%'
				and sde.fecha_creacion between '".$this->data['date-begin']."' and '".$this->data['date-end']."' ";
		if($this->token === 'RG'){
			//and sde.id_poliza like '%".$this->data['policy']."%'
			$this->sql .= "and if(sdf.aprobado is null,
				if(sdp.id_pendiente is not null,
					case sdp.respuesta
						when 1 then 'S'
						when 0 then 'O'
					end,
					if(sde.emitir = false
							and sde.facultativo = true
							and sdf.aprobado is null,
						'P',
						'R')),
				'R') regexp '".$this->data['r-pendant']."'
				and if(sds.id_estado is not null
					and sde.emitir = false
					and sde.facultativo = true
					and sdf.aprobado is null,
				sds.id_estado,
				'0') regexp '".$this->data['r-state']."'
				and if(sdf.aprobado is null,
				if(sde.emitir = true
						and sde.anulado = false,
					'FC',
					'R'),
				case sdf.aprobado
					when 'SI' then 'NF'
					when 'NO' then 'R'
				end) regexp '".$this->data['r-free-cover']."'
				and if(sdf.aprobado is not null,
				if(sdf.aprobado = 'SI',
					if(sdf.tasa_recargo = 'SI', 'EP', 'NP'),
					'R'),
				if(sde.emitir = true
					and sde.facultativo = false,
				'NP',
				'R')) regexp '".$this->data['r-extra-premium']."' 
				and if(sde.emitir = true,
				'EM',
				if(sdf.aprobado is not null,
					if(sdf.aprobado = 'SI', 'NE', 'R'),
					'NE')) regexp '".$this->data['r-issued']."'
				and if(sdf.aprobado is not null,
				if(sdf.aprobado = 'NO', 'RE', 'R'),
				'R') regexp '".$this->data['r-rejected']."'
				and if(sde.anulado = true, 'AN', 'R') regexp '".$this->data['r-canceled']."' 
				and sde.prefijo like '%" . $this->data['prefix'] . "%'
				and sdep.id_depto like '" . $this->data['subsidiary'] . "'
				and sag.id_agencia like '%" . $this->data['agency'] . "%' ";
		} elseif ($this->token === 'RP') {
			$this->sql .= "and sde.emitir = true
							and sde.anulado like '%".$this->data['r-canceled']."%'
							and sde.prefijo like '%" . $this->data['prefix'] . "%'
							and sdep.id_depto like '" . $this->data['subsidiary'] . "'
							and sag.id_agencia like '%" . $this->data['agency'] . "%'
							";
		} elseif ($this->token === 'PA') {
			$this->sql .= "and sde.emitir = false
							and sde.facultativo = false
							and sde.aprobado = false
							and sde.anulado like '%".$this->data['r-canceled']."%'
							and sdep.id_depto like '" . $this->data['subsidiary'] . "'
							and sag.id_agencia like '%" . $this->data['agency'] . "%'
							";
            if (true === $this->cx->getAgency()) {
                session_start();
                if (($rsAg = $this->cx->getAgencyUser(
                        $_SESSION['idEF'], $_SESSION['idUser'])) !== false) {
                    $rowAg = $rsAg->fetch_array(MYSQLI_ASSOC);
                    $rsAg->free();

                    $this->sql .= "and sag.id_agencia = '" . $rowAg['id_agencia'] . "'
                            ";
                } else {
                    $this->sql .= "and sag.id_agencia is null
                            ";
                }
            }
		} elseif ($this->token === 'AN') {
			$this->sql .= "and sde.emitir = true
							and sde.anulado like '%".$this->data['r-canceled']."%'
							and sdep.id_depto like '" . $this->data['subsidiary'] . "'
							and sag.id_agencia like '%" . $this->data['agency'] . "%'
							";
		} elseif ($this->token === 'AP') {
			$this->sql .= "and sde.emitir = false
					and (if(sde.aprobado = true,
			        'A',
			        if(sde.aprobado = false,
			            'R',
			            ''))) regexp '".$this->data['approved']."'
					and sde.anulado like '%".$this->data['r-canceled']."%'
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
			
			$this->sql .= $sqlAg." and sde.emitir = false
					and sde.anulado like '%".$this->data['r-canceled']."%'
					and sde.aprobado = false
					and sde.rechazado = false
					and not exists( select 
						saf2.id_emision
					from
						s_de_facultativo as saf2
					where
						saf2.id_emision = sde.id_emision )
					";
		}
		$this->sql .= "group by sde.id_emision
		order by sde.id_emision desc
		;";
		// echo $this->sql;
		
		if(($this->rs = $this->cx->query($this->sql,MYSQLI_STORE_RESULT))){
			$this->err = FALSE;
		}else{
			$this->err = TRUE;
		}
	}
	
	private function set_query_de_report_quote(){
		switch($this->token){
			case 'RQ': $this->dataToken = 2; break;
			case 'IQ': $this->dataToken = 3; break;
		}
		$this->sql = "select 
			sdc.id_cotizacion as idc,
			sef.id_ef as idef,
			sef.nombre as ef_nombre,
			count(sc.id_cliente) as no_cl,
			sdc.no_cotizacion as r_no_cotizacion,
			sdc.monto as r_monto_solicitado,
			(case sdc.moneda
				when 'BS' then 'Bolivianos'
				when 'USD' then 'Dolares'
			end) as r_moneda,
			sdc.plazo as r_plazo,
			(case sdc.tipo_plazo
				when 'Y' then 'Años'
				when 'M' then 'Meses'
				when 'W' then 'Semanas'
				when 'D' then 'Días'
			end) as r_tipo_plazo,
			su.nombre as r_creado_por,
			date_format(sdc.fecha_creacion, '%d/%m/%Y') as r_fecha_creacion,
			sdep.departamento as r_sucursal,
			sag.agencia as r_agencia
		from
			s_de_cot_cabecera as sdc
				inner join
			s_de_cot_detalle as sdd ON (sdd.id_cotizacion = sdc.id_cotizacion)
				inner join
			s_de_cot_cliente as sc ON (sc.id_cliente = sdd.id_cliente)
				inner join
			s_usuario as su ON (su.id_usuario = sdc.id_usuario)
				inner join
			s_departamento as sdep ON (sdep.id_depto = su.id_depto)
				left join
			s_agencia as sag ON (sag.id_agencia = su.id_agencia)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = sdc.id_ef)
		where
			sef.id_ef = '".$this->data['idef']."'
				and sdc.no_cotizacion like '".$this->data['nc']."'
				and (".$this->data['ef'].")
				and su.id_usuario like '%".$this->data['idUser']."%'
				and sdep.id_depto like '" . $this->data['subsidiary'] . "'
				and sag.id_agencia like '%" . $this->data['agency'] . "%'
				and concat(sc.nombre,
					' ',
					sc.paterno,
					' ',
					sc.materno) like '%".$this->data['client']."%'
				and sc.ci like '%".$this->data['dni']."%'
				and sc.complemento like '%".$this->data['comp']."%'
				and sc.extension like '%".$this->data['ext']."%'
				and sdc.fecha_creacion between '".$this->data['date-begin']."' and '".$this->data['date-end']."'
				and (exists( select 
					sde1.id_cotizacion
				from
					s_de_em_cabecera as sde1
				where
					sde1.id_cotizacion = sdc.id_cotizacion
						and sde1.anulado = true
						and sde1.emitir = true)
				or not exists( select 
					sde1.id_cotizacion
				from
					s_de_em_cabecera as sde1
				where
					sde1.id_cotizacion = sdc.id_cotizacion))
		group by sdc.id_cotizacion
		order by sdc.id_cotizacion desc
		;";
		//echo $this->sql;
		if(($this->rs = $this->cx->query($this->sql,MYSQLI_STORE_RESULT))){
			$this->err = FALSE;
		}else{
			$this->err = TRUE;
		}
	}
	
	public function set_result(){
		if($this->xls === true){
			header("Content-Type:   application/vnd.ms-excel; charset=iso-8859-1");
			header("Content-Disposition: attachment; filename=" . $this->xlsTitle . ".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
		}

		if ($this->token === 'RG' 
			|| $this->token === 'RP' 
			|| $this->token === 'PA' 
			|| $this->token === 'AN' 
			|| $this->token === 'IM' 
			|| $this->token === 'AP'){
			$this->set_result_de();
		} elseif ($this->token === 'RQ' || $this->token === 'IQ'){
			$this->set_result_de_quote();
		}
	}
	
	//EMISION
	private function set_result_de(){
		//echo $this->data['idef'];
?>
<script type="text/javascript">
$(document).ready(function(e) {
    $(".row").reportCxt();
});
</script>
<table class="result-list" id="result-de">
	<thead>
    	<tr>
        	<td>No. Certificado</td>
            <td>Entidad Financiera</td>
            <td>Cliente</td>
            <td>CI</td>
            <td><?=htmlentities('Género', ENT_QUOTES, 'UTF-8');?></td>
            <td>Ciudad</td>
            <td><?=htmlentities('Teléfono', ENT_QUOTES, 'UTF-8');?></td>
            <td>Celular</td>
            <td>Email</td>
            <td>Monto Solicitado</td>
            <td>Moneda</td>
            <td>Saldo Deudor</td>
            <td>Monto Total Acumulado</td>
            <td><?=htmlentities('Plazo Crédito', ENT_QUOTES, 'UTF-8');?></td>
            <td>Estatura (cm)</td>
            <td>Peso (kg)</td>
            <td><?=htmlentities('Participación (%)', ENT_QUOTES, 'UTF-8');?></td>
            <td>Deudor / Codeudor</td>
            <td>Edad</td>
            <td>Creado Por</td>
            <td>Fecha de Ingreso</td>
            <td>Sucursal</td>
            <td>Agencia</td>
            <td>Certificados Anulados</td>
            <td>Anulado por</td>
            <td><?=htmlentities('Fecha de Anulación', ENT_QUOTES, 'UTF-8');?></td>
            <td><?=htmlentities('Estado Compañia', ENT_QUOTES, 'UTF-8');?></td>
            <td>Estado Banco</td>
            <td><?=htmlentities('Motivo Estado Compañia', ENT_QUOTES, 'UTF-8');?></td>
            <td>Porcentaje Extraprima</td>
            <td><?=htmlentities('Fecha Respuesta final Compañia', ENT_QUOTES, 'UTF-8');?></td>
<?php
		if ($this->token === 'RG') {
			echo '<td>' . htmlentities('Días en Proceso', ENT_QUOTES, 'UTF-8') . '</td>
            	<td>' . htmlentities('Días de Ultima Modificación', ENT_QUOTES, 'UTF-8') . '</td>
			';
		}
?>
            <td><?=htmlentities('Duración total del caso', ENT_QUOTES, 'UTF-8');?></td>
<?php
		/*if ($this->token === 'RG') {
			echo '<td>' . htmlentities('Solicitud Enviada', ENT_QUOTES, 'UTF-8') . '</td>';
		}*/
?>
            <!--<td>Días de Ultima Modificación</td>-->
        </tr>
    </thead>
    <tbody>
<?php
		$swBG = FALSE;
		$arr_state = array('txt' => '', 'action' => '', 'obs' => '', 'link' => '', 'bg' => '');
		$bgCheck = '';
		while($this->row = $this->rs->fetch_array(MYSQLI_ASSOC)){
			$nCl = (int)$this->row['no_cl'];
			$nFc = (int)$this->row['noFc'];
			$nAp = (int)$this->row['noAp'];
			$nFa = (int)$this->row['noFa'];
			$nPr = (int)$this->row['noPr'];

			$bc = (boolean)$this->row['bc'];

			if($swBG === FALSE){
				$bg = 'background: #EEF9F8;';
			}elseif($swBG === TRUE){
				$bg = 'background: #D1EDEA;';
			}
						
			$rowSpan = FALSE;
			if ($nCl >= 2) {
				$rowSpan = TRUE;
			}
			
			$arr_state['txt'] = '';		$arr_state['txt_bank'] = '';	$arr_state['action'] = '';
			$arr_state['obs'] = '';		$arr_state['link'] = '';	$arr_state['bg'] = '';
			
			$this->cx->get_state($arr_state, $this->row, 2, 'DE', FALSE);
			
			$this->sqlcl = "select 
				sdc.id_cliente as idCl,
				concat(sdc.nombre,
						' ',
						sdc.paterno,
						' ',
						sdc.materno) as cl_nombre,
				sdc.ci as cl_ci,
				sdc.complemento as cl_complemento,
				sdep.codigo as cl_extension,
				sdep.departamento as cl_ciudad,
				(case sdc.genero
					when 'M' then 'Hombre'
					when 'F' then 'Mujer'
				end) as cl_genero,
				sdc.telefono_domicilio as cl_telefono,
				sdc.telefono_celular as cl_celular,
				sdc.email as cl_email,
				(case sdd.titular
					when 'DD' then 'Deudor'
					when 'CC' then 'Codeudor'
				end) as cl_titular,
				sdc.estatura as cl_estatura,
				sdc.peso as cl_peso,
				sdd.porcentaje_credito as cl_participacion,
				(year(curdate()) - year(sdc.fecha_nacimiento)) as cl_edad,
				sdd.id_detalle,
				sdd.monto_banca_comunal as monto_bc,
				sdd.saldo as cl_saldo,
				sdd.cumulo as cl_cumulo,
				sdd.detalle_f,
				sdd.detalle_p
			from
				s_cliente as sdc
					inner join
				s_de_em_detalle as sdd ON (sdd.id_cliente = sdc.id_cliente)
					inner join
				s_departamento as sdep ON (sdep.id_depto = sdc.extension)
			where
				sdc.id_ef = '".$this->data['idef']."'
					and sdd.id_emision = '".$this->row['ide']."'
					and concat(sdc.nombre,
						' ',
						sdc.paterno,
						' ',
						sdc.materno) like '%".$this->data['client']."%'
					and sdc.ci like '%".$this->data['dni']."%'
					and sdc.complemento like '%".$this->data['comp']."%'
					and sdc.extension like '%".$this->data['ext']."%'
			order by sdc.id_cliente asc
			;";
			
			if(($this->rscl = $this->cx->query($this->sqlcl,MYSQLI_STORE_RESULT))){
				if($this->rscl->num_rows === $nCl){
					while($this->rowcl = $this->rscl->fetch_array(MYSQLI_ASSOC)){
						if($rowSpan === TRUE){
							$rowSpan = 'rowspan="' . $nCl . '"';
						}elseif($rowSpan === FALSE){
							$rowSpan = '';
						}elseif($rowSpan === 'rowspan="' . $nCl . '"'){
							$rowSpan = 'style="display:none;"';
						}
						if($this->xls === TRUE) {
							$rowSpan = '';
						}

						$idd = '';
						$arr_detf = array();
						$arr_detp = array();

						if ($bc === true) {
							$idd = 'data-dd="' . base64_encode($this->rowcl['id_detalle']) . '"';
							$arr_detp = json_decode($this->rowcl['detalle_p'], true);
							$arr_detf = json_decode($this->rowcl['detalle_f'], true);

							$arr_state['obs'] = '';
							$this->row['fecha_ultima_modificacion'] = '';
							$this->row['dias_ultima_modificacion'] = 0;
							$arr_state['txt'] = '';
							$arr_state['bg'] = '';

							$arr_state['txt'] = 'Aprobado Free Cover';
							$arr_state['bg'] = 'background: #FF3C3C; color: #FFF;';

							$this->row['extra_prima'] = 0;

							if (empty($arr_detf) === true) {
								if (count($arr_detp) > 0) {
									$arr_state['obs'] = $arr_detp['estado'];
									$this->row['fecha_ultima_modificacion'] = $arr_detp['fecha_creacion'];

									$date1 = new DateTime(
										date('Y-m-d', strtotime(str_replace('/', '-', $arr_detp['fecha_creacion']))));
									$date2 = new DateTime(date('Y-m-d'));
									$interval = $date1->diff($date2);
									$this->row['dias_ultima_modificacion'] = $interval->format('%a');

									if ($arr_detp['respuesta'] === null) {
										$arr_state['txt'] = 'Observado';
										$arr_state['bg'] = 'background: #009148; color: #FFF;';
									} else {
										$arr_state['txt'] = 'Subsanado/Pendiente';
										$arr_state['bg'] = 'background: #FFFF2D; color: #666;';
									}
								}
							} else {
								if (count($arr_detf) > 0) {
									if ($arr_detf['aprobado'] === 'SI') {
										$arr_state['obs'] = $arr_state['txt'] = 'Aprobado';
										if ($arr_detf['tasa_recargo'] === 'SI') {
											$this->row['extra_prima'] = $arr_detf['porcentaje_recargo'];
										}
									} else {
										$arr_state['obs'] = $arr_state['txt'] = 'Rechazado';
										$cl_aprobado = true;
									}
									
									$this->row['fecha_ultima_modificacion'] = $arr_detf['fecha_creacion'];
									
									$date1 = new DateTime(
										date('Y-m-d', strtotime(str_replace('/', '-', $arr_detf['fecha_creacion']))));
									$date2 = new DateTime(date('Y-m-d'));
									$interval = $date1->diff($date2);
									$this->row['dias_ultima_modificacion'] = $interval->format('%a');

									$date3 = new DateTime(date('Y-m-d', strtotime($this->row['fecha_creacion'])));
									$interval2 = $date1->diff($date3);
									$this->row['dias_proceso'] = $interval2->format('%a');
								}
							}

							$this->row['r_monto_solicitado'] = $this->rowcl['monto_bc'];
						}
?>
		<tr style=" <?=$bg;?> " class="row" rel="0" 
			data-nc="<?=base64_encode($this->row['ide']);?>" 
			data-token="<?=$this->dataToken;?>" 
			data-issue="<?=base64_encode(0);?>" 
			data-bc="<?=(int)$bc;?>" <?=$idd;?> >
        	<td <?=$rowSpan;?>><?=$this->row['r_prefijo'] . '-' . $this->row['r_no_emision'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['ef_nombre'];?></td>
            <td><?=htmlentities($this->rowcl['cl_nombre'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$this->rowcl['cl_ci'].$this->rowcl['cl_complemento'].' '.$this->rowcl['cl_extension'];?></td>
            <td><?=$this->rowcl['cl_genero'];?></td>
            <td><?=$this->rowcl['cl_ciudad'];?></td>
            <td><?=$this->rowcl['cl_telefono'];?></td>
            <td><?=$this->rowcl['cl_celular'];?></td>
            <td><?=$this->rowcl['cl_email'];?></td>
            <td><?=number_format($this->row['r_monto_solicitado'],2,'.',',');?></td>
            <td><?=$this->row['r_moneda'];?></td>
            <td><?=number_format($this->rowcl['cl_saldo'],2,'.',',');?> Bs.</td>
            <td><?=number_format($this->rowcl['cl_cumulo'],2,'.',',');?> Bs.</td>
            <td><?=$this->row['r_plazo'].' '.htmlentities($this->row['r_tipo_plazo'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$this->rowcl['cl_estatura'];?></td>
            <td><?=$this->rowcl['cl_peso'];?></td>
            <td><?=(int)$this->rowcl['cl_participacion'];?></td>
            <td><?=$this->rowcl['cl_titular'];?></td>
            <td><?=$this->rowcl['cl_edad'];?></td>
            <td><?=htmlentities($this->row['r_creado_por'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$this->row['r_fecha_creacion'];?></td>
            <td><?=$this->row['r_sucursal'];?></td>
            <td><?=htmlentities($this->row['r_agencia'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$this->row['r_anulado'];?></td>
            <td><?=htmlentities($this->row['r_anulado_nombre'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$this->row['r_anulado_fecha'];?></td>
            <td><?=htmlentities($arr_state['txt'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$arr_state['txt_bank'];?></td>
            <td><?=$arr_state['obs'];?></td>
            <td><?=$this->row['extra_prima'];?></td>
            <td><?=$this->row['fecha_resp_final_cia'];?></td>
<?php
						if ($this->token === 'RG') {
							echo '<td>' . $this->row['dias_proceso'] . '</td>
								<td>' . $this->row['dias_ultima_modificacion'] . '</td>
								';
						}
?>
            <td><?=htmlentities($this->row['duracion_caso'].' días', ENT_QUOTES, 'UTF-8');?></td>
<?php
						/*if ($this->token === 'RG') {
							$solicitud = 'N/A';
							$bg_sol = '';
							if (true === (boolean)$this->row['estado_facultativo']) {
								if (true === (boolean)$this->row['solicitud']) {
									$solicitud = 'SI';
									$bg_sol = 'background: #18b745; color: #FFF;';
								} else {
									$solicitud = 'NO';
									$bg_sol = 'background: #f31d1d; color: #FFF;';
								}
							}
							echo '<td ' . $rowSpan . ' style="font-size: 110%; font-weight: bold; ' 
								. $bg_sol . '">' . $solicitud . '</td>';
						}
*/?>
            <!--<td>Días de Ultima Modificación</td>-->
        </tr>
<?php
					}
				}
			}

			if ($swBG === FALSE) {
				$swBG = TRUE;
			} elseif($swBG === TRUE) {
				$swBG = FALSE;
			}
		}
		$this->rs->free();
?>
    </tbody>
    <tfoot>
    	<tr>
        	<td colspan="29" style="text-align:left;">
<?php
			if($this->xls === FALSE){
				echo '<a href="rp-records.php?data-pr=' . base64_encode($this->pr) 
					. '&flag=' . $this->flag . '&ms=' . $this->data['ms'] 
					. '&page=' . $this->data['page'] . '&xls=' . md5('TRUE') 
					. '&idef=' . base64_encode($this->data['idef']) 
					. '&frp-policy=' . $this->data['policy'] 
					. '&frp-nc=' . $this->data['nc'] 
					. '&frp-subsidiary=' . base64_encode($this->data['subsidiary'])
					. '&frp-agency=' . base64_encode($this->data['agency'])
					. '&frp-user=' . $this->data['user'] 
					. '&frp-client=' . $this->data['client'] 
					. '&frp-dni=' . $this->data['dni'] 
					. '&frp-comp=' . $this->data['comp'] 
					. '&frp-ext=' . $this->data['ext'] 
					. '&frp-date-b=' . $this->data['date-begin'] 
					. '&frp-date-e=' . $this->data['date-end'] 
					. '&frp-id-user=' . base64_encode($this->data['idUser']) 
					. '&frp-pendant=' . $this->data['r-pendant'] 
					. '&frp-state=' . $this->data['r-state'] 
					. '&frp-free-cover=' . $this->data['r-free-cover'] 
					. '&frp-extra-premium=' . $this->data['r-extra-premium'] 
					. '&frp-issued=' . $this->data['r-issued'] 
					. '&frp-rejected=' . $this->data['r-rejected'] 
					. '&frp-canceled=' . $this->data['r-canceled'] 
					. '" class="send-xls" target="_blank">Exportar a Formato Excel</a>';
			}
?>
			</td>
        </tr>
    </tfoot>
</table>
<?php
	}
	
	//COTIZACION
	private function set_result_de_quote(){
?>
<script type="text/javascript">
$(document).ready(function(e) {
    $(".row").reportCxt({
		context: ''
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
            <td>Monto Solicitado</td>
            <td>Moneda</td>
            <td><?=htmlentities('Plazo Crédito', ENT_QUOTES, 'UTF-8');?></td>
            <td>Estatura (cm)</td>
            <td>Peso (kg)</td>
            <td>Deudor / Codeudor</td>
            <td>Edad</td>
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
			$nCl = (int)$this->row['no_cl'];
			if($swBG === FALSE){
				$bg = 'background: #EEF9F8;';
			}elseif($swBG === TRUE){
				$bg = 'background: #D1EDEA;';
			}
						
			$rowSpan = FALSE;
			if($nCl >= 2){
				$rowSpan = TRUE;
			}
			
			$arr_state['txt'] = '';		$arr_state['txt_bank'] = '';	$arr_state['action'] = '';
			$arr_state['obs'] = '';		$arr_state['link'] = '';	$arr_state['bg'];
			
			//$this->cx->get_state($arr_state, $this->row, 2);
			
			$this->sqlcl = "select 
				sdc.id_cliente as idCl,
				concat(sdc.nombre,
						' ',
						sdc.paterno,
						' ',
						sdc.materno) as cl_nombre,
				sdc.ci as cl_ci,
				sdc.complemento as cl_complemento,
				sdep.codigo as cl_extension,
				sdep.departamento as cl_ciudad,
				(case sdc.genero
					when 'M' then 'Hombre'
					when 'F' then 'Mujer'
				end) as cl_genero,
				sdc.telefono_domicilio as cl_telefono,
				sdc.telefono_celular as cl_celular,
				sdc.email as cl_email,
				(case sdd.titular
					when 'DD' then 'Deudor'
					when 'CC' then 'Codeudor'
				end) as cl_titular,
				sdc.estatura as cl_estatura,
				sdc.peso as cl_peso,
				sdd.porcentaje_credito as cl_participacion,
				(year(curdate()) - year(sdc.fecha_nacimiento)) as cl_edad
			from
				s_de_cot_cliente as sdc
					inner join
				s_de_cot_detalle as sdd ON (sdd.id_cliente = sdc.id_cliente)
					inner join
				s_departamento as sdep ON (sdep.id_depto = sdc.extension)
			where
				sdc.id_ef = '".$this->data['idef']."'
					and sdd.id_cotizacion = '".$this->row['idc']."'
					and concat(sdc.nombre,
						' ',
						sdc.paterno,
						' ',
						sdc.materno) like '%".$this->data['client']."%'
					and sdc.ci like '%".$this->data['dni']."%'
					and sdc.complemento like '%".$this->data['comp']."%'
					and sdc.extension like '%".$this->data['ext']."%'
			order by sdc.id_cliente asc
			;";
			
			if(($this->rscl = $this->cx->query($this->sqlcl,MYSQLI_STORE_RESULT))){
				if($this->rscl->num_rows === $nCl){
					while($this->rowcl = $this->rscl->fetch_array(MYSQLI_ASSOC)){
						if($rowSpan === TRUE){
							$rowSpan = 'rowspan="' . $nCl . '"';
						}elseif($rowSpan === FALSE){
							$rowSpan = '';
						}elseif($rowSpan === 'rowspan="' . $nCl . '"'){
							$rowSpan = 'style="display:none;"';
						}
						
						if($this->xls === TRUE){
							$rowSpan = '';
						}
?>
		<tr style=" <?=$bg;?> " class="row quote" rel="0" 
			data-nc="<?=base64_encode($this->row['idc']);?>" data-token="<?=$this->dataToken;?>">
        	<td <?=$rowSpan;?>><?=$this->row['r_no_cotizacion'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['ef_nombre'];?></td>
            <td><?=htmlentities($this->rowcl['cl_nombre'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$this->rowcl['cl_ci'].$this->rowcl['cl_complemento'].' '.$this->rowcl['cl_extension'];?></td>
            <td><?=$this->rowcl['cl_genero'];?></td>
            <td><?=$this->rowcl['cl_ciudad'];?></td>
            <td><?=$this->rowcl['cl_telefono'];?></td>
            <td><?=$this->rowcl['cl_celular'];?></td>
            <td><?=$this->rowcl['cl_email'];?></td>
            <td><?=number_format($this->row['r_monto_solicitado'],2,'.',',');?></td>
            <td><?=$this->row['r_moneda'];?></td>
            <td><?=$this->row['r_plazo'].' '.htmlentities($this->row['r_tipo_plazo'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$this->rowcl['cl_estatura'];?></td>
            <td><?=$this->rowcl['cl_peso'];?></td>
            <td><?=$this->rowcl['cl_titular'];?></td>
            <td><?=$this->rowcl['cl_edad'];?></td>
            <td><?=htmlentities($this->row['r_creado_por'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$this->row['r_fecha_creacion'];?></td>
            <td><?=$this->row['r_sucursal'];?></td>
            <td><?=htmlentities($this->row['r_agencia'], ENT_QUOTES, 'UTF-8');?></td>
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