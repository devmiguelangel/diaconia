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
			header('Location: index.php?l=entidades&var=enti');
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
						  
                            if(isset($_GET['crear_entidad'])){
								//AGREGAMOS UNA NUEVA ENTIDAD FINANCIERA
								agregar_nueva_entidadfin($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
							}else{
								if(isset($_GET['identidad'])){
									
									if(isset($_GET['editar'])){
										//EDITAMOS LA ENTIDAD CREADA
										editar_entidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
									}
								}else{
								  //MOSTRAMOS LA LSITA DE ENTIDADES FINANCIERAS CREADAS
								  mostrar_lista_entidadesfin($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								}
							}
							//NECESITO SABER SI DEBO CREAR UN NUEVO USUARIO
							/*if(isset($_GET['crear'])) {
						
								agregar_nueva_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								
							} else {
								
								//VEMOS SI NOS PASAN UN ID DE USUARIO
								if(isset($_GET['identidad'])) {
						
									if(isset($_GET['darbaja'])) {
										
										desactivar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									}elseif(isset($_GET['daralta'])){ 
									
									    activar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								    
									}elseif(isset($_GET['editar'])) {
										//SI NO ME PASAN 'CPASS' NI 'ELIMINAR', MUESTRO EL FORM PARA EDITAR USUARIO
										editar_entidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									} 
								} else {
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE FORMULARIOS EXISTENTES
									mostrar_lista_entidades($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								}
							}*/
							
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
function mostrar_lista_entidadesfin($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
?>

<link type="text/css" rel="stylesheet" href="plugins/jalerts/jquery.alerts.css"/>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="plugins/jalerts/jquery.alerts.js"></script>
<script type="text/javascript">
   $(function(){
	   $("a[href].accionef").click(function(e){
		   var valor = $(this).attr('id');
		   var vec = valor.split('|');
		   var id_ef = vec[0];
		   var text = vec[1]; 		  
		   jConfirm("¿<span lang='es'>Esta seguro de "+text+" la Entidad Financiera</span>?", ""+text+" registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_ef='+id_ef+'&text='+text+'&opcion=enabled_disabled_ef';
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
		 setTimeout( "$(location).attr('href', 'index.php?l=entidades&var=<?php echo $var;?>');",5000 );
	<?php }?>
	 
  });
</script>
<?php
$selectFor="select
			   id_ef,
			   nombre,
			   logo,
			   case activado
				 when 1 then 'activo'
				 when 0 then 'inactivo'
			   end as activado,
			   codigo
			from
			  s_entidad_financiera;";
   if($res = $conexion->query($selectFor, MYSQLI_STORE_RESULT)){

		echo'<div class="da-panel collapsible">
				  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
					  <ul class="action_user">
						  <li style="margin-right:6px;">
							 <a href="?l=entidades&crear_entidad=v&var='.$_GET['var'].'" class="da-tooltip-s" title="<span lang=\'es\'>Agregar Registro</span>">
							 <img src="images/add_new.png" width="32" height="32"></a>
						  </li>
					  </ul>
				  </div>
			  </div>';
		
		echo'
		<div class="da-panel collapsible" style="width:860px;">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/list.png" alt="" />
					<span lang="es">Entidades Financieras</span>
				</span>
			</div>
			<div class="da-panel-content">
				<table class="da-table">
					<thead>
						<tr>
							<th><b><span lang="es">Entidad Financiera</span></b></th>
							<th><b><span lang="es">Codigo</span></b></th>
							<th style="width:200px; text-align:center"><b><span lang="es">Imagen</span></b></th>
							<th style="width:100px;"><b><span lang="es">Estado</span></b></th>
							<th></th>
						</tr>
					</thead>
					<tbody>';
					  $num = $res->num_rows;
					  if($num>0){
							while($regi = $res->fetch_array(MYSQLI_ASSOC)){
								echo'<tr ';
										if($regi['activado']=='inactivo'){
											echo'style="background:#D44D24; color:#ffffff;"'; 
										 }else{
											echo'';	 
										 }
								echo'>
										<td>'.$regi['nombre'].'</td>
										<td>'.$regi['codigo'].'</td>
										<td style="text-align:center;">';
										   if($regi['logo']!=''){
											   if(file_exists('../images/'.$regi['logo'])){
												  $imagen = getimagesize('../images/'.$regi['logo']);    //Sacamos la información
												  $ancho = $imagen[0];              //Ancho
												  $alto = $imagen[1];   
												  echo'<img src="../images/'.$regi['logo'].'" width="'.($ancho/2).'" height="'.($alto/2).'"/>';
											   }else{
												  echo'<span lang="es">no existe el archivo físico</span>';   
											   }
										   }else{
											  echo'<span lang="es">no existe el nombre del archivo en la base de datos</span>';   
										   }
								   echo'</td>
										<td lang="es">'.$regi['activado'].'</td>
										<td class="da-icon-column">
										   <ul class="action_user">
											  <li style="margin-right:5px;"><a href="?l=entidades&identidad='.base64_encode($regi['id_ef']).'&editar=v&var='.$_GET['var'].'" class="edit da-tooltip-s" title="<span lang=\'es\'>Editar</span>"></a></li>';
											  if($regi['activado']=='inactivo'){
												  echo'<li><a href="#" id="'.base64_encode($regi['id_ef']).'|activar" class="daralta da-tooltip-s accionef" title="<span lang=\'es\'>Activar</span>"></a></li>';
											  }else{
												  echo'<li><a href="#" id="'.base64_encode($regi['id_ef']).'|desactivar" class="darbaja da-tooltip-s accionef" title="<span lang=\'es\'>Desactivar</span>"></a></li>';  
											  }
																		
									  echo'</ul>	
										</td>
									</tr>';
							}
							$res->free();			
					  }else{
						 echo'<tr><td colspan="7">
								  <div class="da-message info" lang="es">
									   <span lang="es">No existe registros alguno, ingrese nuevos registros</span>
								  </div>
							  </td></tr>';
					  }
			   echo'</tbody>
				</table>
			</div>
		</div>';
   }else{
	   echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
		 <span lang='es'> Error en la consulta:</span> ".$conexion->errno. ": ".$conexion->error
		."</div>";
   }
}

//FUNCION QUE PERMITE VISUALIZAR EL FORMULARIO NUEVO USUARIO
function agregar_nueva_entidadfin($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
	$errFlag = false;
	$errArr['errortitulo'] = '';
	$errArr['errorarchivo'] = '';
	$errArr['errorcodigo'] = '';

	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
		
		$validacion = validarImagen('txtArchivo', 2097152, '2 MB', '../images', true);
		if($validacion['flag']) {
			//SE VALIDO CORRECTAMENTE LA IMAGEN
			$imagenServidor = $validacion['archivo'];
		} else {
			//EL ARCHIVO NO SE VALIDO
			$errArr['errorarchivo'] = $validacion['mensaje'];
			$errFlag = true;
		}
	   			
		//VEMOS SI TODO SE VALIDO BIEN
		if($errFlag) {
			//HUBIERON ERRORES, MOSTRAMOS EL FORM CON LOS ERRORES
			mostrar_crear_entidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
		} else {
			//SI NO HUBIERON ERRORES, CARGAMOS LOS DATOS A LA BASE DE DATOS

			//SEGURIDAD
			$titulo = $conexion->real_escape_string($_POST['txtTitulo']);
			$codigo = $conexion->real_escape_string($_POST['txtCodigo']);
			$nombre_hosting = $conexion->real_escape_string($_POST['txtHost']);
							
			
			//generamos un idusuario unico encriptado
			$id_new_ef = generar_id_codificado('@S#1$2013');					
			//METEMOS LOS DATOS A LA BASE DE DATOS
			$insert ="INSERT INTO s_entidad_financiera(id_ef, nombre, logo, codigo, host_ws, activado) "
				    ."VALUES('".$id_new_ef."', '".$titulo."', '".$imagenServidor."', '".$codigo."', '".$nombre_hosting."', 0)";
			$conexion->query($insert);
			
			$vecidmultiple=$_POST['idmultiple'];
			$cant=count($vecidmultiple);	
			//INSERTAMOS LOS PRODUCTOS ELEGIDOS
			for($i=0;$i<$cant;$i++){
				$vec=explode('|',$vecidmultiple[$i]); 
				//generamos un idusuario unico encriptado
				 $id_new_home = generar_id_codificado('@S#1$2013');
				 $insert_ef = "INSERT INTO s_sgc_home(id_home, id_ef, producto, producto_nombre, id_usuario) VALUES('".$id_new_home."', '".$id_new_ef."', '".$vec[0]."', '".$vec[1]."', '".$id_usuario_sesion."');";
				 $conexion->query($insert_ef);
			}
			
			//INSERTAMOS EL NUEVO HOME PARA LA ENTIDAD FINANCIERA
			$id_new_home_ef_H = generar_id_codificado('@S#1$2013');
			$insert_ef_H = "INSERT INTO s_sgc_home(id_home, id_ef, producto, producto_nombre, cliente, cliente_logo, id_usuario) VALUES('".$id_new_home_ef_H."', '".$id_new_ef."', 'H', '', '".$titulo."', '".$imagenServidor."', '".$id_usuario_sesion."');";
			
			
			//VERIFICAMOS SI HUBO ERROR EN EL INGRESO DEL REGISTRO
			if($conexion->query($insert_ef_H) === TRUE){
								
				//REDIMENCIONAR IMAGEN
				$vec = getimagesize('../images/'.$imagenServidor);
				$ancho = $vec[0];
				$alto = $vec[1];
				// *** 1) Initialise / load image
				$resizeObj = new resize('../images/'.$imagenServidor);
				// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
				$resizeObj -> resizeImage($ancho, 75, 'portrait');
				// *** 3) Save image
				$resizeObj -> saveImage('../images/'.$imagenServidor, 100);
				
				$mensaje="Se registro correctamente los datos del formulario";
			    header('Location: index.php?l=entidades&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
			} else {
				$mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			    header('Location: index.php?l=entidades&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
				exit;
			}
		}

	} else {
		//MUESTRO EL FORM PARA CREAR UNA CATEGORIA
		mostrar_crear_entidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
	}
}

//VISUALIZAMOS EL FORMULARIO CREA USUARIO
function mostrar_crear_entidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr){
   ?>
    
	<script type="text/javascript">
       $(document).ready(function() {
		   $('#btnCancelar').click(function(){   
			   var variable=$('#var').prop('value');
			   $(location).prop('href','index.php?l=entidades&var='+variable);
		   });
		   
		   //VERIFICAR CAMPOS
		   $('#frmEntidad').submit(function(e){
			   var titulo = $('#txtTitulo').prop('value');
			   var codigo = $('#txtCodigo').prop('value');
			   var nombre_host = $('#txtHost').prop('value');
			   var sum=0; var cell=0;
			   //var file = ($("#txtArchivo"))[0].files[0];
			   //alert(file); alert(file.name);
			   $(this).find('.required').each(function(){
				   //alert('hola tu');
				   if(titulo!=''){
					   if(titulo.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s\D]+$/)){
						   $('#errortitulo').hide('slow');
					   }else{
						   sum++;
					       $('#errortitulo').show('slow');
					       $('#errortitulo').html('ingrese solo caracteres');
					   }
				   }else{
					   sum++;
					   $('#errortitulo').show('slow');
					   $('#errortitulo').html('ingrese titulo');
				   }
				   
				   if(codigo!=''){
					   if(codigo.match(/^[A-Z]+$/)){
						   
					   }else{
						   sum++;
					       $('#errorcodigo').show('slow');
					       $('#errorcodigo').html('ingrese solo caracteres');
					   }
				   }else{
					  sum++;
					  $('#errorcodigo').show('slow');
					  $('#errorcodigo').html('ingrese codigo');   
				   }
				   
				   $('#idmultiple option').each(function() { 
						if($(this).is(':selected')){
						  //var valor = $(this).prop('value');
						  cell++;
						}
				   });
				   if(cell==0){
						sum++;
						$('#errorproducto').show('slow');
						$('#errorproducto').html('seleccione al menos un producto');   
				   }else{
						$('#errorproducto').hide('slow');
				   }
				   if(nombre_host!=''){
					   $('#errorhosting').hide('slow');
				   }else{
					   sum++;
					   $('#errorhosting').show('slow');
					   $('#errorhosting').html('ingrese el nombre del dominio');
				   } 
			   });
			   
			   if(sum==0){
			     
			   }else{
				   e.preventDefault();
			   }
			   
		   });
		  		  	  
		   //INGRESAR TEXTO EN MAYUSCULA
		   $('#txtCodigo').keyup(function() {
			   $(this).val($(this).val().toUpperCase());
			});   
	   });
    </script>
 
 <?php
  $text = array("Desgravamen", "Automotores", "Todo Riesgo Domiciliario","Ramos Tecnicos", "Tarjetahabiente");
  $value = array("DE", "AU", "TRD", "TRM", "TH");
  $result = count($value);
  echo'<div class="da-panel collapsible">
		  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			  <ul class="action_user">
				  <li style="margin-right:6px;">
					 <a href="?l=entidades&var='.$_GET['var'].'" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
					 <img src="images/retornar.png" width="32" height="32"></a>
				  </li>
			  </ul>
		  </div>
	  </div>'; 		
  echo'<div class="da-panel" style="width:650px;">
		<div class="da-panel-header">
			<span class="da-panel-title">
				<img src="images/icons/black/16/pencil.png" alt="" />
				<span lang="es">Nueva Entidad Financiera</span>
			</span>
		</div>
		<div class="da-panel-content">
			<form class="da-form" name="frmEntidad" id="frmEntidad" action="" method="post" enctype="multipart/form-data">
				<div class="da-form-row">
				   <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
				   <div class="da-form-item large">
					  <input class="textbox required" type="text" name="txtTitulo" id="txtTitulo" style="width: 400px;" value="" autocomplete="off"/>
					  <span class="errorMessage" id="errortitulo" lang="es"></span>
				   </div>
				</div>
				<div class="da-form-row">
				   <label style="text-align:right;"><b><span lang="es">Codigo</span></b></label>
				   <div class="da-form-item large">
					   <input class="textbox required" type="text" id="txtCodigo" name="txtCodigo" value="" autocomplete="off" maxlength="4" style="width: 400px;"/>
					   <span class="errorMessage" id="errorcodigo" lang="es"></span>
				   </div>	   
				</div>
				<div class="da-form-row">
				   <label style="text-align:right;"><b><span lang="es">Nombre del dominio</span></b></label>
				   <div class="da-form-item large">
					   <input class="textbox required" type="text" id="txtHost" name="txtHost" value="" autocomplete="off" style="width: 400px;"/>
					   <span class="errorMessage" id="errorhosting" lang="es"></span>
				   </div>	   
				</div>
				<div class="da-form-row">
				   <label style="text-align:right;"><b><span lang="es">Productos</span></b></label>
				   <div class="da-form-item large">
					<select name="idmultiple[]" id="idmultiple" class="requerid" style="width:200px;" size="5" multiple="multiple">';
						for($i=0;$i<$result;$i++){
						  echo'<option value="'.$value[$i].'|'.$text[$i].'">'.$text[$i].'</option>';  
						}
			  echo'</select>
					<span class="errorMessage" id="errorproducto" lang="es"></span>  
				   </div>
				</div>   		
				<div class="da-form-row">
					<label style="text-align:right;"><b><span lang="es">Archivo</span></b></label>
					<div class="da-form-item large">
					    <span lang="es">El tamaño máximo del archivo es de 2Mb. Se recomienda que la imagen tenga un alto de 75px, el formato del archivo a subir debe ser [JPG] ó [PNG]</span> 
						<input type="file" id="txtArchivo" name="txtArchivo"/>
						<span class="errorMessage">'.$errArr['errorarchivo'].'</span>
					</div>
				</div>
																
				<div class="da-button-row">
					<input type="button" value="Cancelar" class="da-button gray left" id="btnCancelar" lang="es"/>
					<input type="submit" value="Guardar" class="da-button green" name="btnUsuario" id="btnUsuario" lang="es"/>
					<input type="hidden" name="accionGuardar" value="checkdatos"/>
					<input type="hidden" id="var" value="'.$_GET['var'].'"/>
				</div>
			</form>
		</div>
	</div>';
}

//FUNCION PARA EDITAR UN USUARIO
function editar_entidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion) {

	$errFlag = false;
	$errArr['errortitulo'] = '';
	$errArr['errorarchivo'] = '';
	$errArr['errorcodigo'] = '';

	$identidad = base64_decode($_GET['identidad']);
	//$idusuario = strtolower($idusuario);

	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
       
		$validacion = validarImagen('txtArchivo', 2097152, '2 MB', '../images', false);
		if($validacion['flag']) {
			//SE VALIDO CORRECTAMENTE LA IMAGEN
			$imagenServidor = $validacion['archivo'];
		} else {
			//EL ARCHIVO NO SE VALIDO
			$errArr['errorarchivo'] = $validacion['mensaje'];
			$errFlag = true;
		}
	   		

        //VEMOS SI TODO SE VALIDO BIEN
        if($errFlag) {
            //SI HUBIERON ERRORES, MOSTRAMOS EL FORM CON LOS ERRORES
            mostrar_editar_entidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
        } else {
            //SEGURIDAD
			$titulo = $conexion->real_escape_string($_POST['txtTitulo']);
			$codigo = $conexion->real_escape_string($_POST['txtCodigo']);
			$nombre_hosting = $conexion->real_escape_string($_POST['txtHost']);
						            
			//CARGAMOS LOS DATOS A LA BASE DE DATOS
            $update = "UPDATE s_entidad_financiera SET nombre='".$titulo."', codigo='".$codigo."', host_ws='".$nombre_hosting."',";
            if($imagenServidor!=''){
                if(file_exists('../images/'.$_POST['archivoAux'])){
					borra_archivo('../images/'.$_POST['archivoAux']);
					//REDIMENCIONAR IMAGEN
					$vec = getimagesize('../images/'.$imagenServidor);
					$ancho = $vec[0];
					$alto = $vec[1];
					// *** 1) Initialise / load image
					$resizeObj = new resize('../images/'.$imagenServidor);
					// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
					$resizeObj -> resizeImage($ancho, 75, 'portrait');
					// *** 3) Save image
					$resizeObj -> saveImage('../images/'.$imagenServidor, 100);	
				}elseif(!empty($_POST['archivoAux'])){
					//REDIMENCIONAR IMAGEN
					$vec = getimagesize('../images/'.$imagenServidor);
					$ancho = $vec[0];
					$alto = $vec[1];
					// *** 1) Initialise / load image
					$resizeObj = new resize('../images/'.$imagenServidor);
					// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
					$resizeObj -> resizeImage($ancho, 75, 'portrait');
					// *** 3) Save image
					$resizeObj -> saveImage('../images/'.$imagenServidor, 100);	
			    }
                $update.=" logo='".$imagenServidor."' ";
            }else{
                $update.=" logo='".$_POST['archivoAux']."' ";
            }
            $update.="WHERE id_ef='".$identidad."' LIMIT 1;";
            //echo $update;
            $conexion->query($update);
            
			$vecidmultiple=$_POST['idmultiple'];
			$cant=count($vecidmultiple);	
			//INSERTAMOS LOS PRODUCTOS ELEGIDOS
			for($i=0;$i<$cant;$i++){
				$vec=explode('|',$vecidmultiple[$i]);
				$busca="select
						  count(id_home) as cant
						from
						  s_sgc_home
						where
						  id_ef='".$identidad."' and producto='".$vec[0]."';";
				$resib = $conexion->query($busca, MYSQLI_STORE_RESULT);
				$regib = $resib->fetch_array(MYSQLI_ASSOC);
				if($regib['cant']==0){
					//generamos un idusuario unico encriptado
					 $id_new_home = generar_id_codificado('@S#1$2013');
					 $insert_ef = "INSERT INTO s_sgc_home(id_home, id_ef, producto, producto_nombre, id_usuario) VALUES('".$id_new_home."', '".$identidad."', '".$vec[0]."', '".$vec[1]."', '".$id_usuario_sesion."');";
					 if($conexion->query($insert_ef) === TRUE){
						 $resultado=TRUE;
					 }else{
						 $resultado=FALSE; 
					 } 
				}
			}
			$resib->free();
            if($resultado){
                $mensaje="Se actualizo correctamente los datos del formulario";
			    header('Location: index.php?l=entidades&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
            } else{
                $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			    header('Location: index.php?l=entidades&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
				exit;
            }

        }

	} else {
	   //MUESTRO FORM PARA EDITAR UNA CATEGORIA
	   mostrar_editar_entidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
	}
	
}

//VISUALIZAMOS EL FORMULARIO PARA EDITAR EL FORMULARIO
function mostrar_editar_entidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr){
 ?>
    
	<script type="text/javascript">
       $(document).ready(function() {
		   $('#btnCancelar').click(function(){   
			   //alert('hola');
			   var variable=$('#var').prop('value');
			   $(location).prop('href','index.php?l=entidades&var='+variable);
		   });
		   
		   //VERIFICAR CAMPOS
		   $('#frmEntidad').submit(function(e){
			   var titulo = $('#txtTitulo').prop('value');
			   var codigo = $('#txtCodigo').prop('value');
			   var nombre_hosting = $('#txtHost').prop('value');
			   var sum=0; var cell=0;
			   //var file = ($("#txtArchivo"))[0].files[0];
			   //alert(file); alert(file.name);
			   $(this).find('.required').each(function(){
				   //alert('hola tu');
				   if(titulo!=''){
					   if(titulo.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s\D]+$/)){
						   $('#errortitulo').hide('slow');
					   }else{
						   sum++;
					       $('#errortitulo').show('slow');
					       $('#errortitulo').html('ingrese solo caracteres');
					   }
				   }else{
					   sum++;
					   $('#errortitulo').show('slow');
					   $('#errortitulo').html('ingrese titulo');
				   }
				   
				   if(codigo!=''){
					   if(codigo.match(/^[A-Z]+$/)){
						   
					   }else{
						   sum++;
					       $('#errorcodigo').show('slow');
					       $('#errorcodigo').html('ingresesolo caracteres');
					   }
				   }else{
					  sum++;
					  $('#errorcodigo').show('slow');
					  $('#errorcodigo').html('ingrese codigo');   
				   }
				   $('#idmultiple option').each(function() { 
						if($(this).is(':selected')){
						  //var valor = $(this).prop('value');
						  cell++;
						}
				   });
				   if(cell==0){
						sum++;
						$('#errorproducto').show('slow');
						$('#errorproducto').html('seleccione al menos un producto');   
				   }else{
						$('#errorproducto').hide('slow');
				   }
				   if(nombre_hosting!=''){
					   $('#errorhosting').hide('slow');
				   }else{
					   sum++;
					   $('#errorhosting').show('slow');
					   $('#errorhosting').html('ingrese nombre de hosting');
				   } 
			   });
			   
			   if(sum==0){
			     
			   }else{
				   e.preventDefault();
			   }
			   
		   });
		  		  	  
		   //INGRESAR TEXTO EN MAYUSCULA
		   $('#txtCodigo').keyup(function() {
			   $(this).val($(this).val().toUpperCase());
			});   
	   });
    </script>
 
 <?php    
	$identidad = base64_decode($_GET['identidad']);

	//SACAMOS LOS DATOS DE LA BASE DE DATOS
	$select = "select
				   id_ef,
				   nombre,
				   logo,
				   activado,
				   codigo,
				   host_ws 
				from
				  s_entidad_financiera
				where
				  id_ef='".$identidad."' and activado=1
				limit
				  0,1;";
	if($rs = $conexion->query($select, MYSQLI_STORE_RESULT)){
			$num = $rs->num_rows;
			
			//SI EXISTE EL USUARIO DADO EN LA BASE DE DATOS, LO EDITAMOS
			if($num>0) {
		
				$fila = $rs->fetch_array(MYSQLI_ASSOC);
				$rs->free();
				if(isset($_POST['txtTitulo'])) $titulo = $_POST['txtTitulo']; else $titulo = $fila['nombre'];
				if(isset($_POST['txtCodigo'])) $txtCodigo = $_POST['txtCodigo']; else $txtCodigo = $fila['codigo'];
				if(isset($_POST['txtHost'])) $txtHost = $_POST['txtHost']; else $txtHost = $fila['host_ws'];
				
				$archivo = $fila['logo'];
						
				$text = array("Desgravamen", "Automotores", "Todo Riesgo Domiciliario","Ramos Tecnicos", "Tarjetahabiente");
				$value = array("DE", "AU", "TRD", "TRM", "TH");
				$result = count($value);
				echo'<div class="da-panel collapsible">
						<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
							<ul class="action_user">
								<li style="margin-right:6px;">
								   <a href="?l=entidades&var='.$_GET['var'].'" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
								   <img src="images/retornar.png" width="32" height="32"></a>
								</li>
								<li style="margin-right:6px;">
								   <a href="?l=entidades&crear_entidad=v&var='.$_GET['var'].'" class="da-tooltip-s" title="<span lang=\'es\'>Agregar Registro</span>">
								   <img src="images/add_new.png" width="32" height="32"></a>
								</li>
							</ul>
						</div>
					</div>';
						
				  echo'<div class="da-panel" style="width:650px;">
						<div class="da-panel-header">
							<span class="da-panel-title">
								<img src="images/icons/black/16/pencil.png" alt="" />
								<span lang="es">Editar Entidad Financiera</span>
							</span>
						</div>
						<div class="da-panel-content">
							<form class="da-form" name="frmEntidad" id="frmEntidad" action="" method="post" enctype="multipart/form-data">
								<div class="da-form-row">
								  <label style="text-align:right; margin-right:15px;"><b><span lang="es">Imagen</span></b></label>';
								  if($archivo!=''){
									if(file_exists('../images/'.$archivo)){
									   echo'<img src="../images/'.$archivo.'" />';
									}else{
									   echo'no existe el archivo';	
									}
								  }else{
									echo'<span lang="es">campo vacio</span>';  
								  }
						   echo'</div>
								<div class="da-form-row">
									<label style="text-align:right;"><b><span lang="es">Archivo</span></b></label>
									<div class="da-form-item large">
										<span lang="es">El tamaño máximo del archivo es de 2Mb. Se recomienda que la imagen tenga un alto de 75px, el formato del archivo a subir debe ser [JPG] ó [PNG]</span>
										<input type="file" id="txtArchivo" name="txtArchivo"/>
										<span class="errorMessage">'.$errArr['errorarchivo'].'</span>
										<span><span lang="es">Archivo actual:</span> '.$archivo.'</span>
									</div>
								</div>
								<div class="da-form-row">
									<label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
									<div class="da-form-item large">
										<input class="textbox required" type="text" name="txtTitulo" id="txtTitulo" style="width: 400px;" value="'.$titulo.'" autocomplete="off"/>
										<span class="errorMessage" id="errortitulo" lang="es"></span>
									</div>
								</div>
								<div class="da-form-row">
								   <label style="text-align:right;"><b><span lang="es">Codigo</span></b></label>
								   <div class="da-form-item large">
									   <input class="textbox required" type="text" id="txtCodigo" name="txtCodigo" value="'.$txtCodigo.'" autocomplete="off" maxlength="4" style="width: 400px;"/>
									   <span class="errorMessage" id="errorcodigo" lang="es"></span>
								   </div>	   
								</div>
								<div class="da-form-row">
								   <label style="text-align:right;"><b><span lang="es">Nombre del dominio</span></b></label>
								   <div class="da-form-item large">
									   <input class="textbox required" type="text" id="txtHost" name="txtHost" value="'.$txtHost.'" autocomplete="off" style="width: 400px;"/>
									   <span class="errorMessage" id="errorhosting" lang="es"></span>
								   </div>	   
								</div>
								<div class="da-form-row">
								   <label style="text-align:right;"><b><span lang="es">Productos</span></b></label>
								   <div class="da-form-item large">
									<select name="idmultiple[]" id="idmultiple" class="requerid" style="width:200px;" size="5" multiple="multiple">';
										for($j=0;$j<$result;$j++){
											  $selectEnFi="select
															  count(id_ef) as numreg
														   from
															  s_sgc_home
														   where
															  id_ef='".$fila['id_ef']."' and producto='".$value[$j]."';";				                                      $rescant = $conexion->query($selectEnFi,MYSQLI_STORE_RESULT); 
											  $cant = $rescant->fetch_array(MYSQLI_ASSOC);
											  if($cant['numreg']>0){
												 echo'<option value="'.$value[$j].'|'.$text[$j].'" selected>'.$text[$j].'</option>';  
											  }else{
												 echo'<option value="'.$value[$j].'|'.$text[$j].'">'.$text[$j].'</option>';   
											  }
										}
										$rescant->free();
										//unset($vec);
							   echo'</select>
									<span class="errorMessage" id="errorproducto"></span>  
								   </div>
								</div>     												
								<div class="da-button-row">
									<input type="button" value="Cancelar" class="da-button gray left" id="btnCancelar" lang="es"/>
									<input type="submit" value="Guardar" class="da-button green" name="btnUsuario" id="btnUsuario" lang="es"/>
									<input type="hidden" name="accionGuardar" value="checkdatos"/>
									<input type="hidden" name="archivoAux" value="'.$archivo.'"/>
									<input type="hidden" id="var" value="'.$_GET['var'].'"/>
								</div>
							</form>
						</div>
					</div>';
			
			} else {
				//SI NO EXISTE ENTIDADES EN LA BASE DE DATOS
				header('Location: index.php?l=entidades&var='.$_GET['var']);
			}
	}else{
		echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
		  Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error
		."</div>";
	}		
}

?>