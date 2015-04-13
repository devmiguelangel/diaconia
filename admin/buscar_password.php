<?php
  require_once('config.class.php');
  $conexion = new SibasDB();
  $select="SELECT
				id_usuario,
				password
		   FROM
				s_usuario
		   WHERE
				id_usuario = '".$_POST['idusuario']."'
		   LIMIT 0, 1";
  $regi = $conexion->query($select,MYSQLI_STORE_RESULT);
  $num = $regi->num_rows;
  if($num>0){
        $rowUs = $regi->fetch_array(MYSQLI_ASSOC);
		if(crypt($_POST['nuevo_pass'], $rowUs['password']) == $rowUs['password']){
		  echo 1;
		}else{
		  echo 2;
		}  
  }else{
     echo 1;
  }
?>
