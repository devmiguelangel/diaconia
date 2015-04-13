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
			header('Location: index.php?l=au_tipovehiculo&var=au');
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
								if(isset($_GET['idprcia'])) {
						
									if(isset($_GET['eliminar'])) {
										
										eliminar_producto($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									}elseif(isset($_GET['daralta'])){ 
									
									    activar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								    
									}elseif(isset($_GET['editar'])) {
										//SI NO ME PASAN 'CPASS' NI 'ELIMINAR', MUESTRO EL FORM PARA EDITAR USUARIO
										editar_producto($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									} 
								}else{
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE FORMULARIOS EXISTENTES
									mostrar_lista_tipo_vehiculo($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
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
function mostrar_lista_tipo_vehiculo($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
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
	   $("a[href].accion_active").click(function(e){
		   var valor = $(this).attr('id');
		   var vec = valor.split('|');
		   var id_tipo_vh = vec[0];
		   var id_ef = vec[1];
		   var text = vec[2]; 		  
		   jConfirm("¿Esta seguro de "+text+" el tipo de vehiculo?", ""+text+" registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_tipo_vh='+id_tipo_vh+'&id_ef='+id_ef+'&text='+text+'&opcion=active_tipvehic';
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
								   <a href="adicionar_registro.php?opcion=crear_tipovehi&id_ef_sesion='.base64_encode($id_ef_sesion).'&tipo_sesion='.base64_encode($tipo_sesion).'" class="da-tooltip-s various fancybox.ajax" title="<span lang=\'es\'>Añadir tipo vehiculo</span>">
								   <img src="images/add_new.png" width="32" height="32"></a>
								</li>
							</ul>
						</div>
					</div>';
			   while($regief = $resef->fetch_array(MYSQLI_ASSOC)){	  
				 $select="select
							 id_tipo_vh,
							 id_ef,
							 vehiculo,
							 activado,
							 case activado
							   when 1 then 'activo'
							   when 0 then 'inactivo'
							 end as text_activado	  
						  from
							s_au_tipo_vehiculo
						  where
							id_ef='".$regief['id_ef']."';";
				  if($res = $conexion->query($select,MYSQLI_STORE_RESULT)){			  
						 echo'<div class="da-panel collapsible" style="width:600px;">
								  <div class="da-panel-header">
									  <span class="da-panel-title">
										  <img src="images/icons/black/16/list.png" alt="" />
										  <b>Entidad Financiera: '.$regief['nombre'].'</b> - <span lang="es">Listado tipo de vehiculos</span></b>
									  </span>
								  </div>
								  <div class="da-panel-content">
									  <table class="da-table">
										  <thead>
											  <tr>
												  <th><b><span lang="es">Tipo Vehículo</span></b></th>
												  <th><b><span lang="es">Estado</span></b></th>
												  <th>&nbsp;</th>
											  </tr>
										  </thead>
										  <tbody>';
											$num = $res->num_rows;
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
															  <td>'.$regi['vehiculo'].'</td>
															  <td lang="es">'.$regi['text_activado'].'</td>
															  <td class="da-icon-column">
																 <ul class="action_user">
																	<li style="margin-right:5px;"><a href="adicionar_registro.php?idtipovh='.base64_encode($regi['id_tipo_vh']).'&id_ef='.base64_encode($regief['id_ef']).'&id_ef_sesion='.base64_encode($id_ef_sesion).'&tipo_sesion='.base64_encode($tipo_sesion).'&opcion=editar_tipvehi" class="edit da-tooltip-s various fancybox.ajax" title="<span lang=\'es\'>Editar</span>"></a></li>';
																	if($regi['activado']==0){
																		echo'<li><a href="#" id="'.$regi['id_tipo_vh'].'|'.$regief['id_ef'].'|activar" class="daralta da-tooltip-s accion_active" title="<span lang=\'es\'>Activar</span>"></a></li>';
																	}else{
																		echo'<li><a href="#" id="'.$regi['id_tipo_vh'].'|'.$regief['id_ef'].'|desactivar" class="darbaja da-tooltip-s accion_active" title="<span lang=\'es\'>Desactivar</span>"></a></li>';  
																	}
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

?>