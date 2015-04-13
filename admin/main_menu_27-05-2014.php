<?php
/*MENU DE NAVEGACION LATERAL IZQUIERDO*/
function main_navegation($link_page,$id_usuario_sesion,$tipo_sesion,$usuario_sesion,$conexion){

		//INICIAMOS VARIABLES
		$inicio=false;
		$usuarios=false;
		$formularios=false;
		$compania=false;
		$desgravamen=false;
		$poliza=false;
		$none=false;
		$faculde=false;
		$admindatos=false;
		$email=false;
		$agency=false;
		$autos=false;
		$triesgod=false;
		$triesgoeqm=false;
		$formpago=false;
		$estado=false;
		$certmedico=false;
		$sucursal=false;
		$tipcambio=false;
		$tarjetahabiente=false;
		
		
		if($tipo_sesion!='ROOT'){
				//NO ES DE TIPO ROOT ENTONCES SACAMOS LOS PERMISOS DEL USUARIO
				$selectP="select distinct
						su.id_usuario,
						su.usuario,
						su.id_tipo,
						su.activado,
						sup.id_permiso,
						sup.pagina
					  from
						s_usuario as su
						inner join s_usuario_permiso as sup on (sup.id_usuario=su.id_usuario)
						inner join s_usuario_tipo as sut on (sut.id_tipo=su.id_tipo)
					  where
						su.id_usuario='".$id_usuario_sesion."' and su.usuario='".$usuario_sesion."' and sut.codigo='".$tipo_sesion."';";
				$resup = $conexion->query($selectP,MYSQLI_STORE_RESULT);
				$num = $resup->num_rows;
				if($num>0){
					
						while($regi = $resup->fetch_array(MYSQLI_ASSOC)){
							switch($regi['pagina']) {
								case 'inicio':       //EDITAR SLIDESHOW/CONTENIDO
									$inicio = true;
									break;
								case 'creausuario':   //ADMINISTRAR USUARIOS
									$usuarios = true;
									break;
								case 'formularios':   //ADMINISTRAR DESCARGAS FORMULARIOS
									$formularios = true;
									break;				
								case 'compania':   //ADMINISTRAR COMPAÑIAS DE SEGUROS
									$compania = true;
									break;
								case 'desgravamen': //ADMINISTRAR DESGRAVAMEN
									$desgravamen = true;
									break;
								case 'poliza':   //ADMINISTRAR POLIZAS
									$poliza = true;
									break;
								case 'admindatos':     //GENERAR REPORTES PERSONAS PRODUCTOS
									$admindatos = true;
									break;		
								case 'facultativode': //GENERAR REPORTES FACULTATIVO DESGRAVAMEN
								    $faculde = true;
									break;
								case 'email': //ADMINISTRAR CORREOS ELECTRONICOS
									$email = true;
									break;
								case 'sucursal': //ADMINISTRAR CORREOS ELECTRONICOS
									$sucursal = true;
									break;
								case 'agencia': //ADMINISTRAR CORREOS ELECTRONICOS
									$agency = true;
									break;
								case 'formapago': //ADMINISTRAR FORMA DE PAGOS
									$formpago = true;
									break;
								case 'estados': //ADMINISTRAR ESTADOS
									$estado = true;
									break;	
								case 'automotores': //ADMINISTRAR AUTOMOTORES
									$autos = true;
									break;
								case 'todoriesgod': //ADMINISTRAR TODORIEAGO DOMICILIARIO
									$triesgod = true;
									break;
								case 'triesgoeqmov': //ADMINISTRAR TODORIESGO EQUIPO MOVIL
									$triesgoeqm = true;
									break;
								case 'certificadomedico': //CREAR CERETIFICADO MEDICO 
									$certmedico = true;
									break;
								case 'tipocambio': //ADMINISTRAR TIPO DE CAMBIO
									$tipcambio = true;
									break;
								case 'tarjetahabiente': //TRAJETAHABIENTE
									$tarjetahabiente = true;
									break;							
							}
						}
						
					 echo'<ul>';
					   if($inicio){
						   if($link_page=='escritorio' or $link_page=='slideshow' or $link_page=='contenidohome'){
								$data='class="active"';	
							}else{$data='';}
						   echo'<li '.$data.'>
									<a href="#">
										<span class="da-nav-icon">
											<img src="images/icons/black/32/header.png" alt="Dashboard" />
										</span>
										Inicio
									</a>';
									 switch($link_page){
										 case "slideshow" :
											 if($link_page=='slideshow'){
												  $close=''; $closea='';
												  $closeb='class="closed"'; 
												  break;
											 }else{
												  $close='class="closed"'; $closea='class="closed"';
												  $closeb=''; 
												  break;
											 }
										  case "contenidohome" :
											 if($link_page=='contenidohome'){
												  $close=''; $closeb='';
												  $closea='class="closed"'; 
												  break;
											 }else{
												  $close='class="closed"'; $closeb='class="closed"';
												  $closea=''; 
												  break;
											 }	 
										 default :
											$close='class="closed"';
											$closea='class="closed"';
											$closeb='class="closed"';
											break;	  
									 }	
							   echo'<ul '.$close.'> 
										<li>
										  <a href="#">Slideshow</a>
										   <ul '.$closea.'>
											   <li style="padding-left:10px;">
												 <a href="?l=slideshow&var=s">Listar imagenes</a>
											   </li>
											   <li style="padding-left:10px;">
												 <a href="?l=slideshow&var=s&crear=v">Agregar imagen</a>
											   </li>
										   </ul>
										</li>     
										<li>
										   <a href="#">Contenido home</a>
										   <ul '.$closeb.'>
											   <li style="padding-left:10px;"><a href="?l=contenidohome&var=c">Listar contenido</a></li>
											   <li style="padding-left:10px;"><a href="?l=contenidohome&var=nos&list_nosotros=v">Nosotros</a></li>
											   <li style="padding-left:10px;"><a href="?l=contenidohome&var=prgf&list_preg_frec=v">Preguntas Frecuentes</a></li>
										   </ul> 
										</li>
									</ul>
								</li>';
					   }
					   if($tipcambio){
						   if($link_page=='tipocambio'){
								$data='class="active"';	$close='';
							}else{$data=''; $close='class="closed"';}
						   echo'<li '.$data.'>
									<a href="#">
										<!-- Icon Container -->
										<span class="da-nav-icon">
											<img src="images/icons/black/32/moneda.png" alt="Agencia" />
										</span>
										Tipo de Cambio 
									</a>
									<ul '.$close.'>
										<li><a href="?l=tipocambio&var=tcm">Tipo Cambio Moneda</a></li>
									</ul>
								</li>';
					   }
					   if($desgravamen){    //DESGRAVAMEN
						   if($link_page=='des_producto' or $link_page=='des_preguntas' or 
							  $link_page=='des_tasas' or $link_page=='des_datos' or 
							  $link_page=='des_contenido' or $link_page=='des_producto_extra'){
								$data='class="active"'; $close='';
						   }else{$data=''; $close='class="closed"';}
						   echo'<li '.$data.'>
									<a href="#">
										<span class="da-nav-icon">
											<img src="images/icons/black/32/product.png" alt="Form" />
										</span>
										Desgravamen
									</a>
									<ul '.$close.'>
									  <li><a href="?l=des_datos&var=de">Administrar parametros del Producto</a></li>
									  <li><a href="?l=des_producto&var=de&list_producto=v">Administrar Producto Crediticio</a></li>
									  <li><a href="?l=des_preguntas&var=de&list_compania=v">Administrar preguntas</a></li>
									  <li><a href="?l=des_contenido&var=de">Administrar contenido</a></li>';
										//echo'<li><a href="?l=des_producto_extra&var=pe&list_compania=v">Administrar producto extra</a></li>';
							   echo'</ul>
								</li>';
					   }
					   if($autos){       //AUTOMOTORES
						   if($link_page=='au_tipovehiculo' or $link_page=='au_tasas' or $link_page=='au_marca_modelo' 
							   or $link_page=='au_montos' or $link_page=='au_contenido'){
								$data='class="active"'; $close='';
						   }else{$data=''; $close='class="closed"';}
						   echo'<li '.$data.'>
									<a href="#">
										<span class="da-nav-icon">
											<img src="images/icons/black/32/product.png" alt="Form" />
										</span>
										Automotores
									</a>
									<ul '.$close.'>
										<li><a href="?l=au_montos&var=au">Administrar montos</a></li>
										<li><a href="?l=au_tasas&var=au&list_compania=v">Administrar tasas</a></li>
										<li><a href="?l=au_tipovehiculo&var=au">Administrar tipo vehículo</a></li>
										<li><a href="?l=au_marca_modelo&var=au&list_marca=v">Administrar marca/modelo</a></li>
										<li><a href="?l=au_contenido&var=au">Administrar contenido</a></li>
									</ul>
								</li>';
					   }
					   if($triesgod){    //TODORIEASGO DOMICILIARIO
						   if($link_page=='tr_tasas' or $link_page=='tr_montos' or $link_page=='tr_contenido'){
								$data='class="active"'; $close='';
						   }else{$data=''; $close='class="closed"';}
						   echo'<li '.$data.'>
									<a href="#">
										<span class="da-nav-icon">
											<img src="images/icons/black/32/product.png" alt="Form" />
										</span>
										Todo Riesgo Domiciliario
									</a>
									<ul '.$close.'>
										<li><a href="?l=tr_montos&var=trd">Administrar montos</a></li>
										<li><a href="?l=tr_tasas&var=trd&list_compania=v">Administrar tasas</a></li>
										<li><a href="?l=tr_contenido&var=trd">Administrar contenido</a></li>
									</ul>
								</li>';
					   }
					   if($triesgoeqm){  //TODORIESGO EQUIPO MOVIL
						   if($link_page=='trem_tasas' or $link_page=='trem_montos' or $link_page=='trem_contenido'){
								$data='class="active"'; $close='';
						   }else{$data=''; $close='class="closed"';}
						   echo'<li '.$data.'>
									<a href="#">
										<span class="da-nav-icon">
											<img src="images/icons/black/32/product.png" alt="Form" />
										</span>
										Todo Riesgo Equipo Movil
									</a>
									<ul '.$close.'>
										<li><a href="?l=trem_montos&var=trem">Administrar montos</a></li>
										<li><a href="?l=trem_tasas&var=trem&list_compania=v">Administrar tasas</a></li>
										<li><a href="?l=trem_contenido&var=trem">Administrar contenido</a></li>
									</ul>
								</li>';
					   }
					   if($tarjetahabiente){//TARJETA HABIENTE
						   if($link_page=='th_marcatarjeta' or $link_page=='th_tipotarjeta' or 
							  $link_page=='th_primastarjeta' or $link_page=='th_contenido' or 
							  $link_page=='th_montos'){
								$data='class="active"'; $close='';
						   }else{$data=''; $close='class="closed"';}
						   echo'<li '.$data.'>
									<a href="#">
										<span class="da-nav-icon">
											<img src="images/icons/black/32/product.png" alt="Form" />
										</span>
										Tarjetahabiente
									</a>
									<ul '.$close.'>
										<li><a href="?l=th_montos&var=th">Administrar montos</a></li>
										<li><a href="?l=th_tipotarjeta&var=th&list_producto=v">Administrar tarjeta</a></li>
										<li><a href="?l=th_marcatarjeta&var=th&list_compania=v">Administrar marca</a></li>
										<li><a href="?l=th_primastarjeta&var=th&list_compania=v">Administrar primas</a></li>
										<li><a href="?l=th_contenido&var=th">Administrar contenido</a></li>
									</ul>
								</li>';
					   }
					   if($usuarios){
						   if($link_page=='usuarios_admin'){
								$data='class="active"'; $close='';
						   }else{$data=''; $close='class="closed"';}
						   echo'<li '.$data.'>
									<a href="#">
										<span class="da-nav-icon">
											<img src="images/icons/black/32/users_2.png" alt="Calendar" />
										</span>
										Usuarios
									</a>
									<ul '.$close.'>
										<li><a href="?l=usuarios_admin">Listar usuarios</a></li>';
										//echo'<li><a href="?l=usuarios_admin&crear=v">Crear usuario</a></li>';
							   echo'</ul>
								</li>';
					   }
					   if($poliza){
						   if($link_page=='des_poliza'){
							   $data='class="active"'; $close=''; 
						   }else{$data=''; $close='class="closed"';}
						   echo'<li '.$data.'>
									<a href="#">
										<span class="da-nav-icon">
											<img src="images/icons/black/32/admin.png" alt="Form" />
										</span>
										Administrar Poliza
									</a>
									<ul '.$close.'>
										<li><a href="?l=des_poliza&var=pl">Listar polizas</a></li>
									</ul>
								</li>';
					   }
					   if($admindatos){
						   if($link_page=='ocupacion'){
								$data='class="active"';	
							}else{$data='';}
						   echo'<li '.$data.'>
									<a href="#">
										<!-- Icon Container -->
										<span class="da-nav-icon">
											<img src="images/icons/black/32/admin.png" alt="Form" />
										</span>
										Administrar Ocupacion
									</a>';
										  switch($link_page){
											 case "ocupacion" :
												 if($link_page=='ocupacion'){$closea=''; break;}
												 else{$closea='class="closed"'; break;}
											 default :
												$closea='class="closed"';
												break;	  
										 }	
							   echo'<ul '.$closea.'>
										<li>
										   <a href="#">Ocupacion</a>
										   <ul '.$closea.'>
											   <li style="padding-left:10px;">
												 <a href="?l=ocupacion&var=oc">Listar registros</a>
											   </li>
										   </ul> 
										</li>
										<!--<li>
										   <a href="#">Facultativos 2</a>
										   <ul class="closed">
											   <li style="padding-left:10px;"><a href="#">pregunta 2</a></li>
											   <li style="padding-left:10px;"><a href="#">Agregar 2</a></li>
										   </ul> 
										</li>-->
									</ul>
								</li>';   
					   }
					   if($formpago){
						   if($link_page=='formapago'){
								$data='class="active"';	
							}else{$data='';}
						   echo'<li '.$data.'>
									<a href="#">
										<!-- Icon Container -->
										<span class="da-nav-icon">
											<img src="images/icons/black/32/admin.png" alt="Form" />
										</span>
										Administrar Forma Pago
									</a>';
										  switch($link_page){
											 case "formapago" :
												 if($link_page=='formapago'){$closea=''; break;}
												 else{$closea='class="closed"'; break;}
											 default :
												$closea='class="closed"';
												break;	  
										 }	
							   echo'<ul '.$closea.'>
										<li>
										   <a href="#">Forma Pago</a>
										   <ul '.$closea.'>
											   <li style="padding-left:10px;">
												 <a href="?l=formapago&var=fp">Listar registros</a>
											   </li>
										   </ul> 
										</li>
									</ul>
								</li>';   
					   }
					   if($estado){
						   if($link_page=='estados'){
								$data='class="active"';	
							}else{$data='';}
						   echo'<li '.$data.'>
									<a href="#">
										<!-- Icon Container -->
										<span class="da-nav-icon">
											<img src="images/icons/black/32/admin.png" alt="Form" />
										</span>
										Administrar Estados
									</a>';
										  switch($link_page){
											 case "estados" :
												 if($link_page=='estados'){$closea=''; break;}
												 else{$closea='class="closed"'; break;}
											 default :
												$closea='class="closed"';
												break;	  
										 }	
							   echo'<ul '.$closea.'>
										<li>
										   <a href="#">Estados</a>
										   <ul '.$closea.'>
											   <li style="padding-left:10px;">
												 <a href="?l=estados&var=est">Listar registros</a>
											   </li>
										   </ul> 
										</li>
									</ul>
								</li>';   
					   }
					   if($certmedico){
						   if($link_page=='certmedico'){
								$data='class="active"';	$close='';
							}else{$data=''; $close='class="closed"';}
						   echo'<li '.$data.'>
									<a href="#">
										<!-- Icon Container -->
										<span class="da-nav-icon">
											<img src="images/icons/black/32/certificado.png" alt="Certificado Medico" />
										</span>
										Certificado Medico
									</a>
									<ul '.$close.'>
										<li><a href="?l=certmedico&var=cert">Listar Certificados</a></li>
									</ul>
								</li>';
					   }
					   if($email){
						   if($link_page=='email'){
								$data='class="active"';	$close='';
							}else{$data=''; $close='class="closed"';}
						   echo'<li '.$data.'>
									<a href="#">
										<!-- Icon Container -->
										<span class="da-nav-icon">
											<img src="images/icons/black/32/email.png" alt="Administrar correos" />
										</span>
										Administrar Correos
									</a>
									<ul '.$close.'>
										<li><a href="?l=email&var=em">Administrar correos</a></li>
									</ul>
								</li>';
					   }
					   if($sucursal){
						   if($link_page=='sucursal'){
								$data='class="active"';	$close='';
							}else{$data=''; $close='class="closed"';}
						   echo'<li '.$data.'>
									<a href="#">
										<!-- Icon Container -->
										<span class="da-nav-icon">
											<img src="images/icons/black/32/sucursal.png" alt="Agencia" />
										</span>
										Departamentos/<br/>Sucursales
									</a>
									<ul '.$close.'>
										<li><a href="?l=sucursal&var=suc&listarsuc=v">Listar Departamentos<br/>Sucursales</a></li>
									</ul>
								</li>';
					   }
					   if($agency){
						   if($link_page=='agencia'){
								$data='class="active"';	$close='';
							}else{$data=''; $close='class="closed"';}
						   echo'<li '.$data.'>
									<a href="#">
										<!-- Icon Container -->
										<span class="da-nav-icon">
											<img src="images/icons/black/32/agencia.png" alt="Agencia" />
										</span>
										Agencias
									</a>
									<ul '.$close.'>
										<li><a href="?l=agencia&var=age">Listar Agencias</a></li>
									</ul>
								</li>';
					   }
					   if($formularios){
						   if($link_page=='archivos'){
								$data='class="active"'; $close='';
						   }else{$data=''; $close='class="closed"';}
						   echo'<li '.$data.'>
									<a href="#">
										<span class="da-nav-icon">
											<img src="images/icons/black/32/file_pdf.png" alt="Form" />
										</span>
										Formularios
									</a>
									<ul '.$close.'>
										<li><a href="?l=archivos&var=f">Listar formularios</a></li>
										<li><a href="?l=archivos&crear=v&var=f">Agregar formulario</a></li>
									</ul>
								</li>';
					   }
					   if($none){
						   echo'<li>
									<a href="table.html">
										<!-- Icon Container -->
										<span class="da-nav-icon">
											<img src="images/icons/black/32/table_1.png" alt="Table" />
										</span>
										Table
									</a>
								</li>';
					   }
					   if($none){
						   echo'<li>
									<a href="#">
										<!-- Icon Container -->
										<span class="da-nav-icon">
											<img src="images/icons/black/32/create_write.png" alt="Form" />
										</span>
										Form
									</a>
									<ul>
										<li><a href="form-layouts.html">Layouts</a></li>
										<li><a href="form-elements.html">Elements</a></li>
										<li><a href="form-validation.html">Validation</a></li>
									</ul>
								</li>';
					   }
					   if($none){		
						   echo'<li>
									<a href="ui.html">
										<!-- Icon Container -->
										<span class="da-nav-icon">
											<img src="images/icons/black/32/settings.png" alt="" />
										</span>
										UI Elements
									</a>
								</li>';
					   }
					   if($none){
						   echo'<li>
									<a href="widgets.html">
										<!-- Icon Container -->
										<span class="da-nav-icon">
											<img src="images/icons/black/32/cog_4.png" alt="Widgets" />
										</span>
										Widgets
									</a>
								</li>';
					   }
					   if($none){
						   echo'<li>
									<a href="#">
										<!-- Icon Container -->
										<span class="da-nav-icon">
											<img src="images/icons/black/32/word_documents_1.png" alt="Layout and Typography" />
										</span>
										Layout and Typography
									</a>
									<ul class="closed">
										<li><a href="grids.html">Grids and Panels</a></li>
										<li><a href="typography.html">Typography</a></li>
									</ul>
								</li>';
					   }
					   if($none){
						   echo'<li>
									<a href="gallery.html">
										<!-- Icon Container -->
										<span class="da-nav-icon">
											<img src="images/icons/black/32/images_2.png" alt="Gallery" />
										</span>
										Gallery
									</a>
								</li>';
					   }
					   if($none){
						   echo'<li>
									<a href="error.html">
										<!-- Icon Container -->
										<span class="da-nav-icon">
											<img src="images/icons/black/32/alert.png" alt="Error Pages" />
										</span>
										Error Page (404)
									</a>
								</li>';
					   }
					   if($none){
						   echo'<li>
									<a href="icons.html">
										<!-- Icon Container -->
										<span class="da-nav-icon">
											<img src="images/icons/black/32/pacman.png" alt="Icons" />
										</span>
										Icons
									</a>
								</li>';
					   }
					  echo'</ul>';	
				}else{
				  echo $mensaje="Hubo un error al consultar los datos, consulte con su administrador "."\n ".$conexion->errno. ": " . $conexion->error;
					
				}
		}else{
			  //ES EL ROOT TENDRA TODO EL ACCESO
			  $listado = array("cabecera", "entidadfin", "inicio", "creausuario", "formularios", "compania", "desgravamen", "poliza", "facultativode", "admindatos", "certificadomedico", "email", "agencia", "agregaciaef", "sucursal", "automotores", "formapago", "estados", "todoriesgod", "triesgoeqmov", "tipocambio", "tarjetahabiente", "modalidad");
			  foreach($listado as $acceso){
					switch($acceso) {
						case 'cabecera':  //ADMINISTRAR CABECERA
						    $header = true; 
							break;
						case 'entidadfin':  //ADMINISTRAR ENTIDADES FINANCIERAS
						    $entidadfin = true; 
							break;
						case 'inicio':     //EDITAR SLIDESHOW/CONTENIDO
							$inicio = true;
							break;
						case 'creausuario':   //ADMINISTRAR USUARIOS
							$usuarios = true;
							break;
						case 'formularios': //EDITAR DESCARGA DE FORMULARIOS
							$formularios = true;
							break;				
						case 'compania':   //ADMINISTRAR COMPAÑIAS DE SEGUROS
							$compania = true;
							break;
						case 'desgravamen'://EDITAR PAGINA PERSONAS
							$desgravamen = true;
							break;
						case 'poliza':     //GENERAR REPORTES PERSONAS PRODUCTOS
							$poliza = true;
							break;
						case 'admindatos':     //GENERAR REPORTES PERSONAS PRODUCTOS
							$admindatos = true;
							break;	
						case 'facultativode': //GENERAR REPORTES FACULTATIVO DESGRAVAMEN
							$faculde = true;
							break;
						case 'certificadomedico': //CREAR CERETIFICADO MEDICO 
						    $certmedico = true;
							break;
						case 'email': //ADMINISTRAR CORREOS ELECTRONICOS
						    $email = true;
							break;
						case 'sucursal': //ADMINISTRAR CORREOS ELECTRONICOS
						    $sucursal = true;
							break;	
						case 'agencia': //ADMINISTRAR CORREOS ELECTRONICOS
						    $agency = true;
							break;
						case 'agregaciaef': //AÑADIR COMPAÑIAS DE SEGUROS A ENTIDADES FINANCIERAS
						    $addciaef = true;
							break;
						case 'automotores': //ADMINISTRAR AUTOMOTORES
						    $autos = true;
							break;
						case 'formapago': //ADMINISTRAR FORMA DE PAGOS
						    $formpago = true;
							break;
						case 'estados': //ADMINISTRAR ESTADOS
						    $estado = true;
							break;
					    case 'todoriesgod': //ADMINISTRAR TODORIEAGO DOMICILIARIO
						    $triesgod = true;
						    break;
						case 'triesgoeqmov': //ADMINISTRAR TODORIESGO EQUIPO MOVIL
						    $triesgoeqm = true;
							break;
						case 'tipocambio': //ADMINISTRAR TIPO DE CAMBIO
						    $tipcambio = true;
							break;
						case 'tarjetahabiente': //TRAJETAHABIENTE
						    $tarjetahabiente = true;
						    break;														 	
					}
			  }
			  
			 
			echo'<ul>';
			   /*if($header){
				   if($link_page=='cabecera'){
						$data='class="active"';	$close='';
					}else{$data=''; $close='class="closed"';}
				   echo'<li '.$data.'>
							<a href="#">
								<!-- Icon Container -->
								<span class="da-nav-icon">
									<img src="images/icons/black/32/home.png" alt="entidades Financieras" />
								</span>
								Home
							</a>
							<ul '.$close.'>
							    <li><a href="?l=cabecera&var=cab&listcabecera=v">Cabecera</a></li>
							</ul>
						</li>';
			   }*/
			   if($inicio){
				   if($link_page=='escritorio' or $link_page=='slideshow' or $link_page=='contenidohome'){
						$data='class="active"';	
					}else{$data='';}
				   echo'<li '.$data.'>
							<a href="#">
								<span class="da-nav-icon">
									<img src="images/icons/black/32/home.png" alt="Dashboard" />
								</span>
								Home
							</a>';
							 switch($link_page){
								 case "slideshow" :
									 if($link_page=='slideshow'){
										  $close=''; $closea='';
										  $closeb='class="closed"'; 
										  break;
									 }else{
										  $close='class="closed"'; $closea='class="closed"';
										  $closeb=''; 
										  break;
									 }
								  case "contenidohome" :
									 if($link_page=='contenidohome'){
										  $close=''; $closeb='';
										  $closea='class="closed"'; 
										  break;
									 }else{
										  $close='class="closed"'; $closeb='class="closed"';
										  $closea=''; 
										  break;
									 }	 
								 default :
									$close='class="closed"';
									$closea='class="closed"';
									$closeb='class="closed"';
									break;	  
							 }	
					   echo'<ul '.$close.'> 
								<li>
								  <a href="#">Slideshow</a>
								   <ul '.$closea.'>
									   <li style="padding-left:10px;">
									     <a href="?l=slideshow&var=s">Listar imagenes</a>
									   </li>
									   <li style="padding-left:10px;">
									     <a href="?l=slideshow&var=s&crear=v">Agregar imagen</a>
									   </li>
								   </ul>
								</li>     
								<li>
								   <a href="#">Contenido home</a>
								   <ul '.$closeb.'>
									   <li style="padding-left:10px;"><a href="?l=contenidohome&var=c">Listar contenido</a></li>
									   <li style="padding-left:10px;"><a href="?l=contenidohome&var=nos&list_nosotros=v">Nosotros</a></li>
									   <li style="padding-left:10px;"><a href="?l=contenidohome&var=prgf&list_preg_frec=v">Preguntas Frecuentes</a></li>
								   </ul> 
								</li>
							</ul>
						</li>';
			   }
			   if($compania){
				   if($link_page=='compania' || $link_page=='des_poliza'){
					    $data='class="active"'; $close='';  
				   }else{$data=''; $close='class="closed"';}
				   echo'<li '.$data.'>
							<a href="#">
								<span class="da-nav-icon">
									<img src="images/icons/black/32/companies.png" alt="Form" />
								</span>
								Compañía de Seguros
							</a>
							<ul '.$close.'>
								<li><a href="?l=compania&var=cs">Listar Cia. Seguros</a></li>
								<li><a href="?l=des_poliza&var=pl">Administrar P&oacute;lizas</a></li>
							</ul>
						</li>';
			   }
			   if($entidadfin){
				   if($link_page=='entidades'){
						$data='class="active"';	$close='';
					}else{$data=''; $close='class="closed"';}
				   echo'<li '.$data.'>
							<a href="#">
								<!-- Icon Container -->
								<span class="da-nav-icon">
									<img src="images/icons/black/32/apartment_building.png" alt="entidades Financieras" />
								</span>
								Entidades Financieras
							</a>
							<ul '.$close.'>
							    <li><a href="?l=entidades&var=enti">Listar Entidades</a></li>
							</ul>
						</li>';
			   }
			   if($addciaef){
				   if($link_page=='adciaef'){
						$data='class="active"';	$close='';
					}else{$data=''; $close='class="closed"';}
				   echo'<li '.$data.'>
							<a href="#">
								<!-- Icon Container -->
								<span class="da-nav-icon">
									<img src="images/icons/black/32/add_cia.png" alt="entidades Financieras" />
								</span>
								Agregar Cia. a Entidades Financieras
							</a>
							<ul '.$close.'>
							    <li><a href="?l=adciaef&var=adcia">Listar Cia. agregadas a Entidades</a></li>
							</ul>
						</li>';
			   }
			   if($usuarios){
				   if($link_page=='usuarios'){
					    $data='class="active"'; $close='';
				   }else{$data=''; $close='class="closed"';}
				   echo'<li '.$data.'>
							<a href="#">
								<span class="da-nav-icon">
									<img src="images/icons/black/32/users_2.png" alt="Calendar" />
								</span>
								Usuarios
							</a>
							<ul '.$close.'>
								<li><a href="?l=usuarios">Listar usuarios</a></li>';
								//echo'<li><a href="?l=usuarios&crear=1">Crear usuario</a></li>';
					   echo'</ul>
						</li>';
			   }
			   if($tipcambio){
				   if($link_page=='tipocambio'){
						$data='class="active"';	$close='';
					}else{$data=''; $close='class="closed"';}
				   echo'<li '.$data.'>
							<a href="#">
								<!-- Icon Container -->
								<span class="da-nav-icon">
									<img src="images/icons/black/32/moneda.png" alt="Agencia" />
								</span>
								Tipo de Cambio 
							</a>
							<ul '.$close.'>
								<li><a href="?l=tipocambio&var=tcm">Tipo Cambio Moneda</a></li>
							</ul>
						</li>';
			   }
			   if($desgravamen){    //DESGRAVAMEN
				   if($link_page=='des_producto' or $link_page=='des_preguntas' or 
				      $link_page=='des_tasas' or $link_page=='des_datos' or 
					  $link_page=='des_contenido' or $link_page=='des_producto_extra' or
					  $link_page=='certmedico'){
						$data='class="active"'; $close='';
				   }else{$data=''; $close='class="closed"';}
				   echo'<li '.$data.'>
							<a href="#">
								<span class="da-nav-icon">
									<img src="images/icons/black/32/product.png" alt="Form" />
								</span>
								Desgravamen
							</a>
							<ul '.$close.'>
								<li><a href="?l=des_datos&var=de">Administrar parametros del Producto</a></li>
								<li><a href="?l=des_producto&var=de&list_producto=v">Administrar Producto Crediticio</a></li>
								<li><a href="?l=des_preguntas&var=de&list_compania=v">Administrar preguntas</a></li>
							    <li><a href="?l=des_contenido&var=de">Administrar contenido</a></li>
								<li><a href="?l=des_producto_extra&var=pe&list_compania=v">Administrar producto extra</a></li>
								<li><a href="?l=certmedico&var=cert">Administrar Certificado M&eacute;dico</a></li>
							</ul>
						</li>';
			   }
			   if($autos){          //AUTOMOTORES
				   if($link_page=='au_tipovehiculo' or $link_page=='au_tasas' or $link_page=='au_marca_modelo' 
				       or $link_page=='au_montos' or $link_page=='au_contenido'){
						$data='class="active"'; $close='';
				   }else{$data=''; $close='class="closed"';}
				   echo'<li '.$data.'>
							<a href="#">
								<span class="da-nav-icon">
									<img src="images/icons/black/32/product.png" alt="Form" />
								</span>
								Automotores
							</a>
							<ul '.$close.'>
								<li><a href="?l=au_montos&var=au">Administrar parametros del Producto</a></li>
								<li><a href="?l=au_tasas&var=au&list_compania=v">Administrar tasas</a></li>
								<li><a href="?l=au_tipovehiculo&var=au">Administrar tipo vehículo</a></li>
								<li><a href="?l=au_marca_modelo&var=au&list_marca=v">Administrar marca/modelo</a></li>
							    <li><a href="?l=au_contenido&var=au">Administrar contenido</a></li>
							</ul>
						</li>';
			   }
			   if($triesgod){       //TODORIEASGO DOMICILIARIO
				   if($link_page=='tr_tasas' or $link_page=='tr_montos' or $link_page=='tr_contenido'){
						$data='class="active"'; $close='';
				   }else{$data=''; $close='class="closed"';}
				   echo'<li '.$data.'>
							<a href="#">
								<span class="da-nav-icon">
									<img src="images/icons/black/32/product.png" alt="Form" />
								</span>
								Todo Riesgo Domiciliario
							</a>
							<ul '.$close.'>
								<li><a href="?l=tr_montos&var=trd">Administrar parametros del Producto</a></li>
								<li><a href="?l=tr_tasas&var=trd&list_compania=v">Administrar tasas</a></li>
							    <li><a href="?l=tr_contenido&var=trd">Administrar contenido</a></li>
							</ul>
						</li>';
			   }
			   if($triesgoeqm){     //TODORIESGO EQUIPO MOVIL
				   if($link_page=='trem_tasas' or $link_page=='trem_montos' or $link_page=='trem_contenido'){
						$data='class="active"'; $close='';
				   }else{$data=''; $close='class="closed"';}
				   echo'<li '.$data.'>
							<a href="#">
								<span class="da-nav-icon">
									<img src="images/icons/black/32/product.png" alt="Form" />
								</span>
								Todo Riesgo Equipo Movil
							</a>
							<ul '.$close.'>
								<li><a href="?l=trem_montos&var=trem">Administrar parametros del Producto</a></li>
								<li><a href="?l=trem_tasas&var=trem&list_compania=v">Administrar tasas</a></li>
							    <li><a href="?l=trem_contenido&var=trem">Administrar contenido</a></li>
							</ul>
						</li>';
			   }
			   if($tarjetahabiente){//TARJETA HABIENTE
				   if($link_page=='th_marcatarjeta' or $link_page=='th_tipotarjeta' or 
				      $link_page=='th_primastarjeta' or $link_page=='th_contenido' or 
					  $link_page=='th_montos'){
						$data='class="active"'; $close='';
				   }else{$data=''; $close='class="closed"';}
				   echo'<li '.$data.'>
							<a href="#">
								<span class="da-nav-icon">
									<img src="images/icons/black/32/product.png" alt="Form" />
								</span>
								Tarjetahabiente
							</a>
							<ul '.$close.'>
								<li><a href="?l=th_montos&var=th">Administrar parametros del Producto</a></li>
								<li><a href="?l=th_tipotarjeta&var=th&list_producto=v">Administrar tarjeta</a></li>
								<li><a href="?l=th_marcatarjeta&var=th&list_compania=v">Administrar marca</a></li>
								<li><a href="?l=th_primastarjeta&var=th&list_compania=v">Administrar primas</a></li>
								<li><a href="?l=th_contenido&var=th">Administrar contenido</a></li>
							</ul>
						</li>';
			   }
			   /*if($poliza){
				   if($link_page=='des_poliza'){
					   $data='class="active"'; $close=''; 
				   }else{$data=''; $close='class="closed"';}
				   echo'<li '.$data.'>
							<a href="#">
								<span class="da-nav-icon">
									<img src="images/icons/black/32/admin.png" alt="Form" />
								</span>
								Administrar Poliza
							</a>
							<ul '.$close.'>
								<li><a href="?l=des_poliza&var=pl">Listar polizas</a></li>
							</ul>
						</li>';
			   }*/
			   if($admindatos){
				   if($link_page=='ocupacion'){
						$data='class="active"';	
					}else{$data='';}
	               echo'<li '.$data.'>
							<a href="#">
								<!-- Icon Container -->
								<span class="da-nav-icon">
									<img src="images/icons/black/32/admin.png" alt="Form" />
								</span>
								Administrar Ocupacion
							</a>';
						          switch($link_page){
									 case "ocupacion" :
									     if($link_page=='ocupacion'){$closea=''; break;}
							             else{$closea='class="closed"'; break;}
									 default :
									    $closea='class="closed"';
										break;	  
								 }	
					   echo'<ul '.$closea.'>
						        <li>
								   <a href="#">Ocupacion</a>
								   <ul '.$closea.'>
									   <li style="padding-left:10px;">
									     <a href="?l=ocupacion&var=oc">Listar registros</a>
									   </li>
								   </ul> 
								</li>
								<!--<li>
								   <a href="#">Facultativos 2</a>
								   <ul class="closed">
									   <li style="padding-left:10px;"><a href="#">pregunta 2</a></li>
									   <li style="padding-left:10px;"><a href="#">Agregar 2</a></li>
								   </ul> 
								</li>-->
							</ul>
						</li>';   
			   }
			   if($formpago){
				   if($link_page=='formapago'){
						$data='class="active"';	
					}else{$data='';}
	               echo'<li '.$data.'>
							<a href="#">
								<!-- Icon Container -->
								<span class="da-nav-icon">
									<img src="images/icons/black/32/admin.png" alt="Form" />
								</span>
								Administrar Forma Pago
							</a>';
						          switch($link_page){
									 case "formapago" :
									     if($link_page=='formapago'){$closea=''; break;}
							             else{$closea='class="closed"'; break;}
									 default :
									    $closea='class="closed"';
										break;	  
								 }	
					   echo'<ul '.$closea.'>
						        <li>
								   <a href="#">Forma Pago</a>
								   <ul '.$closea.'>
									   <li style="padding-left:10px;">
									     <a href="?l=formapago&var=fp">Listar registros</a>
									   </li>
								   </ul> 
								</li>
							</ul>
						</li>';   
			   }
			   if($estado){
				   if($link_page=='estados'){
						$data='class="active"';	
					}else{$data='';}
	               echo'<li '.$data.'>
							<a href="#">
								<!-- Icon Container -->
								<span class="da-nav-icon">
									<img src="images/icons/black/32/admin.png" alt="Form" />
								</span>
								Administrar Estados
							</a>';
						          switch($link_page){
									 case "estados" :
									     if($link_page=='estados'){$closea=''; break;}
							             else{$closea='class="closed"'; break;}
									 default :
									    $closea='class="closed"';
										break;	  
								 }	
					   echo'<ul '.$closea.'>
						        <li>
								   <a href="#">Estados</a>
								   <ul '.$closea.'>
									   <li style="padding-left:10px;">
									     <a href="?l=estados&var=est">Listar registros</a>
									   </li>
								   </ul> 
								</li>
							</ul>
						</li>';   
			   }
			   /*if($modalidad){
				   if($link_page=='modalidad'){
						$data='class="active"';	
					}else{$data='';}
	               echo'<li '.$data.'>
							<a href="#">
								<!-- Icon Container -->
								<span class="da-nav-icon">
									<img src="images/icons/black/32/admin.png" alt="Form" />
								</span>
								Administrar Modalidad
							</a>';
						          switch($link_page){
									 case "modalidad" :
									     if($link_page=='modalidad'){$closea=''; break;}
							             else{$closea='class="closed"'; break;}
									 default :
									    $closea='class="closed"';
										break;	  
								 }	
					   echo'<ul '.$closea.'>
						        <li>
								   <a href="#">Modalidad</a>
								   <ul '.$closea.'>
									   <li style="padding-left:10px;">
									     <a href="?l=modalidad&var=mod">Listar registros</a>
									   </li>
								   </ul> 
								</li>
							</ul>
						</li>';   
			   }
			   if($certmedico){
				   if($link_page=='certmedico'){
						$data='class="active"';	$close='';
					}else{$data=''; $close='class="closed"';}
				   echo'<li '.$data.'>
							<a href="#">
								<!-- Icon Container -->
								<span class="da-nav-icon">
									<img src="images/icons/black/32/certificado.png" alt="Certificado Medico" />
								</span>
								Certificado Medico
							</a>
							<ul '.$close.'>
								<li><a href="?l=certmedico&var=cert">Listar Certificados</a></li>
							</ul>
						</li>';
			   }*/
			   if($email){
				   if($link_page=='email'){
						$data='class="active"';	$close='';
					}else{$data=''; $close='class="closed"';}
				   echo'<li '.$data.'>
							<a href="#">
								<!-- Icon Container -->
								<span class="da-nav-icon">
									<img src="images/icons/black/32/email.png" alt="Administrar correos" />
								</span>
								Administrar Correos
							</a>
							<ul '.$close.'>
								<li><a href="?l=email&var=em">Administrar correos</a></li>
							</ul>
						</li>';
			   }
			   if($sucursal){
				   if($link_page=='sucursal'){
						$data='class="active"';	$close='';
					}else{$data=''; $close='class="closed"';}
				   echo'<li '.$data.'>
							<a href="#">
								<!-- Icon Container -->
								<span class="da-nav-icon">
									<img src="images/icons/black/32/sucursal.png" alt="Agencia" />
								</span>
								Departamentos/<br/>Sucursales
							</a>
							<ul '.$close.'>
							    <li><a href="?l=sucursal&var=suc&listarsuc=v">Listar Departamentos<br/>Sucursales</a></li>
							</ul>
						</li>';
			   }
			   if($agency){
				   if($link_page=='agencia'){
						$data='class="active"';	$close='';
					}else{$data=''; $close='class="closed"';}
				   echo'<li '.$data.'>
							<a href="#">
								<!-- Icon Container -->
								<span class="da-nav-icon">
									<img src="images/icons/black/32/agencia.png" alt="Agencia" />
								</span>
								Agencias
							</a>
							<ul '.$close.'>
								<li><a href="?l=agencia&var=age">Listar Agencias</a></li>
							</ul>
						</li>';
			   }
			   if($formularios){
				   if($link_page=='archivos'){
					    $data='class="active"'; $close='';
				   }else{$data=''; $close='class="closed"';}
				   echo'<li '.$data.'>
							<a href="#">
								<span class="da-nav-icon">
									<img src="images/icons/black/32/file_pdf.png" alt="Form" />
								</span>
								Formularios
							</a>
							<ul '.$close.'>
								<li><a href="?l=archivos&var=f">Listar formularios</a></li>
								<li><a href="?l=archivos&crear=v&var=f">Agregar formulario</a></li>
							</ul>
						</li>';
			   }
			   if($none){
				   echo'<li>
							<a href="table.html">
								<!-- Icon Container -->
								<span class="da-nav-icon">
									<img src="images/icons/black/32/table_1.png" alt="Table" />
								</span>
								Table
							</a>
						</li>';
			   }
			   if($none){
				   echo'<li>
							<a href="#">
								<!-- Icon Container -->
								<span class="da-nav-icon">
									<img src="images/icons/black/32/create_write.png" alt="Form" />
								</span>
								Form
							</a>
							<ul>
								<li><a href="form-layouts.html">Layouts</a></li>
								<li><a href="form-elements.html">Elements</a></li>
								<li><a href="form-validation.html">Validation</a></li>
							</ul>
						</li>';
			   }
			   if($none){		
				   echo'<li>
							<a href="ui.html">
								<!-- Icon Container -->
								<span class="da-nav-icon">
									<img src="images/icons/black/32/settings.png" alt="" />
								</span>
								UI Elements
							</a>
						</li>';
			   }
			   if($none){
				   echo'<li>
							<a href="widgets.html">
								<!-- Icon Container -->
								<span class="da-nav-icon">
									<img src="images/icons/black/32/cog_4.png" alt="Widgets" />
								</span>
								Widgets
							</a>
						</li>';
			   }
			   if($none){
				   echo'<li>
							<a href="#">
								<!-- Icon Container -->
								<span class="da-nav-icon">
									<img src="images/icons/black/32/word_documents_1.png" alt="Layout and Typography" />
								</span>
								Layout and Typography
							</a>
							<ul class="closed">
								<li><a href="grids.html">Grids and Panels</a></li>
								<li><a href="typography.html">Typography</a></li>
							</ul>
						</li>';
			   }
			   if($none){
				   echo'<li>
							<a href="gallery.html">
								<!-- Icon Container -->
								<span class="da-nav-icon">
									<img src="images/icons/black/32/images_2.png" alt="Gallery" />
								</span>
								Gallery
							</a>
						</li>';
			   }
			   if($none){
				   echo'<li>
							<a href="error.html">
								<!-- Icon Container -->
								<span class="da-nav-icon">
									<img src="images/icons/black/32/alert.png" alt="Error Pages" />
								</span>
								Error Page (404)
							</a>
						</li>';
			   }
			   if($none){
				   echo'<li>
							<a href="icons.html">
								<!-- Icon Container -->
								<span class="da-nav-icon">
									<img src="images/icons/black/32/pacman.png" alt="Icons" />
								</span>
								Icons
							</a>
						</li>';
			   }
			  echo'</ul>';
		
		}
		
}

function sacamos_valor($link_page){
	 if($link_page=='escritorio' or $link_page=='slideshow'){
		return 'class="active"';
	 }elseif($link_page=='usuarios'){
		return 'class="active"';
	 }elseif($link_page=='archivos'){
		return 'class="active"';
	 }elseif($link_page=='compania'){
		return 'class="active"'; 
	 }elseif($link_page=='des_producto' or $link_page=='des_preguntas' or $link_page=='des_tasas' or $link_page=='des_datos'){
		return 'class="active"'; 
	 }elseif($link_page=='des_poliza'){
		return 'class="active"'; 
	 }else{
	    return '';	 
	 }
}
?>