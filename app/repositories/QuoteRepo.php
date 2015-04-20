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