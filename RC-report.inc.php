<?php
require_once('sibas-db.class.php');
$link = new SibasDB();

$tokenSi = FALSE;
$idRc = '';
$flag = FALSE;
$roCl = 'required';
if(isset($_GET['rc'])){
	$tokenSi = TRUE;
	$idRc = $link->real_escape_string(trim(base64_decode($_GET['rc'])));
}

if(isset($_GET['flag']))
	if($_GET['flag'] === md5('rc-edit'))
		$flag = TRUE;

$readonly = '';
$display = '';
$display_select = '';
$btnText = 'Guardar Siniestro';
$title = 'Reportar Siniestro';
$k = 0;
$kk = '';
$sqlSi = '';
$arrSi = array('s-date-reg' => date('d/m/Y'),
			's-id-client' => '',
			's-type-doc' => '',
			's-dni' => '',
			's-ext' => '',
			's-patern' => '',
			's-matern' => '',
			's-name' => '',
			's-married' => '',
			's-image' => '',
			's-date-sinister' => '',
			's-circumstance' => '',
			's-rep-person' => '',
			's-rep-phone' => '',
			's-rep-email' => '',
			's-date-elaboration' => '',
			's-id-user' => '',
			's-user' => '',
			's-email' => '',
			's-subsidiary' => '',
			's-agency' => '');

if($flag === TRUE)
	$btnText = 'Actualizar Siniestro';

