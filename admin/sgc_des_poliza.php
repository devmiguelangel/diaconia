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
			header('Location: index.php?l=des_poliza&var=pl');
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
						
								agregar_nueva_poliza($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
								
							} else {
								//VEMOS SI NOS PASAN UN ID DE USUARIO
								if(isset($_GET['idpoliza'])) {
						
									if(isset($_GET['eliminar'])) {
										
										eliminar_poliza($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
										
									}elseif(isset($_GET['editar'])) {
										//FUNCION PARA EDITAR DATOS 
										editar_datos_poliza($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
										
									} 
								}elseif(isset($_GET['listarpolizas'])){ 
							        //LISTAMOS LAS POLIZAS CREADAS
									mostrar_lista_datos_poliza($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
								}else {
									//LISTAMOS LAS COMPAÑIAS ACTIVAS PARA ADMINISTRAR POLIZAS
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
        	<div class="da-container clearfix">
            	<p>Copyright 2012. Dandelion Admin. All Rights Reserved.</p>
            </div>
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
						    and ef.id_ef='".$id_ef_sesion."';";
	}
	if($resef = $conexion->query($selectEf,MYSQLI_STORE_RESULT)){
			$num_regi_ef = $resef->num_rows;
			if($num_regi_ef>0){
				while($regief = $resef->fetch_array(MYSQLI_ASSOC)){
				/*$selectFor="select
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
							 efc.id_ef='".$regief['id_ef']."' and efc.activado=1 and scia.activado=1 and efc.producto=sh.producto;";*/
				   $selectFor="select 
									efc.id_ef_cia,
									efc.id_ef,
									efc.id_compania,
									efc.producto as producto_code,
									scia.logo,
									scia.nombre as compania,
									case efc.activado
										when 1 then 'activo'
										when 0 then 'inactivo'
									end as activado,
									sh.producto_nombre
								from
									s_ef_compania as efc
										inner join
									s_compania as scia ON (scia.id_compania = efc.id_compania)
										inner join
									s_sgc_home as sh ON (sh.id_ef = efc.id_ef)
								where
									efc.id_ef = '".$regief['id_ef']."'
										and efc.activado = 1
										and scia.activado = 1
										and efc.producto = sh.producto;";				 
				   if($res = $conexion->query($selectFor,MYSQLI_STORE_RESULT)){		  
						echo'
						<div class="da-panel collapsible" style="width:900px;">
							<div class="da-panel-header">
								<span class="da-panel-title">
									<img src="images/icons/black/16/list.png" alt="" />
									<b>'.$regief['nombre'].'</b> - <span lang="es">Editar Pólizas</span></b>
								</span>
							</div>
							<div class="da-panel-content">
								<table class="da-table">
									<thead>
										<tr>
											<th><b><span lang="es">Titulo Compañía</span></b></th>
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
															  <li style="margin-right:5px;"><a href="?l=des_poliza&id_ef_cia='.base64_encode($regi['id_ef_cia']).'&id_ef='.base64_encode($regief['id_ef']).'&entidad='.base64_encode($regief['nombre']).'&compania='.base64_encode($regi['compania']).'&producto_nombre='.base64_encode($regi['producto_nombre']).'&producto_code='.base64_encode($regi['producto_code']).'&listarpolizas=v&var='.$_GET['var'].'" class="edit da-tooltip-s" title="<span lang=\'es\'>Editar pólizas</span>"></a></li>';
														 //echo'<li style="margin-right:5px;"><a href="?l=des_poliza&idcompania='.base64_encode($regi['id_compania']).'&id_ef='.base64_encode($regief['id_ef']).'&id_poliza='.$regi['id_poliza'].'crear=v&var='.$_GET['var'].'" class="create da-tooltip-s" title="Agregar polizas"></a></li>';
														 //echo'<li><a href="?l=compania&idcompania='.base64_encode($regi['id_compania']).'&darbaja=v&var='.$_GET['var'].'" class="darbaja da-tooltip-s" title="Desactivar"></a></li>';  
															 
													  echo'</ul>	
														</td>
													</tr>';
											}
											$res->free();			
									  }else{
										 echo'<tr><td colspan="7">
												  <div class="da-message warning">
													   <span lang="es">No existe ningún registro, razones alguna:</span>
													   <ul>
														  <li lang="es">Verifique que la Compañía de Seguros este activada</li>
														  <li lang="es">Verifique que la Compañía asignada a la Entidad Financiera este activada</li>
														</ul>
													</div>
											  </td></tr>';
									  }
							   echo'</tbody>
								</table>
							</div>
						</div>';
				   }else{
					   echo'<div class="da-message error"><span lang="es">Error en la consulta:</span> '.$conexion->errno.'&nbsp;'.$conexion->error.'</div>'; 	
				   }
				}
				$resef->free();
			}else{
			   echo'<div class="da-message warning">
						<span lang="es">No existe ningun registro, probablemente se debe a</span>:
						<ul>
						   <li lang="es">Verificar que la Entindad Financiera este activa, consulte con su administrador</li>
						</ul>
					 </div>'; 
			}
	}else{
		echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'><span lang='es'>Error en la consulta:</span> ".$conexion->errno.": ".$conexion->error."</div>";
	}
}


//FUNCION QUE PERMITE LISTAR LOS FORMULARIOS
function mostrar_lista_datos_poliza($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
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
		   var id_poliza = vec[0];
		   var id_ef_cia = vec[1];
		   var num = vec[2];
		  
		   jConfirm("¿Esta seguro de eliminar la poliza?", "Eliminar registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_poliza='+id_poliza+'&id_ef_cia='+id_ef_cia+'&opcion=eliminar_poliza';
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
										 $('#delete_row-'+num).fadeOut('slow');
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
		 setTimeout( "$(location).attr('href', 'index.php?l=des_poliza&var=<?php echo $var;?>&listarpolizas=v&id_ef_cia=<?=$_GET['id_ef_cia'];?>&entidad=<?=$_GET['entidad'];?>&compania=<?=$_GET['compania'];?>&id_ef=<?=base64_encode($_GET['id_ef']);?>&producto_nombre=<?=$_GET['producto_nombre'];?>&producto_code=<?=$_GET['producto_code'];?>');",5000 );
	<?php }?>
	 
  });
