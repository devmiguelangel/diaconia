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
			header('Location: index.php?l=certmedico&var=de');
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
						
								agregar_nueva_ocupacion($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								
							} else {
								//VEMOS SI NOS PASAN UN ID DE USUARIO
								if(isset($_GET['idocupacion'])) {
						
									if(isset($_GET['darbaja'])) {
										
										desactivar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									}elseif(isset($_GET['daralta'])){ 
									
									    activar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								    
									}elseif(isset($_GET['editar'])) {
										//SI NO ME PASAN 'CPASS' NI 'ELIMINAR', MUESTRO EL FORM PARA EDITAR USUARIO
										editar_ocupacion($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									} 
								}elseif(isset($_GET['listarcuestionario'])){ 
								    //VISUALIZAMOS LISTA EXISTENTES DE CUESTIONARIOS
									mostrar_lista_cuestionario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
							    }elseif(isset($_GET['listarpregunta'])){
								   //VISUALIZAMOS LISTA EXISTENTES DE PREGUNTAS
								    mostrar_lista_preguntas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
							    }elseif(isset($_GET['agregarqu'])){
							        //VISUALIZAMOS LA LISTA DE CUESTIONARIOS AGREGADOS
									mostrar_lista_agrega_cuestionario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								}elseif(isset($_GET['agregar_pregunta'])){
								   //VISUALIZAMOS LA LISTA DE PREGUNTAS AGREGADAS
								    mostrar_lista_preguntas_agregadas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
							    }elseif(isset($_GET['listar_cert_medicos'])) {
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE FORMULARIOS EXISTENTES
									mostrar_lista_certificados_medicos($id_usuario_sesion, $tipo_sesion, $id_ef_sesion, $usuario_sesion, $conexion);
								}else{
									//LISTAMOS LAS COMPAÑIAS ACTIVAS PARA ADMINISTRAR LOS CERTIFICADOS MEDICOS
									listar_companias_activas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
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
function listar_companias_activas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
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
						    and ef.id_ef='".$id_ef_sesion."';";
	}
	if($resef = $conexion->query($selectEf,MYSQLI_STORE_RESULT)){
			$num_regi_ef = $resef->num_rows;
			if($num_regi_ef>0){
				while($regief = $resef->fetch_array(MYSQLI_ASSOC)){
				$selectFor="select
							  efc.id_ef_cia,
							  efc.id_ef,
							  efc.id_compania,
							  scia.logo,
							  scia.nombre as compania,
							  case efc.activado
								when 1 then 'activo'
								when 0 then 'inactivo'
							  end as activado,
							  sh.producto_nombre	 
							from
							  s_ef_compania as efc
							  inner join s_compania as scia on (scia.id_compania=efc.id_compania)
							  inner join s_sgc_home as sh on (sh.id_ef=efc.id_ef)
							where
							 efc.id_ef='".$regief['id_ef']."' and efc.activado=1 and scia.activado=1 and efc.producto=sh.producto and efc.producto='DE';";
				    if($res = $conexion->query($selectFor,MYSQLI_STORE_RESULT)){		  
				
						echo'
						<div class="da-panel collapsible" style="width:900px;">
							<div class="da-panel-header">
								<span class="da-panel-title">
									<img src="images/icons/black/16/list.png" alt="" />
									<b>'.$regief['nombre'].'</b> - <span lang="es">Editar Certificado Médico</span></b>
								</span>
							</div>
							<div class="da-panel-content">
								<table class="da-table">
									<thead>
										<tr>
											<th><b><span lang="es">Compañía de Seguros</span></b></th>
											<th style="width:100px;"><b><span lang="es">Estado</span></b></th>
											<th style="width:200px; text-align:center"><b><span lang="es">Imagen</span></b></th>
											<th style="text-align:center"><b><span lang="es">Producto</span></b></th>
											<th></th>
										</tr>
									</thead>
									<tbody>';
									  $num = $res->num_rows;
									  if($num>0){
											while($regi = $res->fetch_array(MYSQLI_ASSOC)){
												echo'<tr>
														<td>'.$regi['compania'].'</td>
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
														<td style="text-align:center;">'.$regi['producto_nombre'].'</td>
														<td class="da-icon-column">
														   <ul class="action_user">
															  <li style="margin-right:5px;"><a href="?l=certmedico&id_ef_cia='.base64_encode($regi['id_ef_cia']).'&id_ef='.base64_encode($regief['id_ef']).'&entidad='.base64_encode($regief['nombre']).'&compania='.base64_encode($regi['compania']).'&listar_cert_medicos=v&var='.$_GET['var'].'" class="cert-medico da-tooltip-s" title="<span lang=\'es\'>Certificados Medicos</span>"></a></li>';
																									 
													  echo'</ul>	
														</td>
													</tr>';
											}
											$res->free();			
									  }else{
										 echo'<tr><td colspan="7">
												  <div class="da-message warning">
													   No existe registros alguno, razones alguna:
													   <ul>
														  <li>Verifique que la Compañia de Seguros este activada</li>
														  <li>Verifique que la Compañia asignada a la Entidad Financiera este activada</li>
														</ul>
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
							<li lang="es">La Entidad Financiera no tiene asignado el producto Desgravamen</li>
							<li lang="es">La Entidad Financiera no esta activado</li>
							<li lang="es">La Entidad Financiera no esta creada</li>
						  </ul>
					</div>'; 
			}
	}else{
		echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error."</div>";
	}
}

//FUNCION QUE PERMITE LISTAR LOS FORMULARIOS
function mostrar_lista_certificados_medicos($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>
<link type="text/css" rel="stylesheet" href="plugins/fancybox/jquery.fancybox.css"/>
<script type="text/javascript" src="plugins/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript">
     $('.various').fancybox({
	    maxWidth	: 425,
		maxHeight	: 475,
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
	   $("a[href].eliminar").click(function(e){
		   var id_ocupacion = $(this).attr('id'); 		  
		  
		   jConfirm("¿Esta seguro de eliminar la ocupacion?", "Eliminar registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_ocupacion='+id_ocupacion+'&opcion=ocupacion';
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
	   
	   //CHECKED
	   $('.certmedico').click(function(){
		   var idcm = $(this).prop('value');
		   //alert(idcm);
		   var dataString ='idcm='+idcm+'&opcion=certmedico';
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
		 $.ambiance({message: "<?php echo $msg;?>", 
				title: "Notificacion",
				type: "<?php echo $valor?>",
				timeout: 5
				});
		 //location.load("sgc.php?l=usuarios&idhome=1");
		 //$(location).attr('href', 'sgc.php?l=crearusuario&idhome=1');		
		 setTimeout( "$(location).attr('href', 'index.php?l=ocupacion&var=<?php echo $var;?>');",5000 );
	<?php }?>
	 
  });
</script>
<?php
//SACAMOS LOS CERTIFICADOS EXISTENTES
$id_ef_cia = base64_decode($_GET['id_ef_cia']);
//$id_ef = base64_decode($_GET['id_ef']);
$entidad = base64_decode($_GET['entidad']);
$compania = base64_decode($_GET['compania']);

$selectFor="select
			  cmc.id_cm,
			  cmc.tipo,
			  cmc.titulo,
			  cmc.activado,
			  count(cert.id_cm) as cantidad 
			from
			  s_cm_certificado as cmc
			  left join s_cm_cert_cues as cert on (cert.id_cm=cmc.id_cm)
			where
			  cmc.id_ef_cia='".$id_ef_cia."'
			group by
			   cmc.tipo
			order by
			  cmc.id_cm asc;";
   if($res = $conexion->query($selectFor,MYSQLI_STORE_RESULT)){

		echo'
		<div class="da-panel collapsible">
			<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
				<ul class="action_user">
					<li style="margin-right:6px;">
					   <a href="?l=certmedico&var='.$_GET['var'].'" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
					   <img src="images/retornar.png" width="32" height="32"></a>
					</li>
					<li style="margin-right:6px;">
					   <a href="certmedico_registro.php?opcion=crear_cert&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'" class="da-tooltip-s various fancybox.ajax" title="<span lang=\'es\'>Crear Certificado</span>">
					   <img src="images/add_cert.png" width="32" height="32"></a>
					</li>
					<li style="margin-right:6px;">
					   <a href="?l=certmedico&listarcuestionario=v&var='.$_GET['var'].'&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'" class="da-tooltip-s" title="<span lang=\'es\'>Crear Cuestionario</span>">
					   <img src="images/add_cuestionario.png" width="32" height="32"></a>
					</li>
					<li>
					   <a href="?l=certmedico&listarpregunta=v&var='.$_GET['var'].'&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'" class="da-tooltip-s" title="<span lang=\'es\'>Crear Preguntas</span>">
					   <img src="images/add_preguntas.png" width="32" height="32"></a>
					</li>
				</ul>
			</div>
		</div>
		
		<div class="da-panel collapsible">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/list.png" alt="" />
					<b>'.$entidad.'</b> - <span lang="es">Listado Certificados Médicos</span>
				</span>
			</div>
			<div class="da-panel-content">
				<table class="da-table">
					<thead>
						<tr>
							<th><b><span lang="es">Tipo Certificado</span></b></th>
							<th><b><span lang="es">Titulo</span></b></th>
							<th><b><span lang="es">Compañía de Seguros</span></b></th>
							<th style="text-align:center;"><b><span lang="es">Activo</span></b></th>
							<th></th>
						</tr>
					</thead>
					<tbody>';
					  $num = $res->num_rows;
					  if($num>0){
							while($regi = $res->fetch_array(MYSQLI_ASSOC)){
								echo'<tr ';
										  if($regi['activado']==1){
											  echo'style="background:#FFCA71; color:#000;"'; 
										   }else{
											  echo'';	 
										   }
								  echo'>
										<td>'.$regi['tipo'].'</td>
										<td>'.$regi['titulo'].'</td>
										<td>'.$compania.'</td>
										<td style="text-align:center;">';
										   if($regi['activado']==1){
											 echo'<input type="radio" name="rd-'.$regi['id_cm'].'" class="certmedico" value="'.$regi['id_cm'].'|'.$id_ef_cia.'" checked/>';
										   }else{
											 echo'<input type="radio" name="rd-'.$regi['id_cm'].'" class="certmedico" value="'.$regi['id_cm'].'|'.$id_ef_cia.'"/>';  
										   }
								   echo'</td>
										<td class="da-icon-column">
										   <ul class="action_user">';
												if($regi['activado']!=1){
													/*echo'<li style="margin-right:5px;"><a href="?l=certmedico&idcm='.base64_encode($regi['id_cm']).'&editar=v&var='.$_GET['var'].'" class="edit da-tooltip-s" title="Editar"></a></li>';*/
													echo'<li style="margin-right:5px;"><a href="certmedico_registro.php?opcion=editar_cert&idcm='.base64_encode($regi['id_cm']).'&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'" class="edit da-tooltip-s various fancybox.ajax" title="<span lang=\'es\'>Editar</span>"></a></li>';
												}else{
												//echo'<li style="margin-right:5px;"><a href="#" class="editar_disabled"></a></li>';
													echo'<li style="margin-right:5px;"><a href="certmedico_registro.php?opcion=editar_cert&idcm='.base64_encode($regi['id_cm']).'&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'" class="edit da-tooltip-s various fancybox.ajax" title="<span lang=\'es\'>Editar</span>"></a></li>';
													if($regi['tipo']=='CUESTIONARIO'){
													  //if($regi['cantidad']>0 and $regi['activado']==1){
													echo'<li><a href="?l=certmedico&idcm='.base64_encode($regi['id_cm']).'&agregarqu=v&var='.$_GET['var'].'&tipocert='.base64_encode($regi['tipo']).'&compania='.$_GET['compania'].'&entidad='.$_GET['entidad'].'&id_ef_cia='.$_GET['id_ef_cia'].'" class="add-cuestionario da-tooltip-s" title="<span lang=\'es\'>Agregar Cuestionario</span>"></a></li>';
													  //}
													}
												}
											  //echo'<li><a href="#" class="eliminar da-tooltip-s" title="Eliminar" id="'.$regi['id_cm'].'"></a></li>';
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

//LISTAMOS CUESTIONARIOS EXISTENTES
function mostrar_lista_cuestionario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
?>
<link type="text/css" rel="stylesheet" href="plugins/fancybox/jquery.fancybox.css"/>
<script type="text/javascript" src="plugins/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript">
     $('.cuestionario').fancybox({
	    maxWidth	: 400,
		maxHeight	: 300,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
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
$(document).ready(function() {
    $("a[href].eliminar").click(function(e){
	   var idcuestionario = $(this).attr('id'); 		  
	   jConfirm("¿Esta seguro de eliminar el titulo cuestionario?", "Eliminar registro", function(r) {
			//alert(r);
			if(r) {
					var dataString ='idcuestionario='+idcuestionario+'&opcion=certmedico';
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
<?php
//SACAMOS LOS CUESTIONARIOS EXISTENTES
$selectFor="select
			  id_cuestionario,
			  titulo
			from
			  s_cm_cuestionario;";
$res = $conexion->query($selectFor,MYSQLI_STORE_RESULT);

echo'
<div class="da-panel collapsible">
    <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
		<ul class="action_user">
			<li style="margin-right:6px;">
			   <a href="?l=certmedico&var='.$_GET['var'].'&listar_cert_medicos=v&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
			   <img src="images/retornar.png" width="32" height="32"></a>
			</li>
			<li style="margin-right:6px;">
			   <a href="certmedico_registro.php?opcion=crear_question" class="da-tooltip-s cuestionario fancybox.ajax" title="<span lang=\'es\'>Crear Cuestionario</span>">
			   <img src="images/add_cert.png" width="32" height="32"></a>
			</li>
		</ul>
	</div>
</div>

<div class="da-panel collapsible" style="width:650px;">
	<div class="da-panel-header">
		<span class="da-panel-title">
			<img src="images/icons/black/16/list.png" alt="" />
			<span lang="es">Listado Cuestionario</span>
		</span>
	</div>
	<div class="da-panel-content">
		<table class="da-table">
			<thead>
				<tr>
					<th><b><span lang="es">Titulo Cuestionario</span></b></th>
					<th></th>
				</tr>
			</thead>
			<tbody>';
			  $num = $res->num_rows;
			  if($num>0){
					while($regi = $res->fetch_array(MYSQLI_ASSOC)){
						echo'<tr>
								<td>'.$regi['titulo'].'</td>
								<td class="da-icon-column">
								   <ul class="action_user">';
								       
									    echo'<li style="margin-right:5px;"><a href="certmedico_registro.php?opcion=editar_question&idcuestionario='.$regi['id_cuestionario'].'" class="edit da-tooltip-s cuestionario fancybox.ajax" title="<span lang=\'es\'>Editar</span>"></a>
										     </li>';
										echo'<li><a href="#" class="eliminar da-tooltip-s" title="<span lang=\'es\'>Eliminar" id="'.$regi['id_cuestionario'].'"></a></li>';
							  echo'</ul>	
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

//LISTAMOS PREGUNTAS EXISTENTES
function mostrar_lista_preguntas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
?>
<link type="text/css" rel="stylesheet" href="plugins/fancybox/jquery.fancybox.css"/>
<script type="text/javascript" src="plugins/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript">
     $('.pregunta').fancybox({
	    maxWidth	: 400,
		maxHeight	: 340,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
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
$(document).ready(function() {
    $("a[href].eliminar").click(function(e){
	   var idpregunta = $(this).attr('id'); 		  
	   jConfirm("¿Esta seguro de eliminar el titulo cuestionario?", "Eliminar registro", function(r) {
			//alert(r);
			if(r) {
					var dataString ='idpregunta='+idpregunta+'&opcion=eliminapregunta';
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
<?php
//SACAMOS LAS PREGUNTAS EXISTENTES
$select="select
			  id_pregunta,
			  pregunta,
			  case tipo
			    when 'cb' then 'CHECKBOX'
				when 'rd' then 'RADIO'
				when 'text' then 'TEXTO'
				when 'txtarea' then 'AREA DE TEXTO'
			  end as tipo	
			from
			  s_cm_pregunta
			order by
			  id_pregunta asc;";
$res = $conexion->query($select,MYSQLI_STORE_RESULT);

echo'
<div class="da-panel collapsible">
    <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
		<ul class="action_user">
			<li style="margin-right:6px;">
			   <a href="?l=certmedico&var='.$_GET['var'].'&listar_cert_medicos=v&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
			   <img src="images/retornar.png" width="32" height="32"></a>
			</li>
			<li style="margin-right:6px;">
			   <a href="certmedico_registro.php?opcion=crear_pregunta" class="da-tooltip-s pregunta fancybox.ajax" title="<span lang=\'es\'>Crear Pregunta</span>">
			   <img src="images/add_cert.png" width="32" height="32"></a>
			</li>
		</ul>
	</div>
</div>

<div class="da-panel collapsible" style="width:750px;">
	<div class="da-panel-header">
		<span class="da-panel-title">
			<img src="images/icons/black/16/list.png" alt="" />
			<span lang="es">Listado Preguntas</span>
		</span>
	</div>
	<div class="da-panel-content">
		<table class="da-table">
			<thead>
				<tr>
					<th><b><span lang="es">Pregunta</span></b></th>
					<th style="width:150px;"><b><span lang="es">Tipo</span></b></th>
					<th></th>
				</tr>
			</thead>
			<tbody>';
			  $num = $res->num_rows;
			  if($num>0){
					while($regi = $res->fetch_array(MYSQLI_ASSOC)){
						echo'<tr>
								<td>'.$regi['pregunta'].'</td>
								<td>'.$regi['tipo'].'</td>
								<td class="da-icon-column">
								   <ul class="action_user">';
								       
									    echo'<li style="margin-right:5px;"><a href="certmedico_registro.php?opcion=editar_pregunta&idpregunta='.$regi['id_pregunta'].'" class="edit da-tooltip-s pregunta fancybox.ajax" title="<span lang=\'es\'>Editar</span>"></a>
										     </li>';
										echo'<li><a href="#" class="eliminar da-tooltip-s" title="<span lang=\'es\'>Eliminar</span>" id="'.$regi['id_pregunta'].'"></a></li>';
							  echo'</ul>	
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

//MOSTRAMOS LA LISTA DE CUESTIONARIOS AGREGADOS
function mostrar_lista_agrega_cuestionario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
?>
<link type="text/css" rel="stylesheet" href="plugins/fancybox/jquery.fancybox.css"/>
<script type="text/javascript" src="plugins/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript">
     $('.pregunta').fancybox({
	    maxWidth	: 400,
		maxHeight	: 370,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'elastic',
		closeEffect	: 'elastic'	 
	 });
</script>

<link type="text/css" rel="stylesheet" href="plugins/jalerts/jquery.alerts.css"/>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="plugins/jalerts/jquery.alerts.js"></script>
<script type="text/javascript" src="js/jquery.tablednd_0_5.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("a[href].eliminar").click(function(e){
	   var variable = $(this).attr('id'); 		  
	   var vec = variable.split('|');
	   var id_cc = vec[0];
	   var id_cm = vec[1];
	   jConfirm("¿Esta seguro de eliminar el titulo cuestionario?", "Eliminar registro", function(r) {
			//alert(r);
			if(r) {
					var dataString ='id_cc='+id_cc+'&id_cm='+id_cm+'&opcion=elimina_cuestionario';
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
	
	//ACTIVAR/DESACTIVAR
	$("a[href].disabled_enabled").click(function(e){
		   var valor = $(this).attr('id');
		   var vec = valor.split('|');
		   var id_cc = vec[0];
		   var id_cm = vec[1];
		   var id_cuestionario = vec[2];
		   var text = vec[3]; 		  
		   jConfirm("¿<span lang='es'>Esta seguro de "+text+" el cuestionario</span>?", ""+text+" registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_cc='+id_cc+'&id_cm='+id_cm+'&id_cuestionario='+id_cuestionario+'&text='+text+'&opcion=enabled_disabled_cm';
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
	   
	   $(".tbl_repeat tbody").tableDnD({
			onDrop: function(table, row) {
				var orders = $.tableDnD.serialize();
				//alert(orders);
				$.post('accion_registro.php', { orders : orders, opcion:'ordenar_filas_cm' }, function(respuesta){
					if(respuesta==1){
						$("#msg").html('<div style="color:#62a426;">los registros se ordenaron con exito</div>').slideDown(1500);
						window.setTimeout('location.reload()', 4000);
					}else{
						$("#msg").html('<div style="color:#d44d24;">no se pudo realizar el proceso de orden, intente nuevamente</div>').slideDown(1500);
					}
					//alert(respuesta);
					//$(".msg").html(respuesta).fadeIn("fast").fadeOut(3500);
				});
			}
	   }); 
});  
	
</script>    
<?php
//SACAMOS LAS PREGUNTAS EXISTENTES
$idcm=base64_decode($_GET['idcm']);
$entidad=base64_decode($_GET['entidad']);
$compania=base64_decode($_GET['compania']);
$id_ef_cia=base64_decode($_GET['id_ef_cia']);

$select="select
			  scc.id_cc,
			  scc.id_cm,
			  scc.id_cuestionario,
			  scc.activado,
			  scc.orden,
			  sct.tipo,
			  scu.titulo as cuestionario
			from
			  s_cm_cert_cues as scc
			  inner join s_cm_certificado as sct on (sct.id_cm=scc.id_cm)
			  inner join s_cm_cuestionario as scu on (scu.id_cuestionario=scc.id_cuestionario)
			where
			  scc.id_cm=".$idcm."
			order by
			   scc.orden asc;";
   if($res = $conexion->query($select,MYSQLI_STORE_RESULT)){

		echo'
		<div class="da-panel collapsible">
			<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
				<ul class="action_user">
					<li style="margin-right:6px;">
					   <a href="?l=certmedico&var='.$_GET['var'].'&listar_cert_medicos=v&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
					   <img src="images/retornar.png" width="32" height="32"></a>
					</li>
					<li style="margin-right:6px;">
					   <a href="certmedico_registro.php?opcion=adicionar_cuestionario_cert&tipocert='.$_GET['tipocert'].'&compania='.$_GET['compania'].'&entidad='.$_GET['entidad'].'&idcm='.$_GET['idcm'].'" class="da-tooltip-s pregunta fancybox.ajax" title="<span lang=\'es\'>Agregar Cuestionario</span>">
					   <img src="images/adicionar_cuestionario.png" width="32" height="32"></a>
					</li>
				</ul>
			</div>
		</div>
		
		<div class="da-panel collapsible">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/list.png" alt="" />
					<b>'.$entidad.'</b> - <span lang="es">Listado de cuestionarios agregados</span><br/>
					<span id="msg"></span>
				</span>
			</div>
			<div class="da-panel-content">
				<table class="da-table tbl_repeat">
					<thead>
						<tr>
							<th><b><span lang="es">Nro</span></b></th>
							<th><b><span lang="es">Tipo Certificado</span></b></th>
							<th><b><span lang="es">Cuestionario</span></b></th>
							<th><b><span lang="es">Compañía de Seguros</span></b></th> 
							<th></th>
						</tr>
					</thead>
					<tbody>';
					  $num = $res->num_rows;
					  if($num>0){
							while($regi = $res->fetch_array(MYSQLI_ASSOC)){
								echo'<tr ';
										if((boolean)$regi['activado'] === false){
											echo'style="background:#D44D24; color:#ffffff;"'; 
										 }else{
											echo'';	 
										 }
								echo' id="'.$regi['id_cc'].'|'.$regi['id_cm'].'|'.$regi['id_cuestionario'].'">
										<td>'.$regi['orden'].'</td>
										<td>'.$regi['tipo'].'</td>
										<td>'.$regi['cuestionario'].'</td>
										<td>'.$compania.'</td>
										<td class="da-icon-column">
										   <ul class="action_user">';
												//VERIFICAMOS QUE EL IDCC NO EXISTA EN LA TABLA S_CM_GRUPO
												$buscaid="select id_cc from s_cm_grupo where id_cc=".$regi['id_cc'].";";
												$resbusc = $conexion->query($buscaid,MYSQLI_STORE_RESULT);
												$num_busca = $resbusc->num_rows;
												if($num_busca==0){	 
													echo'<li style="margin-right:5px;"><a href="#" class="eliminar da-tooltip-s" title="<span lang=\'es\'>Eliminar</span>" id="'.$regi['id_cc'].'|'.$idcm.'"></a></li>';
												}
												echo'<li><a href="?l=certmedico&agregar_pregunta=v&idcc='.base64_encode($regi['id_cc']).'&cuestionario='.base64_encode($regi['cuestionario']).'&compania='.$_GET['compania'].'&id_ef_cia='.$_GET['id_ef_cia'].'&compania='.$_GET['compania'].'&entidad='.$_GET['entidad'].'&var='.$_GET['var'].'&idcm='.$_GET['idcm'].'&tipocert='.base64_encode($regi['tipo']).'" class="add-pregunta da-tooltip-s" title="<span lang=\'es\'>Agregar preguntas</span>"></a>
													 </li>';
												if((boolean)$regi['activado'] === true){
													echo'<li style="margin-left:5px;"><a href="#" id="'.base64_encode($regi['id_cc']).'|'.base64_encode($regi['id_cm']).'|'.base64_encode($regi['id_cuestionario']).'|desactivar" class="darbaja da-tooltip-s disabled_enabled" title="<span lang=\'es\'>Desactivar</span>"></a></li>';
											    }else{
													echo'<li style="margin-left:5px;"><a href="#" id="'.base64_encode($regi['id_cc']).'|'.base64_encode($regi['id_cm']).'|'.base64_encode($regi['id_cuestionario']).'|activar" class="daralta da-tooltip-s disabled_enabled" title="<span lang=\'es\'>Activar</span>"></a></li>';
											    }	 
									  echo'</ul>	
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
   }else{
	   echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error."</div>";
   }
}

//VISUALIZAR LISTA DE PREGUNTAS AGREGADAS
function mostrar_lista_preguntas_agregadas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
?>
<link type="text/css" rel="stylesheet" href="plugins/fancybox/jquery.fancybox.css"/>
<script type="text/javascript" src="plugins/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript">
     $('.pregunta').fancybox({
	    maxWidth	: 400,
		maxHeight	: 350,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'elastic',
		closeEffect	: 'elastic'	 
	 });
</script>

<link type="text/css" rel="stylesheet" href="plugins/jalerts/jquery.alerts.css"/>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="plugins/jalerts/jquery.alerts.js"></script>
<script type="text/javascript" src="js/jquery.tablednd_0_5.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("a[href].eliminar").click(function(e){
	   var idgrupo = $(this).attr('id'); 		  
	   jConfirm("¿Esta seguro de eliminar el registro?", "Eliminar registro", function(r) {
			//alert(r);
			if(r) {
					var dataString ='idgrupo='+idgrupo+'&opcion=eliminaAddpregCuest';
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
	
	//ACTIVAR/DESACTIVAR
	$("a[href].disabled_enabled").click(function(e){
		   var valor = $(this).attr('id');
		   var vec = valor.split('|');
		   var id_grupo = vec[0];
		   var id_cc = vec[1];
		   var id_pregunta = vec[2];
		   var text = vec[3]; 		  
		   jConfirm("¿<span lang='es'>Esta seguro de "+text+" el cuestionario</span>?", ""+text+" registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_grupo='+id_grupo+'&id_cc='+id_cc+'&id_pregunta='+id_pregunta+'&text='+text+'&opcion=enabled_disabled_ap';
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
	
	$(".tbl_repeat tbody").tableDnD({
		  onDrop: function(table, row) {
			  var orders = $.tableDnD.serialize();
			  //alert(orders);
			  $.post('accion_registro.php', { orders : orders, opcion:'ordenar_filas_ap' }, function(respuesta){
				  if(respuesta==1){
					  $("#msg").html('<div style="color:#62a426;">los registros se ordenaron con exito</div>').slideDown(1500);
					  window.setTimeout('location.reload()', 4000);
				  }else{
					  $("#msg").html('<div style="color:#d44d24;">no se pudo realizar el proceso de orden, intente nuevamente</div>').slideDown(1500);
				  }
				  //alert(respuesta);
				  //$(".msg").html(respuesta).fadeIn("fast").fadeOut(3500);
			  });
		  }
	});    
});  
	
</script>    
<?php
//SACAMOS LAS PREGUNTAS EXISTENTES
$select="select
			  scg.id_grupo,
			  scg.id_cc,
			  scg.id_pregunta,
			  scg.orden,
			  scg.activado,
			  scp.pregunta,
			  case scp.tipo
				  when 'cb' then 'CHECKBOX'
				  when 'rd' then 'RADIO'
				  when 'text' then 'TEXTO'
				  when 'txtarea' then 'AREA DE TEXTO'
				end as tipo
			from
			  s_cm_grupo as scg
			  inner join s_cm_pregunta as scp on (scp.id_pregunta=scg.id_pregunta)
			where
			  scg.id_cc=".base64_decode($_GET['idcc'])."
			order by
			  scg.orden asc; ";
   if($res = $conexion->query($select,MYSQLI_STORE_RESULT)){

		echo'
		<div class="da-panel collapsible">
			<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
				<ul class="action_user">
					<li style="margin-right:6px;">
					   <a href="?l=certmedico&var='.$_GET['var'].'&agregarqu=v&idcm='.$_GET['idcm'].'&compania='.$_GET['compania'].'&entidad='.$_GET['entidad'].'&id_ef_cia='.$_GET['id_ef_cia'].'&tipocert='.$_GET['tipocert'].'" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
					   <img src="images/retornar.png" width="32" height="32"></a>
					</li>
					<li style="margin-right:6px;">
					   <a href="certmedico_registro.php?opcion=adicionar_pregunta_cuest&cuestionario='.$_GET['cuestionario'].'&compania='.$_GET['compania'].'&entidad='.$_GET['entidad'].'&id_ef_cia='.$_GET['id_ef_cia'].'&id_cc='.$_GET['idcc'].'&id_cm='.$_GET['idcm'].'" class="da-tooltip-s pregunta fancybox.ajax" title="<span lang=\'es\'>Agregar preguntas</span>">
					   <img src="images/add_cert.png" width="32" height="32"></a>
					</li>
				</ul>
			</div>
		</div>
		
		<div class="da-panel collapsible" style="width:850px;">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/list.png" alt="" />
					<b>'.base64_decode($_GET['entidad']).' - '.base64_decode($_GET['compania']).'</b><br/>
					<div style="margin-left:24px;">'.base64_decode($_GET['cuestionario']).' - <span lang="es">Listado de preguntas agregadas</span></div><br/>
					<span id="msg"></span>
				</span>
			</div>
			<div class="da-panel-content">
				<table class="da-table tbl_repeat">
					<thead>
						<tr>
							<th style="width:50px;"><b><span lang="es">Nro</span></b></th>
							<th><b><span lang="es">Pregunta</span></b></th>
							<th><b><span lang="es">Tipo</span></b></th>
							<th></th>
						</tr>
					</thead>
					<tbody>';
					  $num = $res->num_rows;
					  if($num>0){
							$i=1; 
							while($regi = $res->fetch_array(MYSQLI_ASSOC)){
								echo'<tr ';
										if((boolean)$regi['activado'] === false){
											echo'style="background:#D44D24; color:#ffffff;"'; 
										 }else{
											echo'';	 
										 }
								echo' id="'.$regi['id_grupo'].'|'.$regi['id_cc'].'|'.$regi['id_pregunta'].'">
										<td>'.$i.'</td>
										<td>'.$regi['pregunta'].'</td>
										<td>'.$regi['tipo'].'</td>
										<td class="da-icon-column">
										   <ul class="action_user">';
											   
												/*echo'<li style="margin-right:5px;"><a href="certmedico_registro.php?opcion=editar_pregunta&idcc='.$regi['id_cc'].'" class="edit da-tooltip-s pregunta fancybox.ajax" title="Editar"></a>
													 ';
												echo'<li style="margin-right:5px;">
													  <a href="#" class="eliminar da-tooltip-s" title="Eliminar" id="'.$regi['id_grupo'].'"></a>
													 </li>';*/
												if((boolean)$regi['activado'] === true){
													echo'<li style="margin-left:5px;"><a href="#" id="'.base64_encode($regi['id_grupo']).'|'.base64_encode($regi['id_cc']).'|'.base64_encode($regi['id_pregunta']).'|desactivar" class="darbaja da-tooltip-s disabled_enabled" title="<span lang=\'es\'>Desactivar</span>"></a></li>';
											    }else{
													echo'<li style="margin-left:5px;"><a href="#" id="'.base64_encode($regi['id_grupo']).'|'.base64_encode($regi['id_cc']).'|'.base64_encode($regi['id_pregunta']).'|activar" class="daralta da-tooltip-s disabled_enabled" title="<span lang=\'es\'>Activar</span>"></a></li>';
											    }	 
									  echo'</ul>	
										</td>
									</tr>';
								 $i++;	
							}			
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
   }else{
	  echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error."</div>";   
   }
}

?>