if($tokenSi === TRUE){
	if($flag === FALSE){
		$readonly = 'readonly';
		$display_select = 'display: none;';
	}
	$display = 'display: none;';

	$sqlSi = 'select
			ssi.id_siniestro as idRc,
			ssi.no_siniestro as s_no_siniestro,
			ssi.fecha_registro as s_fecha_registro,
			ssi.id_cliente as idCl,
			ssi.tipo_documento as s_tipo_documento,
			ssi.ci as s_ci,
			ssi.extension as s_extension,
			ssi.paterno as s_paterno,
			ssi.materno as s_materno,
			ssi.nombre as s_nombre,
			ssi.ap_casada as s_ap_casada,
			ssi.imagen as s_imagen,
			ssi.fecha_siniestro as s_fecha_siniestro,
			ssi.circunstancia as s_circunstancia,
			ssi.denuncia_persona as s_denuncia_persona,
			ssi.denuncia_telefono as s_denuncia_telefono,
			ssi.denuncia_email as s_denuncia_email,
			ssi.fecha_elaboracion as s_fecha_elaboracion,
			ssi.denunciado_por as s_denunciado_por,
			su.id_usuario as s_usuario,
			su.nombre as s_usuario_nombre,
			su.email as s_usuario_email,
			sdp.id_depto as s_sucursal,
		    sag.id_agencia as s_agencia
		from
			s_siniestro as ssi
				inner join
			s_usuario as su ON (su.id_usuario = ssi.denunciado_por)
				inner join
			s_departamento as sdp ON (sdp.id_depto = ssi.sucursal)
				left join
			s_agencia as sag ON (sag.id_agencia = ssi.agencia)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = ssi.id_ef)
		where
			ssi.id_siniestro = "'.$idRc.'"
				and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
				and sef.activado = true
		limit 0 , 1
		;';

	if(($rsSi = $link->query($sqlSi, MYSQLI_STORE_RESULT))){
		$rowSi = $rsSi->fetch_array(MYSQLI_ASSOC);
		$rsSi->free();

		$arrSi['s-date-reg'] = $rowSi['s_fecha_registro'];
		$arrSi['s-id-client'] = $rowSi['idCl'];
		if(empty($arrSi['s-id-client']) === TRUE) $roCl = '';
		$arrSi['s-type-doc'] = $rowSi['s_tipo_documento'];
		$arrSi['s-dni'] = $rowSi['s_ci'];

		$arrSi['s-name'] = $rowSi['s_nombre'];
		$arrSi['s-patern'] = $rowSi['s_paterno'];
		$arrSi['s-matern'] = $rowSi['s_materno'];
		$arrSi['s-married'] = $rowSi['s_ap_casada'];

		$arrSi['s-ext'] = $rowSi['s_extension'];

		$arrSi['s-image'] = $rowSi['s_imagen'];
		$arrSi['s-date-sinister'] = $rowSi['s_fecha_siniestro'];
		$arrSi['s-circumstance'] = $rowSi['s_circunstancia'];

		$arrSi['s-rep-person'] = $rowSi['s_denuncia_persona'];
		$arrSi['s-rep-phone'] = $rowSi['s_denuncia_telefono'];
		$arrSi['s-rep-email'] = $rowSi['s_denuncia_email'];

		$arrSi['s-date-elaboration'] = $rowSi['s_fecha_elaboracion'];
		$arrSi['s-id-user'] = $rowSi['s_usuario'];
		$arrSi['s-user'] = $rowSi['s_usuario_nombre'];
		$arrSi['s-email'] = $rowSi['s_usuario_email'];
		$arrSi['s-subsidiary'] = $rowSi['s_sucursal'];
		$arrSi['s-agency'] = $rowSi['s_agencia'];

		$title = 'Detalle de Siniestro N° '.$rowSi['s_no_siniestro'];
	}
}else{
	$sqlUs = 'SELECT su.id_usuario, su.nombre, su.email, su.id_depto, su.id_agencia
			FROM s_usuario as su
			WHERE su.id_usuario = "'.base64_decode($_SESSION['idUser']).'"
			LIMIT 0, 1 ;';
	if(($rsUs = $link->query($sqlUs,MYSQLI_STORE_RESULT))){
		$rowUs = $rsUs->fetch_array(MYSQLI_ASSOC);
		$rsUs->free();

		$arrSi['s-id-user'] = $rowUs['id_usuario'];
		$arrSi['s-user'] = $rowUs['nombre'];
		$arrSi['s-email'] = $rowUs['email'];
		$arrSi['s-subsidiary'] = $rowUs['id_depto'];
		$arrSi['s-agency'] = $rowUs['id_agencia'];
	}
}
?>
<script type="text/javascript">
$(document).ready(function(e) {

	$("input[type='text'].fbin, textarea.fbin").keyup(function(e){
		var arr_key = new Array(37, 39, 8, 16, 32, 18, 17, 46, 36, 35, 186);
		var _val = $(this).prop('value');

		if($.inArray(e.keyCode, arr_key) < 0 && $(this).hasClass('email') === false){
			$(this).prop('value',_val.toUpperCase());
		}
	});


	$('input#rc-cl-exists').iCheck({
		checkboxClass: 'icheckbox_square-red',
		radioClass: 'iradio_square-red',
		increaseArea: '20%' // optional
	});

	//$("#rc-type-doc").attr("disabled", "disabled");
	//$("#rc-ext").attr("disabled", "disabled");

	$('input#rc-cl-exists').on('ifToggled', function(event){
		//alert(event.type + ' callback');
		$("#rc-npolicy").prop('value', '');
		$(".list-cl tbody").html('');
		if($("#rc-id-client + .msg-form").length)
			$("#rc-id-client + .msg-form").remove();
		if($("#rc-dni + .msg-form").length)
			$("#rc-dni + .msg-form").remove();
		if($("#rc-name + .msg-form").length)
			$("#rc-name + .msg-form").remove();
		$("#rc-dni").removeClass('error-text');
		$("#rc-name").removeClass('error-text');



		if($(this).is(':checked') === true){

			$("#rc-search").prop('readonly', true);
			$("#rc-type-doc option").not(":selected").css('display', '');
			$("#rc-dni").prop('readonly', false);
			$("#rc-ext option").not(":selected").css('display', '');
			$("#rc-name").prop('readonly', false);		$("#rc-name").addClass('required');
			$("#rc-patern").prop('readonly', false);		$("#rc-patern").addClass('required');
			$("#rc-matern").prop('readonly', false);
			$("#rc-married").prop('readonly', false);
			$(".btn-add-del").fadeIn();
			$("#rc-id-client").removeClass('required');
			$("#rc-dni").focus();
			$("#td-mark").hide();
		}else{
			$("#rc-search").prop('readonly', false);
			$("#rc-type-doc option").not(":selected").css('display', 'none');
			$("#rc-dni").prop('readonly', true);		$("#rc-dni").removeClass('required');
			$("#rc-ext option").not(":selected").css('display', 'none');
			$("#rc-name").prop('readonly', true);		$("#rc-name").removeClass('required');
			$("#rc-patern").prop('readonly', true);		$("#rc-patern").removeClass('required');
			$("#rc-matern").prop('readonly', true);
			$("#rc-married").prop('readonly', true);
			$(".btn-add-del").fadeOut();
			$("#rc-id-client").addClass('required');
			$("#rc-search").focus();
			$("#td-mark").show();
		}
	});

	$("#rc-search").focus();
<?php
if($flag === TRUE || $tokenSi === FALSE){
?>
	$(".date-sinister").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
		yearRange: "c-100:c+100"
	});

	$(".date-sinister").datepicker($.datepicker.regional[ "es" ]);

	$(".date-elaboration").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
		yearRange: "c-100:c+100"
	});

	$(".date-elaboration").datepicker($.datepicker.regional[ "es" ]);

