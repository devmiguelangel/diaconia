<?php

require __DIR__ . '/app/controllers/ClientController.php';

$Client = new ClientController();

$mess = [
    0 => 0,
    1 => 'R',
    2 => 'Error.'
];

$data       = $_POST;
$data['dc'] = '';

if ($Client->postClient($data, $mess)) {

}

echo json_encode($mess);

?>