<?php
require('sibas-db.class.php');

$arrDE = array(0 => 0, 1 => 'R', 2 => 'Error.');

if(isset($_POST['dq-idc']) && isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['pr'])){
	$swEmpty = FALSE;
	
	/*foreach ($_POST as $key => $value){
		if ($key != 'dq-resp-1' && $key != 'dq-resp-2'){
			if ($value === '') {
				$swEmpty = TRUE;
			}
		}
	}*/
	
	if($swEmpty === FALSE && $_POST['pr'] === base64_encode('DE|03')){
		$link = new SibasDB();
		
		$idc = $link->real_escape_string(trim(base64_decode($_POST['dq-idc'])));
		$idef = $link->real_escape_string(trim(base64_decode($_POST['dq-idef'])));
		
		$max_item = 0;
		if (($rowDE = $link->get_max_amount_optional(base64_encode($idef), 'DE')) !== FALSE) {
			$max_item = (int)$rowDE['max_item'];
		}
		
		$ms = $link->real_escape_string(trim($_POST['ms']));
		$page = $link->real_escape_string(trim($_POST['page']));
		$pr = $link->real_escape_string(trim($_POST['pr']));
		
		$nCl = $link->number_clients($idc, $idef, TRUE);
		
		if($nCl > 0 && $nCl <= $max_item){
			$flag = array();
			$resp = array();
			$idd = array();
			$arr_QR = array();

			for ($i=1; $i <= $nCl; $i++) {
				$flag[$i] = false;
				$resp[$i] = $link->real_escape_string(trim($_POST['dq-resp-' . $i]));
				$idd[$i] = $link->real_escape_string(trim(base64_decode($_POST['dq-idd-' . $i])));

				$arr_QR[$i] = array();
			}
			
			if (($rsQs = $link->get_question(base64_encode($idef))) !== FALSE) {
				for ($k=1; $k <= $nCl; $k++) {
					if ($rsQs->data_seek(0) === true) {
						$i = 0;
						while($rowQs = $rsQs->fetch_array(MYSQLI_ASSOC)){
							$i += 1;

							$valPr = $link->real_escape_string(trim($_POST['dq-qs-' . $k 
								. '-' . $rowQs['id_pregunta']]));
							
							if($rowQs['respuesta'] !== $valPr) {
								$flag[$k] = true;
							}
							
							$arr_QR[$k][$rowQs['id_pregunta']] = $rowQs['id_pregunta'] . '|' . $valPr;
						}
					}

				}

				$sql = 'insert into s_de_cot_respuesta 
					(`id_respuesta`, `id_detalle`, `respuesta`, `observacion`) 
					values ';

				for ($i=1; $i <= $nCl; $i++) {
					if ($flag[$i] === false) {
						$resp[$i] = '';
					}

					$sql .= '("' . uniqid('@S#1$2013-' . $i, true) . '", 
						"' . $idd[$i] . '", 
						"' . $link->real_escape_string(json_encode($arr_QR[$i])) . '", 
						"' . $resp[$i] . '")';

					if ($i === $nCl) {
						$sql .= ';';
					} else {
						$sql .= ',';
					}
				}

				//echo $sql;
				if($link->query($sql) === TRUE){
					$arrDE[0] = 1;
					$arrDE[1] = 'de-quote.php?ms='.$ms.'&page='.$page.'&pr='.base64_encode('DE|04').'&idc='.base64_encode($idc);
					$arrDE[2] = 'Las respuestas se registraron correctamente';
				}else{
					$arrDE[2] = 'Error al registrar las respuestas';
				}
			}else{
				$arrDE[2] = 'No existen Preguntas';
			}
		}else{
			$arrDE[2] = 'No se pueden registrar la respuestas';
		}
		echo json_encode($arrDE);
	}else{
		$arrDE[2] = 'Respuestas incompletas';
		echo json_encode($arrDE);
	}
}else{
	echo json_encode($arrDE);
}
?>