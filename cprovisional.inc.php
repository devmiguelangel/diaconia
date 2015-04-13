<?php
require_once('sibas-db.class.php');
$link = new SibasDB();

$title = '';
switch ($product) {
	case 'AU':
		$title = 'Automotores';
		break;
	case 'DE':
		$title = 'Desgravamen';
		break;
	case 'TRD':
		$title = 'Todo Riesgo Domiciliario';
		break;
	case 'TRM':
		$title = 'Todo Riesgo Equipo Movil';
		break;
}
?>
<style type="text/css">
.rp-pr-container{
	width:100%;
	height:auto;
	display:none;
}
</style>
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
	
    $(".rp-link").click(function(e){
		e.preventDefault();
		$(".rp-link").removeClass('rp-active');
		$(this).addClass('rp-active');
		
		var pr = $(this).attr('rel');
		$(".rp-pr-container").hide();
		$("#rp-tab-"+pr).fadeIn();
	});
	
	$(".f-reports").submit(function(e){
		e.preventDefault();
		$(this).find(":submit").prop('disabled', true);
		var pr = $("#pr").prop('value').toLowerCase();
		var flag = $("#flag").prop('value');
		var _data = $(this).serialize();
		
		$.ajax({
			url:'rp-records.php',
			type:'GET',
			data:'frc=&'+_data+'&flag='+flag,
			//dataType:"json",
			async:true,
			cache:false,
			beforeSend: function(){
				$(".rs-"+pr).hide();
				$(".rl-"+pr).show();
			},
			complete: function(){
				$(".rl-"+pr).hide();
				$(".rs-"+pr).show();
			},
			success: function(result){
				$(".rs-"+pr).html(result);
				$(".f-reports :submit").prop('disabled', false);
			}
		});
		return false;
	});
	
	$(".fde-process").fancybox({
		
	});
	
	$(".observation").fancybox({
		
	});
	
	var icons = {
      header: "ui-icon-circle-arrow-e",
      activeHeader: "ui-icon-circle-arrow-s"
    };
	
	$("#accordion" ).accordion({
		collapsible: true,
		icons: icons,
		heightStyle: "content",
		active: 6
	});
});
</script>
<h3 class="h3">Certificados Provisionales</h3>
<table class="rp-link-container">
	<tr>
    	<td style="width:20%;">
        	<a href="#" class="rp-link rp-active" rel="1"><?=$title;?></a>
		</td>
        <td style="width:20%; border-bottom:1px solid #CECECE;"><!--<a href="#" class="rp-link" rel="2">Automotores</a>--></td>
        <td style="width:20%; border-bottom:1px solid #CECECE;"><!--<a href="#" class="rp-link" rel="3">Todo Riesgo</a>--></td>
        <td style="width:40%; border-bottom:1px solid #CECECE;">
        	<input type="hidden" id="flag" name="flag" value="<?=md5('CP');?>">
		</td>
    </tr>
</table>
<div class="rc-records">
	<div class="rp-pr-container" id="rp-tab-1" style="display:block;">
    	<form class="f-reports">
        	<label>N° de Póliza: </label>
            <select id="frp-policy" name="frp-policy">
                <option value="">Seleccione...</option>
<?php
if (($rsPo = $link->get_policy($_SESSION['idEF'], $product)) !== FALSE) {
	while($rowPo = $rsPo->fetch_array(MYSQLI_ASSOC)){
		echo '<option value="'.base64_encode($rowPo['id_poliza']).'">'.$rowPo['no_poliza'].'</option>';
	}
}
?>
            </select>
            
            <label>N° de Certificado: </label>
            <input type="text" id="frp-nc" name="frp-nc" value="" autocomplete="off">
    
            <label>Usuario: </label>
            <input type="text" id="frp-user" name="frp-user" value="" autocomplete="off">
            <br>            
            <label>Cliente: </label>
            <input type="text" id="frp-client" name="frp-client" value="" autocomplete="off">
            
            <label style="width:auto;">C.I.: </label>
            <input type="text" id="frp-dni" name="frp-dni" value="" autocomplete="off">
            
            <label style="width:auto;">Complemento: </label>
            <input type="text" id="frp-comp" name="frp-comp" value="" autocomplete="off" style="width:40px;">
            
            <label style="width:auto;">Extension: </label>
            <select id="frp-ext" name="frp-ext">
                <option value="">Seleccione...</option>
