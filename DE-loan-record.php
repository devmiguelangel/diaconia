<?php
require('sibas-db.class.php');
require('session.class.php');
$session = new Session();
$session->getSessionCookie();
$token = $session->check_session();

$link = new SibasDB();

$arrDE = array(0 => 0, 1 => 'R', 2 => 'Error: No se pudo procesar la Cotización');

if($token === false){
	if(($_ROOT = $link->get_id_root()) !== false) {
		$_SESSION['idUser'] = base64_encode($_ROOT['idUser']);
	} else {
		$arrDE[0] = 1;
		$arrDE[1] = 'logout.php';
		$arrDE[2] = 'La Cotización no puede ser registrada, intentelo mas tarde';
	}
}

if(isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['pr'])){
	$pr = $link->real_escape_string(trim(base64_decode($_POST['pr'])));

	if($pr === 'DE|01'){
		$ms = $link->real_escape_string(trim($_POST['ms']));
		$page = $link->real_escape_string(trim($_POST['page']));
		$tc = $link->get_rate_exchange();
		$idc = NULL;
		$cp = NULL;
		$sql = '';
	
		if (isset($_POST['idc'])) {
			$idc = $link->real_escape_string(trim(base64_decode($_POST['idc'])));
		}
	
		if (isset($_POST['fq-cp'])) {
			$cp = $link->real_escape_string(trim($_POST['fq-cp']));
			if ($cp === md5(1)) {
				$cp = 1;
			} elseif ($cp === md5(0)) {
				$cp = 0;
			}
		}
	
		if ($cp !== NULL) {
			$idc = uniqid('@S#1$2013',true);
			$record = $link->getRegistrationNumber($_SESSION['idEF'], 'DE', 0);
			
			$sql = 'insert into s_de_cot_cabecera
			(`id_cotizacion`, `no_cotizacion`, `id_ef`, `certificado_provisional`, 
			`id_prcia`, `id_tc`, `fecha_creacion`, `id_usuario`, `act_usuario`, 
			`fecha_actualizacion`)
			values
			("'.$idc.'", '.$record.', "'.base64_decode($_SESSION['idEF']).'", 
			'.$cp.', 1, '.$tc.', curdate(), "'.base64_decode($_SESSION['idUser']).'", 
			"'.base64_decode($_SESSION['idUser']).'", curdate()) 
			;';
			
			if ($link->query($sql) === true) {
				$arrDE[0] = 1;
				$arrDE[1] = 'de-quote.php?ms='.$ms.'&page='.$page.'&pr='.base64_encode('DE|01').'&idc='.base64_encode($idc);
				$arrDE[2] = 'La Cotización fue registrada con exito !';
			} else{
				$arrDE[2] = 'No se pudo registrar la Cotización !';
			}
		} else{
			$dl_coverage = $link->real_escape_string(trim($_POST['dl-coverage']));
			$dl_amount = $link->real_escape_string(trim($_POST['dl-amount']));
			$dl_currency = $link->real_escape_string(trim($_POST['dl-currency']));
			$dl_term = $link->real_escape_string(trim($_POST['dl-term']));
			$dl_type_term = $link->real_escape_string(trim($_POST['dl-type-term']));
			$dl_product = 1;
			if (isset($_POST['dl-product'])) {
				$dl_product = $link->real_escape_string(trim($_POST['dl-product']));
			}
			$dl_modality = 'null';
			if (isset($_POST['dl-modality'])) {
				$value = $link->real_escape_string(trim($_POST['dl-modality']));
				$modality = explode('|', $value);
				$dl_modality = '"' . base64_decode($modality[0]) . '"';
			}
			
			$ca = $link->check_amount($_SESSION['idEF'], $dl_amount, $dl_currency);
			
			if ($ca[0] === true) {
				if ($idc === NULL) {
					$idc = uniqid('@S#1$2013',true);
					$record = $link->getRegistrationNumber($_SESSION['idEF'], 'DE', 0);
					
					$sql = 'insert into s_de_cot_cabecera 
					(`id_cotizacion`, `no_cotizacion`, `id_ef`, 
					`certificado_provisional`, `cobertura`, `id_prcia`, 
					`monto`, `moneda`, `plazo`, `tipo_plazo`, `id_tc`, 
					`fecha_creacion`, `id_usuario`, `act_usuario`, 
					`fecha_actualizacion`, `modalidad`)
					values
					("'.$idc.'", '.$record.', "'.base64_decode($_SESSION['idEF']).'", 
					false, '.$dl_coverage.', '.$dl_product.', '.$dl_amount.', 
					"'.$dl_currency.'", '.$dl_term.', "'.$dl_type_term.'", 
					'.$tc.', curdate(), "'.base64_decode($_SESSION['idUser']).'", 
					"'.base64_decode($_SESSION['idUser']).'", curdate(), 
					'.$dl_modality.' );';
				} else {
					$sql = 'update s_de_cot_cabecera
					set `cobertura` = '.$dl_coverage.', `id_prcia` = '.$dl_product.', 
					`monto` = '.$dl_amount.', `moneda` = "'.$dl_currency.'", `plazo` = '.$dl_term.', 
					`tipo_plazo` = "'.$dl_type_term.'", `id_tc` = '.$tc.', 
					`modalidad` = '.$dl_modality.'
					where id_cotizacion = "'.$idc.'" and id_ef = "'.base64_decode($_SESSION['idEF']).'"
					;';
				}
				
				if ($link->query($sql) === TRUE) {
					$arrDE[0] = 1;
					$arrDE[1] = 'de-quote.php?ms='.$ms.'&page='.$page.'&pr='.base64_encode('DE|02').'&idc='.base64_encode($idc);
					$arrDE[2] = 'La Cotización fue registrada con exito';
				} else {
					$arrDE[2] = 'No se pudo registrar la Cotización. !';
				}
			} else {
				$arrDE[2] = 'El monto no debe sobrepasar los '.number_format($ca[1],2,'.',',').' '.$dl_currency;
			}
		}
	
		$link->close();
	
		echo json_encode($arrDE);
	} else {
		echo json_encode($arrDE);
	}
} else {
	echo json_encode($arrDE);
}
?>