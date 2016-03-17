<?php

date_default_timezone_set('America/La_Paz');

require __DIR__ . '/app/controllers/DiaconiaController.php';
require __DIR__ . '/app/controllers/PolicyController.php';
require('session.class.php');

$Diaconia = new DiaconiaController();

$PolicyController = new PolicyController();

$session = new Session();
$session->getSessionCookie();
$token = $session->check_session();

$mess = array(0 => 0, 1 => 'R', 2 => 'Error');

if ($token) {
	$data = $_POST;
	$data['idef'] = $_SESSION['idEF'];
	$data['user'] = $_SESSION['idUser'];

	if ($PolicyController->postPolicy($data, $mess)) {
		
	}
} else {
	$mess[2] = 'La Póliza no puede ser registrada';
}

echo json_encode($mess);
?>