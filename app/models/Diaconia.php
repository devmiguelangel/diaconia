<?php

class Diaconia
{
	protected $cx;

	protected $certificate = 1;

	protected 
		$coverage = [
			'IM' => 'Individual/Mancomunado',
			'BC' => 'Banca Comunal',
		];

	protected
		$typeTerm = [
			'Y' => 'Años', 
			'M' => 'Meses', 
			'W' => 'Semanas',
			'D' => 'Días'
		];

	protected
		$currency = [
			'BS' 	=> 'Bolivianos', 
			'USD' 	=> 'Dolares Estadounidenses'
		];

	protected
		$moviment = [
			'PU' => 'Primera/Única', 
			'AD' => 'Adicional', 
			'LC' => 'Línea de Crédito'
		];

	protected $productCia = array();

	protected $ws = false;

	public function __construct()
	{
		$this->cx = new SibasDB();
	}

	public function getCertificate()
	{
		return $this->certificate;
	}

	public function getCoverage() {
		return $this->coverage;
	}

	public function getTypeTerm()
	{
		return $this->typeTerm;
	}

	public function getCurrency()
	{
		return $this->currency;
	}

	public function getMoviment()
	{
		return $this->moviment;
	}

	public function getDataProduct($idef, $product = 'DE')
    {
    	$sql = 'select 
			sh.edad_min,
			sh.edad_max,
			sh.max_detalle,
			sh.max_emision_bs,
			sh.max_emision_usd,
			sh.web_service as ws,
			sh.data
    	from 
    		s_sgc_home as sh
    			inner join
    		s_entidad_financiera as sef ON (sef.id_ef = sh.id_ef)
    	where
    		sef.id_ef = "' . base64_decode($idef) . '"
    			and sh.producto = "' . $product . '"
    	limit 0, 1
    	;';
    	
    	if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT)) !== false) {
    		if ($rs->num_rows === 1) {
    			$row = $rs->fetch_array(MYSQLI_ASSOC);
    			$rs->free();

    			return $row;
    		}
    	}

    	return false;
    }

	protected function getProduct($idef)
	{
		$sql = 'select 
			spc.id_prcia, 
			spc.nombre, 
			spc.id_producto, 
			spr.nombre as pr_nombre
		from
			s_producto_cia as spc
				inner join
			s_producto as spr ON (spr.id_producto = spc.id_producto)
				inner join 
			s_ef_compania as sec ON (sec.id_ef_cia = spr.id_ef_cia)
				inner join 
			s_entidad_financiera as sef ON (sef.id_ef = sec.id_ef)
		where
			spr.activado = true
				and sef.id_ef = "' . base64_decode($idef) . '"
				and sef.activado = true
				and spr.nombre = "PRODUCTO"
		order by id_prcia asc
		;';

		if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($rs->num_rows > 0) {
				while ($row = $rs->fetch_array(MYSQLI_ASSOC)) {
					$this->productCia[] = [
						'id' => $row['id_prcia'], 
						'producto' => $row['nombre']
					];
				}
			}
		}

		return $this->productCia;
	}

	public function getRateExchange($flag = false)
	{
		$sql = 'select id_tc, valor_boliviano 
		from 
	    	s_tipo_cambio 
    	where activado = true 
    	limit 0, 1 ;';

		if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT))) {
			if ($rs->num_rows === 1) {
				$row = $rs->fetch_array(MYSQLI_ASSOC);
				$rs->free();

				if ($flag === false) {
					return $row['id_tc'];
				} else {
					return $row['valor_boliviano'];
				}
			}
		}

		return 0;
	}

	public function checkAmount($amount, $currency, $idef, &$data_amount, $product = 'DE')
	{
		$sql = 'select 
		    sh.max_cotizacion_usd AS maxc_usd,
		    sh.max_cotizacion_bs AS maxc_bs,
		    sh.max_emision_usd AS maxe_usd,
		    sh.max_emision_bs AS maxe_bs
		from
		    s_sgc_home AS sh
		        INNER JOIN
		    s_entidad_financiera AS sef ON (sef.id_ef = sh.id_ef)
		where
		    sh.producto = "' . $product . '"
		    	and sef.id_ef = "' . base64_decode($idef) . '"
		        and sef.activado = TRUE
		limit 0 , 1
		;';

		if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($rs->num_rows === 1) {
				$row = $rs->fetch_array(MYSQLI_ASSOC);
				$data_amount = $row;
				$rs->free();

				switch ($currency) {
				case 'BS':
					if ($amount <= $row['maxe_bs']) {
						return true;
					} else {
						$data_amount['amount'] = $row['maxe_bs'];
					}
					break;
				case 'USD':
					if ($amount <= $row['maxe_usd']) {
						return true;
					} else {
						$data_amount['amount'] = $row['maxe_usd'];
					}
					break;
				}
			}
		}

		return false;
	}

	public function getNumberClients($idc, $idef, $flag, $pr = 'DE')
	{
		$sql = 'select 
		    sdc.id_cotizacion, 
		    count(scl.id_cliente) as numCl
		from
		    s_de_cot_cabecera as sdc
		        inner join
		    s_de_cot_detalle as sdd ON (sdd.id_cotizacion = sdc.id_cotizacion)
		        inner join
		    s_de_cot_cliente as scl ON (scl.id_cliente = sdd.id_cliente)
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sdc.id_ef)
		where
		    sdc.id_cotizacion = "' . $idc . '"
		        and sef.id_ef = "' . $idef . '"
		        and sef.activado = true
	    limit 0, 1
		;';
		
		if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT))) {
			$row = $rs->fetch_array(MYSQLI_ASSOC);
			$rs->free();
			
			$nCl = (int)$row['numCl'];
			
			if ($flag === false) {
				if ($nCl === 0) {
					return 'DD';
				} elseif($nCl > 0) {
					return 'CC';
				}
			} else {
				return $nCl;
			}
		}

		return '';
	}

	public function getPolicy($idef, $product = 'DE')
	{
		$idef = $this->cx->real_escape_string(trim(base64_decode($idef)));
		$data = array();

		$sql = 'select 
			sp.id_poliza, sp.no_poliza
		from
			s_poliza sp
				inner join
			s_ef_compania as sec ON (sec.id_ef_cia = sp.id_ef_cia)
				inner join
			s_compania as scia ON (scia.id_compania = sec.id_compania)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = sec.id_ef)
		where
			sef.id_ef = "' . $idef . '"
				and sef.activado = true
				and sp.producto = "' . $product . '"
				and curdate() between sp.fecha_ini and sp.fecha_fin
		;';
		
		if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT))) {
			if ($rs->num_rows > 0) {
				while ($row = $rs->fetch_array(MYSQLI_ASSOC)) {
					$data[] = $row;
				}
			}
		}
		
		return $data;
	}

	public function getRegistrationNumber($idef, $product, $token, $prefix = '') 
	{
		$arrTable = array (
			'DE'	=> array (0 => 's_de_cot_cabecera',		1 => 's_de_em_cabecera',	2 => ''),
			'AU'	=> array (0 => 's_au_cot_cabecera',		1 => 's_au_em_cabecera',	2 => 's_au_em_detalle'),
			'TRD'	=> array (0 => 's_trd_cot_cabecera',	1 => 's_trd_em_cabecera',	2 => 's_trd_em_detalle'),
			'TRM'	=> array (0 => 's_trm_cot_cabecera',	1 => 's_trm_em_cabecera',	2 => 's_trm_em_detalle'),
			'TH'	=> array (0 => 's_th_cot_cabecera',		1 => ''),
		);
		
		$field = $table = '';
		$fieldPrefix = 'tbl1.prefijo';
		$table = $arrTable[$product][$token];
		$flag = true;
		
		switch ($token) {
		case 0:
			$field = 'tbl1.no_cotizacion';
			$flag = false;
			break;
		case 1:
			$field = 'tbl1.no_emision';
			break;
		case 2:
			$field = 'tbl2.no_detalle';
			$fieldPrefix = 'tbl2.prefijo';
			$table = $arrTable[$product][1];
			break;
		}

		$sql = 'select 
		    max(' . $field . ') + 1 as record
		from
		    ' . $table . ' as tbl1 ';
		if ($token === 2) {
			$sql .= '
				inner join
		    ' . $table = $arrTable[$product][2] . ' as tbl2 ON (tbl2.id_emision = tbl1.id_emision) ';
		}
		$sql .= '
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = tbl1.id_ef)
		where
		    sef.id_ef = "' . base64_decode($idef) . '"
				and sef.activado = true ';
		if ($token > 0 || empty($prefix) === false) {
			$sql .= '
				and ' . $fieldPrefix . ' = "' . $prefix . '" ';
		}
		$sql .= ';';
		
		if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($rs->num_rows === 1) {
				$row = $rs->fetch_array(MYSQLI_ASSOC);
				if ($row['record'] === null) {
					return 1;
				} else {
					return (int)$row['record'];
				}
			}
		}

		return 0;
	}
	
}

?>