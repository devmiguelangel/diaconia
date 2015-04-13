<?php
require('sibas-db.class.php');
require('session.class.php');

$arrUSR = array(0 => 0, 1 => 'R', 2 => '');

if(isset($_POST['l-user']) && isset($_POST['l-pass'])){
	$link = new SibasDB();
	$user = $link->real_escape_string(trim($_POST['l-user']));
	$pass = $link->real_escape_string(trim($_POST['l-pass']));
	
	$flag = FALSE;
	$ms = '';
	$page = '';
	$ide = '';
	if(isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['ide'])){
		$ms = $link->real_escape_string(trim($_POST['ms']));
		$page = $link->real_escape_string(trim($_POST['page']));
		$ide = $link->real_escape_string(trim($_POST['ide']));
		$flag = TRUE;
	}
	
	$sqlUser = 'select 
			su.id_usuario,
			su.usuario,
			su.password,
			sut.id_tipo as tipo,
			sut.codigo as tipo_codigo,
			sef.id_ef,
			sef.nombre as ef_nombre,
			sef.activado as ef_activado,
			su.cambio_password as cw
		from
			s_usuario as su
				inner join
			s_usuario_tipo as sut ON (sut.id_tipo = su.id_tipo)
				inner join
			s_ef_usuario as seu ON (seu.id_usuario = su.id_usuario)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = seu.id_ef)
		where
			su.usuario = "'.$user.'"
				and su.activado = true
				and sef.activado = true
		order by sef.id_ef asc
		limit 0 , 1
		;';
	
	if (($rs = $link->query($sqlUser,MYSQLI_STORE_RESULT))) {
		if($rs->num_rows === 1){
			$rowUSR = $rs->fetch_array(MYSQLI_ASSOC);
			$rs->free();
			
			$token = FALSE;
			if((int)$rowUSR['tipo_codigo'] === 'ROOT') {
				$token = TRUE;
			} else {
				if((boolean)$rowUSR['ef_activado'] === TRUE) {
					$token = TRUE;
				}
			}
			
			if($token === TRUE){
				if(crypt($pass, $rowUSR['password']) == $rowUSR['password']){
					$session = new Session();
					$session->start_session($rowUSR['id_usuario'], $rowUSR['id_ef']);
					
					$arrUSR[0] = 1;
					if($flag === FALSE) {
						$arrUSR[1] = 'index.php';
					} elseif($rowUSR['tipo_codigo'] === 'FAC' || $rowUSR['tipo_codigo'] === 'IMP') {
						$arrUSR[1] = 'index.php?ms='.$ms.'&page='.$page.'&ide='.$ide;
					} else {
						$arrUSR[1] = 'index.php';
					}
					
					if ((boolean)$rowUSR['cw'] === false) {
						$arrUSR[1] = 'index.php?ms='.md5('MS_COMP').'&page='.md5('P_change_pass').
							'&user='.base64_encode($rowUSR['id_usuario']).'&url='.base64_encode($arrUSR[1]).
							'&c-p='.md5('true');
					}
					
					$arrUSR[2] = 'Bienvenido';
				} else {
					$arrUSR[2] = 'La Contraseña es incorrecta';
					}
			} else {
				$arrUSR[2] = 'Usted no puede Iniciar Sesión';
			}
		} else {
			$arrUSR[2] = 'El Usuario no existe';
		}
	} else {
		
	}
	$link->close();
	echo json_encode($arrUSR);
}else{
	$arrUSR[2] = 'Intente de nuevo';
	echo json_encode($arrUSR);
}

function crypt_blowfish_bycarluys($password, $digito = 7) {
	//	El salt para Blowfish debe ser escrito de la siguiente manera: 
	//	$2a$, $2x$ o $2y$ + 2 números de iteración entre 04 y 31 + 22 caracteres
	$set_salt = './1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	$salt = sprintf('$2a$%02d$', $digito);
	
	for($i = 0; $i < 22; $i++){
		$salt .= $set_salt[mt_rand(0, 63)];
	}
	
	return crypt($password, $salt);
}

//echo crypt_blowfish_bycarluys('aperez123');
?>