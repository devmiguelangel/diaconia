<?php
require('sibas-db.class.php');
require('session.class.php');
$session = new Session();
$token = $session->check_session();
$arrTR = array(0 => 0, 1 => 'R', 2 => 'Error: No se pudo procesar el Material');

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


if(isset($_POST['dm-token']) && isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['pr']) && isset($_POST['idef'])){
	if($_POST['pr'] === base64_encode('TRM|01')){
		$link = new SibasDB();
		$idc = NULL;
		
		if (isset($_POST['dm-idc'])) {
			$idc = $link->real_escape_string(trim(base64_decode($_POST['dm-idc'])));
		}
		
		$idef = $link->real_escape_string(trim(base64_decode($_POST['idef'])));
		
		$idMt = 0;
		$flag = false;
		$_FAC = false;
		$reason = '';
		$token = false;
		
		if(isset($_POST['dm-idMt'])){
			$flag = TRUE;
			$idMt = $link->real_escape_string(trim(base64_decode($_POST['dm-idMt'])));
		}
		
		$max_item = $max_amount = $max_anio = 0;
		if (($rowTR = $link->get_max_amount_optional(base64_encode($idef), 'TRM')) !== FALSE) {
			$max_item = (int)$rowTR['max_item'];
			$max_amount = (int)$rowTR['max_monto'];
			$max_anio = (int)$rowTR['max_anio'];
		}

		$dm_material = $link->real_escape_string(trim($_POST['dm-material']));
		$dm_modality = 'null';
		if (isset($_POST['dm-modality'])) {
			$dm_modality = '"' . $link->real_escape_string(trim(base64_decode($_POST['dm-modality']))) . '"';
		}
		$dm_value_insured = $link->real_escape_string(trim($_POST['dm-value-insured']));
		
		if($dm_value_insured > $max_amount){
			$_FAC = TRUE;
			$reason .= '| El valor asegurado del Material excede el máximo valor permitido. Valor permitido: '.number_format($max_amount, 2, '.', ',').' USD';
		}
		
		$max_value = $link->get_cumulus($dm_value_insured, 'USD', base64_encode($idef), 'TRM');
		
		$ms = $link->real_escape_string(trim($_POST['ms']));
		$page = $link->real_escape_string(trim($_POST['page']));
		$pr = $link->real_escape_string(trim($_POST['pr']));
		
		if($max_value === 1){
			$sql = '';
			if ($idc === NULL) {
				$idc = uniqid('@S#4$2013',true);
				$record = $link->getRegistrationNumber($_SESSION['idEF'], 'TRM', 0);
				
				$sql = 'insert into s_trm_cot_cabecera 
				(`id_cotizacion`, `no_cotizacion`, `id_ef`, 
				`certificado_provisional`, `fecha_creacion`, `id_usuario`)
				values
				("'.$idc.'", '.$record.', "'.base64_decode($_SESSION['idEF']).'", false, 
				curdate(), "'.base64_decode($_SESSION['idUser']).'")';
				
				if ($link->query($sql) === true) {
					$token = true;
				}
			} else {
				$token = true; 
			}
			
			if ($token === true) {
				if($flag === false){
					$idMt = uniqid('@S#4$2013',true);
					$sql = 'insert into s_trm_cot_detalle 
					(`id_material`, `id_cotizacion`, `material`, `modalidad`, `valor_asegurado`) 
					values 
					("'.$idMt.'", "'.$idc.'", "'.$dm_material.'", 
					'.$dm_modality.', '.$dm_value_insured.') ;';
					
					if($link->query($sql) === TRUE){
						$arrTR[0] = 1;
						$arrTR[1] = 'trm-quote.php?ms='.$ms.'&page='.$page.'&pr='.$pr.'&idc='.base64_encode($idc);
						$arrTR[2] = 'Material registrado con Exito';
					}else {
						$arrTR[2] = 'No se pudo registrar el Material';
					}
				} else {
					$sql = 'update s_trm_cot_detalle 
						set `material` = "'.$dm_material.'", `modalidad` = '.$dm_modality.', 
							`valor_asegurado` = '.$dm_value_insured.'
						where id_material = "'.$idMt.'" ;';
					
					if($link->query($sql) === TRUE){
						$arrTR[0] = 1;
						$arrTR[1] = 'trm-quote.php?ms='.$ms.'&page='.$page.'&pr='.$pr.'&idc='.base64_encode($idc);
						$arrTR[2] = 'Los Datos se actualizaron correctamente';
					}else {
						$arrTR[2] = 'No se pudo actualizar los datos';
					}
				}
			} else {
				$arrTR[2] = 'La Cotización no puede ser registrada';
			}
		}else {
			$arrTR[2] = 'El Valor Asegurado no debe ser mayor a '.number_format($max_value, 2, '.', ',').' USD.';
		}
	}else {
		$arrTR[2] = 'El Material no puede ser registrado';
	}
	
	echo json_encode($arrTR);
}else {
	echo json_encode($arrTR);
}
?>