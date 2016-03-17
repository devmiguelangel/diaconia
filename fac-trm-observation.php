<div style="width:auto; height:auto; min-width:300px; padding:5px 5px; font-size:80%;">
<?php
header("Expires: Tue, 01 Jan 2000 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require('sibas-db.class.php');
$link = new SibasDB();
if(isset($_GET['ide']) && !isset($_GET['resp'])){
	$ide = $link->real_escape_string(trim(base64_decode($_GET['ide'])));
	echo get_observation($ide, $link);
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
		action: 'FAC-TRM-response-record.php',
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
    
    <div class="loading">
        <img src="img/loading-01.gif" width="35" height="35" />
    </div>
</form>
<?php
			break;
		case md5('R1'):
			echo get_observation($ide, $link, 1);
			break;
	}
}else{
	echo 'No existen observaciones |';
}
$link->close();
?>
</div>
<?php
function get_observation($ide, $link, $flag = 0){
	$sql = 'select 
	    stre.id_emision as ide,
	    stre.id_compania as cia,
	    stre.id_ef as ef,
	    strp.id_pendiente,
	    sds.id_estado,
	    sds.estado,
	    strp.observacion,
	    strp.obs_respuesta,
	    strf.aprobado as f_aprobado,
	    strf.observacion as f_observacion
	from
	    s_trm_em_cabecera as stre
	        left join
	    s_trm_pendiente as strp ON (strp.id_emision = stre.id_emision)
	        left join
	    s_estado as sds ON (sds.id_estado = strp.id_estado)
	        left join
	    s_trm_facultativo as strf ON (strf.id_emision = stre.id_emision)
	where
	    stre.id_emision = "'.$ide.'"
	;';
	//echo $sql;
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