<?php

require_once __DIR__ . '/../../sibas-db.class.php';
require_once __DIR__ . '/../models/Diaconia.php';
require_once __DIR__ . '/../repositories/QuestionRepo.php';

class QuestionController extends Diaconia
{
	protected $cx;
	
	public function __construct()
	{
		$this->cx = new SibasDB();
	}

	public function getQuestion($idef, $product = 'DE')
	{
		$idef = $this->cx->real_escape_string(trim(base64_decode($idef)));
		$QuestionRepo = new QuestionRepo($this->cx);

		$data = $QuestionRepo->getQuestionData($idef, $product);

		if (count($data) > 0) {
			return $data;
		}

		return false;
	}

	public function postQuestion($data, &$mess)
	{
		$pr = $this->cx->real_escape_string(trim(base64_decode($data['pr'])));

		if ($pr === 'DE|03') {
			$ms 	= $this->cx->real_escape_string(trim($data['ms']));
			$page 	= $this->cx->real_escape_string(trim($data['page']));
			$idc 	= $this->cx->real_escape_string(trim(base64_decode($data['dq-idc'])));
			$idef 	= $this->cx->real_escape_string(trim(base64_decode($data['dq-idef'])));
			$n_cl 	= $this->cx->real_escape_string(trim($data['n_cl']));

			if (($data_pr = $this->getDataProduct(base64_encode($idef))) !== false) {
				$flag 	= array();
				$resp 	= array();
				$idd 	= array();
				$data_qs = array();

				for ($i = 1; $i <= $n_cl; $i++) {
					$flag[$i] 	= false;
					$resp[$i] 	= $this->cx->real_escape_string(trim($data['dq-resp-' . $i]));
					$idd[$i] 	= $this->cx->real_escape_string(trim(base64_decode($data['dq-idd-' . $i])));

					$data_qs[$i]	= array();
				}

				if (($questions = $this->getQuestion(base64_encode($idef))) !== false) {
					for ($k=1; $k <= $n_cl; $k++) {
						$i = 0;
						
						foreach ($questions as $key => $question) {
							$i += 1;

							$value = $this->cx->real_escape_string(trim($data['dq-qs-' . $k 
								. '-' . $question['id_pregunta']]));
							
							if($question['respuesta'] !== $value) {
								$flag[$k] = true;
							}
							
							$data_qs[$k][$question['orden']] = array(
								'id' 	=> (int)$question['id_pregunta'],
								'value'	=> (int)$value	
							);
						}
					}

					$QuestionRepo = new QuestionRepo($this->cx);

					if ($QuestionRepo->postQuestionData($data_qs, $idd, $resp, $flag)) {
						$mess[0] = 1;
						$mess[1] = 'de-quote.php?ms=' . $ms . '&page=' . $page 
							. '&pr=' . base64_encode('DE|04') . '&idc=' . base64_encode($idc);
						$mess[2] = 'Las respuestas se registraron correctamente';

						return true;
					} else {
						$mess[2] = 'Error al registrar las respuestas';
					}
				} else {
					$mess[2] = 'No existen Preguntas';
				}
			} else {
				$mess[2] = 'No se pueden registrar la respuestas';
			}
		} else {
			$mess[2] = 'Respuestas incompletas';
		}

		return false;
	}

}

?>