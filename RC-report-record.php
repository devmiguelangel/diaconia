<?php
require('sibas-db.class.php');
$arrRC = array(0 => 0, 1 => 'R', 2 => '');
if(isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['rc-id-client']) && isset($_POST['rc-denounced-id']) && isset($_POST['rc-npolicy']) && isset($_POST['idef'])){
	$link = new SibasDB();

	$ms = $link->real_escape_string(trim($_POST['ms']));
	$page = $link->real_escape_string(trim($_POST['page']));

	$new = TRUE;
	if(isset($_POST['rc-cl-exists']))
		$new = FALSE;

	$idRc = '';
	$flag = FALSE;
	if(isset($_POST['idRc'])){
		$idRc = $link->real_escape_string(trim(base64_decode($_POST['idRc'])));
		$flag = TRUE;
	}

	$idef = $link->real_escape_string(trim(base64_decode($_POST['idef'])));
	$rcDateReg = $link->real_escape_string(trim(date('Y-m-d', strtotime(str_replace('/','-',$_POST['rc-date-reg'])))));
	$rcIdCl = $link->real_escape_string(trim(base64_decode($_POST['rc-id-client'])));
	$rcTypeDoc = $link->real_escape_string(trim($_POST['rc-type-doc']));
	$rcDni = $link->real_escape_string(trim(trim($_POST['rc-dni'])));
	$rcExt = $link->real_escape_string(trim($_POST['rc-ext']));
	$rcName = $link->real_escape_string(trim(mb_strtoupper($_POST['rc-name'])));
	$rcPatern = $link->real_escape_string(trim(mb_strtoupper($_POST['rc-patern'])));
	$rcMatern = $link->real_escape_string(trim(mb_strtoupper($_POST['rc-matern'])));
	$rcMarried = $link->real_escape_string(trim(mb_strtoupper($_POST['rc-married'])));
	$rcImage = $link->real_escape_string(trim($_POST['rc-name']));
	$rcDateSinister = $link->real_escape_string(trim($_POST['rc-date-sinister']));
	$rcCircumstance = $link->real_escape_string(trim($_POST['rc-circumstance']));
	$rcRepPerson = $link->real_escape_string(trim($_POST['rc-rep-person']));
	$rcRepPhone = $link->real_escape_string(trim($_POST['rc-rep-phone']));
	$rcRepEmail = $link->real_escape_string(trim($_POST['rc-rep-email']));
	$rcDateElaboration = $link->real_escape_string(trim($_POST['rc-date-elaboration']));
	$rcDenouncedId = $link->real_escape_string(trim(base64_decode($_POST['rc-denounced-id'])));
	$rcSubsidiary = $link->real_escape_string(trim($_POST['rc-subsidiary']));
	$rcAgency = $_POST['rc-agency'];

	if (empty($rcAgency) === true) {
		$rcAgency = 'NULL';
	} else {
		$rcAgency = '"'.$link->real_escape_string(trim(base64_decode($rcAgency))).'"';
	}
	$rcNPolicy = trim($link->real_escape_string(trim($_POST['rc-npolicy'])),'|');
	$rcNPolicy = explode('|', $rcNPolicy);
	$nRC = count($rcNPolicy);

	if ($new === TRUE) {
		$rcIdCl = '"'.$rcIdCl.'"';
	} elseif ($new === FALSE) {
		$rcIdCl = 'NULL';
	}

	if($flag === FALSE){
		$idRc = uniqid('@S#1$2013',true);
		$sqlSi = 'INSERT INTO s_siniestro
			(`id_siniestro`, `id_ef`, `fecha_registro`, `cliente_nuevo`, `id_cliente`, `tipo_documento`, `ci`, `extension`, `paterno`, `materno`, `nombre`, `ap_casada`, `imagen`, `fecha_siniestro`, `circunstancia`, `denuncia_persona`, `denuncia_telefono`, `denuncia_email`, `fecha_elaboracion`, `denunciado_por`, `sucursal`, `agencia`)
			VALUES
			("'.$idRc.'", "'.$idef.'", "'.$rcDateReg.'", '.(int)$new.', '.$rcIdCl.', "'.$rcTypeDoc.'", "'.$rcDni.'", "'.$rcExt.'", "'.$rcPatern.'", "'.$rcMatern.'", "'.$rcName.'", "'.$rcMarried.'", "image.jpg", "'.$rcDateSinister.'", "'.$rcCircumstance.'", "'.$rcRepPerson.'", "'.$rcRepPhone.'", "'.$rcRepEmail.'", "'.$rcDateElaboration.'", "'.$rcDenouncedId.'", '.$rcSubsidiary.', '.$rcAgency.') ;';
	}else{
		$sqlSi = 'UPDATE s_siniestro SET
				`tipo_documento` = "'.$rcTypeDoc.'", `extension` = "'.$rcExt.'",
				`paterno` = "'.$rcPatern.'", `materno` = "'.$rcMatern.'",	`nombre` = "'.$rcName.'",
				`ap_casada` = "'.$rcMarried.'", `imagen` = "image.jpg",
				`fecha_siniestro` = "'.$rcDateSinister.'", `circunstancia` = "'.$rcCircumstance.'",
				`denuncia_persona` = "'.$rcRepPerson.'",	`denuncia_telefono` = "'.$rcRepPhone.'",
				`denuncia_email` = "'.$rcRepEmail.'", `fecha_elaboracion` = "'.$rcDateElaboration.'",
			`sucursal` = '.$rcSubsidiary.', `agencia` = '.$rcAgency.'
			 WHERE id_siniestro = "'.$idRc.'" and id_ef = "'.$idef.'" ;';
	}

	if($link->query($sqlSi) === TRUE){
		$token = FALSE;
		if($nRC > 0){
			$arrDet = array();
			for($k = 0; $k < $nRC; $k++){
				if($flag === FALSE) {
					$arrDet[$k]['rcIdd'] = uniqid('@S#1$2013-'.$rcNPolicy[$k],true);
				} else {
					$arrDet[$k]['rcIdd'] = $link->real_escape_string(trim(base64_decode($_POST['rc-'.$rcNPolicy[$k].'-idd'])));
				}
				$arrDet[$k]['token'] = $rcNPolicy[$k];
				$arrDet[$k]['rcIde'] = $link->real_escape_string(trim(base64_decode($_POST['rc-'.$rcNPolicy[$k].'-ide'])));
				$arrDet[$k]['rcNCertified'] = $link->real_escape_string(trim($_POST['rc-'.$rcNPolicy[$k].'-ncertified']));

				$arrDet[$k]['rcLoanType'] = $link->real_escape_string(trim($_POST['rc-'.$rcNPolicy[$k].'-loan-type']));
				$arrDet[$k]['rcNOcredit'] = $link->real_escape_string(trim($_POST['rc-'.$rcNPolicy[$k].'-nocredit']));

				$arrDet[$k]['rcAmount'] = $link->real_escape_string(trim($_POST['rc-'.$rcNPolicy[$k].'-amount']));
				$arrDet[$k]['rcAmountType'] = $link->real_escape_string(trim($_POST['rc-'.$rcNPolicy[$k].'-amount-type']));
			}

			if($flag === FALSE){
				$sqlSd = 'INSERT INTO s_siniestro_detalle (`id_detalle`, `id_siniestro`, `detalle_nuevo`, `token`, `id_emision`, `no_emision`, `tipo_credito`, `no_credito`, `monto_desembolso`, `moneda`)
				VALUES ';
				for($k = 0; $k < count($arrDet); $k++){
					$sqlSd .= '("'.$arrDet[$k]['rcIdd'].'", "'.$idRc.'", '.(int)$new.', '.$arrDet[$k]['token'].',
						"'.$arrDet[$k]['rcIde'].'", "'.$arrDet[$k]['rcNCertified'].'", "'.$arrDet[$k]['rcLoanType'].'",
						"'.$arrDet[$k]['rcNOcredit'].'", '.$arrDet[$k]['rcAmount'].',
						"'.$arrDet[$k]['rcAmountType'].'"), ';
				}

				$sqlSd = trim(trim($sqlSd),',');
				$sqlSd .= ';';

				if($link->query($sqlSd) === TRUE)
					$token = TRUE;
				else
					$token = FALSE;
			}else{
				$sqlSd = '';
				for($k = 0; $k < count($arrDet); $k++){
					$sqlSd .= 'UPDATE s_siniestro_detalle SET
						`no_emision` = "'.$arrDet[$k]['rcNCertified'].'", `tipo_credito` = "'.$arrDet[$k]['rcLoanType'].'",
						`no_credito` = "'.$arrDet[$k]['rcNOcredit'].'", `monto_desembolso` = '.$arrDet[$k]['rcAmount'].',
						`moneda` = "'.$arrDet[$k]['rcAmountType'].'"
						WHERE id_detalle = "'.$arrDet[$k]['rcIdd'].'" AND id_siniestro = "'.$idRc.'" ;';
				}

				if($link->multi_query($sqlSd) === TRUE){
					$token = TRUE;
					do{
						if($link->errno !== 0)
							$token = FALSE;
					}while($link->next_result());
				}else
					$token = FALSE;
			}
		}else{
			$token = TRUE;
		}

		if($token === TRUE){
			$arrRC[0] = 1;
			$arrRC[1] = 'rc-report.php?ms='.$ms.'&page='.$page.'&rc='.base64_encode($idRc);
			if($flag === FALSE)
				$arrRC[2] = 'El Siniestro se registró con exito !';
			else
				$arrRC[2] = 'El Siniestro se actualizó con exito !';
		}else{
			if($flag === FALSE)
				$arrRC[2] = 'Error: No se pudo registrar el Siniestro y su Detalle';
			else
				$arrRC[2] = 'Error: No se pudo actualizar el Siniestro y su Detalle';
		}
	}else{
		if($flag === FALSE)
			$arrRC[2] = 'Error: No se pudo registrar el Siniestro';
		else
			$arrRC[2] = 'Error: No se pudo actualizar el Siniestro';
	}

	echo json_encode($arrRC);
}else{
	$arrRC[2] = 'No se puede procesar el Siniestro';
	echo json_encode($arrRC);
}
?>