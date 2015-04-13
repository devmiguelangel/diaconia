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
			header('Location: index.php?l=tipocambio&var=tcm');
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
						
								agregar_nuevo_tipo_cambio($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
								
							} else {
								//VEMOS SI NOS PASAN UN ID DE USUARIO
								if(isset($_GET['id_tc'])) {
						
									if(isset($_GET['darbaja'])) {
										
										desactivar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									}elseif(isset($_GET['daralta'])){ 
									
									    activar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								    
									}elseif(isset($_GET['editar'])) {
										//SI NO ME PASAN 'CPASS' NI 'ELIMINAR', MUESTRO EL FORM PARA EDITAR USUARIO
										editar_tipo_cambio($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
										
									} 
								} else {
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE FORMULARIOS EXISTENTES
									mostrar_lista_tipocambio($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
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
function mostrar_lista_tipocambio($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>

<link type="text/css" rel="stylesheet" href="plugins/jalerts/jquery.alerts.css"/>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="plugins/jalerts/jquery.alerts.js"></script>
<script type="text/javascript">
   $(function(){
	   $("a[href].eliminar").click(function(e){
		   var variable = $(this).attr('id'); 		  
		   var vec = variable.split('|');
		   var id_tc = vec[0];
		   var id_ef = vec[1];
		   jConfirm("¿Esta seguro de eliminar el tipo de cambio?", "Eliminar registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_tc='+id_tc+'&id_ef='+id_ef+'&opcion=elimina_tipocambio';
						$.ajax({
							   async: true,
							   cache: false,
							   type: "POST",
							   url: "eliminar_registro.php",
							   data: dataString,
							   success: function(datareturn) {
									  //alert(datareturn);
									  if(datareturn==1){
										 $('#del-'+id_tc).fadeOut('slow');
										 //location.reload(true);
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
	   
	   //CHECKED
	   $('.moneda').click(function(){
		   var variable = $(this).prop('value');
		   //alert(variable);
		   var dataString ='variable='+variable+'&opcion=tipo_cambio';
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
							  alert('error no se puedo actualizar el registro...');
						  }
						  
				   }
			});
		   //e.preventDefault();
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
		 setTimeout( "$(location).attr('href', 'index.php?l=tipocambio&var=<?php echo $var;?>');",5000 );
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
   if($resef = $conexion->query($selectEf,MYSQLI_STORE_RESULT)){
		if($resef->num_rows){
				echo'<div class="da-panel collapsible">
						<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
							<ul class="action_user">
								<li style="margin-right:6px;">
								   <a href="?l=tipocambio&var='.$_GET['var'].'&crear=v" class="da-tooltip-s various fancybox.ajax" title="<span lang=\'es\'>Añadir Registro</span>">
								   <img src="images/add_new.png" width="32" height="32"></a>
								</li>
							</ul>
						</div>
					 </div>';
					while($regief = $resef->fetch_array(MYSQLI_ASSOC)){	
							$selectFor="select
										  id_tc,
										  id_ef,
										  valor_dolar,
										  valor_boliviano,
										  fecha_registro,
										  id_usuario,
										  activado
										from
										  s_tipo_cambio
										where
										  id_ef='".$regief['id_ef']."';";
							  
							if($res = $conexion->query($selectFor,MYSQLI_STORE_RESULT)){
									echo'
									<div class="da-panel collapsible" style="width:750px;">
										<div class="da-panel-header">
											<span class="da-panel-title">
												<img src="images/icons/black/16/list.png" alt="" />
												<b>'.$regief['nombre'].'</b> - <span lang="es">Listado Tipo de Cambio Moneda</span>
											</span>
										</div>
										<div class="da-panel-content">
											<table class="da-table">
												<thead>
													<tr>
														<th><b><span lang="es">Valor USD.</span></b></th>
														<th><b><span lang="es">Valor Bs.</span></b></th>
														<th><b><span lang="es">Fecha Registro</span></b></th>
														<th><b><span lang="es">Vigente</span></b></th>
														<th></th>
													</tr>
												</thead>
												<tbody>';
												  $num = $res->num_rows;
												  if($num>0){
														while($regi = $res->fetch_array(MYSQLI_ASSOC)){
															echo'<tr id="del-'.$regi['id_tc'].'"';
																   if($regi['activado']==1){
																	  echo'style="background:#FFCA71; color:#000;"'; 
																   }else{
																	  echo'';	 
																   }
															echo'>
																	<td>'.$regi['valor_dolar'].'</td>
																	<td>'.$regi['valor_boliviano'].'</td>
																	<td>'.$regi['fecha_registro'].'</td>
																	<td style="text-align:center;">';
																	  if($regi['activado']==1){
																		 echo'<input type="radio" name="rd-'.$regi['id_tc'].'" class="moneda" value="'.$regi['id_tc'].'|'.$regi['id_ef'].'" checked/>';
																	  }else{
																		 echo'<input type="radio" name="rd-'.$regi['id_tc'].'" class="moneda" value="'.$regi['id_tc'].'|'.$regi['id_ef'].'"/>';  
																	  }
															   echo'</td>
																	<td class="da-icon-column">
																	   <ul class="action_user">
																		  <li style="margin-right:5px;"><a href="?l=tipocambio&id_tc='.base64_encode($regi['id_tc']).'&id_ef='.base64_encode($regief['id_ef']).'&editar=v&var='.$_GET['var'].'" class="edit da-tooltip-s" title="<span lang=\'es\'>Editar</span>"></a></li>';
																		 if($regi['activado']==0){ 
																	 echo'<li><a href="#" class="eliminar da-tooltip-s" title="<span lang=\'es\'>Eliminar</span>" id="'.$regi['id_tc'].'|'.$regief['id_ef'].'"></a></li>';
																		 }else{
																		   echo'<li style="margin-left:10px;">&nbsp;</li>';	 
																		 }
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
							}else{
							   echo'<div class="da-message error">error en la consulta'.$conexion->errno.'&nbsp;'.$conexion->error.'</div>';	
							}
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
   }else{
	  echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
		  Error en la consulta: ".$conexion->errno.": ".$conexion->error
		   ."</div>"; 
   }
}

//FUNCION QUE PERMITE VISUALIZAR EL FORMULARIO NUEVO USUARIO
function agregar_nuevo_tipo_cambio($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
	

	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['btnGuardar'])) {
		
			//SEGURIDAD
			
			$idefin = $conexion->real_escape_string($_POST['idefin']);
			$valorusd = $conexion->real_escape_string($_POST['txtValorusd']);
			$valorbs = $conexion->real_escape_string($_POST['txtValorbs']);
			//SACAMOS EL ID CODIFICADO UNICO
			//$id_new_estado = generar_id_codificado('@S#1$2013');					
			//METEMOS LOS DATOS A LA BASE DE DATOS
			$insert ="INSERT INTO s_tipo_cambio(id_tc, id_ef, valor_dolar, valor_boliviano, fecha_registro, id_usuario, activado) "
				    ."VALUES(NULL, '".$idefin."', ".$valorusd.", ".$valorbs.", curdate(), '".$id_usuario_sesion."', 0)";
			//echo $insert;
			
			
			//VERIFICAMOS SI HUBO ERROR EN EL INGRESO DEL REGISTRO
			if($conexion->query($insert)===TRUE){
								
				$mensaje="Se registro correctamente los datos del formulario";
			    header('Location: index.php?l=tipocambio&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
			} else {
				$mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			    header('Location: index.php?l=tipocambio&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
				exit;
			}
		
	} else {
		//MUESTRO EL FORM PARA CREAR UNA CATEGORIA
		mostrar_crear_tipo_cambio($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
	}
}

//VISUALIZAMOS EL FORMULARIO CREA USUARIO
function mostrar_crear_tipo_cambio($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
   ?>
    
	<script type="text/javascript">
       $(document).ready(function() {
           
		   //BOTON SUBMIT 
		   $('#frmTCAd').submit(function(e){
			      var idefin = $('#idefin option:selected').prop('value');
				  var valorbs = $('#txtValorbs').prop('value');
				  //alert(producto);
				  var cont=0;
				  $(this).find('.required').each(function(){
					    					
						if(idefin!=''){
							$('#errorentidad').hide('slow');
						}else{
							cont++;
							$('#errorentidad').show('slow');
							$('#errorentidad').html('seleccione entidad financiera');
						}
						
						if(valorbs!=''){
							if(valorbs.match(/^[0-9\.]+$/)){
								$('#errorvalorbs').hide('slow');
							}else{
								cont++;
								$('#errorvalorbs').show('slow');
								$('#errorvalorbs').html('ingrese solo numeros');  
							}
						}else{
						  cont++;
						  $('#errorvalorbs').show('slow');
						  $('#errorvalorbs').html('ingrese valor Bs.');  
						}
																	
						if(cont==0){
						  //$("#btnUsuario").removeAttr("disabled");
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
			   $(location).prop('href','index.php?l=tipocambio&var='+variable);
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
   if($res1 = $conexion->query($select1,MYSQLI_STORE_RESULT)){ 
		 echo'<div class="da-panel collapsible">
				<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
					<ul class="action_user">
						<li style="margin-right:6px;">
						   <a href="?l=tipocambio&var='.$_GET['var'].'" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
						   <img src="images/retornar.png" width="32" height="32"></a>
						</li>
					</ul>
				</div>
			 </div>';		
		 echo'<div class="da-panel" style="width:600px;">
				<div class="da-panel-header">
					<span class="da-panel-title">
						<img src="images/icons/black/16/pencil.png" alt="" />
						<span lang="es">Nuevo Registro Tipo de Cambio</span>
					</span>
				</div>
				<div class="da-panel-content">
					<form class="da-form" name="frmTCAd" action="" method="post" id="frmTCAd">
						<div class="da-form-row">
							 <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
							 <div class="da-form-item small">
								 <select id="idefin" name="idefin" class="required">';
									echo'<option value="" lang="es">seleccione...</option>';
									while($regi1 = $res1->fetch_array(MYSQLI_ASSOC)){
										echo'<option value="'.$regi1['id_ef'].'">'.$regi1['nombre'].'</option>';  
									}
									$res1->free();
							echo'</select>
								 <span class="errorMessage" id="errorentidad" lang="es"></span>
							 </div>	 
						</div>
						<div class="da-form-row">
							<label style="text-align:right;"><b><span lang="es">Valor USD.</span></b></label>
							<div class="da-form-item small">
							  <input class="textbox required" type="text" id="txtValorusd" name="txtValorusd" value="1" autocomplete="off" readonly/>
							  <span class="errorMessage" id="errorvalorusd"></span>
							</div>
						</div>
						<div class="da-form-row">
							<label style="text-align:right;"><b><span lang="es">Valor Bs.</span></b></label>
							<div class="da-form-item small">
							  <input class="textbox required" type="text" id="txtValorbs" name="txtValorbs" value="" autocomplete="off"/>
							  <span class="errorMessage" id="errorvalorbs" lang="es"></span>
							</div>
						</div>								
						<div class="da-button-row">
						   <input type="button" value="Cancelar" class="da-button gray left" name="btnCancelar" id="btnCancelar" lang="es"/>
						   <input type="submit" value="Guardar" class="da-button green" name="btnGuardar" id="btnGuardar" lang="es"/>
									
						   <input type="hidden" name="var" id="var" value="'.$_GET['var'].'"/>
						</div>
					</form>
				</div>
			</div>';
   }else{
	 echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
		  Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error
		   ."</div>";  
   }
}

//FUNCION PARA EDITAR UN USUARIO
function editar_tipo_cambio($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion) {
		
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['btnGuardar'])) {
            $id_tc = base64_decode($_GET['id_tc']);
            $id_ef = base64_decode($_GET['id_ef']);
            //SEGURIDAD
			$idefin = $conexion->real_escape_string($_POST['idefin']);
			$valorusd = $conexion->real_escape_string($_POST['txtValorusd']);
			$valorbs = $conexion->real_escape_string($_POST['txtValorbs']);
			
            //CARGAMOS LOS DATOS A LA BASE DE DATOS
            $update = "UPDATE s_tipo_cambio SET valor_dolar=".$valorusd.", valor_boliviano=".$valorbs.", id_ef='".$idefin."', fecha_registro=curdate() ";
            $update.= "WHERE id_tc=".$id_tc." and id_ef='".$id_ef."' LIMIT 1;";
            
            
			//echo mysql_errno($conexion);

            if($conexion->query($update)===TRUE){
                $mensaje="Se actualizo correctamente los datos del formulario";
			    header('Location: index.php?l=tipocambio&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
            } else{
                $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			    header('Location: index.php?l=tipocambio&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
				exit;
            }

	} else {
	  //MUESTRO FORM PARA EDITAR UNA CATEGORIA
	  mostrar_editar_tipo_cambio($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
	}
	
}

//VISUALIZAMOS EL FORMULARIO PARA EDITAR EL FORMULARIO
function mostrar_editar_tipo_cambio($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
 ?>
    
	<script type="text/javascript">
       $(function(){
		   			
			//BOTON SUBMIT 
		   $('#frmTcEd').submit(function(e){
			      var idefin = $('#idefin option:selected').prop('value');
				  var valorbs = $('#txtValorbs').prop('value');
				  //alert(producto);
				  var cont=0;
				  $(this).find('.required').each(function(){
					    					
						if(idefin!=''){
							$('#errorentidad').hide('slow');
						}else{
							cont++;
							$('#errorentidad').show('slow');
							$('#errorentidad').html('seleccione entidad financiera');
						}
						
						if(valorbs!=''){
							if(valorbs.match(/^[0-9\.]+$/)){
								$('#errorvalorbs').hide('slow');
							}else{
								cont++;
								$('#errorvalorbs').show('slow');
								$('#errorvalorbs').html('ingrese solo numeros');  
							}
						}else{
						  cont++;
						  $('#errorvalorbs').show('slow');
						  $('#errorvalorbs').html('ingrese valor Bs.');  
						}
																	
						if(cont==0){
						  //$("#btnUsuario").removeAttr("disabled");
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
			   $(location).prop('href','index.php?l=tipocambio&var='+variable);
		   });   
	   });
    </script>
 
 <?php    
	$id_tc = base64_decode($_GET['id_tc']);
    $id_ef = base64_decode($_GET['id_ef']);
	//SACAMOS LOS DATOS DE LA BASE DE DATOS
	$select = "select
				  id_tc,
				  id_ef,
				  valor_dolar,
				  valor_boliviano,
				  fecha_registro,
				  id_usuario,
				  activado
				from
				  s_tipo_cambio
				where
				  id_ef='".$id_ef."' and id_tc=".$id_tc.";";
    			 
	if($rs = $conexion->query($select, MYSQLI_STORE_RESULT)){
			$num = $rs->num_rows;
			
			//SACAMOS LAS ENTIDADES EXISTENTES
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
		
			if($res1 = $conexion->query($selectEf,MYSQLI_STORE_RESULT)){
			  
					//SI EXISTE EL USUARIO DADO EN LA BASE DE DATOS, LO EDITAMOS
					if($num>0) {
					   echo'<div class="da-panel collapsible">
							  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
								  <ul class="action_user">
									  <li style="margin-right:6px;">
										 <a href="?l=tipocambio&var='.$_GET['var'].'" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
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
										  <span lang="es">Editar Tipo de Cambio</span>
									  </span>
								  </div>
								  <div class="da-panel-content">
									  <form class="da-form" name="frmTcEd" action="" method="post" id="frmTcEd">
										  <div class="da-form-row">
											 <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
											 <div class="da-form-item large" style="text-align:left;">
												 <select id="idefin" name="idefin" class="requerid" style="width:160px;">';
													echo'<option value="" lang="es">seleccione...</option>';
													while($regi1 = $res1->fetch_array(MYSQLI_ASSOC)){
													  if($regi1['id_ef']==$fila['id_ef']){
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
										  <div class="da-form-row">
											  <label style="text-align:right;"><b><span lang="es">Valor USD.</span></b></label>
											  <div class="da-form-item small">
												<input class="textbox required" type="text" id="txtValorusd" name="txtValorusd" value="'.$fila['valor_dolar'].'" autocomplete="off" readonly/>
												<span class="errorMessage" id="errorvalorusd"></span>
											  </div>
										  </div>
										  <div class="da-form-row">
											  <label style="text-align:right;"><b><span lang="es">Valor Bs.</span></b></label>
											  <div class="da-form-item small">
												<input class="textbox required" type="text" id="txtValorbs" name="txtValorbs" value="'.$fila['valor_boliviano'].'" autocomplete="off"/>
												<span class="errorMessage" id="errorvalorbs" lang="es"></span>
											  </div>
										  </div>		
																			  
										  <div class="da-button-row">
											 <input type="button" value="Cancelar" class="da-button gray left" name="btnCancelar" id="btnCancelar" lang="es"/>
											 <input type="submit" value="Guardar" class="da-button green" name="btnGuardar" id="btnGuardar" lang="es"/>
													  
											 <input type="hidden" name="var" id="var" value="'.$_GET['var'].'"/>
										  </div>
									  </form>
								  </div>
							  </div>';
					
					} else {
						//SI NO EXISTE EL USUARIO DADO EN LA BASE DE DATOS, VOLVEMOS A LA LISTA DE USUARIOS
						header('Location: index.php?l=tipocambio&var='.$_GET['var']);
					}
			}else{
				 echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
		  Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error
		            ."</div>";
			}
					
	}else{
		echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
		  Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error
		   ."</div>";
    }
}


?>