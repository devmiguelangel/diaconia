<?php
  require_once('config.class.php');
  $conexion = new SibasDB();
  
  if($_POST['opcion']=='certmedico'){
	    //PRIMERO ACTUALIZAMOS TODOS LOS REGISTROS A CERO
		$vec=explode('|',$_POST['idcm']);
		$id_cm=$vec[0];
		$id_ef_cia=$vec[1];
		$update = "UPDATE s_cm_certificado SET activado=0 where id_ef_cia='".$id_ef_cia."';";
		if($conexion->query($update)===TRUE){ $response=TRUE;}else{ $response=FALSE;}
		
		//POSTERIORMENTE ACTUALIZAMOS EL REGISTRO ELEGIDO A 1
		$update2="update s_cm_certificado set activado=1 where id_cm=".$id_cm." and id_ef_cia='".$id_ef_cia."';";
		if($conexion->query($update2)===TRUE){$response=TRUE;}else{$response=FALSE;}
		if($response){
		   echo 1;	
		} else{
		   echo 2;
		}
  }elseif($_POST['opcion']=='registrodatos'){
	    //METEMOS LOS DATOS A LA BASE DE DATOS
		$insert = "INSERT INTO s_cm_certificado(id_cm, tipo, titulo, id_ef_cia, activado) "
				    ."VALUES(NULL, '".$_POST['tipocertificado']."', '".$_POST['titulo']."', '".$_POST['id_ef_cia']."', 0)";
		//VERIFICAMOS SI HUBO ERROR EN EL INGRESO DEL REGISTRO
		if($conexion->query($insert)===TRUE){				
			echo 1;
		} else {
			echo 2;
		}
  }elseif($_POST['opcion']=='editardatos'){
        //ACTUALIZAMOS EL REGISTRO 
		$update2="update s_cm_certificado set tipo='".$_POST['tipocertificado']."', titulo='".$_POST['titulo']."' where id_cm=".$_POST['idcm']." and id_ef_cia='".$_POST['id_ef_cia']."';";
		
		if($conexion->query($update2)===TRUE){
		   echo 1;	
		} else{
		   echo 2;
		}
  }elseif($_POST['opcion']=='registrocuestionario'){
	  //METEMOS LOS DATOS A LA BASE DE DATOS
	  $insert = "insert into s_cm_cuestionario(id_cuestionario, titulo) "
	            ."values(NULL, '".$_POST['titulo']."')";
	 
	  
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($insert)===TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }			
  }elseif($_POST['opcion']=='registroedita'){
	  $update = "update s_cm_cuestionario set titulo='".$_POST['titulo']."' where id_cuestionario=".$_POST['idcuestionario'].";"; 
	  
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($update)===TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }	
  }elseif($_POST['opcion']=='creapregunta'){
	  //METEMOS LOS DATOS A LA BASE DE DATOS
	  $insert = "insert into s_cm_pregunta(id_pregunta, pregunta, tipo) "
	            ."values(NULL, '".$_POST['pregunta']."', '".$_POST['tipopregunta']."')";
	  
	  
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($insert)===TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }		
  }elseif($_POST['opcion']=='editapregunta'){
	  $update = "update s_cm_pregunta set pregunta='".$_POST['pregunta']."', tipo='".$_POST['tipopregunta']."' where id_pregunta=".$_POST['idpregunta'].";"; 
	  
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($update)===TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }	
  }elseif($_POST['opcion']=='adicionacuest'){
	  //METEMOS LOS DATOS A LA BASE DE DATOS
	  $insert = "insert into s_cm_cert_cues(id_cc, id_cm, id_cuestionario) "
	            ."values(NULL, ".$_POST['idcm'].", ".$_POST['idcuestionario'].")";
	  
	  
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($insert)===TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }		
  }elseif($_POST['opcion']=='adicionapregcuest'){
	  //METEMOS LOS DATOS A LA BASE DE DATOS
	  $insert = "insert into s_cm_grupo(id_grupo, id_cc, id_pregunta) "
	            ."values(NULL, ".$_POST['idcc'].", ".$_POST['idpregunta'].")";
	  
	  
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($insert)===TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }		 
  }elseif($_POST['opcion']=='crearcorreo'){
	  //METEMOS LOS DATOS A LA BASE DE DATOS
	  $insert = "insert into s_correo(id_correo, correo, nombre, producto, id_ef) "
	            ."values(NULL, '".$_POST['correo']."', '".$_POST['nombre']."', '".$_POST['producto']."', '".$_POST['idefin']."')";
	  
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($insert)===TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }		 
  }elseif($_POST['opcion']=='editarcorreo'){
	  $update = "update s_correo set correo='".$_POST['correo']."', nombre='".$_POST['nombre']."', producto='".$_POST['producto']."', id_ef='".$_POST['idefin']."' where id_correo=".$_POST['idcorreo']." and id_ef='".$_POST['id_ef']."';"; 
	  
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($update)===TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }	
  }elseif($_POST['opcion']=='crear_agencia'){
		/*$busca="select id_ef from s_agencia where id_ef='".$_POST['idefin']."';";
		$num_reg=mysql_num_rows(mysql_query($busca,$conexion));
		if($num_reg!=0){*/
			$id_new_agencia = generar_id_codificado('@S#1$2013');
			if($_POST['resp_emi']==1){
				$emitir=1;
			}elseif($_POST['resp_emi']==0){
				$emitir=0;
			}
			$insert = "INSERT INTO s_agencia(id_agencia, codigo, agencia, id_depto, id_ef, emision) "
				 ."VALUES('".$id_new_agencia."', '".$_POST['codigo']."', '".$_POST['agencia']."', ".$_POST['id_depto'].", '".$_POST['idefin']."', ".$emitir.")";
			
			//METEMOS A LA TABLA SGC_TBLEDADESMONTO
			if($conexion->query($insert)===TRUE){
				echo 1;
			} else {
				echo 2;
			}
		/*}else{
			$id_new_agencia1 = generar_id_codificado('@S#1$2013');
			$insert1 = "INSERT INTO s_agencia(id_agencia, codigo, agencia, id_ef) "
				 ."VALUES('".$id_new_agencia1."', '', 'Ninguno', '".$_POST['idefin']."')";
			$resu1=mysql_query($insert1,$conexion);
			
			$id_new_agencia2 = generar_id_codificado('@S#1$2013');
			if($_POST['resp_emi']==1){
				$emitir=1;
			}elseif($_POST['resp_emi']==0){
				$emitir=0;
			}
			$insert2 = "INSERT INTO s_agencia(id_agencia, codigo, agencia, id_depto, id_ef, emision) "
				 ."VALUES('".$id_new_agencia2."', '".$_POST['codigo']."', '".$_POST['agencia']."', ".$_POST['id_depto'].", '".$_POST['idefin']."', ".$emitir.")";
			$resu2=mysql_query($insert2,$conexion);
			//METEMOS A LA TABLA SGC_TBLEDADESMONTO
			if(mysql_errno($conexion)==0){
				echo 1;
			} else {
				echo 2;
			}
		}*/
  }elseif($_POST['opcion']=='editar_agencia'){
	  /*if($_POST['resp_emi']==1){
		  $emitir=1;
	  }elseif($_POST['resp_emi']==0){
		  $emitir=0;
	  }*/
	  $update = "update s_agencia set codigo='".$_POST['codigo']."', agencia='".$_POST['agencia']."', id_depto='".$_POST['id_depto']."', id_ef='".$_POST['idefin']."', emision=".$_POST['emitir']." where id_agencia='".$_POST['idagencia']."' and id_ef='".$_POST['id_ef']."';"; 
	  
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($update)===TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }	
  }elseif($_POST['opcion']=='actualizar_check'){
	  if($_POST['vardata']=='V'){
		      $update = "update s_departamento set ".$_POST['campo']."=1 where id_depto=".$_POST['id_depto'].";"; 
			  
			  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
			  if($conexion->query($update)===TRUE){				
				 echo 1;
			  } else {
				 echo 2;
			  }	  
	  }elseif($_POST['vardata']=='F'){
		      $update = "update s_departamento set ".$_POST['campo']."=0 where id_depto=".$_POST['id_depto'].";"; 
			  
			  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
			  if($conexion->query($update)===TRUE){				
				 echo 1;
			  } else {
				 echo 2;
			  }	
	  }
  }elseif($_POST['opcion']=='crear_sucursal'){
	   //METEMOS LOS DATOS A LA BASE DE DATOS
      if($_POST['tipoci']=='ci' and $_POST['tipodepto']=='depto'){
		  $ci=1;$depto=1;$regional=0;$id_ef='null';
	  }elseif($_POST['tipoci']=='ci'){
		  $ci=1;$depto=0;$regional=0;$id_ef='null';  
	  }elseif($_POST['tipodepto']=='depto'){
		  $ci=0;$depto=1;$regional=0;$id_ef='null';
	  }elseif($_POST['tiporeg']=='regional'){
          $ci=0;$depto=0;$regional=1;$id_ef="'".$_POST['idefin']."'";
	  }
	    
	  $insert = "insert into s_departamento(id_depto, departamento, codigo, tipo_ci, tipo_dp, tipo_re, id_ef) "
	            ."values(NULL, '".$_POST['sucursal']."', '".$_POST['codigo']."', ".$ci.", ".$depto.", ".$regional.", ".$id_ef.")";
	 
	  
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($insert)===TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }		
  }elseif($_POST['opcion']=='editar_sucursal'){
	  /*if($_POST['tipo']=='ci'){
		  $ci=1;$depto=0;$regional=0;$id_ef='null';  
	  }elseif($_POST['tipo']=='depto'){
		  $ci=0;$depto=1;$regional=0;$id_ef='null';
	  }elseif($_POST['tipo']=='regional'){
          $ci=0;$depto=0;$regional=1;$id_ef="'".$_POST['idefin']."'";
	  }  
	  $update = "update s_departamento set codigo='".$_POST['codigo']."', departamento='".$_POST['sucursal']."', tipo_ci=".$ci.", tipo_dp=".$depto.", tipo_re=".$regional.", id_ef=".$id_ef." where id_depto=".$_POST['id_depto'].";";*/
	  $update = "update s_departamento set codigo='".$_POST['codigo']."', departamento='".$_POST['sucursal']."' where id_depto=".$_POST['id_depto'].";"; 
	  //echo $update;
	  
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($update)===TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }
  }elseif($_POST['opcion']=='enabled_disabled_ef'){
	  if($_POST['text']=='activar'){
		  $update = "update s_entidad_financiera set activado=1 where id_ef='".base64_decode($_POST['id_ef'])."';"; 
		  
		   
	  }elseif($_POST['text']=='desactivar'){
		  $update = "update s_entidad_financiera set activado=0 where id_ef='".base64_decode($_POST['id_ef'])."';"; 
		 
	  }	
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($update) === TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }	  
  }elseif($_POST['opcion']=='editar_adciaef'){
	  $update = "update s_ef_compania set id_ef='".$_POST['idefin']."', id_compania='".$_POST['idcompania']."' where id_ef_cia='".$_POST['id_ef_cia']."';"; 
	  
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($update)===TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }	
  }elseif($_POST['opcion']=='buscar_compania'){
	  $select="select
				  count(id_ef_cia) as cont 
				from
				  s_ef_compania
				where
				  id_ef='".$_POST['idefin']."' and id_compania='".$_POST['idcompania']."' and producto='".$_POST['producto']."';";
	 $res = $conexion->query($select,MYSQLI_STORE_RESULT);
	 $regi = $res->fetch_array(MYSQLI_NUM);
	 if($regi[0]==0){
	   echo 1; 
	 }else{
	   echo 2;	 
	 }
  }elseif($_POST['opcion']=='crear_adciaef'){
	  //GENERAMOS ID CODIFICADO UNICO
	  $id_new_ef_cia = generar_id_codificado('@S#1$2013');
	  //METEMOS LOS DATOS A LA BASE DE DATOS 
	  $insert = "insert into s_ef_compania(id_ef_cia, id_ef, id_compania, producto, activado) "
	            ."values('".$id_new_ef_cia."', '".$_POST['idefin']."', '".$_POST['idcompania']."', '".$_POST['producto']."', 0)";
	  //$resu = mysql_query($insert, $conexion);
	  
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($insert) === TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }		
  }elseif($_POST['opcion']=='active_adciaef'){
	  if($_POST['text']=='activar'){
		  $update = "update s_ef_compania set activado=1 where id_ef_cia='".base64_decode($_POST['id_ef_cia'])."';"; 
		  //$resu = mysql_query($update,$conexion);
		   
	  }elseif($_POST['text']=='desactivar'){
		  $update = "update s_ef_compania set activado=0 where id_ef_cia='".base64_decode($_POST['id_ef_cia'])."';"; 
		  //$resu = mysql_query($update,$conexion);
	  }	
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($update) === TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }	  
  }elseif($_POST['opcion']=='enabled_disabled_user'){
	  if($_POST['text']=='dar alta'){
		  $update = "update s_usuario set activado=1 where id_usuario='".base64_decode($_POST['id_usuario'])."';"; 
		  
		   
	  }elseif($_POST['text']=='dar baja'){
		  $update = "update s_usuario set activado=0 where id_usuario='".base64_decode($_POST['id_usuario'])."';"; 
		  
	  }	
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($update) === TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }	
  }elseif($_POST['opcion']=='buscar_numpoliza'){
	  $select="select 
	             sp.id_poliza, 
				 sp.no_poliza, 
				 sp.id_compania 
			  from 
			     s_poliza as sp
				 inner join s_ef_compania as efc on (efc.id_compania=sp.id_compania) 
			  where 
			     sp.id_compania=".base64_decode($_POST['id_compania'])." and sp.no_poliza='".$_POST['num_poliza']."' and efc.id_ef='".base64_decode($_POST['id_ef'])."' and sp.id_poliza!=".base64_decode($_POST['id_poliza']).";";
	  $res = $conexion->query($select,MYSQLI_STORE_RESULT);
	  $numreg = $res->num_rows;
	  if($numreg>0){
		  echo 2;
	  }else{
		  echo 1;
	  }
  }elseif($_POST['opcion']=='buscar_numpoliza_add'){
	  $select="select 
	              id_poliza, 
				  no_poliza, 
				  id_ef_cia,
				  producto 
			   from 
			      s_poliza 
			   where 
			     id_ef_cia='".$_POST['id_ef_cia']."' and no_poliza='".$_POST['num_poliza']."'";
				 if(isset($_POST['id_poliza'])){
					$select.=" and id_poliza!='".$_POST['id_poliza']."'"; 
				 }
	  $select.= " and producto='".$_POST['producto']."';";
	  $res = $conexion->query($select,MYSQLI_STORE_RESULT);
	  $numreg = $res->num_rows;
	  if($numreg>0){
		  echo 2;
	  }else{
		  echo 1;
	  }
  }elseif($_POST['opcion']=='agregar_tipvehic'){//AGREGAMOS A LA DB TIPO DE VEHICULO
	  $id_new_tipo_vh = generar_id_codificado('@S#1$2013');
	  $insert = "INSERT INTO s_au_tipo_vehiculo(id_tipo_vh, id_ef, vehiculo) "
			 ."VALUES('".$id_new_tipo_vh."', '".$_POST['idefin']."', '".$_POST['tip_vehiculo']."')";
	  
	  //METEMOS A LA TABLA SGC_TBLEDADESMONTO
	  if($conexion->query($insert)===TRUE){
		  echo 1;
	  } else {
		  echo 2;
	  }
  }elseif($_POST['opcion']=='editar_tipvehic'){
	  $update = "update s_au_tipo_vehiculo set vehiculo='".$_POST['tip_vehiculo']."' where id_tipo_vh='".$_POST['idtipovh']."' and id_ef='".$_POST['id_ef']."';"; 
	  
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($update)===TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }	
  
  }elseif($_POST['opcion']=='active_tipvehic'){
	  if($_POST['text']=='activar'){
		  $update = "update s_au_tipo_vehiculo set activado=1 where id_tipo_vh='".$_POST['id_tipo_vh']."' and id_ef='".$_POST['id_ef']."';"; 
		  
		   
	  }elseif($_POST['text']=='desactivar'){
		  $update = "update s_au_tipo_vehiculo set activado=0 where id_tipo_vh='".$_POST['id_tipo_vh']."' and id_ef='".$_POST['id_ef']."';"; 
		  
	  }	
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($update)===TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }	
  }elseif($_POST['opcion']=='agregar_marca_auto'){//AGREGAMOS MARCA DE AUTO
	  $BuscaMarca="select
					  count(id_marca) as num
					from
					  s_au_marca
					where
					  marca='".$_POST['marca_auto']."' and id_ef='".$_POST['idefin']."';";
	  $res = $conexion->query($BuscaMarca,MYSQLI_STORE_RESULT);
	  $regi = $res->fetch_array(MYSQLI_ASSOC);
	  if($regi['num']>0){ 				  
	     echo 0; 
	  }else{
		  $id_new_marca = generar_id_codificado('@S#1$2013');
		  $insert = "INSERT INTO s_au_marca(id_marca, id_ef, marca, fecha_ingreso) "
				 ."VALUES('".$id_new_marca."', '".$_POST['idefin']."', '".$_POST['marca_auto']."', curdate())";
		  
		  //METEMOS A LA TABLA SGC_TBLEDADESMONTO
		  if($conexion->query($insert)===TRUE){
			  echo 1;
		  } else {
			  echo 2;
		  }
	  }
  }elseif($_POST['opcion']=='editar_marca_auto'){
	   $BuscaMarca="select
					  count(id_marca) as num
					from
					  s_au_marca
					where
					  marca='".$_POST['marca_auto']."' and id_ef='".$_POST['id_ef']."' and id_marca!='".$_POST['id_marca']."';";
	  $res = $conexion->query($BuscaMarca,MYSQLI_STORE_RESULT);
	  $regi = $res->fetch_array(MYSQLI_ASSOC);
	  if($regi['num']>0){ 				  
	     echo 0; 
	  }else{
		  $update = "update s_au_marca set marca='".$_POST['marca_auto']."' where id_marca='".$_POST['id_marca']."' and id_ef='".$_POST['id_ef']."';"; 
		  
		  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
		  if($conexion->query($update)===TRUE){				
			 echo 1;
		  } else {
			 echo 2;
		  }
	  }
  }elseif($_POST['opcion']=='agregar_modelo_auto'){
	  $buscamodelo="select
					  count(id_modelo) as num
					from
					  s_au_modelo
					where
					  id_marca='".$_POST['id_marca']."' and modelo='".$_POST['modelo_auto']."';";
	  $res = $conexion->query($buscamodelo,MYSQLI_STORE_RESULT);				  
	  $regi = $res->fetch_array(MYSQLI_ASSOC);
	  if($regi['num']>0){
		  echo 0;
	  }else{
	  
		  $id_new_modelo = generar_id_codificado('@S#1$2013');
		  $insert = "INSERT INTO s_au_modelo(id_modelo, id_marca, modelo) "
				 ."VALUES('".$id_new_modelo."', '".$_POST['id_marca']."', '".$_POST['modelo_auto']."')";
		  
		  //METEMOS A LA TABLA SGC_TBLEDADESMONTO
		  if($conexion->query($insert)===TRUE){
			  echo 1;
		  } else {
			  echo 2;
		  }
	  }
  }elseif($_POST['opcion']=='editar_modelo_auto'){
      $buscamodelo="select
					  count(id_modelo) as num
					from
					  s_au_modelo
					where
					  id_marca='".$_POST['id_marca']."' and modelo='".$_POST['modelo_auto']."' and id_modelo!='".$_POST['id_modelo']."';";
	  $res = $conexion->query($buscamodelo,MYSQLI_STORE_RESULT);
	  $regi = $res->fetch_array(MYSQLI_ASSOC);				  
	  
	  if($regi['num']>0){
		  echo 0;
	  }else{
		  $update = "update s_au_modelo set modelo='".$_POST['modelo_auto']."' where id_modelo='".$_POST['id_modelo']."' and id_marca='".$_POST['id_marca']."';"; 
		  
		  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
		  if($conexion->query($update)===TRUE){				
			 echo 1;
		  } else {
			 echo 2;
		  }
	  }
  }elseif($_POST['opcion']=='eliminar_modelo'){
      $delete = "delete from s_au_modelo where id_modelo='".$_POST['id_modelo']."' and id_marca='".$_POST['id_marca']."' limit 1;";
	 
	  if($conexion->query($delete)===TRUE){
		  echo 1;
	  }else{
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='update_producto'){
	  $update0="update s_producto set activado=0 where id_ef_cia='".$_POST['id_ef_cia']."';";
	  if($conexion->query($update0)===TRUE){ $response=TRUE;}else{ $response=FALSE;}
	  if($_POST['vardata']=='V'){
		 $update = "update s_producto set activado=1 where id_producto='".$_POST['id_producto']."' and id_ef_cia='".$_POST['id_ef_cia']."';"; 
	     if($conexion->query($update)===TRUE){ $response=TRUE;}else{ $response=FALSE;}
	  }elseif($_POST['vardata']=='F'){
		 $update = "update s_producto set activado=0 where id_producto='".$_POST['id_producto']."' and id_ef_cia='".$_POST['id_ef_cia']."';"; 
	     if($conexion->query($update)===TRUE){ $response=TRUE;}else{ $response=FALSE;}
	  }
	  if($response){
		  echo 1;
	  } else {
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='buscar_tip_produ'){
	  $select="select
				  sp.id_producto,
				  sp.id_ef_cia,
				  sp.nombre
				from
				  s_producto as sp
				  inner join s_ef_compania as efc on (efc.id_ef_cia=sp.id_ef_cia)
				  inner join s_compania as sc on (sc.id_compania=efc.id_compania)
				where
				  efc.id_ef='".$_POST['id_ef']."' and sp.nombre='".$_POST['tip_producto']."' and efc.activado=1 and sc.activado=1;";
	 $res = $conexion->query($select, MYSQLI_STORE_RESULT);
	 $num_regi = $res->num_rows;
	 if($num_regi==0){
	   echo 1; 
	 }else{
	   echo 2;	 
	 }
  }elseif($_POST['opcion']=='crear_tip_producto'){
	  $select="select
				  id_ef_cia,
				  id_ef,
				  id_compania
				from
				  s_ef_compania
				where
				  id_ef='".$_POST['id_ef']."' and producto='DE';";
	  $res = $conexion->query($select,MYSQLI_STORE_RESULT);
	  $regi = $res->fetch_array(MYSQLI_ASSOC); 			  
	  $id_new_producto = generar_id_codificado('@S#1$2013');
	  $insert = "INSERT INTO s_producto(id_producto, id_ef_cia, nombre, activado) "
			 ."VALUES('".$id_new_producto."', '".$regi['id_ef_cia']."', '".$_POST['tip_producto']."', 0)";
	  
	  //METEMOS A LA TABLA SGC_TBLEDADESMONTO
	  if($conexion->query($insert)===TRUE){
		  echo 1;
	  } else {
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='ingresa_producto'){
	  $insert = "INSERT INTO s_producto_cia(id_prcia, nombre, id_ef_cia, id_producto) "
			 ."VALUES(NULL, '".$_POST['productos']."', '".$_POST['id_ef_cia']."', ".$_POST['id_producto'].")";
	  
	  //METEMOS A LA TABLA SGC_TBLEDADESMONTO
	  if($conexion->query($insert)===TRUE){
		  echo 1;
	  } else {
		  echo 2;
	  } 
  }elseif($_POST['opcion']=='editar_producto'){
	  $update = "update s_producto_cia set nombre='".$_POST['productos']."' where id_prcia=".$_POST['idprcia']." and id_ef_cia='".$_POST['id_ef_cia']."' and id_producto=".$_POST['id_producto'].";"; 
	  
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($update)===TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }
  }elseif($_POST['opcion']=='active_marca'){
	  if($_POST['text']=='activar'){
		  $update = "update s_au_marca set activado=1 where id_marca='".$_POST['id_marca']."' and id_ef='".$_POST['id_ef']."';"; 
		  if($conexion->query($update)===TRUE){$response=TRUE;}else{$response=FALSE;}
		  $update2="update s_au_modelo set activado=1 where id_marca='".$_POST['id_marca']."';";
		  if($conexion->query($update2)===TRUE){$response=TRUE;}else{$response=FALSE;}
		   
	  }elseif($_POST['text']=='desactivar'){
		  $update = "update s_au_marca set activado=0 where id_marca='".$_POST['id_marca']."' and id_ef='".$_POST['id_ef']."';"; 
		  if($conexion->query($update)===TRUE){$response=TRUE;}else{$response=FALSE;}
		  $update2="update s_au_modelo set activado=0 where id_marca='".$_POST['id_marca']."';";
		  if($conexion->query($update2)===TRUE){$response=TRUE;}else{$response=FALSE;}
	  }	
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($response){				
		 echo 1;
	  } else {
		 echo 2;
	  }	
  }elseif($_POST['opcion']=='active_modelo'){
	  if($_POST['text']=='activar'){
		  $update2="update s_au_modelo set activado=1 where id_modelo='".$_POST['id_modelo']."' and id_marca='".$_POST['id_marca']."';";
		  
		   
	  }elseif($_POST['text']=='desactivar'){
		  $update2="update s_au_modelo set activado=0 where id_modelo='".$_POST['id_modelo']."' and id_marca='".$_POST['id_marca']."';";
		  
	  }	
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($update2)===TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }	
  }elseif($_POST['opcion']=='crea_pregunta'){
	  $txtpregunta = $conexion->real_escape_string($_POST['pregunta']);
	  //ORDENAMOS LAS PREGUNTAS		
	  $sql_orden="SELECT
					id_pregunta,
					orden
				 FROM
					s_pregunta
				 WHERE
					id_ef_cia='".$_POST['id_ef_cia']."' and producto='DE'	   
				 ORDER BY
					orden ASC";
	  $resOrden = $conexion->query($sql_orden,MYSQLI_STORE_RESULT);
	  $num = $resOrden->num_rows;
	  $orden = $num+1;
	  
						  
	  //METEMOS LOS DATOS A LA BASE DE DATOS
	  $insert ="INSERT INTO s_pregunta(id_pregunta, pregunta, orden, respuesta, producto, id_ef_cia) "
			  ."VALUES(NULL, '".$txtpregunta."', ".$orden.", ".$_POST['respuesta'].", 'DE', '".$_POST['id_ef_cia']."')";
	  
	  
	  //VERIFICAMOS SI HUBO ERROR EN EL INGRESO DEL REGISTRO
	  if($conexion->query($insert)===TRUE){	
		  echo 1;
	  } else {
		  echo 2;
	  }
  }elseif($_POST['opcion']=='edita_pregunta'){
	  $update = "update s_pregunta set pregunta='".$_POST['pregunta']."', respuesta=".$_POST['respuesta']." where id_pregunta=".$_POST['id_pregunta']." and id_ef_cia='".$_POST['id_ef_cia']."' and producto='DE';"; 
	  
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($update)===TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }
  }elseif($_POST['opcion']=='active_pregunta'){
	  if($_POST['text']=='activar'){
		  $update = "update s_pregunta set activado=1 where id_pregunta=".$_POST['id_pregunta']." and id_ef_cia='".$_POST['id_ef_cia']."' and producto='DE';"; 
		  
		   
	  }elseif($_POST['text']=='desactivar'){
		  $update = "update s_pregunta set activado=0 where id_pregunta=".$_POST['id_pregunta']." and id_ef_cia='".$_POST['id_ef_cia']."' and producto='DE';"; 
		  
	  }	
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($update)===TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }	
  }elseif($_POST['opcion']=='enabled_disabled_usuario'){
	  if($_POST['text']=='activar'){
		  $update = "update s_usuario set activado=1 where id_usuario='".base64_decode($_POST['id_usuario'])."';"; 
		  
		   
	  }elseif($_POST['text']=='desactivar'){
		  $update = "update s_usuario set activado=0 where id_usuario='".base64_decode($_POST['id_usuario'])."';"; 
		  
	  }	
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conecion->query($update)===TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }	  
  }elseif($_POST['opcion']=='tipo_cambio'){
	    //PRIMERO ACTUALIZAMOS TODOS LOS REGISTROS A CERO
		$vec=explode('|',$_POST['variable']);
		$id_tc=$vec[0];
		$id_ef=$vec[1];
		$update = "UPDATE s_tipo_cambio SET activado=0 where id_ef='".$id_ef."';";
		if($conexion->query($update)===TRUE){ $response=TRUE;}else{ $response=FALSE;}
		
		//POSTERIORMENTE ACTUALIZAMOS EL REGISTRO ELEGIDO A 1
		$update2="update s_tipo_cambio set activado=1 where id_tc=".$id_tc." and id_ef='".$id_ef."';";
		if($conexion->query($update2)===TRUE){ $response=TRUE;}else{ $response=FALSE;}
		if($response){
		   echo 1;	
		} else{
		   echo 2;
		}
  }elseif($_POST['opcion']=='crea_prodextra'){
	  /*$select="select
				  sdpe.rango
				from
				 s_de_producto_extra as sdpe
				 inner join s_ef_compania as efcia on (efcia.id_ef_cia=sdpe.id_ef_cia)
				where
				  sdpe.id_ef_cia='".$_POST['id_ef_cia']."' and efcia.producto='DE'
				order by sdpe.id_pr_extra desc
                limit 1;";
	  $res = $conexion->query($select,MYSQLI_STORE_RESULT);
	  $row_cnt = $res->num_rows;
	  if($row_cnt>0){
		  $regi=$res->fetch_array(MYSQLI_ASSOC);			  
		  $jsonData=$regi['rango'];
		  $phpArray = json_decode($jsonData, true);
		  if($_POST['rango_min_bs']>$phpArray[1] && $phpArray[1]<$_POST['rango_max_bs']){  
			//GUARDAMOS LOS RANGOS
			//CREAMOS UN VECTOR PARA GUARDAR LOS RANGOS
			$myarray = array(0 => $_POST['rango_min_bs'], 1 => $_POST['rango_max_bs'], 2 => $_POST['rango_min_usd'], 3 => $_POST['rango_max_usd']);
			$myJson = $conexion->real_escape_string(json_encode($myarray));	
			$id_new_pr_extra = generar_id_codificado('@S#1$2013');
			$insert ="INSERT INTO s_de_producto_extra(id_pr_extra, id_ef_cia, rango, pr_hospitalario, pr_vida, pr_cesante, pr_prima) "
			  ."VALUES('".$id_new_pr_extra."', '".$_POST['id_ef_cia']."', '".$myJson."', ".$_POST['hospitalario'].", ".$_POST['vida'].", ".$_POST['cesantia'].", ".$_POST['prima'].")";
			  if($conexion->query($insert)===TRUE){
				echo 1;   
			  }else{
				echo 2;  
			  }
			  
		  }else{
			//DWEVOLVEMOS ERROR
			echo 0;   
		  }
	  }else{*/
		  //EL PRIMER REGISTRO NO COMPARAMOS PROCEDEMOS A GUARDAR
		  //CREAMOS UN VECTOR PARA GUARDAR LOS RANGOS
		  $myarray = array(1 => $_POST['txtRgMinBs'], 2 => $_POST['txtRgMaxBs'], 3 => $_POST['txtRgMinUsd'], 4 => $_POST['txtRgMaxUsd']);
		  $myJson = $conexion->real_escape_string(json_encode($myarray));	
		  $id_new_pr_extra = generar_id_codificado('@S#1$2013');
		  $insert ="INSERT INTO s_de_producto_extra(id_pr_extra, id_ef_cia, rango, pr_hospitalario, pr_vida, pr_cesante, pr_prima) "
			."VALUES('".$id_new_pr_extra."', '".$_POST['id_ef_cia']."', '".$myJson."', ".$_POST['txtHospitalario'].", ".$_POST['txtVida'].", ".$_POST['txtCesantia'].", ".$_POST['txtPrima'].")";
			if($conexion->query($insert)===TRUE){
			  echo 1;   
			}else{
			  echo 2;  
			}  
	  //}
	  
  }elseif($_POST['opcion']=='edita_prodextra'){
	  
	   $myarray = array(1 => $_POST['rango_min_bs'], 2 => $_POST['rango_max_bs'], 3 => $_POST['rango_min_usd'], 4 => $_POST['rango_max_usd']);
	  $myJson = $conexion->real_escape_string(json_encode($myarray));
	  $update = "update s_de_producto_extra set rango='".$myJson."', pr_hospitalario=".$_POST['hospitalario'].", pr_vida=".$_POST['vida'].", pr_cesante=".$_POST['cesantia'].", pr_prima=".$_POST['prima']." where id_pr_extra='".$_POST['id_pr_extra']."' and id_ef_cia='".$_POST['id_ef_cia']."';";
	   if($conexion->query($update)===TRUE){
		  echo 1; 
	   }else{
		  echo 2; 
	   }
	   
  }elseif($_POST['opcion']=='elimina_prodextra'){
	   $delete = "delete from s_de_producto_extra where id_pr_extra='".$_POST['id_pr_extra']."' and id_ef_cia='".$_POST['id_ef_cia']."' limit 1;";
	   if($conexion->query($delete)===TRUE){
		  echo 1; 
	   }else{
		  echo 2;
	   }
  }elseif($_POST['opcion']=='crea_marcatarjeta'){
	   //METEMOS LOS DATOS A LA BASE DE DATOS
	   $busca = "select count(marca) as row_cta from s_th_marca where id_ef_cia='".$_POST['id_ef_cia']."' and marca='".$_POST['marcatarjeta']."';";
	   $resb = $conexion->query($busca,MYSQLI_STORE_RESULT);
	   $regib = $resb->fetch_array(MYSQLI_ASSOC);
	   if($regib['row_cta']>0){
		  echo 0;
	   }else{
		   $id_new_marca = generar_id_codificado('@S#1$2013');
		   $insert ="INSERT INTO s_th_marca(id_marca, id_ef_cia, marca, activado) "
				  ."VALUES('".$id_new_marca."', '".$_POST['id_ef_cia']."', '".$_POST['marcatarjeta']."', 0)";
		   if($conexion->query($insert)===TRUE){
			  echo 1; 
		   }else{
			  echo 2;
		   }
	   }
  }elseif($_POST['opcion']=='active_marcatj'){
	  if($_POST['text']=='activar'){
		  $update = "update s_th_marca set activado=1 where id_ef_cia='".base64_decode($_POST['id_ef_cia'])."' and id_marca='".base64_decode($_POST['id_marca'])."';"; 
		  
		   
	  }elseif($_POST['text']=='desactivar'){
		  $update = "update s_th_marca set activado=0 where id_ef_cia='".base64_decode($_POST['id_ef_cia'])."' and id_marca='".base64_decode($_POST['id_marca'])."';"; 
		 
	  }	
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($update) === TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }	 
  }elseif($_POST['opcion']=='crea_tipotarjeta'){
	 $busca = "select count(tarjeta) as row_cta from s_th_tarjeta where codigo ='".$_POST['codigo']."';";
	 $respb = $conexion->query($busca,MYSQLI_STORE_RESULT);
	 $regib = $respb->fetch_array(MYSQLI_ASSOC);
	 if($regib['row_cta']>0){
		 echo 0;
	 }else{
		 $id_new_tarjeta = generar_id_codificado('@S#1$2013');
		 $insert ="INSERT INTO s_th_tarjeta(id_tarjeta, tarjeta, codigo) "
					  ."VALUES('".$id_new_tarjeta."', '".$_POST['tipotarjeta']."', '".$_POST['codigo']."')";
		 if($conexion->query($insert)===TRUE){
		   echo 1; 
		 }else{
		   echo 2;
		 }
	 }
  }elseif($_POST['opcion']=='crea_primatarjeta'){
	     $i=1; $vec_prim = array();
		 while($i<=$_POST['num_tarj']){
			 $tarjeta=$_POST["id_tarjeta".$i];
			 $vec_prim[$tarjeta]=$_POST["txtPrima".$i];
			 $i++;
		 }
	     $id_new_prima = generar_id_codificado('@S#1$2013');
		 $insert ='INSERT INTO s_th_prima(id_prima, id_ef_cia, prima) VALUES("'.$id_new_prima.'", "'.$_POST['id_ef_cia'].'", "'.$conexion->real_escape_string(json_encode($vec_prim)).'")';
		 if($conexion->query($insert)===TRUE){
		   echo 1; 
		 }else{
		   echo 2;
		 } 
  }elseif($_POST['opcion']=='edita_primatarjeta'){
	     $i=1; $vec_prim = array();
		 while($i<=$_POST['num_tarj']){
			 $tarjeta=$_POST["id_tarjeta".$i];
			 $vec_prim[$tarjeta]=$_POST["txtPrima".$i];
			 $i++;
		 }
		 $update = 'update s_th_prima set prima="'.$conexion->real_escape_string(json_encode($vec_prim)).'" where id_ef_cia="'.$_POST['id_ef_cia'].'" and id_prima="'.$_POST['id_prima'].'";';
		 if($conexion->query($update)===TRUE){
			 echo 1;
		 }else{
			 echo 2;
		 } 
  }elseif($_POST['opcion']=='crear_modalidad'){
	     $id_new_modalidad = generar_id_codificado('@S#1$2013');
		 $vec = explode('|',$_POST['modalidad']);
		 $vec2 = explode('|',$_POST['producto']);
		 $insert ='INSERT INTO s_modalidad(id_modalidad, modalidad, codigo, producto, poliza, id_ef, activado) VALUES("'.$id_new_modalidad.'", "'.$vec[1].'", "'.$vec[0].'", "'.$vec2[0].'", "'.$_POST['poliza'].'", "'.$_POST['idefin'].'", 0)';
		 if($conexion->query($insert)===TRUE){
		   echo 1; 
		 }else{
		   echo 2;
		 } 
  }elseif($_POST['opcion']=='active_modalidad'){
	  if($_POST['text']=='activar'){
		  $update = "update s_modalidad set activado=1 where id_modalidad='".base64_decode($_POST['id_modalidad'])."' and id_ef='".base64_decode($_POST['id_ef'])."';"; 
		  
		   
	  }elseif($_POST['text']=='desactivar'){
		  $update = "update s_modalidad set activado=0 where id_modalidad='".base64_decode($_POST['id_modalidad'])."' and id_ef='".base64_decode($_POST['id_ef'])."';"; 
		 
	  }	
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($update) === TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }	 
  }elseif($_POST['opcion']=='enabled_disabled_cia'){
	  if($_POST['text']=='activar'){
		  $update = "update s_compania set activado=1 where id_compania='".base64_decode($_POST['id_compania'])."';"; 
		  
		   
	  }elseif($_POST['text']=='desactivar'){
		  $update = "update s_compania set activado=0 where id_compania='".base64_decode($_POST['id_compania'])."';"; 
		 
	  }	
	  //VERIFICMOS SI HUBO ERROR EN EL INGRESO
	  if($conexion->query($update) === TRUE){				
		 echo 1;
	  } else {
		 echo 2;
	  }	  
  }
  
  
function generar_id_codificado($prefijo){
	$valor='';
	$valor=uniqid($prefijo,true);
	return $valor;
}
?>