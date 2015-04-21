<?php

require_once __DIR__ . '/../../sibas-db.class.php';
require_once __DIR__ . '/../models/Diaconia.php';
require_once __DIR__ . '/../controllers/ClientController.php';
require_once __DIR__ . '/../repositories/PolicyRepo.php';

class PolicyController extends Diaconia
{
	protected $cx;

	public function __construct()
	{
		$this->cx = new SibasDB();
	}

	public function setLabelData($flag, &$sw, &$action, &$title, &$title_btn, 
								&$read_new, &$read_save, &$read_edit)
	{
		switch($flag){
		case md5('i-new'):
			$action = 'DE-issue-record.php';
			$title = 'Emisión de Póliza de Desgravamen';
			$title_btn = 'Guardar';
			
			$read_new = 'readonly';
			$sw = 1;
			break;
		case md5('i-read'):
			$action = 'DE-policy-record.php';
			$title = 'Póliza No. ';
			$title_btn = 'Emitir';
			$read_new = 'disabled';
			$read_save = 'disabled';
			$sw = 2;
			break;
		case md5('i-edit'):
			$action = 'DE-issue-record.php';
			$title = 'Póliza No. ';
			$title_btn = 'Actualizar Datos';
			$read_edit = 'readonly';
			$sw = 3;
			break;
		}
	}

	public function getPolicyData($sw, $idc, $ide, $idef)
	{
		$PolicyRepo = new PolicyRepo($this->cx);
		$idc	= $this->cx->real_escape_string(trim(base64_decode($idc)));
		$ide	= $this->cx->real_escape_string(trim(base64_decode($ide)));
		$idef	= $this->cx->real_escape_string(trim(base64_decode($idef)));
		$data 	= array ();

		if ($sw === 1) {
			$data = $PolicyRepo->getQuoteData($idc, $idef);
		} else {
			$data = $PolicyRepo->getPolicyData($ide, $idef);
		}

		if (count($data) > 0) {
			return $data;
		}

		return false;
	}

