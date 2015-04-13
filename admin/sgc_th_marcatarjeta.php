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
			header('Location: index.php?l=th_marcatarjeta&var=th&list_compania=v');
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
								if(isset($_GET['listarmarcatarjeta'])) {
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE FORMULARIOS EXISTENTES
									mostrar_lista_marca_tarjeta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
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
							sh.id_ef = ef.id_ef and sh.producto='TH');";
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
							sh.id_ef = ef.id_ef and sh.producto='TH')
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
				  sef.id_ef='".$regief['id_ef']."' and sef.activado=1 and sc.activado=1 and sef.producto='TH';";
		$res = $conexion->query($select,MYSQLI_STORE_RESULT);		  
		echo'
			<div class="da-panel collapsible" style="width:700px;">
				<div class="da-panel-header">
					<span class="da-panel-title">
						<img src="images/icons/black/16/list.png" alt="" />
						<b>'.$regief['nombre'].'</b> - <span lang="es">Administrar marca tarjeta</span> 
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
												   echo'<li style="margin-right:5px;"><a href="?l=th_marcatarjeta&id_ef_cia='.base64_encode($regi['id_ef_cia']).'&entidad='.base64_encode($regief['nombre']).'&compania='.base64_encode($regi['compania']).'&id_ef='.base64_encode($regi['id_ef']).'&listarmarcatarjeta=v&var='.$_GET['var'].'" class="admi-prodextra da-tooltip-s" title="<span lang=\'es\'>Administrar marca tarjeta</span>"></a></li>';
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
										 <span lang="es">No existe registros alguno, razones alguna</span>:
										 <ul>
											<li lang="es">Verifique que la Compañía de Seguros este activada</li>
											<li lang="es">Verifique que la Compañía asignada a la Entidad Financiera este activada</li>
											<li lang="es">Verifique que el producto exista en la Compañía asignada a la Entidad Financiera</li>
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
				  <li lang="es">La Entidad Financiera no tiene asignado el producto Tarjetahabiente</li>
				  <li lang="es">La Entidad Financiera no esta activado</li>
				  <li lang="es">La Entidad Financiera no esta creada</li>
				</ul>
		   </div>'; 
 }
}


