<?php
require('sibas-db.class.php');
require('session.class.php');

$session = new Session();
$token = $session->check_session();

$arrTR = array(0 => 0, 1 => 'R', 2 => 'Error: No se pudo registrar la Póliza');

if((isset($_POST['de-ide']) || isset($_POST['de-idc'])) && isset($_POST['dc-type-client']) && isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['pr']) && isset($_POST['cia'])){
	
	if($_POST['pr'] === base64_encode('TRD|05') && $token === TRUE){
		$link = new SibasDB();
		
		$_FAC = FALSE;
		$_FAC_REASON = '';
		$TASA = $PRIMA = 0;
		$swMo = false;
		
		$ms = $link->real_escape_string(trim($_POST['ms']));
		$page = $link->real_escape_string(trim($_POST['page']));
		$pr = $link->real_escape_string(trim($_POST['pr']));
		
		$max_item = $max_amount = $max_anio = 0;
		if (($rowTR = $link->get_max_amount_optional($_SESSION['idEF'], 'TRD')) !== FALSE) {
			$max_item = (int)$rowTR['max_item'];
			$max_amount = (int)$rowTR['max_monto'];
			$max_anio = (int)$rowTR['max_anio'];
		}
		
		$target = '';
		if(isset($_POST['target'])) {
			$target = '&target='.$link->real_escape_string(trim($_POST['target']));
		}
		
		$flag = $_POST['flag'];
		
		$sw = 0;
		switch($flag){
			case md5('i-new'):		//POLIZA NUEVA
				$sw = 1;
				break;
			case md5('i-read'):		//
				$sw = 2;
				break;
			case md5('i-edit'):		//POLIZA ACTUALIZADA
				$sw = 3;
				break;
		}
		
		$ide = $idc = $idcia = '';
		if(isset($_POST['de-ide'])) {
			$ide = $link->real_escape_string(trim(base64_decode($_POST['de-ide'])));
		} elseif(isset($_POST['de-idc'])) {
			$idc = $link->real_escape_string(trim(base64_decode($_POST['de-idc'])));
			$ide = uniqid('@S#3$2013',true);
		}
		
		if($sw !== 0){
			$idcia = $link->real_escape_string(trim(base64_decode($_POST['cia'])));
			$dcr_amount = 0;
			$dcr_warranty = (int)$link->real_escape_string(trim(base64_decode($_POST['di-warranty'])));
			$dcr_date_begin = $link->real_escape_string(trim(base64_decode($_POST['di-date-inception'])));
			$dcr_date_end = $link->real_escape_string(trim(base64_decode($_POST['di-end-inception'])));
			$dcr_term = $link->real_escape_string(trim($_POST['di-term']));
			$dcr_type_term = $link->real_escape_string(trim($_POST['di-type-term']));
			$methodPayment = $link->real_escape_string(trim($_POST['di-method-payment']));
			$methodPayment = explode('|', $methodPayment);
			$dcr_method_payment = $link->real_escape_string(trim(base64_decode($methodPayment[0])));
			$codeMethodPayment = $link->real_escape_string(trim(base64_decode($methodPayment[1])));
			
			$dcr_opp = $link->real_escape_string(trim($_POST['di-opp']));
			$dcr_policy = 'null';
			if (isset($_POST['di-policy'])) {
				$dcr_policy = '"' . $link->real_escape_string(trim(base64_decode($_POST['di-policy']))) . '"';
			}
			
			$prefix = array();
			$arrPrefix = 'null';
			
			$bl_name = $bl_nit = '';
			if(isset($_POST['bl-name']) && isset($_POST['bl-nit'])){
				$bl_name = $link->real_escape_string(trim($_POST['bl-name']));
				$bl_nit = $link->real_escape_string(trim($_POST['bl-nit']));
			}
			
			$cp = NULL;
			if($sw === 1 && isset($_POST['cp'])) {
				$cp = (int)$link->real_escape_string(trim(base64_decode($_POST['cp'])));
			}	
			
			$cl_type_client = (boolean)$link->real_escape_string(trim(base64_decode($_POST['dc-type-client'])));
			$idcl = $cl_name = $cl_patern = $cl_matern = $cl_married = $cl_dni = $cl_ci = $cl_nit = $cl_comp = $cl_ext = 
			$cl_date_birth = $cl_gender = $cl_place_res = $cl_locality = $cl_phone_home = $cl_phone_cel = $cl_avc = 
			$cl_address_home = $cl_nhome = $cl_occupation = $cl_desc_occ = $cl_address_work = $cl_phone_office = 
			$cl_email = $cl_company_name = $cl_attached = '';
			
			if(isset($_POST['dc-idcl'])) {
				$idcl = $link->real_escape_string(trim(base64_decode($_POST['dc-idcl'])));
			} else { $idcl = uniqid('@S#3$2013',true); }
			
			if($cl_type_client === FALSE){
				$cl_name = $link->real_escape_string(trim($_POST['dc-name']));
				$cl_patern = $link->real_escape_string(trim($_POST['dc-ln-patern']));
				$cl_matern = $link->real_escape_string(trim($_POST['dc-ln-matern']));
				$cl_married = $link->real_escape_string(trim($_POST['dc-ln-married']));
				$cl_ci = $link->real_escape_string(trim($_POST['dc-doc-id']));
				$cl_comp = $link->real_escape_string(trim($_POST['dc-comp']));
				$cl_ext = $link->real_escape_string(trim($_POST['dc-ext']));
				$cl_gender = $link->real_escape_string(trim($_POST['dc-gender']));
				$cl_date_birth = $link->real_escape_string(trim($_POST['dc-date-birth']));
				$cl_place_res = $link->real_escape_string(trim($_POST['dc-place-res']));
				$cl_locality = $link->real_escape_string(trim($_POST['dc-locality']));
				$cl_phone_home = $link->real_escape_string(trim($_POST['dc-phone-1']));
				$cl_phone_cel = $link->real_escape_string(trim($_POST['dc-phone-2']));
				$cl_email = $link->real_escape_string(trim($_POST['dc-email']));
				$cl_avc = $link->real_escape_string(trim($_POST['dc-avc']));
				$cl_address_home = $link->real_escape_string(trim($_POST['dc-address-home']));
				$cl_nhome = $link->real_escape_string(trim($_POST['dc-nhome']));
				$cl_occupation = $link->real_escape_string(trim(base64_decode($_POST['dc-occupation'])));
				$cl_occupation = '"'.$cl_occupation.'"';
				$cl_desc_occ = $link->real_escape_string(trim($_POST['dc-desc-occ']));
				$cl_address_work = $link->real_escape_string(trim($_POST['dc-address-work']));
				$cl_phone_office = $link->real_escape_string(trim($_POST['dc-phone-office']));
				if ($cl_gender === 'M') { $cl_married = ''; }
				$cl_dni = $cl_ci;
			}else{
				$cl_company_name = $link->real_escape_string(trim($_POST['dc-company-name']));
				$cl_nit = $link->real_escape_string(trim($_POST['dc-nit']));
				$cl_ext = $link->real_escape_string(trim($_POST['dc-depto']));
				$cl_phone_office = $link->real_escape_string(trim($_POST['dc-company-phone-office']));
				$cl_email = $link->real_escape_string(trim($_POST['dc-company-email']));
				$cl_avc = $link->real_escape_string(trim($_POST['dc-company-avc']));
				$cl_address_home = $link->real_escape_string(trim($_POST['dc-company-address-home']));
				$cl_nhome = $link->real_escape_string(trim($_POST['dc-company-nhome']));
				$cl_occupation = 'NULL';
				$cl_place_res = $cl_ext;
				$cl_date_birth = date('Y-m-d', time());
				$cl_dni = $cl_nit;
				//$cl_company_name = $link->real_escape_string(trim($_POST['dc-']));
			}			
			
			$cl_attached = '';
			$nPr = (int)$link->real_escape_string(trim(base64_decode($_POST['nPr'])));
			
			$swCl = FALSE;
			$sqlSCl = '';
			if($sw === 1) {
				$sqlSCl = 'select 
						scl.id_cliente as idCl, scl.ci as cl_ci, scl.extension as cl_extension
					from
						s_cliente as scl
							inner join s_entidad_financiera as sef ON (sef.id_ef = scl.id_ef)
					where
						scl.ci = "'.$cl_dni.'" and scl.extension = '.$cl_ext.' and scl.tipo = '.(int)$cl_type_client.'
							and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
					;';
				
				if(($rsSCl = $link->query($sqlSCl,MYSQLI_STORE_RESULT))){
					if($rsSCl->num_rows === 1){
						$rowSCl = $rsSCl->fetch_array(MYSQLI_ASSOC);
						$rsSCl->free();
						$idcl = $rowSCl['idCl'];
						$swCl = TRUE;
					}
				}
			}
			
			$arr_pr = array();
			
			if($nPr <= $max_item){
				for($k = 1; $k <= $nPr; $k++){
					if(isset($_POST['dp-'.$k.'-idpr'])) { 
						$arr_pr[$k]['idpr'] = $link->real_escape_string(trim(base64_decode($_POST['dp-'.$k.'-idpr'])));
					} else { $arr_pr[$k]['idpr'] = uniqid('@S#3$2013'.$k, true); }
					
					$arr_pr[$k]['depto'] = $link->real_escape_string(trim(base64_decode($_POST['dp-'.$k.'-depto'])));
					$arr_pr[$k]['zone'] = $link->real_escape_string(trim($_POST['dp-'.$k.'-zone']));
					$arr_pr[$k]['locality'] = $link->real_escape_string(trim($_POST['dp-'.$k.'-locality']));
					$arr_pr[$k]['address'] = $link->real_escape_string(trim($_POST['dp-'.$k.'-address']));
					$arr_pr[$k]['type'] = $link->real_escape_string(trim(base64_decode($_POST['dp-'.$k.'-type'])));
					$arr_pr[$k]['use'] = $link->real_escape_string(trim($_POST['dp-'.$k.'-use']));
					$arr_pr[$k]['use-other'] = '';
					if($arr_pr[$k]['use'] === 'OTH'){
						$arr_pr[$k]['use-other'] = $link->real_escape_string(trim($_POST['dp-'.$k.'-use-other']));
					}else {
						$arr_pr[$k]['use'] = base64_decode($arr_pr[$k]['use']);
					}
					$arr_pr[$k]['state'] = $link->real_escape_string(trim(base64_decode($_POST['dp-'.$k.'-state'])));
					$arr_pr[$k]['modality'] = 'null';
					if (isset($_POST['dp-'.$k.'-modality'])) {
						$swMo = true;
						$arr_pr[$k]['modality'] = '"' 
							. $link->real_escape_string(trim(base64_decode($_POST['dp-'.$k.'-modality']))) . '"';
					}
					
					$arr_pr[$k]['value-insured'] = $link->real_escape_string(trim(base64_decode($_POST['dp-'.$k.'-value-insured'])));
					$arr_pr[$k]['rate'] = $link->real_escape_string(trim(base64_decode($_POST['dp-'.$k.'-rate'])));
					$arr_pr[$k]['premium'] = $link->real_escape_string(trim(base64_decode($_POST['dp-'.$k.'-premium'])));
					$arr_pr[$k]['attached'] = '';
					
					$arr_pr[$k]['FAC'] = FALSE;
					$arr_pr[$k]['reason'] = '';
					$arr_pr[$k]['approved'] = TRUE;
					
					/*if($arr_pr[$k]['value-insured'] > $max_amount){
						$arr_pr[$k]['FAC'] = TRUE;
						$_FAC = TRUE;
						$arr_pr[$k]['reason'] .= '| El valor asegurado del Inmueble excede el máximo valor permitido. Valor permitido: '.number_format($max_amount, 2, '.', ',').' USD';
					}*/
					
					if($arr_pr[$k]['FAC'] === TRUE) { $arr_pr[$k]['approved'] = FALSE; }
					
					$_FAC_REASON .= $arr_pr[$k]['reason'];
					$PRIMA += $arr_pr[$k]['premium'];
					$TASA = $arr_pr[$k]['rate'];
				}
			}else {
				$arrTR[2] = 'Los Inmuebles no pueden ser Registrados';
			}
			
			$swReg = FALSE;
			$sql = $sqlCl = '';
			if($sw === 1) {
				if($swCl === FALSE) {	// REGISTRAR POLIZA
					$sqlCl = 'INSERT INTO s_cliente 
					(`id_cliente`, `id_ef`, `tipo`, `razon_social`, `paterno`, `materno`, `nombre`, `ap_casada`, `fecha_nacimiento`, `lugar_nacimiento`, `ci`, `extension`, `complemento`, `tipo_documento`, `estado_civil`, `ci_archivo`, `lugar_residencia`, `localidad`, `avenida`, `direccion`, `no_domicilio`, `direccion_laboral`, `pais`, `id_ocupacion`, `desc_ocupacion`, `telefono_domicilio`, `telefono_oficina`, `telefono_celular`, `email`, `peso`, `estatura`, `genero`, `edad`, `mano`) 
					VALUES ("'.$idcl.'", "'.base64_decode($_SESSION['idEF']).'", '.(int)$cl_type_client.', "'.$cl_company_name.'", 
						"'.$cl_patern.'", "'.$cl_matern.'", "'.$cl_name.'", "'.$cl_married.'", "'.$cl_date_birth.'", "", 
						"'.$cl_dni.'", '.$cl_ext.', "'.$cl_comp.'", "", "", "'.$cl_attached.'", '.$cl_place_res.', 
						"'.$cl_locality.'", "'.$cl_avc.'", "'.$cl_address_home.'", "'.$cl_nhome.'", "'.$cl_address_work.'", 
						"BOLIVIA", '.$cl_occupation.', "'.$cl_desc_occ.'", "'.$cl_phone_home.'", "'.$cl_phone_office.'", 
						"'.$cl_phone_cel.'", "'.$cl_email.'", "0", "0", "'.$cl_gender.'", 
						TIMESTAMPDIFF(YEAR, "'.$cl_date_birth.'", curdate()), "") ;';
				} else {
					$sqlCl = 'UPDATE s_cliente 
					SET `razon_social` = "'.$cl_company_name.'", `paterno` = "'.$cl_patern.'", `materno` = "'.$cl_matern.'", 
						`nombre` = "'.$cl_name.'", `ap_casada` = "'.$cl_married.'", `fecha_nacimiento` = "'.$cl_date_birth.'", 
						`extension` = '.$cl_ext.', `complemento` = "'.$cl_comp.'", `ci_archivo` = "'.$cl_attached.'", 
						`lugar_residencia` = '.$cl_place_res.', `localidad` = "'.$cl_locality.'", `avenida` = "'.$cl_avc.'", 
						`direccion` = "'.$cl_address_home.'", `no_domicilio` = "'.$cl_nhome.'", 
						`direccion_laboral` = "'.$cl_address_work.'", `id_ocupacion` = '.$cl_occupation.', 
						`desc_ocupacion` = "'.$cl_desc_occ.'", `telefono_domicilio` = "'.$cl_phone_home.'", 
						`telefono_oficina` = "'.$cl_phone_office.'", `telefono_celular` = "'.$cl_phone_cel.'", 
						`email` = "'.$cl_email.'", `genero` = "'.$cl_gender.'", 
						`edad` = TIMESTAMPDIFF(YEAR, "'.$cl_date_birth.'", curdate())
					WHERE id_cliente = "'.$idcl.'" and id_ef = "'.base64_decode($_SESSION['idEF']).'"
						and tipo = '.(int)$cl_type_client.' ;';
				}
					
				if($link->query($sqlCl) === TRUE) {
					$record = $link->getRegistrationNumber($_SESSION['idEF'], 'TRD', 1, 'TRD');
					
					$sql = 'insert into s_trd_em_cabecera 
					(`id_emision`, `no_emision`, `id_ef`, `id_cotizacion`, 
					`certificado_provisional`, `garantia`, `tipo`, `id_cliente`, 
					`no_operacion`, `prefijo`, `ini_vigencia`, `fin_vigencia`, 
					`id_forma_pago`, `plazo`, `tipo_plazo`, `factura_nombre`, 
					`factura_nit`, `fecha_creacion`, `id_usuario`, `anulado`, 
					`and_usuario`, `fecha_anulado`, `motivo_anulado`, `emitir`, 
					`fecha_emision`, `id_compania`, `id_poliza`, `no_copia`, 
					`facultativo`, `motivo_facultativo`, `tasa`, `prima_total`, 
					`leido`) 
					values ("'.$ide.'", '.$record.', 
					"'.base64_decode($_SESSION['idEF']).'", "'.$idc.'", 
					'.$cp.', '.$dcr_warranty.', '.(int)$cl_type_client.', 
					"'.$idcl.'", "'.$dcr_opp.'", "TRD", "'.$dcr_date_begin.'", 
					"'.$dcr_date_end.'", "'.$dcr_method_payment.'", '.$dcr_term.', 
					"'.$dcr_type_term.'", "'.$bl_name.'", "'.$bl_nit.'", 
					curdate(), "'.base64_decode($_SESSION['idUser']).'", 0, 
					"'.base64_decode($_SESSION['idUser']).'", "", "", FALSE, 
					"", "'.$idcia.'", '.$dcr_policy.', 0, '.(int)$_FAC.', 
					"'.$_FAC_REASON.'", '.$TASA.', '.$PRIMA.', FALSE ) ;';
					
					if($link->query($sql) === TRUE) {
						$auxPrefix = null;
						
						$sqlPr = 'insert into s_trd_em_detalle 
						(`id_inmueble`, `id_emision`, `no_detalle`, 
						`prefijo`, `prefix`, `tipo_in`, 
						`uso`, `uso_otro`, `estado`, `departamento`, 
						`zona`, `localidad`, `direccion`,`modalidad`, 
						`valor_asegurado`, `tasa`, `prima`, 
						`facultativo`, `motivo_facultativo`, 
						`aprobado`, `leido`, `in_archivo`) VALUES ';
						
						for($k = 1; $k <= $nPr; $k++) {
							if ($swMo === true) {
								$link->getPrefixPolicyBanecoTRD(trim($arr_pr[$k]['modality'], '"'), $codeMethodPayment, $prefix);
							} else {
								$prefix[0] = 'TRD';
								$prefix[1] = '';
							}
							
							if ($auxPrefix === $prefix[0]) {
								$record += 1;
							} else {
								$record = $link->getRegistrationNumber($_SESSION['idEF'], 'TRD', 2, $prefix[0]);
							}
							
							$auxPrefix = $prefix[0];
							
							$arrPrefix = array (
								'policy' => $prefix[1],
								'prefix' => $prefix[0]
								);
							$arrPrefix = '"' . $link->real_escape_string(json_encode($arrPrefix)) . '"';
							
							$sqlPr .= '("'.$arr_pr[$k]['idpr'].'", "'.$ide.'",
							'.$record.', "'.$prefix[0].'", '.$arrPrefix.', 
							"'.$arr_pr[$k]['type'].'", "'.$arr_pr[$k]['use'].'", 
							"'.$arr_pr[$k]['use-other'].'", "'.$arr_pr[$k]['state'].'", 
							'.$arr_pr[$k]['depto'].', "'.$arr_pr[$k]['zone'].'", 
							"'.$arr_pr[$k]['locality'].'", "'.$arr_pr[$k]['address'].'",
							'.$arr_pr[$k]['modality'].', '.$arr_pr[$k]['value-insured'].', 
							'.$arr_pr[$k]['rate'].', '.$arr_pr[$k]['premium'].', 
							'.(int)$arr_pr[$k]['FAC'].', "'.$arr_pr[$k]['reason'].'", 
							'.(int)$arr_pr[$k]['approved'].', FALSE, 
							"'.$arr_pr[$k]['attached'].'") ';
							
							$record += 1;
							
							if($k < $nPr) { $sqlPr .= ', '; } elseif($k === $nPr) { $sqlPr .= ';'; };
						}
						
						if($link->query($sqlPr) === TRUE){
							$swReg = TRUE;
							$arrTR[1] = 'trd-quote.php?ms='.$ms.'&page='.$page.'&pr='.$pr.'&ide='.base64_encode($ide).'&flag='.md5('i-read').'&cia='.base64_encode($idcia).'';
							$arrTR[2] = 'La Póliza fue registrada con exito !';
						} else {
							$arrTR[2] = 'Los Inmuebles no pudieron ser registrados';
						}
					} else {
						$arrTR[2] = 'La Póliza no pudo ser registrada';
					}
				} else { $arrTR[2] = 'El Titular no pudo ser registrado'; }
			}elseif($sw === 3) {	// ACTUALIZAR POLIZA
				$sqlCl = 'UPDATE s_cliente 
					SET `razon_social` = "'.$cl_company_name.'", `paterno` = "'.$cl_patern.'", `materno` = "'.$cl_matern.'", 
						`nombre` = "'.$cl_name.'", `fecha_nacimiento` = "'.$cl_date_birth.'", 
						`extension` = '.$cl_ext.', `complemento` = "'.$cl_comp.'", `ci_archivo` = "'.$cl_attached.'", 
						`lugar_residencia` = '.$cl_place_res.', `localidad` = "'.$cl_locality.'", `avenida` = "'.$cl_avc.'", 
						`direccion` = "'.$cl_address_home.'", `no_domicilio` = "'.$cl_nhome.'", 
						`direccion_laboral` = "'.$cl_address_work.'", `id_ocupacion` = '.$cl_occupation.', 
						`desc_ocupacion` = "'.$cl_desc_occ.'", `telefono_domicilio` = "'.$cl_phone_home.'", 
						`telefono_oficina` = "'.$cl_phone_office.'", `telefono_celular` = "'.$cl_phone_cel.'", 
						`email` = "'.$cl_email.'", `genero` = "'.$cl_gender.'", 
						`edad` = TIMESTAMPDIFF(YEAR, "'.$cl_date_birth.'", curdate())
					WHERE id_cliente = "'.$idcl.'" and id_ef = "'.base64_decode($_SESSION['idEF']).'"
						and tipo = '.(int)$cl_type_client.' ;';
					
				if($link->query($sqlCl) === TRUE) {
					$sql = 'UPDATE s_trd_em_cabecera
						SET `no_operacion` = "'.$dcr_opp.'", `ini_vigencia` = "'.$dcr_date_begin.'", 
						`fin_vigencia` = "'.$dcr_date_end.'", `id_forma_pago` = "'.$dcr_method_payment.'", `plazo` = '.$dcr_term.', 
						`tipo_plazo` = "'.$dcr_type_term.'", `factura_nombre` = "'.$bl_name.'", `factura_nit` = "'.$bl_nit.'", 
						`id_poliza` = '.$dcr_policy.', `no_copia` = 0, `facultativo` = '.(int)$_FAC.', 
						`motivo_facultativo` = "'.$_FAC_REASON.'", `tasa` = '.$TASA.', `prima_total` = '.$PRIMA.', `leido` = FALSE
						WHERE id_emision = "'.$ide.'" and id_ef = "'.base64_decode($_SESSION['idEF']).'" ;';
					
					if($link->query($sql) === TRUE) {
						$sqlPr = '';
						for($k = 1; $k <= $nPr; $k++) {//
							$sqlPr .= 'update s_trd_em_detalle
							set `tipo_in` = "'.$arr_pr[$k]['type'].'", `uso` = "'.$arr_pr[$k]['use'].'",
								`uso_otro` = "'.$arr_pr[$k]['use-other'].'", `estado` = "'.$arr_pr[$k]['state'].'",
								`departamento` = '.$arr_pr[$k]['depto'].', `zona` = "'.$arr_pr[$k]['zone'].'",
								`localidad` = "'.$arr_pr[$k]['locality'].'", `direccion` = "'.$arr_pr[$k]['address'].'",
								`modalidad` = '.$arr_pr[$k]['modality'].', `valor_asegurado` = '.$arr_pr[$k]['value-insured'].', 
								`tasa` = '.$arr_pr[$k]['rate'].', `prima` = '.$arr_pr[$k]['premium'].', 
								`facultativo` = '.(int)$arr_pr[$k]['FAC'].', `motivo_facultativo` = "'.$arr_pr[$k]['reason'].'", 
								`aprobado` = '.(int)$arr_pr[$k]['approved'].' 
							where id_inmueble = "'.$arr_pr[$k]['idpr'].'" and id_emision = "'.$ide.'" ;';
						}
						
						if($link->multi_query($sqlPr) === TRUE) {
							$swPr = FALSE;
							do{
								if($link->errno !== 0)
									$swPr = TRUE;
							}while($link->next_result());
							
							if($swPr === FALSE) {
								$swReg = TRUE;
								$arrTR[1] = 'trd-quote.php?ms='.$ms.'&page='.$page.'&pr='.$pr.'&ide='.base64_encode($ide).'&flag='.md5('i-read').'&cia='.base64_encode($idcia).$target;
								$arrTR[2] = 'La Póliza fue actualizada correctamente !';
							} else {
								$arrTR[2] = 'Los datos de los Inmuebles no fueron actualizados';
							}
						} else {
							$arrTR[2] = 'Los datos del Inmueble no fueron actualizados';
						}
					} else {
						$arrTR[2] = 'Los Póliza no pudo ser actualizada';
					}
				} else {
					$arrTR[2] = 'Los datos del Titular no fueron actualizados';
				}
			}
			
			if($swReg === TRUE) {
				$arrTR[0] = 1;
			} else {
				 $arrTR[2] = 'Error. No se pudo registrar la Póliza'; 
			}
		}else {
			$arrTR[2] = 'La Póliza no puede ser registrada';
		}
		
		echo json_encode($arrTR);
	}else{
		$arrTR[2] = 'La Póliza no puede ser registrada.';
		echo json_encode($arrTR);
	}
}else{
	$arrTR[2] = 'La Póliza no puede ser registrada. |';
	echo json_encode($arrTR);
}
?>