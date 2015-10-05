<?php

require __DIR__ . '/app/controllers/DiaconiaController.php';
require __DIR__ . '/app/controllers/ClientController.php';
require __DIR__ . '/app/controllers/WsController.php';

$Diaconia = new DiaconiaController();
$ClientController = new ClientController();

$bc = false;
if ($Diaconia->checkBancaComunal($_GET['idc'])) {
  $bc = true;
}

$data_pr  = array();
$max_item   = $Diaconia->getMaxItem($_GET['idc'], $_SESSION['idEF']);
$depto    = $ClientController->getDepto();
$data_list  = array();

$swCl = false;

$arr_cl = array();
$temp_data = array(
  'code' => '',
  'name' => '',
  'patern' => '',
  'matern' => '',
  'married' => '',
  'status' => '',
  'type_doc' => '',
  'doc_id' => '',
  'comp' => '',
  'ext' => '',
  'country' => '',
  'birth' => '',
  'place_birth' => '',
  'place_res' => '',
  'locality' => '',
  'address' => '',
  'phone_1' => '',
  'phone_2' => '',
  'email' => '',
  'phone_office' => '',
  'occupation' => '',
  'occ_desc' => '',
  'gender' => '',
  'weight' => '',
  'height' => '',
  'amount' => 0,
  'amount_bc' => '',
  );

$arr_cl[0] = $temp_data;

$title_btn = 'Agregar Titular';
$err_search = '';
$web_service = false;

if (($data_pr = $Diaconia->getDataProduct($_SESSION['idEF'])) !== false) {
  $web_service = (boolean)$data_pr['ws'];
}

if(isset($_POST['dsc-dni'])){
  $dni = $_POST['dsc-dni'];

  $WsController = new WsController($_SESSION['idEF'], $web_service, $bc, $dni);
  $data_ws = $WsController->getClientData($arr_cl);
  if ($data_ws['status'] !== 200) {
      $err_search = $data_ws['error'];
    }
}

if (isset($_GET['idCl'])) {
  $swCl = true;
  $title_btn = 'Actualizar datos';
  
  if (($rowUp = $ClientController->getClient($_GET['idc'], $_SESSION['idEF'], $_GET['idCl'])) !== false) {
    $temp_data['code']    = '';
    $temp_data['name']    = $rowUp['nombre'];
    $temp_data['patern']  = $rowUp['paterno'];
    $temp_data['matern']  = $rowUp['materno'];
    $temp_data['married']   = $rowUp['ap_casada'];
    $temp_data['status']  = $rowUp['estado_civil'];
    $temp_data['type_doc']  = $rowUp['tipo_documento'];
    $temp_data['doc_id']  = $rowUp['ci'];
    $temp_data['comp']    = $rowUp['complemento'];
    $temp_data['ext']     = $rowUp['extension'];
    $temp_data['country']   = $rowUp['pais'];
    $temp_data['birth']   = $rowUp['fecha_nacimiento'];
    $temp_data['place_birth'] = $rowUp['lugar_nacimiento'];
    $temp_data['place_res'] = $rowUp['lugar_residencia'];
    $temp_data['locality']  = $rowUp['localidad'];
    $temp_data['address']   = $rowUp['direccion'];
    $temp_data['phone_1']   = $rowUp['telefono_domicilio'];
    $temp_data['phone_2']   = $rowUp['telefono_celular'];
    $temp_data['email']   = $rowUp['email'];
    $temp_data['phone_office'] = $rowUp['telefono_oficina'];
    $temp_data['occupation'] = $rowUp['id_ocupacion'];
    $temp_data['occ_desc']  = $rowUp['desc_ocupacion'];
    $temp_data['gender']  = $rowUp['genero'];
    $temp_data['weight']  = $rowUp['peso'];
    $temp_data['height']  = $rowUp['estatura'];
    $temp_data['amount']  = $rowUp['cl_saldo'];
    $temp_data['amount_bc'] = $rowUp['cl_monto_bc'];

    $arr_cl[0] = $temp_data;
  }
}
?>

<h3>Datos del Titular</h3>