</script>
<?php
$id_ef_cia=base64_decode($_GET['id_ef_cia']);
$entidad=base64_decode($_GET['entidad']);
$id_ef=base64_decode($_GET['id_ef']);
$compania=base64_decode($_GET['compania']);

$selectPol="select
			  sp.id_poliza,
			  sp.no_poliza,
			  sp.fecha_ini,
			  sp.fecha_fin,
			  sp.id_ef_cia,
			  sh.producto,
			  sh.producto_nombre
			from
			  s_poliza as sp
			  inner join s_ef_compania as efc on (efc.id_ef_cia=sp.id_ef_cia)
			  inner join s_compania as sc on (sc.id_compania=efc.id_compania)
			  inner join s_sgc_home as sh on (sh.id_ef=efc.id_ef)
			where
			  sp.id_ef_cia='".$id_ef_cia."' and efc.activado=1 and sc.activado=1 and sp.producto=sh.producto;";			  
//echo $selectPol;			  
   if($res = $conexion->query($selectPol,MYSQLI_STORE_RESULT)){		  
		echo'<div class="da-panel collapsible">
				<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
					<ul class="action_user">
						<li style="margin-right:6px;">
						   <a href="?l=des_poliza&var='.$_GET['var'].'&list_producto=v" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
						   <img src="images/retornar.png" width="32" height="32"></a>
						</li>
						<li style="margin-right:6px;">
						   <a href="?l=des_poliza&id_ef_cia='.$_GET['id_ef_cia'].'&id_ef='.$_GET['id_ef'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&producto_nombre='.$_GET['producto_nombre'].'&producto_code='.$_GET['producto_code'].'&crear=v&var='.$_GET['var'].'" class="da-tooltip-s various" title="<span lang=\'es\'>Añadir nueva póliza</span>">
						   <img src="images/add_new.png" width="32" height="32"></a>
						</li>
					</ul>
				</div>
			 </div>';
		echo'
		<div class="da-panel collapsible">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/list.png" alt="" />
					<b>'.$entidad.' - <span lang="es">Listado de Pólizas</span></b>
				</span>
			</div>
			<div class="da-panel-content">
				<table class="da-table">
					<thead>
						<tr>
						  <th style="text-align:center;"><b><span lang="es">No Póliza</span></b></th>
						  <th style="text-align:center;"><b><span lang="es">Fecha Inicial</span></b></th>
						  <th style="text-align:center;"><b><span lang="es">Fecha Final</span></b></th>
						  <th style="text-align:center;"><b><span lang="es">Producto</span></b></th>
						  <th></th>
						</tr>
					</thead>
					<tbody>';
					  $num = $res->num_rows;
					  if($num>0){
							$i=1;
							while($regi = $res->fetch_array(MYSQLI_ASSOC)){
								echo'<tr id="delete_row-'.$i.'">
										<td>'.$regi['no_poliza'].'</td>
										<td>'.$regi['fecha_ini'].'</td>
										<td>'.$regi['fecha_fin'].'</td>
										<td>'.$regi['producto_nombre'].'</td>
										<td class="da-icon-column">
										   <ul class="action_user">
											  <li style="margin-right:5px;"><a href="?l=des_poliza&idpoliza='.base64_encode($regi['id_poliza']).'&editar=v&var='.$_GET['var'].'&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&producto_nombre='.base64_encode($regi['producto_nombre']).'&producto_code='.base64_encode($regi['producto']).'" class="edit da-tooltip-s" title="<span lang=\'es\'>Editar</span>"></a></li>
											  <li><a href="#" id="'.$regi['id_poliza'].'|'.$id_ef_cia.'|'.$i.'" class="eliminar da-tooltip-s" title="<span lang=\'es\'>Eliminar</span>"></a></li>';
						
									  echo'</ul>	
										</td>
									</tr>';
									$i++;
							}
							$res->free();			
					  }else{
						 echo'<tr><td colspan="5">
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
	   echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'><span lang='es'><span lang='es'>Error en la consulta</span>:</span> ".$conexion->errno.": ".$conexion->error."</div>";
   }
}

