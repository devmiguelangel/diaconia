<?php
require('includes-ce/certificate-RC.inc.php');
/**
 * aqui habia otros
 */
class CertificateHtml{
	protected $cx, $ide, $idc, $idef, $idcia,
			$type, $category, $product, $page, $nCopy, $error, $implant, $fac, $reason,
			$sqlPo, $sqlDt, $rsPo, $rsDt, $rowPo, $rowDt, $url,
			$html, $self, $host;
	public $extra, $modality = false;
	private $host_ws = '';

	protected function __construct() {
		$self = $_SERVER['HTTP_HOST'];
		$this->url = 'http://' . $self . '/';

		if (($this->host_ws = $this->cx->getNameHostEF($_SESSION['idEF'])) !== false) {
			$this->host_ws .= '.';
		}

		if (strpos($self, 'localhost') !== false || filter_var($self, FILTER_VALIDATE_IP) !== false) {
			$this->url .= trim($this->host_ws, '.') . '/';
		} elseif (strpos($self, $this->host_ws . 'abrenet.com') === false){
			$this->url .= trim($this->host_ws, '.') . '/';
		} else {
			$this->url .= '';
		}

		if($this->type === 'PDF' || $this->type === 'ATCH'){
			$this->url = '';
		}

		switch ($this->category) {
		case 'RC':		//	Siniestro
			$this->html = $this->get_html_rc();
			break;
		}
		//case 'CE':		//	Certificado
		//	$this->html = $this->get_html_ce();
		//	break;
		//case 'CP':		//	Certificado Provisional
		//	$this->html = $this->get_html_cp();
		//	break;
		//case 'PES':		//	Slip Producto Extra
		//	$this->html = $this->get_html_pes();
		//	break;
		//case 'PEC':		//	Slip de Cotización
		//	$this->html = $this->get_html_pec();
		//	break;
		//}
	}

	//SLIP DE COTIZACION
	private function get_html_rc(){

		return $this->set_html_de_rc();

		//switch ($this->product){
		//case 'DE':
		//    return $this->set_html_de_sc();
		//    break;
		//case 'AU':
		 //   return $this->set_html_au_sc();
		 //   break;
	    //case 'TRD':
		//    return $this->set_html_trd_sc();
		//    break;
		//case 'TRM':
		//    return $this->set_html_trm_sc();
		//    break;
		//}
	}

	//SLIP DE COTIZACIONES
	private function set_html_de_rc(){ //DESGRAVAMEN SLIP
		//if ($this->modality === false) {
			return rc_certificate($this->cx, $this->rowPo, $this->rsDt, $this->url);
		//} else {
		//	return de_sc_mo_certificate($this->cx, $this->rowPo, $this->rsDt, $this->url, $this->implant, $this->fac, $this->reason);
		//}
	}


}

?>