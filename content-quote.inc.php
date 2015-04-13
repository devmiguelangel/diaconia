<?php
$cp = NULL;

$HOST_HOME = $link->get_home_content($_SESSION['idEF'], $seguro, $token);
$cp = (boolean)$HOST_HOME['cp'];
?>
<h2>Seguro de <?=$HOST_HOME['home_title'];?></h2>
<div class="content-ins"><?=$HOST_HOME['html'];?></div><!--
--><aside class="content-quote">
<?php
if($cp === true){
?>
<script type="text/javascript">
$(document).ready(function(e) {
    $(".quote-ln").click(function(e){
		e.preventDefault();
		//var gm = $("input[name='fq-gm']:checked").prop('value');
		$("#f-quote").submit();
	});
	
	$("#f-quote").validateForm({
		action: '<?=$seguro;?>-loan-record.php'
	});
	
	/*$('input').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green',
		increaseArea: '20%' // optional
	});*/
	
	$('input[type="radio"]').each(function(){
		var self = $(this),
	    label = self.next(),
    	label_text = label.text();
		
		label.remove();
		self.iCheck({
			checkboxClass: 'icheckbox_line-green',
			radioClass: 'iradio_line-green',
			insert: '<div class="icheck_line-icon"></div>' + label_text
		});
	});
});
</script>
<?php
}
?>
	<form id="f-quote" name="f-quote" class="form-quote">
<?php
if($cp === true){
?>
		<div style="width:70%; margin:0 auto;">
        <input type="radio" id="fq-cp-1" name="fq-cp" value="<?=md5(1);?>" checked>
			<label class="check" style="width:auto; vertical-align:bottom;">Certificado Provisional</label><br>
            <input type="radio" id="fq-cp-2" name="fq-cp" value="<?=md5(0);?>">
        	<label class="check" style="width:auto; vertical-align:bottom;">Emisión Directa</label>
            
            <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
            <input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
            <input type="hidden" id="pr" name="pr" value="<?=base64_encode($seguro.'|01');?>">
        </div>
        
        <div class="loading">
            <img src="img/loading-01.gif" width="35" height="35" />
        </div>
<?php
}
?>
        <a href="<?=strtolower($seguro);?>-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=base64_encode($seguro.'|01');?>" class="quote-ln">Cotizar</a>
    </form>
	<div class="quote-img"><img src="images/<?=$HOST_HOME['imagen'];?>"></div>
</aside>