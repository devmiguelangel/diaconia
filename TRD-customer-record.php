<?php
require('sibas-db.class.php');

$arrTR = array(0 => 0, 1 => 'R', 2 => 'Error: No se pudo registrar el Titular');

if(isset($_POST['dc-token']) && isset($_POST['dc-idc']) && isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['pr']) && isset($_POST['id-ef'])){
	
	if($_POST['pr'] === base64_encode('TRD|03')){
		$link = new SibasDB();
		
		$idc = $link->real_escape_string(trim(base64_decode($_POST['dc-idc'])));
		$idef = $link->real_escape_string(trim(base64_decode($_POST['id-ef'])));
		
		$idClient = 0;
		$flag = FALSE;
		if(isset($_POST['dc-idCl'])){
			$flag = TRUE;
			$idClient = $link->real_escape_string(trim(base64_decode($_POST['dc-idCl'])));
		}
		
		$di_date_inception = $link->real_escape_string(trim($_POST['di-date-inception']));
		$di_term = (int)$link->real_escape_string(trim($_POST['di-term']));
		$di_type_term = $link->real_escape_string(trim($_POST['di-type-term']));
		$di_method_payment = $link->real_escape_string(trim(base64_decode($_POST['di-method-payment'])));
		$di_warranty = $link->real_escape_string(trim($_POST['di-warranty']));
		if ($di_warranty === md5('1')) { $di_warranty = TRUE; }
		elseif ($di_warranty === md5('0')) { $di_warranty = FALSE; }
		else { $di_warranty = FALSE; }
		
		$dc_type_client = $link->real_escape_string(trim($_POST['dc-type-client']));
		$dc_name = $link->real_escape_string(trim($_POST['dc-name']));
		$dc_company_name = $link->real_escape_string(trim($_POST['dc-company-name']));
		$dc_lnpatern = $link->real_escape_string(trim($_POST['dc-ln-patern']));
		$dc_lnmatern = $link->real_escape_string(trim($_POST['dc-ln-matern']));
		$dc_lnmarried = $link->real_escape_string(trim($_POST['dc-ln-married']));
		$dc_doc_id = $link->real_escape_string(trim($_POST['dc-doc-id']));
		$dc_nit = $link->real_escape_string(trim($_POST['dc-nit']));
		$dc_comp = $link->real_escape_string(trim($_POST['dc-comp']));
		$dc_ext = $link->real_escape_string(trim($_POST['dc-ext']));
		$dc_depto = $link->real_escape_string(trim($_POST['dc-depto']));
		$dc_birth = $link->real_escape_string(trim($_POST['dc-date-birth']));
		$dc_place = $link->real_escape_string(trim($_POST['dc-place-res']));
		$dc_gender = $link->real_escape_string(trim($_POST['dc-gender']));
		$dc_phone_1 = $link->real_escape_string(trim($_POST['dc-phone-1']));
		$dc_phone_2 = $link->real_escape_string(trim($_POST['dc-phone-2']));
		$dc_email = $link->real_escape_string(trim($_POST['dc-email']));
		$dc_avc = $link->real_escape_string(trim($_POST['dc-avc']));
		$dc_address = $link->real_escape_string(trim($_POST['dc-address']));
		$dc_nd = $link->real_escape_string(trim($_POST['dc-nhome']));
		$dc_locality = $link->real_escape_string(trim($_POST['dc-locality']));
		$dc_dir_office = $link->real_escape_string(trim($_POST['dc-address-work']));
		$dc_occupation = $link->real_escape_string(trim(base64_decode($_POST['dc-occupation'])));
		$dc_desc_occ = $link->real_escape_string(trim($_POST['dc-desc-occ']));
		$dc_phone_office = $link->real_escape_string(trim($_POST['dc-phone-office']));
		$dc_company_email = $link->real_escape_string(trim($_POST['dc-company-email']));
		$dc_company_phone_office = $link->real_escape_string(trim($_POST['dc-company-phone-office']));
		$dc_company_avc = $link->real_escape_string(trim($_POST['dc-company-avc']));
		$dc_company_address = $link->real_escape_string(trim($_POST['dc-company-address']));
		$dc_company_nd = $link->real_escape_string(trim($_POST['dc-company-nhome']));
		$dc_company_locality = $link->real_escape_string(trim($_POST['dc-company-locality']));
		
		$dni = $ext = $email = $phone_office = $avc = $address = $nhome = $locality = '';
		
		if($dc_gender === 'M') { $dc_lnmarried = ''; }
		
		$ms = $link->real_escape_string(trim($_POST['ms']));
		$page = $link->real_escape_string(trim($_POST['page']));
		$pr = $link->real_escape_string(trim($_POST['pr']));
		
		if($dc_type_client === 'NAT'){
			$dc_type_client = FALSE;
			$dc_occupation = '"'.$dc_occupation.'"';
			$dni = $dc_doc_id;
			$ext = $dc_ext;
			$email = $dc_email;
			$phone_office = $dc_phone_office;
			$avc = $dc_avc;
			$address = $dc_address;
			$nhome = $dc_nd;
			$locality = $dc_locality;
			$dc_company_name = '';
		}elseif($dc_type_client === 'JUR'){
			$dc_type_client = TRUE;
			$dni = $dc_nit;
			$ext = $dc_depto;
			$email = $dc_company_email;
			$phone_office = $dc_company_phone_office;
			$avc = $dc_company_avc;
			$address = $dc_company_address;
			$nhome = $dc_company_nd;
			$locality = $dc_company_locality;
		 	$dc_place = $dc_occupation = 'NULL';
			$dc_gender = '';
			$dc_phone_1 = '';
		}
		
		$year = $link->get_year_final($di_term, $di_type_term);
		$di_date_end = '';
		
		$di_date_end = date('Y-m-d', strtotime($di_date_inception.' + '.$year.' year'));
		
		$swAge = 0;
		if($dc_type_client === FALSE){
			$sqlAge = 'SELECT 
					COUNT(ssh.id_home) as token,
					ssh.edad_max,
					ssh.edad_min,
					(TIMESTAMPDIFF(year,
						"'.$dc_birth.'",
						curdate()) between ssh.edad_min and ssh.edad_max) as flag
				from
					s_sgc_home as ssh
						inner join s_entidad_financiera as sef ON (sef.id_ef = ssh.id_ef)
				where
					ssh.producto = "TRD"
						and sef.id_ef = "'.$idef.'"
						and sef.activado = true
				;';
			
			$rsAge = $link->query($sqlAge,MYSQLI_STORE_RESULT);
			$rowAge = $rsAge->fetch_array(MYSQLI_ASSOC);
			$swAge = (int)$rowAge['flag'];
			$rsAge->free();
		}else{
			$swAge = 1;
		}
		
		if($swAge === 1){
			$sql = '';
			if($flag === TRUE){
				$sql = '';
				
				if($link->query($sql) === TRUE){
					$arrTR[0] = 1;
					$arrTR[1] = 'trd-quote.php?ms='.$ms.'&page='.$page.'&pr='.$pr.'&idc='.base64_encode($idc);
					$arrTR[2] = 'Los Datos se actualizaron correctamente';
				}else{
					$arrTR[2] = 'No se pudo actualizar los datos';
				}
			}else{
				$vc = $link->verify_customer($dni, $ext, $idef, 'TRD');
				
				if($vc[0] === TRUE){
					$idClient = $vc[1];
					
					$sql = 'UPDATE s_trd_cot_cliente 
						SET `razon_social` = "'.$dc_company_name.'", `paterno` = "'.$dc_lnpatern.'", 
							`materno` = "'.$dc_lnmatern.'", `nombre` = "'.$dc_name.'", `ap_casada` = "'.$dc_lnmarried.'", 
							`fecha_nacimiento` = "'.$dc_birth.'", `ci` = "'.$dni.'", `extension` = '.$ext.', 
							`complemento` = "'.$dc_comp.'", `lugar_residencia` = '.$dc_place.', `localidad` = "'.$locality.'", 
							`avenida` = "'.$avc.'", `direccion` = "'.$address.'", `no_domicilio` = "'.$nhome.'", 
							`direccion_laboral` = "'.$dc_dir_office.'", `id_ocupacion` = '.$dc_occupation.', 
							`desc_ocupacion` = "'.$dc_desc_occ.'", `telefono_domicilio` = "'.$dc_phone_1.'", 
							`telefono_oficina` = "'.$phone_office.'", `telefono_celular` = "'.$dc_phone_2.'", 
							`email` = "'.$email.'", `genero` = "'.$dc_gender.'"
						WHERE id_cliente = "'.$idClient.'" AND id_ef = "'.$idef.'" ;';
				}else{
					$idClient = uniqid('@S#3$2013',true);
					
					$sql = 'INSERT INTO s_trd_cot_cliente (`id_cliente`, `id_ef`, `tipo`, `razon_social`, `paterno`, `materno`, `nombre`, `ap_casada`, `fecha_nacimiento`, `ci`, `extension`, `complemento`, `lugar_residencia`, `pais`, `localidad`, `avenida`, `direccion`, `no_domicilio`, `direccion_laboral`, `id_ocupacion`, `desc_ocupacion`, `telefono_domicilio`, `telefono_oficina`, `telefono_celular`, `email`, `genero`) 
					VALUES ("'.$idClient.'", "'.$idef.'", '.(int)$dc_type_client.', "'.$dc_company_name.'", "'.$dc_lnpatern.'", 
						"'.$dc_lnmatern.'", "'.$dc_name.'", "'.$dc_lnmarried.'", "'.$dc_birth.'", "'.$dni.'", '.$ext.', 
						"'.$dc_comp.'", '.$dc_place.', "BOLIVIA", "'.$locality.'", "'.$avc.'", "'.$address.'", "'.$nhome.'", 
						"'.$dc_dir_office.'", '.$dc_occupation.', "'.$dc_desc_occ.'", "'.$dc_phone_1.'", 
						"'.$phone_office.'", "'.$dc_phone_2.'", "'.$email.'", "'.$dc_gender.'") ;';
				}
				
				if($link->query($sql) === TRUE){
					$sqlIn = 'UPDATE s_trd_cot_cabecera 
						SET `id_cliente` = "'.$idClient.'", `garantia` = '.(int)$di_warranty.', `tipo` = '.(int)$dc_type_client.', 
							`ini_vigencia` = "'.$di_date_inception.'", `fin_vigencia` = "'.$di_date_end.'", 
							`id_forma_pago` = "'.$di_method_payment.'", `plazo` = '.$di_term.', `tipo_plazo` = "'.$di_type_term.'" 
						WHERE id_cotizacion = "'.$idc.'" ;';
					
					if($link->query($sqlIn) === TRUE){
						$arrTR[0] = 1;
						$arrTR[1] = 'trd-quote.php?ms='.$ms.'&page='.$page.'&pr='.$pr.'&idc='.base64_encode($idc);
						$arrTR[2] = 'Titular registrado con Exito';
					}else {
						$arrTR[2] = 'No se pudo registrar los datos del Seguro Solicitado';
					}
				}else {
					$arrTR[2] = 'No se pudo registrar el Titular';
				}
			}
		}else{
			$arrTR[2] = 'La Fecha de Nacimiento no esta en el rango permitido de Edades [ '.$rowAge['edad_min'].' - '.$rowAge['edad_max'].' ]';
		}
		echo json_encode($arrTR);
	}else{
		echo json_encode($arrTR);
	}
}else{
	echo json_encode($arrTR);
}
?>