<script type="text/javascript" src="plugins/ambience/jquery.ambiance.js"></script>
<script type="text/javascript">
  <?php 
    $op = $_GET["op"];
    $msg = $_GET["msg"];
	$var = $_GET["var"];
	if($op==1){$valor='success';}elseif($op==2){$valor='error';}
  ?>
  $(function(){
    //PLUGIN AMBIENCE
    <?php if($msg!=''){ ?>
		 $.ambiance({message: "<?php echo $msg;?>", 
				title: "Notificacion",
				type: "<?php echo $valor?>",
				timeout: 5
				});
		 //location.load("sgc.php?l=usuarios&idhome=1");
		 //$(location).attr('href', 'sgc.php?l=crearusuario&idhome=1');		
		 setTimeout( "$(location).attr('href', 'index.php?l=des_producto&var=<?php echo $var;?>');",5000 );
	<?php }?>
	 
  });
</script>

<?php
include('sgc_funciones.php');
include('sgc_funciones_entorno.php');
include('main_menu.php');
require_once('config.class.php');
$conexion = new SibasDB();
//TENGO Q VER SI EL USUARIO HA INICIADO SESION
if(isset($_SESSION['usuario_sesion']) && isset($_SESSION['tipo_sesion'])) {
	//SI EL USUARIO HA INICIADO SESION, MOSTRAMOS LA PAGINA
	mostrar_pagina($_SESSION['id_usuario_sesion'], $_SESSION['tipo_sesion'], $_SESSION['usuario_sesion'], $_SESSION['id_ef_sesion'], $conexion, $lugar);
	
} else {
	//SI EL USUARIO NO HA INICIADO SESION, VEMOS SI HA HECHO CLICK EN EL FORMULARIO DE LOGIN
	if(isset($_POST['username'])) {
		//SI HA HECHO CLICK EN EL FORM DE LOGIN, VALIDAMOS LOS DATOS Q HA INGRESADO
		if(validar_login($conexion)) {
			//SI LOS DATOS DEL FORM SON CORRECTOS, MOSTRAMOS LA PAGINA
			header('Location: index.php?l=au_marca_modelo&var=au&list_marca=v');
			exit;
		} else {
			//SI LOS DATOS NO SON CORRECTOS, MOSTRAMOS EL FORM DE LOGIN CON EL MENSAJE DE ERROR
			session_unset();
		    session_destroy();
			session_regenerate_id(true);
			mostrar_login_form(2);
		}
	} else {
		//SI NO HA HECHO CLICK EN EL FORM, MOSTRAMOS EL FORMULARIO DE LOGIN
		session_unset();
		session_destroy();
		session_regenerate_id(true);
		mostrar_login_form(1);
	}
}


