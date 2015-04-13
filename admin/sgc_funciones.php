<?php
//FUNCION QUE PERMITE GENERAR UN ID PREFIJO UNICO
function generar_id_codificado($prefijo){
	$valor='';
	$valor=uniqid($prefijo,true);
	return $valor;
}

//FUNCION PARA MOSTRAR EL FORMULARIO DE USUARIO / CONTRASEÑA
function mostrar_login_form($num) {
	$usuario='';

	$especiales = array("<", ">");
	$reemplazos = array("", "");
	$usuario = str_replace($especiales, $reemplazos, $usuario);
    //echo $num_emision;
?>	
        <div id="da-login">
			<div id="da-login-box-wrapper">
				<div id="da-login-top-shadow">
				</div>
				<div id="da-login-box">
					<div id="da-login-box-header">
                    <?php
					  if($num==1){
						echo'<h1 lang="es">Ingreso</h1>';
					  }elseif($num==2){
					     echo'<h2 lang="es" style="font-size:12px; color:#d44d24; font-weight:bold;">Datos incorrectos</h2>';
					  }
					 ?> 	
			        </div>
					<div id="da-login-box-content">
						<form id="da-login-form" method="post" action="">
							<div id="da-login-input-wrapper">
								<div class="da-login-input">
									<input lang="es" type="text" name="username" id="da-login-username" placeholder="Usuario" autocomplete="off"/>
								</div>
								<div class="da-login-input">
									<input lang="es" type="password" name="password" id="da-login-password" placeholder="Contrase&ntilde;a" />
								</div>
							</div>
							<div id="da-login-button">
								<input lang="es" type="submit" value="Ingresar" id="da-login-submit" />
							</div>
						</form>
					</div>
					<div id="da-login-box-footer">
						<div id="da-login-tape"></div>
					</div>
				</div>
				<div id="da-login-bottom-shadow">
				</div>
			</div>
			<!--<div style="padding-top:40px; padding-left:100px; padding-right:100px;">
			 <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
			   <tr>
			    <td style="width:8%;"><a href="#" onclick="window.lang.change('es'); return false;">Espa&ntilde;ol</a></td>
			    <td style="width:8%;"><a href="#" onclick="window.lang.change('en'); return false;">English(US)</a></td>
                <td style="width:84%;">&nbsp;</td>
			   </tr>
			 </table>
			</div>-->
		</div>
<?php        
}


//FUNCION PARA VER SI UN USUARIO HA INICIADO SESION EN EL SISTEMA DE GESTION DE CONTENIDOS
function validar_login($conexion) {

	//SI EL USUARIO HA INGRESADO NOMBRE Y PASSWORD, VALIDAMOS ESOS DATOS CONTRA LA BASE DE DATOS
	if(!empty($_POST['username']) && !empty($_POST['password'])) {

		if($return_dato = checkear_usuario($_POST['username'], $_POST['password'], $conexion)) {
			//Y RETORNAMOS TRUE
			return true;
		} else {
			//SI EL USUARIO NO EXISTE O LOS DATOS SON INCORRECTOS, RETORNAMOS FALSE
			return false;
		}

	//SI EL USUARIO NO HA INGRESADO NOMBRE DE USUARIO O PASSWORD, MOSTRAMOS MENSAJE DE ERROR
	} else {
		return false;
	}
}

//FUNCION PARA VER SI EL NOMBRE DE USUARIO Y PASSWORD SON CORRECTOS Y SI EL USUARIO ES ADMIN U OPERADOR
function checkear_usuario($usuario, $password, $conexion) {

	//IMPLEMENTAMOS ALGO DE SEGURIDAD
	$usuario = $conexion->real_escape_string(trim($usuario));	
	$selectUs="select
				   s_us.id_usuario,
				   s_us.usuario,
				   s_us.password,
				   s_us.nombre,
				   s_us.id_tipo,
				   s_ust.tipo,
				   efu.id_ef,
				   s_ust.codigo
				from
				  s_usuario as s_us
				  inner join s_usuario_tipo as s_ust on (s_ust.id_tipo=s_us.id_tipo)
				     left join
                  s_ef_usuario as efu on (efu.id_usuario = s_us.id_usuario) 
				where
				  s_us.usuario='".$usuario."' and s_us.activado=1 and s_ust.codigo!='LOG' and s_ust.codigo!='IMP' and s_ust.codigo!='FAC' and s_ust.codigo!='REP';";
	$rsUs = $conexion->query($selectUs, MYSQLI_STORE_RESULT);
	$num = $rsUs->num_rows;
	if($num==1) {
	    //SI EL USUARIO EXISTE Y ES DISTINTO DE LOGUEADO VERIFICAMOS SI SU CONTRASEÑA ES REAL
		$rowUs = $rsUs->fetch_array(MYSQLI_ASSOC);
		if(crypt($password, $rowUs['password']) == $rowUs['password']){
			
			$_SESSION['id_usuario_sesion']  = $rowUs['id_usuario'];
			$_SESSION['usuario_sesion'] = $rowUs['usuario'];
			$_SESSION['tipo_sesion'] = $rowUs['codigo'];
			$_SESSION['id_ef_sesion'] = $rowUs['id_ef'];
			return true;
	    }else{
		  $conexion->close();
		   return false;
		}		
	}else{
	   $conexion->close();
	   return false;
	}    
}

