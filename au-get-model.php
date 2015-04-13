<?php

require('sibas-db.class.php');
$arrMo = array(0 => FALSE);
if(isset($_GET['make']) && isset($_GET['idef'])){
	$link = new SibasDB();
	
	$make = $link->real_escape_string(trim(base64_decode($_GET['make'])));
	$idef = $link->real_escape_string(trim(base64_decode($_GET['idef'])));
	
	if(($rsMo = $link->get_model(base64_encode($idef), $make)) !== FALSE){
		$arrMo[0] = TRUE;
		while($rowMo = $rsMo->fetch_array(MYSQLI_ASSOC)){
			$arrMo[] = base64_encode($rowMo['id_modelo']).'|'.$rowMo['modelo'];
		}
		$arrMo[] = 'OTHER|OTRO';
	}
	$link->close();
	echo json_encode($arrMo);
}else {
	echo json_encode($arrMo);
}
?>