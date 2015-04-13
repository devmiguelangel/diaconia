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
		 $.ambiance({message: "<?php echo $msg;?>", 
				title: "Notificacion",
				type: "<?php echo $valor?>",
				timeout: 5
				});
		 //location.load("sgc.php?l=usuarios&idhome=1");
		 //$(location).attr('href', 'sgc.php?l=crearusuario&idhome=1');		
		 setTimeout( "$(location).attr('href', 'index.php?l=des_preguntas&var=<?php echo $var;?>');",5000 );
	<?php }?>
	 
  });
</script>

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
			header('Location: index.php?l=des_preguntas&var=de&list_compania=v');
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
						
								agregar_nueva_pregunta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								
							} else {
								//VEMOS SI NOS PASAN UN ID DE USUARIO
								if(isset($_GET['idpregunta'])) {
						
									if(isset($_GET['darbaja'])) {
										
										desactivar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									}elseif(isset($_GET['daralta'])){ 
									
									    activar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								    
									}elseif(isset($_GET['editar'])) {
										//SI NO ME PASAN 'CPASS' NI 'ELIMINAR', MUESTRO EL FORM PARA EDITAR USUARIO
										editar_pregunta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									} 
								}elseif(isset($_GET['listarpreguntas'])) {
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE FORMULARIOS EXISTENTES
									mostrar_lista_preguntas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
								}elseif(isset($_GET['list_compania'])){
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE FORMULARIOS EXISTENTES
									mostrar_lista_companias_x_entidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
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

//FUNCION QUE PERMITE LISTAR LOS SEGUROS DE COMPAÑIA ACTIVOS
function mostrar_lista_companias_x_entidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){

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
$resef = $conexion->query($selectEf,MYSQLI_STORE_RESULT);
$num_regi_ef = $resef->num_rows;
if($num_regi_ef>0){
/*echo'<div class="da-panel collapsible">
		<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			<ul class="action_user">
				<li style="margin-right:6px;">
				   <a href="adicionar_registro.php?opcion=crear_tipo_producto&tipo_sesion='.base64_encode($tipo_sesion).'&id_ef_sesion='.base64_encode($id_ef_sesion).'" class="da-tooltip-s various fancybox.ajax" title="Añadir registro">
				   <img src="images/add_new.png" width="32" height="32"></a>
				</li>
			</ul>
		</div>
	 </div>';*/
	 
	 while($regief = $resef->fetch_array(MYSQLI_ASSOC)){		 
		$select="select
				   sef.id_ef_cia,
				   sef.id_ef,
				   sef.id_compania,
				   sc.nombre as compania,
				   sc.logo
				from
				  s_ef_compania sef
				  inner join s_compania as sc on (sc.id_compania=sef.id_compania)
				where
				  sef.id_ef='".$regief['id_ef']."' and sef.activado=1 and sc.activado=1 and sef.producto='DE';";
		$res = $conexion->query($select,MYSQLI_STORE_RESULT);		  
		echo'
			<div class="da-panel collapsible" style="width:700px;">
				<div class="da-panel-header">
					<span class="da-panel-title">
						<img src="images/icons/black/16/list.png" alt="" />
						<b>'.$regief['nombre'].'</b> - <span lang="es">Administrar preguntas Desgravamen</span> 
					</span>
				</div>
				<div class="da-panel-content">
					<table class="da-table">
						<thead>
							<tr>
							  <th style="text-align:center;"><b><span lang="es">Compañía de Seguros</span></b></th>
							  <th style="text-align:center;"><b><span lang="es">Imagen</span></b></th>
							  <th></th>
							</tr>
						</thead>
						<tbody>';
						  $num = $res->num_rows;
						  if($num>0){
							    $c=1;
								while($regi = $res->fetch_array(MYSQLI_ASSOC)){
									echo'<tr>
											<td>'.$regi['compania'].'</td>
											<td style="text-align:center;">';
											   if($regi['logo']!=''){
												   if(file_exists('../images/'.$regi['logo'])){  
													   $imagen = getimagesize('../images/'.$regi['logo']); 
													   $ancho = $imagen[0];   
													   $alto = $imagen[1]; 
													  echo'<img src="../images/'.$regi['logo'].'" width="'.($ancho/2).'" height="'.($alto/2).'"/>';
												   }else{
													  echo'no existe el archivo fisico';   
												   }
											   }else{
												  echo'no existe el nombre del archivo en la base de datos';   
											   }
									   echo'</td>
											<td class="da-icon-column">
											   <ul class="action_user">';
											   
												   /*echo'<li style="padding-right:5px;"><a href="?l=des_producto&var='.$_GET['var'].'&listarproductos=v&id_ef_cia='.base64_encode($regi['id_ef_cia']).'&id_producto='.base64_encode($regi['id_producto']).'&compania='.base64_encode($regi['compania']).'&entidad_fin='.base64_encode($regief['nombre']).'" class="add_mod da-tooltip-s various" title="Agregar Productos"></a></li>';*/
												   echo'<li style="margin-right:5px;"><a href="?l=des_preguntas&id_ef_cia='.base64_encode($regi['id_ef_cia']).'&entidad='.base64_encode($regief['nombre']).'&compania='.base64_encode($regi['compania']).'&id_ef='.base64_encode($regi['id_ef']).'&listarpreguntas=v&var='.$_GET['var'].'" class="admi-preg da-tooltip-s" title="<span lang=\'es\'>Administrar preguntas</span>"></a></li>';
												   /*echo'<li><a href="?l=au_incremento&id_ef_cia='.base64_encode($regi['id_ef_cia']).'&entidad='.base64_encode($regief['nombre']).'&compania='.base64_encode($regi['compania']).'&listarincremento=v&var='.$_GET['var'].'" class="ad_incre da-tooltip-s" title="Administrar Incremento"></a></li>';*/
											   
											 												 
										  echo'</ul>	
											</td>
										</tr>';
										$c++;
								}
								$res->free();			
						  }else{
							 echo'<tr><td colspan="7">
									  <div class="da-message warning">
										 No existe registros alguno, razones alguna:
										 <ul>
											<li>Verifique que la Compañia de Seguros este activada</li>
											<li>Verifique que la Compañia asignada a la Entidad Financiera este activada</li>
											<li>Verifique que el producto exista en la Compañia asignada a la Entidad Financiera</li>
										  </ul>
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
}


//FUNCION QUE PERMITE LISTAR LOS FORMULARIOS
function mostrar_lista_preguntas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>
<link type="text/css" rel="stylesheet" href="plugins/fancybox/jquery.fancybox.css"/>
<script type="text/javascript" src="plugins/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript">
     $('.pregunta').fancybox({
	    maxWidth	: 500,
		maxHeight	: 400,
		fitToView	: false,
		width		: '70%',
		height		: '100%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'elastic',
		closeEffect	: 'elastic'	 
	 });
</script>
<link type="text/css" rel="stylesheet" href="plugins/jalerts/jquery.alerts.css"/>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="plugins/jalerts/jquery.alerts.js"></script>
<script type="text/javascript">
   $(function(){
	   $("a[href].accion_active").click(function(e){
		   var valor = $(this).attr('id');
		   var vec = valor.split('|');
		   var id_pregunta = vec[0];
		   var id_ef_cia = vec[1];
		   var text = vec[2]; 		  
		   jConfirm("¿Esta seguro de "+text+" la pregunta?", ""+text+" registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_pregunta='+id_pregunta+'&id_ef_cia='+id_ef_cia+'&text='+text+'&opcion=active_pregunta';
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
<?php
$id_ef_cia=base64_decode($_GET['id_ef_cia']);
$entidad=base64_decode($_GET['entidad']);
$compania=base64_decode($_GET['compania']);
$id_ef=base64_decode($_GET['id_ef']);

$selectFor="select
			   id_pregunta,
			   pregunta,
			   orden,
			   respuesta,
			   case respuesta
			     when 0 then 'No'
				 when 1 then 'Si'
			   end as respuesta_text,	 
			   producto,
			   id_ef_cia,
			   activado,
			   case activado
			     when 0 then 'inactivo'
				 when 1 then 'activo'
			   end as activado_text	  
			from
			  s_pregunta
			where
			  id_ef_cia='".$id_ef_cia."' and producto='DE';";
$res = $conexion->query($selectFor,MYSQLI_STORE_RESULT);		  
echo'<div class="da-panel collapsible">
		<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			<ul class="action_user">
				<li style="margin-right:6px;">
					 <a href="?l=des_preguntas&var='.$_GET['var'].'&list_compania=v" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
					 <img src="images/retornar.png" width="32" height="32"></a>
				</li>
				<li style="margin-right:6px;">
				   <a href="adicionar_registro.php?opcion=crear_pregunta&id_ef_cia='.$_GET['id_ef_cia'].'&compania='.$_GET['compania'].'&entidad='.$_GET['entidad'].'&id_ef='.$_GET['id_ef'].'" class="da-tooltip-s pregunta fancybox.ajax" title="<span lang=\'es\'>Añadir nueva pregunta</span>">
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
			<b>'.$entidad.' - '.$compania.'</b> - <span lang="es">Listado de Preguntas</span>
		</span>
	</div>
	<div class="da-panel-content">
		<table class="da-table">
			<thead>
				<tr>
					<th style="width:25px;"><b>No</b></th>
					<th style="width:300px;"><b><span lang="es">Preguntas</span></b></th>
					<th style="width:20px; text-align:center;"><b><span lang="es">Respuesta esperada</span></b></th>
					<th style="width:20px; text-align:center;"><b><span lang="es">Estado</span></b></th>
					<th style="width:20px;">&nbsp;</th>
				</tr>
			</thead>
			<tbody>';
			  $num = $res->num_rows;
			  if($num>0){
				    $c=0;
					while($regi = $res->fetch_array(MYSQLI_ASSOC)){
						$c++;
						echo'<tr ';
								  if($regi['activado']==0){
									  echo'style="background:#D44D24; color:#ffffff;"'; 
								   }else{
									  echo'';	 
								   }
						  echo'>
								<td>'.$regi['orden'].'</td>
								<td>'.$regi['pregunta'].'</td>
								<td style="text-align:center;" lang="es">'.$regi['respuesta_text'].'</td>
								<td style="text-align:center;" lang="es">'.$regi['activado_text'].'</td>
								<td class="da-icon-column">
								   <ul class="action_user">
									  <li style="margin-right:5px;"><a href="adicionar_registro.php?opcion=edita_pregunta&idpregunta='.base64_encode($regi['id_pregunta']).'&editar=v&var='.$_GET['var'].'&id_ef_cia='.base64_encode($regi['id_ef_cia']).'&id_ef='.$_GET['id_ef'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'" class="edit da-tooltip-s pregunta fancybox.ajax" title="<span lang=\'es\'>Editar</span>"></a></li>';
									  if($regi['activado']==0){
										  echo'<li><a href="#" id="'.$regi['id_pregunta'].'|'.$regi['id_ef_cia'].'|activar" class="daralta da-tooltip-s accion_active" title="<span lang=\'es\'>Activar</span>"></a></li>';
									  }else{
										  echo'<li><a href="#" id="'.$regi['id_pregunta'].'|'.$regi['id_ef_cia'].'|desactivar" class="darbaja da-tooltip-s accion_active" title="<span lang=\'es\'>Desactivar</span>"></a></li>';  
									  }	  
								  //echo'<li><a href="?l=compania&idcompania='.base64_encode($regi['id_compania']).'&darbaja=v&var='.$_GET['var'].'&idcompania='.base64_encode($idcompania).'" class="eliminar da-tooltip-s" title="Eliminar"></a></li>';  	 
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
}

//FUNCION QUE PERMITE VISUALIZAR EL FORMULARIO NUEVO USUARIO
function agregar_nueva_pregunta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
	$errFlag = false;
	$errArr['errorpregunta'] = '';
	$errArr['errorcompania'] = '';
	$errArr['errorespuesta'] = '';
	

	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
		
		//VALIDAR TITULO
		$num=validar_pregunta($_POST['txtPregunta']);
		if($num==1){
			$errArr['errorpregunta'] = "Ingrese la pregunta";
			$errFlag = true;
		}elseif($num==2){
		  //DATOS CORRECTOS
		}elseif($num==3){
		   $errArr['errorpregunta'] = "Ingrese solo caracteres";
		   $errFlag = true;
		}
		
		//VALIDAR COMPANIA
		$num=validar_select($_POST['idcompania']);
		if($num==1){
			$errArr['errorcompania'] = "seleccione compañia";
			$errFlag = true;
		}elseif($num==2){
			 //SI SE SELECCIONO POLIZA GUARDAMOS
		}
		
		//VALIDAR RESPUESTA
		echo $_POST['txtRespuesta'];
		$num=validar_select_boolean($_POST['txtRespuesta']);
		if($num==1){
			$errArr['errorespuesta'] = "seleccione respuesta";
			$errFlag = true;
		}elseif($num==2){
			 //SI SE SELECCIONO POLIZA GUARDAMOS
		}
				
		//VEMOS SI TODO SE VALIDO BIEN
		if($errFlag) {
			//HUBIERON ERRORES, MOSTRAMOS EL FORM CON LOS ERRORES
			mostrar_crear_pregunta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
		} else {
			
			//SEGURIDAD
			$txtpregunta = $conexion->real_escape_string($_POST['txtPregunta']);
			/*--------------------------------------------------------------*/
			    $sql_orden="SELECT
								id_pregunta,
								orden
							 FROM
								s_pregunta 
							 ORDER BY
								orden ASC";
				$resOrden = $conexion->query($sql_orden,MYSQLI_STORE_RESULT);
				$num = $resOrden->num_rows;
				$orden = $num+1;
			/*--------------------------------------------------------------*/
								
			//METEMOS LOS DATOS A LA BASE DE DATOS
			$insert ="INSERT INTO s_pregunta(id_pregunta, id_compania, pregunta, orden, respuesta, producto) "
				    ."VALUES(NULL, ".$_POST['idcompania'].", '".$txtpregunta."', ".$orden.", ".$_POST['txtRespuesta'].", '')";
			
			
			//VERIFICAMOS SI HUBO ERROR EN EL INGRESO DEL REGISTRO
			if($conexion->query($insert)===TRUE){	
				$mensaje="Se registro correctamente los datos del formulario";
			    header('Location: index.php?l=des_preguntas&var='.$_GET['var'].'&op=1&msg='.$mensaje);
			    exit;
			} else {
				$mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " .$conexion->error;
			    header('Location: index.php?l=des_preguntas&var='.$_GET['var'].'&op=2&msg='.$mensaje);
				exit;
			}
		}

	} else {
		//MUESTRO EL FORM PARA CREAR UNA CATEGORIA
		mostrar_crear_pregunta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
	}
}

//VISUALIZAMOS EL FORMULARIO CREA USUARIO
function mostrar_crear_pregunta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr){
 ?> 
   <script type="text/javascript">
       $(function(){
		   $('#btnCancelar').click(function(){
			   var variable=$('#var').prop('value');
			   $(location).attr('href','index.php?l=des_preguntas&var='+variable);   
		   });   
	   });
   
   </script>
 <?php 
  $idcompania=base64_decode($_GET['idcompania']);

  //SACAMOS EL NOMBRE DE LA COMPAÑIA
  $selectCia="select
				   id_compania,
				   nombre
				from
				   s_compania
				where
				   activado=1 and id_compania=".$idcompania."
				limit 
				   0,1;";
	$rescia = $conexion->query($selectCia, MYSQLI_STORE_RESULT);
	$filacia = $rescia->fetch_array(MYSQLI_ASSOC);
	$rescia->free();
  
  //VARIABLES DE INICIO
  if(isset($_POST['txtPregunta'])) $txtPregunta = $_POST['txtPregunta']; else $txtPregunta = '';
  //if(isset($_POST['idcompania'])) $idcompania = $_POST['idcompania']; else $idcompania = '';
  if(isset($_POST['txtRespuesta'])) $txtRespuesta = $_POST['txtRespuesta']; else $txtRespuesta = '';  
		
  echo'<div class="da-panel" style="width:650px;">
		<div class="da-panel-header">
			<span class="da-panel-title">
				<img src="images/icons/black/16/pencil.png" alt="" />
				Crear Pregunta
			</span>
		</div>
		<div class="da-panel-content">
			<form class="da-form" name="frmUsuario" action="" method="post" enctype="multipart/form-data">
				<div class="da-form-row">
					<label style="width:150px;">Compañía de Seguros</label>
					<div class="da-form-item small">
						<b>'.$filacia['nombre'].'</b>						
						<input type="hidden" name="idcompania" id="idcompania" value="'.$idcompania.'"/>
					</div>
				</div>
				<div class="da-form-row">
					<label>Pregunta</label>
					<div class="da-form-item large">
						<textarea id="txtPregunta" name="txtPregunta" style="height:70px;">'.$txtPregunta.'</textarea>
						<span class="errorMessage">'.$errArr['errorpregunta'].'</span>
					</div>
				</div>
				<div class="da-form-row">
					<label>Respuesta</label>
					<div class="da-form-item large">
					    <select name="txtRespuesta" id="txtRespuesta" style="width:120px;">
							<option value=""';
							 if($txtRespuesta=='') 
								 echo 'selected'; 
					  echo '>Seleccionar...</option>
							<option value="1"';
							 if($txtRespuesta=='1') 
								 echo 'selected'; 
					  echo '>si</option>'
						 .'<option value="0"'; 
							 if($txtRespuesta=='0') 
								 echo 'selected';
					 echo '>no</option>
						</select>
						<span class="errorMessage">'.$errArr['errorespuesta'].'</span>
					</div>
				</div>												
				<div class="da-button-row">
					<input type="button" value="Cancelar" class="da-button gray left" id="btnCancelar"/>
					<input type="submit" value="Guardar" class="da-button green" name="btnPregunta" id="btnPregunta"/>
					<input type="hidden" name="accionGuardar" value="checkdatos"/>
					<input type="hidden" id="var" value="'.$_GET['var'].'"/>
				</div>
			</form>
		</div>
	</div>';
}

//FUNCION PARA EDITAR UN USUARIO
function editar_pregunta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion) {

	$errFlag = false;
	$errArr['errorpregunta'] = '';
	$errArr['errorcompania'] = '';
	$errArr['errorespuesta'] = '';
	

	$idpregunta = base64_decode($_GET['idpregunta']);
	//$idusuario = strtolower($idusuario);

	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['btnGuardar'])) {
       
        //VALIDAR TITULO
		$num=validar_pregunta($_POST['txtPregunta']);
		if($num==1){
			$errArr['errorpregunta'] = "Ingrese la pregunta";
			$errFlag = true;
		}elseif($num==2){
		  //DATOS CORRECTOS
		}elseif($num==3){
		   $errArr['errorpregunta'] = "Ingrese solo caracteres";
		   $errFlag = true;
		}
		
		//VALIDAR COMPANIA
		$num=validar_select($_POST['idcompania']);
		if($num==1){
			$errArr['errorcompania'] = "seleccione compañia";
			$errFlag = true;
		}elseif($num==2){
			 //SI SE SELECCIONO POLIZA GUARDAMOS
		}
		
		//VALIDAR RESPUESTA
		echo $_POST['txtRespuesta'];
		$num=validar_select_boolean($_POST['txtRespuesta']);
		if($num==1){
			$errArr['errorespuesta'] = "seleccione respuesta";
			$errFlag = true;
		}elseif($num==2){
			 //SI SE SELECCIONO POLIZA GUARDAMOS
		}
				
        //VEMOS SI TODO SE VALIDO BIEN
        if($errFlag) {
            //SI HUBIERON ERRORES, MOSTRAMOS EL FORM CON LOS ERRORES
            mostrar_editar_pregunta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
        } else {
            //SEGURIDAD
			$txtpregunta = $conexion->real_escape_string($_POST['txtPregunta']);
			
            //CARGAMOS LOS DATOS A LA BASE DE DATOS
            $update ="UPDATE s_pregunta SET pregunta='".$txtpregunta."',";
            $update.=" id_compania=".$_POST['idcompania'].", respuesta=".$_POST['txtRespuesta']." ";
            $update.="WHERE id_pregunta=".$idpregunta." LIMIT 1;";
            //echo $update;
            //$rsu = mysql_query($update, $conexion);

            if($conexion->query($update)===TRUE){
                $mensaje="Se actualizo correctamente los datos del formulario";
			    header('Location: index.php?l=des_preguntas&var='.$_GET['var'].'&op=1&msg='.$mensaje.'&listacia=v');
			    exit;
            } else{
                $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			    header('Location: index.php?l=des_preguntas&var='.$_GET['var'].'&op=2&msg='.$mensaje.'&listacia=v');
				exit;
            }

        }

	}else {
	  //MUESTRO FORM PARA EDITAR UNA CATEGORIA
	  mostrar_editar_pregunta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
	}
	
}

//VISUALIZAMOS EL FORMULARIO PARA EDITAR EL FORMULARIO
function mostrar_editar_pregunta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr){
 ?>
   <script type="text/javascript">
       $(function(){
		   $('#btnCancelar').click(function(){
			  var variable=$('#var').prop('value');   
			  $(location).prop('href','index.php?l=des_preguntas&var='+variable); 
		   });   
		   
	   });
   
   </script>
 <?php   
  $idcompania=base64_decode($_GET['idcompania']);

  //SACAMOS EL NOMBRE DE LA COMPAÑIA
  $selectCia="select
				   id_compania,
				   nombre
				from
				   s_compania
				where
				   activado=1 and id_compania=".$idcompania."
				limit 
				   0,1;";
    $rescia = $conexion->query($selectCia,MYSQLI_STORE_RESULT);				   
	$filacia = $rescia->fetch_array(MYSQLI_ASSOC);
	
	$idpregunta = base64_decode($_GET['idpregunta']);

	//SACAMOS LOS DATOS DE LA BASE DE DATOS
	$select = "select
				  sp.id_pregunta,
				  sp.id_compania,
				  sp.pregunta,
				  sp.orden,
				  sp.respuesta,
				  sp.producto
				from
				  s_pregunta as sp
				  inner join s_compania as sc on (sc.id_compania=sp.id_compania)
				where
				  sc.activado=1 and sp.id_pregunta=".$idpregunta." and sp.id_compania=". $idcompania."
				order by
				  sp.orden asc;";
	$rs = $conexion->query($select, MYSQLI_STORE_RESULT);
	$num = $rs->num_rows;
	
	//SI EXISTE EL USUARIO DADO EN LA BASE DE DATOS, LO EDITAMOS
	if($num>0) {

		$fila = $rs->fetch_array(MYSQLI_ASSOC);
        $rs->free();
		if(isset($_POST['txtPregunta'])) $txtPregunta = $_POST['txtPregunta']; else $txtPregunta = $fila['pregunta'];
        //if(isset($_POST['idcompania'])) $idcompania = $_POST['idcompania']; else $idcompania = $fila['id_compania'];
        if(isset($_POST['txtRespuesta'])) $txtRespuesta = $_POST['txtRespuesta']; else $txtRespuesta = $fila['respuesta'];
		
		echo'<div class="da-panel" style="width:650px;">
				<div class="da-panel-header">
					<span class="da-panel-title">
						<img src="images/icons/black/16/pencil.png" alt="" />
						Crear Pregunta
					</span>
				</div>
				<div class="da-panel-content">
					<form class="da-form" name="frmUsuario" action="" method="post" enctype="multipart/form-data">
						<div class="da-form-row">
							<label style="width:150px;">Compañía de Seguros</label>
							<div class="da-form-item small">
								<b>'.$filacia['nombre'].'</b>
								<input type="hidden" name="idcompania" id="idcompania" value="'.$idcompania.'"/>
							</div>
						</div>
						<div class="da-form-row">
							<label>Pregunta</label>
							<div class="da-form-item large">
								<textarea id="txtPregunta" name="txtPregunta" style="height:70px;">'.$txtPregunta.'</textarea>
								<span class="errorMessage">'.$errArr['errorpregunta'].'</span>
							</div>
						</div>
						<div class="da-form-row">
							<label>Respuesta</label>
							<div class="da-form-item large">
								<select name="txtRespuesta" id="txtRespuesta" style="width:120px;">
									<option value=""';
									 if($txtRespuesta=='') 
										 echo 'selected'; 
							  echo '>Seleccionar...</option>
									<option value="1"';
									 if($txtRespuesta=='1') 
										 echo 'selected'; 
							  echo '>si</option>'
								 .'<option value="0"'; 
									 if($txtRespuesta=='0') 
										 echo 'selected';
							 echo '>no</option>
								</select>
								<span class="errorMessage">'.$errArr['errorespuesta'].'</span>
							</div>
						</div>												
						<div class="da-button-row">
							<input type="button" value="Cancelar" class="da-button gray left" id="btnCancelar"/>
							<input type="submit" value="Guardar" class="da-button green" name="btnGuardar" id="btnGuardar"/>
							<input type="hidden" id="var" value="'.$_GET['var'].'"/>
							
						</div>
					</form>
				</div>
			 </div>';
	
	} else {
		//SI NO EXISTE EL USUARIO DADO EN LA BASE DE DATOS, VOLVEMOS A LA LISTA DE USUARIOS
		header('Location: index.php?l=des_preguntas&var='.$_GET['var']);
	}
}

?>