<?php
}
?>
    $("#frc-report").validateForm({

		action: 'RC-report-record.php'
	});

	$("#rc-search").autocomplete({
		url: 'get-customer.php'
	});

	/*$("#rc-cl-exists").click(function(e){
		$("#rc-npolicy").prop('value', '');
		$(".list-cl tbody").html('');
		if($("#rc-id-client + .msg-form").length)
			$("#rc-id-client + .msg-form").remove();
		if($("#rc-dni + .msg-form").length)
			$("#rc-dni + .msg-form").remove();
		if($("#rc-name + .msg-form").length)
			$("#rc-name + .msg-form").remove();
		$("#rc-dni").removeClass('error-text');
		$("#rc-name").removeClass('error-text');

		if($(this).is(':checked') === true){
			$("#rc-search").prop('readonly', true);
			$("#rc-dni").prop('readonly', false);		$("#rc-dni").addClass('required');
			$("#rc-name").prop('readonly', false);		$("#rc-name").addClass('required');
			$(".btn-add-del").fadeIn();
			$("#rc-id-client").removeClass('required');
			$("#rc-dni").focus();
			$("#td-mark").hide();
		}else{
			$("#rc-search").prop('readonly', false);
			$("#rc-dni").prop('readonly', true);		$("#rc-dni").removeClass('required');
			$("#rc-name").prop('readonly', true);		$("#rc-name").removeClass('required');
			$(".btn-add-del").fadeOut();
			$("#rc-id-client").addClass('required');
			$("#rc-search").focus();
			$("#td-mark").show();
		}
	});*/

	$(".add-del").click(function(e){
		e.preventDefault();
		var rel = $(this).attr('rel');
		var _np = $("#rc-npolicy").prop('value');
		switch(rel){
			case 'add':
				$.getJSON("get-policy-autocomplete.php", {idCl: 0, np: _np}, function(result){
					$("#rc-npolicy").prop("value", result[0]);
					$(".list-cl tbody:last").append(result[1]);
				});
				break;
			case 'del':
				if(_np.length > 0){
					_np = _np.substring(0, _np.length - 2);
					$(".list-cl tbody tr:last").remove();
					$("#rc-npolicy").prop('value', _np);
				}
				break;
		}
	});

	$("#rc-edit").click(function(e){
		e.preventDefault();
		location.href = 'rc-report.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&rc=<?=base64_encode($idRc);?>&flag=<?=md5('rc-edit');?>';
	});

	$("#rc-type-doc option").not(":selected").css('display', 'none');
	$("#rc-ext option").not(":selected").css('display', 'none');
	$("#rc-type-doc option").not(":selected").css('display', 'none');
	$("#rc-ext option").not(":selected").css('display', 'none');
	//$("select.readonly option").not(":selected").attr("disabled", "disabled");
});
</script>
<script type="text/javascript">
	$(document).ready(function(e) {
		$(".sn-mark").click(function(e){
			var idcb = "#"+this.id;
			var nr = $(idcb).prop("value");
			var np = $("#rc-npolicy").prop("value");
			alert(idcb);
			if($(idcb).is(":checked") === true){
				alert("OK");
			}else{
				alert("Err");
			}
		});
	});
