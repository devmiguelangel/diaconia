<?php
class Session{
	private $user_agent, $skey, $ip_address, $last_activity;
	public $idUser, $idEF, $session_time;
	
	function Session(){
		session_start();
		
		if(isset($_SESSION['sKey'])) $this->skey = $_SESSION['sKey'];
		else $this->skey = '';
		
		if(isset($_SESSION['idUser'])) $this->idUser = $_SESSION['idUser'];
		else $this->idUser = '';
		
		if(isset($_SESSION['idEF'])) $this->idEF = $_SESSION['idEF'];
		else $this->idEF = '';
		
		if(isset($_SESSION['ipAddress'])){
			$this->ip_address = $_SESSION['ipAddress'];
		}else{
			$this->ip_address = $this->get_ip_address();
		}
		
		if(isset($_SESSION['userAgent'])) $this->user_agent = $_SESSION['userAgent'];
		else $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
		
		if(isset($_SESSION['lastActivity'])) $this->last_activity = $_SESSION['lastActivity'];
		else $this->last_activity = '';

		if (isset($_SESSION['session_time'])) $this->session_time = $_SESSION['session_time'];
		else $this->session_time = 0;
	}
	
	public function start_session($idUser, $idEF)
	{
		$_SESSION['idUser'] = base64_encode($idUser);
		$_SESSION['idEF'] = base64_encode($idEF);
		$_SESSION['sKey'] = md5(uniqid(mt_rand(), true));
		$_SESSION['ipAddress'] = $this->ip_address;
		$_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
		$_SESSION['lastActivity'] = $_SERVER['REQUEST_TIME'];
		$_SESSION['session_time'] = 30000;
	}
	
	public function check_session(){
		if (isset($_SESSION['sKey']) 
			&& isset($_SESSION['ipAddress']) 
			&& isset($_SESSION['idUser']) 
			&& isset($_SESSION['idEF'])
			&& isset($_SESSION['session_time'])) {
				
			if ($_SESSION['ipAddress'] === $this->get_ip_address() 
				&& $_SESSION['userAgent'] === $this->user_agent) {
				return true;
			}
		} else {
			return false;
		}
	}

	public function setSessionCookie() 
	{
		if (!isset($_COOKIE['IdeproUser']) 
			&& empty($_SESSION['idUser']) === false
			&& isset($_SESSION['idEF'])) {
			//setcookie('IdeproUser[user]', $_SESSION['idUser'], time() + 1 * 24 * 60 * 60);
			setcookie('IdeproUser[user]', $_SESSION['idUser'], 0, '/', '', false, true);
			setcookie('IdeproUser[ef]', $_SESSION['idEF'], 0, '/', '', false, true);

			return true;
		} else {
			//$this->remove_session();
			return false;
		}
	}

	public function getSessionCookie()
	{
		if (isset($_COOKIE['IdeproUser']) 
			&& empty($_SESSION['idUser']) === true) {
			$data_idepro = $_COOKIE['IdeproUser'];
			/*$ideproUser['user'] = htmlspecialchars($_COOKIE['IdeproUser[user]']);
			$ideproUser['ef'] = htmlspecialchars($_COOKIE['IdeproUser[ef]']);*/
			$idepro_user = htmlspecialchars($data_idepro['user']);
			$idepro_ef = htmlspecialchars($data_idepro['ef']);
			$this->start_session(base64_decode($idepro_user), base64_decode($idepro_ef));
		}
	}
	
	public function remove_session()
	{
		session_unset();
		session_destroy();
		session_regenerate_id(true);

		if (isset($_COOKIE['IdeproUser'])) {
			setcookie('IdeproUser', '', time() - 3600, '/', '', false, true);
			setcookie('IdeproUser[user]', '', time() - 3600, '/', '', false, true);
			setcookie('IdeproUser[ef]', '', time() - 3600, '/', '', false, true);
			/*setcookie('IdeproUser[user]', '', time() - 3600);
			setcookie('IdeproUser[ef]', '', time() - 3600);
			*/
		}
	}
	
	private function get_ip_address(){
		$ip = '';
		if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		return $ip;
	}
}
?>