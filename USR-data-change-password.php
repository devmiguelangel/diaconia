<?php
require_once('sibas-db.class.php');
if (isset($_GET['user']) && isset($_GET['url'])) {
	$link = new SibasDB();
	$idUser = base64_decode($_GET['user']);
	$url = $_GET['url'];
	
	$sql = 'select 
		su.id_usuario as idUser,
		su.usuario as u_usuario,
		su.nombre as u_nombre,
		su.password as u_password,
		su.email
	from
		s_usuario as su
	where
		su.id_usuario = "'.$idUser.'"
	;';
	
	if (($rs = $link->query($sql, MYSQLI_STORE_RESULT))) {
		if ($rs->num_rows === 1) {
			$row = $rs->fetch_array(MYSQLI_ASSOC);
			$rs->free();

			$exit = 'logout.php';

			if (isset($_GET['token'])) {
				if ($_GET['token'] === md5('key001')) {
					$exit = 'index.php';
				}
			}
			
			
?>
<link type="text/css" href="css/jquery.validate.password.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script type="text/javascript" src="js/jquery.validate.password.js"></script>
<style type="text/css">
label.error{
	width:10px !important;
}
</style>
<script type="text/javascript">

$(document).ready(function(e) {
	$('.resp').hide();
	
	$('#cp_current_password').on({
		keyup: function(){
			var arr_key = new Array(37, 39, 8, 16, 32, 18, 17, 46, 36, 35, 186);
			var _val = $(this).prop('value');
			var _user = $('#user').prop('value');
			
			if(arr_key.indexOf(e.keyCode) < 0) {
				verify_current_password(_user, _val);
			}
		}, 
		blur: function(){
			var _val = $(this).prop('value');
			var _user = $('#user').prop('value');
			verify_current_password(_user, _val);
		}
	});
	
	$("#f-change-pass").validate({
		rules: {
			cp_current_password: {
				required: true,
			},
			cp_new_password: {
				required: true,
				password: "#cp_current_password"
			},
			cp_confirm_password: {
				required: true,
				equalTo: "#cp_new_password"
			}
		},
		messages: {
			cp_confirm_password: {
				required: "Repita su contraseña",
				equalTo: "Introduzca la misma contraseña que el anterior"
			}
		},
		// the errorPlacement has to take the table layout into account
		errorPlacement: function(error, element) {
			error.prependTo( element.parent().next() );
		},
		// specifying a submitHandler prevents the default submit, good for the demo
		submitHandler: function(form) {
			$(form).find(':submit').prop('disabled', true);
			var nameLoading = '.loading';
			var imgLoading = nameLoading + ' img';
			var _data = $(form).serialize();
			
			if($(nameLoading + " .loading-text").length) {
				$(nameLoading + " .loading-text").remove();
			}
			
			$.ajax({
				type:'GET',
				url:"USR-change-password.php",
				data: _data,
				dataType:"json",
				async:true,
				cache:false,
				beforeSend: function(){
					$(imgLoading).slideDown();
				},
				complete: function(){
					$(imgLoading).slideUp();
				},
				success: function(data){
					//alert(data);
					$(nameLoading + " img:last").after('<span class="loading-text">'+data[2]+'</span>');
					
					setTimeout(function() {
						if (data[0] === 1) {
							if (data[1] === 'R') {
								location.reload(true);
							} else if (data[1] !== '') {
								location.href = data[1];
							}
						}else if(data[0] === 0) {
							$(form).find(':submit').prop('disabled', false);
						}
					},2500);
				}
			});
			//return false;
		},
		// set this class to error-labels to indicate valid fields
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp;").addClass("checked");
		}
	});

	$('#dc-exit').click(function(e){
		e.preventDefault();
		location.href = '<?=$exit;?>';
	});
	
});

function verify_current_password(_user, _val) {
	$.get('USR-verify-current-password.php', {user: _user, pass: _val}, function(data) {
		resp = parseInt(data);
		if($(".loading .loading-text").length) {
			$(".loading .loading-text").remove();
		}
		if (resp === 1) {
			//$('#cp_current_password + span.resp').css('background-image', 'url(img/pass-ok.png)');
			$('#pass-ok').show();
			$('#pass-err').hide();
			$(".loading img:last").after('<span class="loading-text"></span>');
			$('#f-change-pass :submit').prop('disabled', false);
		} else if (resp === 0) {
			//$('#cp_current_password + span.resp').css('background-image', 'url(img/pass-err.png)');
			$('#pass-err').show();
			$('#pass-ok').hide();
			$('#cp_current_password').focus();
			$('#f-change-pass :submit').prop('disabled', true);
			$(".loading img:last").after('\
				<span class="loading-text">La contraseña introducida no coincide con la actual</span>');
		}
	});
}
</script>
<h3>Cambiar contraseña</h3>
<form id="f-change-pass" name="f-change-pass" action="" method="post" class="form-quote form-customer" action="" >
	<div style="width:60%; margin:0 auto; padding:10px 15px; border:1px solid #CCC; border-radius:10px; box-shadow:0px 0px 10px #999;">
    	<label>Usuario: <span>*</span></label>
        <div class="content-input" style="width:auto;">
            <input type="text" id="cp_user" name="cp_user" autocomplete="off" value="<?=$row['u_usuario'];?>" class="required text fbin" readonly>
            <span class="resp"></span>
        </div><br>
        
        <label>Contraseña actual: <span>*</span></label>
        <div class="content-input" style="width:auto;">
            <input type="password" id="cp_current_password" name="cp_current_password" autocomplete="off" value="" class="fbin">
            <span class="resp" style="background-image: url(img/pass-ok.png);" id="pass-ok"></span>
            <span class="resp" style="background-image: url(img/pass-err.png);" id="pass-err"></span>
        </div><br>
        
        <label>Contraseña nueva: <span>*</span></label>
        <div class="content-input" style="width:auto;">
            <input type="password" id="cp_new_password" name="cp_new_password" autocomplete="off" value="" class="fbin">
            <div class="password-meter">
                <div class="password-meter-message"> </div>
                <div class="password-meter-bg">
                    <div class="password-meter-bar"></div>
                </div>
            </div>
        </div><br>
        
        <label>Confirma la nueva contraseña: <span>*</span></label>
        <div class="content-input" style="width:auto;">
			<input type="password" id="cp_confirm_password" 
				name="cp_confirm_password" autocomplete="off" value="" class="fbin">
        </div><br>
        
        <label>Correo electrónico: <span>*</span></label>
        <div class="content-input" style="width:auto;">
			<input type="text" id="cp_email" name="cp_email" autocomplete="off" value="<?=$row['email'];?>" class="required email fbin">
        </div><br>

        <div class="loading">
            <img src="img/loading-01.gif" width="35" height="35" />
        </div>
        
		<input type="hidden" id="user" name="user" value="<?=base64_encode($row['idUser']);?>" >
        <input type="hidden" id="url" name="url" value="<?=$url;?>">
		<input type="submit" id="dc-customer" name="dc-customer" value="Cambiar contraseña" class="btn-next" >
		
		<input type="button" id="dc-exit" name="dc-exit" value="Salir" 
			class="btn-search-cs" style="display: block; margin: 0 auto; width: 70px; font-size: 90%;" >

    </div>
</form>
<?php
		}
	}
	
}
?>