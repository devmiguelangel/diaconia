<?php
require('../sibas-db.class.php');

if(isset($_GET['product']) && isset($_FILES['attached']) && isset($_POST['attached'])){
	$link = new SibasDB();
	
	//$type = $link->real_escape_string(trim($_GET['type']));
	$product = $link->real_escape_string(trim($_GET['product']));
	$attached = $link->real_escape_string(trim(base64_decode($_POST['attached'])));
	if($_GET['product'] === 'AU' || $_GET['product'] === 'TRM'){
		$arr_type = array(
				'application/pdf', 
				'image/jpeg', 
				'image/png', 
				'image/pjpeg', 
				'image/x-png');
		$arr_extension = array('rar', 'zip');
	}elseif($_GET['product'] === 'DE' || $_GET['product'] === 'US'){
		$arr_type = array(
				'application/vnd.ms-excel',
				'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	}
	
	$sw = FALSE;
	if(empty($attached) === FALSE) { $sw = TRUE; }
	
	$file_name = $_FILES['attached']['name'];
	$file_type = $_FILES['attached']['type'];
	$file_size = $_FILES['attached']['size'];
	$file_error = $_FILES['attached']['error'];
	$file_tmp = $_FILES['attached']['tmp_name'];
	
	$file_id = date('U').'_'.strtolower($product).'_'.md5(uniqid('@F#1$'.time(), true));
	$file_extension = end(explode(".", $file_name));
	$file_new = $file_id.'.'.$file_extension;
	
	if($_FILES['attached']['error'] > 0){
		echo '0|'.fileUploadErrorMsg($_FILES['attached']['error']);
	}else{
		if((20 * 1024 * 1024) >= $file_size 
            && (in_array($file_type, $arr_type) === TRUE 
                || in_array($file_extension, $arr_extension))){
            
			$dir = 'files/';
			if(!is_dir($dir))
				mkdir($dir, 0777);
			else
				chmod($dir, 0777);
			
			switch($product){
				case 'AU':
					break;
				case 'TR':
					break;
				case 'DE':
				    break;
				case 'US':
				    break;		
			}
			
			if (file_exists($dir . $file_new) === TRUE) {
				echo 'El Archivo '.$file_new." ya existe.";
			} else {
				if($sw === TRUE){
					if(file_exists($dir . $attached) === TRUE) {
						//$old = getcwd(); // Save the current directory
						//chdir($dir);
						unlink($dir . $attached);
						//chdir($old); // Restore the old working directory
					}
				}
				
				if (move_uploaded_file($file_tmp, $dir . $file_new) === TRUE) {
					echo '1|'.base64_encode($file_new).'|'.$attached;
				} else {
					echo '0|El Archivo no pudo ser subido';
				}
			}
		}else{
			echo '0|El Archivo no puede ser Subido ';
		}
	}
}else{
	echo '0|Error: El Archivo no puede ser Subido ';
}

function fileUploadErrorMsg($error_code){
	switch ($error_code){
		case UPLOAD_ERR_INI_SIZE:
			return "El archivo es más grande que lo permitido por el Servidor."; break;
        case UPLOAD_ERR_FORM_SIZE: 
            return "El archivo subido es demasiado grande."; break;
        case UPLOAD_ERR_PARTIAL: 
            return "El archivo subido no se terminó de cargar (probablemente cancelado por el usuario)."; break;
        case UPLOAD_ERR_NO_FILE: 
            return "No se subió ningún archivo"; break;
        case UPLOAD_ERR_NO_TMP_DIR: 
            return "Error del servidor: Falta el directorio temporal."; break;
        case UPLOAD_ERR_CANT_WRITE: 
            return "Error del servidor: Error de escritura en disco"; break;
        case UPLOAD_ERR_EXTENSION: 
            return "Error del servidor: Subida detenida por la extención"; break;
     	default: 
            return "Error del servidor: ".$error_code; break;
    } 
}