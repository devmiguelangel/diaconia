<?php
session_start();
ob_start();
//date_default_timezone_set('America/La_Paz');
if(isset($_GET['num_emision_des'])){
   $num_emision_des=$_GET['num_emision_des'];
}else{
   $num_emision_des=NULL;
}


if(isset($_GET['l'])) {
	$lugar = $_GET['l'];
} else {
	$lugar = '';
}
//HEADEAR
if(empty($_SESSION['usuario_sesion'])){
    HeaderUsuario();
}elseif($lugar=="escritorio"){
    HeaderEscritorio();
}elseif($lugar=="slideshow"){
	HeaderSlideshow();
}elseif(($lugar=="usuarios") or ($lugar=="archivos") or ($lugar=="compania") or ($lugar=="des_preguntas")
     or ($lugar=="des_producto") or ($lugar=="des_tasas")  or ($lugar=="des_datos") or ($lugar=="des_poliza")
	 or ($lugar=="des_ocupacion") or ($lugar=="au_ocupacion") or ($lugar=="trem_ocupacion") 
	 or ($lugar=="tr_ocupacion") or ($lugar=="correo") or ($lugar=="contenidohome") or ($lugar=="certmedico") 
	 or ($lugar=="des_estados") or ($lugar=="au_estados") or ($lugar=="tr_estados") or ($lugar=="trem_estados") 
	 or ($lugar=="au_formapago") or ($lugar=="tr_formapago") or ($lugar=="trem_formapago")
	 or ($lugar=="email") or ($lugar=="des_contenido") or ($lugar=="agencia") or ($lugar=="usuarios_admin") 
	 or ($lugar=="sucursal") or ($lugar=="entidades") or ($lugar=="adciaef") or ($lugar=="cabecera") 
	 or ($lugar=="sucursal") or ($lugar=="au_tipovehiculo") or ($lugar=="au_marca_modelo") or ($lugar=="au_montos")
	 or ($lugar=="au_tasas") or ($lugar=="au_incremento") or ($lugar=="au_contenido") or ($lugar=="formapago")
	 or ($lugar=="estados") or ($lugar=="tr_montos") or ($lugar=="tr_tasas") or ($lugar=="tr_contenido") 
	 or ($lugar=="trem_montos") or ($lugar=="trem_tasas") or ($lugar=="trem_contenido") or ($lugar=="tipocambio")
	 or ($lugar=="des_producto_extra") or ($lugar=="th_marcatarjeta") or ($lugar=="th_tipotarjeta")
	 or ($lugar=="th_primastarjeta") or ($lugar=="th_contenido") or ($lugar=="th_montos") or ($lugar=="modalidad")){
    HeaderTablas();
}
echo'<body>';
switch($lugar) {
		case "escritorio":
			include 'sgc_escritorio.php'; //VISUALIZAMOS EL ENTORNO ESCRITORIO
			break;
		case "usuarios":
			include 'sgc_usuarios.php'; //VISUALIZAMOS LA LISTA DE USUARIOS EXISTENTES
			break;
	    case "usuarios_admin":
		    include 'sgc_usuarios_admin.php'; //ADMINISTRAR CERTIFICADOS MEDICOS
			break;		
		case "slideshow":
			include 'sgc_slideshow.php'; //VISUALIZAMOS LA LISTA DE IMAGENES EXISTENTES
			break;
		case "contenidohome":
			include 'sgc_contenido.php'; //VISUALIZAMOS LA LISTA DE IMAGENES EXISTENTES
			break;
		case "cabecera":
			include 'sgc_contenido.php'; //ADMINISTRAR CABECERA
			break;		
		case "archivos":
			include 'sgc_archivos.php'; //VISUALIZAMOS LA LISTA DE ARCHIVOS EXISTENTES
			break;
		case "compania":
			include 'sgc_companias.php'; //VISUALIZAMOS LA LISTA DE COMPAÑIAS DE SEGUROS EXISTENTES
			break;
		case "des_preguntas":
			include 'sgc_des_preguntas.php'; //VISUALIZAMOS LA LISTA DE COMPAÑIAS DE SEGUROS EXISTENTES
			break;
		case "des_producto":
			include 'sgc_des_producto.php'; //VISUALIZAMOS LA LISTA DE COMPAÑIAS DE SEGUROS EXISTENTES
			break;
		case "des_contenido":
		    include 'sgc_des_contenido.php';  //VISUALIZAMOS EL CONTENIDO DESGRAVAMEN
			break;	
		case "des_tasas":
			include 'sgc_des_tasas.php'; //VISUALIZAMOS LA LISTA DE COMPAÑIAS DE SEGUROS EXISTENTES
			break;
		case "des_datos":
			include 'sgc_des_datos.php'; //VISUALIZAMOS LA LISTA DE COMPAÑIAS DE SEGUROS EXISTENTES
			break;
		case "des_poliza":
			include 'sgc_des_poliza.php'; //ADMINISTRAR POLIZAS
			break;
		case "des_producto_extra": //ADMINISTRAR PRODUCTO EXTRA
		    include 'sgc_des_producto_extra.php';
			break;	
		case "des_ocupacion":
			include 'sgc_des_ocupacion.php'; //ADMINISTRACION OCUPACION DESGRAVAMEN
			break;
		case "des_estados":
		    include 'sgc_des_estados.php'; //ADMINISTRACION ESTADOS DESGRAVAMEN
			break;	
		case "formapago":
			include 'sgc_formapago.php'; //ADMINISTRACION FORMA DE PAGO
			break;
		case "estados":
			include 'sgc_estados.php'; //ADMINISTRACION ESTADOS
			break;			
		case "certmedico":
		    include 'sgc_certmedico.php'; //ADMINISTRAR CERTIFICADOS MEDICOS
			break;
		case "agencia":
		    include 'sgc_agencia.php'; //ADMINISTRAR AGENCIAS
			break;
		case "sucursal":
		    include 'sgc_agencia.php'; //ADMINISTRAR SUCURSALES
			break;		
		case "email":
		    include 'sgc_correos.php'; //ADMINISTRAR CORREOS ELECTRONICOS
			break;
		case "entidades":
		    include 'sgc_entidades.php'; //ADMINISTRAR ENTIDADES FINANCIERAS
			break;
		case "adciaef":
		    include 'sgc_agrega_cia_entidad.php';//ADMINISTRAR AGREGAR COMPAÑIAS DE SEGUROS A ENTIDADES FINANCIERAS
			break;
		case "au_tipovehiculo":
		    include 'sgc_au_tipovehiculo.php';//ADMINISTRAR TIPO VEHICULOS
			break;
		case "au_marca_modelo":
		    include 'sgc_au_marca_modelo.php';//ADMINISTRAR MARCAS/MODELOS AUTOS
			break;
		case "au_montos":
		    include 'sgc_au_montos.php';//ADMINISTRAR MARCAS/MODELOS AUTOS
			break;
		case "au_tasas":
		    include 'sgc_au_tasas.php';//ADMINISTRAR MARCAS/MODELOS AUTOS
			break;
		case "au_incremento":
		    include 'sgc_au_incremento.php';//ADMINISTRAR MARCAS/MODELOS AUTOS
			break;
		case "au_contenido":
		    include 'sgc_au_contenido.php';//ADMINISTRAR CONTENIDO AUTOMOTORES
			break;
		case "au_ocupacion":
		    include 'sgc_au_ocupacion.php';//ADMINISTRADOR OCUPACION AUTOMOTORES
			break;
		case "au_estados":
		    include 'sgc_au_estados.php';//ADMINISTRADOR ESTADOS AUTOMOTORES
			break;
		case "au_formapago":
		    include 'sgc_au_formapago.php';//ADMINISTRAR FORMA DE PAGO AUTOMOTORES
			break;		 	
		case "tr_montos":
		    include 'sgc_tr_montos.php';//ADMINISTRAR CONTENIDO MONTOS
			break;
		case "tr_contenido":
		    include 'sgc_tr_contenido.php';//ADMINISTRAR CONTENIDO TODO RIESGO DOMICILIARIO
			break;
		case "tr_tasas":
		    include 'sgc_tr_tasas.php';//ADMINISTRAR TASAS TODO RIESGO DOMICILIARIO
			break;
		case "tr_ocupacion":
		    include 'sgc_tr_ocupacion.php';//ADMINISTRAR OCUPACION TODO RIESGO DOMICILIARIO
			break;	
		case "tr_estados":
		    include 'sgc_tr_estados.php';//ADMINISTRAR ESTADS TODO RIESGO DOMICILIARIO
			break;
		case "tr_formapago":
		    include 'sgc_tr_formapago.php';//ADMINISTRAR FORMA DE PAGO TODO RIESGO DOMICILIARIO
			break;		
		case "trem_montos":
		    include 'sgc_trem_montos.php';//ADMINISTRAR MONTOS TODORIESGO EQUIPO MOVIL
			break;
		case "trem_tasas":
		    include 'sgc_trem_tasas.php';//ADMINISTRAR TASAS TODO RIEASGO EQUIPO MOVIL
			break;
		case "trem_contenido":
		    include 'sgc_trem_contenido.php';//ADMINISTRAR CONTENIDO TODO RIESGO EQUIPO MOVIL
			break;
		case "trem_ocupacion":
		    include 'sgc_trem_ocupacion.php';//ADMINISTRAR OCUPACION TODO RIESGO EQUIPO MOVIL
			break;
		case "trem_estados":
		    include 'sgc_trem_estados.php';//ADMINISTRAR ESTADOS TODO RIESGO EQUIPO MOVIL
			break;
		case "trem_formapago":
		    include 'sgc_trem_formapago.php';//ADMINISTRAR FORMA DE PAGO TODO RIESGO EQUIPO MOVIL
			break;	 	 	
		case "tipocambio":
		    include 'sgc_tipocambio.php';//ADMINISTRAR TIPO DE CAMBIO MONEDA
			break;
		case "th_marcatarjeta":
		    include 'sgc_th_marcatarjeta.php';//ADMINISTRAR MARCA TARJETA
			break;
		case "th_tipotarjeta":
		    include 'sgc_th_tipotarjeta.php';//ADMINISTRAR TIPO TARJETA
			break;
		case "th_primastarjeta": 
		    include 'sgc_th_primatarjeta.php'; //PRIMAS TARJETAS
			break;
		case "th_contenido":
		    include 'sgc_th_contenido.php';//ADMINISTRAR CONTENIDO TARJETA
			break;
		case "th_montos":
		    include 'sgc_th_montos.php'; //ADMINISTRAR MONTOS
			break; 	
		case "modalidad":
		    include 'sgc_modalidad.php';//ADMINISTRAR MODALIDAD
			break;																													
		default:
			include 'sgc_ingreso_usuario.php';
}
echo'</body>
  </html>';

