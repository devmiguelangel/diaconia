<?php
header("Expires: Thu, 27 Mar 1980 23:59:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if(isset($_POST['ide']) && isset($_POST['pr'])){
	require('sibas-db.class.php');
	require('classes/certificate-sibas.class.php');
	session_start();
	
	$link = new SibasDB();
	
	$arrIs = array (0 => 'LA PÓLIZA NO PUEDE SER EMITIDA', 1 => '#');
	
	$ide = $link->real_escape_string(trim(base64_decode($_POST['ide'])));
	$pr = $link->real_escape_string(trim(base64_decode($_POST['pr'])));
	$typeCe = '';
	$url = 'index.php';
	
	if (($type = $link->verify_type_user($_SESSION['idUser'], $_SESSION['idEF'])) !== FALSE) {
		if ($type['u_tipo_codigo'] === 'LOG') {
			$url = 'certificate-policy.php?ms=&page=&pr='.base64_encode($pr).'&ide='.base64_encode($ide);
			$typeCe = 'MAIL';
		} elseif ($type['u_tipo_codigo'] === 'IMP') {
			$typeCe = 'ATCH';
		}
	}
	
	$sql = '';
	switch($pr){
		case 'DE':
			$sql = 'UPDATE s_de_em_cabecera 
				SET emitir = TRUE, fecha_emision = curdate()
				WHERE id_emision = "'.$ide.'" and aprobado = true and rechazado = false
				;';
			break;
		case 'AU':
			$sql = 'UPDATE s_au_em_cabecera 
				SET emitir = TRUE, fecha_emision = curdate()
				WHERE id_emision = "'.$ide.'" and aprobado = true and rechazado = false
				;';
			
			break;
		case 'TRM':
			$sql = 'UPDATE s_trm_em_cabecera 
				SET emitir = TRUE, fecha_emision = curdate()
				WHERE id_emision = "'.$ide.'" and aprobado = true and rechazado = false
				;';
			break;
	}
	
	if($link->query($sql) === true){
		$arrIs[0] = 'LA PÓLIZA FUE EMITIDA CON EXITO.<br>Por favor espere...';
		$arrIs[1] = $url;

		/*$arr_host = array();
		if (($rowIm = $link->get_data_user($_SESSION['idUser'], $_SESSION['idEF'])) !== FALSE) {
			$arr_host['from'] = $rowIm['u_email'];
			$arr_host['fromName'] = $rowIm['u_nombre'];
		}
		
		$ce = new CertificateSibas($ide, NULL, NULL, $pr, $typeCe, 'CE', 1, 0, FALSE);
		$ce->host = $arr_host;
						
		if ($ce->Output() === TRUE) {
			$arrIs[0] = 'LA PÓLIZA FUE EMITIDA CON EXITO !<br>Por favor espere...';
			$arrIs[1] = $url;
		} else {
			$arrIs[0] = 'LA PÓLIZA FUE EMITIDA CON EXITO';
		}*/
	}else{
		$arrIs[0] = 'ERROR: LA PÓLIZA NO PUDO SER EMITIDA';
	}
	
	echo json_encode($arrIs);
} else if(isset($_GET['ide']) && isset($_GET['pr'])) {
?>
<script type="text/javascript">
$(document).ready(function(e) {
	setTimeout(function() {
		var _data = $('#f-issue').serialize();
		
		sendApprove(_data);
	}, 2000);
});

function sendApprove(_data) {
	$.ajax({
		type:"POST",
		async:true,
		cache:false,
		url:"fac-issue-policy.php",
		data: _data,
		dataType: "json",
		beforeSend: function(){
			if($('.loading .loading-text').length) {
				$('.loading .loading-text').remove();
			}
		},
		complete: function(){
			$('.loading img').slideUp();
		},
		success: function(resp){
			$('.loading img:last').after('<span class="loading-text">'+resp[0]+'</span>');
			redirect(resp[1], 3);
		}
	});
	return false;
}
</script>
<div style="width:auto; height:auto; min-width:300px; padding:5px 5px; font-size:80%; text-align:center;">
	<form id="f-issue" name="f-issue">
    	<input type="hidden" id="ide" name="ide" value="<?=$_GET['ide'];?>">
	    <input type="hidden" id="pr" name="pr" value="<?=$_GET['pr'];?>">
    </form>
    <div class="loading">
        <img src="img/loading-01.gif" width="35" height="35" style="display:block;" />
    </div>
</div>
<?php
}
?>