//FUNCION PARA MOSTRAR EL SGC PARA ADMINISTRACION DE USUARIOS
function mostrar_pagina($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $lugar) {			
?>
       
	<!-- Main Wrapper. Set this to 'fixed' for fixed layout and 'fluid' for fluid layout' -->
	<div id="da-wrapper" class="fluid">
    
        <!-- Header -->
        <div id="da-header">
        
        	<div id="da-header-top">
                
                <!-- Container -->
                <div class="da-container clearfix">
                    
                    <!-- Logo Container. All images put here will be vertically centere -->
                    <div id="da-logo-wrap">
                        <?php logo_container($tipo_sesion,$id_ef_sesion,$id_usuario_sesion,$conexion);?>
                    </div>
                                      
                    <!-- Header Toolbar Menu -->
                    <div id="da-header-toolbar" class="clearfix">
                        <?php header_toolbar_menu($id_usuario_sesion,$tipo_sesion,$usuario_sesion,$conexion);?>
                    </div>
                                    
                </div>
            </div>
            
            <div id="da-header-bottom">
                <?php header_bottom('i',$_GET['var'],1);?>
            </div>
        </div>
    
        <!-- Content -->
        <div id="da-content">
            
            <!-- Container -->
            <div class="da-container clearfix">
            
                <!-- Sidebar -->
                <div id="da-sidebar-separator"></div>
                <div id="da-sidebar">
                
                    <!-- Main Navigation -->
                    <div id="da-main-nav" class="da-button-container">
                        <?php main_navegation($lugar,$id_usuario_sesion,$tipo_sesion,$usuario_sesion,$conexion);?>
                    </div>
                    
                </div>
                
                <!-- Main Content Wrapper -->
                <div id="da-content-wrap" class="clearfix">
                
                	<!-- Content Area -->
                	<div id="da-content-area">
                    
                    	<div class="grid_4">
                           <?php
                            //NECESITO SABER SI DEBO CREAR UN NUEVO USUARIO
							if(isset($_GET['crear'])) {
						
								agregar_nuevo_tipo_vehiculo($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
								
							} else {
								//VEMOS SI NOS PASAN UN ID DE USUARIO
								if(isset($_GET['list_modelo'])) {
						            //LISTAR MODELOS AUTO
									mostrar_lista_modelo_auto($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
									/*if(isset($_GET['eliminar'])) {
										
										eliminar_producto($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									}elseif(isset($_GET['daralta'])){ 
									
									    activar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								    
									}elseif(isset($_GET['editar'])) {
										//SI NO ME PASAN 'CPASS' NI 'ELIMINAR', MUESTRO EL FORM PARA EDITAR USUARIO
										editar_producto($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									}*/ 
								}elseif(isset($_GET['list_marca'])){
									//LISTAR MARCAS AUTO
									mostrar_lista_marca_auto($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
								}
							}
							
						   ?>
                        </div>
                                                  
                    </div>
                    
                </div>
                
            </div>
            
        </div>
        
        <!-- Footer -->
        <div id="da-footer">
        	<?php footer();?>
        </div>
        
    </div>

<?php
}

//FUNCION QUE PERMITE LISTAR LOS TIPOS DE VEHICULOS
function mostrar_lista_marca_auto($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>
<link type="text/css" rel="stylesheet" href="plugins/fancybox/jquery.fancybox.css"/>
<script type="text/javascript" src="plugins/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript">
     $('.various').fancybox({
	    maxWidth	: 400,
		maxHeight	: 300,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'elastic',
		closeEffect	: 'elastic'	 
	 });
</script>
<link type="text/css" rel="stylesheet" href="plugins/jalerts/jquery.alerts.css"/>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="plugins/jalerts/jquery.alerts.js"></script>
<script type="text/javascript">
   $(function(){
	   $("a[href].accion_active_marca").click(function(e){
		   var valor = $(this).attr('id');
		   var vec = valor.split('|');
		   var id_marca = vec[0];
		   var id_ef = vec[1];
		   var text = vec[2]; 		  
		   jConfirm("¿Esta seguro de "+text+" el tipo de vehiculo?", ""+text+" registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_marca='+id_marca+'&id_ef='+id_ef+'&text='+text+'&opcion=active_marca';
						$.ajax({
							   async: true,
							   cache: false,
							   type: "POST",
							   url: "accion_registro.php",
							   data: dataString,
							   success: function(datareturn) {
									  //alert(datareturn);
									  if(datareturn==1){
										 location.reload(true);
									  }else if(datareturn==2){
										jAlert("El registro no se proceso correctamente intente nuevamente", "Mensaje");
										 e.preventDefault();
									  }
									  
							   }
					    });
					
				} else {
					//jAlert("No te gusta Actualidad jQuery", "Actualidad jQuery");
				}
		   });
		   e.preventDefault();
	   }); 
	   
	});
</script>
<?php
//SACAMOS LAS ENTIDADES FINANCIERAS EXISTENTES Y POSTERIOR ESTEN ACTIVADAS
if($tipo_sesion=='ROOT'){
	  $selectEf="select 
					ef.id_ef, ef.nombre, ef.logo, ef.activado
				from
					s_entidad_financiera as ef
				where
					ef.activado = 1
						and exists( select 
							sh.id_ef
						from
							s_sgc_home as sh
						where
							sh.id_ef = ef.id_ef and sh.producto='AU');";
}else{
     $selectEf="select 
					  ef.id_ef, ef.nombre, ef.logo, ef.activado
				  from
					  s_entidad_financiera as ef
				  where
					  ef.activado = 1 
					    and exists( select 
							sh.id_ef
						from
							s_sgc_home as sh
						where
							sh.id_ef = ef.id_ef and sh.producto='AU')
						  and ef.id_ef = '".$id_ef_sesion."';";
}
   if($resef = $conexion->query($selectEf,MYSQLI_STORE_RESULT)){
	    if($resef->num_rows>0){
			  echo'<div class="da-panel collapsible">
						<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
							<ul class="action_user">
								<li style="margin-right:6px;">
								   <a href="adicionar_registro.php?opcion=crear_marca_auto&id_ef_sesion='.base64_encode($id_ef_sesion).'&tipo_sesion='.base64_encode($tipo_sesion).'" class="da-tooltip-s various fancybox.ajax" title="<span lang=\'es\'>Añadir marca auto</span>">
								   <img src="images/add_new.png" width="32" height="32"></a>
								</li>
							</ul>
						</div>
					</div>';
			   while($regief = $resef->fetch_array(MYSQLI_ASSOC)){	  
				 $select="select
							id_marca,
							id_ef,
							marca,
							activado,
							case activado
							  when 1 then 'activo'
							  when 0 then 'inactivo'
							end as activado_text
						  from
							s_au_marca
						  where
							id_ef='".$regief['id_ef']."';";
				  if($res = $conexion->query($select,MYSQLI_STORE_RESULT)){
						 $num = $res->num_rows;
						 if($num>0){$id='id="da-ex-datatable-numberpaging"';}else{$id='';}				  
						 echo'<div class="da-panel collapsible" style="width:600px;">
								  <div class="da-panel-header">
									  <span class="da-panel-title">
										  <img src="images/icons/black/16/list.png" alt="" />
										  <b>'.$regief['nombre'].'</b> - <span lang="es">Listado marcas auto</span></b>
									  </span>
								  </div>
								  <div class="da-panel-content">
									  <table '.$id.' class="da-table">
										  <thead>
											  <tr>
												  <th><b><span lang="es">Marca de Auto</span></b></th>
												  <th><b><span lang="es">Estado</span></b></th>
												  <th>&nbsp;</th>
											  </tr>
										  </thead>
										  <tbody>';
											
											if($num>0){
												  $c=0;
												  while($regi = $res->fetch_array(MYSQLI_ASSOC)){
													  $c++;
													   echo'<tr ';
																if($regi['activado']==0){
																	echo'style="background:#D44D24; color:#ffffff;"'; 
																 }else{
																	echo'';	 
																 }
														echo'>
															  <td>'.$regi['marca'].'</td>
															  <td lang="es">'.$regi['activado_text'].'</td>
															  <td class="da-icon-column">
																 <ul class="action_user">
																	<li style="margin-right:5px;"><a href="adicionar_registro.php?id_marca='.base64_encode($regi['id_marca']).'&id_ef='.base64_encode($regief['id_ef']).'&id_ef_sesion='.base64_encode($id_ef_sesion).'&tipo_sesion='.base64_encode($tipo_sesion).'&opcion=editar_marca_auto" class="edit da-tooltip-s various fancybox.ajax" title="<span lang=\'es\'>Editar</span>"></a></li>';
																	if($regi['activado']==0){
																		echo'<li style="margin-right:5px;"><a href="#" id="'.$regi['id_marca'].'|'.$regief['id_ef'].'|activar" class="daralta da-tooltip-s accion_active_marca" title="<span lang=\'es\'>Activar</span>"></a></li>';
																	}else{
																		echo'<li style="margin-right:5px;"><a href="#" id="'.$regi['id_marca'].'|'.$regief['id_ef'].'|desactivar" class="darbaja da-tooltip-s accion_active_marca" title="<span lang=\'es\'>Desactivar</span>"></a></li>';  
																	}
																	echo'<li><a href="?l=au_marca_modelo&var='.$_GET['var'].'&list_modelo=v&id_ef='.base64_encode($regief['id_ef']).'&id_marca='.base64_encode($regi['id_marca']).'&marca='.base64_encode($regi['marca']).'&entidad='.base64_encode($regief['nombre']).'" class="add_mod da-tooltip-s various" title="<span lang=\'es\'>Agregar Modelo Auto</span>"></a></li>';
															echo'</ul>	
															  </td>
														  </tr>';
												  }
												  $res->free();			
											}else{
											   echo'<tr><td colspan="7">
														<div class="da-message info" lang="es">
															 No existe ningun dato, ingrese nuevos registros
														</div>
													</td></tr>';
											}
									 echo'</tbody>
									  </table>
								  </div>
							  </div>';  
				  }else{
					  echo'<div class="da-message error">error en la consulta'.$conexion->errno.'&nbsp;'.$conexion->error.'</div>'; 
				  }
			   }
			   $resef->free();
		}else{
			 echo'<div class="da-message warning">
					 <span lang="es">No existe ningun registro, probablemente se debe a</span>:
					 <ul>
						<li lang="es">La Entidad Financiera no tiene asignado el producto Automotores</li>
						<li lang="es">La Entidad Financiera no esta activado</li>
						<li lang="es">La Entidad Financiera no esta creada</li>
					  </ul>
				 </div>'; 
		}
   }else{
	   echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>Error en la consulta: ".$conexion->errno.": ".$conexion->error."</div>";
   }
}

//FUNCION LISTA LOS MODELOS DE AUTO DE MARCA
function mostrar_lista_modelo_auto($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>
<link type="text/css" rel="stylesheet" href="plugins/fancybox/jquery.fancybox.css"/>
<script type="text/javascript" src="plugins/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript">
     $('.various').fancybox({
	    maxWidth	: 400,
		maxHeight	: 300,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'elastic',
		closeEffect	: 'elastic'	 
	 });
</script>
<link type="text/css" rel="stylesheet" href="plugins/jalerts/jquery.alerts.css"/>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="plugins/jalerts/jquery.alerts.js"></script>
<script type="text/javascript">
   $(function(){
	   $("a[href].accion_active_modelo").click(function(e){
		   var valor = $(this).attr('id');
		   var vec = valor.split('|');
		   var id_modelo = vec[0];
		   var id_marca = vec[1];
		   var text = vec[2]; 		  
		   jConfirm("¿Esta seguro de "+text+" el modelo de auto?", ""+text+" registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_modelo='+id_modelo+'&id_marca='+id_marca+'&text='+text+'&opcion=active_modelo';
						$.ajax({
							   async: true,
							   cache: false,
							   type: "POST",
							   url: "accion_registro.php",
							   data: dataString,
							   success: function(datareturn) {
									  //alert(datareturn);
									  if(datareturn==1){
										 location.reload(true);
									  }else if(datareturn==2){
										jAlert("El registro no se proceso correctamente intente nuevamente", "Mensaje");
										 e.preventDefault();
									  }
									  
							   }
					    });
					
				} else {
					//jAlert("No te gusta Actualidad jQuery", "Actualidad jQuery");
				}
		   });
		   e.preventDefault();
	   });
	   
	   //ELIMINAR
	   $("a[href].eliminar_modelo").click(function(e){
		   var valor = $(this).attr('id');
		   var vec = valor.split('|');
		   var id_modelo = vec[0];
		   var id_marca = vec[1];
		   jConfirm("¿Esta seguro de eliminar el modelo de auto?", "eliminar registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_modelo='+id_modelo+'&id_marca='+id_marca+'&opcion=eliminar_modelo';
						$.ajax({
							   async: true,
							   cache: false,
							   type: "POST",
							   url: "accion_registro.php",
							   data: dataString,
							   success: function(datareturn) {
									  //alert(datareturn);
									  if(datareturn==1){
										 location.reload(true);
									  }else if(datareturn==2){
										jAlert("El registro no se proceso correctamente intente nuevamente", "Mensaje");
										 e.preventDefault();
									  }
									  
							   }
					    });
					
				} else {
					//jAlert("No te gusta Actualidad jQuery", "Actualidad jQuery");
				}
		   });
		   e.preventDefault();
	   }); 
	   
	});
