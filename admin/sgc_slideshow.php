<script type="text/javascript" src="plugins/ambience/jquery.ambiance.js"></script>
<script type="text/javascript">
  <?php 
    $op = $_GET["op"];
    $msg = $_GET["msg"];
	$var = $_GET["var"];
	if(isset($_GET["contenido"])){$contenido='v';}else{$contenido='';}
	
	if($op==1){$valor='success'; $time=5; $milseg=5000;}elseif($op==2){$valor='error'; $time=10; $milseg=10000;}
  ?>
  $(function(){
    //PLUGIN AMBIENCE
    <?php if($msg!=''){ ?>
		 $.ambiance({message: "<?php echo base64_decode($msg);?>", 
				title: "Notificacion",
				type: "<?php echo $valor?>",
				timeout: <?php echo $time;?>
				});
		 //location.load("sgc.php?l=usuarios&idhome=1");
		 //$(location).attr('href', 'sgc.php?l=crearusuario&idhome=1');		
		 setTimeout( "$(location).attr('href', 'index.php?l=slideshow&contenido=<?php echo $contenido;?>&var=<?php echo $var;?>');",<?php echo $milseg;?> );
	<?php }?>
	 
  });
</script>
<!-- elRTE Plugin -->

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
			header('Location: index.php?l=ecritorio');
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

