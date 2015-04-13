<?php
include('sgc_funciones.php');
require('session.class.php');
require_once('config.class.php');
$conexion = new SibasDB();
//TENGO Q VER SI EL USUARIO HA INICIADO SESION
if(isset($_SESSION['usuario_sesion']) && isset($_SESSION['tipo_sesion'])) {
	//SI EL USUARIO HA INICIADO SESION, MOSTRAMOS LA PAGINA
	//mostrar_pagina($_SESSION['id_usuario'], $_SESSION['tipo'], $_SESSION['usuario'], $conexion, $num_emision_des);
	header('Location: index.php?l=escritorio');
	exit;
} else {
	//SI EL USUARIO NO HA INICIADO SESION, VEMOS SI HA HECHO CLICK EN EL FORMULARIO DE LOGIN
	if(isset($_POST['username'])) {
		//SI HA HECHO CLICK EN EL FORM DE LOGIN, VALIDAMOS LOS DATOS Q HA INGRESADO
		if(validar_login($conexion)) {
			//SI LOS DATOS DEL FORM SON CORRECTOS, MOSTRAMOS LA PAGINA
			//mostrar_pagina($_SESSION['usuario'], $_SESSION['tipo'], $conexion);
			header('Location: index.php?l=escritorio');
			exit;
		} else {
			mostrar_login_form(2);
		}
	} else {
		mostrar_login_form(1);
	}
}
?>