<?php

require __DIR__ . '/session.class.php';
require __DIR__ . '/app/controllers/QuoteController.php';

$session = new Session();
$session->getSessionCookie();
$token = $session->check_session();

$Quote = new QuoteController();

$mess = array(
	0 => 0, 
	1 => 'R', 
	2 => 'Error: No se pudo procesar la Cotización'
);

if($token === false){
	if(($user = $Quote->getRootUser()) !== false) {
		$_SESSION['idUser'] = base64_encode($user['id_usuario']);
	} else {
		$mess[0] = 1;
		$mess[1] = 'logout.php';
		$mess[2] = 'La Cotización no puede ser registrada, intentelo mas tarde';
	}
}

$data = $_POST;
$data['idef'] = $_SESSION['idEF'];
$data['user'] = $_SESSION['idUser'];

if ($Quote->postQuote($data, $mess)) {
	
}

echo json_encode($mess);

?>