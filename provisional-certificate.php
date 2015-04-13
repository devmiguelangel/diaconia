<div style="width:300px; font-size:80%; text-align:center;">
<?php
require('sibas-db.class.php');
if(isset($_GET['ide']) && isset($_GET['pr'])){
	$link = new SibasDB();
	
	$ide = $link->real_escape_string(trim(base64_decode($_GET['ide'])));
	$pr = $link->real_escape_string(trim(base64_decode($_GET['pr'])));
	$cp = 0;
	
	$table = '';
	switch ($pr) {
		case 'AU':
			$table = 's_au_em_cabecera';
			break;
		case 'TRD':
			$table = 's_trd_em_cabecera';
			break;
	}
	
	$sql = 'UPDATE '.$table.' 
		SET certificado_provisional = ? 
		WHERE id_emision = ? ;';
	
	if (($stmt = $link->prepare($sql)) !== FALSE) {
		$stmt->bind_param('is', $cp, $ide);
		
		if ($stmt->execute() === TRUE) {
			echo 'La Póliza fue actualizada con éxito<br>Por favor espere...';
			echo '<meta http-equiv="refresh" content="2">';
		} else {
			echo 'La Póliza no puede ser actualizada';
		}
	} else {
		echo 'La Póliza no puede ser actualizada';
	}
}
?>
</div>