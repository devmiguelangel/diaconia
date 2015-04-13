<?php
  require_once('config.class.php');
  $conexion = new SibasDB();
  if($_POST['dato']==1){	 
		 $select="SELECT
					 id_usuario,
					 usuario,
					 password,
					 id_tipo
				  FROM
					 s_usuario
				  WHERE
					 id_usuario = '".$_POST['idusuario']."'
				  LIMIT 0, 1";
		 $res = $conexion->query($select,MYSQLI_STORE_RESULT);
		 $num = $res->num_rows;
		 if($num>0){
		    $rowUs = $res->fetch_array(MYSQLI_ASSOC);
			if(crypt($_POST['password_actual'], $rowUs['password']) == $rowUs['password']){
			  echo 1;
			}else{
			  echo 2;
			}
		 }else{
		   echo 2;
		 } 
  }
?>