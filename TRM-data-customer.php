<script type="text/javascript">
$(document).ready(function(e) {
	$("#fau-customer").validateForm({
		action: 'TRM-customer-record.php'
	});
	
	$(".date").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
		yearRange: "c-100:c+100"
	});
	
	$(".date").datepicker($.datepicker.regional[ "es" ]);
	
	$("input[type='text'].fbin, textarea.fbin").keyup(function(e){
		var arr_key = new Array(37, 39, 8, 16, 32, 18, 17, 46, 36, 35, 186);
		var _val = $(this).prop('value');
		
		if($.inArray(e.keyCode, arr_key) < 0 && $(this).hasClass('email') === false){
			$(this).prop('value',_val.toUpperCase());
		}
	});
	
	$("#dc-type-client").change(function(e){
		var type = $(this).prop('value');
		if(type !== ''){
			$("#fau-sc").slideDown();
			$("#form-person, #form-company").hide();
			switch(type){
				case 'NAT':
					$("#dsc-type-client").prop('value', 'NAT');
					$("#form-person").slideDown();
					$("#form-person").find('.field-person').removeClass('not-required').addClass('required');
					$("#form-company").find('.field-company').removeClass('required').addClass('not-required');
					break;
				case 'JUR':
					$("#dsc-type-client").prop('value', 'JUR');
					$("#form-company").slideDown();
					$("#form-company").find('.field-company').removeClass('not-required').addClass('required');
					$("#form-person").find('.field-person').removeClass('required').addClass('not-required');
					break;
			}
		}else{
			$("#form-person, #form-company").hide();
			$("#fau-sc").slideUp();
			$("#dsc-type-client").prop('value', '');
		}
	});
	
});
</script>
<?php
require_once('sibas-db.class.php');
$link = new SibasDB();

$swCl = FALSE;

$dc_name = '';
$dc_company_name = '';
$dc_lnpatern = '';
$dc_lnmatern = '';
$dc_lnmarried = '';
$dc_doc_id = '';
$dc_nit = '';
$dc_comp = '';
$dc_ext = '';
$dc_depto = '';
$dc_birth = '';
$dc_gender = '';
$dc_phone_1 = '';
$dc_phone_2 = '';
$dc_email = '';
$dc_company_email = '';
$dc_phone_office = '';


$title_btn = 'Cotiza tu mejor seguro';
$err_search = '';

$display_fsc = $display_nat = $display_jur = 'display: none;';
$require_nat = $require_jur = 'not-required';
$_TYPE_CLIENT = '';

if(isset($_POST['dsc-dni']) && isset($_POST['dsc-type-client'])){
	$dni = $link->real_escape_string(trim($_POST['dsc-dni']));
	$type_client = $link->real_escape_string(trim($_POST['dsc-type-client']));
	$_TYPE_CLIENT = $type_client;
	if($type_client === 'NAT'){
		$type_client = 0;
		$display_nat = 'display: block;';
		$require_nat = 'required';
	}elseif($type_client === 'JUR'){
		$type_client = 1;
		$display_jur = 'display: block;';
		$require_jur = 'required';
	}
	
	$display_fsc = 'display: block;';
	
	$sqlSc = 'select 
			scl.id_cliente,
			scl.tipo as cl_tipo,
			scl.razon_social as cl_razon_social,
			scl.nombre as cl_nombre,
			scl.paterno as cl_paterno,
			scl.materno as cl_materno,
			scl.ap_casada as cl_ap_casada,
			scl.ci as cl_dni,
			scl.complemento as cl_complemento,
			scl.extension as cl_extension,
			scl.fecha_nacimiento as cl_fecha_nacimiento,
			scl.telefono_domicilio as cl_tel_domicilio,
			scl.telefono_celular as cl_tel_celular,
			scl.telefono_oficina as cl_tel_oficina,
			scl.email as cl_email,
			scl.genero as cl_genero
		from
			s_trm_cot_cliente as scl
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = scl.id_ef)
		where
			scl.ci = "'.$dni.'"
				and scl.tipo = '.$type_client.'
				and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
				and sef.activado = true
		limit 0 , 1
		;';
	//echo $sqlSc;
	if(($rsSc = $link->query($sqlSc,MYSQLI_STORE_RESULT))){
		if($rsSc->num_rows === 1){
			$rowSc = $rsSc->fetch_array(MYSQLI_ASSOC);
			$rsSc->free();
			
			$dc_company_name = $rowSc['cl_razon_social'];
			$dc_name = $rowSc['cl_nombre'];
			$dc_lnpatern = $rowSc['cl_paterno'];
			$dc_lnmatern = $rowSc['cl_materno'];
			$dc_lnmarried = $rowSc['cl_ap_casada'];
			$dc_nit = $dc_doc_id = $rowSc['cl_dni'];
			$dc_comp = $rowSc['cl_complemento'];
			$dc_depto = $dc_ext = $rowSc['cl_extension'];
			$dc_birth = $rowSc['cl_fecha_nacimiento'];
			$dc_phone_1 = $rowSc['cl_tel_domicilio'];
			$dc_phone_2 = $rowSc['cl_tel_celular'];
			$dc_company_email = $dc_email = $rowSc['cl_email'];
			$dc_phone_office = $rowSc['cl_tel_oficina'];
			$dc_gender = $rowSc['cl_genero'];
			
			$dc_type = (int)$rowSc['cl_tipo'];
			if($dc_type === 1) {
				$dc_doc_id = $dc_ext = $dc_email = '';
			} elseif ($dc_type === 0) {
				$dc_nit = $dc_depto = $dc_company_email = '';
			}
		}else{
			$err_search = 'El Arrendatario no Existe !';
		}
	}else{
		$err_search = 'El Arrendatario no Existe';
	}
}

