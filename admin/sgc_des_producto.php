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
			header('Location: index.php?l=des_producto&var=de&list_producto=v');
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
						
								agregar_nuevo_producto($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								
							} else {
								//VEMOS SI NOS PASAN UN ID DE USUARIO
								if(isset($_GET['idprcia'])) {
						
									if(isset($_GET['eliminar'])) {
										
										eliminar_producto($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									}elseif(isset($_GET['daralta'])){ 
									
									    activar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								    
									}elseif(isset($_GET['editar'])) {
										//SI NO ME PASAN 'CPASS' NI 'ELIMINAR', MUESTRO EL FORM PARA EDITAR USUARIO
										editar_producto($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									} 
								}elseif(isset($_GET['listarproductos'])){
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE FORMULARIOS EXISTENTES
									mostrar_lista_productos($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								}elseif(isset($_GET['list_producto'])){
									 //LISTAMOS LAS COMPAÑIAS ACTIVAS PARA AÑADIR-EDITAR O ELIMINAR TASAS
									listar_productos_activos($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion); 
								
							    }elseif(isset($_GET['listartasas'])){
									//VISUALIZAMOS LAS TASA PARA SER EDITADAS
									listar_tasas_editar($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								}elseif(isset($_GET['agregartasa'])){
									//VISUALIZAMOS AGREGAR NUEVAS TASAS
									agregar_tasas_nuevas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
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
function listar_productos_activos($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>
<link type="text/css" rel="stylesheet" href="plugins/fancybox/jquery.fancybox.css"/>
<script type="text/javascript" src="plugins/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript">
     $('.various').fancybox({
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
<script type="text/javascript">
   $(document).ready(function() {
       //CHECKED UNCHECKED
	   $('.producto').click(function(){
		   var variable = $(this).prop('id');
		   //alert(variable);
		   var data_text = $(this).prop('value');
		   var vec=data_text.split('|');
		   var id_producto=vec[0];
		   var id_ef_cia=vec[1];
		   if($('#'+variable).is(":checked")){
			   var vardata='V';
		   }else{
			   var vardata='F';
		   }
		   var dataString ='id_producto='+id_producto+'&id_ef_cia='+id_ef_cia+'&vardata='+vardata+'&opcion=update_producto';
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
		 setTimeout( "$(location).attr('href', 'index.php?l=des_producto&var=<?php echo $var;?>');",5000 );
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
$resef = $conexion->query($selectEf,MYSQLI_STORE_RESULT);
$num_regi_ef = $resef->num_rows;
if($num_regi_ef>0){
echo'<div class="da-panel collapsible">
		<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			<ul class="action_user">
				<li style="margin-right:6px;">
				   <a href="adicionar_registro.php?opcion=crear_tipo_producto&tipo_sesion='.base64_encode($tipo_sesion).'&id_ef_sesion='.base64_encode($id_ef_sesion).'" class="da-tooltip-s various fancybox.ajax" title="<span lang=\'es\'>Añadir Registro</span>">
				   <img src="images/add_new.png" width="32" height="32"></a>
				</li>
			</ul>
		</div>
	 </div>';
	 
	 while($regief = $resef->fetch_array(MYSQLI_ASSOC)){		 
		$select="select
				  sp.id_producto,
				  sp.id_ef_cia,
				  sp.nombre as tipo_producto,
				  (case sp.nombre
				     when 'PRODUCTO' then 'Con Producto Crediticio'
					 when 'NO PRODUCTO' then 'Sin Producto Crediticio'
				   end) as tipo_producto_text,
				  sp.activado,
				  sc.nombre as compania,
				  sc.logo
				from
				  s_producto as sp
				  inner join s_ef_compania as efc on (efc.id_ef_cia=sp.id_ef_cia)
				  inner join s_compania as sc on (sc.id_compania=efc.id_compania)
				where
				  id_ef='".$regief['id_ef']."' and efc.activado=1 and sc.activado=1 and efc.producto='DE';";
		//echo $select;
		$res = $conexion->query($select,MYSQLI_STORE_RESULT);		  
		echo'
			<div class="da-panel collapsible" style="width:700px;">
				<div class="da-panel-header">
					<span class="da-panel-title">
						<img src="images/icons/black/16/list.png" alt="" />
						<b>'.$regief['nombre'].'</b> - <span lang="es">Lista Registros</span> 
					</span>
				</div>
				<div class="da-panel-content">
					<table class="da-table">
						<thead>
							<tr>
								<th><b><span lang="es">Tipo registro</span></b></th>
								<th style="text-align:center;"><b><span lang="es">Compañía de Seguro</span></b></th>
								<th style="text-align:center;"><b><span lang="es">Estado</span></b></th>
								<th></th>
							</tr>
						</thead>
						<tbody>';
						  $num = $res->num_rows;
						  if($num>0){
							    
								while($regi = $res->fetch_array(MYSQLI_ASSOC)){
									echo'<tr ';
											  if($regi['activado']==0){
												  echo'style="background:#D44D24; color:#ffffff;"'; 
											   }else{
												  echo'';	 
											   }
									  echo'>
											<td>'.$regi['tipo_producto_text'].'</td>
											<td style="text-align:center;">';
											   if($regi['logo']!=''){
												   if(file_exists('../images/'.$regi['logo'])){  
													   $imagen = getimagesize('../images/'.$regi['logo']);    //Sacamos la información
													  $ancho = $imagen[0];              //Ancho
													  $alto = $imagen[1]; 
													  echo'<img src="../images/'.$regi['logo'].'" width="'.($ancho/2).'" height="'.($alto/2).'"/>';
												   }else{
													  echo'no existe el archivo fisico';   
												   }
											   }else{
												  echo'no existe el nombre del archivo en la base de datos';   
											   }
									   echo'</td>
									        <td style="text-align:center;">';
											   if($regi['activado']==1){
												  echo'<input type="radio" name="rd-'.$regi['id_producto'].'" class="producto" id="rd-'.$regi['id_producto'].'" value="'.$regi['id_producto'].'|'.$regi['id_ef_cia'].'" checked/>';
											   }else{
												  echo'<input type="radio" name="rd-'.$regi['id_producto'].'" class="producto" id="rd-'.$regi['id_producto'].'" value="'.$regi['id_producto'].'|'.$regi['id_ef_cia'].'"/>';  
											   }
									   echo'</td>
											<td class="da-icon-column">
											   <ul class="action_user">';
											   if($regi['activado']==1){
												   echo'<li style="padding-right:5px;"><a href="?l=des_producto&var='.$_GET['var'].'&listarproductos=v&id_ef_cia='.base64_encode($regi['id_ef_cia']).'&id_producto='.base64_encode($regi['id_producto']).'&compania='.base64_encode($regi['compania']).'&entidad_fin='.base64_encode($regief['nombre']).'&tipo_producto='.base64_encode($regi['tipo_producto']).'" class="add_mod da-tooltip-s various" title="<span lang=\'es\'>Agregar lista productos</span>"></a></li>';
												   echo'<li style="margin-right:5px;"><a href="?l=des_producto&id_ef_cia='.base64_encode($regi['id_ef_cia']).'&id_producto='.base64_encode($regi['id_producto']).'&entidad='.base64_encode($regief['nombre']).'&compania='.base64_encode($regi['compania']).'&listartasas=v&var='.$_GET['var'].'&tipo_producto='.base64_encode($regi['tipo_producto']).'" class="edit da-tooltip-s" title="<span lang=\'es\'>Editar Tasas Producto</span>"></a></li>';
											   }else{
												   echo'<li>&nbsp;</li>
												        <li>&nbsp;</li>';
											   }
											 /*echo'<li style="margin-right:5px;"><a href="?l=des_producto&idcompania='.base64_encode($regi['id_compania']).'&crear=v&var='.$_GET['var'].'" class="create da-tooltip-s" title="Agregar producto"></a></li>';*/
											 //echo'<li><a href="?l=compania&idcompania='.base64_encode($regi['id_compania']).'&darbaja=v&var='.$_GET['var'].'" class="darbaja da-tooltip-s" title="Desactivar"></a></li>';  
												 
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
											<li>No se encontraron registros</li>
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
function mostrar_lista_productos($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
?>
<link type="text/css" rel="stylesheet" href="plugins/fancybox/jquery.fancybox.css"/>
<script type="text/javascript" src="plugins/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript">
     $('.various').fancybox({
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
   $(function(){
	   $("a[href].eliminar").click(function(e){
		   var variable = $(this).attr('id'); 		  
		   var vec = variable.split('|');
		   var id_prcia = vec[0];
		   var id_ef_cia = vec[1];
		   var id_producto = vec[2];
		   jConfirm("¿Esta seguro de eliminar el producto?", "Eliminar registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_prcia='+id_prcia+'&id_ef_cia='+id_ef_cia+'&id_producto='+id_producto+'&opcion=eliminarproducto';
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
$id_ef_cia=base64_decode($_GET['id_ef_cia']);
$id_producto=base64_decode($_GET['id_producto']);
$nom_compania=base64_decode($_GET['compania']);
$nom_entidad=base64_decode($_GET['entidad_fin']);
$tipo_producto=base64_decode($_GET['tipo_producto']);
//SACAMOS EL LISTADO DE PRODUCTOS EXISTENTES
$selectFor="select
			  id_prcia,
			  nombre,
			  id_ef_cia,
			  id_producto
			from
			  s_producto_cia
			where
			  id_ef_cia='".$id_ef_cia."' and id_producto=".$id_producto.";";
$res = $conexion->query($selectFor,MYSQLI_STORE_RESULT);		  
echo'<div class="da-panel collapsible">
		<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			<ul class="action_user">
				<li style="margin-right:6px;">
					 <a href="?l=des_producto&var='.$_GET['var'].'&list_producto=v" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
					 <img src="images/retornar.png" width="32" height="32"></a>
				  </li>';
				  if($tipo_producto=='PRODUCTO'){
				 echo'<li style="margin-right:6px;">
					   <a href="adicionar_registro.php?opcion=agregar_productos&id_ef_cia='.$_GET['id_ef_cia'].'&id_producto='.$_GET['id_producto'].'&compania='.$_GET['compania'].'&entidad='.$_GET['entidad_fin'].'" class="da-tooltip-s various fancybox.ajax" title="<span lang=\'es\'>Añadir Producto</span>">
					   <img src="images/add_new.png" width="32" height="32"></a>
					</li>';
				  }
	   echo'</ul>
		</div>
	 </div>';
echo'
<div class="da-panel collapsible" style="width:700px;">
	<div class="da-panel-header">
		<span class="da-panel-title">
			<img src="images/icons/black/16/list.png" alt="" />
			<b>'.$nom_entidad.' - '.$nom_compania.'</b> - <span lang="es">Listado de Productos</span>
		</span>
	</div>
	<div class="da-panel-content">
		<table class="da-table">
			<thead>
				<tr>
					<th><b><span lang="es">Productos</span></b></th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>';
			  $num = $res->num_rows;
			  if($num>0){
				    $c=0;
					while($regi = $res->fetch_array(MYSQLI_ASSOC)){
						echo'<tr>
								<td>'.$regi['nombre'].'</td>
								<td class="da-icon-column">
								   <ul class="action_user">
									  <li style="margin-right:5px;"><a href="adicionar_registro.php?opcion=editar_productos&idprcia='.base64_encode($regi['id_prcia']).'&id_ef_cia='.$_GET['id_ef_cia'].'&id_producto='.$_GET['id_producto'].'&compania='.$_GET['compania'].'&entidad='.$_GET['entidad_fin'].'" class="edit da-tooltip-s various fancybox.ajax" title="<span lang=\'es\'>Editar</span>"></a></li>';
									  $selectBusca="select
													  id_tasa,
													  id_prcia
													from
													  s_tasa_de
													where
													  id_prcia=".$regi['id_prcia'].";";
									  $resbusca = $conexion->query($selectBusca,MYSQLI_STORE_RESULT);				  
									  $num = $resbusca->num_rows;				  
									  if($num>0){
										  echo'<li>&nbsp;</li>';
									  }else{
										  echo'<li><a href="#" id="'.$regi['id_prcia'].'|'.$_GET['id_ef_cia'].'|'.$_GET['id_producto'].'" class="eliminar da-tooltip-s" title="Eliminar"></a></li>';  
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
}

//FUNCION QUE PERMITE LISTAR LAS TASAS PARA EDITAR
function listar_tasas_editar($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
?>
<script type="text/javascript" src="plugins/ambience/jquery.ambiance.js"></script>
<script type="text/javascript">
  <?php 
    $op = $_GET["op"];
    $msg = $_GET["msg"];
	$var = $_GET["var"];
	$id_ef_cia = $_GET["id_ef_cia"];
	$id_producto = $_GET["id_producto"];
	$entidad = $_GET["entidad"];
	$compania = $_GET["compania"];
	
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
		 setTimeout( "$(location).attr('href', 'index.php?l=des_producto&listartasas=v&id_ef_cia=<?php echo $id_ef_cia;?>&id_producto=<?php echo $id_producto;?>&entidad=<?php echo $entidad;?>&compania=<?php echo $compania;?>&var=<?php echo $var;?>');",5000 );
	<?php }?>
	 
  });
</script>
<?php    
	//INICIAMOS EL ARRAY CON LOS ERRORES
	$errArr['errortasacom'] = '';
	$errArr['errortasaban'] = '';
	$errArr['errortasafin'] = '';
	$errFlag = false;
	
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
		
		$numerotasas=3;//No TASAS A EDITAR
		$t=1;
		while($t<=$numerotasas){
		    
			
			if($t==1){//COLUMNA TASA COMPAÑIA
				  $tc=1;
				  $indicetc=0;
				  $arrtc[]=array();
				  $swtc=0;
				   //iteramos hasta la cantidad de productos
				  while($tc<=$_POST['num_prod']){
					    $valor_tc=$_POST["txtTcompania".$tc];
					    $numtc=validar_numero($valor_tc);
					    if($numtc==1) {
							$arrtc[$indicetc]=$tc;
							$swtc=1;
							$indicetc++; 
						}elseif($numtc==2) {
							//sin errores, no guardamos nada en el vector
						   //y la variable sw es 0 
						}elseif($numtc==3){
							$arrtc[$indicetc]=$tc;
							$swtc=1; 
							$indicetc++;
						}
						$tc++;
				  }
				  if($swtc==0){
		             
				  }elseif($swtc==1){
						$errArr['errortasacom'] = "Debe ingresar la(s) tasa(s) en la(s) casilla(s): ";
						foreach($arrtc as $datotc){
							 $errArr['errortasacom'] .= $datotc.',';
						}
						$errArr['errortasacom'] .= '&nbsp;de la columna Tasa Compañía';			
						$errFlag = true;
						unset($arrtc);
				  }
			
			}elseif($t==2){//COLUMNA TASA BANCO
			      $tb=1;
				  $indicetb=0;
				  $arrtb[]=array();
				  $swtb=0;
				   //iteramos hasta la cantidad de productos
				  while($tb<=$_POST['num_prod']){
					    $valor_tb=$_POST["txtTbanco".$tb];
					    $numtb=validar_numero($valor_tb);
					    if($numtb==1) {
							$arrtb[$indicetb]=$tb;
							$swtb=1;
							$indicetb++; 
						}elseif($numtb==2) {
							//sin errores, no guardamos nada en el vector
						   //y la variable sw es 0 
						}elseif($numtb==3){
							$arrtb[$indicetb]=$tb;
							$swtb=1; 
							$indicetb++;
						}
						$tb++;
				  }
				  if($swtb==0){
		
				  }elseif($swtb==1){
						$errArr['errortasaban'] = "Debe ingresar la(s) tasa(s) en la(s) casilla(s): ";
						foreach($arrtb as $datotb){
							 $errArr['errortasaban'] .= $datotb.',';
						}
						$errArr['errortasaban'] .= '&nbsp;de la columna Tasa Banco';			
						$errFlag = true;
						unset($arrtb);
				  }
					
			}elseif($t==3){//COLUMNA TASA FINAL
			      $tf=1;
				  $indicetf=0;
				  $arrtf[]=array();
				  $swtf=0;
				   //iteramos hasta la cantidad de productos
				  while($tf<=$_POST['num_prod']){
					    $valor_tf=$_POST["txtTfinal".$tf];
					    $numtf=validar_numero($valor_tf);
					    if($numtf==1) {
							$arrtf[$indicetf]=$tf;
							$swtf=1;
							$indicetf++; 
						}elseif($numtf==2) {
							//sin errores, no guardamos nada en el vector
						   //y la variable sw es 0 
						}elseif($numtf==3){
							$arrtf[$indicetf]=$tf;
							$swtf=1; 
							$indicetf++;
						}
						$tf++;
				  }
				  if($swtf==0){
		
				  }elseif($swtf==1){
						$errArr['errortasafin'] = "Debe ingresar la(s) tasa(s) en la(s) casilla(s): ";
						foreach($arrtf as $datotf){
							 $errArr['errortasafin'] .= $datotf.',';
						}
						$errArr['errortasafin'] .= '&nbsp;de la columna Tasa Final';			
						$errFlag = true;
						unset($arrtf);
				  }
			
			}
			
		  $t++;	
		
		}//FIN WHILE NUMERO TASAS
		
		//VEMOS SI NO HUBO ERRORES*/
		if($errFlag) {
			//HUBIERON ERRORES, MOSTRAMOS EL FORM CON LOS ERRORES
			mostrar_editar_tasas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
		} else {
			$j=1;								 
			while($j<=$_POST['num_prod']){
				$tasacompania=$_POST["txtTcompania".$j];
				$tasabanco=$_POST["txtTbanco".$j];
				$tasafinal=$_POST["txtTfinal".$j];
				$idtasa=$_POST["idtasa".$j];
				$idprcia=$_POST["idprcia".$j];
				$update = "UPDATE s_tasa_de SET tasa_cia=".$tasacompania.", tasa_banco=".$tasabanco.", tasa_final=".$tasafinal." WHERE id_tasa=".$idtasa." and id_prcia=".$idprcia.";"; 
				//$rsudate = mysql_query($update, $conexion);
				if($conexion->query($update)===TRUE){ $response=TRUE;}else{$response=FALSE;}
				$j++;
			}		
			
			if($response){
			    $mensaje="Se actualizo correctamente los datos del formulario";
			    header('Location: index.php?l=des_producto&listartasas=v&id_ef_cia='.$_GET['id_ef_cia'].'&id_producto='.$_GET['id_producto'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&var='.$_GET['var'].'&tipo_producto='.$_GET['tipo_producto'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
			} else{
			    $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " .$conexion->error;
			    header('Location: index.php?l=des_producto&listartasas=v&id_ef_cia='.$_GET['id_ef_cia'].'&id_producto='.$_GET['id_producto'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&var='.$_GET['var'].'&tipo_producto='.$_GET['tipo_producto'].'&op=2&msg='.base64_encode($mensaje));
				exit;
			} 
		}//CERRAMOS LLAVE ELSE SI NO HUBIERON ERRORES
        
	} else {
	  //MUESTRO FORM PARA EDITAR LAS TASAS
	  mostrar_editar_tasas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
	}
}

//VISUALIZMOS EL FORMULARIO CON LAS TASAS PARA SE EDITADAS
function mostrar_editar_tasas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr){
?>	
<link type="text/css" rel="stylesheet" href="plugins/jalerts/jquery.alerts.css"/>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="plugins/jalerts/jquery.alerts.js"></script>
<script type="text/javascript">
   $(function(){
	   $("a[href].eliminar").click(function(e){
		   var variable = $(this).attr('id'); 		  
		   var vec = variable.split('|');
		   var id_tasa = vec[0];
		   var id_prcia = vec[1];
		   var id_ef_cia = vec[2];
		   var id_producto = vec[3];
		   jConfirm("¿Esta seguro de eliminar las tasas?", "Eliminar registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_tasa='+id_tasa+'&id_prcia='+id_prcia+'&id_ef_cia='+id_ef_cia+'&id_producto='+id_producto+'&opcion=eliminartasas';
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
<script type="text/javascript">
  $(function(){
	  $('#btnCancelar').click(function(){
		  var variable=$('#var').prop('value');  
		  $(location).attr('href', 'index.php?l=des_tasas&var='+variable); 
	  });  
  });
</script>	
<?php    
	$id_ef_cia=base64_decode($_GET['id_ef_cia']);
	$id_producto=base64_decode($_GET['id_producto']);
	$entidad=base64_decode($_GET['entidad']);
	$compania=base64_decode($_GET['compania']);
	$tipo_producto=base64_decode($_GET['tipo_producto']);
	//SACAMOS LAS TASAS
	$selectTs="select 
				  spc.id_prcia,
				  spc.nombre,
				  spc.id_ef_cia,
				  spc.id_producto,
				  td.id_tasa,
				  tasa_cia,
				  tasa_banco,
				  tasa_final
				from
				  s_producto_cia as spc
				  inner join s_tasa_de as td on (td.id_prcia=spc.id_prcia)
				  inner join s_producto as sp on (sp.id_producto=spc.id_producto)
				where
				  spc.id_ef_cia='".$id_ef_cia."' and spc.id_producto=".$id_producto." and sp.activado=1;";		  
	$resu = $conexion->query($selectTs,MYSQLI_STORE_RESULT);			  		  
echo'<div class="da-panel collapsible">
		<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			<ul class="action_user">
				<li style="margin-right:6px;">
					 <a href="?l=des_producto&var='.$_GET['var'].'&list_producto=v" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
					 <img src="images/retornar.png" width="32" height="32"></a>
				</li>';
				if($tipo_producto=='PRODUCTO'){
			   echo'<li style="margin-right:6px;">
					   <a href="?l=des_producto&id_ef_cia='.$_GET['id_ef_cia'].'&id_producto='.$_GET['id_producto'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&agregartasa=v&var='.$_GET['var'].'&tipo_producto='.$_GET['tipo_producto'].'" class="da-tooltip-s" title="<span lang=\'es\'>Añadir nuevas tasas</span>">
					   <img src="images/add_new.png" width="32" height="32"></a>
					</li>';
				}
		echo'</ul>
		</div>
	 </div>';
echo'
<div class="da-panel collapsible" style="width:780px;">
	<div class="da-panel-header">
		<span class="da-panel-title">
			<img src="images/icons/black/16/list.png" alt="" />
		    <b>'.$entidad.' - '.$compania.'</b> - <span lang="es">Editar Tasas</span>
		</span>
	</div>
	<div class="da-panel-content">';
	 $num = $resu->num_rows;
	 if($num>0){
	  echo'<form class="da-form" name="frmUsuario" action="" method="post">
	     		<div class="da-form-row" style="padding:0px;">
				  <div class="da-form-item large" style="margin:0px;">
					<table class="da-table">
						<thead>
							<tr>
								<th><b>No</b></th>
								<th style="width:200px;"><b><span lang="es">Producto</span></b></th>
								<th><b><span lang="es">Tasa Compañía</span></b></th>
								<th><b><span lang="es">Tasa Banco</span></b></th>
								<th><b><span lang="es">Tasa Final</span></b></th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>';
						 
								$i=1;
								$suma_total=0;
								while($regi = $resu->fetch_array(MYSQLI_ASSOC)){
									if(isset($_POST["txtTcompania".$i])) $tcompania = $_POST["txtTcompania".$i]; else $tcompania = $regi['tasa_cia']; 
									if(isset($_POST["txtTbanco".$i])) $tbanco = $_POST["txtTbanco".$i]; else $tbanco = $regi['tasa_banco']; 
									if(isset($_POST["txtTfinal".$i])) $tfinal = $_POST["txtTfinal".$i]; else $tfinal = $regi['tasa_final']; 
									$suma_total=$tcompania+$tbanco;
									echo'<tr>
											<td>'.$i.'</td>
											<td>'.$regi['nombre'].'</td>
											<td><input type="text" name="txtTcompania'.$i.'" id="'.$i.'-txtTcompania" value="'.$regi['tasa_cia'].'" class="validatasa"/><span class="errorMessage" id="errortcia'.$i.'"></span></td>
											<td><input type="text" name="txtTbanco'.$i.'" id="'.$i.'-txtTbanco" value="'.$regi['tasa_banco'].'" class="validatasa"/><span class="errorMessage" id="errortban'.$i.'"></span></td>
											<td><input type="text" name="txtTfinal'.$i.'" id="'.$i.'-txtTfinal" value="'.$suma_total.'" readonly="readonly" class="validatasa"/>
											<input type="hidden" name="idtasa'.$i.'" id="idtasa'.$i.'" value="'.$regi['id_tasa'].'"/>
											<input type="hidden" name="idprcia'.$i.'" id="idprcia'.$i.'" value="'.$regi['id_prcia'].'"/></td>
											<td class="da-icon-column">
											   <ul class="action_user">
											     <li><a href="#" id="'.$regi['id_tasa'].'|'.$regi['id_prcia'].'|'.$id_ef_cia.'|'.$id_producto.'" class="eliminar da-tooltip-s" title="<span lang=\'es\'>Eliminar</span>"></a></li>
											   </ul>
											</td>
										</tr>';
										$i++;
								}			
						        $resu->free();
						  echo'<tr><td colspan="6">
								  <span class="errorMessage">'.$errArr['errortasacom'].'</span>
								  <span class="errorMessage">'.$errArr['errortasaban'].'</span>
								  <span class="errorMessage">'.$errArr['errortasafin'].'</span>
							   </td></tr>';
				   echo'</tbody>
					</table>
				  </div>	
		        </div>
			    <div class="da-button-row">
				   
				   <input type="submit" value="Guardar" class="da-button green" name="btnPregunta" id="btnPregunta" lang="es"/>
				   <input type="hidden" name="accionGuardar" value="checkdatos"/>
				   <input type="hidden" name="num_prod" value="'.$num.'" id="num_prod"/>
				   <input type="hidden" id="var" value="'.$_GET['var'].'"/>
			    </div>	
	       </form>';
     }else{
		 echo'<div class="da-message info">
				  No existe registros alguno, ingrese nuevos registros
			  </div>';
	 }		   	
echo'</div>
</div>';
}

//FUNCION QUE PERMITE VISUALIZAR EL FORMULARIO NUEVAS TASAS
function agregar_tasas_nuevas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
	//INICIAMOS EL ARRAY CON LOS ERRORES
	$errArr['errortasacom'] = '';
	$errArr['errortasaban'] = '';
	$errArr['errortasafin'] = '';
	$errFlag = false;
     
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
			
		$numerotasas=3;
		$t=1;
		while($t<=$numerotasas){
		    
			
			if($t==1){//COLUMNA TASA COMPAÑIA
				  $tc=1;
				  $indicetc=0;
				  $arrtc[]=array();
				  $swtc=0;
				   //iteramos hasta la cantidad de productos
				  while($tc<=$_POST['num_prod']){
					    $valor_tc=$_POST["txtTcompania".$tc];
					    $numtc=validar_numero($valor_tc);
					    if($numtc==1) {
							$arrtc[$indicetc]=$tc;
							$swtc=1;
							$indicetc++; 
						}elseif($numtc==2) {
							//sin errores, no guardamos nada en el vector
						   //y la variable sw es 0 
						}elseif($numtc==3){
							$arrtc[$indicetc]=$tc;
							$swtc=1; 
							$indicetc++;
						}
						$tc++;
				  }
				  if($swtc==0){
		             
				  }elseif($swtc==1){
						$errArr['errortasacom'] = "Debe ingresar la(s) tasa(s) en la(s) casilla(s): ";
						foreach($arrtc as $datotc){
							 $errArr['errortasacom'] .= $datotc.',';
						}
						$errArr['errortasacom'] .= '&nbsp;de la columna Tasa Compa&ntilde;&iacute;a';			
						$errFlag = true;
						unset($arrtc);
				  }
			
			}elseif($t==2){//COLUMNA TASA BANCO
			      $tb=1;
				  $indicetb=0;
				  $arrtb[]=array();
				  $swtb=0;
				   //iteramos hasta la cantidad de productos
				  while($tb<=$_POST['num_prod']){
					    $valor_tb=$_POST["txtTbanco".$tb];
					    $numtb=validar_numero($valor_tb);
					    if($numtb==1) {
							$arrtb[$indicetb]=$tb;
							$swtb=1;
							$indicetb++; 
						}elseif($numtb==2) {
							//sin errores, no guardamos nada en el vector
						   //y la variable sw es 0 
						}elseif($numtb==3){
							$arrtb[$indicetb]=$tb;
							$swtb=1; 
							$indicetb++;
						}
						$tb++;
				  }
				  if($swtb==0){
		
				  }elseif($swtb==1){
						$errArr['errortasaban'] = "Debe ingresar la(s) tasa(s) en la(s) casilla(s): ";
						foreach($arrtb as $datotb){
							 $errArr['errortasaban'] .= $datotb.',';
						}
						$errArr['errortasaban'] .= '&nbsp;de la columna Tasa Banco';			
						$errFlag = true;
						unset($arrtb);
				  }
					
			}elseif($t==3){//COLUMNA TASA FINAL
			      $tf=1;
				  $indicetf=0;
				  $arrtf[]=array();
				  $swtf=0;
				   //iteramos hasta la cantidad de productos
				  while($tf<=$_POST['num_prod']){
					    $valor_tf=$_POST["txtTfinal".$tf];
					    $numtf=validar_numero($valor_tf);
					    if($numtf==1) {
							$arrtf[$indicetf]=$tf;
							$swtf=1;
							$indicetf++; 
						}elseif($numtf==2) {
							//sin errores, no guardamos nada en el vector
						   //y la variable sw es 0 
						}elseif($numtf==3){
							$arrtf[$indicetf]=$tf;
							$swtf=1; 
							$indicetf++;
						}
						$tf++;
				  }
				  if($swtf==0){
		
				  }elseif($swtf==1){
						$errArr['errortasafin'] = "Debe ingresar la(s) tasa(s) en la(s) casilla(s): ";
						foreach($arrtf as $datotf){
							 $errArr['errortasafin'] .= $datotf.',';
						}
						$errArr['errortasafin'] .= '&nbsp;de la columna Tasa Final';			
						$errFlag = true;
						unset($arrtf);
				  }
			
			}
			
		  $t++;	
		
		}
								
		//VEMOS SI TODO SE VALIDO BIEN
		if($errFlag) {
			//HUBIERON ERRORES, MOSTRAMOS EL FORM CON LOS ERRORES
			mostrar_crear_tasas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
		} else {
						
			$j=1;								 
			while($j<=$_POST['num_prod']){
				$tasacompania=$_POST["txtTcompania".$j];
				$tasabanco=$_POST["txtTbanco".$j];
				$tasafinal=$_POST["txtTfinal".$j];
				$idprcia=$_POST["idprcia".$j];
				
				$insert = "INSERT INTO s_tasa_de(id_tasa, id_prcia, tasa_cia, tasa_banco, tasa_final) "
			      ."VALUES(NULL, ".$idprcia.", ".$tasacompania.", ".$tasabanco.", ".$tasafinal.")"; 
				//$resu = mysql_query($insert, $conexion);
				if($conexion->query($insert)===TRUE){ $response=TRUE;}else{$response=FALSE;}
				$j++;
			}
			//METEMOS A LA TABLA TBLHOMENOTICIAS
			if($response){
				$mensaje="Se registro correctamente los datos del formulario";
			    header('Location: index.php?l=des_producto&listartasas=v&id_ef_cia='.$_GET['id_ef_cia'].'&id_producto='.$_GET['id_producto'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&var='.$_GET['var'].'&tipo_producto='.$_GET['tipo_producto'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
			} else {
				$mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " .$conexion->error;
			    header('Location: index.php?l=des_producto&listartasas=v&id_ef_cia='.$_GET['id_ef_cia'].'&id_producto='.$_GET['id_producto'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&var='.$_GET['var'].'&tipo_producto='.$_GET['tipo_producto'].'&op=1&msg='.base64_encode($mensaje));
				exit;
			}	
		}

	} else {
		//MUESTRO EL FORM PARA CREAR UNA CATEGORIA
		mostrar_crear_tasas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
	}
}

//VISUALIZAMOS EL FORMULARIO CREA USUARIO
function mostrar_crear_tasas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr){
    $id_ef_cia=base64_decode($_GET['id_ef_cia']);
	$id_producto=base64_decode($_GET['id_producto']);
	$entidad=base64_decode($_GET['entidad']);
	$compania=base64_decode($_GET['compania']);
	  		
	$selectTs="select 
					spc.id_prcia, spc.nombre, spc.id_producto
				from
					s_producto_cia as spc
						inner join
					s_producto as sp ON (sp.id_producto = spc.id_producto)
						left join
					s_tasa_de as std ON (std.id_prcia = spc.id_prcia)
				where
					spc.id_ef_cia='".$id_ef_cia."' and spc.id_producto=".$id_producto."
						and sp.activado = 1
						and not exists( select 
							std2.id_prcia
						from
							s_tasa_de as std2
						where
							std2.id_prcia = spc.id_prcia);";
	$resu = $conexion->query($selectTs,MYSQLI_STORE_RESULT);			  
echo'<div class="da-panel collapsible">
		<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			<ul class="action_user">
				<li style="margin-right:6px;">
					 <a href="?l=des_producto&var='.$_GET['var'].'&listartasas=v&id_ef_cia='.$_GET['id_ef_cia'].'&id_producto='.$_GET['id_producto'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&tipo_producto='.$_GET['tipo_producto'].'" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
					 <img src="images/retornar.png" width="32" height="32"></a>
				</li>
			</ul>
		</div>
	 </div>';

echo'
<div class="da-panel collapsible" style="width:780px;">
	<div class="da-panel-header">
		<span class="da-panel-title">
			<img src="images/icons/black/16/list.png" alt="" />
		    <b>'.$entidad.' - '.$compania.'<b/> - <span lang="es">Agregar nuevas tasas</span>
		</span>
	</div>
	<div class="da-panel-content">';
	 $num = $resu->num_rows;
	 if($num>0){
	  echo'<form class="da-form" name="frmTasas" action="" method="post" id="frmTasas">
	     		<div class="da-form-row" style="padding:0px;">
				   <div class="da-form-item large" style="margin:0px;">
					<table class="da-table">
						<thead>
							<tr>
								<th style="width:200px;"><b>Producto</b></th>
								<th><b>Tasa Compañia</b></th>
								<th><b>Tasa Banco</b></th>
								<th><b>Tasa Final</b></th>
							</tr>
						</thead>
						<tbody>';
						 
								$i=1;
								$suma_total=0;
								while($regi = $resu->fetch_array(MYSQLI_ASSOC)){
									if(isset($_POST["txtTcompania".$i])) $tcompania = $_POST["txtTcompania".$i]; else $tcompania = ''; 
									if(isset($_POST["txtTbanco".$i])) $tbanco = $_POST["txtTbanco".$i]; else $tbanco = ''; 
									if(isset($_POST["txtTfinal".$i])) $tfinal = $_POST["txtTfinal".$i]; else $tfinal = ''; 
									if((!empty($tcompania)) and (!empty($tbanco))){
									   $suma_total=$tcompania+$tbanco;
									}else{
									   $suma_total=0;
									}
									echo'<tr>
											
											<td>'.$regi['nombre'].'</td>
											<td><input type="text" name="txtTcompania'.$i.'" id="'.$i.'-txtTcompania" value="'.$tcompania.'" class="validatasa"/><span class="errorMessage" id="errortcia'.$i.'"></span></td>
											<td><input type="text" name="txtTbanco'.$i.'" id="'.$i.'-txtTbanco" value="'.$tbanco.'" class="validatasa"/><span class="errorMessage" id="errortban'.$i.'"></span></td>
											<td><input type="text" name="txtTfinal'.$i.'" id="'.$i.'-txtTfinal" value="'.$suma_total.'" readonly="readonly" class="validatasa"/><input type="hidden" name="idprcia'.$i.'" id="idprcia'.$i.'" value="'.$regi['id_prcia'].'"/></td>
										</tr>';
										$i++;
								}			
						        $resu->free();
						  echo'<tr><td colspan="5">
								  <span class="errorMessage">'.$errArr['errortasacom'].'</span>
								  <span class="errorMessage">'.$errArr['errortasaban'].'</span>
								  <span class="errorMessage">'.$errArr['errortasafin'].'</span>
							   </td></tr>';
				   echo'</tbody>
					</table>
				   </div>	
		        </div>
			    <div class="da-button-row">
				   <input type="reset" value="Reset" class="da-button gray left"/>
				   <input type="submit" value="Guardar" class="da-button green" name="btnSaveTasas" id="btnSaveTasas"/>
				   <input type="hidden" name="accionGuardar" value="checkdatos"/>
				   <input type="hidden" name="num_prod" value="'.$num.'" id="num_prod"/>
			    </div>	
	       </form>';
     }else{
		 echo'<div class="da-message info">
				  <span lang="es">No existe registros alguno, para ingresar nuevas tasas añada un nuevo producto</span>.
			  </div>';
	 }		   	
echo'</div>
</div>';
}
?>