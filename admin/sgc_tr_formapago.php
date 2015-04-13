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
			header('Location: index.php?l=tr_formapago&var=tr&producto='.$_GET['producto']);
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
						
								agregar_nueva_formapago($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
								
							} else {
								//VEMOS SI NOS PASAN UN ID DE USUARIO
								if(isset($_GET['idformapago'])) {
						
									if(isset($_GET['darbaja'])) {
										
										desactivar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									}elseif(isset($_GET['daralta'])){ 
									
									    activar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								    
									}elseif(isset($_GET['editar'])) {
										//SI NO ME PASAN 'CPASS' NI 'ELIMINAR', MUESTRO EL FORM PARA EDITAR USUARIO
										editar_forma_pago($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
										
									} 
								} else {
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE FORMULARIOS EXISTENTES
									mostrar_lista_formapago($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
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
function mostrar_lista_formapago($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>

<link type="text/css" rel="stylesheet" href="plugins/jalerts/jquery.alerts.css"/>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="plugins/jalerts/jquery.alerts.js"></script>
<script type="text/javascript">
   $(function(){
	   $("a[href].eliminar").click(function(e){
		   var variable = $(this).attr('id'); 		  
		   var vec=variable.split('|');
		   var id_forma_pago = vec[0];
		   var id_ef = vec[1];
		   var dato = vec[2];
		   jConfirm("¿Esta seguro de eliminar forma de pago?", "Eliminar registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_forma_pago='+id_forma_pago+'&id_ef='+id_ef+'&opcion=elimina_formapago';
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
										 $('#del-'+dato).fadeOut('slow');
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
	$produce = $_GET["producto"];
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
		 setTimeout( "$(location).attr('href', 'index.php?l=tr_formapago&var=<?=$var;?>&producto=<?=$produce;?>');",5000 );
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
							sh.id_ef = ef.id_ef and sh.producto='TRD');";
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
							sh.id_ef = ef.id_ef and sh.producto='TRD')
						  and ef.id_ef = '".$id_ef_sesion."';";
}
   if($resef = $conexion->query($selectEf,MYSQLI_STORE_RESULT)){
		$num_regi_ef = $resef->num_rows;
		if($num_regi_ef>0){
			  echo'<div class="da-panel collapsible">
					  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
						  <ul class="action_user">
							  <li style="margin-right:6px;">
								 <a href="?l=tr_formapago&var='.$_GET['var'].'&crear=v&producto='.$_GET['producto'].'" class="da-tooltip-s various fancybox.ajax" title="<span lang=\'es\'>Añadir Forma Pago</span>">
								 <img src="images/add_new.png" width="32" height="32"></a>
							  </li>
						  </ul>
					  </div>
				   </div>';
				  while($regief = $resef->fetch_array(MYSQLI_ASSOC)){	
						  $selectFor="select 
										  sfp.id_forma_pago,
										  sfp.forma_pago,
										  sfp.codigo,
										  sfp.producto,
										  sfp.id_ef,
										  sh.producto_nombre
									  from
										  s_forma_pago as sfp
										  inner join s_sgc_home as sh on (sh.id_ef=sfp.id_ef)
									  where
										  sfp.id_ef = '".$regief['id_ef']."' and sfp.producto=sh.producto and sfp.producto='".base64_decode($_GET['producto'])."';";
							
						  if($res = $conexion->query($selectFor,MYSQLI_STORE_RESULT)){	
								  echo'
								  <div class="da-panel collapsible" style="width:750px;">
									  <div class="da-panel-header">
										  <span class="da-panel-title">
											  <img src="images/icons/black/16/list.png" alt="" />
											  <b>'.$regief['nombre'].'</b> - <span lang="es">Listado de Registros Forma de Pago</span>
										  </span>
									  </div>
									  <div class="da-panel-content">
										  <table class="da-table">
											  <thead>
												  <tr>
													  <th style="width:350px;"><b><span lang="es">Forma de Pago</span></b></th>
													  <th style="width:150px;"><b><span lang="es">Codigo</span></b></th>
													  <th style="width:150px; text-align:center;"><b><span lang="es">Producto</span></b></th>
													  <th></th>
												  </tr>
											  </thead>
											  <tbody>';
												$num = $res->num_rows;
												if($num>0){
													  while($regi = $res->fetch_array(MYSQLI_ASSOC)){
														  $vec=explode('.',$regi['id_forma_pago']);
														  echo'<tr id="del-'.$vec[1].'">
																  <td>'.$regi['forma_pago'].'</td>
																  <td>'.$regi['codigo'].'</td>
																  <td style="text-align:center;" lang="es">'.$regi['producto_nombre'].'</td>
																  <td class="da-icon-column">
																	 <ul class="action_user">';
																		//echo'<li style="margin-right:5px;"><a href="?l=tr_formapago&idformapago='.base64_encode($regi['id_forma_pago']).'&id_ef='.base64_encode($regief['id_ef']).'&editar=v&var='.$_GET['var'].'&producto='.$_GET['producto'].'" class="edit da-tooltip-s" title="Editar"></a></li>';
																		echo'<li><a href="#" class="eliminar da-tooltip-s" title="<span lang=\'es\'>Eliminar</span>" id="'.$regi['id_forma_pago'].'|'.$regief['id_ef'].'|'.$vec[1].'"></a></li>';
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
							  echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error."</div>";
						  }
				  }
				  $resef->free();
		}else{
			echo'<div class="da-message warning">
					 <span lang="es">No existe ningun registro, probablemente se debe a</span>:
					 <ul>
						<li lang="es">La Entidad Financiera no tiene asignado el producto Todo Riesgo Domiciliario</li>
						<li lang="es">La Entidad Financiera no esta activado</li>
						<li lang="es">La Entidad Financiera no esta creada</li>
					  </ul>
				</div>';
		}
   }else{
	   echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error."</div>";
   }
}

//FUNCION QUE PERMITE VISUALIZAR EL FORMULARIO NUEVO USUARIO
function agregar_nueva_formapago($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
	
    $errArr['errorfp'] = '';
	$errFlag = false;
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['btnGuardar'])) {
		
			//SEGURIDAD
			$formapago = $conexion->real_escape_string($_POST['txtFormPago']);
			$vec=explode('|',$formapago);
			$idefin = $conexion->real_escape_string($_POST['idefin']);
			$producto = $conexion->real_escape_string($_POST['producto']);
			
			  $select="select
						  count(id_forma_pago) as num_reg
						from
						  s_forma_pago
						where
						  codigo='".$vec[1]."' 
							  and id_ef='".$idefin."' 
							  and producto='".$producto."';";
			  $res = $conexion->query($select,MYSQLI_STORE_RESULT); 				  
			  $regi = $res->fetch_array(MYSQLI_ASSOC);
			  if($regi['num_reg']>0){
				  $errArr['errorfp'] = 'Esta forma de pago ya existe seleccione otro o en su caso seleccione otro producto o una entidad financiera';
	              $errFlag = true;
			  }				
			
			if($errFlag){
				mostrar_crear_ocupacion($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
			}else{
				//SACAMOS EL ID CODIFICADO UNICO
				$id_new_forma_pago = generar_id_codificado('@S#1$2013');					
				//METEMOS LOS DATOS A LA BASE DE DATOS
				$insert ="INSERT INTO s_forma_pago(id_forma_pago, forma_pago, codigo, producto, id_ef) "
						."VALUES('".$id_new_forma_pago."', '".$vec[0]."', '".$vec[1]."', '".$producto."', '".$idefin."')";
				
				
				//VERIFICAMOS SI HUBO ERROR EN EL INGRESO DEL REGISTRO
				if($conexion->query($insert)===TRUE){
									
					$mensaje="Se registro correctamente los datos del formulario";
					header('Location: index.php?l=tr_formapago&var='.$_GET['var'].'&producto='.base64_encode($producto).'&op=1&msg='.base64_encode($mensaje));
					exit;
				} else {
					$mensaje="Hubo un error al ingresar los datos, consulte con su administrador ".$conexion->errno. ": ".$conexion->error;
					header('Location: index.php?l=tr_formapago&var='.$_GET['var'].'&producto='.base64_encode($producto).'&op=2&msg='.base64_encode($mensaje));
					exit;
				}
			}
	} else {
		//MUESTRO EL FORM PARA CREAR UNA CATEGORIA
		mostrar_crear_ocupacion($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
	}
}

//VISUALIZAMOS EL FORMULARIO CREA USUARIO
function mostrar_crear_ocupacion($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr){
   ?>
    
	<script type="text/javascript">
       $(document).ready(function() {
           //HABILITAMOS PRODUCTO DE ACUERDO A LA ENTIDAD FINANCIERA EXISTENTE DE LA TABLA HOME
		   $('#idefin').change(function(){
			  var idefin = $(this).prop('value');
			  var producto = '<?=base64_decode($_GET['producto']);?>';
			  if(idefin!=''){
				  $('#content-entidadf').fadeIn('slow');
				  var dataString = 'idefin='+idefin+'&producto='+producto+'&opcion=buscar_producto_ocupacion';
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
		   $('#frmForPagoAd').submit(function(e){
			      var formapago = $('#txtFormPago option:selected').prop('value');
				  var vec = formapago.split('|');
				  var idefin = $('#idefin option:selected').prop('value');
				  var producto = $('#producto option:selected').prop('value');
				  //alert(producto);
				  
				  var cont=0;
				  $(this).find('.required').each(function(){
					    if(formapago!=''){
								$('#errorformapago').hide('slow');
					    }else{
							cont++;
				            $('#errorformapago').show('slow');
							$('#errorformapago').html('Seleccione forma de pago');
						}
						
						if(idefin!=''){
							$('#errorentidad').hide('slow');
						}else{
							cont++;
							$('#errorentidad').show('slow');
							$('#errorentidad').html('seleccione entidad financiera');
						}
						
						if(producto!=''){
							$('#errorproducto').hide('slow');
						}else{
							cont++;
							$('#errorproducto').show('slow');
							$('#errorproducto').html('seleccione producto');
						}
						
						/*//VERIFICAMOS SI EXISTE FORMA DE PAGO
						var dataString = 'forma_pago_code='+vec[1]+'&id_ef='+idefin+'&producto='+producto+'&opcion=busca_formapago';
						//alert(dataString);
						$.ajax({
							   async: true,
							   cache: false,
							   type: "POST",
							   url: "buscar_registro.php",
							   data: dataString,
							   success: function(datareturn) {
									  alert(datareturn);
									  if(datareturn==1){
										 cont++;
				                         $('#errorformapago').show('slow');
							             $('#errorformapago').html('Esta forma de pago ya existe seleccione otro o en su caso seleccione otro producto o una entidad financiera');
										 e.preventDefault();
									  }
									  
							   }
						 });*/
						
				  });
				  if(cont==0){
						/*var dataString = 'forma_pago_code='+vec[1]+'&id_ef='+idefin+'&producto='+producto+'&opcion=busca_formapago';
						alert(dataString);
						$.ajax({
							   async: true,
							   cache: false,
							   type: "POST",
							   url: "buscar_registro.php",
							   data: dataString,
							   success: function(datareturn) {
									  alert(datareturn);
									  if(datareturn==1){
										
				                         $('#errorformapago').show('slow');
							             $('#errorformapago').html('Esta forma de pago ya existe seleccione otro o en su caso seleccione otro producto o una entidad financiera');
										 
									  }
									  
							   }
						 });
						*/
							
				  }else{
					 e.preventDefault();
					 //$('#btnUsuario').attr('disabled', true);
				  }
		   });
		   
		   //CONVERTIR A MAYUSCULAS
		   $('#txtCodigo').keyup(function() {
			  $(this).val($(this).val().toUpperCase());
		   }); 
		   
		   //BOTON CANCELAR
		   $('#btnCancelar').click(function(){   
			   var variable=$('#var').prop('value');
			   var producto = '<?=$_GET['producto'];?>';
			   $(location).prop('href','index.php?l=tr_formapago&var='+variable+'&producto='+producto);
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
							  sh.id_ef = ef.id_ef and sh.producto='TRD');";		 
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
							  sh.id_ef = ef.id_ef and sh.producto='TRD')
						  and ef.id_ef='".$id_ef_sesion."';";		
    }
	 $res1 = $conexion->query($select1,MYSQLI_STORE_RESULT);
	 $fp_name=array('Al Contado|CO','Anualizado|AN','Pago Combinado|PC');
	 $num=count($fp_name);
	 if(isset($_POST['idefin'])){ $idefin=$_POST['idefin']; $style=""; }else{ $idefin=''; $style='style="display: none;"';}
	 if(isset($_POST['producto'])) $producto=$_POST['producto']; else $producto='';
	 if(isset($_POST['txtFormPago'])) $txtFormPago=$_POST['txtFormPago']; else $txtFormPago='';
 echo'<div class="da-panel collapsible">
		<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			<ul class="action_user">
				<li style="margin-right:6px;">
				   <a href="?l=tr_formapago&var='.$_GET['var'].'&producto='.$_GET['producto'].'" class="da-tooltip-s" title="Volver">
				   <img src="images/retornar.png" width="32" height="32"></a>
				</li>
			</ul>
		</div>
	 </div>';		
 echo'<div class="da-panel" style="width:600px;">
		<div class="da-panel-header">
			<span class="da-panel-title">
				<img src="images/icons/black/16/pencil.png" alt="" />
				<span lang="es">Nuevo Registro Forma de Pago</span>
			</span>
		</div>
		<div class="da-panel-content">
			<form class="da-form" name="frmForPagoAd" action="" method="post" id="frmForPagoAd">
				<div class="da-form-row">
					 <label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
					 <div class="da-form-item small">
						 <select id="idefin" name="idefin" class="required" style="width:200px;">';
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
						 <span class="errorMessage" id="errorentidad"></span>
				     </div>	 
				</div>
				<div class="da-form-row" '.$style.' id="content-entidadf">
					<label style="text-align:right;"><b><span lang="es">Producto</span></b></label>
					<div class="da-form-item small">
					  <span id="entidad-loading" class="loading-entf">';
					     if($idefin!=''){  
							   $select="select
										  id_home,
										  id_ef,
										  producto,
										  producto_nombre
										from
										  s_sgc_home
										where
										  id_ef='".$idefin."' and producto!='H';";
								if($sql = $conexion->query($select,MYSQLI_STORE_RESULT)){
										echo'<select name="producto" id="producto" class="required requerid" style="width:170px;">';
													echo'<option value="" lang="es">seleccione...</option>';
													while($regief = $sql->fetch_array(MYSQLI_ASSOC)){
														if($producto==$regief['producto']){  
															echo'<option value="'.$regief['producto'].'" selected>'.$regief['producto_nombre'].'</option>';  
														}else{
															echo'<option value="'.$regief['producto'].'">'.$regief['producto_nombre'].'</option>';  
														}
													}
													$sql->free();
										echo'</select>
											 <span class="errorMessage" id="errorproducto"></span>';
								}else{
									echo'<div class="da-message error">error en la consulta'.$conexion->errno.'&nbsp;'.$conexion->error.'</div>'; 	
								}
						 }
				 echo'</span>
					</div>
				</div>
				<div class="da-form-row">
					<label style="text-align:right;"><b><span lang="es">Forma de Pago</span></b></label>
					<div class="da-form-item small">
						<select id="txtFormPago" name="txtFormPago" class="required">';
						   echo'<option value="" lang="es">seleccione...</option>';
						   for($i=0;$i<$num;$i++){
							 $vec=explode('|',$fp_name[$i]);
							 if($txtFormPago==$fp_name[$i]){ 
						        echo'<option value="'.$fp_name[$i].'" selected>'.$vec[0].'</option>';  
						     }else{
							   echo'<option value="'.$fp_name[$i].'">'.$vec[0].'</option>';  
							 }
						   }
				   echo'</select>
				        <span class="errorMessage" id="errorformapago">'.$errArr['errorfp'].'</span>
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
}

//FUNCION PARA EDITAR UN USUARIO
function editar_forma_pago($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion) {
		
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['btnGuardar'])) {
            $idformapago = base64_decode($_GET['idformapago']);
            $id_ef = base64_decode($_GET['id_ef']);
            //SEGURIDAD
			$formapago = $conexion->real_escape_string($_POST['txtFormPago']);
			$vec=explode('|',$formapago);
			$idefin = $conexion->real_escape_string($_POST['idefin']);
			$producto = $conexion->real_escape_string($_POST['producto']);
			
            //CARGAMOS LOS DATOS A LA BASE DE DATOS
            $update = "UPDATE s_forma_pago SET forma_pago='".$vec[0]."', codigo='".$vec[1]."', id_ef='".$idefin."', producto='".$producto."' ";
            $update.= "WHERE id_forma_pago='".$idformapago."' and id_ef='".$id_ef."' LIMIT 1;";
            //echo $update;
           

            if($conexion->query($update)===TRUE){
                $mensaje="Se actualizo correctamente los datos del formulario";
			    header('Location: index.php?l=tr_formapago&var='.$_GET['var'].'&producto='.base64_encode($producto).'&op=1&msg='.base64_encode($mensaje));
			    exit;
            } else{
                $mensaje="Hubo un error al ingresar los datos, consulte con su administrador ".$conexion->errno.": " .$conexion->error;
			    header('Location: index.php?l=tr_formapago&var='.$_GET['var'].'&producto='.base64_encode($producto).'&op=2&msg='.base64_encode($mensaje));
				exit;
            }

	} else {
	  //MUESTRO FORM PARA EDITAR UNA CATEGORIA
	  mostrar_editar_forma_pago($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
	}
	
}

//VISUALIZAMOS EL FORMULARIO PARA EDITAR EL FORMULARIO
function mostrar_editar_forma_pago($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
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
			
		   //FIJAMOS EL PRODUCTO ELEJIDO
		   $('#producto option').not(':selected').attr('disabled', false);
		   $("#producto option").each(function(index) {
				//alert(this.text + ' ' + this.value);
				var option = $(this).prop('value');
				if(option==='<?=base64_decode($_GET['producto']);?>'){
				   $(this).prop('selected',true); 
				}
		   });
		   $('#producto option').not(':selected').attr('disabled', true);		
			
			//BOTON SUBMIT 
		   $('#frmForPagoEd').submit(function(e){
			      var formapago = $('#txtFormPago option:selected').prop('value');
				  var idefin = $('#idefin option:selected').prop('value');
				  var producto = $('#producto option:selected').prop('value');
				  //alert(idefin);
				  var cont=0;
				  $(this).find('.required').each(function(){
					    if(formapago!=''){
							$('#errorformapago').hide('slow');
					    }else{
							cont++;
				            $('#errorformapago').show('slow');
							$('#errorformapago').html('seleccione forma de pago');
						}
						
						if(idefin!=''){
							$('#errorentidad').hide('slow');
					    }else{
							cont++;
							$('#errorentidad').show('slow');
							$('#errorentidad').html('seleccione entidad financiera');
						}
						
						if(producto!=''){
							$('#errorproducto').hide('slow');
						}else{
							cont++;
							$('#errorproducto').show('slow');
							$('#errorproducto').html('seleccione producto');
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
			   var producto = '<?=$_GET['producto'];?>';
			   $(location).prop('href','index.php?l=au_formapago&var='+variable+'&producto='+producto);
		   });   
	   });
    </script>
 
 <?php    
	$idformapago = base64_decode($_GET['idformapago']);
    $id_ef = base64_decode($_GET['id_ef']);
	//SACAMOS LOS DATOS DE LA BASE DE DATOS
	$select = "select
				 id_forma_pago,
				 forma_pago,
				 codigo,
				 id_ef,
				 producto
			   from
				 s_forma_pago
			   where
			     id_forma_pago='".$idformapago."' and id_ef='".$id_ef."'	 
			   limit
				 0,1;";
    			 
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
										sh.id_ef = ef.id_ef and sh.producto='TRD');";
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
										sh.id_ef = ef.id_ef and sh.producto='TRD')
									  and ef.id_ef = '".$id_ef_sesion."';";
			}
		
			 $res1 = $conexion->query($selectEf,MYSQLI_STORE_RESULT);
			 $fp_name=array('Al Contado|CO','Anualizado|AN','Pago Combinado|PC');
			 $num=count($fp_name);
			//SI EXISTE EL USUARIO DADO EN LA BASE DE DATOS, LO EDITAMOS
			if($num>0) {
			   echo'<div class="da-panel collapsible">
					  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
						  <ul class="action_user">
							  <li style="margin-right:6px;">
								 <a href="?l=tr_formapago&var='.$_GET['var'].'&producto='.$_GET['producto'].'" class="da-tooltip-s" title="Volver">
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
								  Editar Registro Ocupación
							  </span>
						  </div>
						  <div class="da-panel-content">
							  <form class="da-form" name="frmForPagoEd" action="" method="post" id="frmForPagoEd">
								  <div class="da-form-row">
									 <label style="text-align:right;"><b>Entidad Financiera</b></label>
									 <div class="da-form-item large" style="text-align:left;">
										 <select id="idefin" name="idefin" class="requerid" style="width:160px;">';
											echo'<option value="">seleccione...</option>';
											while($regi1 = $res1->fetch_array(MYSQLI_ASSOC)){
											  if($regi1['id_ef']==$fila['id_ef']){
												 echo'<option value="'.$regi1['id_ef'].'" selected>'.$regi1['nombre'].'</option>';  
											  }else{
												 echo'<option value="'.$regi1['id_ef'].'">'.$regi1['nombre'].'</option>';  
											  }
											}
											$res1->free();
									echo'</select>
										 <span class="errorMessage" id="errorentidad"></span>
									 </div>	   
								  </div>
								  <div class="da-form-row" id="content-entidadf">
									  <label style="text-align:right;"><b>Producto</b></label>
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
														id_ef='".$fila['id_ef']."' and producto!='H';";
											  $sql = $conexion->query($select,MYSQLI_STORE_RESULT);
											  echo'<select name="producto" id="producto" class="required" style="width:170px;">';
														  echo'<option value="">Seleccionar...</option>';
														  while($regief = $sql->fetch_array(MYSQLI_ASSOC)){
															  if($fila['producto']==$regief['producto']){  
																  echo'<option value="'.$regief['producto'].'" selected>'.$regief['producto_nombre'].'</option>';  
															  }else{
																  echo'<option value="'.$regief['producto'].'">'.$regief['producto_nombre'].'</option>';
															  }
														  }
														  $sql->free();
											  echo'</select>
												   <span class="errorMessage" id="errorproducto"></span>';
								   echo'</span>
									  </div>
								  </div>
								  <div class="da-form-row">
									  <label style="text-align:right;"><b>Titulo</b></label>
									  <div class="da-form-item small">
											<select id="txtFormPago" name="txtFormPago" class="required">';
											   echo'<option value="">seleccione...</option>';
											   for($i=0;$i<$num;$i++){
												 $vec=explode('|',$fp_name[$i]);
												 if($vec[1]==$fila['codigo']){  
													 echo'<option value="'.$fp_name[$i].'" selected>'.$vec[0].'</option>';  
												 }else{
													 echo'<option value="'.$fp_name[$i].'">'.$vec[0].'</option>';  
												 }
											   }
									   echo'</select>
											<span class="errorMessage" id="errorformapago"></span>
									  </div>
								  </div>
																				  
								  <div class="da-button-row">
									 <input type="button" value="Cancelar" class="da-button gray left" name="btnCancelar" id="btnCancelar"/>
									 <input type="submit" value="Guardar" class="da-button green" name="btnGuardar" id="btnGuardar"/>
											  
									 <input type="hidden" name="var" id="var" value="'.$_GET['var'].'"/>
								  </div>
							  </form>
						  </div>
					  </div>';
			
			} else {
				//SI NO EXISTE EL USUARIO DADO EN LA BASE DE DATOS, VOLVEMOS A LA LISTA DE USUARIOS
				echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>Registro inexistente</div>";
				echo '<meta http-equiv="refresh" content="2; url=index.php?l=tr_formapago&var='.$_GET['var'].'&producto='.$_GET['producto'].'">';
			}
	}else{
		echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error."</div>";
	}
}

?>