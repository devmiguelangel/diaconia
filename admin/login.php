<?php
include('config.class.php');
include('sgc_funciones.php');
$base_datos = new DB_bisa();
$conexion = $base_datos->connectDB();

if(isset($_POST['token-login']) && isset($_POST['da-login-username'])){
		
	$usuario = mysql_real_escape_string($_POST['da-login-username']);
	$password = $_POST['da-login-password'];
	//$password_crypt = crypt($_POST['fl-pass'], $password);
	
	$queryLogin = "select
				   s_us.id_usuario,
				   s_us.usuario,
				   s_us.password,
				   s_us.nombre,
				   s_us.id_tipo,
				   s_ust.tipo
				from
				  s_usuario as s_us
				  inner join s_usuario_tipo as s_ust on (s_ust.id_tipo=s_us.id_tipo)
				where
				  s_us.usuario='".$usuario."' and s_us.activado=1 and s_us.id_tipo!=5;";
	$rsUs = mysql_query($queryLogin, $conexion);
	$num = mysql_num_rows($rsUs);
		
	if($num == 1){
		$rowLogin = mysql_fetch_array($rsUs);
		if(crypt($password, $rowLogin['password']) == $rowLogin['password']){
			session_start();
			$_SESSION['id_usuario_sesion']  = $rowLogin['id_usuario'];
			$_SESSION['usuario_sesion'] = $rowLogin['usuario'];
			$_SESSION['tipo_sesion'] = $rowLogin['tipo'];
			//$rsLogin->close();
			echo md5('1');
		}else{
			//$rsLogin->close();
			echo md5('2');
		}
	}else{
		//$rsLogin->close();
		echo md5('2');
	}
	//$cx->mysqli->close();
}else{
	echo md5('0');
}

?>