<?php
$nCl = 0;

if($swCl === false){
  $data_list = $ClientController->getListClient($_GET['idc'], $_SESSION['idEF'], $max_item);
  $nCl = count($data_list);

  if ($nCl < $max_item) {
?>
<?php if ($token && isset($_SESSION['idUser'])): ?>
<form id="fde-sc" name="fde-sc" action="" method="post" class="form-quote search-customer">
  <label>Nro. Solicitud/Operación: <span>*</span></label>
  <div class="content-input" style="width:auto;">
    <input type="text" id="dsc-dni" name="dsc-dni" autocomplete="off" 
      value="" style="width:120px;" class="required text fbin">
  </div>
  <input type="submit" id="dsc-sc" name="dsc-sc" value="Buscar Titular" 
    class="btn-search-cs">
    <div class="mess-err-sc"><?=$err_search;?></div>
</form>
<hr>
<?php endif ?>
<?php
  }
}
?>

<form id="fde-customer" name="fde-customer" action="" method="post" class="form-quote form-customer">
<?php
if($swCl === false){
  if($nCl > 0){
  ?>
    <table class="list-cl">
      <thead>
        <tr>
          <td style="width:5%;"></td>
          <td style="width:10%;">Documento de Identidad</td>
          <td style="width:15%;">Nombres</td>
          <td style="width:16%;">Ap. Paterno</td>
          <td style="width:16%;">Ap. Materno</td>
          <td style="width:10%;">Fecha de Nacimiento</td>
          <td style="width:11%;">Genero</td>
          <td style="width:5%;">% Crédito</td>
          <td style="width:12%;"></td>
        </tr>
      </thead>
      <tbody>
      <?php $cont = 1; foreach ($data_list as $key => $rowCl): ?>
        <tr>
          <td style="font-weight:bold;">T<?=$cont;?></td>
          <td><?=$rowCl['cl_dni'];?></td>
          <td><?=$rowCl['cl_nombre'];?></td>
          <td><?=$rowCl['cl_paterno'];?></td>
          <td><?=$rowCl['cl_materno'];?></td>
          <td><?=$rowCl['cl_fn'];?></td>
          <td><?=$rowCl['cl_genero'];?></td>
          <td><?=$rowCl['cl_pc'];?></td>
          <td><a href="de-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=$_GET['pr'];?>&idc=<?=$_GET['idc'];?>&idCl=<?=base64_encode($rowCl['id_cliente']);?>" title="Editar Información"><img src="img/edit-user-icon.png" width="40" height="40" alt="Editar Información" title="Editar Información"></a></td>
        </tr>
      <?php 
          $cont++;
         endforeach 
       ?>
      </tbody>
    </table>
    
    <div class="mess-cl">
    <?php for ($i = 1; $i <= $nCl; $i++): ?>
      T <?= $i ;?>: Titular <?= $i ;?><br>
    <?php endfor ?>

    </div>
    <input type="button" id="dc-next" name="dc-next" value="Continuar" class="btn-next" >
    <hr>
  <?php
  }
}

