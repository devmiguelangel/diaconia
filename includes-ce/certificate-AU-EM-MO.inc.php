<?php
function au_em_certificate_mo($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
	$type = $link->typeProperty;
	$use = $link->useProperty;
	$state = $link->stateProperty;
	
	$nType = count($type);
	$nUse = count($use);
	$nState = count($state);
	
	$respType = array();	$respUse = array();	$respState = array();
	
	ob_start();
	if ($rsDt->data_seek(0) === true) {
		if ((boolean)$row['cl_tipo'] === true) {
			$row['cl_nombre'] = $row['cl_razon_social'];
		} else {
			$row['cl_nombre'] = $row['nombre'] . ' ' . $row['paterno']. ' ' . $row['materno'];
		}
		
		while ($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)) {
			$prefix = json_decode($rowDt['prefix'], true);
			
			switch ($prefix['prefix']) {
			case 'AUPRM':
				au_em_certificate_mo_auprm($link, $row, $rowDt, $url, $implant, $fac, $reason);
				break;
			case 'AUPBM':
				au_em_certificate_mo_aupbm($link, $row, $rowDt, $url, $implant, $fac, $reason);
				break;
			case 'AUPRU':
				au_em_certificate_mo_aupru($link, $row, $rowDt, $url, $implant, $fac, $reason);
				break;
			case 'AUPBU':
				au_em_certificate_mo_aupbu($link, $row, $rowDt, $url, $implant, $fac, $reason);
				break;
			case 'AULU':
				au_em_certificate_mo_aulu($link, $row, $rowDt, $url, $implant, $fac, $reason);
				break;
			}
		}
	}
	$html = ob_get_clean();
	return $html;
}
?>