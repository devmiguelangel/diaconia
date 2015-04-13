<?php
function au_em_certificate_mo_aupbm ($link, $row, $rowDt, $url, $implant, $fac, $reason = '') {
	$prefix = json_decode($rowDt['prefix'], true);
?>
<div id="container-c-des" style="width:790px;">
<?php

/*--------------------STYLE--------------------*/
$main_c_des='width: 770px; height: auto; margin-top:55px; margin-left:15px; padding: 0;';

$message='margin: 0; padding: 0 0 0 10px; text-align: left; font-weight: bold; font-size: 11px; font-family: Arial;';
$message2='font-weight: bold; font-size: 13px; font-family: Arial; padding: 0; text-align: center;'; 
$header='widows: auto; height: 80px;';
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
$h4_s='width: 100%; height: auto; margin:8px; font-weight:normal; font-size: 10px; font-family: Arial; text-align:center;';

/*-----------Contenido 1-----------*/
$table='width: 100%; font-weight: normal; font-size: 10px; font-family: Arial; margin: 2px 0 0 0; padding: 0; border-collapse: collapse; vertical-align: bottom;';
$table_p='font-weight: normal; font-size: 9px; font-family: Arial; text-align: justify; margin: 2px 0;'; 
$tab='text-indent: 20px;';
$title_regla=' margin-left: 17px; font-weight: normal; text-decoration: underline;';
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
				AL CONTRATO DE SEGURO DE AUTOMOTORES PARA GARANTIAS PRENDARIAS - SERVICIO PUBLICO CON PRIMA MENSUAL
            </h1>           
            <h4 style="<?=$header_h4;?>">
				APROBADDO SEG&Uacute;N RESOLUCI&Oacute;N ASFI N&deg; 470/2009 DE FECHA 07 DE DICIEMBRE DE 2009<br>
                C&Oacute;DIGO ASIGNADO 115-910515-2009 11 061-4001<br>
            </h4><br>			
			<table cellpadding="0" cellspacing="0" border="0" style="width:100%; ">
                <tr>
                	<td style="width:50%;">
                        <h4 style="<?=$header_h1;?> font-size:11px;">
                        	P&oacute;liza N&deg; 
                        	<?=$prefix['policy'];?>
                        </h4>
                    </td>                	
                    <td>
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
            	que el veh&iacute;culo con valor comercial de acuerdo a 
            	aval&uacute;o de $us <?=number_format($rowDt['valor_asegurado'], 2, '.', ',');?> 
            	constituido en garant&iacute;a Prendaria del banco 
            	Econ&oacute;mico S.A. por el cr&eacute;dito otorgado N&deg; 
				<?=$rowDt['no_detalle'];?> a favor de: <?=$row['cl_nombre'];?> 
				se encuentra amparado bajo la p&oacute;liza contra de Automotores 
				para Garantias Prendarias - Servicio P&uacute;blico N&deg; 
				<?=$prefix['policy'];?> de acuerdo a los t&eacute;rminos y 
				condiciones estipulados en el mencionado Contrato de Seguros 
				suscrito entre el "Tomador" y el "Asegurador".
			</h2>
            	
                <h3 style="<?=$h3;?> text-decoration:underline;">MARTERIA ASEGURADA Y COBERTURAS:</h3>
                <h2 style="<?=$h2;?>"><span style="font-weight:bold;">VEH&Iacute;CULOS CONSIDERADOS EN CLASIFICACI&Oacute;N I:</span>&Oacute;mnibus de servicio p&uacute;blico con capacidad m&aacute;xima de 50 asientos destindados al transporte interprovincial interdepartamental y optativamente fuera del territorio nacional sujeto a los t&eacute;rminos y condiciones de la cl&aacute;usula de Extraterritorialidad, que se detalla en las presentes condiciones particulares.</h2><br />
                <table cellpadding="0" cellspacing="2" border="0" style="width:750px; font-size:8px; text-align:center;">
                	<tr style="background:#829DD8;">
                    	<td style="font-weight:bold; width:225px;">COBERTURAS</td>
                        <td style="font-weight:bold; width:225px;">SUMAS ASEGURADAS</td>
                        <td colspan="2" rowspan="2" style="background:#FFF; font-size:9px; text-align:justify; width:300px;">El asegurado en caso de siniestro debe cancelar la Franquicia a Deducible de acuerdo a la siguiente:<br />
                        <span style="font-weight:bold;">Escala de Franquicia-</span>Deducible establecida de acuerdo a los montos de valores asegurados declarados por el Tomador.</td>
                    </tr>
                    <tr style="background:#446FCA;">
                    	<td>RESPONSABILIDAD CIVIL FRENTE A TERCEROS</td>
                        <td>US$ 10.000</td>
                    </tr>
                    <tr style="background:#829DD8;">
                    	<td>PERDIDA TOTAL<br />(100% por accidente y 100% por robo total)</td>
                        <td>VALOR COMERCIAL DECLARADO POR EL TOMADOR</td>
                        <td style="font-weight:bold; width:150px;">VALORES ASEGURADOS</td>
                        <td style="font-weight:bold; width:150px;">FRANQUICIAS</td>
                    </tr>
                    <tr style="background:#446FCA;">
                    	<td>DA&Ntilde;OS PROPIOS<br />FRANQUICIA SEG&Uacute;N ESCALA</td>
                        <td>VALOR COMERCIAL DECLARADO POR EL TOMADOR</td>
                        <td>HASTA US$ 25.000</td>
                        <td>US$ 250</td>
                    </tr>
                    <tr style="background:#829DD8;">
                    	<td>HUELGAS, CONMOCIONES CIVILES, DA&Ntilde;O MALICIOSO<br /> Y VANDALISMO FRANCUICIA SEG&Uacute;N ESCALA</td>
                        <td>VALOR COMERCIAL DECLARADO POR EL TOMADOR</td>
                        <td>DE US$ 25.001 HASTA US$ 50.000</td>
                        <td>US$ 500</td>
                    </tr>
                    <tr style="background:#446FCA;">
                    	<td>RIESGOS DE LA NATURALEZA</td>
                        <td>VALOR COMERCIAL DECLARADO POR EL TOMADOR</td>
                        <td>DE US$ 50.001 HASTA US$ 180.000</td>
                        <td>10% DEL MONTO DEL SINIESTRO<br />(M&Iacute;NIMO US$ 500 Y M&Aacute;XIMO US$ 5.000)</td>
                    </tr>
                </table>
                    
                <h2 style="<?=$h2;?>"><span style="font-weight:bold;">VEH&Iacute;CULOS CONSIDERADOS EN CLASIFICACI&Oacute;N II:</span>Autom&oacute;viles, Camionetas de cualquier tipo, Vagonetas, Jeeps, Furgonetas y otros similares de uso particular de los asegurados o que puedan ser alquilados por los mismos, siempre y cuando no sean destinados para uso de transporte p&uacute;blico: dentro de territorio nacional o optativamente fuera de territorio nacional sujeto a los t&eacute;rminos y condiciones de la clausula de Extraterritorialidad que se detalla en las presentes condiciones particulares.</h2><br />
                
                <table cellpadding="0" cellspacing="2" border="0" style="width:100%; font-size:8px; text-align:center;">
                	<tr>
                    	<td style="width:20%;" rowspan="7">&nbsp;</td>
                    	<td style="background:#829DD8; font-weight:bold; width:30%; height:20px;">COBERTURAS</td>
                        <td style="background:#829DD8; font-weight:bold; width:30%; height:20px;">SUMAS ASEGURADAS</td>
                        <td style="width:20%;" rowspan="7">&nbsp;</td>
                    </tr>
                    <tr style="background:#446FCA;">
                    	<td>RESPONSABILIDAD CIVIL FRENTE A TERCEROS</td>
                        <td>US$ 10.000</td>                        
                    </tr>
                    <tr style="background:#829DD8;">
                    	<td>PERDIDA TOTAL<br />(100% por accidente y 100% por robo total)</td>
                        <td>VALOR COMERCIAL DECLARADO POR EL TOMADOR</td>                        
                    </tr>
                    <tr style="background:#446FCA;">
                    	<td>DA&Ntilde;OS PROPIOS<br />FRANCUICIA US$ 100.-</td>
                        <td>VALOR COMERCIAL DECLARADO POR EL TOMADOR</td>                        
                    </tr>
                    <tr style="background:#829DD8;">
                    	<td>ROBO PARCIAL AL 80%</td>
                        <td>VALOR COMERCIAL DECLARADO POR EL TOMADOR</td>                        
                    </tr>
                    <tr style="background:#446FCA;">
                    	<td>HUELGAS, CONMOCIONES CIVILES, DA&Ntilde;O MALICIOSO<br /> Y VANDALISMO FRANQUICIA US$ 100.-</td>                        <td>VALOR COMERCIAL DECLARADO POR EL TOMADOR</td>                        
                    </tr>
                    <tr style="background:#829DD8;">
                    	<td>RIESGOS DE LA NATURALEZA</td>
                        <td>VALOR COMERCIAL DECLARADO POR EL TOMADOR</td>                        
                    </tr>               
                </table>

				<h3 style="<?=$h3;?>">COBERTURA AUTOM&Aacute;TICA ADICIONAL INCLUIDA</h3>
				<h2 style="<?=$h2;?>">La cobertura de la p&oacute;liza se extiende a cubrir al conductor de cada uno de los veh&iacute;culos incorporados o que se incorporen durante la vigencia en cualquiera de sus clasificaciones contra los riesgos de y hasta los l&iacute;mites que se detallan a continuaci&oacute;n, mientras se hallen como conductores de los veh&iacute;culos asegurados o en el acto de bajar y subir de los mismos para tal objeto cuando se encuentren detenidos:.</h2><br />
                
                <table cellpadding="0" cellspacing="2" border="0" style="width:100%; font-size:8px; text-align:center;">
                	<tr>
                    	<td style="width:20%;" rowspan="7">&nbsp;</td>
                    	<td style="background:#829DD8; font-weight:bold; width:30%; height:20px;">COBERTURAS</td>
                        <td style="background:#829DD8; font-weight:bold; width:30%; height:20px;">SUMAS ASEGURADAS</td>
                        <td style="width:20%;" rowspan="7">&nbsp;</td>
                    </tr>
                    <tr style="background:#446FCA;">
                    	<td>MUERTE ACCIDENTAL</td>
                        <td>US$ 3.000</td>                        
                    </tr>
                    <tr style="background:#829DD8;">
                    	<td>INVALIDEZ TOTAL Y/O PARCIAL PERMANENTE</td>
                        <td>US$ 3.000</td>                        
                    </tr>
                    <tr style="background:#446FCA;">
                    	<td>GASTOS M&Eacute;DICOS POR ACCIDENTE</td>
                        <td>US$ 600</td>                        
                    </tr>
                </table>
                
                <h2 style="<?=$h2;?>"><span style="font-weight:bold;">LA COBERTURA DE GASTOS M&Eacute;DICOS FUNCIONAR&Aacute; EN EXCESO DE LOS VALORES ASEGURADOS EN EL SEGURO OBLIGATORIO DE ACCIDENTES DE TRANSITO (SOAT) </span>Las indemnizaciones procendentes de esta extensi&oacute;n, proceder&aacute;n siempre y cuando las causas del accidente no se encuentren excluidas de la cobertura otorgada para el veh&iacute;culo asegurado.<br />
                La cobertura de invalidez total y/o parcial permanenete queda sujeta a la clasificaci&oacute;n del grado de invalidez y las indemnizaciones a la tabla establecida en el condicionado para accidentes de ocupantes inserta en la p&oacute;liza.</h2><br />
                
                <h3 style="<?=$h3;?>">TASAS TOTALES Y FORMA DE PAGO</h3>
				<h2 style="<?=$h2;?>">Tasas aplicables sobre los montos asegurados establecido en los reportes mensuales.</h2><br />
                <table cellpadding="0" cellspacing="0" border="0" style="width:100%; font-size:8px; text-align:center;">
                	<tr>
                    	<td style="width:20%;" rowspan="2">&nbsp;</td>
                        <td>VEH&Iacute;CULOS COMPRENDIDOS EN LA CLASIFICACI&Oacute;N I</td>
                        <td style="font-weight:bold;">0.35% (POR CIENTO)</td>
                        <td style="width:20%;" rowspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                    	<td>VEH&Iacute;CULOS COMPRENDIDOS EN LA CLASIFICACI&Oacute;N II</td>
                        <td style="font-weight:bold;">0.30% (POR CIENTO)</td>
                    </tr>
                </table>
                
                <h3 style="<?=$h3;?>">CONTRATO PRINCIPAL (P&Oacute;LIZA MATRIZ)</h3>
                <h2 style="<?=$h2;?>">LATINA SEGUROS PATRIMONIALES S.A., Compa&ntilde;&iacute;a de Seguros y Reaseguros asegura al Prestatario, seg&uacute;n las condiciones descritas en el Contrato de Seguro de Automotores para Garant&iacute;as Prendarias - Servicio P&uacute;blico, celebrando entre Latina Seguros Patrimoniales S.A. y el Banco Economico S.A. condiciones que forman parte integrante del seguro aqu&iacute; concedido.<br />
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
           	<h2 style="<?=$h2;?>">He recibido mi Certificado Individual de Cobertura y afiliaci&oacute;n al Contrato de Seguro de Protecci&oacute;n de Tarjetas de Cr&eacute;dito Anual Renovable, con cuyos t&eacute;rminos y condiciones estoy de acuerdo y declaro conocer.</h2><br><br>
           
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
                                    	C.I. <?=$row['ci'];?> 
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
                    <strong>REGLAS DEL CONTRATO DE SEGURO DE AUTOMOTORES GARANTIAS PRENDARIAS - SERVICIO P&Uacute;BLICO</strong>
                </h2><br />
                <table border="0" cellpadding="0" cellspacing="0" style="text-align: justify; font-size: 8px; width: 100%;">
                    <tr>
                        <td style="width: 50%; padding: 5px;" valign="top">
                            <span>I.</span>
                            <span style="<?=$title_regla;?>">DEFINICIONES</span><br />
                            <span>1.01</span>
                            <span style="<?=$title_regla;?>">EL ASEGURADOR:</span> LATINA SEGUROS PATRIMONIALES S.A.<br />
                            
                            <span>1.02</span>
                            <span style="<?=$title_regla;?>">TOMADOR Y BENEFICIARIO DEL SEGURO:</span><strong>BANCO ECONOMICO S.A.</strong><br />
                            
                            <span>1.03</span>
                            <span style="<?=$title_regla;?>">ASEGURADOS:</span> 
                            Prestatarios del Tomador.<br />
                            
                            <span>1.04</span>
                            <span style="<?=$title_regla;?>">BENEFICIARIOS:</span>
                            Banco Economico en casos de P&eacute;rdidas Totales y Prestatarios en casos de Da&ntilde;os Parciales y Da&ntilde;os a Terceros.<br /> 
                            
                            <span>1.05</span>
                            <span style="<?=$title_regla;?>">ALCANCE DE LA COBERTURA:</span><br>
                            <ol style="list-style:disc;">
                            	<li>Veh&iacute;culos en garantias hipotecarias por operaciones de cr&eacute;dito del tomador, declaraci&oacute;n mediante reporte mensuales y que por sus caracteristicas se encuentran considerados en las Clasificaci&oacute;n I y II.</li>
                                <li>La suma Asegurada declarada por el Tomador establecida como base de toda indemnizaci&oacute;n son los valores comerciales establecidos en los ava&uacute;os.</li>
                            </ol>
                            
                            <span style="font-weight:bold;">II.</span>
                            <span style="<?=$title_regla;?> font-weight:bold;">INICIO DE VIGENCIA DE LA COBERTURA PARA CADA ASEGURADO</span><br />
                            <span>2.01</span>
                            &nbsp; Operaciones nuevas desde la fecha de desembolso del cr&eacute;dito sobre el cual se constituy&oacute; la garant&iacute;a.<br />
                            
                            <span>2.02</span>
                            &nbsp; Operaciones aseguradas por el tomador antes del inicio de la vigencia de esta p&oacute;liza: desde el inicio de la vigencia de la presente p&oacute;liza y/o renovaci&oacute;n.<br />
                            
                            <span>2.03</span>
                            &nbsp; Veh&iacute;culos en garant&iacute;a asegurados a trav&eacute;s de otros contratos de seguro subrogados a favor del tomador, contratados en forma particular por prestatarios con operaciones vigentes: desde la fecha de vencimiento del contrato de seguros tomado en forma particular por el prestatario y presentado como garantia al tomador.<br />
                            
                            <span>2.04</span>
                            &nbsp; En ning&uacute;n caso, se cubrir&aacute; bajo la presente p&oacute;liza veh&iacute;culos en garantia correspondiente a operaciones vigentes mientras otro seguro que provea las coberturas otorgados bajo el presente contrato se encuentre en vigor. Tampoco ser&aacute;n objeto del presente contrato la atenci&oacute;n de siniestros ocurrido con anterioridad a la fecha de incorporaci&oacute;n del bien objeto del seguro bajo la presente p&oacute;liza.<br />
                            
                            <span style="font-weight:bold;">III.</span>
                            <span style="<?=$title_regla;?> font-weight:bold;">FINALIZACI&Oacute;N DE LA COBERTURA PARA CADA ASEGURADO</span><br />
                            <span>3.01</span>
                            &nbsp; En la fecha de vencimiento de la p&oacute;liza, salvo la misma haya sido renovada por el tomador a su vencimiento (sin perjuicio de que las condiciones puedan modificarse en cada renocaci&oacute;n)<br />
                            
                            <span>3.02</span>
                            &nbsp; En caso de cancelaci&oacute;n y/o cesaci&oacute;n de la obligaci&oacute;n del prestatario con el tomador: transcurridos los 30 d&iacute;as adicionales contados desde la fecha en la cual el tomador liber&oacute; dichas hipotecas aunque dichos d&iacute;as adicionales superen la fecha de vencimiento de esta p&oacute;liza.<br />
                            
                            <span>3.03</span>
                            &nbsp; En caso de incumplimiento de pago de las primas.<br />                           
                            
                            <span>3.04</span>
                            &nbsp; En el momento en que el veh&iacute;culo materia del seguro pierde su condici&oacute;n de garantia hipotecaria del tomador por cualquier motivo distinto al anteriormente mencionado.<br />
                            
                            <span style="font-weight:bold;">IV.</span>
                            <span style="<?=$title_regla;?> font-weight:bold;">LA P&Oacute;LIZA</span><br />
                            <span>4.01</span>
                            &nbsp; No se pagar&aacute; ninguna indemnizaci&oacute;n conforme a estas reglas si la suma asegurada correspondiente no resultara pagadera con arreglo a las condiciones de la P&oacute;liza. Cualquier miembro puede examinar la p&oacute;liza si lo cree oportuno, previa coordinaci&oacute;n con el Tomador.<br />
                            
                            <span style="font-weight:bold;">V.</span>
                            <span style="<?=$title_regla;?> font-weight:bold;">MODIFICACI&Oacute;N O TERMINACI&Oacute;N</span><br />
                            <span>5.01</span>
                            &nbsp; Las condiciones estipuladas en el presente contrato, permanecer&aacute;n inalterables durante el periodo de vigencia de la p&oacute;liza, salvo que el "Tomador" y "Asegurador", puedan convenir modificaciones mediante anexo.<br />
                            
                            <span>5.02</span>
                            &nbsp; El "Tomador" y el "Asegurador" se reserven el derecho de finalizar el Contrato en el marco de lo se&ntilde;alado en dicho Contrato y C&oacute;digo de Comercio.<br />
                            
                            <span style="font-weight:bold;">VI.</span>
                            <span style="<?=$title_regla;?> font-weight:bold;">RESPONSABILIDAD M&Aacute;XIMA DEL "ASEGURADOR" EN CASO DE SINIESTRO</span><br />
                            <span>6.01</span>
                            &nbsp; La responsabilidad m&aacute;xima del Asegurador en caso de siniestro es el equivalente al Valor Comercial que indistintamente tenga cada garantia prendaria y/o materia Asegurada incorporada en la p&oacute;liza por el Tomador seg&uacute;n establecen los aval&uacute;os que avalan las operaciones crediticias. En ning&uacute;n caso, la suma indemnizable por el Asegurador bajo este seguro superar&aacute; la suma de US$ 180.000,00.- (CIENTO OCHENTA MIL 00/100 DOLARES AMERICANOS) para la Calificaci&oacute;n I y US$ 50.000,00.- (CINCUENTA MIL 00/100 DOLARES AMERICANOS) para la Clasificaci&oacute;n II.<br />
                            
                            <span style="font-weight:bold;">VII.</span>
                            <span style="<?=$title_regla;?> font-weight:bold;">RESTRICCIONES Y EXCLUSIONES</span><br />
                            No obstante las restricciones  exclusiones estipuladas en las condiciones generales de la p&oacute;liza se enumeran a continuaci&oacute;n con car&aacute;cter enunciativo y limitativo las siguientes:<br />                            
                            <ol style="list-style:square">
                            	<li>Veh&iacute;culos de uso particular.</li>
                                <li>Contenidos de los veh&iacute;culos asegurados.</li>
                                <li>Hurtos.</li>
                                <li>Acciones o maniobras consideradas por la Autoridad de Transito como temarios por parte del conductor,salvo que la misma Autoridad establezca que estas fueron hechas por la necesidad de preservar vidas humanas.</li>
                                <li>Hechos producidos en conexi&oacute;n o con motivo de hostilidades, acciones u operaciones de guerra o invasi&oacute;n de enemigo extranjero (haya o no declaraci&oacute;n o estado de guerra) o guerra interna, revoluci&oacute;n, rebeli&oacute;n, insurrecci&oacute;n, terrorismo u otros hechos y delitos contra la seguridad interior o exterior del pais aunque no sean a mano armada, o bien de la administraci&oacute;n y gobierno de cualquier territorio o zona de estado de sitio o de suspencion de garantias o bajo el control de autoridades militares, o de acontecimientos que originen estas situaciones de hecho o de derecho o que ellos, deriven directa o indirectamente relacionados con ellos, como quiera y donde quiera que se origine.</li>
                                <li>La Emisi&oacute;n de radiaciones ionizantes o contaminaci&oacute; por la radioactividad de cualquier conbustible o material nuclear o de cualquier desperdicio proveniente de la combusti&oacute;n cualquier proceso de fisi&oacute;n nuclear que se sostiene por si mismo.</li>
                                <li>Da&ntilde;os que no sean ocasionados en forma s&uacute;bita e imprevista</li>
                                <li>Actos de autoridad tales como el decomiso, apropiaci&oacute;n, espropiaci&oacute;n y requisici&oacute;n</li>
                                <li>Las perdidas o da&ntilde;os materiales que directa o indirectamente provengan de siniestros causados por dolo mala fe o culpa grave del Asegurado, sus apoderados, sus representantes legales, personal directivo o quienes se les haya otorgado la direcci&oacute;n o control de la Empresa, sus beneficiarios o personas por quienes sea civilmente responsable.</li>
                                <li>Las perdidas o da&ntilde;os materiales que directa o indirectamente de manera inmediara a mediata, hayan sido provocados, causads u ocasionados por huelguistas, incluyendo el cierre patronal y sus acciones y reacciones, o personas que tomen parte en paros, disturbios de caracter obrero, motines o alborotos populares o bien producidos por tumultos, motin, conmocion civil o popular y actos mal intencionados de terceros.</li>
                                <li>Los da&ntilde;os al vehiculo asegurado, causados intencionalmente o motivados por resentimiento, odio o venganza hacia el asegurado, sus familiares o sus dependientes son proponerse el autor o autores del delito prvercho o lucro alguno; entendiendo el provecho o lucro como beneficio econ&oacute;mico.</li>
                                <li>Los da&ntilde;os o p&eacute;rdidas indirectas, consecuenciales o p&eacute;rdida de beneficios de cualquier tipo.</li>                                                                
                            </ol>                           
                        </td>
                        <td style="width:1%; border-left:1px solid #000;">&nbsp;</td>
                        <td style="width: 49%; padding: 5px;" valign="top">  
	                        <ol style="list-style:square;">
                            	<li>Los hechos intencionales del asegurado y responsabilidad de orden penal no estar&aacute; en ningun caso, a cargo de la compa&ntilde;ia</li>
                                <li>Da&ntilde;os a personas o bienes que se produzcan al ser el vehiculo asegurado conducido por personas que en el momento del accidente se hallen: Bajo el efecto de bebidas alcoh&oacute;licas, cualquiera sea el grado de dosaje etilico; presencia de drogas de cualquier naturaleza en el organismo, que afecten las facultades para conducir (medicamentos contraindicados para la conducci&oacute;n normal)</li>
                                <li>Cuando se produzcan da&ntilde;os como consecuencia de infracciones de primer grado se&ntilde;alados en el Art&iacute;culo 140 de C&oacute;digo de Tr&aacute;nsito o cualquier articulo complementario y/o cuando se infrinja temerariamente la dispuesta en la Ley de Carga y contravenga cualquier otra disposici&oacute;n de cumplimiento imperativo relativo al uso y tr&aacute;fico de veh&iacute;culos.</li>
                                <li>Muerte, lesiones o personas o da&ntilde;os o bienes que se produzcan mientras el veh&iacute;culo asegurado circule por las vias prohibidas o no habilitadas al transito rodado o por el territorio extranjero (salvo la Compa&ntilde;&iacute;a lo autorice).</li>
                                <li>Da&ntilde;os causados a personas o casas, si el accidente ocurre cuando el veh&iacute;culo se halle conducido por una persona que no cuenta con licencia para conducir o a&uacute;n teniendolo sea menor de dieciocho a&ntilde;os, que no este habilitado con la licencia correspondiente para conducir veh&iacute;cuos de mayor capacidad de acuerdo a la reglamentaci&oacute;n y regulaci&oacute;n de las autoridades de Tr&aacute;nsito.</li>
                                <li>Multas, sanciones y penas impuestas por autoridades judiciales o administrativas.</li>
                                <li>Los da&ntilde;os al vehiculo provenientes de la participaci&oacute;n del mismo en apuestas, desafios, competencias, carreras o concursos de cualquier naturaleza, o en las pruebas preparatorias de los mismos.</li>
                                <li>Cuando se transporten articulos azarosos, inflamables o explosivos sin la previa notificaci&oacute;n y la correspondiente autorizacion de la Compa&ntilde;&iacute;a.</li>
                                <li>La falla o rotura mec&aacute;nica, los desperfectos el&eacute;ctronicos; los deterioros debido a desgaste por uso, sobrecarga, sobre esfuerzo o la inadecuada conservaci&oacute;n y mantenimiento del veh&iacute;culo. Sin embargo, cuando las causas indicadas produzcan accidente, los da&ntilde;os resultantes estar&aacute;n cubiertos, pero no la falla, rotura, desperfectos o deteriores preexistentes.</li>
                            </ol>
                            
                            <span style="font-weight:bold;">VIII.</span>
                            <span style="<?=$title_regla;?> font-weight:bold;">BENEFICIOS ADICIONALES</span><br />
                            <ol style="list-style:square;">
                            	<li>Responsabilidad civil incluyendo responsabilidad civil consecuencial hasta $us 2.500.</li>
                                <li>Asistencia legal en transito.</li>
                                <li>Transito en vias no autorizadas.</li>
                                <li>Exoneraci&oacute;n de denuncia en transito en casos de da&ntilde;os menores a $us 500 siempre y cuando no afecte la cobertura de Responsabilidad Civil.</li>
                                <li>Nombramiento de ajustadores.</li>
                                <li>Rehabilitaci&oacute;n autom&aacute;tica de la Suma Asegurada.</li>
                                <li>Elegibilidad de talleres.</li>
                                <li>Gastos de curaci&oacute;n a los ocupantes a consecuencia de lesiones sufridas por robo asalto y/o atraco hasta el limite de la cobertura de gastos m&eacute;dicos.</li>
                                <li>Reembolso inmediato y en efecto para reclamos aceptados hasta $us 200 por encima el deducible.</li>
                                <li>Adelanto del 50% del siniestro.</li>
                                <li>Cobertura de extraterritorialidad en planes limitrofes los 365 d&iacute;as al a&ntilde;o sin previa notificaci&oacute;n y sin cobro de prima adicional.</li>
                                <li>Riesgos de la naturaleza incluyendo caidas de &aacute;rboles</li>
                                <li>No pago de la franquicia por siniestros ocasionados por riesgos de la naturaleza incluidos da&ntilde;os por ca&iacute;da de &aacute;rboles.</li>                                
                            </ol>
                            
                            <span style="font-weight:bold;">IX.</span>
                            <span style="<?=$title_regla;?> font-weight:bold;">PROCEDIMIENTO EN CASO DE SINIESTROS</span><br />
                           Al ocurrir un siniestro que pueda dar lugar o indemnizaci&oacute;n conforme a los t&eacute;rminos de esta P&oacute;liza el Asegurado deber&aacute;:
                           
                            <ol style="list-style:lower-alpha;">
                            	<li>Dar aviso del siniestro a la Compa&ntilde;&iacute;a o a Sudamericana S.R.L. INMEDIATAMENTE a como m&aacute;ximo dentro de los tres d&iacute;as calendario de haberse producido el siniestro.</li>
                            
                                <li>Tomar todas las acciones dentro de sus medios para minimizar la p&eacute;rdida o da&ntilde;o.</li>
                                <li>Denunciar en el mismo d&iacute;a de ocurrencia del siniestro, el hecho a la autoridad competente, salvo fuerza mayor o impedimento justificado.</li>
                                <li>Someterse al dopaje et&iacute;lico, aun cuando la autoridad no se lo requiera.</li>
                                <li>Solicitar y obtener una copia legalizada del informe t&eacute;cnico Circunstancial (denuncia, informe t&eacute;cnico y/o resuluci&oacute;n del juzgado de tr&aacute;nsito con determinaci&oacute;n de responsabilidades) sobre el siniestro que deber&aacute; ser entregado a la compa&ntilde;&iacute;a en el menor plazo posible.</li>
                                <li>Todos los documentos judiciales y extrajudiciales relativos al caso y todo lo que pudiera contribuir al esclarecimiento del mismo, si los hubiere.</li>
                                <li>Si el asegurado o sus representantes, ocurrido el accidente hacen abandono del veh&iacute;culo siniestrado sin autorizaci&oacute;n escrita de la compa&ntilde;&iacute;a, esta quedar&aacute; exenta de toda responsabilidad.</li>
                                <li>Pagar la franquicia deducible estipulada para la atenci&oacute;n del siniestro. Eb casi de rechazo del reclamo, la compa&ntilde;&iacute;a devolver&aacute; dentro de cinco d&iacute;as el monto cancelado.</li>
                                <li>La Compa&ntilde;&iacute;a tendr&aacute; el derecho a exigir del Asegurado o Beneficiario toda clase de informaciones que se tengan sobre los hechos y circunstancias del siniestro, suministrando las evidencias conducentes a la determinaci&oacute;n de la causa, identidad de personas e intereses asegurados y cuant&iacute;a de los da&ntilde;os que razonablemente puedan ser proporcionadas, as&iacute; como permitir las indagaciones pertinentes y necesarias para tal objetivo.</li>
                                <li>Esperar la autorizaci&oacute;n de la compa&ntilde;&iacute; para iniciar la reapaci&oacute;n de cualquier da&ntilde;o, salvo en caso que el Asegurado se hallase en viaje con el veh&iacute;culo o en un lugar donde no existan agentes de la compa&ntilde;&iacute;a y siempre que tal reparaci&oacute;n fuera impresindible para que el veh&iacute;culo pueda funcionar. En tal caso el Asegurado se hallase en viaje con el veh&iacute;culo o en un lugar donde no existan agentes de la compa&ntilde;&iacute;a y siemrpe que tal reparaci&oacute;n fuera imprescindible para que el veh&iacute;culo pueda funcionar. en tal caso el Asegurado quedar&aacute; autorizado para efectuar reparaciones que no sobrepasen de US$ 150.- (ciento cincuenta 00/100 D&oacute;lares Americanos)</li>
                                <li>Cualquier transacci&oacute;n judicial o extrajudicial deber&aacute; hacerse con el consentimiento y participacion de la Compa&ntilde;&iacute;a, caso contrario tal transacci&oacute;n no compromete la responsabilidad de la Compa&ntilde;&iacute;a</li>
                                <li>El asegurado esta obligado a transcribir inmediatamente a la Compa&ntilde;&iacute;a todos los avisos citaciones, requerimientos, cartas emplazamientos y en general, todos los documentos judiciales o extrajudiciales que con motivo de un accidente cubierto por el seguro le sean dirigidos.</li>
                            </ol>
                            Importante: No llegar a ning&uacute;n acuerdo con autoridades, protagonistas y/o terceros que participaron del siniestro; sin el consentimiento de la Compa&ntilde;&iacute;a.<br /><br />
                            
                            El incumplimiento de estas obligaciones o las acciones del Asegurado que limiten los derechos de la Compa&ntilde;&iacute;a liberan a esta de su responsabilidad en el reclamo.
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
?>