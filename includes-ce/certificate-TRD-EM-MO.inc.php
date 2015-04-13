<?php
function trd_em_certificate_mo($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
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
			$row['cl_nombre'] = $row['cl_nombre'] . ' ' . $row['cl_paterno']. ' ' . $row['cl_materno'];
		}
		while ($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)) {
			$prefix = json_decode($rowDt['prefix'], true);
			
			switch ($prefix['prefix']) {
			case 'TRDM':
				trd_em_certificate_mo_trdm($link, $row, $rowDt, $url, $implant, $fac, $reason);
				break;
			case 'TRDU':
				trd_em_certificate_mo_trdu($link, $row, $rowDt, $url, $implant, $fac, $reason);
				break;
			case 'INCM':
				trd_em_certificate_mo_incm($link, $row, $rowDt, $url, $implant, $fac, $reason);
				break;
			case 'INCU':
				trd_em_certificate_mo_incu($link, $row, $rowDt, $url, $implant, $fac, $reason);
				break;
			case 'CAR':
				trd_em_certificate_mo_car($link, $row, $rowDt, $url, $implant, $fac, $reason);
				break;
			}
		}
	}
	$html = ob_get_clean();
	return $html;
}
?>