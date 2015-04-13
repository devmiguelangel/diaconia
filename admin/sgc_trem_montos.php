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
		 setTimeout( "$(location).attr('href', 'index.php?l=trem_montos&var=<?php echo $var;?>');",5000 );
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
			header('Location: index.php?l=trem_montos&var=trem');
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
						
								agregar_nuevos_montos($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
								
							} else {
								//VEMOS SI NOS PASAN UN ID DE USUARIO
								if(isset($_GET['idhome'])) {
						
									if(isset($_GET['darbaja'])) {
										
										desactivar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									}elseif(isset($_GET['daralta'])){ 
									
									    activar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								    
									}elseif(isset($_GET['editar'])) {
										//SI NO ME PASAN 'CPASS' NI 'ELIMINAR', MUESTRO EL FORM PARA EDITAR USUARIO
										editar_datos_admin($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
										
									} 
								} else {
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE FORMULARIOS EXISTENTES
									mostrar_lista_datos_admin($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
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
function mostrar_lista_datos_admin($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>
<script type="text/javascript"></script>

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
								sh.id_ef = ef.id_ef and sh.producto='TRM');";
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
								sh.id_ef = ef.id_ef and sh.producto='TRM')
							  and ef.id_ef = '".$id_ef_sesion."';";
	}
	if($resef = $conexion->query($selectEf,MYSQLI_STORE_RESULT)){
			$num_reg = $resef->num_rows;
			/*echo'<div class="da-panel collapsible">
					<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
						<ul class="action_user">
							<li style="margin-right:6px;">
							   <a href="?l=trem_montos&var='.$_GET['var'].'&crear=v" class="da-tooltip-s various" title="Añadir nuevo registro">
							   <img src="images/add_new.png" width="32" height="32"></a>
							</li>
						</ul>
					</div>
				 </div>';*/
			if($num_reg>0){	 
				while($regief = $resef->fetch_array(MYSQLI_ASSOC)){		
					$selectFor="select
								   id_home,
								   producto,
								   limite_cotizacion,
								   max_cotizacion_usd,
								   max_cotizacion_bs,
								   max_emision_usd,
								   max_emision_bs,
								   edad_max,
								   edad_min,
								   id_ef,
								   max_detalle ,
								   (case facturacion
									 when 0 then 'No'
									 when 1 then 'Si'
								   end) as facturacion,
								   (case implante
									 when 0 then 'No'
									 when 1 then 'Si'
								   end) as implante_text,
								   implante,
								   certificado_provisional,
								   (case certificado_provisional
									 when 0 then 'No'
									 when 1	then 'Si'
								   end) as cert_provisional_text,	  	
								   (case modalidad
									 when 0 then 'No'
									 when 1	then 'Si'
								   end) as modalidad_text,	
								   monto_facultativo,
								   (case web_service
									 when 0 then 'No'
									 when 1 then 'Si'
								   end) as webservice_text		  	  
								from
								  s_sgc_home
								where
								  producto='TRM' and id_ef='".$regief['id_ef']."';";
					$res = $conexion->query($selectFor,MYSQLI_STORE_RESULT);		  
					
					echo'
					<div class="da-panel collapsible">
						<div class="da-panel-header">
							<span class="da-panel-title" style="font-size:11.5px;">
								<img src="images/icons/black/16/list.png" alt="" />
								<b>'.$regief['nombre'].'</b> - <span lang="es">Administrar parametros del Producto Ramos Técnicos</span>
							</span>
						</div>
						<div class="da-panel-content">
							<table class="da-table">
								<thead>
									<tr style="font-size:11.5px;">
										<th style="text-align:center;"><b><span lang="es">Caducidad Cotización (días)</span></b></th>
										<th style="text-align:center;"><b><span lang="es">Monto Max Cotización (usd)</span></b></th>
										<th style="text-align:center;"><b><span lang="es">Monto Max Emisión (usd)</span></b></th>
										<th style="text-align:center;"><b><span lang="es">Monto Facultativo</span> (usd)</b></th>
										<th style="text-align:center;"><b><span lang="es">Edad Mínima</span></b></th>
										<th style="text-align:center;"><b><span lang="es">Edad Máxima</span></b></th>
										<th style="text-align:center;"><b><span lang="es">Nro Registros</span></b></th>
										<th style="text-align:center;"><b><span lang="es">Facturación</span></b></th>
										<th style="text-align:center;"><b><span lang="es">Implante</span></b></th>
										<th style="text-align:center;"><b><span lang="es">Certificado Provisional</span></b></th>
										<th style="text-align:center;"><b><span lang="es">Modalidad</span></b></th>';
									      if($tipo_sesion=='ROOT'){
											echo'<th style="text-align:center;"><b>Web Service</b></th>';
										  }	
								   echo'<th></th>
									</tr>
								</thead>
								<tbody>';
								  $num = $res->num_rows;
								  if($num>0){
										while($regi = $res->fetch_array(MYSQLI_ASSOC)){
											echo'<tr style="font-size:11.5px;">
													<td style="text-align:center;">'.$regi['limite_cotizacion'].'</td>
													<td style="text-align:center;">'.$regi['max_cotizacion_usd'].'</td>
													<td style="text-align:center;">'.$regi['max_emision_usd'].'</td>
													<td style="text-align:center;">'.$regi['monto_facultativo'].'</td>
													<td style="text-align:center;">'.$regi['edad_min'].'</td>
													<td style="text-align:center;">'.$regi['edad_max'].'</td>
													<td style="text-align:center;">'.$regi['max_detalle'].'</td>
													<td style="text-align:center;">'.$regi['facturacion'].'</td>
													<td style="text-align:center;">'.$regi['implante_text'].'</td>
													<td style="text-align:center;">'.$regi['cert_provisional_text'].'</td>
													<td style="text-align:center;">'.$regi['modalidad_text'].'</td>';
												      if($tipo_sesion=='ROOT'){
														echo'<td style="text-align:center;">'.$regi['webservice_text'].'</td>';
													  }	
											   echo'<td>
													   <ul class="action_user">
														  <li style="margin-right:5px;"><a href="?l=trem_montos&idhome='.base64_encode($regi['id_home']).'&id_ef='.base64_encode($regief['id_ef']).'&editar=v&var='.$_GET['var'].'" class="edit da-tooltip-s" title="<span lang=\'es\'>Editar</span>"></a></li>';
														   /*echo'<li><a href="#" class="eliminar da-tooltip-s" title="Eliminar" id="'.$regi['id_home'].'|'.$regief['id_ef'].'"></a></li>';
														  if($regi['activado']=='deshabilitado'){
															  echo'<li><a href="?l=compania&idcompania='.base64_encode($regi['id_compania']).'&daralta=v&var='.$_GET['var'].'" class="daralta da-tooltip-s" title="Activar"></a></li>';
														  }else{
															  echo'<li><a href="?l=compania&idcompania='.base64_encode($regi['id_compania']).'&darbaja=v&var='.$_GET['var'].'" class="darbaja da-tooltip-s" title="Desactivar"></a></li>';  
														  }*/
												  echo'</ul>	
													</td>
												</tr>';
										}
										$res->free();			
								  }else{
									 echo'<tr><td colspan="8">
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
			}else{
			  echo'<div class="da-message warning">
					   <span lang="es">No existe ningun registro, probablemente se debe a</span>:
					   <ul>
						  <li lang="es">La Entidad Financiera no tiene asignado el producto Todo Riesgo Equipo Movil</li>
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
function agregar_nuevos_montos($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
	
	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['accionGuardar'])) {
				
			//SEGURIDAD
			$caducidadcotiz = $conexion->real_escape_string($_POST['txtLimitCotiza']);
			$montocotizusd = $conexion->real_escape_string($_POST['txtMaxCotiUsd']);
			$montoemiusd = $conexion->real_escape_string($_POST['txtMaxEmiUsd']);
			$edadmax = $conexion->real_escape_string($_POST['txtEdadMax']);
			$edadmin = $conexion->real_escape_string($_POST['txtEdadMin']);
			$idefin = $conexion->real_escape_string($_POST['idefin']);
			$numregi = $conexion->real_escape_string($_POST['txtNumRegi']);
			$facturacion = $conexion->real_escape_string($_POST['factura']);
			$implante = $conexion->real_escape_string($_POST['implante']);
			//$antiguedad = mysql_real_escape_string($_POST['antiguedad']);
			$monto_facu = $conexion->real_escape_string($_POST['txtMontFacu']);
			
			$id_new_home = generar_id_codificado('@S#1$2013');					
			//METEMOS LOS DATOS A LA BASE DE DATOS
			$insert ="INSERT INTO s_sgc_home(id_home, id_ef, producto, limite_cotizacion, max_cotizacion_usd,  max_emision_usd, edad_max, edad_min, max_detalle, monto_facultativo, facturacion, implante, id_usuario) "
				    ."VALUES('".$id_new_home."', '".$_POST['idefin']."', 'TRM', ".$caducidadcotiz.", ".$montocotizusd.", ".$montoemiusd.", ".$edadmax.", ".$edadmin.", ".$numregi.", ".$monto_facu.", ".$facturacion.", ".$implante.", '".$id_usuario_sesion."')";
			//echo $insert;
			
			
			//VERIFICAMOS SI HUBO ERROR EN EL INGRESO DEL REGISTRO
			if($conexion->query($insert)===TRUE){
								
				$mensaje="Se registro correctamente los datos del formulario";
			    header('Location: index.php?l=trem_montos&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
			} else {
				$mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			    header('Location: index.php?l=trem_montos&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
				exit;
			}
		
	} else {
		//MUESTRO EL FORM PARA CREAR UNA CATEGORIA
		mostrar_crear_montos($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
	}
}

//VISUALIZAMOS EL FORMULARIO CREA USUARIO
function mostrar_crear_montos($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
  ?>
<script type="text/javascript">
  $(function(){
	  $('#frmDatosCrea').submit(function(e){
		  //alert('Guardar');
		  var caducidad_cotiza = $('#txtLimitCotiza').prop('value');
		  var monto_cotiza_usd = $("#txtMaxCotiUsd").prop('value');
		  var monto_emite_usd = $("#txtMaxEmiUsd").prop('value');
		  var monto_facu = $('#txtMontFacu').prop('value');
		  var selectEdadMax = $("#txtEdadMax option:selected").prop('value'); 
		  var selectEdadMin = $("#txtEdadMin option:selected").prop('value'); 
		  var idefin = $('#idefin option:selected').prop('value');
		 // var factura = $('input:radio[name="factura"]:checked').prop('value');
		  var numregi = $('#txtNumRegi').prop('value');
		 //var antiguedad = $('#antiguedad').prop('value');
		  var sum=0;
		  var edad=0;
		  $(this).find('.required').each(function(){
			  if(idefin!=''){
				  $('#errorentidad').hide('slow');
			  }else{
				  sum++;
				  $('#errorentidad').show('slow');
				  $('#errorentidad').html('seleccione entidad financiera');
			  }
			  
			  if(caducidad_cotiza!=''){
				  if(caducidad_cotiza.match(/^[0-9]+$/)){
					 $('#errorcaducidad').hide('slow');
				  }else{
					 sum++;
					  $('#errorcaducidad').show('slow'); 
					 $('#errorcaducidad').html('ingrese solo numeros'); 
				  }
			  }else{
				 sum++;  
				 $('#errorcaducidad').html('ingrese caducidad cotizacion'); 
			  }
			  if(monto_cotiza_usd!=''){
				  if(monto_cotiza_usd.match(/^[0-9]+$/)){
					 $('#errorarmaxcotiusd').hide('slow');
				  }else{
					 sum++;
					 $('#errorarmaxcotiusd').show('slow');
					 $('#errorarmaxcotiusd').html('ingrese solo numeros'); 
				  }
			  }else{
				 sum++;  
				 $('#errorarmaxcotiusd').show('slow');
				 $('#errorarmaxcotiusd').html('ingrese monto maximo usd');   
			  }
			  if(monto_emite_usd!=''){
				  if(monto_emite_usd.match(/^[0-9]+$/)){
					  $('#errorarmaxemiusd').hide('slow'); 
				  }else{
					 sum++;
				     $('#errorarmaxemiusd').show('slow'); 
					 $('#errorarmaxemiusd').html('ingrese solo numeros'); 
				  } 
			  }else{
				 sum++;
				 $('#errorarmaxemiusd').show('slow'); 
				 $('#errorarmaxemiusd').html('ingrese monto maximo usd'); 
			  }
			  if(monto_facu!=''){
				  if(monto_facu.match(/^[0-9]+$/)){
					  $('#errormontofacu').hide('slow'); 
				  }else{
					 sum++;
				     $('#errormontofacu').show('slow'); 
					 $('#errormontofacu').html('ingrese solo numeros'); 
				  } 
			  }else{
				 sum++;
				 $('#errormontofacu').show('slow'); 
				 $('#errormontofacu').html('ingrese monto facultativo'); 
			  }
			  if(numregi!=''){
				  if(numregi.match(/^[0-9]+$/)){
					 $('#errornumautos').hide('slow');
				  }else{
					 sum++;
					 $('#errornumautos').show('slow');
					 $('#errornumautos').html('ingrese solo numeros');  
				  }
			  }else{
				  sum++
				  $('#errornumautos').show('slow'); 
				  $('#errornumautos').html('ingrese numero de registros');
			  }
			  /*
			  if(antiguedad!=''){
				  if(antiguedad.match(/^[0-9]+$/)){
					 $('#errorantiguedad').hide('slow');
				  }else{
					 sum++;
					 $('#errorantiguedad').show('slow');
					 $('#errorantiguedad').html('ingrese solo numeros');  
				  }
			  }else{
				  sum++
				  $('#errorantiguedad').show('slow'); 
				  $('#errorantiguedad').html('ingrese antigüedad del auto');
			  }
			  */
			  //SELECCIONAR FACTURACION
			  if( $("#frmDatosCrea input[name='factura']:radio").is(':checked')) {
				  $('#errorfactura').hide('slow');
			  }else{
				  sum++;
				  $('#errorfactura').show('slow');
				  $('#errorfactura').html('seleccione facturacion'); 
			  }
			  //SELECCIONAR IMPLANTE
			  if( $("#frmDatosCrea input[name='implante']:radio").is(':checked')) {
				  $('#errorimplante').hide('slow');
			  }else{
				  sum++;
				  $('#errorimplante').show('slow');
				  $('#errorimplante').html('seleccione implante'); 
			  }
			  /*if(factura!=''){
				  
			  }else{
				
			  }*/
			  
			  if(selectEdadMin!=''){
				 $('#errorminedad').hide('slow'); 
			  }else{
				 sum++;
				 edad++;
				 $('#errorminedad').show('slow');
				 $('#errorminedad').html('seleccione edad minima');
			  }
			  if(selectEdadMax!=''){
				 $('#errormaxedad').hide('slow');
			  }else{
				 sum++;
				 edad++;
				 $('#errormaxedad').show('slow');
				 $('#errormaxedad').html('seleccione edad maxima');   
			  }
			  if(edad==0){
				  if(selectEdadMin<selectEdadMax){
					  $('#errorminedad').hide('slow');
				  }else{
					  sum++;
					  $('#errorminedad').show('slow').stop(true,false);
					  $('#errorminedad').html('edad minima debe ser menor a la edad maxima');
				  }
			  }
			  
		  });
		  if(sum==0){
			  //$("#btnUsuario").removeAttr("disabled");
		  }else{
			 e.preventDefault(); 
			 //$('#btnUsuario').attr('disabled', true);
			 
		  }
		  
	  });
	  
	  /*$('#txtMaxCotiUsd').blur(function(e){
		  var val_cotiza_dolar = $(this).val();
		  //VERIFICAMOS SI LA CASILLA ESTA VACIA
		   e.preventDefault();
		  if (val_cotiza_dolar == "") {
		     $('#errorarmaxcotiusd').show('slow');
			 $('#errorarmaxcotiusd').html('ingrese monto maximo usd');
			 $('#errorarmaxcotiusd').focus();
		  }else if(val_cotiza_dolar.match(/^[0-9]+$/)){//VERIFICAMOS SI EL VALOR ES NUMERO ENTERO
			  $('#errorarmaxcotiusd').hide('slow');
			  var equiv = parseInt(val_cotiza_dolar)*7;
			  $('#txtMaxCotiBs').prop('value',equiv).hide().show("slow");
		  }else{
			 $('#errorarmaxcotiusd').show('slow');
			 $('#errorarmaxcotiusd').html("ingrese solo numeros enteros").fadeIn("slow");
			 $('#errorarmaxcotiusd').focus();
		  }
      });
	  
	  $('#txtMaxEmiUsd').blur(function(e){
		  var val_emite_dolar = $(this).val();
		  //VERIFICAMOS SI LA CASILLA ESTA VACIA
		  e.preventDefault();
		  if (val_emite_dolar == "") {
		     $('#errorarmaxemiusd').show('slow');
			 $('#errorarmaxemiusd').html('ingrese monto maximo usd');
			 $('#errorarmaxemiusd').focus();
		  }else if(val_emite_dolar.match(/^[0-9]+$/)){//VERIFICAMOS SI EL VALOR ES NUMERO ENTERO
			  $('#errorarmaxemiusd').hide('slow');
			  var equiv = parseInt(val_emite_dolar)*7;
			  $("#txtMaxEmiBs").prop('value',equiv).hide().show("slow");
			  
		  }else{
			 $('#errorarmaxemiusd').show('slow');
			 $('#errorarmaxemiusd').html('ingrese solo numeros');
			 $('#errorarmaxemiusd').focus(); 
		  }
     });*/
	 
	  $('#btnCancelar').click(function(e){
		  var variable=$('#var').prop('value');
		  $(location).attr('href', 'index.php?l=trem_montos&var='+variable); 
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
								  and not exists( select 
									  sh.id_ef
								  from
									  s_sgc_home as sh
								  where
									  sh.id_ef = ef.id_ef and sh.producto='TRM');";
		}else{
			 $select1="select 
						  ef.id_ef, ef.nombre, ef.logo, ef.activado
					  from
						  s_entidad_financiera as ef
					  where
						  ef.activado = 1
							  and not exists( select 
								  sh.id_ef
							  from
								  s_sgc_home as sh
							  where
								  sh.id_ef = ef.id_ef and sh.producto='TRM')
							  and ef.id_ef='".$id_ef_sesion."';";
			
		 }
		 if($res1 = $conexion->query($select1,MYSQLI_STORE_RESULT)){
				 $num_reg = $res1->num_rows;	 							
				  echo'<div class="da-panel" style="width:600px;">
						<div class="da-panel-header">
							<span class="da-panel-title">
								<img src="images/icons/black/16/pencil.png" alt="" />
								Crear Montos Todo Riesgo Equipo Movil
							</span>
						</div>
						<div class="da-panel-content">
							<form class="da-form" name="frmDatosCrea" action="" method="post" id="frmDatosCrea">
								<div class="da-form-row">
								   <label style="width:190px; text-align:right; margin-right:10px;"><b>Entidad Financiera</b></label>
								   <div class="da-form-item large">
									   <select id="idefin" name="idefin" class="required" style="width:160px;">';
										  echo'<option value="">seleccione...</option>';
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
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b>Caducidad cotizacion (días)</b></label>
									<div class="da-form-item large">
										<input class="textbox required" type="text" name="txtLimitCotiza" id="txtLimitCotiza" style="width: 200px;" value="" autocomplete="off"/>
										<span class="errorMessage" id="errorcaducidad"></span>
									</div>
								</div>
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b>Monto Maximo Cotizacion (USD)</b></label>
									<div class="da-form-item large">
										<input class="textbox required" type="text" name="txtMaxCotiUsd" id="txtMaxCotiUsd" style="width: 200px;" value="" autocomplete="off"/>
										<span class="errorMessage" id="errorarmaxcotiusd"></span>
									</div>
								</div>
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b>Monto Maximo Emision (USD)</b></label>
									<div class="da-form-item large">
										<input class="textbox required" type="text" name="txtMaxEmiUsd" id="txtMaxEmiUsd" style="width: 200px;" value="" autocomplete="off"/>
										<span class="errorMessage" id="errorarmaxemiusd"></span>
									</div>
								</div>
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b>Monto Facultativo (USD)</b></label>
									<div class="da-form-item large">
										<input class="textbox required" type="text" name="txtMontFacu" id="txtMontFacu" style="width: 200px;" value="" autocomplete="off"/>
										<span class="errorMessage" id="errormontofacu"></span>
									</div>
								</div>
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b>Numero de Registros</b></label>
									<div class="da-form-item large">
										<input class="textbox required" type="text" name="txtNumRegi" id="txtNumRegi" style="width: 200px;" value="" autocomplete="off"/>
										<span class="errorMessage" id="errornumautos"></span>
									</div>
								</div>
								<div class="da-form-row">
									<label style="text-align:right; width:190px; margin-right:10px;"><b>Facturación</b></label>
									<div class="da-form-item">
										<ul class="da-form-list inline">
											<li><input type="radio" name="factura" id="rd-1" value="1" class="required"/> <label>Si</label></li>
											<li><input type="radio" name="factura" id="rd-2" value="0" class="required"/> <label>No</label></li>
										</ul>
										<span class="errorMessage" id="errorfactura"></span>
									</div>
								</div>
								<div class="da-form-row">
									<label style="text-align:right; width:190px; margin-right:10px;"><b>Implante</b></label>
									<div class="da-form-item">
										<ul class="da-form-list inline">
											<li><input type="radio" name="implante" id="rd-1" value="1" class="required"/> <label>Si</label></li>
											<li><input type="radio" name="implante" id="rd-2" value="0" class="required"/> <label>No</label></li>
										</ul>
										<span class="errorMessage" id="errorimplante"></span>
									</div>
								</div>
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b>Edad Minima</b></label>
									<div class="da-form-item large">';
										$j=18;
									  echo'<select id="txtEdadMin" name="txtEdadMin" style="width:120px;" class="required">';
											  echo'<option value="">Seleccione...</option>';
											  while($j<=85){
												 echo'<option value="'.$j.'">'.$j.'</option>';  
												 $j++;   
											  }
									  echo'</select>
										  <span class="errorMessage" id="errorminedad"></span>'; 	
							   echo'</div>
								</div>		
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b>Edad Maxima</b></label>
									<div class="da-form-item large">';
										 $i=18;
									  echo'<select id="txtEdadMax" name="txtEdadMax" style="width:120px;" class="required">';
											  echo'<option value="">Seleccione...</option>';
											  while($i<=85){
												 echo'<option value="'.$i.'">'.$i.'</option>'; 
												 $i++;   
											  }
									  echo'</select>
										  <span class="errorMessage" id="errormaxedad"></span>';
											
							   echo'</div>
								</div>												
								<div class="da-button-row">
									<input type="button" value="Cancelar" class="da-button gray left" name="btnCancelar" id="btnCancelar"/>';
									if($num_reg>0){
									   echo'<input type="submit" value="Guardar" class="da-button green" name="btnUsuario" id="btnUsuario"/>';
									}else{
									   echo'<input type="submit" value="Guardar" class="da-button green" name="btnUsuario" id="btnUsuario" disabled/>';			
									}
									echo'<input type="hidden" name="accionGuardar" value="guardar"/>
									<input type="hidden" name="var" id="var" value="'.$_GET['var'].'"/>
								</div>
							</form>
						</div>
					</div>';
		 }else{
			 echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error."</div>";
		 }
	
}

//FUNCION PARA EDITAR UN USUARIO
function editar_datos_admin($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion) {

	$errFlag = false;
	$errArr['errorcaducidad'] = '';
	$errArr['errorarmaxcotiusd'] = '';
	$errArr['errorarmaxcotibs'] = '';
	$errArr['errorarmaxemiusd'] = '';
	$errArr['errorarmaxemibs'] = '';
	$errArr['errormaxedad'] = '';
	$errArr['errorminedad'] = '';
	

	$idhome = base64_decode($_GET['idhome']);
	$id_ef = base64_decode($_GET['id_ef']);
	//$idusuario = strtolower($idusuario);

	//VEO SI SE HA HECHO CLICK EN EL BOTON GUARDAR
	if(isset($_POST['btnUsuario'])) {
       
        
            //SEGURIDAD
			$caducidadcotiz = $conexion->real_escape_string($_POST['txtLimitCotiza']);
			$montocotizusd = $conexion->real_escape_string($_POST['txtMaxCotiUsd']);
			$montoemiusd = $conexion->real_escape_string($_POST['txtMaxEmiUsd']);
			$edadmax = $conexion->real_escape_string($_POST['txtEdadMax']);
			$edadmin = $conexion->real_escape_string($_POST['txtEdadMin']);
			$numregi = $conexion->real_escape_string($_POST['txtNumRegi']);
			$factura = $conexion->real_escape_string($_POST['factura']);
			$implante = $conexion->real_escape_string($_POST['implante']);
			$cert_prov = $conexion->real_escape_string($_POST['cert_prov']);
			$modalidad = $conexion->real_escape_string($_POST['modalidad']);
			$monto_facu = $conexion->real_escape_string($_POST['txtMontFacu']);
			$webservice = $conexion->real_escape_string($_POST['webservice']);
			
            //CARGAMOS LOS DATOS A LA BASE DE DATOS
            $update = "UPDATE s_sgc_home SET limite_cotizacion=".$caducidadcotiz.", max_cotizacion_usd=".$montocotizusd.", max_emision_usd=".$montoemiusd.", edad_max=".$edadmax.", edad_min=".$edadmin.", max_detalle=".$numregi.", facturacion=".$factura.", implante=".$implante.", certificado_provisional=".$cert_prov.", modalidad=".$modalidad.", monto_facultativo=".$monto_facu.", web_service=".$webservice." WHERE id_home='".$idhome."' and id_ef='".$id_ef."' LIMIT 1;";
            //echo $update;
            

            if($conexion->query($update)===TRUE){
                $mensaje="Se actualizo correctamente los datos del formulario";
			    header('Location: index.php?l=trem_montos&var='.$_GET['var'].'&op=1&msg='.base64_encode($mensaje));
			    exit;
            } else{
                $mensaje="Hubo un error al ingresar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
			    header('Location: index.php?l=trem_montos&var='.$_GET['var'].'&op=2&msg='.base64_encode($mensaje));
				exit;
            }
			
	}else {
	  //MUESTRO FORM PARA EDITAR UNA CATEGORIA
	  mostrar_editar_datos_admin($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr);
	}
	
}

//VISUALIZAMOS EL FORMULARIO PARA EDITAR EL FORMULARIO
function mostrar_editar_datos_admin($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $errArr){
?>
<script type="text/javascript">
  $(function(){
	  $('#frmDatosAdmin').submit(function(e){
		  //alert('Guardar');
		  var caducidad_cotiza = $('#txtLimitCotiza').prop('value');
		  var monto_cotiza_usd = $("#txtMaxCotiUsd").prop('value');
		  var monto_emite_usd = $("#txtMaxEmiUsd").prop('value');
		  var selectEdadMax = $("#txtEdadMax option:selected").prop('value'); 
		  var selectEdadMin = $("#txtEdadMin option:selected").prop('value');
		  var numregi = $('#txtNumRegi').prop('value');
		  //var antiguedad = $('#antiguedad').prop('value');
		  var monto_facu = $('#txtMontFacu').prop('value'); 
		  var sum=0;
		  var edad=0;
		  $(this).find('.required').each(function(){
			  if(caducidad_cotiza!=''){
				  if(caducidad_cotiza.match(/^[0-9]+$/)){
					 $('#errorcaducidad').hide('slow');
				  }else{
					 sum++;
					  $('#errorcaducidad').show('slow'); 
					 $('#errorcaducidad').html('ingrese solo numeros'); 
				  }
			  }else{
				 sum++;  
				 $('#errorcaducidad').html('ingrese caducidad cotizacion'); 
			  }
			  if(monto_cotiza_usd!=''){
				  if(monto_cotiza_usd.match(/^[0-9]+$/)){
					 $('#errorarmaxcotiusd').hide('slow');
				  }else{
					 sum++;
					 $('#errorarmaxcotiusd').show('slow');
					 $('#errorarmaxcotiusd').html('ingrese solo numeros'); 
				  }
			  }else{
				 sum++;  
				 $('#errorarmaxcotiusd').show('slow');
				 $('#errorarmaxcotiusd').html('ingrese monto maximo usd');   
			  }
			  if(monto_emite_usd!=''){
				  if(monto_emite_usd.match(/^[0-9]+$/)){
					  $('#errorarmaxemiusd').hide('slow'); 
				  }else{
					 sum++;
				     $('#errorarmaxemiusd').show('slow'); 
					 $('#errorarmaxemiusd').html('ingrese solo numeros'); 
				  } 
			  }else{
				 sum++;
				 $('#errorarmaxemiusd').show('slow'); 
				 $('#errorarmaxemiusd').html('ingrese monto maximo usd'); 
			  }
			  if(monto_facu!=''){
				  if(monto_facu.match(/^[0-9]+$/)){
					  $('#errormontofacu').hide('slow'); 
				  }else{
					 sum++;
				     $('#errormontofacu').show('slow'); 
					 $('#errormontofacu').html('ingrese solo numeros'); 
				  } 
			  }else{
				 sum++;
				 $('#errormontofacu').show('slow'); 
				 $('#errormontofacu').html('ingrese monto facultativo'); 
			  }
			  if(numregi!=''){
				  if(numregi.match(/^[0-9]+$/)){
					 $('#errornumautos').hide('slow');
				  }else{
					 sum++;
					 $('#errornumautos').show('slow');
					 $('#errornumautos').html('ingrese solo numeros');  
				  }
			  }else{
				  sum++
				  $('#errornumautos').show('slow'); 
				  $('#errornumautos').html('ingrese numero de registros');
			  }
			  /*if(antiguedad!=''){
				  if(antiguedad.match(/^[0-9]+$/)){
					 $('#errorantiguedad').hide('slow');
				  }else{
					 sum++;
					 $('#errorantiguedad').show('slow');
					 $('#errorantiguedad').html('ingrese solo numeros');  
				  }
			  }else{
				  sum++;
				  $('#errorantiguedad').show('slow');
				  $('#errorantiguedad').html('ingrese antigüedad auto');
			  }*/
			  if( $("#frmDatosAdmin input[name='factura']:radio").is(':checked')) {
				  $('#errorfactura').hide('slow');
			  }else{
				  sum++;
				  $('#errorfactura').show('slow');
				  $('#errorfactura').html('seleccione facturacion'); 
			  }
			  if(selectEdadMin!=''){
				 $('#errorminedad').hide('slow'); 
			  }else{
				 sum++;
				 edad++;
				 $('#errorminedad').show('slow');
				 $('#errorminedad').html('seleccione edad minima');
			  }
			  if(selectEdadMax!=''){
				 $('#errormaxedad').hide('slow');
			  }else{
				 sum++;
				 edad++;
				 $('#errormaxedad').show('slow');
				 $('#errormaxedad').html('seleccione edad maxima');   
			  }
			  if(edad==0){
				  if(selectEdadMin<selectEdadMax){
					  $('#errorminedad').hide('slow');
				  }else{
					  sum++;
					  $('#errorminedad').show('slow').stop(true,false);
					  $('#errorminedad').html('edad minima debe ser menor a la edad maxima');
				  }
			  }
			  
		  });
		  if(sum==0){
			  //$("#btnUsuario").removeAttr("disabled");
		  }else{
			 e.preventDefault(); 
			 //$('#btnUsuario').attr('disabled', true);
			 
		  }
		  
	  });
	  /*
	  $('#txtMaxCotiUsd').blur(function(e){
		  var val_cotiza_dolar = $(this).val();
		  //VERIFICAMOS SI LA CASILLA ESTA VACIA
		   e.preventDefault();
		  if (val_cotiza_dolar == "") {
		     $('#errorarmaxcotiusd').show('slow');
			 $('#errorarmaxcotiusd').html('ingrese monto maximo usd');
			 $('#errorarmaxcotiusd').focus();
		  }else if(val_cotiza_dolar.match(/^[0-9]+$/)){//VERIFICAMOS SI EL VALOR ES NUMERO ENTERO
			  $('#errorarmaxcotiusd').hide('slow');
			  var equiv = parseInt(val_cotiza_dolar)*7;
			  $('#txtMaxCotiBs').prop('value',equiv).hide().show("slow");
		  }else{
			 $('#errorarmaxcotiusd').show('slow');
			 $('#errorarmaxcotiusd').html("ingrese solo numeros enteros").fadeIn("slow");
			 $('#errorarmaxcotiusd').focus();
		  }
      });
	  
	  $('#txtMaxEmiUsd').blur(function(e){
		  var val_emite_dolar = $(this).val();
		  //VERIFICAMOS SI LA CASILLA ESTA VACIA
		  e.preventDefault();
		  if (val_emite_dolar == "") {
		     $('#errorarmaxemiusd').show('slow');
			 $('#errorarmaxemiusd').html('ingrese monto maximo usd');
			 $('#errorarmaxemiusd').focus();
		  }else if(val_emite_dolar.match(/^[0-9]+$/)){//VERIFICAMOS SI EL VALOR ES NUMERO ENTERO
			  $('#errorarmaxemiusd').hide('slow');
			  var equiv = parseInt(val_emite_dolar)*7;
			  $("#txtMaxEmiBs").prop('value',equiv).hide().show("slow");
			  
		  }else{
			 $('#errorarmaxemiusd').show('slow');
			 $('#errorarmaxemiusd').html('ingrese solo numeros');
			 $('#errorarmaxemiusd').focus(); 
		  }
     });*/
	 
	  $('#btnCancelar').click(function(e){
		  var variable=$('#var').prop('value');
		  $(location).attr('href', 'index.php?l=trem_montos&var='+variable); 
	  });  
  });
</script>
<?php    
	$idhome = base64_decode($_GET['idhome']);
    $id_ef = base64_decode($_GET['id_ef']);
	//SACAMOS LOS DATOS DE LA BASE DE DATOS
	$select = "select 
					sh.id_home,
					sh.producto,
					sh.limite_cotizacion,
					sh.max_cotizacion_usd,
					sh.max_emision_usd,
					sh.edad_max,
					sh.edad_min,
					ef.nombre,
					sh.id_ef,
					sh.max_detalle,
					sh.facturacion,
					sh.implante,
					sh.certificado_provisional,
					sh.modalidad,
					sh.anio,
					sh.monto_facultativo,
					sh.web_service
				from
					s_sgc_home as sh
					inner join s_entidad_financiera as ef on (ef.id_ef=sh.id_ef) 
				where
					sh.producto = 'TRM' and sh.id_home = '".$idhome."'
						and sh.id_ef = '".$id_ef."' and ef.activado=1;";
	
	if($rs = $conexion->query($select, MYSQLI_STORE_RESULT)){
			$num = $rs->num_rows;
			
			//SI EXISTE EL USUARIO DADO EN LA BASE DE DATOS, LO EDITAMOS
			if($num>0) {
		
				$fila = $rs->fetch_array(MYSQLI_ASSOC);
				$rs->free();
		
				if(isset($_POST['txtLimitCotiz'])) $txtLimitCotiz = $_POST['txtLimitCotiz']; else $txtLimitCotiz = $fila['limite_cotizacion'];
				if(isset($_POST['txtMaxCotiUsd'])) $txtMaxCotiUsd = $_POST['txtMaxCotiUsd']; else $txtMaxCotiUsd = $fila['max_cotizacion_usd'];
				
				if(isset($_POST['txtMaxEmiUsd'])) $txtMaxEmiUsd = $_POST['txtMaxEmiUsd']; else $txtMaxEmiUsd = $fila['max_emision_usd'];
				
				if(isset($_POST['txtEdadMax'])) $txtEdadMax = $_POST['txtEdadMax']; else $txtEdadMax = $fila['edad_max'];
				if(isset($_POST['txtEdadMin'])) $txtEdadMin = $_POST['txtEdadMin']; else $txtEdadMin = $fila['edad_min'];
				if(isset($_POST['txtNumRegi'])) $txtNumRegi = $_POST['txtNumRegi']; else $txtNumRegi = $fila['max_detalle'];
				if(isset($_POST['factura'])) $factura = $_POST['factura']; else $factura = $fila['facturacion'];
				if(isset($_POST['implante'])) $implante = $_POST['implante']; else $implante = $fila['implante'];
				if(isset($_POST['cert_prov'])) $cert_prov = $_POST['cert_prov']; else $cert_prov = $fila['certificado_provisional'];
				if(isset($_POST['modalidad'])) $modalidad = $_POST['modalidad']; else $modalidad = $fila['modalidad'];
				if(isset($_POST['webservice'])) $webservice = $_POST['webservice']; else $webservice = $fila['web_service'];
				if(isset($_POST['txtMontFacu'])) $txtMontFacu = $_POST['txtMontFacu']; else $txtMontFacu = $fila['monto_facultativo'];
						
				  echo'<div class="da-panel" style="width:600px;">
						<div class="da-panel-header">
							<span class="da-panel-title">
								<img src="images/icons/black/16/pencil.png" alt="" />
								<span lang="es">Editar Parametros del Producto Ramos Técnicos</span>
							</span>
						</div>
						<div class="da-panel-content">
							<form class="da-form" name="frmDatosAdmin" action="" method="post" id="frmDatosAdmin">
								<div class="da-form-row">
									 <label style="width:190px; text-align:right; margin-right:10px;"><b><span lang="es">Entidad Financiera</span></b></label>
									 <div class="da-form-item large">
										 '.$fila['nombre'].'
										 <input type="hidden" name="idenfin" value="'.$fila['id_ef'].'"/>								 
									 </div>	 
								</div>	
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b><span lang="es">Caducidad Cotización (días)</span></b></label>
									<div class="da-form-item large">
										<input class="textbox required" type="text" name="txtLimitCotiza" id="txtLimitCotiza" style="width: 200px;" value="'.$txtLimitCotiz.'" autocomplete="off"/>
										<span class="errorMessage" id="errorcaducidad" lang="es"></span>
									</div>
								</div>
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b><span lang="es">Monto Máximo Cotización (USD)</span></b></label>
									<div class="da-form-item large">
										<input class="textbox required" type="text" name="txtMaxCotiUsd" id="txtMaxCotiUsd" style="width: 200px;" value="'.$txtMaxCotiUsd.'" autocomplete="off"/>
										<span class="errorMessage" id="errorarmaxcotiusd" lang="es"></span>
									</div>
								</div>
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b><span lang="es">Monto Máximo Emisión (USD)</span></b></label>
									<div class="da-form-item large">
										<input class="textbox required" type="text" name="txtMaxEmiUsd" id="txtMaxEmiUsd" style="width: 200px;" value="'.$txtMaxEmiUsd.'" autocomplete="off"/>
										<span class="errorMessage" id="errorarmaxemiusd" lang="es"></span>
									</div>
								</div>
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b><span lang="es">Monto Facultativo</span> (USD)</b></label>
									<div class="da-form-item large">
										<input class="textbox required" type="text" name="txtMontFacu" id="txtMontFacu" style="width: 200px;" value="'.$txtMontFacu.'" autocomplete="off"/>
										<span class="errorMessage" id="errormontofacu" lang="es"></span>
									</div>
								</div>
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b><span lang="es">Numero de Registros</span></b></label>
									<div class="da-form-item large">
										<input class="textbox required" type="text" name="txtNumRegi" id="txtNumRegi" style="width: 200px;" value="'.$txtNumRegi.'" autocomplete="off"/>
										<span class="errorMessage" id="errornumautos"></span>
									</div>
								</div>
								<div class="da-form-row">
									<label style="text-align:right; width:190px; margin-right:10px;"><b><span lang="es">Facturación</span></b></label>
									<div class="da-form-item">
										<ul class="da-form-list inline">';
										   if($factura==1){	
											  echo'<li><input type="radio" name="factura" id="rd-1" value="1" class="required" checked/> <label>Si</label></li>';
										   }else{
											  echo'<li><input type="radio" name="factura" id="rd-1" value="1" class="required"/> <label>Si</label></li>'; 
										   }
										   if($factura==0){
											  echo'<li><input type="radio" name="factura" id="rd-2" value="0" class="required" checked/> <label>No</label></li>';
										   }else{
											  echo'<li><input type="radio" name="factura" id="rd-2" value="0" class="required"/> <label>No</label></li>'; 
										   }
								   echo'</ul>
										<span class="errorMessage" id="errorfactura"></span>
									</div>
								</div>
								<div class="da-form-row">
									<label style="text-align:right; width:190px; margin-right:10px;"><b><span lang="es">Implante</span></b></label>
									<div class="da-form-item">
										<ul class="da-form-list inline">';
										   if($implante==1){	
											  echo'<li><input type="radio" name="implante" id="rd-1" value="1" class="required" checked/> <label>Si</label></li>';
										   }else{
											  echo'<li><input type="radio" name="implante" id="rd-1" value="1" class="required"/> <label>Si</label></li>'; 
										   }
										   if($implante==0){
											  echo'<li><input type="radio" name="implante" id="rd-2" value="0" class="required" checked/> <label>No</label></li>';
										   }else{
											  echo'<li><input type="radio" name="implante" id="rd-2" value="0" class="required"/> <label>No</label></li>'; 
										   }
								   echo'</ul>
										<span class="errorMessage" id="errorimplante"></span>
									</div>
								</div>
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b><span lang="es">Certificado Provisional</span></b></label>
									<div class="da-form-item">
										<ul class="da-form-list inline">';
										   if($cert_prov==1){	
											  echo'<li><input type="radio" name="cert_prov" id="ctp-1" value="1" class="required" checked/> <label>Si</label></li>';
										   }else{
											  echo'<li><input type="radio" name="cert_prov" id="ctp-1" value="1" class="required"/> <label>Si</label></li>'; 
										   }
										   if($cert_prov==0){
											  echo'<li><input type="radio" name="cert_prov" id="ctp-2" value="0" class="required" checked/> <label>No</label></li>';
										   }else{
											  echo'<li><input type="radio" name="cert_prov" id="ctp-2" value="0" class="required"/> <label>No</label></li>'; 
										   }
								   echo'</ul>
										<span class="errorMessage" id="errorcertprovisional"></span>
									</div>
								</div>
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b><span lang="es">Modalidad</span></b></label>
									<div class="da-form-item">
										<ul class="da-form-list inline">';
										   if($modalidad==1){	
											  echo'<li><input type="radio" name="modalidad" id="mod-1" value="1" class="required" checked/> <label>Si</label></li>';
										   }else{
											  echo'<li><input type="radio" name="modalidad" id="mod-1" value="1" class="required"/> <label>Si</label></li>'; 
										   }
										   if($modalidad==0){
											  echo'<li><input type="radio" name="modalidad" id="mod-2" value="0" class="required" checked/> <label>No</label></li>';
										   }else{
											  echo'<li><input type="radio" name="modalidad" id="mod-2" value="0" class="required"/> <label>No</label></li>'; 
										   }
								   echo'</ul>
										<span class="errorMessage" id="errorcertprovisional"></span>
									</div>
								</div>';
						        if($tipo_sesion=='ROOT'){
								   echo'<div class="da-form-row">
											<label style="width:190px; text-align:right; margin-right:10px;"><b>Web Services</b></label>
											<div class="da-form-item">
												<ul class="da-form-list inline">';
												   if($webservice==1){	
													  echo'<li><input type="radio" name="webservice" id="ws-1" value="1" class="required" checked/> <label>Si</label></li>';
												   }else{
													  echo'<li><input type="radio" name="webservice" id="ws-1" value="1" class="required"/> <label>Si</label></li>'; 
												   }
												   if($webservice==0){
													  echo'<li><input type="radio" name="webservice" id="ws-2" value="0" class="required" checked/> <label>No</label></li>';
												   }else{
													  echo'<li><input type="radio" name="webservice" id="ws-2" value="0" class="required"/> <label>No</label></li>'; 
												   }
										   echo'</ul>
												<span class="errorMessage" id="errorwservice"></span>
											</div>
										</div>';
								} 
						   echo'<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b><span lang="es">Edad Mínima</span></b></label>
									<div class="da-form-item large">';
										$j=18;
									  echo'<select id="txtEdadMin" name="txtEdadMin" style="width:120px;" class="required">';
											  echo'<option value="" lang="es">seleccione...</option>';
											  while($j<=85){
												 if($txtEdadMin==$j){  
													echo'<option value="'.$j.'" selected>'.$j.'</option>'; 
												 }else{
													echo'<option value="'.$j.'">'.$j.'</option>';  
												 }
												 $j++;   
											  }
									  echo'</select>
										  <span class="errorMessage" id="errorminedad" lang="es"></span>'; 	
							   echo'</div>
								</div>		
								<div class="da-form-row">
									<label style="width:190px; text-align:right; margin-right:10px;"><b><span lang="es">Edad Máxima</span></b></label>
									<div class="da-form-item large">';
										 $i=18;
									  echo'<select id="txtEdadMax" name="txtEdadMax" style="width:120px;" class="required">';
											  echo'<option value="" lang="es">seleccione...</option>';
											  while($i<=85){
												 if($txtEdadMax==$i){ 
													echo'<option value="'.$i.'" selected>'.$i.'</option>'; 
												 }else{
													echo'<option value="'.$i.'">'.$i.'</option>'; 
												 }
												 $i++;   
											  }
									  echo'</select>
										  <span class="errorMessage" id="errormaxedad" lang="es"></span>';
											
							   echo'</div>
								</div>												
								<div class="da-button-row">
									<input type="button" value="Cancelar" class="da-button gray left" name="btnCancelar" id="btnCancelar" lang="es"/>
									<input type="submit" value="Guardar" class="da-button green" name="btnUsuario" id="btnUsuario" lang="es"/>
									
									<input type="hidden" name="var" id="var" value="'.$_GET['var'].'"/>
								</div>
							</form>
						</div>
					</div>';
			
			} else {
				//SI NO EXISTE EL USUARIO DADO EN LA BASE DE DATOS, VOLVEMOS A LA LISTA DE USUARIOS
				echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>No existe el registro</div>";
				echo '<meta http-equiv="refresh" content="2; url=index.php?l=trem_montos&var='.$_GET['var'].'">' ;
			}
	}else{
		echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error."</div>";
	}
}
?>