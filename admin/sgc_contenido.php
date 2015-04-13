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
						
								agregar_nuevo_contenido($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
								
							} else {
								//VEMOS SI NOS PASAN UN ID
								if(isset($_GET['idslider'])) {
						
									if(isset($_GET['editar'])) {
										//SI NO ME PASAN 'CPASS' NI 'ELIMINAR', MUESTRO EL FORM PARA EDITAR USUARIO
										editar_contenido($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
									} 
								}elseif(isset($_GET['listcabecera'])){ 
								        listar_cabecera_home($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
							    }elseif(isset($_GET['editarcabecera'])){
							    
								        editar_cabecera($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								}elseif(isset($_GET['crear_cabecera'])){
								    agregar_nueva_cabecera($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
							    }elseif(isset($_GET['list_nosotros'])){
							        //LISTAMOS CONTENIDO NOSOTROS PARA CADA ENTIDAD FINANCIERA
								    mostrar_lista_contenido_nosotros($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
								}elseif(isset($_GET['editar_nosotros'])){
								    //EDITAMOS CONTENIDO NOSOTROS
									editar_contenido_nosotros($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
							    }elseif(isset($_GET['crear_nosotros'])){
								    //AGREGAR CONTENIDO NOSOTROS
									agregar_nuevo_contenido_nosotros($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
							    }elseif(isset($_GET['list_preg_frec'])){
								    //LISTAR CONTENIDO PREGUNTAS FRECUENTES PARA CADA PRODUCTO EXISTENTE
									//DE CADA ENTIDAD FINANCIERA
									mostrar_lista_contenido_preg_frec($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
							    }elseif(isset($_GET['editar_preg_frec'])){
								    //EDITAMOS CONTENIDO PREGUNTAS FRECUENTES DE CADA PRODUCTO
									editar_contenido_preg_frec($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
							    }else {
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE USUARIOS EXISTENTES
									mostrar_lista_contenido($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
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

//MOSTRAMOS LISTA CONTENIDO HOME
function mostrar_lista_contenido($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
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
		 $.ambiance({message: "<?php echo base64_decode($msg);?>", 
				title: "Notificacion",
				type: "<?php echo $valor?>",
				timeout: 5
				});
		 //location.load("sgc.php?l=usuarios&idhome=1");
		 //$(location).attr('href', 'sgc.php?l=crearusuario&idhome=1');		
		 setTimeout( "$(location).attr('href', 'index.php?l=contenidohome&var=<?php echo $var;?>');",5000 );
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
				echo'<div class="da-panel collapsible">
						<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
							<ul class="action_user">
								<li style="margin-right:6px;">
								   <a href="?l=contenidohome&var='.$_GET['var'].'&crear=v" class="da-tooltip-s various" title="<span lang=\'es\'>Añadir Nuevo Registro</span>">
								   <img src="images/add_new.png" width="32" height="32"></a>
								</li>
							</ul>
						</div>
					 </div>';
				  while($regief = $resef->fetch_array(MYSQLI_ASSOC)){			   
						$selectIdh="select
									  id_home,
									  case producto
										when 'H' then 'Inicio'
									  end as producto,
									  id_ef
									from
									  s_sgc_home
									where
									  producto='H' and id_ef='".$regief['id_ef']."';";
						$residh = $conexion->query($selectIdh,MYSQLI_STORE_RESULT);
						$numidh = $residh->num_rows;
								  
						if($numidh>0){
							$regidh = $residh->fetch_array(MYSQLI_ASSOC);
							$residh->free();
							$selectCont="select
										  id_slider,
										  imagen,
										  id_home,
										  tipo,
										  descripcion
										from
										  s_sgc_slider
										where
										   id_home='".$regidh['id_home']."' and tipo='AR';";		   
							$res = $conexion->query($selectCont,MYSQLI_STORE_RESULT);
						}
						echo'
						<div class="da-panel collapsible">
							<div class="da-panel-header">
								<span class="da-panel-title">
									<img src="images/icons/black/16/list.png" alt="" />
									<b>'.$regief['nombre'].'</b> - <span lang="es">Lista Contenido Inicio</span>
								</span>
							</div>
							<div class="da-panel-content">
								<table class="da-table">
									<thead>
										<tr>
										   <th><b><span lang="es">Contenido</span></b></th>
										   <th style="width:200px; text-align:center"><b><span lang="es">Imagen</span></b></th>
										   <th></th>
										</tr>
									</thead>
									<tbody>';
									  if($numidh>0){
										  $num = $res->num_rows;
									  }else{
										  $num=0;  
									  }
									  if($num>0){
											while($regi = $res->fetch_array(MYSQLI_ASSOC)){
												echo'<tr>
														<td>'.$regi['descripcion'].'</td>
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
														<td class="da-icon-column">
														   <ul class="action_user">
															  <li style="margin-right:5px;"><a href="?l=contenidohome&idslider='.base64_encode($regi['id_slider']).'&editar=v&var='.$_GET['var'].'&idhome='.base64_encode($regi['id_home']).'&tipo='.base64_encode($regi['tipo']).'&id_ef='.base64_encode($regief['id_ef']).'" class="edit da-tooltip-s" title="<span lang=\'es\'>Editar"></a></li>';
														 echo'<li><a href="#" class="eliminar da-tooltip-s" title="<span lang=\'es\'>Eliminar</span>" id="'.$regi['id_slider'].'|'.$regi['imagen'].'|'.$regi['id_home'].'|'.$regi['tipo'].'|'.$regief['id_ef'].'"></a></li>';
													  echo'</ul>	
														</td>
													</tr>';
											}
											$res->free();			
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
		  Error en la consulta: ".$conexion->errno. ": ".$conexion->error
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
		
		$idslider = base64_decode($_GET['idslider']);
		$idhome = base64_decode($_GET['idhome']);
		$tipo = base64_decode($_GET['tipo']);
		$id_ef = base64_decode($_GET['id_ef']); 
        //VALIDAR CONTENIDO
		if(validar_texto($_POST['descripcion'])){
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
			   
			   $contenido = $conexion->real_escape_string($_POST['descripcion']);
			   $patrones = array('@<script[^>]*?>.*?</script>@si',  	// Strip out javascript
						'@<colgroup[^>]*?>.*?</colgroup>@si',			// Strip out HTML tags
						'@<style[^>]*?>.*?</style>@siU',				// Strip style tags properly
						'@<style[^>]*>.*</style>@siU',					// Strip style
						'@<![\s\S]*?--[ \t\n\r]*>@siU',					// Strip multi-line comments including CDATA,
						'@width:[^>].*;@siU',							// Strip width
						'@width="[^>].*"@siU',							// Strip width style
						'@height="[^>].*"@siU',							// Strip height
						'@class="[^>].*"@siU',							// Strip class
						'@border="[^>].*"@siU',							// Strip border
						'@font-family:[^>].*;@siU'						// Strip fonts
				);
				
				$sus = array('','','','','','width: 500px;','width="500"','','','','font-family: Helvetica, sans-serif, Arial;');
				$_cot_txt = preg_replace($patrones,$sus,$contenido);
			  			  
			   $update = "UPDATE s_sgc_slider as sl 
			              INNER JOIN s_sgc_home as sh on (sh.id_home=sl.id_home) 
						  SET"; 
			   if($imagenServidor!=''){
			      if(file_exists('../images/'.$_POST['auximage']))
                  {borra_archivo('../images/'.$_POST['auximage']);}
				  				  
				  $update.=" sl.imagen='".$imagenServidor."', ";
			   }else{
			      $update.=" sl.imagen='".$_POST['auximage']."', ";
			   }
			   $update.="sl.descripcion='".$_cot_txt."' WHERE sl.id_slider='".$idslider."' and sl.id_home = '".$idhome."' and sl.tipo='".$tipo."' and sh.id_ef='".$id_ef."';";
			   echo $update;
	                       
			if($conexion->query($update) === TRUE){
				if($imagenServidor!=''){
					$vec = getimagesize('../images/'.$imagenServidor);
					$ancho = $vec[0];
					$alto = $vec[1];
					if($ancho>330){
					   if($alto>200){
						   if($ancho==$alto){
							    //REDIMENCIONAMOS AUTOMOATICO
								// *** 1) Initialise / load image
								$resizeObj = new resize('../images/'.$imagenServidor);
								// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
								$resizeObj -> resizeImage($ancho, 200, 'portrait');
								// *** 3) Save image
								$resizeObj -> saveImage('../images/'.$imagenServidor, 100);
						   }else{
								//REDIMENCIONAMOS AUTOMOATICO
								// *** 1) Initialise / load image
								$resizeObj = new resize('../images/'.$imagenServidor);
								// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
								$resizeObj -> resizeImage(330, 200, 'exact');
								// *** 3) Save image
								$resizeObj -> saveImage('../images/'.$imagenServidor, 100);
						   }
					   }else{
						    //REDIMENCIONAMOS DE ACUERDO AL ANCHO
						    // *** 1) Initialise / load image
							$resizeObj = new resize('../images/'.$imagenServidor);
							// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
							$resizeObj -> resizeImage(330, $alto, 'landscape');
							// *** 3) Save image
							$resizeObj -> saveImage('../images/'.$imagenServidor, 100); 
					   }
					}elseif($alto>200){
						    //REDIMENCIONAMOS DE ACUERDO AL ALTO
						    // *** 1) Initialise / load image
							$resizeObj = new resize('../images/'.$imagenServidor);
							// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
							$resizeObj -> resizeImage($ancho, 200, 'portrait');
							// *** 3) Save image
							$resizeObj -> saveImage('../images/'.$imagenServidor, 100); 
				    }
					
			     }
			   $mensaje="Se actualizo correctamente los datos del formulario";
			   header('Location: index.php?l=contenidohome&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
			   exit;
			} else{
			   $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			   header('Location: index.php?l=contenidohome&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
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
  $idslider = base64_decode($_GET['idslider']);
  $idhome = base64_decode($_GET['idhome']);
  $tipo = base64_decode($_GET['tipo']); 
  $id_ef = base64_decode($_GET['id_ef']);
 
   
  $selectImg="select 
					sgs.id_slider, 
					sgs.imagen, 
					sgs.descripcion, 
					sgs.id_home, 
					sh.id_ef,
                    ef.nombre
				from
					s_sgc_slider as sgs
					inner join s_sgc_home as sh on (sh.id_home=sgs.id_home)
					inner join s_entidad_financiera as ef on (ef.id_ef=sh.id_ef)
				where
					sgs.id_home = '".$idhome."' and sgs.id_slider = '".$idslider."'
						and sgs.tipo = '".$tipo."' and sh.id_ef='".$id_ef."'
				limit 0 , 1;";				  
  if($resu = $conexion->query($selectImg,MYSQLI_STORE_RESULT)){
		  $regImg = $resu->fetch_array(MYSQLI_ASSOC);
		  $resu->free();
		  
		  if(file_exists('../images/'.$regImg['imagen'])){
			  $imagen = getimagesize('../images/'.$regImg['imagen']);    //Sacamos la información
			  $ancho = $imagen[0];              //Ancho
			  $alto = $imagen[1]; 
		  }
		  if(isset($_POST['descripcion'])) $descripcion = $_POST['descripcion']; else $descripcion = $regImg['descripcion']; 				  if(isset($_POST['idefin'])) $idefin = $_POST['idefin']; else $idefin = $id_ef;
		  echo'<div class="da-panel collapsible">
				  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
					<ul class="action_user">
					  <li style="margin-right:6px;">
						 <a href="?l=contenidohome&var='.$_GET['var'].'" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
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
							  <span lang="es">Actualizar contenido</span>
						  </span>
					  </div>
					  <div class="da-panel-content">
						  <form class="da-form" action="" method="POST" enctype="multipart/form-data" name="formUpdateImage" id="formUpdateImage">
							  <div class="da-form-row">
									 <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
									 <div class="da-form-item large">
										 '.$regImg['nombre'].'
										 <input type="hidden" name="idenfin" value="'.$regImg['id_ef'].'"/>								 
									 </div>	 
								</div>					  
							  <div class="da-form-row" style="text-align:center;">';
								  if(file_exists('../images/'.$regImg['imagen'])){
									  echo'<img src="../images/'.$regImg['imagen'].'"/>';
								  }else{
									  echo'<span lang="es">No existe el archivo físico</span>';  
								  }
						 echo'</div>
							  <div class="da-form-row">
								  <label style="text-align:right;"><b><span lang="es">Archivo</span></b></label>
								  
								  <div class="da-form-item large">
									  <span lang="es">El tamaño máximo del archivo es de 1Mb. Se recomienda que la imagen tenga un alto de 200px y un ancho de 330px., el formato del archivo a subir debe ser [jpg].</span> 
									  <input type="file" class="da-custom-file" id="update" name="txtImagen"/>
									  <span class="errorMessage">'.$errArr['imagen'].'</span>
									  <span><b><span lang="es">Archivo actual:</span></b> '.$regImg['imagen'].'</span>
									  <input type="hidden" name="auximage" value="'.$regImg['imagen'].'"/>
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
	  echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
		  Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error
		."</div>";
  }
}


//AGREGAR NUEVA IMAGEN
function agregar_nuevo_contenido($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
		
	$errArr['imagen'] = '';
	$errArr['errorcontenido'] = '';
	$errArr['errorentidad'] = '';
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
			   $selectIdh="select
							  id_home,
							  id_ef	
							from
							  s_sgc_home
							where
							  producto='H' and id_ef='".$_POST['idefin']."'
							limit
							  0,1;";
			    if($resu = $conexion->query($selectIdh,MYSQLI_STORE_RESULT)){				  
					   $regidh = $resu->fetch_array(MYSQLI_ASSOC);
					   $resu->free();
					   
					   //SEGURIDAD
					   $especiales = array("\r\n", "\n", "\r", "\t");
					   $reemplazos = array("", "", "", "");
					   $tituloIni = str_replace($especiales, $reemplazos, $_POST['descripcion']);
					   $contenido = $conexion->real_escape_string(stripslashes($tituloIni));
						
					   $idhome = $conexion->real_escape_string($_POST['txtIdhome']);			   			
					   //METEMOS LOS DATOS A LA BASE DE DATOS
					   //GENERAMOS EL ID CODIFICADO UNICO
					   $id_new_slider = generar_id_codificado('@S#1$2013');
					   $insert ="INSERT INTO s_sgc_slider(id_slider, imagen, descripcion, id_home, tipo) "
								."VALUES('".$id_new_slider."', '".$imagenServidor."', '".$contenido."', '".$regidh['id_home']."' ,'AR')";
					   
					if($conexion->query($insert) === TRUE){
					   //REDIMENCIONAR IMAGEN
						$vec = getimagesize('../images/'.$imagenServidor);
						$ancho = $vec[0];
						$alto = $vec[1];
						if($ancho>330){
						   if($alto>200){
							   if($ancho==$alto){
									//REDIMENCIONAMOS AUTOMOATICO
									// *** 1) Initialise / load image
									$resizeObj = new resize('../images/'.$imagenServidor);
									// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
									$resizeObj -> resizeImage($ancho, 200, 'portrait');
									// *** 3) Save image
									$resizeObj -> saveImage('../images/'.$imagenServidor, 100);
							   }else{
									//REDIMENCIONAMOS AUTOMOATICO
									// *** 1) Initialise / load image
									$resizeObj = new resize('../images/'.$imagenServidor);
									// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
									$resizeObj -> resizeImage(330, 200, 'exact');
									// *** 3) Save image
									$resizeObj -> saveImage('../images/'.$imagenServidor, 100);
							   }
						   }else{
								//REDIMENCIONAMOS DE ACUERDO AL ANCHO
								// *** 1) Initialise / load image
								$resizeObj = new resize('../images/'.$imagenServidor);
								// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
								$resizeObj -> resizeImage(330, $alto, 'landscape');
								// *** 3) Save image
								$resizeObj -> saveImage('../images/'.$imagenServidor, 100); 
						   }
						}elseif($alto>200){
								//REDIMENCIONAMOS DE ACUERDO AL ALTO
								// *** 1) Initialise / load image
								$resizeObj = new resize('../images/'.$imagenServidor);
								// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
								$resizeObj -> resizeImage($ancho, 200, 'portrait');
								// *** 3) Save image
								$resizeObj -> saveImage('../images/'.$imagenServidor, 100); 
						}
					   
					   $mensaje="Se actualizo correctamente los datos del formulario";
					   header('Location: index.php?l=contenidohome&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
					   exit;
					} else{
					   $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
					   header('Location: index.php?l=contenidohome&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
					   exit;
					}  
				}else{
					  $mensaje="Hubo un error en la consulta "."\n ".$conexion->errno. ": " . $conexion->error;
					   header('Location: index.php?l=contenidohome&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
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
<!--
<link rel="stylesheet" type="text/css" href="plugins/editorjqueryTE/jquery-te-1.4.0.css" media="screen" />
<script type="text/javascript" src="plugins/editorjqueryTE/jquery-te-1.4.0.js"></script>
<script type="text/javascript">
  $(function(){
	  $('.jqte-test').jqte();  
  });
</script>
-->
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
  if(isset($_POST['idefin'])) $idefin = $_POST['idefin']; else $idefin='';
  echo'<div class="da-panel collapsible">
			<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
				<ul class="action_user">
					<li style="margin-right:6px;">
					   <a href="?l=contenidohome&var='.$_GET['var'].'" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
					   <img src="images/retornar.png" width="32" height="32"></a>
					</li>
				</ul>
			</div>
		</div>';
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
			 $num = $res1->num_rows;		   			  
		  echo'<div class="grid_5">
				  <div class="da-panel">
					  <div class="da-panel-header">
						  <span class="da-panel-title">
							  <img src="images/icons/black/16/pencil.png" alt=""/>
							  <span lang="es">Agregar Contenido</span>
						  </span>
					  </div>
					  <div class="da-panel-content">
						  <form class="da-form" action="" method="POST" enctype="multipart/form-data" name="formUpdateImage" id="formUpdateImage"> 
							  <div class="da-form-row">
								   <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
								   <div class="da-form-item small">
									   <select id="idefin" name="idefin" class="requerid" style="width:160px;">';
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
									   <span class="errorMessage" id="errorentidad" lang="es">'.$errArr['errorentidad'].'</span>
								   </div>	 
							  </div>	
							  <div class="da-form-row">
								  <label style="text-align:right;"><b><span lang="es">Imagen</span></b></label>
								  <div class="da-form-item large">
									  <span lang="es">El tamaño máximo del archivo es de 1Mb. Se recomienda que la imagen tenga un alto de 200px y un ancho de 330px., el formato del archivo a subir debe ser [jpg].</span> 
									  <input type="file" class="da-custom-file" id="update" name="txtImagen"/>
									  <span class="errorMessage">'.$errArr['imagen'].'</span>
								  </div>
							  </div>
							  <div class="da-form-row">
								  <label style="text-align:right;"><b><span lang="es">Contenido</span></b></label>
								  <div class="da-form-item large">
									<textarea name="descripcion" id="descripcion">'.$descripcion.'</textarea>
									<span class="errorMessage" lang="es">'.$errArr['errorcontenido'].'</span>
								  </div>
							  </div>
							  <div class="da-button-row">';  
								 if($num>0){ 
									echo'<input type="submit" value="Guardar" class="da-button green" lang="es"/>';
								 }else{
									echo'<input type="submit" value="Guardar" class="da-button green" disabled lang="es"/>';
								 }	
								  echo'<input type="hidden" name="accionGuardar" value="ok"/> 
							  </div>
						  </form>
					  </div>
				  </div>
			  </div>';
	 }else{
		 echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
		  Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error
		    ."</div>";
	 }
}

//FUNCION PARA LISTAR CABECERA
function listar_cabecera_home($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
?>
<link type="text/css" rel="stylesheet" href="plugins/jalerts/jquery.alerts.css"/>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="plugins/jalerts/jquery.alerts.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("a[href].eliminar").click(function(e){
	   var variable = $(this).attr('id');
	   var vec = variable.split('|');
	   var idhome = vec[0];
	   var id_ef = vec[1]; 		  
	   jConfirm("¿Esta seguro de eliminar el registro?", "Eliminar registro", function(r) {
			//alert(r);
			if(r) {
					var dataString ='idhome='+idhome+'&id_ef='+id_ef+'&opcion=eliminacabecera';
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
		 setTimeout( "$(location).attr('href', 'index.php?l=cabecera&var=<?php echo $var;?>&listcabecera=v');",5000 );
	<?php }?>
	 
  });
</script>
<?php	
	//SACAMOS LAS ENTIDADES FINANCIERAS EXISTENTES Y POSTERIOR ESTEN ACTIVADAS
$selectEf="select 
				ef.id_ef, ef.nombre, ef.logo, ef.activado, ef.codigo
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
$resef = $conexion->query($selectEf,MYSQLI_STORE_RESULT);
$num_reg_h = $resef->num_rows;
if($num_reg_h>0){	
/*
echo'<div class="da-panel collapsible">
		<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
		  <ul class="action_user">
			<li style="margin-right:6px;">
			   <a href="?l=cabecera&crear_cabecera=v&var='.$_GET['var'].'" class="da-tooltip-s various" title="Añadir nuevo registro">
			   <img src="images/add_new.png" width="32" height="32"></a>
			</li>
		  </ul>
		</div>
	 </div>';*/
  $c=0;
  	   
 	 while($regief = $resef->fetch_array(MYSQLI_ASSOC)){	
	
		$selectCab="select
				  id_home,
                  cliente,
                  cliente_logo,
				  id_ef
				from
				  s_sgc_home
				where
				  producto='H' and id_ef='".$regief['id_ef']."'
				limit
				  0,1;";		  
	   $rescab = $conexion->query($selectCab,MYSQLI_STORE_RESULT); 
	   
	   //SACAMOS EL NOMBRE DE LA CARPETA CREADA
	   $carpeta=str_replace(" ","",strtolower(trim($regief['codigo']))); 			  
	
	echo'
		<div class="da-panel collapsible" style="width:60%;">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/list.png" alt="" />
					<b>'.$regief['nombre'].'</b> - Lista Cabecera Inicio
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
											   if(file_exists('../images/'.$carpeta.'/'.$regicab['cliente_logo'])){  
												  echo'<img src="../images/'.$carpeta.'/'.$regicab['cliente_logo'].'"/>';
											   }else{
												  echo'no existe el archivo fisico';   
											   }
										   }else{
											  echo'no existe el nombre del archivo en la base de datos';   
										   }
								   echo'</td>
										<td class="da-icon-column">
										   <ul class="action_user">
											  <li style="margin-right:5px;"><a href="?l=cabecera&editarcabecera=v&var='.$_GET['var'].'&idhome='.base64_encode($regicab['id_home']).'&id_ef='.base64_encode($regief['id_ef']).'" class="edit da-tooltip-s" title="Editar"></a></li>';
										   if($c!=1){	  
											  echo'<li>&nbsp;</li>';
										   }else{
											  echo'<li><a href="#" id="'.$regicab['id_home'].'|'.$regief['id_ef'].'" class="eliminar da-tooltip-s" title="Eliminar"></a></li>';   
										   }
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
	  $c++;	
     }
	 $resef->free();
  }else{
	  echo'<div class="da-message warning">
			   No existe registros alguno, razones alguna: 
			   <ul>
				<li>La(s) Entidad(es) Financiera(s) no esta activada(s) consulte con su administrador</li>
			   </ul>
		  </div>';
  }
}


//FUNCION EDITAMOS LA CABECERA
function editar_cabecera($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
	$errArr['imagen'] = '';
	$errArr['errortitulo'] = '';
	$errFlag = false;
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
		$idhome = base64_decode($_GET['idhome']);
	    $id_ef = base64_decode($_GET['id_ef']);		
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
			   $cliente = $CONEXION->real_escape_string($_POST['txtCliente']);
			  			  
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
			   $update.=" WHERE id_home = '".$idhome."' and id_ef='".$id_ef."' LIMIT 1;";
	           	

			if($conexion->query($update) === TRUE){
				
			   $mensaje="Se actualizo correctamente los datos del formulario";
			   header('Location: index.php?l=cabecera&var='.$_GET['var'].'&op=1&msg='.$mensaje.'&listcabecera=v');
			   exit;
			} else{
			   $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			   header('Location: index.php?l=cabecera&var='.$_GET['var'].'&op=2&msg='.$mensaje.'&listcabecera=v');
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
			   $(location).attr('href', 'index.php?l=cabecera&var='+variable+'&listcabecera=v');  
		  });
	  });
   </script>
<?php	
	
	$idhome = base64_decode($_GET['idhome']);
	$id_ef = base64_decode($_GET['id_ef']);
	
	$selectCab="select 
					sgh.id_home, 
					sgh.cliente, 
					sgh.cliente_logo, 
					sgh.id_ef, 
					ef.nombre
				from
					s_sgc_home as sgh
					inner join s_entidad_financiera as ef on (ef.id_ef=sgh.id_ef)
				where
					sgh.producto = 'H' and sgh.id_home = '".$idhome."'
						and sgh.id_ef = '".$id_ef."'
				limit 0 , 1;";
	$resucab = $conexion->query($selectCab,MYSQLI_STORE_RESULT); 			 			  			  		  
	$regicab = $resucab->fetch_array(MYSQLI_ASSOC);
	$resucab->free();
	if(isset($_GET['txtCliente'])) $txtCliente = $_GET['txtCliente']; else $txtCliente=$regicab['cliente']; 
	if(isset($_POST['idefin'])) $idefin = $_POST['idefin']; else $idefin = $regicab['id_ef'];
		 
	echo'<div class="da-panel collapsible">
			<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
				<ul class="action_user">
					<li style="margin-right:6px;">
					   <a href="?l=contenidohome&var='.$_GET['var'].'&listcabecera=v" class="da-tooltip-s" title="Volver">
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
					  Actualizar cabecera
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" enctype="multipart/form-data" name="formUpdateImage" id="formUpdateImage">
					  <div class="da-form-row">
							 <label style="width:190px;"><b>Entidad Financiera</b></label>
							 <div class="da-form-item large">
								 '.$regicab['nombre'].'
								 <input type="hidden" name="idefin" value="'.$regicab['id_ef'].'"/>
							 </div>	 
						</div>					  
					  <div class="da-form-row" style="text-align:center;">';
					      if(file_exists('../images/'.$regicab['cliente_logo'])){
						     echo'<img src="../images/'.$regicab['cliente_logo'].'"/>';
						  }else{
							 echo'NO EXISTE EL ARCHIVO FISICO';   
						  }
				 echo'</div>
					  <div class="da-form-row">
						  <label><b>Logo</b></label>
						  
						  <div class="da-form-item large">
							  <span>El tama&ntilde;o m&aacute;ximo del archivo es de 1Mb. Se recomienda que la imagen tenga un alto de 100px.,&nbsp;el formato del archivo a subir debe ser [jpg].</span> 
							  <input type="file" class="da-custom-file" id="txtImagen" name="txtImagen"/>
							  <span class="errorMessage">'.$errArr['imagen'].'</span>
							  <span><b>Archivo actual:</b> '.$regicab['cliente_logo'].'</span>
							  <input type="hidden" name="auximage" value="'.$regicab['cliente_logo'].'"/>
						  </div>
					  </div>
					
					  <div class="da-form-row">
						  <label><b>Cliente</b></label>
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
						  <input type="hidden" name="idhome" value="'.$regicab['id_home'].'"/> 
					  </div>
				  </form>
			  </div>
		  </div>
	  </div>';
}

function agregar_nueva_cabecera($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
		
	$errArr['imagen'] = '';
	$errArr['errortitulo'] = '';
	$errArr['errorentidad']= '';
	$errFlag = false;
    
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
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
		
		//VALIDAMOS DEPARTAMENTO DOMICILIO
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
			mostrar_agregar_cabecera($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
		} else {
			   //SEGURIDAD
			   $especiales = array("\r\n", "\n", "\r", "\t");
			   $reemplazos = array("", "", "", "");
			   $tituloIni = str_replace($especiales, $reemplazos, $_POST['txtCliente']);
			   $titulo_cliente = $conexion->real_escape_string(stripslashes($tituloIni));
			   //GENERAMOS EL ID CODIFICADO UNICO	
			   $id_new_home = generar_id_codificado('@S#1$2013');		
			   //METEMOS LOS DATOS A LA BASE DE DATOS
			   $insert ="INSERT INTO s_sgc_home(id_home, id_ef, producto, cliente, cliente_logo, id_usuario) "
						."VALUES('".$id_new_home."', '".$_POST['idefin']."', 'H', '".$titulo_cliente."', '".$imagenServidor."', '".$id_usuario_sesion."')";
			   echo $insert;
			               
			if($rs = $conexion->query($insert,MYSQLI_STORE_RESULT)){
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
			   
			   $mensaje="Se actualizo correctamente los datos del formulario";
			   header('Location: index.php?l=cabecera&var='.$_GET['var'].'&op=1&msg='.$mensaje.'&listcabecera=v');
			   exit;
			} else{
			   $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			   header('Location: index.php?l=cabecera&var='.$_GET['var'].'&op=2&msg='.$mensaje.'&listcabecera=v');
			   exit;
			}  
			
		}

	} else {
		//MUESTRO EL FORM PARA EDITAR UNA FOTO
		mostrar_agregar_cabecera($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
	}
}

function mostrar_agregar_cabecera($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr){
 ?>
   <script type="text/javascript">
      $(function(){
		  $('#btnCancelar').click(function(e){
			   var variable = $('#var').prop('value');
			   $(location).attr('href', 'index.php?l=cabecera&var='+variable+'&listcabecera=v');  
		  });
	  });
   </script>
<?php	
		
	if(isset($_POST['txtCliente'])) $txtCliente = $_POST['txtCliente']; else $txtCliente=''; 
	if(isset($_POST['idefin'])) $idefin = $_POST['idefin']; else $idefin = '';
	//SACAMOS LAS ENTIDADES EXISTENTES
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
							sh.id_ef = ef.id_ef and sh.producto='H');";		  
	 $res1 = $conexion->query($select1,MYSQLI_STORE_RESULT);	
	 $num = $res1->num_rows; 
	echo'<div class="da-panel collapsible">
			<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
				<ul class="action_user">
					<li style="margin-right:6px;">
					   <a href="?l=cabecera&var='.$_GET['var'].'&listcabecera=v" class="da-tooltip-s" title="Volver">
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
					  <b>Crear</b> Home - Cabecera
				  </span>
			  </div>
			  <div class="da-panel-content">
				  <form class="da-form" action="" method="POST" enctype="multipart/form-data" name="formCreaCabecera" id="formCreaCabecera">
					<div class="da-form-row">
						   <label><b>Entidad Financiera</b></label>
						   <div class="da-form-item small">
							   <select id="idefin" name="idefin" class="requerid" style="width:160px;">';
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
						  <label><b>Logo</b></label>
						  
						  <div class="da-form-item large">
							  <span>El tama&ntilde;o m&aacute;ximo del archivo es de 1Mb. Se recomienda que la imagen tenga un alto de 100px.,&nbsp;el formato del archivo a subir debe ser [jpg].</span> 
							  <input type="file" class="da-custom-file" id="txtImagen" name="txtImagen"/>
							  <span class="errorMessage">'.$errArr['imagen'].'</span>
						  </div>
					  </div>
					
					  <div class="da-form-row">
						  <label><b>Cliente</b></label>
						  <div class="da-form-item large">
							<input class="textbox" type="text" name="txtCliente" id="txtCliente" style="width: 250px;" value="'.$txtCliente.'" autocomplete="off"/>
							<span class="errorMessage">'.$errArr['errortitulo'].'</span>
						  </div>
					  </div>
					  
					  <div class="da-button-row">  
						  <input type="button" value="Cancelar" class="da-button gray left" name="btnCancelar" id="btnCancelar"/>';
						  if($num>0){
						     echo'<input type="submit" value="Guardar" class="da-button green"/>';
						  }else{
							 echo'<input type="submit" value="Guardar" class="da-button green" disabled/>'; 
						  }
						  echo'<input type="hidden" name="accionGuardar" value="ok"/>
						  <input type="hidden" name="var" id="var" value="'.$_GET['var'].'"/> 
					  </div>
				  </form>
			  </div>
		  </div>
	  </div>';	
	
}

//LISTADO CONTENIDO NOSOTROS PARA CADA ENTIDAD FINANCIERA
function mostrar_lista_contenido_nosotros($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
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
		 $.ambiance({message: "<?php echo base64_decode($msg);?>", 
				title: "Notificacion",
				type: "<?php echo $valor?>",
				timeout: 5
				});
		 //location.load("sgc.php?l=usuarios&idhome=1");
		 //$(location).attr('href', 'sgc.php?l=crearusuario&idhome=1');		
		 setTimeout( "$(location).attr('href', 'index.php?l=contenidohome&var=<?php echo $var;?>&list_nosotros=v');",5000 );
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
			  /*echo'<div class="da-panel collapsible">
					  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
						  <ul class="action_user">
							  <li style="margin-right:6px;">
								 <a href="?l=contenidohome&var='.$_GET['var'].'&crear_nosotros=v" class="da-tooltip-s various" title="Añadir nuevo registro">
								 <img src="images/add_new.png" width="32" height="32"></a>
							  </li>
						  </ul>
					  </div>
				   </div>';*/
				while($regief = $resef->fetch_array(MYSQLI_ASSOC)){			   
					  $selectIdh="select
									id_home,
									case producto
									  when 'H' then 'Inicio'
									end as producto,
									id_ef,
									nosotros,
									imagen
								  from
									s_sgc_home
								  where
									producto='H' and id_ef='".$regief['id_ef']."';";
					  if($residh = $conexion->query($selectIdh,MYSQLI_STORE_RESULT)){
							$numidh = $residh->num_rows;
							$regi = $residh->fetch_array(MYSQLI_ASSOC);
							$residh->free();
										
							echo'
							<div class="da-panel collapsible">
								<div class="da-panel-header">
									<span class="da-panel-title">
										<img src="images/icons/black/16/list.png" alt="" />
										<b>'.$regief['nombre'].'</b> - <span lang="es">Contenido Nosotros</span>
									</span>
								</div>
								<div class="da-panel-content">
									<table class="da-table">
										<thead>
											<tr>
											   <th><b><span lang="es">Contenido</span></b></th>
											   <th style="width:200px; text-align:center"><b><span lang="es">Imagen</span></b></th>
											   <th></th>
											</tr>
										</thead>
										<tbody>';
										  if($numidh>0){
											  echo'<tr>
													  <td>'.substr_replace($regi['nosotros'],'...',610).'</td>
													  <td style="text-align:center; vertical-align:top;">';
														if($regi['imagen']!=''){
															 if(file_exists('../images/'.$regi['imagen'])){  
																echo'<img src="../images/'.$regi['imagen'].'"/>';
															 }else{
																echo'<span lang="es">no existe el archivo físico</span>';   
															 }
														 }else{
															echo'<span lang="es">no existe el nombre del archivo en la base de datos</span>';   
														 }
												 echo'</td>
													  <td class="da-icon-column">
														 <ul class="action_user">
															<li style="margin-right:5px;"><a href="?l=contenidohome&idhome='.base64_encode($regi['id_home']).'&id_ef='.base64_encode($regief['id_ef']).'&editar_nosotros=v&var='.$_GET['var'].'" class="edit da-tooltip-s" title="<span lang=\'es\'>Editar</span>"></a></li>';
													   //echo'<li><a href="#" class="eliminar da-tooltip-s" title="Eliminar" id="'.$regi['id_slider'].'|'.$regi['imagen'].'|'.$regi['id_home'].'|'.$regi['tipo'].'|'.$regief['id_ef'].'"></a></li>';
													echo'</ul>	
													  </td>
												  </tr>';	
										  }else{
											 echo'<tr><td colspan="3">
													  <div class="da-message info">
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
		  Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error
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
		  Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error
		    ."</div>";
	}
}

//FUNCION QUE NOS PERMITE EDITAR CONTENIDO NOSOTROS
function editar_contenido_nosotros($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
	
	$errArr['imagen'] = '';
	$errArr['errorcontenido'] = '';
	$errFlag = false;
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
		
		
		$idhome = base64_decode($_GET['idhome']);
		$id_ef = base64_decode($_GET['id_ef']); 
        //VALIDAR CONTENIDO
		if(validar_texto($_POST['descripcion'])){
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
			mostrar_editar_contenido_nosotros($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
		} else {
			   //SEGURIDAD
			   $contenido = $conexion->real_escape_string($_POST['descripcion']);
			   $patrones = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
						'@<colgroup[^>]*?>.*?</colgroup>@si',		// Strip out HTML tags
						'@<style[^>]*?>.*?</style>@siU',			// Strip style tags properly
						'@<style[^>]*>.*</style>@siU',				// Strip style
						'@<![\s\S]*?--[ \t\n\r]*>@siU',				// Strip multi-line comments including CDATA,
						'@width:[^>].*;@siU',						// Strip width
						'@width="[^>].*"@siU',						// Strip width style
						'@height="[^>].*"@siU',						// Strip height
						'@class="[^>].*"@siU',						// Strip class
						'@border="[^>].*"@siU',						// Strip border
						'@font-family:[^>].*;@siU'					// Strip fonts
				);
				
				$sus = array('','','','','','width: 500px;','width="500"','','','','font-family: Helvetica, sans-serif, Arial;');
				$content_txt = preg_replace($patrones,$sus,$contenido);			  
			   $update = "UPDATE s_sgc_home SET"; 
			   if($imagenServidor!=''){
			      if(file_exists('../images/'.$_POST['auximage']))
                  {borra_archivo('../images/'.$_POST['auximage']);}
				  				  
				  $update.=" imagen='".$imagenServidor."', ";
			   }else{
			      $update.=" imagen='".$_POST['auximage']."', ";
			   }
			   $update.="nosotros='".$content_txt."' WHERE id_home = '".$idhome."' and id_ef='".$id_ef."' and producto='H';";
			   //echo $update;
	          			
            
			if($conexion->query($update) === TRUE){
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
			   header('Location: index.php?l=contenidohome&var='.$_GET['var'].'&list_nosotros=v&op=1&msg='.base64_encode($mensaje));
			   exit;
			} else{
			   $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			   header('Location: index.php?l=contenidohome&var='.$_GET['var'].'&list_nosotros=v&op=2&msg='.base64_encode($mensaje));
			   exit;
			}  
			
		}

	} else {
		//MUESTRO EL FORM PARA EDITAR UNA FOTO
		mostrar_editar_contenido_nosotros($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
	}
}

//FUNCION PARA VISUALIZAR EL FORMULARIO EDITAR
function mostrar_editar_contenido_nosotros($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr){
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
				sh.id_ef,
				sh.imagen,
				sh.nosotros,
				ef.nombre
			  from
				s_sgc_home as sh
				inner join s_entidad_financiera as ef on (ef.id_ef=sh.id_ef)
			  where
			   sh.id_home='".$idhome."' and sh.id_ef='".$id_ef."' and sh.producto='H' and ef.activado=1;";				  
  if($resimg=$conexion->query($selectImg,MYSQLI_STORE_RESULT)){
		  $regImg=$resimg->fetch_array(MYSQLI_ASSOC);
		  $resimg->free();
		  
		  if($regImg['imagen']!=''){
			  if(file_exists('../images/'.$regImg['imagen'])){
				  $imagen = getimagesize('../images/'.$regImg['imagen']);    
				  $ancho = $imagen[0];              
				  $alto = $imagen[1]; 
			  }
		  }
		  if(isset($_POST['descripcion'])) $descripcion = $_POST['descripcion']; else $descripcion = $regImg['nosotros']; 				  
		  echo'<div class="da-panel collapsible">
				  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
					<ul class="action_user">
					  <li style="margin-right:6px;">
						 <a href="?l=contenidohome&var='.$_GET['var'].'&list_nosotros=v" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
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
							  <span lang="es">Editar contenido nosotros</span>
						  </span>
					  </div>
					  <div class="da-panel-content">
						  <form class="da-form" action="" method="POST" enctype="multipart/form-data" name="formUpdateImage" id="formUpdateImage">
							  <div class="da-form-row">
								   <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
								   <div class="da-form-item large">
									   '.$regImg['nombre'].'
								   </div>	 
							  </div>					  
							  <div class="da-form-row" style="text-align:center;">';
								 if($regImg['imagen']!=''){  
									  if(file_exists('../images/'.$regImg['imagen'])){
										  echo'<img src="../images/'.$regImg['imagen'].'"/>';
									  }else{
										  echo'<span lang="es">No existe el archivo físico</span>';  
									  }
								 }else{
									echo'<span lang="es">campo vacio</span>';	 
								 }
						 echo'</div>
							  <div class="da-form-row">
								  <label style="text-align:right;"><b><span lang="es">Archivo</span></b></label>
								  
								  <div class="da-form-item large">
									  <span lang="es">El tamaño máximo del archivo es de 1Mb. Se recomienda que la imagen tenga un ancho de 350px., el formato del archivo a subir debe ser [jpg].</span> 
									  <input type="file" class="da-custom-file" id="update" name="txtImagen"/>
									  <span class="errorMessage">'.$errArr['imagen'].'</span>
									  <span><b><span lang="es">Archivo actual:</span></b> '.$regImg['imagen'].'</span>
									  <input type="hidden" name="auximage" value="'.$regImg['imagen'].'"/>
								  </div>
							  </div>
							
							  <div class="da-form-row">
								  <label style="text-align:right;"><b><span lang="es">Texto</span></b></label>
								  <div class="da-form-item large">
									<textarea name="descripcion" id="descripcion">'.$descripcion.'</textarea>
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
  }else{
	 echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
		  <span lang='es'>Error en la consulta:</span> ".$conexion->errno.": ".$conexion->error
		."</div>"; 
  }
}

//AGREGAR NUEVA IMAGEN
function agregar_nuevo_contenido_nosotros($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
		
	$errArr['imagen'] = '';
	$errFlag = false;
    
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
		//IMAGEN
		$validacion = validarImagen('txtImagen', 1048576, '1 Mb', '../images', true);
		if($validacion['flag']) {
			//SI VALIDO CORRECTAMENTE EL ARCHIVO
			$imagenServidor = $validacion['archivo'];
		} else {
			$errArr['imagen'] = $validacion['mensaje'];
			$errFlag = true;
		}	

		//VEMOS SI TODO SE VALIDO BIEN
		if($errFlag) {
			//HUBIERON ERRORES, MOSTRAMOS EL FORM CON LOS ERRORES
			mostrar_agregar_contenido_nosotros($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
		} else {
			   			   
			   //SEGURIDAD
			   $especiales = array("\r\n", "\n", "\r", "\t");
			   $reemplazos = array("", "", "", "");
			   $tituloIni = str_replace($especiales, $reemplazos, $_POST['descripcion']);
			   $contenido = $conexion->real_escape_string(stripslashes($tituloIni));
			   	
			   $idefin = $conexion->real_escape_string($_POST['idefin']);			   			
			   //METEMOS LOS DATOS A LA BASE DE DATOS
			   //GENERAMOS EL ID CODIFICADO UNICO
			   $id_new_home = generar_id_codificado('@S#1$2013');
			   $insert ="INSERT INTO s_sgc_home(id_home, id_ef, producto, imagen, nosotros, id_usuario) "
						."VALUES('".$id_new_home."', '".$idefin."', 'H', '".$imagenServidor."', '".$contenido."', '".$id_usuario_sesion."')";
			   //$rs = mysql_query($insert, $conexion);
			

			if($conexion->query($insert) === TRUE){
			   //REDIMENCIONAR IMAGEN
				$vec = getimagesize('../images/'.$imagenServidor);
				$ancho = $vec[0];
				$alto = $vec[1];
				// *** 1) Initialise / load image
				$resizeObj = new resize('../images/'.$imagenServidor);
				// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
				$resizeObj -> resizeImage(350, $alto, 'portrait');
				// *** 3) Save image
				$resizeObj -> saveImage('../images/'.$imagenServidor, 100);
			   
			   $mensaje="Se actualizo correctamente los datos del formulario";
			   header('Location: index.php?l=contenidohome&var='.$_GET['var'].'&list_nosotros=v&op=1&msg='.base64_encode($mensaje));
			   exit;
			} else{
			   $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			   header('Location: index.php?l=contenidohome&var='.$_GET['var'].'&list_nosotros=v&op=2&msg='.base64_encode($mensaje));
			   exit;
			}  
			
		}

	} else {
		//MUESTRO EL FORM PARA EDITAR UNA FOTO
		mostrar_agregar_contenido_nosotros($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
	}
}

//FUNCION QUE PERMITE AGREGAR NUEVO CONTENIDO NOSOTROS
function mostrar_agregar_contenido_nosotros($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr){
  ?>
<!--
<link rel="stylesheet" type="text/css" href="plugins/editorjqueryTE/jquery-te-1.4.0.css" media="screen" />
<script type="text/javascript" src="plugins/editorjqueryTE/jquery-te-1.4.0.js"></script>
<script type="text/javascript">
  $(function(){
	  $('.jqte-test').jqte();  
  });
</script>
-->
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
  $(document).ready(function() {
      $('#formUpdateImage').submit(function(e){   
         var idefin=$('#idefin option:selected').prop('value');
		 var contenido=$('#descripcion').val();
		 var sum=0;
		 $(this).find('.requerid').each(function() {
              if(idefin!=''){
				  $('#errorentidad').hide('slow');
			  }else{
				  sum++;
				  $('#errorentidad').show('slow');
				  $('#errorentidad').html('seleccione entidad financiera');
			  }
			  if(contenido!=''){
				  $('#errorcontenido').hide('slow');
			  }else{
				  sum++;
				  $('#errorcontenido').show('slow');
				  $('#errorcontenido').html('ingrese contenido');
			  } 
         });
		 if(sum==0){
			 
		 }else{
	        e.preventDefault();
		 }
	  });
  });
   
</script>
<?php
     
  echo'<div class="da-panel collapsible">
		  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			<ul class="action_user">
			  <li style="margin-right:6px;">
				 <a href="?l=contenidohome&var='.$_GET['var'].'&list_nosotros=v" class="da-tooltip-s" title="Volver">
				 <img src="images/retornar.png" width="32" height="32"></a>
			  </li>
			</ul>
		  </div>
		</div>';
	   //SACAMOS LAS ENTIDADES FINANCIERAS		
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
								sh.id_ef = ef.id_ef and producto='H');";	 						
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
								sh.id_ef = ef.id_ef and producto='H')
							and ef.id_ef = '".$id_ef_sesion."';";	 						
	  }  
	 $res1 = $conexion->query($select1,MYSQLI_STORE_RESULT);
	 $num = $res1->num_rows;		   			  
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
						   <label><b>Entidad Financiera</b></label>
						   <div class="da-form-item small">
							   <select id="idefin" name="idefin" class="requerid" style="width:160px;">';
								  echo'<option value="">seleccione...</option>';
								  while($regi1 = $res1->fetch_array(MYSQLI_ASSOC)){
									 echo'<option value="'.$regi1['id_ef'].'">'.$regi1['nombre'].'</option>'; 
								  }
								  $res1->free();
						  echo'</select>
							   <span class="errorMessage" id="errorentidad"></span>
						   </div>	 
					  </div>	
					  <div class="da-form-row">
						  <label><b>Imagen</b></label>
						  <div class="da-form-item large">
							  <span>El tama&ntilde;o m&aacute;ximo del archivo es de 1Mb. Se recomienda que la imagen tenga un alto de 100px.,&nbsp;el formato del archivo a subir debe ser [jpg].</span> 
							  <input type="file" class="da-custom-file" id="update" name="txtImagen"/>
							  <span class="errorMessage">'.$errArr['imagen'].'</span>
						  </div>
					  </div>
					  <div class="da-form-row">
						  <label><b>Contenido</b></label>
						  <div class="da-form-item large">
							<textarea name="descripcion" id="descripcion" class="requerid">'.$descripcion.'</textarea>
							<span class="errorMessage" id="errorcontenido"></span>
						  </div>
					  </div>
					  <div class="da-button-row">';  
						 if($num>0){ 
						    echo'<input type="submit" value="Guardar" class="da-button green"/>';
						 }else{
							echo'<input type="submit" value="Guardar" class="da-button green" disabled/>';
						 }	
						  echo'<input type="hidden" name="accionGuardar" value="ok"/> 
					  </div>
				  </form>
			  </div>
		  </div>
	  </div>';	
}

//LISTAMOS LAS PREGUNTAS FRECUENTES DE CADA PRODUCTO EXISTENTE DE UNA DETERMINADA ENTIDAD FINANCIERA
function mostrar_lista_contenido_preg_frec($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
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
		 $.ambiance({message: "<?php echo base64_decode($msg);?>", 
				title: "Notificacion",
				type: "<?php echo $valor?>",
				timeout: 5
				});
		 //location.load("sgc.php?l=usuarios&idhome=1");
		 //$(location).attr('href', 'sgc.php?l=crearusuario&idhome=1');		
		 setTimeout( "$(location).attr('href', 'index.php?l=contenidohome&var=<?php echo $var;?>'&list_preg_frec=v);",5000 );
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
								sh.id_ef = ef.id_ef and sh.producto!='H');";
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
							  sh.id_ef = ef.id_ef and sh.producto!='H')
							and ef.id_ef = '".$id_ef_sesion."';";
	}
	if($resef = $conexion->query($selectEf,MYSQLI_STORE_RESULT)){
			$num_reg_ef = $resef->num_rows;
			if($num_reg_ef>0){
				/*echo'<div class="da-panel collapsible">
						<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
							<ul class="action_user">
								<li style="margin-right:6px;">
								   <a href="?l=contenidohome&var='.$_GET['var'].'&crear=v" class="da-tooltip-s various" title="Añadir nuevo registro">
								   <img src="images/add_new.png" width="32" height="32"></a>
								</li>
							</ul>
						</div>
					 </div>';*/
				  while($regief = $resef->fetch_array(MYSQLI_ASSOC)){			   
						$selectIdh="select
									  id_home,
									  producto,
									  producto_nombre,
									  id_ef,
									  preguntas_frecuentes
									from
									  s_sgc_home
									where
									  producto!='H' and id_ef='".$regief['id_ef']."';";
						$residh = $conexion->query($selectIdh,MYSQLI_STORE_RESULT);
						$numidh = $residh->num_rows;
										
						echo'
						<div class="da-panel collapsible">
							<div class="da-panel-header">
								<span class="da-panel-title">
									<img src="images/icons/black/16/list.png" alt="" />
									<b>'.$regief['nombre'].'</b> - <span lang="es">Lista Contenido Preguntas Frecuentes</span>
								</span>
							</div>
							<div class="da-panel-content">
								<table class="da-table">
									<thead>
										<tr>
										   <th><b><span lang="es">Preguntas Frecuentes</span></b></th>
										   <th><b><span lang="es">Producto</span></b></th>
										   <th>&nbsp;</th>
										</tr>
									</thead>
									<tbody>';
									  if($numidh>0){
											while($regi = $residh->fetch_array(MYSQLI_ASSOC)){
												echo'<tr>
														<td>'.substr_replace($regi['preguntas_frecuentes'],'...',610).'</td>
														<td style="text-align:center;">'.$regi['producto_nombre'].'</td>
														<td class="da-icon-column">
														   <ul class="action_user">
															  <li style="margin-right:5px;"><a href="?l=contenidohome&idhome='.base64_encode($regi['id_home']).'&id_ef='.base64_encode($regief['id_ef']).'&producto='.base64_encode($regi['producto']).'&entidad='.base64_encode($regief['nombre']).'&editar_preg_frec=v&var='.$_GET['var'].'" class="edit da-tooltip-s" title="<span lang=\'es\'>Editar</span>"></a></li>';
													  echo'</ul>	
														</td>
													</tr>';
											}
											$residh->free();			
									  }else{
										 echo'<tr><td colspan="3">
												  <div class="da-message info">
													   <span lang="es">No existe registros alguno, ingrese nuevos registros</span>
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
						  <li lang="es">No existe una cabecera creada para la nueva Entidad Financiera, consulte con su administrador</li>
						  <li lang="es">Verificar que la Entindad Financiera este activa, consulte con su administrador</li>
						</ul>
					 </div>';
			}	
	}else{
		echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
		  <span lang='es'>Error en la consulta:</span> ".$conexion->errno .": ".$conexion->error
		   ."</div>"; 
	}
}

//FUNCION QUE NOS PERMITE EDITAR LAS PREGUNTAS FRECUENTES DE CADA PRODUCTO
function editar_contenido_preg_frec($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
	
	if(isset($_POST['accionGuardar'])) {
								
		       $idhome = base64_decode($_GET['idhome']);
               $producto = base64_decode($_GET['producto']); 
               $id_ef = base64_decode($_GET['id_ef']);
			    
			   //SEGURIDAD
			   $contenido = $conexion->real_escape_string($_POST['descripcion']);
			   $patrones = array('@<script[^>]*?>.*?</script>@si',  	// Strip out javascript
						'@<colgroup[^>]*?>.*?</colgroup>@si',			// Strip out HTML tags
						'@<style[^>]*?>.*?</style>@siU',				// Strip style tags properly
						'@<style[^>]*>.*</style>@siU',					// Strip style
						'@<![\s\S]*?--[ \t\n\r]*>@siU',					// Strip multi-line comments including CDATA,
						'@width:[^>].*;@siU',							// Strip width
						'@width="[^>].*"@siU',							// Strip width style
						'@height="[^>].*"@siU',							// Strip height
						'@class="[^>].*"@siU',							// Strip class
						'@border="[^>].*"@siU',							// Strip border
						'@font-family:[^>].*;@siU'						// Strip fonts
				);
				
			   $sus = array('','','','','','width: 500px;','width="500"','','','','font-family: Helvetica, sans-serif, Arial;');
			   $content_txt = preg_replace($patrones,$sus,$contenido);
			  			  
			   $update = "UPDATE s_sgc_home SET"; 
			   $update.=" preguntas_frecuentes='".$content_txt."' WHERE id_home = '".$idhome."' and producto='".$producto."' and id_ef='".$id_ef."';";
			   
	           //$rsu = mysql_query($update, $conexion);
			
            
			if($conexion->query($update) === TRUE){
				
			   $mensaje="Se actualizo correctamente los datos del formulario";
			   header('Location: index.php?l=contenidohome&var='.$_GET['var'].'&list_preg_frec=v&op=1&msg='.base64_encode($mensaje));
			   exit;
			} else{
			   $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			   header('Location: index.php?l=contenidohome&var='.$_GET['var'].'&list_preg_frec=v&op=2&msg='.base64_encode($mensaje));
			   exit;
			}  
	
	} else {
		//MUESTRO EL FORM PARA EDITAR UNA FOTO
		mostrar_editar_preg_frec($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
	}
}

//VISUALIZAMOS EL FORMULARIO PREGUNTAS FRECUENTES
function mostrar_editar_preg_frec($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
  ?>
<script type="text/javascript" src="plugins/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: "textarea#descripcion",
    theme: "modern",
    width: 490,
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
  $producto = base64_decode($_GET['producto']); 
  $id_ef = base64_decode($_GET['id_ef']);
  $entidad = base64_decode($_GET['entidad']);
   
  $select="select
				  id_home,
				  id_ef,
				  producto,
				  producto_nombre,
				  preguntas_frecuentes
				from
				  s_sgc_home
				where
				  id_home='".$idhome."' and id_ef='".$id_ef."' and producto='".$producto."';";				  
  if($res = $conexion->query($select,MYSQLI_STORE_RESULT)){
		  $reg = $res->fetch_array(MYSQLI_ASSOC);
		  $res->free();  
		  echo'<div class="da-panel collapsible">
				  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
					<ul class="action_user">
					  <li style="margin-right:6px;">
						 <a href="?l=contenidohome&var='.$_GET['var'].'&list_preg_frec=v" class="da-tooltip-s" title="Volver">
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
							  <span lang="es">Actualizar contenido</span>
						  </span>
					  </div>
					  <div class="da-panel-content">
						  <form class="da-form" action="" method="POST" enctype="multipart/form-data" name="formUpdateImage" id="formUpdateImage">
							  <div class="da-form-row">
								   <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
								   <div class="da-form-item large">
									   '.$entidad.'						 
								   </div>	 
							  </div>
							  <div class="da-form-row">
								   <label style="text-align:right;"><b><span lang="es">Producto</span></b></label>
								   <div class="da-form-item large">
									   '.$reg['producto_nombre'].'						 
								   </div>	 
							  </div>					  	
							  <div class="da-form-row">
								  <label style="text-align:right;"><b><span lang="es">Preguntas Frecuentes</span></b></label>
								  <div class="da-form-item large">
									<textarea name="descripcion" id="descripcion">'.$reg['preguntas_frecuentes'].'</textarea>
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
	  echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
		  Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error
		   ."</div>";
  }
}
?>    