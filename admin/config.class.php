<?php
require '../configuration.class.php';
class SibasDB extends MySQLi
{
	private $config, $host, $user, $password, $db, $sql, $rs, $row;
	
	public function SibasDB()
	{
		/*$self = strtolower($_SERVER['HTTP_HOST']);
		$res = strpos($self, 'abrenet.com');
		
		if($res !== FALSE){
			$this->user = 'admin';
			$this->password = 'CoboserDB@3431#';
		}else{
			$this->user = 'root';
			$this->password = '';
		}
		
		$this->host = 'localhost';
		$this->db = 'sibas';*/

		$this->config = new ConfigurationSibas();
		$this->host = $this->config->host;
		$this->user = $this->config->user;
		$this->password = $this->config->password;
		$this->db = $this->config->db;
		
		parent::__construct($this->host, $this->user, $this->password, $this->db);
		
		if(mysqli_connect_error()){
			die('Error de Conexion (' .mysqli_connect_errno().' ) '.mysqli_connect_error());
		}
	}
}
?>