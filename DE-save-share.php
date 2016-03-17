<?php
require_once 'sibas-db.class.php';
$arrDE = array(0 => 0, 1 => 'R', 2 => 'Error: No se pudo procesar la Cotización');

if (isset($_GET['idc'])
	&& isset($_GET['idPe'])
	&& isset($_GET['idEf'])
	&& isset($_GET['cia'])
	&& isset($_GET['ms'])
	&& isset($_GET['page'])) {
	
	$link = new SibasDB();
	
	$idc = $link->real_escape_string(trim(base64_decode($_GET['idc'])));
	$idPe = $link->real_escape_string(trim(base64_decode($_GET['idPe'])));
	$idEf = $link->real_escape_string(trim(base64_decode($_GET['idEf'])));
	$idCia = $link->real_escape_string(trim(base64_decode($_GET['cia'])));
	$ms = $link->real_escape_string(trim($_GET['ms']));
	$page = $link->real_escape_string(trim($_GET['page']));
	
	$sql = 'update s_de_cot_cabecera as sdc
		inner join 
	s_entidad_financiera as sef ON (sef.id_ef = sdc.id_ef)
		set sdc.id_pr_extra = "'.$idPe.'"
	where sdc.id_cotizacion = "'.$idc.'" 
		and sef.id_ef = "'.$idEf.'"
	;';
	
	if ($link->query($sql) === true) {
		$arrDE[0] = 1;
		if ($link->affected_rows > 0) {
			$arrDE[1] = 'de-quote.php?ms='.$ms.'&page='.$page.'&pr='.base64_encode('DE|05')
				.'&idc='.base64_encode($idc).'&flag='.md5('i-new').'&cia='.base64_encode($idCia);
			$arrDE[2] = 'La Cotización fue procesada correctamente';
		} else {
			$arrDE[2] = 'No se pudo procesar la Cotización';
		}
	} else {
		$arrDE[2] = 'La Cotización no fue procesada';
	}
	echo json_encode($arrDE);
} else {
	echo json_encode($arrDE);
}
?>