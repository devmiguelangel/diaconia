<?php
/*Cuztomizer*/
function customizer($tipo_sesion){
	if($tipo_sesion=='ROOT'){
		  /*echo'<div id="da-customizer-content">
					<ul>
						<li>
							<span class="da-customizer-title">Modelo de fondo</span>
							<span id="da-customizer-body-bg"></span>
						</li>
						<li>
							<span>Patr&oacute;n cabecera</span>
							<span id="da-customizer-header-bg"></span>
						</li>
					</ul>
					
				</div>
				<span id="da-customizer-pulldown"></span>';*/
	}
}

/*Logo Container*/
function logo_container($tipo_sesion,$id_ef_sesion,$id_usuario_sesion,$conexion){
   if($tipo_sesion!='ROOT'){
	   $select="select
				  su.id_usuario,
				  su.usuario,
				  su.id_tipo,
				  ut.tipo,
				  ef.nombre,
				  ef.logo
				from
				  s_usuario as su
				  inner join s_usuario_tipo as ut on (ut.id_tipo=su.id_tipo)
				  inner join s_ef_usuario as efu on (efu.id_usuario=su.id_usuario)
				  inner join s_entidad_financiera as ef on (ef.id_ef=efu.id_ef)
				where
				  su.id_usuario='".$id_usuario_sesion."' and efu.id_ef='".$id_ef_sesion."';";
	   $resu = $conexion->query($select,MYSQLI_STORE_RESULT);
	   $regi = $resu->fetch_array(MYSQLI_ASSOC);
	   $logo_entidad = '<img src="../images/'.$regi['logo'].'" alt="'.$regi['nombre'].' Admin"/>'; 			  
   }else{
	   $logo_entidad = '<img src="images/sibas-logo.png" alt="Sibas Admin"/>'; 
   }
   
   echo'<div id="da-logo">
			<div id="da-logo-img">
				<a href="#">
					'.$logo_entidad.'
				</a>
			</div>
		</div>';	
}
/*Header Toolbar Menu*/
function header_toolbar_menu($id_usuario_sesion,$tipo_sesion,$usuario_sesion,$conexion){
	$selectUsu="select
				  su.id_usuario,
				  su.usuario,
				  su.nombre,
				  sut.tipo
				from
				  s_usuario as su
				  inner join s_usuario_tipo sut on (sut.id_tipo=su.id_tipo)
				where
				  su.id_usuario='".$id_usuario_sesion."' and su.usuario='".$usuario_sesion."' and sut.codigo='".$tipo_sesion."'
				limit
				  0,1;";
  $resu = $conexion->query($selectUsu,MYSQLI_STORE_RESULT);				  
  $reg = $resu->fetch_array(MYSQLI_ASSOC);	
  echo'<div id="da-user-profile">
		<div id="da-user-avatar">
			<img src="images/icons/white/32/user_2.png" alt="Charts"/>
		</div>
		<div id="da-user-info">
			'.$reg['nombre'].'
			<span class="da-user-title" lang="es">'.$reg['tipo'].'</span>
		</div>
		<ul class="da-header-dropdown">
			<li class="da-dropdown-caret">
				<span class="caret-outer"></span>
				<span class="caret-inner"></span>
			</li>
			<li class="da-dropdown-divider"></li>
			<li lang="es"><a href="?l=escritorio">Inicio</a></li>
			<li class="da-dropdown-divider"></li>
			<li lang="es"><a href="?l=usuarios&editar=v&idusuario='.base64_encode($reg['id_usuario']).'">Editar Datos</a></li>
			<li lang="es"><a href="?l=usuarios&cpass=v&idusuario='.base64_encode($reg['id_usuario']).'">Cambiar Contraseña</a></li>
		</ul>
	</div>';
?>	<!--
	<div id="da-user-profile">
	   <div id="da-user-info">
	     <span class="da-user-title" lang="es">Idioma</span>
	   </div>
	   <ul class="da-header-dropdown">
		 <li class="da-dropdown-caret">
			<span class="caret-outer"></span>
			<span class="caret-inner"></span>
		 </li>
		 <li><a href="#" id="es" class="language" onclick="window.lang.change('es'); return false;">Español</a></li>
		 <li class="da-dropdown-divider"></li>
		 <li><a href="#" id="en" class="language" onclick="window.lang.change('en'); return false;">English(US)</a></li>
	   </ul>	 
	</div>
    -->
<?php    
echo'<div id="da-header-button-container">
		<ul>
		<!-- NOTIFICACIONES
			<li class="da-header-button notif">
				<span class="da-button-count">32</span>
				<a href="#">Notifications</a>
				<ul class="da-header-dropdown">
					<li class="da-dropdown-caret">
						<span class="caret-outer"></span>
						<span class="caret-inner"></span>
					</li>
					<li>
						<span class="da-dropdown-sub-title">Notifications</span>
						<ul class="da-dropdown-sub">
							<li class="unread">
								<a href="#">
									<span class="message">
										Lorem ipsum dolor sit amet consectetur adipiscing elit, et al commore
									</span>
									<span class="time">
										January 21, 2012
									</span>
								</a>
							</li>
							<li class="unread">
								<a href="#">
									<span class="message">
										Lorem ipsum dolor sit amet
									</span>
									<span class="time">
										January 21, 2012
									</span>
								</a>
							</li>
							<li class="read">
								<a href="#">
									<span class="message">
										Lorem ipsum dolor sit amet
									</span>
									<span class="time">
										January 21, 2012
									</span>
								</a>
							</li>
							<li class="read">
								<a href="#">
									<span class="message">
										Lorem ipsum dolor sit amet
									</span>
									<span class="time">
										January 21, 2012
									</span>
								</a>
							</li>
						</ul>
						<a class="da-dropdown-sub-footer">
							View all notifications
						</a>
					</li>
				</ul>
			</li>
			<li class="da-header-button message">
				<span class="da-button-count">5</span>
				<a href="#">Messages</a>
				<ul class="da-header-dropdown">
					<li class="da-dropdown-caret">
						<span class="caret-outer"></span>
						<span class="caret-inner"></span>
					</li>
					<li>
						<span class="da-dropdown-sub-title">Messages</span>
						<ul class="da-dropdown-sub">
							<li class="unread">
								<a href="#">
									<span class="message">
										Lorem ipsum dolor sit amet consectetur adipiscing elit, et al commore
									</span>
									<span class="time">
										January 21, 2012
									</span>
								</a>
							</li>
							<li class="unread">
								<a href="#">
									<span class="message">
										Lorem ipsum dolor sit amet
									</span>
									<span class="time">
										January 21, 2012
									</span>
								</a>
							</li>
							<li class="read">
								<a href="#">
									<span class="message">
										Lorem ipsum dolor sit amet
									</span>
									<span class="time">
										January 21, 2012
									</span>
								</a>
							</li>
							<li class="read">
								<a href="#">
									<span class="message">
										Lorem ipsum dolor sit amet
									</span>
									<span class="time">
										January 21, 2012
									</span>
								</a>
							</li>
						</ul>
						<a class="da-dropdown-sub-footer">
							View all messages
						</a>
					</li>
				</ul>
			</li> -->
			<li class="da-header-button logout">
				<!--SALIR-->
				<a href="sgc_logout.php" class="da-tooltip-n da-customizer-tooltip" title="Salir" lang="es">Salir</a>
			</li>
		</ul>
	</div>';                    
}

