<?php
require('fac-au-email.class.php');

$arrAU = array(0 => 0, 1 => 'R', 2 => '');
if(isset($_GET['fp-ide']) && isset($_GET['fp-idvh']) && isset($_GET['fp-approved']) && isset($_GET['fp-rate']) && isset($_GET['fp-final-rate']) && isset($_GET['fp-state']) && isset($_GET['fp-observation']) && isset($_GET['ms']) && isset($_GET['page'])){
	$smail = new FACEmailAU();
	$link = $smail->cx;
	$swF = FALSE;
	$ide = $link->real_escape_string(trim(base64_decode($_GET['fp-ide'])));
	$idVh = $link->real_escape_string(trim(base64_decode($_GET['fp-idvh'])));
	$user = $link->real_escape_string(trim(base64_decode($_GET['fp-user'])));
	$pr_approved = $link->real_escape_string(trim($_GET['fp-approved']));
	$pr_rate = $link->real_escape_string(trim($_GET['fp-rate']));
	$pr_percentage = $link->real_escape_string(trim($_GET['fp-percentage']));
	$pr_current_rate = $link->real_escape_string(trim($_GET['fp-current-rate']));
	$pr_final_rate = $link->real_escape_string(trim($_GET['fp-final-rate']));
	$pr_state = $link->real_escape_string(trim($_GET['fp-state']));
	$state = explode('|', $pr_state);
	$pr_observation = $link->real_escape_string(trim($_GET['fp-observation']));
	$pr_rate_curr = $link->real_escape_string(trim($_GET['fp-rate-curr']));
	$pr_email = $link->real_escape_string(trim($_GET['fp-email']));
	
	$_PR = 0;
	$_CR = 0;
	$_FR = 0;
	$_OB = '';
	$_AP = 0;
	$_TEXT = $pr_observation;
	$sqlF = '';	$sqlP = '';
	
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
	
	switch($pr_approved){
		case 'SI':
			$_AP = 1;
			switch($pr_rate){
				case 'SI':
					$_PR = $pr_percentage;	$_CR = $pr_current_rate;	$_FR = $pr_final_rate;
					break;
				case 'NO':
					$_CR = $pr_rate_curr;	$_FR = $pr_rate_curr;
					break;
			}
			break;
		case 'NO':
			$_FR = $pr_rate_curr;
			break;
		case 'PE':
			switch($state[1]){
				case 'EM':
					$_OB = '';
					break;
				default:
					break;
			}
			break;
	}
	
	if($pr_approved !== 'PE'){
		$idF = uniqid('@S#2$2013',true);
	
		$sqlF = 'INSERT INTO s_au_facultativo 
		(`id_facultativo`, `id_emision`, `id_vehiculo`, `aprobado`, `tasa_recargo`, `porcentaje_recargo`, `tasa_actual`, `tasa_final`, `observacion`, `pro_usuario`, `fecha_creacion`, `recordatorio`, `fecha_recordatorio`) 
		VALUES 
		("'.$idF.'", "'.$ide.'", "'.$idVh.'", "'.$pr_approved.'", "'.$pr_rate.'", '.$_PR.', '.$_CR.', '.$_FR.', "'.$_OB.'", 
			"'.$user.'", curdate(), 0, "");';
		
		if($link->query($sqlF) === TRUE)
			$swF = TRUE;
		else
			$swF = FALSE;
	}elseif($pr_approved === 'PE'){
		$sqlSearch = 'SELECT id_pendiente, id_emision, id_vehiculo, COUNT(id_emision) as token
				FROM s_au_pendiente
			WHERE id_emision = "'.$ide.'" and id_vehiculo = "'.$idVh.'" ;';
		
		if(($rsSearch = $link->query($sqlSearch,MYSQLI_STORE_RESULT))){
			$rowSearch = $rsSearch->fetch_array(MYSQLI_ASSOC);
			$rsSearch->free_result();
			if((int)$rowSearch['token'] === 0){
				$idP = uniqid('@S#2$2013',true);
				$sqlP = 'INSERT INTO s_au_pendiente 
					(`id_pendiente`, `id_emision`, `id_vehiculo`, `id_estado`, `observacion`, `pro_usuario`, `fecha_creacion`, `respuesta`, `obs_respuesta`, `fecha_respuesta`)
					VALUES
					("'.$idP.'", "'.$ide.'", "'.$idVh.'", '.$state[0].', "'.$_OB.'", "'.$user.'", curdate(), 0, "", "") ;';
			}elseif((int)$rowSearch['token'] > 0){
				$sqlP = 'UPDATE s_au_pendiente 
					SET `id_estado` = '.$pr_state[0].', `observacion` = "'.$_OB.'", `pro_usuario` = "'.$user.'", 
						`fecha_creacion` = curdate(), `respuesta` = FALSE
					WHERE 
						id_pendiente = "'.$rowSearch['id_pendiente'].'" 
							AND id_emision = "'.$rowSearch['id_emision'].'"
							AND id_vehiculo = "'.$rowSearch['id_vehiculo'].'" ;';
			}
		}
		
		if($link->query($sqlP) === TRUE){
			$swF = TRUE;
		}else
			$swF = FALSE;
	}
	
	if($swF === TRUE){
		$sqlm = 'UPDATE s_au_em_cabecera as sae
				INNER JOIN s_au_em_detalle as sad ON (sad.id_emision = sae.id_emision)
			SET sae.leido = FALSE, sad.leido = FALSE, sad.aprobado = '.$_AP.' 
			WHERE sae.id_emision = "'.$ide.'" AND sad.id_vehiculo = "'.$idVh.'" ;';
		
		if($link->query($sqlm) === TRUE){
			$arrAU[0] = 1;
			$arrAU[1] = 'index.php?ms='.$_GET['ms'].'&page='.$_GET['page'].'&fwd='.md5('forwardFAC');
			$arrAU[2] = 'El caso facultativo se proceso correctamente';
			if($smail->send_mail_fac($ide, $idVh, $pr_email) === TRUE){
				$arrAU[2] .= '<br>y se envió el Correo Electronico';
			}else{
				$arrAU[2] .= '<br>pero no se envió el Correo Electronico';
			}
		}else {
			$arrAU[2] = 'El caso facultativo se proceso correctamente pero no se marco como leído';
		}
	}else {
		$arrAU[2] = 'No se pudo procesar el caso facultativo';
	}
	
	echo json_encode($arrAU);
}else{
	echo json_encode($arrAU);
}
?>