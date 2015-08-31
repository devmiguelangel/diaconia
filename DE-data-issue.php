<script type="text/javascript">
$(document).ready(function(e) {
    $("#dcr-type-mov").change(function(){
		var tm = $(this).prop('value');
		var sw = 0;
		var amount = $("#dcr-amount").prop('value');
		var currency = $("#dcr-currency").prop('value');
		var tcm = $('#tcm').prop('value');
		var mess = 'No tomar en cuenta <br> Solicitud Actual';
		var _idd = $("#dcr-amount-acc").prop('id');
		var _idc = $("#dcr-amount-acc-2").prop('id');
		var per1 = $('#dc-1-share').prop('value');
		
		if (currency === 'USD') {
			tcm = parseFloat(tcm);
			amount = amount * tcm;
		}
		
		if($("#"+_idd+" + .msg-form-lc").length)
			$("#"+_idd+" + .msg-form-lc").remove();
		
		if($("#"+_idc+" + .msg-form-lc").length)
			$("#"+_idc+" + .msg-form-lc").remove();
		
		$(".amount, .amount-2, .amount-type").show();
		$("#dcr-amount-de, #dcr-amount-cc").removeClass('required');
		//$("#dcr-amount-de, #dcr-amount-cc").prop('value', '0');
		$("#dcr-amount-de, #dcr-amount-cc").prop('readonly', true);
		//$("#dcr-amount-acc, #dcr-amount-acc-2").prop('value', amount);
		$(".dcr-amount-ac, .amount-mess-lc").hide();
		
		switch(tm){
			case 'PU':
				sw = 1;
				$("#dcr-amount-acc, #dcr-amount-acc-2").prop('value', amount);
				break;
			case 'AD':
				sw =2;
				$(".dcr-amount-de").addClass('required').prop('readonly', false);
				break;
			case 'LC':
				sw = 3;
				$("#dcr-amount-de, #dcr-amount-cc").prop('readonly', false);
				mess = 'Llenar solo en caso que el cliente tenga créditos adicionales, FUERA de la línea.';
				break;
		}
		
		//$(".amount, .amount-2").html(amount);
		$(".amount-text").html(mess);
		
		if(sw !== 0){
			$("#dcr-amount-acc, #dcr-amount-acc-2").addClass('required');
			if(sw === 3){
				$(".amount-text").addClass('amount-lc');
				$("#dcr-amount-de, #dcr-amount-cc").prop('value', 0);
				$("#dcr-amount-acc, #dcr-amount-acc-2").prop('value', '');
				
				$(".amount, .amount-2, .amount-type").hide();
				$("#dcr-amount-acc, #dcr-amount-acc-2, .amount-mess-lc").show();
			}else{
				$(".amount-text").removeClass('amount-lc');
			}
			$(".amout-total, .amount-icon, .amount-text").show();
		}else{
			$(".amout-total, .amount-icon, .amount-text").hide();
		}
	});
	
	$(".dcr-amount-de").keyup(function(e){
		var rel = parseInt($(this).attr('rel'));
		var amount = 0;

		if ($('#dc-' + rel + '-amount-bc').length) {
			amount = parseFloat($('#dc-' + rel + '-amount-bc').prop('value'));
		} else {
			amount = parseFloat($('#dcr-amount').prop('value'));
		}
		//var amount = parseFloat($('#dc-' + rel + '-amount-bc').prop('value'));
		var amount_de = parseFloat($(this).prop('value'));
		var currency = $("#dcr-currency").prop('value');
		var tcm = $('#tcm').prop('value');
		var total = 0;
		if(currency === 'USD') {
			tcm = parseFloat(tcm);
			amount = amount * tcm;
		}
		
		if(!isNaN(amount_de)){
			if(validarRealf(amount_de) === true){
				total = amount + amount_de;
				if($("#dcr-type-mov").prop('value') === 'AD'){
					$('#dcr-amount-ac-' + rel).prop('value', total);
					$('.amount-' + rel).html(total);
				}
			}else{
				$(this).prop('value', '');
			}
		}else{
			$(this).prop('value', '');
			$("#dcr-amount-ac-" + rel).prop('value', amount);
			$(".amount-" + rel).html(amount);
		}
	});
	
	$("#dcr-amount-acc, #dcr-amount-acc-2").keyup(function(e){
		var rel = parseInt($(this).attr('rel'));
		
		var amount = parseFloat($("#dcr-amount").prop('value'));
		var amount_de = 0;
		var text = '';
		if(rel === 1){
			amount_de = parseFloat($("#dcr-amount-de").prop('value'));
			text = 'deudor';
		}else if(rel === 2){
			amount_de = parseFloat($("#dcr-amount-cc").prop('value'));
			text = 'codeudor';
		}
		
		var amount_acc = parseFloat($(this).prop('value'));
		var _id = $(this).prop('id');
		var currency = $("#dcr-currency").prop('value');
		if(currency === 'USD')
			amount = amount * 7;
		
		if(!isNaN(amount_acc)){
			if(validarRealf(amount_acc) === true){
				if(amount_acc >= amount && amount_acc >= amount_de){
					if($("#"+_id+" + .msg-form-lc").length)
						$("#"+_id+" + .msg-form-lc").remove();
				}else{
					if(!$("#"+_id+" + .msg-form-lc").length)
						$("#"+_id+":last").after('<span class="msg-form-lc">El monto total acumulado debe ser mayor o igual al monto actual solicitado y al saldo '+text+' actual</span>');
				}
			}else{
				$(this).prop('value', '');
			}
		}else{
			$(this).prop('value', '');
		}
	});
	
	$("select.readonly option").not(":selected").attr("disabled", "disabled");
	
	$("input[type='text'].fbin, textarea.fbin").keyup(function(e){
		var arr_key = new Array(37, 39, 8, 16, 32, 18, 17, 46, 36, 35);
		var _val = $(this).prop('value');
		
		if($.inArray(e.keyCode, arr_key) < 0 && $(this).hasClass('email') === false){
			$(this).prop('value',_val.toUpperCase());
		}
	});
});


