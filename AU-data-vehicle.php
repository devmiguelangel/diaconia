<script type="text/javascript">
$(document).ready(function(e) {
	$("#fau-vehicle").validateForm({
		action: 'AU-vehicle-record.php'
	});
	
	$("input[type='text'].fbin, textarea.fbin").keyup(function(e){
		var arr_key = new Array(37, 39, 8, 16, 32, 18, 17, 46, 36, 35, 186);
		var _val = $(this).prop('value');
		
		if($.inArray(e.keyCode, arr_key) < 0 && $(this).hasClass('email') === false){
			$(this).prop('value',_val.toUpperCase());
		}
	});
	
	$("#dv-make").change(function(e){
		var make = $(this).prop('value');
		var idef = $("#idef").prop('value');
		var model = '';
		
		$("#dv-model").slideUp();
		$.getJSON('au-get-model.php', {make: make, idef: idef}, function(data){
			if(data[0] === true){
				$("#dv-model").find('option').remove();
				for(var i = 1; i < data.length; i++){
					model = data[i].split('|');
					$("<option value='"+model[0]+"'>"+model[1]+"</option>").appendTo("#dv-model");
				}
				$("#dv-model-other").removeClass('required');
				$("#dv-model-other").addClass('not-required');
			}else
				alert('Error: ');
			$("#dv-model").slideDown();
		});
	});
	
	$("#dv-model").change(function(e){
		var model = $(this).prop('value');
        $("#dv-model-other").prop('value', '');
		if(model === 'OTHER'){
			$("#dv-model-other").removeClass('not-required')
                                .addClass('required')
                                .prop("readonly", false)
                                .focus()
                                .slideDown();
		}else{
			$("#dv-model-other").removeClass('required')
                                .addClass('not-required')
                                .prop("readonly", true)
                                .slideUp();
		}
	});
	
	$("#dv-year").change(function(e){
		var year = $(this).prop('value');
        $("#dv-year-other").prop('value', '');
		if(year === 'YEAR'){
			$("#dv-year-other").removeClass('not-required')
                                .addClass('required')
                                .prop("readonly", false)
                                .focus().slideDown();
            
			$("#mess-year").fadeIn();
		}else{
			$("#dv-year-other").removeClass('required')
                                .addClass('not-required')
                                .prop("readonly", true)
                                .slideUp();
			$("#mess-year").fadeOut();
		}
	});
	
	$("#dv-value-insured").keyup(function(e){
		var amount = parseInt($(this).prop('value'));
		var max_amount = parseInt($("#max-amount").prop('value'));
		if(isNaN(amount) === true) {
			$(this).prop('value', '');
		} else {
			if(amount > max_amount) {
				$("#mess-amount").fadeIn();
			} else {
				$("#mess-amount").fadeOut();
			}
		}
	});
<?php
if (isset($_GET['idc'])) {
?>
	$("#dv-cancel").click(function(e){
		e.preventDefault();
		location.href = 'au-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=$_GET['pr'];?>&idc=<?=$_GET['idc'];?>';
	});
	
	$("#dv-next").click(function(e){
		e.preventDefault();
		location.href = 'au-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=base64_encode('AU|02');?>&idc=<?=$_GET['idc']?>';
	});
<?php
}
?>
});
</script>
<?php
require_once('sibas-db.class.php');
$link = new SibasDB();

$max_item = $max_amount = 0;
if (($rowAU = $link->get_max_amount_optional($_SESSION['idEF'])) !== FALSE) {
	$max_item = (int)$rowAU['max_item'];
	$max_amount = (int)$rowAU['max_monto'];
}

$swVh = FALSE;

$dv_type_vehicle = '';
$dv_category = '';
$dv_make = '';
$dv_model = '';
$dv_model_other = '';
$dv_year = '';
$dv_year_other = '';
$dv_plate = '';
$dv_use = '';
$dv_traction = '';
$dv_zero_km = '';
$dv_modality = '';
$dv_value_insured = '';

$title_btn = 'Agregar Vehículo';

