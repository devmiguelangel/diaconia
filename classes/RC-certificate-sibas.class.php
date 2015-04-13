<?php
require_once('sibas-db.class.php');
require_once('session.class.php');
require_once('PHPMailer/class.phpmailer.php');
require_once('RC-certificate-sibas-query.class.php');
/**
 *
 */
class CertificateSibas extends CertificateQuery{
	private $title, $subject, $formatPdf, $namePdf, $linkExtra = '';
	public $host = NULL, $address = NULL, $mod = NULL;

	public function __construct($idrc, $type, $category, $page) {

		$this->error = TRUE;
		$this->formatPdf = 'Legal';

		$this->cx = new SibasDB();

		$this->idrc = $this->cx->real_escape_string(trim($idrc));
		$this->type = $this->cx->real_escape_string(trim($type));
		$this->category = $this->cx->real_escape_string(trim($category));
		$this->page = $this->cx->real_escape_string(trim($page));

		$this->url = $_SERVER['HTTP_HOST'];
	}

	public function Output() {
		if (!isset($_SESSION['idEF'])) {
			$session = new Session();
			$session->getSessionCookie();
			$token = $session->check_session();
		}

		//$this->modality = $this->cx->verifyModality($_SESSION['idEF'], $this->product);
		parent::__construct();

		$this->subject = '';

		if ($this->error === FALSE) {
			$this->subject = 'Denuncia de Siniestro No. ' . $this->rowPo['s_no_siniestro'];

			$this->title = 'Detalle Denuncia de Sinistro';
			switch ($this->type) {
			case 'PRINT':
				echo $this->_PRINT();
				break;
			case 'PDF':
				return $this->_PDF();
				break;
			case 'MAIL':
				return $this->_MAIL();
				break;
			case 'ATCH':
				return $this->_ATTACHED();
				break;
			}
		} else {
			echo 'No se puede obtener el Certificado';
		}
	}

	private function _PRINT() {
		$this->get_style();
		$this->get_script();
		$this->get_content_html();

		$contenido = '<div id="print">';

				$contenido .= $this->html;

		$contenido .= '</div>';

		return $contenido;
	}

	private function _PDF() {
		set_time_limit(0);
		$content = $this->html;

		require_once(dirname(__FILE__).'/../html2pdf/html2pdf.class.php');
		try
		{
		    if ($this->category === 'RC') {
				$this->namePdf = 'siniestro.pdf';
			}

			$html2pdf = new HTML2PDF('P', $this->formatPdf, 'en', true, 'UTF-8', 2);
			$html2pdf->WriteHTML($content);
			$html2pdf->Output($this->namePdf);
			return TRUE;
		}
		catch(HTML2PDF_exception $e) {
		    //echo $e;
		    return FALSE;
		    exit;
		}
	}

	private function _MAIL() {
		$mail = new PHPMailer();

		/*if (is_array($this->host) === TRUE) {
			$mail->Host = $this->host['from'];
			$mail->From = $this->host['from'];
			$mail->FromName = $this->host['fromName'];
		} else{
			$mail->Host = $this->rowPo['u_email'];
			$mail->From = $this->rowPo['u_email'];
			$mail->FromName = $this->rowPo['ef_nombre'];
		}*/

		$mail->Host = $this->rowPo['u_email'];
		$mail->From = $this->rowPo['u_email'];
		$mail->FromName = $this->rowPo['ef_nombre'];
		$mail->Subject = $this->subject;


		if (is_array($this->address) === TRUE) {
			for ($i = 0; $i < count($this->address); $i++) {
				$mail->addAddress($this->address[$i]['address'], $this->address[$i]['name']);
			}
		}

		$mail->addAddress($this->rowPo['u_email'], $this->rowPo['u_nombre']);

		if (($rsc = $this->email_copy()) !== FALSE) {
			while($rowc = $rsc->fetch_array(MYSQLI_ASSOC)){
				/*if ($this->fac === TRUE
					&& $this->implant === FALSE
					&& $rowc['producto'] === 'F' . $this->product
					) {
					$mail->addAddress($rowc['correo'], $rowc['nombre']);
				} else {
				*/
				$mail->addCC($rowc['correo'], $rowc['nombre']);

				//}
			}
		}

		//$mail->addCC($this->rowPo['u_email'], $this->rowPo['u_nombre']);
		//if (is_array($this->host) === TRUE) {
		//	$mail->addCC($this->host['from'], $this->host['fromName']);
		//}

		$mail->Body = $this->html;
		//$mail->Body = "test";
		$mail->AltBody = $this->html;
		//echo $mail->Body;

		if($mail->send()){
			return TRUE;
		}else{
			return FALSE;
		}

	}

