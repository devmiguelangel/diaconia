<?php
function trd_em_certificate_mo_trdm ($link, $row, $rowDt, $url, $implant, $fac, $reason = '') {
	$prefix = json_decode($rowDt['prefix'], true);
?>
<div class="container-c-des" style="width:790px;">
<?php

/*--------------------STYLE--------------------*/
$main_c_des='width: 770px; height: auto; margin-top:55px; margin-left:15px; padding: 0;';

$message='margin: 0; padding: 0 0 0 10px; text-align: left; font-weight: bold; font-size: 11px; font-family: Arial;';
$message2='font-weight: bold; font-size: 13px; font-family: Arial; padding: 0; text-align: center;'; 
$header='widows: auto; height: 100px;';
$header_img='margin: 0;	float: left;';
$header_h1='text-align: center;	margin-left:18px; font-weight: bold; font-size: 10px; font-family: Arial;';
$pag_2='margin-bottom /*\**/: 150px\9 ';
$pag_4='margin-bottom /*\**/: 170px\9 ';
$h1_s='text-align: center; font-weight: bold; font-size: 14px; font-family: Arial; margin: 0;';
$h4_s='text-align: center; font-weight: bold; font-size: 9px; font-family: Arial; margin: 0;';
$header_h4='text-align:center; margin: -5px 0 0 20px; float: center; font-weight: bold; font-size: 10px; font-family: Arial;';
$footer_h1='text-align: left;	font-weight: bold; font-size: 12px; font-family: Arial;';

$container_1='width: 770px; height: auto; margin: 0 0 5px 0;';
$h2='width: auto; height: auto; text-align: left; margin: 0; font-weight: normal; font-size: 9px; font-family: Arial; text-align:justify;';
$h2_s='width: auto; height: auto; text-align: left; margin: 0; font-weight: normal; font-size: 9px; font-family: Arial; text-align:center;';
$h2_s1='margin: 0 0 10px 40px; font-weight: normal; font-size: 12px; font-family: Arial;';

$content='width: 770px; height: auto; margin: 0 0 5px 0; padding: 0px 0px; font-weight: bold; font-size: 8px; font-family: Arial; text-align: left;'; 
$content2='text-align: center; font-weight: normal; font-size: 9px; font-family: Arial;';
$content3='text-align: center; font-weight: normal; font-size: 9px; font-family: Arial; border-top:1px solid #000;';

$h3='width: auto; height: auto;	font-weight: bold; font-size: 10px; font-family: Arial; margin: 0;';
$h3_s='font-weight: bold; font-size: 8px; font-family: Arial;';

$h4='width: 100%; height: auto;	font-weight: bold; font-size: 10px; font-family: Arial; margin: 0; text-align:center;';
$h4_s='width: 100%; height: auto; margin:4px; font-weight:normal; font-size: 10px; font-family: Arial; text-align:center;';

/*-----------Contenido 1-----------*/
$table='width: 100%; font-weight: normal; font-size: 10px; font-family: Arial; margin: 2px 0 0 0; padding: 0; border-collapse: collapse; vertical-align: bottom;';
$table_p='font-weight: normal; font-size: 9px; font-family: Arial; text-align: justify; margin: 2px 0;'; 
$tab='text-indent: 20px;';
$title_regla=' margin-left: 17px; font-weight: normal; text-decoration: none;';
$title_regla1=' margin-left: 5px; font-weight: normal; text-decoration: none;';
$table_borde_tr_td='border-bottom: 1px dashed #080808; width:100%;';
$table_borde12_tr_td='border: 1px solid #080808; padding: 3px 8px;';
$table_borde2_tr_td= $table.' border-bottom: 1px solid #080808; width:100%;';
$table_borde3_tr_td= $table.' width:100%; text-align:center;';
$table_preg_tr_td='height: 20px;';
$table_sepelio_tr_td='border: 1px solid #080808; height: 15px;';
$table_borde_sup='width: 100%; border-top: 2px solid #000000;';

$input_inf='display: inline-block; font-weight: bold; font-size: 8px; font-family: Arial; border-bottom: 1px solid #777777;';
$input_check='display: inline-block; width: 15px; height: 15px; margin: 2px 0 0 0; text-align: center; vertical-align: baseline; border: 1px solid #0F0F0F;';
$input_question='border: 1px solid #0F0F0F; display: inline-block; #display: inline; _display: inline; width: 10px; height: 8px; margin: 2px 0 0 0; padding: 1px; text-align: center; zoom: 1;';
$cita='display: inline-block; margin: 0 0 0 100px;';
$firma='position: relative; margin-bottom: -20px; z-index: -1000;';
$font_bold='font-weight: bold;';

?>
	<div style="<?=$main_c_des;?>">
		<div style="<?=$header;?>">
        	<h1 style="<?=$header_h1;?>">
                CERTIFICADO INDIVIDUAL DE COBERTURA<br>

				AL CONTRATO DE SEGURO DE TODO RIESGO DE DA&Ntilde;OS A LA PROPIEDAD PARA GARANTIAS PRENDARIAS CON PRIMA MENSUAL<br>
            </h1>           
            <h4 style="<?=$header_h4;?>">				
				C&oacute;digo Asignado: 115-910194-2010 07 084-4001<br>
                Resoluci&oacute;n ASFI N&deg; 669/2010 de fecha 09 de agosto de 2010
            </h4><br>			
			<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                <tr>
                	<td style="width:50%;">
                        <h4 style="<?=$header_h1;?> font-size:11px;">
                        	P&oacute;liza N&deg; 
                        	<?=$prefix['policy'];?>
                        </h4>
                    </td>                	
                    <td style="width:50%;">
                    <h4 style="<?=$header_h1;?> font-size:11px;">
                   		Certificado de Cobertura N&deg;
                   		<?=$rowDt['prefijo'] . '-' . $rowDt['no_detalle'];?>
                    </h4>
                    </td>
                </tr>
           </table>	
        </div>
        
        <div style="<?=$container_1;?>">
        	<div style="<?=$content;?>">
            <h2 style="<?=$h2;?>">
            	Se deja expresa constancia mediante el presente certificado, 
            	que las existencias de mercaderias a valor de costo de acuerdo 
            	a aval&uacute;o y/o inventario de $us <?=number_format($rowDt['pr_valor_asegurado'], 2, '.', ',');?> 
            	constituido en garant&iacute;a prendaria del banco 
            	Econ&oacute;mico S.A. por el cr&eacute;dito otorgado 
            	N&deg; <?=$rowDt['no_detalle'];?> a favor de: 
            	<?=$row['cl_nombre'];?> se encuentra amparado bajo la p&oacute;liza 
            	Todo Riesgo de Da&ntilde;os a la Propiedad para garantias 
            	Prendarias con Prima Mensual N&deg; <?=$prefix['policy'];?> de 
            	acuerdo a los t&eacute;rminos y condiciones estipulados en el 
            	mencionado Contrato de Seguros suscrito entre el "Tomador" y el 
            	"Asegurador".
            </h2><br>
                <h3 style="<?=$h3;?> text-decoration:underline;">COBERTURAS</h3>
                <h2 style="<?=$h2;?>">Bajo esta p&oacute;liza, la Compa&ntilde;&iacute;a acuerda cubrir la propiedad asegurada contra los riesgos de p&eacute;rdidas y/o da&ntilde;o y/o destrucci&oacute;n f&iacute;sica s&uacute;bita y accidental, atribuible a cualquier causa que no est&eacute; expresamente excluida, incluyendo m&aacute;s no limitando las siguientes coberturas, que solo se enuncian para fines de incorporaci&oacute;n en caso se encuentren excluidas en las Condiciones Generales de la p&oacute;liza y por tanto, no limitan ni modifican el formato de la p&oacute;liza de "Todo Riesgo" contratada bajo la presente p&oacute;liza.<br><br>
                
                Explosion de cualquier naturaleza y por cualquier causa excepto radioactiva o nuclear, que se genere dentro o fuera de los locales y/o predios; Sabotaje; Ca&iacute;da de aeronaves u objetos que caigan de ellas; Ventarron, huracan, tempestad y granizo; Da&ntilde;os por lluvia e Inundacion; Da&ntilde;os por agua, Riagas y/o todos, da&ntilde;os por humo,; Hundimiento y asentamiento; Deslizamiento y desploe; impacto de vehiculos propios y ajenos; Derrame de instalaciones de rociadores; Combustion expontanea; riesgo de refrigeraci&oacute;n hasta $us. 50.000,00; Incendio y/o RAyo en instalaciones y/o aparatos el&eacute;ctricos, Corto circuito por cualquier causa incluyendo; Huelgas, Motines, Conmociones Civiles; Da&ntilde;o malicioso, vandalismo incluyendo Terrorismo, Tumultos Populares; hasta $us. 1.500.000,00 y terremoto, temblor y/o erupci&oacute;n volc&aacute;nica hasta $us 1.500.000,00 robo y/o asalto, hasta $us 400.000,00 para todos los amparos excepto: joyas, piedras preciosas y relojes, hasta $us 10.000,00; veh&iacute;culos limitando hasta $us. 50.000,00, aclar&aacute;ndose que &uacute;nicamente tendr&aacute;n cobertura si los mismos se encuentran dentreo de los predios declarados.</h2><br />
                
                <h3 style="<?=$h3;?>">COBERTURAS ADICIONALES</h3>
                <h2 style="<?=$h2;?>">Gastos extraordinarios: remoci&oacute;n de escombros, documentos y modelso, hnorarios de arquitectos, top&oacute;grafos e ingenieros hasta $us. 300.000,00</h2><br>
                
                <h2 style="<?=$h2;?>"><span style="text-decoration:underline;">TASAS Y FORMAS DE PAGOS:</span> Tasas aplicables sobre los valores asegurados establecidos en lso Aval&uacute;os y/o inventarios valorados. La prima ser&aacute; cancelada por el "Tomador" cada mes vencido y a su vez el "Tomador" cargar&aacute; el costo de este seguro por adelantado en las cuotas de amortizaci&oacute;n de cada prestatario en funci&oacute;n de su cronograma de pago.</h2><br>
                
                <h4 style="<?=$h4_s;?>">TASA &nbsp; &nbsp; &nbsp; &nbsp; 0.141 &nbsp; &nbsp; &nbsp; &nbsp; %(POR MIL)</h4><br>
                
                <h3 style="<?=$h3;?> text-decoration:underline;">MATERIA ASEGURADA</h3>
                <h2 style="<?=$h2;?>">Existencias de mercader&iacute;as en general recibidas en garantia prendaria por el tomador con o sin desplazamiento consistentes principalmente pero no limitados a: Muebles y enseres, telas en general, ropa de vestir, calzados y accesorios de distintos tipos y marcas, maquinas, equipos y complemento electr&oacute;nicos y/o digitales y/o de cualquier otra naturaleza fijos y/o moviles y/o portatiles (equipos de computaci&oacute;n, impresoras, c&aacute;maras, tel&eacute;fonos, centrales telef&oacute;nicas); maquinas electromecanicas, electricas, o mec&aacute;nicas en general y/o cualquier otra naturaleza (fijas y/o m&oacute;viles y/o port&aacute;tiles) incluyendo sus componentes, accesorios (heladeras, refrigeradores, aires acondicionados, lavadoras, secadoras, linea blanca y electrodom&eacute;sticos en general); cueros, lanas, papel, cart&oacute;n pl&aacute;sticos, granos, aceite crudo, harina de soya y girasol, vehiculos en general, motocicletas, herramientas, repuestos, equipos para gimnasios, equipos m&eacute;dicos, productos farmac&eacute;uticos; vidrios, cables, productos e insumos qu&iacute;micos en general, joyas, piedras preciosas, relojes y cualquier otro bien que no haya especificamente descrito pero que forme parte de la materia asegurada.</h2>	<br>
                    
                <h3 style="<?=$h3;?>">ACTIVIDADES DECLARADAS Y/O GIROS DEL NEGOCIO</h3>
                <h2 style="<?=$h2;?>">Sin prohibici&oacute;n ni exclusi&oacute;n, ni restricci&oacute;n de giros de negocio y/o actividades que se desarrollen para la utilizaci&oacute;n de los bienes asegurados; excluyendo algodoneras y algod&oacute;n, equipos y empresas petroleras, aeronaves.</h2><br />
                
                <h3 style="<?=$h3;?>">CONTRATO PRINCIPAL (P&Oacute;LIZA MATRIZ)</h3>
                <h2 style="<?=$h2;?>">LATINA SEGUROS PATRIMONIALES S.A., Compa&ntilde;&iacute;a de Seguros y Reaseguros asegura al Prestatario, seg&uacute;n las condiciones descritas en el Contrato del Seguro de Todo Riesgo de Da&ntilde;os a la Propiedad para Garantias Prendarias con Prima Mensual; celebrando entre LATINA Seguros Patrimoniales S.A. y el Banco Economico S.A. condiciones que forman parte integrante del seguro aqu&iacute; concedido.<br />
                El presente Certificado de Seguro tendr&aacute; valor siempre y cuando la p&oacute;liza este vigente y sus primas se encuentren pagadas de acuerdo a las Condiciones de la p&oacute;liza.</h2><br />
                
                <h3 style="<?=$h3;?>">ADHESION VOLUNTARIA DEL ASEGURADO</h3>
                <h2 style="<?=$h2;?>">El asegurado al momento de concretar el cr&eacute;dito con el Contratante declara su consentimiento VOLUNTARIO para ser asegurado en la p&oacute;liza arriba indicada y declara conocer y estar de acuerdo con las condiciones del contrato de seguros.</h2>
                <h4 style="<?=$h4;?>">"LATINA" SEGUROS PATRIMONIALES S.A.</h4>
               <table cellpadding="0" cellspacing="0" border="0" style="<?=$table;?> text-align:center; width:100%;" align="center">                    <tr><td colspan="7">&nbsp;</td></tr>
                    <tr><td colspan="7">&nbsp;</td></tr>
                    <tr><td colspan="7">&nbsp;</td></tr>
                    
               		<tr>
                        <td style="width:10%;">&nbsp;</td>                        
                        <td style="width:20%;">
                            FIRMA DEL ASEGURADO
                        </td>
                        <td style="width:10%;">&nbsp;</td>
                        <td style="width:20%;">
                            <table border="0" cellpadding="0" cellspacing="0" style="<?=$table_borde_tr_td;?>"><tr>
                                <td style="width: 100%;"></td>
                            </tr></table>
                        </td>
                        <td style="width:10%;">&nbsp;</td>
                        <td style="width:20%;">
                            <table border="0" cellpadding="0" cellspacing="0" style="<?=$table_borde_tr_td;?>"><tr>
                                <td style="width: 100%;"></td>
                            </tr></table>
                        </td>
                        <td style="width:10%;">&nbsp;</td>
                    </tr>                            
                </table>            
            </div><br>
           
           <div style="<?=$content2;?>">
           EN CASO DE SINIESTRO S&Iacute;RVASE CONTACTARSE DIRECTAMENTE CON LA CIA O CON "EL BROKER":<br><br><br><br>
				<h2 style="<?=$h2_s;?>">
                	<b>Call Center 800 - 10 - 3070</b><br>
                	<b>La Paz, </b> Prolongacion Cordero N&deg; 163 - San Jorge, Telf.: (591)-2-2433500, Fax(591)-2-2128329<br>
                	<b>Santa Cruz,</b> Equipetrol Calle N&deg; 6 Este N&deg; 21 Entre Canal Isuto y Avenida San Martin, Telf.: (591)-3-3416055, Fax: (591)-3-3416056<br>
                    <b>Cochabamba,</b> Calle 16 de Julio No. 823 entre La Paz y Ram&oacute;n Rivero, Telf.: (591)-4-4521280, Fax:(591)-4-4521281<br>
                    <b>Sucre,</b> Calle Bolivar N&deg; 266 Ed. Bolivar Pb, Telf.:(591)-4-6429081, (591)-4-6913797, Fax(591)-4-6435348<br>
                    <b>Potos&iacute;,</b> Calle Bolivar esq. Junin shopping C&C piso 3 of. 26, Telfs.: 6227293, 6225768, Fax: 6223145<br>
                    <b>Tarija,</b> Calle Santa Cruz No. 830 entre Domingo Paz y Bolivar, Telf./Fax: 6672014
                </h2>

           </div><br>
           
           <div style="<?=$content;?>">
           	<h2 style="<?=$h2;?>">He recibido mi Certificado Individual de Cobertura y afiliaci&oacute;n al Contrato de Seguro de Todo Riesgo de Da&ntilde;os a la Propiedad para Garantias Prendarias con Prima Mensual Renovable, con cuyos t&eacute;rminos y condiciones estoy de acuerdo y declaro conocer.</h2><br><br>
           
               <table cellpadding="0" cellspacing="0" border="0" style="width:100%; text-align:left;">
                    <tr>
                        <td style="width:50%;" colspan="2">
                            <h4 style="<?=$footer_h1;?> font-size:11px; text-align:left;">
                            	P&oacute;liza N&deg; 
                            	<?=$prefix['policy'];?>
                            </h4>
                        </td>                	
                        <td style="width:50%;">
                        <h4 style="<?=$footer_h1;?> font-size:11px;">
                        	Certificado de Cobertura N&deg;
                        	<?=$rowDt['prefijo'] . '-' . $rowDt['no_detalle'];?>
                        </h4>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:5%;"><h4 style="<?=$footer_h1;?> font-size:11px;">
                        Nombre:
                        </h4></td>
                        <td style="width:45%;">
                            <table border="0" cellpadding="0" cellspacing="0" style="<?=$table_borde2_tr_td;?>"><tr>
                               	<td style="width: 100%;">
                               		&nbsp;<?=$row['cl_nombre'];?>
                               	</td>
                            </tr></table>
                        </td>                	
                        <td style="width:50%;">
                        <h4 style="<?=$footer_h1;?> font-size:11px;">
                        	Fecha de Afiliaci&oacute;n:
                        	<?=date('d/m/Y', strtotime($row['fecha_emision']));?>
                        </h4>
                        </td>
                    </tr>
                    <tr>
                    	<td style="width:50%;" colspan="2">&nbsp;</td>
                        <td style="width:50%;">
                        	<table style="font-size:9px;" width="100%">
                            	<tr>
                                	<td width="49%">
                                    	<table border="0" cellpadding="0" cellspacing="0" style="<?=$table_borde2_tr_td;?>"><tr>
                                            <td style="width: 100%;">&nbsp;</td>
                                        </tr></table>
                                    </td>
                                    <td width="2%">&nbsp;</td>
                                    <td width="49%">
                                        <table border="0" cellpadding="0" cellspacing="0" style="<?=$table_borde2_tr_td;?>"><tr>
                                            <td style="width: 32%;">&nbsp;</td>
                                            <td style="width: 2%;">/</td>
                                            <td style="width: 32%;">&nbsp;</td>
                                            <td style="width: 2%;">/</td>
                                            <td style="width: 32%;">&nbsp;</td>
                                        </tr></table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>FIRMA DEL ASEGURADO<br>
                                    	C.I. <?=$row['cl_ci'];?>
                                    </td>
                                    <td>&nbsp;</td>
                                    <td valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" style="<?=$table_borde3_tr_td;?>"><tr>
                                            <td style="width: 32%;">D&iacute;a</td>
                                            <td style="width: 2%;">&nbsp;</td>
                                            <td style="width: 32%;">Mes</td>
                                            <td style="width: 2%;">&nbsp;</td>
                                            <td style="width: 32%;">A&ntilde;o</td>
                                        </tr></table>                                
                                    </td>
                             	</tr>  
                            </table>
                        </td>
                    </tr>
               </table>
           </div>
           <div style="<?=$content3;?>">
           		SANTA CRUZ: Av Monse&ntilde;or Rievero, 223-2 &bull; Tel&eacute;fono: 371-6565 &bull; Fax: 371-6905 &bull; LA PAZ: Calle Capit&aacute;n Ravelo, 2334 &bull; Tel&eacute;fono: 244-2942 &bull; Fax: 244-2905 &bull; COCHABAMBA: Av. Libertador, 1150 Tel&eacute;fono: 444-8750 &bull; Fax: 444-8754
           </div>
        </div>
        <div style="page-break-before: always;"> &nbsp;</div>
        <div style="<?=$container_1;?>">
            <div style="<?=$content;?>"><br /><br />
                <h2 style="<?=$h2_s1;?> text-align: center;">
                    La adhesion a esta p&oacute;es de car&aacute;cter voluntario y puede ser reemplazado por otro de similares caracteristicas<br>
                    <strong>REGLAS DEL CONTRATO DE SEGURO DE TODO RIESGO DE DA&Ntilde;OS A LA PROPIEDAD PARA GARANTIAS PRENDARIAS CON PRIMA MENSUAL</strong>
                </h2><br />
                <table border="0" cellpadding="0" cellspacing="0" style="text-align: justify; font-size: 8px; width: 100%;">
                    <tr>
                        <td style="width: 50%; padding: 5px;" valign="top">
                            <span>I.</span>
                            <span style="<?=$title_regla;?>">DEFINICIONES</span><br />
                            <span>1.01</span>
                            <span style="<?=$title_regla1;?>">EL ASEGURADOR:</span> LATINA SEGUROS PATRIMONIALES S.A.<br />
                            
                            <span>1.02</span>
                            <span style="<?=$title_regla1;?>">TOMADOR Y BENEFICIARIO DEL SEGURO:</span><strong>BANCO ECONOMICO S.A.</strong><br />
                            
                            <span>1.03</span>
                            <span style="<?=$title_regla1;?>">ASEGURADOS:</span> 
                            Prestatarios del Tomador.<br />
                            
                            <span>1.04</span>
                            <span style="<?=$title_regla1;?>">UBICACIONES DEL RIESGO:</span>
                            Todas las ubicaciones que figuren en los reportes mensuales del Tomador y/o en los contratos de pr&eacute;stamo dentro del territorio del Estado Plurinacional de Bolivia.<br /> 
                            
                            <span>II.</span>
                            <span style="<?=$title_regla;?>">INICIO DE VIGENCIA DE LA COBERTURA PARA CADA ASEGURADO</span><br />
                            <span>2.01</span>
                            &nbsp; Operaciones nuevas: desde la fecha de desembolso del cr&eacute;dito sobre el cual se constituy&oacute; la garantia.<br />
                            <span>2.02</span>
                            &nbsp; Operaciones aseguradas por el Tomador antes del inicio de la vigencia de esta p&oacute;liza: desde el inicio de la vigencia de la presente p&oacute;liza y/o renovaci&oacute;n.<br />
                            <span>2.03</span>
                            &nbsp; Bienes en garantia asegurados a trav&eacute;s de otros contratos de seguro subrogados a favor del Tomador, contratados en forma particular por prestatarios con operaciones vigentes: desde la fecha de vencimiento del contrato de seguros tomando en forma particular por le prestatario y presentado como garantia al tomador.<br />
                            
                            <span>III.</span>
                            <span style="<?=$title_regla;?>">FINALIZACI&Oacute;N DE LA COBERTURA PARA CADA ASEGURADO</span><br />
                            <span>3.01</span>
                            &nbsp; En la fecha de vencimiento de la p&oacute;liza, salvo la misma haya sido renovada por el tomador a su vencimiento (sin perjuicio de que las condiciones pueden modificarse en cada renovaci&oacute;n).<br />
                            
                            <span>3.02</span>
                            &nbsp; La fecha de cancelaci&oacute;n y/o cesaci&oacute;n de la obligaci&oacute;n del prestatario con el tomador: transcurridos 30 d&iacute;as adicionales contados desde la fecha en la cuel el tomador liber&oacute; dichas garantias, aunque dichos d&iacute;as adicionales superen la fecha de vencimiento de esta p&oacute;liza.<br />
                                                        
                            <span>3.03</span>
                            &nbsp; Por incumplimiento en el pago de sus primas.<br />
                            
                            <span>3.04</span>
                            &nbsp; En el momento en que el bien materia del seguro pierde su condici&oacute;n de garantia prendaria del tomador por cualquier motivo distinto al anteriormente mencionado.<br />
                            
                            <span>IV.</span>
                            <span style="<?=$title_regla;?>">LA P&Oacute;LIZA</span><br />
                            <span>4.01</span>
                            &nbsp; No se pagar&aacute; ninguna indemnizaci&oacute;n conforme a estas reglas si la suma asegurada correspondiente no resultara pagadera con arreglo a las condiciones de la P&oacute;liza.<br />
                            
                            <span>4.02</span>
                            &nbsp; La informaci&oacute;n completa referente y las estipulaciones del seguro se detallan en la P&oacute;liza Madre emitida por Latina Seguros Patrimoniales S.A. a favor del Banco Econ&oacute;mico S.A.<br />
                            
                            <span>V.</span>
                            <span style="<?=$title_regla;?>">MODIFICACI&Oacute;N O TERMINACI&Oacute;N</span><br />
                            <span>5.01</span>
                            &nbsp; Las condiciones estipuladas en el presente contrato, permanecer&aacute;n inalterables durante el periodo de vigencia de la p&oacute;liza, salvo que el "Tomador" y "Asegurador", puedan convenir modificaciones mediante anexo.<br />
                            
                            <span>5.02</span>
                            &nbsp; El "Tomador" y el "Asegurador" se reserven el derecho de finalizar el Contrato en el marco de lo se&ntilde;alado en dicho Contrato y C&oacute;digo de Comercio.<br />
                            
                            <span>VI.</span>
                            <span style="<?=$title_regla;?>">RESPONSABILIDAD M&Aacute;XIMA DEL "ASEGURADOR" EN CASO DE SINIESTRO</span><br />
                            <span>6.01</span>
                            &nbsp; La responsabilidad m&aacute;xima del Asegurador en caso de siniestro elequivalente a Valor de Costo que indistintamente tenga cada garant&iacute;a prendaria y/o materia Asegurada incorporada en la p&oacute;liza por el Tomador seg&uacute;n establecen los aval&uacute;os y/o inventarios que avalan las operaciones crediticias. En ningun caso, la suma indemnizable por el Asegurador bajo este seguro superar&aacute; el valor de costo mencioado para cada materia asegurada. Independientemente a lo se&ntilde;alado, el limite m&aacute;ximo de responsabilidad del Asegurador por las garantias de cada una de las materias aseguradas incorporadas por el Tomador no superar&aacute; en ning&uacute;n caso la suma de US$ 1'500.000,00 (UN MILLON QUINIENTOS MIL 00/100 DOLARES AMERICANOS POR UNICACI&Oacute;N). Las coberturas a primer riesgo son aquellas contratadas con un limite de responsabilidad a cargo del Asegurador.<br />
                            
                            <span>6.02</span>
                            &nbsp; En caso de que el "Tomador", como administrador colectivo, cargase primas por capitales asegurados superiores a la responsabilidad m&aacute;xima del "Asegurador", la responsabilidad del "Tomador" y el "Asegurador" frente al asegurado y/o sus representantes se limitaran a la devoluci&oacute;n de las primas cobradas y pagadas en exceso.<br />
                            
                            <span>VII.</span>
                            <span style="<?=$title_regla;?>">RESTRICCIONES Y EXCLUSIONES</span><br />
                            No obstante las restricciones  exclusiones estipuladas en las condiciones generales de la p&oacute;liza se enumeran a continuaci&oacute;n con car&aacute;cter enunciativo y limitativo las siguientes:<br />                            
                            <ol style="list-style:square">
                            	<li>Se excluyen los hechos en conexi&oacute;n o con motivos de hostilidades, acciones u operaciones de guerra o invacion de enemigo extranjero (haya o no declaraci&oacute;n de estado de guerra) o guerra interna, revoluci&oacute;n, rebeli&oacute;n, insurrecci&oacute;n, terrorismo y otros hechos y delitos contra la seguridad interior o exterior del pa&iacute;s, aunque nos ean a mano armada, o bien de la administracion o gobierno de cualquier territorio o zona de estado de sitio o de suspencion de garantias bajo el control de autoridades militares o de acontecimientos que originen estas situaciones de hecho o de derecho o que ellos deriven directa o indirectamente relacionados con ellos, como quiera y donde quiera que se originen.</li>
                                <li>Da&ntilde;os que no sean originados en forma s&uacute;bita e imprevista.</li>
                                <li>Actos de autoridad, tales como: decomiso, apropiaci&oacute;n, expropiaci&oacute;n o requisici&oacute;n.</li>
                                <li>Las perdidas o da&ntilde;os que directa o indirectamente provengan de siniestros causados por incendiarismo y/o piroman&iacute;a, dolo, mala fe o culpa grave del asegurado, sus apoderados, sus representantes legales, personal directivo a quienes se les haya otorgado la direcci&oacute;n y control de la empresa, sus beneficiarios y personas por quienes sea civilmente responsable.</li>
                                <li>P&eacute;rdidas o da&ntilde;os indirectos o consecuenciales o por lucro cesante de cualquier tipo.</li>
                                <li>Contaminaci&oacute;n, poluci&oacute;n s&uacute;bita o gradual</li>
                                <li>Perdidas directas o indirectamente causadas por promulgaci&oacute;n de cualquier ley u ordenanza municipal que regule la construcci&oacute;n, reparaci&oacute;n o demolicion de edificios y constructuras.</li>
                                <li>Se excluyen algodoneras y algodon; equipos y empresas petroleras; aeronaves; siembras, animales vivos, terrenos.</li>
                                <li>P&eacute;rdidas o da&ntilde;os a titulos, obligaciones o documentos de cualquier clase, timbres postales, monedas, billetes de banco, cheques, letras, pagar&eacute;s, libros de contabilidad u otros libros de comercio, tributaci&oacute;n y demas similares.</li>
                                <li>Los hechos intencionales del asegurado y responsabilidad de orden penal no estar&aacute; a cargo de la Compa&ntilde;&iacute;a.</li>
                                <li>Los explosivos o substancias inflamables, sean quimicos o no.</li>
                                <li>P&eacute;rdidas o da&ntilde;os indirectos o consencuenciales o por lucro cesante de cualquier tipo.</li>
                                <li>La emision de radiaciones ionizantes, o contaminaci&oacute;n de la radioactividad de cualquier combustible.</li>
                                <li>Deterioro de la propiedad debido a cambios de temperaturao humedad o a falta y operaci&oacute;n inadecuada de algun sistema de acondicionamiento de aire.</li>
                                <li>La averia o destrucci&oacute;n de objetos por fermentaci&oacute;n, vicio propio o combusti&oacute;n espontanea, o por cualquier procedimiento de calefacci&oacute;a la cual hubieran sido sometidos los objetos asegurados, asi como su deterioro gradual.</li>
                                <li>Uso, desgaste, herrumbre, uncrustaciones, corrosi&oacute;n, oxidaci&oacute;n, cavitaci&oacute;n y otros defectos latentes</li>
                                <li>Material defectuoso, diseño defectuoso y errores de mano de obra</li>
                                <li>Responsabilidad Civil de cualquier naturaleza</li>
                            </ol>                            
                        </td>
                        <td style="width:1%; border-left:1px solid #000;">&nbsp;</td>
                        <td style="width: 49%; padding: 5px;" valign="top">  
	                        <ol style="list-style:square">
                                <li>Propiedad tomada o confiscada por quebrantamiento de cualquier ley o por orden de cualquier autoridad p&uacute;blica..</li>
                            </ol>	
                            
                            <span>VIII.</span>
                            <span style="<?=$title_regla;?>">PROCEDIMIENTO EN CASO DE SINIESTROS</span><br />
                            En caso de siniestros contemplados bajo el presente contrato, el asegurado debe obrar de la siguiente manera:
                           
                            <ol style="list-style:square">
                            	<li>Poner en conocimiento inmediato del hecho a la Compa&ntilde;&iacute;a o a Sudamericana S.R.L., para la correspondiente inspecci&oacute;n.</li>
                            </ol>
                            
                            Posterior al aviso, deber&aacute; presentar la siguiente documentacion:
                            
                            <ol style="list-style:square">
                                <li>Informe t&eacute;cnico emitido por los bomberos(en caso de incendio).</li>
                      			<li>Informe de tr&aacute;nsito en caso de impacto de vehiculos propios o ajenos.</li>
                                <li>Informe en conclusiones de la Policia en caso de P&eacute;rdidas o Da&ntilde;os a causa de los Riesgos Politicos cubiertos.</li>
                                <li>Informe metereol&oacute;gico ofical en caso de p&eacute;rdidas o da&ntilde;os como consecuencia de Riesgos de la Naturaleza cubiertos.</li>
                                <li>Informe t&eacute;cnico de acuerdo al tipo de da&ntilde;o ocasionado (en caso de da&ntilde;os por agua)</li>
                                <li>Aval&uacute;o de la garant&iacute;a asegurada en poder del Tomador que garantiza la operaci&oacute;n crediticia.</li>
                                <li>Copia del Contrato de pr&eacute;stamo</li>
                                <li>Dos Presupuestos detallados de refacci&oacute;n de los da&ntilde;os amparables emitidos por reparadores</li>
                                <li>Cualquier otro informe o evidencia que el Asegurador pueda requerir con el objeto de determinar la procedencia del siniestro y cuantificaci&oacute;n de la p&eacute;rdida amparable.</li>
                                <li>Pagar la franquicia o Deducible a la compa&ntilde;&iacute;a seg&uacute;n el da&ntilde;o que tenga el bien</li>
                                <li>Riesgos de Refrigeraci&oacute;n: 48 horas</li>
                                <li>Riesgos Pol&iacute;ticos, incluyendo terrorismo: 1% del monto del siniestro m&iacute;nimo US$ 1.500.- por evento y/o reclamo.</li>
                                <li>Terremoto, temblor, erupciones voc&aacute;nicas y riesgo de la naturaleza: 1% del valor del predio afectado m&iacute;nimo US$ 1.000.- por evento y/o reclamo</li>
                                <li>Robo y asalto: US$ 1.000.- por evento y/o reclamo</li>
                                <li>Manipuleo para vidrios y equipos m&eacute;dicos de alta sensibilidad: US$ 1.000.- por evento y/o reclamo.</li>
                                <li>Otros amparos: US$ 500.- por evento y/o reclamo</li>
                            </ol>
                            En caso de se necesario la compa&ntilde;&iacute;a se reserva el derecho de exigir cualquier otra informaci&oacute;n adicional que pudiese ayudar a determinar el pago del siniestro.
                        </td>
                    </tr>
                </table>
            </div><br /><br />
            <div style="<?=$content3;?>">
           		SANTA CRUZ: Av Monse&ntilde;or Rievero, 223-2 &bull; Tel&eacute;fono: 371-6565 &bull; Fax: 371-6905 &bull; LA PAZ: Calle Capit&aacute;n Ravelo, 2334 &bull; Tel&eacute;fono: 244-2942 &bull; Fax: 244-2905 &bull; COCHABAMBA: Av. Libertador, 1150 Tel&eacute;fono: 444-8750 &bull; Fax: 444-8754
           </div>
        </div>
    </div>
</div>
<?php
}