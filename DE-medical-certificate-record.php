<?php
require('sibas-db.class.php');

$arrDE = array(0 => 0, 1 => 'R', 2 => 'err');
if((isset($_POST['fc-cm-1']) || isset($_POST['fc-cm-2'])) && isset($_POST['ide']) && (isset($_POST['fc-idCl-1']) || isset($_POST['fc-idCl-2'])) && isset($_POST['fp-idCm'])){
	$link = new SibasDB();
	$ide = $link->real_escape_string(trim(base64_decode($_POST['ide'])));
	$idCm = $link->real_escape_string(trim(base64_decode($_POST['fp-idCm'])));
	$cm1 = $cm2 = $k = $j = 0;
	
	$token = FALSE;
	if(isset($_POST['token']))
		if($_POST['token'] === md5('editor'))
			$token = TRUE;
	
	
	if(isset($_POST['fc-cm-1'])){
		$cm1 = (int)$link->real_escape_string(trim($_POST['fc-cm-1']));
		$k = 1;
	}
	if(isset($_POST['fc-cm-2'])){
		$cm2 = (int)$link->real_escape_string(trim($_POST['fc-cm-2']));
		$k = 2;
	}
	
	if($token === FALSE){	
		if($k > 0){
			$arrCM = array();
			for($j = 1; $j <= $k; $j++){
				if($cm1 === 1)
					set_response_cm($arrCM,$link,$j);
				else
					$j += 1;
				
				if($cm2 === 1)
					set_response_cm($arrCM,$link,$j);
				else
					$j += 1;
			}
			
			if(count($arrCM) > 0){
				$sql = '';
				$kk = 0;
				for($j = 1; $j <= count($arrCM); $j++){
					if(count($arrCM) === 1){
						if($cm1 === 1 && $cm2 === 0)
							$kk = 1;
						elseif($cm1 === 0 && $cm2 === 1)
							$kk = 2;
					}else{
						$kk = $j;
					}
					
					if(($rowSr = search_response_cm($ide, $arrCM[$kk]['cm-idCl'], $link)) !== 0){
						$sql .= "UPDATE s_cm_respuesta SET
							`id_cm` = ".$idCm.", `centro_atencion` = '".$arrCM[$kk]['cm-center-attention']."', 
							`persona_contacto` = '".$arrCM[$kk]['cm-contact-person']."', 
							`respuesta` = '".$arrCM[$kk]['cm-data']."', `otros` = '".$arrCM[$kk]['cm-obs']."', 
							`fecha_creacion` = curdate()
							WHERE `id_respuesta` = '".$rowSr['id_respuesta']."' AND 
								`id_emision` = '".$rowSr['id_emision']."' AND `id_cliente` = '".$rowSr['id_cliente']."' ;";
						
					}else{
						$idr = uniqid('@S#1$2013'.$j,true);
						$sql .= "INSERT INTO s_cm_respuesta 
							(`id_respuesta`, `id_emision`, `id_cliente`, `id_cm`, `centro_atencion`, `persona_contacto`, `respuesta`, `otros`, `fecha_creacion`)
							VALUES 
							('".$idr."', '".$ide."', '".$arrCM[$kk]['cm-idCl']."', ".$idCm.", '".$arrCM[$kk]['cm-center-attention']."', '".$arrCM[$kk]['cm-contact-person']."', '".$arrCM[$kk]['cm-data']."', '".$arrCM[$kk]['cm-obs']."', curdate())
							;";
					}
				}
				
				//echo $sql;
				if($link->multi_query($sql) === TRUE){
					$swCm = FALSE;
					do{
						if($link->errno !== 0)
							$swCm = TRUE;
					}while($link->next_result());
				}else{
					$swCm = TRUE;
				}
				if($swCm === FALSE){
					$arrDE[0] = 1;
					$arrDE[1] = 'CM';
					$arrDE[2] = 'El Certificado Medico se registro correctamente';
				}else
					$arrDE[2] = 'Error. No se pudo registrar el Certificado Médico';
			}else
				$arrDE[2] = 'No existe un Certificado Médico Asociado |';
		}else
			$arrDE[2] = 'No existe un Certificado Médico Asociado';
	}else{
		if($k > 0){
			$idc = $link->real_escape_string(trim(base64_decode($_POST['fc-idCl-1'])));
			$_TEXT = $link->real_escape_string(trim($_POST['fc-observation']));
			
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
			
			$sql = '';
			
			if(($rowSr = search_response_cm($ide, $idc, $link)) !== 0){
				$sql = "UPDATE s_cm_respuesta SET
					`id_cm` = ".$idCm.", `centro_atencion` = '', `persona_contacto` = '', `respuesta` = '', 
					`otros` = '".$_OB."', `fecha_creacion` = curdate()
					WHERE `id_respuesta` = '".$rowSr['id_respuesta']."' AND 
						`id_emision` = '".$rowSr['id_emision']."' AND `id_cliente` = '".$rowSr['id_cliente']."' ;";
			}else{
				$idr = uniqid('@S#1$2013',true);
				$sql = "INSERT INTO s_cm_respuesta 
					(`id_respuesta`, `id_emision`, `id_cliente`, `id_cm`, `centro_atencion`, `persona_contacto`, `respuesta`, `otros`, `fecha_creacion`)
					VALUES 
					('".$idr."', '".$ide."', '".$idc."', ".$idCm.", '', '', '', '".$_OB."', curdate()) ;";
			}
			
			if($link->query($sql, MYSQLI_STORE_RESULT) === TRUE){
				$arrDE[0] = 1;
				$arrDE[1] = 'CM';
				$arrDE[2] = 'La Orden Médica se agrego correctamente';
			}else
				$arrDE[2] = 'Erro. No se pudo registrar la Declaración Jurada de Salud';
		}else
			$arrDE[2] = 'No existe una Declaración Jurada de Salud Asociado';
	}
	echo json_encode($arrDE);
}else{
	echo json_encode($arrDE);
}

