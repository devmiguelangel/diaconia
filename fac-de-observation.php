<div style="width:auto; height:auto; min-width:300px; padding:5px 5px; font-size:80%;">
<?php
header("Expires: Tue, 01 Jan 2000 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require('sibas-db.class.php');
$link = new SibasDB();
$idd = null;

if (isset($_GET['idd'])) {
	$idd = $link->real_escape_string(trim(base64_decode($_GET['idd'])));
}

if(isset($_GET['ide']) && !isset($_GET['resp'])){
	$ide = $link->real_escape_string(trim(base64_decode($_GET['ide'])));
	echo get_observation($ide, $idd, $link);
}elseif(isset($_GET['ide']) && $_GET['resp']){
	$ide = $link->real_escape_string(trim(base64_decode($_GET['ide'])));
	$resp = $link->real_escape_string(trim($_GET['resp']));
	
	switch($resp){
		case md5('R0'):
?>
<script type="text/javascript">
$(document).ready(function(e) {
    get_tinymce('fp-obs');
	
	$("#form-resp").validateForm({
		action: 'FAC-DE-response-record.php',
		method: 'GET'
	});
});
</script>
<form id="form-resp" name="form-resp" class="f-process" style="width:570px; font-size:130%;">
	<h4 class="h4">Formulario de respuesta del usuario</h4>
	<label class="fp-lbl" style="text-align:left;">Respuesta: <span>*</span></label>
	<textarea id="fp-obs" name="fp-obs" class="required"></textarea><br>
    <div style="text-align:center">
    	<input type="hidden" id="fp-ide" name="fp-ide" value="<?=base64_encode($ide);?>">
    	<input type="submit" id="fp-process" name="fp-process" value="Guardar" class="fp-btn">
    </div>
<?php
	if ($idd !== null) {
		echo '<input type="hidden" id="fp-idd" name="fp-idd" value="' . base64_encode($idd) . '">';
	}
?>
    
    <div class="loading">
        <img src="img/loading-01.gif" width="35" height="35" />
    </div>
</form>
<?php
			break;
		case md5('R1'):
			echo get_observation($ide, $idd, $link, 1);
			break;
	}
}else{
	echo 'No existen observaciones';
}
$link->close();
?>
</div>
<?php
function get_observation($ide, $idd, $link, $flag = 0){
	$sql = 'select 
		sde.id_emision as ide,
		sde.id_compania as cia,
		sde.id_ef as ef,
		sdp.id_pendiente,
		sds.id_estado,
		sds.codigo,
		sds.estado,
		sdp.observacion,
		sdp.obs_respuesta,
		sdf.aprobado as f_aprobado,
		sdf.observacion as f_observacion,
		sdd.detalle_f,
		sdd.detalle_p
	from
		s_de_em_cabecera as sde
			inner join 
		s_de_em_detalle as sdd ON (sdd.id_emision = sde.id_emision)
			left join
		s_de_pendiente as sdp ON (sdp.id_emision = sde.id_emision)
			left join
		s_estado as sds ON (sds.id_estado = sdp.id_estado)
			left join 
		s_de_facultativo as sdf ON (sdf.id_emision = sde.id_emision)
	where
		sde.id_emision = "'.$ide.'"';
	if ($idd !== null) {
		$sql .= ' and sdd.id_detalle = "' . $idd . '" ';
	} else {
		$sql .= ' group by sde.id_emision ';
	}
	$sql .= ';';
	
	if(($rs = $link->query($sql,MYSQLI_STORE_RESULT))){
		if($rs->num_rows === 1){
			$row = $rs->fetch_array(MYSQLI_ASSOC);
			$rs->free();

			if ($idd !== null) {
				$arr_f = json_decode($row['detalle_f'], true);
				$arr_p = json_decode($row['detalle_p'], true);

				if (empty($arr_f) === true) {
					$row['f_aprobado'] = null;
					if (count($arr_p) > 0) {
						if ($arr_p['id_estado'] !== 1) {
							$row['codigo'] = '';
							if ($flag === 0) {
								$row['observacion'] = $arr_p['observacion'];
							} else {
								$row['obs_respuesta'] = $arr_p['respuesta'];
							}
						} else {
							$row['codigo'] = 'EM';
							$row['observacion'] = 'CM';
						}
					}
				} else {
					$row['f_aprobado'] = 0;
					if (count($arr_f) > 0) {
						$row['f_observacion'] = $arr_f['observacion'];
					}
				}
			}
			
			if($flag === 0){
				if($row['f_aprobado'] === null){
					if ($row['codigo'] === 'EM') {
						ob_start();
						require_once('medical-certificate.php');
						$cm = ob_get_clean();
						return $cm;
					} else {
						return $row['observacion'];
					}
				} else {
					return $row['f_observacion'];
				}
			} elseif($flag === 1) {
				return $row['obs_respuesta'];
			}
		}
	}else{
		return 'No existen Observaciones';
	}
}
?>