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
			header('Location: index.php?l=compania&var=cs');
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
									mostrar_lista_companias($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
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
function mostrar_lista_companias($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
?>

<link type="text/css" rel="stylesheet" href="plugins/jalerts/jquery.alerts.css"/>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="plugins/jalerts/jquery.alerts.js"></script>
<script type="text/javascript">
   $(function(){
	   $("a[href].eliminar").click(function(e){
		   var valor = $(this).attr('id');
		   var vec = valor.split('|');
		   var id_compania = vec[0];
		   var archivo = vec[1];
		  
		   jConfirm("¿Esta seguro de eliminar la Compañia de Seguros?", "Eliminar registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_compania='+id_compania+'&archivo='+archivo+'&opcion=compania';
						$.ajax({
							   async: true,
							   cache: false,
							   type: "POST",
							   url: "eliminar_registro.php",
							   data: dataString,
							   success: function(datareturn) {
									  //alert(datareturn);
									  if(datareturn==1){
										 location.reload(true);
									  }else if(datareturn==2){
										jAlert("La compañia no pudo eliminarse intente nuevamente", "Mensaje");
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
		   var id_compania = vec[0];
		   var text = vec[1]; 		  
		   jConfirm("¿Esta seguro de "+text+" la Compañia?", ""+text+" registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_compania='+id_compania+'&text='+text+'&opcion=enabled_disabled_cia';
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
		 setTimeout( "$(location).attr('href', 'index.php?l=compania&var=<?php echo $var;?>');",5000 );
	<?php }?>
	 
  });
</script>
<?php
$selectFor="select 
			  id_compania,
			  nombre,
			  logo,
			  if(activado=1,'habilitado','deshabilitado') as activado
			from
			  s_compania
			order by id_compania asc;";
  if($res = $conexion->query($selectFor,MYSQLI_STORE_RESULT)){		  
		echo'<div class="da-panel collapsible">
				  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
					  <ul class="action_user">
						  <li style="margin-right:6px;">
							 <a href="?l=compania&crear=v&var='.$_GET['var'].'" class="da-tooltip-s" title="<span lang=\'es\'>Agregar Cia. Seguros</span>">
							 <img src="images/add_new.png" width="32" height="32"></a>
						  </li>
					  </ul>
				  </div>
			  </div>';
		echo'
		<div class="da-panel collapsible" style="width:850px;">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/list.png" alt="" />
					<span lang="es">Compañías de Seguros</span>
				</span>
			</div>
			<div class="da-panel-content">
				<table class="da-table">
					<thead>
						<tr>
							<th lang="es" style="font-weight:bold;">Compañías de Seguros</th>
							<th style="width:100px; font-weight:bold;" lang="es">Estado</th>
							<th style="width:200px; text-align:center; font-weight:bold;" lang="es">Imagen</th>
							<th></th>
						</tr>
					</thead>
					<tbody>';
					  $num = $res->num_rows;
					  if($num>0){
							while($regi = $res->fetch_array(MYSQLI_ASSOC)){
								echo'<tr ';
										if($regi['activado']=='deshabilitado'){
											echo'style="background:#D44D24; color:#ffffff;"'; 
										 }else{
											echo'';	 
										 }
								echo'>
										<td>'.$regi['nombre'].'</td>
										<td lang="es">'.$regi['activado'].'</td>
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
										<td class="da-icon-column">
										   <ul class="action_user">
											  <li style="margin-right:5px;"><a href="?l=compania&idcompania='.base64_encode($regi['id_compania']).'&editar=v&var='.$_GET['var'].'" class="edit da-tooltip-s" title="<span lang=\'es\'>Editar</span>"></a></li>';
											  if($regi['activado']=='deshabilitado'){
												  echo'<li><a href="#" id="'.base64_encode($regi['id_compania']).'|activar" class="daralta da-tooltip-s accionef" title="<span lang=\'es\'>Activar</span>"></a></li>';
											  }else{
												  echo'<li><a href="#" id="'.base64_encode($regi['id_compania']).'|desactivar" class="darbaja da-tooltip-s accionef" title="<span lang=\'es\'>Desactivar"></a></li>';  
											  }
											  /*
											  if($regi['activado']=='deshabilitado'){
												  echo'<li style="margin-right:5px;"><a href="?l=compania&idcompania='.base64_encode($regi['id_compania']).'&daralta=v&var='.$_GET['var'].'" class="daralta da-tooltip-s" title="Activar"></a></li>';
											  }else{
												  echo'<li style="margin-right:5px;"><a href="?l=compania&idcompania='.base64_encode($regi['id_compania']).'&darbaja=v&var='.$_GET['var'].'" class="darbaja da-tooltip-s" title="Desactivar"></a></li>';  
											  }
											  */
											  $selectDelCia="select 
																	sc.id_compania, sc.nombre
																from
																	s_compania as sc
																where
																	sc.id_compania = '".$regi['id_compania']."'
																		and not exists( select 
																			sdec.id_compania
																		from
																			s_de_em_cabecera as sdec
																		where
																			sdec.id_compania = sc.id_compania);";
											  if($resdel = $conexion->query($selectDelCia,MYSQLI_STORE_RESULT)){
												  $numdel = $resdel->num_rows;
												  if($numdel!=0 and $tipo_sesion=='root'){
													   echo'<li><a href="#" class="eliminar da-tooltip-s" title="Eliminar" id="'.$regi['id_compania'].'|'.$regi['logo'].'"></a></li>';
												  }else{
													  echo'<li style="margin-left:12px;">&nbsp;</li>';
												  }
											  }else{
												 echo'<div class="da-message error"><span lang="es">Error en la consulta</span>'.$conexion->errno.'&nbsp;'.$conexion->error.'</div>';  
											  }
									  echo'</ul>	
										</td>
									</tr>';
							}
							$res->free();			
					  }else{
						 echo'<tr><td colspan="7">
								  <div class="da-message info" lang="es">
									   No existe registros alguno, ingrese nuevos registros
								  </div>
							  </td></tr>';
					  }
			   echo'</tbody>
				</table>
			</div>
		</div>';
  }else{
	 echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
		  <span lang='es'>Error en la consulta:</span> ".$conexion->errno.": ".$conexion->error
		."</div>"; 
  }
}

//FUNCION QUE PERMITE VISUALIZAR EL FORMULARIO NUEVO USUARIO
function agregar_nueva_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
	$errFlag = false;
	$errArr['errortitulo'] = '';
	$errArr['errorarchivo'] = '';
	$errArr['errorproducto'] = '';

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
			mostrar_crear_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
		} else {
			//SI NO HUBIERON ERRORES, CARGAMOS LOS DATOS A LA BASE DE DATOS

			//SEGURIDAD
			$titulo = $conexion->real_escape_string($_POST['txtTitulo']);
			//GENERAMOS ID CODIFICADO UNICO
			$id_new_compania = generar_id_codificado('@S#1$2013');					
			//METEMOS LOS DATOS A LA BASE DE DATOS
			$insert ="INSERT INTO s_compania(id_compania, nombre, logo, activado) "
				    ."VALUES('".$id_new_compania."', '".$titulo."', '".$imagenServidor."', 0)";
			
			//echo $insert;
			//$rs = mysql_query($insert, $conexion);
			
			//VERIFICAMOS SI HUBO ERROR EN EL INGRESO DEL REGISTRO
			if($conexion->query($insert) === TRUE){
				//REDIMENCIONAR IMAGEN
				$vec = getimagesize('../images/'.$imagenServidor);
				$ancho = $vec[0];
				$alto = $vec[1];
				// *** 1) Initialise / load image
				$resizeObj = new resize('../images/'.$imagenServidor);
				// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
				$resizeObj -> resizeImage($ancho, 100, 'portrait');
				// *** 3) Save image
				$resizeObj -> saveImage('../images/'.$imagenServidor, 100);
				
				$mensaje="Se registro correctamente los datos del formulario";
			    header('Location: index.php?l=compania&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
			} else {
				$mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			    header('Location: index.php?l=compania&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
				exit;
			}
		}

	} else {
		//MUESTRO EL FORM PARA CREAR UNA CATEGORIA
		mostrar_crear_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
	}
}

//VISUALIZAMOS EL FORMULARIO CREA USUARIO
function mostrar_crear_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr){
   ?>
    
	<script type="text/javascript">
       $(function(){
		   $('#frmCreaCia').submit(function(e){
			   var titulo_cia=$('#txtTitulo').prop('value');
			   var sum=0;
			   $(this).find('.required').each(function() {
                    if(titulo_cia!=''){
						if(titulo_cia.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s\D]+$/)){
						   $('#errortitulo').hide('slow');
						}else{
						   sum++;
						   $('#errortitulo').show('slow');
						   $('#errortitulo').html('Ingrese solo caracteres');
						}
				    }else{
						sum++;
						$('#errortitulo').show('slow');
						$('#errortitulo').html('Ingrese titulo del la Compañia de Seguros');
				    }
               });
			   if(sum==0){
				   
			   }else{
			      e.preventDefault();
			   }
		   });
		   
		   
		   $('#btnCancelar').click(function(){   
			   var variable=$('#var').prop('value');
			   $(location).prop('href','index.php?l=compania&var='+variable);
		   });   
	   });
    </script>
 
 <?php
  //VARIABLES DE INICIO
  if(isset($_POST['txtTitulo'])) $titulo = $_POST['txtTitulo']; else $titulo = '';
  
		
  echo'<div class="da-panel collapsible">
		  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			  <ul class="action_user">
				  <li style="margin-right:6px;">
					 <a href="?l=compania&var='.$_GET['var'].'" class="da-tooltip-s" title="Volver">
					 <img src="images/retornar.png" width="32" height="32"></a>
				  </li>
			  </ul>
		  </div>
	  </div>'; 	
  echo'<div class="da-panel" style="width:650px;">
		<div class="da-panel-header">
			<span class="da-panel-title">
				<img src="images/icons/black/16/pencil.png" alt="" />
				<span lang="es">Nueva Compañía de Seguros</span>
			</span>
		</div>
		<div class="da-panel-content">
			<form class="da-form" name="frmCreaCia" id="frmCreaCia" action="" method="post" enctype="multipart/form-data">
				<div class="da-form-row">
					<label style="text-align:right;"><b><span lang="es">Titulo Compañía de Seguros</span></b></label>
					<div class="da-form-item large">
						<input class="textbox required" type="text" name="txtTitulo" id="txtTitulo" style="width: 400px;" value="'.$titulo.'" autocomplete="off"/>
						<span class="errorMessage" id="errortitulo" lang="es"></span>
					</div>
				</div>
				<div class="da-form-row">
					<label style="text-align:right;"><b><span lang="es">Archivo</span></b></label>
					<div class="da-form-item large">
					    <span lang="es">El tamaño máximo del archivo es de 500Kb. Se recomienda que la imagen tenga un ancho de 140px y un alto de 50px, el formato del archivo a subir debe ser [JPG]</span> 
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
function editar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion) {

	$errFlag = false;
	$errArr['errortitulo'] = '';
	$errArr['errorarchivo'] = '';
	$errArr['errorproducto'] = '';

	$idcompania = base64_decode($_GET['idcompania']);
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
            mostrar_editar_contenido($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
        } else {
            //SEGURIDAD
			$titulo = $conexion->real_escape_string($_POST['txtTitulo']);
			
            //CARGAMOS LOS DATOS A LA BASE DE DATOS
            $update = "UPDATE s_compania SET nombre='".$titulo."',";
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
					$resizeObj -> resizeImage($ancho, 100, 'portrait');
					// *** 3) Save image
					$resizeObj -> saveImage('../images/'.$imagenServidor, 100);
				}elseif($_POST['archivoAux']!=''){
					borra_archivo('../images/'.$_POST['archivoAux']);
					//REDIMENCIONAR IMAGEN
					$vec = getimagesize('../images/'.$imagenServidor);
					$ancho = $vec[0];
					$alto = $vec[1];
					// *** 1) Initialise / load image
					$resizeObj = new resize('../images/'.$imagenServidor);
					// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
					$resizeObj -> resizeImage($ancho, 100, 'portrait');
					// *** 3) Save image
					$resizeObj -> saveImage('../images/'.$imagenServidor, 100);
				}
                $update.=" logo='".$imagenServidor."' ";
            }else{
                $update.=" logo='".$_POST['archivoAux']."' ";
            }
            $update.="WHERE id_compania='".$idcompania."' LIMIT 1;";
            //echo $update;
            //$rsu = mysql_query($update, $conexion);

            if($conexion->query($update) === TRUE){
                $mensaje="Se actualizo correctamente los datos del formulario";
			    header('Location: index.php?l=compania&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
            } else{
                $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			    header('Location: index.php?l=compania&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
				exit;
            }

        }

	} else {
	  //MUESTRO FORM PARA EDITAR UNA CATEGORIA
	  mostrar_editar_contenido($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
	}
	
}

//VISUALIZAMOS EL FORMULARIO PARA EDITAR EL FORMULARIO
function mostrar_editar_contenido($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr){
 ?>
    
	<script type="text/javascript">
       $(function(){
		   $('#frmEditcia').submit(function(e){
			   var titulo_cia=$('#txtTitulo').prop('value');
			   var sum=0;
			   $(this).find('.required').each(function() {
                    if(titulo_cia!=''){
						if(titulo_cia.match(/^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s\D]+$/)){
						   $('#errortitulo').hide('slow');
						}else{
						   sum++;
						   $('#errortitulo').show('slow');
						   $('#errortitulo').html('Ingrese solo caracteres');
						}
				    }else{
						sum++;
						$('#errortitulo').show('slow');
						$('#errortitulo').html('Ingrese titulo del la Compañia de Seguros');
				    }
               });
			   if(sum==0){
				   
			   }else{
			      e.preventDefault();
			   }
		   });
		   
		   $('#btnCancelar').click(function(){   
			   var variable=$('#var').prop('value');
			   $(location).prop('href','index.php?l=compania&var='+variable);
		   });   
	   });
    </script>
 
 <?php    
	$idcompania = base64_decode($_GET['idcompania']);

	//SACAMOS LOS DATOS DE LA BASE DE DATOS
	$select = "select 
				  id_compania,
				  nombre,
				  logo
				from
				  s_compania
				where
				  id_compania='".$idcompania."' and activado=1
				limit
				  0,1;";
	if($rs = $conexion->query($select, MYSQLI_STORE_RESULT)){
			$num = $rs->num_rows;
			
			//SI EXISTE EL USUARIO DADO EN LA BASE DE DATOS, LO EDITAMOS
			if($num) {
		
				$fila = $rs->fetch_array(MYSQLI_ASSOC);
				$rs->free();
				if(isset($_POST['txtTitulo'])) $titulo = $_POST['txtTitulo']; else $titulo = $fila['nombre'];
				
				$archivo = $fila['logo'];	
				  echo'<div class="da-panel" style="width:650px;">
						<div class="da-panel-header">
							<span class="da-panel-title">
								<img src="images/icons/black/16/pencil.png" alt="" />
								<span lang="es">Editar Compañía de Seguros</span>
							</span>
						</div>
						<div class="da-panel-content">
							<form class="da-form" name="frmEditcia" id="frmEditcia" action="" method="post" enctype="multipart/form-data">
								<div class="da-form-row">
								  <label style="text-align:right;"><b>Imagen</b></label>';
								  if($archivo!=''){
									if(file_exists('../images/'.$archivo)){
									   $imagen = getimagesize('../images/'.$fila['logo']);   
									   $ancho = $imagen[0];              
									   $alto = $imagen[1];	
									   echo'<img src="../images/'.$archivo.'" width="'.($ancho/2).'" height="'.($alto/2).'"/>';
									}else{
									   echo'<span lang="es">No existe el archivo físico</span>';	
									}
								  }else{
									echo'<span lang="es">campo vacio</span>';  
								  }
						   echo'</div>
								<div class="da-form-row">
									<label style="text-align:right;"><b><span lang="es">Compañía de Seguros</span></b></label>
									<div class="da-form-item large">
										<input class="textbox required" type="text" name="txtTitulo" id="txtTitulo" style="width: 400px;" value="'.$titulo.'" autocomplete="off"/>
										<span class="errorMessage" id="errortitulo" lang="es"></span>
									</div>
								</div>
								<div class="da-form-row">
									<label style="text-align:right;"><b><span lang="es">Archivo</span></b></label>
									<div class="da-form-item large">
										<span lang="es">El tamaño máximo del archivo es de 500Kb. Se recomienda que la imagen tenga un ancho de 140px y un alto de 50px, el formato del archivo a subir debe ser [JPG]</span>
										<input type="file" id="txtArchivo" name="txtArchivo"/>
										<span class="errorMessage">'.$errArr['errorarchivo'].'</span>
										<span><b><span lang="es">Archivo actual:</span></b> '.$archivo.'</span>
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
				//SI NO EXISTE EL USUARIO DADO EN LA BASE DE DATOS, VOLVEMOS A LA LISTA DE USUARIOS
				header('Location: index.php?l=compania&var='.$_GET['var']);
			}
	}else{
		echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
		  Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error
		   ."</div>";
    }
}

//FUNCION QUE PERMITE DAR BAJA AL USUARIO
function desactivar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
   $idcompania = base64_decode($_GET['idcompania']);

	
	if(isset($_POST['btnBajaCompania'])) {
		
		$update ="UPDATE s_compania SET activado=0 "
				."WHERE id_compania = '".$idcompania."' LIMIT 1";
			

		if($conexion->query($update) === TRUE){
			//SE METIO A TBLHOMENOTICIAS, VAMOS A VER LA NOTICIA NUEVA
			$mensaje='se desactivo la compañia '.$idusuario.' correctamente';
			header('Location: index.php?l=compania&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
		} else{
			$mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			header('Location: index.php?l=compania&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
		} 
	}elseif(isset($_POST['btnCancelar'])){ 
		//MOSTRAMOS LA LISTA DE USUARIOS
		header('Location: index.php?l=compania&var='.$_GET['var']);
	}else {
		//MOSTRAMOS EL FORMULARIO PARA DAR BAJA COMPANIA
		mostrar_dar_baja_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
	}
	
}

//FUNCION PARA MOSTRAR EL FORMULARIO
function mostrar_dar_baja_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
    $idcompania = base64_decode($_GET['idcompania']);
	$selectCia="select nombre from s_compania where id_compania=".$idcompania.";";
	if($rescia = $conexion->query($selectCia, MYSQLI_STORE_RESULT)){
		$regCia = $rescia->fetch_array(MYSQLI_ASSOC);
		$rescia->free();
		echo'<div style="text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;">';
		echo '<form name="frmDarbaja" action="" method="post" class="da-form">';
		echo '<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" cols="2">';
		echo '<tr><td align="center" width="100%" style="height:60px;">';
		echo 'Al deshabilitar la compañia, '
			.'se actualizara en la base de datos, est&aacute; seguro de deshabilitar la compañia <b>'.$regCia['nombre'].'</b> de forma permanente?';
		echo '</td></tr>
			  <tr> 
			  <td align="center">
			  <input class="da-button green" type="submit" name="btnBajaCompania" value="Desactivar"/>'
			.'<input type="hidden" name="accionEliminar" value="checkdatos"/>
			  <input class="da-button gray left" type="submit" name="btnCancelar" value="Cancelar"/>';
		echo '</td></tr></table></form>';
		echo'</div>';
	}else{
		echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
		  Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error
		   ."</div>";
	}
}

//FUNCION QUE PERMITE DAR ALTA UN REGISTRO
function activar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
   $idcompania = base64_decode($_GET['idcompania']);

	if(isset($_POST['btnAltaCompania'])) {
		
		$update ="UPDATE s_compania SET activado=1 "
				."WHERE id_compania = '".$idcompania."' LIMIT 1";
			

		if($conexion->query($update) === TRUE){
			//SE METIO A TBLHOMENOTICIAS, VAMOS A VER LA NOTICIA NUEVA
			$mensaje='se desactivo la compañia '.$idusuario.' correctamente';
			header('Location: index.php?l=compania&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
		} else{
			$mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			header('Location: index.php?l=compania&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
		} 
	}elseif(isset($_POST['btnCancelar'])){ 
		//MOSTRAMOS LA LISTA DE USUARIOS
		header('Location: index.php?l=compania&var='.$_GET['var']);
	}else {
		//MOSTRAMOS EL FORMULARIO PARA DAR BAJA COMPANIA
		mostrar_dar_alta_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
	}
	
}

//FUNCION PARA MOSTRAR EL FORMULARIO
function mostrar_dar_alta_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
    $idcompania = base64_decode($_GET['idcompania']);
	$selectCia="select nombre from s_compania where id_compania=".$idcompania.";";
	if($rescia = $conexion->query($selectCia,MYSQLI_STORE_RESULT)){
			$regCia = $rescia->fetch_array(MYSQLI_ASSOC);
			$rescia->free();
			echo'<div style="text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;">';
			echo '<form name="frmDarbaja" action="" method="post" class="da-form">';
			echo '<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" cols="2">';
			echo '<tr><td align="center" width="100%" style="height:60px;">';
			echo 'Al habilitar la compañia, '
				.'se actualizara en la base de datos, est&aacute; seguro de habilitar la compañia <b>'.$regCia['nombre'].'</b> de forma permanente?';
			echo '</td></tr>
				  <tr> 
				  <td align="center">
				  <input class="da-button green" type="submit" name="btnAltaCompania" value="Activar"/>'
				.'<input type="hidden" name="accionEliminar" value="checkdatos"/>
				  <input class="da-button gray left" type="submit" name="btnCancelar" value="Cancelar"/>';
			echo '</td></tr></table></form>';
			echo'</div>';
	}else{
		echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
		  Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error
		   ."</div>";
	}
}
?>