<?php
require('sibas-db.class.php');
require('PHPMailer/class.phpmailer.php');

$arrCF = array(0 => 0, 1 => 'R', 2 => '');

if(isset($_GET['idef']) && isset($_GET['fc-name']) && isset($_GET['fc-email']) && isset($_GET['fc-city']) && isset($_GET['fc-comments'])){
	$link = new SibasDB();
	$arrRequest = array();
	
	$idef = $link->real_escape_string(trim(base64_decode($_GET['idef'])));
	$arrRequest['name'] = $link->real_escape_string(trim($_GET['fc-name']));
	$arrRequest['email'] = $link->real_escape_string(trim($_GET['fc-email']));
	$arrRequest['city'] = $link->real_escape_string(trim($_GET['fc-city']));
	$arrRequest['comments'] = $link->real_escape_string(trim($_GET['fc-comments']));
	
	$sqlCf = 'select 
			sc.correo as c_correo,
			sc.nombre as c_nombre,
			sef.id_ef as idef,
			sef.nombre as ef_nombre,
			sef.logo as ef_logo
		from
			s_correo as sc
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = sc.id_ef)
		where
			sef.id_ef = "'.$idef.'"
				and sef.activado = true
				and sc.producto = "CO"
		order by sc.id_correo asc
		;';
	
	if(($rsCf = $link->query($sqlCf, MYSQLI_STORE_RESULT))){
		$mail = new PHPMailer();
		
		$arrRequest['fromName'] = '';
		
		$mail->Host = $arrRequest['email'];
		$mail->From = $arrRequest['email'];
		
		$nc = 0;
		while($rowCf = $rsCf->fetch_array(MYSQLI_ASSOC)){
			if($nc === 0)
				$mail->addAddress($rowCf['c_correo'], $rowCf['c_nombre']);
			else
				$mail->addCC($rowCf['c_correo'], $rowCf['c_nombre']);
			$nc += 1;
			$arrRequest['fromName'] = $rowCf['ef_nombre'];
		}
		
		$mail->FromName = $arrRequest['fromName'];
		$mail->Subject = 'Nuevo mensaje de '.$arrRequest['fromName'];
		
		$body = get_html_request($arrRequest);
		
		$mail->Body = $body;
		$mail->AltBody = $body;
		
		if($mail->send()){
			$arrCF[0] = 1;
			$arrCF[2] = 'Solicitud enviada con exito';
		}else
			$arrCF[2] = 'La Solicitud no fue enviada';
	}else
		$arrCF[2] = 'No se puede enviar la Solicitud';
	echo json_encode($arrCF);
}else{
	echo json_encode($arrCF);
}

function get_html_request($arrRequest){
	ob_start();
?>
<div style="width:600px; border:1px solid #CCCCCC; color:#000000; font-weight:bold; font-size:12px; text-align:left;">
	<div style="padding:5px 10px; background:#008080; color:#FFFFFF;">SE HA RECIBIDO UN MENSAJE EN EL SITIO <?=$arrRequest['fromName'];?></div><br>
    <div style="padding:5px 10px;"><?=$arrRequest['name'];?></div>
    <div style="padding:5px 10px;"><?=$arrRequest['city'];?></div>
    <div style="padding:5px 10px;"><?=$arrRequest['email'];?></div>
    <div style="padding:5px 10px; background:#008080; color:#FFFFFF;">MENSAJE: </div>
    <div style="padding:5px 10px;"><?=$arrRequest['comments'];?></div>
</div>
<?php
	$html = ob_get_clean();
	return $html;
}
?>