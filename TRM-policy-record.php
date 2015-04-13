<?php
require('session.class.php');
$session = new Session();
$token = $session->check_session();
$arrTR = array(0 => 0, 1 => 'R', 2 => 'Error');

if($token === TRUE){
require('sibas-db.class.php');
$link = new SibasDB();
	if(isset($_POST['flag']) && isset($_POST['de-ide']) && isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['pr']) && isset($_POST['cia'])){
		if($_POST['pr'] === base64_encode('TRM|05')){
			$ID = $link->real_escape_string(trim(base64_decode($_POST['de-ide'])));
			if(empty($ID) === FALSE){
				$sql = 'UPDATE s_trm_em_cabecera as stre
						INNER JOIN s_entidad_financiera as sef ON (sef.id_ef = stre.id_ef)
					SET stre.emitir = TRUE, stre.fecha_emision = curdate(), stre.aprobado = TRUE, stre.leido = FALSE
					WHERE stre.id_emision = "'.$ID.'" 
						AND sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
						AND sef.activado = TRUE ;';
				if($link->query($sql) === TRUE){
					$arrTR[0] = 1;
					$arrTR[1] = 'certificate-policy.php?ms='.$_POST['ms'].'&page='.$_POST['page'].'&pr='.base64_encode('TRM').'&ide='.base64_encode($ID);
					$arrTR[2] = 'LA PÓLIZA FUE EMITIDA CON EXITO !!!';
				}else {
					$arrTR[2] = 'La Póliza no pudo ser Emitida';
				}
			} else {
				$arrTR[2] = 'La Póliza no puede ser Emitida';
			}
		}else{
			$arrTR[2] = 'Error: La Póliza no puede ser Emitida';
		}
	}else{
		$arrTR[2] = 'Error: La Póliza no puede ser Emitida |';
	}
	echo json_encode($arrTR);
}else{
	echo json_encode($arrTR);
}
?>