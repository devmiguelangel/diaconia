<?php
require('sibas-db.class.php');
session_start();
$arrTH = array(0 => 0, 1 => 'R', 2 => 'Error: No se pudo registrar el Titular');

if(isset($_POST['dc-token']) && isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['pr']) && isset($_POST['id-ef'])){
	
	if($_POST['pr'] === base64_encode('TH|03')){
		$link = new SibasDB();
		
		$idc = NULL;
		if (isset($_POST['dc-idc'])) {
			$idc = $link->real_escape_string(trim(base64_decode($_POST['dc-idc'])));
		}
		$idef = $link->real_escape_string(trim(base64_decode($_POST['id-ef'])));
		
		$idClient = 0;
		$flag = false;
		if(isset($_POST['dc-idCl'])){
			$flag = true;
			$idClient = $link->real_escape_string(trim(base64_decode($_POST['dc-idCl'])));
		}
		
		$card = $link->real_escape_string(trim($_POST['di-card']));
		$di_card = explode('|', $card);
		$di_card[0] = base64_decode($di_card[0]);
		$di_make_card = 'null';
		if ($di_card[1] === 'TC') {
			$di_make_card = '"' . $link->real_escape_string(trim(base64_decode($_POST['di-make-card']))) . '"';
		}
		$di_modality = 'null';
		if (isset($_POST['di-modality'])) {
			if (md5('1') === $_POST['di-modality']) {
				$di_modality = '"' . $di_card[1] . '"';
			}
		}
		$di_no_card = $link->real_escape_string(trim($_POST['di-no-card']));
		$prefix = array();
		$arrPrefix = 'null';
		
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
		$dc_gender = $link->real_escape_string(trim($_POST['dc-gender']));
		$dc_phone_1 = $link->real_escape_string(trim($_POST['dc-phone-1']));
		$dc_phone_2 = $link->real_escape_string(trim($_POST['dc-phone-2']));
		$dc_email = $link->real_escape_string(trim($_POST['dc-email']));
		$dc_company_email = $link->real_escape_string(trim($_POST['dc-company-email']));
		$dc_phone_office = $link->real_escape_string(trim($_POST['dc-phone-office']));
		$dni = '';
		$ext = '';
		$email = '';
		
		if($dc_gender === 'M') { $dc_lnmarried = ''; }
		
		$ms = $link->real_escape_string(trim($_POST['ms']));
		$page = $link->real_escape_string(trim($_POST['page']));
		$pr = $link->real_escape_string(trim($_POST['pr']));
		
		if($dc_type_client === 'NAT'){
			$dc_type_client = FALSE;
			$dni = $dc_doc_id;
			$ext = $dc_ext;
			$email = $dc_email;
			$dc_company_name = '';
		}elseif($dc_type_client === 'JUR'){
			$dc_type_client = TRUE;
			$dni = $dc_nit;
			$ext = $dc_depto;
			$email = $dc_company_email;
			$dc_gender = '';
			$dc_phone_1 = '';
		}
		
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
					ssh.producto = "TH"
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
					$arrTH[0] = 1;
					$arrTH[1] = 'th-quote.php?ms='.$ms.'&page='.$page.'&pr='.$pr.'&idc='.base64_encode($idc);
					$arrTH[2] = 'Los Datos se actualizaron correctamente';
				}else{
					$arrTH[2] = 'No se pudo actualizar los datos';
				}
			}else{
				$vc = $link->verify_customer($dni, $ext, $idef, 'TH');
				
				if($vc[0] === TRUE){
					$idClient = $vc[1];
					
					$sql = 'update s_th_cot_cliente 
					set `razon_social` = "'.$dc_company_name.'", `paterno` = "'.$dc_lnpatern.'", 
						`materno` = "'.$dc_lnmatern.'", `nombre` = "'.$dc_name.'", `ap_casada` = "'.$dc_lnmarried.'", 
						`fecha_nacimiento` = "'.$dc_birth.'", `ci` = "'.$dni.'", `extension` = '.$ext.', 
						`complemento` = "'.$dc_comp.'", `genero` = "'.$dc_gender.'", `telefono_domicilio` = "'.$dc_phone_1.'", 
						`telefono_oficina` = "'.$dc_phone_office.'", `telefono_celular` = "'.$dc_phone_2.'", 
						`email` = "'.$email.'"
					where id_cliente = "'.$idClient.'" and id_ef = "'.$idef.'" ;';
				}else{
					$idClient = uniqid('@S#5$2013',true);
					
					$sql = 'INSERT INTO s_th_cot_cliente (`id_cliente`, `id_ef`, `tipo`, `razon_social`, `paterno`, `materno`, `nombre`, `ap_casada`, `fecha_nacimiento`, `ci`, `extension`, `complemento`, `genero`, `telefono_domicilio`, `telefono_oficina`, `telefono_celular`, `email`) 
						VALUES ("'.$idClient.'", "'.$idef.'", '.(int)$dc_type_client.', "'.$dc_company_name.'", 
							"'.$dc_lnpatern.'", "'.$dc_lnmatern.'", "'.$dc_name.'", "'.$dc_lnmarried.'", "'.$dc_birth.'", 
							"'.$dni.'", '.$ext.', "'.$dc_comp.'", "'.$dc_gender.'", "'.$dc_phone_1.'", "'.$dc_phone_office.'", 
							"'.$dc_phone_2.'", "'.$email.'") ;';
				}
				
				if($link->query($sql) === TRUE){
					$link->getPrefixPolicyBanecoTH($di_card[1], $prefix);
					$record = $link->getRegistrationNumber($_SESSION['idEF'], 'TH', 0, $prefix[0]);
					
					$arrPrefix = array (
						'policy' => $prefix[1],
						'prefix' => $prefix[0]
						);
					$arrPrefix = '"' . $link->real_escape_string(json_encode($arrPrefix)) . '"';
					
					if ($idc !== NULL) {
						$sqlIn = 'update s_th_cot_cabecera 
						set `no_cotizacion` = '.$record.', `prefijo` = "'.$prefix[0].'",
							`prefix` = '.$arrPrefix.', `id_cliente` = "'.$idClient.'", 
							`id_tarjeta` = "'.$di_card[0].'", 
							`no_tarjeta` = '.$di_no_card.', `id_marca` = '.$di_make_card.', 
							`modalidad` = '.$di_modality.', `prima_total` = 0.00
						where id_cotizacion = "'.$idc.'" ;';
					} else {
						$idc = uniqid('@S#5$2013',true);
						
						$sqlIn = 'insert into s_th_cot_cabecera 
						(`id_cotizacion`, `no_cotizacion`, `id_ef`, 
						`id_cliente`, `certificado_provisional`, `prefijo`, 
						`prefix`, `id_tarjeta`, `no_tarjeta`, `id_marca`, 
						`modalidad`, `fecha_creacion`, `id_usuario`)
						values
						("'.$idc.'", '.$record.', "'.$idef.'", "'.$idClient.'", 
						false, "'.$prefix[0].'", '.$arrPrefix.', "'.$di_card[0].'", 
						'.$di_no_card.', '.$di_make_card.', '.$di_modality.', 
						curdate(), "'.base64_decode($_SESSION['idUser']).'");';
					}

					if($link->query($sqlIn) === TRUE){
						$arrTH[0] = 1;
						$arrTH[1] = 'th-quote.php?ms='.$ms.'&page='.$page.'&pr='.$pr.'&idc='.base64_encode($idc);
						$arrTH[2] = 'Titular registrado con Exito';
					}else {
						$arrTH[2] = 'No se pudo registrar los datos del Seguro Solicitado'.$sqlIn;
					}
				}else {
					$arrTH[2] = 'No se pudo registrar el Titular';
				}
			}
		}else{
			$arrTH[2] = 'La Fecha de Nacimiento no esta en el rango permitido de Edades [ '.$rowAge['edad_min'].' - '.$rowAge['edad_max'].' ]';
		}
		echo json_encode($arrTH);
	}else{
		echo json_encode($arrTH);
	}
}else{
	echo json_encode($arrTH);
}
?>