//FUNCION QUE NOS PERMITE ENCRIPTAR EL PASSWORD DEL USUARIO
function crypt_blowfish_bycarluys($password, $digito = 7) {
		$set_salt = './1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		$salt = sprintf('$2a$%02d$', $digito);
		for($i = 0; $i < 22; $i++){
			$salt .= $set_salt[mt_rand(0, 63)];
		}
		return crypt($password, $salt);
}


//FUNCION QUE PERMITE BORRAR UN ARCHIVO
function borra_archivo($archivo_a_eliminar)
{
	unlink($archivo_a_eliminar);
}

//FUNCION PARA REEMPLAZAR CARACTERES LATINOS Y ESPACIOS EN BLANCO DE UN STRING
function remCarEspeciales($cadena) {
	$especiales = array("ñ", "Ñ", "á", "Á", "é", "É", "í", "Í", "ó", "Ó", "ú", "Ú", "ü", "Ü", " ", "'");
	$reemplazos = array("n", "N", "a", "A", "e", "E", "i", "I", "o", "O", "u", "U", "u", "U", "", "");
	$nuevaCadena = str_replace($especiales, $reemplazos, $cadena);
	return $nuevaCadena;
}


//FUNCION PARA VALIDAR UN CAMPO TIPO FILE PARA UNA IMAGEN
function validarImagen($nombreCampo, $tamanoMax, $tamanoMaxStr, $folderDestino, $requerido) {

	//VARIABLE DE RESULTADO
	$resultado['flag'] = true;
	$resultado['mensaje'] = '';
	$resultado['archivo'] = '';

	//VALIDAMOS LA IMAGEN
	if(!empty($_FILES[$nombreCampo]['name'])) {

		$imagenSize = $_FILES[$nombreCampo]['size'];
		$imagenType = $_FILES[$nombreCampo]['type'];
		//REEMPLAZAMOS LOS CARACTERES LATINOS
		$imagenName = remCarEspeciales($_FILES[$nombreCampo]['name']);
		//VERIFICAMOS SI LA EXTENCION DEL ARCHIVO ESTA EN MAYUSCULA
		$info_fichero = pathinfo($imagenName);
		if(isset($info_fichero['extension'])&&$info_fichero['extension']!=strtolower($info_fichero['extension'])){
		     $info_fichero['basename_we'] = substr($info_fichero['basename'],0, -(strlen($info_fichero['extension']) + ($info_fichero['extension'] == '' ? 0 : 1)));
			 $imagenName=$info_fichero['basename_we'].'.'.strtolower($info_fichero['extension']);
		}

		$suffix = substr(md5(uniqid(rand())), 0, 5);
		$imagen_en_servidor = $suffix.$imagenName;
		//echo $imagen_en_servidor; echo'<br/>';

		//PRIMERO VEMOS SI EL ARCHIVO SUBIDO ES UNA IMAGEN
		if($imagenType == "image/jpeg" || $imagenType == "image/jpg"   || 
		   $imagenType == "image/pjpg" || $imagenType == "image/pjpeg" || 
		   $imagenType == "image/gif"  || $imagenType == "image/png"   || 
		   $imagenType == "image/x-png") {

			//LUEGO, VEMOS SI EL TAMAÑO DE ARCHIVO ES MENOR O IGUAL AL LIMITE DADO
			if($imagenSize <= $tamanoMax) {
				$destiny = $folderDestino."/".$imagen_en_servidor;
				if(copy($_FILES[$nombreCampo]['tmp_name'], $destiny)) {

					//EL ARCHIVO HA SIDO COPIADO AL DIRECTORIO DESTINO
					$resultado['archivo'] = $imagen_en_servidor;
					echo $resultado['archivo']; echo'<br/>';
				} else {
					//EL ARCHIVO NO HA SIDO COPIADO
					$resultado['mensaje'] = "El archivo no pudo ser copiado al Servidor.<br>"
					."Por favor, espere un minuto e intente nuevamente.";
					$resultado['flag'] = false;
				}
			} else {
				//TAMAÑO DE ARCHIVO ES MAS DEL LIMITE DADO
				$resultado['mensaje'] = "El tama&ntilde;o del archivo es mayor al permitido.<br>"
				."El tama&ntilde;o permitido es ".$tamanoMaxStr.".";
				$resultado['flag'] = false;
			}
		} else {
			//EL ARCHIVO NO ES UNA IMAGEN
			$resultado['mensaje'] = "El archivo no pudo ser copiado al Servidor.<br>"
			."El archivo no tiene un formato de imagen permitido.&nbsp;".$imagenType;
			$resultado['flag'] = false;
		}
	} else {
		//SI NO SELECCIONO NINGUNA IMAGEN Y EL CAMPO ES REQUERIDO MOSTRAMOS MENSAJE DE ERROR
		if($requerido) {
			$resultado['mensaje'] = "Debe seleccionar una imagen.";
			$resultado['flag'] = false;
		}
	}

	return $resultado;
}


