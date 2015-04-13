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
			header('Location: index.php?l=tr_tasas&var=trd&list_compania=v');
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
								}elseif(isset($_GET['list_compania'])){
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE FORMULARIOS EXISTENTES
									mostrar_lista_companias_x_entidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
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
						  sef.id_ef='".$regief['id_ef']."' and sef.activado=1 and sc.activado=1 and sef.producto='TRD';";
				if($res = $conexion->query($select,MYSQLI_STORE_RESULT)){		  
						echo'
							<div class="da-panel collapsible" style="width:700px;">
								<div class="da-panel-header">
									<span class="da-panel-title">
										<img src="images/icons/black/16/list.png" alt="" />
										<b>'.$regief['nombre'].'</b> - <span lang="es">Administrar tasas</span> 
									</span>
								</div>
								<div class="da-panel-content">
									<table class="da-table">
										<thead>
											<tr>
												<th style="text-align:center;"><b><span lang="es">Compañía de Seguros</span></b></th>
												<th style="text-align:center;"><b>Imagen</b></th>
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
																	  echo'<span lang="es">no existe el archivo físico</span>';   
																   }
															   }else{
																  echo'<span lang="es">no existe el nombre del archivo en la base de datos</span>';   
															   }
													   echo'</td>
															<td class="da-icon-column">
															   <ul class="action_user">';
															   
																   /*echo'<li style="padding-right:5px;"><a href="?l=des_producto&var='.$_GET['var'].'&listarproductos=v&id_ef_cia='.base64_encode($regi['id_ef_cia']).'&id_producto='.base64_encode($regi['id_producto']).'&compania='.base64_encode($regi['compania']).'&entidad_fin='.base64_encode($regief['nombre']).'" class="add_mod da-tooltip-s various" title="Agregar Productos"></a></li>';*/
																   echo'<li style="margin-right:5px;"><a href="?l=tr_tasas&id_ef_cia='.base64_encode($regi['id_ef_cia']).'&entidad='.base64_encode($regief['nombre']).'&compania='.base64_encode($regi['compania']).'&listartasas=v&var='.$_GET['var'].'" class="add_mod da-tooltip-s" title="<span lang=\'es\'>Editar Tasas</span>"></a></li>';
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
														 <span lang="es">No existe ningun registro, probablemente se debe a</span>:
														 <ul>
															<li lang="es">La Compañía de Seguros no esta activada</li>
															<li lang="es">La Compañía asignada a la Entidad Financiera no esta activada</li>
															<li lang="es">El producto no existe en la Compañía asignada a la Entidad Financiera</li>
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


//FUNCION QUE PERMITE LISTAR LAS TASAS PARA EDITAR
function listar_tasas_editar($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion){
  
	//INICIAMOS EL ARRAY CON LOS ERRORES
	$errArr['errortasa'] = '';
	$errArr['erroranio'] = '';
	$errFlag = false;

	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
		
		//VEMOS SI TODO SE VALIDO BIEN
		if($errFlag) {
			//HUBIERON ERRORES, MOSTRAMOS EL FORM CON LOS ERRORES
			mostrar_editar_tasas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
		} else {
			$j=1;								 
			while($j<=$_POST['cant_tasas']){
				$tasa_anio=$_POST["txtTasaAnio".$j];
				$tasarest=$_POST["txtTasaRestante".$j];
				$tasaestan=$_POST["txtTasaEstandar".$j];
				
				$id_tasa=$_POST["id_tasa".$j];
				$id_ef_cia=$_POST["id_ef_cia".$j];
				
				$update = "UPDATE s_tasa_trd SET tasa_anio=".$tasa_anio.", tasa_restante=".$tasarest.", tasa_estandar=".$tasaestan." WHERE id_tasa=".$id_tasa." and id_ef_cia='".$id_ef_cia."';"; 
				if($conexion->query($update)===TRUE){ $response=TRUE;}else{ $response=FALSE;}
							
				$j++;
			}		
			
			if($response){
			    $mensaje="Se actualizo correctamente los datos del formulario";
			    header('Location: index.php?l=tr_tasas&listartasas=v&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
			} else{
			    $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " .$conexion->error;
			    header('Location: index.php?l=tr_tasas&listartasas=v&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
				exit;
			} 
		}//CERRAMOS LLAVE ELSE SI NO HUBIERON ERRORES
        
	} else {
	  //MUESTRO FORM PARA EDITAR UNA CATEGORIA
	 
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
		   var id_ef_cia = vec[1];
		   
		   jConfirm("¿Esta seguro de eliminar las tasas?", "Eliminar registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_tasa='+id_tasa+'&id_ef_cia='+id_ef_cia+'&opcion=elimina_tasa_trd';
						
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
		  $(location).attr('href', 'index.php?l=tr_tasas&var='+variable); 
	  });
	  
	  $('#frmTasas').submit(function(e){
		  var cant_tasas=$('#cant_tasas').prop('value');
		  var sum=0; var i=1;
		  $(this).find('.required').each(function() {
              while(i<=cant_tasas){
				  var tasa_anio=$('#'+i+'txtTasaAnio').prop('value');
				  if(tasa_anio!=''){
					  if(tasa_anio.match(/^[0-9\.]+$/)){
						 $('#errortasa'+i).hide('slow');  
					  }else{
					     sum++;
						 $('#errortasa'+i).show('slow');
					     $('#errortasa'+i).html('ingrese solo numeros enteros o decimales');  
					  }
				  }else{
					 sum++;
					 $('#errortasa'+i).show('slow');
					 $('#errortasa'+i).html('ingrese la tasa año');  
				  }
				  var tasarestan=$('#'+i+'txtTasaRestante').prop('value');
				  if(tasarestan!=''){
					  if(tasarestan.match(/^[0-9\.]+$/)){
						  $('#errortasarest'+i).hide('slow');
					  }else{
						  sum++;
					      $('#errortasarest'+i).show('slow');
					      $('#errortasarest'+i).html('ingrese solo numeros enteros o decimales');
					  }
				  }else{
					 sum++;
					 $('#errortasarest'+i).show('slow');
					 $('#errortasarest'+i).html('ingrese tasa restante'); 
				  }
				  var tasaestand=$('#'+i+'txtTasaEstandar').prop('value');
				  if(tasaestand!=''){
					  if(tasaestand.match(/^[0-9\.]+$/)){
						  $('#errortasaestan'+i).hide('slow');
					  }else{
						  sum++;
						  $('#errortasaestan'+i).show('slow');
					      $('#errortasaestan'+i).html('ingrese solo numeros enteros o decimales');
					  }
				  }else{
					  sum++;
					  $('#errortasaestan'+i).show('slow');
					  $('#errortasaestan'+i).html('ingrese tasa estandar');
				  }
				  i++;
			  }
          });
		  if(sum==0){
			  
		  }else{
		     e.preventDefault();
		  }
	  });
	    
  });