//FUNCION QUE PERMITE VISUALIZAR EL FORMULARIO NUEVO USUARIO
function agregar_nueva_poliza($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
	
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['btnGuardar'])) {
		
			$id_ef_cia = base64_decode($_GET['id_ef_cia']);	
		      
			//SEGURIDAD
			$num_poliza = $conexion->real_escape_string($_POST['txtPoliza']);
			$fecha_ini = $conexion->real_escape_string($_POST['txtFechaini']);
			$fecha_fin = $conexion->real_escape_string($_POST['txtFechafin']);
			$producto = $conexion->real_escape_string($_POST['txtProducto']);
			//GENERAMOS EL ID CODIFICADO UNICO
			$id_new_poliza = generar_id_codificado('@S#1$2013');					
			//METEMOS LOS DATOS A LA BASE DE DATOS
			$insert ="INSERT INTO s_poliza(id_poliza, no_poliza, fecha_ini, fecha_fin, producto, id_ef_cia) "
				    ."VALUES('".$id_new_poliza."', '".$num_poliza."', '".$fecha_ini."', '".$fecha_fin."', '".$producto."', '".$id_ef_cia."')";
			
			
			//VERIFICAMOS SI HUBO ERROR EN EL INGRESO DEL REGISTRO
			if($conexion->query($insert)===TRUE){
								
				$mensaje="Se registro correctamente los datos del formulario";
			    header('Location: index.php?l=des_poliza&var='.$_GET['var'].'&listarpolizas=v&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&id_ef='.base64_encode($_GET['id_ef']).'&producto_nombre='.$_GET['producto_nombre'].'&producto_code='.$_GET['producto_code'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
			} else {
				$mensaje="Hubo un error al ingresar los datos, consulte con su administrador ".$conexion->errno.": ".$conexion->error;
			    header('Location: index.php?l=des_poliza&var='.$_GET['var'].'&listarpolizas=v&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&id_ef='.base64_encode($_GET['id_ef']).'&producto_nombre='.$_GET['producto_nombre'].'&producto_code='.$_GET['producto_code'].'&op=2&msg='.base64_encode($mensaje));
				exit;
			}
		
	}else {
		//MUESTRO EL FORM PARA CREAR UNA CATEGORIA
		mostrar_crear_poliza($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
	}
}

//VISUALIZAMOS EL FORMULARIO CREA USUARIO
function mostrar_crear_poliza($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>
<script type="text/javascript" src="jui/js/source/jquery.ui.core.js"></script>
<script type="text/javascript" src="jui/js/source/jquery.ui.widget.js"></script>
<script type="text/javascript" src="jui/js/source/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="jui/js/i18n/jquery.ui.datepicker-es.js"></script>
<script type="text/javascript">
  $(function(){
	  $("#txtFechaini").datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'yy-mm-dd',
			yearRange: "c-100:c+100"
	  });
	 $("#txtFechafin").datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'yy-mm-dd',
			yearRange: "c-100:c+100"
	 }); 	
	
	 $("#txtFechaini").datepicker($.datepicker.regional[ "es" ]);
	 $("#txtFechafin").datepicker($.datepicker.regional[ "es" ]);
	 
	 //VALIDAMOS LOS CAMPOS DEL FORMULARIO
	 $('#frmDatosPoliza').submit(function(e){
		  var num_poliza = $('#txtPoliza').prop('value');
		  var fecha_ini = $('#txtFechaini').prop('value');
		  var fecha_fin = $('#txtFechafin').prop('value');
		  //var selectProducto = $("#txtProducto option:selected").prop('value');
		  var sum=0;
		  $(this).find('.required').each(function(){
			   if(num_poliza!=''){
				  if(num_poliza.match(/^[0-9A-Z\s\-]+$/)){
					  $('#errorpoliza').hide('slow');
				  }else{
					 sum++;  
					 $('#errorpoliza').show('slow');  
					 $('#errorpoliza').html('ingrese solo alfanumerico'); 
				  }
			   }else{
				  sum++;
				  $('#errorpoliza').show('slow');
				  $('#errorpoliza').html('ingrese el numero de poliza');
			   }
			   /*if(selectProducto!=''){
				    $('#errorproducto').hide('slow');
			   }else{
				   sum++; 
				   $('#errorproducto').show('slow');
				   $('#errorproducto').html('seleccione producto');  
			   }*/
			   if(fecha_ini!=''){
				   $('#errorfechaini').hide('slow');
			   }else{
				   sum++;
				   $('#errorfechaini').show('slow');
				   $('#errorfechaini').html('ingrese la fecha');
			   }
			   if(fecha_fin!=''){
				   $('#errorfechafin').hide('slow');
			   }else{
				   sum++;
				   $('#errorfechafin').show('slow');
				   $('#errorfechafin').html('ingrese la fecha');
			   }
		  });
		  if(sum==0){
			  
		  }else{
			 e.preventDefault();  
		  }
		   
	 });
	 //VERIFICAMOS SI EL NUMERO DE POLIZA EXISTE
	 $('#txtPoliza').blur(function(e){
		  var num_poliza = $(this).val();
		  var producto = $('#txtProducto').prop('value');
		  var id_ef_cia = $('#id_ef_cia').prop('value');
		  //VERIFICAMOS SI LA CASILLA ESTA VACIA
		  //e.preventDefault();
		  if (num_poliza == "") {
		     $('#errorpoliza').show('slow');
			 $('#errorpoliza').html('ingrese numero de poliza');
			 $("#btnGuardar").attr("disabled", true);
			 $('#errorpoliza').focus();
		  }else if(num_poliza.match(/^[0-9A-Z\s\-]+$/)){//VERIFICAMOS SI EL VALOR ES NUMERO ENTERO
			  $('#errorpoliza').hide('slow');
			  var dataString = 'num_poliza='+num_poliza+'&id_ef_cia='+id_ef_cia+'&producto='+producto+'&opcion=buscar_numpoliza_add';
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
							   $('#errorpoliza').hide('slow');
							   $("#btnGuardar").removeAttr("disabled");
							}else if(datareturn==2){
							  $('#errorpoliza').show('slow');
							  $('#errorpoliza').html('el numero de poliza ya existe ingrese otra');
							  $("#btnGuardar").attr("disabled", true); 
							  $('#errorpoliza').focus();
							  e.stopPropagation();
							} 
					 }
			   });			  
		  }else{
			 $('#errorpoliza').show('slow');
			 $('#errorpoliza').html('ingrese solo alfanumericos');
			 $("#btnGuardar").attr("disabled", true); 
			 $('#errorpoliza').focus();
			 e.stopPropagation();
		  }
     });
	 
	 $('#txtProducto').change(function(){
		 var producto=$(this).prop('value');
		 if(producto!=''){
			 $('#txtPoliza').removeAttr("disabled");
		 }else{
			 $('#txtPoliza').attr("disabled", true);
		 }
	 });
	 
	 
	  $('#btnCancelar').click(function(e){
		  var variable=$('#var').prop('value');
		  var idcompania=$('#idcompania').prop('value');
		  var id_ef=$('#id_ef').prop('value');
		  $(location).attr('href', 'index.php?l=des_poliza&var='+variable+'&listarpolizas=v&idcompania='+idcompania+'&id_ef='+id_ef); 
	  });
	  
	  $('#txtPoliza').keyup(function() {
          $(this).val($(this).val().toUpperCase());
      });  
  });

