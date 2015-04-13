<?php
require('sibas-db.class.php');
if (isset($_GET['user']) && isset($_GET['pass'])) {
	$link = new SibasDB();
	
	$idUser = $link->real_escape_string(trim(base64_decode($_GET['user'])));
	$pass = $link->real_escape_string(trim($_GET['pass']));
	
	$sql = 'select 
		su.id_usuario as idUser,
		su.usuario as u_usuario,
		su.password as u_password
	from
		s_usuario as su
	where
		su.id_usuario = "'.$idUser.'"
	';
	
	if (($rs = $link->query($sql, MYSQLI_STORE_RESULT))) {
		if ($rs->num_rows === 1) {
			$row = $rs->fetch_array(MYSQLI_ASSOC);
			$rs->free();
			
			if(crypt($pass, $row['u_password']) === $row['u_password']) {
				echo 1;
			} else {
				echo 0;
			}
		}
	}
}
?>