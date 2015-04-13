<?php
require_once('sibas-db.class.php');
$link = new SibasDB();
$ide = 0;
$idc = 0;
if(isset($_GET['ide'])) {
	$ide = $link->real_escape_string(base64_decode($_GET['ide']));
} elseif (isset($_GET['idc'])) {
	$idc = $link->real_escape_string(base64_decode($_GET['idc']));
}

$max_item = 0;
if (($rowTR = $link->get_max_amount_optional($_SESSION['idEF'], 'TRM')) !== FALSE) {
	$max_item = (int)$rowTR['max_item'];
}

$flag = $_GET['flag'];
$action = '';

$read_new = '';
$read_save = '';
$read_edit = '';
$title = '';
$title_btn = '';

$sw = 0;
$swMo = false;

switch($flag){
	case md5('i-new'):
		$action = 'TRM-issue-record.php';
		$title = 'Emisión de Póliza de Seguro de Todo Riesgo Equipo Móvil';
		$title_btn = 'Guardar';
		
		$read_new = 'readonly';
		$sw = 1;
		break;
	case md5('i-read'):
		$action = 'TRM-policy-record.php';
		$title = 'Póliza No. TRM - ';
		$title_btn = 'Emitir';
		$read_new = 'disabled';
		$read_save = 'disabled';
		$sw = 2;
		break;
	case md5('i-edit'):
		$action = 'TRM-issue-record.php';
		$title = 'Póliza No. TRM - ';
		$title_btn = 'Actualizar Datos';
		$read_edit = 'readonly';
		$sw = 3;
		break;
}

$sql = '';
switch($sw){
	case 1:
		$sql = 'select 
	    strc.id_cotizacion as idc,
	    sef.id_ef,
        sef.nombre as ef_nombre,
	    strc.certificado_provisional as cp,
	    strc.garantia as c_garantia,
	    strc.ini_vigencia as c_ini_vigencia,
	    strc.fin_vigencia as c_fin_vigencia,
	    sfp.id_forma_pago,
	    sfp.codigo as c_forma_pago,
	    strc.plazo as c_plazo,
	    strc.tipo_plazo as c_tipo_plazo,
	    strc.prima_total as c_prima_total,
	    scl.tipo as cl_tipo_cliente,
	    scl.id_cliente as idcl,
	    scl.ci as cl_dni,
	    scl.extension as cl_extension,
	    (case scl.tipo
	        when 1 then scl.razon_social
	        when
	            0
	        then
	            concat(scl.nombre,
	                    " ",
	                    scl.paterno,
	                    " ",
	                    scl.materno)
	    end) as facturacion_nombre,
	    scl.razon_social as cl_razon_social,
	    scl.nombre as cl_nombre,
	    scl.paterno as cl_paterno,
	    scl.materno as cl_materno,
	    scl.ap_casada as cl_ap_casada,
	    scl.fecha_nacimiento as cl_fecha_nacimiento,
	    scl.complemento as cl_complemento,
	    scl.genero as cl_genero,
	    scl.telefono_domicilio as cl_tel_domicilio,
	    scl.telefono_celular as cl_tel_celular,
	    scl.telefono_oficina as cl_tel_oficina,
	    scl.email as cl_email,
	    "" as cl_lugar_residencia,
	    "" as cl_localidad,
	    "" as cl_ocupacion,
	    "" as cl_desc_ocupacion,
	    "" as cl_adjunto,
	    strd.id_material as idmt,
	    strd.material as m_material,
	    strd.modalidad as m_modalidad,
	    strd.valor_asegurado as m_valor_asegurado,
	    "" as mt_adjunto
	from
	    s_trm_cot_cabecera as strc
	        inner join
	    s_trm_cot_cliente as scl ON (scl.id_cliente = strc.id_cliente)
	        inner join
	    s_trm_cot_detalle as strd ON (strd.id_cotizacion = strc.id_cotizacion)
	        inner join
	    s_entidad_financiera as sef ON (sef.id_ef = strc.id_ef)
	        inner join
	    s_forma_pago as sfp ON (sfp.id_forma_pago = strc.id_forma_pago)
	where
	    strc.id_cotizacion = "'.$idc.'"
	        and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
	        and sef.activado = true
	order by strd.id_material asc
	;';
		break;
}

