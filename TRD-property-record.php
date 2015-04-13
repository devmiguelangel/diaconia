<?php
require('sibas-db.class.php');
require('session.class.php');

$session = new Session();
$token = $session->check_session();
$arrTR = array(0 => 0, 1 => 'R', 2 => 'Error: No se pudo procesar el Inmueble');

$link = new SibasDB();

if($token === FALSE){
	if(($_ROOT = $link->get_id_root()) !== FALSE) {
		$_SESSION['idUser'] = base64_encode($_ROOT['idUser']);
	} else {
		$arrTR[0] = 1;
		$arrTR[1] = 'logout.php';
		$arrTR[2] = 'La Cotización no puede ser registrada, intentelo mas tarde';
	}
}

if(isset($_POST['dp-token']) && isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['pr']) && isset($_POST['idef'])){
	if($_POST['pr'] === base64_encode('TRD|01')){
		$link = new SibasDB();
		$idc = NULL;
		
		if (isset($_POST['dp-idc'])) {
			$idc = $link->real_escape_string(trim(base64_decode($_POST['dp-idc'])));
		}
		
		$idef = $link->real_escape_string(trim(base64_decode($_POST['idef'])));
		
		$idPr = 0;
		$flag = FALSE;
		$swMo = FALSE;
		$_FAC = FALSE;
		$reason = '';
		$token = false;
		
		if(isset($_POST['dp-idPr'])){
			$flag = TRUE;
			$idPr = $link->real_escape_string(trim(base64_decode($_POST['dp-idPr'])));
		}
		
		$max_item = $max_amount = $max_anio = 0;
		if (($rowTR = $link->get_max_amount_optional(base64_encode($idef), 'TRD')) !== FALSE) {
			$max_item = (int)$rowTR['max_item'];
			$max_amount = (int)$rowTR['max_monto'];
			$max_anio = (int)$rowTR['max_anio'];
		}
		
		$dp_type = $link->real_escape_string(trim(base64_decode($_POST['dp-type'])));
		$dp_use = $link->real_escape_string(trim($_POST['dp-use']));
		$dp_use_other = '';
		if($dp_use === 'OTH'){
			$dp_use_other = $link->real_escape_string(trim($_POST['dp-use-other']));
		}else {
			$dp_use = base64_decode($dp_use);
		}
		$dp_state = $link->real_escape_string(trim(base64_decode($_POST['dp-state'])));
		$dp_depto = $link->real_escape_string(trim(base64_decode($_POST['dp-depto'])));
		$dp_zone = $link->real_escape_string(trim($_POST['dp-zone']));
		$dp_locality = $link->real_escape_string(trim($_POST['dp-locality']));
		$dp_address = $link->real_escape_string(trim($_POST['dp-address']));
		$dp_modality = 'null';
		if (isset($_POST['dp-modality'])) {
			$dp_modality = '"' . $link->real_escape_string(trim(base64_decode($_POST['dp-modality']))) . '"';
		}
		$dp_value_insured = $link->real_escape_string(trim($_POST['dp-value-insured']));
		
		/*if($dv_value_insured > $max_amount){
			$_FAC = TRUE;
			$reason .= '| El valor asegurado del Vehículo excede el máximo valor permitido. Valor permitido: '.number_format($max_amount, 2, '.', ',').' USD';
		}*/
		
		$max_value = $link->get_cumulus($dp_value_insured, 'USD', base64_encode($idef), 'TRD');
		
		$ms = $link->real_escape_string(trim($_POST['ms']));
		$page = $link->real_escape_string(trim($_POST['page']));
		$pr = $link->real_escape_string(trim($_POST['pr']));
		
		if($max_value === 1){
			$sql = '';
			
			if ($idc === NULL) {
				$idc = uniqid('@S#3$2013',true);
				$record = $link->getRegistrationNumber($_SESSION['idEF'], 'TRD', 0);
				
				$sql = 'insert into s_trd_cot_cabecera 
				(`id_cotizacion`, `no_cotizacion`, `id_ef`, 
				`certificado_provisional`, `fecha_creacion`, `id_usuario`)
				values
				("'.$idc.'", '.$record.', "'.base64_decode($_SESSION['idEF']).'", false, 
				curdate(), "'.base64_decode($_SESSION['idUser']).'") ;';
				
				if ($link->query($sql) === true) {
					$token = true;
				}
			} else {
				$token = true;
			}
			
			if($flag === false && $token === true){
				$idPr = uniqid('@S#2$2013',true);
				$sql = 'insert into s_trd_cot_detalle 
				(`id_inmueble`, `id_cotizacion`, `tipo_in`, 
				`uso`, `uso_otro`, `estado`, `departamento`, 
				`zona`, `localidad`, `direccion`, `modalidad`, 
				`valor_asegurado`) 
				values 
				("'.$idPr.'", "'.$idc.'", "'.$dp_type.'", 
				"'.$dp_use.'", "'.$dp_use_other.'", "'.$dp_state.'", 
				'.$dp_depto.', "'.$dp_zone.'", "'.$dp_locality.'", 
				"'.$dp_address.'", '.$dp_modality.', '.$dp_value_insured.') ;';
				
				if($link->query($sql) === TRUE){
					$arrTR[0] = 1;
					$arrTR[1] = 'trd-quote.php?ms='.$ms.'&page='.$page.'&pr='.$pr.'&idc='.base64_encode($idc);
					$arrTR[2] = 'Inmueble registrado con Exito';
				}else {
					$arrTR[2] = 'No se pudo registrar el Inmueble';
				}
			} elseif ($token === true) {
				$sql = 'update s_trd_cot_detalle 
				set `tipo_in` = "'.$dp_type.'", `uso` = "'.$dp_use.'", 
					`uso_otro` = "'.$dp_use_other.'", `estado` = "'.$dp_state.'", 
					`departamento` = '.$dp_depto.', `zona` = "'.$dp_zone.'",
					`localidad` = "'.$dp_locality.'", `direccion` = "'.$dp_address.'", 
					`modalidad` = '.$dp_modality.', `valor_asegurado` = '.$dp_value_insured.'
				where id_inmueble = "'.$idPr.'" ;';
				
				if($link->query($sql) === TRUE){
					$arrTR[0] = 1;
					$arrTR[1] = 'trd-quote.php?ms='.$ms.'&page='.$page.'&pr='.$pr.'&idc='.base64_encode($idc);
					$arrTR[2] = 'Los Datos se actualizaron correctamente';
				}else {
					$arrTR[2] = 'No se pudo actualizar los datos';
				}
			}
		}else {
			$arrTR[2] = 'El Valor Asegurado no debe ser mayor a '.number_format($max_value, 2, '.', ',').' USD.';
		}
	}else {
		$arrTR[2] = 'El Inmueble no puede ser registrado';
	}
	
	echo json_encode($arrTR);
}else {
	echo json_encode($arrTR);
}
?>