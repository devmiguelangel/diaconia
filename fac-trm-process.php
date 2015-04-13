<?php
header("Expires: Tue, 01 Jan 2000 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require('sibas-db.class.php');
if(isset($_GET['ide']) && isset($_GET['idvh'])){
	session_start();
	
	$link = new SibasDB();
	$ide = $link->real_escape_string(trim(base64_decode($_GET['ide'])));
	//$idVh = $link->real_escape_string(trim(base64_decode($_GET['idvh'])));
	$user = $_SESSION['idUser'];
	
	$sqlPr = 'select 
		stre.id_emision as ide,
	    scia.id_compania,
	    stre.tasa,
	    (case scl.tipo
	        when
	            0
	        then
	            concat(scl.nombre,
	                    " ",
	                    scl.paterno,
	                    " ",
	                    scl.materno)
	        when 1 then scl.razon_social
	    end) as cl_nombre,
	    su.email as email_user,
		stre.certificado_provisional as cp
	from
	    s_trm_em_cabecera as stre
			inner join
		s_cliente as scl ON (scl.id_cliente = stre.id_cliente)
			inner join
		s_compania as scia ON (scia.id_compania = stre.id_compania)
			inner join
	    s_usuario as su ON (su.id_usuario = stre.id_usuario)
	where
	    stre.id_emision = "'.$ide.'"
	limit 0, 1
	;';
	
	//echo $sqlPr;
	$CIA = '';
	$TASA = 0;
	$EMAIL_USER = '';
	$cp = NULL;
	$category = '';
	$arr_Customer = array(1 => NULL, 2 => NULL);
	
	$rsPr = $link->query($sqlPr,MYSQLI_STORE_RESULT);
	if($rsPr->num_rows === 1){
		$rowPr = $rsPr->fetch_array(MYSQLI_ASSOC);
		$rsPr->free_result();
		
		$CIA = $rowPr['id_compania'];
		$TASA = $rowPr['tasa'];
		$EMAIL_USER = $rowPr['email_user'];
		
		$cp = (boolean)$rowPr['cp'];
		if ($cp === true) {
			$category = 'CP';
		} else {
			$category = 'CE';
		}
?>
<script type="text/javascript">
$(document).ready(function(e) {
    get_tinymce('fp-observation');
	
	$('input').iCheck({
		checkboxClass: 'icheckbox_square-red',
		radioClass: 'iradio_square-red',
		increaseArea: '20%' // optional
	});
	
	$('input[name="fp-approved"]').on('ifToggled', function(event){
		var rb_approved = $("input[name='fp-approved']:checked").prop('value');
		$("#fp-percentage").prop('value', 0);
		var current_rate = $("#fp-rate-curr").prop('value');
		$("#fp-current-rate, #fp-final-rate").prop('value', current_rate);
		$(".ctr-obs").slideDown();
		
		switch(rb_approved){
			case 'SI':
				$(".ctr-rate, .ctr-frate").slideDown();
				$(".ctr-state").slideUp();
				$("#fp-state").removeClass('required');
				break;
			case 'NO':
				$(".ctr-rate, .ctr-frate, .ctr-state").slideUp();
				$("#fp-state").removeClass('required');
				break;
			case 'PE':
				$(".ctr-rate, .ctr-frate").slideUp();
				$(".ctr-state").slideDown();
				$("#fp-state").addClass('required');
				break;
		}
	});
	
	$('input[name="fp-rate"]').on('ifToggled', function(event){
		var rb_rate = $("input[name='fp-rate']:checked").prop('value');
		var current_rate = $("#fp-rate-curr").prop('value');
		$("#fp-current-rate, #fp-final-rate").prop('value', current_rate);
		
		switch(rb_rate){
			case 'SI':
				$(".ctr-current-rate").slideDown();
				$("#fp-percentage").addClass('required number');
				break;
			case 'NO':
				$("#fp-percentage").prop('value', 0);
				$("#fp-percentage").removeClass('required number');
				$(".ctr-current-rate").slideUp();
				break;
		}
	});
	
	$("#form-process").validateForm({
		action: 'FAC-TRM-record.php',
		method: 'GET',
		nameLoading: '.loading-02'
	});
	
	/*$("input[name='fp-approved']").click(function(e){
		var rb_approved = $("input[name='fp-approved']:checked").prop('value');
		$("#fp-percentage").prop('value', 0);
		var current_rate = $("#fp-rate-curr").prop('value');
		$("#fp-current-rate, #fp-final-rate").prop('value', current_rate);
		$(".ctr-obs").slideDown();
		
		switch(rb_approved){
			case 'SI':
				$(".ctr-rate, .ctr-frate").slideDown();
				$(".ctr-state").slideUp();
				$("#fp-state").removeClass('required');
				break;
			case 'NO':
				$(".ctr-rate, .ctr-frate, .ctr-state").slideUp();
				$("#fp-state").removeClass('required');
				break;
			case 'PE':
				$(".ctr-rate, .ctr-frate").slideUp();
				$(".ctr-state").slideDown();
				$("#fp-state").addClass('required');
				break;
		}
	});*/
	
	/*$("input[name='fp-rate']").click(function(e){
		var rb_rate = $("input[name='fp-rate']:checked").prop('value');
		var current_rate = $("#fp-rate-curr").prop('value');
		$("#fp-current-rate, #fp-final-rate").prop('value', current_rate);
		
		switch(rb_rate){
			case 'SI':
				$(".ctr-current-rate").slideDown();
				$("#fp-percentage").addClass('required number');
				break;
			case 'NO':
				$("#fp-percentage").prop('value', 0);
				$("#fp-percentage").removeClass('required number');
				$(".ctr-current-rate").slideUp();
				break;
		}
	});*/
	
	/*$("#fp-state").change(function(){
		var state = $(this).prop('value');
		
		switch(state){
			case '1':
				$(".ctr-obs").slideUp();
				$("#fp-observation").removeClass('required');
				//***********CERTIFICADO MEDICO*************
				$("#ctr-process").slideUp();
				$("#ctr-certified").show();
				
				var cia = $("#cia").prop('value');
				var ef = $("#ef").prop('value');
				var ide = $("#fp-ide").prop('value');
				$.get('DE-medical-certificate.php', {cm: 1, cia: cia, ide: ide, ef: ef}, function(data){
					$("#ctr-certified").html(data);
				});
				break;
			default:
				$(".ctr-obs").slideDown();
				$("#fp-observation").addClass('required');
				break;
		}
		
	});*/
	
	$("#fp-percentage").keyup(function(e){
		var per = parseInt($(this).prop('value'));
		$(this).prop('value', per);
		var current_rate = parseFloat($("#fp-current-rate").prop('value'));
		var final_rate = $("#fp-rate-curr").prop('value');
		
		if((/^([0-9])*$/.test(per)) && per.length !== 0 && per <= 100){
			final_rate = (current_rate * per) / 100;
			final_rate += current_rate;
			final_rate = final_rate.toFixed(2);
		}else{
			$(this).prop('value', '0');
		}
		
		$("#fp-final-rate").prop('value', final_rate);
	});
	
	$(".vc-popup").click(function(e){
		e.preventDefault();
		var url = $(this).prop('href');
		new_window = window.open(url, 'name', 'height=500,width=850,dependent=yes');
		if (window.focus) {new_window.focus()}
		return false;
	});
});
</script>
<div class="ctr-form-process" style="min-height:550px; ">
	<div class="content-process" id="ctr-process" >
    	<form id="form-process" name="form-process" class="f-process" style="width:75%; margin:0 auto;">
            <h4 class="h4">Formulario para aprobar la solicitud no emitida</h4>
			<a href="certificate-detail.php?ide=<?=base64_encode($ide);?>&pr=<?=base64_encode('TRM');?>&type=<?=base64_encode('PRINT');?>&category=<?=base64_encode($category);?>" class="vc-popup">Ver Certificado</a>
        	<label class="fp-lbl">Aprobado: <span>*</span></label>
            <label class="fp-rb">
            	<input type="radio" id="fp-approved-1" name="fp-approved" value="SI"> SI
            </label>
            <label class="fp-rb">
                <input type="radio" id="fp-approved-2" name="fp-approved" value="NO" checked> NO
            </label>
            <label class="fp-rb">
                <input type="radio" id="fp-approved-3" name="fp-approved" value="PE"> PENDIENTE
            </label><br>
            
            <div style="display:none;" class="ctr-rate">
            	<label class="fp-lbl">Tasa de Recargo: <span>*</span></label>
                <label class="fp-rb">
                    <input type="radio" id="fp-rate-1" name="fp-rate" value="SI"> SI
                </label>
                <label class="fp-rb">
                    <input type="radio" id="fp-rate-2" name="fp-rate" value="NO" checked> NO
                </label>
            </div>
            
            <div style="display:none;" class="ctr-frate">
            	<div style="display:none;" class="ctr-current-rate">
                	<label class="fp-lbl">Porcentaje de Recargo: <span>*</span></label>
                    <input type="text" id="fp-percentage" name="fp-percentage" value="0" autocomplete="off"> % <br>
                    
                    <label class="fp-lbl">Tasa Actual: <span>*</span></label>
                    <input type="text" id="fp-current-rate" name="fp-current-rate" value="" autocomplete="off" readonly>
                </div>
                
                <div style="display:block;" class="ctr-final-rate">
                	<label class="fp-lbl" >Tasa Final: <span>*</span></label>
	                <input type="text" id="fp-final-rate" name="fp-final-rate" value="<?=$TASA;?>" autocomplete="off" readonly>
                </div>
            </div>
            
            <div style="display:none;" class="ctr-state">
            	<label class="fp-lbl">Estado: <span>*</span></label>
                <select id="fp-state" name="fp-state" style="width:250px;">
                    <option value="">-Seleccione-</option>
<?php
	$sqlSt = 'SELECT sst.id_estado, sst.estado, sst.codigo
		FROM s_estado as sst
			INNER JOIN s_entidad_financiera as sef ON (sef.id_ef = sst.id_ef)
		WHERE sst.producto = "TRM" 
			and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
			and sef.activado = true
		ORDER BY sst.id_estado ASC ;';
	$rsSt = $link->query($sqlSt,MYSQLI_STORE_RESULT);
	if($rsSt->num_rows > 0){
		while($rowSt = $rsSt->fetch_array(MYSQLI_ASSOC)){
			echo '<option value="'.$rowSt['id_estado'].'|'.$rowSt['codigo'].'">'.$rowSt['estado'].'</option>';
		}
	}
?>
                </select>
            </div>
            
            <div class="ctr-obs" align="center">
            	<label class="fp-lbl" style="text-align:left;">Observaciones: <span>*</span></label>
	            <textarea id="fp-observation" name="fp-observation" class="required"></textarea><br>
            </div>
            
            <label class="fp-lbl">Arrendatario:</label>
            <label class="fp-lbl fp-name"><?=$rowPr['cl_nombre'];?></label><br>
            
			<label class="fp-lbl fp-rb">Envie la aprobación via correo electrónico <span>*</span></label><br>
			<input type="text" id="fp-email" name="fp-email" value="<?=$EMAIL_USER;?>" autocomplete="off" style="width:460px;" class="required multiple-email">
            
            <div class="loading loading-02">
                <img src="img/loading-01.gif" width="35" height="35" />
            </div>
			<div align="center">
            	<input type="hidden" id="fp-rate-curr" name="fp-rate-curr" value="<?=$TASA;?>">
                <input type="hidden" id="fp-ide" name="fp-ide" value="<?=base64_encode($ide);?>">
                <input type="hidden" id="fp-user" name="fp-user" value="<?=$user;?>">
                <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
				<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
                <input type="hidden" id="cia" name="cia" value="<?=base64_encode($CIA);?>">
                <input type="hidden" id="ef" name="ef" value="<?=$_SESSION['idEF'];?>">
            	<input type="submit" id="fp-process" name="fp-process" value="Guardar" class="fp-btn">
            </div>
        </form>
    </div>
    <!--<div class="content-process" style="min-height:400px; display:none;" id="ctr-certified">
    	
    </div>-->
	<!--<div class="content-process" style="" id="ctr-certified2">
    	sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>sssss<br>
    </div>-->
</div>
<?php
	}else{
		
	}
}else{
	echo 'USTED NO PUEDE PROCESAR ESTE CERTIFICADO';
}

?>
