<?php
require('fac-de-email.class.php');

$arrDE = array(0 => 0, 1 => 'R', 2 => '');

if(isset($_GET['fp-ide']) && isset($_GET['fp-obs'])){
	$smail = new FACEmailDE(TRUE);
	$link = $smail->cx;
	
	$bc = false;
	$idd = null;
	$err_data = false;

	$ide = $link->real_escape_string(trim(base64_decode($_GET['fp-ide'])));
	$resp = $link->real_escape_string(trim($_GET['fp-obs']));


	if (isset($_GET['fp-idd'])) {
		$bc = true;
		$idd = $link->real_escape_string(trim(base64_decode($_GET['fp-idd'])));
	}
	
	if (empty($resp) === true) {
		$err_data = true;
		$resp = 'El Formulario ya fue Modificado de acuerdo a las observaciones realizadas';
	}

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
	$_OB = preg_replace($patrones, $sus, $_TEXT);
	
	if ($bc === false) {
		$sql = 'UPDATE s_de_pendiente as sdp
			INNER JOIN 
		s_de_em_cabecera as sde ON (sde.id_emision = sdp.id_emision)
		SET 
			sdp.respuesta = TRUE, 
			sdp.obs_respuesta = "'.$_OB.'", 
			sdp.fecha_respuesta = curdate(), 
			sde.leido = FALSE
		WHERE sde.id_emision = "'.$ide.'"
		;';
	} else {
		$arr_p = array();

		$sqlSearch = 'select 
			sdd.detalle_p
		from 
			s_de_em_cabecera as sde
				inner join
			s_de_em_detalle as sdd ON (sdd.id_emision = sde.id_emision)
		where sde.id_emision = "' . $ide . '"
			and sdd.id_detalle = "' . $idd . '"
		;';

		if (($rs = $link->query($sqlSearch, MYSQLI_STORE_RESULT)) !== false) {
			if ($rs->num_rows === 1) {
				$row = $rs->fetch_array(MYSQLI_ASSOC);
				$rs->free();

				$arr_p = json_decode($row['detalle_p'], true);

				if (count($arr_p) > 0) {
					$arr_p['respuesta'] = $_OB;
					$arr_p['fecha_respuesta'] = date('d/m/Y');
				}

				$sql = "update 
					s_de_em_detalle as sdd
				set sdd.detalle_p = '" . json_encode($arr_p) . "'
				where sdd.id_emision = '" . $ide . "'
					and sdd.id_detalle = '" . $idd . "'
				;";
			}
		}
	}
	
	if($link->query($sql) === true){
		$arrDE[0] = 1;
		$arrDE[2] = 'La Respuesta fue registrada con exito';

		if ($err_data === true) {
			$arrDE[1] = 'index.php';
		}
		
		if($smail->send_mail_fac($ide, '', 2, $bc, $idd) === true){
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