if($nCl < $max_item || $swCl === true){
  $client = count($arr_cl);
?>
  <p style="margin: 10px 0; font-size: 80%; font-style: italic; 
    text-align: center; font-weight: bold; color: #037984; ">
    "Los campos marcados con asterisco <span style="color: #f00;" >(*)</span> son obligatorios".
  </p>

<?php
  $k = 0;
  foreach ($arr_cl as $key => $data) {
?>
  <div id="form-<?= $k ;?>" style="border: 1px solid #e2e2e2;">
    <div style="text-align: right;">
      <a href="#" class="form-remove" data-number="<?= $k ;?>" title="Eliminar">X</a>
    </div>

    <div class="form-col">
      <label>Nombres: <span>*</span></label>
      <div class="content-input">
        <input type="text" id="dc-name-<?=$k?>" name="dc-name-<?=$k?>" autocomplete="off" 
          value="<?=$data['name'];?>" class="required text fbin">
      </div><br>    
      
      <label>Apellido Paterno: <span>*</span></label>
      <div class="content-input">
        <input type="text" id="dc-ln-patern-<?=$k?>" name="dc-ln-patern-<?=$k?>" 
          autocomplete="off" value="<?=$data['patern'];?>" class="required text fbin">
      </div><br>
      
      <label>Apellido Materno: </label>
      <div class="content-input">
        <input type="text" id="dc-ln-matern-<?=$k?>" name="dc-ln-matern-<?=$k?>" 
          autocomplete="off" value="<?=$data['matern'];?>" class="not-required text fbin">
      </div><br>
      
      <label>Apellido de Casada: </label>
      <div class="content-input">
        <input type="text" id="dc-ln-married-<?=$k?>" name="dc-ln-married-<?=$k?>" 
          autocomplete="off" value="<?=$data['married'];?>" class="not-required text fbin">
      </div><br>

      <label>Estado Civil: <span>*</span></label>
      <div class="content-input">
        <select id="dc-status-<?=$k?>" name="dc-status-<?=$k?>" class="required fbin">
                <option value="">Seleccione...</option>
            <?php foreach ($ClientController->getStatus() as $key => $value): $selected = '' ?>
              <?php if ($key === $data['status']): $selected = 'selected' ?>
              <?php endif ?>
            <option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
            <?php endforeach ?>
        </select>
      </div><br>

      <label>Tipo de Documento: <span>*</span></label>
      <div class="content-input">
        <select id="dc-type-doc-<?=$k?>" name="dc-type-doc-<?=$k?>" class="required fbin">
          <option value="">Seleccione...</option>
          <?php foreach ($ClientController->getTypeDoc() as $key => $value): $selected = '' ?>
            <?php if ($key === $data['type_doc']): $selected = 'selected' ?>
              <?php endif ?>
            <option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
          <?php endforeach ?>
        </select>
      </div><br>
      
      <label>Documento de Identidad: <span>*</span></label>
      <div class="content-input">
        <input type="text" id="dc-doc-id-<?=$k?>" name="dc-doc-id-<?=$k?>" 
          autocomplete="off" value="<?=$data['doc_id'];?>" class="required dni fbin">
      </div><br>
      
      <label>Complemento: </label>
      <div class="content-input">
        <input type="text" id="dc-comp-<?=$k?>" name="dc-comp-<?=$k?>" 
          autocomplete="off" value="<?=$data['comp'];?>" class="not-required dni fbin" 
          style="width:60px;">
      </div><br>
      
      <label>Extensión: <span>*</span></label>
      <div class="content-input">
        <select id="dc-ext-<?=$k?>" name="dc-ext-<?=$k?>" class="required fbin">
                <option value="">Seleccione...</option>
                <?php foreach ($depto as $key => $value): $selected = '' ?>
                  <?php if ((boolean)$value['tipo_ci'] === true): ?>
                    <?php if ($value['id_depto'] === $data['ext']): $selected = 'selected' ?>
                <?php endif ?>
              <option value="<?= $value['id_depto'] ;?>" 
                <?= $selected ;?>><?= $value['departamento'] ;?></option>
                  <?php endif ?>
                <?php endforeach ?>
        </select>
      </div><br>
      
      <label>Fecha de Nacimiento: <span>*</span></label>
      <div class="content-input">
        <input type="text" id="dc-date-birth-<?=$k?>" name="dc-date-birth-<?=$k?>" 
          autocomplete="off" value="<?=$data['birth'];?>" class="required fbin dc-date-birth" 
          readonly style="cursor:pointer;">
      </div><br>
      
      <label>País: <span>*</span></label>
      <div class="content-input">
        <input type="text" id="dc-country-<?=$k?>" name="dc-country-<?=$k?>" 
          autocomplete="off" value="<?=$data['country'];?>" class="required text fbin">
      </div><br>
      
      <label>Lugar de Nacimiento: <span></span></label>
      <div class="content-input">
        <input type="text" id="dc-place-birth-<?=$k?>" name="dc-place-birth-<?=$k?>" 
          autocomplete="off" value="<?=$data['place_birth'];?>" class="not-required fbin">
      </div><br>
      
      <label>Lugar de Residencia: <span></span></label>
      <div class="content-input">
        <select id="dc-place-res-<?=$k?>" name="dc-place-res-<?=$k?>" class="not-required fbin">
          <option value="">Seleccione...</option>
          <?php foreach ($depto as $key => $value): $selected = '' ?>
            <?php if ((boolean)$value['tipo_dp'] === true): ?>
                    <?php if ($value['id_depto'] === $data['place_res']): $selected = 'selected' ?>
                <?php endif ?>
              <option value="<?= $value['id_depto'] ;?>" 
                <?= $selected ;?>><?= $value['departamento'] ;?></option>
            <?php endif ?>
                <?php endforeach ?>
        </select>
      </div><br>
      
      <label>Localidad: <span></span></label>
      <div class="content-input">
        <input type="text" id="dc-locality-<?=$k?>" name="dc-locality-<?=$k?>" 
          autocomplete="off" value="<?=$data['locality'];?>" class="not-required text-2 fbin">
      </div><br>
    </div><!--
    --><div class="form-col">
      <label>Dirección: <span>*</span></label><br>
      <textarea id="dc-address-<?=$k?>" name="dc-address-<?=$k?>" 
        class="required fbin"><?=$data['address'];?></textarea><br>
      
      <label>Teléfono 1: <span>*</span></label>
      <div class="content-input">
        <input type="text" id="dc-phone1-<?=$k?>" name="dc-phone1-<?=$k?>" 
          autocomplete="off" value="<?=$data['phone_1'];?>" class="required phone fbin">
      </div><br>
      
      <label>Teléfono 2: </label>
      <div class="content-input">
        <input type="text" id="dc-phone2-<?=$k?>" name="dc-phone2-<?=$k?>" 
          autocomplete="off" value="<?=$data['phone_2'];?>" class="not-required phone fbin">
      </div><br>
      
      <label>Teléfono oficina: </label>
      <div class="content-input">
        <input type="text" id="dc-phone-office-<?=$k?>" name="dc-phone-office-<?=$k?>" 
          autocomplete="off" value="<?=$data['phone_office'];?>" class="not-required phone fbin">
      </div><br>
      
      <label>Email: </label>
      <div class="content-input">
        <input type="text" id="dc-email-<?=$k?>" name="dc-email-<?=$k?>" 
          autocomplete="off" value="<?=$data['email'];?>" class="not-required email fbin">
      </div><br>
      
      <label>Ocupación (CAEDEC): <span>*</span></label>
      <div class="content-input">
        <select id="dc-occupation-<?=$k?>" name="dc-occupation-<?=$k?>" class="required fbin occupation">
          <option value="">Seleccione...</option>
          <?php foreach ($ClientController->getOccupation($_SESSION['idEF']) as $key => $value):
            $selected = '' ?>
            <?php if ($value['id_ocupacion'] === $data['occupation']): $selected = 'selected' ?>
              <?php endif ?>
            <option value="<?= base64_encode($value['id_ocupacion']) ;?>" 
              <?= $selected ;?> data-desc="<?= trim($value['ocupacion']) ;?>" 
              data-number=<?= $k ;?>>Código CAEDEC - <?= $value['codigo'] ;?></option>
          <?php endforeach ?>
        </select>
      </div><br>
      
      <label style="width:auto;">Descripción Ocupación: <span>*</span></label><br>
      <textarea id="dc-desc-occ-<?=$k?>" name="dc-desc-occ-<?=$k?>" 
        class="required fbin"><?=$data['occ_desc'];?></textarea><br>
      
      <label>Género: <span>*</span></label>
      <div class="content-input">
        <select id="dc-gender-<?=$k?>" name="dc-gender-<?=$k?>" class="required fbin">
          <option value="">Seleccione...</option>
          <?php foreach ($ClientController->getGender() as $key => $value): $selected = '' ?>
            <?php if ($key === $data['gender']): $selected = 'selected' ?>
              <?php endif ?>
            <option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
          <?php endforeach ?>
        </select>
      </div><br>
      
      <label>Peso (kg): <span>*</span></label>
      <div class="content-input">
        <input type="text" id="dc-weight-<?=$k?>" name="dc-weight-<?=$k?>" 
          autocomplete="off" value="<?=$data['weight'];?>" class="required wh fbin">
      </div><br>
      
      <label>Estatura (cm): <span>*</span></label>
      <div class="content-input">
        <input type="text" id="dc-height-<?=$k?>" name="dc-height-<?=$k?>" 
          autocomplete="off" value="<?=$data['height'];?>" class="required wh fbin">

              <input type="hidden" id="dc-amount-<?=$k?>" name="dc-amount-<?=$k?>" 
                value="<?=base64_encode($data['amount']);?>">
      </div><br>
      <?php if ($bc): ?>
      <label>Monto<br>Banca Comunal: <span>*</span></label>
      <div class="content-input">
        <input type="text" id="dc-amount-bc-<?=$k?>" name="dc-amount-bc-<?=$k?>" 
          autocomplete="off" value="<?=$data['amount_bc'];?>" class="required real fbin">
      </div><br>
      <?php endif ?>
    </div>
    <hr>
    
  </div>
<?php
    $k += 1;
  }
?>  
    <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
    <input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
    <input type="hidden" id="pr" name="pr" value="<?=base64_encode('DE|02');?>">
    <input type="hidden" id="dc-idc" name="dc-idc" value="<?=$_GET['idc'];?>" >
    <input type="hidden" id="dc-token" name="dc-token" value="<?=base64_encode('dc-OK');?>" >
    <input type="hidden" id="id-ef" name="id-ef" value="<?=$_SESSION['idEF'];?>" >
    <input type="hidden" id="dc-bc" name="dc-bc" value="<?=base64_encode((int)$bc);?>">
    <input type="hidden" id="dc-ncl" name="dc-ncl" value="<?=base64_encode($client);?>">
    <input type="hidden" id="dc-record" name="dc-record" value="<?= $client ;?>">
    <?php if ($swCl): ?>
    <input type="hidden" id="dc-idCl" name="dc-idCl" value="<?= $_GET['idCl'] ;?>" >
    <?php endif ?>
    <input type="submit" id="dc-customer" name="dc-customer" value="<?=$title_btn;?>" class="btn-next" >
<?php
}
?>  
  <div class="loading">
    <img src="img/loading-01.gif" width="35" height="35" />
  </div>
