<?php
include('sgc_funciones.php');
include('sgc_funciones_entorno.php');
include('main_menu.php');
require('session.class.php');
require_once('config.class.php');
$conexion = new SibasDB();
//TENGO Q VER SI EL USUARIO HA INICIADO SESION
if(isset($_SESSION['usuario_sesion']) && isset($_SESSION['tipo_sesion'])) {
	//SI EL USUARIO HA INICIADO SESION, MOSTRAMOS LA PAGINA
	mostrar_pagina_escritorio($_SESSION['id_usuario_sesion'], $_SESSION['tipo_sesion'], $_SESSION['usuario_sesion'], $_SESSION['id_ef_sesion'], $conexion, $num_emision_des, $lugar);
	
} else {
	//SI EL USUARIO NO HA INICIADO SESION, VEMOS SI HA HECHO CLICK EN EL FORMULARIO DE LOGIN
	if(isset($_POST['username'])) {
		//SI HA HECHO CLICK EN EL FORM DE LOGIN, VALIDAMOS LOS DATOS Q HA INGRESADO
		if(validar_login($conexion)) {
			//SI LOS DATOS DEL FORM SON CORRECTOS, MOSTRAMOS LA PAGINA
			header('Location: index.php?l=ecritorio');
			exit;
		} else {
			//SI LOS DATOS NO SON CORRECTOS, MOSTRAMOS EL FORM DE LOGIN CON EL MENSAJE DE ERROR
			$session = new Session();
            $session->remove_session();
			mostrar_login_form(2);
		}
	} else {
		//SI NO HA HECHO CLICK EN EL FORM, MOSTRAMOS EL FORMULARIO DE LOGIN
		$sesion = new Session();
		$sesion->remove_session();
		mostrar_login_form(1);
	}
}

//VISUALIZAR EL DESKTOP