/*if(isset($_GET['idCl'])){
	$swCl = TRUE;
	$title_btn = 'Actualizar datos';
	
	$sqlUp = 'select 
			scl.id_cliente,
			scl.paterno,
			scl.materno,
			scl.nombre,
			scl.ap_casada,
			scl.fecha_nacimiento,
			scl.lugar_nacimiento,
			scl.ci,
			scl.extension,
			scl.complemento,
			scl.tipo_documento,
			scl.estado_civil,
			scl.lugar_residencia,
			scl.localidad,
			scl.direccion,
			scl.pais,
			scl.id_ocupacion,
			scl.desc_ocupacion,
			scl.telefono_domicilio,
			scl.telefono_oficina,
			scl.telefono_celular,
			scl.email,
			scl.peso,
			scl.estatura,
			scl.genero
		from
			s_de_cot_cliente as scl
				inner join s_entidad_financiera as sef ON (sef.id_ef = scl.id_ef)
		where
			scl.id_cliente = "'.base64_decode($_GET['idCl']).'"
				and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
				and sef.activado = true
		;';
	
	$rsUp = $link->query($sqlUp);
	
	if($rsUp->num_rows === 1){
		$rowUp = $rsUp->fetch_array(MYSQLI_ASSOC);
		$rsUp->free();
		
		$dc_name = $rowUp['nombre'];
		$dc_lnpatern = $rowUp['paterno'];
		$dc_lnmatern = $rowUp['materno'];
		$dc_lnmarried = $rowUp['ap_casada'];
		$dc_status = $rowUp['estado_civil'];
		$dc_type_doc = $rowUp['tipo_documento'];
		$dc_doc_id = $rowUp['ci'];
		$dc_comp = $rowUp['complemento'];
		$dc_ext = $rowUp['extension'];
		$dc_country = $rowUp['pais'];
		$dc_birth = $rowUp['fecha_nacimiento'];
		$dc_place_birth = $rowUp['lugar_nacimiento'];
		$dc_place_res = $rowUp['lugar_residencia'];
		$dc_locality = $rowUp['localidad'];
		$dc_address = $rowUp['direccion'];
		$dc_occupation = $rowUp['id_ocupacion'];
		$dc_desc_occ = $rowUp['desc_ocupacion'];
		$dc_phone_1 = $rowUp['telefono_domicilio'];
		$dc_phone_2 = $rowUp['telefono_celular'];
		$dc_phone_office = $rowUp['telefono_oficina'];
		$dc_email = $rowUp['email'];
		$dc_gender = $rowUp['genero'];
		$dc_weight = $rowUp['peso'];
		$dc_height = $rowUp['estatura'];
	}
}*/