//FUNCION PARA VALIDAR UN CAMPO TIPO FILE PARA UN AUDIO
function validarArchivo($nombreCampo, $tamanoMax, $tamanoMaxStr, $folderDestino, $requerido) {

	//VARIABLE DE RESULTADO
	$resultado['flag'] = true;
	$resultado['mensaje'] = '';
	$resultado['archivo'] = '';

	//VALIDAMOS LA IMAGEN
	if(!empty($_FILES[$nombreCampo]['name'])) {

		$audioSize = $_FILES[$nombreCampo]['size'];
		$audioType = $_FILES[$nombreCampo]['type'];
		//REEMPLAZAMOS LOS CARACTERES LATINOS
		$audioName = remCarEspeciales($_FILES[$nombreCampo]['name']);

		$suffix = substr(md5(uniqid(rand())), 0, 5);
		$audio_en_servidor = $suffix.$audioName;
		//echo $imagen_en_servidor; echo'<br/>';

		//PRIMERO VEMOS SI EL ARCHIVO SUBIDO ES UNA IMAGEN
		if($audioType == "audio/x-ms-wma" || $audioType == "audio/mpeg3" || 
		$audioType == "audio/x-mpeg-3" || $audioType == "audio/mpeg" ) {

			//LUEGO, VEMOS SI EL TAMAÑO DE ARCHIVO ES MENOR O IGUAL AL LIMITE DADO
			if($audioSize <= $tamanoMax) {
				$destiny = $folderDestino."/".$audio_en_servidor;
				if(copy($_FILES[$nombreCampo]['tmp_name'], $destiny)) {

					//EL ARCHIVO HA SIDO COPIADO AL DIRECTORIO DESTINO
					$resultado['archivo'] = $audio_en_servidor;
					echo $resultado['archivo']; echo'<br/>';
				} else {
					//EL ARCHIVO NO HA SIDO COPIADO
					$resultado['mensaje'] = "El archivo no pudo ser copiado al Servidor.<br>"
					."Por favor, espere un minuto e intente nuevamente.";
					$resultado['flag'] = false;
				}
			} else {
				//TAMAÑO DE ARCHIVO ES MAS DEL LIMITE DADO
				$resultado['mensaje'] = "El tama&ntilde;o del archivo es mayor al permitido.<br>"
				."El tama&ntilde;o permitido es ".$tamanoMaxStr.".";
				$resultado['flag'] = false;
			}
		} else {
			//EL ARCHIVO NO ES UNA IMAGEN
			$resultado['mensaje'] = "El archivo no pudo ser copiado al Servidor.<br>"
			."El archivo no tiene un formato de audio permitido.&nbsp;".$imagenType;
			$resultado['flag'] = false;
		}
	} else {
		//SI NO SELECCIONO NINGUNA IMAGEN Y EL CAMPO ES REQUERIDO MOSTRAMOS MENSAJE DE ERROR
		if($requerido) {
			$resultado['mensaje'] = "Debe seleccionar un audio.";
			$resultado['flag'] = false;
		}
	}

	return $resultado;
}


