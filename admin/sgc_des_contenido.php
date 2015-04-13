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
			header('Location: index.php?l=des_contenido&var=de');
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
  $res = $conexion->query($selectUsu,MYSQLI_STORE_RESULT);
  $reg = $res->fetch_array(MYSQLI_ASSOC);	
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
							if(isset($_GET['crear_contenido'])) {
						
								agregar_nuevo_contenido($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
								
							} else {
								//VEMOS SI NOS PASAN UN ID
								if(isset($_GET['idhome'])) {
						
									if(isset($_GET['editar'])) {
										//SI NO ME PASAN 'CPASS' NI 'ELIMINAR', MUESTRO EL FORM PARA EDITAR USUARIO
										editar_contenido($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
									} 
								}elseif(isset($_GET['cabecera'])){ 
								        listar_cabecera_home($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
							    }elseif(isset($_GET['editarcabecera'])){
							    
								        editar_cabecera($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								}else {
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE USUARIOS EXISTENTES
									mostrar_lista_contenido_desgravamen($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
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
function mostrar_lista_contenido_desgravamen($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>
<link type="text/css" rel="stylesheet" href="plugins/jalerts/jquery.alerts.css"/>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="plugins/jalerts/jquery.alerts.js"></script>
<script type="text/javascript">
   $(function(){
	   $("a[href].eliminar").click(function(e){
		   var valor = $(this).attr('id');
		   var vec = valor.split('|');
		   var idslider = vec[0];
		   var imagen = vec[1];
		   var idhome = vec[2];
		   var tipo = vec[3];
		  
		   jConfirm("¿Esta seguro de eliminar el registro contenido?", "Eliminar registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_slider='+idslider+'&name_file='+imagen+'&id_home='+idhome+'&tipo='+tipo+'&opcion=inicio';
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
										jAlert("El registro no pudo eliminarse, intente nuevamente", "Mensaje");
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
	//if(isset($_GET["contenido"])){$contenido='v';}else{$contenido='';}
	
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
		 setTimeout( "$(location).attr('href', 'index.php?l=des_contenido&var=<?php echo $var;?>');",5000 );
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
								sh.id_ef = ef.id_ef and sh.producto='DE');";
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
								sh.id_ef = ef.id_ef and sh.producto='DE')
							  and ef.id_ef = '".$id_ef_sesion."';";
	}
	if($resef = $conexion->query($selectEf,MYSQLI_STORE_RESULT)){
		if($resef->num_rows>0){
			/*echo'<div class="da-panel collapsible">
					  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
						  <ul class="action_user">
							  <li style="margin-right:6px;">
								 <a href="?l=des_contenido&crear_contenido=v&var='.$_GET['var'].'" class="da-tooltip-s various fancybox.ajax" title="Añadir nuevo registro">
								 <img src="images/add_new.png" width="32" height="32"></a>
							  </li>
						  </ul>
					  </div>
				   </div>';*/		  
			while($regief = $resef->fetch_array(MYSQLI_ASSOC)){
				$selectCont="select
							  id_home,
							  producto_nombre,
							  html,
							  imagen,
							  id_ef
							from
							  s_sgc_home
							where
							  id_ef='".$regief['id_ef']."' and producto='DE';";
				$res = $conexion->query($selectCont,MYSQLI_STORE_RESULT);			   
				echo'
				<div class="da-panel collapsible">
					<div class="da-panel-header">
						<span class="da-panel-title">
							<img src="images/icons/black/16/list.png" alt="" />
							<b>'.$regief['nombre'].'</b> - <span lang="es">Contenido Desgravamen</span>
						</span>
					</div>
					<div class="da-panel-content">
						<table class="da-table">
							<thead>
								<tr>
								   <th><b><span lang="es">Contenido</span></b></th>
								   <th style="width:200px; text-align:center"><b><span lang="es">Imagen</span></b></th>
								   <th style="width:100px;"><b><span lang="es">Producto</span></b></th>
								   <th></th>
								</tr>
							</thead>
							<tbody>';
							  $num = $res->num_rows;
							  if($num>0){
									while($regi = $res->fetch_array(MYSQLI_ASSOC)){
										echo'<tr>
												<td>'.substr_replace($regi['html'], '...',600).'</td>
												<td style="text-align:center;">';
												  if($regi['imagen']!=''){
													   if(file_exists('../images/'.$regi['imagen'])){  
														  echo'<img src="../images/'.$regi['imagen'].'"/>';
													   }else{
														  echo'no existe el archivo fisico';   
													   }
												   }else{
													  echo'no existe el nombre del archivo en la base de datos';   
												   }
										   echo'</td>
												<td>'.$regi['producto_nombre'].'</td>
												<td class="da-icon-column">
												   <ul class="action_user">
													  <li style="margin-right:5px;"><a href="?l=des_contenido&editar=v&var='.$_GET['var'].'&idhome='.base64_encode($regi['id_home']).'&id_ef='.base64_encode($regief['id_ef']).'" class="edit da-tooltip-s" title="<span lang=\'es\'>Editar</span>"></a></li>';
											  echo'</ul>	
												</td>
											</tr>';
									}
									$res->free();			
							  }else{
								 echo'<tr><td colspan="4">
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
		}else{
			echo'<div class="da-message warning">
					 <span lang="es">No existe ningun registro, probablemente se debe a</span>:
					 <ul>
						<li lang="es">La Entidad Financiera no tiene asignado el producto Desgravamen</li>
						<li lang="es">La Entidad Financiera no esta activado</li>
						<li lang="es">La Entidad Financiera no esta creada</li>
					  </ul>
				</div>'; 
		}
	}else{
		echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
		  Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error
		   ."</div>";
	}
}

//FUNCION QUE NOS PERMITE ACTUALIZAQR UNA IMAGEN
function editar_contenido($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
	
	$errArr['imagen'] = '';
	$errArr['errorcontenido'] = '';
	$errFlag = false;
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
		
		$idhome = base64_decode($_GET['idhome']);
		$id_ef = base64_decode($_GET['id_ef']);
        //VALIDAR CONTENIDO
		if(validar_texto($_POST['contenido'])){
			$errArr['errorcontenido'] = "ingrese un texto";
			$errFlag = true;
		}else{
			 //SI SE SELECCIONO POLIZA GUARDAMOS
		}
		if($errFlag){
			
		}else{
			//IMAGEN
			$validacion = validarImagen('txtImagen', 1048576, '1 Mb', '../images', false);
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
			mostrar_editar_contenidohome($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
		} else {
			   //SEGURIDAD
			   $especiales = array("\r\n", "\n", "\r", "\t");
			   $reemplazos = array("", "", "", "");
			   $tituloIni = str_replace($especiales, $reemplazos, $_POST['contenido']);
			   $contenido = $conexion->real_escape_string(stripslashes($tituloIni));
			  			  
			   $update = "UPDATE s_sgc_home SET"; 
			   if($imagenServidor!=''){
			      if(file_exists('../images/'.$_POST['auximage']))
                  {borra_archivo('../images/'.$_POST['auximage']);}
				  				  
				  $update.=" imagen='".$imagenServidor."', ";
			   }else{
			      $update.=" imagen='".$_POST['auximage']."', ";
			   }
			   $update.="html='".$contenido."' WHERE id_home = '".$idhome."' and id_ef ='".$id_ef."' LIMIT 1;";
	          
			

			if($conexion->query($update)===TRUE){
				if($imagenServidor!=''){
					$vec = getimagesize('../images/'.$imagenServidor);
					$ancho = $vec[0];
					$alto = $vec[1];
					if($ancho>350){
						// *** 1) Initialise / load image
						$resizeObj = new resize('../images/'.$imagenServidor);
						// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
						$resizeObj -> resizeImage(350, $alto, 'landscape');
						// *** 3) Save image
						$resizeObj -> saveImage('../images/'.$imagenServidor, 100);
					}
			     }
			   $mensaje="Se actualizo correctamente los datos del formulario";
			   header('Location: index.php?l=des_contenido&var='.$_GET['var'].'&op=1&msg='.$mensaje);
			   exit;
			} else{
			   $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			   header('Location: index.php?l=des_contenido&var='.$_GET['var'].'&op=2&msg='.$mensaje);
			   exit;
			}  
			
		}

	} else {
		//MUESTRO EL FORM PARA EDITAR UNA FOTO
		mostrar_editar_contenidohome($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
	}
}

//FUNCION PARA VISUALIZAR EL FORMULARIO
function mostrar_editar_contenidohome($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr){
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
  $idhome = base64_decode($_GET['idhome']);
  $id_ef = base64_decode($_GET['id_ef']);
   
  $selectImg="select
				  sh.id_home,
				  sh.producto,
				  sh.html,
				  sh.imagen,
				  sh.id_ef,
				  sef.nombre
				from
				  s_sgc_home as sh
				  inner join s_entidad_financiera as sef on (sef.id_ef=sh.id_ef)
				where
				  sh.id_home='".$idhome."' and sh.id_ef='".$id_ef."' and sh.producto='DE' and sef.activado=1;";
  //echo $selectImg;
  $resimg = $conexion->query($selectImg,MYSQLI_STORE_RESULT);				  
  $regImg = $resimg->fetch_array(MYSQLI_ASSOC);
  $resimg->free();
  if(!empty($regImg['imagen'])){
	if(file_exists('../images/'.$regImg['imagen'])){  	
			$imagen = getimagesize('../images/'.$regImg['imagen']);    //Sacamos la información
			$ancho = $imagen[0];              //Ancho
			$alto = $imagen[1];
	}
  }
  if(isset($_POST['contenido'])) $contenido = $_POST['contenido']; else $contenido = $regImg['html']; 				  
 echo'
	<div class="da-panel collapsible">
		<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			<ul class="action_user">
				<li style="margin-right:6px;">
				   <a href="?l=des_contenido&var='.$_GET['var'].'" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
				   <img src="images/retornar.png" width="32" height="32"></a>
				</li>
			</ul>
		</div>
	</div>';
  echo'<div class="grid_5">
		  <div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt=""/>
					  <span lang="es">Actualizar Contenido Desgravamen</span>
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
						  if(!empty($regImg['imagen'])){	  
							  if(file_exists('../images/'.$regImg['imagen'])){
								  echo'<img src="../images/'.$regImg['imagen'].'"/>';
							  }else{
								  echo'<span lang="es">No existe el archivo físico</span>';  
							  }
						  }else{
							 echo'No existe el nombre en la base de datos'; 
						  }
				 echo'</div>
					  <div class="da-form-row">
						  <label style="text-align:right;"><b><span lang="es">Archivo</span></b></label>
						  
						  <div class="da-form-item large">
							  <span lang="es">El tamaño máximo del archivo es de 1Mb. Se recomienda que la imagen tenga un alto de 100px., el formato del archivo a subir debe ser [jpg]</span> 
							  <input type="file" class="da-custom-file" id="update" name="txtImagen"/>
							  <span class="errorMessage">'.$errArr['imagen'].'</span>
							  <span><b>Archivo actual:</b> '.$regImg['imagen'].'</span>
							  <input type="hidden" name="auximage" value="'.$regImg['imagen'].'"/>
						  </div>
					  </div>
					
					  <div class="da-form-row">
						  <label style="text-align:right;"><b><span lang="es">Contenido</span></b></label>
						  <div class="da-form-item large">
							<textarea name="contenido" id="descripcion">'.$contenido.'</textarea>
							<span class="errorMessage">'.$errArr['errorcontenido'].'</span>
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
}


//AGREGAR NUEVA IMAGEN
function agregar_nuevo_contenido($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
		
	$errArr['imagen'] = '';
	$errArr['errorcontenido'] = '';
	$errArr['errorentidad']='';
	$errFlag = false;
    
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
        //VALIDAR TIPO PRODUCTO
		if(validar_texto($_POST['descripcion'])){
			$errArr['errorcontenido'] = "ingrese un texto";
			$errFlag = true;
		}else{
			 //SI SE SELECCIONO POLIZA GUARDAMOS
		}
		//VALIDAR SELECT
		$num=validar_select($_POST['idefin']);
		if($num==1){
			$errArr['errorentidad'] = "seleccione entidad financiera";
			$errFlag = true;
	    }elseif($num==2){
			
		}
		
		if($errFlag){
			
		}else{
		    //IMAGEN
			$validacion = validarImagen('txtImagen', 1048576, '1 Mb', '../images', true);
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
			mostrar_agregar_contenido($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
		} else {
			   //SEGURIDAD
			   $especiales = array("\r\n", "\n", "\r", "\t");
			   $reemplazos = array("", "", "", "");
			   $tituloIni = str_replace($especiales, $reemplazos, $_POST['descripcion']);
			   $contenido = $conexion->real_escape_string(stripslashes($tituloIni));
			   	
			   $idhome = $conexion->real_escape_string($_POST['txtIdhome']);
			   $id_ef = $conexion->real_escape_string($_POST['idefin']);
			   //GENERAMOS UN ID CODIFICADO UNICO			   			
			   $id_new_home = generar_id_codificado('@S#1$2013');
			   //METEMOS LOS DATOS A LA BASE DE DATOS
			   $insert ="INSERT INTO s_sgc_home(id_home, id_ef, producto, html, imagen, id_usuario) "
						."VALUES('".$id_new_home."', '".$id_ef."', 'DE', '".$contenido."' ,'".$imagenServidor."', '".$id_usuario_sesion."')";
			   
			

			if($conexion->query($insert)===TRUE){
			   //REDIMENCIONAR IMAGEN
				$vec = getimagesize('../images/'.$imagenServidor);
				$ancho = $vec[0];
				$alto = $vec[1];
				// *** 1) Initialise / load image
				$resizeObj = new resize('../images/'.$imagenServidor);
				// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
				$resizeObj -> resizeImage(350, $alto, 'landscape');
				// *** 3) Save image
				$resizeObj -> saveImage('../images/'.$imagenServidor, 100);
			   
			    $mensaje="Se actualizo correctamente los datos del formulario";
			    header('Location: index.php?l=des_contenido&var='.$_GET['var'].'&op=1&msg='.$mensaje);
			    exit;
			} else{
			    $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			    header('Location: index.php?l=des_contenido&var='.$_GET['var'].'&op=2&msg='.$mensaje);
			    exit;
			}  
			
		}

	} else {
		//MUESTRO EL FORM PARA EDITAR UNA FOTO
		mostrar_agregar_contenido($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
	}
}

//FUNCION QUE PERMITE AGREGAR IMAGEN
function mostrar_agregar_contenido($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr){
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
  //SACAMOS LAS ENTIDADES EXISTENTES
  if($tipo_sesion=='ROOT'){
		$select1="select 
					  ef.id_ef, ef.nombre, ef.logo, ef.activado
				  from
					  s_entidad_financiera as ef
				  where
					  ef.activado = 1
						  and not exists( select 
							  sh.id_ef
						  from
							  s_sgc_home as sh
						  where
							  sh.id_ef = ef.id_ef and sh.producto='DE');";
  }else{
	  $select1="select 
					ef.id_ef, ef.nombre, ef.logo, ef.activado
				from
					s_entidad_financiera as ef
				where
					ef.activado = 1
						and not exists( select 
							sh.id_ef
						from
							s_sgc_home as sh
						where
							sh.id_ef = ef.id_ef and sh.producto='DE')
					and ef.id_ef='".$id_ef_sesion."';";
  }
   $res1 = $conexion->query($select1,MYSQLI_STORE_RESULT);
   $num_reg = $res1->num_rows;
   echo'
	<div class="da-panel collapsible">
		<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			<ul class="action_user">
				<li style="margin-right:6px;">
				   <a href="?l=des_contenido&var='.$_GET['var'].'" class="da-tooltip-s" title="Volver">
				   <img src="images/retornar.png" width="32" height="32"></a>
				</li>
			</ul>
		</div>
	</div>';  			  
  echo'<div class="grid_5">
		  <div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt=""/>
					  Agregar Contenido
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" enctype="multipart/form-data" name="formUpdateImage" id="formUpdateImage"> 
					  <div class="da-form-row">
						   <label style="text-align:right;"><b>Entidad Financiera</b></label>
						   <div class="da-form-item small">
							   <select id="idefin" name="idefin" class="required" style="width:160px;">';
								  echo'<option value="">seleccione...</option>';
								  while($regi1 = $res1->fetch_array(MYSQLI_ASSOC)){
									  if($idefin==$regi1['id_ef']){ 
									   echo'<option value="'.$regi1['id_ef'].'" selected>'.$regi1['nombre'].'</option>';  
									  }else{
										  echo'<option value="'.$regi1['id_ef'].'">'.$regi1['nombre'].'</option>';  
									  }
								  }
								  $res1->free();
						  echo'</select>
							   <span class="errorMessage" id="errorentidad">'.$errArr['errorentidad'].'</span>
						   </div>	 
					  </div>		
					  <div class="da-form-row">
						  <label style="text-align:right;"><b>Imagen</b></label>
						  <div class="da-form-item large">
							  <span>El tama&ntilde;o m&aacute;ximo del archivo es de 1Mb. Se recomienda que la imagen tenga un ancho de 350px.,&nbsp;el formato del archivo a subir debe ser [jpg].</span> 
							  <input type="file" class="da-custom-file" id="update" name="txtImagen"/>
							  <span class="errorMessage">'.$errArr['imagen'].'</span>
						  </div>
					  </div>
					  <div class="da-form-row">
						  <label style="text-align:right;"><b>Contenido</b></label>
						  <div class="da-form-item large">
							<textarea name="descripcion" id="descripcion">'.$descripcion.'</textarea>
							<span class="errorMessage">'.$errArr['errorcontenido'].'</span>
						  </div>
					  </div>
					  <div class="da-button-row">  
						  <input type="hidden" name="accionGuardar" value="ok"/> ';
						  if($num_reg>0){ 
						    echo'<input type="submit" value="Guardar" class="da-button green"/>';
						  }else{
							echo'<input type="submit" value="Guardar" class="da-button green" disabled/>';  
						  }
				 echo'</div>
				  </form>
			  </div>
		  </div>
	  </div>';
}

//FUNCION PARA LISTAR CABECERA
function listar_cabecera_home($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
?>
<script type="text/javascript" src="plugins/ambience/jquery.ambiance.js"></script>
<script type="text/javascript">
  <?php 
    $op = $_GET["op"];
    $msg = $_GET["msg"];
	$var = $_GET["var"];
	//if(isset($_GET["contenido"])){$contenido='v';}else{$contenido='';}
	
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
		 setTimeout( "$(location).attr('href', 'index.php?l=contenidohome&var=<?php echo $var;?>&cabecera=v');",5000 );
	<?php }?>
	 
  });
</script>
<?php	
	
	$selectCab="select
				  id_home,
                  cliente,
                  cliente_logo
				from
				  s_sgc_home
				where
				  producto='H'
				limit
				  0,1;";		  
	$rescab = $conexion->query($selectCab,MYSQLI_STORE_RESULT);  			  
	
	echo'
<div class="da-panel collapsible" style="width:60%;">
	<div class="da-panel-header">
		<span class="da-panel-title">
			<img src="images/icons/black/16/list.png" alt="" />
			Lista Cabecera Home
		</span>
	</div>
	<div class="da-panel-content">
		<table class="da-table">
			<thead>
				<tr>
				   <th><b>Cliente</b></th>
				   <th><b>Cliente logo</b></th>
				   <th></th>
				</tr>
			</thead>
			<tbody>';
			  $num = $rescab->num_rows;
			  if($num>0){
					while($regicab = $rescab->fetch_array(MYSQLI_ASSOC)){
						echo'<tr>
								<td>'.$regicab['cliente'].'</td>
								<td style="text-align:center;">';
								  if($regicab['cliente_logo']!=''){
									   if(file_exists('../images/'.$regicab['cliente_logo'])){  
									      echo'<img src="../images/'.$regicab['cliente_logo'].'"/>';
									   }else{
										  echo'no existe el archivo fisico';   
									   }
								   }else{
									  echo'no existe el nombre del archivo en la base de datos';   
								   }
						   echo'</td>
								<td class="da-icon-column">
								   <ul class="action_user">
									  <li style="margin-right:5px;"><a href="?l=contenidohome&editarcabecera=v&var='.$_GET['var'].'&idhome='.base64_encode($regicab['id_home']).'" class="edit da-tooltip-s" title="Editar"></a></li>';
							  echo'</ul>	
								</td>
							</tr>';
					}
					$rescab->free();			
			  }else{
			     echo'<tr><td colspan="3">
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


//FUNCION EDITAMOS LA CABECERA
function editar_cabecera($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
	$errArr['imagen'] = '';
	$errArr['errortitulo'] = '';
	$errFlag = false;
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
				
		$idhome = base64_decode($_GET['idhome']);
		//VALIDAR CONTENIDO
		$num=validar_texto2($_POST['txtCliente']);
		if($num==1){
			$errArr['errortitulo'] = "ingrese un texto";
			$errFlag = true;
		}elseif($num==2){
			 //corecto
		}elseif($num==3){
			$errArr['errortitulo'] = "ingrese solo letras";
			$errFlag = true;
		}
		if($errFlag){
			
		}else{
			//IMAGEN
			$validacion = validarImagen('txtImagen', 1048576, '1 Mb', '../images', false);
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
			mostrar_editar_cabecera($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
		} else {
			   //SEGURIDAD
			   $cliente = $conexion->real_escape_string($_POST['txtCliente']);
			  			  
			   $update = "UPDATE s_sgc_home SET cliente='".$cliente."',"; 
			   if($imagenServidor!=''){
			      if(file_exists('../images/'.$_POST['auximage']))
                  {borra_archivo('../images/'.$_POST['auximage']);}
				  	$vec = getimagesize('../images/'.$imagenServidor);
					$ancho = $vec[0];
					$alto = $vec[1];
					// *** 1) Initialise / load image
					$resizeObj = new resize('../images/'.$imagenServidor);
					// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
					$resizeObj -> resizeImage($ancho, 75, 'portrait');
					// *** 3) Save image
					$resizeObj -> saveImage('../images/'.$imagenServidor, 100);			  
				  $update.=" cliente_logo='".$imagenServidor."'";
			   }else{
			      $update.=" cliente_logo='".$_POST['auximage']."'";
			   }
			   $update.=" WHERE id_home = ".$idhome."  LIMIT 1;";
	           
			

			if($conexion->query($update)===TRUE){
				
			   $mensaje="Se actualizo correctamente los datos del formulario";
			   header('Location: index.php?l=contenidohome&var='.$_GET['var'].'&op=1&msg='.$mensaje.'&cabecera=v');
			   exit;
			} else{
			   $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			   header('Location: index.php?l=contenidohome&var='.$_GET['var'].'&op=2&msg='.$mensaje.'&cabecera=v');
			   exit;
			}  
			
		}

	} else {
		//MUESTRO EL FORM PARA EDITAR UNA FOTO
		mostrar_editar_cabecera($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
	}
}

//FUNCION MUESTRA FORMULARIO EDITAR CABECERA
function mostrar_editar_cabecera($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr){
?>
   <script type="text/javascript">
      $(function(){
		  $('#btnCancelar').click(function(e){
			   var variable = $('#var').prop('value');
			   $(location).attr('href', 'index.php?l=contenidohome&var='+variable+'&cabecera=v');  
		  });
	  });
   </script>
<?php	
	
	$idhome = base64_decode($_GET['idhome']);
	$selectCab="select
				  id_home,
                  cliente,
                  cliente_logo
				from
				  s_sgc_home
				where
				  producto='H' and id_home=".$idhome."
				limit
				  0,1;";
    $resicab = $conexion->query($selectCab,MYSQLI_STORE_RESULT);				  			  		  
	$regicab = $resicab->fetch_array(MYSQLI_ASSOC);
	$resicab->free();
	if(isset($_GET['txtCliente'])) $txtCliente = $_GET['txtCliente']; else $txtCliente=$regicab['cliente'];  
	echo'<div class="grid_5">
		  <div class="da-panel">
			  <div class="da-panel-header">
				  <span class="da-panel-title">
					  <img src="images/icons/black/16/pencil.png" alt=""/>
					  Actualizar cabecera
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" enctype="multipart/form-data" name="formUpdateImage" id="formUpdateImage">
					  					  
					  <div class="da-form-row" style="text-align:center;">';
					      if(file_exists('../images/'.$regicab['cliente_logo'])){
						     echo'<img src="../images/'.$regicab['cliente_logo'].'"/>';
						  }else{
							 echo'NO EXISTE EL ARCHIVO FISICO';   
						  }
				 echo'</div>
					  <div class="da-form-row">
						  <label>Logo</label>
						  
						  <div class="da-form-item large">
							  <span>El tama&ntilde;o m&aacute;ximo del archivo es de 1Mb. Se recomienda que la imagen tenga un alto de 100px.,&nbsp;el formato del archivo a subir debe ser [jpg].</span> 
							  <input type="file" class="da-custom-file" id="txtImagen" name="txtImagen"/>
							  <span class="errorMessage">'.$errArr['imagen'].'</span>
							  <span><b>Archivo actual:</b> '.$regicab['cliente_logo'].'</span>
							  <input type="hidden" name="auximage" value="'.$regicab['cliente_logo'].'"/>
						  </div>
					  </div>
					
					  <div class="da-form-row">
						  <label>Cliente</label>
						  <div class="da-form-item large">
							<input class="textbox" type="text" name="txtCliente" id="txtCliente" style="width: 250px;" value="'.$txtCliente.'" autocomoplete="off"/>
							<span class="errorMessage">'.$errArr['errortitulo'].'</span>
						  </div>
					  </div>
					  
					  <div class="da-button-row">  
						  <input type="button" value="Cancelar" class="da-button gray left" name="btnCancelar" id="btnCancelar"/>
						  <input type="submit" value="Guardar" class="da-button green"/>
						  <input type="hidden" name="accionGuardar" value="ok"/>
						  <input type="hidden" name="var" id="var" value="'.$_GET['var'].'"/> 
					  </div>
				  </form>
			  </div>
		  </div>
	  </div>';
}

?>    