function validarRealf(dat){
	var er_num=/^([0-9])*[.]?[0-9]*$/;
	if(dat.value != ""){
		if(!er_num.test(dat))
			return false;
		return true
	}
}
</script>
<?php

require __DIR__ . '/app/controllers/DiaconiaController.php';
require __DIR__ . '/app/controllers/ClientController.php';
require __DIR__ . '/app/controllers/QuestionController.php';
require __DIR__ . '/app/controllers/PolicyController.php';

$Diaconia = new DiaconiaController();
$PolicyController = new PolicyController();
$ClientController = new ClientController();
$QuestionController = new QuestionController();

$depto = $ClientController->getDepto();

$ide 		= 0;
$idc 		= 0;
$_target 	= false;
$max_item 	= 0;
$action 	= '';
$read_new 	= '';
$read_save 	= '';
$read_edit 	= '';
$title 		= '';
$title_btn 	= '';
$cp 		= null;
$bc 		= false;
$sw 		= 0;
$data		= array();
$n_cl		= 0;

$flag = $_GET['flag'];

if (isset($_GET['ide'])) {
	$ide = $_GET['ide'];
} elseif (isset($_GET['idc'])) {
	$idc = $_GET['idc'];
}

if (isset($_GET['target'])) {
	if ($_GET['target'] === md5('ERROR-C')) {
		$_target = true;
	}
}

if (($data_pr = $Diaconia->getDataProduct($_SESSION['idEF'])) !== false) {
	$max_item = (int)$data_pr['max_detalle'];
}

$PolicyController->setLabelData($flag, $sw, $action, $title, $title_btn, $read_new, $read_save, $read_edit);

