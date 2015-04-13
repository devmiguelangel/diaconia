<?php
require_once('sibas-db.class.php');
$link = new SibasDB();
$product = $seguro;

$ide = NULL;
$nEm = '';

$_TYPE_USER = $link->verify_type_user($_SESSION['idUser'], $_SESSION['idEF']);
?>
<script type="text/javascript">
$(document).ready(function(e) {
	$(".date").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
		yearRange: "c-100:c+100"
	});
	
	$(".date").datepicker($.datepicker.regional[ "es" ]);
	
    $(".f-records").submit(function(e){
		e.preventDefault();
		$(this).find(':submit').prop('disabled', true);
		
		var _data = $(this).serialize();
		//alert(_data)
		$.ajax({
			url:'FAC-DE-result.inc.php',
			type:'GET',
			data:'fde=&'+_data,
			//dataType:"json",
			async:true,
			cache:false,
			beforeSend: function(){
				$(".result-search").hide();
				$(".result-loading").show();
			},
			complete: function(){
				$(".result-loading").hide();
				$(".result-search").show();
			},
			success: function(result){
				$(".result-search").html(result);
				$(".f-records :submit").prop('disabled', false);
			}
		});
		
		return false;
	});
});
</script>
<?php
if(isset($_GET['ide'])){
	$ide = $link->real_escape_string(trim(base64_decode($_GET['ide'])));
	
	$sqlId = 'SELECT no_emision FROM s_de_em_cabecera WHERE id_emision = "'.$ide.'" ;';
	if(($rsId = $link->query($sqlId,MYSQLI_STORE_RESULT))){
		if($rsId->num_rows === 1){
			$rowId = $rsId->fetch_array(MYSQLI_ASSOC);
			$rsId->free();
			$nEm = $rowId['no_emision'];
?>
<script type="text/javascript">
$(document).ready(function(e) {
    $(".f-records").submit();
});
</script>
<?php
		}
	}
}
?>
<h3>Registros Facultativos - Desgravamen</h3>
<div class="fac-records">
	<form class="f-records">
    	<label style="width:auto;">Entidad Financiera: </label>
        <select id="fde-ef" name="fde-ef[]" size="2" multiple style="width:auto; height:50px;">
<?php
if(($rsEf = $link->get_financial_institution_user($_SESSION['idUser'])) !== FALSE){
	if($rsEf->data_seek(0) === TRUE){
		while($rowEf = $rsEf->fetch_array(MYSQLI_ASSOC)){
			echo '<option value="'.base64_encode($rowEf['idef']).'" selected>'.$rowEf['ef_nombre'].'</option>';
		}
	}
}
?>
		</select>
        
    	<label>N° de Certificado: </label>
		<select id="fde-prefix" name="fde-prefix" style="width: auto;">
			<option value="DE">DE</option>
            <!--<option value="">Prefijo</option>
            <option value="CCB">CCB</option>
            <option value="CCD">CCD</option>
            <option value="CDB">CDB</option>
            <option value="CDD">CDD</option>-->
		</select>
        <input type="text" id="fde-nc" name="fde-nc" value="<?=$nEm;?>" autocomplete="off">

        <label style="width:auto;">Usuario: </label>
        <input type="text" id="fde-user" name="fde-user" value="" autocomplete="off">
        <br>
        
        <label>Cliente: </label>
        <input type="text" id="fde-client" name="fde-client" value="" autocomplete="off">
        
        <label style="width:auto;">C.I.: </label>
        <input type="text" id="fde-dni" name="fde-dni" value="" autocomplete="off">
        
        <label style="width:auto;">Complemento: </label>
        <input type="text" id="fde-comp" name="fde-comp" value="" autocomplete="off" style="width:40px;">
        
        <label style="width:auto;">Extension: </label>
        <select id="fde-ext" name="fde-ext">
        	<option value="">Seleccione...</option>
<?php
if (($rsEx = $link->get_depto()) !== FALSE) {
	while($rowEx = $rsEx->fetch_array(MYSQLI_ASSOC)){
		if ((boolean)$rowEx['tipo_ci'] === TRUE) {
			echo '<option value="'.$rowEx['id_depto'].'">'.$rowEx['departamento'].'</option>';
		}
	}
	$rsEx->free();
}
?>
        	
        </select><br>
        
        <label style="width:auto;">Fecha: </label>
        <label style="width:auto;">desde: </label>
        <input type="text" id="fde-date-b" name="fde-date-b" value="" autocomplete="off" class="date" readonly>
        
        <label style="width:auto;">hasta: </label>
        <input type="text" id="fde-date-e" name="fde-date-e" value="" autocomplete="off" class="date" readonly>
        <input type="hidden" id="fde-id-user" name="fde-id-user" value="<?=$_SESSION['idUser'];?>">
        <input type="hidden" id="fde-type-user" name="fde-type-user" value="<?=base64_encode($_TYPE_USER['u_tipo_codigo']);?>" />
        <input type="hidden" id="token" name="token" value="<?=md5('1');?>">
        <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
		<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
        <br>

        <div align="center">
        	<input type="submit" id="fde-search" name="fde-search" value="Buscar" class="fde-btn">
	        <input type="reset" id="fde-reset" name="fde-reset" value="Restablecer Campos" class="fde-btn">
        </div>
    </form>
    
    <div class="result-container">
    	<div class="result-loading"></div>
        <div class="result-search"></div>
    </div>
</div>
<?php
if(isset($_GET['fwd'])){
	if($_GET['fwd'] === md5('forwardFAC')){
?>
<script type="text/javascript">
$(document).ready(function(e) {
    $(".f-records").submit();
});
</script>
<?php
	}
}
?>