if(isset($_GET['idVh'])){
	$swVh = TRUE;
	$title_btn = 'Actualizar datos';
	
	$sqlUp = 'select 
		sad.id_vehiculo as idVh,
		sad.id_tipo_vh as v_tipo_vehiculo,
		sad.categoria as v_categoria,
		sad.id_marca as v_marca,
		sad.id_modelo as v_modelo,
		sad.km as v_km,
		sad.modalidad as v_modalidad,
		sad.anio as v_anio,
		sad.placa as v_placa,
		sad.uso as v_uso,
		sad.traccion as v_traccion,
		sad.valor_asegurado as v_valor_asegurado
	from
		s_au_cot_detalle as sad
			inner join
		s_au_cot_cabecera as sac ON (sac.id_cotizacion = sad.id_cotizacion)
			inner join
		s_entidad_financiera as sef ON (sef.id_ef = sac.id_ef)
	where
		sac.id_cotizacion = "'.base64_decode($_GET['idc']).'"
			and sad.id_vehiculo = "'.base64_decode($_GET['idVh']).'"
			and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
			and sef.activado = true
	order by sad.id_vehiculo asc
	;';
	
	$rsUp = $link->query($sqlUp);
	
	if($rsUp->num_rows === 1){
		$rowUp = $rsUp->fetch_array(MYSQLI_ASSOC);
		$rsUp->free();
		
		$dv_type_vehicle = $rowUp['v_tipo_vehiculo'];
		$dv_category = $rowUp['v_categoria'];
		$dv_make = $rowUp['v_marca'];
		$dv_model = $rowUp['v_modelo'];
		$dv_model_other = '';
		$dv_year = (int)$rowUp['v_anio'];
		$dv_year_other = '';
		$dv_plate = $rowUp['v_placa'];
		$dv_use = $rowUp['v_uso'];
		$dv_traction = $rowUp['v_traccion'];
		$dv_zero_km = $rowUp['v_km'];
		$dv_modality = $rowUp['v_modalidad'];
		$dv_value_insured = (int)$rowUp['v_valor_asegurado'];
	}
}
?>
<h3>Datos del Vehículo</h3>
<?php
$nVh = 0;
if($swVh === false && isset($_GET['idc'])){
	$sqlVh = 'select 
		sad.id_vehiculo as idVh,
		stv.vehiculo as v_tipo_vehiculo,
		sma.marca as v_marca,
		smo.modelo as v_modelo,
		sad.km as v_km,
		sad.anio as v_anio,
		sad.placa as v_placa,
		(case sad.categoria
			when "RAC" then "Rent a Car"
			when "OTH" then "Otros"
		end) as v_categoria,
		(case sad.traccion
			when "4X2" then "4x2"
			when "4X4" then "4x4"
			when "VHP" then "Vehículo Pesado"
		end) as v_traccion,
		sad.valor_asegurado as v_valor_asegurado,
		sad.facultativo as v_facultativo
	from
		s_au_cot_detalle as sad
			inner join
		s_au_cot_cabecera as sac ON (sac.id_cotizacion = sad.id_cotizacion)
			inner join
	    s_entidad_financiera as sef ON (sef.id_ef = sac.id_ef)
			inner join
		s_au_tipo_vehiculo as stv ON (stv.id_tipo_vh = sad.id_tipo_vh)
			inner join
		s_au_marca as sma ON (sma.id_marca = sad.id_marca)
			inner join
		s_au_modelo as smo ON (smo.id_modelo = sad.id_modelo)
	where
		sac.id_cotizacion = "'.base64_decode($_GET['idc']).'"
			and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
			and sef.activado = true
	order by sad.id_vehiculo asc
	;';
	//echo $sqlVh;
	$rsVh = $link->query($sqlVh,MYSQLI_STORE_RESULT);
	$nVh = $rsVh->num_rows;
	if($nVh < $max_item){
		
	}
}
?>

