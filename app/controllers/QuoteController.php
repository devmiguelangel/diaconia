<?php 

require_once __DIR__ . '/../../sibas-db.class.php';
require_once __DIR__ . '/../models/Diaconia.php';
require __DIR__ . '/../repositories/QuoteRepo.php';

class QuoteController extends Diaconia
{
	
	public function __construct()
	{
		$this->cx = new SibasDB();
	}
	
	public function postQuote($data, &$mess)
	{
		$pr = $this->cx->real_escape_string(trim(base64_decode($data['pr'])));
		if ($pr === 'DE|01') {
			$ms 	= $this->cx->real_escape_string(trim($data['ms']));
			$page 	= $this->cx->real_escape_string(trim($data['page']));
			$data['tc'] 	= $this->getRateExchange();
			$data_amount	= 0;

			$cp 	= null;
			$sql 	= '';
		
			$data['coverage'] 	= $this->cx->real_escape_string(trim($data['dl-coverage']));
			$data['amount'] 	= $this->cx->real_escape_string(trim($data['dl-amount']));
			$data['currency'] 	= $this->cx->real_escape_string(trim($data['dl-currency']));
			$data['term'] 		= $this->cx->real_escape_string(trim($data['dl-term']));
			$data['type_term'] 	= $this->cx->real_escape_string(trim($data['dl-type-term']));
			$data['product'] 	= $this->cx->real_escape_string(trim($data['dl-product']));
			$data['modality'] 	= 'null';
			
			if ($this->checkAmount($data['amount'], $data['currency'], $data['idef'], $data_amount)) {
				$QuoteRepo = new QuoteRepo($this->cx);

				if ($QuoteRepo->postQuoteRepo($data)) {
					$mess[0] = 1;
					$mess[1] = 'de-quote.php?ms=' . $ms . '&page=' . $page 
						. '&pr=' . base64_encode('DE|02') 
						. '&idc=' . base64_encode($data['idc']);
					$mess[2] = 'La Cotización fue registrada con exito';
					
					return true;
				} else {
					$mess[2] = 'No se pudo registrar la Cotización. !';
				}
			} else {
				$mess[2] = 'El monto no debe sobrepasar los ' 
					. number_format($data_amount['amount'], 2, '.', ',') . ' ' . $data['currency'];
			}
		}

		return false;
	}

	public function getResultQuote($idc, $idef)
	{
		$idc 	= $this->cx->real_escape_string(trim(base64_decode($idc)));
		$idef 	= $this->cx->real_escape_string(trim(base64_decode($idef)));

		$QuoteRepo = new QuoteRepo($this->cx);
		$data = $QuoteRepo->getResultQuoteData($idc, $idef);

		if (count($data) > 0) {
			return $data;
		}

		return false;		
	}


}

?>