//HEADER BOTTOM
function header_bottom($ini,$crumbs,$cant){
   echo'<!-- Container -->
		<div class="da-container clearfix">          	                	
			<!-- Breadcrumbs -->
			<div id="da-breadcrumb">
				<ul>';
				    if($crumbs!=''){
						echo'<li><a href="?l=escritorio" lang="es"><img src="images/icons/black/16/home.png" alt="Home" /> Inicio</a></li>';
						if($crumbs=='s'){
							echo'<li class="active"><span lang="es">Diapositiva</span></li>';
						}elseif($crumbs=='c'){
							echo'<li class="active"><span lang="es">Contenido</span></li>';
						}elseif($crumbs=='f'){
							echo'<li class="active"><span lang="es">Formulario</span></li>';
						}elseif($crumbs=='cs'){
							echo'<li class="active"><span lang="es">Compañía de Seguros</span></li>';
						}elseif($crumbs=='de'){
							echo'<li class="active"><span lang="es">Desgravamen</span></li>';
						}elseif($crumbs=='dt'){
							echo'<li class="active"><span lang="es">Administración Datos Desgravamen</span></li>';
						}elseif($crumbs=='pl'){
							echo'<li class="active"><span lang="es">Administración Pólizas</span></li>';
						}elseif($crumbs=='oc'){
							echo'<li class="active"><span lang="es">Administración Ocupación</span></li>';
						}elseif($crumbs=='cert'){
							echo'<li class="active"><span lang="es">Certificado Médico</span></li>';
						}elseif($crumbs=='em'){
							echo'<li class="active"><span lang="es">Administración Correos</span></li>';
						}elseif($crumbs=='age'){
							echo'<li class="active"><span lang="es">Agencias</span></li>';
						}elseif($crumbs=='suc'){
							echo'<li class="active"><span lang="es">Sucursales</span></li>';
						}elseif($crumbs=='enti'){
							echo'<li class="active"><span lang="es">Entidades Financieras</span></li>';
						}elseif($crumbs=='adcia'){
							echo'<li class="active"><span lang="es">Compañías agregadas a Entidades</span></li>';
						}elseif($crumbs=='cab'){
							echo'<li class="active"><span lang="es">Cabecera</span></li>';
						}elseif($crumbs=='au'){
							echo'<li class="active"><span lang="es">Automotores</span></li>';
						}elseif($crumbs=='nos'){
							echo'<li class="active"><span lang="es">Contenido Nosotros</span></li>';
						}elseif($crumbs=='fp'){
							echo'<li class="active"><span lang="es">Forma de Pago</span></li>';
						}elseif($crumbs=='est'){
							echo'<li class="active"><span lang="es">Estados</span></li>';
						}elseif($crumbs=='trd'){
							echo'<li class="active"><span lang="es">Todo Riesgo Domiciliario</span></li>';
						}elseif($crumbs=='trem'){
							echo'<li class="active"><span lang="es">Ramos Técnicos</span></li>';
						}elseif($crumbs=='tcm'){
							echo'<li class="active"><span lang="es">Tipo de Cambio Moneda</span></li>';
						}elseif($crumbs=='pe'){
							echo'<li class="active"><span lang="es">Producto Extra</span></li>';
						}elseif($crumbs=='th'){
							echo'<li class="active"><span lang="es">Tarjetahabiente</span></li>';
						}elseif($crumbs=='mod'){
							echo'<li class="active"><span lang="es">Modalidad</span></li>';
						}
					}else{
					   echo'<li class="active"><span lang="es"><img src="images/icons/black/16/home.png" alt="Home" />Inicio</span></li>';	
					}
		   echo'</ul>
			</div> 
		</div>';	
}

//FOOTER
function footer(){
  echo'<div class="da-container clearfix">
           <p>Copyright 2013. SIBAS SRL. All Rights Reserved.
       </div>';	
}                        
?>