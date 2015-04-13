<?php
  require_once('config.class.php');
  $conexion = new SibasDB();
  
  if($_POST['opcion']=='inicio'){//eliminamos resgistros de inicio
		$delete = "DELETE FROM s_sgc_slider WHERE id_slider='".$_POST['id_slider']."' and id_home='".$_POST['id_home']."' and tipo='".$_POST['tipo']."' LIMIT 1;";
		
		if($conexion->query($delete)===TRUE){
		   if(file_exists('../images/'.$_POST['name_file'])){ 
			  borra_archivo('../images/'.$_POST['name_file']);
		   }		
		   echo 1;	
		} else{
		   echo 2;
		}
  }elseif($_POST['opcion']=='formulario'){
	    $delete = "delete from s_sgc_formulario where id_formulario='".$_POST['id_formulario']."' and id_home='".$_POST['id_home']."' limit 1;";
	    
		if($conexion->query($delete)===TRUE){
		   if(file_exists('../file_form/'.$_POST['archivo'])){ 
			  borra_archivo('../file_form/'.$_POST['archivo']);
		   }else{
			 echo 2;   
		   }		
		   echo 1;	
		} else{
		   echo 2;
		}
	     
  }elseif($_POST['opcion']=='compania'){
	  $delete = "delete from s_compania where id_compania='".$_POST['id_compania']."' limit 1;";
	    
		if($conexion->query($delete)===TRUE){
		  
		   if(file_exists('../images/'.$_POST['archivo'])){ 
			  borra_archivo('../images/'.$_POST['archivo']);
		   }
		   
		echo 1;	
		} else{
		 echo 2;
		}
  }elseif($_POST['opcion']=='ocupacion'){
	    $delete = "delete from s_ocupacion where id_ocupacion='".$_POST['id_ocupacion']."' and id_ef='".$_POST['id_ef']."' limit 1;";
	    
		if($conexion->query($delete)===TRUE){	  
	  	   echo 1;	
		} else{
		   echo 2;
		}
  }elseif($_POST['opcion']=='certmedico'){
	  $delete = "delete from s_cm_cuestionario where id_cuestionario=".$_POST['idcuestionario']." limit 1;";
	  
	  if($conexion->query($delete)===TRUE){
		  echo 1;
	  }else{
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='eliminapregunta'){
	  $delete = "delete from s_cm_pregunta where id_pregunta=".$_POST['idpregunta']." limit 1;";
	  
	  if($conexion->query($delete)===TRUE){
		  echo 1;
	  }else{
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='elimina_cuestionario'){
      $delete = "delete from s_cm_cert_cues where id_cc=".$_POST['id_cc']." and id_cm=".$_POST['id_cm']." limit 1;";
	  
	  if($conexion->query($delete)===TRUE){
		  echo 1;
	  }else{
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='eliminarcorreo'){
	  $delete = "delete from s_correo where id_correo=".$_POST['idcorreo']." and id_ef='".$_POST['id_ef']."' limit 1;";
	  
	  if($conexion->query($delete)===TRUE){
		  echo 1;
	  }else{
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='eliminaragencia'){
	  $delete = "delete from s_agencia where id_agencia='".$_POST['idagencia']."' and id_ef='".$_POST['id_ef']."' and id_depto=".$_POST['id_depto']." limit 1;";
	  
	  if($conexion->query($delete)===TRUE){
		  echo 1;
	  }else{
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='eliminar_sucursal'){
	  $delete = "delete from s_departamento where id_depto='".$_POST['id_depto']."' limit 1;";
	  
	  if($conexion->query($delete)===TRUE){
		  echo 1;
	  }else{
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='eliminacabecera'){
	  $delete = "delete from s_sgc_home where id_home='".$_POST['idhome']."' and id_ef='".$_POST['id_ef']."' limit 1;";
	  
	  if($conexion->query($delete)===TRUE){
		  echo 1;
	  }else{
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='eliminar_poliza'){
	  $delete = "delete from s_poliza where id_ef_cia='".$_POST['id_ef_cia']."' and id_poliza='".$_POST['id_poliza']."';";
	  
	  if($conexion->query($delete)===TRUE){
		  echo 1;
	  }else{
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='eliminarproducto'){
	  $delete = "delete from s_producto_cia where id_prcia=".$_POST['id_prcia']." and id_ef_cia='".base64_decode($_POST['id_ef_cia'])."' and id_producto=".base64_decode($_POST['id_producto']).";";
	  
	  if($conexion->query($delete)===TRUE){
		  echo 1;
	  }else{
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='eliminartasas'){
	  $delete = "delete from s_tasa_de where id_tasa=".$_POST['id_tasa']." and id_prcia=".$_POST['id_prcia'].";";
	  
	  if($conexion->query($delete)===TRUE){
		  echo 1;
	  }else{
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='elimina_tasa_auto'){
	  $delete2 = "delete from s_au_incremento where id_tasa=".$_POST['id_tasa']." and id_incremento='".$_POST['id_incremento_rac']."' and categoria='RAC';";
	  if($conexion->query($delete2)===TRUE){$response=TRUE;}else{$response=FALSE;}
	  
	  $delete3 = "delete from s_au_incremento where id_tasa=".$_POST['id_tasa']." and id_incremento='".$_POST['id_incremento_oth']."' and categoria='OTH';";
	  if($conexion->query($delete3)===TRUE){$response=TRUE;}else{$response=FALSE;}
	  if($response){
		  $delete = "delete from s_tasa_au where id_tasa=".$_POST['id_tasa']." and id_ef_cia='".$_POST['id_ef_cia']."';";
		  
		  if($conexion->query($delete)===TRUE){
			  echo 1;
		  }else{
			  echo 2;
		  } 
	  }else{
		  echo 2;
	  } 
	  
  }elseif($_POST['opcion']=='elimina_tasa_trd'){//ELIMINAR TASAS TODO RIESGO DOMICILIARIO 
	  $delete = "delete from s_tasa_trd where id_tasa=".$_POST['id_tasa']." and id_ef_cia='".$_POST['id_ef_cia']."';";
	  
	  if($conexion->query($delete)===TRUE){
		  echo 1;
	  }else{
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='elimina_tasa_trem'){//ELIMINAR TODO RIESGO EQUIPO MOVIL
	  $delete = "delete from s_tasa_trm where id_tasa=".$_POST['id_tasa']." and id_ef_cia='".$_POST['id_ef_cia']."';";
	  
	  if($conexion->query($delete)===TRUE){
		  echo 1;
	  }else{
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='elimina_estado'){
	  $delete = "delete from s_estado where id_estado=".$_POST['id_estado']." and id_ef='".$_POST['id_ef']."';";
	  
	  if($conexion->query($delete)===TRUE){
		  echo 1;
	  }else{
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='elimina_tipocambio'){
	  $delete = "delete from s_tipo_cambio where id_tc=".$_POST['id_tc']." and id_ef='".$_POST['id_ef']."';";
	  
	  if($conexion->query($delete)===TRUE){
		  echo 1;
	  }else{
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='elimina_formapago'){
	  $delete = "delete from s_forma_pago where id_forma_pago='".$_POST['id_forma_pago']."' and id_ef='".$_POST['id_ef']."';";
	  
	  if($conexion->query($delete)===TRUE){
		  echo 1;
	  }else{
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='elimina_tipotarjeta'){
	  $delete = "delete from s_th_tarjeta where id_tarjeta='".$_POST['id_tarjeta']."';";
	  
	  if($conexion->query($delete)===TRUE){
		  echo 1;
	  }else{
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='elimina_marcatj'){
	  $delete = "delete from s_th_marca where id_marca='".$_POST['id_marca']."' and id_ef_cia='".$_POST['id_ef_cia']."';";
	  
	  if($conexion->query($delete)===TRUE){
		  echo 1;
	  }else{
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='elimina_primatj'){
		  $select = "select 
					   id_prima, 
					   id_ef_cia, 
					   prima 
					 from 
					   s_th_prima 
					 where
						id_prima='".$_POST['id_prima']."' 
						and id_ef_cia='".$_POST['id_ef_cia']."';";
		 $res = $conexion->query($select,MYSQLI_STORE_RESULT);
		 $regi = $res->fetch_array(MYSQLI_ASSOC);
		 $jsonData = $regi['prima'];
		 $phpArray = json_decode($jsonData, true);
		 $vec_prim = array();
		 foreach ($phpArray as $clave => $valor) {
			 if(base64_decode($clave)!=$_POST['id_tarjeta']){
				$vec_prim[$clave]=$valor;  
			 }
		 }
		 $update = 'update s_th_prima set prima="'.$conexion->real_escape_string(json_encode($vec_prim)).'" where id_ef_cia="'.$_POST['id_ef_cia'].'" and id_prima="'.$_POST['id_prima'].'";';
		 if($conexion->query($update)===TRUE){
			 echo 1;
		 }else{
			 echo 2;
		 } 
  }elseif($_POST['opcion']=='eliminar_modalidad'){
	  $delete = "delete from s_modalidad where id_modalidad='".$_POST['id_modalidad']."' and id_ef='".$_POST['id_ef']."';";
	  
	  if($conexion->query($delete)===TRUE){
		  echo 1;
	  }else{
		  echo 2;
	  } 
  }
  
  
  
  
//FUNCION QUE PERMITE BORRAR UN ARCHIVO
function borra_archivo($archivo_a_eliminar)
{
	unlink($archivo_a_eliminar);
}  
?>