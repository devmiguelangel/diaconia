<?php
//ELIMINAMOS LOS DATOS DE SESION
session_unset($_SESSION['id_usuario_sesion']);
session_unset($_SESSION['usuario_sesion']);
session_unset($_SESSION['tipo_sesion']);
$CookieInfo = session_get_cookie_params();
if ( (empty($CookieInfo['domain'])) && (empty($CookieInfo['secure'])) ) {
	setcookie(session_name(), '', time()-3600, $CookieInfo['path']);
}elseif (empty($CookieInfo['secure'])) {
	setcookie(session_name(), '', time()-3600, $CookieInfo['path'], $CookieInfo['domain']);
}else{
	setcookie(session_name(), '', time()-3600, $CookieInfo['path'], $CookieInfo['domain'], $CookieInfo['secure']);
}
session_destroy();
session_regenerate_id(true);
//VAMOS A LA PAGINA PRINCIPAL DEL SISTEMA DE GESTION DE CONTENIDOS
header("location: index.php");
exit;
?>