//FUNCION PARA VALIDAR UN CAMPO TIPO FILE PARA UN ARCHIVO
function validarPdf($nombreCampo, $tamanoMax, $tamanoMaxStr, $folderDestino, $requerido) {

	//VARIABLE DE RESULTADO
	$resultado['flag'] = true;
	$resultado['mensaje'] = '';
	$resultado['archivo'] = '';

	//VALIDAMOS EL ARCHIVO
	if(!empty($_FILES[$nombreCampo]['name'])) {

		$fileSize = $_FILES[$nombreCampo]['size'];
		$fileType = $_FILES[$nombreCampo]['type'];
		//REEMPLAZAMOS LOS CARACTERES LATINOS
		$fileName = remCarEspeciales($_FILES[$nombreCampo]['name']);

		$suffix = substr(md5(uniqid(rand())), 0, 5);
		$file_en_servidor = $suffix.$fileName;
		//echo $imagen_en_servidor; echo'<br/>';

		//PRIMERO VEMOS SI EL ARCHIVO SUBIDO ES UN PDF, DOC, DOCX
		if($fileType == "application/pdf" /*|| $fileType == "application/msword" || $fileType == "application/vnd.ms-powerpoint"*/) {

			//LUEGO, VEMOS SI EL TAMAÑO DE ARCHIVO ES MENOR O IGUAL AL LIMITE DADO
			if($fileSize <= $tamanoMax) {
				$destiny = $folderDestino."/".$file_en_servidor;
				if(copy($_FILES[$nombreCampo]['tmp_name'], $destiny)) {

					//EL ARCHIVO HA SIDO COPIADO AL DIRECTORIO DESTINO
					$resultado['archivo'] = $file_en_servidor;
					echo $resultado['archivo']; echo'<br/>';
				} else {
					//EL ARCHIVO NO HA SIDO COPIADO
					$resultado['mensaje'] = "El archivo no pudo ser copiado al Servidor.<br>"
					."Por favor, espere un minuto e intente nuevamente.";
					$resultado['flag'] = false;
				}
			} else {
				//TAMAÑO DE ARCHIVO ES MAS DEL LIMITE DADO
				$resultado['mensaje'] = "El tama&ntilde;o del archivo es mayor al permitido, "
				."el tama&ntilde;o permitido es ".$tamanoMaxStr.".";
				$resultado['flag'] = false;
			}
		} else {
			//EL ARCHIVO NO ES UNA IMAGEN
			$resultado['mensaje'] = "El archivo no pudo ser copiado al servidor, el archivo no tiene un formato permitido. ".$fileType;
			$resultado['flag'] = false;
		}
	} else {
		//SI NO SELECCIONO NINGUNA IMAGEN Y EL CAMPO ES REQUERIDO MOSTRAMOS MENSAJE DE ERROR
		if($requerido) {
			$resultado['mensaje'] = "Debe seleccionar un archivo.";
			$resultado['flag'] = false;
		} else{
		    $resultado['archivo']='';
		}
	}

	return $resultado;
}



//FUNCION CAPTCHA RAMDON
function randomText($length){
		    $key = '';
			$pattern = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
			for($i=0; $i<$length; $i++) {
				$key .= $pattern{rand(0, 61)};
			}
			return $key;
		   }

//VALIDAR PRECIO UNITARIO
function validar_numero($numero){
  if(empty($numero)){
	  //campo vacio
	  return 1;
  }else{
     if(preg_match('/^[0-9\.]+$/',$numero)){
	   return 2;
	 }else{
	   return 3;//no es numero
	 }
  }
}

//VALIDAR FONO AGENCIA
function validar_fono_agencia($cadena){
  if(empty($cadena)){
	  //campo vacio
	  return 1;
  }else{
     if(preg_match("/^[0-9a-zA-z\s\-]+$/",$cadena)){
	   return 2;
	 }else{
	   return 3;//no es cadena
	 }
  }
}

//VALIDAR NUMERO ENTERO
function validar_numero_entero($numero){
  if(empty($numero)){
	  //campo vacio
	  return 1;
  }else{
     if(preg_match('/^[0-9]+$/',$numero)){
	   return 2;
	 }else{
	   return 3;//no es numero
	 }
  }
}


//VALIDAR SELECT
function validar_select($select){
    if(empty($select)){
	  //elegir
	  return 1;
	}else{
	  return 2;
	}
}

//VALIDAR SELECT
function validar_select_boolean($select){
   if($select!=''){ 
		return 2;
   }else{
	    if(empty($select)){
		  //elegir
		  return 1;
		} 
   }
}

//FUNCION PARA VALIDAR EXPRESIONES REGULARES
function validar_email($email) {
	if (preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_-]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/',$email)) {
    return 1;
  } else{
    return 2;
  }	
}

//FUNCION PARA VALIDAR EL TEXTO ESCRITO POR EL USUARIO EN UN TEXTBOX
function validar_texto($texto) {

	$texto = trim($texto);
	if (empty($texto)) {
		return true;
	}else{
	   return false;
	}
}

