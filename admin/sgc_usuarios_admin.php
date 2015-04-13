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
//echo $id_usuario_sesion.'<br/>'.$tipo_sesion.'<br/>'.$usuario_sesion;
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
                <!-- Container -->
                <div class="da-container clearfix">
                                	                	
                    <!-- Breadcrumbs -->
                    <div id="da-breadcrumb">
                        <ul>
                            <li><a href="?l=escritorio"><img src="images/icons/black/16/home.png" alt="Home" />Inicio</a></li>
                            <li class="active"><span>Usuarios</span></li>
                        </ul>
                    </div>
                    
                </div>
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
						
								//MOSTRAMOS EL FORM PARA CREAR NUEVO USUARIO
								if($tipo_sesion=='CRU' or $tipo_sesion=='ADM') {
									crear_nuevo_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
								} else {
									//SOLO EL USUARIO ADMIN PUEDE CREAR USUARIOS
									header('Location: index.php?l=usuarios_admin');
								}
							} else {
								//VEMOS SI NOS PASAN UN ID DE USUARIO
								if(isset($_GET['idusuario'])) {
						
									//VEO SI ME PASAN VARIABLE PARA CAMBIAR DE PASSWORD
									if(isset($_GET['cpass'])) {
										//MOSTRARMOS EL FORM PARA CAMBIAR DE PASSWORD
										cambiar_password($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
						
									} elseif(isset($_GET['editar'])) {
										//SI NO ME PASAN 'CPASS' NI 'ELIMINAR', MUESTRO EL FORM PARA EDITAR USUARIO
										editar_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
										
									} elseif(isset($_GET['reset'])){
										//FUNCION QUE PERMITE RESETEAR CONTRASE—A DEL USUARIO
										resetear_contrasenia_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
										
									} elseif(isset($_GET['darbaja'])){
										//FUNCION QUE PERMITE DAR DE BAJA AL USUARIO
										dar_baja_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
									}
								} else {
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE USUARIOS EXISTENTES
									mostrar_lista_usuarios($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
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

//FUNCION QUE PERMITE LISTAR LOS USUARIOS
function mostrar_lista_usuarios($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>
<link type="text/css" rel="stylesheet" href="plugins/jalerts/jquery.alerts.css"/>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="plugins/jalerts/jquery.alerts.js"></script>
<script type="text/javascript">
   $(function(){
	   $("a[href].accionef").click(function(e){
		   var valor = $(this).attr('id');
		   var vec = valor.split('|');
		   var id_usuario = vec[0];
		   var id_ef = vec[1];
		   var text = vec[2]; 		  
		   jConfirm("Esta seguro de "+text+" el usuario?", ""+text+" registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_usuario='+id_usuario+'&id_ef='+id_ef+'&text='+text+'&opcion=enabled_disabled_usuario';
						//alert(dataString);
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
		 setTimeout( "$(location).attr('href', 'index.php?l=usuarios_admin');",5000 );
	<?php }?>
	 
  });
</script>
<?php
	//echo $tipo_sesion.'<br/>'.$id_usuario_sesion;
	//SACAMOS LAS ENTIDADES FINANCIERAS EXISTENTES Y POSTERIOR ESTEN ACTIVADAS
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
	$resef = $conexion->query($selectEf,MYSQLI_STORE_RESULT);					
   while($regief = $resef->fetch_array(MYSQLI_ASSOC)){
		$tipo_user=md5($tipo_sesion);
		//echo $tipo_sesion;
		if($tipo_user==md5('CRU') or $tipo_user==md5('ADM')){
			  $selectUs="select
						  ef.id_ef,
						  ef.nombre as entidad,
						  ef.codigo,
						  efu.usuario,
						  su.id_usuario,
						  su.nombre as nombre_usuario,
						  su.email,
						  su.id_depto,
						  su.id_agencia,
						  case su.activado
							when 1 then 'activo'
							when 0 then 'inactivo'
						  end as activado,
						  ust.tipo,
						  ust.codigo,
						  dep.departamento,
						  ag.agencia 
						from
						  s_entidad_financiera as ef
						  inner join s_ef_usuario as efu on (efu.id_ef=ef.id_ef)
						  inner join s_usuario as su on (su.id_usuario=efu.id_usuario)
						  inner join s_usuario_tipo as ust on (ust.id_tipo=su.id_tipo)
						  left join s_departamento as dep on (dep.id_depto=su.id_depto)
						  left join s_agencia as ag on (ag.id_agencia=su.id_agencia)
						where
						  ef.id_ef='".$regief['id_ef']."' and (ust.codigo='LOG' or ust.codigo='REP') 
						order by
						  ef.nombre, ust.tipo, efu.usuario asc;";
		}else{
		  echo '<meta http-equiv="refresh" content="1; url=index.php?l=escritorio">' ;	
		}
		//echo $selectUs;
		$res = $conexion->query($selectUs,MYSQLI_STORE_RESULT);		  
		echo'<div class="da-panel collapsible">
		  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			  <ul class="action_user">
				  <li style="margin-right:6px;">
					 <a href="?l=usuarios_admin&crear=v" class="da-tooltip-s" title="Agregar nuevo usuario">
					 <img src="images/add_new_users.png" width="32" height="32"></a>
				  </li>
			  </ul>
		  </div>
	  </div>';
	  if($res->num_rows>0){$id='id="da-ex-datatable-numberpaging"';}else{$id='';}	
		echo'
		<div class="da-panel collapsible">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/list.png" alt="" />
					<b>Entidad Financiera: '.$regief['nombre'].'</b> - Listado de Usuarios
				</span>
			</div>
			<div class="da-panel-content">
				<table class="da-table" '.$id.'>
					<thead>
						<tr>
						    <th>Tipo usuario</th>
							<th>Usuario</th>
							<th>Nombre</th>
							<th>Email</th>
							<th>Departamento</th>
							<th>Agencia</th>
							<th>Estado</th>
							<th></th>
						</tr>
					</thead>
					<tbody>';
					  if($res->num_rows>0){
							while($regi = $res->fetch_array(MYSQLI_ASSOC)){
								echo'<tr ';
										 if($regi['activado']=='inactivo'){
											echo'style="background:#FD2F18; color:#ffffff;"'; 
										 }else{
											echo'';	 
										 }
								echo'>  <td>'.$regi['tipo'].'</td> 
										<td>'.$regi['usuario'].'</td>
										<td>'.$regi['nombre_usuario'].'</td>
										<td>'.$regi['email'].'</td>';
										echo'<td>';
											if($regi['codigo']!='REP'){
											  echo $regi['departamento'];
											}else{
												if(!empty($regi['departamento'])){ 
												  echo $regi['departamento'];
												}else {
												  echo'Todos'; 
												}
											}
								   echo'</td>';
								   echo'<td>';
										    if($regi['codigo']!='REP'){ 
												echo $regi['agencia'];
											}else{
												if(!empty($regi['agencia'])){
													echo $regi['agencia'];
												}else{ 
													echo 'Todos';
												}
											}
								   echo'</td>	
										<td>'.$regi['activado'].'</td>
										<td class="da-icon-column">';
										   echo'
										   <ul class="action_user">
											<li><a href="?l=usuarios_admin&idusuario='.base64_encode($regi['id_usuario']).'&id_ef_sesion='.base64_encode($id_ef_sesion).'&editar=v" class="edit da-tooltip-s" title="Editar"></a></li>
											<li><a href="?l=usuarios_admin&idusuario='.base64_encode($regi['id_usuario']).'&id_ef_sesion='.base64_encode($id_ef_sesion).'&cpass=v" class="privacidad da-tooltip-s" title="Cambiar Password"></a></li>';
											if($tipo_user==md5('CRU') or $tipo_user==md5('ADM')){
											   echo'<li><a href="?l=usuarios_admin&idusuario='.base64_encode($regi['id_usuario']).'&id_ef_sesion='.base64_encode($id_ef_sesion).'&reset=v" class="resetear da-tooltip-s" title="Resetear Password"></a></li>';
											   //echo'<li><a href="?l=usuarios_admin&idusuario='.base64_encode($regi['id_usuario']).'&id_ef_sesion='.base64_encode($id_ef_sesion).'&darbaja=v" class="darbaja da-tooltip-s" title="Dar baja"></a></li>';
											   if($regi['activado']=='inactivo'){
													echo'<li><a href="#" id="'.base64_encode($regi['id_usuario']).'|'.base64_encode($id_ef_sesion).'|activar" class="daralta da-tooltip-s accionef" title="Activar"></a></li>';
											   }else{
													echo'<li><a href="#" id="'.base64_encode($regi['id_usuario']).'|'.base64_encode($id_ef_sesion).'|desactivar" class="darbaja da-tooltip-s accionef" title="Desactivar"></a></li>';  
											   }
											}
											//echo'<li><a href="bloquear_desbloquear_usuario.php?idusuario='.base64_encode($regi['id_usuario']).'&op=lock" rel="facebox" class="darbaja da-tooltip-s" title="Dar Baja"></a></li>';
									  echo'</ul>';	
										echo'
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
function crear_nuevo_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>    
	<script type="text/javascript">
	  $(function(){
		$("#txtPassword").valid();
		//$("#txtPassNuevo").valid();
	  });
	</script>
<?php	
	$errFlag = false;
	$errArr['depar'] = '';
	$errArr['idusuario'] = '';
	$errArr['password'] = '';
	$errArr['password2'] = '';
	$errArr['email'] = '';
	$errArr['paginas'] = '';
	$errArr['fonoagencia'] = '';
	$errArr['tipousu'] = '';

	//VEMOS SI EL USUARIO YA HIZO CLICK EN GUARDAR
	if(isset($_POST['accionUsuarios'])) {

					
		if($errFlag) {
			//MOSTRAMOS EL FORMULARIO CON LOS ERRORES
			mostrar_crear_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
		} else {
			//GUARDAMOS EL NUEVO USUARIO EN LA BASE DE DATOS

			//IMPLEMENTAMOS SEGURIDAD
			$vec2=array();
									
			$vec2=explode('|',$_POST['txtTipousuario']);
			$id_tipo = $conexion->real_escape_string($vec2[0]);
			
			if($_POST['txtFonoAgencia']) $fono_agencia = $conexion->real_escape_string($_POST['txtFonoAgencia']);
			else $fono_agencia='';

			$especiales = array(" ");
			$reemplazos = array("");
			$usuario = $conexion->real_escape_string($_POST['txtIdusuario']);
			$usuario = str_replace($especiales, $reemplazos, $usuario);
			$usuario = strtolower($usuario);
            $email = $conexion->real_escape_string($_POST['txtEmail']);
			if(isset($_POST['txtNombre'])){
			   $nombre = $conexion->real_escape_string($_POST['txtNombre']);
			}else { 
			   $nombre = '';
			}
			if($vec2[1]=='REP'){
			   if(!empty($_POST['departamento'])){
					$depto_regional = $_POST['departamento'];
				}else{
					$depto_regional = 'null';
				}	
			}else{
			   $depto_regional = $_POST['departamento'];
			}
			//VERIFICAMOS SI ID_AGENCIA NO ESTA VACIO 
			if(!empty($_POST['id_agencia'])){
				$id_agencia="'".$_POST['id_agencia']."'";
			}else{
				$id_agencia='null';
				
			}            
			//generamos un idusuario unico encriptado
			$prefijo='@S#1$2013';
            $id_unico='';
            $id_unico=uniqid($prefijo,true);
			
			//encriptamos el password
			$encrip_pass=crypt_blowfish_bycarluys($_POST['txtPassword']);
			 
			$insert = "INSERT INTO s_usuario(id_usuario, usuario, password, nombre, email, id_tipo, id_depto, activado, id_agencia, fono_agencia, fecha_creacion) "
			."VALUES('".$id_unico."', '".$usuario."', '".$encrip_pass."', '".$nombre."', '".$email."', ".$id_tipo.", ".$depto_regional.", 1, ".$id_agencia.", '".$fono_agencia."', curdate())";
			//echo $insert;
			if($conexion->query($insert)===TRUE){$response=TRUE;}else{$response=FALSE;}
			
			//INSERTAMOS LA ENTIDAD FINANCIERA
			$prefijo2='@S#1$2013';
			$id_unico2='';
			$id_unico2=uniqid($prefijo2,true);
			$insert_ef = "INSERT INTO s_ef_usuario(id_eu, usuario, id_usuario, id_ef) VALUES('".$id_unico2."', '".$usuario."', '".$id_unico."', '".$_POST['identidadf']."');";
			//echo $insert_ef;
			if($conexion->query($insert_ef)===TRUE){$response=TRUE;}else{$response=FALSE;}
			
            //VERIFICAMOS SI HUBO ALGUN ERROR AL INGRESAR EN LA TABLA
			if($response){
				$mensaje="Se registro correctamente los datos del usuario ".$usuario;
				header('Location: index.php?l=usuarios_admin&op=1&msg='.$mensaje);
				//echo'<meta http-equiv="Refresh" content="0;url=index.php?l=usuarios&op=1&msg='.$mensaje.'">';
		    } else {
			    $mensaje="Hubo un error al ingresar los datos, consulte con su administrador ".$conexion->errno.": ". $conexion->error;
				header('Location: index.php?l=usuarios_admin&op=2&msg='.$mensaje);
		    }	 
			
		}
	} else {
		//MOSTRAMOS EL FORMULARIO PARA INGRESAR LOS DATOS
		mostrar_crear_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
	}
}

//VISUALIZAMOS EL FORMULARIO CREA USUARIO
function mostrar_crear_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr){
  ?>
    <style type="text/css">
       .loading-fac{
			background: #FFFFFF url(images/loading30x30.gif) top center no-repeat;
			height: 0px;
			margin: 10px 0;
			text-align: center;
			font-size: 90%;
			font-weight: bold;
			color: #0075AA;
		}
    </style>
	<script type="text/javascript">
       $(document).ready(function(e) {
           $('#btnCancelar').click(function(){   
			   $(location).prop('href','index.php?l=usuarios_admin');
		   });
		   
		   //SELECT DEPARTAMANTO - FUNCION QUE PERMITE BUSCAR LA AGENCIA DE UN
		   //DETERMINADO DEPARTAMENTO O REGION
		   $('#departamento').change(function(e) {
			   
			   var id_departamento = $(this).attr('value');
			   var identidadf = $('#identidadf').prop('value');
			   var variable = $('#txtTipousuario option:selected').prop('value');
			   var tipo_sesion = $('#tipo_sesion').prop('value');
			   var id_usuario_sesion = $('#id_usuario_sesion').prop('value');
			   
			   var vec = variable.split('|');
			   var tipousuario = vec[1];
			   //alert(tipousuario)
			   if(id_departamento!=''){
				     				   
				     if(tipousuario!='FAC'){
						if(tipousuario!='LOG'){ 
						   var dataString = 'id_departamento='+id_departamento+'&identidadf='+identidadf+'&opcion=buscar_agencia&required=f&tipousuario='+tipousuario;
						   $.ajax({
								 async: true,
								 cache: false,
								 type: "POST",
								 url: "buscar_registro.php",
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
									  //alert(datareturn);
									  $('#content-agency').fadeIn('slow');
									  $('#response-loading').html(datareturn);
										
								 }
						   });
						}else{
						   //VERIFICAMOS SI EXISTE ALGUN IMPLANTE ACTIVADO 
						   //EN ALGUN PRODUCTO DE LA ENTIDAD FINANCIERA
						   $.post("buscar_registro.php",
							  {id_ef:identidadf,opcion:"busca_implante"},
							  function(data, textStatus, jqXHR){
								 //alert(data);
								 if(data>0){
								   //SI HAY IMPLANTES OBLIGATORIO DEBE EXISTIR
								   //AGENCIAS EN DEPARTAMENTO
								   var dataString = 'id_departamento='+id_departamento+'&identidadf='+identidadf+'&tipo_sesion='+tipo_sesion+'&id_usuario_sesion='+id_usuario_sesion+'&opcion=buscar_agencia&required=v';
								   //alert(dataString);
								   $.ajax({
										 async: true,
										 cache: false,
										 type: "POST",
										 url: "buscar_registro.php",
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
											  //alert(datareturn);
											  $('#content-agency').fadeIn('slow');
											  $('#response-loading').html(datareturn);
												
										 }
								   });   	 
								 }else{
								   //SI NO HAY IMPLANTES PUEDE O NO PUEDE HABER AGENCIAS
								   var dataString = 'id_departamento='+id_departamento+'&identidadf='+identidadf+'&opcion=buscar_agencia&required=f&tipousuario='+tipousuario;
								   $.ajax({
										 async: true,
										 cache: false,
										 type: "POST",
										 url: "buscar_registro.php",
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
											  //alert(datareturn);
											  $('#content-agency').fadeIn('slow');
											  $('#response-loading').html(datareturn);
												
										 }
								   });   
								 }	
							  }
						   );
						}
					 }
					 //alert(tipousuario)
					 
			   }else{
				  $('#content-agency').fadeOut('slow');
			   }
			   e.preventDefault();
		   });
		   		   
		   //VISUALIZAMOS EL SELECT TIPO DE REPORTE
		   $('#txtTipousuario').change(function(e){
			   var tipousuario = $(this).attr('value');
			   if(tipousuario!=''){
				   $("#departamento").removeAttr("disabled");
				   $('#txtIdusuario').removeAttr('disabled');
				   $('#btnUsuario').removeAttr('disabled');
				   var vec=tipousuario.split('|');
				   if(vec[1]=='REP'){
					   $('#content-agency').fadeOut('slow');
					   $('#departamento option').each(function(index) {
							//var option = $(this).text().toLowerCase();
							var option = $(this).prop('value');
							if (option == '') {
								$(this).prop('selected', true).text('Todos');
							}
					   });
				   }else{
					   $('#departamento option').each(function(index) {
							//var option = $(this).text().toLowerCase();
							var option = $(this).prop('value');
							if (option == '') {
								$(this).prop('selected', true).text('Seleccione...');
							}
					   });
				   }
			   }else{
				   $('#content-agency').fadeOut('slow');
				   $('#departamento option[value=""]').prop('selected',true);
				   $("#departamento").attr("disabled", true);
				   $('#btnUsuario').attr('disabled', true);
				   $('#txtIdusuario').attr('disabled', true);
			   }
			   e.preventDefault();
		   });
		   
		   //CONVERTIMOS A MINUSCULAS
		   $('#txtIdusuario').keyup(function() {
			  $(this).val($(this).val().toLowerCase());
		   });
		   
		   //VALIDAR CAMPOS
		   $('#frmUsuario').submit(function(e){
			  //alert('hola');
			  var tipousuario = $('#txtTipousuario option:selected').prop('value');
			  var tiporeporte = $('#tipo-reporte option:selected').prop('value');
			  var departamento = $('#departamento option:selected').prop('value');
			  var usuario = $('#txtIdusuario').prop('value');
			  var nombre = $('#txtNombre').prop('value');
			  var fonoagencia = $('#txtFonoAgencia').prop('value');
			  var email = $('#txtEmail').prop('value');
			  var sum=0;
			 
			  var chek=0;
			  $(this).find('.requerid').each(function(){
				   if(tipousuario!=''){
					   $('#errortipousuario').hide('slow');
					   var vec=tipousuario.split('|');
					   //alert(vec[1]);
					   if(vec[1]=='LOG'){
						   //alert(tiporeporte);
						   if(tiporeporte!=''){
							   $('#errortiporeport').hide('slow');
						   }else{
							   sum++;
							   $('#errortiporeport').show('slow');
							   $('#errortiporeport').html('seleccione tipo de reporte');
						   }   
					   } 
				   }else{
					   sum++;
					   $('#errortipousuario').show('slow');
					   $('#errortipousuario').html('seleccione tipo de usuario');
				   }
				   
				   
				   
				   if(departamento!=''){
					   $('#errordepartamento').hide('slow');
				   }else{
					   if(vec[1]!='REP'){
						   sum++;
						   $('#errordepartamento').show('slow');
						   $('#errordepartamento').html('seleccione departamento');
					   }
				   }
				   
				   if(usuario!=''){
					   $('#error_usuario').hide('slow');
				   }else{
					   sum++; 
					   $('#error_usuario').show('slow');
					   $('#error_usuario').html('ingrese el usuario');
				   }
				   
				   if(nombre!=''){
					   if(nombre.match(/^[a-zA-Z·ÈÌÛ˙‡ËÏÚ˘¿»Ã“Ÿ¡…Õ”⁄Ò—¸‹\s\D]+$/)){
						    $('#errornombre').hide('slow');
					   }else{
						    sum++;
							$('#errornombre').show('slow');
							$('#errornombre').html('ingrese solo caracteres');
					   }
				   }
				   
				   if(fonoagencia!=''){
					   if(fonoagencia.match(/^[0-9]+$/)){
						  $('#errorfonoagencia').hide('slow');
					   }else{
						  sum++;
						  $('#errorfonoagencia').show('slow');
						  $('#errorfonoagencia').html('ingrese solo numeros'); 
					   }
				   }
				   
				   if(email!=''){
					   if(email.match(/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.-][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/)){
						   $('#erroremail').hide('slow');
					   }else{
						   sum++;
						   $('#erroremail').show('slow');
						   $('#erroremail').html('ingrese correo electronico');
					   }
				   }else{
					   sum++;
					   $('#erroremail').show('slow');
					   $('#erroremail').html('ingrese correo electronico');
				   }
				   
				  
  
			  });
			  if(sum==0){
				  
			  }else{
			      e.preventDefault();
			  }
		   });
		   
		   /*VERIFICAMOS SI EXISTE EL USUARIO*/
		   $('#frmUsuario #txtIdusuario').blur(function(e){
			     var usuario = $("#txtIdusuario").prop('value');
			     var variable = $('#txtTipousuario option:selected').prop('value');
			     var vec = variable.split('|');
			     var tipousuario = vec[1];
			     var identidadf = $('#identidadf').prop('value');
				 if(usuario!=''){
					 var dataString = 'usuario='+ usuario +'&id_ef='+ identidadf+ '&tipousuario='+ tipousuario;
					 //alert(dataString);
					 $.ajax({
						   async:true,
						   cache:false,
						   type: "POST",
						   url: "buscar_idusuario.php",
						   data: dataString,
						   success: function(data) {
								  //alert(data);
								  var results = data.split("|");
								  if(results[0]==1){
									  //$("#error_usuario").remove().hide().fadeIn('slow');
									  $("#ver_msg").html("<div id='ver_msg'><div class='errorMessage' id='error_usuario'></div></div>");
									  $("#txtIdusuario").css({'border' : '1px solid #7F9DB9'});
									  $("#btnUsuario").removeAttr("disabled");
									  return true;
								  }else if(results[0]==2){
									  $("#ver_msg").html("<div id='ver_msg'><div class='errorMessage' id='error_usuario'></div></div>");
									  $("#error_usuario").html("El usuario: "+results[1]+" ya existe ingrese otro usuario o seleccione otra entidad financiera").fadeIn("slow");
									  $("#txtIdusuario").css({'border' : '1px solid #d44d24'}).focus();
									  $("#btnUsuario").attr("disabled", true);
									  e.stopPropagation();
								  }
								 
						   }
					  });
				 }else{
					   $("#error_usuario").html("Ingrese nombre de usuario").fadeIn("slow");
					   $("#txtIdusuario").css({'border' : '1px solid #d44d24'}).focus();
					   $("#btnUsuario").attr("disabled", true);
					   e.stopPropagation();
				 }
		   });      
       });
    </script>
 
 <?php
  //VARIABLES DE INICIO
	$admiope='';
	$facul='';
	$oficred='';
	$creaus='';
	$respso='';
	$repeco='';
	$oficredrep='';
	//RECORDAMOS LOS VALORES INGRESADOS ANTERIORMENTE
	if(isset($_POST['txtTipousuario'])) $txtTipousuario = $_POST['txtTipousuario']; else $txtTipousuario = '';
	if(isset($_POST['departamento'])) $departamento = $_POST['departamento']; else $departamento = '';
	if(isset($_POST['txtIdusuario'])) $txtIdusuario = $_POST['txtIdusuario']; else $txtIdusuario = '';
	if(isset($_POST['txtNombre'])) $txtNombre = $_POST['txtNombre']; else $txtNombre = '';
	if(isset($_POST['txtEmail'])) $txtEmail = $_POST['txtEmail']; else $txtEmail = '';
	if(isset($_POST['txtFonoAgencia'])) $txtFonoAgencia = $_POST['txtFonoAgencia']; else $txtFonoAgencia = '';
	
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
	$rei1 = $conexion->query($select1,MYSQLI_STORE_RESULT);				  
	$regi1 = $rei1->fetch_array(MYSQLI_ASSOC);				  
	$rei1->free();
	
	 echo'<div class="da-panel collapsible">
		  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			  <ul class="action_user">
				  <li style="margin-right:6px;">
					 <a href="?l=usuarios_admin" class="da-tooltip-s" title="Volver atras">
					 <img src="images/retornar.png" width="32" height="32"></a>
				  </li>
			  </ul>
		  </div>
	  </div>';
	
	
  echo'<div class="da-panel" style="width:650px;">
		<div class="da-panel-header">
			<span class="da-panel-title">
				<img src="images/icons/black/16/pencil.png" alt="" />
				Nuevo Usuario
			</span>
		</div>
		<div class="da-panel-content">
			<form class="da-form" name="frmUsuario" action="" method="post" id="frmUsuario">
				<div class="da-form-row">
					 <label style="text-align:right; margin-right:15px;"><b>Entidad Financiera</b></label>
					 '.$regi1['nombre'].'
					 <input type="hidden" name="identidadf" id="identidadf" value="'.$regi1['id_ef'].'"/>					 
				</div>
				<div class="da-form-row">
					<label style="text-align:right;"><b>Tipo de usuario</b></label>
					<div class="da-form-item small">';
						$selectTi="select 
										id_tipo, tipo, codigo
									from
										s_usuario_tipo
									where
										codigo != 'ROOT'
										and codigo != 'ADM'
										and codigo != 'OPR'
										and codigo != 'FAC'
										and codigo != 'CRU'
										and codigo != 'IMP';";
						$rstip = $conexion->query($selectTi,MYSQLI_STORE_RESULT);
						echo'<select name="txtTipousuario" id="txtTipousuario" class="requerid">';
							  echo'<option value="">Seleccionar...</option>';
							  while($filatip = $rstip->fetch_array(MYSQLI_ASSOC)){
								 if($filatip['id_tipo']==$txtTipousuario){
									echo'<option value="'.$filatip['id_tipo'].'|'.$filatip['codigo'].'" selected>'.$filatip['tipo'].'</option>';
								 }else{
									echo'<option value="'.$filatip['id_tipo'].'|'.$filatip['codigo'].'">'.$filatip['tipo'].'</option>';  
								 }  
							  }
							  $rstip->free();
						echo'</select>';
						
						echo'<span class="errorMessage" id="errortipousuario"></span>
					</div>
				</div>
				<div class="da-form-row">
					<label style="text-align:right;"><b>Departamento</b></label>
					<div class="da-form-item small">';
						$selectDep="select
									   id_depto,
									   departamento,
									   codigo 
									from
									  s_departamento
									where
									  tipo_dp=1;";
						$rsdep = $conexion->query($selectDep,MYSQLI_STORE_RESULT);
						echo'<select name="departamento" id="departamento" class="requerid" disabled>';
							  echo'<option value="">Seleccionar...</option>';
							  while($filadep = $rsdep->fetch_array(MYSQLI_ASSOC)){
								 if($filadep['id_depto']==$departamento){
									echo'<option value="'.$filadep['id_depto'].'" selected>'.$filadep['departamento'].'</option>';
								 }else{
									echo'<option value="'.$filadep['id_depto'].'">'.$filadep['departamento'].'</option>';  
								 }  
							  }
						echo'</select>';
						
				     echo'<span class="errorMessage" id="errordepartamento"></span>
					</div>
				</div>
				<div class="da-form-row" style="display: none;" id="content-agency">
				  <label style="text-align:right;"><b>Agencia</b></label>
				  <div class="da-form-item small">
				    <span id="response-loading" class="loading-fac"></span>
				  </div>	
				</div>  
				<div class="da-form-row">
					<label style="text-align:right;"><b>Usuario</b></label>
					<div class="da-form-item large">
						<input class="textbox requerid" type="text" name="txtIdusuario" id="txtIdusuario" style="width: 200px;" maxlength="15" value="'.$txtIdusuario.'" autocomplete="off" disabled/>
		                <span class="formNote" style="text-align:rigth; width:430px;">No use may&uacute;sculas ni espacios en blanco.</span>
		                <div id="ver_msg"><div class="errorMessage" id="error_usuario"></div></div>
					</div>
					
				</div>
				<div class="da-form-row">
					<label style="text-align:right;"><b>contrase&ntilde;a</b></label>
					<div class="da-form-item large">
						<input class="textbox" type="password" name="txtPassword" id="txtPassword" style="width: 200px;" maxlength="15"/>
						<span class="formNote" style="text-align:right; width:400px;">M&aacute;x. 15 caracteres M&iacute;n. 8 caracteres</span> 
						<div class="password-meter" style="width:199px;">
							<div class="password-meter-message">&nbsp;</div>
							<div class="password-meter-bg">
								<div class="password-meter-bar"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="da-form-row">
					<label style="text-align:right;"><b>Confirmar contrase&ntilde;a</b></label>
					<div class="da-form-item large">
						<input class="textbox" type="password" name="txtPassword2" id="txtPassword2" style="width: 200px;" maxlength="15"/>
						<div id="ver_msg2"><div class="errorMessage" id="error_contrasenia_igual"></div></div>
					</div>
				</div>
				<div class="da-form-row">
					<label style="text-align:right;"><b>Nombre completo</b></label>
					<div class="da-form-item large">
						<input class="textbox requerid" type="text" name="txtNombre" id="txtNombre" style="width: 250px;" maxlength="50" value="'.$txtNombre.'" autocomplete="off"/>
						<span class="errorMessage" id="errornombre"></span>
					</div>
				</div>
				<div class="da-form-row">
					<label style="text-align:right;"><b>Telefono de la agencia</b></label>
					<div class="da-form-item large">
						<input class="textbox requerid" type="text" name="txtFonoAgencia" id="txtFonoAgencia" style="width: 250px;" value="'.$txtFonoAgencia.'"/>
						<span class="errorMessage" id="errorfonoagencia"></span>
					</div>
				</div>
				<div class="da-form-row">
					<label style="text-align:right;"><b>Correo electr&oacute;nico</b></label>
					<div class="da-form-item large">
						<input class="textbox requerid" type="text" name="txtEmail" id="txtEmail" style="width: 250px;" maxlength="50" value="'.$txtEmail.'" autocomplete="off"/>
						<span class="errorMessage" id="erroremail"></span>
					</div>
				</div>
				<div class="da-button-row">
					<input type="button" value="Cancelar" class="da-button gray left" id="btnCancelar"/>
					<input type="submit" value="Guardar" class="da-button green" name="btnUsuario" id="btnUsuario" disabled/>
					<input type="hidden" id="identidadf" value="'.$regi1['id_ef'].'"/>
					<input type="hidden" id="tipo_sesion" value="'.$_SESSION['tipo_sesion'].'"/>
					<input type="hidden" id="id_usuario_sesion" value="'.$_SESSION['id_usuario_sesion'].'"/>
					<input type="hidden" name="accionUsuarios" value="checkdatos"/>
				</div>
			</form>
		</div>
	</div>';
}

//FUNCION PARA EDITAR UN USUARIO
function editar_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion) {

	$errFlag = false;
	$errArr['email'] = '';
	$errArr['paginas'] = '';
	$errArr['depar'] = '';
	$errArr['fonoagencia'] = '';
	$errArr['tipousu'] = '';

	$idusuario = base64_decode($_GET['idusuario']);
	//$idusuario = strtolower($idusuario);

	//SI ESTAMOS LOGUEADOS COMO 'ROOT', PODEMOS EDITAR CUALQUIER USUARIO
	if($tipo_sesion=='CRU' or $tipo_sesion=='ADM') {
		if(isset($_POST['accionEditar'])) {
            
			if($errFlag) {
				//MOSTRAMOS EL FORMULARIO CON LOS ERRORES
				mostrar_editar_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
			} else {
				//GUARDAMOS LOS CAMBIOS EN LA BASE DE DATOS

				//IMPLEMENTAMOS SEGURIDAD
				
				$vec2=array();
				
				
				$vec2=explode('|',$_POST['txtTipousuario']);
				$id_tipo = $conexion->real_escape_string($vec2[0]);
				$tipouser_text=$vec2[1];
				
				if(isset($_POST['txtNombre'])) $nombre = $conexion->real_escape_string($_POST['txtNombre']);
				else $nombre = '';
				if(isset($_POST['txtEmail'])) $email = $conexion->real_escape_string($_POST['txtEmail']);
				else $email = '';
				
				if($tipouser_text!='REP'){
					 $depto_regional = $_POST['departamento'];
				}else{
					if(!empty($_POST['departamento'])){
						$depto_regional = $_POST['departamento'];
					}else{
						$depto_regional = 'null';
					}
				}
				//VERIFICAMOS SI ID_AGENCIA NO ESTA VACIO 
				if(!empty($_POST['id_agencia'])){
					$id_agencia="'".$_POST['id_agencia']."'";
				}else{
					$id_agencia='null';
					
				}    
				$fono_agencia = $conexion->real_escape_string($_POST['txtFonoAgencia']);

				$update = "UPDATE s_usuario as su 
				           inner join s_ef_usuario as efu on (efu.id_usuario=su.id_usuario)
						   SET su.nombre='".$nombre."',";
				$update .= " su.id_tipo=".$id_tipo.", su.id_depto=".$depto_regional.", su.id_agencia=".$id_agencia.",";
				$update .= " su.email='".$email."', su.fono_agencia='".$fono_agencia."' "
				."WHERE su.id_usuario='".$idusuario."' and efu.id_ef='".$_POST['id_ef']."';";
		
                 
				//VERIFICAMOS SI HUBO ALGUN ERROR AL INGRESAR EN LA TABLA
			    if($conexion->query($update)===TRUE){ 
					$mensaje='Se edito correctamente los datos del usuario '.$_POST['usuario'];
					header('Location: index.php?l=usuarios_admin&op=1&msg='.$mensaje);
				}else{
				    $mensaje="Hubo un error al actualizar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " .$conexion->error;
				    header('Location: index.php?l=usuarios_admin&op=2&msg='.$mensaje);
				}
				
			}

		} else {
			//MOSTRAMOS EL FORMULARIO PARA EDITAR AL USUARIO
			mostrar_editar_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
		}
	} else {
		echo '<meta http-equiv="refresh" content="1; url=index.php?l=escritorio">' ;
	}
}

//VISUALIZAMOS EL FORMULARIO PARA EDITAR EL USUARIO
function mostrar_editar_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr){
 ?>
    <style type="text/css">
       .loading-fac{
			background: #FFFFFF url(images/loading30x30.gif) top center no-repeat;
			height: 0px;
			margin: 10px 0;
			text-align: center;
			font-size: 90%;
			font-weight: bold;
			color: #0075AA;
		}
    </style>
	<script type="text/javascript">
       $(document).ready(function(e) {
		   $('#btnCancelar').click(function(){   
			   $(location).prop('href','index.php?l=usuarios_admin');
		   });
		   
		   //SELECT DEPARTAMANTO - FUNCION QUE PERMITE BUSCAR LA AGENCIA DE UN
		   //DETERMINADO DEPARTAMENTO O REGION
		   $('#departamento').change(function(e) {
			   
			   var id_departamento = $(this).attr('value');
			   var identidadf = $('#id_ef').prop('value');
			   var variable = $('#txtTipousuario option:selected').prop('value');
			   var tipo_sesion = $('#tipo_sesion').prop('value');
			   var id_usuario_sesion = $('#idusuariosesion').prop('value');
			   //alert(id_departamento);
			   var vec = variable.split('|');
			   var tipousuario = vec[1];
			   if(id_departamento!=''){
				     if(tipousuario!='FAC'){
						if(tipousuario!='LOG'){ 
						   var dataString = 'id_departamento='+id_departamento+'&identidadf='+identidadf+'&opcion=buscar_agencia&required=f&tipousuario='+tipousuario;
						   $.ajax({
								 async: true,
								 cache: false,
								 type: "POST",
								 url: "buscar_registro.php",
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
									  //alert(datareturn);
									  $('#content-agency').fadeIn('slow');
									  $('#response-loading').html(datareturn);
										
								 }
						   });   
						}else{
						   //VERIFICAMOS SI EXISTE ALGUN IMPLANTE ACTIVADO 
						   //EN ALGUN PRODUCTO DE LA ENTIDAD FINANCIERA
						   $.post("buscar_registro.php",
							  {id_ef:identidadf,opcion:"busca_implante"},
							  function(data, textStatus, jqXHR){
								 //alert(data);
								 if(data>0){
								   //SI HAY IMPLANTES OBLIGATORIO DEBE EXISTIR
								   //AGENCIAS EN DEPARTAMENTO
								   var dataString = 'id_departamento='+id_departamento+'&identidadf='+identidadf+'&tipo_sesion='+tipo_sesion+'&id_usuario_sesion='+id_usuario_sesion+'&opcion=buscar_agencia&required=v';
								   //alert(dataString);
								   $.ajax({
										 async: true,
										 cache: false,
										 type: "POST",
										 url: "buscar_registro.php",
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
											  //alert(datareturn);
											  $('#content-agency').fadeIn('slow');
											  $('#response-loading').html(datareturn);
												
										 }
								   });   	 
								 }else{
									 //SI NO HAY IMPLANTES PUEDE O NO PUEDE HABER AGENCIAS
									 var dataString = 'id_departamento='+id_departamento+'&identidadf='+identidadf+'&opcion=buscar_agencia&required=f&tipousuario='+tipousuario;
									 $.ajax({
										   async: true,
										   cache: false,
										   type: "POST",
										   url: "buscar_registro.php",
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
												//alert(datareturn);
												$('#content-agency').fadeIn('slow');
												$('#response-loading').html(datareturn);
												  
										   }
									 });   
								 }	
							  }
						   );
						}
					 }
			   }else{
				  $('#id_agencia option[value=""]').prop('selected',true); 
				  $('#content-agency').fadeOut('slow');
			   }
			   e.preventDefault();
		   });
		   
		   //VISUALIZAMOS EL SELECT TIPO DE REPORTE
		   $('#txtTipousuario').change(function(e){
			   var tipousuario = $(this).attr('value');
			   if(tipousuario!=''){
				   $("#departamento").removeAttr("disabled");
				   $('#txtIdusuario').removeAttr('disabled');
				   $('#btnUsuario').removeAttr('disabled');
				   var vec=tipousuario.split('|');
				   if(vec[1]=='REP'){
					   $('#content-agency').fadeOut('slow');
					   $('#departamento option').each(function(index) {
							//var option = $(this).text().toLowerCase();
							var option = $(this).prop('value');
							if (option == '') {
								$(this).prop('selected', true).text('Todos');
							}
					   });
				   }else{
					   $('#departamento option').each(function(index) {
							//var option = $(this).text().toLowerCase();
							var option = $(this).prop('value');
							if (option == '') {
								$(this).prop('selected', true).text('Seleccione...');
							}
					   });
				   }
			   }else{
				   $('#content-agency').fadeOut('slow');
				   $('#departamento option[value=""]').prop('selected',true);
				   $("#departamento").attr("disabled", true);
				   $('#btnUsuario').attr('disabled', true);
				   $('#txtIdusuario').attr('disabled', true);
			   }
			   e.preventDefault();
		   });
		   
		   //VALIDAR CAMPOS
		   $('#frmUsuario').submit(function(e){
			  //alert('hola');
			  var tipousuario = $('#txtTipousuario option:selected').prop('value');
			  var tiporeporte = $('#tipo-reporte option:selected').prop('value');
			  var departamento = $('#departamento option:selected').prop('value');
			 
			  var nombre = $('#txtNombre').prop('value');
			  var fonoagencia = $('#txtFonoAgencia').prop('value');
			  var email = $('#txtEmail').prop('value');
			  var sum=0;
			  
			  var chek=0;
			  $(this).find('.requerid').each(function(){
				   if(tipousuario!=''){
					   $('#errortipousuario').hide('slow');
					   var vec=tipousuario.split('|');
					   //alert(vec[1]);
					   if(vec[1]=='LOG'){
						   //alert(tiporeporte);
						   if(tiporeporte!=''){
							   $('#errortiporeport').hide('slow');
						   }else{
							   sum++;
							   $('#errortiporeport').show('slow');
							   $('#errortiporeport').html('seleccione tipo de reporte');
						   }   
					   } 
				   }else{
					   sum++;
					   $('#errortipousuario').show('slow');
					   $('#errortipousuario').html('seleccione tipo de usuario');
				   }
				   
				   
				   
				   if(departamento!=''){
					   $('#errordepartamento').hide('slow');
				   }else{
					   if(vec[1]!='REP'){
						   sum++;
						   $('#errordepartamento').show('slow');
						   $('#errordepartamento').html('seleccione departamento');
					   }
				   }
				   				   				  
				   if(nombre!=''){
					   if(nombre.match(/^[a-zA-Z·ÈÌÛ˙‡ËÏÚ˘¿»Ã“Ÿ¡…Õ”⁄Ò—¸‹\s\D]+$/)){
						    $('#errornombre').hide('slow');
					   }else{
						    sum++;
							$('#errornombre').show('slow');
							$('#errornombre').html('ingrese solo caracteres');
					   }
				   }else{
					    sum++;
							$('#errornombre').show('slow');
							$('#errornombre').html('ingrese texto');
					 }
				    
				   if(fonoagencia!=''){
					   if(fonoagencia.match(/^[0-9]+$/)){
						  $('#errorfonoagencia').hide('slow');
					   }else{
						  sum++;
						  $('#errorfonoagencia').show('slow');
						  $('#errorfonoagencia').html('ingrese solo numeros'); 
					   }
				   }
				   
				   if(email!=''){
					   if(email.match(/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.-][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/)){
						   $('#erroremail').hide('slow');
					   }else{
						   sum++;
						   $('#erroremail').show('slow');
						   $('#erroremail').html('ingrese correo electronico');
					   }
				   }else{
					   sum++;
					   $('#erroremail').show('slow');
					   $('#erroremail').html('ingrese correo electronico');
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
	
	//SEGURIDAD
	$admiope='';
	$facul='';
	$oficred='';
	$creaus='';
	$respso='';
	$repeco='';
	$oficredrep='';
	$idusuario = base64_decode($_GET['idusuario']);

	//SACAMOS LOS DATOS DE LA BASE DE DATOS
	$select = "select 
					su.id_tipo,
					su.id_depto,
					su.usuario,
					su.nombre,
					su.fono_agencia,
					su.email,
					su.id_agencia,
					sup.pagina,
					sut.codigo,
					efu.id_ef,
					sef.nombre as name_entidad  
				from
					s_usuario as su
						left join
					s_usuario_permiso as sup ON (sup.id_usuario = su.id_usuario)
					    inner join 
				    s_usuario_tipo as sut on (sut.id_tipo = su.id_tipo)
					    inner join
				    s_ef_usuario as efu ON (efu.id_usuario = su.id_usuario)
					    inner join
					s_entidad_financiera as sef on (sef.id_ef = efu.id_ef) 	
				where
					su.id_usuario = '".$idusuario."' and efu.id_ef='".base64_decode($_GET['id_ef_sesion'])."'
				limit 0 , 1;";
	//echo $select;
	$rs = $conexion->query($select, MYSQLI_STORE_RESULT);
	$num = $rs->num_rows;
	
	//SI EXISTE EL USUARIO DADO EN LA BASE DE DATOS, LO EDITAMOS
	if($num>0) {

		$fila = $rs->fetch_array(MYSQLI_ASSOC);
        $rs->free();
		//RECORDAMOS LOS VALORES INGRESADOS ANTERIORMENTE
		if(isset($_POST['txtTipousuario'])) $txtTipousuario = $_POST['txtTipousuario'];else $txtTipousuario = $fila['id_tipo'];
		if(isset($_POST['departamento'])) $departamento = $_POST['departamento']; else $departamento = $fila['id_depto'];
		if(isset($_POST['txtNombre'])) $txtNombre = $_POST['txtNombre']; else $txtNombre = $fila['nombre'];
		if(isset($_POST['txtEmail'])) $txtEmail = $_POST['txtEmail']; else $txtEmail = $fila['email'];
		if(isset($_POST['txtFonoAgencia'])) $txtFonoAgencia = $_POST['txtFonoAgencia']; else $txtFonoAgencia = $fila['fono_agencia'];
		if(isset($_POST['id_agencia'])) $id_agencia = $_POST['id_agencia']; else $id_agencia = $fila['id_agencia'];
		
	   echo'<div class="da-panel collapsible">
		<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			<ul class="action_user">
				<li style="margin-right:6px;">
				   <a href="?l=usuarios_admin" class="da-tooltip-s" title="Volver atras">
				   <img src="images/retornar.png" width="32" height="32"></a>
				</li>
			</ul>
		</div>
	</div>';
	
echo'<div class="da-panel" style="width:650px;">
		<div class="da-panel-header">
			<span class="da-panel-title">
				<img src="images/icons/black/16/pencil.png" alt="" />
				Editar Usuario
			</span>
		</div>
		<div class="da-panel-content">
			<form class="da-form" name="frmUsuario" action="" method="post" id="frmUsuario">';
		  echo'<div class="da-form-row">
					 <label style="text-align:right; margin-right:15px;"><b>Entidad Financiera</b></label>
					 '.$fila['name_entidad'].'
					 <input type="hidden" name="id_ef"  id="id_ef" value="'.$fila['id_ef'].'"/>		 
				</div>
			   <div class="da-form-row">
						<label style="text-align:right;"><b>Tipo de usuario</b></label>
						<div class="da-form-item small">';
							$selectTi="select 
											id_tipo, tipo, codigo
										from
											s_usuario_tipo
										where
											codigo != 'ROOT'
												and codigo != 'ADM'
												and codigo != 'OPR'
												and codigo != 'FAC'
												and codigo != 'CRU'
												and codigo != 'IMP';"; 					
							$rstip = $conexion->query($selectTi,MYSQLI_STORE_RESULT);
							echo'<select name="txtTipousuario" id="txtTipousuario" class="requerid">';
								  echo'<option value="">Seleccionar...</option>';
								  while($filatip = $rstip->fetch_array(MYSQLI_ASSOC)){
									 if($filatip['id_tipo']==$txtTipousuario){
										echo'<option value="'.$filatip['id_tipo'].'|'.$filatip['codigo'].'" selected>'.$filatip['tipo'].'</option>';
									 }else{
										echo'<option value="'.$filatip['id_tipo'].'|'.$filatip['codigo'].'">'.$filatip['tipo'].'</option>';  
									 }  
								  }
								  $rstip->free();
							echo'</select>';
							
							echo'<span class="errorMessage" id="errortipousuario"></span>
						</div>
					</div>
					<div class="da-form-row">
						<label style="text-align:right;"><b>Departamento</b></label>
						<div class="da-form-item small">';
							$selectDep="select
									   id_depto,
									   departamento,
									   codigo 
									from
									  s_departamento
									where
									  tipo_dp=1;";
						$rsdep = $conexion->query($selectDep,MYSQLI_STORE_RESULT);
						echo'<select name="departamento" id="departamento" class="requerid">';
						      if($fila['codigo']!='REP')
							      echo'<option value="">Seleccionar...</option>';
							    else
							      echo'<option value="">Todos</option>';	  
							  while($filadep = $rsdep->fetch_array(MYSQLI_ASSOC)){
								 if($filadep['id_depto']==$departamento){
									echo'<option value="'.$filadep['id_depto'].'" selected>'.$filadep['departamento'].'</option>';
								 }else{
									echo'<option value="'.$filadep['id_depto'].'">'.$filadep['departamento'].'</option>';  
								 }  
							  }
							  $rsdep->free();
						echo'</select>';
					   echo'<span class="errorMessage" id="errordepartamento"></span>
						</div>
					</div>';
					   if(!empty($departamento)){
						 echo'<div class="da-form-row" id="content-agency">
								<label style="text-align:right;"><b>Agencia</b></label>
								<div class="da-form-item small">
								  <span id="response-loading" class="loading-fac">';
									 $selectAg="select
												 id_agencia,
												 codigo,
												 agencia,
												 id_depto,
												 id_ef
												from
												 s_agencia
												where
												  (id_depto=".$departamento." or id_depto is null) and id_ef='".$fila['id_ef']."'
												order by
												  id_agencia asc;";
									 //echo $selectAg;				
									 if($resag = $conexion->query($selectAg,MYSQLI_STORE_RESULT)){
											 $numag = $resag->num_rows;
											 //echo $numag;
											 if($numag>0){
												  echo'<select name="id_agencia" id="id_agencia" style="width:250px; font-size:12px;">';
												         if($fila['codigo']!='REP')
															  echo'<option value="">Seleccionar...</option>';
															else
															  echo'<option value="">Todos</option>';
														  while($regiag = $resag->fetch_array(MYSQLI_ASSOC)){
															  if($regiag['id_agencia']==$id_agencia){
																 echo'<option value="'.$regiag['id_agencia'].'" selected>'.$regiag['agencia'].'</option>';	
															  }else{
																 echo'<option value="'.$regiag['id_agencia'].'">'.$regiag['agencia'].'</option>';
															  }
														  }
														  $resag->free();
												  echo'</select>';	
											 }else{
												  
												 $select="select
															id_agencia,
															agencia
														  from
															s_agencia
														  where
															id_agencia='';";
												 if($res = $conexion->query($select,MYSQLI_STORE_RESULT)){
														 if($numag>0){$var='';}else{$var='<option value="">Ninguno</option>';}
														 echo'<select name="id_agencia" id="id_agencia" style="width:250px; font-size:12px;">';
																 echo $var;
																 while($regi = $res->fetch_array(MYSQLI_ASSOC)){  
																	echo'<option value="'.$regi['id_agencia'].'">'.$regi['agencia'].'</option>';
																 }
																 $res->free();
														 echo'</select>';
												 }else{
													 echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
															Error en la consulta: "."\n ".$conexion->errno .": ".$conexion->error
														."</div>";
												 }
											 }			
									 }else{
										 echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>
												Error en la consulta: ".$conexion->errno.": ".$conexion->error
											."</div>";
									 }
							 echo'</span>
								</div>	
							  </div>'; 
	                   }else{
						   echo'<div class="da-form-row" id="content-agency" style="display:none">';
								 echo'<label style="text-align:right;"><b><span lang="es">Agencia</span></b></label>
									  <div class="da-form-item small">
										<span id="response-loading" class="loading-fac"></span>
									  </div>	
								 </div>'; 
					   }
			   echo'<div class="da-form-row">
						<label style="text-align:right;"><b>Usuario</b></label>
						<div class="da-form-item large">
							<i>'.$fila['usuario'].'</i>
							<input type="hidden" name="usuario" value="'.$fila['usuario'].'"/>
						</div>
					</div>';
			  
		   echo'<div class="da-form-row">
					<label style="text-align:right;"><b>Nombre completo</b></label>
					<div class="da-form-item large">
						<input class="textbox requerid" type="text" name="txtNombre" id="txtNombre" style="width: 250px;" maxlength="50" value="'.$txtNombre.'"/>
						<span class="errorMessage" id="errornombre"></span>
					</div>
				</div>
				<div class="da-form-row">
					<label style="text-align:right;"><b>Telefono de la agencia</b></label>
					<div class="da-form-item large">
						<input class="textbox requerid" type="text" name="txtFonoAgencia" id="txtFonoAgencia" style="width: 250px;" value="'.$txtFonoAgencia.'"/>
						<span class="errorMessage" id="errorfonoagencia"></span>
					</div>
				</div>
				<div class="da-form-row">
					<label style="text-align:right;"><b>Correo electr&oacute;nico</b></label>
					<div class="da-form-item large">
						<input class="textbox requerid" type="text" name="txtEmail" id="txtEmail" style="width: 250px;" maxlength="50" value="'.$txtEmail.'"/>
						<span class="errorMessage" id="erroremail"></span>
					</div>
				</div>';
					
		   echo'<div class="da-button-row">
					<input type="button" value="Cancelar" class="da-button gray left" id="btnCancelar"/>
					<input type="submit" value="Guardar" class="da-button green" name="btnUsuario" id="btnUsuario"/>
					<input type="hidden" value="'.base64_decode($_GET['id_ef_sesion']).'" id="identidadf"/>
					<input type="hidden" id="tipo_sesion" value="'.$_SESSION['tipo_sesion'].'"/>
					<input type="hidden" id="id_usuario_sesion" value="'.$_SESSION['id_usuario_sesion'].'"/>
					<input type="hidden" name="accionEditar" value="checkdatos"/>
				</div>
			</form>
		</div>
	</div>';
	
	} else {
		//SI NO EXISTE EL USUARIO DADO EN LA BASE DE DATOS, VOLVEMOS A LA LISTA DE USUARIOS
		echo '<meta http-equiv="refresh" content="0; url=index.php?l=escritorio">' ;
	}
}

//FUNCION PARA CAMBIAR EL PASSWORD DE UN USUARIO
function cambiar_password($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion) {
?>
<script type="text/javascript">
  $(function(){
	$("#txtPassword").valid();
	//$("#txtPassNuevo").valid();
  });
</script>
<?php
	$errFlag = false;
	$errArr['password'] = '';
	$errArr['password2'] = '';
	$errArr['password3'] = '';

	$idusuario = base64_decode($_GET['idusuario']);

	//SI ESTAMOS LOGUEADOS COMO 'ADMIN', PODEMOS CAMBIAR EL PASSWORD DE CUALQUIER USUARIO
	
		if(isset($_POST['accionCambiar'])) {
			

			if($errFlag) {
				//MOSTRAMOS EL FORMULARIO CON LOS ERRORES
				mostrar_cambiar_password($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
			} else {
                //limpiar entradas
				$nuevopass = $conexion->real_escape_string($_POST['txtPassNuevo']);
				//encriptamos el password
			    $encrip_pass=crypt_blowfish_bycarluys($nuevopass); 
				
				$update = "UPDATE s_usuario as su
				                  inner join s_ef_usuario as efu on (efu.id_usuario=su.id_usuario) 
						   SET su.password='".$encrip_pass."' "
				         ."WHERE su.id_usuario='".$idusuario."' and efu.id_ef='".$id_ef_sesion."';";
				

				//MOSTRAMOS LA LISTA DE USUARIOS
				if($conexion->query($update)===TRUE){
				   //REALIZAMOS EL CAMBIO DE CONTRASE—A
				   $mensaje='Se cambio correctamente la contraseÒa del usuario '.$_POST['usuario'];
				   header('Location: index.php?l=usuarios_admin&op=1&msg='.$mensaje);
				} else{
				   $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
				   header('Location: index.php?l=usuarios_admin&op=2&msg='.$mensaje);
				} 
				
			}

		} else {
			//SI NO HIZO CLICK EN EL BOTON 'CAMBIAR', MOSTRAMOS EL FORM PARA CAMBIAR PASSWORD
			mostrar_cambiar_password($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
		}
	 
}

//FUNCION QUE PERMITE REALIZAR EL CAMBIO DE UN NUEVO PASSWORD
function mostrar_cambiar_password($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
  ?>
    
	<script type="text/javascript">
       $(function(){
		   $('#btnCancelar').click(function(){   
			   $(location).prop('href','index.php?l=usuarios_admin');
		   });   
	   });
    </script>
 
 <?php   
	$idusuario = base64_decode($_GET['idusuario']);
	$selectUs="select 
	            su.usuario,
				efu.id_ef,
				sef.nombre
			   from 
			     s_usuario as su 
				 inner join s_ef_usuario as efu on (efu.id_usuario=su.id_usuario)
				 inner join s_entidad_financiera as sef on (sef.id_ef=efu.id_ef) 
			   where 
			     su.id_usuario='".$idusuario."' and efu.id_ef='".$id_ef_sesion."';";
	$resus = $conexion->query($selectUs,MYSQLI_STORE_RESULT);
	$regUs = $resus->fetch_array(MYSQLI_ASSOC);
	$resus->free();
	
	 echo'<div class="da-panel collapsible">
		  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			  <ul class="action_user">
				  <li style="margin-right:6px;">
					 <a href="?l=usuarios_admin" class="da-tooltip-s" title="Volver atras">
					 <img src="images/retornar.png" width="32" height="32"></a>
				  </li>
			  </ul>
		  </div>
	  </div>';
	
    echo'<div class="da-panel" style="width:650px;">
		<div class="da-panel-header">
			<span class="da-panel-title">
				<img src="images/icons/black/16/locked_2.png" alt="" />
				Cambiar password
			</span>
		</div>
		<div class="da-panel-content">
			<form class="da-form" name="frmUsuario" action="" method="post">
				<div class="da-form-row">
					 <label style="width:136px;">Entidad Financiera</label>
					 <b>'.$regUs['nombre'].'</b>	 
				</div>
				<div class="da-form-row">
					<label>Usuario</label>
					<div class="da-form-item large">
						<b>'.$regUs['usuario'].'</b>
					</div>
				</div>
				<div class="da-form-row">
					<label>Contrase&ntilde;a actual</label>
					<div class="da-form-item large">
						<input class="textbox" type="password" name="txtPassActual" id="txtPassActual" style="width: 200px;" maxlength="15"/>
						<div id="ver_msg1"><div class="errorMessage" id="erro_pass_actual"></div></div>
					</div>
				</div>
				<div class="da-form-row">
					<label>Nueva contrase&ntilde;a</label>
					<div class="da-form-item large">
						<input class="textbox" type="password" name="txtPassNuevo" id="txtPassword" style="width: 200px;" maxlength="15" disabled="false"/>
						<span class="formNote" style="text-align:right; width:400px;">M&aacute;x. 15 caracteres M&iacute;n. 8 caracteres</span> 
                        <div id="ver_msg2"><label class="errorMessage" id="erro_pass_nuevo"></label></div>
						<div class="password-meter" style="width:199px;">
							<div class="password-meter-message">&nbsp;</div>
							<div class="password-meter-bg">
								<div class="password-meter-bar"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="da-form-row">
					<label>Confirmar contrase&ntilde;a</label>
					<div class="da-form-item large">
						<input class="textbox" type="password" name="txtPassRepite" id="txtPassRepite" style="width: 200px;" maxlength="15" disabled="false"/>
						<div id="ver_msg3"><div class="errorMessage" id="error_contrasenia_igual"></div></div>
					</div>
				</div>
				<div class="da-button-row">
					<input type="button" value="Cancelar" class="da-button gray left" id="btnCancelar"/>
					<input type="submit" value="Guardar" class="da-button green" name="btn_cambiar_pass" id="btn_cambiar_pass" disabled="false"/>
					<input type="hidden" name="accionCambiar" value="checkdatos"/>
	                <input type="hidden" name="idusuario" id="idusuario" value="'.$idusuario.'"/>
					<input type="hidden" name="usuario" id="usuario" value="'.$regUs['usuario'].'"/>
				</div>	
			</form>
		</div>
	</div>';
}

//FUNCION PARA RESETEAR EL PASSWORD DE UN USUARIO
function resetear_contrasenia_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion) {
?>
<script type="text/javascript">
  $(function(){
	$("#txtPassword").valid();
	//$("#txtPassNuevo").valid();
  });
</script>
<?php
	$errFlag = false;
	$errArr['password'] = '';
	$errArr['password2'] = '';
	$errArr['password3'] = '';

	$idusuario = base64_decode($_GET['idusuario']);

	//SI ESTAMOS LOGUEADOS COMO 'ADMIN', PODEMOS CAMBIAR EL PASSWORD DE CUALQUIER USUARIO
	
		if(isset($_POST['accionCambiar'])) {
			
			if($errFlag) {
				//MOSTRAMOS EL FORMULARIO CON LOS ERRORES
				mostrar_resetear_contrasenia($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
			} else {

				//limpiar entradas
				$nuevopass = $conexion->real_escape_string($_POST['txtPassNuevo']);
				//encriptamos el password
			    $encrip_pass=crypt_blowfish_bycarluys($nuevopass); 
				
				//date_default_timezone_set('America/La_Paz');
				$fecChange = date('Y-m-d');
				$update = "UPDATE s_usuario as su 
				                  inner join s_ef_usuario as efu on (efu.id_usuario=su.id_usuario)  
						   SET su.password='".$encrip_pass."' WHERE su.id_usuario='".$idusuario."' and efu.id_ef='".$id_ef_sesion."';";
				
				
				if($conexion->query($update)===TRUE){
				   //SE METIO A TBLHOMENOTICIAS, VAMOS A VER LA NOTICIA NUEVA
				   $mensaje='Se reseteo correctamente la contraseÒa del usuario: '.$_POST['usuario'];
				   header('Location: index.php?l=usuarios_admin&op=1&msg='.$mensaje);
				} else{
				   $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " .$conexion->error;
				   header('Location: index.php?l=usuarios_admin&op=2&msg='.$mensaje);
				} 
			}

		} else {
			//SI NO HIZO CLICK EN EL BOTON 'CAMBIAR', MOSTRAMOS EL FORM PARA CAMBIAR PASSWORD
			mostrar_resetear_contrasenia($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
		}
	
}

//FUNCION PARA IMPRIMIR EL FORM PARA RESETEAR CONTRASE?A DE USUARIO
function mostrar_resetear_contrasenia($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr) {
 ?>
    
	<script type="text/javascript">
       $(function(){
		   $('#btnCancelar').click(function(){   
			   $(location).prop('href','index.php?l=usuarios_admin');
		   });   
	   });
    </script>
 
 <?php
	$idusuario = base64_decode($_GET['idusuario']);
	$selectUs="select 
	            su.usuario,
				efu.id_ef,
				sef.nombre
			   from 
			     s_usuario as su 
				 inner join s_ef_usuario as efu on (efu.id_usuario=su.id_usuario)
				 inner join s_entidad_financiera as sef on (sef.id_ef=efu.id_ef) 
			   where 
			     su.id_usuario='".$idusuario."' and efu.id_ef='".$id_ef_sesion."';";
	$resus = $conexion->query($selectUs,MYSQLI_STORE_RESULT);
	$regUs = $resus->fetch_array(MYSQLI_ASSOC);
	$resus->free();
	
	 echo'<div class="da-panel collapsible">
		  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			  <ul class="action_user">
				  <li style="margin-right:6px;">
					 <a href="?l=usuarios_admin" class="da-tooltip-s" title="Volver atras">
					 <img src="images/retornar.png" width="32" height="32"></a>
				  </li>
			  </ul>
		  </div>
	  </div>';
	
    echo'<div class="da-panel" style="width:650px;">
		<div class="da-panel-header">
			<span class="da-panel-title">
				<img src="images/icons/black/16/locked_2.png" alt="" />
				Cambiar password
			</span>
		</div>
		<div class="da-panel-content">
			<form class="da-form" name="frmUsuario" action="" method="post">
				<div class="da-form-row">
					 <label style="width:136px;">Entidad Financiera</label>
					 <b>'.$regUs['nombre'].'</b>	 
				</div>
				<div class="da-form-row">
					<label>Usuario</label>
					<div class="da-form-item large">
						<b>'.$regUs['usuario'].'</b>
					</div>
				</div>
				<div class="da-form-row">
					<label>Nueva contrase&ntilde;a</label>
					<div class="da-form-item large">
						<input class="textbox" type="password" name="txtPassNuevo" id="txtPassword" style="width: 200px;" maxlength="15"/>
						<span class="formNote" style="text-align:right; width:400px;">M&aacute;x. 15 caracteres M&iacute;n. 8 caracteres</span> 
                        <div id="ver_msg2"><label class="errorMessage" id="erro_pass_nuevo"></label></div>
						<div class="password-meter" style="width:199px;">
							<div class="password-meter-message">&nbsp;</div>
							<div class="password-meter-bg">
								<div class="password-meter-bar"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="da-form-row">
					<label>Confirmar contrase&ntilde;a</label>
					<div class="da-form-item large">
						<input class="textbox" type="password" name="txtPassRepite" id="txtPassRepite" style="width: 200px;" maxlength="15" disabled="false"/>
						<div id="ver_msg3"><div class="errorMessage" id="error_contrasenia_igual"></div></div>
					</div>
				</div>
				<div class="da-button-row">
					<input type="button" value="Cancelar" class="da-button gray left" id="btnCancelar"/>
					<input type="submit" value="Guardar" class="da-button green" name="btn_cambiar_pass" id="btn_cambiar_pass" disabled="false"/>
					<input type="hidden" name="accionCambiar" value="checkdatos"/>
	                <input type="hidden" name="idusuario" id="idusuario" value="'.$idusuario.'"/>
					<input type="hidden" name="usuario" id="usuario" value="'.$regUs['usuario'].'"/>
				</div>	
			</form>
		</div>
	</div>';
}

//FUNCION QUE PERMITE DAR BAJA AL USUARIO
function dar_baja_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
   $idusuario = base64_decode($_GET['idusuario']);

	//NO PODEMOS ELIMINAR AL USUARIO ADMINISTRADOR
	
		if(isset($_POST['btnBajaUsuario'])) {
			
			//SI EL USUARIO HIZO CLICK EN EL BOTON PARA ELIMINAR EL REGISTRO, LO ELIMINAMOS DE LA BD
			$update ="UPDATE s_usuario as su 
			                 inner join s_ef_usuario as efu on (efu.i_usuario=su.id_usuario)
					  SET su.activado=0 "
				    ."WHERE su.id_usuario='".$idusuario."' and efu.id_ef='".$id_ef_sesion."';";
				

			
			//MOSTRAMOS LA LISTA DE USUARIOS
			if($conexion->query($update)===TRUE){
			    //SE METIO A TBLHOMENOTICIAS, VAMOS A VER LA NOTICIA NUEVA
			    $mensaje='se dio de baja al usuario '.$idusuario.' correctamente';
			    header('Location: index.php?l=usuarios_admin&op=1&msg='.$mensaje);
			} else{
			    $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			    header('Location: index.php?l=usuarios_admin&op=2&msg='.$mensaje);
			} 
		}elseif(isset($_POST['btnCancelar'])){ 
		    //MOSTRAMOS LA LISTA DE USUARIOS
			header('Location: index.php?l=usuarios_admin');
		}else {
			//MOSTRAMOS EL FORMULARIO PARA ELIMINAR AL USUARIO
			mostrar_dar_baja_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
		}
	
}

//FUNCION PARA MOSTRAR EL FORMULARIO
function mostrar_dar_baja_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
    $idusuario = base64_decode($_GET['idusuario']);
	$selectUs="select 
	            su.usuario,
				efu.id_ef,
				sef.nombre
			   from 
			     s_usuario as su 
				 inner join s_ef_usuario as efu on (efu.id_usuario=su.id_usuario)
				 inner join s_entidad_financiera as sef on (sef.id_ef=efu.id_ef) 
			   where 
			     su.id_usuario='".$idusuario."' and efu.id_ef='".$id_ef_sesion."';";
	$resus = $conexion->query($selectUs,MYSQLI_STORE_RESULT);
	$regUs = $resus->fetch_array(MYSQLI_ASSOC);
	$resus->free();
	echo'<div style="font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;">';
	echo '<form name="frmDarbaja" action="" method="post" class="da-form">';
	echo '<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" cols="2">';
	echo '<tr><td align="center" width="100%" style="height:60px;">
	       <label style="width:136px;">Entidad Financiera</label>
		   <b>'.$regUs['nombre'].'</b><br/><br/>';
	echo 'Al dar de baja al usuario, '
		.'se actualizara en la base de datos, est&aacute; seguro de dar baja al usuario <b>'.$regUs['usuario'].'</b> de forma permanente?';
	echo '</td></tr>
	      <tr> 
	      <td align="center">
	      <input class="da-button green" type="submit" name="btnBajaUsuario" value="Dar baja"/>'
		.'<input type="hidden" name="accionEliminar" value="checkdatos"/>
		  <input class="da-button gray left" type="submit" name="btnCancelar" value="Cancelar"/>';
	echo '</td></tr></table></form>';
	echo'</div>';
}
?>