//FUNCION PARA VISUALIZAR EL INGRESO LOGIN
function HeaderUsuario(){
   echo'<!DOCTYPE HTML>
			<html lang="es">
			<head>
				<meta charset="utf-8">
				<link href="../img/favicon.ico" type="image/x-icon" rel="shortcut icon" />
				<!-- iOS webapp metatags -->
				<meta name="apple-mobile-web-app-capable" content="yes" />
				<meta name="apple-mobile-web-app-status-bar-style" content="black" />
				
				<!-- iOS webapp icons -->
				<link rel="apple-touch-icon" href="touch-icon-iphone.png" />
				<link rel="apple-touch-icon" sizes="72x72" href="touch-icon-ipad.png" />
				<link rel="apple-touch-icon" sizes="114x114" href="touch-icon-retina.png" />
				
				<!-- CSS Reset -->
				<link rel="stylesheet" type="text/css" href="css/reset.css" media="screen" />
				<!--  Fluid Grid System -->
				<link rel="stylesheet" type="text/css" href="css/fluid.css" media="screen" />
				<!-- Login Stylesheet -->
				<link rel="stylesheet" type="text/css" href="css/login.css" media="screen" />
				
				<!-- Required JavaScript Files -->
				<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
				<script type="text/javascript" src="js/jquery.placeholder.js"></script>
				<script type="text/javascript" src="plugins/validate/jquery.validate.min.js"></script>
				
				<!-- Core JavaScript Files -->
				<script type="text/javascript" src="js/core/dandelion.login.js"></script>
				
				<!-- Languaje -->
				<script type="text/javascript" src="js/jquery-cookie.js" charset="utf-8"></script>
				<script type="text/javascript" src="js/jquery-lang.js" charset="utf-8"></script>
				<script type="text/javascript">
					var lang = new Lang("es");
					lang.dynamic("en", "js/langpack/en.json");
				</script>
				
				<!-- Script que evita ir hacia atras -->
				<script type="text/javascript">
				  if (history.forward(1)){location.replace(history.forward(1))}
				</script>
				<title>SISTEMA DE GESTION DE CONTENIDOS - SGC</title>
			</head>';
}