function validar_pregunta($texto) {
  if(empty($texto)){
	  //campo vacio
	  return 1;
  }else{
	  return 2;//no es numero
  }
}

//VALIDAR PRECIO UNITARIO
function validar_texto2($cadena){
  if(empty($cadena)){
	  //campo vacio
	  return 1;
  }else{
     if(preg_match("/^[[:alpha:]\s\D]+$/i",$cadena)){
	   return 2;
	 }else{
	   return 3;//no es cadena
	 }
  }
}

function validar_texto4($cadena){
  if(empty($cadena)){
	  //campo vacio
	  return 1;
  }else{
     if(preg_match("/^[[:alpha:]\s\w]+$/i",$cadena)){
	   return 2;
	 }else{
	   return 3;//no es cadena
	 }
  }
}

//FUNCION PARA CREAR USUARIOS
function validar_texto3($texto){
  if(empty($texto)){
	  //campo vacio
	  return 1;
  }else{
     if(strlen($texto)>=5 and strlen($texto)<=10){
		  $patron_texto = "/^[a-z]+$/";
		  if(preg_match($patron_texto, $texto)){ 
			 return 2;
		  }else{
			 //SOLO LETRAS MINUSCULAS Y MAYUSCULAS
			 return 3;
		  }
	 }else{
	    return 4;
	 } 
  }
}		

//FUNCION QUE PERMITE VALIDAR FECHA INGRESADA
function check_date($input,$format) {
	$separator_type= array(
		"/",
		"-",
		"."
	);
	foreach ($separator_type as $separator) {
		$find= stripos($input,$separator);
		if($find!==false){
			$separator_used= $separator;
		}
	}
	$input_array= explode($separator_used,$input);
	if ($format=="mdy") {
		return checkdate($input_array[0],$input_array[1],$input_array[2]);
	} elseif ($format=="ymd") {
		return checkdate($input_array[1],$input_array[2],$input_array[0]);
	} else {
		return checkdate($input_array[1],$input_array[0],$input_array[2]);
	}
	$input_array=array();
}

//VALIDAR SI EL CAMPO ESTA VACIO 
function validar_contenido($texto){
  if(empty($texto)){
	  //campo vacio
	  return 1;
  }else{
 	 
      return 2; 
  } 	  
}

//ALTERNATIVAS B
function alternativa_b($num){
   switch($num){
      case 1: return 'B1';
	  case 2: return 'B2';
   }
}	

//ALTERNATIVAS A
function alternativa_a($num){
   switch($num){
      case 3: return 'A1';
	  case 4: return 'A2';
	  case 5: return 'A3';
   }
}

//FORMAS DE PAGO CONTADO CREDITO
function formapagos_1($num){
   switch($num){
      case 1: return 'contado';
	  case 2: return 'credito';
   }
}

//FORMAS DE PAGO EN A&Ntilde;OS
function formapagos_2($num){
   switch($num){
      case 3: return '1 a&ntilde;o';
	  case 4: return '2 a&ntilde;os';
	  case 5: return '3 a&ntilde;os';
	  case 6: return '4 a&ntilde;os';
	  case 7: return '5 a&ntilde;os';
	  case 8: return '6 a&ntilde;os';
	  case 9: return '7 a&ntilde;os';
   }
}

//CONVERSOR ANIOS NUMERAL
function conversor_anios($num){
   switch($num){
      case 3: return 1;
	  case 4: return 2;
	  case 5: return 3;
	  case 6: return 4;
	  case 7: return 5;
	  case 8: return 6;
	  case 9: return 7;
   }
}
//CONVERSOR TRACCION
function categoria_conversor($num){
   switch($num){
      case 1: return 'A';
	  case 2: return 'B';
	  case 3: return 'C';
   }
}	
//CONVERSOR PLAZA CIRCULACION
function circulacion_conversor($num){
   switch($num){
      case 1: return 'La Paz, Cochabamba, Santa Cruz';
	  case 2: return 'Resto del Pa&iacute;s';
   }
}

//CONVERSOR DEPARTAMENTO
function departamento($depa){

    switch ($depa) :
        case 'lapaz': return 'La Paz';  
        case 'cochabamba': return 'Cochabamba';
        case 'oruro': return 'Oruro';
        case 'potosi': return 'Potosi';
        case 'sucre': return 'Sucre';
        case 'pando': return 'Pando';
        case 'tarija': return 'Tarija';
        case 'beni': return 'Beni';
        case 'santacruz': return 'Santa Cruz';
    endswitch;
}  
?>