</script>
<h3><?=$title;?></h3>
<form id="frc-report" name="frc-report" class="form-quote form-customer">
	<label>Fecha de Registro: <span>*</span></label>
    <div class="content-input">
        <input type="text" id="rc-date-reg" name="rc-date-reg" autocomplete="off" value="<?=$arrSi['s-date-reg'];?>" class="required fbin" readonly  style="cursor:pointer;"  >
    </div><br>

    <div id="ctr-search" style=" <?=$display;?> ">
    	<label style="width:auto;">Nombre o C.I. del Cliente: <span>*</span></label>
        <div class="content-input" style="width:auto;">
            <input type="hidden" id="rc-id-client" name="rc-id-client" value="<?=base64_encode($arrSi['s-id-client']);?>" class="<?=$roCl;?>">
            <input type="text" id="rc-search" name="rc-search" autocomplete="off" value="" class="not-required fbin" style="width:350px;" >
        </div><br>

        <label style="width:auto; cursor: pointer; vertical-align:middle; font-size:95%;"><input type="checkbox" id="rc-cl-exists" name="rc-cl-exists" value="1"> Cliente no existe en el Sistema</label><br>
    </div>

	<label>Tipo de Documento Identidad: <span>*</span></label>
		<div class="content-input">
			<select id="rc-type-doc" name="rc-type-doc" class="required fbin <?=$readonly;?>">
				<option value="">Seleccione...</option>
<?php
$arr_type_doc = $link->typeDoc;
for($i = 0; $i < count($arr_type_doc); $i++){
	$type_doc = explode('|',$arr_type_doc[$i]);
	if($type_doc[0] === $arrSi['s-type-doc'])
		echo '<option value="'.$type_doc[0].'" selected>'.$type_doc[1].'</option>';
	else
		echo '<option value="'.$type_doc[0].'">'.$type_doc[1].'</option>';
}
?>
			</select>
	</div><br>

    <label>Documento Identidad: </label>
    <div class="content-input">
        <input type="text" id="rc-dni" name="rc-dni" autocomplete="off" value="<?=$arrSi['s-dni'];?>" class="dni fbin" readonly >
    </div>

    <label style="text-align:right;">Extensión: </label>
    <div class="content-input">

       	<select id="rc-ext" name="rc-ext" class="not-required fbin <?=$readonly;?><?=$readonly;?>">
        	<option value="">Seleccione...</option>
<?php
$rsDep = null;
if (($rsDep = $link->get_depto()) === FALSE) {
	$rsDep = null;
}
if ($rsDep->data_seek(0) === TRUE) {
	while($rowDep = $rsDep->fetch_array(MYSQLI_ASSOC)){
		if((boolean)$rowDep['id_depto'] === TRUE){
			if($rowDep['id_depto'] === $arrSi['s-ext'])
				echo '<option value="'.$rowDep['id_depto'].'" selected>'.$rowDep['departamento'].'</option>';
			else
				echo '<option value="'.$rowDep['id_depto'].'">'.$rowDep['departamento'].'</option>';
		}
	}
}


?>
		</select>
    </div><br>

    <label style="text-align:left;">Nombre: <span>*</span></label>
    <div class="content-input">
        <input type="text" id="rc-name" name="rc-name" autocomplete="off" value="<?=$arrSi['s-name'];?>" class="required fbin fbin" readonly >
    </div>

    <label style="text-align:right;">Apellido Paterno: <span>*</span></label>
    <div class="content-input">
        <input type="text" id="rc-patern" name="rc-patern" autocomplete="off" value="<?=$arrSi['s-patern'];?>" class="required fbin fbin" readonly >
    </div>
    <br>

    <label style="text-align:left;">Apellido Materno: </label>
    <div class="content-input">
        <input type="text" id="rc-matern" name="rc-matern" autocomplete="off" value="<?=$arrSi['s-matern'];?>" class="text fbin fbin" readonly >
    </div>

    <label style="text-align:right;">Apellido Casada: </label>
    <div class="content-input">
        <input type="text" id="rc-married" name="rc-married" autocomplete="off" value="<?=$arrSi['s-married'];?>" class="text fbin fbin" readonly >
    </div>
     <br>

