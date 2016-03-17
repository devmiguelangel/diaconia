<?php
header("Expires: Tue, 01 Jan 2000 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require('DE-reports-prop.class.php');
//require('AU-reports-general.class.php');
//require('TRD-reports-general.class.php');
//require('TRM-reports-general.class.php');

if(isset($_GET['data-pr']) && isset($_GET['flag'])){

	$xls = FALSE;
	if(isset($_GET['xls'])) {
		if($_GET['xls'] === md5('TRUE')) {
			$xls = TRUE;
		}
	}

	$arrData = array();

	$arrData['idef'] = $_GET['idef'];
	$arrData['r-nc'] = $_GET['frp-nc'];
	$arrData['r-prefix'] = '';
	if (isset($_GET['frp-prefix'])) {
		$arrData['r-prefix'] = $_GET['frp-prefix'];
	}
	$arrData['r-user'] = '';
	if (isset($_GET['frp-user'])) {
		$arrData['r-user'] = $_GET['frp-user'];
	}
	$arrData['r-subsidiary'] = '';
	if (isset($_GET['frp-subsidiary'])) {
		$arrData['r-subsidiary'] = $_GET['frp-subsidiary'];
	}
	$arrData['r-agency'] = '';
	if (isset($_GET['frp-agency'])) {
		$arrData['r-agency'] = $_GET['frp-agency'];
	}

	$arrData['r-client'] = $_GET['frp-client'];
	$arrData['r-dni'] = $_GET['frp-dni'];
	$arrData['r-comp'] = $_GET['frp-comp'];
	$arrData['r-ext'] = $_GET['frp-ext'];
	$arrData['r-date-b'] = $_GET['frp-date-b'];
	$arrData['r-date-e'] = $_GET['frp-date-e'];
	if(empty($arrData['r-date-b']) === TRUE) {
		$arrData['r-date-b'] = '2000-01-01';
	}
	if(empty($arrData['r-date-e']) === TRUE) {
		$arrData['r-date-e'] = '2100-01-01';
	}

	$arrData['r-policy'] = '';
	if(isset($_GET['frp-policy'])) { $arrData['r-policy'] .= $_GET['frp-policy']; }

	$arrData['r-idUser'] = $_GET['frp-id-user'];

	$arrData['r-approved'] = $approved_p = '';
	if ($xls === FALSE) {
		if(isset($_GET['frp-approved-p'])) { $approved_p .= $_GET['frp-approved-p']; }
		if($approved_p === '') {
			$arrData['r-approved'] = '[AR]';
		} else {
			if ($approved_p == 1) { $arrData['r-approved'] = '[A]'; }
			elseif ($approved_p == 0) { $arrData['r-approved'] = '[R]'; }
		}
	} else {
		if (isset($_GET['frp-approved-p'])) { $arrData['r-approved'] = $_GET['frp-approved-p']; }
	}

	$arrData['r-pendant'] = '';
	if($xls === FALSE){
		if(isset($_GET['frp-pe'])) { $arrData['r-pendant'] .= $_GET['frp-pe']; }
		if(isset($_GET['frp-sp'])) { $arrData['r-pendant'] .= $_GET['frp-sp']; }
		if(isset($_GET['frp-ob'])) { $arrData['r-pendant'] .= $_GET['frp-ob']; }
		if(empty($arrData['r-pendant']) === TRUE) { $arrData['r-pendant'] = '.'; }
		else { $arrData['r-pendant'] = '['.$arrData['r-pendant'].']'; }
	}else{
		if(isset($_GET['frp-pendant'])) $arrData['r-pendant'] .= $_GET['frp-pendant'];
	}

	$arrData['r-state'] = '';
	if($xls === FALSE){
		if(isset($_GET['frp-estado-1'])) { $arrData['r-state'] .= $_GET['frp-estado-1']; }
		if(isset($_GET['frp-estado-2'])) { $arrData['r-state'] .= $_GET['frp-estado-2']; }
		if(isset($_GET['frp-estado-3'])) { $arrData['r-state'] .= $_GET['frp-estado-3']; }
		if(isset($_GET['frp-estado-4'])) { $arrData['r-state'] .= $_GET['frp-estado-4']; }

		if(isset($_GET['frp-estado-5'])) { $arrData['r-state'] .= $_GET['frp-estado-5']; }
		if(isset($_GET['frp-estado-6'])) { $arrData['r-state'] .= $_GET['frp-estado-6']; }
		if(isset($_GET['frp-estado-7'])) { $arrData['r-state'] .= $_GET['frp-estado-7']; }
		if(isset($_GET['frp-estado-8'])) { $arrData['r-state'] .= $_GET['frp-estado-8']; }
		if(isset($_GET['frp-estado-9'])) { $arrData['r-state'] .= $_GET['frp-estado-9']; }

		if(isset($_GET['frp-estado-10'])) { $arrData['r-state'] .= $_GET['frp-estado-10']; }
		if(isset($_GET['frp-estado-11'])) { $arrData['r-state'] .= $_GET['frp-estado-11']; }
		if(isset($_GET['frp-estado-12'])) { $arrData['r-state'] .= $_GET['frp-estado-12']; }
		if(isset($_GET['frp-estado-13'])) { $arrData['r-state'] .= $_GET['frp-estado-13']; }
		if(isset($_GET['frp-estado-14'])) { $arrData['r-state'] .= $_GET['frp-estado-14']; }

		if(empty($arrData['r-state']) === TRUE) { $arrData['r-state'] = '.'; }
		else { $arrData['r-state'] = '['.$arrData['r-state'].']'; }
	}else{
		if(isset($_GET['frp-state'])) { $arrData['r-state'] = $_GET['frp-state']; }
	}

	$arrData['r-free-cover'] = '';
	if($xls === FALSE){
		if(isset($_GET['frp-approved-fc'])) { $arrData['r-free-cover'] .= $_GET['frp-approved-fc']; }
		if(isset($_GET['frp-approved-nf'])) { $arrData['r-free-cover'] .= $_GET['frp-approved-nf']; }
		if(empty($arrData['r-free-cover']) === TRUE) { $arrData['r-free-cover'] = '.'; }
		else { $arrData['r-free-cover'] = '['.$arrData['r-free-cover'].']{2}'; }
	}else{
		if(isset($_GET['frp-free-cover'])) { $arrData['r-free-cover'] = $_GET['frp-free-cover']; }
	}

	$arrData['r-extra-premium'] = '';
	if($xls === FALSE){
		if(isset($_GET['frp-approved-ep'])) { $arrData['r-extra-premium'] .= $_GET['frp-approved-ep']; }
		if(isset($_GET['frp-approved-np'])) { $arrData['r-extra-premium'] .= $_GET['frp-approved-np']; }
		if(empty($arrData['r-extra-premium']) === TRUE) { $arrData['r-extra-premium'] = '.'; }
		else { $arrData['r-extra-premium'] = '['.$arrData['r-extra-premium'].']{2}'; }
	}else{
		if(isset($_GET['frp-extra-premium'])) { $arrData['r-extra-premium'] = $_GET['frp-extra-premium']; }
	}

	$arrData['r-issued'] = '';
	if($xls === FALSE){
		if(isset($_GET['frp-approved-em'])) { $arrData['r-issued'] .= $_GET['frp-approved-em']; }
		if(isset($_GET['frp-approved-ne'])) { $arrData['r-issued'] .= $_GET['frp-approved-ne']; }
		if(empty($arrData['r-issued']) === TRUE) { $arrData['r-issued'] = '.'; }
		else { $arrData['r-issued'] = '['.$arrData['r-issued'].']{2}'; }
	}else{
		if(isset($_GET['frp-issued'])) { $arrData['r-issued'] = $_GET['frp-issued']; }
	}

	$arrData['r-rejected'] = '';
	if($xls === FALSE){
		if(isset($_GET['frp-rejected'])) { $arrData['r-rejected'] .= $_GET['frp-rejected']; }
		if(empty($arrData['r-rejected']) === TRUE) { $arrData['r-rejected'] = '.'; }
		else { $arrData['r-rejected'] = '['.$arrData['r-rejected'].']{2}'; }
	}else{
		if(isset($_GET['frp-rejected'])) { $arrData['r-rejected'] = $_GET['frp-rejected']; }
	}

	$arrData['r-canceled'] = '';
	if($xls === FALSE){
		if(isset($_GET['frp-canceled'])) { $arrData['r-canceled'] .= $_GET['frp-canceled']; }
		if(empty($arrData['r-canceled']) === TRUE) { $arrData['r-canceled'] = '.'; }
		else { $arrData['r-canceled'] = '['.$arrData['r-canceled'].']{2}'; }
		if(isset($_GET['frp-canceled-p'])) { $arrData['r-canceled'] = $_GET['frp-canceled-p']; }	//Polizas
	}else{
		if(isset($_GET['frp-canceled'])) { $arrData['r-canceled'] = $_GET['frp-canceled'];}
	}

	//echo $arrData['r-canceled'];

	$arrData['ms'] = $_GET['ms'];
	$arrData['page'] = $_GET['page'];

	$pr = $_GET['data-pr'];
	$flag = $_GET['flag'];

	switch(base64_decode($pr)){
		case 'DE':

			$rpde = new ReportsPropDE($arrData, $pr, $flag, $xls);

			if ($rpde->err === FALSE) {
				$rpde->set_result();
			} else {
				echo 'No se puede obtener el reporte';
			}
			break;
		case 'AU':
			$rpau = new ReportsGeneralAU($arrData, $pr, $flag, $xls);

			if ($rpau->err === FALSE) {
				$rpau->set_result();
			} else {
				echo 'No se puede obtener el reporte';
			}
			break;
		case 'TRD':
			$rptrd = new ReportsGeneralTRD($arrData, $pr, $flag, $xls);

			if ($rptrd->err === FALSE) {
				$rptrd->set_result();
			} else {
				echo 'No se puede obtener el reporte';
			}
			break;
		case 'TRM':
			$rptrm = new ReportsGeneralTRM($arrData, $pr, $flag, $xls);

			if ($rptrm->err === FALSE) {
				$rptrm->set_result();
			} else {
				echo 'No se puede obtener el reporte';
			}
			break;
	}
}
?>