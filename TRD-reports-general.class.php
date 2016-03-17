<?php
require_once('sibas-db.class.php');
class ReportsGeneralTRD{
	private $cx, $sql, $rs, $row, $sqlpr, $rspr, $rowpr, $pr, $flag, $token, $nEF, $dataToken, $xls, $xlsTitle;
	protected $data = array();
	public $err;
	
	public function ReportsGeneralTRD($data, $pr, $flag, $xls){
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
			case md5('RG'): $this->token = 'RG'; $this->xlsTitle = 'Todo Riesgo Domiciliario - Reporte General'; break;
			case md5('RP'): $this->token = 'RP'; $this->xlsTitle = 'Todo Riesgo Domiciliario - Reporte Polizas Emitidas'; break;
			case md5('RQ'): $this->token = 'RQ'; $this->xlsTitle = 'Todo Riesgo Domiciliario - Reporte Cotizaciones'; break;
			
			case md5('IQ'): $this->token = 'IQ'; $this->xlsTitle = 'Todo Riesgo Domiciliario - Cotizaciones'; break;
			case md5('PA'): $this->token = 'PA'; $this->xlsTitle = 'Todo Riesgo Domiciliario - Solicitudes Preaprobadas'; break;
            case md5('SP'): $this->token = 'SP'; $this->xlsTitle = 'Todo Riesgo Domiciliario - Solicitudes Pendientes'; break;
			case md5('AP'): $this->token = 'AP'; $this->xlsTitle = 'Todo Riesgo Domiciliario - Pólizas Aprobadas'; break;
			case md5('AN'): $this->token = 'AN'; $this->xlsTitle = 'Todo Riesgo Domiciliario - Pólizas Emitidas'; break;
			
			case md5('IM'): $this->token = 'IM'; $this->xlsTitle = 'Todo Riesgo Domiciliario - Preaprobadas'; break;
			case md5('CP'): $this->token = 'CP'; $this->xlsTitle = 'Todo Riesgo Domiciliario - Certificados Provisionales'; break;
		}
		
