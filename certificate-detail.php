<?php
header("Expires: Thu, 27 Mar 1980 23:59:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require('classes/certificate-sibas.class.php');
if ((isset($_GET['ide']) || isset($_GET['idc'])) && isset($_GET['type']) && isset($_GET['pr'])) {
	$category = $ide = $idc = $idcia = $extra = NULL;
	$popup = false;

	if (isset($_GET['popup'])) {
		if ($_GET['popup'] === md5('true')) {
			$popup = true;
		}
	}
	
	if (isset($_GET['ide'])) {
		$ide = base64_decode($_GET['ide']);
		$category = 'CE';
	} elseif ($_GET['idc']) {
		$idc = base64_decode($_GET['idc']);
		$category = 'SC';
	}
	
	if (isset($_GET['cia'])) {
		$idcia = base64_decode($_GET['cia']);
	}
	
	if (isset($_GET['category'])) {
		$category = base64_decode($_GET['category']);
	}
	
	if (isset($_GET['pe'])) {
		$extra = base64_decode($_GET['pe']);
	}
	
	$type = base64_decode($_GET['type']);
	$product = base64_decode($_GET['pr']);
	
	$cs = new CertificateSibas($ide, $idc, $idcia, $product, $type, $category, 1, 0, FALSE);
	if (isset($_GET['emails'])) {
		$vec = array();
		$arr_emails = array();
		$vec = explode(",", $_GET['emails']);
		
		foreach ($vec as $vemail) {
			if($vemail != '') {
				$arr_emails[] = array('address' => $vemail, 'name' => $vemail);
			}
		}
		$cs->address=$arr_emails;
	} else {
		if ($type==='MAIL') {
			echo 'Error al enviar el Correo Eléctronico';
		}
	}
	
	$cs->extra = $extra;
	
	if ($type === 'PRINT' && $popup === true) {
		echo '<meta charset="utf-8">';
	}
	
	if ($cs->Output() === true) {
		if ($type === 'MAIL') {
			echo 1;
		}
	} else {
		if ($type === 'MAIL') {
			echo 0;
		} else {
			//echo 'No se pudo obtener el certificado';	
		}
    }
} else {
	echo 'Usted no tiene permisos para visualizar el Detalle';
}
?>