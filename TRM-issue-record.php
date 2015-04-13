<?php
require('sibas-db.class.php');
require('session.class.php');

$session = new Session();
$token = $session->check_session();

$arrTR = array(0 => 0, 1 => 'R', 2 => 'Error: No se pudo registrar la Póliza');

if((isset($_POST['de-ide']) || isset($_POST['de-idc'])) && isset($_POST['dc-type-client']) && isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['pr']) && isset($_POST['cia'])){
	
	if($_POST['pr'] === base64_encode('TRM|05') && $token === TRUE){
		$link = new SibasDB();
		
		$_FAC = FALSE;
		$_FAC_REASON = '';
		$PRIMA = 0;
		$TASA = 0;
		$VALUE_INSURED = 0;
		$swMo = false;
		
		$ms = $link->real_escape_string(trim($_POST['ms']));
		$page = $link->real_escape_string(trim($_POST['page']));
		$pr = $link->real_escape_string(trim($_POST['pr']));
		
		$max_item = $max_amount = $max_anio = 0;
		if (($rowTR = $link->get_max_amount_optional($_SESSION['idEF'], 'TRM')) !== FALSE) {
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
			$ide = uniqid('@S#4$2013',true);
		}
		
		if($sw !== 0){
			$idcia = $link->real_escape_string(trim(base64_decode($_POST['cia'])));
			$dcr_amount = 0;
			$dcr_warranty = (int)$link->real_escape_string(trim(base64_decode($_POST['di-warranty'])));
			$dcr_date_begin = $link->real_escape_string(trim(base64_decode($_POST['di-date-inception'])));
			$dcr_date_end = $link->real_escape_string(trim(base64_decode($_POST['di-end-inception'])));
			$dcr_term = $link->real_escape_string(trim($_POST['di-term']));
			$dcr_type_term = $link->real_escape_string(trim($_POST['di-type-term']));
			$dcr_method_payment = $link->real_escape_string(trim(base64_decode($_POST['di-method-payment'])));
			$dcr_opp = $link->real_escape_string(trim($_POST['di-opp']));
			$dcr_policy = 'null';
			if (isset($_POST['di-policy'])) {
				$dcr_policy = '"' . $link->real_escape_string(trim(base64_decode($_POST['di-policy']))). '"';
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
			} else { $idcl = uniqid('@S#4$2013',true); }
			
			
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
			
			$cl_attached = $link->real_escape_string(trim(base64_decode($_POST['dc-attached'])));
			$nMt = (int)$link->real_escape_string(trim(base64_decode($_POST['nMt'])));
			
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
			
			$arr_mt = array();
			
			if($nMt <= $max_item){
				for($k = 1; $k <= $nMt; $k++){
					if(isset($_POST['dm-'.$k.'-idmt'])) { 
						$arr_mt[$k]['idmt'] = $link->real_escape_string(trim(base64_decode($_POST['dm-'.$k.'-idmt'])));
					} else { $arr_mt[$k]['idmt'] = uniqid('@S#4$2013'.$k, true); }
					
					$arr_mt[$k]['material'] = $link->real_escape_string(trim($_POST['dm-'.$k.'-material']));
					$arr_mt[$k]['modality'] = 'null';
					if (isset($_POST['dm-'.$k.'-modality'])) {
						$arr_mt[$k]['modality'] = '"' 
							. $link->real_escape_string(trim(base64_decode($_POST['dm-'.$k.'-modality']))) . '"';
						$swMo = true;
					}
					$arr_mt[$k]['value-insured'] = $link->real_escape_string(trim(base64_decode($_POST['dm-'.$k.'-value-insured'])));
					$arr_mt[$k]['rate'] = $link->real_escape_string(trim(base64_decode($_POST['dm-'.$k.'-rate'])));
					$arr_mt[$k]['premium'] = $link->real_escape_string(trim(base64_decode($_POST['dm-'.$k.'-premium'])));
					$arr_mt[$k]['attached'] = $link->real_escape_string(trim(base64_decode($_POST['dm-'.$k.'-attached'])));
					
					$VALUE_INSURED += (int)$arr_mt[$k]['value-insured'];
					$PRIMA += (float)$arr_mt[$k]['premium'];
					$TASA = (float)$arr_mt[$k]['rate'];
				}
			}else {
				$arrTR[2] = 'La Materia Asegurada no puede ser Registrada';
			}
			
			if ($VALUE_INSURED > $max_amount) {
				$_FAC = TRUE;
				$_FAC_REASON = 'La suma de los valores asegurados excede el máximo valor permitido. Valor permitido: '.number_format($max_amount, 2, '.', ',').' USD';
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
					$record = $link->getRegistrationNumber($_SESSION['idEF'], 'TRM', 1, 'TRM');
					
					$sql = 'insert into s_trm_em_cabecera 
					(`id_emision`, `no_emision`, `id_ef`, `id_cotizacion`, 
					`certificado_provisional`, `garantia`, `tipo`, 
					`id_cliente`, `no_operacion`, `prefijo`, `ini_vigencia`, 
					`fin_vigencia`, `id_forma_pago`, `plazo`, `tipo_plazo`, 
					`factura_nombre`, `factura_nit`, `fecha_creacion`, 
					`id_usuario`, `anulado`, `and_usuario`, `fecha_anulado`, 
					`motivo_anulado`, `emitir`, `fecha_emision`, `id_compania`, 
					`id_poliza`, `no_copia`, `facultativo`, `motivo_facultativo`, 
					`tasa`, `prima_total`, `valor_asegurado_total`, `leido`) 
					values 
					("'.$ide.'", '.$record.', 
					"'.base64_decode($_SESSION['idEF']).'", "'.$idc.'", '.$cp.', 
					'.$dcr_warranty.', '.(int)$cl_type_client.', "'.$idcl.'", 
					"'.$dcr_opp.'", "TRM", "'.$dcr_date_begin.'", 
					"'.$dcr_date_end.'", "'.$dcr_method_payment.'", '.$dcr_term.', 
					"'.$dcr_type_term.'", "'.$bl_name.'", "'.$bl_nit.'", 
					curdate(), "'.base64_decode($_SESSION['idUser']).'", false, 
					"'.base64_decode($_SESSION['idUser']).'", "", "", false, "", 
					"'.$idcia.'", '.$dcr_policy.', 0, '.(int)$_FAC.', 
					"'.$_FAC_REASON.'", '.$TASA.', '.$PRIMA.', 
					'.$VALUE_INSURED.', false);';
					
					if($link->query($sql) === TRUE) {
						if ($swMo === true) {
							$link->getPrefixPolicyBanecoTRM($prefix);
						} else {
							$prefix[0] = 'TRM';
							$prefix[1] = '';
						}
						
						$record = $link->getRegistrationNumber($_SESSION['idEF'], 'TRM', 2, $prefix[0]);
						
						$arrPrefix = array (
							'policy' => $prefix[1],
							'prefix' => $prefix[0]
							);
						$arrPrefix = '"' . $link->real_escape_string(json_encode($arrPrefix)) . '"';
						
						$sqlMt = 'insert into s_trm_em_detalle 
						(`id_material`, `id_emision`, `no_detalle`, 
						`prefijo`, `prefix`, `material`,  
						`modalidad`, `valor_asegurado`, `tasa`, 
						`prima`, `mt_archivo`) 
						values ';
						
						for($k = 1; $k <= $nMt; $k++) {
							$sqlMt .= '("'.$arr_mt[$k]['idmt'].'", "'.$ide.'",
							'.$record.', "'.$prefix[0].'", '.$arrPrefix.', 
							"'.$arr_mt[$k]['material'].'", '.$arr_mt[$k]['modality'].', 
							'.$arr_mt[$k]['value-insured'].', '.$arr_mt[$k]['rate'].', 
							'.$arr_mt[$k]['premium'].', "'.$arr_mt[$k]['attached'].'") ';
							
							$record += 1;
							
							if($k < $nMt) { $sqlMt .= ', '; } elseif($k === $nMt) { $sqlMt .= ';'; };
						}
						
						if($link->query($sqlMt) === TRUE){
							$swReg = TRUE;
							$arrTR[1] = 'trm-quote.php?ms='.$ms.'&page='.$page.'&pr='.$pr.'&ide='.base64_encode($ide).'&flag='.md5('i-read').'&cia='.base64_encode($idcia).'';
							$arrTR[2] = 'La Póliza fue registrada con exito !';
						} else {
							$arrTR[2] = 'La Materia Asegurada no pudo ser registrada';
						}
					} else {
						$arrTR[2] = 'La Póliza no pudo ser registrada';
					}
				} else { $arrTR[2] = 'El Arrendatario no pudo ser registrado'; }
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
					$sql = 'UPDATE s_trm_em_cabecera 
						SET 
						`no_operacion` = "'.$dcr_opp.'", `ini_vigencia` = "'.$dcr_date_begin.'", 
						`fin_vigencia` = "'.$dcr_date_end.'", `id_forma_pago` = "'.$dcr_method_payment.'", `plazo` = '.$dcr_term.', 
						`tipo_plazo` = "'.$dcr_type_term.'", `factura_nombre` = "'.$bl_name.'", `factura_nit` =  "'.$bl_nit.'", 
						`id_poliza` = '.$dcr_policy.', `no_copia` = 0, `facultativo` = '.(int)$_FAC.', 
						`motivo_facultativo` = "'.$_FAC_REASON.'", `tasa` = '.$TASA.', `prima_total` = '.$PRIMA.', 
						`valor_asegurado_total` = '.$VALUE_INSURED.', `leido` = false
						WHERE id_emision = "'.$ide.'" and id_ef = "'.base64_decode($_SESSION['idEF']).'" ;';
					
					if($link->query($sql) === TRUE) {
						$sqlMt = '';
						for($k = 1; $k <= $nMt; $k++) {
							$sqlMt .= 'update s_trm_em_detalle 
							set `material` = "'.$arr_mt[$k]['material'].'", `modalidad` = '.$arr_mt[$k]['modality'].', 
								`valor_asegurado` = '.$arr_mt[$k]['value-insured'].', `tasa` = '.$arr_mt[$k]['rate'].', 
								`prima` = '.$arr_mt[$k]['premium'].', `mt_archivo` = "'.$arr_mt[$k]['attached'].'"
							where id_material = "'.$arr_mt[$k]['idmt'].'" and id_emision = "'.$ide.'" ;';
						}
						
						if($link->multi_query($sqlMt) === TRUE) {
							$swVh = FALSE;
							do{
								if($link->errno !== 0)
									$swVh = TRUE;
							}while($link->next_result());
							
							if($swVh === FALSE) {
								$swReg = TRUE;
								$arrTR[1] = 'trm-quote.php?ms='.$ms.'&page='.$page.'&pr='.$pr.'&ide='.base64_encode($ide).'&flag='.md5('i-read').'&cia='.base64_encode($idcia).$target;
								$arrTR[2] = 'La Póliza fue actualizada correctamente !';
							} else {
								$arrTR[2] = 'Los datos de la Materia Asegurada no fueron actualizados';
							}
						} else {
							$arrTR[2] = 'Los datos de la Materia Asegurada no fueron actualizados';
						}
					} else {
						$arrTR[2] = 'Los Póliza no pudo ser actualizada';
					}
				} else {
					$arrTR[2] = 'Los datos del Arrendatario no fueron actualizados';
				}
			}
			
			if($swReg === TRUE) {
				$arrTR[0] = 1;
			} else { $arrTR[2] = 'Error. No se pudo registrar la Póliza'; }
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