<!--
    <label style="text-align:right;">Foto: <span>*</span></label>
    <div class="content-input">
        <input type="text" id="rc-image" name="rc-image" autocomplete="off" value="<?=$arrSi['s-image'];?>" class="text fbin" readonly >
    </div>

	<label style="text-align:right;">Foto: <span>*</span></label>
	<div class="content-input" style="width:auto;">
		<input type="file" id="file-import" name="file-import"  style="width:310px;" class="required text fbin"/>
		<a href="javascript:;" id="a-dc-attached" class="attached">Seleccione archivo</a>
		<div class="attached-mess" style="width:220px;">
			El formato del archivo a subir debe ser JPG
		</div>
		<script type="text/javascript">
			set_ajax_upload('dc-attached', '');
		</script>
	</div>
-->
    <label>Fecha del Siniestro:  <span>*</span></label>
    <div class="content-input">
        <input type="text" id="rc-date-sinister" name="rc-date-sinister" autocomplete="off" value="<?=$arrSi['s-date-sinister'];?>" class="required fbin date-sinister" readonly  style="cursor:pointer;" >
    </div><br>

    <!--
    <label style="text-align:left;">Tipo Evento: <span>*</span></label>
    <div class="content-input">
		<select id="rc-type-event" name="rc-type-event" class="required fbin">
            <option value="">Seleccione...</option>
<?php
/*
$rsEve = null;
if (($rsEve = $link->get_event_type()) === FALSE) {
	$rsEve = null;
}
if ($rsEve->data_seek(0) === TRUE) {
	while($rowEve = $rsEve->fetch_array(MYSQLI_ASSOC)){

		if($rowEve['id_te'] === 3412)
			echo '<option value="'.$rowEve['id_te'].'" selected>'.$rowEve['nombre'].'</option>';
		else
			echo '<option value="'.$rowEve['id_te'].'">'.$rowEve['nombre'].'</option>';

	}
}*/
?>
		</select>
    </div>

    <label style="text-align:right;">Lugar Evento: <span>*</span></label>
    <div class="content-input">
        <input type="text" id="rc-place-event" name="rc-place-event" autocomplete="off" value="<?=$arrSi['s-place-event'];?>" class="text fbin" readonly >
    </div><br>

-->
    <label>Circunstancias: <span></span></label>
    <textarea id="rc-circumstance" name="rc-circumstance" class="not-required fbin" <?=$readonly;?> ><?=$arrSi['s-circumstance'];?></textarea><br>
 <!--
	<label>Fecha de Denuncia: <span>*</span></label>
    <div class="content-input">
        <input type="text" id="rc-date-rep" name="rc-date-rep" autocomplete="off" value="<?=$arrSi['s-date-rep'];?>" class="required fbin date-rep" <?=$readonly;?> >
    </div><br>
-->

    <label style="text-align:left;">Persona Denuncia: <span>*</span></label>
    <div class="content-input">
        <input type="text" id="rc-rep-person" name="rc-rep-person" autocomplete="off" value="<?=$arrSi['s-rep-person'];?>" class="required fbin" <?=$readonly;?> >
    </div><br>

    <label style="text-align:left;">Persona Teléfono: </label>
    <div class="content-input">
        <input type="text" id="rc-rep-phone" name="rc-rep-phone" autocomplete="off" value="<?=$arrSi['s-rep-phone'];?>" class="not-required phone fbin" <?=$readonly;?> >
    </div>

    <label style="text-align:right;">Persona Email: </label>
    <div class="content-input">
        <input type="text" id="rc-rep-email" name="rc-rep-email" autocomplete="off" value="<?=$arrSi['s-rep-email'];?>" class="not-required email fbin" <?=$readonly;?> >
    </div><br>

    <label>Fecha de Elaboración: <span>*</span></label>
    <div class="content-input">
        <input type="text" id="rc-date-elaboration" name="rc-date-elaboration" autocomplete="off" value="<?=$arrSi['s-date-elaboration'];?>" class="required fbin date-elaboration" readonly  style="cursor:pointer;" >
    </div><br>

 <!--
    <label>Descripción del Hecho: <span></span></label>
    <textarea id="rc-description-fact" name="rc-description-fact" class="not-required fbin" <?=$readonly;?> ><?=$arrSi['s-description-fact'];?></textarea><br>