function mostrar_pagina_escritorio($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $num_emision_des, $lugar){			
?>
	<!-- Dandelion Customizer (remove if not needed) -->
    <div id="da-customizer">
    	<?php customizer($tipo_sesion);?>
    </div>

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
                <?php header_bottom('i','',1);//(inicio,variable,nivel)?>
            </div>
        </div>
    
        <!-- Content -->
        <div id="da-content">
            
            <!-- Container -->
            <div class="da-container clearfix">
                
	            <!-- Sidebar Separator do not remove -->
                <div id="da-sidebar-separator"></div>
                
                <!-- Sidebar -->
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
                    
                    	<div class="grid_3">
                        	<div class="da-panel-widget">
                                <h1 lang="es">Bienvenido al Sistema de Gestion de Contenidos</h1>
                                <div id="da-ex-gchart-line" style="height:225px;"></div>
                            </div>
                        </div>
                        <!--
                        <div class="grid_1">
                        	<div class="da-panel-widget">
                                <h1>Summary</h1>
                                <ul class="da-summary-stat">
                                	<li>
                                    	<a href="#">
                                            <span class="da-summary-icon" style="background-color:#e15656;">
                                                <img src="images/icons/white/32/truck.png" alt="" />
                                            </span>
                                            <span class="da-summary-text">
                                                <span class="value up">211</span>
                                                <span class="label">Packages Distributed</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                    	<a href="#">
                                            <span class="da-summary-icon" style="background-color:#a6d037;">
                                                <img src="images/icons/white/32/sport_shirt.png" alt="" />
                                            </span>
                                            <span class="da-summary-text">
                                                <span class="value">512</span>
                                                <span class="label">T-Shirts Sold</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                    	<a href="#">
                                            <span class="da-summary-icon" style="background-color:#ea799b;">
                                                <img src="images/icons/white/32/abacus.png" alt="" />
                                            </span>
                                            <span class="da-summary-text">
                                                <span class="value up">286</span>                                        
                                                <span class="label">Transactions Completed</span>
	                                        </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="da-summary-icon" style="background-color:#fab241;">
                                                <img src="images/icons/white/32/airplane.png" alt="" />
                                            </span>
                                            <span class="da-summary-text">
                                                <span class="value down">61</span>
                                                <span class="label">Planes Flown</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                    	<a href="#">
                                            <span class="da-summary-icon" style="background-color:#61a5e4;">
                                                <img src="images/icons/white/32/shopping_basket_2.png" alt="" />
                                            </span>
                                            <span class="da-summary-text">
                                                <span class="value">42</span>
                                                <span class="label">Shops Visited</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                    	<a href="#">
                                            <span class="da-summary-icon" style="background-color:#656565;">
                                                <img src="images/icons/white/32/users_2.png" alt="" />
                                            </span>
                                            <span class="da-summary-text">
                                                <span class="value">266</span>
                                                <span class="label">Customers Satisfied</span>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        -->
                        <div class="clear"></div>
                        
                    	<!--<div class="grid_2">
                        	<div class="da-panel">
                            	<div class="da-panel-header">
                                	<span class="da-panel-title">
                                        <img src="images/icons/color/wand.png" alt="" />
                                        Wizard Form
                                    </span>
                                    
                                </div>
                                <div class="da-panel-content">
                                	<form id="da-ex-wizard-form" class="da-form">
                                    	<fieldset class="da-form-inline">
                                        	<legend>Account</legend>
                                        	<div class="da-form-row">
                                            	<label>Username <span class="required">*</span></label>
                                                <div class="da-form-item large">
                                                	<input type="text" name="username" class="required" />
                                                </div>
                                            </div>
                                        	<div class="da-form-row">
                                            	<label>Email <span class="required">*</span></label>
                                                <div class="da-form-item large">
                                                	<input type="text" name="email" class="required email" />
                                                </div>
                                            </div>
                                        	<div class="da-form-row">
                                            	<label>Password <span class="required">*</span></label>
                                                <div class="da-form-item large">
                                                	<input type="password" name="password" class="required" />
                                                </div>
                                            </div>
                                        </fieldset>
                                    	<fieldset class="da-form-inline">
                                        	<legend>Member</legend>
                                        	<div class="da-form-row">
                                            	<label>Fullname <span class="required">*</span></label>
                                                <div class="da-form-item large">
                                                	<input type="text" name="fullname" class="required" />
                                                </div>
                                            </div>
                                        	<div class="da-form-row">
                                            	<label>Address <span class="required">*</span></label>
                                                <div class="da-form-item large">
                                                	<textarea name="address" class="required"></textarea>
                                                </div>
                                            </div>
                                        	<div class="da-form-row">
                                            	<label>Gender <span class="required">*</span></label>
                                                <div class="da-form-item large">
                                                	<ul class="da-form-list">
                                                    	<li><input type="radio" name="gender" class="required" /> <label>Male</label></li>
                                                    	<li><input type="radio" name="gender" /> <label>Female</label></li>
                                                    </ul>
                                                    <label for="gender" class="error" generated="true" style="display:none"></label>
                                                </div>
                                            </div>
                                        </fieldset>
                                    	<fieldset class="da-form-inline">
                                        	<legend>Membership</legend>
                                        	<div class="da-form-row">
                                            	<label>Membership Period <span class="required">*</span></label>
                                                <div class="da-form-item large">
                                                	<select name="period" class="required">
                                                    	<option>1 Month</option>
                                                    	<option>3 Months</option>
                                                    	<option>6 Months</option>
                                                        <option>1 Year</option>
                                                    </select>
                                                </div>
                                            </div>
                                        	<div class="da-form-row">
                                            	<label>Package <span class="required">*</span></label>
                                                <div class="da-form-item large">
                                                	<ul class="da-form-list">
                                                    	<li><input type="radio" name="package" class="required" /> <label>Basic</label></li>
                                                    	<li><input type="radio" name="package" /> <label>Full</label></li>
                                                    	<li><input type="radio" name="package" /> <label>Premium</label></li>
                                                    </ul>
                                                    <label for="package" class="error" generated="true" style="display:none"></label>
                                                </div>
                                            </div>
                                        </fieldset>
                                    	<fieldset class="da-form-inline">
                                        	<legend>Confirmation</legend>
                                        	<div class="da-form-row">
                                            	<label>Payment Method <span class="required">*</span></label>
                                                <div class="da-form-item large">
                                                	<select name="payment" class="required">
                                                    	<option>PayPal</option>
                                                    	<option>Visa</option>
                                                    	<option>Mastercard</option>
                                                        <option>Wire Transfer</option>
                                                    </select>
                                                </div>
                                            </div>
                                        	<div class="da-form-row">
                                                <div class="da-form-item large">
                                                	<ul class="da-form-list inline">
                                                    	<li><input type="checkbox" name="tos" class="required" /> <label>I agree to the terms of service <span class="required">*</span></label></li>
                                                    </ul>
                                                    <label for="tos" class="error" generated="true" style="display:none"></label>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                    	<div class="grid_2">
                        	<div class="da-panel">
                            	<div class="da-panel-header">
                                	<span class="da-panel-title">
                                        <img src="images/icons/color/calendar_2.png" alt="" />
                                        Holiday Calendar
                                    </span>
                                    
                                </div>
                                <div class="da-panel-content with-padding">
                                	<div id="da-ex-calendar-gcal"></div>
                                </div>
                            </div>
                        </div>-->
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
?>    