	public function postPolicy($vars, &$mess)
	{
		$pr = $this->cx->real_escape_string(trim(base64_decode($vars['pr'])));

		if ($pr === 'DE|05') {
			$sw 		= 0;
	        $data 		= array();
	        $data_pr 	= array();
	        $data_range = array();
	        $client 	= array();

	        $ms 	= $this->cx->real_escape_string(trim($vars['ms']));
			$page 	= $this->cx->real_escape_string(trim($vars['page']));
			$pr 	= $this->cx->real_escape_string(trim($vars['pr']));

			$max_item = $amount_max_bs = $amount_max_usd = 0;

			if (($data_pr = $this->getDataProduct($_SESSION['idEF'])) !== false) {
				$max_item 		= (int)$data_pr['max_detalle'];
				$amount_max_bs 	= (float)$data_pr['max_emision_bs'];
				$amount_max_usd = (float)$data_pr['max_emision_usd'];
				$data_range		= json_decode($data_pr['data'], true);
			}

			$target = '';
			if(isset($vars['target'])) {
				$target = '&target=' . $this->cx->real_escape_string(trim($vars['target']));
			}

			$idd = '';
			if(isset($vars['idd'])) {
				$idd = '&idd=' . $this->cx->real_escape_string(trim($vars['idd']));
			}
			
			$flag = $vars['flag'];

			switch($flag){
			case md5('i-new'):	$sw = 1; break;
			case md5('i-read'):	$sw = 2; break;
			case md5('i-edit'):	$sw = 3; break;
			}

			if ($sw < 1) {
				$mess[2] = 'No se puede guardar la Póliza';
				return false;
			}

			$arr_cl = array();
			$n_cl 	= 0;
			if (isset($vars['ncl-data'])) {
				$n_cl = $this->cx->real_escape_string(trim(base64_decode($vars['ncl-data'])));
			}

			if ($n_cl > 0 && $n_cl <= $max_item) {
				$swDE 		= false;
				$swMo 		= false;
				$bc 		= false;
				$birth_flag = true;
				$birth_mess = '';
				$cp 		= null;

				$this->setPolicyData($vars);

				if ($vars['dcr_coverage'] === 'BC') {
					$bc = true;
				}

				if ($bc) {
					$data_pr['edad_max'] = 75;
				}

				$this->setClientData($vars, $arr_cl, $client, $n_cl);
				$ClientController = new ClientController();

				foreach ($arr_cl as $key => $client_data) {
					if ($ClientController->verifyYearUser($data_pr['edad_min'], 
		            		$data_pr['edad_max'], $client_data['cl-date']) === false) {
		            	$name = $client[$key]['cl_nombre'] . ' ' 
							. $client[$key]['cl_paterno'] . ' ' 
							. $client[$key]['cl_materno'];

						$birth_flag = false;
						$birth_mess .= 'La Fecha de nacimiento del titular ' . $name 
							. ' no esta en el rango permitido de edades <br>';
		            }
				}

				$PolicyRepo = new PolicyRepo($this->cx);

				$tipo_pr 		= 0;
				$vars['tipo_cm'] = $this->getRateExchange(false);
				$tcm 			= $this->getRateExchange(true);
				$prefix 		= array();
				$arrPrefix 		= 'null';
				$FAC 			= false;
				$FAC_BC 		= false;
				$fac_reason 	= '';
				$fac_reason_bc 	= '';
				$cu_amount 		= false;
				$cu_amount_mess = '';
				$IMC 			= false;
				$QS 			= false;
				$CU 			= false;
				
				$TASA 	= $PolicyRepo->getTasa($vars['dcr_cia'], $vars['dcr_prcia'], $vars['idef']);
				$PRIMA 	= $PolicyRepo->getPrima($vars['dcr_amount'], $TASA);
				$age	= 0;
				
				$fac_mess 	= '';
				$mess_aux 	= '';
				$cont 		= 0;
				$slug		= '';

				if ($vars['dcr_currency'] === 'USD') {
					$vars['amount'] = $vars['amount'] * $tcm;
				}

				foreach ($arr_cl as $key => $client_data) {
					$token_range = false;
					$age = $client_data['cl-age'];

					foreach ($data_range as $key1 => $ranges) {
						foreach ($ranges['range'] as $key2 => $range) {
							if (($vars['amount'] >= $range['amount_min'] 
									&& $vars['amount'] <= $range['amount_max'])
									&& ($age >= $range['edad_min'] && $age <= $range['edad_max'])) {
								$slug 			= $ranges['slug'];
								$token_range 	= true;
								break;
							}
						}

						if ($token_range) {
							break;
						}
					}

					if($ClientController->getImc($client_data['cl-weight'], $client_data['cl-height'])
							&& $slug !== 'FC'){
						$IMC 		= true;
						$mess_aux 	= ' | El Titular ' . $key . ' no cumple con el IMC ';
						
						if ($bc) {
							$client_data['cl-approved'] 	= false;
							$client_data['cl-fac'] 			= true;
							$client_data['cl-fac-reason'] 	.= $mess_aux;
						}

						$fac_mess .= $mess_aux;
					}

					if($client_data['cl-q-fac'] > 1 && $slug !== 'FC'){
						$QS 		= true;
						$mess_aux 	= ' | El Titular ' . $key . ' no cumple con las Preguntas ';

						if ($bc) {
							$client_data['cl-approved'] 	= false;
							$client_data['cl-fac'] 			= true;
							$client_data['cl-fac-reason'] 	.= $mess_aux;
						}

						$fac_mess .= $mess_aux;
					}

					$cu_amount = false;
					if ($slug === 'FA') {
						$cu_amount = true;
					}
					
					if ($cu_amount === true) {
						$CU = true;
						$mess_aux = '
							| El monto total acumulado del Titular ' . $key . ' ' 
							. number_format($client_data['cl-accumulation'], 2, '.', ',') . ' Bs. 
							supera el monto maximo permitido. Monto maximo permitido ' 
							. number_format($amount_max_bs, 2, '.', ',') . ' Bs. ';

						if ($bc) {
							$client_data['cl-approved'] 	= false;
							$client_data['cl-fac'] 			= true;
							$client_data['cl-fac-reason'] 	.= $mess_aux;
						}

						$cu_amount_mess .= $mess_aux;

						$cu_amount_mess = '
							| El monto solicitado supera el monto maximo permitido. 
							Monto maximo permitido ' . number_format($range['amount_min'], 2, '.', ',') 
								. ' ' . $vars['dcr_currency'] . '. ';
						$fac_reason .= $cu_amount_mess;
					}

					if ($bc) {
						switch ($vars['dcr_currency']) {
						case 'BS':
							if ($client_data['cl-fac'] === true) {
								FacBS_bc:
								$FAC_BC = true;
								$fac_reason_bc .= $client_data['cl-fac-reason'];
							} elseif ($cu_amount === true) {
								goto FacBS_bc;
							} else {
								$client_data['cl-fac'] = false;
								$client_data['cl-approved'] = true;
								$client_data['cl-fac-reason'] = '';
							}
							break;
						case 'USD':
							if($client_data['cl-fac'] === true){
								FacUSD_bc:
								$FAC_BC = true;
								$fac_reason_bc .= $client_data['cl-fac-reason'];
							} elseif ($cu_amount === true) {
								goto FacUSD_bc;
							} else {
								$client_data['cl-fac'] = false;
								$client_data['cl-approved'] = true;
								$client_data['cl-fac-reason'] = '';
							}
							break;
						}
					}
				}

				if ($swMo === false) {
					$prefix[0] = 'DE';

					if ($bc === false) {
						switch ($vars['dcr_currency']) {
						case 'BS':
							if ($IMC === TRUE || $QS === TRUE || $CU === TRUE) {
								FacBS:
								$FAC = TRUE;
								$fac_reason .= $fac_mess;
							} elseif ($CU === true) {
								goto FacBS;
							}
							break;
						case 'USD':
							if ($IMC === TRUE || $QS === TRUE || $CU === TRUE) {
								FacUSD:
								$FAC = TRUE;
								$fac_reason .= $fac_mess;
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
				}

				$fac_reason 	= preg_replace('/\s+/', ' ', $fac_reason);
                $vars['prefix'] = $prefix[0];
                $vars['id'] 	= '';
                $vars['FAC']	= $FAC;
                $vars['fac_reason'] = $fac_reason;
                $vars['tasa']	= $TASA;
                $vars['prima']	= $PRIMA;

                $swPolicy = null;
                if ($sw === 1) {
                    $swPolicy = $PolicyRepo->checkPolicyFac($vars, $client);
                }

				if ($birth_flag) {
					if ($sw === 1 && $swPolicy === false) {
						$vars['idc'] 	= $this->cx->real_escape_string(trim(base64_decode($vars['de-idc'])));
						$vars['id'] 	= uniqid('@S#1$2013',true);
						$vars['record']	= $data['record'] = $this->getRegistrationNumber(
							base64_encode($vars['idef']), 'DE', 1, $vars['prefix']);

						if ($PolicyRepo->postPolicyData($vars, $arr_cl)) {
							$mess[0] = 1;
							$mess[1] = 'de-quote.php?ms=' . $ms . '&page=' . $page 
								. '&pr=' . $pr . '&ide=' . base64_encode($vars['id']) 
								. '&flag=' . md5('i-read') . '&cia=' . base64_encode($vars['dcr_cia']);
							$mess[2] = 'La Póliza fue registrada con exito';
							return true;
						} else {
							$mess[2] = 'La Póliza no pudo ser registrada ';
						}
					} elseif ($sw === 3) {
						$vars['id'] = $this->cx->real_escape_string(trim(base64_decode($vars['de-ide'])));

						if ($PolicyRepo->putPolicyData($vars, $arr_cl)) {
							$mess[0] = 1;
							$mess[1] = 'de-quote.php?ms=' . $ms . '&page=' . $page 
								. '&pr=' . $pr . '&ide=' . base64_encode($vars['id']) 
								. '&flag=' . md5('i-read') . '&cia=' . base64_encode($vars['dcr_cia'])
								. $target . $idd;
							$mess[2] = 'La Póliza fue actualizada con exito !';
						} else {
							$mess[2] = 'La Póliza no pudo ser Actualizada ';
						}
					}
				} else {
					$mess[2] = $birth_mess . '[ ' . $data_pr['edad_min'] 
						. ' - ' . $data_pr['edad_max'] . ' ]';
				}
			} else {
				$mess[2] = 'No existen Titulares';
			}
		} else {
			$mess[2] = 'No se puede registrar la Póliza';
		}

		return false;
	}

	private function setPolicyData(&$vars)
	{
		$vars['certificate']	= $this->getCertificate();
		$vars['user']			= $this->cx->real_escape_string(trim(base64_decode($vars['user'])));
		$vars['idef']			= $this->cx->real_escape_string(trim(base64_decode($vars['idef'])));
		$vars['dcr_cia'] 		= $this->cx->real_escape_string(trim(base64_decode($vars['cia'])));
		$vars['dcr_prcia'] 		= $this->cx->real_escape_string(trim($vars['dl-product']));
		$vars['dcr_modality'] 	= 'null';
		$vars['dcr_coverage'] 	= $this->cx->real_escape_string(trim($vars['dcr-coverage']));
		$vars['dcr_amount'] 	= $this->cx->real_escape_string(trim($vars['dcr-amount']));
		$vars['amount']			= $vars['dcr_amount'];
		$vars['dcr_currency'] 	= $this->cx->real_escape_string(trim($vars['dcr-currency']));
		$vars['dcr_term'] 		= $this->cx->real_escape_string(trim($vars['dcr-term']));
		$vars['dcr_type_term'] 	= $this->cx->real_escape_string(trim($vars['dcr-type-term']));
		$vars['dcr_type_mov'] 	= '';
		if (isset($vars['dcr-type-mov'])) {
			$vars['dcr_type_mov'] = $this->cx->real_escape_string(trim($vars['dcr-type-mov']));
		}
		$vars['dcr_opp'] = $this->cx->real_escape_string(trim($vars['dcr-opp']));
		$vars['dcr_policy'] = 'null';
		if (isset($vars['dcr-policy'])) {
			$vars['dcr_policy'] = '"' . $this->cx->real_escape_string(trim(base64_decode($vars['dcr-policy']))) . '"';
		}
		$vars['dcr_amount_de'] = '';
		//$dcr_amount_de = $this->cx->real_escape_string(trim($vars['dcr-amount-de']));
		if(empty($dcr_amount_de) === true) {
			$vars['dcr_amount_de'] = 0;
		}
		
		$vars['dcr_amount_cc'] 		= 0;
		$vars['dcr_amount_acc'] 	= 0;
		$vars['dcr_amount_acc_2'] 	= 0;

		/*if($sw === 1 && isset($vars['cp'])) {
			$cp = (int)$this->cx->real_escape_string(trim(base64_decode($vars['cp'])));
		}*/
	}

	public function setClientData(&$vars, &$arr_cl, &$client, $n_cl)
	{
		$k = 0;
		while ($k < $n_cl) {
			$k += 1;
			$arr_cl[$k]['cl_id'] 		= $this->cx->real_escape_string(trim(base64_decode($vars['dc-'.$k.'-idcl'])));
			$arr_cl[$k]['cl-name'] 		= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-name']));
			$arr_cl[$k]['cl-patern']	= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-ln-patern']));
			$arr_cl[$k]['cl-matern'] 	= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-ln-matern']));
			$arr_cl[$k]['cl-married']	= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-ln-married']));
			$arr_cl[$k]['cl-gender'] 	= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-gender']));
			$arr_cl[$k]['cl-status'] 	= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-status']));
			$arr_cl[$k]['cl-type-doc'] 	= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-type-doc']));
			$arr_cl[$k]['cl-doc-id'] 	= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-doc-id']));
			$arr_cl[$k]['cl-comp'] 		= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-comp']));
			$arr_cl[$k]['cl-ext'] 		= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-ext']));
			$arr_cl[$k]['cl-date'] 		= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-date-birth']));
			$arr_cl[$k]['cl-age'] 		= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-age']));
			$arr_cl[$k]['cl-hand'] 		= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-hand']));
			$arr_cl[$k]['cl-country'] 	= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-country']));
			$arr_cl[$k]['cl-place-birth'] = $this->cx->real_escape_string(trim($vars['dc-'.$k.'-place-birth']));
			$arr_cl[$k]['cl-place-res']	= 'null';
			$aux_place_res 				= $vars['dc-'.$k.'-place-res'];
			if (empty($aux_place_res) === false) {
				$arr_cl[$k]['cl-place-res'] = $this->cx->real_escape_string(trim($aux_place_res));
			}
			$arr_cl[$k]['cl-locality'] 	= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-locality']));
			$arr_cl[$k]['cl-avc'] 		= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-avc']));
			$arr_cl[$k]['cl-address-home'] = $this->cx->real_escape_string(trim($vars['dc-'.$k.'-address-home']));
			$arr_cl[$k]['cl-nhome'] 	= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-nhome']));
			$arr_cl[$k]['cl-phone-1'] 	= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-phone-1']));
			$arr_cl[$k]['cl-phone-2'] 	= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-phone-2']));
			$arr_cl[$k]['cl-email'] 	= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-email']));
			$arr_cl[$k]['cl-occupation'] = 'null';
			$aux_occupation 			= $vars['dc-'.$k.'-occupation'];
			if (empty($aux_occupation) === false) {
				$arr_cl[$k]['cl-occupation'] = 
					'"' . $this->cx->real_escape_string(trim(base64_decode($aux_occupation))) . '"';
			}
			$arr_cl[$k]['cl-desc-occ'] 		= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-desc-occ']));
			$arr_cl[$k]['cl-address-work'] 	= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-address-work']));
			$arr_cl[$k]['cl-phone-office'] 	= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-phone-office']));
			$arr_cl[$k]['cl-weight'] 		= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-weight']));
			$arr_cl[$k]['cl-height'] 		= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-height']));
			$arr_cl[$k]['cl-amount-bc'] 	= 0;
			if (isset($vars['dc-'.$k.'-amount-bc'])) {
				$arr_cl[$k]['cl-amount-bc'] 	= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-amount-bc']));
			}
			$arr_cl[$k]['cl-tasa'] 			= 0;
			if (isset($vars['dc-' . $k . '-tasa'])) {
				$arr_cl[$k]['cl-tasa'] 		= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-tasa']));
			}
			$arr_cl[$k]['cl-share'] 	= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-share']));
			$arr_cl[$k]['cl-residue'] 	= 
				$this->cx->real_escape_string(trim($vars['dcr-amount-de-' . $k]));
			$arr_cl[$k]['cl-accumulation'] = 
				$this->cx->real_escape_string(trim($vars['dcr-amount-ac-' . $k]));
			$arr_cl[$k]['cl-fac'] 		= false;
			$arr_cl[$k]['cl-fac-reason'] = '';
			$arr_cl[$k]['cl-approved'] 	= true;
			$arr_cl[$k]['cl-titular'] 	= $this->cx->real_escape_string(trim($vars['dc-'.$k.'-titular']));
            
            $client[$k]['cl_nombre'] 	= $arr_cl[$k]['cl-name'];
            $client[$k]['cl_paterno'] 	= $arr_cl[$k]['cl-patern'];
            $client[$k]['cl_materno'] 	= $arr_cl[$k]['cl-matern'];
            $client[$k]['cl_ci'] 		= $arr_cl[$k]['cl-doc-id'];
            $client[$k]['cl_extension'] = $arr_cl[$k]['cl-ext'];

            $arr_cl[$k]['cl-q-idd'] 	= $this->cx->real_escape_string(trim(base64_decode($vars['dq-'.$k.'-idd'])));
			$arr_cl[$k]['cl-d-idd'] 	= uniqid('@S#1$2013' . date('U'), true);
			$arr_cl[$k]['cl-q-idr'] 	= $this->cx->real_escape_string(trim(base64_decode($vars['dq-'.$k.'-idr'])));
			$arr_cl[$k]['cl-q-fac'] 	= $this->cx->real_escape_string(trim(base64_decode($vars['dq-'.$k.'-fac'])));
			$arr_cl[$k]['cl-q-resp'] 	= $this->cx->real_escape_string(trim($vars['dq-'.$k.'-resp']));
		}


	}

}

?>