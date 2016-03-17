<?php
require('fac-au-email.class.php');

$arrDE = array(0 => 0, 1 => 'R', 2 => '');
if(isset($_GET['fp-ide']) && isset($_GET['fp-idVh']) && isset($_GET['fp-obs'])){
	$smail = new FACEmailAU(TRUE);
	$link = $smail->cx;
	
	$ide = $link->real_escape_string(trim(base64_decode($_GET['fp-ide'])));
	$idVh = $link->real_escape_string(trim(base64_decode($_GET['fp-idVh'])));
	$resp = $link->real_escape_string(trim($_GET['fp-obs']));
	
	$_TEXT = $resp;
	$patrones = array('@<script[^>]*?>.*?</script>@si',  	// Strip out javascript
			'@<colgroup[^>]*?>.*?</colgroup>@si',			// Strip out HTML tags
			'@<style[^>]*?>.*?</style>@siU',				// Strip style tags properly
			'@<style[^>]*>.*</style>@siU',					// Strip style
			'@<![\s\S]*?--[ \t\n\r]*>@siU',					// Strip multi-line comments including CDATA,
			'@width:[^>].*;@siU',							// Strip width
			'@width="[^>].*"@siU',							// Strip width style
			'@height="[^>].*"@siU',							// Strip height
			'@class="[^>].*"@siU',							// Strip class
			'@border="[^>].*"@siU',							// Strip border
			'@font-family:[^>].*;@siU'						// Strip fonts
	);
	
	$sus = array('','','','','','width: 500px;','width="500"','','','','font-family: Helvetica, sans-serif, Arial;');
	$_OB = preg_replace($patrones,$sus,$_TEXT);
	
	$sql = 'UPDATE s_au_pendiente as sap
			INNER JOIN s_au_em_cabecera as sae ON (sae.id_emision = sap.id_emision)
			INNER JOIN s_au_em_detalle as sad ON (sad.id_vehiculo = sap.id_vehiculo)
		SET sap.respuesta = TRUE, sap.obs_respuesta = "'.$_OB.'", sap.fecha_respuesta = curdate(), 
			sae.leido = FALSE, sad.leido = FALSE
		WHERE sae.id_emision = "'.$ide.'"
			and sad.id_vehiculo = "'.$idVh.'"
		;';
	
	if($link->query($sql) === TRUE){
		$arrDE[0] = 1;
		$arrDE[2] = 'La Respuesta fue registrada con exito';
		
		if($smail->send_mail_fac($ide, $idVh) === TRUE){
			$arrDE[2] .= '<br>y se envió el Correo Electronico';
		}else{
			$arrDE[2] .= '<br>pero no se envió el Correo Electronico';
		}
	}else{
		$arrDE[2] = 'No se pudo registrar la Respuesta';
	}
	echo json_encode($arrDE);
}else{
	echo json_encode($arrDE);
}
?>