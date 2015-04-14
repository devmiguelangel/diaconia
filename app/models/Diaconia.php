<?php

class Diaconia
{
	protected $cx;
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

	protected $productCia = array();

	public function __construct()
	{
		$this->cx = new SibasDB();
	}

	public function getCoverage() {
		return $this->coverage;
	}

	public function getTypeTerm()
	{
		return $this->typeTerm;
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

	public function checkAmount($amount, $currency, $idef, $product = 'DE')
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
				$rs->free();

				switch ($currency) {
				case 'BS':
					if ($amount <= $row['maxe_bs']) {
						return true;
					}
					break;
				case 'USD':
					if ($amount <= $row['maxe_usd']) {
						return true;
					}
					break;
				}
			}
		}

		return false;
	}
	
}

?>