if($sw !== 1){
	$sql = 'select 
	    stre.id_emision as ide,
	    stre.id_cotizacion as idc,
        sef.id_ef,
        sef.nombre as ef_nombre,
	    stre.certificado_provisional as cp,
	    stre.no_emision,
	    stre.ini_vigencia as c_ini_vigencia,
	    stre.fin_vigencia as c_fin_vigencia,
	    sfp.id_forma_pago,
	    sfp.codigo as c_forma_pago,
	    stre.plazo as c_plazo,
	    stre.tipo_plazo as c_tipo_plazo,
	    stre.prima_total as c_prima_total,
	    stre.no_operacion as c_no_operacion,
	    stre.id_poliza as c_poliza,
	    stre.facultativo as c_facultativo,
	    stre.motivo_facultativo as c_motivo_facultativo,
	    scl.tipo as cl_tipo_cliente,
	    scl.id_cliente as idcl,
	    scl.ci as cl_dni,
	    scl.extension as cl_extension,
	    stre.factura_nombre as facturacion_nombre,
	    scl.razon_social as cl_razon_social,
	    scl.nombre as cl_nombre,
	    scl.paterno as cl_paterno,
	    scl.materno as cl_materno,
	    scl.ap_casada as cl_ap_casada,
	    scl.fecha_nacimiento as cl_fecha_nacimiento,
	    scl.complemento as cl_complemento,
	    scl.genero as cl_genero,
	    scl.telefono_domicilio as cl_tel_domicilio,
	    scl.telefono_celular as cl_tel_celular,
	    scl.telefono_oficina as cl_tel_oficina,
	    scl.email as cl_email,
	    scl.avenida as cl_avc,
	    scl.direccion as cl_direccion_domicilio,
	    scl.no_domicilio as cl_no_domicilio,
	    scl.lugar_residencia as cl_lugar_residencia,
	    scl.localidad as cl_localidad,
	    scl.id_ocupacion as cl_ocupacion,
	    scl.desc_ocupacion as cl_desc_ocupacion,
	    scl.direccion_laboral as cl_direccion_laboral,
	    scl.ci_archivo as cl_adjunto,
	    strd.id_material as idmt,
	    strd.material as m_material,
	    strd.modalidad as m_modalidad,
	    strd.valor_asegurado as m_valor_asegurado,
	    strd.tasa as m_tasa,
    	strd.prima as m_prima,
	    strd.mt_archivo as mt_adjunto
	from
	    s_trm_em_cabecera as stre
	        inner join
	    s_cliente as scl ON (scl.id_cliente = stre.id_cliente)
	        inner join
	    s_trm_em_detalle as strd ON (strd.id_emision = stre.id_emision)
	        inner join
	    s_entidad_financiera as sef ON (sef.id_ef = stre.id_ef)
	        inner join
	    s_forma_pago as sfp ON (sfp.id_forma_pago = stre.id_forma_pago)
	where
	    stre.id_emision = "'.$ide.'"
	        and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
	        and sef.activado = true
	order by strd.id_material asc
	;';
}
//echo $sql;
$rs = $link->query($sql,MYSQLI_STORE_RESULT);
$nMt = $rs->num_rows;
if($nMt > 0 && $nMt <= $max_item){
	if($sw !== 1){
		if($rs->data_seek(0) === TRUE){
			$row = $rs->fetch_array(MYSQLI_ASSOC);
			$idc = $row['idc'];
		}
	}
?>
<script type="text/javascript">
$(document).ready(function(e) {
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
<h3 id="issue-title"><?=$title;?></h3>
<a href="certificate-detail.php?idc=<?=base64_encode($idc);?>&cia=<?=$_GET['cia'];?>&type=<?=base64_encode('PRINT');?>&pr=<?=base64_encode('TRM');?>" class="fancybox fancybox.ajax btn-see-slip">Ver Slip Cotización</a>
<form id="fde-issue" name="fde-issue" action="" method="post" class="form-quote form-customer" enctype="multipart/form-data">
<?php
$cont = 0;

$TASA = 0;
$PRIMA = 0;
$YEAR_FINAL = 0;

$cr_amount = 0;
$cr_term = 0;
$cr_type_term = $cr_method_payment = $cr_opp = $cr_policy = '';

$display_nat = $display_jur = 'display: block;';
$read_nat = $read_jur = 'not-required';

$idNE = '';

$FC = FALSE;

if($rs->data_seek(0) === TRUE){
	$row = $rs->fetch_array(MYSQLI_ASSOC);
	$cr_term = $row['c_plazo'];
	$cr_type_term = $row['c_tipo_plazo'];
	
	$cl_avc = $cl_dir = $cl_dir_office = $cl_nd = '';
	
	$cl_type_client = (int)$row['cl_tipo_cliente'];
	
	if($cl_type_client === 0) { 
		$display_jur = 'display: none;';
		$read_nat = 'required';
	} elseif($cl_type_client === 1) { 
		$display_nat = 'display: none;'; 
		$read_jur = 'required';
	}
	
	if($sw !== 1){
		$idNE = $row['no_emision'];
		
		$cr_opp = $row['c_no_operacion'];
		$cr_policy = $row['c_poliza'];
		$mFC = $row['c_motivo_facultativo'];
		
		$cl_avc = $row['cl_avc'];
		$cl_dir = $row['cl_direccion_domicilio'];
		$cl_nd = $row['cl_no_domicilio'];
		
		if($cl_type_client === 0) { $cl_dir_office = $row['cl_direccion_laboral']; }
		
		if($sw === 2 || $sw === 3){
			if((boolean)$row['c_facultativo'] === TRUE) {
				$FC = TRUE;
			}
		}
	}else{
		
	}
	
	$YEAR_FINAL = $link->get_year_final($row['c_plazo'], $row['c_tipo_plazo']);
?>
	<h4>Datos del Arrendatario</h4>
<?php
if($sw > 1){
	echo '<input type="hidden" id="dc-idcl" name="dc-idcl" value="'.base64_encode($row['idcl']).'" class="required">';
}
?>
    <input type="hidden" id="dc-type-client" name="dc-type-client" value="<?=base64_encode($cl_type_client);?>">
    
    <!-- NATURAL -->
    <div id="form-person" style=" <?=$display_nat;?> ">
    	<div class="form-col">
            <label>Apellido Paterno: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-ln-patern" name="dc-ln-patern" autocomplete="off" value="<?=$row['cl_paterno'];?>" class="<?=$read_nat;?> text fbin field-person" <?=$read_new;?>>
            </div><br>
            
            <label>Apellido Materno: </label>
            <div class="content-input">
                <input type="text" id="dc-ln-matern" name="dc-ln-matern" autocomplete="off" value="<?=$row['cl_materno'];?>" class="text fbin" <?=$read_new;?>>
            </div><br>
            
            <label>Nombres: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-name" name="dc-name" autocomplete="off" value="<?=$row['cl_nombre'];?>" class="<?=$read_nat;?> text fbin field-person" <?=$read_new;?>>
            </div><br>
            
            <label>Apellido de Casada: </label>
            <div class="content-input">
                <input type="text" id="dc-ln-married" name="dc-ln-married" autocomplete="off" value="<?=$row['cl_ap_casada'];?>" class="not-required text fbin" <?=$read_new;?>>
            </div><br>
            
            <label>Documento de Identidad: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-doc-id" name="dc-doc-id" autocomplete="off" value="<?=$row['cl_dni'];?>" class="<?=$read_nat;?> dni fbin field-person" <?=$read_new.$read_edit;?>>
            </div><br>
            
            <label>Complemento: </label>
            <div class="content-input">
                <input type="text" id="dc-comp" name="dc-comp" autocomplete="off" value="<?=$row['cl_complemento'];?>" class="not-required dni fbin" style="width:60px;" <?=$read_new;?>>
            </div><br>
            
            <label>Extensión: <span>*</span></label>
            <div class="content-input">
                <select id="dc-ext" name="dc-ext" class="<?=$read_nat;?> fbin field-person <?=$read_new.$read_edit;?>" <?=$read_new;?> >
                    <option value="">Seleccione...</option>
<?php
$rsDep = null;
if (($rsDep = $link->get_depto()) === FALSE) {
	$rsDep = null;
}

if ($rsDep->data_seek(0) === TRUE) {
	while($rowDep = $rsDep->fetch_array(MYSQLI_ASSOC)){
		if((boolean)$rowDep['tipo_ci'] === TRUE){
			if($rowDep['id_depto'] === $row['cl_extension']) {
				echo '<option value="'.$rowDep['id_depto'].'" selected>'.$rowDep['departamento'].'</option>';
			} else {
				echo '<option value="'.$rowDep['id_depto'].'">'.$rowDep['departamento'].'</option>';
			}
		}
	}
}
?>
                </select>
            </div><br>
            <label>Género: <span>*</span></label>
            <div class="content-input">
                <select id="dc-gender" name="dc-gender" class="<?=$read_nat;?> fbin field-person <?=$read_new;?>" <?=$read_new;?>>
                    <option value="">Seleccione...</option>
<?php
$arr_gender = $link->gender;
for($i = 0; $i < count($arr_gender); $i++){
	$gender = explode('|',$arr_gender[$i]);
	if($gender[0] === $row['cl_genero']) {
		echo '<option value="'.$gender[0].'" selected>'.$gender[1].'</option>';
	} else {
		echo '<option value="'.$gender[0].'">'.$gender[1].'</option>';
	}
}
?>
                </select>
            </div><br>
            
            <label>Fecha de Nacimiento: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-date-birth" name="dc-date-birth" autocomplete="off" value="<?=$row['cl_fecha_nacimiento'];?>" class="<?=$read_nat;?> fbin date field-person" readonly style="cursor:pointer;" <?=$read_new;?>>
            </div><br>
            
            <label>Lugar de Residencia: <span>*</span></label>
            <div class="content-input">
                <select id="dc-place-res" name="dc-place-res" class="<?=$read_nat;?> fbin " <?=$read_save;?>>
                    <option value="">Seleccione...</option>
<?php
if ($rsDep->data_seek(0) === TRUE) {
	while($rowDep = $rsDep->fetch_array(MYSQLI_ASSOC)){
		if((boolean)$rowDep['tipo_dp'] === TRUE){
			if($rowDep['id_depto'] === $row['cl_lugar_residencia']) {
				echo '<option value="'.$rowDep['id_depto'].'" selected>'.$rowDep['departamento'].'</option>';
			} else {
				echo '<option value="'.$rowDep['id_depto'].'">'.$rowDep['departamento'].'</option>';
			}
		}
	}
}
?>
                </select>
            </div><br>
            <label>Localidad: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-locality" name="dc-locality" autocomplete="off" value="<?=$row['cl_localidad'];?>" class="<?=$read_nat;?> text-2 fbin" <?=$read_save;?>>
            </div><br>
            
            <label>Teléfono de domicilio: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-phone-1" name="dc-phone-1" autocomplete="off" value="<?=$row['cl_tel_domicilio'];?>" class="<?=$read_nat;?> phone fbin" <?=$read_new;?>>
            </div><br>
            
            <label>Teléfono celular: </label>
            <div class="content-input">
                <input type="text" id="dc-phone-2" name="dc-phone-2" autocomplete="off" value="<?=$row['cl_tel_celular'];?>" class="not-required phone fbin" <?=$read_new;?>>
            </div><br>
            
            <label>Email: </label>
			<div class="content-input">
				<input type="text" id="dc-email" name="dc-email" autocomplete="off" value="<?=$row['cl_email'];?>" class="not-required email fbin" <?=$read_new;?>>
			</div><br>
        </div><!--
        --><div class="form-col">
			<label>Avenida o Calle: <span>*</span></label>
            <div class="content-input">
                <select id="dc-avc" name="dc-avc" class="<?=$read_nat;?> fbin <?=$read_save;?>" <?=$read_save;?>>
                    <option value="">Seleccione...</option>
<?php
$arr_AC = $link->avc;
for($i = 0; $i < count($arr_AC); $i++){
	$AC = explode('|',$arr_AC[$i]);
	if($AC[0] === $cl_avc) {
		echo '<option value="'.$AC[0].'" selected>'.$AC[1].'</option>';
	}else {
		echo '<option value="'.$AC[0].'">'.$AC[1].'</option>';
	}
}
?>
                </select>
            </div><br>
            
            <label>Dirección domicilio: <span>*</span></label><br>
            <textarea id="dc-address-home" name="dc-address-home" class="<?=$read_nat;?> fbin" <?=$read_save;?>><?=$cl_dir;?></textarea><br>
            
            <label>Número de domicilio: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-nhome" name="dc-nhome" autocomplete="off" value="<?=$cl_nd;?>" class="<?=$read_nat;?> number fbin" <?=$read_save;?>>
            </div><br>
            
            <label>Ocupación: <span>*</span></label>
            <div class="content-input">
                <select id="dc-occupation" name="dc-occupation" class="<?=$read_nat;?> fbin " <?=$read_save;?>>
                    <option value="">Seleccione...</option>
<?php
if (($rsOcc = $link->get_occupation($_SESSION['idEF'], 'TRM')) !== FALSE) {
	while($rowOcc = $rsOcc->fetch_array(MYSQLI_ASSOC)){
		if($rowOcc['id_ocupacion'] === $row['cl_ocupacion']) {
			echo '<option value="'.base64_encode($rowOcc['id_ocupacion']).'" selected>'.$rowOcc['ocupacion'].'</option>';
		} else {
			echo '<option value="'.base64_encode($rowOcc['id_ocupacion']).'">'.$rowOcc['ocupacion'].'</option>';
		}
	}
}
?>
                </select>
            </div><br>
            
            <label style="width:auto;">Descripción Ocupación: <span>*</span></label><br>
            <textarea id="dc-desc-occ" name="dc-desc-occ" class="<?=$read_nat;?> fbin" <?=$read_save;?> ><?=$row['cl_desc_ocupacion'];?></textarea><br>
            
            <label>Dirección laboral: <span>*</span></label><br>
            <textarea id="dc-address-work" name="dc-address-work" class="<?=$read_nat;?> fbin" <?=$read_save;?>><?=$cl_dir_office;?></textarea><br>
            
            <label>Teléfono oficina: </label>
            <div class="content-input">
                <input type="text" id="dc-phone-office" name="dc-phone-office" autocomplete="off" value="<?=$row['cl_tel_oficina'];?>" class="not-required phone fbin" <?=$read_save;?>>
            </div><br>
        </div><br>
    </div>
    
    <!-- JURIDICO -->
    <div id="form-company" style=" <?=$display_jur;?> ">
    	<div class="form-col">
            <label style="width:auto;">Nombre o Razón Social: <span>*</span></label><br>
            <div class="content-input">
                <textarea id="dc-company-name" name="dc-company-name" class="<?=$read_jur;?> fbin field-company" <?=$read_new;?>><?=$row['cl_razon_social'];?></textarea><br>
            </div><br>
            
            <label>NIT: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-nit" name="dc-nit" autocomplete="off" value="<?=$row['cl_dni'];?>" class="<?=$read_jur;?> dni fbin field-company" <?=$read_new.$read_edit;?>>
            </div><br>
            
            <label>Departamento: <span>*</span></label>
            <div class="content-input">
                <select id="dc-depto" name="dc-depto" class="<?=$read_jur;?> fbin field-company <?=$read_new.$read_edit;?>" <?=$read_save;?>>
                    <option value="">Seleccione...</option>
<?php
if ($rsDep->data_seek(0) === TRUE) {
	while($rowDep = $rsDep->fetch_array(MYSQLI_ASSOC)){
		if((boolean)$rowDep['tipo_ci'] === TRUE){
			if($rowDep['id_depto'] === $row['cl_extension']) {
				echo '<option value="'.$rowDep['id_depto'].'" selected>'.$rowDep['departamento'].'</option>';
			} else {
				echo '<option value="'.$rowDep['id_depto'].'">'.$rowDep['departamento'].'</option>';
			}
		}
	}
}
?>
                </select>
            </div><br>
            
            <label>Teléfono oficina: </label>
            <div class="content-input">
                <input type="text" id="dc-company-phone-office" name="dc-company-phone-office" autocomplete="off" value="<?=$row['cl_tel_oficina'];?>" class="not-required phone  fbin" <?=$read_new;?>>
            </div><br>
                
			<label>Email: </label>
            <div class="content-input">
                 <input type="text" id="dc-company-email" name="dc-company-email" autocomplete="off" value="<?=$row['cl_email'];?>" class="not-required email fbin" <?=$read_new;?>>
            </div><br>
        </div><!--
        --><div class="form-col">
        	<label>Avenida o Calle: <span>*</span></label>
            <div class="content-input">
                <select id="dc-company-avc" name="dc-company-avc" class="<?=$read_jur;?> fbin <?=$read_save;?>" <?=$read_save;?>>
                    <option value="">Seleccione...</option>
<?php
$arr_AC = $link->avc;
for($i = 0; $i < count($arr_AC); $i++){
	$AC = explode('|',$arr_AC[$i]);
	if($AC[0] === $cl_avc) {
		echo '<option value="'.$AC[0].'" selected>'.$AC[1].'</option>';
	} else {
		echo '<option value="'.$AC[0].'">'.$AC[1].'</option>';
	}
}
?>
                </select>
            </div><br>
            
            <label>Dirección domicilio: <span>*</span></label><br>
			<textarea id="dc-company-address-home" name="dc-company-address-home" class="<?=$read_jur;?> fbin" <?=$read_save;?>><?=$cl_dir;?></textarea><br>
            
            <label>Número de domicilio: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-company-nhome" name="dc-company-nhome" autocomplete="off" value="<?=$cl_nd;?>" class="<?=$read_jur;?> number fbin" <?=$read_save;?>>
            </div><br>
        </div>
    </div>
<?php
}

?>
	<hr>
    <div class="form-col">
	    <input type="hidden" id="dc-attached" name="dc-attached" value="<?=base64_encode($row['cl_adjunto']);?>" class="required">
        <div class="content-input" style="width:100%; text-align:center;">
<?php
if($sw === 2 || $sw === 3) {
	echo '<a href="files/'.$row['cl_adjunto'].'" target="_blank" class="attached-link">Documentación del Arrendatario</a><br><br>';
}

if($sw !== 2) {
?>
			<a href="javascript:;" id="a-dc-attached" class="attached">Adjuntar documentación del Arrendatario</a>
            <div class="attached-mess">
                El tamaño máximo del archivo es de 20Mb. <br>
                El formato del archivo a subir debe ser JPG, PNG, PDF, RAR ó ZIP
            </div>
<script type="text/javascript">
set_ajax_upload('dc-attached', 'TRM');
</script>
<?php
}
?>
		</div><br>
    </div><!--
    --><div class="form-col">
<?php
if($sw !== 2) {
?>
		<div class="mess-attched-au">
        	<ul>
            	<li>Formulario de Identificación del cliente <?=$row['ef_nombre'];?></li>
                <li>Carta de Nombramiento</li>
                <li>Fotocopia de la cedula de identidad (si es persona natural o del Representante legal si es Jurídica)</li>
                <li>Fotocopia del NIT (para Personas Jurídicas o unipersonales)</li>
                <li>Fotocopia de fundempresa (para Personas Jurídicas)</li>
                <li>Testimonio de Poder del representante legal (para Personas Jurídicas)</li>
                <li>Testimonio de Constitución (para Personas Jurídicas)</li>
                <li>Conformidad del Arrendatario y especificando la forma de pago (Contado o Contado anualizado)</li>
                <li>Copia del Avalúo actualizado (de ser necesario para Inmuebles y Automotores).</li>
                <li>Formulario para Inventariación de Contenido en Riesgos Domiciliarios.</li>
            </ul>
        </div>
<?php
}
?>
    </div>
    
    <h4>Datos del Material Asegurado</h4>
<?php
if($rs->data_seek(0) === TRUE){
	$k = 0;
?>
	<table class="list-cl list-vh">
		<tr class="title-vh" valign="top">
            <td style="width:60%;">Material Asegurado</td>
            <td style="width:20%;">Valor Asegurado</td>
            <td style="width:20%;">Prima</td>
        </tr>
<?php
	while($rowMt = $rs->fetch_array(MYSQLI_ASSOC)){
		$k += 1;
?>
        <tr valign="top">
        	<td>
<?php
if($sw > 1){
	echo '<input type="hidden" id="dm-'.$k.'-idmt" name="dm-'.$k.'-idmt" value="'.base64_encode($rowMt['idmt']).'" class="required">';
}
?>
        		<textarea id="dm-<?=$k;?>-material" name="dm-<?=$k;?>-material" class="required fbin" <?=$read_new.' '.$read_save;?>><?=$rowMt['m_material'];?></textarea>
				</select>
            </td>
            <td>
            	<span class="value"><?=number_format($rowMt['m_valor_asegurado'], 2, '.', ',');?> USD.</span>
            </td>
            <td colspan="2">
<?php
if($sw === 1) {
	if(($rowTasa = $link->get_tasa_year_trm($_GET['cia'], base64_encode($rowMt['id_ef']), $YEAR_FINAL, $rowMt['c_forma_pago'])) !== FALSE){
		$TASA = $rowTasa['t_tasa_final'];
		$PRIMA = ($rowMt['m_valor_asegurado'] * $TASA) / 100;
	}
} else {
	$TASA = $rowMt['m_tasa'];
	$PRIMA = $rowMt['m_prima'];
}
?>
				<span class="value value-premium"><?=number_format($PRIMA, 2, '.', ',');?> USD.</span>
                <input type="hidden" id="dm-<?=$k;?>-value-insured" name="dm-<?=$k;?>-value-insured" value="<?=base64_encode($rowMt['m_valor_asegurado']);?>" class="required"> 
                <input type="hidden" id="dm-<?=$k;?>-rate" name="dm-<?=$k;?>-rate" value="<?=base64_encode($TASA);?>" class="required">
                <input type="hidden" id="dm-<?=$k;?>-premium" name="dm-<?=$k;?>-premium" value="<?=base64_encode($PRIMA);?>" class="required">
<?php
if ($rowMt['m_modalidad'] !== null) {
	$swMo = true;
?>
				<input type="hidden" id="dm-<?=$k;?>-modality" name="dm-<?=$k;?>-modality" value="<?=base64_encode($rowMt['m_modalidad']);?>" >
<?php
}
?>
			</td>
        </tr>
        
        <tr class="thead">
        	<td colspan="8">
            	<input type="hidden" id="dm-<?=$k;?>-attached" name="dm-<?=$k;?>-attached" value="<?=base64_encode($rowMt['mt_adjunto']);?>" class="required">
<?php
if($sw === 2 || $sw === 3) {
	echo '<a href="files/'.$rowMt['mt_adjunto'].'" target="_blank" class="attached-link">Documentación del la Materia Asegurada</a>';
}
if($sw !== 2) {
?>
			<a href="javascript:;" id="a-dm-<?=$k;?>-attached" class="attached">Adjuntar documentación de la Materia Asegurada</a>
            <div class="attached-mess">
                El tamaño máximo del archivo es de 20Mb. <br>
                El formato del archivo a subir debe ser JPG, PNG, PDF, RAR ó ZIP
            </div>
<script type="text/javascript">
set_ajax_upload('dm-<?=$k;?>-attached', 'TRM');
</script>
<?php    
}
?>
            </td>
        </tr>
<?php
	}
?>
	 </table>
<?php
}
?>
	<br>
	<h4>Datos del Crédito Solicitado</h4>
	<div class="form-col">
    	<input type="hidden" id="nMt" name="nMt" value="<?=base64_encode($nMt);?>">
        <input type="hidden" id="di-warranty" name="di-warranty" value="<?=base64_encode($row['c_garantia']);?>">
    	<label>Inicio de Vigencia: <span>*</span></label>
        <div class="content-input">
            <input type="text" id="di-date-inception-1" name="di-date-inception-1" autocomplete="off" value="<?=date('d/m/Y', strtotime($row['c_ini_vigencia']));?>" class="required fbin" readonly style="cursor:pointer;" <?=$read_new.$read_edit;?>>
            <input type="hidden" id="di-date-inception" name="di-date-inception" value="<?=base64_encode($row['c_ini_vigencia']);?>">
            <input type="hidden" id="di-end-inception" name="di-end-inception" value="<?=base64_encode($row['c_fin_vigencia']);?>">
        </div><br>
        
		<label>Plazo del Crédito: <span>*</span></label>
		<div class="content-input" style="width:auto;">
			<input type="text" id="di-term" name="di-term" autocomplete="off" value="<?=$cr_term;?>" style="width:30px;" maxlength="" class="not-required number fbin" <?=$read_new.$read_edit;?>>
		</div>
		
		<label>&nbsp;</label>
		<div class="content-input">
			<select id="di-type-term" name="di-type-term" class="required fbin <?=$read_new.$read_edit;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
<?php
$arr_term = array(0 => 'Y|Años', 1 => 'M|Meses', 2 => 'D|Días');
for($i = 0; $i < count($arr_term); $i++){
	$term = explode('|',$arr_term[$i]);
	if($term[0] === $cr_type_term) {
		echo '<option value="'.$term[0].'" selected>'.$term[1].'</option>';
	} else {
		echo '<option value="'.$term[0].'">'.$term[1].'</option>';
	}
}
?>
			</select>
		</div><br>
	</div><!--
	--><div class="form-col">
    	<label>Forma de Pago: <span>*</span></label>
        <div class="content-input">
            <select id="di-method-payment" name="di-method-payment" class="required fbin <?=$read_new.$read_edit;?>" <?=$read_save;?>>
	            <option value="">Seleccione...</option>
<?php
if(($rsFp = $link->get_method_payment('TRM', $_SESSION['idEF'])) !== FALSE) {
	while($rowFp = $rsFp->fetch_array(MYSQLI_ASSOC)) {
		if($rowFp['id_forma_pago'] === $row['id_forma_pago']) {
			echo '<option value="'.base64_encode($rowFp['id_forma_pago']).'" selected>'.$rowFp['forma_pago'].'</option>';
		} else {
			echo '<option value="'.base64_encode($rowFp['id_forma_pago']).'">'.$rowFp['forma_pago'].'</option>';
		}
	}
}
?>
            </select>
        </div><br>
    
		<label>Número de Operación: </label>
		<div class="content-input" style="width:auto;">
			<input type="text" id="di-opp" name="di-opp" autocomplete="off" value="<?=$cr_opp;?>" class="not-required number fbin" <?=$read_save;?>>
		</div>
<?php
if ($swMo === false) {
?>
		<label>Número de Póliza: <span>*</span></label>
		<div class="content-input">
			<select id="di-policy" name="di-policy" class="required fbin" <?=$read_save;?>>
				<option value="">Seleccione...</option>
<?php
if (($rsPl = $link->get_policy($_SESSION['idEF'], 'TRM')) !== FALSE) {
	while($rowPl = $rsPl->fetch_array(MYSQLI_ASSOC)){
		if($rowPl['id_poliza'] == $cr_policy) {
			echo '<option value="'.base64_encode($rowPl['id_poliza']).'" selected>'.$rowPl['no_poliza'].'</option>';
		} else {
			echo '<option value="'.base64_encode($rowPl['id_poliza']).'">'.$rowPl['no_poliza'].'</option>';
		}
	}
}
?>
			</select>
		</div><br>
<?php
}
?>
	</div>
<?php
$display_bll = 'display: none;';
if(($BLL = $link->verify_billing('TRM', $_SESSION['idEF'])) !== FALSE) {
	if($BLL === 'SI') { $display_bll = 'display: block'; }
}
?>
	<div style=" <?=$display_bll;?> ">
    	<h4>Datos de Facturación</h4>
        <div class="form-col">
            <label>Facturar a: <span>*</span></label><br>
            <div class="content-input">
                <textarea id="bl-name" name="bl-name" class="required fbin" <?=$read_new;?>><?=$row['facturacion_nombre'];?></textarea><br>
            </div><br>
        </div><!--
        --><div class="form-col">
            <label>NIT: <span>*</span></label><br>
            <div class="content-input">
                <input type="text" id="bl-nit" name="bl-nit" autocomplete="off" value="<?=$row['cl_dni'];?>" class="required dni fbin field-company" <?=$read_new;?>>
            </div><br>
        </div>
    </div>
    
    <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
	<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
	<input type="hidden" id="pr" name="pr" value="<?=$_GET['pr'];?>">
    <input type="hidden" id="flag" name="flag" value="<?=$_GET['flag'];?>">
    <input type="hidden" id="cia" name="cia" value="<?=$_GET['cia'];?>">
    <input type="hidden" id="idef" name="idef" value="<?=$_SESSION['idEF'];?>">
<?php
	if($sw === 1) {
		echo '<input type="hidden" id="cp" name="cp" value="'.base64_encode($row['cp']).'">';
	}
	
	$target = '';
	if(isset($_GET['target'])){
		echo '<input type="hidden" id="target" name="target" value="'.$_GET['target'].'">';
		$target = '&target='.$_GET['target'];
	}

	if(isset($_GET['ide'])) {
		echo '<input type="hidden" id="de-ide" name="de-ide" value="'.base64_encode($ide).'" >';
	} elseif(isset($_GET['idc'])) {
		echo '<input type="hidden" id="de-idc" name="de-idc" value="'.base64_encode($idc).'" >';
	}
?>
	<div style="text-align:center;">
<?php
	if($sw === 2) {
		echo '<input type="button" id="dc-edit" name="dc-edit" value="Editar" class="btn-next btn-issue" > ';
	}
	// IMPLANTE
	$_IMP = $link->verify_implant($_SESSION['idEF'], 'TRM');
	
	if($_IMP === TRUE) {
		if ($link->verify_agency_issuing($_SESSION['idUser'], $_SESSION['idEF'], 'TRM') === TRUE && $sw === 2) {
			if($FC === TRUE && $sw === 2){
				if(!isset($_GET['target'])) {
					goto btnApproval;
				}
			} else {
				btnIssue: 
				echo '<input type="submit" id="dc-issue" name="dc-issue" value="'.$title_btn.'" class="btn-next btn-issue" > ';
			}
		} elseif ($sw === 2) {
			if(!isset($_GET['target'])) {
				echo '<a href="implant-send-approve.php?ide='.base64_encode($ide).'&pr='.base64_encode('TRM').'" class="fancybox fancybox.ajax btn-issue">Solicitar aprobación del Intermediario</a> ';
			}
		} else {
			goto btnIssue;
			//echo '<input type="submit" id="dc-issue" name="dc-issue" value="'.$title_btn.'" class="btn-next btn-issue" > ';
		}
	} else {
		if($FC === TRUE && $sw === 2){
			if(!isset($_GET['target'])) {
				btnApproval:
				echo '<a href="company-approval.php?ide='.base64_encode($ide).'&pr='.base64_encode('TRM').'" class="fancybox fancybox.ajax btn-issue">Solicitar aprobación de la Compañia</a> ';
			}
		} else{
			goto btnIssue;
			//echo '<input type="submit" id="dc-issue" name="dc-issue" value="'.$title_btn.'" class="btn-next btn-issue" > ';
		}
	}
	
	if($sw === 2) {
		echo '<input type="button" id="dc-save" name="dc-save" value="Guardar/Cerrar" class="btn-next btn-issue" >';
	}
?>
    </div>
    
    <div class="loading">
		<img src="img/loading-01.gif" width="35" height="35" />
	</div>
</form>
<script type="text/javascript">
$(document).ready(function(e) {
	$("#dc-save").click(function(e){
		e.preventDefault();
		location.href = 'index.php';
	});
	
	$("#dc-edit").click(function(e){
		e.preventDefault();
		location.href = 'trm-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=$_GET['pr'];?>&ide=<?=base64_encode($ide);?>&flag=<?=md5('i-edit');?>&cia=<?=$_GET['cia'].$target;?>';
	});
<?php
switch($sw){
	case 1:
?>
	$("#fde-issue").validateForm({
		action: '<?=$action;?>',
		tm: true
	});
	
	$("#fde-issue").submit();
<?php
		break;
	case 2:
?>
	$("#fde-issue").validateForm({
		action: '<?=$action;?>',
		tm: true,
		issue: true
	});
	$("#issue-title").append(' <?=$idNE;?>');
	
<?php
		break;
	case 3:
?>
	$(".date").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
		yearRange: "c-100:c+100"
	});
	
	$(".date").datepicker($.datepicker.regional[ "es" ]);
	
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
	$("#issue-title:last").after('\
		<div class="fac-mess">\
			<strong>Nota:</strong> Se deshabilitó el boton "Emitir" por las siguientes razones: <br><?=$mFC;?>\
			<br><br><strong>Por tanto:</strong><br>\
			Debe solicitar aprobación a la Compañía de Seguros. \
		</div>');
<?php
}
?>
});
</script>
<?php
}else{
	echo 'No existen Clientes';
	exit();
}