<?php

require_once __DIR__ . '/../../sibas-db.class.php';
require_once __DIR__ . '/../models/Diaconia.php';
require_once __DIR__ . '/../repositories/WsRepo.php';

class WsController extends Diaconia
{
	protected 
		$cx,
		$dni,
		$idef,
		$ws;

	public function __construct($dni, $idef, $ws)
	{
		$this->cx 	= new SibasDB();
		$this->dni 	= $this->cx->real_escape_string(trim($dni));
		$this->idef = $this->cx->real_escape_string(trim(base64_decode($idef)));
		$this->ws 	= $ws;
	}

	public function getClientData()
	{
		$WsRepo = new WsRepo($this->cx);

		$clients	= array();
		$data 		= array();

		if ($this->ws) {
			// $clients = $this->dataClientWS();
		} else {
			if ($WsRepo->dataClientDB($this->dni, $this->idef)) {
				return $WsRepo->getData();
			}
		}

		return false;
	}

	private function setClientData($data)
	{
		foreach ($data as $key => $row) {
			# code...
		}
	}


}

?>