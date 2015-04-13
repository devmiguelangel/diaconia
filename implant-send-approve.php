<div style="width:auto; height:auto; min-width:400px; padding:5px 5px; font-size:80%; text-align:center;">
<?php
if (isset($_GET['ide']) && isset($_GET['pr'])) {
	$ide = $_GET['ide'];
	$pr = $_GET['pr'];
?>
	<form id="f-approve" name="f-approve">
	    <input type="hidden" id="ide" name="ide" value="<?=$ide;?>" >
    	<input type="hidden" id="pr" name="pr" value="<?=$pr;?>" >
    </form>
    <div class="loading">
		<img src="img/loading-01.gif" width="35" height="35" style="display:block;" />
    </div>

<script type="text/javascript">
$(document).ready(function(e) {
	setTimeout(function() {
		var _data = $('#f-approve').serialize();
		
		sendApprove(_data);
	}, 2000);
});

function sendApprove(_data) {
	$.ajax({
		url:"implant-send-certificate.php",
		type: "POST",
		data: _data,
		async:true,
		cache:false,
		beforeSend: function(){
			if($('.loading .loading-text').length) {
				$('.loading .loading-text').remove();
			}
		},
		complete: function(){
			$('.loading img').slideUp();
		},
		success: function(resp){
			$('.loading img:last').after('<span class="loading-text">'+resp+'</span>');
			redirect('index.php', 3);
		}
	});
	
	return false;
}
</script>
<?php
} else {
	echo 'Usted no puede enviar la Solicitud de Aprobación';
}
?>
</div>