//FUNCION QUE PERMITE LISTAR LOS FORMULARIOS
function mostrar_lista_marca_tarjeta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>
<link type="text/css" rel="stylesheet" href="plugins/fancybox/jquery.fancybox.css"/>
<script type="text/javascript" src="plugins/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript">
     $('.marcatarjeta').fancybox({
	    maxWidth	: 400,
		maxHeight	: 350,
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
		   var valor = $(this).attr('id');
		   var vec = valor.split('|');
		   var id_ef_cia = vec[0];
		   var id_marca = vec[1];
		   var c = vec[2];
		   		  
		   jConfirm("¿Esta seguro de eliminar el registro?", "eliminar registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_marca='+id_marca+'&id_ef_cia='+id_ef_cia+'&opcion=elimina_marcatj';
						$.ajax({
							   async: true,
							   cache: false,
							   type: "POST",
							   url: "eliminar_registro.php",
							   data: dataString,
							   success: function(datareturn) {
									  //alert(datareturn);
									  if(datareturn==1){
										// location.reload(true);
										$('#delete-'+c).hide('slow');
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
	   
	   $("a[href].accionef").click(function(e){
		   var valor = $(this).attr('id');
		   var vec = valor.split('|');
		   var id_ef_cia = vec[0];
		   var id_marca = vec[1];
		   var text = vec[2]; 		  
		   jConfirm("¿Esta seguro de "+text+" la marca?", ""+text+" registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_ef_cia='+id_ef_cia+'&id_marca='+id_marca+'&text='+text+'&opcion=active_marcatj';
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
			   thm.id_marca,
			   thm.id_ef_cia,
			   thm.marca,
			   thm.activado,
			   (case thm.activado
			     when 1 then 'Activo'
				 when 0 then 'Inactivo'
				end) as activado_text 
			from
			  s_th_marca as thm
			  inner join s_ef_compania as efcia on (efcia.id_ef_cia=thm.id_ef_cia)
			where  
			  thm.id_ef_cia='".$id_ef_cia."' and efcia.producto='TH';";
$res = $conexion->query($selectFor,MYSQLI_STORE_RESULT);		  
echo'<div class="da-panel collapsible">
		<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			<ul class="action_user">
				<li style="margin-right:6px;">
					 <a href="?l=th_marcatarjeta&var='.$_GET['var'].'&list_compania=v" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
					 <img src="images/retornar.png" width="32" height="32"></a>
				</li>
				<li style="margin-right:6px;">
				   <a href="adicionar_registro.php?opcion=crear_marcatarjeta&id_ef_cia='.$_GET['id_ef_cia'].'&compania='.$_GET['compania'].'&entidad='.$_GET['entidad'].'&id_ef='.$_GET['id_ef'].'" class="da-tooltip-s marcatarjeta fancybox.ajax" title="<span lang=\'es\'>Añadir marca tarjeta</span>">
				   <img src="images/add_new.png" width="32" height="32"></a>
				</li>
			</ul>
		</div>
	 </div>';
echo'
<div class="da-panel collapsible" style="width:650px;">
	<div class="da-panel-header">
		<span class="da-panel-title">
			<img src="images/icons/black/16/list.png" alt="" />
			<b>'.$entidad.'</b><div style="margin-left:25px;"><b>'.$compania.'</b> - <span lang="es">Listado Marca de Tarjetas</span></div>
		</span>
	</div>
	<div class="da-panel-content">
		<table class="da-table">
			<thead>
				<tr>
					<th style="text-align:center;"><b><span lang="es">Marca de la Tarjeta</span></b></th>
					<th style="text-align:center;"><b><span lang="es">Estado</span></b></th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>';
			  $num = $res->num_rows;
			  if($num>0){
				    $c=0;
					while($regi = $res->fetch_array(MYSQLI_ASSOC)){
						$c++;
						echo'<tr ';
						        if((boolean)$regi['activado']===false){
									echo'style="background:#D44D24; color:#ffffff;"'; 
								 }else{
								    echo'';	 
							     }
						echo' id="delete-'.$c.'">
						       	<td style="text-align:center;">'.$regi['marca'].'</td>
								<td style="text-align:center;" lang="es">'.$regi['activado_text'].'</td>
								<td class="da-icon-column">
								  <ul class="action_user">';
									  if((boolean)$regi['activado']===false){
										  echo'<li><a href="#" id="'.base64_encode($regi['id_ef_cia']).'|'.base64_encode($regi['id_marca']).'|activar" class="daralta da-tooltip-s accionef" title="Activar"></a></li>';
									  }else{
										  echo'<li><a href="#" id="'.base64_encode($regi['id_ef_cia']).'|'.base64_encode($regi['id_marca']).'|desactivar" class="darbaja da-tooltip-s accionef" title="Desactivar"></a></li>';  
									  }
									  $busca="select
												count(id_cotizacion) as row_cta
											  from
												s_th_cot_cabecera
											  where
												id_marca='".$regi['id_marca']."';";
									  if($resb = $conexion->query($busca,MYSQLI_STORE_RESULT)){
										  $regib = $resb->fetch_array(MYSQLI_ASSOC);
										  echo'<li style="margin-left:5px;"><a href="#" class="eliminar da-tooltip-s" title="Eliminar" id="'.$regi['id_ef_cia'].'|'.$regi['id_marca'].'|'.$c.'"></a></li>';
									  }else{
										 echo'<div class="da-message error">error en la consulta'.$conexion->errno.'&nbsp;'.$conexion->error.'</div>'; 
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
}


?>