<?php
  require_once('config.class.php');
  $conexion = new SibasDB();
  
  $select="select
			  id_eu,
			  usuario,
			  id_usuario,
			  id_ef
		  from
			s_ef_usuario
		  where
			usuario='".$_POST['usuario']."';";
  /*$select="(select 
				id_usuario, usuario
			from
				s_usuario
			where
				usuario = '".$_POST['usuario']."') union (select 
				id_usuario, usuario
			from
				s_ef_usuario
			where
				usuario = '".$_POST['usuario']."'
					and id_ef = '".$_POST['id_ef']."');";*/		   
  $rs = $conexion->query($select,MYSQLI_STORE_RESULT);
  $num = $rs->num_rows;
  if($num>0){
     $valor=2;
	 $return=$valor.'|'.$_POST['usuario'];
	 echo $return;
  }else{
     $valor=1;
	 $return=$valor.'|'.$_POST['usuario'];
	 echo $return;
  }		   
?>
