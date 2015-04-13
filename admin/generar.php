<?php
  $prefijo='@S#1$2013';
  $valor='';
  $valor=uniqid($prefijo,true);
  echo $valor;
  echo'<br/>';
  echo crypt_blowfish_bycarluys('pw4rootadmin');
  //generar password
  function crypt_blowfish_bycarluys($password, $digito = 7) {
		$set_salt = './1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		$salt = sprintf('$2a$%02d$', $digito);
		for($i = 0; $i < 22; $i++){
			$salt .= $set_salt[mt_rand(0, 63)];
		}
		return crypt($password, $salt);
	}
?>