//FUNCION PARA VISUALIZAR EL ENTORNO ESCRITORIO
function HeaderEscritorio(){
   echo'<!DOCTYPE HTML>
        <html>
		<head>
			<meta charset="utf-8">
			<link href="../img/favicon.ico" type="image/x-icon" rel="shortcut icon" />
			<!-- Viewport metatags -->
			<meta name="HandheldFriendly" content="true" />
			<meta name="MobileOptimized" content="320" />
			<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
			
			<!-- iOS webapp metatags -->
			<meta name="apple-mobile-web-app-capable" content="yes" />
			<meta name="apple-mobile-web-app-status-bar-style" content="black" />
			
			<!-- iOS webapp icons -->
			<link rel="apple-touch-icon" href="touch-icon-iphone.png" />
			<link rel="apple-touch-icon" sizes="72x72" href="touch-icon-ipad.png" />
			<link rel="apple-touch-icon" sizes="114x114" href="touch-icon-retina.png" />
			
			<!-- CSS Reset -->
			<link rel="stylesheet" type="text/css" href="css/reset.css" media="screen" />
			<!--  Fluid Grid System -->
			<link rel="stylesheet" type="text/css" href="css/fluid.css" media="screen" />
			<!-- Theme Stylesheet -->
			<link rel="stylesheet" type="text/css" href="css/dandelion.theme.css" media="screen" />
			<!--  Main Stylesheet -->
			<link rel="stylesheet" type="text/css" href="css/dandelion.css" media="screen" />
			<!-- Demo Stylesheet -->
			<link rel="stylesheet" type="text/css" href="css/demo.css" media="screen" />
			<!-- Mensages Ambience-->
			<link rel="stylesheet" href="plugins/ambience/jquery.ambiance.css" type="text/css" media="all">
			
			<!-- jQuery JavaScript File -->
			<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
			
			<!-- jQuery-UI JavaScript Files -->
			<script type="text/javascript" src="jui/js/jquery-ui-1.8.20.min.js"></script>
			<script type="text/javascript" src="jui/js/jquery.ui.timepicker.min.js"></script>
			<script type="text/javascript" src="jui/js/jquery.ui.touch-punch.min.js"></script>
			<link rel="stylesheet" type="text/css" href="jui/css/jquery.ui.all.css" media="screen" />
			
			<!-- Plugin Files -->
			
			<!-- FileInput Plugin -->
			<script type="text/javascript" src="js/jquery.fileinput.js"></script>
			<!-- Placeholder Plugin -->
			<script type="text/javascript" src="js/jquery.placeholder.js"></script>
			<!-- Mousewheel Plugin -->
			<script type="text/javascript" src="js/jquery.mousewheel.min.js"></script>
			<!-- Scrollbar Plugin -->
			<script type="text/javascript" src="js/jquery.tinyscrollbar.min.js"></script>
			<!-- Tooltips Plugin -->
			<script type="text/javascript" src="plugins/tipsy/jquery.tipsy-min.js"></script>
			<link rel="stylesheet" href="plugins/tipsy/tipsy.css" />
			
			<!-- Validation Plugin -->
			<script type="text/javascript" src="plugins/validate/jquery.validate.js"></script>
			
			<!-- Statistic Plugin JavaScript Files (requires metadata and excanvas for IE) -->
			<script type="text/javascript" src="js/jquery.metadata.js"></script>
						
			<!-- Wizard Plugin -->
			<script type="text/javascript" src="js/core/plugins/dandelion.wizard.min.js"></script>
									
			<!-- Demo JavaScript Files-->
			<script type="text/javascript" src="js/demo/demo.dashboard.js"></script> 
			
			<!-- Core JavaScript Files -->
			<script type="text/javascript" src="js/core/dandelion.core.js"></script>
			
			<!-- Customizer JavaScript File (remove if not needed) -->
			<script type="text/javascript" src="js/core/dandelion.customizer.js"></script>
			
			<!-- Languaje -->
			<script type="text/javascript" src="js/jquery-cookie.js" charset="utf-8"></script>
			<script type="text/javascript" src="js/jquery-lang.js" charset="utf-8"></script>
			<script type="text/javascript">
				var lang = new Lang("es");
				lang.dynamic("en", "js/langpack/en.json");
			</script>
			
			<script type="text/javascript">
			  $(document).ready(function(){
				  $("a[href].language").click(function(e){
					  var languaje=$(this).prop("id");
					  alert(languaje);
					  if(languaje=="es"){
						  
					  }else if(languaje=="en"){
						  
					  }
				  });  
			  });
			</script>
			
			<!-- Script que evita ir hacia atras -->
			<script type="text/javascript">
			  if (history.forward(1)){location.replace(history.forward(1))}
			</script>
			
			<title>SISTEMA DE GESTION DE CONTENIDOS - SGC</title>
		
		</head>';
}

