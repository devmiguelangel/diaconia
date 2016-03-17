<script type="text/javascript">
$(document).ready(function(e) {
	$("#ftr-property").validateForm({
		action: 'TRD-property-record.php'
	});
	
	$("input[type='text'].fbin, textarea.fbin").keyup(function(e){
		var arr_key = new Array(37, 39, 8, 16, 32, 18, 17, 46, 36, 35, 186);
		var _val = $(this).prop('value');
		
		if($.inArray(e.keyCode, arr_key) < 0 && $(this).hasClass('email') === false){
			$(this).prop('value',_val.toUpperCase());
		}
	});
	
	$("#dp-use").change(function(e){
		var use = $(this).prop('value');
		if(use === 'OTH'){
			$("#dp-use-other").removeClass('not-required').addClass('required');
			$("#dp-use-other").prop("readonly", false).focus();
		}else{
			$("#dp-use-other").removeClass('required').addClass('not-required');
			$("#dp-use-other").prop("readonly", true);
			$("#dp-use-other").prop("value", '');
		}
	});
	
<?php
if (isset($_GET['idc'])) {
?>
	$("#dp-cancel").click(function(e){
		e.preventDefault();
		location.href = 'trd-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=$_GET['pr'];?>&idc=<?=$_GET['idc'];?>';
	});
	
	$("#dp-next").click(function(e){
		e.preventDefault();
		location.href = 'trd-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=base64_encode('TRD|02');?>&idc=<?=$_GET['idc']?>';
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
if (($rowTR = $link->get_max_amount_optional($_SESSION['idEF'], 'TRD')) !== FALSE) {
	$max_item = (int)$rowTR['max_item'];
	$max_amount = (int)$rowTR['max_monto'];
}

$swPr = false;

$dp_type = $dp_use = $dp_use_other = $dp_state = $dp_depto = $dp_zone = 
$dp_locality = $dp_address = $dp_modality = $dp_value_insured = '';

$title_btn = 'Agregar Inmueble';

if(isset($_GET['idPr'])){
	$swPr = true;
	$title_btn = 'Actualizar datos';
	
	$sqlUp = 'select 
	    strd.id_inmueble as idPr,
	    strd.departamento as pr_departamento,
	    strd.zona as pr_zona,
	    strd.localidad as pr_localidad,
	    strd.direccion as pr_direccion,
	    strd.tipo_in as pr_tipo,
	    strd.uso as pr_uso,
	    strd.uso_otro as pr_uso_otro,
	    strd.estado as pr_estado,
	    strd.modalidad as pr_modalidad,
	    strd.valor_asegurado as pr_valor_asegurado
	from
	    s_trd_cot_detalle as strd
	        inner join
	    s_trd_cot_cabecera as strc ON (strc.id_cotizacion = strd.id_cotizacion)
	        inner join
	    s_entidad_financiera as sef ON (sef.id_ef = strc.id_ef)
	where
	    strc.id_cotizacion = "'.base64_decode($_GET['idc']).'"
	        and strd.id_inmueble = "'.base64_decode($_GET['idPr']).'"
	        and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
	        and sef.activado = true
	;';
	
	$rsUp = $link->query($sqlUp);
	
	if($rsUp->num_rows === 1){
		$rowUp = $rsUp->fetch_array(MYSQLI_ASSOC);
		$rsUp->free();
		
		$dp_type = $rowUp['pr_tipo'];
		$dp_use = $rowUp['pr_uso'];
		$dp_use_other = $rowUp['pr_uso_otro'];
		$dp_state = $rowUp['pr_estado'];
		$dp_depto = $rowUp['pr_departamento'];
		$dp_zone = $rowUp['pr_zona'];
		$dp_locality = $rowUp['pr_localidad'];
		$dp_address = $rowUp['pr_direccion'];
		$dp_modality = $rowUp['pr_modalidad'];
		$dp_value_insured = (int)$rowUp['pr_valor_asegurado'];
	}
}
?>
<h3>Datos del Inmueble</h3>
<?php
$nPr = 0;
if($swPr === false && isset($_GET['idc'])){
	$sqlPr = 'select 
	    strd.id_inmueble as idPr,
	    (case strd.tipo_in
	        when "HOME" then "Casa"
	        when "DEPT" then "Departamento"
	        when "BLDN" then "Edificio"
	        when "LOCL" then "Local"
	    end) as pr_tipo,
	    if(strd.uso != "OTH",
	        case strd.uso
	            when "DMC" then "Domiciliario"
	            when "COM" then "Comercial"
	        end,
	        strd.uso_otro) as pr_uso,
	    (case strd.estado
	        when "FINS" then "Terminado"
	        when "CONS" then "En construcción"
	        when "PRAR" then "En proceso de remodelación, ampliación o refacción"
	    end) as pr_estado,
	    sdep.departamento as pr_departamento,
	    strd.zona as pr_zona,
	    strd.localidad as pr_localidad,
	    strd.direccion as pr_direccion,
	    strd.valor_asegurado as pr_valor_asegurado
	from
	    s_trd_cot_detalle as strd
	        inner join
	    s_trd_cot_cabecera as strc ON (strc.id_cotizacion = strd.id_cotizacion)
	        inner join
	    s_departamento as sdep ON (sdep.id_depto = strd.departamento)
	        inner join
	    s_entidad_financiera as sef ON (sef.id_ef = strc.id_ef)
	where
	    strc.id_cotizacion = "'.base64_decode($_GET['idc']).'"
	        and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
	        and sef.activado = true
	order by strd.id_inmueble asc
	;';
	//echo $sqlPr;
	$rsPr = $link->query($sqlPr,MYSQLI_STORE_RESULT);
	$nPr = $rsPr->num_rows;
	if($nPr < $max_item){
		
	}
}
?>

<form id="ftr-property" name="ftr-property" action="" method="post" class="form-quote form-customer">
<?php
if($swPr === FALSE){
	if($nPr > 0){
?>
		<table class="list-cl">
			<thead>
				<tr>
					<td style="width:3%;"></td>
					<td style="width:8%;">Tipo</td>
					<td style="width:10%;">Uso</td>
					<td style="width:10%;">Estado</td>
					<td style="width:8%;">Departamento</td>
					<td style="width:15%;">Zona</td>
					<td style="width:10%;">Ciudad/Localidad</td>
					<td style="width:15%;">Dirección</td>
                    <td style="width:9%;">Valor Asegurado USD.</td>
					<td style="width:6%;"></td>
                    <td style="width:6%;"></td>
				</tr>
			</thead>
			<tbody>
<?php
		$cont = 1;
		while($rowPr = $rsPr->fetch_array(MYSQLI_ASSOC)){
			$bgFac = '';
			/*if((boolean)$rowPr['v_facultativo'] === TRUE)
				$bgFac = 'background:#FFE6D9;';*/
?>
				<tr style=" <?=$bgFac;?> ">
					<td style="font-weight:bold;"><?=$cont;?></td>
					<td><?=$rowPr['pr_tipo'];?></td>
					<td><?=$rowPr['pr_uso'];?></td>
					<td><?=$rowPr['pr_estado'];?></td>
					<td><?=$rowPr['pr_departamento'];?></td>
					<td><?=$rowPr['pr_zona'];?></td>
					<td><?=$rowPr['pr_localidad'];?></td>
					<td><?=$rowPr['pr_direccion'];?></td>
                    <td><span class="value"><?=number_format($rowPr['pr_valor_asegurado'], 2, '.', ',');?> USD.</span></td>
					<td><a href="trd-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=$_GET['pr'];?>&idc=<?=$_GET['idc'];?>&idPr=<?=base64_encode($rowPr['idPr']);?>" title="Editar Información"><img src="img/edit-inf-icon.png" width="40" height="40" alt="Editar Información" title="Editar Información"></a></td>
                    <td><a href="trd-remove-property.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=$_GET['pr'];?>&idc=<?=$_GET['idc'];?>&idPr=<?=base64_encode($rowPr['idPr']);?>" title="Eliminar Inmueble" class="fancybox fancybox.ajax"><img src="img/delete-icon.png" width="40" height="40" alt="Eliminar Inmueble" title="Eliminar Vehículo"></a></td>
				</tr>
<?php
			$cont += 1;
		}
		$rsPr->free();
?>
			</tbody>
		</table>
		
		<!--<div class="mess-cl">
        	<span class="bg-fac"></span> <strong>Nota:</strong> Año o Monto requieren aprobación de la Compañia de Seguros
		</div>-->
		<input type="button" id="dp-next" name="dp-next" value="Continuar" class="btn-next" >
		<hr>
<?php
	}
}
if($nPr < $max_item || $swPr === TRUE){
?>
	<div class="form-col">
		<label>Departamento: <span>*</span></label>
		<div class="content-input">
        	<select id="dp-depto" name="dp-depto" class="required fbin">
            	<option value="">Seleccione...</option>
<?php
if(($rsDp = $link->get_depto()) !== FALSE){
	while($rowDp = $rsDp->fetch_array(MYSQLI_ASSOC)){
		if ((boolean)$rowDp['tipo_dp'] === TRUE) {
			if($rowDp['id_depto'] === $dp_depto) {
				echo '<option value="'.base64_encode($rowDp['id_depto']).'" selected>'.$rowDp['departamento'].'</option>';
			} else {
				echo '<option value="'.base64_encode($rowDp['id_depto']).'">'.$rowDp['departamento'].'</option>';
			}
		}
	}
}
?>
			</select>
		</div><br>		
		
		<label>Zona: <span>*</span></label><br />
		<textarea id="dp-zone" name="dp-zone" class="required fbin"><?=$dp_zone;?></textarea><br>
		
		<label>Localidad: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dp-locality" name="dp-locality" autocomplete="off" value="<?=$dp_locality;?>" class="required text-2 fbin">
		</div><br>
		
		<label>Dirección: <span>*</span></label>
		<textarea id="dp-address" name="dp-address" class="required fbin"><?=$dp_address;?></textarea><br>
	</div><!--
	--><div class="form-col">
    	<label>Tipo: <span>*</span></label>
		<div class="content-input">
			<select id="dp-type" name="dp-type" class="required fbin">
            	<option value="">Seleccione...</option>
<?php
$arr_type = $link->typeProperty;
for ($i = 0; $i  < count($arr_type); $i ++) { 
	$type = explode('|', $arr_type[$i]);
	if ($type[0] === $dp_type) {
		echo '<option value="'.base64_encode($type[0]).'" selected>'.$type[1].'</option>';
	} else {
		echo '<option value="'.base64_encode($type[0]).'">'.$type[1].'</option>';
	}
}
?>
            </select>
		</div><br>
        
		<label>Uso: <span>*</span></label>
		<div class="content-input">
			<select id="dp-use" name="dp-use" class="required fbin">
            	<option value="">Seleccione...</option>
<?php
$arr_use = $link->useProperty;
for($i = 0; $i < count($arr_use); $i++){
	$use = explode('|', $arr_use[$i]);
	if($use[0] === $dp_use) {
		echo '<option value="'.base64_encode($use[0]).'" selected>'.$use[1].'</option>';
	} else {
		echo '<option value="'.base64_encode($use[0]).'">'.$use[1].'</option>';
	}
}

$readonly_use = 'readonly';
$required_use = 'not-required';
if ($dp_use === 'OTH') {
	echo '<option value="OTH" selected>OTRO</option>';
	$readonly_use = '';
	$required_use = 'required';
} else {
	echo '<option value="OTH">OTRO</option>';
}
?>
			</select>
		</div><br>
		
		<label></label>
		<div class="content-input">
			<input type="text" id="dp-use-other" name="dp-use-other" autocomplete="off" value="<?=$dp_use_other;?>" class="<?=$required_use;?> text-2 fbin" <?=$readonly_use;?> >
		</div><br>
		
		<label>Estado: <span>*</span></label>
		<div class="content-input">
			<select id="dp-state" name="dp-state" class="required fbin">
            	<option value="">Seleccione...</option>
<?php
$arr_state = $link->stateProperty;
for($i = 0; $i < count($arr_state); $i++){
	$state = explode('|', $arr_state[$i]);
	if($state[0] === $dp_state) {
		echo '<option value="'.base64_encode($state[0]).'" selected>'.$state[1].'</option>';
	} else {
		echo '<option value="'.base64_encode($state[0]).'">'.$state[1].'</option>';
	}
}
?>
			</select>
		</div><br>
<?php
if ($link->verifyModality($_SESSION['idEF'], 'TRD') === true) {
?>
		<label>Modalidad: <span>*</span></label>
		<div class="content-input">
			<select id="dp-modality" name="dp-modality" class="required fbin">
            	<option value="">Seleccione...</option>
<?php
	foreach ($link->modTRD as $key => $value) {
		$modality = explode('|', $value);
		if ($dp_modality === $modality[0]) {
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
		<label>Valor Asegurado (USD): </label>
		<div class="content-input">
			<input type="text" id="dp-value-insured" name="dp-value-insured" autocomplete="off" value="<?=$dp_value_insured;?>" class="required number fbin">
		</div><br>
	</div><br>
	
    <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
	<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
	<input type="hidden" id="pr" name="pr" value="<?=base64_encode('TRD|01');?>">
<?php
if (isset($_GET['idc'])) {
?>
	<input type="hidden" id="dp-idc" name="dp-idc" value="<?=$_GET['idc'];?>" >
<?php
}
?>
	<input type="hidden" id="dp-token" name="dp-token" value="<?=base64_encode('dp-OK');?>" >
    <input type="hidden" id="idef" name="idef" value="<?=$_SESSION['idEF'];?>" >
	
    <div style="text-align:center">
    	<input type="submit" id="dp-property" name="dp-property" value="<?=$title_btn;?>" class="btn-next btn-issue" >
<?php
	if($swPr === TRUE){
		echo '<input type="button" id="dp-cancel" name="dp-cancel" value="Cancelar" class="btn-next btn-issue" >
			<input type="hidden" id="dp-idPr" name="dp-idPr" value="'.$_GET['idPr'].'" >';
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