function set_response_cm(&$arrCM, $link, $j){
	$arrCM[$j]['cm-idCl'] = $link->real_escape_string(trim(base64_decode($_POST['fc-idCl-'.$j])));
	$arrCM[$j]['cm-ci'] = $link->real_escape_string(trim($_POST['fc-ci-'.$j]));
	$arrCM[$j]['cm-address'] = $link->real_escape_string(trim($_POST['fc-address-'.$j]));
	$arrCM[$j]['cm-regional'] = $link->real_escape_string(trim($_POST['fc-regional-'.$j]));
	$arrCM[$j]['cm-phone'] = $link->real_escape_string(trim($_POST['fc-phone-'.$j]));
	$arrCM[$j]['cm-center-attention'] = $link->real_escape_string(trim($_POST['fc-center-attention-'.$j]));
	$arrCM[$j]['cm-contact-person'] = $link->real_escape_string(trim($_POST['fc-contact-person-'.$j]));
	
	$arrCM[$j]['cm-data'] = stripslashes($link->real_escape_string(trim($_POST['data-0'.$j])));
	$arrCM[$j]['cm-obs'] = stripslashes($link->real_escape_string(trim($_POST['data-obs-0'.$j])));
}

function search_response_cm($ide, $idc, $link){
	$sqlSr = 'SELECT id_respuesta, id_emision, id_cliente FROM s_cm_respuesta 
		WHERE id_emision = "'.$ide.'" and id_cliente = "'.$idc.'" ORDER BY id_respuesta ASC ;';
		
	$rsSr = $link->query($sqlSr,MYSQLI_STORE_RESULT);
	
	if($rsSr->num_rows === 1){
		$rowSr = $rsSr->fetch_array(MYSQLI_ASSOC);
		$rsSr->free();
		return $rowSr;
	}elseif($rsSr->num_rows === 0){
		return 0;
	}
}
?>