<script type="text/javascript">
$(document).ready(function(e) {
	$("#form-login").validateForm();
});
</script>
<li>
	<form id="form-login" name="form-login" action="" method="post">
		<label class="login-lbl">Tu nombre de usuario</label>
		<input type="text" id="l-user" name="l-user" value="" autocomplete="off" class="login-txt border-input required" />
		<br>
		
		<label class="login-lbl">Tu contraseña</label>
		<input type="password" id="l-pass" name="l-pass" value="" autocomplete="off" class="login-txt border-input required" />
		<br>
<?php
if(isset($_GET['ms']) && isset($_GET['page']) && isset($_GET['ide'])){
	if($page === md5('P_fac') || $page === md5('P_app_imp')){
?>
		<input type="hidden" id="ms" name="ms" value="<?=$ms;?>">
        <input type="hidden" id="page" name="page" value="<?=$page;?>">
        <input type="hidden" id="ide" name="ide" value="<?=$_GET['ide'];?>">
<?php
	}
}
?>
		<input type="submit" id="l-login" name="l-login" value="INGRESAR" class="login-btn" />
		<div class="loading">
			<img src="img/loading-01.gif" width="35" height="35" />
		</div>
	</form>
</li>