<?php
$rsEx = $link->get_depto();
if($rsEx->data_seek(0) === TRUE){
	if($rsEx->num_rows > 1){
		while($rowEx = $rsEx->fetch_array(MYSQLI_ASSOC)){
			if((boolean)$rowEx['tipo_ci'] === TRUE)
				echo '<option value="'.$rowEx['id_depto'].'">'.$rowEx['departamento'].'</option>';
		}
		$rsEx->free();
	}
}
?>
            </select><br>
            <input type="hidden" id="frp-canceled-p" name="frp-canceled-p" value="0" >
            
            <label style="">Fecha: </label>
            <label style="width:auto;">desde: </label>
            <input type="text" id="frp-date-b" name="frp-date-b" value="" autocomplete="off" class="date" readonly>
            
            <label style="width:auto;">hasta: </label>
            <input type="text" id="frp-date-e" name="frp-date-e" value="" autocomplete="off" class="date" readonly><br>
            
            <input type="hidden" id="frp-id-user" name="frp-id-user" value="<?=$_SESSION['idUser'];?>">
            <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
            <input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
            <input type="hidden" id="data-pr" name="data-pr" value="<?=base64_encode($product);?>" >
            <input type="hidden" id="pr" name="pr" value="<?=$product;?>">
            <br>
            <!--<div id="accordion">
                <h5>Pendiente</h5>
                <div>
                    <label class="lbl-cb"><input type="checkbox" id="frp-pe" name="frp-pe" value="P">Pendiente</label>
                    <label class="lbl-cb"><input type="checkbox" id="frp-sp" name="frp-sp" value="S">Subsanado/Pendiente</label>
                    <label class="lbl-cb"><input type="checkbox" id="frp-ob" name="frp-ob" value="O">Observado</label><br>
<?php
$sqlSt = 'SELECT id_estado, estado FROM s_estado WHERE producto = "DE" ORDER BY id_estado ASC ;';
$rsSt = $link->query($sqlSt,MYSQLI_STORE_RESULT);
while($rowSt = $rsSt->fetch_array(MYSQLI_ASSOC)){
	echo '<label class="lbl-cb"><input type="checkbox" id="frp-estado-'.$rowSt['id_estado'].'" name="frp-estado-'.$rowSt['id_estado'].'" value="'.$rowSt['id_estado'].'">'.$rowSt['estado'].'</label> ';
}
$rsSt->free();
?>
                </div>
                <h5>Aprobado</h5>
                <div>
                	<label class="lbl-cb"><input type="checkbox" id="frp-approved-fc" name="frp-approved-fc" value="FC">Free Cover</label>
                    <label class="lbl-cb"><input type="checkbox" id="frp-approved-nf" name="frp-approved-nf" value="NF">No Free Cover</label>
                    <label class="lbl-cb"><input type="checkbox" id="frp-approved-ep" name="frp-approved-ep" value="EP">Extraprima</label>
                    <label class="lbl-cb"><input type="checkbox" id="frp-approved-np" name="frp-approved-np" value="NP">No Extraprima</label>
                    <label class="lbl-cb"><input type="checkbox" id="frp-approved-em" name="frp-approved-em" value="EM">Emitido</label>
                    <label class="lbl-cb"><input type="checkbox" id="frp-approved-ne" name="frp-approved-ne" value="NE">No Emitido</label>
                </div>
                <h5>Rechazado</h5>
                <div>
                    <label class="lbl-cb"><input type="checkbox" id="frp-rejected" name="frp-rejected" value="RE">Rechazado</label>
                </div>
                <h5>Anulado</h5>
                <div>
                	<label class="lbl-cb"><input type="checkbox" id="frp-canceled" name="frp-canceled" value="AN">Anulado</label>
                </div>
            </div>-->
    
            <div align="center">
            	<input type="hidden" id="idef" name="idef" value="<?=$_SESSION['idEF'];?>">
                <input type="submit" id="frp-search" name="frp-search" value="Buscar" class="frp-btn">
                <input type="reset" id="frp-reset" name="frp-reset" value="Restablecer Campos" class="frp-btn">
            </div>
        </form>
        <div class="result-container">
            <div class="result-loading rl-<?=strtolower($product);?>"></div>
            <div class="result-search rs-<?=strtolower($product);?>"></div>
        </div>
    </div>
    
    <div class="rp-pr-container" id="rp-tab-2">2</div>
    <div class="rp-pr-container" id="rp-tab-3">3</div>
</div>