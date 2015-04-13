<?php
require('sibas-db.class.php');

$arrDE = array(0 => 0, 1 => 'R', 2 => 'Error.');

if(isset($_POST['dc-token']) 
	&& isset($_POST['dc-idc']) 
	&& isset($_POST['ms']) 
	&& isset($_POST['page']) 
	&& isset($_POST['pr']) 
	&& isset($_POST['id-ef'])){

	$swEmpty = false;
	
	if($swEmpty === FALSE && $_POST['pr'] === base64_encode('DE|02')){
		$link = new SibasDB();
		
		$idc = $link->real_escape_string(trim(base64_decode($_POST['dc-idc'])));
		$idef = $link->real_escape_string(trim(base64_decode($_POST['id-ef'])));
		
		$idClient = 0;
		$nCl = 0;
		$flag = FALSE;
		$birth_flag = true;
		$birth_mess = '';
		$bc = false;
		$amount = 0;

		$arr_cl = array();

		if(isset($_POST['dc-idCl'])){
			$flag = TRUE;
			$idClient = $link->real_escape_string(trim(base64_decode($_POST['dc-idCl'])));
		}

		if (isset($_POST['dc-bc'])) {
			$aux_bc = (boolean)$link->real_escape_string(trim(base64_decode($_POST['dc-bc'])));
			if ($aux_bc === true) {
				$bc = true;
			}
		}

		$nCl = (int)$link->real_escape_string(trim(base64_decode($_POST['dc-ncl'])));

		$ms = $link->real_escape_string(trim($_POST['ms']));
		$page = $link->real_escape_string(trim($_POST['page']));
		$pr = $link->real_escape_string(trim($_POST['pr']));

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
				and sef.id_ef = "' . $idef . '"
				and sef.activado = true
		;';
		
		$rsAge = $link->query($sqlAge,MYSQLI_STORE_RESULT);
		$rowAge = $rsAge->fetch_array(MYSQLI_ASSOC);
		$rsAge->free();

		if ($bc === true) {
			$rowAge['edad_max'] = 75;
		}

		for ($k = 0; $k < $nCl; $k++) {
			$temp_data = array();

			$temp_data['name'] = $link->real_escape_string(trim($_POST['dc-name-'.$k]));
			$temp_data['patern'] = $link->real_escape_string(trim($_POST['dc-ln-patern-'.$k]));
			$temp_data['matern'] = $link->real_escape_string(trim($_POST['dc-ln-matern-'.$k]));
			$temp_data['married'] = $link->real_escape_string(trim($_POST['dc-ln-married-'.$k]));
			$temp_data['status'] = $link->real_escape_string(trim($_POST['dc-status-'.$k]));
			$temp_data['type_doc'] = $link->real_escape_string(trim($_POST['dc-type-doc-'.$k]));
			$temp_data['doc_id'] = $link->real_escape_string(trim($_POST['dc-doc-id-'.$k]));
			$temp_data['comp'] = $link->real_escape_string(trim($_POST['dc-comp-'.$k]));
			$temp_data['ext'] = $link->real_escape_string(trim($_POST['dc-ext-'.$k]));
			$temp_data['country'] = $link->real_escape_string(trim($_POST['dc-country-'.$k]));
			$temp_data['birth'] = $link->real_escape_string(trim($_POST['dc-date-birth-'.$k]));
			$temp_data['place_birth'] = $link->real_escape_string(trim($_POST['dc-place-birth-'.$k]));
			$temp_data['place_res'] = 'null';
			$aux_place_res = $_POST['dc-place-res-'.$k];
			if (empty($aux_place_res) === false) {
				$temp_data['place_res'] = $link->real_escape_string(trim($aux_place_res));
			}
			$temp_data['locality'] = $link->real_escape_string(trim($_POST['dc-locality-'.$k]));
			$temp_data['address'] = $link->real_escape_string(trim($_POST['dc-address-'.$k]));
			$temp_data['phone_1'] = $link->real_escape_string(trim($_POST['dc-phone1-'.$k]));
			$temp_data['phone_2'] = $link->real_escape_string(trim($_POST['dc-phone2-'.$k]));
			$temp_data['email'] = $link->real_escape_string(trim($_POST['dc-email-'.$k]));
			$temp_data['phone_office'] = $link->real_escape_string(trim($_POST['dc-phone-office-'.$k]));
			$temp_data['occupation'] = 'null';
			$aux_occupation = $_POST['dc-occupation-'.$k];
			if (empty($aux_occupation) === false) {
				$temp_data['occupation'] = '"' 
					. $link->real_escape_string(trim(base64_decode($aux_occupation))) . '"';
			}
			$temp_data['occ_desc'] = $link->real_escape_string(trim($_POST['dc-desc-occ-'.$k]));
			$temp_data['gender'] = $link->real_escape_string(trim($_POST['dc-gender-'.$k]));
			$temp_data['weight'] = $link->real_escape_string(trim($_POST['dc-weight-'.$k]));
			$temp_data['height'] = $link->real_escape_string(trim($_POST['dc-height-'.$k]));
			$temp_data['amount'] = 0;
			if (isset($_POST['dc-amount-'.$k])) {
	            $temp_data['amount'] = 
	            	$link->real_escape_string(trim(base64_decode($_POST['dc-amount-'.$k])));
	        }
			$temp_data['amount_bc'] = 0;
			if (isset($_POST['dc-amount-bc-'.$k])) {
	        	$temp_data['amount_bc'] = $link->real_escape_string(trim($_POST['dc-amount-bc-'.$k]));
	        }
	        $temp_data['percentage'] = 100;
	        if(($temp_data['status'] !== 'CAS' && $temp_data['status'] !== 'VIU') 
	        	|| $temp_data['gender'] !== 'F'){
				$temp_data['married'] = '';
			}

			$amount += $temp_data['amount_bc'];

			$arr_cl[$k] = $temp_data;

			$date1 = new DateTime($temp_data['birth']);
			$date2 = new DateTime(date('Y-m-d'));
			$interval = $date1->diff($date2);
			$year = $interval->format('%y');

			if ($link->verifyYearUser($rowAge['edad_min'], 
				$rowAge['edad_max'], $temp_data['birth']) === false) {

				$name = $temp_data['name'] . ' ' . $temp_data['patern'] . ' ' . $temp_data['matern'];
				$birth_flag = false;
				$birth_mess .= 'La Fecha de nacimiento del titular ' . $name 
					. ' no esta en el rango permitido de edades <br>';
			}
		}
		
		if($birth_flag === true){
			$sql = '';
			if($flag === TRUE){
				$data = $arr_cl[0];

				$sql = 'update s_de_cot_cliente as scl
                set scl.tipo = 0, scl.razon_social = "",
                    scl.paterno = "'.$data['patern'].'",
                    scl.materno = "'.$data['matern'].'",
                    scl.nombre = "'.$data['name'].'",
                    scl.ap_casada = "'.$data['married'].'",
                    scl.fecha_nacimiento = "'.$data['birth'].'",
                    scl.lugar_nacimiento = "'.$data['place_birth'].'",
                    scl.ci = "'.$data['doc_id'].'",
                    scl.extension = '.$data['ext'].',
                    scl.complemento = "'.$data['comp'].'",
                    scl.tipo_documento = "'.$data['type_doc'].'",
                    scl.estado_civil = "'.$data['status'].'",
                    scl.lugar_residencia = '.$data['place_res'].',
                    scl.localidad = "'.$data['locality'].'",
                    scl.direccion = "'.$data['address'].'",
                    scl.pais = "'.$data['country'].'",
                    scl.id_ocupacion = '.$data['occupation'].',
                    scl.desc_ocupacion = "'.$data['occ_desc'].'",
                    scl.telefono_domicilio = "'.$data['phone_1'].'",
                    scl.telefono_oficina = "'.$data['phone_office'].'",
                    scl.telefono_celular = "'.$data['phone_2'].'",
                    scl.email = "'.$data['email'].'",
                    scl.peso = '.$data['weight'].',
                    scl.estatura = '.$data['height'].',
                    scl.genero = "'.$data['gender'].'",
                    scl.edad = TIMESTAMPDIFF(YEAR, "'.$data['birth'].'", curdate()),
                    scl.saldo_deudor = ' . $data['amount'] . '
                where scl.id_cliente = "'.$idClient.'"
                ;';
				
				if($link->query($sql) === TRUE){
					if ($bc === true) {
						$sqlDet = 'update s_de_cot_detalle as sdd
						set monto_banca_comunal = ' . $data['amount_bc'] . '
						where sdd.id_cotizacion = "' . $idc . '"
							and sdd.id_cliente = "' . $idClient . '"
						;';

						if ($link->query($sqlDet) === true) {
							if ($link->setDatabcCot($idc) === true) {
								goto Resp1;
							}
							//goto Resp1;
						} else {
							$arrDE[2] = 'Los Datos se actualizaron correctamente.';
						}
					} else {
						Resp1:

						$arrDE[0] = 1;
						$arrDE[1] = 'de-quote.php?ms='.$ms.'&page='.$page.'&pr='.$pr.'&idc='.base64_encode($idc);
						$arrDE[2] = 'Los Datos se actualizaron correctamente';
					}
				}else{
					$arrDE[2] = 'No se pudo actualizar los datos';
				}
			}else{
				if ($nCl === 1 && $bc === false) {
					$DC = $link->number_clients($idc, $idef, FALSE);
				}

				$nReg = 0;
				$nIns = false;
				$err = true;
				
				$sqlNew = 'insert into s_de_cot_cliente
				(`id_cliente`, `id_ef`, `tipo`, `razon_social`, `paterno`,
				`materno`, `nombre`, `ap_casada`, `fecha_nacimiento`,
				`lugar_nacimiento`, `ci`, `extension`, `complemento`,
				`tipo_documento`, `estado_civil`, `lugar_residencia`,
				`localidad`, `direccion`, `pais`, `id_ocupacion`,
				`desc_ocupacion`, `telefono_domicilio`, `telefono_oficina`,
				`telefono_celular`, `email`, `peso`, `estatura`, `genero`,
				`edad`, `saldo_deudor` )
				values ';

				$sqlDet = 'insert into s_de_cot_detalle
                (`id_detalle`, `id_cotizacion`, `id_cliente`,
                `porcentaje_credito`, `tasa`, `monto_banca_comunal`, `titular`)
				values';

				for ($k = 0; $k < $nCl; $k++) {
					// Arreglar
					if ($bc === true) {
						$DC = 'CC';
					}

					$data = $arr_cl[$k];

					$vc = $link->verify_customer($data['doc_id'], $data['ext'], $idef);
					
					$idd = uniqid('@S#1$2013',true);

					if ($vc[0] === TRUE) {
						$idClient = $vc[1];
						
						$sql = 'update s_de_cot_cliente as scl
	                    set scl.tipo = 0, scl.razon_social = "",
	                        scl.paterno = "'.$data['patern'].'",
	                        scl.materno = "'.$data['matern'].'",
	                        scl.nombre = "'.$data['name'].'",
	                        scl.ap_casada = "'.$data['married'].'",
	                        scl.fecha_nacimiento = "'.$data['birth'].'",
	                        scl.lugar_nacimiento = "'.$data['place_birth'].'",
	                        scl.ci = "'.$data['doc_id'].'",
	                        scl.extension = '.$data['ext'].',
	                        scl.complemento = "'.$data['comp'].'",
	                        scl.tipo_documento = "'.$data['type_doc'].'",
	                        scl.estado_civil = "'.$data['status'].'",
	                        scl.lugar_residencia = '.$data['place_res'].',
	                        scl.localidad = "'.$data['locality'].'",
	                        scl.direccion = "'.$data['address'].'",
	                        scl.pais = "'.$data['country'].'",
	                        scl.id_ocupacion = '.$data['occupation'].',
	                        scl.desc_ocupacion = "'.$data['occ_desc'].'",
	                        scl.telefono_domicilio = "'.$data['phone_1'].'",
	                        scl.telefono_oficina = "'.$data['phone_office'].'",
	                        scl.telefono_celular = "'.$data['phone_2'].'",
	                        scl.email = "'.$data['email'].'",
	                        scl.peso = '.$data['weight'].',
	                        scl.estatura = '.$data['height'].',
	                        scl.genero = "'.$data['gender'].'",
	                        scl.edad = TIMESTAMPDIFF(YEAR, "'.$data['birth'].'", curdate()),
	                        scl.saldo_deudor = ' . $data['amount'] . '
	                    where scl.id_cliente = "'.$idClient.'"
	                    ;';

	                    if($link->query($sql) === true){
							$nReg += 1;
						}
						/*else{
							$arrDE[2] = 'No se pudo registrar el Cliente';
						}*/

					} else {
						$idClient = uniqid('@S#1$2013',true);
						
						$sqlNew .= '
						("'.$idClient.'", "'.$idef.'", 0, "", "'.$data['patern'].'",
						"'.$data['matern'].'", "'.$data['name'].'", "'.$data['married'].'",
	                    "'.$data['birth'].'", "'.$data['place_birth'].'", "'.$data['doc_id'].'",
	                    '.$data['ext'].', "'.$data['comp'].'", "'.$data['type_doc'].'",
	                    "'.$data['status'].'", '.$data['place_res'].', "'.$data['locality'].'",
	                    "'.$data['address'].'", "'.$data['country'].'", '.$data['occupation'].',
	                    "'.$data['occ_desc'].'", "'.$data['phone_1'].'", "'.$data['phone_office'].'",
	                    "'.$data['phone_2'].'", "'.$data['email'].'", '.$data['weight'].',
	                    '.$data['height'].', "'.$data['gender'].'",
	                    TIMESTAMPDIFF(YEAR, "'.$data['birth'].'", curdate()), ' . $data['amount'] . '),';

						$nReg += 1;
						$nIns = true;
					}

					$sqlDet .= '
                    ("'.$idd.'", "'.$idc.'", "'.$idClient.'",
                    ' . $data['percentage'] . ', 0, ' . $data['amount_bc'] . ', "'.$DC.'")';
					
					if (($k + 1) === $nCl) {
						$sqlDet .= ';';
					} else {
						$sqlDet .= ',';
					}
				}

				$sqlNew = trim($sqlNew, ',') . ';';

				if ($nReg === $nCl) {
					if ($nIns === true) {
						if($link->query($sqlNew) === true){
							$err = false;
						}
					} else {
						$err = false;
					}

					if ($err === false) {
						if ($link->query($sqlDet) === true) {
							if ($bc === true) {
								if ($link->setDatabcCot($idc) === true) {
									goto Resp2;
								}
							} else {
								Resp2:

								$arrDE[0] = 1;
								$arrDE[1] = 'de-quote.php?ms=' . $ms . '&page=' . $page 
									. '&pr=' . $pr . '&idc=' . base64_encode($idc);
								$arrDE[2] = 'Cliente(s) registrado(s) con Éxito';
							}
						} else {
							$arrDE[2] = 'No se pudo registrar el Detalle';
						}
					} else {
						$arrDE[2] = 'No se pudo registrar al(los) Cliente(s).';
					}
				} else {
					$arrDE[2] = 'No se pudo registrar al(los) Cliente(s)';
				}
			}
		}else{
			$arrDE[2] = $birth_mess . '[ ' . $rowAge['edad_min'] . ' - ' . $rowAge['edad_max'] . ' ]';
		}
		echo json_encode($arrDE);
	}else{
		$arrDE[2] = 'Error .';
		echo json_encode($arrDE);
	}
}else{
	$arrDE[2] = 'Error!';
	echo json_encode($arrDE);
}
?>