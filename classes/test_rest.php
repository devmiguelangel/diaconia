<form id="form-ws" name="form-ws" action="" method="post">
    Nro Operacion:&nbsp;
    <input type="text" id="ws-nt" name="ws-nt" value="<?=$_POST['ws-nt']?>" autocomplete="off"><br>
    Cobertura:&nbsp;
    <select id="ws_cob" name="ws_cob">
      <option value=""
        <?php
          if($_POST['ws_cob']==''){
			 echo'selected'; 
		  }
		?>  
      >Seleccione</option>
      <option value="IM"
        <?php
          if($_POST['ws_cob']=='IM'){
			  echo'selected';
		  }
		?> 
      >Individual/Mancomuno</option>
      <option value="BC"
        <?php
          if($_POST['ws_cob']=='BC'){
			  echo'selected';
		  }
		?>
      >Banca Comunal</option>
    </select><br>
    <input type="submit" value="Enviar" name="enviar">
</form>

<?php
if(isset($_POST['ws-nt'])){

	$flag = FALSE;
	/*
	$str = exec("ping -c 1 http://10.80.70.33");
	if ($result == 0){
	  //echo "ping succeeded";
	  $flag = TRUE;
	}else{
	  $flag = FALSE;
	}
	*/
	$host = '10.80.70.33'; 
	$port = 80; 
	$waitTimeoutInSeconds = 1; 
	if($fp = fsockopen($host,$port,$errCode,$errStr,$waitTimeoutInSeconds)){   
	   //echo' It worked'; 
	   $flag = TRUE;
	} else {
	   //echo' It didnt work';
	   $flag = FALSE; 
	} 
	fclose($fp);
	
	if($flag){
				
		if($_POST['ws_cob']=='BC'){
		   $str_datos = file_get_contents("http://10.80.70.33/wsodfclibc.php?parametro=".$_POST['ws-nt']);
	    }elseif($_POST['ws_cob']=='IM'){
		   $str_datos = file_get_contents("http://10.80.70.33/wsodfcliind.php?parametro=".$_POST['ws-nt']);	
		}
		//echo $str_datos;
		$datos = json_decode($str_datos,true);
		var_dump($datos);
		if(is_array($datos)){
		  if(count($datos)>0){
			 foreach ($datos as $key => $value){
				 echo'
				   <label>Nro operacion:</label>&nbsp;'.$value['nrooperacion'].'<br>
				   <label>Codigo Cliente:</label>&nbsp; '.$value['codigocliente'].'<br>
				   <label>Nombre:</label>&nbsp; '.$value['nombrecliente'].'<br>
				   <label>Ap Paterno:</label>&nbsp; '.$value['apellidopaterno'].'<br>
				   <label>Ap Materno:</label>&nbsp; '.$value['apellidomaterno'].'<br>
				   <label>Ap Casada:</label>&nbsp; '.$value['apellidocasada'].'<br>
				   <label>CI:</label>&nbsp; '.$value['carnetdeidentidad'].'<br>
				   <label>expedido:</label>&nbsp; '.$value['expedido'].'<br>
				   <label>complemento:</label>&nbsp; '.$value['complemento'].'<br>
				   <label>genero:</label>&nbsp; '.$value['genero'].'<br>
				   <label>Estado Civil:</label>&nbsp; '.$value['estadocivil'].'<br>
				   <label>Nombre Completo:</label>&nbsp; '.$value['nombrecompleto'].'<br>
				   <label>Monto solicitado:</label>&nbsp; '.$value['montosolicitado'].'<br>
				   <label>Monto desembolsado:</label>&nbsp; '.$value['montodesembolsado'].'<br>
				   <label>Fecha Nacimiento:</label>&nbsp; '.$value['fechanacimiento'].'<br>
				   <label>Caedec:</label>&nbsp; '.$value['caedec'].'<br>
				   <label>Telefono:</label>&nbsp; '.$value['telefono'].'<br>
				   <label>Direccion:</label>&nbsp; '.$value['direccciondomicilio'].'<hr>';
			 }
	
		  }else{
			echo'no existe el usuario';	
		  }
		}else{
			echo'Devolucion de datos NULL';
		}
		//var_dump($datos);
		
		//echo is_array($datos) ? 'Es un array' : 'No es un array';
		//echo'<br>';
		//echo count($datos);
		/*
		foreach ($datos as $key => $value){
			echo $key;
			
		}
		*/
	}else{
		echo "ping failed";
	}

}


/* 
echo "Aficiones del jefe: ".$datos["responsable"]["Aficiones"][0]."n";
 
// Modifica el valor, y escribe el fichero json de salida
//
$datos["responsable"]["Aficiones"][0] = "Natación";
 
$fh = fopen("datos_out.json", 'w')
      or die("Error al abrir fichero de salida");
fwrite($fh, json_encode($datos,JSON_UNESCAPED_UNICODE));
fclose($fh);
*/
?>