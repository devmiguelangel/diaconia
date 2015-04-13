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
<h3>Seguro de Desgravamen - Cuestionario</h3>
<p style="margin: 10px 0; font-size: 80%; font-style: italic; 
	text-align: center; font-weight: bold; color: #037984; ">
	"En caso que el cúmulo de la operación supere 15,000 USD, modificar las respuestas según corresponda".
</p>
<?php
require_once('sibas-db.class.php');
$link = new SibasDB();

if (($rsQs = $link->get_question($_SESSION['idEF'])) !== FALSE) {
	$sqlCl = 'select
        sdd.id_detalle,
		sdd.id_cliente,
		sdd.titular,
		concat(scl.nombre,
				" ",
				scl.paterno,
				" ",
				scl.materno) as titular,
		tc.valor_boliviano,
		(if(sdc.moneda = "BS",
		    sdc.monto,
		    (sdc.monto * tc.valor_boliviano))) as monto
	from
		s_de_cot_detalle as sdd
			inner join
		s_de_cot_cabecera as sdc ON (sdc.id_cotizacion = sdd.id_cotizacion)
			inner join
		s_de_cot_cliente as scl ON (scl.id_cliente = sdd.id_cliente)
			inner join
		s_tipo_cambio as tc ON (tc.id_ef = sdc.id_ef)
	where
		sdd.id_cotizacion = "'.base64_decode($_GET['idc']).'"
			and sdc.id_ef = "'.base64_decode($_SESSION['idEF']).'"
	order by sdd.id_detalle ASC
	;';
	
	$max_item = 0;
	if (($rowDE = $link->get_max_amount_optional($_SESSION['idEF'], 'DE')) !== FALSE) {
		$max_item = (int)$rowDE['max_item'];
	}
	
	if (($rsCl = $link->query($sqlCl,MYSQLI_STORE_RESULT))) {
		$numCl = $rsCl->num_rows;
		
		if($numCl > 0 && $numCl <= $max_item){
			$cont = 0;
?>
<form id="fde-question" name="fde-question" action="" method="post" class="form-quote form-question">
<?php
			while($rowCl = $rsCl->fetch_array(MYSQLI_ASSOC)){
				$cont += 1;
				
				echo '<h4>Titular '.$cont.' - '.$rowCl['titular'].'</h4>';
				
				$class_yes = $class_no = $check_yes = $check_no = $disabled_yes = $disabled_no = '';
				
				$rsQs->data_seek(0);
				$res = '';
				while ($rowQs = $rsQs->fetch_array(MYSQLI_ASSOC)) {
                    $class_yes = $class_no = $check_yes = $check_no = $disabled_yes = $disabled_no = '';

					if ($rowQs['respuesta'] == 0) {
						$class_no = 'class="required"';
						$res .= $rowQs['orden'].', ';
                        if ($rowCl['monto'] <= ($rowCl['valor_boliviano'] * 15000)) {
                            $check_no = 'checked';
                            //$disabled_yes = 'disabled';
                        }
					} elseif($rowQs['respuesta'] == 1) {
						$class_yes = 'class="required"';
                        if ($rowCl['monto'] <= ($rowCl['valor_boliviano'] * 15000)) {
                            $check_yes = 'checked';
                            //$disabled_no = 'disabled';
                        }
					}
?>
	<div class="question">
		<span class="qs-no"><?=$rowQs['orden'];?></span>
		<p class="qs-title"><?=$rowQs['pregunta'];?></p>
		<div class="qs-option">
            <label class="check">SI&nbsp;&nbsp;
                <input type="radio" id="dq-qs<?=$cont;?>-<?=$rowQs['id_pregunta'];?>1"
                       name="dq-qs-<?=$cont;?>-<?=$rowQs['id_pregunta'];?>" value="1"
                       <?=$class_yes . ' ' . $check_yes. ' ' . $disabled_yes;?>></label>
            <label class="check">NO&nbsp;&nbsp;
                <input type="radio" id="dq-qs<?=$cont;?>-<?=$rowQs['id_pregunta'];?>2"
                       name="dq-qs-<?=$cont;?>-<?=$rowQs['id_pregunta'];?>" value="0"
                       <?=$class_no . ' ' . $check_no . ' ' . $disabled_no;?>></label>
		</div>
	</div>
<?php
				}
?>
	<input type="hidden" id="dq-idd-<?=$cont;?>" name="dq-idd-<?=$cont;?>" value="<?=base64_encode($rowCl['id_detalle']);?>">
	<span style="display:block; font-size:85%; font-weight:bold; color:#408080; text-align:center;">Si alguna de sus respuestas [ <?=trim(trim($res),',');?> ] es afirmativa, sirvase brindar detalles:</span>
	<textarea id="dq-resp-<?=$cont;?>" name="dq-resp-<?=$cont;?>" style="display:block; width:600px; height:100px; margin:4px auto 18px auto;" class="fbin"></textarea>
<?php
			}
			$rsCl->free();
?>
	<div class="loading">
		<img src="img/loading-01.gif" width="35" height="35" />
	</div>
	
	<input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
	<input type="hidden" id="n_cl" name="n_cl" value="<?=$numCl;?>">
	<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
	<input type="hidden" id="pr" name="pr" value="<?=base64_encode('DE|03');?>">
	<input type="hidden" id="dq-idc" name="dq-idc" value="<?=$_GET['idc'];?>" >
    <input type="hidden" id="dq-idef" name="dq-idef" value="<?=$_SESSION['idEF'];?>">
	
	<input type="submit" id="dc-customer" name="dc-customer" value="Agregar Respuestas" class="btn-next" >
</form>
<?php
		}
	} else {
		echo 'No existen Titulares';
	}
} else {
	echo 'No existen Preguntas';
}
?>