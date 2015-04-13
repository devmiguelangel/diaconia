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
			header('Location: index.php?l=archivos&var=f');
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
						
								agregar_nuevo_formulario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
								
							} else {
								//VEMOS SI NOS PASAN UN ID DE USUARIO
								if(isset($_GET['idformulario'])) {
						
									if(isset($_GET['eliminar'])) {
										
										eliminar_formulario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									} elseif(isset($_GET['editar'])) {
										//SI NO ME PASAN 'CPASS' NI 'ELIMINAR', MUESTRO EL FORM PARA EDITAR USUARIO
										editar_formulario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
										
									} 
								} else {
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE FORMULARIOS EXISTENTES
									mostrar_lista_formularios($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
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
function mostrar_lista_formularios($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>
<link href="plugins/jalerts/jquery.alerts.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="plugins/jalerts/jquery.alerts.js"></script>
<script type="text/javascript">
   $(function(){
	   $("a[href].eliminar").click(function(e){
		   var valor = $(this).attr('id');
		   //alert(valor);
		   var vec=valor.split('|');
		   var id_formulario=vec[0];
		   var id_home=vec[1];
		   var archivo=vec[2];
		   var id_ef=vec[3];
		  
		   jConfirm("¿Esta seguro de eliminar el archivo pdf?", "Eliminar registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_formulario='+id_formulario+'&id_home='+id_home+'&archivo='+archivo+'&id_ef='+id_ef+'&opcion=formulario';
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
										jAlert("El archivo no pudo eliminarse intente nuevamente", "Mensaje");
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
		 setTimeout( "$(location).attr('href', 'index.php?l=archivos&var=<?php echo $var;?>');",5000 );
	<?php }?>
	 
  });
</script>
<?php
//echo $tipo_sesion; echo $id_ef_sesion;
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
  if($resef->num_rows>0){	  
	  while($regief = $resef->fetch_array(MYSQLI_ASSOC)){
		$selectFor="select
					  sf.id_formulario,
					  sf.id_home,
					  sf.archivo,
					  sf.titulo,
					  sh.producto,
					  sh.producto_nombre
					from
					  s_sgc_formulario as sf
					  inner join s_sgc_home as sh on (sh.id_home=sf.id_home)
					where
					  id_ef='".$regief['id_ef']."' and producto !='H'; ";
		//echo $selectFor; 			
		$res = $conexion->query($selectFor,MYSQLI_STORE_RESULT);			
		echo'
		<div class="da-panel collapsible">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/list.png" alt="" />
					<b>'.$regief['nombre'].'</b> - <span lang="es">Listado Formularios</span>
				</span>
			</div>
			<div class="da-panel-content">
				<table class="da-table">
					<thead>
						<tr>
							<th><span lang="es">Titulo</span></th>
							<th style="width:200px;"><span lang="es">Producto</span></th>
							<th style="width:100px; text-align:center"><span lang="es">Archivo</span></th>
							<th></th>
						</tr>
					</thead>
					<tbody>';
					  $num = $res->num_rows;
					  if($num>0){
							while($regi = $res->fetch_array(MYSQLI_ASSOC)){
								echo'<tr>
										<td>'.$regi['titulo'].'</td>
										<td>'.$regi['producto_nombre'].'</td>
										<td style="text-align:center;">';
										   if($regi['archivo']!=''){
											   if(file_exists('../file_form/'.$regi['archivo'])){  
												  echo'<img src="images/pdf.jpg" width="25" height="32"/>';
											   }else{
												  echo'<span lang="es">no existe el archivo físico</span>';   
											   }
										   }else{
											  echo'<span lang="es">no existe el nombre del archivo en la base de datos</span>';   
										   }
								   echo'</td>
										<td class="da-icon-column">
										   <ul class="action_user">
											  <li><a href="?l=archivos&idformulario='.base64_encode($regi['id_formulario']).'&id_ef='.base64_encode($regief['id_ef']).'&editar=v&var='.$_GET['var'].'" class="edit da-tooltip-s" title="<span lang=\'es\'>Editar</span>"></a></li>
											  <li><a href="#" id="'.$regi['id_formulario'].'|'.$regi['id_home'].'|'.$regi['archivo'].'|'.$regief['id_ef'].'" class="eliminar da-tooltip-s" title="<span lang=\'es\'>Eliminar</span>"></a></li>
										   </ul>	
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
	  }
	  $resef->free();
  }else{
	  echo'<div class="da-message warning">
			   <span lang="es">No existe ningun registro, probablemente se debe a</span>:
			   <ul>
				  <li lang="es">La Entidad Financiera no tiene asignado un producto</li>
				  <li lang="es">La Entidad Financiera no esta activado</li>
				  <li lang="es">La Entidad Financiera no esta creada</li>
				</ul>
		   </div>';
  }
}

//FUNCION QUE PERMITE VISUALIZAR EL FORMULARIO NUEVO USUARIO
function agregar_nuevo_formulario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
	$errFlag = false;
	$errArr['errortitulo'] = '';
	$errArr['errorarchivo'] = '';
	$errArr['errorproducto'] = '';
	$errArr['errorentidad'] = '';

	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
		
		if($errFlag){
			//HUBO ERRORES EN LOS CAMPOS NO SE PODRA SUBIR EL ARCHIVO			
			
		}else{
		    $validacion = validarPdf('txtArchivo', 2097152, '2 MB', '../file_form', true);
			if($validacion['flag']) {
				//SE VALIDO CORRECTAMENTE LA IMAGEN
				$archivoServidor = $validacion['archivo'];
			} else {
				//EL ARCHIVO NO SE VALIDO
				$errArr['errorarchivo'] = $validacion['mensaje'];
				$errFlag = true;
			}
	    }		
		
		//VEMOS SI TODO SE VALIDO BIEN
		if($errFlag) {
			//HUBIERON ERRORES, MOSTRAMOS EL FORM CON LOS ERRORES
			mostrar_crear_formulario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
		} else {
			//SI NO HUBIERON ERRORES, CARGAMOS LOS DATOS A LA BASE DE DATOS

			//SEGURIDAD
			$titulo = $conexion->real_escape_string($_POST['txtTitulo']);
			$idefin = $conexion->real_escape_string($_POST['idefin']);
			$idhome = $conexion->real_escape_string($_POST['idhome']);
			//GENERAMOS ID CODIFICADO UNICO
			$id_new_formulario = generar_id_codificado('@S#1$2013');
			//METEMOS LOS DATOS A LA BASE DE DATOS
			$insert ="INSERT INTO s_sgc_formulario(id_formulario, id_home, archivo, titulo) "
				    ."VALUES('".$id_new_formulario."', '".$idhome."', '".$archivoServidor."', '".$titulo."')";
						
			//VERIFICAMOS SI HUBO ERROR EN EL INGRESO DEL REGISTRO
			if($conexion->query($insert)===TRUE){
				$mensaje="Se registro correctamente los datos del formulario";
			    header('Location: index.php?l=archivos&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
			} else {
				$mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno.": ". $conexion->error;
			    header('Location: index.php?l=archivos&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
				exit;
			}
		}

	} else {
		//MUESTRO EL FORM PARA CREAR UNA CATEGORIA
		mostrar_crear_formulario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
	}
}

//VISUALIZAMOS EL FORMULARIO CREA USUARIO
function mostrar_crear_formulario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr){
   ?>
    <style type="text/css">
       .loading-entf{
			background: #FFFFFF url(images/loading.png) top center no-repeat;
			height: 0px;
			margin: 10px 0;
			text-align: center;
			font-size: 90%;
			font-weight: bold;
			color: #0075AA;
		}
    </style>
	<script type="text/javascript">
       $(function(){
		   $('#btnCancelar').click(function(){   
			   var variable=$('#var').prop('value');
			   $(location).prop('href','index.php?l=archivos&var='+variable);
		   });
		   
		   //HABILITAMOS PRODUCTO DE ACUERDO A LA ENTIDAD FINANCIERA EXISTENTE DE LA TABLA HOME
		   $('#idefin').change(function(){
			  var idefin = $(this).prop('value');
			  if(idefin!=''){
				  $('#content-entidadf').fadeIn('slow');
				  var dataString = 'idefin='+idefin+'&opcion=buscar_producto';
				   $.ajax({
						 async: true,
						 cache: false,
						 type: "POST",
						 url: "buscar_registro.php",
						 data: dataString,
						 beforeSend: function(){
							  $("#entidad-loading").css({
								  'height': '11px'
							  });
						 },
						 complete: function(){
							  $("#entidad-loading").css({
								  "background": "transparent"
							  });
						 },
						 success: function(datareturn) {
							  //alert(datareturn);
							  $('#entidad-loading').html(datareturn);
						 }
				   });
			  }else{
				  $('#content-entidadf').fadeOut('slow');
			  }
		   });
		   
		   $('#frmArchivo').submit(function(e){
			  	var idefin = $('#idefin option:selected').prop('value');
				var idhome = $('#idhome option:selected').prop('value');
				var titulo = $('#txtTitulo').prop('value');
				//alert('hola');
				var sum=0;
				$(this).find('.required').each(function(){
					  if(idefin!=''){
						 $('#errorentidad').hide('slow');
						 if(idhome!=''){
							 $('#errorproducto').hide('slow');
						 }else{
							 sum++;
							 $('#errorproducto').show('slow');
							 $('#errorproducto').html('seleccione producto');
						 }
					  }else{
						 sum++;
						 $('#errorentidad').show('slow');
						 $('#errorentidad').html('seleccione entidad financiera');
					  }
					  
					  if(titulo!=''){
							 if(titulo.match(/^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s\D\d]+$/)){
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
				});
				if(sum==0){
					
				}else{
				   e.preventDefault();
				}
		   });
		   
	   });
    </script>
 
 <?php
  //VARIABLES DE INICIO
  if(isset($_POST['txtTitulo'])) $titulo = $_POST['txtTitulo']; else $titulo = '';
  if(isset($_POST['txtIdhome'])) $txtIdhome = $_POST['txtIdhome']; else $txtIdhome = '';
  if(isset($_POST['idefin'])) $idefin = $_POST['idefin']; else $idefin = '';
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
	if($res1->num_rows>0){
		  echo'<div class="da-panel collapsible">
				  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
					<ul class="action_user">
					  <li style="margin-right:6px;">
						 <a href="?l=archivos&var='.$_GET['var'].'" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
						 <img src="images/retornar.png" width="32" height="32"></a>
					  </li>
					</ul>
				  </div>
			  </div>';
		  echo'<div class="da-panel" style="width:650px;">
				<div class="da-panel-header">
					<span class="da-panel-title">
						<img src="images/icons/black/16/pencil.png" alt="" />
						<span lang="es">Nuevo Archivo</span>
					</span>
				</div>
				<div class="da-panel-content">
					<form class="da-form" name="frmArchivo" id="frmArchivo" action="" method="post" enctype="multipart/form-data">
						<div class="da-form-row">
							 <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
							 <div class="da-form-item small">
								 <select id="idefin" name="idefin" class="required">';
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
								 <span class="errorMessage" id="errorentidad" lang="es"></span>
							 </div>	 
						</div>
						<div class="da-form-row" style="display: none;" id="content-entidadf">
							<label style="text-align:right;"><b><span lang="es">Producto</span></b></label>
							<div class="da-form-item small">
							  <span id="entidad-loading" class="loading-entf"></span>
							</div>
						</div>
						<div class="da-form-row">
							<label style="text-align:right;"><b><span lang="es">Titulo</span></b></label>
							<div class="da-form-item large">
								<input class="textbox required" type="text" name="txtTitulo" id="txtTitulo" style="width: 400px;" value="'.$titulo.'" autocomoplete="off"/>
								<span class="errorMessage" id="errortitulo" lang="es"></span>
							</div>
						</div>
						<div class="da-form-row">
							<label style="text-align:right;"><b><span lang="es">Archivo</span></b></label>
							<div class="da-form-item large">
								<span lang="es">El tamaño máximo del archivo es de 2Mb, el formato del archivo a subir debe ser [PDF]</span> .
								<input type="file" id="txtArchivo" name="txtArchivo"/>
								<span class="errorMessage" lang="es">'.$errArr['errorarchivo'].'</span>
							</div>
						</div>
																		
						<div class="da-button-row">
							
							<input type="submit" value="Guardar" class="da-button green" name="btnUsuario" id="btnUsuario" lang="es"/>
							<input type="hidden" name="accionGuardar" value="checkdatos"/>
							<input type="hidden" id="var" value="'.$_GET['var'].'"/>
						</div>
					</form>
				</div>
			</div>';
	}else{
		echo'<div class="da-message info">
					 <span lang="es">No existe ningun registro, probablemente se debe a</span>:
					 <ul>
						<li lang="es">La Entidad Financiera no tiene asignado un producto</li>
						<li lang="es">La Entidad Financiera no esta activado</li>
						<li lang="es">La Entidad Financiera no esta creada</li>
					  </ul>
				 </div>';
    }
}

//FUNCION PARA EDITAR UN USUARIO
function editar_formulario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion) {

	$errFlag = false;
	$errArr['errorarchivo'] = '';
	$idformulario = base64_decode($_GET['idformulario']);
	$id_ef = base64_decode($_GET['id_ef']);
	//$idusuario = strtolower($idusuario);

	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
       
		
		    $validacion = validarPdf('txtArchivo', 2097152, '2 MB', '../file_form', false);
			if($validacion['flag']) {
				//SE VALIDO CORRECTAMENTE LA IMAGEN
				$archivoServidor = $validacion['archivo'];
			} else {
				//EL ARCHIVO NO SE VALIDO
				$errArr['errorarchivo'] = $validacion['mensaje'];
				$errFlag = true;
			}
	   
        //VEMOS SI TODO SE VALIDO BIEN
        if($errFlag) {
            //SI HUBIERON ERRORES, MOSTRAMOS EL FORM CON LOS ERRORES
            mostrar_editar_contenido($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
        } else {
            //SEGURIDAD
			$titulo = $conexion->real_escape_string($_POST['txtTitulo']);
			$idhome = $conexion->real_escape_string($_POST['idhome']);
            //CARGAMOS LOS DATOS A LA BASE DE DATOS
            $update = "UPDATE s_sgc_formulario as sf
			                  inner join s_sgc_home as sh on (sh.id_home=sf.id_home)
					   SET sf.titulo='".$titulo."',";
            if($archivoServidor!=''){
                if(file_exists('../file_form/'.$_POST['archivoAux']))
                {borra_archivo('../file_form/'.$_POST['archivoAux']);}

                $update.=" sf.archivo='".$archivoServidor."', ";
            }else{
                $update.=" sf.archivo='".$_POST['archivoAux']."', ";
            }
            $update.="sf.id_home='".$idhome."' WHERE sf.id_formulario='".$idformulario."' and sh.id_ef='".$id_ef."';";
            //echo $update;

            if($conexion->query($update) === TRUE){
                $mensaje="Se actualizo correctamente los datos del formulario";
			    header('Location: index.php?l=archivos&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
            } else{
                $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno.": " . $conexion->error;
			    header('Location: index.php?l=archivos&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
				exit;
            }

        }

	} else {
	  //MUESTRO FORM PARA EDITAR UNA CATEGORIA
	  mostrar_editar_contenido($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
	}
	
}

//VISUALIZAMOS EL FORMULARIO PARA EDITAR EL FORMULARIO
function mostrar_editar_contenido($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr){
  ?>
    
	<script type="text/javascript">
       $(function(){
		   $('#btnCancelar').click(function(){   
			   var variable=$('#var').prop('value');
			   $(location).prop('href','index.php?l=archivos&var='+variable);
		   });
		   
		   //HABILITAMOS PRODUCTO DE ACUERDO A LA ENTIDAD FINANCIERA EXISTENTE DE LA TABLA HOME
		   $('#idefin').change(function(){
			  var idefin = $(this).prop('value');
			  if(idefin!=''){
				  $('#content-entidadf').fadeIn('slow');
				  var dataString = 'idefin='+idefin+'&opcion=buscar_producto';
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
		   
		   $('#frmArchivoEditar').submit(function(e){
			  	var idefin = $('#idefin option:selected').prop('value');
				var idhome = $('#idhome option:selected').prop('value');
				var titulo = $('#txtTitulo').prop('value');
				//alert('hola');
				var sum=0;
				$(this).find('.required').each(function(){
					  if(idefin!=''){
						 $('#errorentidad').hide('slow');
						 if(idhome!=''){
							 $('#errorproducto').hide('slow');
						 }else{
							 sum++;
							 $('#errorproducto').show('slow');
							 $('#errorproducto').html('seleccione producto');
						 }
					  }else{
						 sum++;
						 $('#errorentidad').show('slow');
						 $('#errorentidad').html('seleccione entidad financiera');
					  }
					  
					  if(titulo!=''){
							 if(titulo.match(/^[0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ\s\D\d]+$/)){
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
				});
				if(sum==0){
					
				}else{
				   e.preventDefault();
				}
		   });
		   
	   });
    </script>
 
 <?php   
	$idformulario = base64_decode($_GET['idformulario']);
    $id_ef=base64_decode($_GET['id_ef']);
	//SACAMOS LOS DATOS DE LA BASE DE DATOS
	$select = "select
					sf.id_formulario,
					sf.id_home,
					sf.archivo,
					sf.titulo
				from
				   s_sgc_formulario as sf
				   inner join s_sgc_home as sh on (sh.id_home=sf.id_home) 
				where
				   sf.id_formulario='".$idformulario."' and sh.id_ef='".$id_ef."'
				limit
				   0,1;";
		   
	$rs = $conexion->query($select, MYSQLI_STORE_RESULT);
	$num = $rs->num_rows;
	
	//SI EXISTE EL USUARIO DADO EN LA BASE DE DATOS, LO EDITAMOS
	if($num>0) {

		$fila = $rs->fetch_array(MYSQLI_ASSOC);

		if(isset($_POST['txtTitulo'])) $titulo = $_POST['txtTitulo']; else $titulo = $fila['titulo'];
		if(isset($_POST['txtIdhome'])) $txtIdhome = $_POST['txtIdhome']; else $txtIdhome = $fila['id_home'];
		$archivo = $fila['archivo'];

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
		  echo'<div class="da-panel collapsible">
				  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
					<ul class="action_user">
					  <li style="margin-right:6px;">
						 <a href="?l=archivos&var='.$_GET['var'].'" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
						 <img src="images/retornar.png" width="32" height="32"></a>
					  </li>
					</ul>
				  </div>
			  </div>';
		  echo'<div class="da-panel" style="width:650px;">
				<div class="da-panel-header">
					<span class="da-panel-title">
						<img src="images/icons/black/16/pencil.png" alt="" />
						<span lang="es">Editar Archivo</span>
					</span>
				</div>
				<div class="da-panel-content">
					<form class="da-form" name="frmArchivoEditar" id="frmArchivoEditar" action="" method="post" enctype="multipart/form-data">
						<div class="da-form-row">
							 <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
							 <div class="da-form-item small">
								 <select id="idefin" name="idefin" class="required">';
									echo'<option value="" lang="es">seleccione...</option>';
									while($regi1 = $res1->fetch_array(MYSQLI_ASSOC)){
										if($id_ef==$regi1['id_ef']){ 
										 echo'<option value="'.$regi1['id_ef'].'" selected>'.$regi1['nombre'].'</option>';  
										}else{
											echo'<option value="'.$regi1['id_ef'].'">'.$regi1['nombre'].'</option>';  
										}
									}
									$res1->free();
							echo'</select>
								 <span class="errorMessage" id="errorentidad" lang="es"></span>
							 </div>	 
						</div>
						<div class="da-form-row" id="content-entidadf">
							<label style="text-align:right;"><b><span lang="es">Producto</span></b></label>
							<div class="da-form-item small">
							  <span id="entidad-loading" class="loading-entf">';
							       $select="select
											  id_home,
											  id_ef,
											  producto,
											  producto_nombre	
											from
											  s_sgc_home
											where
											  id_ef='".$id_ef."' and producto!='H';";
									$sql = $conexion->query($select,MYSQLI_STORE_RESULT);
									echo'<select name="idhome" id="idhome" class="required" style="width:170px;">';
												echo'<option value="" lang="es">seleccione...</option>';
												while($regief = $sql->fetch_array(MYSQLI_ASSOC)){
													if($fila['id_home']==$regief['id_home']){  
													  echo'<option value="'.$regief['id_home'].'" selected>'.$regief['producto_nombre'].'</option>';  
													}else{
														echo'<option value="'.$regief['id_home'].'">'.$regief['producto_nombre'].'</option>';
													}
												}
												$sql->free();
									echo'</select>
										 <span class="errorMessage" id="errorproducto" lang="es"></span>';
						 echo'</span>
							</div>
						</div>
						<div class="da-form-row">
							<label style="text-align:right;"><b><span lang="es">Titulo</span></b></label>
							<div class="da-form-item large">
								<input class="textbox required" type="text" name="txtTitulo" id="txtTitulo" style="width: 400px;" value="'.$titulo.'" autocomoplete="off"/>
								<span class="errorMessage" id="errortitulo" lang="es"></span>
							</div>
						</div>
						<div class="da-form-row">
							<label style="text-align:right;"><b><span lang="es">Archivo</span></b></label>
							<div class="da-form-item large">
							    <span lang="es">El tamaño máximo del archivo es de 2Mb, el formato del archivo a subir debe ser [PDF].</span> 
								<input type="file" id="txtArchivo" name="txtArchivo"/>
								<span class="errorMessage">'.$errArr['errorarchivo'].'</span>
								<span><span lang="es">Archivo actual:</span> '.$archivo.'</span>
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
	$rs->free();
	} else {
		//SI NO EXISTE EL USUARIO DADO EN LA BASE DE DATOS, VOLVEMOS A LA LISTA DE USUARIOS
		header('Location: index.php?l=archivos&var='.$_GET['var']);
	}
}

//FUNCION QUE PERMITE ELIMINAR EL FORMULARIO
function eliminar_formulario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion) {

	$idformulario = base64_decode($_GET['idformulario']);
	//VEO SI SE HA HECHO CLICK EN EL BOTON ELIMINAR
	if(isset($_POST['btnEliminar'])) {

		//ELIMINAMOS EL REGISTRO
		
		$delete = "DELETE FROM s_sgc_formulario WHERE id_formulario='".$idformulario."' LIMIT 1;";
		//$rs = mysql_query($delete, $conexion);	  
		if($conexion->query($delete)===TRUE){
		    
			 if(file_exists('../file_form/'.$_POST['archivodel'])){ 
			    borra_archivo('../file_form/'.$_POST['archivodel']);
			 }		
			
		   $mensaje="Se elimino correctamente el archivo";
		   header('Location: index.php?l=archivos&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
		   exit;				
		} else{
		   $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
		   header('Location: index.php?l=archivos&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
		   exit;
		}
		
	} elseif(isset($_POST['btnCancelar'])){
	   header('Location: index.php?l=archivos&var='.$_GET['var']);
	   exit;
	}else {
		//SI NO SE HIZO CLICK EN EL BOTON ELIMINAR, MOSTRAMOS EL FORM
		mostrar_form_eliminar($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
	}
}

//FUNCION PARA MOSTRAR EL FORM PARA ELIMINAR UNA NOTICIA
function mostrar_form_eliminar($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion) {
    $idformulario = base64_decode($_GET['idformulario']); 
	//VEMOS SI LA CATEGORIA EXISTE
	$select = "select
					id_formulario,
					archivo
				from
				   s_sgc_formulario
				where
				   id_formulario=".$idformulario."
				limit
				   0,1;";
	//echo $select;
	$rs = $conexion->query($select, MYSQLI_STORE_RESULT);
	$num = $rs->num_rows;
   
	//SI EXISTE LA NOTICIA, PODEMOS ELIMINAR
	if($num) {

			//MOSTRAMOS EL MENU USUARIOS Y SALIR
			$fila = $rs->fetch_array(MYSQLI_ASSOC);
			echo'<div style="font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;">';
			echo '<form name="frmDarbaja" action="" method="post" class="da-form">';
			echo '<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" cols="2">';
			echo '<tr><td align="center" width="100%" style="height:60px;">';
			echo 'Al eliminar el archivo, se eliminara de la base de datos, est&aacute; seguro de eliminar el archivo <b>'.$fila['archivo'].'</b> de forma permanente?';
			echo '</td></tr>
				  <tr> 
				  <td align="center">
				  <input class="da-button green" type="submit" name="btnEliminar" value="Eliminar"/>'
				.'<input type="hidden" name="accionEliminar" value="checkdatos"/>
				  <input class="da-button gray left" type="submit" name="btnCancelar" value="Cancelar"/>
				   <input type="hidden" name="archivodel" value="'.$fila['archivo'].'"/>';
			echo '</td></tr></table></form>';
			echo'</div>';
			$rs->free();
	} else {
		//SI NO EXISTE LA NOTICIA, NOS VAMOS A LA LISTA DE CATEGORIAS
		header('Location: index.php?l=archivos&var='.$_GET['var']);
	}
}


?>