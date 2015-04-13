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
			header('Location: index.php?l=escritorio');
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
								}elseif(isset($_GET['listarsuc'])){ 
								    //VISUALIZAMOS LISTA EXISTENTES DE CUESTIONARIOS
									mostrar_lista_sucursales($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
							    }elseif(isset($_GET['listarpregunta'])){
								   //VISUALIZAMOS LISTA EXISTENTES DE PREGUNTAS
								    mostrar_lista_preguntas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
							    }elseif(isset($_GET['agregarqu'])){
							        //VISUALIZAMOS LA LISTA DE CUESTIONARIOS AGREGADOS
									mostrar_lista_agrega_cuestionario($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								}elseif(isset($_GET['agregar_pregunta'])){
								   //VISUALIZAMOS LA LISTA DE PREGUNTAS AGREGADAS
								    mostrar_lista_preguntas_agregadas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
							    }else {
									//MUESTRO LA LISTA DE CORREOS EXISTENTES
									mostrar_lista_agencias($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
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
function mostrar_lista_agencias($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>
<link type="text/css" rel="stylesheet" href="plugins/fancybox/jquery.fancybox.css"/>
<script type="text/javascript" src="plugins/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript">
     $('.various').fancybox({
	    maxWidth	: 400,
		maxHeight	: 390,
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
		   var variable = $(this).attr('id'); 		  
		   var vec = variable.split('|');
		   var idagencia = vec[0];
		   var id_ef = vec[1];
		   var id_depto = vec[2];
		   var i = vec[3];
		  
		   jConfirm("¿Esta seguro de eliminar la agencia?", "Eliminar registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='idagencia='+idagencia+'&id_ef='+id_ef+'&id_depto='+id_depto+'&opcion=eliminaragencia';
						$.ajax({
							   async: true,
							   cache: false,
							   type: "POST",
							   url: "eliminar_registro.php",
							   data: dataString,
							   success: function(datareturn) {
									  if(datareturn==1){
										 //location.reload(true);
										 $('#del-'+i).fadeOut('slow');
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
						and ef.id_ef = '".$id_ef_sesion."';";
}
    if($resef = $conexion->query($selectEf,MYSQLI_STORE_RESULT)){
       if($resef->num_rows>0){
		  echo'<div class="da-panel collapsible">
				  <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
					  <ul class="action_user">
						  <li style="margin-right:6px;">
							 <a href="adicionar_registro.php?opcion=crear_agencia&id_ef_sesion='.base64_encode($id_ef_sesion).'&tipo_sesion='.base64_encode($tipo_sesion).'" class="da-tooltip-s various fancybox.ajax" title="<span lang=\'es\'>Añadir nueva agencia</span>">
							 <img src="images/add_new.png" width="32" height="32"></a>
						  </li>
					  </ul>
				  </div>
			   </div>';
		while($regief = $resef->fetch_array(MYSQLI_ASSOC)){
				 //SACAMOS LAS AGENCIAS EXISTENTES
				$select="select 
							  sa.id_agencia, 
							  sa.codigo, 
							  sa.agencia, 
							  sa.id_depto, 
							  sa.id_ef,
							  sd.departamento,
							  case sa.emision
								when 0 then 'No'
								when 1 then 'Si'
							  end as emision	
						  from
							  s_agencia as sa
							  inner join s_departamento as sd on (sd.id_depto=sa.id_depto)
						  where
							  sa.id_ef = '".$regief['id_ef']."' and (sd.tipo_re=1 or sd.tipo_dp=1)
						  order by sa.id_agencia;";
				$res = $conexion->query($select,MYSQLI_STORE_RESULT);
			echo'<div class="da-panel collapsible" style="width:750px;">
					<div class="da-panel-header">
						<span class="da-panel-title">
							<img src="images/icons/black/16/list.png" alt="" />
							<b>'.$regief['nombre'].'</b> - <span lang="es">listado de agencias</span>
						</span>
					</div>
					<div class="da-panel-content">
						<table class="da-table">
							<thead>
								<tr>
									<th><b>Agencia</b></th>
									<th style="text-align:center;"><b><span lang="es">Departamento/Sucursal</span></b></th>
									<th style="text-align:center;"><b><span lang="es">Codigo</span></b></th>
									<th style="text-align:center;"><b><span lang="es">Emisión</span></b></th>
									<th></th>
								</tr>
							</thead>
							<tbody>';
							  $num = $res->num_rows;
							  if($num>0){
									
									while($regi = $res->fetch_array(MYSQLI_ASSOC)){
										$vec=explode('.',$regi['id_agencia']);
										echo'<tr id="del-'.$vec[1].'">
												<td>'.$regi['agencia'].'</td>
												<td style="text-align:center;">'.$regi['departamento'].'</td>
												<td style="text-align:center;">'.$regi['codigo'].'</td>
												<td style="text-align:center;">'.$regi['emision'].'</td>
												<td class="da-icon-column">
												   <ul class="action_user">';
													
													  echo'<li style="margin-right:5px;"><a href="adicionar_registro.php?idagencia='.base64_encode($regi['id_agencia']).'&id_depto='.base64_encode($regi['id_depto']).'&id_ef='.base64_encode($regief['id_ef']).'&id_ef_sesion='.base64_encode($id_ef_sesion).'&tipo_sesion='.base64_encode($tipo_sesion).'&opcion=editar_agencia" class="edit da-tooltip-s various fancybox.ajax" title="<span lang=\'es\'>Editar</span>"></a></li>';
												   
													//VERIFICAMOS SI AGENCIA ESTA AÑADIDO A USUARIO
													$busca="select
															  count(id_usuario) as cant
															from
															  s_usuario
															where
															  id_agencia='".$regi['id_agencia']."';";
													$reslrg = $conexion->query($busca,MYSQLI_STORE_RESULT);		  
													$sqlrg = $reslrg->fetch_array(MYSQLI_ASSOC);
													$reslrg->free();
													if($sqlrg['cant']==0){		  
													  echo'<li><a href="#" class="eliminar da-tooltip-s" title="Eliminar" id="'.$regi['id_agencia'].'|'.$regief['id_ef'].'|'.$regi['id_depto'].'|'.$vec[1].'"></a></li>';
													}else{
													   echo'<li style="margin-left:10px;">&nbsp;</li>';	
													}
											  echo'</ul>	
												</td>
											</tr>';
											
									}			
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
	   }else{
		   echo'<div class="da-message warning">
					 <span lang="es">No existe ningun registro, probablemente se debe a</span>:
					 <ul>
						<li lang="es">La Entidad Financiera no tiene asignado un producto</li>
						<li lang="es">La Entidad Financiera no esta activado</li>
						<li lang="es">La Entidad Financiera no esta creada</li>
					  </ul>
				 </div>';
	   }
	}else{
		echo"<div style='font-size:8pt; text-align:center; margin-top:20px; margin-bottom:15px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px; width:600px;'>Error en la consulta: "."\n ".$conexion->errno . ": " .$conexion->error."</div>";
	}
}

//LISTAMOS SUCURSALES EXISTENTES
function mostrar_lista_sucursales($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>
<link type="text/css" rel="stylesheet" href="plugins/fancybox/jquery.fancybox.css"/>
<script type="text/javascript" src="plugins/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript">
     $('.cuestionario').fancybox({
	    maxWidth	: 450,
		maxHeight	: 355,
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
$(document).ready(function() {
    $("a[href].eliminar").click(function(e){
	   var id_depto = $(this).attr('id'); 		  
	   jConfirm("¿Esta seguro de eliminar la sucursal?", "Eliminar registro", function(r) {
			//alert(r);
			if(r) {
					var dataString ='id_depto='+id_depto+'&opcion=eliminar_sucursal';
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
	
	//CHECKBOX TIPO CI
	$('.tipoci').click(function(){
		   var id_depto = $(this).prop('value');
		   if($('#ci-'+id_depto).is(":checked")){
			   var vardata='V';
		   }else{
			   var vardata='F';
		   }
		   var dataString ='id_depto='+id_depto+'&vardata='+vardata+'&campo=tipo_ci&opcion=actualizar_check';
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
	
	//CHECKBOX TIPO REGION
	$('.tiporeg').click(function(){
		   var id_depto = $(this).prop('value');
		   if($('#reg-'+id_depto).is(":checked")){
			   var vardata='V';
		   }else{
			   var vardata='F';
		   }
		   var dataString ='id_depto='+id_depto+'&vardata='+vardata+'&campo=tipo_re&opcion=actualizar_check';
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
	
	//CHECKBOX TIPO DEPARTAMENTO
	$('.tipodepto').click(function(){
		   var id_depto = $(this).prop('value');
		   if($('#dep-'+id_depto).is(":checked")){
			   var vardata='V';
		   }else{
			   var vardata='F';
		   }
		   var dataString ='id_depto='+id_depto+'&vardata='+vardata+'&campo=tipo_dp&opcion=actualizar_check';
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
<?php
//SACAMOS LOS CUESTIONARIOS EXISTENTES
$selectFor="select
			   sdp.id_depto,
			   sdp.departamento,
			   sdp.codigo,
			   sdp.tipo_ci,
			   sdp.tipo_re,
			   sdp.tipo_dp,
			   sdp.id_ef,
			   ef.logo 
			from
			  s_departamento sdp
			  left join s_entidad_financiera as ef on (ef.id_ef=sdp.id_ef) 
			order by
			  sdp.id_depto; ";
$res = $conexion->query($selectFor,MYSQLI_STORE_RESULT);

echo'
<div class="da-panel collapsible">
    <div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
		<ul class="action_user">
			<li style="margin-right:6px;">
			   <a href="adicionar_registro.php?opcion=crear_sucursal&tipo_sesion='.base64_encode($tipo_sesion).'&id_ef_sesion='.base64_encode($id_ef_sesion).'" class="da-tooltip-s cuestionario fancybox.ajax" title="<span lang=\'es\'>Añadir Nuevo Registro</span>">
			   <img src="images/add_new.png" width="32" height="32"></a>
			</li>
		</ul>
	</div>
</div>

<div class="da-panel collapsible" style="width:850px;">
	<div class="da-panel-header">
		<span class="da-panel-title">
			<img src="images/icons/black/16/list.png" alt="" />
			<span lang="es">Listado Departamentos/Sucursales</span>
		</span>
	</div>
	<div class="da-panel-content">
		<table class="da-table">
			<thead>
				<tr>
					<th><b>Departamento/Sucursal</b></th>
					<th><b>Codigo</b></th>
					<th style="text-align:center;"><b><span lang="es">Tipo CI</span></b></th>
					<th style="text-align:center;"><b><span lang="es">Tipo Regional</span></b></th>
					<th style="text-align:center;"><b><span lang="es">Tipo Departamento</span></b></th>
					<th style="text-align:center;"><b><span lang="es">Entidad Financiera</span></b></th>
					<th style="text-align:center;"><b><span lang="es">Acción</span></b></th>
				</tr>
			</thead>
			<tbody>';
			  $num = $res->num_rows;
			  if($num>0){
					while($regi = $res->fetch_array(MYSQLI_ASSOC)){
						echo'<tr>
								<td>'.$regi['departamento'].'</td>
								<td>'.$regi['codigo'].'</td>
								<td style="text-align:center;">';
								$select_ci="select 
												count(id_depto) as numr
											from
												s_departamento
											where
												id_depto = ".$regi['id_depto']." and id_ef is null;";
								 $resci = $conexion->query($select_ci,MYSQLI_STORE_RESULT); 				
								 $rci = $resci->fetch_array(MYSQLI_ASSOC);
								 $resci->free();
								 if($rci['numr']>0){
									if($regi['tipo_ci']==1){
										if($tipo_sesion=='ROOT'){
									        echo'<input type="checkbox" name="tipoci" class="tipoci" id="ci-'.$regi['id_depto'].'" value="'.$regi['id_depto'].'" checked/>';
										}else{
											echo'<input type="checkbox" name="tipoci" class="tipoci" id="ci-'.$regi['id_depto'].'" value="'.$regi['id_depto'].'" checked disabled/>';
										}
									}else{
										if($tipo_sesion=='ROOT'){
									        echo'<input type="checkbox" name="tipoci" class="tipoci" id="ci-'.$regi['id_depto'].'" value="'.$regi['id_depto'].'"/>';	
										}else{
											echo'<input type="checkbox" name="tipoci" class="tipoci" id="ci-'.$regi['id_depto'].'" value="'.$regi['id_depto'].'" disabled/>';	
										}
									}
								 }else{
									 echo'<input type="checkbox" name="tipoci" class="tipoci" disabled/>';	
								 }
						   echo'</td>
						        <td style="text-align:center;">';
								$select_re="select 
												count(id_depto) as numr
											from
												s_departamento
											where
												id_depto = ".$regi['id_depto']." and id_ef is not null;";
								 $resp = $conexion->query($select_re,MYSQLI_STORE_RESULT);				
								 $re = $resp->fetch_array(MYSQLI_ASSOC);
								 $resp->free();
								 if($re['numr']>0){		
									if($regi['tipo_re']==1){
									 echo'<input type="checkbox" name="tiporeg" class="tiporeg" id="reg-'.$regi['id_depto'].'" value="'.$regi['id_depto'].'" checked/>';
									}else{
										echo'<input type="checkbox" name="tiporeg" class="tiporeg" id="reg-'.$regi['id_depto'].'" value="'.$regi['id_depto'].'"/>';
									}
								 }else{
									 echo'<input type="checkbox" name="tiporeg" class="tiporeg" disabled/>';
								 }
						   echo'</td>
						        <td style="text-align:center;">';
								 $select_dp="select 
												count(id_depto) as numr
											from
												s_departamento
											where
												id_depto = ".$regi['id_depto']." and id_ef is null
													and codigo != 'PE';";
							     $resdp = $conexion->query($select_dp,MYSQLI_STORE_RESULT);						
								 $rdp = $resdp->fetch_array(MYSQLI_ASSOC);
								 $resdp->free();					
								 if($rdp['numr']>0){ 
									 if($regi['tipo_dp']==1){
										 if($tipo_sesion=='ROOT'){
										     echo'<input type="checkbox" name="tipodepto" class="tipodepto" id="dep-'.$regi['id_depto'].'" value="'.$regi['id_depto'].'" checked/>';
										 }else{
											 echo'<input type="checkbox" name="tipodepto" class="tipodepto" id="dep-'.$regi['id_depto'].'" value="'.$regi['id_depto'].'" checked disabled/>';
										 }
									 }else{
										 if($tipo_sesion=='ROOT'){
										    echo'<input type="checkbox" name="tipodepto" class="tipodepto" id="dep-'.$regi['id_depto'].'" value="'.$regi['id_depto'].'"/>'; 
										 }else{
											echo'<input type="checkbox" name="tipodepto" class="tipodepto" id="dep-'.$regi['id_depto'].'" value="'.$regi['id_depto'].'" disabled/>';  
										 }  
									 }
								 }else{
									 echo'<input type="checkbox" name="tipodepto" class="tipodepto" disabled/>'; 
								 }	 
						   echo'</td>
						        <td>';
								  if($regi['tipo_re']==1){
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
								  }else{
									echo'&nbsp;';  
								  }
						   echo'</td>
								<td class="da-icon-column">
								   <ul class="action_user">';
									  echo'<li style="margin-right:5px;">';
									        if($tipo_sesion=='ROOT'){
									            echo'<a href="adicionar_registro.php?opcion=editar_sucursal&id_depto='.base64_encode($regi['id_depto']).'&tipo_sesion='.base64_encode($tipo_sesion).'&id_ef_sesion='.base64_encode($id_ef_sesion).'" class="edit da-tooltip-s cuestionario fancybox.ajax" title="<span lang=\'es\'>Editar</span>"></a>';
											}elseif($regi['tipo_re']==1 and $tipo_sesion=='ADM'){
												echo'<a href="adicionar_registro.php?opcion=editar_sucursal&id_depto='.base64_encode($regi['id_depto']).'&tipo_sesion='.base64_encode($tipo_sesion).'&id_ef_sesion='.base64_encode($id_ef_sesion).'" class="edit da-tooltip-s cuestionario fancybox.ajax" title="<span lang=\'es\'>Editar</span>"></a>';
											}
									  echo'</li>';
										   //VERIFICAMOS SI LA SUCURSAL NO TIENE AGENCIAS
										   $busca="select
													  count(id_agencia) as cont
													from
													  s_agencia
													where
													  id_depto=".$regi['id_depto'].";";
										   $resbusca = $conexion->query($busca,MYSQLI_STORE_RESULT);			  
										   $rgbusca = $resbusca->fetch_array(MYSQLI_ASSOC);
										   $resbusca->free();
										   if($rgbusca['cont']==0){	  
									  //echo'<li><a href="#" class="eliminar da-tooltip-s" title="Eliminar" id="'.$regi['id_depto'].'"></a></li>';
										   }else{
											 //echo'<li style="margin-left:10px;">&nbsp;</li>';   
										   }
							  echo'</ul>	
								</td>
							</tr>';
					}			
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

?>