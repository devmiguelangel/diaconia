<?php
class Session{
				
	public function remove_session(){
		session_unset();
		session_destroy();
		session_regenerate_id(true);
	}

}
?>