if (($data = $PolicyController->getPolicyData($sw, $idc, $ide, $_SESSION['idEF'])) !== false) {
	switch ($data[0]['cobertura']) {
	case 'BC':
		$bc = true;
		break;
	}

	$n_cl = count($data);

	if ($n_cl <= $max_item) {
		$cont 			= 0;
		$rsCl 			= array();
		$cr_coverage 	= 0;
		$cr_product 	= 0;
		$cr_modality 	= '';
		$cr_amount 		= 0;
		$cr_currency 	= '';
		$cr_term 		= 0;
		$cr_type_term 	= '';
		$cr_type_mov 	= '';
		$cr_opp 		= '';
		$cr_policy 		= '';
		$cr_amount_de 	= 0;
		$cr_amount_cc 	= 0;
		$cr_amount_ac 	= '';
		$idNE 			= '';
		$swDE 			= false;
		$swMo 			= false;
		$disMo 			= '';
		$readMo 		= '';
		$FC 			= false;
?>
<h3 id="issue-title"><?=$title;?></h3>
<a href="certificate-detail.php?idc=<?= base64_encode($data[0]['id_cotizacion']) ;?>&cia=<?=
	$_GET['cia'];?>&type=<?=base64_encode('PRINT');?>&pr=<?=
	base64_encode('DE');?>" 
	class="fancybox fancybox.ajax btn-see-slip">Ver Slip Cotización</a>
<form id="fde-issue" name="fde-issue" action="" method="post" class="form-quote form-customer">
	<?php foreach ($data as $key => $row): 
		$cont += 1;
		$rsCl[$cont] 	= json_decode($row['cl_respuesta'], true);
		$cr_coverage 	= $row['cobertura'];
		$cr_product 	= $row['id_prcia'];
		$cr_modality 	= $row['modalidad'];
		$cr_amount 		= $row['monto'];
		$cr_currency 	= $row['moneda'];
		$cr_term 		= $row['plazo'];
		$cr_type_term 	= $row['tipo_plazo'];
		$cl_dir_office 	= '';
		$cl_hand 		= '';	
		$cl_avc 		= '';	
		$cl_nd 			= '';	

		if($sw !== 1){
			$idNE 			= $row['prefijo'] . '-' . $row['no_emision'];
			$cr_type_mov 	= $row['operacion'];
			$cr_opp 		= $row['no_operacion'];
			$cr_policy 		= $row['id_poliza'];
			$cl_hand 		= $row['cl_mano'];	
			$cl_avc 		= $row['cl_avenida'];
			$cl_nd 			= $row['cl_nd'];
			$cl_dir_office 	= $row['cl_direccion_laboral'];
			$mFC 			= $row['motivo_facultativo'];
			
			if((boolean)$row['facultativo'] === true) {
				$FC = true;
			}

		} else {
			$cp = $row['cp'];
	        if($cont === 1) {
	            $cr_amount_de = $row['cl_saldo'];
	        } elseif($cont === 2) {
	            $cr_amount_cc = $row['cl_saldo'];
	        }
		}
		
		if(($cr_currency === 'BS' && $cr_amount > 35000) 
			|| ($cr_currency === 'USD' && $cr_amount > 5000)){
			$swDE = true;
		}
			
		if ($cr_modality !== null) {
			$swMo 	= true;
			$disMo 	= 'display: none;';
			$readMo = 'readonly';
		}

	
	?>
	<h4>Titular <?=$cont;?></h4>
	<div class="form-col">
		<label>Nombres: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-name" name="dc-<?=$cont;?>-name" 
				autocomplete="off" value="<?=$row['cl_nombre'];?>" 
				class="required text fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Apellido Paterno: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-ln-patert" name="dc-<?=$cont;?>-ln-patern" 
				autocomplete="off" value="<?=$row['cl_paterno'];?>" 
				class="required text fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Apellido Materno: </label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-ln-matern" name="dc-<?=$cont;?>-ln-matern" 
				autocomplete="off" value="<?=$row['cl_materno'];?>" 
				class="not-required text fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Apellido de Casada: </label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-ln-married" name="dc-<?=$cont;?>-ln-married" 
				autocomplete="off" value="<?=$row['cl_casada'];?>" 
				class="not-required text fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Género: <span>*</span></label>
		<div class="content-input">
			<select id="dc-<?=$cont;?>-gender" name="dc-<?=$cont;?>-gender" 
				class="required fbin <?=$read_new;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
				<?php foreach ($ClientController->getGender() as $key => $value): $selected = '' ?>
					<?php if ($key === $row['cl_genero']): $selected = 'selected' ?>
		    		<?php endif ?>
					<option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>';
				<?php endforeach ?>
			</select>
		</div><br>

		<label>Estado Civil: <span>*</span></label>
		<div class="content-input">
			<select id="dc-<?=$cont;?>-status" name="dc-<?=$cont;?>-status" 
				class="required fbin <?=$read_new;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
				<?php foreach ($ClientController->getStatus() as $key => $value): $selected = '' ?>
		    		<?php if ($key === $row['cl_estado_civil']): $selected = 'selected' ?>
		    		<?php endif ?>
					<option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
		    	<?php endforeach ?>
		</select>
		</div><br>
		
		<label>Tipo de Documento: <span>*</span></label>
		<div class="content-input">
			<select id="dc-<?=$cont;?>-type-doc" name="dc-<?=$cont;?>-type-doc" 
				class="required fbin <?=$read_new;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
				<?php foreach ($ClientController->getTypeDoc() as $key => $value): $selected = '' ?>
					<?php if ($key === $row['cl_tipo_documento']): $selected = 'selected' ?>
		    		<?php endif ?>
					<option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
				<?php endforeach ?>
			</select>
		</div><br>
		
		<label>Documento de Identidad: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-doc-id" name="dc-<?=$cont;?>-doc-id" 
				autocomplete="off" value="<?=$row['cl_ci'];?>" 
				class="required dni fbin" <?=$read_new.$read_edit;?>>
		</div><br>
		
		<label>Complemento: </label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-comp" name="dc-<?=$cont;?>-comp" 
				autocomplete="off" value="<?=$row['cl_complemento'];?>" 
				class="not-required dni fbin" style="width:60px;" <?=$read_new;?>>
		</div><br>
		
		<label>Extensión: <span>*</span></label>
		<div class="content-input">
			<select id="dc-<?=$cont;?>-ext" name="dc-<?=$cont;?>-ext" 
				class="required fbin <?=$read_new;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
				<?php foreach ($depto as $key => $value): $selected = '' ?>
            		<?php if ((boolean)$value['tipo_ci'] === true): ?>
	            		<?php if ($value['id_depto'] === $row['cl_extension']): $selected = 'selected' ?>
			    		<?php endif ?>
						<option value="<?= $value['id_depto'] ;?>" 
							<?= $selected ;?>><?= $value['departamento'] ;?></option>';
            		<?php endif ?>
            	<?php endforeach ?>
			</select>
		</div><br>
		
		<label>Fecha de Nacimiento: <span>*</span></label>
		<div class="content-input">
        	<input type="hidden" id="dc-<?=$cont;?>-age" 
        		name="dc-<?=$cont;?>-age" value="<?=$row['cl_edad'];?>">
			<input type="text" id="dc-<?=$cont;?>-date-birth" name="dc-<?=$cont;?>-date-birth" 
				autocomplete="off" value="<?=$row['cl_fecha_nacimiento'];?>" 
				class="required fbin date-birth" <?=$read_new;?>>
		</div><br>
		
		<label>País: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-country" name="dc-<?=$cont;?>-country" 
				autocomplete="off" value="<?=$row['cl_pais'];?>" 
				class="required text fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Lugar de Nacimiento: <span></span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-place-birth" name="dc-<?=$cont;?>-place-birth" 
				autocomplete="off" value="<?=$row['cl_lugar_nacimiento'];?>" 
				class="not-required fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Lugar de Residencia: <span></span></label>
		<div class="content-input">
			<select id="dc-<?=$cont;?>-place-res" name="dc-<?=$cont;?>-place-res" 
				class="not-required fbin <?=$read_new;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
				<?php foreach ($depto as $key => $value): $selected = '' ?>
					<?php if ((boolean)$value['tipo_dp'] === true): ?>
	            		<?php if ($value['id_depto'] === $row['cl_lugar_residencia']): $selected = 'selected' ?>
			    		<?php endif ?>
						<option value="<?= $value['id_depto'] ;?>" 
							<?= $selected ;?>><?= $value['departamento'] ;?></option>
					<?php endif ?>
            	<?php endforeach ?>
			</select>
		</div><br>
		
		<label>Localidad: <span></span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-locality" name="dc-<?=$cont;?>-locality" 
				autocomplete="off" value="<?=$row['cl_localidad'];?>" 
				class="not-required text-2 fbin" <?=$read_new;?>>
		</div><br>
        
        <label>Mano utilizada para escribir y/o firmar: <span></span></label>
		<div class="content-input">
			<select id="dc-<?=$cont;?>-hand" name="dc-<?=$cont;?>-hand" 
				class="not-required fbin <?=$read_save;?>" <?=$read_save;?>>
            	<option value="">Seleccione...</option>
            	<?php foreach ($ClientController->getHand() as $key => $value): ?>
					<?php if ($key === $cl_hand): $selected = 'selected' ?>
		    		<?php endif ?>
					<option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
				<?php endforeach ?>
            </select>
		</div><br>
	</div><!--
	--><div class="form-col">
		<label>Avenida o Calle: <span></span></label>
		<div class="content-input">
        	<select id="dc-<?=$cont;?>-avc" name="dc-<?=$cont;?>-avc" 
        		class="not-required fbin <?=$read_save;?>" <?=$read_save;?>>
            	<option value="">Seleccione...</option>
            	<?php foreach ($ClientController->getAvc() as $key => $value): ?>
					<?php if ($key === $cl_avc): $selected = 'selected' ?>
		    		<?php endif ?>
					<option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
				<?php endforeach ?>
            </select>
		</div><br>
		
		<label>Dirección domicilio: <span>*</span></label><br>
		<textarea id="dc-<?=$cont;?>-address-home" name="dc-<?=$cont;?>-address-home" 
			class="required fbin" <?=$read_new;?>><?=$row['cl_direccion'];?></textarea><br>
		
		<label>Número de domicilio: <span></span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-nhome" name="dc-<?=$cont;?>-nhome" 
				autocomplete="off" value="<?=$cl_nd;?>" 
				class="not-required number fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Teléfono 1: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-phone-1" name="dc-<?=$cont;?>-phone-1" 
				autocomplete="off" value="<?=$row['cl_telefono_domicilio'];?>" 
				class="required phone fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Teléfono 2: </label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-phone-2" name="dc-<?=$cont;?>-phone-2" 
				autocomplete="off" value="<?=$row['cl_telefono_celular'];?>" 
				class="not-required phone fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Email: </label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-email" name="dc-<?=$cont;?>-email" 
			autocomplete="off" value="<?=$row['cl_email'];?>" 
			class="not-required email fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Ocupación: <span>*</span></label>
		<div class="content-input">
			<select id="dc-<?=$cont;?>-occupation" name="dc-<?=$cont;?>-occupation" 
				class="required fbin <?=$read_new;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
				<?php foreach ($ClientController->getOccupation($_SESSION['idEF']) as $key => $value):
					$selected = '' ?>
					<?php if ($value['id_ocupacion'] === $row['cl_ocupacion']): $selected = 'selected' ?>
		    		<?php endif ?>
					<option value="<?= base64_encode($value['id_ocupacion']) ;?>" 
						<?= $selected ;?>><?= $value['ocupacion'] ;?></option>
				<?php endforeach ?>
			</select>
		</div><br>
		
		<label style="width:auto;">Descripción Ocupación: <span>*</span></label><br>
		<textarea id="dc-<?=$cont;?>-desc-occ" name="dc-<?=$cont;?>-desc-occ" 
			class="required fbin" <?=$read_new;?>><?=$row['cl_desc_ocupacion'];?></textarea><br>
		
		<label>Dirección laboral: <span></span></label><br>
		<textarea id="dc-<?=$cont;?>-address-work" name="dc-<?=$cont;?>-address-work" 
			class="not-required fbin" <?=$read_save;?>><?=$cl_dir_office;?></textarea><br>
		
		<label>Teléfono oficina: </label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-phone-office" 
				name="dc-<?=$cont;?>-phone-office" autocomplete="off" 
				value="<?=$row['cl_telefono_oficina'];?>" 
				class="not-required phone fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Peso (kg): <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-weight" name="dc-<?=$cont;?>-weight" 
				autocomplete="off" value="<?=$row['cl_peso'];?>" 
				class="required fbin" <?=$read_new.$read_edit;?>>
		</div><br>
		
		<label>Estatura (cm): <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-height" name="dc-<?=$cont;?>-height" 
				autocomplete="off" value="<?=$row['cl_estatura'];?>" 
				class="required fbin" <?=$read_new.$read_edit;?>>
		</div><br>
		<?php if ($bc === true): ?>
		<label>Monto<br>Banca Comunal: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-amount-bc" name="dc-<?=$cont;?>-amount-bc" 
				autocomplete="off" value="<?=$row['cl_monto_bc'];?>" 
				class="required real fbin" <?=$read_new.$read_edit;?>>
		</div><br>
		<input type="hidden" id="dc-<?=$cont;?>-tasa" name="dc-<?=$cont;?>-tasa" 
			value="<?=$row['cl_tasa'];?>" >
		<?php endif ?>

		<label>Participación: % <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-share" name="dc-<?=$cont;?>-share" 
				autocomplete="off" value="<?=$row['cl_porcentaje_credito'];?>" 
				class="required real fbin" <?=$read_new.$read_edit;?>>
		</div><br>
		<?php if ($sw === 1): $row['id_cliente'] = uniqid('@S#1$2013-' . $cont . '-', true); ?>
			
		<?php endif ?>

		<input type="hidden" id="dc-<?=$cont;?>-idcl" name="dc-<?=$cont;?>-idcl" 
			autocomplete="off" value="<?=base64_encode($row['id_cliente']);?>" 
			class="required fbin" <?=$read_new;?>>
	</div><br>
	<?php if ($cont === 1): ?>
		<input type="hidden" id="dc-<?= $cont ;?>-titular" 
		name="dc-<?= $cont ;?>-titular" value="DD">
	<?php elseif ($cont >= 2): ?>
		<input type="hidden" id="dc-<?= $cont ;?>-titular" 
			name="dc-<?= $cont ;?>-titular" value="CC">
	<?php endif ?>
	<br>
	<?php endforeach ?>

	<?php if (($questions = $QuestionController->getQuestion($_SESSION['idEF'])) !== false): 
		$resp 		= array();
		$required 	= array();
		$fac 		= array();
	?>
		<hr>
		<h4>Resultado de las Preguntas</h4>
		<div style="width: 100%; overflow-x: scroll;">
			<div class="question result-question" style="width: 600%;">
				<span class="qs-no">&nbsp;</span>
				<p class="qs-title" style="text-align:center; font-weight:bold;">Preguntas</p>
				<?php for ($i = 1; $i <= $n_cl; $i++): 
					$resp[$i] 		= '';
					$required[$i] 	= '';
					$fac[$i] 		= 1;
				?>
				<div class="qs-option">Titular <?= $i ;?></div>
				<?php endfor ?>
			</div>

			<?php foreach ($questions as $key => $question): ?>
			<div class="question result-question" style="width: 600%;">
				<span class="qs-no"><?= $question['orden'] ;?></span>
				<p class="qs-title"><?= $question['pregunta'] ;?></p>
<?php
			for ($i = 1; $i <= $n_cl; $i++) {
				if (count($rsCl[$i]) > 0) {
					$respCl = $rsCl[$i][$question['orden']];
					
					if ($question['id_pregunta'] == $respCl['id']) {
						if ($respCl['value'] === 1) {
							$resp[$i] = 'SI';
						} elseif($respCl['value'] === 0) {
							$resp[$i] = 'NO';
						}
							
						if ($respCl['value'] != $question['respuesta']) {
							$required[$i] = 'required';
							$fac[$i] += 1;
						}
					}
				}

				echo '<div class="qs-option">' . $resp[$i] . '</div>';
			}
?>
			</div>
		<?php endforeach ?>
		</div>

		<?php 
			$cont 			= 0;
			$required_qs 	= '';
			$fac_qs 		= 0;
			foreach ($data as $key => $row):
				$cont += 1;
				$required_qs	= $required[$cont];	
				$fac_qs 		= $fac[$cont];
			?>
			<label style="width:auto;">Aclaraciones Titular <?=$cont;?></label>
			<textarea id="dq-<?=$cont;?>-resp" name="dq-<?=$cont;?>-resp" 
				style="width:600px; height:100px; margin:4px auto 18px auto;" 
				class="fbin <?=$required_qs;?>" <?=$read_save;?>><?=$row['cl_observacion'];?></textarea>
		    <input type="hidden" id="dq-<?=$cont;?>-idd" name="dq-<?=$cont;?>-idd" 
		    	value="<?=base64_encode($row['id_detalle']);?>">
		    <input type="hidden" id="dq-<?=$cont;?>-idr" name="dq-<?=$cont;?>-idr" 
		    	value="<?=base64_encode($row['id_respuesta']);?>">
		    <input type="hidden" id="dq-<?=$cont;?>-fac" name="dq-<?=$cont;?>-fac" 
		    	value="<?=base64_encode($fac[$cont]);?>"><br>
			<?php endforeach ?>
	<?php endif ?>
	<hr>
	<h4>Datos del Crédito Solicitado</h4>
	<div class="form-col">
		<label>Tipo de Cobertura: <span>*</span></label>
		<div class="content-input">
        	<select id="dcr-coverage" name="dcr-coverage" 
        		class="not-required fbin <?=$read_new.$read_edit;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
				<?php foreach ($Diaconia->getCoverage() as $key => $value): $selected = '' ?>
					<?php if ($key === $cr_coverage): $selected = 'selected' ?>
					<?php endif ?>
				<option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
				<?php endforeach ?>
			</select>
		</div><br>
		
		<label>Monto Actual Solicitado: <span>*</span></label>
		<div class="content-input">
        	<input type="hidden" id="dcr-amount-r" name="dcr-amount-r" 
        		value="<?=(int)$cr_amount;?>">
			<input type="text" id="dcr-amount" name="dcr-amount" 
				autocomplete="off" value="<?=(int)$cr_amount;?>" 
				class="required number fbin" <?=$read_new . $read_edit;?>>
		</div><br>
		
		<label>Moneda: <span>*</span></label>
		<div class="content-input">
        	<input type="hidden" id="dcr-currency-r" name="dcr-currency-r" value="<?=$cr_currency;?>">
        	<select id="dcr-currency" name="dcr-currency" 
        		class="required fbin  <?=$read_new . $read_edit;?>" <?=$read_save ;?>>
				<option value="">Seleccione...</option>
				<?php foreach ($Diaconia->getCurrency() as $key => $value): $selected = '' ?>
					<?php if ($key === $cr_currency): $selected = 'selected' ?>
					<?php endif ?>
				<option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
				<?php endforeach ?>
			</select>
		</div><br>
		
		<label>Plazo del Crédito: <span>*</span></label>
		<div class="content-input" style="width:auto;">
			<input type="text" id="dcr-term" name="dcr-term" autocomplete="off" 
				value="<?=$cr_term;?>" style="width:30px;" maxlength="" 
				class="not-required number fbin" <?=$read_new;?>>
		</div>
		
		<label>&nbsp;</label>
		<div class="content-input">
			<select id="dcr-type-term" name="dcr-type-term" 
				class="required fbin <?=$read_new . $read_edit;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
				<?php foreach ($Diaconia->getTypeTerm() as $key => $value): $selected = '' ?>
					<?php if ($key === $cr_type_term): $selected = 'selected' ?>
					<?php endif ?>
				<option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
				<?php endforeach ?>
			</select>
		</div><br>
	</div><!--
	--><div class="form-col">
		<?php if (count(($products = $Diaconia->getProductData($_SESSION['idEF']))) > 0): ?>
		<label>Producto: <span>*</span></label>
		<div class="content-input">
			<select id="dl-product" name="dl-product" class="required 
				fbin <?= $read_new . $read_edit ;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
				<?php foreach ($products as $key => $value): $selected = '' ?>
					<?php if ($value['id'] === $cr_product): $selected = 'selected' ?>
					<?php endif ?>
				<option value="<?= $value['id'] ;?>" <?= $selected ;?>><?= $value['producto'] ;?></option>
				<?php endforeach ?>
			</select>
		</div>
		<?php else: ?>
			<input type="hidden" id="prcia" name="prcia" 
				value="<?= base64_encode($cr_product) ;?>">
		<?php endif ?>

		<?php if ($swMo): ?>
		<input type="hidden" id="dcr-modality" name="dcr-modality" 
			value="<?=base64_encode($cr_modality);?>">
		<?php else: $disMo = 'display: none;'; ?>
			<?php if ($sw === 1): ?>
<script type="text/javascript">
$(document).ready(function(){
	$('#dcr-type-mov option[value="AD"]').prop('selected', true);
	$('#dcr-type-mov').trigger('change');

	$('#fde-issue').find('.dcr-amount-de').each(function(index, element){
		$(element).trigger('keyup');
	});
});
</script>
			<?php endif ?>
		<?php endif ?>

		<div style="<?=$disMo;?>">
			<label>Tipo de Movimiento: <span>*</span></label>
			<div class="content-input">
				<select id="dcr-type-mov" name="dcr-type-mov" 
					class="required fbin" <?=$read_save . ' ' . $read_edit;?>>
					<option value="">Seleccione...</option>
					<?php foreach ($Diaconia->getMoviment() as $key => $value): $selected = '' ?>
						<?php if ($key === $cr_type_mov): $selected = 'selected' ?>
						<?php endif ?>
						<option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>

		<label>Número de Crédito: <span>*</span></label>
		<div class="content-input" style="width:auto;">
			<input type="text" id="dcr-opp" name="dcr-opp" autocomplete="off" 
				value="<?=$cr_opp;?>" class="required number fbin" <?=$read_save;?>>
		</div>
<?php
$opp_dis1 	= '';
$opp_dis2 	= '';
$opp_dis3 	= '';
$opp_dis4 	= '';
$opp_txt1 	= 'No tomar en cuenta<br>Solicitud Actual';
$opp_class 	= '';
$opp_read 	= 'readonly';

if ($sw === 3) {
	$opp_dis1 = 'display: block;';
	$opp_dis2 = 'display: block;';	
	
	switch($cr_type_mov){
	case 'PU':
		$opp_dis4 = 'display: none;';
		break;
	case 'AD':
		$opp_dis4 = 'display: none;';
		$opp_read = '';
		break;
	case 'LC':
		$opp_txt1 	= 'Llenar solo en caso que el cliente tenga 
			créditos adicionales, FUERA de la línea.';
		$opp_dis3 	= 'display: none;';
		$opp_class 	= 'amount-lc';
		$opp_read 	= '';
		break;
	}
} elseif ($sw === 2) {
	$opp_dis2 = 'display: block;';
	$opp_dis3 = 'visibility:visible;';
	$opp_dis4 = 'display: none;';
}

$arr_sub_title 	= array('Titular', 'Deudor', 'Codeudor');
$sub_title 		= '';
$percentage 	= 0;

$k = 0;

foreach ($data as $key => $row) {
	$k += 1;
	if ($bc === true) {
		$sub_title = $arr_sub_title[0] . ' ' . $k;
	} else {
		$sub_title = $arr_sub_title[$k];
	}

	$cr_amount_de = (float)$row['cl_saldo'];

	if ($sw !== 1) {
		$cr_amount_ac = (float)$row['cl_cumulo'];
	} else {
		$percentage = (float)$row['cl_porcentaje_credito'] / 100;
		//$cr_amount_ac = (int)$row['monto'] * $percentage;
		$cr_amount_ac = (float)$row['cl_monto_bc'];
		if (empty($cr_amount_ac) === true) {
			$cr_amount_ac = (int)$cr_amount;
		}
	}
?>
		<label>Saldo <?=$sub_title;?> actual del asegurado (Bs.) : </label>
		<div class="content-input" style="width:auto;">
			<input type="hidden" id="tcm" name="tcm" 
				value="<?= $Diaconia->getRateExchange(true) ;?>" >
			<input type="text" id="dcr-amount-de-<?=$k;?>" name="dcr-amount-de-<?=$k;?>" 
				autocomplete="off" value="<?=(float)$cr_amount_de;?>" 
				class="required real fbin dcr-amount-de" <?=$opp_read.' '.$read_save;?> rel="<?=$k;?>">
            <span class="amount-mess">
            	<div class="amount-icon" style=" <?=$opp_dis1;?> "></div>
            	<div class="amount-text <?=$opp_class;?>" style=" <?=$opp_dis1;?> "><?=$opp_txt1;?></div>
            </span>
		</div>
		<div class="amout-total" style=" <?=$opp_dis2;?> ">
			Monto Total Acumulado<br>
			<span class="amount-type" style=" <?=$opp_dis3;?> "> Bs.</span>
			<span class="amount-<?=$k;?>" style="<?=$opp_dis3;?> font-weight: bold; font-size: 150%;">
				<?=$cr_amount_ac?>
			</span>
            <br>
            <input type="text" id="dcr-amount-ac-<?=$k;?>" name="dcr-amount-ac-<?=$k;?>" 
            	autocomplete="off" value="<?=$cr_amount_ac;?>" 
            	class="real fbin dcr-amount-ac" style=" <?=$opp_dis4;?> " rel="1"><br>
            <span class="amount-mess-lc" style=" <?=$opp_dis4;?> ">
            	Sumatoria del total de operaciones vigentes, incluyendo el Monto Actual Solicitado.
            </span>
		</div>
<?php


}

?>
	</div>

	<input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
	<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
	<input type="hidden" id="pr" name="pr" value="<?=$_GET['pr'];?>">
    <input type="hidden" id="flag" name="flag" value="<?=$_GET['flag'];?>">
    <input type="hidden" id="cia" name="cia" value="<?=$_GET['cia'];?>">
    <input type="hidden" id="ncl-data" name="ncl-data" value="<?=base64_encode($n_cl);?>" >
    <?php if ($sw === 1): ?>
    	<input type="hidden" id="cp" name="cp" value="<?= base64_encode($cp) ;?>">
    <?php endif ?>
    <?php $target = ''; 
    if ($_target): $target = '&target=' . $_GET['target']; ?>
    	<input type="hidden" id="target" name="target" value="<?= $_GET['target'] ;?>">
    <?php endif ?>
    <?php $idd = ''; 
    if (isset($_GET['idd'])): $idd = '&idd=' . $_GET['idd']; ?>
    	<input type="hidden" id="idd" name="idd" value="<?= $_GET['idd'] ;?>" >
    <?php endif ?>
    <?php if (isset($_GET['ide'])): ?>
    	<input type="hidden" id="de-ide" name="de-ide" value="<?= $ide ;?>" >
    <?php elseif(isset($_GET['idc'])): ?>
    	<input type="hidden" id="de-idc" name="de-idc" value="<?= $idc ;?>" >
    <?php endif ?>
	
	<div style="text-align:center;">
	<?php if ($sw === 2): ?>
		<input type="button" id="dc-edit" name="dc-edit" value="Editar" class="btn-next btn-issue" >
	<?php endif ?>
<?php
	// IMPLANTE
	// $_IMP = $link->verify_implant($_SESSION['idEF'], 'DE');
	$_IMP = false;
	
	if($_IMP === TRUE) {
		if ($link->verify_agency_issuing($_SESSION['idUser'], $_SESSION['idEF'], 'DE') === TRUE && $sw === 2) {
			if($FC === TRUE && $sw === 2){
				if($_target === false) {
					goto btnApproval;
				}
			} else {
				btnIssue: 
				echo '<input type="submit" id="dc-issue" name="dc-issue" value="'.$title_btn.'" class="btn-next btn-issue" > ';
			}
		} elseif ($sw === 2) {
			if ($_target === false) {
				echo '<a href="implant-send-approve.php?ide='.$ide.'&pr='.base64_encode('DE').'" class="fancybox fancybox.ajax btn-issue">Solicitar aprobación del Intermediario</a> ';
			}
		} else {
			goto btnIssue;
			//echo '<input type="submit" id="dc-issue" name="dc-issue" value="'.$title_btn.'" class="btn-next btn-issue" > ';
		}
	} else {
		if($FC === TRUE && $sw === 2){
			if($_target === false) {
				btnApproval:
				echo '<a href="company-approval.php?ide='.$ide.'&pr='.base64_encode('DE').'" class="fancybox fancybox.ajax btn-issue">Solicitar aprobación de la Compañia</a> ';
			}
		} else{
			goto btnIssue;
			//echo '<input type="submit" id="dc-issue" name="dc-issue" value="'.$title_btn.'" class="btn-next btn-issue" > ';
		}
	}
	
	if($sw === 2) {
		$type_btn = 'button';
		if ($_target === true) {
			$type_btn = 'submit';
			echo '<input type="hidden" id="fp-ide" name="fp-ide" value="' . $ide . '" >
				<input type="hidden" id="fp-obs" name="fp-obs" value="" >';

			if (isset($_GET['idd'])) {
				echo '<input type="hidden" id="fp-idd" name="fp-idd" value="' . $_GET['idd'] . '" >';
			}

		}

		/*echo '<input type="' . $type_btn . '" id="dc-save" 
			name="dc-save" value="Guardar/Cerrar" class="btn-next btn-issue" >';*/
	}
?>
    </div>
    <div class="loading">
		<img src="img/loading-01.gif" width="35" height="35" />
	</div>

</form>
<script type="text/javascript">
$(document).ready(function(e) {
<?php
if ($_target === false) {
?>
	$("#dc-save").click(function(e){
		e.preventDefault();
		location.href = 'index.php';
	});
<?php
}
?>
	
	$("#dc-edit").click(function(e){
		e.preventDefault();
		location.href = 'de-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=$_GET['pr'];?>&ide=<?=$ide;?>&flag=<?=md5('i-edit');?>&cia=<?=$_GET['cia'].$target.$idd;?>';
	});
<?php
switch($sw){
	case 1:
?>
	$("#fde-issue").validateForm({
		action: '<?=$action;?>',
		tm: true
	});
	
<?php
		break;
	case 2:
		if ($_target === false) {
?>
	$("#fde-issue").validateForm({
		action: '<?=$action;?>',
		tm: true,
		issue: true
	});
<?php
		} else {
?>
	$("#fde-issue").validateForm({
		action: 'FAC-DE-response-record.php',
		method: 'GET',
		issue: true
	});
<?php
		}
?>

	$("#issue-title").append(' <?=$idNE;?>');
	
<?php
		break;
	case 3:
?>
	$(".date-birth").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
		yearRange: "c-100:c+100"
	});
	
	$(".date-birth").datepicker($.datepicker.regional[ "es" ]);
	
	$("#fde-issue").validateForm({
		action: '<?=$action;?>',
		tm: true
	});
	$("#issue-title").append(' <?=$idNE;?>');
<?php
		break;
}

if($FC === TRUE && ($sw === 2 || $sw === 3)){
?>
	$("#issue-title:last").after('<div class="fac-mess"><strong>Nota:</strong> Se deshabilitó el boton "Emitir" por las siguientes razones: <br><?=$mFC;?><br><br><strong>Por tanto:</strong><br>Debe solicitar aprobación a la Compañía de Seguros. </div>');
<?php
}
?>
});

function get_new_amount(pr, amount, amountR, currency, currencyR){
	var arr_limit = new Array();
	arr_limit[1] = 0;
	arr_limit[2] = 35000;
	arr_limit[3] = 5000;
	//arr_limit[4] = Number.MAX_VALUE;
	
	switch(pr){
		case 'VG':
			switch(currency){
				case 'BS':
					if(amount > arr_limit[1] && amount <= arr_limit[2])
						return true;
					else
						return false;
					break;
				case 'USD':
					if(amount > arr_limit[1] && amount <= arr_limit[3])
						return true;
					else
						return false;
					break;
			}
			break;
		case 'DE':
			switch(currency){
				case 'BS':
					if(amount > arr_limit[2])
						return true;
					else
						return false;
					break;
				case 'USD':
					if(amount > arr_limit[3])
						return true;
					else
						return false;
					break;
			}
			break;
	}
}
</script>
<?php
	} else {
		echo 'No existen Clientes';
	}
} else {
	echo "No existen Clientes.";
}
?>