-->

    <label>Denunciado por: <span>*</span></label>
    <div class="content-input">
    	<input type="hidden" id="rc-denounced-id" name="rc-denounced-id" value="<?=base64_encode($arrSi['s-id-user']);?>">
        <input type="text" id="rc-denounced-name" name="rc-denounced-name" autocomplete="off" value="<?=$arrSi['s-user'];?>" class="required text fbin" readonly >
    </div><br>

	<label>Sucursal: <span>*</span></label>
    <div class="content-input">
    	<select id="rc-subsidiary" name="rc-subsidiary" class="required fbin <?=$readonly;?>">
        <!--	<option  style="<?=$display_select?>" value="">Seleccione...</option>-->
<?php
$rsSb = $link->get_depto();
if($rsSb->data_seek(0) === TRUE){
	while($rowSb = $rsSb->fetch_array(MYSQLI_ASSOC)){
		if((boolean)$rowSb['tipo_dp'] === TRUE){
			if($arrSi['s-subsidiary'] === $rowSb['id_depto'])
				echo '<option value="'.$rowSb['id_depto'].'" selected>'.$rowSb['departamento'].'</option>';
			//else
				//echo '<option style="'.$display_select.'" value="'.$rowSb['id_depto'].'">'.$rowSb['departamento'].'</option>';
		}
	}
}
?>
		</select>
	</div>

    <label style="text-align:right;">Agencia: <span>*</span></label>
    <div class="content-input">
    	<select id="rc-agency" name="rc-agency" class="fbin <?=$readonly;?>">
          <!--	<option style="<?=$display_select?>" value="">Seleccione...</option>-->
<?php
$sqlAg = 'SELECT sa.id_agencia, sa.agencia
		FROM s_agencia as sa
			INNER JOIN s_entidad_financiera as sef ON (sef.id_ef = sa.id_ef)
		WHERE sa.id_agencia != ""
			AND sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
		ORDER BY sa.id_agencia ASC ;';
if(($rsAg = $link->query($sqlAg,MYSQLI_STORE_RESULT))){
	if($rsAg->num_rows > 0){
		while($rowAg = $rsAg->fetch_array(MYSQLI_ASSOC)){
			if($arrSi['s-agency'] === $rowAg['id_agencia'])
				echo '<option value="'.base64_encode($rowAg['id_agencia']).'" selected>'.$rowAg['agencia'].'</option>';
			//else
			//	echo '<option style="'.$display_select.'" value="'.base64_encode($rowAg['id_agencia']).'">'.$rowAg['agencia'].'</option>';
		}
	}
}
?>
		</select>
	</div>
    <br><br>

    <h4 class="h4">
	    <table style="width:100%;">
            <tr>
                <td style="width:30%;">Datos del Crédito</td>
                <td style="width:70%;">
                	<div class="btn-add-del" style="text-align:right;">
                        <a href="#" class="add-del" title="Adicionar" style="background-image:url(img/add-icon.png);" rel="add">Adicionar</a>
                        <a href="#" class="add-del" title="Eliminar" style="background-image:url(img/remove-icon.png);" rel="del">Eliminar</a>
                    </div>
                </td>
            </tr>
        </table>
	</h4>
    <div id="content-policy">
    	<table class="list-cl">
			<thead>
				<tr>