</script>
<?php  
  //VARIABLES DE INICIO
  $id_ef_cia = base64_decode($_GET['id_ef_cia']);
  $id_ef = base64_decode($_GET['id_ef']);
  $entidad = base64_decode($_GET['entidad']);
  $compania = base64_decode($_GET['compania']); 
    
  if(isset($_POST['txtPoliza'])) $txtPoliza = $_POST['txtPoliza']; else $txtPoliza = '';
  if(isset($_POST['txtFechaini'])) $txtFechaini = $_POST['txtFechaini']; else $txtFechaini = '';
  if(isset($_POST['txtFechaFin'])) $txtFechaFin = $_POST['txtFechaFin']; else $txtFechaFin = '';
  if(isset($_POST['txtProducto'])) $txtProducto = $_POST['txtProducto']; else $txtProducto = '';
  echo'<div class="da-panel collapsible">
		<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			<ul class="action_user">
				<li style="margin-right:6px;">
				   <a href="?l=des_poliza&var='.$_GET['var'].'&listarpolizas=v&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&id_ef='.$_GET['id_ef'].'&producto_nombre='.$_GET['producto_nombre'].'&producto_code='.$_GET['producto_code'].'" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
				   <img src="images/retornar.png" width="32" height="32"></a>
				</li>
			</ul>
		</div>
	 </div>'; 		
  echo'<div class="da-panel" style="width:600px;">
			<div class="da-panel-header">
				<span class="da-panel-title">
					<img src="images/icons/black/16/pencil.png" alt="" />
					<b>'.$entidad.' - '.$compania.'</b>
					<div style="margin-left:20px;" lang="es">Agregar Nueva Póliza</div>
				</span>
			</div>
			<div class="da-panel-content">
				<form class="da-form" name="frmDatosPoliza" action="" method="post" id="frmDatosPoliza">
					<div class="da-form-row" id="content-entidadf">
					  <label style="text-align:right;"><b><span lang="es">Producto</span></b></label>
					  <div class="da-form-item small">
						'.base64_decode($_GET['producto_nombre']).'
						<input type="hidden" name="txtProducto" id="txtProducto" value="'.base64_decode($_GET['producto_code']).'"/>
					  </div>
					</div>
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">No Póliza</span></b></label>
						<div class="da-form-item large">
							<input class="textbox required" type="text" name="txtPoliza" id="txtPoliza" style="width: 200px;" value="'.$txtPoliza.'" autocomoplete="off"/>
							<span class="errorMessage" id="errorpoliza" lang="es"></span>
						</div>
					</div>
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Fecha Inicial</span></b></label>
						<div class="da-form-item large">
							<input class="textbox required" type="text" name="txtFechaini" id="txtFechaini" style="width: 200px;" value="'.$txtFechaini.'" readonly/>
							<span class="errorMessage" id="errorfechaini" lang="es"></span>
						</div>
					</div>
					<div class="da-form-row">
						<label style="text-align:right;"><b><span lang="es">Fecha Final</span></b></label>
						<div class="da-form-item large">
							<input class="textbox required" type="text" name="txtFechafin" id="txtFechafin" style="width: 200px;" value="'.$txtFechaFin.'" readonly/>
							<span class="errorMessage" id="errorfechafin" lang="es"></span>
						</div>
					</div>
												
					<div class="da-button-row">
					   <input type="submit" value="Guardar" class="da-button green" name="btnGuardar" id="btnGuardar" lang="es"/>                       
					   <input type="hidden" id="var" value="'.$_GET['var'].'"/>
				       <input type="hidden" id="id_ef_cia" value="'.$id_ef_cia.'"/>
					</div>
				</form>
			</div>
		</div>';
}