?>
<h3>Datos del Arrendatario</h3>
<div style="text-align:center;">
	<form id="fau-sc" name="fau-sc" action="" method="post" class="form-quote" style=" <?=$display_fsc;?> ">
        <label>Documento de Identidad: <span>*</span></label>
        <div class="content-input" style="width:auto;">
            <input type="text" id="dsc-dni" name="dsc-dni" autocomplete="off" value="" style="width:120px;" class="required text fbin">
        </div>
        <input type="hidden" id="dsc-type-client" name="dsc-type-client" value="<?=$_TYPE_CLIENT;?>">
        <input type="submit" id="dsc-sc" name="dsc-sc" value="Buscar Arrendatario" class="btn-search-cs">
		<div class="mess-err-sc"><?=$err_search;?></div>
    </form>
</div>

<form id="fau-customer" name="fau-customer" action="" method="post" class="form-quote form-customer">
    <div style="text-align:center;">
    	<label style="text-align:right;">Tipo de Cliente: <span>*</span></label>
            <div class="content-input">
            <select id="dc-type-client" name="dc-type-client" class="required fbin">
                <option value="">Seleccione...</option>
<?php
$arr_type_client = $link->typeClient;
for($i = 0; $i < count($arr_type_client); $i++){
	$tc = explode('|', $arr_type_client[$i]);
	if($_TYPE_CLIENT === $tc[0]) {
		echo '<option value="'.$tc[0].'" selected>'.$tc[1].'</option>';
	} else {
		echo '<option value="'.$tc[0].'">'.$tc[1].'</option>';
	}
}
?>
            </select>
        </div><br>
    </div><br>
    
    <div id="form-person" style=" <?=$display_nat;?> ">
    	<div class="form-col">
            <label>Apellido Paterno: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-ln-patern" name="dc-ln-patern" autocomplete="off" value="<?=$dc_lnpatern;?>" class="<?=$require_nat;?> text fbin field-person">
            </div><br>
            
            <label>Apellido Materno: </label>
            <div class="content-input">
                <input type="text" id="dc-ln-matern" name="dc-ln-matern" autocomplete="off" value="<?=$dc_lnmatern;?>" class="not-required text fbin">
            </div><br>
            
            <label>Nombres: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-name" name="dc-name" autocomplete="off" value="<?=$dc_name;?>" class="<?=$require_nat;?> text fbin field-person">
            </div><br>
            
            <label>Apellido de Casada: </label>
            <div class="content-input">
                <input type="text" id="dc-ln-married" name="dc-ln-married" autocomplete="off" value="<?=$dc_lnmarried;?>" class="not-required text fbin">
            </div><br>
            
            <label>Documento de Identidad: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-doc-id" name="dc-doc-id" autocomplete="off" value="<?=$dc_doc_id;?>" class="<?=$require_nat;?> dni fbin field-person">
            </div><br>
            
            <label>Complemento: </label>
            <div class="content-input">
                <input type="text" id="dc-comp" name="dc-comp" autocomplete="off" value="<?=$dc_comp;?>" class="not-required dni fbin" style="width:60px;">
            </div><br>
            
            <label>Extensión: <span>*</span></label>
            <div class="content-input">
                <select id="dc-ext" name="dc-ext" class="<?=$require_nat;?> fbin field-person">
                    <option value="">Seleccione...</option>
<?php
$rsDep = null;
if(($rsDep = $link->get_depto()) === FALSE) {
	$rsDep = null;
}