	private function _ATTACHED() {
		set_time_limit(0);

		$attached = '';
		$content = $this->html;

		require_once(dirname(__FILE__).'/../html2pdf/html2pdf.class.php');
		try
		{
			$html2pdf = new HTML2PDF('P', $this->formatPdf, 'en', true, 'UTF-8', 2);
		    $html2pdf->WriteHTML($content);

			$attached = $html2pdf->Output('','S');

			$mail = new PHPMailer();

			/*if (is_array($this->host) === TRUE) {
				$mail->Host = $this->host['from'];
				$mail->From = $this->host['from'];
				$mail->FromName = $this->host['fromName'];
			} else{
				$mail->Host = $this->rowPo['u_email'];
				$mail->From = $this->rowPo['u_email'];
				$mail->FromName = $this->rowPo['ef_nombre'];
			}*/

			$mail->Host = $this->rowPo['u_email'];
			$mail->From = $this->rowPo['u_email'];
			$mail->FromName = $this->rowPo['ef_nombre'];
			$mail->Subject = $this->subject;


			if (is_array($this->address) === TRUE) {
				for ($i = 0; $i < count($this->address); $i++) {
					$mail->addAddress($this->address[$i]['address'], $this->address[$i]['name']);
				}
			}

			$mail->addAddress($this->rowPo['u_email'], $this->rowPo['u_nombre']);

			if (($rsc = $this->email_copy()) !== FALSE) {
				while($rowc = $rsc->fetch_array(MYSQLI_ASSOC)){
					/*if ($this->fac === TRUE
						&& $this->implant === FALSE
						&& $rowc['producto'] === 'F' . $this->product
						) {
						$mail->addAddress($rowc['correo'], $rowc['nombre']);
					} else {
					*/
					$mail->addCC($rowc['correo'], $rowc['nombre']);

					//}
				}
			}

			//$mail->addCC($this->rowPo['u_email'], $this->rowPo['u_nombre']);
			//if (is_array($this->host) === TRUE) {
			//	$mail->addCC($this->host['from'], $this->host['fromName']);
			//}


			//$mail->AddAttachment($attached,'Detalle-Certificado-Automotores.pdf','base64','application/pdf');
			$mail->AddStringAttachment($attached, $this->title . '.pdf', 'base64', 'application/pdf');

			$mail->Body = $this->html;
			$mail->AltBody = $this->html;

			if($mail->Send()){
				return TRUE;
			}else{
				return FALSE;
			}
		}
		catch(HTML2PDF_exception $e) {
		    //echo $e;
		    return FALSE;
		    exit;
		}

	}