//FUNCION PARA EDITAR
function editar_datos_poliza($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion) {
		

	//$idpoliza = base64_decode($_GET['idpoliza']);
	//$idcompania = base64_decode($_GET['idcompania']);
	//$id_ef = base64_decode($_GET['id_ef']);
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['btnGuardar'])) {
             $idpoliza = base64_decode($_GET['idpoliza']);
			 $id_ef_cia = base64_decode($_GET['id_ef_cia']);
        
            //SEGURIDAD
			$num_poliza = $conexion->real_escape_string($_POST['txtPoliza']);
			$fecha_ini = $conexion->real_escape_string($_POST['txtFechaini']);
			$fecha_fin = $conexion->real_escape_string($_POST['txtFechafin']);
			
			
            //CARGAMOS LOS DATOS A LA BASE DE DATOS
            $update = "UPDATE s_poliza SET no_poliza='".$num_poliza."', fecha_ini='".$fecha_ini."', fecha_fin='".$fecha_fin."' WHERE id_poliza='".$idpoliza."' and id_ef_cia='".$id_ef_cia."';";
            //echo $update;
            

            if($conexion->query($update)===TRUE){
                $mensaje="Se actualizo correctamente los datos del formulario";
			    header('Location: index.php?l=des_poliza&var='.$_GET['var'].'&listarpolizas=v&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&id_ef='.base64_encode($_POST['id_ef']).'&producto_nombre='.$_GET['producto_nombre'].'&producto_code='.$_GET['producto_code'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
            } else{
                $mensaje="Hubo un error al ingresar los datos, consulte con su administrador ".$conexion->errno.": ".$conexion->error;
			    header('Location: index.php?l=des_poliza&var='.$_GET['var'].'&listarpolizas=v&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&id_ef='.base64_encode($_POST['id_ef']).'&producto_nombre='.$_GET['producto_nombre'].'&producto_code='.$_GET['producto_code'].'&op=2&msg='.base64_encode($mensaje));
				exit;
            }
			
	}else {
	  //MUESTRO FORM PARA EDITAR UNA CATEGORIA
	  mostrar_editar_datos_poliza($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
	}
	
}

//VISUALIZAMOS EL FORMULARIO PARA EDITAR EL FORMULARIO
function mostrar_editar_datos_poliza($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>
<script type="text/javascript" src="jui/js/source/jquery.ui.core.js"></script>
<script type="text/javascript" src="jui/js/source/jquery.ui.widget.js"></script>
<script type="text/javascript" src="jui/js/source/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="jui/js/i18n/jquery.ui.datepicker-es.js"></script>
<script type="text/javascript">
  $(function(){
	  $("#txtFechaini").datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'yy-mm-dd',
			yearRange: "c-100:c+100"
	  });
	 $("#txtFechafin").datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'yy-mm-dd',
			yearRange: "c-100:c+100"
	 }); 	
	
	 $("#txtFechaini").datepicker($.datepicker.regional[ "es" ]);
	 $("#txtFechafin").datepicker($.datepicker.regional[ "es" ]);
	 
	 //VALIDAMOS LOS CAMPOS DEL FORMULARIO
	 $('#frmDatosPoliza').submit(function(e){
		  var num_poliza = $('#txtPoliza').prop('value');
		  var fecha_ini = $('#txtFechaini').prop('value');
		  var fecha_fin = $('#txtFechafin').prop('value');
		  //var selectProducto = $("#txtProducto option:selected").prop('value');
		  var sum=0;
		  $(this).find('.required').each(function(){
			   if(num_poliza!=''){
				  if(num_poliza.match(/^[0-9A-Z\s\-]+$/)){
					  $('#errorpoliza').hide('slow');
				  }else{
					 sum++;  
					 $('#errorpoliza').show('slow');  
					 $('#errorpoliza').html('ingrese solo alfanumerico'); 
				  }
			   }else{
				  sum++;
				  $('#errorpoliza').show('slow');
				  $('#errorpoliza').html('ingrese el numero de poliza');
			   }
			   /*if(selectProducto!=''){
				    $('#errorproducto').hide('slow');
			   }else{
				   sum++; 
				   $('#errorproducto').show('slow');
				   $('#errorproducto').html('seleccione producto');  
			   }*/
			   if(fecha_ini!=''){
				   $('#errorfechaini').hide('slow');
			   }else{
				   sum++;
				   $('#errorfechaini').show('slow');
				   $('#errorfechaini').html('ingrese la fecha');
			   }
			   if(fecha_fin!=''){
				   $('#errorfechafin').hide('slow');
			   }else{
				   sum++;
				   $('#errorfechafin').show('slow');
				   $('#errorfechafin').html('ingrese la fecha');
			   }
		  });
		  if(sum==0){
			  
		  }else{
			 e.preventDefault();  
		  }
		   
	 });
	 
	  $('#btnCancelar').click(function(e){
		  var variable=$('#var').prop('value');
		  var idcompania=$('#idcompania').prop('value');
		  var id_ef=$('#id_ef').prop('value');
		  $(location).attr('href', 'index.php?l=des_poliza&var='+variable+'&listarpolizas=v&idcompania='+idcompania+'&id_ef='+id_ef); 
	  });
	  
	 //VERIFICAMOS SI EL NUMERO DE POLIZA EXISTE
	 $('#txtPoliza').blur(function(e){
		  var num_poliza = $(this).val();
		  var producto = $('#txtProducto').prop('value');
		  var id_ef_cia = $('#id_ef_cia').prop('value');
		  var id_poliza = $('#id_poliza').prop('value');
		  //VERIFICAMOS SI LA CASILLA ESTA VACIA
		  //e.preventDefault();
		  if (num_poliza == "") {
		     $('#errorpoliza').show('slow');
			 $('#errorpoliza').html('ingrese numero de poliza');
			 $("#btnGuardar").attr("disabled", true);
			 $('#errorpoliza').focus();
		  }else if(num_poliza.match(/^[0-9A-Z\s\-]+$/)){//VERIFICAMOS SI EL VALOR ES NUMERO ENTERO
			  $('#errorpoliza').hide('slow');
			  var dataString = 'num_poliza='+num_poliza+'&id_ef_cia='+id_ef_cia+'&producto='+producto+'&id_poliza='+id_poliza+'&opcion=buscar_numpoliza_add';
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
							   $('#errorpoliza').hide('slow');
							   $("#btnGuardar").removeAttr("disabled");
							}else if(datareturn==2){
							  $('#errorpoliza').show('slow');
							  $('#errorpoliza').html('el numero de poliza ya existe ingrese otra');
							  $("#btnGuardar").attr("disabled", true); 
							  $('#errorpoliza').focus();
							  e.stopPropagation();
							} 
					 }
			   });			  
		  }else{
			 $('#errorpoliza').show('slow');
			 $('#errorpoliza').html('ingrese solo alfanumericos');
			 $("#btnGuardar").attr("disabled", true); 
			 $('#errorpoliza').focus();
			 e.stopPropagation();
		  }
     });
	  
	  $('#txtProducto').change(function(){
		 var producto=$(this).prop('value');
		 if(producto!=''){
			  $('#txtPoliza').removeAttr("disabled");
			  /*var num_poliza = $('#txtPoliza').prop('value');
			  var producto = $('#txtProducto option:selected').prop('value');
			  var id_ef_cia = $('#id_ef_cia').prop('value');
			  //VERIFICAMOS SI LA CASILLA ESTA VACIA
			  //e.preventDefault();
			  if (num_poliza == "") {
				 $('#errorpoliza').show('slow');
				 $('#errorpoliza').html('ingrese numero de poliza');
				 $("#btnGuardar").attr("disabled", true);
				 $('#errorpoliza').focus();
			  }else if(num_poliza.match(/^[0-9A-Z\s\-]+$/)){//VERIFICAMOS SI EL VALOR ES NUMERO ENTERO
				  $('#errorpoliza').hide('slow');
				  var dataString = 'num_poliza='+num_poliza+'&id_ef_cia='+id_ef_cia+'&producto='+producto+'&opcion=buscar_numpoliza_add';
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
								   $('#errorpoliza').hide('slow');
								   $("#btnGuardar").removeAttr("disabled");
								}else if(datareturn==2){
								  $('#errorpoliza').show('slow');
								  $('#errorpoliza').html('el numero de poliza ya existe ingrese otra');
								  $("#btnGuardar").attr("disabled", true); 
								  $('#errorpoliza').focus();
								  e.stopPropagation();
								} 
						 }
				   });			  
			  }else{
				 $('#errorpoliza').show('slow');
				 $('#errorpoliza').html('ingrese solo alfanumericos');
				 $("#btnGuardar").attr("disabled", true); 
				 $('#errorpoliza').focus();
				 e.stopPropagation();
			  }*/
		 }else{
			 $('#txtPoliza').attr("disabled", true);
		 }
	 });
	  
	  $('#txtPoliza').keyup(function() {
          $(this).val($(this).val().toUpperCase());
      });  
  });