if ($rsDep->data_seek(0) === TRUE) {
	while($rowDep = $rsDep->fetch_array(MYSQLI_ASSOC)){
		if((boolean)$rowDep['tipo_ci'] === TRUE){
			if($rowDep['id_depto'] === $dc_ext) {
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
        </div><!--
        --><div class="form-col">
        	<label>Género: <span>*</span></label>
            <div class="content-input">
                <select id="dc-gender" name="dc-gender" class="<?=$require_nat;?> fbin field-person">
                    <option value="">Seleccione...</option>
<?php
$arr_gender = $link->gender;
for($i = 0; $i < count($arr_gender); $i++){
	$gender = explode('|',$arr_gender[$i]);
	if($gender[0] === $dc_gender) {
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
                <input type="text" id="dc-date-birth" name="dc-date-birth" autocomplete="off" value="<?=$dc_birth;?>" class="<?=$require_nat;?> fbin date field-person" readonly style="cursor:pointer;">
            </div><br>
            
            <label>Teléfono de domicilio: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-phone-1" name="dc-phone-1" autocomplete="off" value="<?=$dc_phone_1;?>" class="<?=$require_nat;?> phone fbin field-person">
            </div><br>
            
            <label>Teléfono celular: </label>
            <div class="content-input">
                <input type="text" id="dc-phone-2" name="dc-phone-2" autocomplete="off" value="<?=$dc_phone_2;?>" class="not-required phone fbin">
            </div><br>
            
            <label>Email: </label>
            <div class="content-input">
                <input type="text" id="dc-email" name="dc-email" autocomplete="off" value="<?=$dc_email;?>" class="not-required email fbin">
            </div><br>
        </div><br>
    </div>
    
    <div id="form-company" style=" <?=$display_jur;?> ">
    	<div class="form-col">
            <label style="width:auto;">Nombre o Razón Social: <span>*</span></label><br>
            <div class="content-input">
                <textarea id="dc-company-name" name="dc-company-name" class="<?=$require_jur;?> fbin field-company"><?=$dc_company_name;?></textarea><br>
            </div><br>
            
            <label>NIT: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-nit" name="dc-nit" autocomplete="off" value="<?=$dc_nit;?>" class="<?=$require_jur;?> dni fbin field-company">
            </div><br>
            
            <label>Departamento: <span>*</span></label>
            <div class="content-input">
                <select id="dc-depto" name="dc-depto" class="<?=$require_jur;?> fbin field-company">
                    <option value="">Seleccione...</option>
<?php
if ($rsDep->data_seek(0) === TRUE) {
	while($rowDep = $rsDep->fetch_array(MYSQLI_ASSOC)){
		if((boolean)$rowDep['tipo_ci'] === TRUE){
			if($rowDep['id_depto'] === $dc_depto) {
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
        </div><!--
        --><div class="form-col">
        	<label>Teléfono oficina: </label>
		<div class="content-input">
			<input type="text" id="dc-phone-office" name="dc-phone-office" autocomplete="off" value="<?=$dc_phone_office;?>" class="not-required phone  fbin">
		</div><br>
            
            <label>Email: </label>
            <div class="content-input">
                <input type="text" id="dc-company-email" name="dc-company-email" autocomplete="off" value="<?=$dc_company_email;?>" class="not-required email fbin">
            </div><br>
        </div><br>
    </div>
    
    <h2>Datos del Seguro Solicitado</h2>
    
    <label>Inicio de Vigencia: <span>*</span></label>
    <div class="content-input">
        <input type="text" id="di-date-inception" name="di-date-inception" autocomplete="off" value="<?=date('Y-m-d');?>" class="required fbin date" readonly style="cursor:pointer;">
    </div><br>
    
    <label>Plazo del Crédito: <span>*</span></label>
	<div class="content-input" style="width:auto;">
		<input type="text" id="di-term" name="di-term" autocomplete="off" value="" style="width:30px;" maxlength="4" class="required number fbin">
	</div>
	
	<div class="content-input" style="width:150px;">
		<select id="di-type-term" name="di-type-term" class="required fbin" style="width:133px;">
			<option value="">Seleccione...</option>
			<option value="Y">Años</option>
			<option value="M">Meses</option>
			<option value="D">Días</option>
		</select>
	</div><br>
    
    <label>Forma de Pago: <span>*</span></label>
    <div class="content-input">
		<select id="di-method-payment" name="di-method-payment" class="required fbin">
			<option value="">Seleccione...</option>
<?php
if(($rsFp = $link->get_method_payment('TRM', $_SESSION['idEF'])) !== FALSE) {
	while($rowFp = $rsFp->fetch_array(MYSQLI_ASSOC)) {
		echo '<option value="'.base64_encode($rowFp['id_forma_pago']).'">'.$rowFp['forma_pago'].'</option>';
	}
}
?>
		</select>
	</div><br>
	
    <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
	<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
	<input type="hidden" id="pr" name="pr" value="<?=base64_encode('TRM|03');?>">
	<input type="hidden" id="dc-idc" name="dc-idc" value="<?=$_GET['idc'];?>" >
	<input type="hidden" id="dc-token" name="dc-token" value="<?=base64_encode('dc-OK');?>" >
    <input type="hidden" id="id-ef" name="id-ef" value="<?=$_SESSION['idEF'];?>" >
<?php
if($swCl === TRUE) {
	echo '<input type="hidden" id="dc-idCl" name="dc-idCl" value="'.$_GET['idCl'].'" >';
}
?>    
	
	<input type="submit" id="dc-customer" name="dc-customer" value="<?=$title_btn;?>" class="btn-next" >

	<div class="loading">
		<img src="img/loading-01.gif" width="35" height="35" />
	</div>
</form>
<hr>