</script>
<script type="text/javascript" src="plugins/ambience/jquery.ambiance.js"></script>
<script type="text/javascript">
  <?php 
    $op = $_GET["op"];
    $msg = $_GET["msg"];
	$var = $_GET["var"];
	$id_ef_cia = $_GET["id_ef_cia"];
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
		 setTimeout( "$(location).attr('href', 'index.php?l=tr_tasas&listartasas=v&id_ef_cia=<?php echo $id_ef_cia;?>&entidad=<?php echo $entidad;?>&compania=<?php echo $compania;?>&var=<?php echo $var;?>');",5000 );
	<?php }?>
	 
  });
</script>	
<?php    
	$id_ef_cia=base64_decode($_GET['id_ef_cia']);
	$entidad=base64_decode($_GET['entidad']);
	$compania=base64_decode($_GET['compania']);
	//SACAMOS LAS TASAS
	$selectTs="select
				  id_tasa,
				  id_ef_cia,
				  tasa_anio,
				  tasa_restante,
				  tasa_estandar 
				from
				  s_tasa_trd
				where
				  id_ef_cia='".$id_ef_cia."';";		  
	if($resu = $conexion->query($selectTs,MYSQLI_STORE_RESULT)){
			$num = $resu->num_rows;		  		  
		echo'<div class="da-panel collapsible">
				<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
					<ul class="action_user">
						<li style="margin-right:6px;">
							 <a href="?l=tr_tasas&var='.$_GET['var'].'&list_compania=v" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
							 <img src="images/retornar.png" width="32" height="32"></a>
						</li>';
						if($num==0){
				   echo'<li style="margin-right:6px;">
							 <a href="?l=tr_tasas&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&agregartasa=v&var='.$_GET['var'].'" class="da-tooltip-s" title="Añadir nuevas tasas">
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
					<b>'.$entidad.' - '.$compania.'</b> - <span lang="es">Editar Tasas</span>
				</span>
			</div>
			<div class="da-panel-content">';
			 if($num>0){
			  echo'<form class="da-form" name="frmTasas" id="frmTasas" action="" method="post">
						<div class="da-form-row" style="padding:0px;">
						  <div class="da-form-item large" style="margin:0px;">
							<table class="da-table">
								<thead>
									<tr>
									  <th><b><span lang="es">Tasa Año</span></b></th>
									  <th><b><span lang="es">Tasa Restante</span></b></th>
									  <th><b><span lang="es">Tasa Estandar</span></b></th>
									  <th>&nbsp;</th>
									</tr>
								</thead>
								<tbody>';
										$i=1;
										while($regi = $resu->fetch_array(MYSQLI_ASSOC)){
											if(isset($_POST["txtTasaAnio".$i])) $txtTasaAnio = $_POST["txtTasaAnio".$i]; else $txtTasaAnio = $regi['tasa_anio'];
											if(isset($_POST["txtTasaRestante".$i])) $txtTasaRestante = $_POST["txtTasaRestante".$i]; else $txtTasaRestante = $regi['tasa_restante'];
											if(isset($_POST["txtTasaEstandar".$i])) $txtTasaEstandar = $_POST["txtTasaEstandar".$i]; else $txtTasaEstandar = $regi['tasa_estandar'];
																													
											echo'<tr>
													<td><input type="text" name="txtTasaAnio'.$i.'" id="'.$i.'txtTasaAnio" value="'.$txtTasaAnio.'" class="required" style="width:100px;"/>
													<span class="errorMessage" id="errortasa'.$i.'"></span>
													</td>
													<td><input type="text" name="txtTasaRestante'.$i.'" id="'.$i.'txtTasaRestante" value="'.$txtTasaRestante.'" class="required" style="width:100px;"/>
													<span class="errorMessage" id="errortasarest'.$i.'"></span>
													</td>
													<td><input type="text" name="txtTasaEstandar'.$i.'" id="'.$i.'txtTasaEstandar" value="'.$txtTasaEstandar.'" class="required" style="width:100px;"/>
													<span class="errorMessage" id="errortasaestan'.$i.'"></span>
													<input type="hidden" name="id_tasa'.$i.'" id="id_tasa'.$i.'" value="'.$regi['id_tasa'].'"/>
													<input type="hidden" name="id_ef_cia'.$i.'" id="id_ef_cia'.$i.'" value="'.$regi['id_ef_cia'].'"/>
													</td>
													<td class="da-icon-column">
													   <ul class="action_user">
														 <li><a href="#" id="'.$regi['id_tasa'].'|'.$regi['id_ef_cia'].'" class="eliminar da-tooltip-s" title="<span lang=\'es\'>Eliminar</span>"></a></li>
													   </ul>
													</td>
												</tr>';
											$i++;	
										}			
									  $resu->free();
								  
						   echo'</tbody>
							</table>
						  </div>	
						</div>
						<div class="da-button-row">
						   <input type="submit" value="Guardar" class="da-button green" name="btnPregunta" id="btnPregunta" lang="es"/>
						   <input type="hidden" name="accionGuardar" value="checkdatos"/>
						   <input type="hidden" name="cant_tasas" value="'.$num.'" id="cant_tasas"/>
						   <input type="hidden" id="var" value="'.$_GET['var'].'"/>
						</div>	
				   </form>';
			 }else{
				 echo'<div class="da-message info" lang="es">
						  No existe ningun dato, ingrese nuevos registros
					  </div>';
			 }		   	
		echo'</div>
		</div>';
	}else{
	   echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error."</div>";
	}
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
					
		//VEMOS SI TODO SE VALIDO BIEN
		if($errFlag) {
			//HUBIERON ERRORES, MOSTRAMOS EL FORM CON LOS ERRORES
			mostrar_crear_tasas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
		} else {
			  		
		  
			  $tasa_anio = $conexion->real_escape_string($_POST["txtTasaAnio"]);
			  $tasarestan = $conexion->real_escape_string($_POST["txtTasaRestan"]);
			  $tasaestand = $conexion->real_escape_string($_POST["txtTasaEstand"]);
			  $id_ef_cia = $_POST["id_ef_cia"];
			  
			  $insert = "INSERT INTO s_tasa_trd(id_tasa, id_ef_cia, tasa_anio, tasa_restante, tasa_estandar) "
				."VALUES(NULL, '".$id_ef_cia."', ".$tasa_anio.", ".$tasarestan.", ".$tasaestand.")"; 
			  
			  				
			//METEMOS A LA TABLA TBLHOMENOTICIAS
			if($conexion->query($insert)===TRUE){
				$mensaje="<span lang='es'>Se registro correctamente los datos del formulario</span>";
			    header('Location: index.php?l=tr_tasas&listartasas=v&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
			} else {
				$mensaje="Hubo un error al ingresar los datos, consulte con su administrador ".$conexion->errno.": " .$conexion->error;
			    header('Location: index.php?l=tr_tasas&listartasas=v&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
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
?>
<script type="text/javascript">
$(document).ready(function() {
    
	$('#frmTasas').submit(function(e){
		var tasa_anio=$('#txtTasaAnio').prop('value');
		var tasarestan=$('#txtTasaRestan').prop('value');
		var tasaestand=$('#txtTasaEstand').prop('value');
		var num_regi=$('#num_regi').prop('value');
		var sum=0; 
		$(this).find('.required').each(function() {
			if(tasa_anio!=''){
				if(tasa_anio.match(/^[0-9\.]+$/)){
				   $('#errortasa').hide('slow');  
				}else{
				   sum++;
				   $('#errortasa').show('slow');
				   $('#errortasa').html('ingrese solo numeros enteros o decimales');  
				}
			}else{
			   sum++;
			   $('#errortasa').show('slow');
			   $('#errortasa').html('campo obligatorio');  
			}
			if(tasarestan!=''){
				if(tasarestan.match(/^[0-9\.]+$/)){
			       $('#errortasarestan').hide('slow');	
				}else{
				   sum++;
				   $('#errortasarestan').show('slow');
				   $('#errortasarestan').html('ingrese solo numeros enteros o decimales');
			    }
		    }else{
				sum++;
				$('#errortasarestan').show('slow');
				$('#errortasarestan').html('campo obligatorio');
		    }
			if(tasaestand!=''){
				if(tasaestand.match(/^[0-9\.]+$/)){
					$('#errortasaestand').hide('slow');
				}else{
					sum++;
				    $('#errortasaestand').show('slow');
				    $('#errortasaestand').html('ingrese solo numeros enteros o decimales');
				}
		    }else{
				sum++;
				$('#errortasaestand').show('slow');
				$('#errortasaestand').html('campo obligatorio');
		    }	
		});
		if(sum==0){
			/*if(num_regi<3){
				$('#errortasa').hide('slow');
		    }else{
				$('#errortasa').show('slow');
				$('#errortasa').html('no puede ingresar mas de tres tasas');
				e.preventDefault();
		    }*/
		}else{
		   e.preventDefault();
		}
	});
	
});	
</script>	
<?php    
	
	$id_ef_cia=base64_decode($_GET['id_ef_cia']);
	$entidad=base64_decode($_GET['entidad']);
	$compania=base64_decode($_GET['compania']);
      		
	  
echo'<div class="da-panel collapsible">
		<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			<ul class="action_user">
				<li style="margin-right:6px;">
					 <a href="?l=tr_tasas&var='.$_GET['var'].'&listartasas=v&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'" class="da-tooltip-s" title="Volver">
					 <img src="images/retornar.png" width="32" height="32"></a>
				</li>
			</ul>
		</div>
	 </div>';
    $select="select
			  count(id_tasa) as num_regi
			from
			  s_tasa_au
			where
			  id_ef_cia='".$id_ef_cia."';";
    $resi = $conexion->query($select,MYSQLI_STORE_RESULT);			  
	$regi = $resi->fetch_array(MYSQLI_ASSOC);
	$resi->free();		  
echo'
<div class="da-panel collapsible" style="width:600px;">
	<div class="da-panel-header">
		<span class="da-panel-title">
			<img src="images/icons/black/16/list.png" alt="" />
		    <b>'.$entidad.' - '.$compania.'</b> - <span lang="es">Agregar nuevas tasas</span>
		</span>
	</div>
	<div class="da-panel-content">';
	
	  echo'<form class="da-form" name="frmTasas" action="" method="post" id="frmTasas">
	     		<div class="da-form-row" style="padding:0px;">
				   <div class="da-form-item large" style="margin:0px;">
					<table class="da-table">
						<thead>
							<tr>
								<th><b><span lang="es">Tasa Año</span></b></th>
								<th><b><span lang="es">Tasa Restante</span></b></th>
								<th><b><span lang="es">Tasa Estandar</span></b></th>
							</tr>
						</thead>
						<tbody>';
	
						echo'<tr>
								<td>
								 <input type="text" name="txtTasaAnio" id="txtTasaAnio" value="" class="required" style="width:125px;"/>
								 <span class="errorMessage" id="errortasa" lang="es"></span>
								</td>
								<td>
								 <input type="text" name="txtTasaRestan" id="txtTasaRestan" value="" class="required" style="width:125px;"/>
								 <span class="errorMessage" id="errortasarestan" lang="es"></span>
								 
								</td>
								<td>
								 <input type="text" name="txtTasaEstand" id="txtTasaEstand" value="" class="required" style="width:125px;"/>
								 <span class="errorMessage" id="errortasaestand" lang="es"></span>
								</td>
							 </tr>';
															
				   echo'</tbody>
					</table>
				   </div>	
		        </div>
			    <div class="da-button-row">
				   
				   <input type="submit" value="Guardar" class="da-button green" name="btnSaveTasas" id="btnSaveTasas" lang="es"/>
				   <input type="hidden" name="accionGuardar" value="checkdatos"/>
				   <input type="hidden" name="id_ef_cia" id="id_ef_cia" value="'.$id_ef_cia.'"/>
				   <input type="hidden" name="num_regi" id="num_regi" value="'.$regi['num_regi'].'"/>
			    </div>	
	       </form>';
     	   	
echo'</div>
</div>';
}
?>