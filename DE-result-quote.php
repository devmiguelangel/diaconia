<h3>Seguro de Desgravamen - Tenemos las siguientes ofertas</h3>
<h4>Escoge el plan que mas te convenga</h4>
<section style="text-align:center;">
<?php

require_once __DIR__ . '/app/controllers/QuoteController.php';

$Quote = new QuoteController();

if (($quotes = $Quote->getResultQuote($_GET['idc'], $_SESSION['idEF'])) !== false) {
	foreach ($quotes as $key => $quote) {
		$tasa_cia = (double)$quote['t_tasa_final'];
		$tasa_bc = (double)$quote['tasa_final'];
		$tasa_final = 0;

		if (empty($tasa_bc) === true) {
			$tasa_final = $tasa_cia;
		} else {
			$tasa_final = $tasa_bc;
		}
?>
<div class="result-quote" style="height:300px;">
	<div class="rq-img">
		<img src="images/<?=$quote['cia_logo'];?>" alt="<?=$quote['cia_nombre'];?>" 
			title="<?=$quote['cia_nombre'];?>">
	</div>
	<span class="rq-tasa">
		Tasa Desgravamen: 
		<?=number_format($tasa_final, 3, '.', ',');?> %
	</span>
	
	<a href="certificate-detail.php?idc=<?=
		base64_encode($quote['id_cotizacion']);?>&cia=<?=
		base64_encode($quote['idcia']);?>&pr=<?=
		base64_encode('DE');?>&type=<?=
		base64_encode('PRINT');?>" 
		class="fancybox fancybox.ajax btn-see-slip">
		Ver slip de Cotización</a>
	<?php if ($token === true): ?>
	<a href="de-quote.php?ms=<?=
		$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=
		base64_encode('DE|05');?>&idc=<?=
		$_GET['idc'];?>&flag=<?=
		md5('i-new');?>&cia=<?=
		base64_encode($quote['idcia']);?>" 
		class="btn-send">Emitir</a>	
	<?php endif ?>
</div>
<?php
	}
}
?>
</section>
<br>
<br>

<div class="contact-phone">
	Todas las ofertas tienen las mismas condiciones, elige la compañía de tu elección<br><br>
	* Para cualquier duda o consulta, contacta a la Línea Gratuita de Sudamericana S.R.L. 800-10-3070
</div>

<script type="text/javascript">
$(document).ready(function(e) {
    $('.f_cot_save').validateForm({
		method: 'GET',
		action: 'DE-save-share.php'
	});
});
</script>