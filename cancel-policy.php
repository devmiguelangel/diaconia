<?php
require('sibas-db.class.php');
session_start();

if(isset($_GET['ide']) && isset($_GET['pr'])){
	$link = new SibasDB();
	
	$ide = $link->real_escape_string(trim(base64_decode($_GET['ide'])));
	$pr = strtoupper($link->real_escape_string(trim(base64_decode($_GET['pr']))));

	$table = '';
	switch ($pr) {
		case 'DE':
			$table = 's_de_em_cabecera';
			break;
		case 'AU':
			$table = 's_au_em_cabecera';
			break;
		case 'TRD':
			$table = 's_trd_em_cabecera';
			break;
		case 'TRM':
			$table = 's_trm_em_cabecera';
			break;
	}

	$sql = 'select stec.prefijo, stec.no_emision 
		from ' . $table . ' as stec 
		where stec.id_emision = "' . $ide . '"
		limit 0, 1';

	if (($rs = $link->query($sql, MYSQLI_STORE_RESULT)) !== false) {
		if ($rs->num_rows === 1) {
			$row = $rs->fetch_array(MYSQLI_ASSOC);
			$rs->free();
?>
<script type="text/javascript">
$(document).ready(function(e) {
    get_tinymce('fp-obs');
	
	$("#form-cancel").validateForm({
		action: 'cancel-policy-record.php',
		method: 'GET'
	});
});
</script>
<form id="form-cancel" name="form-cancel" class="f-process" style="width:570px; font-size:130%;">
	<h4 class="h4">Formulario de anulación Póliza N° <?=$row['prefijo'] . '-' . $row['no_emision'];?></h4>
	<label class="fp-lbl" style="text-align:left; width:auto;">Motivo de Anulación: <span>*</span></label>
	<textarea id="fp-obs" name="fp-obs" class="required"></textarea><br>
    <div style="text-align:center">
		<input type="hidden" id="fp-ide" name="fp-ide" value="<?=base64_encode($ide);?>">
        <input type="hidden" id="idUser" name="idUser" value="<?=$_SESSION['idUser'];?>">
        <input type="hidden" id="pr" name="pr" value="<?=base64_encode($pr);?>">
    	<input type="submit" id="fp-process" name="fp-process" value="Anular Certificado" class="fp-btn">
    </div>
    
    <div class="loading">
        <img src="img/loading-01.gif" width="35" height="35" />
    </div>
</form>
<?php
		} else {
			echo 'No se puede anular la Póliza.';
		}
	} else {
		echo 'No se puede anular la Póliza .';
	}
}else
	echo 'No se puede anular la Póliza';
?>