//FUNCION PARA MOSTRAR EL SGC PARA ADMINISTRACION IMAGES SLIDESHOW
function mostrar_pagina($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $lugar) {
?>
<script type="text/javascript">
     $('.update_image').fancybox({
	    maxWidth	: 600,
		maxHeight	: 600,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'	 
	 });
</script> 
<?php
  $selectUsu="select
				id_usuario,
				usuario,
				nombre
		      from
				s_usuario
			  where
				id_usuario='".$id_usuario_sesion."' and usuario='".$usuario_sesion."'
			  limit
				0,1;";
  $resu = $conexion->query($selectUsu,MYSQLI_STORE_RESULT);				
  $reg = $resu->fetch_array(MYSQLI_ASSOC);	
  $resu->free();
?>	
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
                    <!-- Header Toolbar Menu -->
                    <div id="da-header-toolbar" class="clearfix">
                        <?php header_toolbar_menu($id_usuario_sesion,$tipo_sesion,$usuario_sesion,$conexion);?>   
                    </div>
                                    
                </div>
            </div>
            
            <div id="da-header-bottom">
                <?php header_bottom('i',$_GET['var'],1);//(inicio,variable,nivel)?>
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
						
									agregar_nueva_imagen($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
								
							} else {
								//VEMOS SI NOS PASAN UN ID
								if(isset($_GET['id_slider'])) {
						
									//VEO SI ME PASAN VARIABLE PARA CAMBIAR DE PASSWORD
									if(isset($_GET['cpass'])) {
										//MOSTRARMOS EL FORM PARA CAMBIAR DE PASSWORD
										cambiar_password($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
						
									} elseif(isset($_GET['editar'])) {
										//SI NO ME PASAN 'CPASS' NI 'ELIMINAR', MUESTRO EL FORM PARA EDITAR USUARIO
										editar_imagen($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
									} 
								}else {
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE USUARIOS EXISTENTES
									mostrar_lista_imagenes($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
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

//MOSTRAMOS LA LISTA DE IMAGENES
function mostrar_lista_imagenes($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>
<!-- Photoswipe Plugin -->
<script type="text/javascript" src="plugins/photoswipe/lib/klass.min.js"></script>
<script type="text/javascript" src="plugins/photoswipe/js/code.photoswipe.jquery-3.0.4.min.js"></script>
<link rel="stylesheet" href="plugins/photoswipe/css/photoswipe.css" media="screen" />

<!-- prettyPhoto Plugin -->
<script type="text/javascript" src="plugins/prettyphoto/js/jquery.prettyPhoto.min.js"></script>
<link rel="stylesheet" href="plugins/prettyphoto/css/prettyPhoto.css" media="screen" />	
<!-- Demo JavaScript Files -->
<script type="text/javascript" src="js/demo/demo.gallery.js"></script>

<link href="plugins/jalerts/jquery.alerts.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="plugins/jalerts/jquery.alerts.js"></script>

<script type="text/javascript">
   $(function(){
	   $("a[href].delete_img").click(function(e){
		   var valor = $(this).attr('id');
		   var vec=valor.split('|');
		   var id_slider=vec[0];
		   var id_home=vec[1];
		   var name_file=vec[2];
		   var tipo = vec[3];
		   var id_ef = vec[4];
		  
		   jConfirm("¿Esta seguro de eliminar la imagen?", "Eliminar registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_slider='+id_slider+'&id_home='+id_home+'&name_file='+name_file+'&id_ef='+id_ef+'&opcion=inicio&tipo='+tipo;
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
										jAlert("La imagen no pudeo eliminarse intente nuevamente", "Mensaje");
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
								sh.id_ef = ef.id_ef and sh.producto='H');";
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
								sh.id_ef = ef.id_ef and sh.producto='H')
							and ef.id_ef = '".$id_ef_sesion."';";
	}
	
	if($resef = $conexion->query($selectEf,MYSQLI_STORE_RESULT)){
			$num_reg_ef = $resef->num_rows;
			if($num_reg_ef>0){
				while($regief = $resef->fetch_array(MYSQLI_ASSOC)){ 			  
			
					$selectImg="select
								  sh.id_home,
								  sh.id_ef,
								  sh.producto,
								  sl.id_slider,
								  sl.imagen,
								  sl.tipo 
								from
								  s_sgc_home as sh
								  inner join s_sgc_slider as sl on (sl.id_home=sh.id_home)
								where
								  sh.id_ef='".$regief['id_ef']."' and sh.producto='H' and sl.tipo='SL';";
					if($sqlimg = $conexion->query($selectImg,MYSQLI_STORE_RESULT)){
							$num_regi = $sqlimg->num_rows;			   
							echo'<div class="da-panel">
									<div class="da-panel-header">
										<span class="da-panel-title">
											<img src="images/icons/color/layout.png" alt="" />
											<b>'.$regief['nombre'].'</b> - <span lang="es">Listado de Imagenes</span>
										</span>
									</div>
									<div class="da-panel-content with-padding">';
									if($num_regi>0){
										echo'<div class="da-gallery photoSwipe">
												<ul>';
													while($regimg = $sqlimg->fetch_array(MYSQLI_ASSOC)){
														echo'<li>
																<a href="../images/'.$regimg['imagen'].'" class="touchTouch"> 
																   <img src="../images/'.$regimg['imagen'].'" alt="" width="150" height="150"/>
																</a>
																<span class="da-gallery-hover">
																	<ul>
																		<li class="da-gallery-update"><a href="?l=slideshow&id_slider='.base64_encode($regimg['id_slider']).'&editar=v&var='.$_GET['var'].'&idhome='.base64_encode($regimg['id_home']).'&tipo='.base64_encode($regimg['tipo']).'&id_ef='.base64_encode($regief['id_ef']).'">Update</a></li>
																		<li class="da-gallery-delete">
																		  <a href="#" id="'.$regimg['id_slider'].'|'.$regimg['id_home'].'|'.$regimg['imagen'].'|'.$regimg['tipo'].'|'.$regief['id_ef'].'" class="delete_img">Delete</a>
																		</li>
																	</ul>
																</span>
															</li>';
													}
													$sqlimg->free();
										   echo'</ul>
											</div>';
									}else{
									  echo'<div class="da-message info">
											   <span lang="es">No existe registros alguno, ingrese nuevos registros</span>
										   </div>';
									}
							   echo'</div>
								 </div>';
					}else{
						echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
							  <span lang='es'>Error en la consulta: ".$conexion->errno .": ".$conexion->error
							."</div>";
					}
				}
				$resef->free();
			}else{
				echo'<div class="da-message warning">
						<span lang="es">No existe ningun registro, probablemente se debe a</span>:
						<ul>
						  <li lang="es">No existe una cabecera creada para la nueva Entidad Financiera, consulte con su administrador</li>
						  <li lang="es">Verificar que la Entindad Financiera este activa, consulte con su administrador</li>
						</ul>
					 </div>';
			}
	}else{
		echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
	      <span lang='es'><span lang='es'>Error en la consulta</span>: ".$conexion->errno." : ".$conexion->error
		."</div>";
	}
}

//FUNCION QUE NOS PERMITE ACTUALIZAQR UNA IMAGEN
function editar_imagen($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
	
	$errArr['imagen'] = '';
	$errFlag = false;
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
        
		$idslider = base64_decode($_GET['id_slider']);
		$idhome = base64_decode($_GET['idhome']);
		$tipo = base64_decode($_GET['tipo']);
		$id_ef = base64_decode($_GET['id_ef']);
		
		if($errFlag){
			
		}else{
			//IMAGEN
			$validacion = validarImagen('txtImagen', 5242880, '5 Mb', '../images', false);
			if($validacion['flag']) {
				//SI VALIDO CORRECTAMENTE EL ARCHIVO
				$imagenServidor = $validacion['archivo'];
			} else {
				$errArr['imagen'] = $validacion['mensaje'];
				$errFlag = true;
			}
		}
		//VEMOS SI TODO SE VALIDO BIEN
		if($errFlag) {
			//HUBIERON ERRORES, MOSTRAMOS EL FORM CON LOS ERRORES
			mostrar_editar_foto_slide($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
		} else {
			   //SEGURIDAD
			   $especiales = array("\r\n", "\n", "\r", "\t");
			   $reemplazos = array("", "", "", "");
			   $tituloIni = str_replace($especiales, $reemplazos, $_POST['descripcion']);
			   $tituloFin = $conexion->real_escape_string(stripslashes($tituloIni));
			   
			   //echo $tituloFin; 
			   //CARGAMOS LOS DATOS A LA BASE DE DATOS
			   $update = "UPDATE s_sgc_slider as ss
			              INNER JOIN s_sgc_home as sh on (sh.id_home=ss.id_home)
						  SET"; 
			   if($imagenServidor!=''){
			      if(file_exists('../images/'.$_POST['auximage']))
                  {borra_archivo('../images/'.$_POST['auximage']);}
				  				  
				  $update.=" ss.imagen='".$imagenServidor."', ";
			   }else{
			      $update.=" ss.imagen='".$_POST['auximage']."', ";
			   }
			   $update.="ss.descripcion='".$tituloFin."' WHERE ss.id_slider='".$idslider."' and ss.id_home='".$idhome."' and sh.id_ef='".$id_ef."' and ss.tipo='".$tipo."';";
	           //echo $update;		
			   
			   if($conexion->query($update) === TRUE){
				   $mensaje="Se actualizo correctamente los datos del formulario";
				   header('Location: index.php?l=slideshow&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
				   exit;
			   } else{
				   $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno . ": " .$conexion->error;
				   header('Location: index.php?l=slideshow&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
				   exit;
			   } 
			
		}

	} else {
		//MUESTRO EL FORM PARA EDITAR UNA FOTO
		mostrar_editar_foto_slide($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
	}
}

//FUNCION PARA VISUALIZAR EL FORMULARIO
function mostrar_editar_foto_slide($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr){
?>
<script type="text/javascript" src="plugins/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: "textarea#descripcion",
    theme: "modern",
    width: 480,
    height: 250,
    plugins: [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
         "save table contextmenu directionality template paste textcolor"
   ],
   content_css: "css/content.css",
   toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons", 
   style_formats: [
        {title: 'Bold text', inline: 'b'},
        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
        {title: 'Example 1', inline: 'span', classes: 'example1'},
        {title: 'Example 2', inline: 'span', classes: 'example2'},
        {title: 'Table styles'},
        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
    ]
 }); 
</script>
<script type="text/javascript">
      $(function(){
		  $('#btnCancelar').click(function(e){
			   var variable = $('#var').prop('value');
			   $(location).attr('href', 'index.php?l=slideshow&var='+variable);  
		  });
	  });
   </script>
<?php
  $idslider = base64_decode($_GET['id_slider']);
  $idhome = base64_decode($_GET['idhome']);
  $tipo = base64_decode($_GET['tipo']);
  $id_ef = base64_decode($_GET['id_ef']);
  
  $selectImg="select
				sh.id_home,
				sh.id_ef,
				sh.producto,
				sl.id_slider,
				sl.imagen,
				sl.tipo,
                ef.nombre,
				sl.descripcion 
			  from
				s_sgc_home as sh
				inner join s_sgc_slider as sl on (sl.id_home=sh.id_home)
				inner join s_entidad_financiera as ef on (ef.id_ef=sh.id_ef)
			  where
				sh.id_ef='".$id_ef."' and sh.producto='H' and sl.tipo='".$tipo."' and sl.id_slider='".$idslider."' and sh.id_home='".$idhome."';"; 				  
  //echo $selectImg;
  if($resu = $conexion->query($selectImg,MYSQLI_STORE_RESULT)){				  
		  $regImg = $resu->fetch_array(MYSQLI_ASSOC);
		  $resu->free();				  
		  echo'<div class="grid_5">
				  <div class="da-panel">
					  <div class="da-panel-header">
						  <span class="da-panel-title">
							  <img src="images/icons/black/16/pencil.png" alt=""/>
							  <span lang="es">Actualizar imagen</span>
						  </span>
					  </div>
					  <div class="da-panel-content">
						  <form class="da-form" action="" method="POST" enctype="multipart/form-data" name="formUpdateImage" id="formUpdateImage">
							  
							  <div class="da-form-row">
								   <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
								   <div class="da-form-item small">
									   '.$regImg['nombre'].'
								   </div>	 
							  </div>	
							  <div class="da-form-row" style="text-align:center;">';
								  if(file_exists('../images/'.$regImg['imagen'])){
									   echo'<img src="../images/'.$regImg['imagen'].'" width="375" height="150"/>';
								  }else{
									   echo'El archivo fisico no existe';
								  }
						 echo'</div>
							  <div class="da-form-row">
								  <label  style="text-align:right;"><b><span lang="es">Archivo</span></b></label>
								  
								  <div class="da-form-item large">
									  <span lang="es">El tama&ntilde;o máximo del archivo es de 5Mb. Se recomienda que la imagen tenga un ancho de 1000px por un alto de 400px., el formato del archivo a subir debe ser [jpg].</span> 
									  <input type="file" class="da-custom-file" id="update" name="txtImagen"/>
									  <span class="errorMessage">'.$errArr['imagen'].'</span>
									  <span><b><span lang="es">Archivo actual:</span></b> '.$regImg['imagen'].'</span>
									   <input type="hidden" name="auximage" value="'.$regImg['imagen'].'"/>
								  </div>
							  </div>
							
							  <div class="da-form-row">
								  <label style="text-align:right;"><b><span lang="es">Texto</span></b></label>
								  <div class="da-form-item large">
									<textarea name="descripcion" id="descripcion">'.$regImg['descripcion'].'</textarea>
								  </div>
							  </div>
							  
							  <div class="da-button-row">
								  <input type="button" value="Cancelar" class="da-button gray left" name="btnCancelar" id="btnCancelar"/>  
								  <input type="submit" value="Guardar" class="da-button green" lang="es"/>
								  <input type="hidden" name="accionGuardar" value="ok"/> 
							  </div>
						  </form>
					  </div>
				  </div>
			  </div>';
  }else{
	  echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
		  <span lang='es'>Error en la consulta:</span> ".$conexion->errno.": ".$conexion->error
		."</div>";
  }
}


//AGREGAR NUEVA IMAGEN
function agregar_nueva_imagen($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
	$errArr['imagen'] = '';
	$errArr['errorentidad'] = '';
	$errFlag = false;
    
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
        		
		//VALIDAMOS ENTIDAD FINANCIERA
		$num=validar_select($_POST['idefin']);
		if($num==1){
			$errArr['errorentidad'] = "seleccione opcion";
			$errFlag = true;
		}elseif($num==2){
		  //seleccionado
		}
				
		if($errFlag){
			
		}else{
		    //IMAGEN
			$validacion = validarImagen('txtImagen', 5242880, '5 Mb', '../images', true);
			if($validacion['flag']) {
				//SI VALIDO CORRECTAMENTE EL ARCHIVO
				$imagenServidor = $validacion['archivo'];
			} else {
				$errArr['imagen'] = $validacion['mensaje'];
				$errFlag = true;
			}	
		}

		//VEMOS SI TODO SE VALIDO BIEN
		if($errFlag) {
			//HUBIERON ERRORES, MOSTRAMOS EL FORM CON LOS ERRORES
			mostrar_agregar_foto_slide($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
		} else {
			   //SEGURIDAD
			   $especiales = array("\r\n", "\n", "\r", "\t");
			   $reemplazos = array("", "", "", "");
			   $tituloIni = str_replace($especiales, $reemplazos, $_POST['descripcion']);
			   $tituloFin = $conexion->real_escape_string(stripslashes($tituloIni));
			   	
			   $idefin = $conexion->real_escape_string($_POST['idefin']);
			   
			   $selectIdh="select
							  id_home,
							  id_ef	
							from
							  s_sgc_home
							where
							  producto='H' and id_ef='".$_POST['idefin']."'
							limit
							  0,1;";
			   $residh = $conexion->query($selectIdh,MYSQLI_STORE_RESULT);
			   $rows = $residh->num_rows;
			   if($rows>0){
				    $regidh = $residh->fetch_array(MYSQLI_ASSOC);
				    $residh->free();
					//GENERAMOS EL ID CODIFICADO UNICO	
				    $id_new_slider = generar_id_codificado('@S#1$2013');		   			
				    //METEMOS LOS DATOS A LA BASE DE DATOS
				    $insert ="INSERT INTO s_sgc_slider(id_slider, imagen, descripcion, id_home, tipo) "
							."VALUES('".$id_new_slider."', '".$imagenServidor."', '".$tituloFin."', '".$regidh['id_home']."', 'SL')";
				    //echo $insert;
									    
					if($conexion->query($insert) === TRUE){
					   $mensaje="Se actualizo correctamente los datos del formulario";
					   header('Location: index.php?l=slideshow&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
					   exit;
					} else{
					   $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
					   header('Location: index.php?l=slideshow&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
					   exit;
					}
			   }else{
				     $mensaje="No se puede agregar registros, debe tener agregado una nueva cabecera con la nueva entidad financiera, consulte con su administrador";
					 header('Location: index.php?l=slideshow&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
					 exit;
			   }
			
		}

	} else {
		//MUESTRO EL FORM PARA EDITAR UNA FOTO
		mostrar_agregar_foto_slide($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
	}
}

//FUNCION QUE PERMITE AGREGAR IMAGEN
function mostrar_agregar_foto_slide($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr){
	?>
<script type="text/javascript" src="plugins/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: "textarea#descripcion",
    theme: "modern",
    width: 480,
    height: 250,
    plugins: [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
         "save table contextmenu directionality template paste textcolor"
   ],
   content_css: "css/content.css",
   toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons", 
   style_formats: [
        {title: 'Bold text', inline: 'b'},
        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
        {title: 'Example 1', inline: 'span', classes: 'example1'},
        {title: 'Example 2', inline: 'span', classes: 'example2'},
        {title: 'Table styles'},
        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
    ]
 }); 
</script>
<?php
   //VARIABLES DE INICIO
  if(isset($_POST['descripcion'])) $descripcion = $_POST['descripcion']; else $descripcion = '';
  if(isset($_POST['idefin'])) $idefin = $_POST['idefin']; else $idefin = '';
  //SACAMOS LAS ENTIDADES FINANCIERAS
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
						and ef.id_ef = '".$id_ef_sesion."';";	 						
  }
  if($res1 = $conexion->query($select1,MYSQLI_STORE_RESULT)){
		 if($res1->num_rows>0){		   			   			  
			  echo'<div class="grid_5">
					  <div class="da-panel">
						  <div class="da-panel-header">
							  <span class="da-panel-title">
								  <img src="images/icons/black/16/pencil.png" alt=""/>
								  <span lang="es">Agregar imagen</span>
							  </span>
						  </div>
						  <div class="da-panel-content">
							  <form class="da-form" action="" method="POST" enctype="multipart/form-data" name="formUpdateImage" id="formUpdateImage">
								  <div class="da-form-row">
									   <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
									   <div class="da-form-item small">';
									   
										  echo'<select id="idefin" name="idefin" style="width:200px;">';
												  echo'<option value="" lang="es">seleccione...</option>';
												  while($regi1 = $res1->fetch_array(MYSQLI_ASSOC)){
													  if($idefin==$regi1['id_ef']){ 
														  echo'<option value="'.$regi1['id_ef'].'" selected>'.$regi1['nombre'].'</option>';  
													  }else{
														  echo'<option value="'.$regi1['id_ef'].'">'.$regi1['nombre'].'</option>';  
													  }
												  }
												  $res1->free();
										  echo'</select>
											   <span class="errorMessage" id="errorentidad" lang="es">'.$errArr['errorentidad'].'</span>';
									   
								  echo'</div>	 
								  </div>	
								  <div class="da-form-row">
									  <label style="text-align:right;"><b><span lang="es">Archivo</span></b></label>
									  <div class="da-form-item large">
										  <span lang="es">El tamaño máximo del archivo es de 5Mb. Se recomienda que la imagen tenga un ancho de 1000px por un alto de 400px., el formato del archivo a subir debe ser [jpg].</span> 
										  <input type="file" class="da-custom-file" id="update" name="txtImagen"/>
										  <span class="errorMessage" lang="es">'.$errArr['imagen'].'</span>
									  </div>
								  </div>
								
								  <div class="da-form-row">
									  <label style="text-align:right;"><b><span lang="es">Texto</span></b></label>
									  <div class="da-form-item large">
										<textarea name="descripcion" id="descripcion">'.$descripcion.'</textarea>
									  </div>
								  </div>
								  
								  <div class="da-button-row">  
									  <input type="submit" value="Guardar" class="da-button green" lang="es"/>
									  <input type="hidden" name="accionGuardar" value="ok"/> 
								  </div>
							  </form>
						  </div>
					  </div>
				  </div>';
		 }else{
			echo'<div class="da-message warning">
					<span lang="es">No existe ningun registro, probablemente se debe a</span>:
					<ul>
					  <li lang="es">No existe una cabecera creada para la nueva Entidad Financiera, consulte con su administrador</li>
					  <li lang="es">Verificar que la Entindad Financiera este activa, consulte con su administrador</li>
					</ul>
				 </div>'; 
		 }		  
  }else{
	 echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
		  Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error
		."</div>"; 
  }
}

?>    