<form id="fau-vehicle" name="fau-vehicle" action="" method="post" class="form-quote form-customer">
<?php
if($swVh === false){
	if($nVh > 0){
?>
		<table class="list-cl">
			<thead>
				<tr>
					<td style="width:3%;"></td>
					<td style="width:13%;">Vehículo</td>
					<td style="width:14%;">Marca</td>
					<td style="width:14%;">Modelo</td>
					<td style="width:5%;">Cero Km.</td>
					<td style="width:5%;">Año</td>
					<td style="width:15%;">Placa</td>
					<td style="width:5%;">Categoria</td>
                    <td style="width:5%;">Tracción</td>
                    <td style="width:9%;">Valor Asegurado USD.</td>
					<td style="width:6%;"></td>
                    <td style="width:6%;"></td>
				</tr>
			</thead>
			<tbody>
<?php
		$cont = 1;
		while($rowVh = $rsVh->fetch_array(MYSQLI_ASSOC)){
			$bgFac = '';
			if((boolean)$rowVh['v_facultativo'] === TRUE)
				$bgFac = 'background:#FFE6D9;';
?>
				<tr style=" <?=$bgFac;?> ">
					<td style="font-weight:bold;"><?=$cont;?></td>
					<td><?=$rowVh['v_tipo_vehiculo'];?></td>
					<td><?=$rowVh['v_marca'];?></td>
					<td><?=$rowVh['v_modelo'];?></td>
					<td><?=$rowVh['v_km'];?></td>
					<td><?=$rowVh['v_anio'];?></td>
					<td><?=$rowVh['v_placa'];?></td>
					<td><?=$rowVh['v_categoria'];?></td>
                    <td><?=$rowVh['v_traccion'];?></td>
                    <td><span class="value"><?=number_format($rowVh['v_valor_asegurado'], 2, '.', ',');?> USD.</span></td>
					<td><a href="au-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=$_GET['pr'];?>&idc=<?=$_GET['idc'];?>&idVh=<?=base64_encode($rowVh['idVh']);?>" title="Editar Información"><img src="img/edit-inf-icon.png" width="40" height="40" alt="Editar Información" title="Editar Información"></a></td>
                    <td><a href="au-remove-vehicle.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=$_GET['pr'];?>&idc=<?=$_GET['idc'];?>&idVh=<?=base64_encode($rowVh['idVh']);?>" title="Eliminar Vehículo" class="fancybox fancybox.ajax"><img src="img/delete-icon.png" width="40" height="40" alt="Eliminar Vehículo" title="Eliminar Vehículo"></a></td>
				</tr>
<?php
			$cont += 1;
		}
		$rsVh->free();
?>
			</tbody>
		</table>
		
		<div class="mess-cl">
        	<span class="bg-fac"></span> <strong>Nota:</strong> Año o Monto requieren aprobación de la Compañia de Seguros
		</div>
		<input type="button" id="dv-next" name="dv-next" value="Continuar" class="btn-next" >
		<hr>
<?php
	}
}
if($nVh < $max_item || $swVh === TRUE){
?>
	<div class="form-col">
		<label>Tipo de Vehículo: <span>*</span></label>
		<div class="content-input">
        	<select id="dv-type-vehicle" name="dv-type-vehicle" class="required fbin">
            	<option value="">Seleccione...</option>
<?php
if(($rsTv = $link->get_type_vehicle($_SESSION['idEF'])) !== FALSE){
	while($rowTv = $rsTv->fetch_array(MYSQLI_ASSOC)){
		if($rowTv['id_vh'] === $dv_type_vehicle) {
			echo '<option value="'.base64_encode($rowTv['id_vh']).'" selected>'.$rowTv['vehiculo'].'</option>';
		} else {
			echo '<option value="'.base64_encode($rowTv['id_vh']).'">'.$rowTv['vehiculo'].'</option>';
		}
	}
}
?>
			</select>
		</div><br>		
		
		<label>Categoria: <span>*</span></label>
		<div class="content-input">
			<select id="dv-category" name="dv-category" class="required fbin">
            	<option value="">Seleccione...</option>
<?php
$arr_category = $link->category;
for($i = 0; $i < count($arr_category); $i++){
	$category = explode('|', $arr_category[$i]);
	if($category[0] === $dv_category) {
		echo '<option value="'.base64_encode($category[0]).'" selected>'.$category[1].'</option>';
	} else {
		echo '<option value="'.base64_encode($category[0]).'">'.$category[1].'</option>';
	}
}
?>
			</select>
		</div><br>
		
		<label>Marca: <span>*</span></label>
		<div class="content-input">
        	<select id="dv-make" name="dv-make" class="required fbin">
<?php
if(($rsMa = $link->get_make($_SESSION['idEF'])) !== FALSE){
	while($rowMa = $rsMa->fetch_array(MYSQLI_ASSOC)){
		if($rowMa['id_marca'] === $dv_make) {
			echo '<option value="'.base64_encode($rowMa['id_marca']).'" selected>'.$rowMa['marca'].'</option>';
		} else {
			echo '<option value="'.base64_encode($rowMa['id_marca']).'">'.$rowMa['marca'].'</option>';
		}
	}
}
?>
			</select>
		</div><br>
		
		<label>Modelo: <span>*</span></label>
		<div class="content-input">
			<select id="dv-model" name="dv-model" class="required fbin">
<?php
if (($rsMarca = $link->get_make($_SESSION['idEF'], TRUE)) !== FALSE) {
	$rowMarca = $rsMarca->fetch_array(MYSQLI_ASSOC);
	$rsMarca->free();
	$marca = $rowMarca['id_marca'];
	if($swVh === TRUE) {
		$marca = $dv_make;
	}
	
	if(($rsMo = $link->get_model($_SESSION['idEF'], $marca)) !== FALSE){
		while($rowMo = $rsMo->fetch_array(MYSQLI_ASSOC)){
			if($rowMo['id_modelo'] === $dv_model) {
				echo '<option value="'.base64_encode($rowMo['id_modelo']).'" selected>'.$rowMo['modelo'].'</option>';
			} else {
				echo '<option value="'.base64_encode($rowMo['id_modelo']).'">'.$rowMo['modelo'].'</option>';
			}
		}
	}
	echo '<option value="OTHER">OTRO</option>';
}
?>
			</select>
		</div><br>
        
        <label></label>
		<div class="content-input">
			<input type="text" id="dv-model-other" name="dv-model-other" autocomplete="off" value="" class="not-required text-2 fbin" readonly style="display:none;">
		</div><br>

		<label>Año: <span>*</span></label>
		<div class="content-input">
			<select id="dv-year" name="dv-year" class="required fbin">
<?php
$display_year = 'display: none;';
if(($rowYear = $link->get_year_cot($_SESSION['idEF'])) !== FALSE){
	$year = (int)$rowYear['anio'];
	$year_max = (int)$rowYear['anio_max'];
	$year_min = (int)$rowYear['anio_min'];
	
	if($swVh === FALSE) {
		$dv_year = $year_max;
	}
		
	if($dv_year < $year_min){
		$dv_year_other = $dv_year;
		$dv_year = 'YEAR';
		$display_year = 'display: block;';
	}
		
	for($i = $year_max; $i >= $year_min; $i--){
		if($i === $dv_year) {
			echo '<option value="'.base64_encode($i).'" selected>'.$i.'</option>';
		} else {
			echo '<option value="'.base64_encode($i).'">'.$i.'</option>';
		}
	}
	
	if($dv_year === 'YEAR') {
		echo '<option value="YEAR" selected>(-) '.$year_min.'</option>';
	} else {
		echo '<option value="YEAR">(-) '.$year_min.'</option>';
	}
}
?>
			</select>
		</div><br>
        
        <label></label>
		<div class="content-input">
			<input type="text" id="dv-year-other" name="dv-year-other" autocomplete="off" value="<?=$dv_year_other;?>" maxlength="4" class="not-required number fbin" readonly style="display:none;">
		</div><br>
        <div id="mess-year" class="au-mess" style=" <?=$display_year;?> ">Vehiculos con antigüedad mayor a <?=$year;?> años requieren aprobación de la Compañia de Seguros</div>
	</div><!--
	--><div class="form-col">
    	<label>Placa: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dv-plate" name="dv-plate" autocomplete="off" value="<?=$dv_plate;?>" class="required text-2 fbin">
		</div><br>
        <div class="au-mess">En caso de que la placa este en tramite esciba ET</div>
        
		<label>Uso de Vehículo: <span>*</span></label>
		<div class="content-input">
			<select id="dv-use" name="dv-use" class="required fbin">
            	<option value="">Seleccione...</option>
<?php
$arr_use = $link->use;
for($i = 0; $i < count($arr_use); $i++){
	$use = explode('|', $arr_use[$i]);
	if($use[0] === $dv_use) {
		echo '<option value="'.base64_encode($use[0]).'" selected>'.$use[1].'</option>';
	} else {
		echo '<option value="'.base64_encode($use[0]).'">'.$use[1].'</option>';
	}
}
?>
			</select>
		</div><br>
		
		<label>Tracción: <span>*</span></label>
		<div class="content-input">
			<select id="dv-traction" name="dv-traction" class="required fbin">
            	<option value="">Seleccione...</option>
<?php
$arr_traction = $link->traction;
for($i = 0; $i < count($arr_traction); $i++){
	$traction = explode('|', $arr_traction[$i]);
	if($traction[0] === $dv_traction) {
		echo '<option value="'.base64_encode($traction[0]).'" selected>'.$traction[1].'</option>';
	} else {
		echo '<option value="'.base64_encode($traction[0]).'">'.$traction[1].'</option>';
	}
}
?>
			</select>
		</div><br>
		
		<label>Cero Kilómetros: <span>*</span></label>
		<div class="content-input">
			<select id="dv-zero-km" name="dv-zero-km" class="required fbin">
            	<option value="">Seleccione...</option>
<?php
$arr_zero_km = array(0 => 'SI|SI', 1 => 'NO|NO');
for($i = 0; $i < count($arr_zero_km); $i++){
	$zero_km = explode('|', $arr_zero_km[$i]);
	if($zero_km[0] === $dv_zero_km) {
		echo '<option value="'.base64_encode($zero_km[0]).'" selected>'.$zero_km[1].'</option>';
	} else {
		echo '<option value="'.base64_encode($zero_km[0]).'">'.$zero_km[1].'</option>';
	}
}
?>
			</select>
		</div><br>

<?php
if ($link->verifyModality($_SESSION['idEF'], 'AU') === true) {
?>
		<label>Modalidad: <span>*</span></label>
		<div class="content-input" style="width: auto;">
			<select id="dv-modality" name="dv-modality" class="required fbin">
				<option value="">Seleccione...</option>
<?php
	foreach ($link->modAU as $key => $value) {
		$modality = explode('|', $value);
		if ($dv_modality === $modality[0]) {
			echo '<option value="'.base64_encode($modality[0]).'" selected>'.$modality[1].'</option>';
		} else {
			echo '<option value="'.base64_encode($modality[0]).'">'.$modality[1].'</option>';
		}
		
	}
	
?>
			</select>
		</div><br />
<?php
}
?>
		<label>Valor Asegurado (USD):<span>*</span></label>
<?php
$display_value = 'display: none;';
if($dv_value_insured > $max_amount) {
	$display_value = 'display: block;';
}
?>
		<div class="content-input">
			<input type="text" id="dv-value-insured" name="dv-value-insured" autocomplete="off" value="<?=$dv_value_insured;?>" class="required number fbin">
		</div><br>
		<div id="mess-amount" class="au-mess" style=" <?=$display_value;?> ">Vehículos cuyo valor excedan los <?=$max_amount;?> USD requieren aprobación de la Compañia de Seguros</div>
		
	</div><br>
    <input type="hidden" id="max-amount" name="max-amount" value="<?=$max_amount;?>">
    <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
	<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
	<input type="hidden" id="pr" name="pr" value="<?=base64_encode('AU|01');?>">
<?php
if (isset($_GET['idc'])) {
?>
	<input type="hidden" id="dv-idc" name="dv-idc" value="<?=$_GET['idc'];?>" >
<?php
}
?>
	<input type="hidden" id="dv-token" name="dv-token" value="<?=base64_encode('dv-OK');?>" >
    <input type="hidden" id="idef" name="idef" value="<?=$_SESSION['idEF'];?>" >
	
    <div style="text-align:center">
    	<input type="submit" id="dv-vehicle" name="dv-vehicle" value="<?=$title_btn;?>" class="btn-next btn-issue" >
<?php
	if($swVh === TRUE){
		echo '<input type="button" id="dv-cancel" name="dv-cancel" value="Cancelar" class="btn-next btn-issue" >
			<input type="hidden" id="dv-idVh" name="dv-idVh" value="'.$_GET['idVh'].'" >';
	}
?>
    </div>
<?php
}
?>	
	<div class="loading">
		<img src="img/loading-01.gif" width="35" height="35" />
	</div>
</form>