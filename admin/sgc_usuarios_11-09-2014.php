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
			header('Location: index.php?l=usuarios');
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
								if($tipo_sesion=='ROOT') {
									crear_nuevo_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								} else {
									//SOLO EL USUARIO ADMIN PUEDE CREAR USUARIOS
									header('Location: index.php?l=usuarios');
								}
							}elseif(isset($_GET['importar'])){
								if($tipo_sesion=='ROOT'){
                                   importar_archivo_usuarios($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								}else{
									//SOLO EL USUARIO ADMIN PUEDE IMPORTAR USUARIOS
									//header('Location: index.php?l=usuarios');
									echo'<meta http-equiv="refresh" content="0;url=index.php?l=usuarios">';
								}
							}else {
								//VEMOS SI NOS PASAN UN ID DE USUARIO
								if(isset($_GET['idusuario'])) {
						
									//VEO SI ME PASAN VARIABLE PARA CAMBIAR DE PASSWORD
									if(isset($_GET['cpass'])) {
										//MOSTRARMOS EL FORM PARA CAMBIAR DE PASSWORD
										cambiar_password($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
						
									} elseif(isset($_GET['editar'])) {
										//SI NO ME PASAN 'CPASS' NI 'ELIMINAR', MUESTRO EL FORM PARA EDITAR USUARIO
										editar_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									} elseif(isset($_GET['reset'])){
										//FUNCION QUE PERMITE RESETEAR CONTRASE—A DEL USUARIO
										resetear_contrasenia_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									} elseif(isset($_GET['darbaja'])){
										//FUNCION QUE PERMITE DAR DE BAJA AL USUARIO
										dar_baja_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
									}
								} else {
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE USUARIOS EXISTENTES
									mostrar_lista_usuarios($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
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
function mostrar_lista_usuarios($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
?>
<link type="text/css" rel="stylesheet" href="plugins/jalerts/jquery.alerts.css"/>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="plugins/jalerts/jquery.alerts.js"></script>
<script type="text/javascript">
   $(function(){
	   $("a[href].activar_user").click(function(e){
		   var valor = $(this).attr('id');
		   var vec = valor.split('|');
		   var id_usuario = vec[0];
		   var text = vec[1]; 		  
		   jConfirm("Esta seguro de "+text+" al usuario?", ""+text+" usuario", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_usuario='+id_usuario+'&text='+text+'&opcion=enabled_disabled_user';
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
		 $.ambiance({message: "<?php echo base64_decode($msg);?>", 
				title: "Notificacion",
				type: "<?php echo $valor?>",
				timeout: 5
				});
		 //location.load("sgc.php?l=usuarios&idhome=1");
		 //$(location).attr('href', 'sgc.php?l=crearusuario&idhome=1');		
		 setTimeout( "$(location).attr('href', 'index.php?l=usuarios');",5000 );
	<?php }?>
	 
  });
</script>
<?php
$tipo_user=md5($tipo_sesion);

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
			  ef.activado=1 ";
			  if($tipo_user!=md5('ROOT')){
					$selectUs.="and su.id_usuario='".$id_usuario_sesion."' ";
			  }
$selectUs.="order by
			  ef.nombre, ust.tipo, dep.departamento;";			  
$res = $conexion->query($selectUs,MYSQLI_STORE_RESULT);
	  
  if($res->num_rows>0){
		if($tipo_user==md5('ROOT')){
			echo'<div class="da-panel collapsible">
					  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
						  <ul class="action_user">
							  <li style="margin-right:6px;">
								 <a href="?l=usuarios&crear=1" class="da-tooltip-s" title="<span lang=\'es\'>Agregar nuevo usuario</span>">
								 <img src="images/add_new_users.png" width="32" height="32"></a>
							  </li>
							  <li style="margin-right:6px;">
								 <a href="?l=usuarios&importar=1" class="da-tooltip-s" title="Importar usuarios">
								 <img src="images/upload.png" width="32" height="32"></a>
							  </li>
						  </ul>
					  </div>
				  </div>';
		}
		if($res->num_rows>0){$id='id="da-ex-datatable-numberpaging"';}else{$id='';}	
		echo'
		<div class="da-panel collapsible">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/list.png" alt="" />
					<span lang="es">Usuarios</span>
				</span>
			</div>
			<div class="da-panel-content" style="font-size:11px;">
				<table class="da-table" '.$id.'>
					<thead>
						<tr>
							<th><b><span lang="es">Entidad Financiera</span></b></th>
							<th><b><span lang="es">Tipo usuario</span></b></th>
							<th><b><span lang="es">Usuario</span></b></th>
							<th><b><span lang="es">Nombre</span></b></th>
							<th><b><span lang="es">Correo electronico</span></b></th>
							<th><b><span lang="es">Departamento</span><br/><span lang="es">Regional</span></b></th>
							<th><b><span lang="es">Agencia</span></b></th>
							<th><b><span lang="es">Estado</span></b></th>
							<th></th>
						</tr>
					</thead>
					<tbody>';
					  $num = $res->num_rows;
					  if($num>0){
							while($regi = $res->fetch_array(MYSQLI_ASSOC)){
								echo'<tr ';
										 if($regi['activado']=='inactivo'){
											echo'style="background:#FD2F18; color:#ffffff;"'; 
										 }else{
											echo'';	 
										 }
								echo'>
										<td>'.$regi['entidad'].'</td>
										<td>'.$regi['tipo'].'</td>
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
										   if($regi['codigo']!='IMP'){
											  if($regi['codigo']!='REP'){ 
											      echo $regi['agencia'];
											  }else{
												  if(!empty($regi['agencia'])){
													  echo $regi['agencia'];
												  }else{ 
												      echo 'Todos';
												  }
											  }
										   }else{
											   $selectIm="select
														   sag.agencia
														from 
														  s_im_usuario_agencia as uag
														  inner join s_agencia as sag on (sag.id_agencia=uag.id_agencia)
														where
														  uag.id_usuario='".$regi['id_usuario']."';";
											   $resim = $conexion->query($selectIm,MYSQLI_STORE_RESULT);
											   echo'<ul>';
											   while($rgim = $resim->fetch_array(MYSQLI_ASSOC)){
												 echo'<li style="margin-left:5px;">'.$rgim['agencia'].'</li>';  
											   }
											   $resim->free();
											   echo'</ul>';			  
										   }
								   echo'</td>
										<td><span lang="es">'.$regi['activado'].'</span></td>
										
										<td style="text-align:center; width:70px;">';
										   echo'
										   <ul class="action_user">
											<li><a href="?l=usuarios&idusuario='.base64_encode($regi['id_usuario']).'&editar=v" class="edit da-tooltip-s" title="<span lang=\'es\'>Editar</span>"></a></li>
											<li><a href="?l=usuarios&idusuario='.base64_encode($regi['id_usuario']).'&cpass=v" class="privacidad da-tooltip-s" title="<span lang=\'es\'>Cambiar Contrase&ntilde;a</span>"></a></li>';
											if($tipo_user==md5('ROOT')){
											   echo'<li><a href="?l=usuarios&idusuario='.base64_encode($regi['id_usuario']).'&reset=v" class="resetear da-tooltip-s" title="<span lang=\'es\'>Resetear Contrase&ntilde;a</span>"></a></li>';
											   if($regi['activado']=='activo'){
												  echo'<li><a href="#" class="darbaja da-tooltip-s activar_user" id="'.base64_encode($regi['id_usuario']).'|dar baja" title="<span lang=\'es\'>dar baja</span>"></a></li>';
											   }elseif($regi['activado']=='inactivo'){
												  echo'<li><a href="#" class="daralta da-tooltip-s activar_user" id="'.base64_encode($regi['id_usuario']).'|dar alta" title="<span lang=\'es\'>dar alta</span>"></a></li>'; 
											   }
											}
											
									  echo'</ul>';	
										echo'
										</td>
									</tr>';
							}
							$res->free();			
					  }else{
						 echo'<tr><td colspan="9">
								  <div class="da-message info">
									   <span lang="es">No existe ningun registro, ingrese nuevos registros</span>
								  </div>
							  </td></tr>';
					  }
			   echo'</tbody>
				</table>
			</div>
		</div>';
  }else{
	  echo'<div class="da-message warning">
			   <span lang="es">No existe ningun registro, probablemente se debe a</span>:
			   <ul>
				  <li lang="es">La Entidad Financiera no esta activado</li>
				  <li lang="es">La Entidad Financiera no esta creada</li>
				</ul>
		  </div>'; 
  }
}

//FUNCION QUE PERMITE VISUALIZAR EL FORMULARIO NUEVO USUARIO
function crear_nuevo_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
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
			mostrar_crear_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
		} else {
			//GUARDAMOS EL NUEVO USUARIO EN LA BASE DE DATOS

			//IMPLEMENTAMOS SEGURIDAD
			$vec2=array();
			
			$vec2 = explode('|',$_POST['txtTipousuario']);
			$id_tipo = $conexion->real_escape_string($vec2[0]);
			$tipouser_text=$vec2[1];
			if($tipouser_text!='IMP'){
				if($tipouser_text=='REP'){
				   if(!empty($_POST['departamento'])){
						$depto_regional = $_POST['departamento'];
					}else{
						$depto_regional = 'null';
					}	
				}else{
				   $depto_regional = $_POST['departamento'];
				}
			}else{
				$variable=explode('|',$_POST['regional']);
				$depto_regional=$variable[0];
			}
			//$id_depto = mysql_real_escape_string($vec[0]);
			if($_POST['txtFonoAgencia']) $fono_agencia = $conexion->real_escape_string($_POST['txtFonoAgencia']);
			else $fono_agencia='';

			$especiales = array("");
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
					
			//generamos un idusuario unico encriptado
			$prefijo='@S#1$2013';
            $id_unico='';
            $id_unico=uniqid($prefijo,true);
			
			//encriptamos el password del usuario
			$encrip_pass=crypt_blowfish_bycarluys($_POST['txtPassword']);
			
			if($tipouser_text!='FAC' and $tipouser_text!='IMP'){ 
			        $identidadf = $conexion->real_escape_string($_POST['identidadf']);
					if(!empty($_POST['id_agencia'])){
						$id_agencia="'".$_POST['id_agencia']."'";
					}else{
						$id_agencia='null';
						
					}
					
					//INSERTAMOS LOS DATOS DEL USUARIO
					$insert_us = "INSERT INTO s_usuario(id_usuario, usuario, password, nombre, email, id_tipo, id_depto, activado, id_agencia, fono_agencia, fecha_creacion) "
					         ."VALUES('".$id_unico."', '".$usuario."', '".$encrip_pass."', '".$nombre."', '".$email."', ".$id_tipo.", ".$depto_regional.", 1, ".$id_agencia.", '".$fono_agencia."', curdate())";
			        //echo $insert_us;
					if($conexion->query($insert_us) === TRUE){ $resultado=TRUE; }else{ $resultado=FALSE; }
					//INSERTAMOS LA ENTIDAD FINANCIERA
					$prefijo2='@S#1$2013';
					$id_unico2='';
					$id_unico2=uniqid($prefijo2,true);
					$insert_ef = "INSERT INTO s_ef_usuario(id_eu, usuario, id_usuario, id_ef) VALUES('".$id_unico2."', '".$usuario."', '".$id_unico."', '".$identidadf."');";
					if($conexion->query($insert_ef) === TRUE){ $resultado=TRUE;}else{ $resultado=FALSE;}				 
			}elseif($tipouser_text=='FAC'){
			    $vecidmultiple=$_POST['idmultiple'];
				/*$selectAg="select
							  id_agencia,
							  agencia
							from
							  s_agencia
							where
							  id_depto is null and id_ef='".$vecidmultiple[0]."';";
				  $reg_ag=mysql_fetch_array(mysql_query($selectAg,$conexion));*/
				//INSERTAMOS LOS DATOS DEL USUARIO
				$insert_us = "INSERT INTO s_usuario(id_usuario, usuario, password, nombre, email, id_tipo, id_depto, activado, id_agencia, fono_agencia, fecha_creacion) "
					     ."VALUES('".$id_unico."', '".$usuario."', '".$encrip_pass."', '".$nombre."', '".$email."', ".$id_tipo.", ".$depto_regional.", 1, null, '".$fono_agencia."', curdate())";
				if($conexion->query($insert_us) === TRUE){ $resultado=TRUE;}else{ $resultado=FALSE;}
								
				$cant=count($vecidmultiple);	
			    //INSERTAMOS LAS ENTIDADES FINANCIERAS
				for($i=0;$i<$cant;$i++){
					//generamos un idusuario unico encriptado
					 $prefijo2='@S#1$2013';
					 $id_unico2='';
					 $id_unico2=uniqid($prefijo2,true);
					 $insert_ef = "INSERT INTO s_ef_usuario(id_eu, usuario, id_usuario, id_ef) VALUES('".$id_unico2."', '".$usuario."', '".$id_unico."', '".$vecidmultiple[$i]."');";
					 if($conexion->query($insert_ef) === TRUE){ $resultado=TRUE;}else{ $resultado=FALSE;}
				}
				
		    }elseif($tipouser_text=='IMP'){
				$vecidmultiple=$_POST['idmultiple_agency'];
				$identidadf = $conexion->real_escape_string($_POST['identidadf']);
				//INSERTAMOS LOS DATOS DEL USUARIO
				$insert_us = "INSERT INTO s_usuario(id_usuario, usuario, password, nombre, email, id_tipo, id_depto, activado, id_agencia, fono_agencia, fecha_creacion) "
					     ."VALUES('".$id_unico."', '".$usuario."', '".$encrip_pass."', '".$nombre."', '".$email."', ".$id_tipo.", ".$depto_regional.", 1, null, '".$fono_agencia."', curdate())";
				//echo $insert_us; 
				if($conexion->query($insert_us) === TRUE){ $resultado=TRUE;}else{ $resultado=FALSE;}
				
				//INSERTAMOS LA ENTIDAD FINANCIERA PARA EL USUARIO CREADO
				$prefijo_eu='@S#1$2013';
				$id_new_eu='';
				$id_new_eu=uniqid($prefijo_eu,true);
				$insert_eu = "INSERT INTO s_ef_usuario(id_eu, usuario, id_usuario, id_ef) VALUES('".$id_new_eu."', '".$usuario."', '".$id_unico."', '".$identidadf."');";
				if($conexion->query($insert_eu) === TRUE){ $resultado=TRUE;}else{ $resultado=FALSE;}
				
				$cant=count($vecidmultiple);	
			    //INSERTAMOS LAS AGENCIAS ELEGIDAS
				for($i=0;$i<$cant;$i++){
					//generamos un idusuario unico encriptado
					 $prefijo2='@S#1$2013';
					 $id_unico2='';
					 $id_unico2=uniqid($prefijo2,true);
					 $insert_ef = "INSERT INTO s_im_usuario_agencia(id_user_age, usuario, id_usuario, id_agencia) VALUES('".$id_unico2."', '".$usuario."', '".$id_unico."', '".$vecidmultiple[$i]."');";
					 if($conexion->query($insert_ef) === TRUE){
						 $resultado=TRUE;
					 }else{
						 $resultado=FALSE;
				     }
				}
			}
           
            //VERIFICAMOS SI HUBO ALGUN ERROR AL INGRESAR EN LA TABLA
			if($resultado){
				 //VERIFICAMOS EL TIPO DE USUARIO NO ES [FAC/LOG/REP/IMP]
				 if($tipouser_text!='FAC' and $tipouser_text!='LOG' and $tipouser_text!='REP' and $tipouser_text!='IMP'){   
						//METEMOS LOS PERMISOS DEL USUARIO A LA TABLA TBLUSUARIOSPERMISOS
						if(isset($_POST['cbIni'])) {
							$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
							."VALUES(null, '".$id_unico."', 'inicio')";
							if($conexion->query($insert_permiso) === TRUE){$respta=TRUE;}else{$respta=FALSE;}
						}
						if(isset($_POST['cbFor'])) {
							$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
							."VALUES(null, '".$id_unico."', '".$_POST['cbFor']."')";
							if($conexion->query($insert_permiso) === TRUE){$respta=TRUE;}else{$respta=FALSE;}
						}
						if(isset($_POST['cbCia'])) {
							$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
							."VALUES(null, '".$id_unico."', 'compania')";
							if($conexion->query($insert_permiso) === TRUE){$respta=TRUE;}else{$respta=FALSE;}
						}
						if(isset($_POST['cbDes'])) {
							$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
							."VALUES(null, '".$id_unico."', '".$_POST['cbDes']."')";
							if($conexion->query($insert_permiso) === TRUE){$respta=TRUE;}else{$respta=FALSE;}
						}
						if(isset($_POST['cbPol'])) {
							$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
							."VALUES(null, '".$id_unico."', '".$_POST['cbPol']."')";
							if($conexion->query($insert_permiso) === TRUE){$respta=TRUE;}else{$respta=FALSE;}
						}
						if(isset($_POST['cbCreaU'])) {
							$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
							."VALUES(null, '".$id_unico."', '".$_POST['cbCreaU']."')";
							if($conexion->query($insert_permiso) === TRUE){$respta=TRUE;}else{$respta=FALSE;}
						}
						
						if(isset($_POST['cbEmail'])){
							$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
							."VALUES(null, '".$id_unico."', '".$_POST['cbEmail']."')";
							if($conexion->query($insert_permiso) === TRUE){$respta=TRUE;}else{$respta=FALSE;}
						}
						
						if(isset($_POST['cbAgen'])){
							$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
							."VALUES(null, '".$id_unico."', '".$_POST['cbAgen']."')";
							if($conexion->query($insert_permiso) === TRUE){$respta=TRUE;}else{$respta=FALSE;}
						}
						if(isset($_POST['cbAuto'])){
							$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
							."VALUES(null, '".$id_unico."', '".$_POST['cbAuto']."')";
							if($conexion->query($insert_permiso) === TRUE){$respta=TRUE;}else{$respta=FALSE;}
						}
						if(isset($_POST['cbTrd'])){
							$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
							."VALUES(null, '".$id_unico."', '".$_POST['cbTrd']."')";
							if($conexion->query($insert_permiso) === TRUE){$respta=TRUE;}else{$respta=FALSE;}
						}
						if(isset($_POST['cbTrem'])){
							$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
							."VALUES(null, '".$id_unico."', '".$_POST['cbTrem']."')";
							if($conexion->query($insert_permiso) === TRUE){$respta=TRUE;}else{$respta=FALSE;}
						}					
						if(isset($_POST['cbDepSuc'])){
							$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
							."VALUES(null, '".$id_unico."', '".$_POST['cbDepSuc']."')";
							if($conexion->query($insert_permiso) === TRUE){$respta=TRUE;}else{$respta=FALSE;}
						}
						if(isset($_POST['cbTipc'])){
							$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
							."VALUES(null, '".$id_unico."', '".$_POST['cbTipc']."')";
							if($conexion->query($insert_permiso) === TRUE){$respta=TRUE;}else{$respta=FALSE;}
						}
						if(isset($_POST['cbTh'])){
							$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
							."VALUES(null, '".$id_unico."', '".$_POST['cbTh']."')";
							if($conexion->query($insert_permiso) === TRUE){$respta=TRUE;}else{$respta=FALSE;}
						}
						
						//VERIFICAMOS SI HUBO ALGUN ERROR AL INGRESAR EN LA TABLA
						if($respta){
							$mensaje="Se registro correctamente los datos del usuario ".$usuario;
							header('Location: index.php?l=usuarios&op=1&msg='.base64_encode($mensaje));
							//echo'<meta http-equiv="Refresh" content="0;url=index.php?l=usuarios&op=1&msg='.$mensaje.'">';
						} else {
							$mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
							header('Location: index.php?l=usuarios&op=2&msg='.base64_encode($mensaje));
						}	
				 }else{
					 $mensaje="Se registro correctamente los datos del usuario ".$usuario;
				     header('Location: index.php?l=usuarios&op=1&msg='.base64_encode($mensaje));
				 }
		    } else {
			    $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".mysql_errno($conexion) . ": " . mysql_error($conexion);
				header('Location: index.php?l=usuarios&op=2&msg='.base64_encode($mensaje));
		    }	 
			
		}
	} else {
		//MOSTRAMOS EL FORMULARIO PARA INGRESAR LOS DATOS
		mostrar_crear_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
	}
}

//VISUALIZAMOS EL FORMULARIO CREA USUARIO
function mostrar_crear_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr){
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
			   $(location).prop('href','index.php?l=usuarios');
		   });
		   
		   //SELECT DEPARTAMANTO - FUNCION QUE PERMITE BUSCAR LA AGENCIA DE UN
		   //DETERMINADO DEPARTAMENTO O REGION
		   $('#departamento').change(function(e) {
			   
			   var id_departamento = $(this).attr('value');
			   var identidadf = $('#identidadf option:selected').prop('value');
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
		   
		   		   
		   //VALIDAR CAMPOS
		   $('#frmUsuario').submit(function(e){
			  //alert('hola');
			  var variable = $('#txtTipousuario option:selected').prop('value');
			  //alert(variable);
			  var vec = variable.split('|');
			  var tipousuario = vec[1];
			  var departamento = $('#departamento option:selected').prop('value');
			  var regional = $('#regional option:selected').prop('value');
			  var identidadf = $('#identidadf option:selected').prop('value');
			  var usuario = $('#txtIdusuario').prop('value');
			  var nombre = $('#txtNombre').prop('value');
			  var fonoagencia = $('#txtFonoAgencia').prop('value');
			  var email = $('#txtEmail').prop('value');
			  var sum=0;
			  var miarray = new Array();
			  miarray=[ "cbIni", "cbFor", "cbCia", "cbDes", "cbPol", "cbCreaU", "cbEmail", "cbAgen", "cbAuto", "cbTrd", "cbTrem", "cbDepSuc", "cbTipc", "cbTh" ];
			  var numelem=miarray.length;
			 // alert(numelem);
			  var chek=0; var ds=0; var cell=0;
			  $(this).find('.requerid').each(function(){
				   				   
				   if(variable!=''){
					   $('#errortipousuario').hide('slow'); 
					   if(tipousuario!='FAC'){
						   if(identidadf!=''){
							  $('#erroref').hide('slow');
						   }else{
							  sum++;
							  $('#erroref').show('slow');
							  $('#erroref').html('seleccione entidad financiera');   
						   }
					   }else if(tipousuario=='FAC'){
						      $('#idmultiple option').each(function() { 
							      if($(this).is(':selected')){
							        //var valor = $(this).prop('value');
								    cell++;
								  }
							  });
							  if(cell==0){
								  sum++;
							      $('#erroref').show('slow');
							      $('#erroref').html('seleccione al menos una entidad financiera');   
							  } 
					   }else if(tipousuario=='IMP'){
						      
					   }
				   }else{
					   sum++;
					   $('#errortipousuario').show('slow');
					   $('#errortipousuario').html('seleccione tipo de usuario');
				   }
				   if(tipousuario!='IMP'){  
					   if(departamento!=''){
						   $('#errordepartamento').hide('slow');
					   }else{
						   if(tipousuario!='REP'){
							   sum++;
							   $('#errordepartamento').show('slow');
							   $('#errordepartamento').html('seleccione departamento');
						   }
					   }
				   }else{
					   if(regional!=''){
						   $('#errorregional').hide('slow');
						   $('#idmultiple_agency option').each(function() { 
								if($(this).is(':selected')){
								  //var valor = $(this).prop('value');
								  cell++;
								}
						   });
						   if(cell==0){
								sum++;
								$('#errormultiagency').show('slow');
								$('#errormultiagency').html('seleccione al menos una agencia');   
						   }else{
								$('#errormultiagency').hide('slow');  
						   }
					   }else{
						   sum++;
						   $('#errorregional').show('slow');
						   $('#errorregional').html('seleccione regional');   
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
				   }else{
					   sum++;
					   $('#errornombre').show('slow');
					   $('#errornombre').html('ingrese nombre completo');
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
				   
				   $.each( miarray, function( i, l ){
                       
					   if($('input[name="'+l+'"]').is(':enabled')){
						   ds++;
						   if($('input[name="'+l+'"]').is(":checked")){
							  chek++;
						   } 
					   }else{
						      
					   }
					  //alert( "Index #" + i + ": " + l );
					
				   });
				  
				   if(ds!=0){
					   if(chek!=0){
						   $('#errorseleccion').hide('slow');
					   }else{
						   sum++;
						   $('#errorseleccion').show('slow');
						   $('#errorseleccion').html('Debe de seleccionar al menos un elemento');
					   } 
				   }
			  });
			  if(sum==0){
				  
			  }else{
			      e.preventDefault();
			  }
		   });
		   
		   //CONVERTIMOS A MINUSCULAS
		   $('#txtIdusuario').keyup(function() {
			  $(this).val($(this).val().toLowerCase());
		   });
		   
		   //HABILITAMOS ENTIDAD FINANCIERA VERIFICAMOS QUE TIPO DE USUARIO ELIGIO
		   //POSTERIOR VISUALIZAMOS EL SELECT O SELECT MULTIMPLE
		   $('#txtTipousuario').change(function(){
			  var variable = $(this).prop('value');
			  if(variable!=''){
				  var vec = variable.split('|');
				  var tipousuario=vec[1];
				  //habilitar/dsabilitar msg en permisos checkbox
				  if(tipousuario=='FAC' || tipousuario=='LOG' || tipousuario=='REP' || tipousuario=='IMP'){
					  $('#errorseleccion').hide('slow');
				  }
				  				  
				  if(tipousuario=='REP'){
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
				  
				  
				  if(tipousuario=='FAC'){
					  $('#departamento option[value=""]').prop('selected',true);
					  $('#content-agency').fadeOut('slow');
					  $('#content-regional').fadeOut('slow');
					  $('#content-departamento').fadeIn('slow');
				  }else if(tipousuario=='IMP'){
					  $('#content-departamento').fadeOut('slow');
					  $('#content-agency').fadeOut('slow');
					   
				  }else{
					  $('#content-departamento').fadeIn('slow');
					  $('#content-regional').fadeOut('slow');
					  $('#content-agency').fadeOut('slow');  
				  }
				  $('#content-entidadf').fadeIn('slow');
				  var dataString = 'tipousuario='+tipousuario+'&opcion=buscar_entidad';
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
				  $('#departamento option[value=""]').prop('selected',true);
				  $("#departamento").attr("disabled", true);
				  $('#content-agency').fadeOut('slow'); 
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
	$implante='';
	//RECORDAMOS LOS VALORES INGRESADOS ANTERIORMENTE
	if(isset($_POST['txtTipousuario'])) $txtTipousuario = $_POST['txtTipousuario']; else $txtTipousuario = '';
	if(isset($_POST['departamento'])) $departamento = $_POST['departamento']; else $departamento = '';
	if(isset($_POST['txtIdusuario'])) $txtIdusuario = $_POST['txtIdusuario']; else $txtIdusuario = '';
	if(isset($_POST['txtNombre'])) $txtNombre = $_POST['txtNombre']; else $txtNombre = '';
	if(isset($_POST['txtEmail'])) $txtEmail = $_POST['txtEmail']; else $txtEmail = '';
	if(isset($_POST['txtFonoAgencia'])) $txtFonoAgencia = $_POST['txtFonoAgencia']; else $txtFonoAgencia = '';
	if(isset($_POST['cbIni'])) $cbIni = 'checked'; else $cbIni = 'unchecked';
	if(isset($_POST['cbFor'])) $cbFor = 'checked'; else $cbFor = 'unchecked';
	if(isset($_POST['cbCia'])) $cbCia = 'checked'; else $cbCia = 'unchecked';
	if(isset($_POST['cbDes'])) $cbDes = 'checked'; else $cbDes = 'unchecked';
	if(isset($_POST['cbPol'])) $cbPol = 'checked'; else $cbPol = 'unchecked';
	if(isset($_POST['cbCreaU'])) $cbCreaU = 'checked'; else $cbCreaU = 'unchecked';
	if(isset($_POST['cbEmail'])) $cbEmail = 'checked'; else $cbEmail = 'unchecked';
	//if(isset($_POST['cbOcupa'])) $cbOcupa = 'checked'; else $cbOcupa = 'unchecked';
	//if(isset($_POST['cbForpag'])) $cbForpag = 'checked'; $cbForpag = 'unchecked';
	//if(isset($_POST['cbEstado'])) $cbEstado = 'checked'; $cbEstado = 'unchecked';
	//if(isset($_POST['cbCertMed'])) $cbCertMed = 'checked'; $cbCertMed = 'unchecked';
	if(isset($_POST['cbDepSuc'])) $cbDepSuc = 'cheked'; $cbDepSuc = 'unchecked';
	if(isset($_POST['cbAgen'])) $cbAgen = 'checked'; else $cbAgen = 'unchecked';
	if(isset($_POST['cbAuto'])) $cbAuto = 'checked'; else $cbAuto = 'unchecked';
	if(isset($_POST['cbTrd'])) $cbTrd = 'checked'; else $cbTrd = 'unchecked';
	if(isset($_POST['cbTrem'])) $cbTrem = 'checked'; else $cbTrem = 'unchecked';
	if(isset($_POST['cbTipc'])) $cbTipc = 'checked'; else $cbTipc = 'unchecked';
	if(isset($_POST['cbTh'])) $cbTh = 'checked'; else $cbTh = 'unchecked';
	/*if(isset($_POST['cbRepFact'])) $cbRepFact = 'checked'; else $cbRepFact = 'unchecked';
	if(isset($_POST['cbLog'])) $cbLog = 'checked'; else $cbLog = 'unchecked';
	if(isset($_POST['cbCreus'])) $cbCreus = 'checked'; else $cbCreus = 'unchecked'; 
	if(isset($_POST['cbRepSolPr'])) $cbRepSolPr = 'checked'; else $cbRepSolPr = 'unchecked'; 
	if(isset($_POST['cbRepEcoSud'])) $cbRepEcoSud = 'checked'; else $cbRepEcoSud = 'unchecked'; 
	if(isset($_POST['cbRepLog'])) $cbRepLog = 'checked'; else $cbRepLog = 'unchecked'; // NUEVO 05-06-2013*/
	/*
	if($txtTipousuario!=''){
	   if($txtTipousuario==2){//administrador
	       $facul='';
		   $admiope='';
		   $oficred='disabled';
		   $oficredrep='';
		   $creaus='';
		   $implante='disabled';
	   }elseif($txtTipousuario==3){//operador
	       $facul='enabled';
		   $admiope='enabled';
		   $oficred='disabled';
		   $creaus='disabled';
		   $oficredrep='disabled';
		   $implante='disabled';
	   }elseif($txtTipousuario==4){//facultativo
	       $admiope='disabled';
		   $facul='enabled';
		   $oficred='disabled';
		   $creaus='disabled';
		   $respso='enabled';
		   $repeco='enabled';
		   $repanula='enabled';
		   $oficredrep='disabled';
		   $implante='disabled';
	   }elseif($txtTipousuario==5){//logueado
	       $admiope='disabled';
		   $facul='disabled';
		   $oficred='enabled';
		   $creaus='disabled';
		   $respso='disabled';
		   $repeco='disabled';
		   $repanula='disabled';
		   $oficredrep='enabled';
		   $implante='disabled';
	   }elseif($txtTipousuario==6){//creausuario
	       $admiope='disabled';
		   $facul='disabled';
		   $oficred='disabled';
		   $creaus='enabled';
		   $respso='disabled';
		   $repeco='disabled';
		   $repanula='disabled';
		   $oficredrep='disabled';
		   $implante='disabled';
	   }elseif($txtTipousuario==8){//implante
	       $admiope='disabled';
		   $facul='disabled';
		   $oficred='disabled';
		   $creaus='disabled';
		   $respso='disabled';
		   $repeco='disabled';
		   $repanula='disabled';
		   $oficredrep='disabled';
		   $implante='enabled';
	   }
	}else{
	   $admiope='disabled';
	   $facul='disabled';
	   $oficred='disabled';
	   $creaus='disabled';
	   $respso='disabled';
	   $repeco='disabled';
	   $repanula='disabled';
	   $oficredrep='disabled';
	   $implante='disabled';
	}*/
	$opera='disabled';
	$admiope='disabled';
	$creaus='disabled';
	$oficred='disabled';
	$facul='enabled';
	$implante='disabled';
	$facul='disabled';
  
  echo'<div class="da-panel collapsible">
		  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			  <ul class="action_user">
				  <li style="margin-right:6px;">
					 <a href="?l=usuarios" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
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
			<form class="da-form" name="frmUsuario" action="" method="post" id="frmUsuario">';
				
				echo'
				<div class="da-form-row">
					<label style="text-align:right;"><b><span lang="es">Tipo usuario</span></b></label>
					<div class="da-form-item small">';
						$selectTi="select 
										id_tipo,
										tipo,
										codigo
									from
										s_usuario_tipo
									where
										codigo!='ROOT';";
						$rstip = $conexion->query($selectTi,MYSQLI_STORE_RESULT);
						echo'<select name="txtTipousuario" id="txtTipousuario" class="requerid">';
							  echo'<option value="" lang="es">seleccione...</option>';
							  while($filatip = $rstip->fetch_array(MYSQLI_ASSOC)){
								 if($filatip['id_tipo']==$txtTipousuario){
									echo'<option value="'.$filatip['id_tipo'].'|'.$filatip['codigo'].'" selected>'.$filatip['tipo'].'</option>';
								 }else{
									echo'<option value="'.$filatip['id_tipo'].'|'.$filatip['codigo'].'">'.$filatip['tipo'].'</option>';  
								 }  
							  }
							  $rstip->free();
						echo'</select>';
						
						echo'<span class="errorMessage" id="errortipousuario" lang="es"></span>
					</div>
				</div>
				<div class="da-form-row" style="display: none;" id="content-entidadf">
					<label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
					<div class="da-form-item large">
					 <span id="entidad-loading" class="loading-entf"></span>
					</div>
				</div>	
				<div class="da-form-row" id="content-departamento">
					<label style="text-align:right;"><b><span lang="es">Departamento</span></b></label>
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
							  echo'<option value="" lang="es">seleccione...</option>';
							  while($filadep = $rsdep->fetch_array(MYSQLI_ASSOC)){
								 if($filadep['id_depto']==$departamento){
									echo'<option value="'.$filadep['id_depto'].'" selected>'.$filadep['departamento'].'</option>';
								 }else{
									echo'<option value="'.$filadep['id_depto'].'">'.$filadep['departamento'].'</option>';  
								 }  
							  }
							  $rsdep->free();
						echo'</select>';
						
				     echo'<span class="errorMessage" id="errordepartamento" lang="es"></span>
					</div>
				</div>
				<div class="da-form-row" style="display: none;" id="content-regional">
				  <label style="text-align:right;"><b><span lang="es">Departamento</span></b></label>
				  <div class="da-form-item small">
				      <span id="response-regional"></span> 
			      </div>	
				</div>  
				<div class="da-form-row" style="display: none;" id="content-agency">
				  <label style="text-align:right;"><b><span lang="es">Agencia</span></b></label>
				  <div class="da-form-item small">
				    <span id="response-loading"></span>
				  </div>	
				</div>  
				<div class="da-form-row">
					<label style="text-align:right;"><b><span lang="es">Usuario</span></b></label>
					<div class="da-form-item large">
						<input class="textbox requerid" type="text" name="txtIdusuario" id="txtIdusuario" style="width: 200px;" maxlength="20" value="'.$txtIdusuario.'" disabled autocomplete="off"/>
		                <span class="formNote" style="text-align:rigth; width:430px;" lang="es">'.utf8_encode('No use may˙sculas ni espacios en blanco.').'</span>
		                <div id="ver_msg"><div class="errorMessage" id="error_usuario" lang="es"></div></div>
					</div>
					
				</div>
				<div class="da-form-row">
					<label style="text-align:right;"><b><span lang="es">'.utf8_encode('ContraseÒa').'</span></b></label>
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
					<label style="text-align:right;"><b><span lang="es">'.utf8_encode('Confirmar contraseÒa').'</span></b></label>
					<div class="da-form-item large">
						<input class="textbox" type="password" name="txtPassword2" id="txtPassword2" style="width: 200px;" maxlength="15"/>
						<div id="ver_msg2"><div class="errorMessage" id="error_contrasenia_igual"></div></div>
					</div>
				</div>
				<div class="da-form-row">
					<label style="text-align:right;"><b><span lang="es">Nombre completo</span></b></label>
					<div class="da-form-item large">
						<input class="textbox requerid" type="text" name="txtNombre" id="txtNombre" style="width: 250px;" maxlength="50" value="'.$txtNombre.'" autocomplete="off"/>
						<span class="errorMessage" id="errornombre"></span>
					</div>
				</div>
				<div class="da-form-row">
					<label style="text-align:right;"><b><span lang="es">Telefono de la agencia</span></b></label>
					<div class="da-form-item large">
						<input class="textbox requerid" type="text" name="txtFonoAgencia" id="txtFonoAgencia" style="width: 250px;" value="'.$txtFonoAgencia.'" autocomplete="off"/>
						<span class="errorMessage" id="errorfonoagencia"></span>
					</div>
				</div>
				<div class="da-form-row">
					<label style="text-align:right;"><b><span lang="es">Correo electronico</span></b></label>
					<div class="da-form-item large">
						<input class="textbox requerid" type="text" name="txtEmail" id="txtEmail" style="width: 250px;" maxlength="50" value="'.$txtEmail.'" autocomplete="off"/>
						<span class="errorMessage" id="erroremail" lang="es"></span>
					</div>
				</div>
				<div class="da-form-row">
				 <b><span lang="es">'.utf8_encode('Seleccione los elementos que el usuario podr· editar').'</span></b><br/>
				 <input class="child requerid" type="checkbox" name="cbIni" '.$cbIni.' '.$opera.' value="inicio"><span lang="es">Inicio</span><br/>
				 <input class="child requerid" type="checkbox" name="cbFor" '.$cbFor.' '.$opera.' value="formularios"><span lang="es">Formularios</span><br/>
				 
				 <span id="DES" style="display:none;"><input class="child requerid" type="checkbox" name="cbDes" '.$cbDes.' '.$admiope.' value="desgravamen"><span lang="es">Producto Desgravamen</span><br/></span>
				 <span id="AUT" style="display:none;"><input class="child requerid" type="checkbox" name="cbAuto" '.$cbAuto.' '.$admiope.' value="automotores"><span lang="es">Producto Automotores</span><br/></span>
				 <span id="TRD" style="display:none;"><input class="child requerid" type="checkbox" name="cbTrd" '.$cbTrd.' '.$admiope.' value="todoriesgod"><span lang="es">Producto Todo Riesgo Domiciliario</span><br/></span>
				 <span id="TREM" style="display:none;"><input class="child requerid" type="checkbox" name="cbTrem" '.$cbTrem.' '.$admiope.' value="triesgoeqmov"><span lang="es">Producto Todo Riesgo Equipo Movil</span><br/></span>
				 <span id="TH" style="display:none;"><input class="child requerid" type="checkbox" name="cbTh" '.$cbTh.' '.$admiope.' value="tarjetahabiente"><span lang="es">Producto Tarjetahabiente</span><br/></span>
				 
				 <input class="child requerid" type="checkbox" name="cbCreaU" '.$cbCreaU.' '.$creaus.' value="creausuario"><span lang="es">Crear Usuarios</span><br/>
				 <input class="child requerid" type="checkbox" name="cbPol" '.$cbPol.' '.$admiope.' value="poliza"><span lang="es">'.utf8_encode('Administrar PÛlizas').'</span><br/>
				 <input class="child requerid" type="checkbox" name="cbEmail" '.$cbEmail.' '.$admiope.' value="email"><span lang="es">Administrar Correos electronicos</span><br/>
				 <input class="child requerid" type="checkbox" name="cbDepSuc" '.$cbDepSuc.' '.$admiope.' value="sucursal"><span lang="es">Administrar Regional</span><br/>
				 <input class="child requerid" type="checkbox" name="cbAgen" '.$cbAgen.' '.$admiope.' value="agencia"><span lang="es">Administrar Agencias</span><br/>
				 <input class="child requerid" type="checkbox" name="cbTipc" '.$cbTipc.' '.$admiope.' value="tipocambio"><span lang="es">Administrar Tipo de Cambio</span><br/>';
				 /*echo'
				 <input class="child requerid" type="checkbox" name="cbCia" '.$cbCia.' '.$admiope.' value="companias">Compa&ntilde;&iacute;as de Seguros<br>
				 <input class="child" type="checkbox" name="cbCorre" '.$cbCorre.' '.$admiope.' value="agenda">Correos<br>
				 <input class="child" type="checkbox" name="cbRepFacv" '.$cbRepFacv.' '.$facul.' value="diversotv">Reportes Facultativos Vehiculos<br>
				 <input class="child" type="checkbox" name="cbRepFact" '.$cbRepFact.' '.$facul.' value="diversotv">Reportes Facultativos Multiriesgo<br>
				 <input class="child" type="checkbox" name="cbLog" '.$cbLog.' '.$oficred.' value="ninguno">Oficial de cr&eacute;dito<br>
				 <input class="child" type="checkbox" name="cbCreus" '.$cbCreus.' '.$creaus.' value="creausuario">Crear Usuarios<br>
				 <input class="child" type="checkbox" name="cbRepSolPr" '.$cbRepSolPr.' '.$respso.' value="reportesol">Reportes Solicitudes Aprobadas<br>
				 <input class="child" type="checkbox" name="cbRepEcoSud" '.$cbRepEcoSud.' '.$repeco.' value="reporteeco">Reportes Bisa Sudamericana<br>
				 <input class="child" type="checkbox" name="cbRepLog" '.$cbRepLog.' '.$oficredrep.' value="farep">Acceso Reportes Generales (Oficial de Cr&eacute;dito)<br>';*/
			echo'<span class="errorMessage" id="errorseleccion" style="color:#d44d24; font-size:11px;" lang="es"></span>
				</div>
				<div class="da-button-row">
					<input type="button" value="Cancelar" class="da-button gray left" id="btnCancelar" lang="es"/>
					<input type="submit" value="Guardar" class="da-button green" name="btnUsuario" id="btnUsuario" lang="es"/>
					<input type="hidden" id="tipo_sesion" value="'.$_SESSION['tipo_sesion'].'"/>
					<input type="hidden" id="id_usuario_sesion" value="'.$_SESSION['id_usuario_sesion'].'"/>
					<input type="hidden" name="accionUsuarios" value="checkdatos"/>
				</div>
			</form>
		</div>
	</div>';
}

//FUNCION QUE PERMITE IMPORTAR USUARIOS ARCHIVO PLANO
function importar_archivo_usuarios($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
	//VEMOS SI EL USUARIO YA HIZO CLICK EN IMPORTAR
	if(isset($_POST['accionUpload'])) {
		    //incluimos la clase
			require_once 'phpexcel/Classes/PHPExcel/IOFactory.php';
			//cargamos el archivo que deseamos leer
			$objPHPExcel = PHPExcel_IOFactory::load('files/'.base64_decode($_POST['dc-attached']));
	        //Obtenemos los datos de la hoja activa (la primera)
			$objHoja = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			
			$vec2 = explode('|',$_POST['txtTipousuario']);
			$id_tipo = $conexion->real_escape_string($vec2[0]);
			$identidadf = $conexion->real_escape_string($_POST['identidadf']);
			$depto_regional = $_POST['departamento'];
			
			$insert_us = "INSERT INTO s_usuario(id_usuario, usuario, password, nombre, email, id_tipo, id_depto, activado, cambio_password, id_agencia, fono_agencia, fecha_creacion) VALUES ";
			$insert_ef = "INSERT INTO s_ef_usuario(id_eu, usuario, id_usuario, id_ef) VALUES ";
			
			//recorremos las filas obtenidas 
			foreach($objHoja as $iIndice => $objCelda){
				$agencia = $objCelda['A'];
				$name = $objCelda['B'];
				$user = $objCelda['E'];
				$password = $objCelda['F'];
				
				$select="select
						  id_agencia,
						  agencia,
						  id_depto,
						  id_ef
						from
						  s_agencia
						where
						  agencia='".$agencia."' and id_depto=".$depto_regional." and id_ef='".$identidadf."';";
				$sql = $conexion->query($select, MYSQLI_STORE_RESULT);
				if($sql->num_rows>0){
				   $reg = $sql->fetch_array(MYSQLI_ASSOC);
				   $id_agencia = "'".$reg['id_agencia']."'";		  	
				}else{
				   $id_agencia = 'null';
				}
								
				//generamos un idusuario unico encriptado
			    $prefijo = '@S#1$2013';
                $id_unico = '';
                $id_unico = uniqid($prefijo,true);
			
			    //encriptamos el password del usuario
			    $encrip_pass = crypt_blowfish_bycarluys($password);
				
				$insert_us.="('".$id_unico."', '".$user."', '".$encrip_pass."', '".$name."', '', ".$id_tipo.", ".$depto_regional.", 1, 0, ".$id_agencia.", '', curdate()), ";
				
				//INSERTAMOS LA ENTIDAD FINANCIERA
				$prefijo2 = '@S#1$2013';
				$id_unico2 = '';
				$id_unico2 = uniqid($prefijo2,true);
				$insert_ef.="('".$id_unico2."', '".$user."', '".$id_unico."', '".$identidadf."'), ";
				
			}
			$insert_us = trim(trim($insert_us),',');
			$insert_ef = trim(trim($insert_ef),',');
			if($conexion->query($insert_us)===TRUE){
				if($conexion->query($insert_ef)===TRUE){
					$mensaje="Se registro correctamente los datos del usuario ".$user;
					header('Location: index.php?l=usuarios&op=1&msg='.base64_encode($mensaje));
					exit;
				}else{
					$mensaje="Error en la consulta ".$conexion->errno.": ".$conexion->error;
					header('Location: index.php?l=usuarios&op=2&msg='.base64_encode($mensaje));
					exit;
				}
			}else{
				$mensaje="Error en la consulta ".$conexion->errno.": ".$conexion->error;
				header('Location: index.php?l=usuarios&op=2&msg='.base64_encode($mensaje));
				exit;
			}
			
			unlink('files/'.base64_decode($_POST['dc-attached']));
	}else{
		mostrar_importar_archivo_usuarios($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
	}
}

//FUNCION QUE PERMITE IMPORTAR Y GUARDAR VARIOS USUARIOS DE UN ARCHIVO PLANO
function mostrar_importar_archivo_usuarios($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
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
           		   		   	   
		   		   
		   //VALIDAR CAMPOS
		   $('#frmUploadUsuario').submit(function(e){
			  var variable = $('#txtTipousuario option:selected').prop('value');
			  var vec = variable.split('|');
			  var tipousuario = vec[1];
			  var departamento = $('#departamento option:selected').prop('value');
			  var regional = $('#regional option:selected').prop('value');
			  var identidadf = $('#identidadf option:selected').prop('value');
			  var attached = $('#dc-attached').prop('value');
			  
			  var sum=0;
			 			 
			  $(this).find('.requerid').each(function(){
				   				   
				   if(variable!=''){
					   $('#errortipousuario').hide('slow'); 
					   if(tipousuario!='FAC'){
						   if(identidadf!=''){
							  $('#erroref').hide('slow');
						   }else{
							  sum++;
							  $('#erroref').show('slow');
							  $('#erroref').html('seleccione entidad financiera');   
						   }
					   }
				   }else{
					   sum++;
					   $('#errortipousuario').show('slow');
					   $('#errortipousuario').html('seleccione tipo de usuario');
				   }
				   if(tipousuario!='IMP'){  
					   if(departamento!=''){
						   $('#errordepartamento').hide('slow');
					   }else{
						   sum++;
						   $('#errordepartamento').show('slow');
						   $('#errordepartamento').html('seleccione departamento');
					   }
				   }else{
					   if(regional!=''){
						   $('#errorregional').hide('slow');
						   $('#idmultiple_agency option').each(function() { 
								if($(this).is(':selected')){
								  //var valor = $(this).prop('value');
								  cell++;
								}
						   });
						   if(cell==0){
								sum++;
								$('#errormultiagency').show('slow');
								$('#errormultiagency').html('seleccione al menos una agencia');   
						   }else{
								$('#errormultiagency').hide('slow');  
						   }
					   }else{
						   sum++;
						   $('#errorregional').show('slow');
						   $('#errorregional').html('seleccione regional');   
					   }
				   }
				   if(attached!=''){
					   $('#erro_file').fadeOut('slow');
				   }else{
					   sum++;
					   $('#erro_file').fadeIn('slow');
					   $('#erro_file').html('seleccione un archivo'); 
				   }
				   
			  });
			  if(sum==0){
				  
			  }else{
			      e.preventDefault();
			  }
		   });
		   
		   //CONVERTIMOS A MINUSCULAS
		   $('#txtIdusuario').keyup(function() {
			  $(this).val($(this).val().toLowerCase());
		   });
		   
		   //HABILITAMOS ENTIDAD FINANCIERA VERIFICAMOS QUE TIPO DE USUARIO ELIGIO
		   //POSTERIOR VISUALIZAMOS EL SELECT O SELECT MULTIMPLE
		   $('#txtTipousuario').change(function(){
			  var variable = $(this).prop('value');
			  if(variable!=''){
				  var vec = variable.split('|');
				  var tipousuario=vec[1];
				  //habilitar/dsabilitar msg en permisos checkbox
				  if(tipousuario=='FAC' || tipousuario=='LOG' || tipousuario=='REP' || tipousuario=='IMP'){
					  $('#errorseleccion').hide('slow');
				  }
				  
				  if(tipousuario=='FAC'){
					  $('#departamento option[value=""]').prop('selected',true);
					  $('#content-agency').fadeOut('slow');
					  $('#content-regional').fadeOut('slow');
					  $('#content-departamento').fadeIn('slow');
				  }else if(tipousuario=='IMP'){
					  $('#content-departamento').fadeOut('slow');
					  $('#content-agency').fadeOut('slow');
					   
				  }else{
					  $('#content-departamento').fadeIn('slow');
					  $('#content-regional').fadeOut('slow');
					  $('#content-agency').fadeOut('slow');  
				  }
				  $('#content-entidadf').fadeIn('slow');
				  var dataString = 'tipousuario='+tipousuario+'&opcion=buscar_entidad';
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
				  $('#departamento option[value=""]').prop('selected',true);
				  $("#departamento").attr("disabled", true);
				  $('#content-agency').fadeOut('slow'); 
			  }
		   });
		   
		         
       });
    </script>
    <script type="text/javascript" src="js/ajaxupload.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
<?php	
	echo'<div class="da-panel collapsible">
			<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
				<ul class="action_user">
					<li style="margin-right:6px;">
					   <a href="?l=usuarios" class="da-tooltip-s" title="Volver atras">
					   <img src="images/retornar.png" width="32" height="32"></a>
					</li>
				</ul>
			</div>
		 </div>';
		 
   echo'<div class="da-panel" style="width:650px;">
		  <div class="da-panel-header">
			  <span class="da-panel-title">
				  <img src="images/icons/black/16/pencil.png" alt="" />
				  Importar usuarios de un texto plano
			  </span>
		  </div>
		  <div class="da-panel-content">
			  <form class="da-form" name="frmUploadUsuario" id="frmUploadUsuario" action="" method="post">
				  <div class="da-form-row">
					  <label style="text-align:right;"><b>Tipo de usuario</b></label>
					  <div class="da-form-item small">';
						  $selectTi="select 
										  id_tipo,
										  tipo,
										  codigo
									  from
										  s_usuario_tipo
									  where
										  codigo!='ROOT' and codigo!='ADM' and codigo!='OPR' and codigo!='FAC' 
										  and codigo!='CRU' and codigo!='REP' and codigo!='IMP';";
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
				  <div class="da-form-row" style="display: none;" id="content-entidadf">
					  <label style="text-align:right;"><b>Entidad Financiera</b></label>
					  <div class="da-form-item large">
					   <span id="entidad-loading" class="loading-entf"></span>
					  </div>
				  </div>	
				  <div class="da-form-row" id="content-departamento">
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
								$rsdep->free();
						  echo'</select>';
						  
					   echo'<span class="errorMessage" id="errordepartamento"></span>
					  </div>
				  </div>
				  <div class="da-form-row" style="display: none;" id="content-agency">
					<label style="text-align:right;"><b>Agencia</b></label>
					<div class="da-form-item small">
					  <span id="response-loading"></span>
					</div>	
				  </div>  
				  <div class="da-form-row">
					  <label style="text-align:right;"></label>
					  <div class="da-form-item large">
					    <div class="content-input" style="width:auto;">
							<a href="javascript:;" id="a-dc-attached" class="attached">Seleccione archivo</a>
							<div class="attached-mess" style="width:220px; margin-top:2px; margin-left:0;">
								El formato del archivo a subir debe ser TXT
							</div>
							<span class="errorMessage" id="erro_file"></span>';
							?>
							<script type="text/javascript">
							set_ajax_upload('dc-attached', 'US');
							</script>
                            <?php
					echo'</div>	  
					  </div>
				  </div>
				  <div class="da-button-row">
					  <input type="submit" value="Importar Usuarios" class="da-button green" name="btnUsuario" id="btnUsuario"/>
					  <input type="hidden" id="tipo_sesion" value="'.$_SESSION['tipo_sesion'].'"/>
					  <input type="hidden" id="id_usuario_sesion" value="'.$_SESSION['id_usuario_sesion'].'"/>
					  <input type="hidden" name="accionUpload" value="checkdatos"/>
					  <input type="hidden" id="dc-attached" name="dc-attached" value="" class="requerid"/>
				  </div>
			  </form>
		  </div>
		</div>';
} 


//FUNCION PARA EDITAR UN USUARIO
function editar_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion) {

	$errFlag = false;
	$errArr['email'] = '';
	$errArr['paginas'] = '';
	$errArr['depar'] = '';
	$errArr['fonoagencia'] = '';
	$errArr['tipousu'] = '';

	$idusuario = base64_decode($_GET['idusuario']);
	//$idusuario = strtolower($idusuario);

	//SI ESTAMOS LOGUEADOS COMO 'ROOT', PODEMOS EDITAR CUALQUIER USUARIO
	if($tipo_sesion=='ROOT') {
		if(isset($_POST['accionEditar'])) {			 						

			if($errFlag) {
				//MOSTRAMOS EL FORMULARIO CON LOS ERRORES
				mostrar_editar_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
			} else {
				//GUARDAMOS LOS CAMBIOS EN LA BASE DE DATOS
                //echo $_POST['id_agencia'];
				//IMPLEMENTAMOS SEGURIDAD
				if($idusuario!=$id_usuario_sesion) {
					$vec2=array();
					$vec2=explode('|',$_POST['txtTipousuario']);
					$id_tipo = $conexion->real_escape_string($vec2[0]);
					$tipouser_text=$vec2[1];
					if($tipouser_text!='IMP'){
						 if($tipouser_text!='REP'){
							 $depto_regional = $_POST['departamento'];
						 }else{
							if(!empty($_POST['departamento'])){
								$depto_regional = $_POST['departamento'];
							}else{
								$depto_regional = 'null';
							}
						 }
					}else{
						$depto_regional=$_POST['regional'];
					}
					
				} 
				
				if(isset($_POST['txtNombre'])) $nombre = $conexion->real_escape_string($_POST['txtNombre']);
				else $nombre = '';
				if(isset($_POST['txtEmail'])) $email = $conexion->real_escape_string($_POST['txtEmail']);
				else $email = '';
				
				if($tipouser_text!='FAC' and $tipouser_text!='IMP'){
					echo 'hola';
					if(!empty($_POST['id_agencia'])){
						$id_agencia = "'".$_POST['id_agencia']."'";
					}else{
						$id_agencia = 'null';
					}
				}else{
					$id_agencia = 'null';
				}
				
				$fono_agencia = $conexion->real_escape_string($_POST['txtFonoAgencia']);
				

				$update = "UPDATE s_usuario SET nombre='".$nombre."',";
				if($idusuario!=$id_usuario_sesion) {				   
				   $update .= " id_tipo=".$id_tipo.", id_depto=".$depto_regional.", id_agencia=".$id_agencia.",";
				}
				$update .= " email='".$email."', fono_agencia='".$fono_agencia."' "
				."WHERE id_usuario='".$idusuario."' LIMIT 1";
				if($conexion->query($update) === TRUE){ $resultado=TRUE;}else{ $resultado=FALSE;}
                 
				if($idusuario!=$id_usuario_sesion){
					echo $tipouser_text;
					
					$delete = "DELETE FROM s_ef_usuario WHERE id_usuario='".$idusuario."'";
					if($conexion->query($delete) === TRUE){ $resultado=TRUE;}else{ $resultado=FALSE;}
					$usuario = $conexion->real_escape_string($_POST['usuario']);
					if($tipouser_text!='FAC' and $tipouser_text!='IMP'){
						 $identidadf = $conexion->real_escape_string($_POST['identidadf']);
						 //INSERTAMOS LA ENTIDAD FINANCIERA
						 $prefijo2 = '@S#1$2013';
						 $id_unico2 = '';
						 $id_unico2 = uniqid($prefijo2,true);
						 $insert_ef = "INSERT INTO s_ef_usuario(id_eu, usuario, id_usuario, id_ef) VALUES('".$id_unico2."', '".$usuario."', '".$idusuario."', '".$identidadf."');";
						 if($conexion->query($insert_ef) === TRUE){ $resultado=TRUE;}else{ $resultado=FALSE;}		
					}elseif($tipouser_text=='FAC'){
						$vecidmultiple=$_POST['idmultiple'];
						$cant=count($vecidmultiple);	
						//INSERTAMOS LAS ENTIDADES FINANCIERAS
						for($i=0;$i<$cant;$i++){
							//generamos un idusuario unico encriptado
							 $prefijo2='@S#1$2013';
							 $id_unico2='';
							 $id_unico2=uniqid($prefijo2,true);
							 $insert_ef = "INSERT INTO s_ef_usuario(id_eu, usuario, id_usuario, id_ef) VALUES('".$id_unico2."', '".$usuario."', '".$idusuario."', '".$vecidmultiple[$i]."');";
							 if($conexion->query($insert_ef) === TRUE){ $resultado=TRUE;}else{ $resultado=FALSE;}
						}
					}elseif($tipouser_text=='IMP'){
						//INSERTAMOS LA ENTIDAD FINANCIERA
						 $identidadf = $conexion->real_escape_string($_POST['identidadf']);
						 $prefijo2 = '@S#1$2013';
						 $id_new_eu = '';
						 $id_new_eu = uniqid($prefijo2,true);
						 $insert_eu = "INSERT INTO s_ef_usuario(id_eu, usuario, id_usuario, id_ef) VALUES('".$id_new_eu."', '".$usuario."', '".$idusuario."', '".$identidadf."');";
						 //echo $insert_eu;
						 if($conexion->query($insert_eu) === TRUE){ $resultado=TRUE;}else{ $resultado=FALSE;}	
						
						 $deleteag = "DELETE FROM s_im_usuario_agencia WHERE id_usuario='".$idusuario."'";
						 if($conexion->query($deleteag) === TRUE){ $resultado=TRUE;}else{ $resultado=FALSE;}
						
						 $vecidmultiple=$_POST['idmultiple_agency'];
						 $cant=count($vecidmultiple);	
						 //INSERTAMOS LAS ENTIDADES FINANCIERAS
						 for($i=0;$i<$cant;$i++){
							//generamos un idusuario unico encriptado
							 $prefijo3='@S#1$2013';
							 $id_new_age='';
							 $id_new_age=uniqid($prefijo3,true);
							 $insert_age = "INSERT INTO s_im_usuario_agencia(id_user_age, usuario, id_usuario, id_agencia) VALUES('".$id_new_age."', '".$usuario."', '".$idusuario."', '".$vecidmultiple[$i]."');";
							 if($conexion->query($insert_age) === TRUE){ $resultado=TRUE;}else{ $resultado=FALSE;}
						 }
				    }
					
				} 
				 
				//VERIFICAMOS SI HUBO ALGUN ERROR AL INGRESAR EN LA TABLA
			    if($resultado){ 
				      //VERIFICAMOS EL TIPO DE USUARIO NO ES [FAC/LOG/REP/IMP]
				      if($tipouser_text!='FAC' and $tipouser_text!='LOG' and $tipouser_text!='REP' and $tipouser_text!='IMP'){
							//SOLO CAMBIAMOS LOS PERMISOS, SI EL USUARIO NO ES 'ROOT'
							if($idusuario!=$id_usuario_sesion) {
			
								$delete = "DELETE FROM s_usuario_permiso WHERE id_usuario='".$idusuario."'";
								if($conexion->query($delete) === TRUE){ $respuesta=TRUE;}else{ $respuesta=FALSE;}
								
								//METEMOS LOS PERMISOS DEL USUARIO A LA TABLA TBLUSUARIOSPERMISOS
								if(isset($_POST['cbIni'])) {
									$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
									."VALUES(null, '".$idusuario."', 'inicio')";
									if($conexion->query($insert_permiso) === TRUE){ $respuesta=TRUE;}else{ $respuesta=FALSE;}
								}
								if(isset($_POST['cbFor'])) {
									$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
									."VALUES(null, '".$idusuario."', '".$_POST['cbFor']."')";
									if($conexion->query($insert_permiso) === TRUE){ $respuesta=TRUE;}else{ $respuesta=FALSE;}
								}
								if(isset($_POST['cbCia'])) {
									$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
									."VALUES(null, '".$idusuario."', 'compania')";
									if($conexion->query($insert_permiso) === TRUE){ $respuesta=TRUE;}else{ $respuesta=FALSE;}
								}
								if(isset($_POST['cbDes'])) {
									$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
									."VALUES(null, '".$idusuario."', '".$_POST['cbDes']."')";
									if($conexion->query($insert_permiso) === TRUE){ $respuesta=TRUE;}else{ $respuesta=FALSE;}
								}
								if(isset($_POST['cbPol'])) {
									$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
									."VALUES(null, '".$idusuario."', '".$_POST['cbPol']."')";
									if($conexion->query($insert_permiso) === TRUE){ $respuesta=TRUE;}else{ $respuesta=FALSE;}
								}
								
								if(isset($_POST['cbCreaU'])) {
									$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
									."VALUES(null, '".$idusuario."', '".$_POST['cbCreaU']."')";
									if($conexion->query($insert_permiso) === TRUE){ $respuesta=TRUE;}else{ $respuesta=FALSE;}
								}
								
								if(isset($_POST['cbEmail'])){
									$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
									."VALUES(null, '".$idusuario."', '".$_POST['cbEmail']."')";
									if($conexion->query($insert_permiso) === TRUE){ $respuesta=TRUE;}else{ $respuesta=FALSE;}
								}
								if(isset($_POST['cbAgen'])){
									$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
									."VALUES(null, '".$idusuario."', '".$_POST['cbAgen']."')";
									if($conexion->query($insert_permiso) === TRUE){ $respuesta=TRUE;}else{ $respuesta=FALSE;}
								}
								
								if(isset($_POST['cbAuto'])){
									$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
									."VALUES(null, '".$idusuario."', '".$_POST['cbAuto']."')";
									if($conexion->query($insert_permiso) === TRUE){ $respuesta=TRUE;}else{ $respuesta=FALSE;}
								}
								if(isset($_POST['cbTrd'])){
									$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
									."VALUES(null, '".$idusuario."', '".$_POST['cbTrd']."')";
									if($conexion->query($insert_permiso) === TRUE){ $respuesta=TRUE;}else{ $respuesta=FALSE;}
								}
								if(isset($_POST['cbTrem'])){
									$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
									."VALUES(null, '".$idusuario."', '".$_POST['cbTrem']."')";
									if($conexion->query($insert_permiso) === TRUE){ $respuesta=TRUE;}else{ $respuesta=FALSE;}
								}
								if(isset($_POST['cbDepSuc'])){
									$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
									."VALUES(null, '".$idusuario."', '".$_POST['cbDepSuc']."')";
									if($conexion->query($insert_permiso) === TRUE){ $respuesta=TRUE;}else{ $respuesta=FALSE;}
								}
								if(isset($_POST['cbTipc'])){
									$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
									."VALUES(null, '".$idusuario."', '".$_POST['cbTipc']."')";
									if($conexion->query($insert_permiso) === TRUE){ $respuesta=TRUE;}else{ $respuesta=FALSE;}
								}
								if(isset($_POST['cbTh'])){
									$insert_permiso = "INSERT INTO s_usuario_permiso(id_permiso, id_usuario, pagina) "
									."VALUES(null, '".$idusuario."', '".$_POST['cbTh']."')";
									if($conexion->query($insert_permiso) === TRUE){ $respuesta=TRUE;}else{ $respuesta=FALSE;}
								}
								//MOSTRAMOS EL MENSAJE DE EDICION DE DATOS
								if($respuesta){
								   //REALIZAMOS EL CAMBIO DE CONTRASE—A
								   $mensaje='Se edito correctamente los datos del usuario '.$_POST['usuario'];
								   header('Location: index.php?l=usuarios&op=1&msg='.base64_encode($mensaje));
								} else{
								   $mensaje="Hubo un error al actualizar los datos, consulte con su administrador ".$conexion->errno().": ".$conexion->error;
								   header('Location: index.php?l=usuarios&op=2&msg='.base64_encode($mensaje));
								} 
							
							}else{
								 //REALIZAMOS EL CAMBIO DE DATOS DEL SUPER ADMINISTRADOR
								 $mensaje='Se edito correctamente los datos del usuario '.$_POST['usuario'];
								 header('Location: index.php?l=usuarios&op=1&msg='.base64_encode($mensaje));
							}
					  }else{
						  //REALIZAMOS EL CAMBIO DE DATOS DEL SUPER ADMINISTRADOR
						  $mensaje='Se edito correctamente los datos del usuario '.$_POST['usuario'];
						  header('Location: index.php?l=usuarios&op=1&msg='.base64_encode($mensaje));
					  }
				}else{
				    $mensaje="Hubo un error al actualizar los datos, consulte con su administrador ".$conexion->errno.": ".$conexion->error;
				    header('Location: index.php?l=usuarios&op=2&msg='.$mensaje);
				}
				
			}

		} else {
			//MOSTRAMOS EL FORMULARIO PARA EDITAR AL USUARIO
			mostrar_editar_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
		}
	} else {
		//SI NO ESTAMOS LOGUEADOS COMO ADMINISTRADOR, SOLO PODEMOS EDITAR NUESTRO USUARIO
		if($id_usuario_sesion==$idusuario) {

			if(isset($_POST['accionEditar'])) {
				//SI EL USUARIO HIZO CLICK EN EL BOTON PARA EDITAR EL REGISTRO, VALIDAMOS LOS DATOS

				//CORREO ELECTRONICO
				if(!empty($_POST['txtEmail'])) {
					if(validar_email($_POST['txtEmail'])) {
						
					} else {
						$errFlag = true;
						$errArr['email'] = 'La direcci&oacute;n de correo ingresada no es '
						.'v&aacute;lida';
					}
				}

				if($errFlag) {
					//MOSTRAMOS EL FORMULARIO CON LOS ERRORES
					mostrar_editar_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
				} else {
					//GUARDAMOS LOS CAMBIOS EN LA BASE DE DATOS

					//IMPLEMENTAMOS SEGURIDAD
					if(isset($_POST['txtNombre'])) {
						$nombre = $conexion->real_escape_string($_POST['txtNombre']);
					} else {
						$nombre = '';
					}
					if(isset($_POST['txtEmail'])) {
						$email = $conexion->real_escape_string($_POST['txtEmail']);
					} else {
						$email = '';
					}

                    $fono_agencia = $conexion->real_escape_string($_POST['txtFonoAgencia']);
                     
					$update = "UPDATE s_usuario SET nombre='".$nombre."', email='".$email."', fono_agencia='".$fono_agencia."' "
					."WHERE id_usuario='".$idusuario."' LIMIT 1";
					//$rs = mysql_query($update, $conexion);
					
                     if($conexion->query($update) === TRUE){ 
					     $mensaje='Se edito correctamente los datos del usuario '.$_POST['usuario'];
						 header('Location: index.php?l=usuarios&op=1&msg='.base64_encode($mensaje));
					 }else{
					     $mensaje="Hubo un error al actualizar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
				         header('Location: index.php?l=usuarios&op=2&msg='.base64_encode($mensaje));	 
					 }
					
				}
			} else {
				//MOSTRAMOS EL FORMULARIO PARA EDITAR AL USUARIO
				mostrar_editar_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
			}
		} else {
			//SI UN USUARIO OPERADOR INTENTA EDITAR OTRO USUARIO, VOLVEMOS A LA LISTA DE USUARIOS
			header('Location: index.php?l=usuarios');
		}
	}
}

//VISUALIZAMOS EL FORMULARIO PARA EDITAR EL USUARIO
function mostrar_editar_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr){
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
			   $(location).prop('href','index.php?l=usuarios');
		   });
		   
		   //SELECT DEPARTAMANTO - FUNCION QUE PERMITE BUSCAR LA AGENCIA DE UN
		   //DETERMINADO DEPARTAMENTO O REGION
		   $('#departamento').change(function(e) {
			   
			   var id_departamento = $(this).attr('value');
			   var identidadf = $('#identidadf option:selected').prop('value');
			   var variable = $('#txtTipousuario option:selected').prop('value');
			   var tipo_sesion = $('#tipo_sesion').prop('value');
			   var id_usuario_sesion = $('#idusuariosesion').prop('value');
			   
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
				  $('#content-agency').fadeOut('slow');
			   }
			   e.preventDefault();
		   });
		   
		   //VALIDAR CAMPOS
		   $('#frmEditaUsuario').submit(function(e){
			  //alert('hola');
			  var variable = $('#txtTipousuario option:selected').prop('value');
			  //alert(variable);
			  var vec = variable.split('|');
			  var tipousuario = vec[1];
			  var departamento = $('#departamento option:selected').prop('value');
			  var regional = $('#regional option:selected').prop('value');
			  var usuario = $('#txtIdusuario').prop('value');
			  var nombre = $('#txtNombre').prop('value');
			  var fonoagencia = $('#txtFonoAgencia').prop('value');
			  var email = $('#txtEmail').prop('value');
			  var identidadf = $('#identidadf option:selected').prop('value');
			  var idusuario = $('#idusuario').prop('value')
			  var id_usuario_sesion = $('#idusuariosesion').prop('value');
			  var tipo_sesion = $('#tipo_sesion').prop('value')
			  
			  var sum=0;
			  var miarray = new Array();
			  miarray=[ "cbIni", "cbFor", "cbCia", "cbDes", "cbPol", "cbCreaU", "cbEmail", "cbAgen", "cbAuto", "cbTrd", "cbTrem", "cbDepSuc", "cbTipc", "cbTh" ];
			  var numelem=miarray.length;
			  //alert('idusuario:'+idusuario+' id_usuario_sesion:'+id_usuario_sesion+' tipo_sesion:'+tipo_sesion);
			  //alert('tipousuario:'+tipousuario);
			  //alert('regional:'+regional);
			  var chek=0; var ds=0; var cell=0;
			  $(this).find('.requerid').each(function(){
				   if(idusuario!=id_usuario_sesion && tipo_sesion=='ROOT') {
					   if(variable!=''){
						   $('#errortipousuario').hide('slow'); 
						   if(tipousuario!='FAC'){
							   if(identidadf!=''){
								  $('#erroref').hide('slow');
							   }else{
								  sum++;
								  $('#erroref').show('slow');
								  $('#erroref').html('seleccione entidad financiera');   
							   }
						   }else if(tipousuario=='FAC'){
								  $('#idmultiple option').each(function() { 
									  if($(this).is(':selected')){
										//var valor = $(this).prop('value');
										cell++;
									  }
								  });
								  if(cell==0){
									  sum++;
									  $('#erroref').show('slow');
									  $('#erroref').html('seleccione al menos una entidad financiera');   
								  } 
						   }else if(tipousuario=='IMP'){
								  
						   }
					   }else{
						   sum++;
						   $('#errortipousuario').show('slow');
						   $('#errortipousuario').html('seleccione tipo de usuario');
					   }
					   
					   if(tipousuario!='IMP'){  
						   if(departamento!=''){
							   $('#errordepartamento').hide('slow');
						   }else{
							   if(tipousuario!='REP'){
								   sum++;
								   $('#errordepartamento').show('slow');
								   $('#errordepartamento').html('seleccione departamento');
							   }
						   }
					   }else{
						   if(regional!=''){
							   $('#errorregional').hide('slow');
							   if(tipousuario!='IMP'){
								   $('#idmultiple_agency option').each(function() { 
										if($(this).is(':selected')){
										  //var valor = $(this).prop('value');
										  cell++;
										}
								   });
								   if(cell==0){
										sum++;
										$('#errormultiagency').show('slow');
										$('#errormultiagency').html('seleccione al menos una agencia');   
								   }else{
										$('#errormultiagency').hide('slow');  
								   }
							   }
						   }else{
							   sum++;
							   $('#errorregional').show('slow');
							   $('#errorregional').html('seleccione regional');   
						   }
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
				   }else{
					   sum++;
					   $('#errornombre').show('slow');
					   $('#errornombre').html('ingrese nombre completo');   
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
				   }/*else{
					   sum++;
					   $('#erroremail').show('slow');
					   $('#erroremail').html('ingrese correo electronico');
				   }*/
				   
				   if(tipo_sesion=='ROOT' && idusuario!=id_usuario_sesion) {
					   $.each( miarray, function( i, l ){
						   if($('input[name="'+l+'"]').is(':enabled')){
							   ds++;
							   if($('input[name="'+l+'"]').is(":checked")){
								  chek++;
							   } 
						   }else{
								  
						   }
						  //alert( "Index #" + i + ": " + l );
					   });
					   if(ds!=0){
						   if(chek!=0){
							   $('#errorseleccion').hide('slow');
						   }else{
							   sum++;
							   $('#errorseleccion').show('slow');
							   $('#errorseleccion').html('Debe de seleccionar al menos un elemento');
						   } 
					   }
				   }
  
			  });
			  if(sum==0){
				  
			  }else{
				  e.preventDefault();
			  }
			  //e.preventDefault();
		   });
		   
		   //CONVERTIMOS A MINUSCULAS
		   $('#txtIdusuario').keyup(function() {
			  $(this).val($(this).val().toLowerCase());
		   });
		   
		   //HABILITAMOS ENTIDAD FINANCIERA VERIFICAMOS QUE TIPO DE USUARIO ELIGIO
		   //POSTERIOR VISUALIZAMOS EL SELECT O SELECT MULTIMPLE
		   $('#txtTipousuario').change(function(){
			  var variable = $(this).prop('value');
			  if(variable!=''){
				  var vec = variable.split('|');
				  var tipousuario=vec[1];
				  //habilitar/dsabilitar msg en permisos checkbox
				  if(tipousuario=='FAC' || tipousuario=='LOG' || tipousuario=='REP' || tipousuario=='IMP'){
					  $('#errorseleccion').hide('slow');
				  }
				  
				  if(tipousuario=='REP'){
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
				  
				  if(tipousuario=='FAC'){
					  $('#departamento option[value=""]').prop('selected',true);
					  $('#content-agency').fadeOut('slow');
					  $('#content-regional').fadeOut('slow');
					  $('#content-departamento').fadeIn('slow'); 
				  }else if(tipousuario=='IMP'){
					  $('#content-departamento').fadeOut('slow');
					  $('#content-agency').fadeOut('slow');
				  }else{
					  $('#content-departamento').fadeIn('slow');
					  $('#content-regional').fadeOut('slow');
					  $('#content-agency').fadeOut('slow');  
				  }
				  $('#content-entidadf').fadeIn('slow');
				  var dataString = 'tipousuario='+tipousuario+'&opcion=buscar_entidad';
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
				  $('#departamento option[value=""]').prop('selected',true);
				  $("#departamento").attr("disabled", true);
				  $('#content-agency').fadeOut('slow'); 
			  }
		   });
		   
		   
		   $('#identidadf').change(function(){
			   var identidadf=$(this).prop('value');
			   //alert(identidadf);
			   var tipousuario=$('#txtTipousuario option:selected').prop('value');
			   var vec=tipousuario.split('|');
			   //alert(vec[1]);
			   if(vec[1]=='IMP'){
				   var dataString = 'identidadf='+identidadf+'&opcion=buscar_regional';
				   $.ajax({
						 async: true,
						 cache: false,
						 type: "POST",
						 url: "buscar_registro.php",
						 data: dataString,
						 success: function(datareturn) {
							  //alert(datareturn);
							  $('#response-regional').html(datareturn);
								
						 }
				   });   
			   }else if(vec[1]=='ADM'){
					$('#DES').fadeOut('slow'); $('#AUT').fadeOut('slow');
					$('#TRD').fadeOut('slow'); $('#TREM').fadeOut('slow');
					$('#TH').fadeOut('slow');
					 var dataString = 'idef='+identidadf+'&opcion=busca_producto_entidad';
					 $.ajax({
						   async: true,
						   cache: false,
						   type: "POST",
						   url: "buscar_registro.php",
						   data: dataString,
						   dataType:"json",
						   success: function(datavec) {
								$.each(datavec, function( index, value ) {
									 //alert(value);
									 if(value=='DE'){
										$('#DES').fadeIn('slow');
									 }else if(value=='AU'){
										$('#AUT').fadeIn('slow');
									 }else if(value=='TRD'){
										$('#TRD').fadeIn('slow'); 
									 }else if(value=='TRM'){
										$('#TREM').fadeIn('slow');  
									 }else if('#TH'){
										$('#TH').fadeIn('slow');
									 }
								});    
						   }
					 }); 
			   }
		   });
		   
	   });
    </script>
 
 <?php
	
	//SEGURIDAD
	$opera='';
	$admiope='';
	$creaus='';
	$oficred='';
	$facul='';
	$implante='';
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
					ut.tipo as tipo_usuario,
					su.id_usuario,
					ut.codigo
				from
					s_usuario as su
					inner join s_usuario_tipo as ut on (ut.id_tipo=su.id_tipo)
				where
					su.id_usuario = '".$idusuario."'
				limit 0 , 1;";
	$rs = $conexion->query($select, MYSQLI_STORE_RESULT);
	$num = $rs->num_rows;
	
	//SI EXISTE EL USUARIO DADO EN LA BASE DE DATOS, LO EDITAMOS
	if($num) {

		$fila = $rs->fetch_array(MYSQLI_ASSOC);
        $rs->free();
		//RECORDAMOS LOS VALORES INGRESADOS ANTERIORMENTE
		if(isset($_POST['txtTipousuario'])) $txtTipousuario = $_POST['txtTipousuario'];else $txtTipousuario = $fila['id_tipo'];
		if($fila['codigo']!='IMP'){
		    if(isset($_POST['departamento'])) $departamento = $_POST['departamento']; else $departamento = $fila['id_depto'];
		}else{
			if(isset($_POST['regional'])) $regional = $_POST['regional']; else $regional = $fila['id_depto'];
		}
		if(isset($_POST['txtNombre'])) $txtNombre = $_POST['txtNombre']; else $txtNombre = $fila['nombre'];
		if(isset($_POST['txtEmail'])) $txtEmail = $_POST['txtEmail']; else $txtEmail = $fila['email'];
		if(isset($_POST['txtFonoAgencia'])) $txtFonoAgencia = $_POST['txtFonoAgencia']; else $txtFonoAgencia = $fila['fono_agencia'];
		if(isset($_POST['id_agencia'])) $id_agencia = $_POST['id_agencia']; else $id_agencia = $fila['id_agencia'];
		
		if(isset($_POST['cbIni'])) $cbIni = 'checked'; else $cbIni = 'unchecked';
		if(isset($_POST['cbFor'])) $cbFor = 'checked'; else $cbFor = 'unchecked';
		if(isset($_POST['cbCia'])) $cbCia = 'checked'; else $cbCia = 'unchecked';
		if(isset($_POST['cbDes'])) $cbDes = 'checked'; else $cbDes = 'unchecked';
		if(isset($_POST['cbPol'])) $cbPol = 'checked'; else $cbPol = 'unchecked';
		if(isset($_POST['cbCreaU'])) $cbCreaU = 'checked'; else $cbCreaU = 'unchecked';
		if(isset($_POST['cbEmail'])) $cbEmail = 'checked'; else $cbEmail = 'unchecked';
		//if(isset($_POST['cbOcupa'])) $cbOcupa = 'checked'; else $cbOcupa = 'unchecked';
		//if(isset($_POST['cbForpag'])) $cbForpag = 'checked'; $cbForpag = 'unchecked';
	    //if(isset($_POST['cbEstado'])) $cbEstado = 'checked'; $cbEstado = 'unchecked';
	    //if(isset($_POST['cbCertMed'])) $cbCertMed = 'checked'; $cbCertMed = 'unchecked';
	    if(isset($_POST['cbDepSuc'])) $cbDepSuc = 'cheked'; $cbDepSuc = 'unchecked';
		if(isset($_POST['cbAgen'])) $cbAgen = 'checked'; else $cbAgen = 'unchecked';
		if(isset($_POST['cbAuto'])) $cbAuto = 'checked'; else $cbAuto = 'unchecked';
	    if(isset($_POST['cbTrd'])) $cbTrd = 'checked'; else $cbTrd = 'unchecked';
	    if(isset($_POST['cbTrem'])) $cbTrem = 'checked'; else $cbTrem = 'unchecked';
		if(isset($_POST['cbTipc'])) $cbTipc = 'checked'; else $cbTipc = 'unchecked';
		if(isset($_POST['cbTh'])) $cbTh = 'checked'; else $cbTh = 'unchecked';
		
		//HABILITAMOS O DESHABILITAMOS,LOS CHECKBOX DE ACUERDO AL TIPO DE USUARIO
		
		if($fila['codigo']!=''){
		   if($fila['codigo']=='ADM'){//ADMINISTRADOR
			   $opera='enabled';
			   $admiope='enabled';
			   $creaus='enabled';
			   $oficred='disabled';
			   
		   }elseif($fila['codigo']=='OPR'){//OPERADOR
			   $opera='enabled';
			   $admiope='disabled';
			   $creaus='disabled';
			   $oficred='disabled';
			   
		   }elseif($fila['codigo']=='FAC'){//FACULTATIVO
			   $opera='disabled';
			   $admiope='disabled';
			   $creaus='disabled';
			   $oficred='disabled';
			  
		   }elseif($fila['codigo']=='LOG'){//OFICIAL DE CREDITO
			   $opera='disabled';
			   $admiope='disabled';
			   $creaus='disabled';
			   $oficred='enabled';
			   
		   }elseif($fila['codigo']=='CRU'){//CREAUSUARIO
			   $opera='disabled';
			   $admiope='disabled';
			   $creaus='enabled';
			   $oficred='disabled';
			  
		   }elseif($fila['codigo']=='IMP'){//IMPLANTE
			   $opera='disabled';
			   $admiope='disabled';
			   $creaus='disabled';
			   $oficred='disabled';
			   
		   }elseif($fila['codigo']=='REP'){//REPORTES
			   $opera='disabled';
			   $admiope='disabled';
			   $creaus='disabled';
			   $oficred='disabled';
			   
		   }
		}
		
		//SACAMOS LOS PERMISOS DEL USUARIO
		if(!isset($_POST['accionEditar']) && $tipo_sesion=='ROOT') {

			$select2 = "SELECT DISTINCT pagina FROM s_usuario_permiso WHERE id_usuario='".$idusuario."'";
			$rs2 = $conexion->query($select2, MYSQLI_STORE_RESULT);
			while($fila2 = $rs2->fetch_array(MYSQLI_NUM)) {
				switch($fila2[0]) {
					case 'inicio':
						$cbIni = 'checked';
						break;
					case 'formularios':
						$cbFor = 'checked';
						break;
					case 'desgravamen':
						$cbDes = 'checked';
						break;	
					case 'automotores':
					    $cbAuto = 'checked';
						break;
					case 'todoriesgod':
					    $cbTrd = 'checked';
						break;
					case 'triesgoeqmov':
					    $cbTrem = 'checked';
						break;
					case 'creausuario':
						$cbCreaU = 'checked';
						break;
					case 'poliza':
						$cbPol = 'checked';
						break;	
					case 'email':
						$cbEmail = 'checked';
						break;	
					case 'sucursal':
						$cbDepSuc = 'checked';
						break;
					case 'agencia':
						$cbAgen = 'checked';
						break;
					case 'tipocambio':
					    $cbTipc = 'checked';
						break;
					case 'tarjetahabiente':
					    $cbTh = 'checked';
						break; 					
				}
			}
			$rs2->free();
		}
	//SACAMOS LAS ENTIDADES FINANCIERAS DEL USUARIO SIEMPRE Y CUANDO ESTE
	//TENGA PERMISO FACULTATIVO
	$selectEnFi="select 
					sef.id_ef as idef, sef.nombre as ef_nombre
				from
					s_entidad_financiera as sef
						left join
					s_ef_usuario as seu ON (seu.id_ef = sef.id_ef)
						left join
					s_usuario as su ON (su.id_usuario = seu.id_usuario)
				where
					su.id_usuario = '".$fila['id_usuario']."'
						or seu.id_usuario = '".$fila['id_usuario']."'
				order by sef.id_ef asc;";
	//echo $selectEnFi;
	$resenfi = $conexion->query($selectEnFi,MYSQLI_STORE_RESULT);
	$vec[]=array();
	$i=0;
	while($regienfi = $resenfi->fetch_array(MYSQLI_ASSOC)){
		$vec[$i]=$regienfi['idef'];
		$i++;
	}				
	$resenfi->free();
	if($fila['codigo']!='IMP'){
		$display_d='';
		$display_r='style="display: none;"';
	}else{
		$display_d='style="display: none;"';
		$display_r='';
    }
	
	echo'<div class="da-panel collapsible">
		  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			  <ul class="action_user">
				  <li style="margin-right:6px;">
					 <a href="?l=usuarios" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
					 <img src="images/retornar.png" width="32" height="32"></a>
				  </li>
			  </ul>
		  </div>
	  </div>';
	
echo'<div class="da-panel" style="width:650px;">
		<div class="da-panel-header">
			<span class="da-panel-title">
				<img src="images/icons/black/16/pencil.png" alt="" />
				<span lang="es">Editar Usuario</span>
			</span>
		</div>
		<div class="da-panel-content">
			<form class="da-form" name="frmUsuario" action="" method="post" id="frmEditaUsuario">';
			   if($idusuario<>$id_usuario_sesion && $tipo_sesion=='ROOT') {
			   echo'<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Tipo usuario</span></b></label>
						<div class="da-form-item small">';
							$selectTi="select 
											id_tipo,
											tipo,
											codigo
										from
											s_usuario_tipo
										where
											codigo != 'ROOT';";
						$rstip = $conexion->query($selectTi,MYSQLI_STORE_RESULT);
						echo'<select name="txtTipousuario" id="txtTipousuario" class="requerid">';
							  echo'<option value="" lang="es">seleccione...</option>';
							  while($filatip = $rstip->fetch_array(MYSQLI_ASSOC)){
								 if($filatip['id_tipo']==$txtTipousuario){
									echo'<option value="'.$filatip['id_tipo'].'|'.$filatip['codigo'].'" selected>'.$filatip['tipo'].'</option>';
								 }else{
									echo'<option value="'.$filatip['id_tipo'].'|'.$filatip['codigo'].'">'.$filatip['tipo'].'</option>';  
								 }  
							  }
							  $rstip->free();
						echo'</select>';
					   echo'<span class="errorMessage" id="errortipousuario" lang="es"></span>
						</div>
					</div>
					<div class="da-form-row" id="content-entidadf">
						<label style="text-align:right;"><b><span lang="es">Entidad Financiera</span></b></label>
						<div class="da-form-item large">
						 <span id="entidad-loading" class="loading-entf">';
						    $selectEF="select
										  id_ef,
										  nombre,
										  codigo,
										  activado
										from
										  s_entidad_financiera
										where
										  activado=1;";  
							$sqlef = $conexion->query($selectEF,MYSQLI_STORE_RESULT);			  
							if($fila['codigo']!='FAC'){
								echo'<select name="identidadf" id="identidadf" class="requerid" style="width:200px;">';
										echo'<option value="" lang="es">seleccione...</option>';
										
										while($regief = $sqlef->fetch_array(MYSQLI_ASSOC)){
											$selectEnFi="select 
															  count(sef.id_ef) as ctn_reg
														  from
															  s_entidad_financiera as sef
																  left join
															  s_ef_usuario as seu ON (seu.id_ef = sef.id_ef)
																  left join
															  s_usuario as su ON (su.id_usuario = seu.id_usuario)
														  where
															  (su.id_usuario = '".$fila['id_usuario']."'
																  or seu.id_usuario = '".$fila['id_usuario']."')
															   and sef.id_ef='".$regief['id_ef']."'
														  order by sef.id_ef asc;";
											  $res = $conexion->query($selectEnFi,MYSQLI_STORE_RESULT);
											  $regfi = $res->fetch_array(MYSQLI_ASSOC);
											if($regfi['ctn_reg']>0){  
											    echo'<option value="'.$regief['id_ef'].'" selected>'.$regief['nombre'].'</option>';
											}else{
												echo'<option value="'.$regief['id_ef'].'">'.$regief['nombre'].'</option>';
											}
											
										}
										$sqlef->free();
								echo'</select>';
						    }else{
								echo'<select name="idmultiple[]" id="idmultiple" class="requerid" style="width:200px;" size="5" multiple="multiple">';
										while($regief = $sqlef->fetch_array(MYSQLI_ASSOC)){
											$selectEnFi="select 
															  count(sef.id_ef) as ctn_reg
														  from
															  s_entidad_financiera as sef
																  left join
															  s_ef_usuario as seu ON (seu.id_ef = sef.id_ef)
																  left join
															  s_usuario as su ON (su.id_usuario = seu.id_usuario)
														  where
															  (su.id_usuario = '".$fila['id_usuario']."'
																  or seu.id_usuario = '".$fila['id_usuario']."')
															   and sef.id_ef='".$regief['id_ef']."'
														  order by sef.id_ef asc;";
											  $resfi = $conexion->query($selectEnFi,MYSQLI_STORE_RESULT);
											  $regfi = $resfi->fetch_array(MYSQLI_ASSOC);
											  
											  if($regfi['ctn_reg']>0){
											     echo'<option value="'.$regief['id_ef'].'" selected>'.$regief['nombre'].'</option>';  
											  }else{
												 echo'<option value="'.$regief['id_ef'].'">'.$regief['nombre'].'</option>';   
											  }
											  
										}
										$sqlef->free();
								echo'</select>';
							}
							echo'<span class="errorMessage" id="erroref" lang="es"></span>';
					echo'</span>
						</div>
					</div>	
					<div class="da-form-row" '.$display_d.' id="content-departamento">
						<label style="text-align:right;"><b><span lang="es">Departamento</span></b></label>
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
						      if($fila['codigo']!='REP'){
							     echo'<option value="" lang="es">Seleccione...</option>';
							  }else{
								 echo'<option value="" lang="es">Todos</option>'; 
							  }
							  while($filadep = $rsdep->fetch_array(MYSQLI_ASSOC)){
								 if($filadep['id_depto']==$departamento){
									echo'<option value="'.$filadep['id_depto'].'" selected>'.$filadep['departamento'].'</option>';
								 }else{
									echo'<option value="'.$filadep['id_depto'].'">'.$filadep['departamento'].'</option>';  
								 }  
							  }
							  $rsdep->free();
						echo'</select>';
					   echo'<span class="errorMessage" id="errordepartamento" lang="es"></span>
						</div>
					</div>
					<div class="da-form-row" '.$display_r.' id="content-regional">
					  <label style="text-align:right;"><b><span lang="es">Departamento</span></b></label>
					  <div class="da-form-item small">
					     <span id="response-regional">';
						   $selectReg="select
										 id_depto,
										 departamento,
										 codigo 
									  from
										s_departamento
									  where
										tipo_dp=1;";
							$rsreg = $conexion->query($selectReg,MYSQLI_STORE_RESULT);
							echo'<select name="regional" id="regional" class="requerid" style="width:200px;">';
								  echo'<option value="" lang="es">seleccione...</option>';
								  while($filareg = $rsreg->fetch_array(MYSQLI_ASSOC)){
									 if($filareg['id_depto']==$regional){
										echo'<option value="'.$filareg['id_depto'].'" selected>'.$filareg['departamento'].'</option>';
									 }else{
										echo'<option value="'.$filareg['id_depto'].'">'.$filareg['departamento'].'</option>';  
									 }  
								  }
								  $rsreg->free();
							echo'</select>';
						 echo'<span class="errorMessage" id="errorregional"></span>
						 </span>
				       </div>	
					</div>';  
					 if($fila['codigo']!='FAC' and $fila['codigo']!='IMP'){
						 if($fila['codigo']!='REP'){
							  echo'<div class="da-form-row" id="content-agency">';
								  echo'<label style="text-align:right;"><b><span lang="es">Agencia</span></b></label>
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
														  id_depto=".$departamento." and id_ef='".$vec[0]."';";
											 $resag = $conexion->query($selectAg,MYSQLI_STORE_RESULT);
											 $num_reg = $resag->num_rows;
											  echo'<select name="id_agencia" id="id_agencia" id="id_agencia" style="width:250px; font-size:12px;">';
													if($num_reg>0){
													  echo'<option value="" lang="es">Ninguno</option>';  
													  while($regiag = $resag->fetch_array(MYSQLI_ASSOC)){
														  if($regiag['id_agencia']==$id_agencia){
															 echo'<option value="'.$regiag['id_agencia'].'" selected>'.$regiag['agencia'].'</option>';	
														  }else{
															 echo'<option value="'.$regiag['id_agencia'].'">'.$regiag['agencia'].'</option>';
														  }
													  }
													  $resag->free();
													}else{
													   echo'<option value="" lang="es">Ninguno</option>';  
													}
											  echo'</select>';	
												
									 echo'</span>
										</div>	
								   </div>'; 
						 }else{
							if(!empty($departamento)){ 
							  echo'<div class="da-form-row" id="content-agency">';
								  echo'<label style="text-align:right;"><b><span lang="es">Agencia</span></b></label>
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
														  id_depto=".$departamento." and id_ef='".$vec[0]."';";
											 $resag = $conexion->query($selectAg,MYSQLI_STORE_RESULT);
											 $num_reg = $resag->num_rows;
											  echo'<select name="id_agencia" id="id_agencia" id="id_agencia" style="width:250px; font-size:12px;">';
													if($num_reg>0){
														if($fila['codigo']!='REP')
													        echo'<option value="" lang="es">Ninguno</option>';  
														  else
														     echo'<option value="" lang="es">Todos</option>';  
													  while($regiag = $resag->fetch_array(MYSQLI_ASSOC)){
														  if($regiag['id_agencia']==$id_agencia){
															 echo'<option value="'.$regiag['id_agencia'].'" selected>'.$regiag['agencia'].'</option>';	
														  }else{
															 echo'<option value="'.$regiag['id_agencia'].'">'.$regiag['agencia'].'</option>';
														  }
													  }
													  $resag->free();
													}else{
														if($fila['codigo']!='REP')
													       echo'<option value="" lang="es">Ninguno</option>';  
														 else
														   echo'<option value="" lang="es">Todos</option>';    
													} 
											  echo'</select>';	
												
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
						 }
					 }elseif($fila['codigo']=='IMP'){
						echo'<div class="da-form-row" id="content-agency" style="display:none">';
							 echo'<label style="text-align:right;"><b><span lang="es">Agencia</span></b></label>
								  <div class="da-form-item small">
									<span id="response-loading" class="loading-fac">';
									   
										echo'<select name="id_agencia" id="id_agencia" id="id_agencia" style="width:250px; font-size:12px;">';
										        echo'<option value="" lang="es">Ninguno</option>';
										       
										echo'</select>';	
										  
							   echo'</span>
								  </div>	
							 </div>'; 
					 }elseif($fila['codigo']=='IMP'){
						     $select="select
										id_agencia,
										codigo,
										agencia,
										id_ef
									  from
										s_agencia
									  where
										id_depto=".$regional." and id_ef='".$vec[0]."'
									  order by 
										id_agencia asc;";
							  //echo $select;			
							  $res = $conexion->query($select,MYSQLI_STORE_RESULT);
							  $numreg = $res->num_rows;
							 
							 echo'<div class="da-form-row" id="content-agency">';
							 echo'<label style="text-align:right;"><b><span lang="es">Agencia</span></b></label>
								  <div class="da-form-item small">
									<span id="response-loading" class="loading-fac">';
									   echo'<select name="idmultiple_agency[]" id="idmultiple_agency" class="requerid" style="width:250px;" size="5" multiple="multiple">';
											  while($regi = $res->fetch_array(MYSQLI_ASSOC)){
												 $busca="select
														   count(id_agencia) as ctage
														 from
														   s_im_usuario_agencia
														 where   
														   id_usuario='".$fila['id_usuario']."' and id_agencia='".$regi['id_agencia']."';"; 
												 $resbusca = $conexion->query($busca,MYSQLI_STORE_RESULT);
												 $rbusca = $resbusca->fetch_array(MYSQLI_ASSOC);
												 
												 if($rbusca['ctage']>0){
												    echo'<option value="'.$regi['id_agencia'].'" selected>'.$regi['agencia'].'</option>';
												 }else{
													echo'<option value="'.$regi['id_agencia'].'">'.$regi['agencia'].'</option>'; 
												 }
											  }
											  $res->free();
									  echo'</select>';
									  echo'<span class="errorMessage" id="errormultiagency"></span>';
							   echo'</span>
								  </div>	
							 </div>'; 
						 
					 }
				 
					 echo'<div class="da-form-row">
							  <label style="text-align:right;"><b><span lang="es">Usuario</span></b></label>
							  <div class="da-form-item large">
								  <i>'.$fila['usuario'].'</i>
								  <input type="hidden" name="usuario" value="'.$fila['usuario'].'"/>
							  </div>
						  </div>';
			   }else{
			        echo'<div class="da-form-row">
							<label style="text-align:right;"><b><span lang="es">Usuario</span></b></label>
							<div class="da-form-item large">
								<b>'.$fila['usuario'].'</b>
								<input type="hidden" name="usuario" value="'.$fila['usuario'].'"/>
							</div>
						</div>';
			   }
		   echo'<div class="da-form-row">
					<label style="text-align:right;"><b><span lang="es">Nombre completo</span></b></label>
					<div class="da-form-item large">
						<input class="textbox requerid" type="text" name="txtNombre" id="txtNombre" style="width: 250px;" maxlength="50" value="'.$txtNombre.'"/>
						<span class="errorMessage" id="errornombre" lang="es"></span>
					</div>
				</div>
				<div class="da-form-row">
					<label style="text-align:right;"><b><span lang="es">Telefono de la agencia</span></b></label>
					<div class="da-form-item large">
						<input class="textbox requerid" type="text" name="txtFonoAgencia" id="txtFonoAgencia" style="width: 250px;" value="'.$txtFonoAgencia.'"/>
						<span class="errorMessage" id="errorfonoagencia"></span>
					</div>
				</div>
				<div class="da-form-row">
					<label style="text-align:right;"><b><span lang="es">Correo electronico</span></b></label>
					<div class="da-form-item large">
						<input class="textbox requerid" type="text" name="txtEmail" id="txtEmail" style="width: 250px;" maxlength="50" value="'.$txtEmail.'"/>
						<span class="errorMessage" id="erroremail" lang="es"></span>
					</div>
				</div>';
				if($tipo_sesion=='ROOT' && $idusuario<>$id_usuario_sesion) {
					$selectEf="select
								  id_eu,
								  usuario,
								  id_ef
								from
								  s_ef_usuario
								where
								  id_usuario='".$idusuario."';";
					$resief = $conexion->query($selectEf,MYSQLI_STORE_RESULT);
					$regief = $resief->fetch_array(MYSQLI_ASSOC);
					
				 ?>
				   <script type="text/javascript">
                       $(document).ready(function(e) {
                           //alert('<?=$fila['codigo'];?>');
						   var codigo='<?=$fila['codigo'];?>';
						   var id_ef='<?=$regief['id_ef'];?>';
						   //alert('<?=$regief['id_ef'];?>');
						   if(codigo=='ADM'){
							     $('#DES').fadeOut('slow'); $('#AUT').fadeOut('slow');
							     $('#TRD').fadeOut('slow'); $('#TREM').fadeOut('slow');
								 $('#TH').fadeOut('slow');
								 var dataString = 'idef='+id_ef+'&opcion=busca_producto_entidad';
								 $.ajax({
									   async: true,
									   cache: false,
									   type: "POST",
									   url: "buscar_registro.php",
									   data: dataString,
									   dataType:"json",
									   success: function(datavec) {
											$.each(datavec, function( index, value ) {
												 //alert(value);
												 if(value=='DE'){
													$('#DES').fadeIn('slow');
												 }else if(value=='AU'){
													$('#AUT').fadeIn('slow');
												 }else if(value=='TRD'){
													$('#TRD').fadeIn('slow'); 
												 }else if(value=='TRM'){
													$('#TREM').fadeIn('slow');  
												 }else if(value=='TH'){
												    $('#TH').fadeIn('slow');	 
												 }
											});    
									   }
								 });
						   }
                       });
                   </script>
                 <?php	
				   echo'<div class="da-form-row">
						 <b><span lang="es">'.utf8_encode('Seleccione los elementos que el usuario podr· editar').'</span></b><br/>
						 <input class="child requerid" type="checkbox" name="cbIni" '.$cbIni.' '.$opera.' value="inicio"><span lang="es">Inicio</span><br/>
						 <input class="child requerid" type="checkbox" name="cbFor" '.$cbFor.' '.$opera.' value="formularios"><span lang="es">Formularios</span><br/>
						 
						 <span id="DES" style="display:none;"><input class="child requerid" type="checkbox" name="cbDes" '.$cbDes.' '.$admiope.' value="desgravamen"><span lang="es">Producto Desgravamen</span><br/></span>
						 <span id="AUT" style="display:none;"><input class="child requerid" type="checkbox" name="cbAuto" '.$cbAuto.' '.$admiope.' value="automotores"><span lang="es">Producto Automotores</span><br/></span>
						 <span id="TRD" style="display:none;"><input class="child requerid" type="checkbox" name="cbTrd" '.$cbTrd.' '.$admiope.' value="todoriesgod"><span lang="es">Producto Todo Riesgo Domiciliario</span><br/></span>
						 <span id="TREM" style="display:none;"><input class="child requerid" type="checkbox" name="cbTrem" '.$cbTrem.' '.$admiope.' value="triesgoeqmov"><span lang="es">Producto Todo Riesgo Equipo Movil</span><br/></span>
						 <span id="TH" style="display:none;"><input class="child requerid" type="checkbox" name="cbTh" '.$cbTh.' '.$admiope.' value="tarjetahabiente"><span lang="es">Producto Tarjetahabiente</span><br/></span>
						 
						 <input class="child requerid" type="checkbox" name="cbCreaU" '.$cbCreaU.' '.$creaus.' value="creausuario"><span lang="es">Crear Usuarios</span><br/>
						 						 
						 <input class="child requerid" type="checkbox" name="cbPol" '.$cbPol.' '.$admiope.' value="poliza"><span lang="es">'.utf8_encode('Administrar PÛlizas').'</span><br/>
						 <input class="child requerid" type="checkbox" name="cbEmail" '.$cbEmail.' '.$admiope.' value="email"><span lang="es">Administrar Correos electronicos</span><br/>
						 <input class="child requerid" type="checkbox" name="cbDepSuc" '.$cbDepSuc.' '.$admiope.' value="sucursal"><span lang="es">Administrar Regional</span><br/>
						 <input class="child requerid" type="checkbox" name="cbAgen" '.$cbAgen.' '.$admiope.' value="agencia"><span lang="es">Administrar Agencias</span><br/>
						 <input class="child requerid" type="checkbox" name="cbTipc" '.$cbTipc.' '.$admiope.' value="tipocambio"><span lang="es">Administrar Tipo de Cambio</span><br/>';
						/* echo'
						 <input class="child requerid" type="checkbox" name="cbCia" '.$cbCia.' '.$admiope.' value="companias">Compa&ntilde;&iacute;as de Seguros<br>
						 <input class="child" type="checkbox" name="cbLog" '.$cbLog.' '.$oficred.' value="ninguno">Oficial de cr&eacute;dito<br>
						 <input class="child" type="checkbox" name="cbCreus" '.$cbCreus.' '.$creaus.' value="creausuario">Crear Usuarios<br>
						 <input class="child" type="checkbox" name="cbRepSolPr" '.$cbRepSolPr.' '.$respso.' value="reportesol">Reportes Solicitudes Aprobadas<br>
						 <input class="child" type="checkbox" name="cbRepEcoSud" '.$cbRepEcoSud.' '.$repeco.' value="reporteeco">Reportes Bisa Sudamericana<br>
						 <input class="child" type="checkbox" name="cbRepLog" '.$cbRepLog.' '.$oficredrep.' value="farep">Acceso Reportes Generales (Oficial de Cr&eacute;dito)<br>';*/
					echo'<span class="errorMessage" id="errorseleccion" style="color:#d44d24; font-size:11px;" lang="es"></span>
						</div>';
			    }			
		   echo'<div class="da-button-row">
					<input type="button" value="Cancelar" class="da-button gray left" id="btnCancelar" lang="es"/>
					<input type="submit" value="Guardar" class="da-button green" name="btnUsuario" id="btnUsuario" lang="es"/>
					<input type="hidden" name="accionEditar" value="checkdatos"/>
					<input type="hidden" id="idusuario" value="'.$idusuario.'" class="requerid"/>
					<input type="hidden" id="idusuariosesion" value="'.$id_usuario_sesion.'" class="requerid"/>
					<input type="hidden" id="tipo_sesion" value="'.$tipo_sesion.'" class="requerid"/>
				</div>
			</form>
		</div>
	</div>';
	
	} else {
		//SI NO EXISTE EL USUARIO DADO EN LA BASE DE DATOS, VOLVEMOS A LA LISTA DE USUARIOS
		header('Location: index.php?l=usuarios');
	}
}

//FUNCION PARA CAMBIAR EL PASSWORD DE UN USUARIO
function cambiar_password($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion) {
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
	if($tipo_sesion=='ROOT') {
		if(isset($_POST['accionCambiar'])) {
			

			if($errFlag) {
				//MOSTRAMOS EL FORMULARIO CON LOS ERRORES
				mostrar_cambiar_password($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
			} else {
                //limpiar entradas
				$nuevopass = $conexion->real_escape_string($_POST['txtPassNuevo']);
				//encriptamos el password
			    $encrip_pass=crypt_blowfish_bycarluys($nuevopass); 
				
				$update = "UPDATE s_usuario SET password='".$encrip_pass."' "
				."WHERE id_usuario='".$idusuario."' LIMIT 1";
				

				//MOSTRAMOS LA LISTA DE USUARIOS
				if($conexion->query($update) === TRUE){
				   //REALIZAMOS EL CAMBIO DE CONTRASE—A
				   $mensaje='Se cambio correctamente la contraseÒa del usuario '.$_POST['usuario'];
				   header('Location: index.php?l=usuarios&op=1&msg='.base64_encode($mensaje));
				} else{
				   $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
				   header('Location: index.php?l=usuarios&op=2&msg='.base64_encode($mensaje));
				} 
				
			}

		} else {
			//SI NO HIZO CLICK EN EL BOTON 'CAMBIAR', MOSTRAMOS EL FORM PARA CAMBIAR PASSWORD
			mostrar_cambiar_password($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
		}
	} else {
		//SI NO ESTAMOS LOGUEADOS COMO ADMINISTRADOR, SOLO PODEMOS CAMBIAR NUESTRO PASSWORD
		if($idusuario==$id_usuario_sesion) {

			if(isset($_POST['accionCambiar'])) {
				

				if($errFlag) {
					//MOSTRAMOS EL FORMULARIO CON LOS ERRORES
					mostrar_cambiar_password($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
				} else {
					//limpiar entradas
                    $nuevopass = $conexion->real_escape_string($_POST['txtPassNuevo']);
					//encriptamos el password
			        $encrip_pass=crypt_blowfish_bycarluys($nuevopass);
					  
					$update = "UPDATE s_usuario SET password='".$encrip_pass."' "
					."WHERE id_usuario='".$idusuario."' LIMIT 1";
					
                     
					//MOSTRAMOS LA LISTA DE USUARIOS
					if($conexion->query($update) === TRUE){
					   //REALIZAMOS EL CAMBIO DE CONTRASE—A
					   $mensaje='Se cambio correctamente la contraseÒa del usuario '.$_POST['usuario'];
				       header('Location: index.php?l=usuarios&op=1&msg='.base64_encode($mensaje));
					} else{
					   $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " .$conexion->error;
				       header('Location: index.php?l=usuarios&op=2&msg='.base64_encode($mensaje));
					} 
					 
					//MOSTRAMOS LA LISTA DE USUARIOS
					//header('Location: sgc.php?l=usuarios');
				}

			} else {
				//SI NO HIZO CLICK EN EL BOTON 'CAMBIAR', MOSTRAMOS EL FORM PARA CAMBIAR PASSWORD
				mostrar_cambiar_password($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
			}
		} else {
			//SI UN USUARIO OPERADOR INTENTA CAMBIAR EL PASSWORD DE OTRO USUARIO, VAMOS A LA LISTA DE USUARIOS
			header('Location: index.php?l=usuarios');
		}
	}
}

//FUNCION QUE PERMITE REALIZAR EL CAMBIO DE UN NUEVO PASSWORD
function mostrar_cambiar_password($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
  ?>
    
	<script type="text/javascript">
       $(function(){
		   $('#btnCancelar').click(function(){   
			   $(location).prop('href','index.php?l=usuarios');
		   });   
	   });
    </script>
 
 <?php   
	$idusuario = base64_decode($_GET['idusuario']);
	$selectUs="select usuario from s_usuario where id_usuario='".$idusuario."';";
	$reus = $conexion->query($selectUs,MYSQLI_STORE_RESULT);
	$regUs = $reus->fetch_array(MYSQLI_ASSOC);
	$reus->free();
	
	echo'<div class="da-panel collapsible">
		  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			  <ul class="action_user">
				  <li style="margin-right:6px;">
					 <a href="?l=usuarios" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
					 <img src="images/retornar.png" width="32" height="32"></a>
				  </li>
			  </ul>
		  </div>
	  </div>';
	
    echo'<div class="da-panel" style="width:650px;">
		<div class="da-panel-header">
			<span class="da-panel-title">
				<img src="images/icons/black/16/locked_2.png" alt="" />
				<span lang="es">'.utf8_encode('Cambiar ContraseÒa').'</span>
			</span>
		</div>
		<div class="da-panel-content">
			<form class="da-form" name="frmUsuario" action="" method="post">
				<div class="da-form-row">
					<label><span lang="es">Usuario</span></label>
					<div class="da-form-item large">
						<b>'.$regUs['usuario'].'</b>
					</div>
				</div>
				<div class="da-form-row">
					<label><span lang="es">'.utf8_encode('ContraseÒa actual').'</span></label>
					<div class="da-form-item large">
						<input class="textbox" type="password" name="txtPassActual" id="txtPassActual" style="width: 200px;" maxlength="15"/>
						<div id="ver_msg1"><div class="errorMessage" id="erro_pass_actual" lang="es"></div></div>
					</div>
				</div>
				<div class="da-form-row">
					<label><span lang="es">'.utf8_encode('Nueva contraseÒa').'</span></label>
					<div class="da-form-item large">
						<input class="textbox" type="password" name="txtPassNuevo" id="txtPassword" style="width: 200px;" maxlength="15" disabled="false"/>
						<span class="formNote" style="text-align:right; width:400px;" lang="es">'.utf8_encode('M·x. 15 caracteres MÌn. 8 caracteres').'</span> 
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
					<label><span lang="es">'.utf8_encode('Confirmar contraseÒa').'</span></label>
					<div class="da-form-item large">
						<input class="textbox" type="password" name="txtPassRepite" id="txtPassRepite" style="width: 200px;" maxlength="15" disabled="false"/>
						<div id="ver_msg3"><div class="errorMessage" id="error_contrasenia_igual"></div></div>
					</div>
				</div>
				<div class="da-button-row">
					<input type="button" value="Cancelar" class="da-button gray left" id="btnCancelar" lang="es"/>
					<input type="submit" value="Guardar" class="da-button green" name="btn_cambiar_pass" id="btn_cambiar_pass" disabled="false" lang="es"/>
					<input type="hidden" name="accionCambiar" value="checkdatos"/>
	                <input type="hidden" name="idusuario" id="idusuario" value="'.$idusuario.'"/>
					<input type="hidden" name="usuario" id="usuario" value="'.$regUs['usuario'].'"/>
				</div>	
			</form>
		</div>
	</div>';
}

//FUNCION PARA RESETEAR EL PASSWORD DE UN USUARIO
function resetear_contrasenia_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion) {
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
	if($tipo_sesion=='ROOT') {
		if(isset($_POST['accionCambiar'])) {
			
			if($errFlag) {
				//MOSTRAMOS EL FORMULARIO CON LOS ERRORES
				mostrar_resetear_contrasenia($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
			} else {

				//limpiar entradas
				$nuevopass = $conexion->real_escape_string($_POST['txtPassNuevo']);
				//encriptamos el password
			    $encrip_pass=crypt_blowfish_bycarluys($nuevopass); 
				
				//date_default_timezone_set('America/La_Paz');
				$fecChange = date('Y-m-d');
				$update = "UPDATE s_usuario SET password='".$encrip_pass."' WHERE id_usuario='".$idusuario."' LIMIT 1";
				
				
				if($conexion->query($update) === TRUE){
				   //SE METIO A TBLHOMENOTICIAS, VAMOS A VER LA NOTICIA NUEVA
				   $mensaje='Se reseteo correctamente la contrase&ntilde;a del usuario: '.$_POST['usuario'];
				   header('Location: index.php?l=usuarios&op=1&msg='.base64_encode($mensaje));
				} else{
				   $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " .$conexion->error;
				   header('Location: index.php?l=usuarios&op=2&msg='.base64_encode($mensaje));
				} 
			}

		} else {
			//SI NO HIZO CLICK EN EL BOTON 'CAMBIAR', MOSTRAMOS EL FORM PARA CAMBIAR PASSWORD
			mostrar_resetear_contrasenia($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
		}
	} else {
		
		//SI UN USUARIO QUE NO SEA ADMIN INTENTA RESETEAR EL PASSWORD DE OTRO USUARIO, VAMOS A LA LISTA DE USUARIOS
		$mensaje="Esta accion no esta permitida solo usuario autorizado ";
		header('Location: index.php?l=usuarios&op=2&msg='.$mensaje);
		
	}
}

//FUNCION PARA IMPRIMIR EL FORM PARA RESETEAR CONTRASE?A DE USUARIO
function mostrar_resetear_contrasenia($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr) {
 ?>
    
	<script type="text/javascript">
       $(function(){
		   $('#btnCancelar').click(function(){   
			   $(location).prop('href','index.php?l=usuarios');
		   });   
	   });
    </script>
 
 <?php
	$idusuario = base64_decode($_GET['idusuario']);
	$selectUs="select usuario from s_usuario where id_usuario='".$idusuario."';";
	$resus = $conexion->query($selectUs,MYSQLI_STORE_RESULT);
	$regUs = $resus->fetch_array(MYSQLI_ASSOC);
	$resus->free();
	
	echo'<div class="da-panel collapsible">
		  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			  <ul class="action_user">
				  <li style="margin-right:6px;">
					 <a href="?l=usuarios" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
					 <img src="images/retornar.png" width="32" height="32"></a>
				  </li>
			  </ul>
		  </div>
	  </div>';
	
    echo'<div class="da-panel" style="width:650px;">
		<div class="da-panel-header">
			<span class="da-panel-title">
				<img src="images/icons/black/16/locked_2.png" alt="" />
				<span lang="es">'.utf8_encode('Resetear ContraseÒa').'</span>
			</span>
		</div>
		<div class="da-panel-content">
			<form class="da-form" name="frmUsuario" action="" method="post">
				<div class="da-form-row">
					<label><span lang="es">Usuario</span></label>
					<div class="da-form-item large">
						<b>'.$regUs['usuario'].'</b>
					</div>
				</div>
				<div class="da-form-row">
					<label><span lang="es">'.utf8_encode('Nueva contraseÒa').'</span></label>
					<div class="da-form-item large">
						<input class="textbox" type="password" name="txtPassNuevo" id="txtPassword" style="width: 200px;" maxlength="15"/>
						<span class="formNote" style="text-align:right; width:400px;" lang="es">'.utf8_encode('M·x. 15 caracteres MÌn. 8 caracteres').'</span> 
                        <div id="ver_msg2"><label class="errorMessage" id="erro_pass_nuevo" lang="es"></label></div>
						<div class="password-meter" style="width:199px;">
							<div class="password-meter-message" lang="es">&nbsp;</div>
							<div class="password-meter-bg">
								<div class="password-meter-bar"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="da-form-row">
					<label><span lang="es">'.utf8_encode('Confirmar contraseÒa').'</span></label>
					<div class="da-form-item large">
						<input class="textbox" type="password" name="txtPassRepite" id="txtPassRepite" style="width: 200px;" maxlength="15" disabled="false"/>
						<div id="ver_msg3"><div class="errorMessage" id="error_contrasenia_igual" lang="es"></div></div>
					</div>
				</div>
				<div class="da-button-row">
					<input type="button" value="Cancelar" class="da-button gray left" id="btnCancelar" lang="es"/>
					<input type="submit" value="Guardar" class="da-button green" name="btn_cambiar_pass" id="btn_cambiar_pass" disabled="false" lang="es"/>
					<input type="hidden" name="accionCambiar" value="checkdatos"/>
	                <input type="hidden" name="idusuario" id="idusuario" value="'.$idusuario.'"/>
					<input type="hidden" name="usuario" id="usuario" value="'.$regUs['usuario'].'"/>
				</div>	
			</form>
		</div>
	</div>';
}

//FUNCION QUE PERMITE DAR BAJA AL USUARIO
function dar_baja_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
   $idusuario = base64_decode($_GET['idusuario']);

	//NO PODEMOS ELIMINAR AL USUARIO ADMINISTRADOR
	if($idusuario<>$id_usuario_sesion) {
		if(isset($_POST['btnBajaUsuario'])) {
			
			//SI EL USUARIO HIZO CLICK EN EL BOTON PARA ELIMINAR EL REGISTRO, LO ELIMINAMOS DE LA BD
			$update ="UPDATE s_usuario SET activado=0 "
				    ."WHERE id_usuario='".$idusuario."' LIMIT 1";
				

			
			//MOSTRAMOS LA LISTA DE USUARIOS
			if($conexion->query($update) === TRUE){
			    //SE METIO A TBLHOMENOTICIAS, VAMOS A VER LA NOTICIA NUEVA
			    $mensaje='se dio de baja al usuario '.$idusuario.' correctamente';
			    header('Location: index.php?l=usuarios&op=1&msg='.$mensaje);
			} else{
			    $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			    header('Location: index.php?l=usuarios&op=2&msg='.$mensaje);
			} 
		}elseif(isset($_POST['btnCancelar'])){ 
		    //MOSTRAMOS LA LISTA DE USUARIOS
			header('Location: index.php?l=usuarios');
		}else {
			//MOSTRAMOS EL FORMULARIO PARA ELIMINAR AL USUARIO
			mostrar_dar_baja_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
		}
	} else {
		//SI SE INTENTO ELIMINAR AL USUARIO ADMINISTRADOR, VAMOS A LA LISTA DE USUARIOS
		header('Location: index.php?l=usuarios');
	}
}

//FUNCION PARA MOSTRAR EL FORMULARIO
function mostrar_dar_baja_usuario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
    $idusuario = base64_decode($_GET['idusuario']);
	$selectUs="select usuario from s_usuario where id_usuario='".$idusuario."';";
	$resus = $conexion->query($selectUs,MYSQLI_STORE_RESULT);
	$regUs = $resus->fetch_array(MYSQLI_ASSOC);
	$resus->free();
	echo'<div style="font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;">';
	echo '<form name="frmDarbaja" action="" method="post" class="da-form">';
	echo '<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" cols="2">';
	echo '<tr><td align="center" width="100%" style="height:60px;">';
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