<?php
if($tokenSi === FALSE)	echo '<td style="width:8%;" id="td-mark">Marcar</td>';
?>
				<!--<td style="width:10%;">No. Certificado</td>
					<td style="width:10%;">No. Póliza</td>
					<td style="width:17%;">Ramo</td>
					<td style="width:10%;">No. Operación</td>
					<td style="width:15%;">Plazo del Crédito</td>
					<td style="width:10%;">Fecha desembolso</td>
					<td style="width:20%;">Saldo Adeudado</td>
				-->
					<td style="width:10%;">Tipo de Crédito</td>
					<td style="width:10%;">Saldo a Capital*</td>
					<td style="width:17%;">Nº de crédito</td>
					<td style="width:10%;">Moneda</td>

				</tr>
			</thead>
            <tbody>
<?php
if($tokenSi === TRUE){
	$sqlSd = 'select
			ssi.id_siniestro as idRc,
			ssd.id_detalle as idRd,
			ssd.token as d_token,
			ssd.id_emision as ide,
			ssd.no_emision as d_no_emision,
			ssd.tipo_credito as d_tipo_credito,
			ssd.no_credito as d_no_credito,
			ssd.monto_desembolso as d_monto_desembolso,
			ssd.moneda as d_moneda
		from
			s_siniestro_detalle as ssd
				inner join
			s_siniestro as ssi ON (ssi.id_siniestro = ssd.id_siniestro)
		where
			ssi.id_siniestro = "'.$idRc.'"
		order by ssd.id_detalle asc
		;';

	if(($rsSd = $link->query($sqlSd,MYSQLI_STORE_RESULT))){
		if($rsSd->num_rows > 0){
			/*$arr_Product = array(
					0 => 'DE|Desgravamen',
					1 => 'AU|Automotores',
					2 => 'TRD|Todo Riesgo Domiciliario',
					3 => 'TRM|Todo Riesgo Equipo Movil',
					4 => 'CCB|Desgravamen',
					5 => 'CCD|Desgravamen',
					6 => 'CDB|Desgravamen',
					7 => 'CDD|Desgravamen',
					8 => 'VG|Desgravamen');
			$arr_Term = array(0 => 'Y|Años', 1 => 'M|Meses', 2 => 'W|Semanas', 3 => 'D|Días');*/
			$arr_Currency = array(0 => 'BS|Bolivianos', 1 => 'USD|Dolares');
			$arr_Loan = array(
					0 => 'PEBBCC|Prestamo Externo BBCC',
					1 => 'PIBBCC|Prestamo Interno BBCC',
					2 => 'CO|Credito Oportuno',
					3 => 'CI|Crédito Individual');
			while($rowSd = $rsSd->fetch_array(MYSQLI_ASSOC)){
				$k = $rowSd['d_token'];
				$kk .= $rowSd['d_token'].'|';
?>
<tr>

    <!--
    <td>
        <input type="text" id="rc-<?=$k;?>-npolicy" name="rc-<?=$k;?>-npolicy" autocomplete="off" value="<?=$rowSd['d_no_poliza'];?>" class="required fbin" <?=$readonly;?>>
    </td>
-->
    <td>



<!--
        <select id="rc-<?=$k;?>-product" name="rc-<?=$k;?>-product" class="required fbin">
<?php
				if($readonly === '')
					echo '<option value="">Seleccione...</option>';
				for($i = 0; $i < count($arr_Product); $i++){
					$PR = explode('|', $arr_Product[$i]);
					if($rowSd['d_producto'] === $PR[0])
						echo '<option value="'.$PR[0].'" selected>'.$PR[1].'</option>';
					elseif($readonly === '')
						echo '<option value="'.$PR[0].'">'.$PR[1].'</option>';
				}
?>
        </select>
-->


<?php
				if($flag === TRUE)
					echo '<input type="hidden" id="rc-'.$k.'-idd" name="rc-'.$k.'-idd" value="'.base64_encode($rowSd['idRd']).'">'
?>
    	<input type="hidden" id="rc-<?=$k;?>-ide" name="rc-<?=$k;?>-ide" value="<?=base64_encode($rowSd['ide']);?>">
        <input type="hidden" id="rc-<?=$k;?>-ncertified" name="rc-<?=$k;?>-ncertified" autocomplete="off" value="<?=$rowSd['d_no_emision'];?>" class="required fbin" <?=$readonly;?>>


 		<select id="rc-<?=$k;?>-loan-type" name="rc-<?=$k;?>-loan-type" class="required fbin">

<?php
				if($readonly === '')
					echo '<option value="">Seleccione...</option>';
				for($i = 0; $i < count($arr_Loan); $i++){
					$CURR = explode('|', $arr_Loan[$i]);
					if($rowSd['d_tipo_credito'] === $CURR[0])
						echo '<option value="'.$CURR[0].'" selected>'.$CURR[1].'</option>';
					elseif($readonly === '')
						echo '<option value="'.$CURR[0].'">'.$CURR[1].'</option>';
				}
?>
        </select>

    </td>

 	<td>
         <input type="text" id="rc-<?=$k;?>-amount" name="rc-<?=$k;?>-amount"
        	autocomplete="off" value="<?=(float)$rowSd['d_monto_desembolso'];?>"
        	class="required amount-ce fbin" <?=$readonly;?>>
    </td>

 	<td>
         <input type="text" id="rc-<?=$k;?>-nocredit" name="rc-<?=$k;?>-nocredit"
        	autocomplete="off" value="<?=$rowSd['d_no_credito'];?>"
        	class="required nocredit-ce fbin" <?=$readonly;?>>
    </td>

    <td>

        <select id="rc-<?=$k;?>-amount-type" name="rc-<?=$k;?>-amount-type" class="required fbin">
<?php
				if($readonly === '')
					echo '<option value="">Seleccione...</option>';
				for($i = 0; $i < count($arr_Currency); $i++){
					$CURR = explode('|', $arr_Currency[$i]);
					if($rowSd['d_moneda'] === $CURR[0])
						echo '<option value="'.$CURR[0].'" selected>'.$CURR[1].'</option>';
					elseif($readonly === '')
						echo '<option value="'.$CURR[0].'">'.$CURR[1].'</option>';
				}
?>
        </select>
    </td>
</tr>
<?php
			}
		}
	}
}
?>
            </tbody>
		</table>
        <div id="content-script"></div>
    </div>

    <div style="text-align:center;">
    	<!--<input type="hidden" id="rc-npolicy" name="rc-npolicy" value="<?=$kk;?>" >-->
        <input type="hidden" id="rc-npolicy" name="rc-npolicy" value="<?=$kk;?>" class="required" >
        <input type="hidden" id="idef" name="idef" value="<?=$_SESSION['idEF'];?>" >
        <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>" >
        <input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>" >