	private function email_copy() {

		$sqlc = 'select correo, nombre, producto
			from s_correo
			where producto = "RC"';

		if (($rsc = $this->cx->query($sqlc, MYSQLI_STORE_RESULT))) {
			if ($rsc->num_rows > 0) {
				return $rsc;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	public function add_email_cc($email) {

	}

	private function get_style(){
?>
<style type="text/css">
.link-cert{
	display: inline-block;
	*display: inline;
	_display: inline;
	width: 50px;
	height: 50px;
	margin: 3px 5px;
	padding: 0;
	border: 0 none;
	text-decoration: none;
	vertical-align: top;
	zoom: 1;
}

.link-cert img{ border: 0 none; }

.loading-resp{
	display: inline-block;
	*display: inline;
	_display: inline;
	width: 350px;
	height: 0px;
	background: url(img/loading-01.gif) top center no-repeat;
	vertical-align: top;
	font: bold 90% Arial, Helvetica, sans-serif;
	text-align: center;
	zoom: 1;
}
#view-form input[type="text"]{
	display: inline-block;
	padding: 7px 10px;
	margin: 3px 0px;
	border: 1px solid #bababa;
	width: 200px;
	font-size: 10px;
}
#view-form #enviar{
	display: inline-block;
	width: 100px;
	padding: 5px 5px;
	margin: 3px auto 0 auto;
	border: 0 none;
	background: #0075aa;
	color: #FFFFFF;
	cursor: pointer;
}

#view-form #enviar:hover{ background: #1a834c; }
</style>
<?php
	}

	private function get_script(){
?>
<script type="text/javascript">
$(document).ready(function(){
	//VISUALIZAR FORMULARIO
	$('#send-mail').click(function(e){
		$('#view-form').fadeIn('slow');
		e.preventDefault();
	});

	$('#view-form').submit(function(e){
		var emails = $('#email').prop('value');
		var category = $('#category').prop('value');
		var idsiniestro = $('#idsiniestro').prop('value');
		var sum = 0;
		var parsed = new Array();
		var vector = new Array();
		var cont = 0;
		var indice = 0;
		var sw = 1;

		$(this).find('.required').each(function() {
			if(emails != ''){
				var rows = emails.split(",");

				$.each(rows, function() {
					var texto = this;
					if (texto != ''){
						if (texto.match(/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.-][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/)) {
							sw = 1;
							vector[indice]=texto;
						   	indice++;
						} else {
							sw = 0;
							parsed[cont] = texto;
							cont++;
						}
					}
				});


				if (sw == 1) {
					$('#erroremails').hide('slow');
				}else{
					var dato = parsed.toString ();
					sum++;
					$('#erroremails').show('slow');
					$('#erroremails').html('ingrese correos validos:'+dato);
				}
			}
		});

		if(sum == 0){
			$("#view-form :submit").attr("disabled", true);
			e.preventDefault();
			var resultCl = $("#response-cert");
			var correos = vector.toString();
			/*
			if (category === 'SC' || category === 'PES' || product === 'TH'){
				var dataString = 'idc='+idcotizacion+'&type=<?=base64_encode('MAIL');?>&pr=<?=base64_encode($this->product);?>&cia=<?=base64_encode($this->idcia).$this->linkExtra;?>&category=<?=base64_encode($this->category);?>&emails=' + correos;
			} else {
				var dataString = 'ide=' + idemision + '&type=<?=base64_encode('MAIL');?>&pr=<?=base64_encode($this->product);?>&category=<?=base64_encode($this->category);?>&emails=' + correos;
			}*/

			var dataString = 'idRc='+idsiniestro+'&type=<?=base64_encode('ATCH');?>&category=<?=base64_encode($this->category);?>&emails=' + correos;
			$.ajax({
				async: true,
				cache: false,
				type: "GET",
				url: 'RC-certificate-detail.php',
				data: dataString,
				//dataType: 'json',
				beforeSend: function(){
					resultCl.html('');
					resultCl.css({
						'height': '50px'
					}).show();
				},
				complete: function(){
					resultCl.css({
						'background': 'none'
					});
				},
				success: function(data){
					//alert(data);
					// resultCl.html(data);
					if(data==1){
						resultCl.html('Email enviado con Exito');
					}else{
						rtCl.html('No se pudo enviar el Email');
					}
					resultCl.delay(3000).slideUp(function(){
						$(this).css('background', 'url(img/loading-01.gif) top center no-repeat');
					});
				}
			});
		}else{
		   e.preventDefault();
		}
	});

	//IMPRIMIR PAGINA
	$("#send-print").click(function(e){
		e.preventDefault();
		var rel = $(this).prop("rel");

		$(".attached-link").hide();
		//$(".container-logo").hide();

		var ficha = document.getElementById(rel);
		var ventimp = window.open(' ','popimpr');
		ventimp.document.write(ficha.innerHTML);
		ventimp.document.close();
		ventimp.print();
		ventimp.close();
		//ventimp.document.onbeforeunload = confirmExit();
	});

});

function confirmExit(){
	$(".attached-link").show();
	$(".container-logo").show();
}

</script>
<?php
	}

	private function get_content_html(){
?>
<a href="#" title="Imprimir" class="link-cert" rel="print" id="send-print">
	<img src="img/icon-print-01.png" width="50" height="50" alt="Imprimir" />
</a>

	<a href="RC-certificate-detail.php?idRc=<?=base64_encode($this->rowPo['idRc']);?>&type=<?=base64_encode('PDF')?>&category=<?=base64_encode($this->category);?>" target="_blank" title="Exportar a PDF" class="link-cert">

	<img src="img/icon-pdf-01.png" width="50" height="50" alt="Exportar a PDF" />
</a>

<?php

if (($rowUser = $this->cx->verify_type_user($_SESSION['idUser'], $_SESSION['idEF'])) !== false) {
		$type = $rowUser['u_tipo_codigo'];
}

if($type==='LOG'){
?>

<a href="#" target="_blank" title="Enviar por Correo Electronico" id="send-mail" class="link-cert">
	<img src="img/icon-mail-01.png" width="50" height="50" alt="Enviar por Correo Electronico" />
</a>
<?php
}
?>
<div class="loading-resp" id="response-cert">
	<form id="view-form" name="view-form" action="" method="get" style="display:none;">
		<input id="email" name="email" value="" type="text" class="required"/>
		<input type="hidden" id="product" name="product" value="<?=$this->product;?>">
		<input type="submit" id="enviar" value="Enviar"/>

		<div id="erroremails" style="font-size:9px; color:#9b4449; text-align:left;"></div>

		<input type="hidden" id="category" value="<?=$this->category;?>"/>
		<input type="hidden" id="idsiniestro" value="<?=base64_encode($this->rowPo['idRc']);?>"/>

	</form>
</div>
<?php
		echo '<hr />';
	}

}


?>