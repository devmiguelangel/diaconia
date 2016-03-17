<?php

require __DIR__ . '/app/controllers/QuestionController.php';

$Question = new QuestionController();

$mess = array(
	0 => 0, 
	1 => 'R', 
	2 => 'Error.'
);

$data = $_POST;

if ($Question->postQuestion($data, $mess)) {
	
}

echo json_encode($mess);

?>