</script>
<?php    
	$idpoliza = base64_decode($_GET['idpoliza']);
	$id_ef_cia = base64_decode($_GET['id_ef_cia']);
	$entidad = base64_decode($_GET['entidad']);
    $compania = base64_decode($_GET['compania']);
	
	//SACAMOS LOS DATOS DE LA BASE DE DATOS
	$select = "select
				  sp.id_poliza,
				  sp.no_poliza,
				  sp.fecha_ini,
				  sp.fecha_fin,
				  sp.producto,
				  sp.id_ef_cia,
				  efc.id_ef
				from
				  s_poliza as sp
				  inner join s_ef_compania as efc on (efc.id_ef_cia=sp.id_ef_cia)
				  inner join s_compania as sc on (sc.id_compania=efc.id_compania)
				where
				  sp.id_ef_cia='".$id_ef_cia."' and efc.activado=1 and sc.activado=1 and sp.id_poliza='".$idpoliza."';";
	if($rs = $conexion->query($select, MYSQLI_STORE_RESULT)){
			$num = $rs->num_rows;
			
			//SI EXISTE EL USUARIO DADO EN LA BASE DE DATOS, LO EDITAMOS
			if($num>0) {
				$fila = $rs->fetch_array(MYSQLI_ASSOC);
				$rs->free();
				echo'<div class="da-panel collapsible">
				<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
					<ul class="action_user">
						<li style="margin-right:6px;">
						   <a href="?l=des_poliza&var='.$_GET['var'].'&listarpolizas=v&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&id_ef='.base64_encode($fila['id_ef']).'&producto_nombre='.$_GET['producto_nombre'].'&producto_code='.$_GET['producto_code'].'" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
						   <img src="images/retornar.png" width="32" height="32"></a>
						</li>
					</ul>
				</div>
			 </div>'; 
				
		
				if(isset($_POST['txtPoliza'])) $txtPoliza = $_POST['txtPoliza']; else $txtPoliza = $fila['no_poliza'];
				if(isset($_POST['txtFechaini'])) $txtFechaini = $_POST['txtFechaini']; else $txtFechaini = $fila['fecha_ini'];
				if(isset($_POST['txtFechaFin'])) $txtFechaFin = $_POST['txtFechaFin']; else $txtFechaFin = $fila['fecha_fin'];
				if(isset($_POST['txtProducto'])) $txtProducto = $_POST['txtProducto']; else $txtProducto = $fila['producto'];
						
				  echo'<div class="da-panel" style="width:600px;">
							<div class="da-panel-header">
								<span class="da-panel-title">
									<img src="images/icons/black/16/pencil.png" alt="" />
									<b>'.$entidad.' - '.$compania.'</b> - <span lang="es">Editar Póliza</span>
								</span>
							</div>
							<div class="da-panel-content">
								<form class="da-form" name="frmDatosPoliza" action="" method="post" id="frmDatosPoliza">
									<div class="da-form-row" id="content-entidadf">
									  <label style="text-align:right;"><b><span lang="es">Producto</span></b></label>
									  <div class="da-form-item small">
										'.base64_decode($_GET['producto_nombre']).'
										<input type="hidden" id="txtProducto" name="txtProducto" value="'.base64_decode($_GET['producto_code']).'"/>
									  </div>
									</div>
									<div class="da-form-row">
										<label style="text-align:right;"><b><span lang="es">Nro Poliza</span></b></label>
										<div class="da-form-item large">
											<input class="textbox required" type="text" name="txtPoliza" id="txtPoliza" style="width: 200px;" value="'.$txtPoliza.'" autocomoplete="off"/>
											<span class="errorMessage" id="errorpoliza"></span>
										</div>
									</div>
									<div class="da-form-row">
										<label style="text-align:right;"><b><span lang="es">Fecha Inicial</span></b></label>
										<div class="da-form-item large">
											<input class="textbox required" type="text" name="txtFechaini" id="txtFechaini" style="width: 200px;" value="'.$txtFechaini.'" readonly/>
											<span class="errorMessage" id="errorfechaini"></span>
										</div>
									</div>
									<div class="da-form-row">
										<label style="text-align:right;"><b><span lang="es">Fecha Final</span></b></label>
										<div class="da-form-item large">
											<input class="textbox required" type="text" name="txtFechafin" id="txtFechafin" style="width: 200px;" value="'.$txtFechaFin.'" readonly/>
											<span class="errorMessage" id="errorfechafin"></span>
										</div>
									</div>
																
									<div class="da-button-row">
										
										<input type="submit" value="Guardar" class="da-button green" name="btnGuardar" id="btnGuardar" lang="es"/>
										<input type="hidden" id="var" value="'.$_GET['var'].'"/>
										<input type="hidden" name="id_ef" value="'.$fila['id_ef'].'"/>
										<input type="hidden" id="id_ef_cia" value="'.$id_ef_cia.'"/>
										<input type="hidden" id="id_poliza" value="'.$idpoliza.'"/>
									</div>
								</form>
							</div>
						</div>';
			
			} else {
				//SI NO EXISTE EL USUARIO DADO EN LA BASE DE DATOS, VOLVEMOS A LA LISTA DE USUARIOS
				header('Location: index.php?l=des_poliza&var='.$_GET['var']);
			}
	}else{
		echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error."</div>";
	}
}

