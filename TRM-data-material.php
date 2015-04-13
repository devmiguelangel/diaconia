<script type="text/javascript">
$(document).ready(function(e) {
	$("#fmt-material").validateForm({
		action: 'TRM-material-record.php'
	});
	
	$("input[type='text'].fbin, textarea.fbin").keyup(function(e){
		var arr_key = new Array(37, 39, 8, 16, 32, 18, 17, 46, 36, 35, 186);
		var _val = $(this).prop('value');
		
		if($.inArray(e.keyCode, arr_key) < 0 && $(this).hasClass('email') === false){
			$(this).prop('value',_val.toUpperCase());
		}
	});
	
	$("#dm-value-insured").keyup(function(e){
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
	
	set_tooltip("#dm-material");
});

</script>
<?php
require_once('sibas-db.class.php');
$link = new SibasDB();

$max_item = $max_amount = 0;
if (($rowTR = $link->get_max_amount_optional($_SESSION['idEF'], 'TRM')) !== FALSE) {
	$max_item = (int)$rowTR['max_item'];
	$max_amount = (int)$rowTR['max_monto'];
}

$swMt = FALSE;
$idc = '';

if (isset($_GET['idc'])) {
	$idc = $link->real_escape_string(trim($_GET['idc']));
}

$dm_material = '';
$dm_modality = '';
$dm_value_insured = '';

$title_btn = 'Agregar Material';

if(isset($_GET['idMt'])){
	$swMt = TRUE;
	$title_btn = 'Actualizar datos';
	
	$sqlUp = 'select 
	    strd.id_material as idMt,
	    strd.material as m_material,
	    strd.modalidad as m_modalidad,
	    strd.valor_asegurado as m_valor_asegurado
	from
	    s_trm_cot_detalle as strd
	        inner join
	    s_trm_cot_cabecera as strc ON (strc.id_cotizacion = strd.id_cotizacion)
	        inner join
	    s_entidad_financiera as sef ON (sef.id_ef = strc.id_ef)
	where
	    strc.id_cotizacion = "'.base64_decode($idc ).'"
	    	and strd.id_material = "'.base64_decode($_GET['idMt']).'"
	        and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
	        and sef.activado = true
	order by strd.id_material asc
	;';
	
	$rsUp = $link->query($sqlUp);
	
	if($rsUp->num_rows === 1){
		$rowUp = $rsUp->fetch_array(MYSQLI_ASSOC);
		$rsUp->free();
		
		$dm_material = $rowUp['m_material'];
		$dm_modality = $rowUp['m_modalidad'];
		$dm_value_insured = (int)$rowUp['m_valor_asegurado'];
	}
}
?>
<h3>Datos de la Materia Asgurada</h3>
<?php
$nMt = 0;
if($swMt === false && isset($_GET['idc'])){
	$sqlMt = 'select 
	    strd.id_material as idMt,
	    strd.material as m_material,
	    strd.valor_asegurado as m_valor_asegurado
	from
	    s_trm_cot_detalle as strd
	        inner join
	    s_trm_cot_cabecera as strc ON (strc.id_cotizacion = strd.id_cotizacion)
	        inner join
	    s_entidad_financiera as sef ON (sef.id_ef = strc.id_ef)
	where
	    strc.id_cotizacion = "'.base64_decode($idc ).'"
	        and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
	        and sef.activado = true
	order by strd.id_material asc
	;';
	//echo $sqlMt;
	$rsVh = $link->query($sqlMt,MYSQLI_STORE_RESULT);
	$nMt = $rsVh->num_rows;
	if($nMt < $max_item){
		
	}
}
?>

<form id="fmt-material" name="fmt-material" action="" method="post" class="form-quote form-customer">
<?php
if($swMt === FALSE){
	if($nMt > 0){
?>
		<table class="list-cl">
			<thead>
				<tr>
					<td style="width:4%;"></td>
					<td style="width:65%;">Materia Asegurada</td>
                    <td style="width:15%;">Valor Asegurado USD.</td>
					<td style="width:8%;"></td>
                    <td style="width:8%;"></td>
				</tr>
			</thead>
			<tbody>
<?php
		$cont = 1;
		while($rowMt = $rsVh->fetch_array(MYSQLI_ASSOC)){
			$bgFac = '';
			/*if((boolean)$rowMt['v_facultativo'] === TRUE)
				$bgFac = 'background:#FFE6D9;';*/
?>
				<tr style=" <?=$bgFac;?> ">
					<td style="font-weight:bold;"><?=$cont;?></td>
					<td style="text-align: justify;"><?=$rowMt['m_material'];?></td>
					<td><span class="value"><?=number_format($rowMt['m_valor_asegurado'], 2, '.', ',');?> USD.</span></td>
					<td><a href="trm-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=$_GET['pr'];?>&idc=<?=$_GET['idc'];?>&idMt=<?=base64_encode($rowMt['idMt']);?>" title="Editar Información"><img src="img/edit-inf-icon.png" width="40" height="40" alt="Editar Información" title="Editar Información"></a></td>
                    <td><a href="trm-remove-material.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=$_GET['pr'];?>&idc=<?=$_GET['idc'];?>&idMt=<?=base64_encode($rowMt['idMt']);?>" title="Eliminar Materia Asegurada" class="fancybox fancybox.ajax"><img src="img/delete-icon.png" width="40" height="40" alt="Eliminar Vehículo" title="Eliminar Materia Asegurada"></a></td>
				</tr>
<?php
			$cont += 1;
		}
		$rsVh->free();
?>
			</tbody>
		</table>
		
		<!--<div class="mess-cl">
        	<span class="bg-fac"></span> <strong>Nota:</strong> Año o Monto requieren aprobación de la Compañia de Seguros
		</div>-->
		<input type="button" id="dm-next" name="dm-next" value="Continuar" class="btn-next" >
		<hr>
<?php
	}
}
if($nMt < $max_item || $swMt === TRUE){
?>
	<div style="width: 80%; margin: 0 auto; text-align: center;">
		<label>Materia Asegurada: <span>*</span></label><br />
		<textarea id="dm-material" name="dm-material" class="required fbin" style="width: 500px; height: 150px;" title="Nota: Introduzca primero la cantidad y luego la descripción, ingrese solo un tipo de materia asegurada. Ejemplo: 10 tractores"><?=$dm_material;?></textarea><br>
		
	    <div class="au-mess" style="width: 300px; margin: 0 auto; text-align: center;">Detallar Número de Factura y/o Número de Avalúo</div><br />
	    
<?php
if ($link->verifyModality($_SESSION['idEF'], 'TRM') === true) {
?>
		<label>Modalidad: <span>*</span></label>
		<div class="content-input">
			<select id="dm-modality" name="dm-modality" class="required fbin">
				<option value="">Seleccione...</option>
<?php	
	foreach ($link->modTRM as $key => $value) {
		$modality = explode('|', $value);
		if ($dm_modality === $modality[0]) {
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
<?php
$display_value = 'display: none;';
if($dm_value_insured > $max_amount) {
	$display_value = 'display: block;';
}
?>
		<div class="content-input">
			<input type="text" id="dm-value-insured" name="dm-value-insured" autocomplete="off" value="<?=$dm_value_insured;?>" class="required number fbin">
		</div><br>
		<div id="mess-amount" class="au-mess" style=" <?=$display_value;?> ">Material cuyo valor excedan los <?=$max_amount;?> USD requieren aprobación de la Compañia de Seguros</div>
	</div>
	
    <input type="hidden" id="max-amount" name="max-amount" value="<?=$max_amount;?>">
    <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
	<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
<?php
if (isset($_GET['idc'])) {
?>
	<input type="hidden" id="dm-idc" name="dm-idc" value="<?=$idc;?>" >
<?php
}
?>
	<input type="hidden" id="pr" name="pr" value="<?=base64_encode('TRM|01');?>">
	<input type="hidden" id="dm-token" name="dm-token" value="<?=base64_encode('dm-OK');?>" >
    <input type="hidden" id="idef" name="idef" value="<?=$_SESSION['idEF'];?>" >
	
    <div style="text-align:center">
    	<input type="submit" id="dm-vehicle" name="dm-vehicle" value="<?=$title_btn;?>" class="btn-next btn-issue" >
<?php
	if (isset($_GET['idc'])) {
?>
<script type="text/javascript">
$(document).ready(function(){
	$("#dm-cancel").click(function(e){
		e.preventDefault();
		location.href = 'trm-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=$_GET['pr'];?>&idc=<?=$idc;?>';
	});
	
	$("#dm-next").click(function(e){
		e.preventDefault();
		location.href = 'trm-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=base64_encode('TRM|02');?>&idc=<?=$idc;?>';
	});
});
</script>
<?php
	}
	if($swMt === TRUE){
		echo '<input type="button" id="dm-cancel" name="dm-cancel" value="Cancelar" class="btn-next btn-issue" >
			<input type="hidden" id="dm-idMt" name="dm-idMt" value="'.$_GET['idMt'].'" >';
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