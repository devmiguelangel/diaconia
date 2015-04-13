<?php
  require_once('config.class.php');
  $conexion = new SibasDB();
  
  $select="select
			  id_usuario,
			  usuario,
			  nombre
		  from
			s_usuario
		  where
			usuario='".$_POST['usuario']."';";
  	   
  $rs = $conexion->query($select,MYSQLI_STORE_RESULT);
  if($rs->num_rows>0){
	  $reg = $rs->fetch_array(MYSQLI_ASSOC);
	  if($reg['id_usuario'] == $_POST['id_usuario']){
		  $valor = 1;
	      $return = $valor.'|'.$_POST['usuario'];
	      echo $return;
	  }else{
		  $valor = 2;
	      $return = $valor.'|'.$_POST['usuario'];
	      echo $return;
	  }
  }else{
	 $valor = 1;
	 $return = $valor.'|'.$_POST['usuario'];
	 echo $return;
  }
     
?>
