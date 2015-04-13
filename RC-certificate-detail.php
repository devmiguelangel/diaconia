<?php
header("Expires: Thu, 27 Mar 1980 23:59:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require('classes/RC-certificate-sibas.class.php');

if ((isset($_GET['idRc']) && isset($_GET['type']) )) {
	$category = $idrc = NULL;
	$popup = false;

	if (isset($_GET['popup'])) {
		if ($_GET['popup'] === md5('true')) {
			$popup = true;
		}
	}

	$idrc = base64_decode($_GET['idRc']);
	$type = base64_decode($_GET['type']);
	$category = base64_decode($_GET['category']);


	$cs = new CertificateSibas($idrc, $type, $category, 1);
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
		//if ($type==='MAIL') {
		if ($type==='MAIL' || $type==='ATCH') {
			echo 'Error al enviar el Correo Eléctronico';
		}
	}

	//$cs->extra = $extra;

	if ($type === 'PRINT' && $popup === true) {
		echo '<meta charset="utf-8">';
	}

	if ($cs->Output() === true) {
		if ($type === 'MAIL' || $type==='ATCH') {
			echo 1;
		}
	} else {
		if ($type === 'MAIL' || $type==='ATCH') {
			echo 0;
		} else {
			//echo 'No se pudo obtener el certificado';
		}
    }
} else {
	echo 'Usted no tiene permisos para visualizar el Detalle';
}
?>