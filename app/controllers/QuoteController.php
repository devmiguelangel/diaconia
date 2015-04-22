<?php 

require_once __DIR__ . '/../../sibas-db.class.php';
require_once __DIR__ . '/../models/Diaconia.php';
require __DIR__ . '/../repositories/QuoteRepo.php';

class QuoteController extends Diaconia
{
	protected $cx;
	
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
				$data['record'] = $this->getRegistrationNumber($_SESSION['idEF'], 'DE', 0);

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

	public function setDataBcCot($idc)
	{
		$amount = $percentage = $tasa = $tasa_pr = 0;

    	$sql = 'select 
			sdd.id_detalle,
			sdd.monto_banca_comunal,
			std.tasa_final as tasa_producto
		from s_de_cot_cabecera as sdc
			inner join 
				s_de_cot_detalle as sdd on (sdd.id_cotizacion = sdc.id_cotizacion)
			inner join 
				s_de_cot_cliente as scl on (scl.id_cliente = sdd.id_cliente)
			inner join 
				s_producto_cia as spc on (spc.id_prcia = sdc.id_prcia)
			inner join 
				s_tasa_de as std on (std.id_prcia = spc.id_prcia)
		where sdc.id_cotizacion = "' . $idc . '"
		;';

		if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($rs->num_rows > 0) {
				$err = false;

				while ($row = $rs->fetch_array(MYSQLI_ASSOC)) {
					$amount += $row['monto_banca_comunal'];
					$tasa_pr = $row['tasa_producto'];
				}

				$sql = 'update s_de_cot_cabecera as sdc
				set sdc.monto = ' . $amount . '
				where sdc.id_cotizacion = "' . $idc . '"
				;';

				if ($this->cx->query($sql) === true) {
					if ($rs->data_seek(0) === true) {
						while ($row = $rs->fetch_array(MYSQLI_ASSOC)) {
							$percentage = 0;
							$tasa = 0;

							if (empty($amount) === false) {
								$percentage = ($row['monto_banca_comunal'] / $amount) * 100;
								$tasa = ($percentage * $tasa_pr) / 100;
							}
							
							$sql = 'update s_de_cot_detalle as sdd
							set 
								sdd.porcentaje_credito = ' . $percentage . ',
								sdd.tasa = ' . $tasa . '
							where sdd.id_detalle = "' . $row['id_detalle'] . '"
							;';

							if ($this->cx->query($sql) === false) {
								$err = true;
							}
						}
					}
				} else {
					$err = true;
				}

				if ($err === false) {
					return true;
				}
			}
		}

		return false;
	}

	public function getRootUser()
	{
		$sql = 'select 
			su.id_usuario, 
			su.usuario
		from
			s_usuario as su
				inner join
			s_usuario_tipo as sut ON (sut.id_tipo = su.id_tipo)
		where
			sut.codigo = "ROOT"
		limit 0 , 1
		;';
		
		if(($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT))){
			if($rs->num_rows === 1) {
				$row = $rs->fetch_array(MYSQLI_ASSOC);
				$rs->free();

				return $row;
			}
		}
		
		return false;
	}


}

?>