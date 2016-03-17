<?php
require('session.class.php');
$session = new Session();
$token = $session->check_session();
$arrAU = array(0 => 0, 1 => 'R', 2 => 'Error');

if($token === TRUE){
require('sibas-db.class.php');
$link = new SibasDB();
	if(isset($_POST['flag']) && isset($_POST['de-ide']) && isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['pr']) && isset($_POST['cia'])){
		if($_POST['pr'] === base64_encode('AU|05')){
			$ID = $link->real_escape_string(trim(base64_decode($_POST['de-ide'])));
			if(empty($ID) === FALSE){
				$sql = 'UPDATE s_au_em_cabecera as sae
						INNER JOIN s_entidad_financiera as sef ON (sef.id_ef = sae.id_ef)
					SET sae.emitir = TRUE, sae.fecha_emision = curdate(), sae.aprobado = TRUE, sae.leido = FALSE
					WHERE sae.id_emision = "'.$ID.'" 
						AND sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
						AND sef.activado = TRUE ;';
				if($link->query($sql) === TRUE){
					$arrAU[0] = 1;
					$arrAU[1] = 'certificate-policy.php?ms='.$_POST['ms'].'&page='.$_POST['page'].'&pr='.base64_encode('AU').'&ide='.base64_encode($ID);
					$arrAU[2] = 'LA PÓLIZA FUE EMITIDA CON EXITO !!!';
				}else {
					$arrAU[2] = 'La Póliza no pudo ser Emitida';
				}
			} else {
				$arrAU[2] = 'La Póliza no puede ser Emitida';
			}
		}else{
			$arrAU[2] = 'Error: La Póliza no puede ser Emitida';
		}
	}else{
		$arrAU[2] = 'Error: La Póliza no puede ser Emitida |';
	}
	echo json_encode($arrAU);
}else{
	echo json_encode($arrAU);
}
?>