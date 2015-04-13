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
			mostrar_pagina($_SESSION['id_usuario_sesion'], $_SESSION['tipo_sesion'], $_SESSION['usuario_sesion'], $_SESSION['id_ef_sesion'], $conexion, $lugar);
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
						
								agregar_nueva_modalidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
								
							} else {
								//VEMOS SI NOS PASAN UN ID DE USUARIO
								if(isset($_GET['id_modalidad'])) {
						
									if(isset($_GET['darbaja'])) {
										
										desactivar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									}elseif(isset($_GET['daralta'])){ 
									
									    activar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								    
									}elseif(isset($_GET['editar'])) {
										//SI NO ME PASAN 'CPASS' NI 'ELIMINAR', MUESTRO EL FORM PARA EDITAR USUARIO
										editar_modalidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
										
									} 
								} else {
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE FORMULARIOS EXISTENTES
									mostrar_lista_ocupacion($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
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
function mostrar_lista_ocupacion($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>

<link type="text/css" rel="stylesheet" href="plugins/jalerts/jquery.alerts.css"/>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="plugins/jalerts/jquery.alerts.js"></script>
<script type="text/javascript">
   $(function(){
	   $("a[href].eliminar").click(function(e){
		   var variable = $(this).attr('id'); 		  
		   var vec = variable.split('|');
		   var id_modalidad = vec[0];
		   var id_ef = vec[1];
		   var c = vec[2];
		   jConfirm("¿Esta seguro de eliminar la modalidad?", "Eliminar registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_modalidad='+id_modalidad+'&id_ef='+id_ef+'&opcion=eliminar_modalidad';
						$.ajax({
							   async: true,
							   cache: false,
							   type: "POST",
							   url: "eliminar_registro.php",
							   data: dataString,
							   success: function(datareturn) {
									  //alert(datareturn)
									  if(datareturn==1){
										 //location.reload(true);
										 $('#delete-'+c).hide('slow');
									  }else if(datareturn==2){
										jAlert("El registro no pudo eliminarse intente nuevamente", "Mensaje");
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
	   
	   $("a[href].accionef").click(function(e){
		   var valor = $(this).attr('id');
		   var vec = valor.split('|');
		   var id_modalidad = vec[0];
		   var id_ef = vec[1];
		   var text = vec[2]; 		  
		   jConfirm("¿Esta seguro de "+text+" la modalidad?", ""+text+" registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_modalidad='+id_modalidad+'&id_ef='+id_ef+'&text='+text+'&opcion=active_modalidad';
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
		 $.ambiance({message: "<?php echo base64_decode($msg);?>", 
				title: "Notificacion",
				type: "<?php echo $valor?>",
				timeout: 5
				});
		 //location.load("sgc.php?l=usuarios&idhome=1");
		 //$(location).attr('href', 'sgc.php?l=crearusuario&idhome=1');		
		 setTimeout( "$(location).attr('href', 'index.php?l=modalidad&var=<?php echo $var;?>');",5000 );
	<?php }?>
	 
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
							sh.id_ef = ef.id_ef);";
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
							sh.id_ef = ef.id_ef)
						  and ef.id_ef = '".$id_ef_sesion."';";
}
$resef = $conexion->query($selectEf,MYSQLI_STORE_RESULT);
$num_regi_ef =$resef->num_rows;
echo'<div class="da-panel collapsible">
		<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			<ul class="action_user">
				<li style="margin-right:6px;">
				   <a href="?l=modalidad&var='.$_GET['var'].'&crear=v" class="da-tooltip-s various fancybox.ajax" title="Añadir Modalidad">
				   <img src="images/add_new.png" width="32" height="32"></a>
				</li>
			</ul>
		</div>
	 </div>';
	while($regief = $resef->fetch_array(MYSQLI_ASSOC)){	
			$selectFor="select
						  smod.id_modalidad,
						  smod.modalidad,
						  smod.codigo,
						  smod.producto,
						  smod.poliza,
						  smod.id_ef,
						  smod.activado,
						  (case smod.activado
						     when 0 then 'Inactivo'
							 when 1 then 'Activo'
						   end) as activado_text,
						  sh.producto_nombre
						from
						  s_modalidad as smod
						  inner join s_sgc_home as sh on (sh.id_ef=smod.id_ef) 
						where
						  smod.id_ef='".$regief['id_ef']."' and smod.producto=sh.producto and sh.modalidad=true
						order by
						  smod.id_modalidad asc;";
			  
			$res = $conexion->query($selectFor,MYSQLI_STORE_RESULT);	
			echo'
			<div class="da-panel collapsible">
				<div class="da-panel-header">
					<span class="da-panel-title">
						<img src="images/icons/black/16/list.png" alt="" />
						<b>'.$regief['nombre'].'</b> - Listado de Registros de Modalidad
					</span>
				</div>
				<div class="da-panel-content">
					<table class="da-table">
						<thead>
							<tr>
								<th><b>Modalidad</b></th>
								<th><b>Codigo</b></th>
								<th><b>Producto</b></th>
								<th><b>Póliza</b></th>
								<th><b>Estado</b></th>
								<th></th>
							</tr>
						</thead>
						<tbody>';
						  $num = $res->num_rows;
						  if($num>0){
							    $c=0;
								while($regi = $res->fetch_array(MYSQLI_ASSOC)){
									$c++;
									echo'<tr ';
											if((boolean)$regi['activado']===false){
												echo'style="background:#D44D24; color:#ffffff;"'; 
											 }else{
												echo'';	 
											 }
										echo' id="delete-'.$c.'">
											<td>'.$regi['modalidad'].'</td>
											<td>'.$regi['codigo'].'</td>
											<td>'.$regi['producto_nombre'].'</td>
											<td>'.$regi['poliza'].'</td>
											<td>'.$regi['activado_text'].'</td>
											<td class="da-icon-column">
											   <ul class="action_user">
												  <li style="margin-right:5px;"><a href="?l=modalidad&id_modalidad='.base64_encode($regi['id_modalidad']).'&id_ef='.base64_encode($regief['id_ef']).'&editar=v&var='.$_GET['var'].'" class="edit da-tooltip-s" title="Editar"></a></li>';
											      if((boolean)$regi['activado']===false){
													  echo'<li><a href="#" id="'.base64_encode($regi['id_modalidad']).'|'.base64_encode($regi['id_ef']).'|activar" class="daralta da-tooltip-s accionef" title="Activar"></a></li>';
												  }else{
													  echo'<li><a href="#" id="'.base64_encode($regi['id_modalidad']).'|'.base64_encode($regi['id_ef']).'|desactivar" class="darbaja da-tooltip-s accionef" title="Desactivar"></a></li>';  
												  }
											 echo'<li><a href="#" class="eliminar da-tooltip-s" title="Eliminar" id="'.$regi['id_modalidad'].'|'.$regief['id_ef'].'|'.$c.'"></a></li>';
										  echo'</ul>	
											</td>
										</tr>';
								}
								$res->free();			
						  }else{
							 echo'<tr><td colspan="7">
									  <div class="da-message info">
										   No existe registros alguno, ingrese nuevos registros
									  </div>
								  </td></tr>';
						  }
				   echo'</tbody>
					</table>
				</div>
			</div>';
	}
	$resef->free();
}

//FUNCION QUE PERMITE VISUALIZAR EL FORMULARIO NUEVO USUARIO
function agregar_nueva_modalidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
	

	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['btnGuardar'])) {
		
			//SEGURIDAD
			$idefin = $conexion->real_escape_string($_POST['idefin']);
			$producto = $conexion->real_escape_string($_POST['productoMod']);
			$modalidad = $conexion->real_escape_string($_POST['txtModalidad']);
			$poliza = $conexion->real_escape_string($_POST['txtPoliza']);
			//SACAMOS EL ID CODIFICADO UNICO
			$id_new_modalidad = generar_id_codificado('@S#1$2013');					
			//METEMOS LOS DATOS A LA BASE DE DATOS
			 $vec = explode('|',$modalidad);
			 $vec2 = explode('|',$producto);
			 $insert ='INSERT INTO s_modalidad(id_modalidad, modalidad, codigo, producto, poliza, id_ef, activado) VALUES("'.$id_new_modalidad.'", "'.$vec[1].'", "'.$vec[0].'", "'.$vec2[0].'", "'.$poliza.'", "'.$_POST['idefin'].'", 0)';
			
			
			//VERIFICAMOS SI HUBO ERROR EN EL INGRESO DEL REGISTRO
			if($conexion->query($insert)===TRUE){
								
				$mensaje="Se registro correctamente los datos del formulario";
			    header('Location: index.php?l=modalidad&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
			} else {
				$mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			    header('Location: index.php?l=modalidad&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
				exit;
			}
		
	} else {
		//MUESTRO EL FORM PARA CREAR UNA CATEGORIA
		mostrar_crear_modalidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
	}
}

//VISUALIZAMOS EL FORMULARIO CREA USUARIO
function mostrar_crear_modalidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
   ?>
    
	<script type="text/javascript">
       $(document).ready(function() {
           //HABILITAMOS PRODUCTO DE ACUERDO A LA ENTIDAD FINANCIERA EXISTENTE DE LA TABLA HOME
		   $('#idefin').change(function(){
			  var idefin = $(this).prop('value');
			  if(idefin!=''){
				  $('#content-entidadf').fadeIn('slow');
				  var dataString = 'idefin='+idefin+'&opcion=busca_productos_mod';
				   $.ajax({
						 async: true,
						 cache: false,
						 type: "POST",
						 url: "buscar_registro.php",
						 data: dataString,
						 success: function(datareturn) {
							  //alert(datareturn);
							  $('#entidad-loading').html(datareturn);
						 }
				   });
			  }else{
				  $('#content-entidadf').fadeOut('slow');
			  }
		   });
		   
		   
		   //BOTON SUBMIT 
		   $('#frmAdiModalidad').submit(function(e){
				  var idefin = $('#idefin option:selected').prop('value');
				  var producto = $('#productoMod option:selected').prop('value');
				  var modalidad = $('#txtModalidad option:selected').prop('value');
				  var poliza = $('#txtPoliza').prop('value');
		          
				  //alert(producto);
				  var cont=0;
				  $(this).find('.required').each(function(){
						if(idefin!=''){
							$('#errorentidad').hide('slow');
						}else{
							cont++;
							$('#errorentidad').show('slow');
							$('#errorentidad').html('Seleccione entidad financiera');
						}
						
						if(producto!=''){
							$('#errorproductomod').hide('slow');
						}else{
							cont++;
							$('#errorproductomod').show('slow');
							$('#errorproductomod').html('Seleccione producto');
						}
						
						if(modalidad!=''){
							$('#errormodalidad').hide('slow');
						}else{
							cont++;
							$('#errormodalidad').show('slow');
							$('#errormodalidad').html('Seleccione modalidad');
						}
						
						if(poliza!=''){
							if(poliza.match(/^[0-9A-Z\s\-]+$/)){
								 $('#errorpoliza').hide('slow');
							}else{
								cont++;
				                $('#errorpoliza').show('slow');
							    $('#errorpoliza').html('Ingrese solo alfanumerico');
							}
						}else{
						   cont++;
						   $('#errorpoliza').show('slow');
						   $('#errorpoliza').html('Ingrese la poliza');	
						}
						
						if(cont==0){
						      /* 
							   $("#frmAdiModalidad :submit").attr("disabled", true);
							   e.preventDefault();
							   //var FormCadena = $(this).serialize();
							   var dataString = 'idefin='+idefin+'&producto='+producto+'&modalidad='+modalidad+'&poliza='+poliza+'&opcion=crear_modalidad';
							   //alert (dataString);
							   //ejecutando ajax 
							   $.ajax({
									 async: true,
									 cache: false,
									 type: "POST",
									 url: "accion_registro.php",
									 //data: FormCadena+'&opcion=guardar_modalidad',
									 data: dataString,
									 beforeSend: function(){
										  $("#response-loading").css({
											  'height': '30px'
										  });
									 },
									 complete: function(){
										  $("#response-loading").css({
											  "background": "transparent"
										  });
									 },
									 success: function(datareturn) {
											alert(datareturn);
											if(datareturn==1){
											   $('#response-loading').html('<div style="color:#62a426; text-align:center;">Se registro correctamente el registro</div>');
												//window.setTimeout('location.reload()', 3000); 
												setTimeout( "$(location).attr('href', 'index.php?l=modalidad&var=<?=$_GET['var'];?>');",3000 );	
											}else if(datareturn==2){
											   $('#response-loading').html('<div style="color:#d44d24; text-align:center;">Hubo un error al registrar el registro, consulte con su administrador</div>');
											   e.preventDefault();
											}
											
									 }
							   });
							   */
						}else{
						   e.preventDefault();
						   //$('#btnUsuario').attr('disabled', true);
						}
				  });
		   });
		   
		   //CONVERTIR A MAYUSCULAS
		   $('#txtPoliza').keyup(function() {
			  $(this).val($(this).val().toUpperCase());
		   }); 
		   
		   //BOTON CANCELAR
		   $('#btnCancelar').click(function(){   
			   var variable=$('#var').prop('value');
			   $(location).prop('href','index.php?l=ocupacion&var='+variable);
		   });   
	   });
    </script>
 
 <?php
 //SACAMOS LAS ENTIDADES EXISTENTES
	if($tipo_sesion=='ROOT'){
		$select1="select 
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
							  sh.id_ef = ef.id_ef);";		 
	}else{
		$select1="select 
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
							  sh.id_ef = ef.id_ef)
						  and ef.id_ef='".$id_ef_sesion."';";		
    }
	 $res1 = $conexion->query($select1,MYSQLI_STORE_RESULT);
	 
 //CREAMOS NUESTRO VECTOR DE MODALIDADES + SU CODIGO
 $mod_data = array("Protección de Tarjetas de Crédito","Protección de Tarjetas de Débito", "Rotura de Maquinaria", "Todo Riesgo Garantias Prendarias (Prima Mensual)", "Todo Riesgo Garantias Prendarias (Prima Única)", "Todo Riesgo Garantias Hipotecarias (Prima Mensual)", "Todo Riesgo Garantias Hipotecarias (Prima Única)", "Automotores Servicio Particular (Prima Mensual)", "Automotores Servicio Público (Prima Mensual)", "Automotores Servicio Particular (Prima Única)", "Automotores Servicio Público (Prima Única)", "Póliza Unificada de Automotores");	
 $mod_code = array("PTC", "PTD", "RMQ", "GPM", "GPU", "GHM", "GHU", "SPTM", "SPBM", "SPTU", "SPBU", "PUA");
 $num_regi = count($mod_data);//CANTIDAD DE DATOS EN EL ARRAY
 
 echo'<div class="da-panel collapsible">
		<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			<ul class="action_user">
				<li style="margin-right:6px;">
				   <a href="?l=modalidad&var='.$_GET['var'].'" class="da-tooltip-s" title="Volver">
				   <img src="images/retornar.png" width="32" height="32"></a>
				</li>
			</ul>
		</div>
	 </div>';		
 echo'<div class="da-panel" style="width:600px;">
		<div class="da-panel-header">
			<span class="da-panel-title">
				<img src="images/icons/black/16/pencil.png" alt="" />
				Nuevo Registro Modalidad
			</span>
		</div>
		<div class="da-panel-content">
			<form class="da-form" name="frmAdiModalidad" action="" method="post" id="frmAdiModalidad">
				<div class="da-form-row">
					 <label style="text-align:right;"><b>Entidad Financiera</b></label>
					 <div class="da-form-item large">
						 <select id="idefin" name="idefin" class="required" style="width:210px;">';
							echo'<option value="">seleccione...</option>';
							while($regi1 = $res1->fetch_array(MYSQLI_ASSOC)){
								echo'<option value="'.$regi1['id_ef'].'">'.$regi1['nombre'].'</option>';  
							}
							$res1->free();
					echo'</select>
						 <span class="errorMessage" id="errorentidad"></span>
				     </div>	 
				</div>
				<div class="da-form-row" style="display: none;" id="content-entidadf">
					<label style="text-align:right;"><b>Producto</b></label>
					<div class="da-form-item small">
					  <span id="entidad-loading" class="loading-entf"></span>
					</div>
				</div>
				<div class="da-form-row">
					<label style="text-align:right;"><b>Modalidad</b></label>
					<div class="da-form-item large">
					   <select id="txtModalidad" name="txtModalidad" class="required" style="width:315px;">';
						      echo'<option value="">seleccione...</option>';
						  for($i=0; $i<$num_regi; $i++){
							  echo'<option value="'.$mod_code[$i].'|'.$mod_data[$i].'">'.$mod_data[$i].'</option>';  
						  }
				  echo'</select>
					   <span class="errorMessage" id="errormodalidad"></span>
					</div>
				</div>
				<div class="da-form-row">
					<label style="text-align:right;"><b>Poliza</b></label>
					<div class="da-form-item large">
						<input class="required textbox" type="text" name="txtPoliza" id="txtPoliza" value="" autocomplete="off" style="width:200px;"/>
						<span class="errorMessage" id="errorpoliza"></span>
					</div>
				</div>
																
				<div class="da-button-row">
				   <input type="button" value="Cancelar" class="da-button gray left" name="btnCancelar" id="btnCancelar"/>
				   <input type="submit" value="Guardar" class="da-button green" name="btnGuardar" id="btnGuardar"/>
				   <input type="hidden" value="guardar" name="btnGuardar" id="btnGuardar"/>
				   <div id="response-loading" class="loading-fac"></div>	
				   <input type="hidden" name="var" id="var" value="'.$_GET['var'].'"/>
			    </div>
			</form>
		</div>
	</div>';
}

//FUNCION PARA EDITAR UN USUARIO
function editar_modalidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion) {
		
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['btnGuardar'])) {
            $id_modalidad = base64_decode($_GET['id_modalidad']);
            $id_ef = base64_decode($_GET['id_ef']);
            //SEGURIDAD
			
			$poliza = $conexion->real_escape_string($_POST['txtPoliza']);
			
            //CARGAMOS LOS DATOS A LA BASE DE DATOS
            $update = "UPDATE s_modalidad SET poliza='".$poliza."' ";
            $update.= "WHERE id_modalidad='".$id_modalidad."' and id_ef='".$id_ef."' LIMIT 1;";
            //echo $update;
            

            if($conexion->query($update)===TRUE){
                $mensaje="Se actualizo correctamente los datos del formulario";
			    header('Location: index.php?l=modalidad&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
            } else{
                $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			    header('Location: index.php?l=modalidad&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
				exit;
            }

	} else {
	  //MUESTRO FORM PARA EDITAR UNA CATEGORIA
	  mostrar_editar_modalidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
	}
	
}

//VISUALIZAMOS EL FORMULARIO PARA EDITAR EL FORMULARIO
function mostrar_editar_modalidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
 ?>
    
	<script type="text/javascript">
       $(function(){
		   //HABILITAMOS PRODUCTO DE ACUERDO A LA ENTIDAD FINANCIERA EXISTENTE DE LA TABLA HOME
		   $('#idefin').change(function(){
			  var idefin = $(this).prop('value');
			  if(idefin!=''){
				  $('#content-entidadf').fadeIn('slow');
				  var dataString = 'idefin='+idefin+'&opcion=buscar_producto_ocupacion';
				   $.ajax({
						 async: true,
						 cache: false,
						 type: "POST",
						 url: "buscar_registro.php",
						 data: dataString,
						 success: function(datareturn) {
							  //alert(datareturn);
							  $('#entidad-loading').html(datareturn);
						 }
				   });
			  }else{
				  $('#content-entidadf').fadeOut('slow');
			  }
		   });
			
			//BOTON SUBMIT 
		   $('#frmEdiModalidad').submit(function(e){
				  //var idefin = $('#idefin option:selected').prop('value');
				  //var producto = $('#productoMod option:selected').prop('value');
				  //var modalidad = $('#txtModalidad option:selected').prop('value');
				  var poliza = $('#txtPoliza').prop('value');
		          
				  //alert(producto);
				  var cont=0;
				  $(this).find('.required').each(function(){
						/*if(idefin!=''){
							$('#errorentidad').hide('slow');
						}else{
							cont++;
							$('#errorentidad').show('slow');
							$('#errorentidad').html('Seleccione entidad financiera');
						}
						
						if(producto!=''){
							$('#errorproductomod').hide('slow');
						}else{
							cont++;
							$('#errorproductomod').show('slow');
							$('#errorproductomod').html('Seleccione producto');
						}
						
						if(modalidad!=''){
							$('#errormodalidad').hide('slow');
						}else{
							cont++;
							$('#errormodalidad').show('slow');
							$('#errormodalidad').html('Seleccione modalidad');
						}*/
						
						if(poliza!=''){
							if(poliza.match(/^[0-9A-Z\s\-]+$/)){
								 $('#errorpoliza').hide('slow');
							}else{
								cont++;
				                $('#errorpoliza').show('slow');
							    $('#errorpoliza').html('Ingrese solo alfanumerico');
							}
						}else{
						   cont++;
						   $('#errorpoliza').show('slow');
						   $('#errorpoliza').html('Ingrese la poliza');	
						}
						
						if(cont==0){
						      /* 
							   $("#frmAdiModalidad :submit").attr("disabled", true);
							   e.preventDefault();
							   //var FormCadena = $(this).serialize();
							   var dataString = 'idefin='+idefin+'&producto='+producto+'&modalidad='+modalidad+'&poliza='+poliza+'&opcion=crear_modalidad';
							   //alert (dataString);
							   //ejecutando ajax 
							   $.ajax({
									 async: true,
									 cache: false,
									 type: "POST",
									 url: "accion_registro.php",
									 //data: FormCadena+'&opcion=guardar_modalidad',
									 data: dataString,
									 beforeSend: function(){
										  $("#response-loading").css({
											  'height': '30px'
										  });
									 },
									 complete: function(){
										  $("#response-loading").css({
											  "background": "transparent"
										  });
									 },
									 success: function(datareturn) {
											alert(datareturn);
											if(datareturn==1){
											   $('#response-loading').html('<div style="color:#62a426; text-align:center;">Se registro correctamente el registro</div>');
												//window.setTimeout('location.reload()', 3000); 
												setTimeout( "$(location).attr('href', 'index.php?l=modalidad&var=<?=$_GET['var'];?>');",3000 );	
											}else if(datareturn==2){
											   $('#response-loading').html('<div style="color:#d44d24; text-align:center;">Hubo un error al registrar el registro, consulte con su administrador</div>');
											   e.preventDefault();
											}
											
									 }
							   });
							   */
						}else{
						   e.preventDefault();
						   //$('#btnUsuario').attr('disabled', true);
						}
				  });
		   });
		   
		   //CONVERTIR A MAYUSCULAS
		   $('#txtCodigo').keyup(function() {
			  $(this).val($(this).val().toUpperCase());
		   }); 
		   
		   //BOTON CANCELAR
		   $('#btnCancelar').click(function(){   
			   var variable=$('#var').prop('value');
			   $(location).prop('href','index.php?l=ocupacion&var='+variable);
		   });   
	   });
    </script>
 
 <?php    
	$id_modalidad = base64_decode($_GET['id_modalidad']);
    $id_ef = base64_decode($_GET['id_ef']);
	//SACAMOS LOS DATOS DE LA BASE DE DATOS
	$select = "select 
				smod.id_modalidad,
				smod.modalidad,
				smod.codigo,
				smod.producto,
				smod.poliza,
				smod.id_ef,
				sh.producto_nombre,
				sef.nombre as entidad_financiera
			from
				s_modalidad as smod
					inner join
				s_sgc_home as sh ON (sh.id_ef = smod.id_ef)
					inner join
				s_entidad_financiera as sef ON (sef.id_ef = sh.id_ef)
			where
				smod.id_ef = '".$id_ef."'
					and smod.producto = sh.producto
					and sh.modalidad = true
					and smod.id_modalidad = '".$id_modalidad."'
					and sef.activado = true
			limit 0 , 1;";
				$rs = $conexion->query($select, MYSQLI_STORE_RESULT);
				$num = $rs->num_rows;
	
	//SI EXISTE EL USUARIO DADO EN LA BASE DE DATOS, LO EDITAMOS
	if($num>0) {
       echo'<div class="da-panel collapsible">
			  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
				  <ul class="action_user">
					  <li style="margin-right:6px;">
						 <a href="?l=modalidad&var='.$_GET['var'].'" class="da-tooltip-s" title="Volver">
						 <img src="images/retornar.png" width="32" height="32"></a>
					  </li>
				  </ul>
			  </div>
		   </div>';
	   $fila = $rs->fetch_array(MYSQLI_ASSOC);
	   $rs->free();
	   echo'<div class="da-panel" style="width:600px;">
				  <div class="da-panel-header">
					  <span class="da-panel-title">
						  <img src="images/icons/black/16/pencil.png" alt="" />
						  Editar Registro Modalidad
					  </span>
				  </div>
				  <div class="da-panel-content">
					<form class="da-form" name="frmEdiModalidad" action="" method="post" id="frmEdiModalidad">
						<div class="da-form-row">
							 <label style="text-align:right;"><b>Entidad Financiera</b></label>
							 <div class="da-form-item large">
								 <i>'.$fila['entidad_financiera'].'</i>
							 </div>	 
						</div>
						<div class="da-form-row" style="display: none;" id="content-entidadf">
							<label style="text-align:right;"><b>Producto</b></label>
							<div class="da-form-item small">
							  <i>'.$fila['producto_nombre'].'</i>
							</div>
						</div>
						<div class="da-form-row">
							<label style="text-align:right;"><b>Modalidad</b></label>
							<div class="da-form-item large">
							   <i>'.$fila['modalidad'].'</i>
							</div>
						</div>
						<div class="da-form-row">
							<label style="text-align:right;"><b>Poliza</b></label>
							<div class="da-form-item large">
								<input class="required textbox" type="text" name="txtPoliza" id="txtPoliza" value="'.$fila['poliza'].'" autocomplete="off" style="width:200px;"/>
								<span class="errorMessage" id="errorpoliza"></span>
							</div>
						</div>
																		
						<div class="da-button-row">
						   <input type="button" value="Cancelar" class="da-button gray left" name="btnCancelar" id="btnCancelar"/>
						   <input type="submit" value="Guardar" class="da-button green" name="btnGuardar" id="btnGuardar"/>
						   <input type="hidden" value="guardar" name="btnGuardar" id="btnGuardar"/>
						   <div id="response-loading" class="loading-fac"></div>	
						   <input type="hidden" name="var" id="var" value="'.$_GET['var'].'"/>
						</div>
					</form>
				</div>
			  </div>';
	
	} else {
		//SI NO EXISTE EL USUARIO DADO EN LA BASE DE DATOS, VOLVEMOS A LA LISTA DE USUARIOS
		header('Location: index.php?l=modalidad&var='.$_GET['var']);
	}
}
?>