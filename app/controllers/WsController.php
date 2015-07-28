<?php

require_once __DIR__ . '/../../sibas-db.class.php';
require_once __DIR__ . '/../models/Diaconia.php';
require_once __DIR__ . '/../repositories/WsRepo.php';

class WsController extends Diaconia
{
	protected 
		$cx,
		$idef,
		$ws,
		$bc,
		$dni;

	public function __construct($idef, $ws, $bc, $dni)
	{
		$this->cx 	= new SibasDB();
		$this->idef = $this->cx->real_escape_string(trim(base64_decode($idef)));
		$this->ws 	= $ws;
		$this->bc 	= $bc;
		$this->dni 	= $this->cx->real_escape_string(trim($dni));
	}

	public function getClientData()
	{
		$WsRepo = new WsRepo($this->cx, $this->ws, $this->bc, $this->dni);
		$data 	= $WsRepo->getData();

		return $data;
	}

	private function setClientData($data)
	{
		foreach ($data as $key => $row) {
			# code...
		}
	}


}

?>