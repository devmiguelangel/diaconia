<?php 

require __DIR__ . '/../models/Diaconia.php';
require_once __DIR__ . '/../../sibas-db.class.php';

class DiaconiaController extends Diaconia
{
	
	public function __construct()
	{
		$this->cx = new SibasDB();
	}
	
	public function getProductData($idef)
	{
		return $this->getProduct($idef);
	}

	

}

?>