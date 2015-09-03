<?php
require_once('sibas-db.class.php');
require_once('session.class.php');
require_once('PHPMailer/class.phpmailer.php');
require_once('certificate-sibas-query.class.php');
/**
 * 
 */
class CertificateSibas extends CertificateQuery{
	private $title, $subject, $formatPdf, $namePdf, $linkExtra = '';
	public $host = NULL, $address = NULL, $mod = NULL;
	
	public function __construct($ide, $idc, $idcia, $product, $type, 
		$category, $page, $nCopy, $implant, $fac = FALSE, $reason = '') {
			
		$this->error = TRUE;
		$this->formatPdf = 'Legal';
		
		$this->cx = new SibasDB();
		
		$this->ide = $this->cx->real_escape_string(trim($ide));
		$this->idc = $this->cx->real_escape_string(trim($idc));
		$this->idcia = $this->cx->real_escape_string(trim($idcia));
		$this->product = $this->cx->real_escape_string(trim($product));
		$this->type = $this->cx->real_escape_string(trim($type));
		$this->category = $this->cx->real_escape_string(trim($category));
		$this->page = $this->cx->real_escape_string(trim($page));
		$this->nCopy = $this->cx->real_escape_string(trim($nCopy));
		$this->implant = (boolean)$this->cx->real_escape_string(trim($implant));
		$this->fac = (boolean)$this->cx->real_escape_string(trim($fac));
		$this->reason = $this->cx->real_escape_string(trim($reason));
		
		$this->url = $_SERVER['HTTP_HOST'];
	}

