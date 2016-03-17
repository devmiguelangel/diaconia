<?php
require('sibas-db.class.php');
require('session.class.php');

$session = new Session();
$token = $session->check_session();
$arrAU = array(0 => 0, 1 => 'R', 2 => 'Error: No se pudo procesar el Vehículo');

$link = new SibasDB();

if($token === FALSE){
	if(($_ROOT = $link->get_id_root()) !== FALSE) {
		$_SESSION['idUser'] = base64_encode($_ROOT['idUser']);
	} else {
		$arrAU[0] = 1;
		$arrAU[1] = 'logout.php';
		$arrAU[2] = 'La Cotización no puede ser registrada, intentelo mas tarde';
	}
}

if(isset($_POST['dv-token']) && isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['pr']) && isset($_POST['idef'])){
	if($_POST['pr'] === base64_encode('AU|01')){
		$link = new SibasDB();
		$idc = NULL;
		
		if (isset($_POST['dv-idc'])) {
			$idc = $link->real_escape_string(trim(base64_decode($_POST['dv-idc'])));
		}
		
		$idef = $link->real_escape_string(trim(base64_decode($_POST['idef'])));
		
		$idVh = 0;
		$flag = FALSE;
		$swMo = FALSE;
		$_FAC = FALSE;
		$reason = '';
		$token = false;
		
		if(isset($_POST['dv-idVh'])){
			$flag = TRUE;
			$idVh = $link->real_escape_string(trim(base64_decode($_POST['dv-idVh'])));
		}
		
		$max_item = $max_amount = $max_anio = 0;
		if (($rowAU = $link->get_max_amount_optional(base64_encode($idef))) !== FALSE) {
			$max_item = (int)$rowAU['max_item'];
			$max_amount = (int)$rowAU['max_monto'];
			$max_anio = (int)$rowAU['max_anio'];
		}
		
		$year_min = 0;
		if(($rowYear = $link->get_year_cot(base64_encode($idef))) !== FALSE) {
			$year_min = (int)$rowYear['anio_min'];
		}
		
		$dv_type_vehicle = $link->real_escape_string(trim(base64_decode($_POST['dv-type-vehicle'])));
		$dv_category = $link->real_escape_string(trim(base64_decode($_POST['dv-category'])));
		$dv_make = $link->real_escape_string(trim(base64_decode($_POST['dv-make'])));
		$dv_model = $link->real_escape_string(trim($_POST['dv-model']));
		if($dv_model === 'OTHER'){
			$dv_model_other = $link->real_escape_string(trim($_POST['dv-model-other']));
			if(($dv_model = $link->set_model(base64_encode($dv_make), $dv_model_other)) !== FALSE) {
				$dv_model = base64_decode($dv_model);
			} else {
				$swMo = TRUE;
			}
		}else {
			$dv_model = base64_decode($dv_model);
		}
		
		$dv_year_other = 0;
		$dv_year = $link->real_escape_string(trim($_POST['dv-year']));
		if($dv_year === 'YEAR') {
			$dv_year = $link->real_escape_string(trim($_POST['dv-year-other']));
			$dv_year_other = $dv_year;
			$_FAC = TRUE;
			$reason .= '| El Vehículo tiene una antiguedad mayor a '.$max_anio.' años';
		} else {
			$dv_year = base64_decode($dv_year);
		}
		
		$dv_plate = $link->real_escape_string(trim($_POST['dv-plate']));
		$dv_use = $link->real_escape_string(trim(base64_decode($_POST['dv-use'])));
		$dv_traction = $link->real_escape_string(trim(base64_decode($_POST['dv-traction'])));
		$dv_zero_km = $link->real_escape_string(trim(base64_decode($_POST['dv-zero-km'])));
		$dv_modality = 'null';
		if (isset($_POST['dv-modality'])) {
			$dv_modality = '"'.$link->real_escape_string(trim(base64_decode($_POST['dv-modality']))).'"';
		}
		$dv_value_insured = $link->real_escape_string(trim($_POST['dv-value-insured']));
		
		if($dv_value_insured > $max_amount){
			$_FAC = TRUE;
			$reason .= '| El valor asegurado del Vehículo excede el máximo valor permitido. Valor permitido: '.number_format($max_amount, 2, '.', ',').' USD';
		}
		
		$max_value = $link->get_cumulus($dv_value_insured, 'USD', base64_encode($idef), 'AU');
		
		$ms = $link->real_escape_string(trim($_POST['ms']));
		$page = $link->real_escape_string(trim($_POST['page']));
		$pr = $link->real_escape_string(trim($_POST['pr']));
		
		if($swMo === FALSE){
			if($dv_year_other < $year_min){
				if($max_value === 1){
					$sql = '';
					
					if ($idc === NULL) {
						$idc = uniqid('@S#2$2013',true);
						$record = $link->getRegistrationNumber($_SESSION['idEF'], 'AU', 0);
						
						$sql = 'insert into s_au_cot_cabecera 
						(`id_cotizacion`, `no_cotizacion`, `id_ef`, 
						`certificado_provisional`, `fecha_creacion`, `id_usuario`)
						values
						("'.$idc.'", '.$record.', "'.base64_decode($_SESSION['idEF']).'", 
						false, curdate(), "'.base64_decode($_SESSION['idUser']).'") ;';
						
						if ($link->query($sql) === true) {
							$token = true;
						}
					} else {
						$token = true;
					}
					
					if($flag === FALSE && $token === true){
						$idVh = uniqid('@S#2$2013',true);
						$sql = 'insert into s_au_cot_detalle 
						(`id_vehiculo`, `id_cotizacion`, `id_tipo_vh`, 
						`categoria`, `id_marca`, `id_modelo`, 
						`anio`, `placa`, `uso`, `traccion`, 
						`km`, `modalidad`, `valor_asegurado`, 
						`facultativo`, `motivo_facultativo`) 
						values 
						("'.$idVh.'", "'.$idc.'", "'.$dv_type_vehicle.'", 
						"'.$dv_category.'", "'.$dv_make.'", "'.$dv_model.'",
						'.$dv_year.', "'.$dv_plate.'", "'.$dv_use.'", "'.$dv_traction.'", 
						"'.$dv_zero_km.'", '.$dv_modality.', '.$dv_value_insured.', 
						'.(int)$_FAC.', "'.$reason.'") ;';
						
						if($link->query($sql) === TRUE){
							$arrAU[0] = 1;
							$arrAU[1] = 'au-quote.php?ms='.$ms.'&page='.$page.'&pr='.$pr.'&idc='.base64_encode($idc);
							$arrAU[2] = 'Vehículo registrado con Exito';
						}else {
							$arrAU[2] = 'No se pudo registrar el Vehículo';
						}
					} elseif ($token === true) {
						$sql = 'update s_au_cot_detalle 
						set `id_tipo_vh` = "'.$dv_type_vehicle.'", `categoria` = "'.$dv_category.'", 
							`id_marca` = "'.$dv_make.'", `id_modelo` = "'.$dv_model.'", 
							`anio` = '.$dv_year.', `placa` = "'.$dv_plate.'", 
							`uso` = "'.$dv_use.'", `traccion` = "'.$dv_traction.'", 
							`km` = "'.$dv_zero_km.'", `modalidad` = '.$dv_modality.', 
							`valor_asegurado` = '.$dv_value_insured.', `facultativo` = '.(int)$_FAC.', 
							`motivo_facultativo` = "'.$reason.'"
						where id_vehiculo = "'.$idVh.'" ;';
						
						if($link->query($sql) === TRUE){
							$arrAU[0] = 1;
							$arrAU[1] = 'au-quote.php?ms='.$ms.'&page='.$page.'&pr='.$pr.'&idc='.base64_encode($idc);
							$arrAU[2] = 'Los Datos se actualizaron correctamente';
						}else {
							$arrAU[2] = 'No se pudo actualizar los datos';
						}
					}
				}else {
					$arrAU[2] = 'El Valor Asegurado no debe ser mayor a '.number_format($max_value, 2, '.', ',').' USD.';
				}
			}else {
				$arrAU[2] = 'El Año del Vehículo se encuentra en la lista';
			}
		}else {
			$arrAU[2] = 'El Modelo de Vehículo no pudo ser registrado';
		}
	}else {
		$arrAU[2] = 'El Vehículo no puede ser registrado';
	}
	
	echo json_encode($arrAU);
}else {
	echo json_encode($arrAU);
}
?>