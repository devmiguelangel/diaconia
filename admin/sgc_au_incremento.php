<?php
include('config.class.php');
include('sgc_funciones.php');
include('sgc_funciones_entorno.php');
include('main_menu.php');
$base_datos = new DB_bisa();
$conexion = $base_datos->connectDB();
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
			mostrar_login_form(2);
		}
	} else {
		//SI NO HA HECHO CLICK EN EL FORM, MOSTRAMOS EL FORMULARIO DE LOGIN
		session_unset();
		session_destroy();
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
								
							    }elseif(isset($_GET['listarincremento'])){
									//VISUALIZAMOS LAS TASA PARA SER EDITADAS
									listar_incremento_tasas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
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
if($tipo_sesion=='root'){
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
							sh.id_ef = ef.id_ef and sh.producto='AU');";
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
							sh.id_ef = ef.id_ef and sh.producto='AU')
						  and ef.id_ef = '".$id_ef_sesion."';";
}
$resef=mysql_query($selectEf,$conexion);
$num_regi_ef=mysql_num_rows($resef);
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
	 
	 while($regief=mysql_fetch_array($resef)){		 
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
				  sef.id_ef='".$regief['id_ef']."' and sef.activado=1 and sc.activado=1;";
		$res=mysql_query($select,$conexion);		  
		echo'
			<div class="da-panel collapsible" style="width:700px;">
				<div class="da-panel-header">
					<span class="da-panel-title">
						<img src="images/icons/black/16/list.png" alt="" />
						<b>Entidad Financiera: '.$regief['nombre'].'</b> - Administrar tasas
					</span>
				</div>
				<div class="da-panel-content">
					<table class="da-table">
						<thead>
							<tr>
								<th style="text-align:center;"><b>Compañia de Seguro</b></th>
								<th style="text-align:center;"><b>Logo</b></th>
								<th></th>
							</tr>
						</thead>
						<tbody>';
						  $num=mysql_num_rows($res);
						  if($num>0){
							    $c=1;
								while($regi=mysql_fetch_array($res)){
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
												   echo'<li style="margin-right:5px;"><a href="?l=au_tasas&id_ef_cia='.base64_encode($regi['id_ef_cia']).'&entidad='.base64_encode($regief['nombre']).'&compania='.base64_encode($regi['compania']).'&listartasas=v&var='.$_GET['var'].'" class="add_mod da-tooltip-s" title="Editar Tasas"></a></li>';
												   echo'<li><a href="?l=au_incremento&id_ef_cia='.base64_encode($regi['id_ef_cia']).'&entidad='.base64_encode($regief['nombre']).'&compania='.base64_encode($regi['compania']).'&listarincremento=v&var='.$_GET['var'].'" class="ad_incre da-tooltip-s" title="Administrar Incremento"></a></li>';
											   
											 												 
										  echo'</ul>	
											</td>
										</tr>';
										$c++;
								}			
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
	 }
 }else{
	echo'<div class="da-message info">
			 No existe registros alguno o la entidad Financiera no esta activada
		</div>'; 
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
		/*
		$j=1;
		$indice=0;
		$vec[]=array();
		$sw=0;
		while($j<=$_POST['total']){
		    $valor=$_POST["txtTasa".$j]; 
			$num=validar_numero($valor);
			if($num==1) {
			    $vec[$indice]=$j;
				$sw=1;
				$indice++; 
			}elseif($num==2) {
			    //sin errores, no guardamos nada en el vector
			   //y la variable sw es 1 
			}elseif($num==3){
			    $vec[$indice]=$j;
				$sw=1; 
				$indice++;
			}
			$j++;
		}
		if($sw==0){
		
		}elseif($sw==1){
		    $errArr['errortasa'] = "Debe ingresar la(s) tasa(s) en la(s) casilla(s): ";
			foreach($vec as $indi=>$dato){
				 $errArr['errortasa'] .= $dato.',';
				 
			}
						
		    $errFlag = true;
		}
		*/

		//VEMOS SI TODO SE VALIDO BIEN
		if($errFlag) {
			//HUBIERON ERRORES, MOSTRAMOS EL FORM CON LOS ERRORES
			mostrar_editar_tasas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
		} else {
			$j=1;								 
			while($j<=$_POST['cant_tasas']){
				$tasa=$_POST["txtTasa".$j];
				$id_tasa=$_POST["id_tasa".$j];
				$id_ef_cia=$_POST["id_ef_cia".$j];
				
				$update = "UPDATE s_tasa_au SET tasa=".$tasa." WHERE id_tasa=".$id_tasa." and id_ef_cia='".$id_ef_cia."';"; 
				$rsupdate = mysql_query($update, $conexion);
				$j++;
			}		
			
			if(mysql_errno($conexion)==0){
			    $mensaje="Se actualizo correctamente los datos del formulario";
			    header('Location: index.php?l=au_tasas&listartasas=v&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
			} else{
			    $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".mysql_errno($conexion) . ": " .mysql_error($conexion);
			    header('Location: index.php?l=au_tasas&listartasas=v&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
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
		   jConfirm("¿Esta seguro de eliminar la tasa?", "Eliminar registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_tasa='+id_tasa+'&id_ef_cia='+id_ef_cia+'&opcion=elimina_tasa_auto';
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
	  
	  $('#frmTasas').submit(function(e){
		  var cant_tasas=$('#cant_tasas').prop('value');
		  var sum=0; var i=1;
		  $(this).find('.required').each(function() {
              while(i<=cant_tasas){
				  var tasa=$('#'+i+'txtTasa').prop('value');
				  
				  if(tasa!=''){
					  if(tasa.match(/^[0-9\.]+$/)){
						 $('#errortasa'+i).hide('slow');  
					  }else{
					     sum++;
						 $('#errortasa'+i).show('slow');
					     $('#errortasa'+i).html('ingrese solo numeros enteros o decimales');  
					  }
				  }else{
					 sum++;
					 $('#errortasa'+i).show('slow');
					 $('#errortasa'+i).html('ingrese la tasa');  
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
		 setTimeout( "$(location).attr('href', 'index.php?l=au_tasas&listartasas=v&id_ef_cia=<?php echo $id_ef_cia;?>&entidad=<?php echo $entidad;?>&compania=<?php echo $compania;?>&var=<?php echo $var;?>');",5000 );
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
				  tasa,
				  anio
				from
				  s_tasa_au
				where
				  id_ef_cia='".$id_ef_cia."';";		  
	$resu=mysql_query($selectTs,$conexion);			  		  
echo'<div class="da-panel collapsible">
		<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			<ul class="action_user">
				<li style="margin-right:6px;">
					 <a href="?l=au_tasas&var='.$_GET['var'].'&list_compania=v" class="da-tooltip-s" title="Volver">
					 <img src="images/retornar.png" width="32" height="32"></a>
				</li>
				<li style="margin-right:6px;">
				   <a href="?l=au_tasas&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&agregartasa=v&var='.$_GET['var'].'" class="da-tooltip-s" title="Añadir nuevas tasas">
				   <img src="images/add_new.png" width="32" height="32"></a>
				</li>
			</ul>
		</div>
	 </div>';
echo'
<div class="da-panel collapsible" style="width:500px;">
	<div class="da-panel-header">
		<span class="da-panel-title">
			<img src="images/icons/black/16/list.png" alt="" />
		    <b>'.$entidad.' - '.$compania.'</b> - Editar Tasas
		</span>
	</div>
	<div class="da-panel-content">';
	 $num=mysql_num_rows($resu);
	 if($num>0){
	  echo'<form class="da-form" name="frmTasas" id="frmTasas" action="" method="post">
	     		<div class="da-form-row" style="padding:0px;">
				  <div class="da-form-item large" style="margin:0px;">
					<table class="da-table">
						<thead>
							<tr>
							  <th style="width:25px;"><b>N&deg;</b></th>
							  <th><b>Tasa</b></th>
							  <th style="text-align:center"><b>Año</b></th>
							  <th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>';
								$i=1;
								while($regi=mysql_fetch_array($resu)){
									if(isset($_POST["txtTasa".$i])) $txtTasa = $_POST["txtTasa".$i]; else $txtTasa = $regi['tasa'];
																											
									echo'<tr>
											<td>'.$i.'</td>
											<td><input type="text" name="txtTasa'.$i.'" id="'.$i.'txtTasa" value="'.$txtTasa.'" class="required" style="width:100px;"/><span class="errorMessage" id="errortasa'.$i.'"></span>
											</td>
											
											<td style="text-align:center">'.$regi['anio'].'
											<input type="hidden" name="id_tasa'.$i.'" id="id_tasa'.$i.'" value="'.$regi['id_tasa'].'"/>
											<input type="hidden" name="id_ef_cia'.$i.'" id="id_ef_cia'.$i.'" value="'.$regi['id_ef_cia'].'"/>
											</td>
											<td class="da-icon-column">
											   <ul class="action_user">
											     <li><a href="#" id="'.$regi['id_tasa'].'|'.$regi['id_ef_cia'].'" class="eliminar da-tooltip-s" title="Eliminar"></a></li>
											   </ul>
											</td>
										</tr>';
										$i++;
								}			
						  
						  echo'<tr><td colspan="6">
								  <span class="errorMessage">'.$errArr['errortasa'].'</span>
								  <span class="errorMessage">'.$errArr['erroranio'].'</span>
							   </td></tr>';
				   echo'</tbody>
					</table>
				  </div>	
		        </div>
			    <div class="da-button-row">
				   <input type="submit" value="Guardar" class="da-button green" name="btnPregunta" id="btnPregunta"/>
				   <input type="hidden" name="accionGuardar" value="checkdatos"/>
				   <input type="hidden" name="cant_tasas" value="'.$num.'" id="cant_tasas"/>
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
					
		//VEMOS SI TODO SE VALIDO BIEN
		if($errFlag) {
			//HUBIERON ERRORES, MOSTRAMOS EL FORM CON LOS ERRORES
			mostrar_crear_tasas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion, $errArr);
		} else {
			  $anio=$_POST['num_regi']+1;			
		  
			  $tasa = mysql_real_escape_string($_POST["txtTasa"]);
			  $id_ef_cia = $_POST["id_ef_cia"];
			  
			  $insert = "INSERT INTO s_tasa_au(id_tasa, id_ef_cia, tasa, anio) "
				."VALUES(NULL, '".$id_ef_cia."', ".$tasa.", ".$anio.")"; 
			  $resu = mysql_query($insert, $conexion);
				
			//METEMOS A LA TABLA TBLHOMENOTICIAS
			if(mysql_errno($conexion)==0){
				$mensaje="Se registro correctamente los datos del formulario";
			    header('Location: index.php?l=au_tasas&listartasas=v&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
			} else {
				$mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".mysql_errno($conexion) . ": " .mysql_error($conexion);
			    header('Location: index.php?l=au_tasas&listartasas=v&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
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
		var tasa=$('#txtTasa').prop('value');
		var num_regi=$('#num_regi').prop('value');
		var sum=0; 
		$(this).find('.required').each(function() {
			if(tasa!=''){
				if(tasa.match(/^[0-9\.]+$/)){
				   $('#errortasa').hide('slow');  
				}else{
				   sum++;
				   $('#errortasa').show('slow');
				   $('#errortasa').html('ingrese solo numeros enteros o decimales');  
				}
			}else{
			   sum++;
			   $('#errortasa').show('slow');
			   $('#errortasa').html('ingrese la tasa');  
			}	
		});
		if(sum==0){
			if(num_regi<3){
				$('#errortasa').hide('slow');
		    }else{
				$('#errortasa').show('slow');
				$('#errortasa').html('no puede ingresar mas de tres tasas');
				e.preventDefault();
		    }
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
					 <a href="?l=au_tasas&var='.$_GET['var'].'&listartasas=v&id_ef_cia='.$_GET['id_ef_cia'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'" class="da-tooltip-s" title="Volver">
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
	$regi=mysql_fetch_array(mysql_query($select,$conexion));		  
echo'
<div class="da-panel collapsible" style="width:600px;">
	<div class="da-panel-header">
		<span class="da-panel-title">
			<img src="images/icons/black/16/list.png" alt="" />
		    <b>'.$entidad.' - '.$compania.'</b> - Agregar nueva tasa
		</span>
	</div>
	<div class="da-panel-content">';
	
	  echo'<form class="da-form" name="frmTasas" action="" method="post" id="frmTasas">
	     		<div class="da-form-row" style="padding:0px;">
				   <div class="da-form-item large" style="margin:0px;">
					<table class="da-table">
						<thead>
							<tr>
								<th><b>Tasa</b></th>
							</tr>
						</thead>
						<tbody>';
	
						echo'<tr>
								<td><input type="text" name="txtTasa" id="txtTasa" value="" class="required" style="width:125px;"/>
								<span class="errorMessage" id="errortasa"></span>
								<input type="hidden" name="id_ef_cia" id="id_ef_cia" value="'.$id_ef_cia.'"/>
								</td>
							 </tr>';
															
				   echo'</tbody>
					</table>
				   </div>	
		        </div>
			    <div class="da-button-row">
				   <input type="reset" value="Reset" class="da-button gray left"/>
				   <input type="submit" value="Guardar" class="da-button green" name="btnSaveTasas" id="btnSaveTasas"/>
				   <input type="hidden" name="accionGuardar" value="checkdatos"/>
				   <input type="hidden" name="num_regi" id="num_regi" value="'.$regi[0].'"/>
			    </div>	
	       </form>';
     	   	
echo'</div>
</div>';
}
?>