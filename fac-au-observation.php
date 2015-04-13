<div style="width:auto; height:auto; min-width:300px; padding:5px 5px; font-size:80%;">
<?php
header("Expires: Tue, 01 Jan 2000 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require('sibas-db.class.php');
$link = new SibasDB();
if(isset($_GET['ide']) && isset($_GET['idvh']) && !isset($_GET['resp'])){
	$ide = $link->real_escape_string(trim(base64_decode($_GET['ide'])));
	$idVh = $link->real_escape_string(trim(base64_decode($_GET['idvh'])));
	echo get_observation($ide, $idVh, $link);
}elseif(isset($_GET['ide']) && isset($_GET['idvh']) && $_GET['resp']){
	$ide = $link->real_escape_string(trim(base64_decode($_GET['ide'])));
	$idVh = $link->real_escape_string(trim(base64_decode($_GET['idvh'])));
	$resp = $link->real_escape_string(trim($_GET['resp']));
	
	switch($resp){
		case md5('R0'):
?>
<script type="text/javascript">
$(document).ready(function(e) {
    get_tinymce('fp-obs');
	
	$("#form-resp").validateForm({
		action: 'FAC-AU-response-record.php',
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
    	<input type="hidden" id="fp-idVh" name="fp-idVh" value="<?=base64_encode($idVh);?>">
    	<input type="submit" id="fp-process" name="fp-process" value="Guardar" class="fp-btn">
    </div>
    
    <div class="loading">
        <img src="img/loading-01.gif" width="35" height="35" />
    </div>
</form>
<?php
			break;
		case md5('R1'):
			echo get_observation($ide, $idVh, $link, 1);
			break;
	}
}else{
	echo 'No existen observaciones |';
}
$link->close();
?>
</div>
<?php
function get_observation($ide, $idVh, $link, $flag = 0){
	$sql = 'select 
	    sae.id_emision as ide,
	    sad.id_vehiculo as idVh,
	    sae.id_compania as cia,
	    sae.id_ef as ef,
	    sap.id_pendiente,
	    sds.id_estado,
	    sds.estado,
	    sap.observacion,
	    sap.obs_respuesta,
	    saf.aprobado as f_aprobado,
	    saf.observacion as f_observacion
	from
	    s_au_em_cabecera as sae
	        inner join
	    s_au_em_detalle as sad ON (sad.id_emision = sae.id_emision)
	        left join
	    s_au_pendiente as sap ON (sap.id_emision = sae.id_emision
	        and sap.id_vehiculo = sad.id_vehiculo)
	        left join
	    s_estado as sds ON (sds.id_estado = sap.id_estado)
	        left join
	    s_au_facultativo as saf ON (saf.id_emision = sae.id_emision
	        and saf.id_vehiculo = sad.id_vehiculo)
	where
	    sae.id_emision = "'.$ide.'"
	        and sad.id_vehiculo = "'.$idVh.'"
	;';
	
	if(($rs = $link->query($sql,MYSQLI_STORE_RESULT))){
		if($rs->num_rows === 1){
			$row = $rs->fetch_array(MYSQLI_ASSOC);
			$rs->free();
			
			if($flag === 0){
				if($row['f_aprobado'] == NULL) {
					return $row['observacion'];
					/*if((int)$row['id_estado'] === 1){
						ob_start();
						require_once('medical-certificate.php');
						$cm = ob_get_clean();
						return $cm;
					}else
						return $row['observacion'];*/
				}else {
					return $row['f_observacion'];
				}
			}elseif($flag === 1) {
				return $row['obs_respuesta'];
			}
		}
	}else{
		return 'No existen Observaciones';
	}
}
?>