	public function Output() {
		if (!isset($_SESSION['idEF'])) {
			$session = new Session();
			$session->getSessionCookie();
			$token = $session->check_session();

			if ($token === false) {
				if (($HOST_CLIENT = $this->cx->get_financial_institution_ins()) !== false) {
					$_SESSION['idEF'] = base64_encode($HOST_CLIENT['idef']);
				} else {
					exit();
				}
			}
		}

		$this->modality = $this->cx->verifyModality($_SESSION['idEF'], $this->product);
		parent::__construct();
		
		$this->subject = '';
		
		if ($this->error === FALSE) {
			if ($this->implant === TRUE) {
				$this->subject = 'Solicitud de aprobacion: Poliza No. '
					. $this->rowPo['prefijo'] . '-' . $this->rowPo['no_emision'];
			} elseif ($this->fac === TRUE) {
				$this->subject = 'Solicitud de aprobacion: Caso Facultativo No. ' 
					. $this->rowPo['prefijo'] . '-' . $this->rowPo['no_emision'];
			} else {
				switch ($this->product) {
				case 'DE':
					$this->title = 'Certificado-Poliza-Desgravamen';
					break;
				case 'AU':
					$this->title = 'Certificado-Poliza-Automotores';
					break;
				case 'TRD':
					$this->title = 'Certificado-Poliza-Todo-Riesgo-Domiciliario';
					break;
				case 'TRM':
					$this->title = 'Certificado-Poliza-Todo-Riesgo-Equipo-Movil';
					break;
				case 'TH':
					$this->title = 'Certificado-Poliza-Tarjetahabiente';
					break;
				}
				
				if ($this->category === 'SC') {
					$this->formatPdf = 'Letter';
					switch ($this->product) {
					case 'DE':
						$this->subject = 'Slip de Cotizacion Desgravamen No. ' 
							. $this->rowPo['no_cotizacion'];
						break;
					case 'AU':
						$this->subject = 'Slip de Cotizacion Automotores No. '.$this->rowPo['no_cotizacion'];
						break;
					case 'TRD':
						$this->subject = 'Slip de cotizacion Todo Riesgo Domiciliario No. '.$this->rowPo['no_cotizacion'];
						break;
					case 'TRM':
						$this->subject = 'Slip de Cotizacion Todo Riesgo Equipo Movil No. '.$this->rowPo['no_cotizacion'];
						break;
					}
				} elseif ($this->category === 'CE') {
					switch ($this->product) {
					case 'DE':
						$this->formatPdf = 'Letter';
						$this->subject = 'Certificado Desgravamen Poliza No. ' 
							. $this->rowPo['prefijo'] . ' - ' 
							. $this->rowPo['no_emision'];
						break;
					case 'AU':
						$this->subject = 'Certificado Automotores Poliza No. '.$this->rowPo['no_emision'];
						break;
					case 'TRD':
						$this->subject = 'Certificado Todo Riesgo Domiciliario Poliza No. '.$this->rowPo['no_emision'];
						break;
					case 'TRM':
						$this->subject = 'Certificado Todo Riesgo Equipo Movil Poliza No. '.$this->rowPo['no_emision'];
						break;
					case 'TH':
						$this->subject = 'Certificado Tarjetahabiente No. ' 
							. $this->rowPo['prefijo'] . '-' . $this->rowPo['no_cotizacion'];
						break;
					}
				} elseif ($this->category === 'CP'){
					switch ($this->product){
					case 'DE':
						$this->subject = 'Certificado Provisional Desgravamen Poliza No. '.$this->rowPo['no_emision'];
						break;
					case 'AU':
						$this->subject = 'Certificado Provisional Automotores Poliza No. '.$this->rowPo['no_emision'];
						break;
					case 'TRD':
						$this->subject = 'Certificado Provisional Todo Riesgo Domiciliario Poliza No. '.$this->rowPo['no_emision'];
						break;
					case 'TRM':
						$this->subject = 'Certificado Provisional Todo Riesgo Equipo Movil Poliza No. '.$this->rowPo['no_emision'];
						break;
					}
				} elseif ($this->category === 'PES') {
					$this->formatPdf = 'Letter';
					switch ($this->product) {
					case 'DE':
						$this->subject = 'Slip Producto extra Desgravamen No. '.$this->rowPo['no_cotizacion'];
						break;
					}
				} elseif ($this->category === 'PEC') {
					$this->formatPdf = 'Letter';
					switch ($this->product) {
					case 'DE':
						$this->subject = 'Certificado Producto extra Desgravamen No. '.$this->rowPo['no_emision'];
						break;
					}
				}
			}
			
			if ($this->extra !== NULL) {
				$this->linkExtra = '&pe='.base64_encode($this->extra);
			}
			
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
		
		if ($this->idc !== NULL) {
			$contenido .= $this->html;
		} elseif ($this->ide !== NULL) {
			if ($this->nCopy > 0) {
				for ($i = 1;$i <= $this->nCopy; $i++) {
					$contenido .= $this->html;
				}
			} else {
				$contenido .= $this->html;
			}
		}
		$contenido .= '</div>';
		
		return $contenido;
	}
	
	private function _PDF() {
		set_time_limit(0);
		$content = $this->html;
		
		require_once(dirname(__FILE__).'/../html2pdf/html2pdf.class.php');
		try
		{
		    if ($this->category === 'SC') {
				switch ($this->product) {
				case 'DE':
					$this->namePdf = 'slip_cotizacion_DE.pdf';
					break;
				case 'AU':
					$this->namePdf = 'slip_cotizacion_AU.pdf';
					break;
				case 'TRD':
					$this->namePdf = 'slip_cotizacion_TRD.pdf';
					break;
				case 'TRM':
					$this->namePdf = 'slip_cotizacion_TRM.pdf';
					break;
				}
			} elseif ($this->category === 'CE'){
				switch ($this->product) {
				case 'DE':
					$this->namePdf = 'certificado_emision_DE.pdf';
					break;
				case 'AU':
					$this->namePdf = 'certificado_emision_AU.pdf'; 
					break;
				case 'TRD':
					$this->namePdf = 'certificado_emision_TRD.pdf';
					break;
				case 'TRM':
					$this->namePdf = 'certificado_emision_TRM.pdf';
					break;
				}
			} elseif($this->category === 'CP'){
				switch ($this->product) {
				case 'DE':
					$this->namePdf = 'certificado_provisional_DE.pdf'; 
					break;
				case 'AU':
					$this->namePdf = 'certificado_provisional_AU.pdf'; 
					break;
				case 'TRD':
					$this->namePdf = 'certificado_provisional_TRD.pdf';
					break;
				case 'TRM':
					$this->namePdf = 'certificado_provisional_TRM.pdf';
					break;
				}
			} elseif ($this->category === 'PES') {
				switch ($this->product) {
				case 'DE':
					$this->namePdf = 'slip_vida_grupo_DE.pdf';
					break;
				}
			} elseif ($this->category === 'PEC') {
				switch ($this->product) {
				case 'DE':
					$this->namePdf = 'certificado_vida_grupo_DE.pdf';
					break;
				}
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
		
		if (is_array($this->host) === TRUE) {
			$mail->Host = 'emontano@sudseguros.com';//$this->host['from'];
			$mail->From = 'emontano@sudseguros.com';//$this->host['from'];
			$mail->FromName = $this->host['fromName'];
		} else{
			$mail->Host = 'emontano@sudseguros.com';//$this->rowPo['u_email'];
			$mail->From = 'emontano@sudseguros.com';//$this->rowPo['u_email'];
			$mail->FromName = $this->rowPo['ef_nombre'];
		}
		
		$mail->Subject = $this->subject;

		if (is_array($this->address) === TRUE) {
			for ($i = 0; $i < count($this->address); $i++) {
				$mail->addAddress($this->address[$i]['address'], $this->address[$i]['name']);
			}
		}
		
		$mail->addAddress($this->rowPo['u_email'], $this->rowPo['u_nombre']);
		
		if (($rsc = $this->email_copy()) !== FALSE) {
			while($rowc = $rsc->fetch_array(MYSQLI_ASSOC)){
				if ($this->fac === TRUE 
					&& $this->implant === FALSE 
					&& $rowc['producto'] === 'F' . $this->product
					) {
					$mail->addAddress($rowc['correo'], $rowc['nombre']);
				} else {
					$mail->addCC($rowc['correo'], $rowc['nombre']);
				}
			}
		}
		
		//$mail->addCC($this->rowPo['u_email'], $this->rowPo['u_nombre']);
		if (is_array($this->host) === TRUE) {
			$mail->addCC($this->host['from'], $this->host['fromName']);
		}
				
		$mail->Body = $this->html;
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
			if (is_array($this->host) === TRUE) {
				$mail->Host = $this->host['from'];
				$mail->From = $this->host['from'];
				$mail->FromName = $this->host['fromName'];
			} else{
				$mail->Host = $this->rowPo['u_email'];
				$mail->From = $this->rowPo['u_email'];
				$mail->FromName = $this->rowPo['ef_nombre'];
			}
			
			$mail->Subject = $this->subject;
			
			if (is_array($this->address) === TRUE) {
				for ($i = 0; $i < count($this->address); $i++) {
					$mail->addAddress($this->address[$i]['address'], $this->address[$i]['name']);
				}
			}

			$mail->addAddress($this->rowPo['u_email'], $this->rowPo['u_nombre']);
		
			if (($rsc = $this->email_copy()) !== FALSE) {
				while($rowc = $rsc->fetch_array(MYSQLI_ASSOC)){
					if ($this->fac === TRUE 
						&& $this->implant === FALSE 
						&& $rowc['producto'] === 'F' . $this->product) {
						$mail->addAddress($rowc['correo'], $rowc['nombre']);
					} else {
						$mail->addCC($rowc['correo'], $rowc['nombre']);
					}
				}
			}

			//$mail->addCC($this->rowPo['u_email'], $this->rowPo['u_nombre']);
			if (is_array($this->host) === TRUE) {
				$mail->addCC($this->host['from'], $this->host['fromName']);
			}
			
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
			where (producto = "'.$this->product.'" OR producto = "F'.$this->product.'") 
				and id_ef = "'.$this->rowPo['idef'].'" ;';
		
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
		var product = $('#product').prop('value');
		var idcotizacion = $('#idcotizacion').prop('value');
		var idemision = $('#idemision').prop('value');
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
			if (category === 'SC' || category === 'PES' || product === 'TH'){
				var dataString = 'idc='+idcotizacion+'&type=<?=base64_encode('MAIL');?>&pr=<?=base64_encode($this->product);?>&cia=<?=base64_encode($this->idcia).$this->linkExtra;?>&category=<?=base64_encode($this->category);?>&emails=' + correos;
			} else {
				var dataString = 'ide=' + idemision + '&type=<?=base64_encode('MAIL');?>&pr=<?=base64_encode($this->product);?>&category=<?=base64_encode($this->category);?>&emails=' + correos;
			}
			
			$.ajax({
				async: true,
				cache: false,
				type: "GET",
				url: 'certificate-detail.php',
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
					//resultCl.html(data);
					if(data==1){
						resultCl.html('Email enviado con Exito');
					}else{
						resultCl.html('No se pudo enviar el Email');
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
<?php
	if($this->category === 'SC' || $this->category === 'PES' || $this->product === 'TH') {
		echo '<a href="' . $this->url . 'certificate-detail.php?idc=' 
			. base64_encode($this->rowPo['id_cotizacion']) . '&type=' 
			. base64_encode('PDF') . '&pr=' . base64_encode($this->product) 
			. '&cia=' . base64_encode($this->idcia) . '&category=' 
			. base64_encode($this->category).$this->linkExtra . '" 
			target="_blank" title="Exportar a PDF" class="link-cert">';
	} else {
		echo '<a href="' . $this->url . 'certificate-detail.php?ide=' 
			. base64_encode($this->rowPo['id_emision']) . '&type=' 
			. base64_encode('PDF') . '&pr=' . base64_encode($this->product) 
			. '&category=' . base64_encode($this->category) . '" 
			target="_blank" title="Exportar a PDF" class="link-cert">';
	}
?>  
	<img src="img/icon-pdf-01.png" width="50" height="50" alt="Exportar a PDF" />
</a>
<a href="#" target="_blank" title="Enviar por Correo Electronico" id="send-mail" class="link-cert">
	<img src="img/icon-mail-01.png" width="50" height="50" alt="Enviar por Correo Electronico" />
</a>
<div class="loading-resp" id="response-cert">
	<form id="view-form" name="view-form" action="" method="get" style="display:none;">
		<input id="email" name="email" value="" type="text" class="required"/>
		<input type="hidden" id="product" name="product" value="<?=$this->product;?>">
		<input type="submit" id="enviar" value="Enviar"/>
		
		<div id="erroremails" style="font-size:9px; color:#9b4449; text-align:left;"></div>
<?php
	if($this->category === 'SC' || $this->category === 'PES' || $this->product === 'TH') {
?>
		<input type="hidden" id="category" value="<?=$this->category;?>"/>
		<input type="hidden" id="idcotizacion" value="<?=base64_encode($this->rowPo['id_cotizacion']);?>"/>
<?php
	} else{
?>
		<input type="hidden" id="category" value="<?=$this->category;?>"/>
		<input type="hidden" id="idemision" value="<?=base64_encode($this->rowPo['id_emision']);?>"/>
<?php
	}
?>
	</form>
</div>
<?php
		echo '<hr />';
	}
	
}


?>