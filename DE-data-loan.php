<script type="text/javascript">
$(document).ready(function(e) {
	$("#fde-loan").validateForm({
		action: 'DE-loan-record.php'
	});
	
	$('input').iCheck({
		checkboxClass: 'icheckbox_square-red',
		radioClass: 'iradio_square-red',
		increaseArea: '20%' // optional
	});

	$('#dl-coverage').change(function(e){
		var coverage = parseInt($(this).prop('value'));
		
		switch (coverage) {
		case 2:
			$('#ctn-amount').slideUp(function(){
				$('#dl-amount').prop('value', 0);
			});

			$('#dl-product option').attr("disabled", true);
			$('#dl-product option').each(function(index) {
				var option = $(this).text().toLowerCase();
				
				if (option === 'banca comunal') {
					$(this).prop('selected', true).prop('disabled', false);
				}
			});

			break;
		default:
			$('#dl-amount').prop('value', '');
			$('#ctn-amount').slideDown();

			$('#dl-product option').each(function(index) {
				var option = $(this).text().toLowerCase();
				
				if (option === 'banca comunal') {
					$(this).prop('disabled', true);
				} else {
					$(this).prop('disabled', false);
				}

				if ($(this).prop('value') === '') {
					$(this).prop('selected', true)
				}
			});
			break;
		}
	});
	
});
</script>
<?php
require_once('sibas-db.class.php');
$link = new SibasDB();
?>
<h3>Datos del Prestamo</h3>
<form id="fde-loan" name="fde-loan" action="" method="post" class="form-quote">
	<label>Tipo de Cobertura: <span>*</span></label>
	<div class="content-input">
		<select id="dl-coverage" name="dl-coverage" class="required fbin">
			<option value="">Seleccione...</option>
<?php
foreach ($link->coverage as $key => $value) {
	$coverage = explode('|', $value);
	if ($coverage[0] != 2) {
	}
		echo '<option value="'.$coverage[0].'">'.$coverage[1].'</option>';
}
?>
		</select>
	</div>
	<br>
	
	<div id="ctn-amount">
		<label>Monto Solicitado: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dl-amount" name="dl-amount" autocomplete="off" 
				value="" style="width:90px;" class="required real fbin">
		</div>
	</div>
	
	<label>Moneda: <span>*</span></label>
	<div class="content-input">
		<label class="check"><input type="radio" id="dl-currency-bs" name="dl-currency" value="BS" checked>&nbsp;&nbsp;BS</label>
		<label class="check"><input type="radio" id="dl-currency-usd" name="dl-currency" value="USD">&nbsp;&nbsp;USD</label><br>
	</div><br>
	
	<label>Plazo del Crédito: <span>*</span></label>
	<div class="content-input" style="width:auto;">
		<input type="text" id="dl-term" name="dl-term" autocomplete="off" value="" style="width:30px;" maxlength="4" class="required number fbin">
	</div>
	
	<div class="content-input">
		<select id="dl-type-term" name="dl-type-term" class="required fbin">
			<option value="">Seleccione...</option>
<?php
$arr_type_term = $link->typeTerm;
for ($i=0; $i < count($arr_type_term); $i++) {
	$type_term = explode('|', $arr_type_term[$i]);
	echo '<option value="'.$type_term[0].'">'.$type_term[1].'</option>';
}
?>
		</select>
	</div><br>
<?php

$sqlPr = 'select 
		spr.id_producto, spr.nombre
	from
		s_producto as spr
			inner join s_ef_compania as sec ON (sec.id_ef_cia = spr.id_ef_cia)
			inner join s_entidad_financiera as sef ON (sef.id_ef = sec.id_ef)
	where
		spr.activado = true
			and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
			and sef.activado = true
	limit 0 , 1
	; ';

$sqlPr .= 'select 
		spc.id_prcia, spc.nombre, spc.id_producto, spr.nombre as pr_nombre
	from
		s_producto_cia as spc
			inner join
		s_producto as spr ON (spr.id_producto = spc.id_producto)
			inner join 
		s_ef_compania as sec ON (sec.id_ef_cia = spr.id_ef_cia)
			inner join 
		s_entidad_financiera as sef ON (sef.id_ef = sec.id_ef)
	where
		spr.activado = true
			and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
			and sef.activado = true
	order by id_prcia asc
	;';
//echo $sqlPr;
$swPr = FALSE;
if($link->multi_query($sqlPr) === TRUE){
	do{
		if($swPr === TRUE) {
			echo ' <select id="dl-product" name="dl-product" class="required fbin">
				<option value="">Seleccione...</option>';
		}
		if($rsPr = $link->store_result()){
			if($rsPr->num_rows === 1){
				$rowPr = $rsPr->fetch_array(MYSQLI_ASSOC);
				if($rowPr['nombre'] === 'PRODUCTO'){
					echo ' <label>Producto: <span>*</span></label>';
					$swPr = TRUE;
				}
			}else{
				while($rowPr = $rsPr->fetch_array(MYSQLI_ASSOC)){
					if ($rowPr['id_prcia'] != 1) {
					}
						echo '<option value="'.$rowPr['id_prcia'].'">'.$rowPr['nombre'].'</option>';
				}
			}
		}
	}while($link->next_result());
	if($swPr === TRUE) {
		echo '</select>';
	}
}

if ($link->verifyModality($_SESSION['idEF'], 'DE') === true) {
?>
<script type="text/javascript">
$(document).ready(function(){
	$('#dl-product').change(function() {
		var product = $(this).find(':selected').text();
		$('#dl-modality option').not(":selected").attr("disabled", false);
		if ('VIVIENDA' === product) {
			$('#dl-modality option').each(function(index) {
				var option = $(this).prop('value');
				var value = option.split('|');
				if (value[1] === 'CD') {
					$(this).prop('selected', true);
				}
			});
			
			$('#dl-modality option').not(":selected").attr("disabled", true);
		} else {
			$('#dl-modality').prop('value', '');
		}
	});
});
</script>
	<br />
	<label>Modalidad: <span>*</span></label>
	<div class="content-input">
		<select id="dl-modality" name="dl-modality" class="required fbin">
			<option value="">Seleccione...</option>
<?php
	foreach ($link->modDE as $key => $value) {
		$modality = explode('|', $value);
		echo '<option value="' . base64_encode($modality[0]) . '|' . $modality[0] .'">' . $modality[1] . '</option>';
	}
?>
		</select>
	</div>
<?php
}
?>
	<input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
	<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
	<input type="hidden" id="pr" name="pr" value="<?=$_GET['pr'];?>">
<?php
	if (isset($_GET['idc'])) {
?>
	<input type="hidden" id="idc" name="idc" value="<?=$_GET['idc'];?>">
<?php
	}
?>
	
	<input type="submit" id="dl-loan" name="dl-loan" value="Continuar" class="btn-next">
	<div class="loading">
		<img src="img/loading-01.gif" width="35" height="35" />
	</div>
</form>