<?php
if($tokenSi === TRUE){
	echo '<input type="hidden" id="idRc" name="idRc" value="'.base64_encode($idRc).'" >';
	if($flag === FALSE)
		//echo '<input type="button" id="rc-edit" name="rc-edit" value="Editar Siniestro" class="btn-next btn-issue" >
		//<a href="rc-send-sinister.php?rc='.base64_encode($idRc).'" class="fancybox fancybox.ajax btn-issue">Enviar y Salir</a>';
		echo '<input type="button" id="rc-edit" name="rc-edit" value="Editar Siniestro" class="btn-next btn-issue" >
		<a href="RC-certificate-detail.php?idRc='.base64_encode($idRc).'&type='.base64_encode('PRINT').'&category='.base64_encode('RC').'" class="fancybox fancybox.ajax btn-issue">Ver Detalle</a>
		<a href="rc-report.php?ms='.md5('MS_RC').'&page='.md5('P_records').'" class="btn-issue" >Cerrar</a>
		';
}


if($tokenSi === FALSE || $flag === TRUE){
	echo '<input type="submit" id="rc-report" name="rc-report" value="'.$btnText.'" class="btn-next btn-issue" >';
}
?>
    </div>
    <div class="loading">
		<img src="img/loading-01.gif" width="35" height="35" />
	</div>
</form>

<!--
	certificate-detail.php?idc=<?=base64_encode($rowCia['id_cotizacion']);?>&cia=<?=base64_encode($rowCia['idcia']);?>&pr=<?=base64_encode('DE');?>&type=<?=base64_encode('PRINT');?>

-->