//FUNCION PARA VISUALIZAR TABLAS
function HeaderTablas(){
    echo'<!DOCTYPE HTML>
	       <html> 
		   <head>
				<meta charset="utf-8">
				<link href="../img/favicon.ico" type="image/x-icon" rel="shortcut icon" />
				<!-- Viewport metatags -->
				<meta name="HandheldFriendly" content="true" />
				<meta name="MobileOptimized" content="320" />
				<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
				
				<!-- iOS webapp metatags -->
				<meta name="apple-mobile-web-app-capable" content="yes" />
				<meta name="apple-mobile-web-app-status-bar-style" content="black" />
				
				<!-- iOS webapp icons -->
				<link rel="apple-touch-icon" href="touch-icon-iphone.png" />
				<link rel="apple-touch-icon" sizes="72x72" href="touch-icon-ipad.png" />
				<link rel="apple-touch-icon" sizes="114x114" href="touch-icon-retina.png" />
				
				<!-- CSS Reset -->
				<link rel="stylesheet" type="text/css" href="css/reset.css" media="screen" />
				<!--  Fluid Grid System -->
				<link rel="stylesheet" type="text/css" href="css/fluid.css" media="screen" />
				<!-- Theme Stylesheet -->
				<link rel="stylesheet" type="text/css" href="css/dandelion.theme.css" media="screen" />
				<!--  Main Stylesheet -->
				<link rel="stylesheet" type="text/css" href="css/dandelion.css" media="screen" />
				<!-- Demo Stylesheet -->
				<link rel="stylesheet" type="text/css" href="css/demo.css" media="screen" />
				<!-- Password validate -->
				<link rel="stylesheet" type="text/css" href="css/jquery.validate.password.css"/>
				<!-- Admin usuarios -->
				<link rel="stylesheet" type="text/css" href="css/admin_user.css"/>
				<!-- Mensages Ambience-->
				<link rel="stylesheet" href="plugins/ambience/jquery.ambiance.css" type="text/css" media="all">
				
				<!-- jQuery JavaScript File -->
				<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
				
				<!-- Script propio -->
				<script type="text/javascript" src="js/jqueryajaxSGC.js"></script>
				
				<!-- Validation Plugin -->
			    <script type="text/javascript" src="plugins/validate/jquery.validate.js"></script>
				
				<!-- Validation Password -->
				<script type="text/javascript" src="js/jquery.validate.password.js"></script>
				
				<!-- jQuery-UI JavaScript Files -->
				<script type="text/javascript" src="jui/js/jquery-ui-1.8.20.min.js"></script>
				<script type="text/javascript" src="jui/js/jquery.ui.timepicker.min.js"></script>
				<script type="text/javascript" src="jui/js/jquery.ui.touch-punch.min.js"></script>
				<link rel="stylesheet" type="text/css" href="jui/css/jquery.ui.all.css" media="screen"/>
				
				<!-- Plugin Files -->
				
				<!-- FileInput Plugin 
				<script type="text/javascript" src="js/jquery.fileinput.js"></script>-->
				<!-- Placeholder Plugin -->
				<script type="text/javascript" src="js/jquery.placeholder.js"></script>
				<!-- Mousewheel Plugin -->
				<script type="text/javascript" src="js/jquery.mousewheel.min.js"></script>
				<!-- Scrollbar Plugin -->
				<script type="text/javascript" src="js/jquery.tinyscrollbar.min.js"></script>
				<!-- Tooltips Plugin -->
				<script type="text/javascript" src="plugins/tipsy/jquery.tipsy-min.js"></script>
				<link rel="stylesheet" href="plugins/tipsy/tipsy.css" />
				
				<!-- DataTables Plugin -->
				<script type="text/javascript" src="plugins/datatables/jquery.dataTables.js"></script>
				
				<!-- Demo JavaScript Files -->
				<script type="text/javascript" src="js/demo/demo.tables.js"></script>
				
				<!-- Core JavaScript Files -->
				<script type="text/javascript" src="js/core/dandelion.core.js"></script>
				
				<!-- Languaje JavaScript Files-->
				<script type="text/javascript" src="js/jquery-cookie.js" charset="utf-8"></script>
				<script type="text/javascript" src="js/jquery-lang.js" charset="utf-8"></script>
				<script type="text/javascript">
					var lang = new Lang("es");
					lang.dynamic("en", "js/langpack/en.json");
				</script>
				
				<script type="text/javascript">
				  $(document).ready(function(){
					  $("a[href].language").click(function(e){
						  var languaje=$(this).prop("id");
						  alert(languaje);
						  if(languaje=="es"){
							  
						  }else if(languaje=="en"){
							  
						  }
					  });  
				  });
				</script>
				
				<!-- Script que evita ir hacia atras -->
				<script type="text/javascript">
				  if (history.forward(1)){location.replace(history.forward(1))}
				</script>
												
				<title>SISTEMA DE GESTION DE CONTENIDOS - SGC</title>
		   </head>'; 
}

