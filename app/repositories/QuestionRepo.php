<?php

class QuestionRepo
{
	protected $cx;
	
	public function __construct($cx)
	{
		$this->cx = $cx;
	}

	public function getQuestionData($idef, $product)
	{
		$data = array();

		$sql = 'select 
		    spr.id_pregunta,
		    spr.orden,
		    spr.pregunta,
		    spr.respuesta
		from
		    s_pregunta as spr
		        inner join
		    s_ef_compania as sec ON (sec.id_ef_cia = spr.id_ef_cia)
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sec.id_ef)
		        inner join
		    s_compania as scia ON (scia.id_compania = sec.id_compania)
		where
		    sef.id_ef = "' . $idef . '"
		        and sef.activado = true
		        and scia.activado = true
		        and spr.producto = "' . $product . '"
		        and spr.activado = true
		order by spr.orden asc
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

	public function postQuestionData($data_qs, $idd, $resp, $flag)
	{
		$sql = 'insert into s_de_cot_respuesta 
		(id_respuesta, id_detalle, respuesta, observacion) 
		values ';

		$k = 0;

		foreach ($data_qs as $key => $qs) {
			$k += 1;
			if ($flag[$k] === false) {
				$resp[$k] = '';
			}

			$sql .= '("' . uniqid('@S#1$2013-' . $k, true) . '", 
			"' . $idd[$k] . '", 
			"' . $this->cx->real_escape_string(json_encode($qs)) . '", 
			"' . $resp[$k] . '"),';
		}

		$sql = trim($sql, ',') . ';';
		
		if ($this->cx->query($sql)) {
			return true;
		}

		return false;
	}

}

?>