</form>

<script type="text/javascript">
$(document).ready(function(e) {
  $("#fde-customer").validateForm({
    action: 'DE-customer-record.php'
  });
  
  $(".dc-date-birth").datepicker({
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'yy-mm-dd',
    yearRange: "c-100:c+100"
  });
  
  $(".dc-date-birth").datepicker($.datepicker.regional[ "es" ]);
  
  $("#dc-next").click(function(e){
    e.preventDefault();
    location.href = 'de-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=base64_encode('DE|03');?>&idc=<?=$_GET['idc']?>';
  });
  
  $("input[type='text'].fbin, textarea.fbin").keyup(function(e){
    var arr_key = new Array(37, 39, 8, 16, 32, 18, 17, 46, 36, 35, 186);
    var _val = $(this).prop('value');
    
    if($.inArray(e.keyCode, arr_key) < 0 && $(this).hasClass('email') === false){
      $(this).prop('value',_val.toUpperCase());
    }
  });

  $('.occupation').change(function (e) {
    var selected = $(this).find('option:selected');

    var desc = $(selected).data('desc');
    var number = $(selected).data('number');
    
    $('#dc-desc-occ-' + number).prop('value', desc);
  });

  $('.form-remove').click(function (e) {
    e.preventDefault();

    var number  = $(this).data('number');
    var form    = $('#form-' + number);
    var n_cl    = parseInt($('#dc-record').prop('value'));

    n_cl = n_cl - 1;
    $('#dc-record').prop('value', n_cl);

    form.fadeOut('slow', function (e) {
      form.remove();
    });
  });
});
</script>