<?php
header("Expires: Thu, 27 Mar 1980 23:59:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<div style="width:300px; font-size:80%; text-align:center;">
<?php
require('sibas-db.class.php');
if(isset($_GET['ide']) && isset($_GET['idvh']) && isset($_GET['flag']) && isset($_GET['fwd'])){
	$link = new SibasDB();
	$ide = $link->real_escape_string(trim(base64_decode($_GET['ide'])));
	$idVh = $link->real_escape_string(trim(base64_decode($_GET['idvh'])));
	$flag = (int)$link->real_escape_string(trim(base64_decode($_GET['flag'])));
	$fwd = $link->real_escape_string(trim(base64_decode($_GET['fwd'])));
	
	$txtMark = '';
	if($flag === 0){
		$flag = 1;
		$txtMark = 'El Registro se Marco como <br> <strong>Leído</strong>';
	}elseif($flag === 1){
		$flag = 0;
		$txtMark = 'El Registro se Marco como <br> <strong>No Leído</strong>';
	}
	
	
	$sql = '';
	switch ($fwd) {
		case 'DE':
			$sql = 'UPDATE s_de_em_cabecera 
				SET leido = '.$flag.'
				WHERE id_emision = "'.$ide.'"
				;';
			break;
		case 'AU':
			$sql = 'UPDATE s_au_em_cabecera as sae
				INNER JOIN s_au_em_detalle as sad ON (sad.id_emision = sae.id_emision)
				SET sae.leido = FALSE, sad.leido = '.$flag.'
				WHERE sae.id_emision = "'.$ide.'" and sad.id_vehiculo = "'.$idVh.'" ;';
			break;
		case 'TRM':
			$sql = 'UPDATE s_trm_em_cabecera as stre
				SET stre.leido = '.$flag.'
				WHERE stre.id_emision = "'.$ide.'" ;';
			break;
	}
	
	if($link->query($sql) === TRUE) {
		echo $txtMark.'<br><br>Por favor Espere...';
	} else {
		echo 'El Registro no puede ser marcado';
	}
}else{
	echo 'El Registro no puede ser marcado';
}
?>
</div>
<script type="text/javascript">
$(document).ready(function(e) {
    redirect('index.php?fwd=<?=md5($fwd);?>', 2);
});
</script>