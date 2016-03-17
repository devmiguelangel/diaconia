<?php
include('header.inc.php');
?>
<div id="content-main">
	<section id="main">
<?php
if($token === TRUE){
	switch($ms){
	case md5('MS_COMP'):	//MENU ENTIDAD FINANCIERA
		switch($page){
		case md5('P_aboutus'):
			include('index-aboutus.inc.php');
			break;
		case md5('P_forms'):
			include('index-forms.inc.php');
			break;
		case md5('P_fAQs'):
			include('index-faqs.inc.php');
			break;
		case md5('P_contacts'):
			include('contact-us.inc.php');
			break;
		case md5('P_change_pass'):
			include('USR-data-change-password.php');
			break;
		}
		break;
	case md5('MS_DE'):		//MENU DESGRAVAMEN
		switch($page){
		case md5('P_issue'):	//EMISION DE COTIZACIONES
			$product = 'DE';
			require('issue-price.inc.php');
			break;
		case md5('P_app_ok'):	//SOLICITUDES PREARPOBADAS
			$product = 'DE';
			require('pre-approved-app.inc.php');
			break;
		case md5('P_cancel'):	//ANULACION DE POLIZAS
			$product = 'DE';
			require('annulment.inc.php');
			break;
		
		case md5('P_quote'):
			$seguro = 'DE';
			require('content-quote.inc.php');
			break;
		case md5('P_fac'):
			if ($user_type === 'FAC') {
				$seguro = 'DE';
				require('FAC-DE-records.inc.php');
			} else {
				echo '<meta http-equiv="refresh" content="0;url=index.php" >';
			}
			break;
		case md5('P_app_imp'):	//ARPOBADAS IMPLANTE
			if ($user_type === 'IMP') {
				$product = 'DE';
				require('approved-imp.inc.php');
			} else {
				echo '<meta http-equiv="refresh" content="0;url=index.php" >';
			}
			break;
		default:
			echo '<meta http-equiv="refresh" content="0;url=de-quote.php" >';
			break;
		}
		break;
	case md5('MS_AU'):		//MENU AUTOMOTORES
		switch($page){
		case md5('P_issue'):	//EMISION DE COTIZACIONES
			$product = 'AU';
			require('issue-price.inc.php');
			break;
		case md5('P_app_ok'):	//SOLICITUDES PREARPOBADAS
			$product = 'AU';
			require('pre-approved-app.inc.php');
			break;
        case md5('P_app_pe'):	//SOLICITUDES PENDIENTES
			$product = 'AU';
			require('pre-approved-pe.inc.php');
			break;
		case md5('P_cancel'):	//ANULACION DE POLIZAS
			$product = 'AU';
			require('annulment.inc.php');
			break;
		
		case md5('P_quote'):
			$seguro = 'AU';
			require('content-quote.inc.php');
			break;
		case md5('P_fac'):
			if ($user_type === 'FAC') {
				$seguro = 'AU';
				require('FAC-AU-records.inc.php');
			} else {
				echo '<meta http-equiv="refresh" content="0;url=index.php" >';
			}
			break;
		case md5('P_app_imp'):	//ARPOBADAS IMPLANTE
			if ($user_type === 'IMP') {
				$product = 'AU';
				require('approved-imp.inc.php');
			} else {
				echo '<meta http-equiv="refresh" content="0;url=index.php" >';
			}
			break;
		case md5('P_provisional'):	//CERTIFICADO PROVISIONAL
			$product = 'AU';
			require('cprovisional.inc.php');
			break;
		default:
			echo '<meta http-equiv="refresh" content="0;url=au-quote.php" >';
			break;
		}
		break;
	case md5('MS_TRD'):		//MENU TODO RIESGO DOMICILIARIO
		switch($page){
		case md5('P_issue'):	//EMISION DE COTIZACIONES
			$product = 'TRD';
			require('issue-price.inc.php');
			break;
		case md5('P_app_ok'):	//SOLICITUDES PREARPOBADAS
			$product = 'TRD';
			require('pre-approved-app.inc.php');
			break;
        case md5('P_app_pe'):	//SOLICITUDES PENDIENTES
			$product = 'TRD';
			require('pre-approved-pe.inc.php');
			break;
		case md5('P_cancel'):	//ANULACION DE POLIZAS
			$product = 'TRD';
			require('annulment.inc.php');
			break;
		
		case md5('P_quote'):
			$seguro = 'TRD';
			require('content-quote.inc.php');
			break;
		case md5('P_fac'):
			$seguro = 'TRD';
			//require('FAC-AU-records.inc.php');
			echo '<meta http-equiv="refresh" content="0;url=index.php" >';
			break;
		case md5('P_app_imp'):	//ARPOBADAS IMPLANTE
			if ($user_type === 'IMP') {
				$product = 'TRD';
				require('approved-imp.inc.php');
			} else {
				echo '<meta http-equiv="refresh" content="0;url=index.php" >';
			}
			break;
		case md5('P_provisional'):	//CERTIFICADO PROVISIONAL
			$product = 'TRD';
			require('cprovisional.inc.php');
			break;
		default:
			echo '<meta http-equiv="refresh" content="0;url=au-quote.php" >';
			break;
		}
		break;
	case md5('MS_TRM'):		//MENU TODO RIESGO EQUIPO MOVIL
		switch($page){
		case md5('P_issue'):	//EMISION DE COTIZACIONES
			$product = 'TRM';
			require('issue-price.inc.php');
			break;
		case md5('P_app_ok'):	//SOLICITUDES PREARPOBADAS
			$product = 'TRM';
			require('pre-approved-app.inc.php');
			break;
        case md5('P_app_pe'):	//SOLICITUDES PENDIENTES
			$product = 'TRM';
			require('pre-approved-pe.inc.php');
			break;
		case md5('P_cancel'):	//ANULACION DE POLIZAS
			$product = 'TRM';
			require('annulment.inc.php');
			break;
		
		case md5('P_quote'):
			$seguro = 'TRM';
			require('content-quote.inc.php');
			break;
		case md5('P_fac'):
			if ($user_type === 'FAC') {
				$seguro = 'TRM';
				require('FAC-TRM-records.inc.php');
			} else {
				echo '<meta http-equiv="refresh" content="0;url=index.php" >';
			}
			break;
		case md5('P_app_imp'):	//ARPOBADAS IMPLANTE
			if ($user_type === 'IMP') {
				$product = 'TRM';
				require('approved-imp.inc.php');
			} else {
				echo '<meta http-equiv="refresh" content="0;url=index.php" >';
			}
			break;
		case md5('P_provisional'):	//CERTIFICADO PROVISIONAL
			break;
		default:
			echo '<meta http-equiv="refresh" content="0;url=au-quote.php" >';
			break;
		}
		break;
	case md5('MS_TH'):
		switch($page){
		case md5('P_quote'):
			$seguro = 'TH';
			require('content-quote.inc.php');
			break;
		default:
			echo '<meta http-equiv="refresh" content="0;url=th-quote.php" >';
			break;
		}
		break;
	case md5('MS_REP'):		//MENU REPORTES
		switch($page){
		case md5('P_general'):
			require('RP-general.inc.php');
			break;
		case md5('P_general_prop'):
			require('RP-general-prop.inc.php');
			break;
		case md5('P_policy'):
			require('RP-policy.inc.php');
			break;
		case md5('P_quote'):
			require('RP-quote.inc.php');
			break;
		default:
			echo '<meta http-equiv="refresh" content="0;url=index.php" >';
			break;
		}
		break;
	default:
		if (($user_type === 'LOG' || $user_type === 'IMP')) {
			include('FAC-optional-case.php');
		} else {
			include('index-content.inc.php');
		}
		break;
	}
}else{
	if($ms !== NULL){
		switch($ms){
		case md5('MS_COMP'):	//MENU COMPAÑIA
			switch($page){
			case md5('P_aboutus'):
				include('index-aboutus.inc.php');
				break;
			case md5('P_forms'):
				include('index-forms.inc.php');
				break;
			case md5('P_fAQs'):
				include('index-faqs.inc.php');
				break;
			case md5('P_contacts'):
				include('contact-us.inc.php');
				break;
			}
			break;
		case md5('MS_DE'):		//MENU DESGRAVAMEN
			switch($page){
			case md5('P_quote'):
				$seguro = 'DE';
				require('content-quote.inc.php');
				break;
			case md5('P_fac'):
				if(isset($_GET['ide'])) {
					include('index-content.inc.php');
				} else {
					echo '<meta http-equiv="refresh" content="0;url=index.php" >';
				}
				break;
			}
			break;
		case md5('MS_AU'):		//MENU AUTOMOTORES
			switch($page){
			case md5('P_quote'):
				$seguro = 'AU';
				require('content-quote.inc.php');
				break;
			case md5('P_fac'):
				if(isset($_GET['ide'])) {
					include('index-content.inc.php');
				} else {
					echo '<meta http-equiv="refresh" content="0;url=index.php" >';
				}
				break;
			}
			break;
		case md5('MS_TRD'):		//MENU TODO RIESGO DOMICILIARIO
			switch($page){
			case md5('P_quote'):
				$seguro = 'TRD';
				require('content-quote.inc.php');
				break;
			}
			break;
		case md5('MS_TRM'):		//MENU TODO RIESGO EQUIPO MOVIL
			switch($page){
			case md5('P_quote'):
				$seguro = 'TRM';
				require('content-quote.inc.php');
				break;
			case md5('P_fac'):
				if(isset($_GET['ide'])) {
					include('index-content.inc.php');
				} else {
					echo '<meta http-equiv="refresh" content="0;url=index.php" >';
				}
				break;
			}
			break;
		case md5('MS_TH'):
			switch($page){
			case md5('P_quote'):
				$seguro = 'TH';
				require('content-quote.inc.php');
				break;
			default:
				echo '<meta http-equiv="refresh" content="0;url=th-quote.php" >';
				break;
			}
		}
	}else{
		include('index-content.inc.php');
	}
}
?>
	</section>
</div>
<?php
include('footer.inc.php');
?>