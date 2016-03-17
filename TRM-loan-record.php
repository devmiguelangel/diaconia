<?php
require('sibas-db.class.php');
require('session.class.php');
$session = new Session();
$token = $session->check_session();
$arrTRM = array(0 => 0, 1 => 'R', 2 => 'Error: No se pudo procesar la Cotización');

$link = new SibasDB();

if($token === FALSE){
	if(($_ROOT = $link->get_id_root()) !== FALSE) {
		$_SESSION['idUser'] = base64_encode($_ROOT['idUser']);
	} else {
		$arrTRM[0] = 1;
		$arrTRM[1] = 'logout.php';
		$arrTRM[2] = 'La Cotización no puede ser registrada, intentelo mas tarde';
	}
}

if(isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['pr'])){
	$pr = $link->real_escape_string(trim(base64_decode($_POST['pr'])));
	
	if($pr === 'TRM|01'){
		$ms = $link->real_escape_string(trim($_POST['ms']));
		$page = $link->real_escape_string(trim($_POST['page']));
		
		$cp = 0;
		
		if (isset($_POST['fq-cp'])) {
			$cp = $link->real_escape_string(trim($_POST['fq-cp']));
			if($cp === md5(1)) {
				$cp = 1;
			} elseif($cp === md5(0)) {
				$cp = 0;
			}
		}
		
		$idc = uniqid('@S#4$2013',true);
		$record = $link->getRegistrationNumber($_SESSION['idEF'], 'TRM', 0);
		
		$sql = 'insert into s_trm_cot_cabecera 
		(`id_cotizacion`, `no_cotizacion`, `id_ef`, `certificado_provisional`, 
		`fecha_creacion`, `id_usuario`)
		values
		("'.$idc.'", '.$record.', "'.base64_decode($_SESSION['idEF']).'", 
		'.$cp.', curdate(), "'.base64_decode($_SESSION['idUser']).'")';
		
		if($link->query($sql) === TRUE){
			$arrTRM[0] = 1;
			$arrTRM[1] = 'trm-quote.php?ms='.$ms.'&page='.$page.'&pr='.base64_encode('TRM|01').'&idc='.base64_encode($idc);
			$arrTRM[2] = 'La Cotización fue registrada con exito';
		}else{
			
		}
		$link->close();
	}else{
		$arrTRM[2] = 'La Cotización no puede ser registrada';
	}
	echo json_encode($arrTRM);
}else{
	echo json_encode($arrTRM);
}
?>