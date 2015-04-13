<?php
require('session.class.php');
date_default_timezone_set('America/La_Paz');

$session = new Session();
$session->getSessionCookie();
$token = $session->check_session();
$arrDE = array(0 => 0, 1 => 'R', 2 => '');

if($token === TRUE){
require('sibas-db.class.php');
$link = new SibasDB();

if (isset($_POST['flag']) 
	&& (isset($_POST['de-idc']) 
		|| isset($_POST['de-ide'])) 
	&& isset($_POST['ms']) 
	&& isset($_POST['page']) 
	&& isset($_POST['pr']) 
	&& isset($_POST['cia']) 
	&& isset($_POST['prcia'])) {

	if($_POST['pr'] === base64_encode('DE|05')){
		$sw = 0;
        $data = array();
        $client = array();
		
		$ms = $link->real_escape_string(trim($_POST['ms']));
		$page = $link->real_escape_string(trim($_POST['page']));
		$pr = $link->real_escape_string(trim($_POST['pr']));
		
		$max_item = $amount_max_bs = $amount_max_usd = 0;

		if (($rowDE = $link->get_max_amount_optional($_SESSION['idEF'], 'DE')) !== FALSE) {
			$max_item = (int)$rowDE['max_item'];
			$amount_max_bs = (float)$rowDE['monto_max_bs'];
			$amount_max_usd = (float)$rowDE['monto_max_usd'];
		}
		
		$target = '';
		if(isset($_POST['target'])) {
			$target = '&target='.$link->real_escape_string(trim($_POST['target']));
		}

		$idd = '';
		if(isset($_POST['idd'])) {
			$idd = '&idd='.$link->real_escape_string(trim($_POST['idd']));
		}
		
		$flag = $_POST['flag'];
		
		switch($flag){
			case md5('i-new'):		//POLIZA NUEVA
				$sw = 1;
				break;
			case md5('i-read'):		//
				$sw = 2;
				break;
			case md5('i-edit'):		//POLIZA ACTUALIAZADA
				$sw = 3;
				break;
		}

		if($sw !== 0){
			$arr_cl = array();
			$nCl = 0;
			if (isset($_POST['ncl-data'])) {
				$nCl = $link->real_escape_string(trim(base64_decode($_POST['ncl-data'])));
			}
			/*if(isset($_POST['dc-1-idcl'])) {		//TITULAR 1
				$nCl = 1;
			}
			if(isset($_POST['dc-2-idcl'])) {		//TITULAR 2
				$nCl = 2;
			}*/
			
			if($nCl > 0 && $nCl <= $max_item){		// VERIFICAR SI EXISTEN CLIENTES
				$swDE = false;
				$swMo = false;
				$bc = false;
				$birth_flag = true;
				$birth_mess = '';
				
				$dcr_cia = $link->real_escape_string(trim(base64_decode($_POST['cia'])));
				$dcr_prcia = $link->real_escape_string(trim($_POST['prcia']));
				$dcr_modality = 'null';
				if (isset($_POST['dcr-modality'])) {
					$dcr_modality = '"' 
						. $link->real_escape_string(trim(base64_decode($_POST['dcr-modality']))) . '"';
					$swMo = true;
				}
				$dcr_coverage = $link->real_escape_string(trim($_POST['dcr-coverage']));
				$dcr_amount = $link->real_escape_string(trim($_POST['dcr-amount']));
				$dcr_currency = $link->real_escape_string(trim($_POST['dcr-currency']));
				$dcr_term = $link->real_escape_string(trim($_POST['dcr-term']));
				$dcr_type_term = $link->real_escape_string(trim($_POST['dcr-type-term']));
				$dcr_type_mov = '';
				if (isset($_POST['dcr-type-mov'])) {
					$dcr_type_mov = $link->real_escape_string(trim($_POST['dcr-type-mov']));
				}
				$dcr_opp = $link->real_escape_string(trim($_POST['dcr-opp']));
				$dcr_policy = 'null';
				if (isset($_POST['dcr-policy'])) {
					$dcr_policy = '"' . $link->real_escape_string(trim(base64_decode($_POST['dcr-policy']))) . '"';
				}
				$dcr_amount_de = '';
				//$dcr_amount_de = $link->real_escape_string(trim($_POST['dcr-amount-de']));
				if(empty($dcr_amount_de) === TRUE) {
					$dcr_amount_de = 0;
				}
				//$dcr_amount_acc = $link->real_escape_string(trim($_POST['dcr-amount-acc']));
				$dcr_amount_acc = 0;

				$dcr_amount_cc = 0;
				$dcr_amount_acc_2 = 0;
				/*if(isset($_POST['dcr-amount-cc'])){
					$dcr_amount_cc = $link->real_escape_string(trim($_POST['dcr-amount-cc']));
					if(empty($dcr_amount_cc) === TRUE) {
						$dcr_amount_cc = 0;
					}
					$dcr_amount_acc_2 = $link->real_escape_string(trim($_POST['dcr-amount-acc-2']));
				}*/
				
				$cp = NULL;
				if($sw === 1 && isset($_POST['cp'])) {
					$cp = (int)$link->real_escape_string(trim(base64_decode($_POST['cp'])));
				}

				if ((int)$dcr_coverage === 2) {
					$bc = true;
				}

				$sqlAge = 'select 
					count(ssh.id_home) as token,
					ssh.edad_max,
					ssh.edad_min
				from
					s_sgc_home as ssh
						inner join 
					s_entidad_financiera as sef ON (sef.id_ef = ssh.id_ef)
				where
					ssh.producto = "DE"
						and sef.id_ef = "' . base64_decode($_SESSION['idEF']) . '"
						and sef.activado = true
				;';
				
				$rsAge = $link->query($sqlAge,MYSQLI_STORE_RESULT);
				$rowAge = $rsAge->fetch_array(MYSQLI_ASSOC);
				$rsAge->free();

				if ($bc === true) {
					$rowAge['edad_max'] = 75;
				}
				
				$cont = 0;
				while($cont < $nCl){
					$cont += 1;
					
					$arr_cl[$cont]['cl-id'] = $link->real_escape_string(trim(base64_decode($_POST['dc-'.$cont.'-idcl'])));
					$arr_cl[$cont]['cl-name'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-name']));
					$arr_cl[$cont]['cl-patern'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-ln-patern']));
					$arr_cl[$cont]['cl-matern'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-ln-matern']));
					$arr_cl[$cont]['cl-married'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-ln-married']));
					$arr_cl[$cont]['cl-gender'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-gender']));
					$arr_cl[$cont]['cl-status'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-status']));
					$arr_cl[$cont]['cl-type-doc'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-type-doc']));
					$arr_cl[$cont]['cl-doc-id'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-doc-id']));
					$arr_cl[$cont]['cl-comp'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-comp']));
					$arr_cl[$cont]['cl-ext'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-ext']));
					$arr_cl[$cont]['cl-date'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-date-birth']));
					$arr_cl[$cont]['cl-age'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-age']));
					$arr_cl[$cont]['cl-hand'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-hand']));
					$arr_cl[$cont]['cl-country'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-country']));
					$arr_cl[$cont]['cl-place-birth'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-place-birth']));
					$arr_cl[$cont]['cl-place-res'] = 'null';
					$aux_place_res = $_POST['dc-'.$cont.'-place-res'];
					if (empty($aux_place_res) === false) {
						$arr_cl[$cont]['cl-place-res'] = $link->real_escape_string(trim($aux_place_res));
					}
					$arr_cl[$cont]['cl-locality'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-locality']));
					$arr_cl[$cont]['cl-avc'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-avc']));
					$arr_cl[$cont]['cl-address-home'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-address-home']));
					$arr_cl[$cont]['cl-nhome'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-nhome']));
					$arr_cl[$cont]['cl-phone-1'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-phone-1']));
					$arr_cl[$cont]['cl-phone-2'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-phone-2']));
					$arr_cl[$cont]['cl-email'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-email']));
					$arr_cl[$cont]['cl-occupation'] = 'null';
					$aux_occupation = $_POST['dc-'.$cont.'-occupation'];
					if (empty($aux_occupation) === false) {
						$arr_cl[$cont]['cl-occupation'] = 
							'"' . $link->real_escape_string(trim(base64_decode($aux_occupation))) . '"';
					}
					$arr_cl[$cont]['cl-desc-occ'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-desc-occ']));
					$arr_cl[$cont]['cl-address-work'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-address-work']));
					$arr_cl[$cont]['cl-phone-office'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-phone-office']));
					$arr_cl[$cont]['cl-weight'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-weight']));
					$arr_cl[$cont]['cl-height'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-height']));
					$arr_cl[$cont]['cl-amount-bc'] = 0;
					if (isset($_POST['dc-'.$cont.'-amount-bc'])) {
						$arr_cl[$cont]['cl-amount-bc'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-amount-bc']));
					}
					$arr_cl[$cont]['cl-tasa'] = 0;
					if (isset($_POST['dc-'.$cont.'-tasa'])) {
						$arr_cl[$cont]['cl-tasa'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-tasa']));
					}

					$arr_cl[$cont]['cl-share'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-share']));
					$arr_cl[$cont]['cl-residue'] = 
						$link->real_escape_string(trim($_POST['dcr-amount-de-' . $cont]));
					$arr_cl[$cont]['cl-accumulation'] = 
						$link->real_escape_string(trim($_POST['dcr-amount-ac-' . $cont]));
					$arr_cl[$cont]['cl-fac'] = false;
					$arr_cl[$cont]['cl-fac-reason'] = '';
					$arr_cl[$cont]['cl-approved'] = true;

					$arr_cl[$cont]['cl-titular'] = $link->real_escape_string(trim($_POST['dc-'.$cont.'-titular']));

                    $client[$cont]['cl_nombre'] = $arr_cl[$cont]['cl-name'];
                    $client[$cont]['cl_paterno'] = $arr_cl[$cont]['cl-patern'];
                    $client[$cont]['cl_materno'] = $arr_cl[$cont]['cl-matern'];
                    $client[$cont]['cl_ci'] = $arr_cl[$cont]['cl-doc-id'];
                    $client[$cont]['cl_extension'] = $arr_cl[$cont]['cl-ext'];

                    if ($link->verifyYearUser($rowAge['edad_min'], 
						$rowAge['edad_max'], $arr_cl[$cont]['cl-date']) === false) {

						$name = $client[$cont]['cl_nombre'] . ' ' 
							. $client[$cont]['cl_paterno'] . ' ' 
							. $client[$cont]['cl_materno'];

						$birth_flag = false;
						$birth_mess .= 'La Fecha de nacimiento del titular ' . $name 
							. ' no esta en el rango permitido de edades <br>';
					}
					
					// Beneficiarios de Sepelio
					/*if (isset($_POST['dsp-'.$cont.'-idb'])) {
						$arr_cl[$cont]['cl-sp-idb'] = $link->real_escape_string(trim(base64_decode($_POST['dsp-'.$cont.'-idb'])));
					} else {
						$arr_cl[$cont]['cl-sp-idb'] = uniqid('@S#1$2013'.$cont, true);
					}
					$arr_cl[$cont]['cl-sp-name'] = $link->real_escape_string(trim($_POST['dsp-'.$cont.'-name']));
					$arr_cl[$cont]['cl-sp-patern'] = $link->real_escape_string(trim($_POST['dsp-'.$cont.'-ln-patern']));
					$arr_cl[$cont]['cl-sp-matern'] = $link->real_escape_string(trim($_POST['dsp-'.$cont.'-ln-matern']));
					$arr_cl[$cont]['cl-sp-relation'] = $link->real_escape_string(trim($_POST['dsp-'.$cont.'-relation']));
					$arr_cl[$cont]['cl-sp-doc-id'] = $link->real_escape_string(trim($_POST['dsp-'.$cont.'-doc-id']));
					$arr_cl[$cont]['cl-sp-ext'] = $link->real_escape_string(trim($_POST['dsp-'.$cont.'-ext']));
					$arr_cl[$cont]['cl-sp-cov'] = 'SP';
					if(empty($arr_cl[$cont]['cl-sp-ext']) === TRUE) {
						$arr_cl[$cont]['cl-sp-ext'] = 1;
					}*/
					
					// Beneficiarios de Vida Grupo
					/*if(($dcr_currency === 'BS' && $dcr_amount > 35000) || ($dcr_currency === 'USD' && $dcr_amount > 5000)){
						$swDE = true;
					}
					
					if ($swMo === false) {
						if (isset($_POST['dvg-'.$cont.'-idb'])) {
							$arr_cl[$cont]['cl-vg-idb'] = $link->real_escape_string(trim(base64_decode($_POST['dvg-'.$cont.'-idb'])));
						} else {
							$arr_cl[$cont]['cl-vg-idb'] = uniqid('@S#1$2013'.($cont + 1), true);
						}
						$arr_cl[$cont]['cl-vg-name'] = $link->real_escape_string(trim($_POST['dvg-'.$cont.'-name']));
						$arr_cl[$cont]['cl-vg-patern'] = $link->real_escape_string(trim($_POST['dvg-'.$cont.'-ln-patern']));
						$arr_cl[$cont]['cl-vg-matern'] = $link->real_escape_string(trim($_POST['dvg-'.$cont.'-ln-matern']));
						$arr_cl[$cont]['cl-vg-relation'] = $link->real_escape_string(trim($_POST['dvg-'.$cont.'-relation']));
						$arr_cl[$cont]['cl-vg-age'] = $link->real_escape_string(trim($_POST['dvg-'.$cont.'-age']));
						$arr_cl[$cont]['cl-vg-cov'] = 'VG';
					}*/
					
					/*if ($swMo === false) {
						// Beneficiarios Primarios
						if (isset($_POST['dpr-'.$cont.'-idb'])) {
							$arr_cl[$cont]['cl-pr-idb'] = $link->real_escape_string(trim(base64_decode($_POST['dpr-'.$cont.'-idb'])));
						} else {
							$arr_cl[$cont]['cl-pr-idb'] = uniqid('@S#1$2013'.($cont + 2), true);
						}
						$arr_cl[$cont]['cl-pr-name'] = $link->real_escape_string(trim($_POST['dpr-'.$cont.'-name']));
						$arr_cl[$cont]['cl-pr-patern'] = $link->real_escape_string(trim($_POST['dpr-'.$cont.'-ln-patern']));
						$arr_cl[$cont]['cl-pr-matern'] = $link->real_escape_string(trim($_POST['dpr-'.$cont.'-ln-matern']));
						$arr_cl[$cont]['cl-pr-relation'] = $link->real_escape_string(trim($_POST['dpr-'.$cont.'-relation']));
						$arr_cl[$cont]['cl-pr-doc-id'] = $link->real_escape_string(trim($_POST['dpr-'.$cont.'-doc-id']));
						$arr_cl[$cont]['cl-pr-ext'] = $link->real_escape_string(trim($_POST['dpr-'.$cont.'-ext']));
						$arr_cl[$cont]['cl-pr-cov'] = 'PR';
						if(empty($arr_cl[$cont]['cl-pr-ext']) === TRUE) {
							$arr_cl[$cont]['cl-pr-ext'] = 1;
						}
						
						// Beneficiarios Contingente
						if (isset($_POST['dco-'.$cont.'-idb'])) {
							$arr_cl[$cont]['cl-co-idb'] = $link->real_escape_string(trim(base64_decode($_POST['dco-'.$cont.'-idb'])));
						} else {
							$arr_cl[$cont]['cl-co-idb'] = uniqid('@S#1$2013'.($cont + 3), true);
						}
						$arr_cl[$cont]['cl-co-name'] = $link->real_escape_string(trim($_POST['dco-'.$cont.'-name']));
						$arr_cl[$cont]['cl-co-patern'] = $link->real_escape_string(trim($_POST['dco-'.$cont.'-ln-patern']));
						$arr_cl[$cont]['cl-co-matern'] = $link->real_escape_string(trim($_POST['dco-'.$cont.'-ln-matern']));
						$arr_cl[$cont]['cl-co-relation'] = $link->real_escape_string(trim($_POST['dco-'.$cont.'-relation']));
						$arr_cl[$cont]['cl-co-doc-id'] = $link->real_escape_string(trim($_POST['dco-'.$cont.'-doc-id']));
						$arr_cl[$cont]['cl-co-ext'] = $link->real_escape_string(trim($_POST['dco-'.$cont.'-ext']));
						$arr_cl[$cont]['cl-co-cov'] = 'CO';
						if(empty($arr_cl[$cont]['cl-co-ext']) === TRUE) {
							$arr_cl[$cont]['cl-co-ext'] = 1;
						}
					}*/
					
					$arr_cl[$cont]['cl-q-idd'] = $link->real_escape_string(trim(base64_decode($_POST['dq-'.$cont.'-idd'])));
					$arr_cl[$cont]['cl-d-idd'] = uniqid('@S#1$2013' . date('U'), true);
					$arr_cl[$cont]['cl-q-idr'] = $link->real_escape_string(trim(base64_decode($_POST['dq-'.$cont.'-idr'])));
					$arr_cl[$cont]['cl-q-fac'] = $link->real_escape_string(trim(base64_decode($_POST['dq-'.$cont.'-fac'])));
					$arr_cl[$cont]['cl-q-resp'] = $link->real_escape_string(trim($_POST['dq-'.$cont.'-resp']));
				}
				
				$tipo_pr = 0;
				$tipo_cm = $link->get_type_exch($_SESSION['idEF']);
				$tcm = $link->get_rate_exchange(true);
				$prefix = array();
				$arrPrefix = 'null';
				$FAC = false;
				$FAC_BC = false;
				$fac_reason = '';
				$fac_reason_bc = '';
				$cu_amount_mess = '';
				$IMC = false;
				$QS = false;
				$CU = false;
				
				$TASA = $link->get_tasa($dcr_cia, $dcr_prcia, $_SESSION['idEF']);
				$PRIMA = $link->get_prima($dcr_amount, $TASA);
				
				$fac_mess = '';
				$mess_aux = '';
				$cont = 0;

				while($cont < $nCl){
					$cont += 1;

					if($link->get_imc($arr_cl[$cont]['cl-weight'], $arr_cl[$cont]['cl-height']) === true){
						$IMC = TRUE;
						$mess_aux = ' | El Titular ' . $cont . ' no cumple con el IMC ';
						
						if ($bc === true && 
							(($dcr_currency === 'BS' && $dcr_amount > (15000 * $tcm)) 
								|| ($dcr_currency === 'USD' && $dcr_amount > 15000))) {
							$arr_cl[$cont]['cl-approved'] = false;
							$arr_cl[$cont]['cl-fac'] = true;
							$arr_cl[$cont]['cl-fac-reason'] .= $mess_aux;
						}

						$fac_mess .= $mess_aux;
					}

					if($arr_cl[$cont]['cl-q-fac'] > 1){
						$QS = TRUE;
						$mess_aux = ' | El Titular ' . $cont . ' no cumple con las Preguntas ';

						if ($bc === true && 
							(($dcr_currency === 'BS' && $dcr_amount > (15000 * $tcm)) 
								|| ($dcr_currency === 'USD' && $dcr_amount > 15000))) {
							$arr_cl[$cont]['cl-approved'] = false;
							$arr_cl[$cont]['cl-fac'] = true;
							$arr_cl[$cont]['cl-fac-reason'] .= $mess_aux;
						}

						$fac_mess .= $mess_aux;
					}

					$cu_amount = $link->verify_amount($arr_cl[$cont]['cl-accumulation'], 
						'BS', $amount_max_bs, $amount_max_usd);
					
					if ($cu_amount === true) {
						$CU = true;
						$mess_aux = '
							| El monto total acumulado del Titular ' . $cont . ' ' 
							. number_format($arr_cl[$cont]['cl-accumulation'], 2, '.', ',') . ' Bs. 
							supera el monto maximo permitido. Monto maximo permitido ' 
							. number_format($amount_max_bs, 2, '.', ',') . ' Bs. ';

						if ($bc === true) {
							$arr_cl[$cont]['cl-approved'] = false;
							$arr_cl[$cont]['cl-fac'] = true;
							$arr_cl[$cont]['cl-fac-reason'] .= $mess_aux;
						}

						$cu_amount_mess .= $mess_aux;
					}

					// CODE
					if ($bc === true) {
						switch ($dcr_currency) {
						case 'BS':
							if ($arr_cl[$cont]['cl-amount-bc'] > (15000 * $tcm) 
								&& $arr_cl[$cont]['cl-fac'] === true) {
								FacBS_bc:
								$FAC_BC = true;
								$fac_reason_bc .= $arr_cl[$cont]['cl-fac-reason'];
							} elseif ($cu_amount === true) {
								goto FacBS_bc;
							} else {
								$arr_cl[$cont]['cl-fac'] = false;
								$arr_cl[$cont]['cl-approved'] = true;
								$arr_cl[$cont]['cl-fac-reason'] = '';
							}
							break;
						case 'USD':
							if($arr_cl[$cont]['cl-amount-bc'] > 15000 
								&& $arr_cl[$cont]['cl-fac'] === true){
								FacUSD_bc:
								$FAC_BC = true;
								$fac_reason_bc .= $arr_cl[$cont]['cl-fac-reason'];
							} elseif ($cu_amount === true) {
								goto FacUSD_bc;
							} else {
								$arr_cl[$cont]['cl-fac'] = false;
								$arr_cl[$cont]['cl-approved'] = true;
								$arr_cl[$cont]['cl-fac-reason'] = '';
							}
							break;
						}
					}

				}
				
				if ($bc === false) {
					$cu_amount = $link->verify_amount($dcr_amount, $dcr_currency, $amount_max_bs, $amount_max_usd);
					/*
					$cu_amount_acc = $link->get_cumulus($dcr_amount_acc, 'BS', $_SESSION['idEF']);
					$cu_amount_acc_2 = $link->get_cumulus($dcr_amount_acc_2, 'BS', $_SESSION['idEF']);
					*/
					
					if ($cu_amount === true) {
						$CU = true;
						switch ($dcr_currency) {
							case 'BS':
								$cu_amount = $amount_max_bs;
								break;
							case 'USD':
								$cu_amount = $amount_max_usd;
								break;
						}

						$cu_amount_mess .= '
							| El monto solicitado supera el monto maximo permitido. 
							Monto maximo permitido '.number_format($cu_amount, 2, '.', ',') . ' ' . $dcr_currency . '. ';
						//$cu_amount_mess .= $cu_amount;
					}

					/*
					if(($cu_amount_acc > 1 || $cu_amount_acc === false)){
						$CU = true;
						$cu_amount_mess .= '
							| El monto total acumulado del Titular 1 ' 
							. number_format($dcr_amount_acc, 2, '.', ',') . ' Bs. 
							supera el monto maximo permitido. Monto maximo permitido ' 
							. number_format($cu_amount_acc, 2, '.', ',') . ' Bs. ';
					}
					if(($cu_amount_acc_2 > 1 || $cu_amount_acc_2 === false)){
						$CU = true;
						$cu_amount_mess .= '
							| El monto total acumulado del Titular 2 ' 
							. number_format($dcr_amount_acc_2, 2, '.', ',') . ' Bs. 
							supera el monto maximo permitido. Monto maximo permitido ' 
							. number_format($cu_amount_acc_2, 2, '.', ',') . ' Bs. ';
					}
					*/
					if($CU === true){
						$fac_reason .= $cu_amount_mess;
					}
				}
				
				
				if ($swMo === false) {
					/*if(($dcr_currency === 'BS' && $dcr_amount > (15000 * $tcm)) 
						|| ($dcr_currency === 'USD' && $dcr_amount > 15000)){
						$fac_reason .= $fac_mess;
					}*/
					$prefix[0] = 'DE';

					if ($bc === false) {
						switch ($dcr_currency) {
						case 'BS':
							if ($dcr_amount > (15000 * $tcm)) {
								if ($IMC === TRUE || $QS === TRUE || $CU === TRUE) {
									FacBS:
									$FAC = TRUE;
									$fac_reason .= $fac_mess;
								}
							} elseif ($CU === true) {
								goto FacBS;
							}
							break;
						case 'USD':
							if ($dcr_amount > 15000) {
								if ($IMC === TRUE || $QS === TRUE || $CU === TRUE) {
									FacUSD:
									$FAC = TRUE;
									$fac_reason .= $fac_mess;
								}
							} elseif ($CU === true) {
								goto FacUSD;
							}
							break;
						}

						/*if($CU === TRUE){
							$fac_reason .= $fac_mess;
							$FAC = TRUE;
						}*/
					} else {
						if ($FAC_BC === true) {
							$FAC = true;
							$fac_reason = $fac_reason_bc;
						}
					}
					
				} else {
					switch ($link->getTypeIssueIdeproDE($dcr_amount, $dcr_currency, $tcm)) {
					case 'DE':
						if ($IMC === true || $QS === true || $CU === true) {
							$FAC = true;
							$fac_reason .= $fac_mess;
						}
						break;
					case 'FA':
						if ($IMC === true || $QS === true || $CU === true) {
							$fac_reason .= $fac_mess;
						}
						$FAC = true;
						break;
					default:
						if ($CU === true) {
							$FAC = true;
							$fac_reason .= $fac_mess;
						}
						break;
					}
					
					$link->getPrefixPolicyIdeproDE($dcr_currency, trim($dcr_modality, '"'), $prefix);
					
					$arrPrefix = array (
						'policy' => $prefix[1],
						'prefix' => $prefix[0]
						);
					$arrPrefix = '"' . $link->real_escape_string(json_encode($arrPrefix)) . '"';
				}
				
				$fac_reason = preg_replace('/\s+/', ' ', $fac_reason);

                /* Data de la Poliza */
                $data['id_ef'] = $_SESSION['idEF'];
                $data['prefix'] = $prefix[0];
                $data['amount'] = $dcr_amount;
                $data['currency'] = $dcr_currency;
                $data['term'] = $dcr_term;
                $data['type_term'] = $dcr_type_term;
                /******************/

                $sqlC = '';
				$sqlCL = '';
				$sqlD = '';
				$sqlBN = '';
				$ID = '';

                $swPolicy = null;
                if ($sw === 1) {
                    $swPolicy = $link->checkPolicyFac($data, $client);
                }

                if ($birth_flag === true) {
					if($sw === 1 && $swPolicy === false){
						$idc = $link->real_escape_string(trim(base64_decode($_POST['de-idc'])));
						$ID = uniqid('@S#1$2013',true);
						$record = $link->getRegistrationNumber($_SESSION['idEF'], 'DE', 1, $prefix[0]);
						
						$sqlC = 'insert into s_de_em_cabecera 
						(`id_emision`, `no_emision`, `id_ef`, `id_cotizacion`, 
						`certificado_provisional`, `no_operacion`, `prefijo`, 
						`prefix`, `cobertura`, `id_prcia`, `modalidad`, 
						`monto_solicitado`, `moneda`, `monto_deudor`, 
						`monto_codeudor`, `cumulo_deudor`, `cumulo_codeudor`, 
						`id_tc`, `plazo`, `tipo_plazo`, `id_usuario`, 
						`fecha_creacion`, `hora_creacion`, `anulado`, `and_usuario`, `fecha_anulado`, 
						`motivo_anulado`, `emitir`, `fecha_emision`, `id_compania`, 
						`id_poliza`, `operacion`, `facultativo`, `motivo_facultativo`, 
						`tasa`, `prima_total`, `no_copia`, `no_copia_cob`, `leido`, 
						`id_certificado`) 
						values 
						("'.$ID.'", '.$record.', "'.base64_decode($_SESSION['idEF']).'", 
						"'.$idc.'", '.$cp.', "'.$dcr_opp.'", "'.$prefix[0].'", '.$arrPrefix.', 
						'.$dcr_coverage.', '.$dcr_prcia.', '.$dcr_modality.', 
						'.$dcr_amount.', "'.$dcr_currency.'", '.$dcr_amount_de.', 
						'.$dcr_amount_cc.', '.$dcr_amount_acc.', '.$dcr_amount_acc_2.', 
						'.$tipo_cm.', '.$dcr_term.', "'.$dcr_type_term.'", 
						"'.base64_decode($_SESSION['idUser']).'", "' . date('Y-m-d') . '", 
						"' . date('H:i:s') . '", FALSE, "'.base64_decode($_SESSION['idUser']).'", 
						"", "", FALSE, "", "'.$dcr_cia.'", '.$dcr_policy.', "'.$dcr_type_mov.'", 
						'.(int)$FAC.', "'.$fac_reason.'", '.$TASA.', '.$PRIMA.', 
						0, 0, FALSE, 1);';
						
						$sqlCL = '';
						
						$sqlD = 'INSERT INTO s_de_em_detalle 
						(`id_detalle`, `id_emision`, `id_cliente`, `porcentaje_credito`, 
							`tasa`, `monto_banca_comunal`, `saldo`, `cumulo`, 
							`facultativo`, `motivo_facultativo`, `aprobado`, `titular`) 
						VALUES ';
						/*	
						$sqlBN = 'INSERT INTO s_de_beneficiario 
							(`id_beneficiario`, `id_detalle`, `cobertura`, `paterno`, `materno`, `nombre`, `ci`, `id_depto`, `edad`, `parentesco`) VALUES ';
						*/
						$k = 0;
						while($k < $nCl){
							$k += 1;
							
							$flagCl = FALSE;
							$sqlSCl = 'select 
								scl.id_cliente as idCl, scl.ci as cl_ci, scl.extension as cl_extension
							from
								s_cliente as scl
									inner join s_entidad_financiera as sef ON (sef.id_ef = scl.id_ef)
							where
								scl.ci = "'.$arr_cl[$k]['cl-doc-id'].'" and scl.extension = '.$arr_cl[$k]['cl-ext'].' 
									and scl.tipo = 0 and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
							;';
							
							if(($rsSCl = $link->query($sqlSCl,MYSQLI_STORE_RESULT))){
								if($rsSCl->num_rows === 1){
									$rowSCl = $rsSCl->fetch_array(MYSQLI_ASSOC);
									$rsSCl->free();
									
									$sqlCL .= 'update s_cliente 
									set `paterno` = "'.$arr_cl[$k]['cl-patern'].'", `materno` = "'.$arr_cl[$k]['cl-matern'].'", 
										`nombre` = "'.$arr_cl[$k]['cl-name'].'", `ap_casada` = "'.$arr_cl[$k]['cl-married'].'", 
										`fecha_nacimiento` = "'.$arr_cl[$k]['cl-date'].'", 
										`lugar_nacimiento` = "'.$arr_cl[$k]['cl-place-birth'].'", 
										`extension` = '.$arr_cl[$k]['cl-ext'].', `complemento` = "'.$arr_cl[$k]['cl-comp'].'", 
										`tipo_documento` = "'.$arr_cl[$k]['cl-type-doc'].'", 
										`estado_civil` = "'.$arr_cl[$k]['cl-status'].'", 
										`lugar_residencia` = '.$arr_cl[$k]['cl-place-res'].', 
										`localidad` = "'.$arr_cl[$k]['cl-locality'].'", `avenida` = "'.$arr_cl[$k]['cl-avc'].'", 
										`direccion` = "'.$arr_cl[$k]['cl-address-home'].'", 
										`no_domicilio` = "'.$arr_cl[$k]['cl-nhome'].'", 
										`direccion_laboral` = "'.$arr_cl[$k]['cl-address-work'].'", 
										`pais` = "'.$arr_cl[$k]['cl-country'].'", `id_ocupacion` = '.$arr_cl[$k]['cl-occupation'].', 
										`desc_ocupacion` = "'.$arr_cl[$k]['cl-desc-occ'].'", 
										`telefono_domicilio` = "'.$arr_cl[$k]['cl-phone-1'].'", 
										`telefono_oficina` = "'.$arr_cl[$k]['cl-phone-office'].'", 
										`telefono_celular` = "'.$arr_cl[$k]['cl-phone-2'].'", 
										`email` = "'.$arr_cl[$k]['cl-email'].'", `genero` = "'.$arr_cl[$k]['cl-gender'].'", 
										`edad` = '.$arr_cl[$k]['cl-age'].', `mano` = "'.$arr_cl[$k]['cl-hand'].'", 
										`peso` = '.$arr_cl[$k]['cl-weight'].', `estatura` = '.$arr_cl[$k]['cl-height'].'
									WHERE id_cliente = "'.$rowSCl['idCl'].'" ;';
									
									$arr_cl[$k]['cl-id'] = $rowSCl['idCl'];
									$flagCl = TRUE;
								}
							}
							
							if($flagCl === FALSE){
								$sqlCL .= 'INSERT INTO s_cliente 
								(`id_cliente`, `id_ef`, `tipo`, `razon_social`, 
									`paterno`, `materno`, `nombre`, `ap_casada`, 
									`fecha_nacimiento`, `lugar_nacimiento`, `ci`, 
									`extension`, `complemento`, `tipo_documento`, 
									`estado_civil`, `ci_archivo`, `lugar_residencia`, 
									`localidad`, `avenida`, `direccion`, `no_domicilio`, 
									`direccion_laboral`, `pais`, `id_ocupacion`, 
									`desc_ocupacion`, `telefono_domicilio`, 
									`telefono_oficina`, `telefono_celular`, `email`, 
									`peso`, `estatura`, `genero`, `edad`, `mano`) 
							VALUES ("'.$arr_cl[$k]['cl-id'].'", "'.base64_decode($_SESSION['idEF']).'", 0, "", 
								"'.$arr_cl[$k]['cl-patern'].'", "'.$arr_cl[$k]['cl-matern'].'", "'.$arr_cl[$k]['cl-name'].'", 
								"'.$arr_cl[$k]['cl-married'].'", "'.$arr_cl[$k]['cl-date'].'", "'.$arr_cl[$k]['cl-place-birth'].'", 
								"'.$arr_cl[$k]['cl-doc-id'].'", '.$arr_cl[$k]['cl-ext'].', "'.$arr_cl[$k]['cl-comp'].'", 
								"'.$arr_cl[$k]['cl-type-doc'].'", "'.$arr_cl[$k]['cl-status'].'", "", '.$arr_cl[$k]['cl-place-res'].', 
								"'.$arr_cl[$k]['cl-locality'].'", "'.$arr_cl[$k]['cl-avc'].'", "'.$arr_cl[$k]['cl-address-home'].'", 
								"'.$arr_cl[$k]['cl-nhome'].'", "'.$arr_cl[$k]['cl-address-work'].'", "'.$arr_cl[$k]['cl-country'].'", 
								'.$arr_cl[$k]['cl-occupation'].', "'.$arr_cl[$k]['cl-desc-occ'].'", "'.$arr_cl[$k]['cl-phone-1'].'", 
								"'.$arr_cl[$k]['cl-phone-office'].'", "'.$arr_cl[$k]['cl-phone-2'].'", "'.$arr_cl[$k]['cl-email'].'", 
								'.$arr_cl[$k]['cl-weight'].', '.$arr_cl[$k]['cl-height'].', "'.$arr_cl[$k]['cl-gender'].'", 
								'.$arr_cl[$k]['cl-age'].', "'.$arr_cl[$k]['cl-hand'].'") ;';
							}

							if ($bc === false) {
								$arr_cl[$k]['cl-fac'] = $FAC;
							}
							
							$sqlD .= '("'.$arr_cl[$k]['cl-d-idd'].'", "'.$ID.'", "'.$arr_cl[$k]['cl-id'].'", 
								' . $arr_cl[$k]['cl-share'] . ', ' . $arr_cl[$k]['cl-tasa'] . ', 
								' . $arr_cl[$k]['cl-amount-bc'] . ', ' . $arr_cl[$k]['cl-residue'] . ', 
								' . $arr_cl[$k]['cl-accumulation'] . ', ' . (int)$arr_cl[$k]['cl-fac'] . ', 
								"' . $arr_cl[$k]['cl-fac-reason'] . '", ' . (int)$arr_cl[$k]['cl-approved'] . ', 
								"'.$arr_cl[$k]['cl-titular'].'"), ';
							/*
							$sqlBN .= '("'.$arr_cl[$k]['cl-sp-idb'].'", "'.$arr_cl[$k]['cl-d-idd'].'", 
								"'.$arr_cl[$k]['cl-sp-cov'].'", "'.$arr_cl[$k]['cl-sp-patern'].'", "'.$arr_cl[$k]['cl-sp-matern'].'", 
								"'.$arr_cl[$k]['cl-sp-name'].'", "'.$arr_cl[$k]['cl-sp-doc-id'].'", 
								'.$arr_cl[$k]['cl-sp-ext'].', 0, "'.$arr_cl[$k]['cl-sp-relation'].'"), ';
							
							if($swMo === false){
								$sqlBN .= '("'.$arr_cl[$k]['cl-vg-idb'].'", "'.$arr_cl[$k]['cl-d-idd'].'", 
									"'.$arr_cl[$cont]['cl-vg-cov'].'", "'.$arr_cl[$k]['cl-vg-patern'].'", 
									"'.$arr_cl[$k]['cl-vg-matern'].'", "'.$arr_cl[$k]['cl-vg-name'].'", 
									"", 1, "'.$arr_cl[$k]['cl-vg-age'].'", "'.$arr_cl[$k]['cl-vg-relation'].'"), ';
							}
							
							if ($swMo === false) {
								$sqlBN .= '("'.$arr_cl[$k]['cl-pr-idb'].'", "'.$arr_cl[$k]['cl-d-idd'].'", 
								"'.$arr_cl[$k]['cl-pr-cov'].'", "'.$arr_cl[$k]['cl-pr-patern'].'", 
								"'.$arr_cl[$k]['cl-pr-matern'].'", "'.$arr_cl[$k]['cl-pr-name'].'", 
								"'.$arr_cl[$k]['cl-pr-doc-id'].'", '.$arr_cl[$k]['cl-pr-ext'].', 0, 
								"'.$arr_cl[$k]['cl-pr-relation'].'"), ';
							
								$sqlBN .= '("'.$arr_cl[$k]['cl-co-idb'].'", "'.$arr_cl[$k]['cl-d-idd'].'", 
								"'.$arr_cl[$k]['cl-co-cov'].'", "'.$arr_cl[$k]['cl-co-patern'].'", 
								"'.$arr_cl[$k]['cl-co-matern'].'", "'.$arr_cl[$k]['cl-co-name'].'", 
								"'.$arr_cl[$k]['cl-co-doc-id'].'", '.$arr_cl[$k]['cl-co-ext'].', 0, 
								"'.$arr_cl[$k]['cl-co-relation'].'"), ';
							}
							*/
						}
						
						$sqlCL = trim(trim($sqlCL),',');
						$sqlD = trim(trim($sqlD),',');
						$sqlBN = trim(trim($sqlBN),',');
						$sqlCL .= ';';
						$sqlD .= ';';
						$sqlBN .= ';';
						// echo $sqlCL . '<br>' . $sqlD;
						if($link->query($sqlC) === TRUE){
							if($link->multi_query($sqlCL) === TRUE){
								$swCl = FALSE;
								do{
									if($link->errno !== 0)
										$swCl = TRUE;
								}while($link->next_result());
								
								if($swCl === FALSE){
									if($link->query($sqlD) === TRUE){
										//if($link->query($sqlBN) === TRUE){
										if(true){
											$sw_UQs = FALSE;
											$k = 0;
											while($k < $nCl){
												$k += 1;
												$sqlQs = 'INSERT INTO s_de_em_respuesta 
													(`id_respuesta`, `id_detalle`, `respuesta`, 
													`observacion`, `enfermedad`, `fecha_tratamiento`, 
													`duracion`, `tratante`, `estado`) 
													SELECT "'.uniqid('@S#1$2013'.$k, true).'", 
													"'.$arr_cl[$k]['cl-d-idd'].'", `respuesta`, 
													"'.$arr_cl[$k]['cl-q-resp'].'", `enfermedad`, 
													`fecha_tratamiento`, `duracion`, `tratante`, 
													`estado` FROM s_de_cot_respuesta 
													WHERE id_respuesta = "'.$arr_cl[$k]['cl-q-idr'].'" 
														AND id_detalle = "'.$arr_cl[$k]['cl-q-idd'].'" ;';
												
												//$sqlQs = 'UPDATE s_de_respuesta 
												//	SET `observacion` = "'.$arr_cl[$k]['cl-q-resp'].'"
												//	WHERE id_respuesta = '.$arr_cl[$k]['cl-q-idr'].' AND 
												//		id_detalle = "'.$arr_cl[$k]['cl-q-idd'].'" ;';
												//
												
												if($link->query($sqlQs) === TRUE) {
													$sw_UQs = TRUE;
												} else {
													$sw_UQs = FALSE;
												}
											}
											if($sw_UQs === TRUE){
												$arrDE[0] = 1;
												$arrDE[1] = 'de-quote.php?ms='.$ms.'&page='.$page.'&pr='.$pr.'&ide='.base64_encode($ID).'&flag='.md5('i-read').'&cia='.base64_encode($dcr_cia).'';
												$arrDE[2] = 'La Póliza fue registrada con exito';
											}else {
												$arrDE[2] = 'No se pudo actualizar las Observaciones de las respuestas';
											}
										}else {
											$arrDE[2] = 'No se pudo registrar a los Beneficiarios';
										}
									}else {
										$arrDE[2] = 'No se pudo registrar el Detalle';
									}
								}else {
									$arrDE[2] = 'No se pudo registrar a los Titulares.';
								}
							}else {
								$arrDE[2] = 'No se pudo registrar a los Titulares';
							}
						}else {
							$arrDE[2] = 'La Póliza no pudo ser registrada ';
						}
					} elseif($sw === 3){
						$ide = $link->real_escape_string(trim(base64_decode($_POST['de-ide'])));
						
						//////////////////////FACULTATIVO CUMULO/////////////////
						/*if(($dcr_currency === 'BS' && $dcr_amount > 35000) || ($dcr_currency === 'USD' && $dcr_amount > 5000)){
							if($CU === TRUE && ($IMC === FALSE && $QS === FALSE)) {
								$FAC === TRUE;
							} elseif($CU === FALSE && ($IMC === FALSE && $QS === FALSE)) {
								$FAC = FALSE;
							} elseif($CU === FALSE && ($IMC === TRUE || $QS === TRUE)) {
								$FAC = TRUE;
							}
						}else{
							if($CU === TRUE) {
								$FAC = TRUE;
							} elseif($CU === FALSE) {
								$FAC = FALSE;
							}
						}*/
						///////////////////////////////////////
						
						$sqlC = 'update s_de_em_cabecera 
						set `no_operacion` = "'.$dcr_opp.'", `id_prcia` = '.$dcr_prcia.', 
							`modalidad` = '.$dcr_modality.', `monto_solicitado` = '.$dcr_amount.', 
							`moneda` = "'.$dcr_currency.'", `monto_deudor` = '.$dcr_amount_de.', 
							`monto_codeudor` = '.$dcr_amount_cc.', `cumulo_deudor` = '.$dcr_amount_acc.', 
							`cumulo_codeudor` = '.$dcr_amount_acc_2.', `plazo` = '.$dcr_term.', 
							`tipo_plazo` = "'.$dcr_type_term.'", `id_poliza` = '.$dcr_policy.', 
							`operacion` = "'.$dcr_type_mov.'", `facultativo` = '.(int)$FAC.', 
							`motivo_facultativo` = "'.$fac_reason.'", `tasa` = '.$TASA.', 
							`prima_total` = '.$PRIMA.', `leido` = false, `id_certificado` = 1
						where id_emision = "'.$ide.'" ;';
						
						$sw_UCl = false;
						$sw_UBs = false;
						$sw_UQs = false;
						$sw_UBv = true;
						$sw_UBp = false;
						$sw_UBc = false;
						$sw_UDt = false;
						
						if($link->query($sqlC) === TRUE){
							$k = 0;
							while($k < $nCl){
								$k += 1;
								
								$sqlCL = 'UPDATE s_cliente 
									SET `paterno` = "'.$arr_cl[$k]['cl-patern'].'", `materno` = "'.$arr_cl[$k]['cl-matern'].'", 
										`nombre` = "'.$arr_cl[$k]['cl-name'].'", `ap_casada` = "'.$arr_cl[$k]['cl-married'].'", 
										`fecha_nacimiento` = "'.$arr_cl[$k]['cl-date'].'", 
										`lugar_nacimiento` = "'.$arr_cl[$k]['cl-place-birth'].'", 
										`extension` = '.$arr_cl[$k]['cl-ext'].', `complemento` = "'.$arr_cl[$k]['cl-comp'].'", 
										`tipo_documento` = "'.$arr_cl[$k]['cl-type-doc'].'", 
										`estado_civil` = "'.$arr_cl[$k]['cl-status'].'", 
										`lugar_residencia` = '.$arr_cl[$k]['cl-place-res'].', 
										`localidad` = "'.$arr_cl[$k]['cl-locality'].'", `avenida` = "'.$arr_cl[$k]['cl-avc'].'", 
										`direccion` = "'.$arr_cl[$k]['cl-address-home'].'", 
										`no_domicilio` = "'.$arr_cl[$k]['cl-nhome'].'", 
										`direccion_laboral` = "'.$arr_cl[$k]['cl-address-work'].'", 
										`pais` = "'.$arr_cl[$k]['cl-country'].'", `id_ocupacion` = '.$arr_cl[$k]['cl-occupation'].', 
										`desc_ocupacion` = "'.$arr_cl[$k]['cl-desc-occ'].'", 
										`telefono_domicilio` = "'.$arr_cl[$k]['cl-phone-1'].'", 
										`telefono_oficina` = "'.$arr_cl[$k]['cl-phone-office'].'", 
										`telefono_celular` = "'.$arr_cl[$k]['cl-phone-2'].'", 
										`email` = "'.$arr_cl[$k]['cl-email'].'", `genero` = "'.$arr_cl[$k]['cl-gender'].'", 
										`edad` = '.$arr_cl[$k]['cl-age'].', `mano` = "'.$arr_cl[$k]['cl-hand'].'" 
									WHERE id_cliente = "'.$arr_cl[$k]['cl-id'].'" ;';
								
								if($link->query($sqlCL) === TRUE) {
									$sw_UCl = TRUE;
								} else {
									$sw_UCl = FALSE;
								}

								$sqlD = 'update s_de_em_detalle 
								set `saldo` = ' . $arr_cl[$k]['cl-residue'] . ', 
									`cumulo` = ' . $arr_cl[$k]['cl-accumulation'] . ', 
									`facultativo` = ' . (int)$arr_cl[$k]['cl-fac'] . ', 
									`motivo_facultativo` = "' . $arr_cl[$k]['cl-fac-reason'] . '", 
									`aprobado` = ' . (int)$arr_cl[$k]['cl-approved'] . '
								where id_detalle = "' . $arr_cl[$k]['cl-q-idd'] . '"
									and id_emision = "' . $ide . '"
								;';

								if($link->query($sqlD) === TRUE) {
									$sw_UDt = TRUE;
								} else {
									$sw_UDt = FALSE;
								}
									
								$sqlQs = 'UPDATE s_de_em_respuesta 
									SET `observacion` = "'.$arr_cl[$k]['cl-q-resp'].'"
									WHERE id_respuesta = "'.$arr_cl[$k]['cl-q-idr'].'" AND 
										id_detalle = "'.$arr_cl[$k]['cl-q-idd'].'" ;';
									
								if($link->query($sqlQs) === TRUE) {
									$sw_UQs = TRUE;
								} else {
									$sw_UQs = FALSE;
								}
								/*
								$sqlBN = 'UPDATE s_de_beneficiario 
									SET `paterno` = "'.$arr_cl[$k]['cl-sp-patern'].'", `materno` = "'.$arr_cl[$k]['cl-sp-matern'].'", 
										`nombre` = "'.$arr_cl[$k]['cl-sp-name'].'", `ci` = "'.$arr_cl[$k]['cl-sp-doc-id'].'", 
										`id_depto` = '.$arr_cl[$k]['cl-sp-ext'].', `parentesco` ="'.$arr_cl[$k]['cl-sp-relation'].'" 
									WHERE id_beneficiario = "'.$arr_cl[$k]['cl-sp-idb'].'" AND cobertura = "SP" ;';
								
								if($link->query($sqlBN) === true) {
									$sw_UBs = true;
								} else {
									$sw_UBs = false;
								}
								
								if($swMo === false){
									$sqlBN = 'UPDATE s_de_beneficiario 
									SET `paterno` = "'.$arr_cl[$k]['cl-vg-patern'].'", `materno` = "'.$arr_cl[$k]['cl-vg-matern'].'", 
										`nombre` = "'.$arr_cl[$k]['cl-vg-name'].'", `edad` = "'.$arr_cl[$k]['cl-vg-age'].'", 
										`parentesco` ="'.$arr_cl[$k]['cl-vg-relation'].'"
									WHERE id_beneficiario = "'.$arr_cl[$k]['cl-vg-idb'].'" AND cobertura = "VG" ;';
									
									if($link->query($sqlBN) === true) {
										$sw_UBv = true;
									} else {
										$sw_UBv = false;
									}
								}

								if ($swMo === false) {
									$sqlBN = 'UPDATE s_de_beneficiario 
									SET `paterno` = "'.$arr_cl[$k]['cl-pr-patern'].'", 
										`materno` = "'.$arr_cl[$k]['cl-pr-matern'].'", 
										`nombre` = "'.$arr_cl[$k]['cl-pr-name'].'", 
										`ci` = "'.$arr_cl[$k]['cl-pr-doc-id'].'", 
										`id_depto` = '.$arr_cl[$k]['cl-pr-ext'].', 
										`parentesco` ="'.$arr_cl[$k]['cl-pr-relation'].'" 
									WHERE id_beneficiario = "'.$arr_cl[$k]['cl-pr-idb'].'" 
										AND cobertura = "PR" ;';
									
									if($link->query($sqlBN) === true) {
										$sw_UBs = true;
									} else {
										$sw_UBs = false;
									}
									
									$sqlBN = 'UPDATE s_de_beneficiario 
									SET `paterno` = "'.$arr_cl[$k]['cl-co-patern'].'", 
										`materno` = "'.$arr_cl[$k]['cl-co-matern'].'", 
										`nombre` = "'.$arr_cl[$k]['cl-co-name'].'", 
										`ci` = "'.$arr_cl[$k]['cl-co-doc-id'].'", 
										`id_depto` = '.$arr_cl[$k]['cl-co-ext'].', 
										`parentesco` ="'.$arr_cl[$k]['cl-co-relation'].'" 
									WHERE id_beneficiario = "'.$arr_cl[$k]['cl-co-idb'].'" 
										AND cobertura = "CO" ;';
								
									if($link->query($sqlBN) === true) {
										$sw_UBs = true;
									} else {
										$sw_UBs = false;
									}
								}*/
							}
							
							if($sw_UCl === TRUE){
								if($sw_UQs === TRUE){
									//if($sw_UBs === TRUE && $sw_UBv === TRUE){
									if($sw_UDt === true){
										$arrDE[0] = 1;
										$arrDE[1] = 'de-quote.php?ms='.$ms.'&page='.$page.'&pr='.$pr.'&ide='.base64_encode($ide).'&flag='.md5('i-read').'&cia='.base64_encode($dcr_cia).$target.$idd;
										$arrDE[2] = 'La Póliza fue actualizada con exito !';
									}else {
										$arrDE[2] = 'No se pudo actualizar los Beneficiarios';
									}
								}else {
									$arrDE[2] = 'No se pudo actualizar las Observaciones de las respuestas ';
									}
							}else {
								$arrDE[2] = 'No se pudo actualizar los Titulares';
							}
						}else {
							$arrDE[2] = 'La Póliza no pudo ser Actualizada ';
						}
					} elseif ($swPolicy === true) {
	                    $arrDE[2] = 'Esta Póliza ya esta registrada';
	                }
	            } else {
	            	$arrDE[2] = $birth_mess . '[ ' . $rowAge['edad_min'] . ' - ' . $rowAge['edad_max'] . ' ]';
	            }
			}else{
				$arrDE[2] = 'No existen Titulares';
			}
		}else{
			$arrDE[2] = 'No se puede guardar la Póliza';
		}
		echo json_encode($arrDE);
	}else{
		$arrDE[2] = 'No se puede registrar la Póliza';
		echo json_encode($arrDE);
	}
}else{
	$arrDE[2] = 'La Póliza no puede ser registrada';
	echo json_encode($arrDE);
}
}else{
	echo json_encode($arrDE);
}
?>