//FUNCION PARA VISUALIZAR GALERIA IMAGES
function HeaderSlideshow(){
	echo'<!DOCTYPE html>
			<html>
			<head>
				<meta charset="utf-8">
				<link href="../img/favicon.ico" type="image/x-icon" rel="shortcut icon" />
				<!-- Viewport metatags -->
				<meta name="HandheldFriendly" content="true" />
				<meta name="MobileOptimized" content="320" />
				<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
				
				<!-- iOS webapp metatags -->
				<meta name="apple-mobile-web-app-capable" content="yes" />
				<meta name="apple-mobile-web-app-status-bar-style" content="black" />
				
				<!-- iOS webapp icons -->
				<link rel="apple-touch-icon" href="touch-icon-iphone.png" />
				<link rel="apple-touch-icon" sizes="72x72" href="touch-icon-ipad.png" />
				<link rel="apple-touch-icon" sizes="114x114" href="touch-icon-retina.png" />
				
				<!-- CSS Reset -->
				<link rel="stylesheet" type="text/css" href="css/reset.css" media="screen" />
				<!--  Fluid Grid System -->
				<link rel="stylesheet" type="text/css" href="css/fluid.css" media="screen" /> 
				<!-- Theme Stylesheet -->
				<link rel="stylesheet" type="text/css" href="css/dandelion.theme.css" media="screen" />
				<!--  Main Stylesheet -->
				<link rel="stylesheet" type="text/css" href="css/dandelion.css" media="screen" />
				<!-- Mensages Ambience-->
				<link rel="stylesheet" href="plugins/ambience/jquery.ambiance.css" type="text/css" media="all">
				
				<!-- jQuery JavaScript File 
				<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
				<script type="text/javascript" src="js/jquery-migrate-1.2.1.js"></script>-->
				<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
				
				<!-- jQuery-UI JavaScript Files -->
				<script type="text/javascript" src="jui/js/jquery-ui-1.8.20.min.js"></script>
				<script type="text/javascript" src="jui/js/jquery.ui.timepicker.min.js"></script>
				<script type="text/javascript" src="jui/js/jquery.ui.touch-punch.min.js"></script>
				<link rel="stylesheet" type="text/css" href="jui/css/jquery.ui.all.css" media="screen" />
				
				<!-- Plugin Files -->
				
				<!-- FileInput Plugin -->
				<script type="text/javascript" src="js/jquery.fileinput.js"></script>
				<!-- Placeholder Plugin -->
				<script type="text/javascript" src="js/jquery.placeholder.js"></script>
				<!-- Mousewheel Plugin -->
				<script type="text/javascript" src="js/jquery.mousewheel.min.js"></script>
				<!-- Scrollbar Plugin -->
				<script type="text/javascript" src="js/jquery.tinyscrollbar.min.js"></script>
				<!-- Tooltips Plugin -->
				<script type="text/javascript" src="plugins/tipsy/jquery.tipsy-min.js"></script>
				<link rel="stylesheet" href="plugins/tipsy/tipsy.css" />
				
				<!-- Spinner Plugin -->
				<script type="text/javascript" src="jui/js/jquery.ui.spinner.min.js"></script>
				
				<!-- Chosen Plugin -->
				<script type="text/javascript" src="plugins/chosen/chosen.jquery.js"></script>
				<link rel="stylesheet" href="plugins/chosen/chosen.css" media="screen" />
				
				<!-- Picklist Plugin -->
				<script type="text/javascript" src="js/core/plugins/picklist/jquery.picklist.min.js"></script>
				<link rel="stylesheet" href="js/core/plugins/picklist/jquery.picklist.css" media="screen" />
				
				<!-- Colorpicker Plugin 
				<script type="text/javascript" src="plugins/colorpicker/colorpicker.js"></script>
				<link rel="stylesheet" href="plugins/colorpicker/colorpicker.css" media="screen" />-->
												
				<!-- elFinder Plugin 
				<script type="text/javascript" src="plugins/elfinder/js/elfinder.min.js"></script>
				<link rel="stylesheet" href="plugins/elfinder/css/elfinder.css" media="screen" />-->
				
				<!-- Demo JavaScript Files -->
				<script type="text/javascript" src="plugins/elastic/jquery.elastic.min.js"></script>
				<script type="text/javascript" src="js/demo/demo.form.js"></script>
				
				<!-- Core JavaScript Files -->
                <script type="text/javascript" src="js/core/dandelion.core.js"></script>
				
				<!-- Languaje -->
				<script type="text/javascript" src="js/jquery-cookie.js" charset="utf-8"></script>
				<script type="text/javascript" src="js/jquery-lang.js" charset="utf-8"></script>
				<script type="text/javascript">
					var lang = new Lang("es");
					lang.dynamic("en", "js/langpack/en.json");
				</script>
			
				<!-- Script que evita ir hacia atras -->
				<script type="text/javascript">
				  if (history.forward(1)){location.replace(history.forward(1))}
				</script>
				
				<title>SISTEMA DE GESTION DE CONTENIDOS - SGC</title>
				
				</head>';
}

ob_end_flush();
?>