		if($this->token === 'RG' 
           || $this->token === 'RP' 
           || $this->token === 'PA' 
           || $this->token === 'SP' 
           || $this->token === 'AN' 
           || $this->token === 'IM' 
           || $this->token === 'AP' 
           || $this->token === 'CP'){
			$this->set_query_trd_report();
		}elseif($this->token === 'RQ' || $this->token === 'IQ'){
			$this->set_query_trd_report_quote();
		}else
			$this->err = TRUE;
	}
	
	private function set_query_trd_report(){
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
		    stre.id_emision as ide,
		    stre.id_cotizacion as idc,
		    count(strd.id_emision) as noPr,
		    stre.prefijo,
		    stre.no_emision,
		    stre.plazo as r_plazo,
		    (case stre.tipo_plazo
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
		    date_format(stre.fecha_creacion, '%d/%m/%Y') as fecha_ingreso,
		    if(stre.fecha_emision != '0000-00-00',
		        datediff(stre.fecha_emision, stre.fecha_creacion),
		        0) as duracion_caso,
		    (case stre.anulado
		        when 1 then 'SI'
		        when 0 then 'NO'
		    end) as a_anulado,
		    if(stre.anulado = true, sua.nombre, '') as a_anulado_nombre,
		    if(stre.anulado = true,
		        date_format(stre.fecha_anulado, '%d/%m/%Y'),
		        '') as a_anulado_fecha,
		    sef.nombre as ef_nombre,
		    sef.logo as ef_logo,
		    if((stre.emitir = 0 and stre.aprobado = 0)
		            or stre.facultativo = 1,
		        'P',
		        'F') as estado,
		    if(stre.anulado = 1,
		        1,
		        if(stre.emitir = 1, 2, 3)) as estado_banco,
		    null as observacion,
		    stre.facultativo as estado_facultativo
		from
		    s_trd_em_cabecera as stre
		        inner join
		    s_trd_em_detalle as strd ON (strd.id_emision = stre.id_emision)
		        inner join
		    s_cliente as scl ON (scl.id_cliente = stre.id_cliente)
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = stre.id_ef)
		        inner join
		    s_departamento as sdep ON (sdep.id_depto = scl.extension)
		        inner join
		    s_usuario as su ON (su.id_usuario = stre.id_usuario)
		        inner join
		    s_departamento as sdepu ON (sdepu.id_depto = su.id_depto)
		        left join
		    s_agencia as sag ON (sag.id_agencia = su.id_agencia)
		        inner join
		    s_usuario as sua ON (sua.id_usuario = stre.and_usuario)
		        inner join
		    s_forma_pago as sfp ON (sfp.id_forma_pago = stre.id_forma_pago)
		where sef.id_ef = '".$this->data['idef']."'
	        and stre.no_emision like '%".$this->data['nc']."%'
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
	        and stre.fecha_creacion between '".$this->data['date-begin']."' and '".$this->data['date-end']."' ";
		if ($this->token === 'RG') {
			// and stre.id_poliza like '%".$this->data['policy']."%'
			$this->sql .= "
				and if(stre.emitir = true,
					'EM', 'NE') regexp '".$this->data['r-issued']."'
				and if(strd.aprobado = false, 
					'RE', 'R') regexp '".$this->data['r-rejected']."'
				and if(stre.anulado = true, 'AN', 'R') regexp '".$this->data['r-canceled']."'
				 ";
		} elseif($this->token === 'PA'){
			$this->sql .= "and stre.emitir = false
							and stre.facultativo = false
							and stre.anulado like '%".$this->data['r-canceled']."%'
							";
		} elseif($this->token === 'SP'){
			$this->sql .= "and stre.emitir = false
							and stre.aprobado = false
                            and stre.rechazado = false
							and stre.anulado like '%".$this->data['r-canceled']."%'
							";
		} elseif($this->token === 'IM'){
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
			
			$this->sql .= $sqlAg."and stre.emitir = false
					and stre.anulado like '%".$this->data['r-canceled']."%'
					and stre.aprobado = false
					and stre.rechazado = false
					";
		} if ($this->token === 'RP') {
			$this->sql .= "and stre.emitir = true
					and stre.anulado like '%".$this->data['r-canceled']."%'
					";
		} elseif($this->token === 'AP'){
			$this->sql .= "and (if(stre.aprobado = true and stre.rechazado = false,
			        'A',
			        if(stre.aprobado = false and stre.rechazado = true,
			            'R',
			            ''))) regexp '".$this->data['approved']."'
					and stre.anulado like '%".$this->data['r-canceled']."%'
					";
		} elseif($this->token === 'AN'){
			$this->sql .= "and stre.emitir = true
					and stre.anulado like '%".$this->data['r-canceled']."%'
					";
		} elseif ($this->token === 'CP') {
			$this->sql .= "and stre.emitir = true
					and stre.aprobado = true
					and stre.rechazado = false
					and stre.anulado like '%".$this->data['r-canceled']."%'
					and stre.certificado_provisional = true
					";
		}
		$this->sql .= "group by stre.id_emision
		order by stre.id_emision desc
		;";
		
		//echo $this->sql;
		
		if(($this->rs = $this->cx->query($this->sql,MYSQLI_STORE_RESULT))){
			$this->err = FALSE;
		}else{
			$this->err = TRUE;
		}
	}
	
	private function set_query_trd_report_quote(){
		switch($this->token){
			case 'RQ': $this->dataToken = 2; break;
			case 'IQ': $this->dataToken = 3; break;
		}
		
		$this->sql = "select 
		    strc.id_cotizacion as idc,
		    count(strc.id_cotizacion) as noPr,
		    strc.no_cotizacion,
		    strc.plazo as r_plazo,
		    (case strc.tipo_plazo
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
		    date_format(strc.fecha_creacion, '%d/%m/%Y') as fecha_ingreso,
		    sef.nombre as ef_nombre,
		    sef.logo as ef_logo
		from
		    s_trd_cot_cabecera as strc
		        inner join
		    s_trd_cot_detalle as strd ON (strd.id_cotizacion = strc.id_cotizacion)
		        inner join
		    s_trd_cot_cliente as scl ON (scl.id_cliente = strc.id_cliente)
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = strc.id_ef)
		        inner join
		    s_departamento as sdep ON (sdep.id_depto = scl.extension)
		        inner join
		    s_usuario as su ON (su.id_usuario = strc.id_usuario)
		        inner join
		    s_departamento as sdepu ON (sdepu.id_depto = su.id_depto)
		        left join
		    s_agencia as sag ON (sag.id_agencia = su.id_agencia)
		        inner join
		    s_forma_pago as sfp ON (sfp.id_forma_pago = strc.id_forma_pago)
		where
		    sef.id_ef = '".$this->data['idef']."'
		        and strc.no_cotizacion like '".$this->data['nc']."'
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
		        and strc.fecha_creacion between '".$this->data['date-begin']."' and '".$this->data['date-end']."'
		        and (exists( select 
		            stre1.id_cotizacion
		        from
		            s_trd_em_cabecera as stre1
		        where
		            stre1.id_cotizacion = strc.id_cotizacion
		                and stre1.anulado = true
		                and stre1.emitir = true)
		        or not exists( select 
		            stre1.id_cotizacion
		        from
		            s_trd_em_cabecera as stre1
		        where
		            stre1.id_cotizacion = strc.id_cotizacion))
		group by strc.id_cotizacion
		order by strc.id_cotizacion desc
		;";
		
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
			$this->set_result_trd();
		}elseif($this->token === 'RQ' || $this->token === 'IQ'){
			$this->set_result_trd_quote();
		}
	}
	
	//EMISION
	private function set_result_trd(){
		//echo $this->token;
		//echo $this->data['idef'];
?>
<script type="text/javascript">
$(document).ready(function(e) {
    $(".row-au").reportCxt({
    	product: 'TRD'
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
            <td>Tipo</td>
            <td>Uso</td>
            <td>Estado</td>
            <td>Departamento</td>
            <td>Zona</td>
            <td>Ciudad/Localidad</td>
            <td><?=htmlentities('Dirección', ENT_QUOTES, 'UTF-8');?></td>
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
			$nPr = (int)$this->row['noPr'];
			
			if($swBG === FALSE){
				$bg = 'background: #EEF9F8;';
			}elseif($swBG === TRUE){
				$bg = 'background: #D1EDEA;';
			}
						
			$rowSpan = FALSE;
			if($nPr > 0) {
				$rowSpan = TRUE;
			}
			
			$this->sqlpr = "select 
			    stre.id_emision as ide,
			    strd.id_inmueble as idPr,
			    (case strd.tipo_in
			        when 'HOME' then 'Casa'
			        when 'DEPT' then 'Departamento'
			        when 'BLDN' then 'Edificio'
			        when 'LOCL' then 'Local Comercial/Oficina'
			    end) as pr_tipo,
			    (case strd.uso
			        when 'DMC' then 'Domiciliario'
			        when 'COM' then 'Comercial'
			        when 'OTH' then strd.uso_otro
			    end) as pr_uso,
			    (case strd.estado
			        when 'FINS' then 'Terminado'
			    end) as pr_estado,
			    sdep.departamento as pr_departamento,
			    strd.zona as pr_zona,
			    strd.localidad as pr_localidad,
			    strd.direccion as pr_direccion,
			    strd.valor_asegurado as pr_valor_asegurado,
			    '' as pr_adjunto,
			    strd.tasa as pr_tasa,
			    strd.prima as pr_prima
			from
			    s_trd_em_detalle as strd
			        inner join
			    s_trd_em_cabecera as stre ON (stre.id_emision = strd.id_emision)
			    	inner join
    			s_departamento as sdep ON (sdep.id_depto = strd.departamento)
			where
			    strd.id_emision = '".$this->row['ide']."' ";
			if ($this->token === 'AP') {
				$this->sqlpr .= 'and (strd.aprobado = true) ';
			}
			$this->sqlpr .= "order by strd.id_inmueble asc
			;";
			//echo $this->sqlpr;
			if(($this->rspr = $this->cx->query($this->sqlpr,MYSQLI_STORE_RESULT))){
				if($this->rspr->num_rows <= $nPr){
					$nPr = $this->rspr->num_rows;
					
					if ($this->token === 'AP') {
						//$nPr = $_APS;
					}
					
					while($this->rowpr = $this->rspr->fetch_array(MYSQLI_ASSOC)){
						if($rowSpan === TRUE){
							$rowSpan = 'rowspan="'.$nPr.'"';
						}elseif($rowSpan === FALSE){
							$rowSpan = '';
						}elseif($rowSpan === 'rowspan="'.$nPr.'"'){
							$rowSpan = 'style="display:none;"';
						}
						if($this->xls === TRUE) {
							$rowSpan = '';
						}
						
						$arr_state['txt'] = '';		$arr_state['txt_bank'] = '';	$arr_state['action'] = '';
						$arr_state['obs'] = '';		$arr_state['link'] = '';	$arr_state['bg'] = '';
						
						$this->cx->get_state($arr_state, $this->row, 2, 'TRD', FALSE);
?>
		<tr style=" <?=$bg;?> " class="row-au" rel="0" data-nc="<?=base64_encode($this->row['ide']);?>" data-token="<?=$this->dataToken;?>" data-pr="<?=base64_encode($this->rowpr['idPr']);?>" data-issue="<?=base64_encode(0);?>">
        	<td <?=$rowSpan;?>>TRD-<?=$this->row['no_emision'];?></td>
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
            <td><?=$this->rowpr['pr_tipo'];?></td>
            <td><?=$this->rowpr['pr_uso'];?></td>
            <td><?=$this->rowpr['pr_estado'];?></td>
            <td><?=$this->rowpr['pr_departamento'];?></td>
            <td><?=$this->rowpr['pr_zona'];?></td>
            <td><?=$this->rowpr['pr_localidad'];?></td>
            <td><?=$this->rowpr['pr_direccion'];?></td>
            <td><?=number_format($this->rowpr['pr_valor_asegurado'],2,'.',',');?> USD</td>
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
            <td><?=htmlentities($this->row['duracion_caso'].' días', ENT_QUOTES, 'UTF-8');?></td>
            <!--<td>Días de Ultima Modificación</td>-->
        </tr>
<?php
					}
				}
			}
			if($swBG === FALSE) {
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
	private function set_result_trd_quote(){
?>
<script type="text/javascript">
$(document).ready(function(e) {
    $(".row").reportCxt({
		context: '',
		product: 'TRD'
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
            <td>Ciudad</td>
            <td><?=htmlentities('Género', ENT_QUOTES, 'UTF-8');?></td>
            <td><?=htmlentities('Teléfono', ENT_QUOTES, 'UTF-8');?></td>
            <td>Celular</td>
            <td>Email</td>
            <td><?=htmlentities('Plazo Crédito', ENT_QUOTES, 'UTF-8');?></td>
            <td>Forma de Pago</td>
            <td>Tipo</td>
            <td>Uso</td>
            <td>Estado</td>
            <td>Departamento</td>
            <td>Zona</td>
            <td>Ciudad/Localidad</td>
            <td><?=htmlentities('Dirección', ENT_QUOTES, 'UTF-8');?></td>
            <td>Valor Asegurado</td>
            <td>Creado Por</td>
            <td>Fecha de Ingreso</td>
            <td>Sucursal Registro</td>
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
			$nPr = (int)$this->row['noPr'];
			if($swBG === FALSE){
				$bg = 'background: #EEF9F8;';
			}elseif($swBG === TRUE){
				$bg = 'background: #D1EDEA;';
			}
						
			$rowSpan = FALSE;
			if($nPr > 0)
				$rowSpan = TRUE;
			
			$arr_state['txt'] = '';		$arr_state['txt_bank'] = '';	$arr_state['action'] = '';
			$arr_state['obs'] = '';		$arr_state['link'] = '';	$arr_state['bg'];
			
			//$this->cx->get_state($arr_state, $this->row, 2);
			
			$this->sqlpr = "select strc.id_cotizacion as idc,
			    strd.id_inmueble as idPr,
			    (case strd.tipo_in
			        when 'HOME' then 'Casa'
			        when 'DEPT' then 'Departamento'
			        when 'BLDN' then 'Edificio'
			        when 'LOCL' then 'Local Comercial/Oficina'
			    end) as pr_tipo,
			    (case strd.uso
			        when 'DMC' then 'Domiciliario'
			        when 'COM' then 'Comercial'
			        when 'OTH' then strd.uso_otro
			    end) as pr_uso,
			    (case strd.estado
			        when 'FINS' then 'Terminado'
			    end) as pr_estado,
			    sdep.departamento as pr_departamento,
			    strd.zona as pr_zona,
			    strd.localidad as pr_localidad,
			    strd.direccion as pr_direccion,
			    strd.valor_asegurado as pr_valor_asegurado
			from
			    s_trd_cot_detalle as strd
			        inner join
			    s_trd_cot_cabecera as strc ON (strc.id_cotizacion = strd.id_cotizacion)
			        inner join
			    s_departamento as sdep ON (sdep.id_depto = strd.departamento)
			where
    			strc.id_cotizacion = '".$this->row['idc']."'
			order by strd.id_inmueble asc;";
			//echo $this->sqlpr;
			if(($this->rspr = $this->cx->query($this->sqlpr, MYSQLI_STORE_RESULT))){
				if($this->rspr->num_rows <= $nPr){
					while($this->rowpr = $this->rspr->fetch_array(MYSQLI_ASSOC)){
						if($rowSpan === TRUE){
							$rowSpan = 'rowspan="'.$nPr.'"';
						}elseif($rowSpan === FALSE){
							$rowSpan = '';
						}elseif($rowSpan === 'rowspan="'.$nPr.'"'){
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
            <td <?=$rowSpan;?>><?=$this->row['cl_ciudad'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['cl_genero'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['cl_telefono'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['cl_celular'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['cl_email'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['r_plazo'].' '.htmlentities($this->row['r_tipo_plazo'], ENT_QUOTES, 'UTF-8');?></td>
            <td <?=$rowSpan;?>><?=$this->row['r_forma_pago'];?></td>
            <td><?=$this->rowpr['pr_tipo'];?></td>
            <td><?=$this->rowpr['pr_uso'];?></td>
            <td><?=$this->rowpr['pr_estado'];?></td>
            <td><?=$this->rowpr['pr_departamento'];?></td>
            <td><?=$this->rowpr['pr_zona'];?></td>
            <td><?=$this->rowpr['pr_localidad'];?></td>
            <td><?=$this->rowpr['pr_direccion'];?></td>
            <td><?=number_format($this->rowpr['pr_valor_asegurado'],2,'.',',');?> USD.</td>
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