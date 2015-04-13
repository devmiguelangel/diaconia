<?php
  require_once('config.class.php');
  $conexion = new SibasDB();
  
  $delete = "DELETE FROM s_sgc_slider WHERE id_slider=".$_POST['id_slider']." and id_home=".$_POST['id_home']." LIMIT 1;";
   
  if($conexion->query($delete)===TRUE){
	  
	   if(file_exists('file_img/'.$_POST['name_file'])){ 
		  borra_archivo('file_img/'.$_POST['name_file']);
	   }		
	  
	echo 1;	
  } else{
	 echo 2;
  }

//FUNCION QUE PERMITE BORRAR UN ARCHIVO
function borra_archivo($archivo_a_eliminar)
{
	unlink($archivo_a_eliminar);
}  
?>