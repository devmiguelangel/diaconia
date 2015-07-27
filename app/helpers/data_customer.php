<?php 
// ini_set('display_errors', 1);
require __DIR__ . '/../../sibas-db.class.php';
require __DIR__ . '/../../app/repositories/WsRepo.php';

$res = array(
	'error' => 'La conexión a fallado'
);

if (isset($_GET['ws'], $_GET['bc'], $_GET['dni'])) {
	$cx 	= new SibasDB();
	$ws		= (boolean)$cx->real_escape_string(base64_decode($_GET['ws']));
	$bc		= (boolean)$cx->real_escape_string(base64_decode($_GET['bc']));
	$dni	= $cx->real_escape_string($_GET['dni']);

	$ws = new WsRepo($cx);
	$res[] = $ws->getData($ws, $bc, $dni);
}

echo json_encode($res);

/*var_dump($res);
exit();*/

?>