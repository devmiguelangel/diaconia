<?php
header("Expires: Tue, 01 Jan 2000 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require('sibas-db.class.php');
$s_ef = array();
$s_nc = '';
$s_user = '';
$s_client = '';
$s_dni = '';
$s_comp = '';
$s_ext = '';
$s_date_begin = '2000-01-01';
$s_date_end = '3000-01-01';

$token = 0;
$bg = '';
$nVh = 0;

if(isset($_GET['token'])){
	switch($_GET['token']){
		case md5('1'):
			$token = 0;
			break;
		case md5('2'):
			$token = 1;
			break;
	}
}
?>
<script type="text/javascript">
$(document).ready(function(e) {
	$(".row").reportCxt({
		product: 'AU'
	});
	
	$(".fde-process").fancybox({
		
	});
	
	$(".observation").fancybox({
		
	});
});
</script>
<input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
<table class="result-list">
	<thead>
    	<tr>
<?php
if($token === 1){
	echo '<td>Leido / No Leido</td>';
}
?>
        	<td>No. Certificado</td>
            <td>Entidad Financiera</td>
            <td>Cliente</td>
            <td>CI</td>
            <td>Complemento</td>
            <td>Extensión</td>
            <td>Ciudad</td>
            <td>Género</td>
            <td>Teléfono</td>
            <td>Celular</td>
            <td>Email</td>
            <td>Tipo Vehículo</td>
            <td>Marca</td>
            <td>Modelo</td>
            <td>Año</td>
            <td>Placa</td>
            <td>Uso</td>
            <td>Tracción</td>
            <td>Cero Km.</td>
            <td>Valor Asegurado</td>
            <td>Creado Por</td>
            <td>Sucursal Registro</td>
            <td>Agencia</td>
            <td>Fecha de Ingreso</td>
            <td>Días en Proceso</td>
            <td>Estado</td>
            <td>Días de Ultima Modificación</td>
            <td>Observaciones Realizadas</td>
            <!--<td>Acción</td>-->
        </tr>
    </thead>
<?php
if(isset($_GET['fde']) && isset($_GET['fde-id-user'])){
	$link = new SibasDB();
	
	if(isset($_GET['fde-ef'])){
		$s_ef = $_GET['fde-ef'];
	}else {
		$s_ef[0] = base64_encode('%%');
	}
	
	$s_nc = $link->real_escape_string(trim($_GET['fde-nc']));
	$s_user = $link->real_escape_string(trim($_GET['fde-user']));
	$s_client = $link->real_escape_string(trim($_GET['fde-client']));
	$s_dni = $link->real_escape_string(trim($_GET['fde-dni']));
	$s_comp = $link->real_escape_string(trim($_GET['fde-comp']));
	$s_ext = $link->real_escape_string(trim($_GET['fde-ext']));
	$s_date_begin = $link->real_escape_string(trim($_GET['fde-date-b']));
	$s_date_end = $link->real_escape_string(trim($_GET['fde-date-e']));
	
	$idUSer = $link->real_escape_string(trim(base64_decode($_GET['fde-id-user'])));
	$type_user = $link->real_escape_string(trim(base64_decode($_GET['fde-type-user'])));
	
	if($s_date_begin === '' && $s_date_end === ''){
		$s_date_begin = '2000-01-01';	$s_date_end = '3000-01-01';
	}elseif($s_date_begin !== '' && $s_date_end === ''){
		$s_date_end = '3000-01-01';
	}elseif($s_date_begin === '' && $s_date_end !== ''){
		$s_date_end = '3000-01-01';
	}
	
	$_IMP = FALSE;
	if($token === 1){
		$s_user = $idUSer;
		
		$_IMP = $link->verify_implant($s_ef[0], 'AU');
	}
	
	$_SW_EM = FALSE;
	
	$nEF = count($s_ef);
	
	$sql = "select 
	    sae.id_emision as ide,
	    sae.id_cotizacion as idc,
	    count(sad.id_emision) as noVh,
	    sum(if(saf.id_facultativo is null
				and sad.facultativo = true
				and sad.aprobado = false,
			1,
			0)) as pendiente,
		sum(if(saf.aprobado = 'SI'
				or (sad.facultativo = false
				and sad.aprobado = true),
			1,
			0)) as aprobado_si,
		sum(if(saf.aprobado = 'NO'
				or (sad.aprobado = false
				and saf.id_facultativo is not null), 
				1, 0)) as aprobado_no,
	    sae.prefijo,
	    sae.no_emision,
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
	    sdep.codigo as cl_extension,
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
	    sef.nombre as ef_nombre,
	    sef.logo as ef_logo
	from
	    s_au_em_cabecera as sae
	        inner join
	    s_au_em_detalle as sad ON (sad.id_emision = sae.id_emision)
			left join
	    s_au_facultativo as saf ON (saf.id_vehiculo = sad.id_vehiculo
	        and saf.id_emision = sae.id_emision)
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
	where
	    sae.facultativo = true
	        and sae.emitir = false
	        and sae.anulado = false
	        and sef.activado = true
	        and sae.aprobado = true
	        and sae.rechazado = false 
	        and (";
	if($nEF > 0) {
		$sql .= "sef.id_ef like '".base64_decode($s_ef[0])."'";
		for($i = 1; $i < $nEF; $i++){
			$sql .= " or sef.id_ef like '".base64_decode($s_ef[$i])."' ";
		}
	} else {
		$sql .= "sef.id_ef like '%%'";
	}
	$sql .= ") and sae.no_emision like '%".$s_nc."%' ";
	
	$sqlAg = '';
	if ($_IMP === FALSE) {
		$_SW_EM = TRUE;
		user_log:
		$sql .= " and (su.usuario like '%".$s_user."%'
	        or su.id_usuario = '".$s_user."')";
	} else {
		if ($type_user === 'IMP') {
			$_SW_EM = TRUE;
			if (($rsAg = $link->get_agency_implant(base64_encode($idUSer), $s_ef[0])) !== FALSE) {
				$sqlAg = ' and (';
				while ($rowAg = $rsAg->fetch_array(MYSQLI_ASSOC)) {
					$sqlAg .= 'sag.id_agencia = "'.$rowAg['ag_id'].'" or ';
				}
				$sqlAg = trim($sqlAg, 'or ').') ';
				$rsAg->free();
			}
		} elseif ($type_user === 'LOG') {
			$_SW_EM = FALSE;
			goto user_log;
		}
	}
	
	$sql .= $sqlAg." and (case scl.tipo
				when
					0
				then
					concat(scl.nombre,
							' ',
							scl.paterno,
							' ',
							scl.materno)
				when 1 then scl.razon_social
			end) like '%".$s_client."%'
	        and scl.ci like '%".$s_dni."%'
	        and scl.complemento like '%".$s_comp."%'
	        and scl.extension like '%".$s_ext."%'
	        and sae.fecha_creacion between '".$s_date_begin."' and '".$s_date_end."'
	group by sae.id_emision
	order by sae.id_emision desc
	;";
	//echo $sql;
	
	$rs = $link->query($sql, MYSQLI_STORE_RESULT);
	if($rs->num_rows > 0){
?>
	<tbody>
<?php
		$swBG = FALSE;
		$arr_state = array('txt' => '', 'action' => '', 'obs' => '', 'link' => '', 'bg' => '');
		$bgCheck = '';
		$unread = '';
		
		while($row = $rs->fetch_array(MYSQLI_ASSOC)){
			$nVh = (int)$row['noVh'];
			$_PEN = (int)$row['pendiente'];
			$_APS = (int)$row['aprobado_si'];
			$_APN = (int)$row['aprobado_no'];
			
			if($swBG === FALSE){
				$bg = 'background: #EEF9F8;';
			}elseif($swBG === TRUE){
				$bg = 'background: #D1EDEA;';
			}
			
			$rowSpan = FALSE;
			if($nVh > 0) {
				$rowSpan = TRUE;
			}
			
			$sqlVh = "select 
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
			            'P'),
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
			    sad.id_emision = '".$row['ide']."'
			    	and sad.facultativo = true ";
        	if($token === 0){
				$sqlVh .= "and sad.aprobado = false 
					and not exists( select 
						saf2.id_emision, saf2.id_vehiculo
					from
						s_au_facultativo as saf2
					where
						saf2.id_emision = sae.id_emision
							and saf2.id_vehiculo = sad.id_vehiculo)";
			}
			$sqlVh .= " order by sad.id_emision asc ;";
			//echo $sqlVh;
			$rsVh = $link->query($sqlVh, MYSQLI_STORE_RESULT);
			if($rsVh->num_rows <= $nVh){
				while($rowVh = $rsVh->fetch_array(MYSQLI_ASSOC)){
					$_EM = 0;
					if ($token === 0) {
						$nVh = $_PEN;
					} else {
						$nVh = $rsVh->num_rows;
					}
					
					if($token === 1){
						if(1 === (int)$rowVh['leido']){
							$bgCheck = 'background-position:0 0;';
							$unread = '';
						}elseif(0 === (int)$rowVh['leido']){
							$bgCheck = 'background-position:0 -24px;';
							$unread = 'unread';
						}
						
						if ($_PEN === 0 && $_APS > 0 && $_SW_EM === TRUE) {
							$_EM = 1;
						}
					}
					
					$arr_state['txt'] = '';		$arr_state['action'] = '';
					$arr_state['obs'] = '';		$arr_state['link'] = '';	$arr_state['bg'];
					
					$link->get_state($arr_state, $rowVh, $token, 'AU', FALSE);
?>
		<tr style=" <?=$bg;?> " class="row <?=$unread;?>" rel="0" data-nc="<?=base64_encode($row['ide']);?>" data-token="<?=$token;?>" data-vh="<?=base64_encode($rowVh['idVh']);?>" data-issue="<?=base64_encode($_EM);?>" >
<?php
				if($rowSpan === TRUE){
					$rowSpan = 'rowspan="'.$nVh.'"';
				}elseif($rowSpan === FALSE){
					$rowSpan = '';
				}elseif($rowSpan === 'rowspan="'.$nVh.'"'){
					$rowSpan = 'style="display:none;"';
				}
				
				if($token === 1){
?>
			<td <?=$rowSpan;?>><label class="check-label" style=" <?=$bgCheck;?> " data-read="<?=$row['leido'];?>"></label></td>
<?php
				}
?>
			
            <td <?=$rowSpan;?>><?=$row['prefijo'].'-'.$row['no_emision'];?></td>
            <td <?=$rowSpan;?>><?=mb_strtoupper($row['ef_nombre']);?></td>
            <td <?=$rowSpan;?>><?=mb_strtoupper($row['cl_nombre']);?></td>
            <td <?=$rowSpan;?>><?=$row['cl_ci'];?></td>
            <td <?=$rowSpan;?>><?=$row['cl_complemento'];?></td>
            <td <?=$rowSpan;?>><?=$row['cl_extension'];?></td>
            <td <?=$rowSpan;?>><?=$row['cl_ciudad'];?></td>
            <td <?=$rowSpan;?>><?=$row['cl_genero'];?></td>
            <td <?=$rowSpan;?>><?=$row['cl_telefono'];?></td>
            <td <?=$rowSpan;?>><?=$row['cl_celular'];?></td>
            <td <?=$rowSpan;?>><?=$row['cl_email'];?></td>
            <td><?=$rowVh['v_tipo_vehiculo'];?></td>
            <td><?=$rowVh['v_marca'];?></td>
            <td><?=$rowVh['v_modelo'];?></td>
            <td><?=$rowVh['v_anio'];?></td>
            <td><?=$rowVh['v_placa'];?></td>
            <td><?=$rowVh['v_uso'];?></td>
            <td><?=$rowVh['v_traccion'];?></td>
            <td><?=$rowVh['v_km'];?></td>
            <td><?=number_format($rowVh['v_valor_asegurado'], 2, '.', ',');?> USD.</td>
            <td><?=$row['u_nombre'];?></td>
            <td><?=$row['u_sucursal'];?></td>
            <td><?=htmlentities($row['u_agencia'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$row['fecha_ingreso'];?></td>
            <td><?=$rowVh['dias_proceso'];?></td>
            <td style=" <?=$arr_state['bg'];?> "><?=$arr_state['txt'];?></td>
            <td><?=$rowVh['dias_ultima_modificacion'];?></td>
            <td><?=$arr_state['obs'];?></td>
            <!--<td>Acción</td>-->
        </tr>
<?php
				}
			}
			if($swBG === FALSE)
				$swBG = TRUE;
			elseif($swBG === TRUE)
				$swBG = FALSE;
		}
?>
	</tbody>
<?php
	}
}
?>
</table>