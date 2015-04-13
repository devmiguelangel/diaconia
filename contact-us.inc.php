<script type="text/javascript">
$(document).ready(function(e) {
    $("#form-contact").validateForm({
		action: 'contact-submit-request.php',
		method: 'GET'
	});
});
</script>
<h3>Solicitar más información</h3>
<form id="form-contact" class="form-quote form-contact">
	<label style="height:auto;">Nombre completo <span>*</span></label>
    <div class="content-input" style="width:55%;">
	    <input type="text" id="fc-name" name="fc-name" value="" class="required text fbin" style="width:95%;">
    </div>
    
    <label style="height:auto;">Correo electrónico <span>*</span></label>
    <div class="content-input" style="width:55%;">
	    <input type="text" id="fc-email" name="fc-email" value="" class="required email fbin" style="width:95%;">
	</div>
    
    <label style="height:auto;">Ciudad <span>*</span></label>
    <div class="content-input" style="width:55%;">
	    <input type="text" id="fc-city" name="fc-city" class="required text-2 fbin" style="width:95%;">
    </div>
    
    <label style="height:auto;">Consulta <span>*</span></label>
    <div class="content-input" style="width:75%;">
	    <textarea id="fc-comments" name="fc-comments" class="required fbin" style="width:90%; height:100px;"></textarea><br>
	</div>
    
    <input type="hidden" id="idef" name="idef" value="<?=$_SESSION['idEF'];?>">
    <input type="submit" id="dc-customer" name="dc-customer" value="Enviar Solicitud" class="btn-next" >
    <div class="loading">
		<img src="img/loading-01.gif" width="35" height="35" />
	</div>
</form>