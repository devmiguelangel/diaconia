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
$nCl = 0;
$nFc = 0;
$nAp = 0;
$nPr = 0;
$nFa = 0;
$reg_total = 0;

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
	$(".row").reportCxt();
	
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
            <td>Monto Solicitado <br>(Banca/Individual)</td>
            <td>Moneda</td>
            <td>Monto Total Acumulado</td>
            <td>Plazo Crédito</td>
            <td>Creado Por</td>
            <td>Sucursal Registro</td>
            <td>Agencia</td>
            <td>Fecha de Ingreso</td>
            <td>Hora de Ingreso</td>
            <!--<td>Tipo de Cobertura</td>-->
            <td>Deudor / Codeudor</td>
            <td>Días en Proceso</td>
            <td>Estado</td>
            <td>Días de Ultima Modificación</td>
            <td>Fecha Ultima Modificación</td>
            <td>Hora Ultima Modificación</td>
            <td>Observaciones Realizadas</td>
            <!--<td>Acción</td>-->
        </tr>
    </thead>
<?php
if(isset($_GET['fde']) && isset($_GET['fde-id-user'])){
	$link = new SibasDB();
	
	if(isset($_GET['fde-ef'])) {
		$s_ef = $_GET['fde-ef'];
	} else {
		$s_ef[0] = base64_encode('%%');
	}
	
	$s_nc = $link->real_escape_string(trim($_GET['fde-nc']));
	$s_prefix = '';
	if (isset($_GET['fde-prefix'])) {
		$s_prefix = $_GET['fde-prefix'];
	}
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
		
		$_IMP = $link->verify_implant($s_ef[0], 'DE');
	}
		
	$_SW_EM = FALSE;
	
	$nEF = count($s_ef);
	
	$sql = "select 
		sde.id_emision as ide,
		sde.id_cotizacion as idc,
		sef.nombre as ef_nombre,
		count(sdc.id_cliente) as noCl,
		sum(if(sdd.facultativo = true, 1, 0)) as noFc,
		sum(if(sdd.aprobado = true and sdd.detalle_f = '', 1, 0)) as noAp,
		sum(if(sdd.aprobado = true and sdd.detalle_f != '', 1, 0)) as noFa,
		sum(if(sdd.aprobado = false and sdd.detalle_f != '', 1, 0)) as noPr,
		if(sde.cobertura = 2, 1, 0) as bc,
		sde.prefijo,
		sde.no_emision,
		sde.id_compania,
		sde.monto_solicitado,
		(case sde.moneda
			when 'BS' then 'Bolivianos'
			when 'USD' then 'Dolares Estadounidenses'
		end) as moneda,
		sde.plazo,
		case sde.tipo_plazo
			when 'Y' then 'Años'
			when 'M' then 'Meses'
			when 'W' then 'Semanas'
			when 'D' then 'Días'
		end as tipo_plazo,
		su.usuario,
		su.nombre as usuario_nombre,
		sdep.departamento as sucursal,
		sag.agencia as u_agencia,
		date_format(sde.fecha_creacion, '%d/%m/%Y') as fecha_ingreso,
		sde.fecha_creacion,
		sde.hora_creacion as hora_ingreso,
		case sde.cobertura
			when 1 then 'Individual/Mancomuno'
			when 2 then 'Banca Comunal'
		end as cobertura,
		if(sde.emitir = true,
			if(sdf.aprobado is not null,
				datediff(sdf.fecha_creacion, sde.fecha_creacion),
				datediff(sde.fecha_emision, sde.fecha_creacion)),
			if(sdf.aprobado is null,
				datediff(curdate(), sde.fecha_creacion),
				datediff(sdf.fecha_creacion, sde.fecha_creacion))) as dias_proceso,
		if(sdf.aprobado is null,
			if(sdp.id_pendiente is not null,
				case sdp.respuesta
					when 1 then 'S'
					when 0 then 'O'
				end,
				'P'),
			case sdf.aprobado
				when 'SI' then 'A'
				when 'NO' then 'R'
			end) as estado,
		@fum:=(if(sdf.aprobado is null,
			datediff(curdate(), sdp.fecha_creacion),
			datediff(curdate(), sdf.fecha_creacion))) as fum,
		if(@fum is not null, @fum, 0) as dias_ultima_modificacion,
		if(sdf.aprobado is null, 
			date_format(sdp.fecha_creacion, '%d/%m/%Y'),
			date_format(sdf.fecha_creacion, '%d/%m/%Y')) as fecha_ultima_modificacion,
		if(sdf.aprobado is null, 
			sdp.hora_creacion,
			sdf.hora_creacion) as hora_ultima_modificacion,
		case
			when sds.codigo = 'ED' then 'E'
	        when sds.codigo != 'ED' then 'NE'
			else null
		end as observacion,
		sds.id_estado,
		sds.estado as estado_pendiente,
		sds.codigo as estado_codigo,
		sde.leido
	from
		s_de_em_cabecera as sde
			inner join
		s_de_em_detalle as sdd ON (sdd.id_emision = sde.id_emision)
			inner join
		s_cliente as sdc ON (sdc.id_cliente = sdd.id_cliente)
			left join
		s_de_facultativo as sdf ON (sdf.id_emision = sde.id_emision)
			left join
		s_de_pendiente as sdp ON (sdp.id_emision = sde.id_emision)
			left join
	    s_estado as sds ON (sds.id_estado = sdp.id_estado)
			inner join
	    s_entidad_financiera as sef ON (sef.id_ef = sde.id_ef)
			inner join
		s_usuario as su ON (su.id_usuario = sde.id_usuario)
			inner join
		s_departamento as sdep ON (sdep.id_depto = su.id_depto)
			left join
		s_agencia as sag ON (sag.id_agencia = su.id_agencia)
			inner join
    	s_producto_cia as spc on (spc.id_prcia = sde.id_prcia)
	where
		sde.facultativo = true
			and sde.emitir = false
			and sde.anulado = false
			and sef.activado = true
			and sde.aprobado = true
			and sde.rechazado = false
			and sde.prefijo like '%" . $s_prefix . "%'
			and (";
	if($nEF > 0){
		$sql .= "sef.id_ef like '".base64_decode($s_ef[0])."'";
		for($i = 1; $i < $nEF; $i++){
			$sql .= " or sef.id_ef like '".base64_decode($s_ef[$i])."' ";
		}
	}else {
		$sql .= "sef.id_ef like '%%'";
	}
	
	$sql .= ") and sde.no_emision like '%".$s_nc."%' ";
	
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
	
	$sql .= $sqlAg." and concat(sdc.nombre,
				' ',
				sdc.paterno,
				' ',
				sdc.materno) like '%".$s_client."%'
			and sdc.ci like '%".$s_dni."%'
			and sdc.complemento like '%".$s_comp."%'
			and sdc.extension like '%".$s_ext."%'
			and sde.fecha_creacion between '".$s_date_begin."' and '".$s_date_end."' ";
			
	if($token === 0){
		$sql .= "and not exists( select 
				sdf2.id_emision
			from
				s_de_facultativo as sdf2
			where
				sdf2.id_emision = sde.id_emision
					and sde.cobertura != 2 )";
	}
	
			
	$sql .= "group by sde.id_emision
	order by sde.id_emision desc
	;";
	//echo $sql;
	$rs = $link->query($sql,MYSQLI_STORE_RESULT);
	if($rs->num_rows > 0){
		// echo $rs->num_rows;
?>
	<tbody>
<?php
		$swBG = FALSE;
		$arr_state = array('txt' => '', 'action' => '', 'obs' => '', 'link' => '', 'bg' => '');
		$bgCheck = '';
		$unread = '';
        $bgOld = '';
		
		while($row = $rs->fetch_array(MYSQLI_ASSOC)){
            $rowSpanBgOld = '';
			$_EM = 0;
			
			$nCl = (int)$row['noCl'];
			$nFc = (int)$row['noFc'];
			$nAp = (int)$row['noAp'];
			$nFa = (int)$row['noFa'];
			$nPr = (int)$row['noPr'];

			$bc = (boolean)$row['bc'];

			if($swBG === FALSE){
				$bg = 'background: #EEF9F8;';
			}elseif($swBG === TRUE){
				$bg = 'background: #D1EDEA;';
			}
			
			if($token === 1){
				if(1 === (int)$row['leido']){
					$bgCheck = 'background-position:0 0;';
					$unread = '';
				}elseif(0 === (int)$row['leido']){
					$bgCheck = 'background-position:0 -24px;';
					$unread = 'unread';
				}

                $bgOld = 'color: #ffffff; background: ';
                $dayOld = (int)$row['dias_ultima_modificacion'];

                if ($dayOld >= 0 && $dayOld <= 2) {
                    $bgOld .= '#18b745;';
                } elseif ($dayOld >= 3 && $dayOld <= 10) {
                    $bgOld = 'color: #000000; background: #f4ec1c;';
                } elseif ($dayOld > 10) {
                    $bgOld .= '#f31d1d;';
                }

                if ($token === 1) {
                    $rowSpanBgOld .= ' style="' . $bgOld . '"';
                }
			} else {
                $bgOld = '';
            }
			
			$arr_state['txt'] = '';		$arr_state['action'] = '';
			$arr_state['obs'] = '';		$arr_state['link'] = '';	$arr_state['bg'];
			
			$link->get_state($arr_state, $row, $token, 'DE', FALSE);
			
			if ($_SW_EM === TRUE) {
				$_EM = 1;
			}
			
			$sqlCl = "select 
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
					sdd.monto_banca_comunal as cl_monto_bc,
					sdd.facultativo as cl_facultativo,
					sdd.aprobado as cl_aprobado,
					sdd.id_detalle,
					sdd.cumulo as monto_acumulado,
					sdd.detalle_f,
					sdd.detalle_p
				from
					s_cliente as sdc
						inner join
					s_de_em_detalle as sdd ON (sdd.id_cliente = sdc.id_cliente)
						inner join
					s_departamento as sdep ON (sdep.id_depto = sdc.extension)
				where
					sdd.id_emision = '".$row['ide']."'
				order by sdd.id_detalle asc
				;";
			
			$rsCl = $link->query($sqlCl,MYSQLI_STORE_RESULT);
			$nDt = $rsCl->num_rows;

			if ($bc === true) {
				if ($token === 0) {
					//$nDt = $nCl - $nAp;
					$nDt = $nCl - $nAp - $nPr - $nFa;
				} else {
					// echo '[' . $nCl . ' ' . $nFc . ' ' . $nAp . ' ' . $nFa . ' ' . $nPr . ']';
					$nDt = $nCl - $nAp;
					//$nDt = $nCl - $nAp + $nPr + $nFa;
				}
			}
			//echo '|'.$nDt.'|'.$nAp.'|'.$nPr.'|'.$nFa.'| - ';
			$rowSpan = false;
			if ($token === 0) {
				if($nDt > 1) {
					$rowSpan = true;
				}
			} else {
				if($nCl > 1) {
					$rowSpan = true;
				}
			}

			if($nDt <= $nCl){
				$cont = 0;
				$flag = 0;
				while($rowCl = $rsCl->fetch_array(MYSQLI_ASSOC)){
					$cont += 1;

					$idd = '';
					$arr_detf = array();
					$arr_detp = array();

					$reg_show = false;
					$cl_facultativo = (boolean)$rowCl['cl_facultativo'];
					$cl_aprobado = (boolean)$rowCl['cl_aprobado'];

					if ($bc === true) {
						$arr_detp = json_decode($rowCl['detalle_p'], true);
						$arr_detf = json_decode($rowCl['detalle_f'], true);
					}

					if ($rowSpan === true) {
						$rowSpan = 'rowspan="' . $nDt . '"';
					} elseif ($rowSpan === false) {
						$rowSpan = '';
					} elseif (strpos($rowSpan, 'rowspan') !== false) {
						$rowSpan = 'style="display:none;"';
					}

					$rowSpan_bc = $rowSpan;

					if ($bc === true) {
						$rowSpan_bc = '';
						$idd = 'data-dd="' . base64_encode($rowCl['id_detalle']) . '"';

						$arr_state['obs'] = '';
						$row['fecha_ultima_modificacion'] = '';
						$row['dias_ultima_modificacion'] = 0;
						$row['hora_ultima_modificacion'] = '';
						//$row['dias_proceso'] = 0;

						$arr_state['txt'] = '';
						$arr_state['bg'] = '';

						$arr_state['txt'] = 'PENDIENTE';
						$arr_state['bg'] = 'background: #FF3C3C; color: #FFF;';

						if (empty($arr_detf) === true) {
							if (count($arr_detp) > 0) {
								$arr_state['obs'] = $arr_detp['estado'];
								$row['fecha_ultima_modificacion'] = $arr_detp['fecha_creacion'];
								$row['hora_ultima_modificacion'] = $arr_detp['hora_creacion'];

								$date1 = new DateTime(
									date('Y-m-d', strtotime(str_replace('/', '-', $arr_detp['fecha_creacion']))));
								$date2 = new DateTime(date('Y-m-d'));
								$interval = $date1->diff($date2);
								$row['dias_ultima_modificacion'] = $interval->format('%a');

								if ($arr_detp['respuesta'] === null) {
									$arr_state['txt'] = 'OBSERVADO';
									$arr_state['bg'] = 'background: #009148; color: #FFF;';
								} else {
									$arr_state['txt'] = 'SUBSANADO/PENDIENTE';
									$arr_state['bg'] = 'background: #FFFF2D; color: #666;';
								}
							}
						} else {
							if (count($arr_detf) > 0) {
								if ($arr_detf['aprobado'] === 'SI') {
									$arr_state['obs'] = $arr_state['txt'] = 'APROBADO';
								} else {
									$arr_state['obs'] = $arr_state['txt'] = 'RECHAZADO';
									$cl_aprobado = true;
								}
								$row['fecha_ultima_modificacion'] = $arr_detf['fecha_creacion'];
								$row['hora_ultima_modificacion'] = $arr_detf['hora_creacion'];
								
								$date1 = new DateTime(
									date('Y-m-d', strtotime(str_replace('/', '-', $arr_detf['fecha_creacion']))));
								$date2 = new DateTime(date('Y-m-d'));
								$interval = $date1->diff($date2);
								$row['dias_ultima_modificacion'] = $interval->format('%a');
								
								$date3 = new DateTime(date('Y-m-d', strtotime($row['fecha_creacion'])));
								$interval2 = $date1->diff($date3);
								$row['dias_proceso'] = $interval2->format('%a');
							}
						}

						if ($cl_facultativo === true) {
							if ($token === 0 && $cl_aprobado === false && count($arr_detf) === 0) {
								$reg_show = true;
								$flag += 1;
							} elseif ($token === 1 || ($cl_aprobado === true && count($arr_detf) === 0)) {
								$reg_show = true;
								$flag += 1;
							}
						}
					} else {
						$reg_show = true;
						$flag += 1;
					}



					if ($reg_show === true) {
						if ($flag === 1) {
							$rowSpan = 'rowspan="' . $nDt . '"';
						}
?>
		<tr style=" <?=$bg;?> " class="row <?=$unread;?>" rel="0" 
			data-nc="<?=base64_encode($row['ide']);?>" data-token="<?=$token;?>" 
			data-issue="<?=base64_encode($_EM);?>" data-bc="<?=(int)$bc;?>" 
			<?=$idd;?> >
<?php
				if ($token === 1) {
                    $arr_state['bg'] = '';
?>
			<td <?=$rowSpan;?> ><label class="check-label" style=" <?=$bgCheck;?> " 
				data-read="<?=$row['leido'];?>"></label></td>
<?php
				}

?>
					
            <td <?=$rowSpan . $rowSpanBgOld;?>><?=$row['prefijo'] . '-' . $row['no_emision'];?></td>
            <td ><?=mb_strtoupper($row['ef_nombre']);?></td>
            <td ><?=mb_strtoupper($rowCl['cl_nombre']);?></td>
            <td ><?=$rowCl['cl_ci'];?></td>
            <td ><?=$rowCl['cl_complemento'];?></td>
            <td ><?=$rowCl['cl_extension'];?></td>
            <td ><?=$rowCl['cl_ciudad'];?></td>
            <td ><?=$rowCl['cl_genero'];?></td>
            <td ><?=$rowCl['cl_telefono'];?></td>
            <td ><?=$rowCl['cl_celular'];?></td>
            <td ><?=$rowCl['cl_email'];?></td>
            <td ><?=number_format($row['monto_solicitado'],2,'.',',');?></td>
            <td ><?=$row['moneda'];?></td>
            <td ><?=number_format($rowCl['monto_acumulado'],2,'.',',') . ' Bs.';?></td>
            <td ><?=$row['plazo'].' '.$row['tipo_plazo'];?></td>
            <td ><?=$row['usuario_nombre'];?></td>
            <td ><?=$row['sucursal'];?></td>
            <td ><?=htmlentities($row['u_agencia'], ENT_QUOTES, 'UTF-8');?></td>
            <td ><?=$row['fecha_ingreso'];?></td>
            <td ><?=$row['hora_ingreso'];?></td>
            <!--<td >Tipo de Cobertura</td>-->
            <td ><?=$rowCl['cl_titular'];?></td>
            <td <?=$rowSpan_bc;?>><?=$row['dias_proceso'];?></td>
            <td <?=$rowSpan_bc;?> style=" <?=$arr_state['bg'];?> "><?=$arr_state['txt'];?></td>
            <td <?=$rowSpan_bc;?>><?=$row['dias_ultima_modificacion'];?></td>
            <td <?=$rowSpan_bc;?>><?=$row['fecha_ultima_modificacion'];?></td>
            <td <?=$rowSpan_bc;?>><?=$row['hora_ultima_modificacion'];?></td>
            <td <?=$rowSpan_bc;?>><?=$arr_state['obs'];?></td>
            <!--<td>Acción</td>-->
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
?>
	</tbody>
<?php
	}
}
?>
</table>