<?php
require('sibas-db.class.php');
require('session.class.php');
$session = new Session();
$token = $session->check_session();
$arrTH = array(0 => 0, 1 => 'R', 2 => 'Error: No se pudo procesar la Cotización');

$link = new SibasDB();

if($token === FALSE){
	if(($_ROOT = $link->get_id_root()) !== FALSE) {
		$_SESSION['idUser'] = base64_encode($_ROOT['idUser']);
	} else {
		$arrTH[0] = 1;
		$arrTH[1] = 'logout.php';
		$arrTH[2] = 'La Cotización no puede ser registrada, intentelo mas tarde';
	}
}

if (isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['pr'])) {
	$pr = $link->real_escape_string(trim(base64_decode($_POST['pr'])));
	
	if($pr === 'TH|01'){
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
		
		$idc = uniqid('@S#5$2013',true);
		
		$sql = 'insert into s_th_cot_cabecera 
		(`id_cotizacion`, `no_cotizacion`, `id_ef`, `certificado_provisional`, 
		`fecha_creacion`, `id_usuario`)
		values
		("'.$idc.'", 0, "'.base64_decode($_SESSION['idEF']).'", 
		'.$cp.', curdate(), "'.base64_decode($_SESSION['idUser']).'") ;';
		
		if($link->query($sql) === TRUE){
			$arrTH[0] = 1;
			$arrTH[1] = 'th-quote.php?ms='.$ms.'&page='.$page.'&pr='.base64_encode('TH|01').'&idc='.base64_encode($idc);
			$arrTH[2] = 'La Cotización fue registrada con exito';
		}else{
			
		}
		$link->close();
	}else{
		$arrTH[2] = 'La Cotización no puede ser registrada';
	}
	echo json_encode($arrTH);
}else{
	echo json_encode($arrTH);
}
?>