</script>
<?php
  $select="select
			  id_modelo,
			  id_marca,
			  modelo,
			  activado,
			  case activado 
			   when 1 then 'activo'
			   when 0 then 'inactivo'
			  end as activado_text
			from
			  s_au_modelo
			where
			  id_marca='".base64_decode($_GET['id_marca'])."';";
   if($res = $conexion->query($select,MYSQLI_STORE_RESULT)){
		$entidad=base64_decode($_GET['entidad']);
		echo'<div class="da-panel collapsible">
				<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
					<ul class="action_user">
						<li style="margin-right:6px;">
							 <a href="?l=au_marca_modelo&var='.$_GET['var'].'&list_marca=v" class="da-tooltip-s" title="Volver">
							 <img src="images/retornar.png" width="32" height="32"></a>
						</li>
						<li style="margin-right:6px;">
						   <a href="adicionar_registro.php?opcion=crear_modelo_auto&id_marca='.$_GET['id_marca'].'&marca='.$_GET['marca'].'&entidad='.$_GET['entidad'].'" class="da-tooltip-s various fancybox.ajax" title="<span lang=\'es\'>Añadir modelo auto</span>">
						   <img src="images/add_new.png" width="32" height="32"></a>
						</li>
					</ul>
				</div>
			</div>';
 	  
   
		   $num = $res->num_rows;
		   if($num>0){$id='id="da-ex-datatable-numberpaging"';}else{$id='';}			  
		   echo'<div class="da-panel collapsible" style="width:600px;">
					<div class="da-panel-header">
						<span class="da-panel-title">
							<img src="images/icons/black/16/list.png" alt="" />
							<b><span lang="es">Marca</span>: '.base64_decode($_GET['marca']).'</b> - <span lang="es">Listado modelos auto</span></b><br/>
							<div style="margin-left:25px; font-weight: bold;">'.$entidad.'</div>
						</span>
					</div>
					<div class="da-panel-content">
						<table '.$id.' class="da-table">
							<thead>
								<tr>
									<th><b><span lang="es">Modelo de Auto</span></b></th>
									<th><b><span lang="es">Estado</span></b></th>
									<th>&nbsp;</th>
								</tr>
							</thead>
							<tbody>';
							  
							  if($num>0){
									$c=0;
									while($regi = $res->fetch_array(MYSQLI_ASSOC)){
										$c++;
										 echo'<tr ';
												  if($regi['activado']==0){
													  echo'style="background:#D44D24; color:#ffffff;"'; 
												   }else{
													  echo'';	 
												   }
										  echo'>
												<td>'.$regi['modelo'].'</td>
												<td lang="es">'.$regi['activado_text'].'</td>
												<td class="da-icon-column">
												   <ul class="action_user">
													  <li style="margin-right:5px;"><a href="adicionar_registro.php?id_modelo='.base64_encode($regi['id_modelo']).'&id_marca='.$_GET['id_marca'].'&marca='.$_GET['marca'].'&entidad='.$_GET['entidad'].'&opcion=editar_modelo_auto" class="edit da-tooltip-s various fancybox.ajax" title="<span lang=\'es\'>Editar</span>"></a></li>';
													  if($regi['activado']==0){
														  echo'<li style="margin-right:5px;"><a href="#" id="'.$regi['id_modelo'].'|'.base64_decode($_GET['id_marca']).'|activar" class="daralta da-tooltip-s accion_active_modelo" title="<span lang=\'es\'>Activar</span>"></a></li>';
													  }else{
														  echo'<li style="margin-right:5px;"><a href="#" id="'.$regi['id_modelo'].'|'.base64_decode($_GET['id_marca']).'|desactivar" class="darbaja da-tooltip-s accion_active_modelo" title="<span lang=\'es\'>Desactivar</span>"></a></li>';  
													  }
													  if($tipo_sesion=='ROOT'){
														 echo'<li style="margin-right:5px;"><a href="#" class="eliminar_modelo da-tooltip-s" id="'.$regi['id_modelo'].'|'.base64_decode($_GET['id_marca']).'" title="<span lang=\'es\'>Eliminar</span>"></a></li>';
													  }
											  echo'</ul>	
												</td>
											</tr>';
									}			
							  }else{
								 echo'<tr><td colspan="7">
										  <div class="da-message info" lang="es">
											   No existe ningun registro, razones alguna
										  </div>
									  </td></tr>';
							  }
					   echo'</tbody>
						</table>
					</div>
				</div>';  
   }else{
	   echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error."</div>";
   }

}

?>