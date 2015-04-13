<?php
include('sgc_funciones.php');
include('sgc_funciones_entorno.php');
include('resize-class.php');
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
			header('Location: index.php?l=th_tipotarjeta&var=th&list_producto=v');
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
						
								agregar_nueva_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								
							} else {
								//VEMOS SI NOS PASAN UN ID DE USUARIO
								if(isset($_GET['idcompania'])) {
						
									if(isset($_GET['darbaja'])) {
										
										desactivar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									}elseif(isset($_GET['daralta'])){ 
									
									    activar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								    
									}elseif(isset($_GET['editar'])) {
										//SI NO ME PASAN 'CPASS' NI 'ELIMINAR', MUESTRO EL FORM PARA EDITAR USUARIO
										editar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									} 
								} else {
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE FORMULARIOS EXISTENTES
									mostrar_lista_tipo_tarjeta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
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

//FUNCION QUE PERMITE LISTAR LOS FORMULARIOS
function mostrar_lista_tipo_tarjeta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
?>
<link type="text/css" rel="stylesheet" href="plugins/fancybox/jquery.fancybox.css"/>
<script type="text/javascript" src="plugins/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript">
     $('.tipotarjeta').fancybox({
	    maxWidth	: 400,
		maxHeight	: 220,
		fitToView	: false,
		width		: '70%',
		height		: '100%',
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
	   $("a[href].eliminar").click(function(e){
		   var variable = $(this).attr('id');
		   var vec = variable.split('|');
		   jConfirm("¿Esta seguro de eliminar tipo de tarjeta?", "Eliminar registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_tarjeta='+vec[0]+'&opcion=elimina_tipotarjeta';
						$.ajax({
							   async: true,
							   cache: false,
							   type: "POST",
							   url: "eliminar_registro.php",
							   data: dataString,
							   success: function(datareturn) {
									  //alert(datareturn);
									  if(datareturn==1){
										 //location.reload(true);
										 $('#delete-'+vec[1]).hide('slow');
									  }else if(datareturn==2){
										jAlert("El registro no se pudo eliminar, intente nuevamente", "Mensaje");
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
$selectFor="select  
			  id_tarjeta,
			  tarjeta,
			  codigo
			from
			  s_th_tarjeta
			order by 
			  id_tarjeta asc;";
$res = $conexion->query($selectFor,MYSQLI_STORE_RESULT);		  
echo'<div class="da-panel collapsible">
		  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			  <ul class="action_user">
				  <li style="margin-right:6px;">
					 <a href="adicionar_registro.php?opcion=crea_tipotarjeta" class="da-tooltip-s tipotarjeta fancybox.ajax" title="<span lang=\'es\'>Agregar nuevo registro</span>">
					 <img src="images/add_new.png" width="32" height="32"></a>
				  </li>
			  </ul>
		  </div>
	  </div>';
echo'
<div class="da-panel collapsible" style="width:500px;">
	<div class="da-panel-header">
		<span class="da-panel-title">
			<img src="images/icons/black/16/list.png" alt="" />
			<b><span lang="es">Lista Tipo de Tarjeta</span></b>
		</span>
	</div>
	<div class="da-panel-content">
		<table class="da-table">
			<thead>
				<tr>
					<th><span lang="es">Tipo de Tarjeta</span></th>
					<th><span lang="es">Codigo</span></th>
					<th></th>
				</tr>
			</thead>
			<tbody>';
			  $num = $res->num_rows;
			  if($num>0){
				    $c=0;
					while($regi = $res->fetch_array(MYSQLI_ASSOC)){
						$c++;
						echo'<tr id="delete-'.$c.'">
								<td>'.$regi['tarjeta'].'</td>
								<td>'.$regi['codigo'].'</td>
								<td class="da-icon-column">
								   <ul class="action_user">';
									 
									  $busca="select
												  count(id_cotizacion) as row_cta
												from
												  s_th_cot_cabecera
												where
												  id_tarjeta='".$regi['id_tarjeta']."';";
									  if($resb = $conexion->query($busca,MYSQLI_STORE_RESULT)){
										  $regib = $resb->fetch_array(MYSQLI_ASSOC);
										  if($regib['row_cta']==0){
											   echo'<li><a href="#" class="eliminar da-tooltip-s" title="<span lang=\'es\'>Eliminar</span>" id="'.$regi['id_tarjeta'].'|'.$c.'"></a></li>';
										  }else{
											   echo'<li style="margin-left:12px;">&nbsp;</li>';
										  }
									  }else{
										 //echo'; 
										 echo'<div class="da-message error"><span lang="es">error en la consulta</span>'.$conexion->errno.'&nbsp;'.$conexion->error.'</div>';
									  }
									  
							  echo'</ul>	
								</td>
							</tr>';
					}
					$res->free();			
			  }else{
			     echo'<tr><td colspan="3">
				          <div class="da-message info" lang="es">
                               No existe ningun dato, ingrese nuevos registros
                          </div>
				      </td></tr>';
			  }
	   echo'</tbody>
		</table>
	</div>
</div>';
}

?>