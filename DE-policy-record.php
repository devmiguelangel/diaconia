<?php
require('session.class.php');
$session = new Session();
$session->getSessionCookie();
$token = $session->check_session();
$arrDE = array(0 => 0, 1 => 'R', 2 => 'Error');

if($token === TRUE){
require('sibas-db.class.php');
$link = new SibasDB();
	if(isset($_POST['flag']) && isset($_POST['de-ide']) && isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['pr']) && isset($_POST['cia'])){
		if($_POST['pr'] === base64_encode('DE|05')){
			$ID = $link->real_escape_string(trim(base64_decode($_POST['de-ide'])));
			if(empty($ID) === FALSE){
				$sql = 'UPDATE s_de_em_cabecera as sde
						INNER JOIN s_entidad_financiera as sef ON (sef.id_ef = sde.id_ef)
					SET sde.emitir = TRUE, sde.fecha_emision = curdate(), sde.aprobado = TRUE, sde.leido = FALSE
					WHERE sde.id_emision = "'.$ID.'" 
						AND sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
						AND sef.activado = TRUE ;';
				if($link->query($sql) === TRUE){
					$arrDE[0] = 1;
					$arrDE[1] = 'certificate-policy.php?ms='.$_POST['ms'].'&page='.$_POST['page'].'&pr='.base64_encode('DE').'&ide='.base64_encode($ID);
					$arrDE[2] = 'LA PÓLIZA FUE EMITIDA CON EXITO !!!';
				} else {
					$arrDE[2] = 'La Póliza no pudo ser Emitida';
				}
			} else {
				$arrDE[2] = 'La Póliza no puede ser Emitida';
			}
		}else{
			$arrDE[2] = 'Error: La Póliza no puede ser Emitida';
		}
	}else{
		$arrDE[2] = 'Error: La Póliza no puede ser Emitida |';
	}
	echo json_encode($arrDE);
}else{
	echo json_encode($arrDE);
}
?>