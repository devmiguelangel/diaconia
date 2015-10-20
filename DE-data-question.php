<h3>Seguro de Desgravamen - Cuestionario</h3>
<?php

require __DIR__ . '/app/controllers/DiaconiaController.php';
require __DIR__ . '/app/controllers/QuestionController.php';
require __DIR__ . '/app/controllers/ClientController.php';

$Diaconia = new DiaconiaController();
$QuestionController = new QuestionController();
$ClientController = new ClientController();

if (($questions = $QuestionController->getQuestion($_SESSION['idEF'])) !== false) {
  if (($data = $Diaconia->getDataProduct($_SESSION['idEF'])) !== false) {
    $clients = $ClientController->getListClient($_GET['idc'], $_SESSION['idEF'], $data['max_detalle']);

    if (count($clients) > 0) {
      $cont = 0;
?>
<form id="fde-question" name="fde-question" action="" method="post" class="form-quote form-question">
<?php foreach ($clients as $key => $client): 
  $cont += 1;
  $res = '';
  ?>
  <h4>Titular <?= $cont ;?> - <?= $client['cl_name'] ;?></h4>
  <?php foreach ($questions as $key => $question): 
    $class_yes = $class_no = $check_yes = $check_no = $disabled_yes = $disabled_no = '';

    if ($question['respuesta'] == 0) {
      $class_no = 'class="required"';
      $res .= $question['orden'] . ', ';
      if ($client['monto'] <= ($client['valor_boliviano'] * 15000)) {
          $check_no = 'checked';
      }
    } elseif ($question['respuesta'] == 1) {
      $class_yes = 'class="required"';
      if ($client['monto'] <= ($client['valor_boliviano'] * 15000)) {
          $check_yes = 'checked';
      }
    }
  ?>
    <div class="question">
      <span class="qs-no"><?=$question['orden'];?></span>
      <p class="qs-title"><?=$question['pregunta'];?></p>
      <div class="qs-option">
              <label class="check">SI&nbsp;&nbsp;
                  <input type="radio" id="dq-qs<?=$cont;?>-<?=$question['id_pregunta'];?>1"
                         name="dq-qs-<?=$cont;?>-<?=$question['id_pregunta'];?>" value="1"
                         <?=$class_yes . ' ' . $check_yes. ' ' . $disabled_yes;?>></label>
              <label class="check">NO&nbsp;&nbsp;
                  <input type="radio" id="dq-qs<?=$cont;?>-<?=$question['id_pregunta'];?>2"
                         name="dq-qs-<?=$cont;?>-<?=$question['id_pregunta'];?>" value="0"
                         <?=$class_no . ' ' . $check_no . ' ' . $disabled_no;?>></label>
      </div>
    </div>
  <?php endforeach ?>
  <input type="hidden" id="dq-idd-<?=$cont;?>" name="dq-idd-<?=$cont;?>" 
    value="<?=base64_encode($client['id_detalle']);?>">
  <span style="display:block; font-size:85%; font-weight:bold; 
    color:#408080; text-align:center;">
    Si alguna de sus respuestas [ <?=trim(trim($res),',');?> ] es afirmativa, 
    sirvase brindar detalles:
  </span>
  <textarea id="dq-resp-<?=$cont;?>" name="dq-resp-<?=$cont;?>" 
    style="display:block; width:600px; height:100px; margin:4px auto 18px auto;" 
    class="fbin"></textarea>
<?php endforeach ?>
  <div class="loading">
    <img src="img/loading-01.gif" width="35" height="35" />
  </div>
  
  <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
  <input type="hidden" id="n_cl" name="n_cl" value="<?= count($clients) ;?>">
  <input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
  <input type="hidden" id="pr" name="pr" value="<?=base64_encode('DE|03');?>">
  <input type="hidden" id="dq-idc" name="dq-idc" value="<?=$_GET['idc'];?>" >
    <input type="hidden" id="dq-idef" name="dq-idef" value="<?=$_SESSION['idEF'];?>">
  
  <input type="submit" id="dc-customer" name="dc-customer" value="Agregar Respuestas" class="btn-next" >
</form>
<?php
    } else {
      echo 'No existen Titulares';
    }
  } else {
    echo 'No existen Preguntas';
  }
} else {
  echo 'No existen Preguntas.';
}
?>

<script type="text/javascript">
$(document).ready(function(e) {
  $("#fde-question").validateForm({
    action: 'DE-question-record.php',
    qs: true
  });
  
  $('input').iCheck({
    checkboxClass: 'icheckbox_flat',
    radioClass: 'iradio_flat'
  });
});
</script>