<?php

class QuoteRepo
{
	protected $cx;
	
	public function __construct($cx)
	{
		$this->cx = $cx;
	}

	public function postQuoteRepo(&$data)
	{
		$data['idc'] 	= uniqid('@S#1$2013',true);
		$data['record'] = $this->getRegistrationNumber($_SESSION['idEF'], 'DE', 0);

		$sql = 'insert into s_de_cot_cabecera 
		(id_cotizacion, no_cotizacion, id_ef, 
			certificado_provisional, cobertura, id_prcia, 
			monto, moneda, plazo, tipo_plazo, id_tc, 
			fecha_creacion, id_usuario, act_usuario, 
			fecha_actualizacion, modalidad)
		values
		("' . $data['idc'] . '", "' . $data['record'] . '", 
			"' . base64_decode($data['idef']) . '", false, 
			"' . $data['coverage'] . '", "' . $data['product'] . '", 
			"' . $data['amount'] . '", "' . $data['currency'] . '", 
			"' . $data['term'] . '", "' . $data['type_term'] . '", 
			"' . $data['tc'] . '", curdate(), 
			"' . base64_decode($data['user']) . '", 
			"' . base64_decode($data['user']) . '", 
			curdate(), ' . $data['modality'] . ' )
		;';
		
		if ($this->cx->query($sql)) {
			return true;
		}

		return false;
	}

	private function getRegistrationNumber($idef, $product, $token, $prefix = '') 
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

	public function getResultQuoteData($idc, $idef)
	{
		$data = array();

		$sql = 'select 
			sdc.id_cotizacion,
			sec.id_ef_cia,
			sdc.id_prcia,
			scia.id_compania as idcia,
			scia.nombre as cia_nombre,
			scia.logo as cia_logo,
			sdc.monto as valor_asegurado,
			sdc.moneda,
			st.tasa_final as t_tasa_final,
			sdc.modalidad,
			sum(sdd.tasa) as tasa_final
		from
			s_de_cot_cabecera as sdc
				inner join 
			s_de_cot_detalle as sdd ON (sdd.id_cotizacion = sdc.id_cotizacion)
				inner join
			s_producto_cia as spc ON (spc.id_prcia = sdc.id_prcia)
				inner join
			s_ef_compania as sec ON (sec.id_ef_cia = spc.id_ef_cia)
				inner join
			s_compania as scia ON (scia.id_compania = sec.id_compania)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = sec.id_ef)
				inner join
			s_tasa_de as st ON (st.id_prcia = spc.id_prcia)
				inner join
			s_producto as spr ON (spr.id_producto = spc.id_producto)
		where
			sdc.id_cotizacion = "' . $idc . '"
				and sef.id_ef = "' . $idef . '"
				and sef.activado = true
				and sec.producto = "DE"
				and scia.activado = true
				and spr.activado = true
		group by scia.id_compania
		;';

		if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($rs->num_rows > 0) {
				while ($row = $rs->fetch_array(MYSQLI_ASSOC)) {
					$data[] = $row;
				}
			}
		}

		return $data;
	}

}

?>