//FUNCION QUE PERMITE DAR BAJA AL USUARIO
function eliminar_poliza($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
   $idcompania = base64_decode($_GET['idcompania']);
   $idpoliza = base64_decode($_GET['idpoliza']);	
	if(isset($_POST['btnEliminar'])) {
		
		$update ="delete from s_poliza where id_poliza = ".$idpoliza." and id_compania=".$idcompania." LIMIT 1";
		

		if($conexion->query($update)===TRUE){
			//SE METIO A TBLHOMENOTICIAS, VAMOS A VER LA NOTICIA NUEVA
			$mensaje='se elimino el numero de poliza correctamente';
			header('Location: index.php?l=des_poliza&var='.$_GET['var'].'&op=1&msg='.$mensaje);
		} else{
			$mensaje="Hubo un error al ingresar los datos, consulte con su administrador ".$conexion->errno. ": ". $conexion->error;
			header('Location: index.php?l=des_poliza&var='.$_GET['var'].'&op=2&msg='.$mensaje);
		} 
	}else {
		//MOSTRAMOS EL FORMULARIO PARA DAR BAJA COMPANIA
		mostrar_eliminar_poliza($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
	}
	
}

//FUNCION PARA MOSTRAR EL FORMULARIO
function mostrar_eliminar_poliza($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
?>    
  <script type="text/javascript">
     $(function(){
	    $('#btnCancelar').click(function(){
		    var variable=$('#var').prop('value');
			var idcompania=$('#idcompania').prop('value');
			$(location).attr('href', 'index.php?l=des_poliza&var='+variable+'&listarpolizas=v&idcompania='+idcompania);	
	    });	 
     });
  </script>	
<?php    
	$idcompania = base64_decode($_GET['idcompania']);
	$idpoliza = base64_decode($_GET['idpoliza']);
	$selectPl="select no_poliza from s_poliza where id_poliza = ".$idpoliza." and id_compania = ".$idcompania.";";
	$resip = $conexion->query($selectPl,MYSQLI_STORE_RESULT);
	$regipl = $resip->fetch_array(MYSQLI_ASSOC);
	echo'<div style="text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;">';
	echo '<form name="frmDarbaja" action="" method="post" class="da-form">';
	echo '<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" cols="2">';
	echo '<tr><td align="center" width="100%" style="height:60px;">';
	echo 'Al eliminar el numero de poliza <b>'.$regipl['no_poliza'].'</b>, '
		.'se borrara de la base de datos, est&aacute; seguro de eliminar el numero de poliza de forma permanente?';
	echo '</td></tr>
	      <tr> 
	      <td align="center"><div class="da-button-row">
	        <input class="da-button green" type="submit" name="btnEliminar" value="Eliminar"/>
		    <input class="da-button gray left" type="button" id="btnCancelar" value="Cancelar"/>
			<input type="hidden" id="var" value="'.$_GET['var'].'"/>
			<input type="hidden" id="idcompania" value="'.$_GET['idcompania'].'"/>';
	echo '</div></td></tr></table></form>';
	echo'</div>';
}


?>