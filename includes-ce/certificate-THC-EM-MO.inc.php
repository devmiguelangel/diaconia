<?php
function thc_em_certificate_mo($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
    $main_c_des='width: 770px; height: auto; margin-top:55px; margin-left:15px; padding: 0;';

	$message='margin: 0; padding: 0 0 0 10px; text-align: left; font-weight: bold; font-size: 11px; font-family: Arial;';
	$message2='font-weight: bold; font-size: 13px; font-family: Arial; padding: 0; text-align: center;'; 
	$header='widows: auto; height: 100px;';
	$header_img='margin: 0;	float: left;';
	$header_h1='text-align: center;	margin-left:18px; font-weight: bold; font-size: 12px; font-family: Arial;';
	$pag_2='margin-bottom /*\**/: 150px\9 ';
	$pag_4='margin-bottom /*\**/: 170px\9 ';
	$h1_s='text-align: center; font-weight: bold; font-size: 14px; font-family: Arial; margin: 0;';
	$h4_s='text-align: center; font-weight: bold; font-size: 9px; font-family: Arial; margin: 0;';
	$header_h4='text-align:center; margin: -5px 0 0 20px; float: center; font-weight: normal; font-size: 10px; font-family: Arial;';
	$footer_h1='text-align: left;	font-weight: bold; font-size: 12px; font-family: Arial;';
	
	$container_1='width: 770px; height: auto; margin: 0 0 5px 0;';
	$h2='width: auto; height: auto; text-align: left; margin: 0; font-weight: normal; font-size: 9px; font-family: Arial; text-align:justify;';
	$h2_s='width: auto; height: auto; text-align: left; margin: 0; font-weight: normal; font-size: 9px; font-family: Arial; text-align:center;';
	$h2_s1='margin: 0 0 10px 40px; font-weight: normal; font-size: 12px; font-family: Arial;';
	
	$content='width: 100%; height: auto; margin: 0 0 5px 0; padding: 0px 0px; font-weight: bold; font-size: 8px; font-family: Arial; text-align: left;'; 
	$content2='text-align: center; font-weight: normal; font-size: 9px; font-family: Arial;';
	$content3='text-align: center; font-weight: normal; font-size: 9px; font-family: Arial; border-top:1px solid #000;';
	
	$h3='width: auto; height: auto;	font-weight: bold; font-size: 10px; font-family: Arial; margin: 0;';
	$h3_s='font-weight: bold; font-size: 8px; font-family: Arial;';
	
	$h4='width: 100%; height: auto;	font-weight: bold; font-size: 10px; font-family: Arial; margin: 0; text-align:center;';
	
	/*-----------Contenido 1-----------*/
	$table='width: 100%; font-weight: normal; font-size: 10px; font-family: Arial; margin: 2px 0 0 0; padding: 0; border-collapse: collapse; vertical-align: bottom;';
	$table_p='font-weight: normal; font-size: 9px; font-family: Arial; text-align: justify; margin: 2px 0;'; 
	$tab='text-indent: 20px;';
	$title_regla=' margin-left: 17px; font-weight: bold; text-decoration: underline;';
	$title_regla1=' margin-left: 5px; font-weight: normal; text-decoration: underline;';
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
	
	$prefix = json_decode($row['prefix'], true);
  ob_start();
  ?>
   <div style="<?=$main_c_des;?>">
		<div style="<?=$header;?>">
        	<h1 style="<?=$header_h1;?>">
                CERTIFICADO INDIVIDUAL DE COBERTURA<br>
				AL CONTRATO DE SEGURO DE PROTECCI&Oacute;N DE TARJETAS DE CR&Eacute;DITO ANUAL RENOVABLE<br>
            </h1>           
            <h4 style="<?=$header_h4;?>">
				APROBADO SEG&Uacute;N RESOLUCI&Oacute;N ASFI N&deg; 139/2009 DE FECHA 25 DE AGOSTO DE 2009<br>
				C&Oacute;DIGO ASFI 115-910991-2009 07 057-4003
            </h4><br>			
			<table cellpadding="0" cellspacing="0" border="0" style="width:100%; ">
                <tr>
                	<td>
                        <h4 style="<?=$header_h1;?> font-size:11px;">
                        P&oacute;liza N&deg; 
                        <?=$prefix['policy'];?>
                        </h4>
                    </td>                	
                    <td>
                    <h4 style="<?=$header_h1;?> font-size:11px;">
                    	Certificado de Cobertura N&deg;
                    	<?=$prefix['prefix'] . '-' . $row['no_cotizacion'];?>
                    </h4>
                    </td>
                </tr>
           </table>	
        </div>
        
        <div style="<?=$container_1;?>">
        	<h2 style="<?=$h2;?>">Se deja expresa constancia mediante el presente certificado, que la persona Asegurada abajo se&ntilde;alada ha sido admitida como integrante de la P&oacute;liza de Seguros contratada por el Tomador:</h2>
            <div style="<?=$content;?>">
                <table border="0" cellpadding="0" cellspacing="0" style="<?=$table;?>"><tr>
                    <td style="width: 23%;"><b>ASEGURADO:</b> (Nombre y Apellidos) </td>
                    <td style="width: 77%;">
                        <table border="0" cellpadding="0" cellspacing="0" style="<?=$table_borde2_tr_td;?>"><tr>
                        	<td style="width: 100%; height:20px;">
                        		<?=$row['cl_nombre'] . ' ' 
                        			. $row['cl_paterno'] . ' ' 
                        			. $row['cl_materno'];?>
                        	</td>
                        </tr></table>
                    </td></tr>
                    <tr>
                    <td style="width: 23%;"><b>FECHA DE INICIO DE VIGENCIA:</b> </td>
                    <td style="width: 77%;">
                        <table border="0" cellpadding="0" cellspacing="0" style="<?=$table_borde2_tr_td;?>"><tr>
                        	<td style="width: 100%; height:20px;">
                        		<?=date('d/m/Y', strtotime($row['fecha_creacion']));?>
                        	</td>
                        </tr></table>
                    </td></tr>
                    <tr>
                    <td style="width: 23%;"><b>TOMADOR:</b> </td>
                    <td style="width: 77%;">
                        <table border="0" cellpadding="0" cellspacing="0" style="<?=$table_borde2_tr_td;?>"><tr>
                        	<td style="width: 100%; height:45px; font-weight:bold;">
                        		<?=$row['ef_nombre'];?>
                        	</td>
                        </tr></table>
                    </td></tr>
                    <tr>
                    <td style="width: 23%;"><b>BENEFICIARIOS:</b> </td>
                    <td style="width: 77%;">
                        <table border="0" cellpadding="0" cellspacing="0" style="<?=$table_borde2_tr_td;?>"><tr>
                                <td style="width: 100%;"><b>Tarjetahabientes Titilares</b> de las Tarjetas de D&eacute;bito y <b>Herederos Legales</b> del Tarjetahabiente Asegurado en los casos que se aplique la cobertura de muerte accdental asociado al asalto.</td>
                        </tr></table>
                    </td></tr>
                </table>
                <br/><br/>
                
                <h3 style="<?=$h3;?>">COBERTURAS Y CAPITALES ASEGURADOS:<br></h3>
                
                <ol style="list-style:circle; font-size:9px; text-align:justify; font-weight:normal; width:95%;">
                	<li><strong>Protecci&oacute;n contra Robo, Clonaci&oacute;n a Extravio de la Tarjeta de Cr&eacute;dito:</strong></li>
                    <li style="list-style:none;">
                    	<ol style="list-style:square;">
                        	<li><b>Cobertura contra Robo, hurto o Extravio</b> de la tarjeta, hasta US$ 10,000 como Beneficio M&aacute;ximo Anual. Tiene hasta <b>90 d&iacute;as</b> calendario para hacer su relclamaci&oacute;n, a partir de la fecha de la transacci&oacute;n fraudulenta reflejada en el estado de cuenta, donde aparezca algun cargo indebido con su tarjeta robada o extraviada.</li>
                            <li><b>Cobertura contra Clonaci&oacute;n (Skimming)</b> hasta US$ 10,000.Tiene hasta <b>90 d&iacute;as</b> calendario para hacer su relclamaci&oacute;n, a partir de la fecha de la transacci&oacute;n fraudulenta reflejada en el estado de cuenta, donde aparezca algun cargo indebido con su tarjeta clonada o falsificada.</li>
                        </ol>
                    </li>
                    <li><strong>Protecci&oacute;n Personal al Asegurado:</strong></li>
                    <li style="list-style:none;">
                    	<ol style="list-style:square;">
                        	<li><b>Cobertura contra robo como consecuencia de asalto y/o secuestro,</b> US$ 1,000 por evento, hasta dos eventos al a&ntilde;o.</li>
                            <li><b>Cobertura de traslado m&eacute;dico terrestre a consecuencia del asalto y/o secuestro. (Solo en Bolivia)</b> hasta 2 eventos al a&ntilde;o.</li>
                            <li><b>Cobertura de Gastos por asistencia m&eacute;dica a consecuencia del asalto y/o secuestro. (Solo en Bolivia), US$ 1,500.</b></li>
                            <li><b>Cobertura de muerte accidental asociada al asalto y/o secuestro,</b> US$ 10,000.</li>
                            <li><b>Cobertura de Gastos por recuperacion de documentos (reembolso),</b> US$ 250 (1 evento al a&ntilde;o).</li>
                        </ol>
                    </li>
                </ol>
                <h3 style="<?=$h3;?>">PRIMA TOTAL MENSUAL:<br></h3>
                <h2 style="<?=$h2;?>">Las primas ser&aacute;n canceladas por el "Tomador" cada mes vencido y a su vez el "Tomador" cargara el costo de este seguro en las cuotas de amortizaci&oacute;n de cada Tarjetahabiente en funci&oacute;n de su cronograma de Pago.<br>
				Tasa Total Mensual a Facturar por el Asegurado: <b>US$ 1,75</b> (POR TARJETAHABIENTE)</h2><br>
                
                <h3 style="<?=$h3;?>">CONTRATO RPINCIPAL (P&Oacute;LIZA MATRIZ):<br></h3>
                <h2 style="<?=$h2;?>">LATINA SEGUROS PATRIMONIALES S.A., Compa&ntilde;&iacute;a de Seguros y Reaseguros asegura al TARJETAHABIENTE, seg&uacute;n las condiciones descritas en el Contrato del Seguro de Protecci&oacute;n de Tarjetas de Cr&eacute;dito; celebrado entre LATINA Seguros Patrimoniales S.A. y el Tomador <b>BANCO ECONOMICO S.A.,</b> condiciones que forman parte integrante del seguro aqu&iacute; concedido.<br>
                El presente Certificado se Seguro tendr&aacute; valor siempre y cuando la p&oacute;liza este vigente y sus primas se encuentren pagadas de acuerdo a las Condiciones de la p&oacute;liza.</h2><br>

                <h3 style="<?=$h3;?>">ADHESI&Oacute;N VOLUNTARIA DEL ASEGURADO:<br></h3>
                <h2 style="<?=$h2;?>">El Asegurado al momento de concretar el cr&eacute;dito con el Contratante declara su consentimiento VOLUNTARIO para ser asegurado en la p&oacute;liza arriba indicada y declara conocer y estar de acuerdo con las condiciones del contrato de seguros.</h2>
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
           </div>
           <br/>
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

           </div>
           <br/>
           <div style="<?=$content;?>">
           	<h2 style="<?=$h2;?>">He recibido mi Certificado Individual de Cobertura y afiliaci&oacute;n al Contrato de Seguro de Protecci&oacute;n de Tarjetas de Cr&eacute;dito Anual Renovable, con cuyos t&eacute;rminos y condiciones estoy de acuerdo y declaro conocer.</h2><br/><br/>
           
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
                        	<?=$prefix['prefix'] . '-' . $row['no_cotizacion'];?>
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
                            		<?=$row['cl_nombre'] . ' ' 
	                        			. $row['cl_paterno'] . ' ' 
	                        			. $row['cl_materno'];?>
                            	</td>
                            </tr></table>
                        </td>                	
                        <td style="width:50%;">
                        <h4 style="<?=$footer_h1;?> font-size:11px;">
                        	Fecha de Afiliaci&oacute;n:
                        	<strong><?=date('d/m/Y', strtotime($row['fecha_creacion']));?></strong>
                        </h4>
                        </td>
                    </tr>
                    <tr>
                    	<td style="width:50%;" colspan="2">&nbsp;</td>
                        <td style="width:50%;">
                        	<table cellpadding="0" cellspacing="0" border="0" style="<?=$table;?> text-align:center; width:100%;" align="center">                    
                              <tr>
                                <td style="width:45%;">
                                   <table border="0" cellpadding="0" cellspacing="0" style="<?=$table_borde2_tr_td;?>">
                                      <tr>
                                       <td style="width: 100%;">&nbsp;</td>
                                      </tr>
                                   </table>
                                </td>
                                <td style="width:10%;">&nbsp;</td>
                                <td style="width:45%;">
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
                             	<td style="width:45%;">
                             		FIRMA DEL ASEGURADO<br>
                             		C.I. <?=$row['cl_ci'] . $row['cl_complemento'] 
                             			. ' ' . $row['cl_extension'];?>
                             	</td>
                                <td style="width:10%;">&nbsp;</td>
                                <td style="width:45%;" valign="top" >
                                  <table border="0" cellpadding="0" cellspacing="0" style="<?=$table_borde3_tr_td;?>">
                                    <tr>
                                      <td style="width: 32%;">D&iacute;a</td>
                                      <td style="width: 2%;">&nbsp;</td>
                                      <td style="width: 32%;">Mes</td>
                                      <td style="width: 2%;">&nbsp;</td>
                                      <td style="width: 32%;">A&ntilde;o</td>
                                    </tr>
                                  </table>                                
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
                    <strong>REGLAS DEL CONTRATO DE SEGURO DE PROTECCI&Oacute;N DE TARJETAS DE CR&Eacute;DITO ANUAL RENOVABLE</strong>
                </h2><br />
                <table border="0" cellpadding="0" cellspacing="0" style="text-align: justify; font-size: 8px; width: 100%;">
                    <tr>
                        <td style="width: 50%; padding: 5px;" valign="top">
                            <span style="<?=$font_bold;?>">I.</span>
                            <span style="<?=$title_regla;?> text-decoration:none;">DEFINICIONES</span><br />
                            <span>1.01</span>
                            <span style="<?=$title_regla1;?>">EL ASEGURADOR:</span> LATINA SEGUROS PATRIMONIALES S.A.<br />
                            
                            <span>1.02</span>
                            <span style="<?=$title_regla1;?>">TOMADOR DEL SEGURO:</span><strong>BANCO ECONOMICO S.A.</strong><br />
                            
                            <span>1.03</span>
                            <span style="<?=$title_regla1;?>">ASEGURADOS:</span> 
                            Tarjetahabientes del "Tomador", que figuren en las planiallas mensuales elaboradas por el Tomador.<br />
                            
                            <span>1.04</span>
                            <span style="<?=$title_regla1;?>">BENEFICIARIOS:</span>
                            <strong>Tarjetahabientes y Herederos Legales</strong> del Tarjetahabiente Asegurado en los casos que se aplique la cobertura de vida asociada al asalto.<br /> 
                            
                            <span>1.05</span>
                            <span style="<?=$title_regla1;?>">COBERTURA CONTRA ROBO, HURTO O EXTRAVIO:</span>
                            Robo, hurto o extravio significa la apropiaci&oacute;n indebida o, el robo de la tarjeta de cr&eacute;dito asegurada contra la voluntad o por inadvertencia de su poseedor y posterior retiro ilegal o frudulento mediante dicha tarjeta de los fondos de la cuenta del cliente o, el robo de la tarjeta de cr&eacute;dito asegurada contra la voluntad o por inadvertencia de su poseedor que posteriormente es utilizada por la persona o personas que se apropiaron indebidamente de la tarjeta de cr&eacute;dito del Asegurado efectuando de esta forma consumos fraudulentos, sin que para dichos fines haya existido amenaza, secuestro, extorsi&oacute;n o violencia en contra de la persona del Asegurado. La cobertura funcionara dentro o fuera del territorio boliviano.<br />

                            <span>1.06</span>
                            <span style="<?=$title_regla1;?>">COBERTURA CONTRA CLINACI&Oacute;N "SKIMMING":</span>    
                            Clonaci&oacute;n significa la reproducci&oacute;n ilegal de la tarjeta de cr&eacute;dito del Asegurado o de los c&oacute;digos electr&oacute;nicos de la misma por parte de personas maliciosas para realizar de esta forma, ya sean consumos y/o retiro de fondos de un cajero automatico (ATM) mediante la utilizaci&oacute;n de dicha tarjeta y/o de los c&oacute;digos electr&oacute;nicos falsificados, afectando de esta manera, la cuenta del Asegurado. Sin que para dichos fines haya existido amenaza, secuestro, extorsi&oacute;n o violencia en contra de la persona del Asegurado. La cobertura funcionar&aacute; dentro o fuera del territorio boliviano.<br />
                            
                            <span>1.07</span>
                            <span style="<?=$title_regla1;?>">COBERTURA CONTRA ROBO COMO CONSECUENCIA DE ASALTO Y/O SECUESTRO:</span> 
                            Robo como consecuencia de Asalto y/o secuestro, es el acto de apoderamiento intencional e ilegal, cometido por una persona o personas en contra del asegurado incluyendo la eventualidad de secuestro. Los actos mencionados deben involucrar ya sea el uso de armas peligrosas y/o amenazas de inflingir lesi&oacute;n corporal al Asegurado. El robo a secuestro incluye sin limitaci&oacute;n, el robo calificado con violencia o el robo con retenci&oacute;n del Asegurado con el prop&oacute;sito de robarle ya sea la tarjeta de cr&eacute;dito, el dinero retirado de un cajero automatico o a obligarle a diulgar sus claves y/o pines para efectuar retiros ilegales de su cuenta o el robo por medio de hechos violentos como robo con fractura o robo por escalamiento del domicilio o local donde se encuentre el Asegurado y como consecuencia de dichos actos, la tarjeta de cr&eacute;dito del Asegurado haya sido objeto de robo y retiros frauduentos de su cuenta. La cobertura funcionar&aacute; dentro o fuera del territorio Noliviano.
                            <br />
                            
                            <span>1.08</span>
                            <span style="<?=$title_regla1;?>">COBERTURA DE TRASLADO MEDICO TERRESTRE A CONSECUENCIA DE ASALTO Y/O SECUESTRO:</span> 
                            Es el gasto que se acredite haber erogado dentro de Territorio Boliviano por, la contrataci&oacute;n necesaria de una ambulancia o cualquier otro medio de transporte terrestre con acompañamiento de personal especializado en primeros auxilios, con el fin de trabajar al Asegurado herido o un centro m&eacute;dico asistencial a causa de las lesiones infligidas por el Asalto y/o Secuestro cubierto por esta p&oacute;liza. Se aclara que la cobertura funcionar&aacute; mediante reembolso, contra entrega de Factura.                            <br />
                            
                            <span>1.09</span>
                            <span style="<?=$title_regla1;?>">COBERTURA DE GASTOS POR ASISTENCIA M&Eacute;DICA A CONSECUENCIA DE ASALTO Y/O SECUESTRO:</span> 
                            Son los gastos m&eacute;dicos erogados dentro de Territorio Boliviano y facturados por el centro m&eacute;dico asistencial y por los profesionales m&eacute;dicos que dieron asistencia al Asegurado herido, por las lesiones sufridas como consecuencia de Asalto y/o Secuestro cubierto por esta p&oacute;liza. Se aclara que la cobertura funcionar&aacute; mediante reembolso contra entrega de factura y sobre la base de los aranceles m&eacute;dicos Bolivianos.
                            <br />
                            
                            <span>1.10</span>
                            <span style="<?=$title_regla1;?>">COBERTURA DE VIDA ASOCIADA AL ASALTO Y/O SECUESTRO:</span> 
                            Es el beneficio que el Asegurador garantiza indemnizar hasta el limite de responsabilidad establecido en las Condiciones Particulares o favor de los herederos legales, en caso de muerte del Asegurado cuya causa est&eacute; debidamente certificada por las Autoridades Competentes y por los Profesionales M&eacute;dicos como causa asociada y/o vinculada a las lesiones inflingidas por los delincuentes en el Asalto y/o Secuestro cubierto por esta p&oacute;liza. Se aclara que la cobertura funcionar&aacute; dentro o fuera de territorio Boliviano.
                            <br />
                            
                            <span>1.11</span>
                            <span style="<?=$title_regla1;?>">COBERTURA DE GASTOS POR RECUPERACI&Oacute;N DE DOCUMENTOS:</span> 
                            Cubre los gastos que el Asegurado acredite haber realizado en Territorio Boliviano, con el proposito de obtener la reposici&oacute;n y/o duplicados de docuemtos personales y otras tarjetas de cr&eacute;dito o cr&eacute;dito que hayan sido objeto de robo o sustracci&oacute;n en el asalto y/o secuestro cubierto por la p&oacute;liza ya sea que el evento haya ocurrido dentro o fuera del territorio Boliviano. La cobertura funcionar&aacute; mediante reembolso contra entrega de documentos que sustenten los cobros realizados por los entes emisores respectivos y dem&aacute;s comprobantes de gastos relacionados directamente con la reposici&oacute;n de dichos documentos; siempre que los mismos se especifiquen en el Atestado o Manifiesto Policial emitido por las autoridades competentes.
                            <br />
                            
                            <span>1.12</span>
                            <span style="<?=$title_regla1;?>">COBERTURA CONTRA ROBO CON VIOLENCIA EN LA VIVIENDA HABITUAL O DA&Ntilde;OS ACCIDENTALES DE LOS BIENES:</span> 
                            Cubre el robo con violencia o los da&ntilde;os que por accidente se causen a los bienes que fueron adquiridos con la tarjeta por un periodo no mayor a 60 d&iacute;as contados desde la fecha de compra.
                            <br />
                            
                            <span>1.13</span>
                            <span style="<?=$title_regla1;?>">COBERTURA DE EXTENSI&Oacute;N DE GARANT&Iacute;A DEL FABRICANTE:</span> 
                            Duplica o extiende la garantia del articulo comprado con la tarjeta de cr&eacute;dito por un periodo no mayor a 12 meses desde la fecha de compra.
                            <br />
                            
                            <span style="<?=$font_bold;?>">II.</span>
                            <span style="<?=$title_regla;?> text-decoration:none;">CONDICIONES DE ADHESI&Oacute;N DE LOS ASEGURADOS</span><br />
                            <span>2.01</span>
                            Podr&aacute;n pertenecer al colectivo asegurado todos los tarjetahabientes Titulares y Adicionales que re&uacute;nan los requisitos o condiciones de adhesi&oacute;n en la fecha de efecto o con posterioridad y figuren en ultima planilla de declaraci&oacute;n de asegurados elaborada para tal efecto por el "Tomador" y que forma parte de la p&oacute;liza.<br />
                            La cobertura para cada asegurado ser&aacute; aplicable de la siguiente forma:
                            <ol style="list-style:circle;">
                            	<li>Nuevos tarjeta-habientes: Nuevos tarjeta-habientes: desde la fecha de otorgamiento de la linea de cr&eacute;dito por parte del tomador.</li>
                                <li>Las lineas de cr&eacute;dito correspondiente a operaciones otorgadas por el tomador antes del inicio de la vigencia de la presente p&oacute;liza y/o sus renovaciones.</li> 
                            </ol>
                            En cada vencimiento de la p&oacute;liza, este seguro ser&aacute; renovado automaticamente por un nuevo periodo anual. En este periodo el asegurado se sujetara a los t&eacute;rminos y condiciones de la vigencia renovada de la p&oacute;liza por una nueva anualidad.
                            <br />

                            <span style="<?=$font_bold;?>">III.</span>
                            <span style="<?=$title_regla;?> text-decoration:none;">P&Eacute;RDIDA DE LA CONDICI&Oacute;N DE PERTENENCIA AL GRUPO</span><br />
                            <span>3.01</span>
                            Los beneficios otorgados por este seguro cesar&aacute;n automaticamente sin derecho a devoluci&oacute;n de primas en los siguientes casos:
                            <ol style="list-style:circle;">
                            	<li>Por cancelaci&oacute;n de la tarjeta de cr&eacute;dito.</li>
                                <li>Por la expiraci&oacute;n sin renovaci&oacute;n de la tarjeta de cr&eacute;dito</li>
                                <li>Por el incumplimiento o mora en el pago de las primas seg&uacute;n reglamento interno del Tomador</li>
                                <li>En la fecha de vencimiento de las p&oacute;lizas salvo en los siguientes casos.</li>
                            </ol>
                            <ol style="list-style:lower-alpha;">
                                <li>Los casos en los que las eventualidades previstas y cubiertas por esta p&oacute;lisa hayan ocurrido dentro de la vigencia de la p&oacute;liza y el tomador haya cumplido con la condici&oacute;n "plazo de aviso" inserta en las condiciones particulares.</li>
                            </ol>
                                
                        </td>
                        <td style="width:1%; border-left:1px solid #000;">&nbsp;</td>
                        <td style="width: 49%; padding: 5px;" valign="top">  
	                        <ol style="list-style:lower-alpha">
                                <li value="2">La p&oacute;liza haya sido renovada por el tomador a su vencimiento (sin perjuicio de que las condiciones puedan modificarse en cada renovaci&oacute;n).</li>
                            </ol>
                            <span style="<?=$font_bold;?>">IV.</span>
                            <span style="<?=$title_regla;?> text-decoration:none;">LA P&Oacute;LIZA</span><br />
                            <span>4.01</span>
                            No se pagar&aacute; ninguna indemnizaci&oacute;n conforme a estas reglas si la suma asegurada correspondiente no resultara pagadera con arreglo a las condiciones de la P&oacute;liza. Cualquier miembro puede examinar la p&oacute;liza si lo cree oportuno, previa coordinaci&oacute;n con el Tomador.<br />
                            
                            <span style="<?=$font_bold;?>">V.</span>
                            <span style="<?=$title_regla;?> text-decoration:none;">MODIFICACI&Oacute;N O TERMINACI&Oacute;N</span><br />
                            <span>5.01</span>
                            Las condiciones estipuladas en el presente contrato, permanecer&aacute;n inalterables durante el periodo de vigencia de la p&oacute;liza, salvo que el "Tomador" y "Asegurador", puedan convenir modificaciones mediante anexo.<br />
                            
                            <span>5.02</span>
                            El "Tomador" y el "Asegurador" se reservan el derecho de finalizar el contrato.
                            <br />
                            
                            <span style="<?=$font_bold;?>">VI.</span>
                            <span style="<?=$title_regla;?> text-decoration:none;">RESTRICCIONES Y EXCLUSIONES</span><br />
                            No obstante las restricciones y exclusiones estipuladas en las condiciones generales de la p&oacute;liza, se enumeran a continuaci&oacute;n y con caracter enunciativo y no limitativo en las siguientes exclusiones generales:<br />
                            La presente P&oacute;liza no cubre ninguna p&eacute;rdida, da&ntilde;o o costo resultante de, o que surja de:<br />                      
                            <span>6.01</span>
                            Que el Asegurado, su conyuge o cualquier pariente del Asegurado dentro del cuarto grado de consanguinidad o segundo de afinidad sea autor o complice del Robo, Asalto, Secuestro o Clonacion.<br />
                            
                            <span>6.02</span>
                            Que el Robo, Asalto o Atraco sea cometido durante cualquiera de las siguientes situaciones:
                            	<ol style="list-style:disc;">
                                	<li>Guerra internacional a civil, actos perpetrados por fuerzas extranjeras, hostilidades u operaciones belicas (sea que se haya declarado guerra o no), rebeli&oacute;n, sedici&oacute;n o usurpacion de poder.</li>
                                    <li>Cualquier p&eacute;rdida consencuencial incluyendo pero no limitandose a: Interrupcion de Negocios, Demora, P&eacute;rdida de Mercado, Lucro Cesante, Retraso o cualquier otra p&eacute;rdida similar.</li>
                                    <li>Transacciones realizadas a trav&eacute;s de ventas por cat&aacute;logo, por tel&eacute;fono o por cualquier medio de transmision de datos en que no exista la firma manuscrita del titular o adicional.</li>
                                </ol>
                            <br />
                            
                            <span style="<?=$font_bold;?>">VII.</span>
                            <span style="<?=$title_regla;?> text-decoration:none;">PROCEDIMIENTO EN CASO DE SINIESTROS</span><br />
                            El Asegurado o beneficiario y el Tomador seg&uacute;n el caso, facilitar&aacute;n en caso de siniestro, los siguientes informes y evidencias:<br />
                            <span>7.01</span>
                            <span style="<?=$title_regla1;?>">CASOS DE ROBO, HURTO O EXTRAVIO DE LA TARJETA DE CR&Eacute;DITO:</span> 
                            <table border="0" cellpadding="0" cellspacing="0" style="font-size:8px; text-align:justify;">
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(a)</td>
                                    <td style="width: 90%">
                                        Carta de reclamo al seguro emitido por el Tomador con confirmaci&oacute;n y cuantificaci&oacute;n de p&eacute;rdidas en la cuenta del Asegurado.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(b)</td>
                                    <td style="width: 90%">
                                        Copia de la carta del Asegurado al Tomador por p&eacute;rdidas detectadas e identificadas en su Estado de Cuentas y/o por denuncia de robo y/o extravio de la tarjeta de cr&eacute;dito siempre que estos hechos le hayan originado perdida en su cuenta y/o en defecto de la carta de reclamo a la Entidad Financiera copia del "Formulario de Reclamo del Tarjetahabiente" debidamente llenado en formato que para tal efectoes proporcionado por la Entidad Financiera.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(c)</td>
                                    <td style="width: 90%">
                                        Copia de la denuncia hecha a la polic&iacute;a por el Tarjetahabiente afectado, con el relato de las circunstancias del robo y/o hurto y/o extravio de la Tarjeta de cr&eacute;dito y/o segun sea el caso.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(d)</td>
                                    <td style="width: 90%">
                                        Copia del Estado de Cuentas en el que aparezcan transacciones fraudulentas.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(e)</td>
                                    <td style="width: 90%">
                                        Copia del carnet de identidad del tarjetahabiente
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(f)</td>
                                    <td style="width: 90%">
                                        Copia del pasaporte del tarjetahabiente en caso de que el robo y/o hurto y/o extrav&iacute;o se hubieren producido fuera del pa&iacute;s.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(g)</td>
                                    <td style="width: 90%">
                                        Informe sobre las transacciones reclamadas emitida por la Administradora de tarjetas de Cr&eacute;dito.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(h)</td>
                                    <td style="width: 90%">
                                        Copia del Contrato de Caja de Ahorro suscrito entre el Tarjetahabiente y la Entidad Financiera.
                                    </td>
                                </tr>                           
                            </table>
                            
                            <span>7.02</span>
                            <span style="<?=$title_regla1;?>">CASOS DE CLONACI&Oacute;N:</span> Ademas de lo se&ntilde;alado en el punto 7.01:
                            <table border="0" cellpadding="0" cellspacing="0" style="font-size:8px; text-align:justify;">
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(a)</td>
                                    <td style="width: 90%">
                                        Copia de la tarjeta de cr&eacute;dito afectada (anverso y reverso).
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(b)</td>
                                    <td style="width: 90%">
                                        Informe y dictamen sobre cl&oacute;nacion y/o Skimming emitido por la Administradora de Tarjetas de Cr&eacute;dito y copia de los vouchers evaluados.
                                    </td>
                                </tr>                                                          
                            </table>
                            
                            <span>7.03</span>
                            <span style="<?=$title_regla1;?>">CASOS DE ROBO POR ASALTO Y/O SECUESTRO DEL ASEGURADO:</span> Ademas de lo se&ntilde;alado en el punto 7.01:
                            <table border="0" cellpadding="0" cellspacing="0" style="font-size:8px; text-align:justify;">
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(a)</td>
                                    <td style="width: 90%">
                                        Copia de la carta del Asegurado o de sus familiares o representantes legales (seg&uacute;n sea el caso) a la Entidad Financiera con el relato de los hechos y reclamo de p&eacute;rdidas en la cuenta del Asegurado como consecuencia del robo por Asalto y/o Secuestro cometidos al Asegurado.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(b)</td>
                                    <td style="width: 90%">
                                        Copia del Informe Policial y/o Manifiesto y/o Atestado Policial, emitido por las autoridades como resultado de las investigaciones.
                                    </td>
                                </tr>                                                          
                            </table>
                            
                            <span>7.04</span>
                            En caso de aplicai&oacute;n de las coberturas adicionales asociadas al Asalto y/o Secuestro, la Aseguradora requerira adicionalmente:
                            <table border="0" cellpadding="0" cellspacing="0" style="font-size:8px; text-align:justify;">
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(a)</td>
                                    <td style="width: 90%">
                                        Original del Certificado M&eacute;dico sobre lesiones del Asegurado.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(b)</td>
                                    <td style="width: 90%">
                                        Copia de las facturas y/o comprobantes que acrediten los gastos incurridos por concepto de traslado m&eacute;dico terrestre, gastos por asistencia m&eacute;dica al Asegurado, gastos por recuperaci&oacute;n de documentos.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(c)</td>
                                    <td style="width: 90%">
                                        En caso de Muerte del Asegurado: Informe del M&eacute;dico Forense, Original Certificado de Defunci&oacute;n, Original de la Declaratoria de Herederos, Histodia Clinica del Asegurado (siempre que sea aplicable al caso). Si el fallecimiento ocurriese fuera del pa&iacute;s, el Asegurador requerir&aacute; que los documentos de acreditaci&oacute;n de muerte del Asegurado cuenten con las legalizaciones correspondientes en Bolivia.
                                    </td>
                                </tr>                                                          
                            </table>
                            
                            <span>7.05</span>
                            <span style="<?=$title_regla1;?>">CASOS DE PROTECCI&Oacute;N DE COMPRA, ROBO CON VIOLENCIA O DA&Ntilde;OS ACCIDENTALES:</span>
                            <table border="0" cellpadding="0" cellspacing="0" style="font-size:8px; text-align:justify;">
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(a)</td>
                                    <td style="width: 90%">
                                        Carta de reclamo al Asegurador emitida por el Tomador con la confirmaci&oacute;n y cuantifici&oacute;n del robo o da&ntilde;os sufridos por el Asegurado.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(b)</td>
                                    <td style="width: 90%">
                                        Copia de la carta del Asegurado el Tomador denunciando el robo o da&ntilde;os de los bienes adquiridos con su tarjeta de cr&eacute;dito y/o debito.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(c)</td>
                                    <td style="width: 90%">
                                       En el caso de robo, copia de la denuncia hecha por el Tarjetahabiente afectado, con el relato de las circusntancias del robo y el detalle de los bienes robados adquiridos con su tarjeta.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(d)</td>
                                    <td style="width: 90%">
                                        Copia del carnet de identidad del tarjetahabiente.
                                    </td>
                                </tr> 
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(e)</td>
                                    <td style="width: 90%">
                                        Copia del voucher y factura de compra de los bienes adquiridos.
                                    </td>
                                </tr>                                                          
                            </table>
                            	
                            <span>7.06</span>
                            <span style="<?=$title_regla1;?>">CASO DE EXTENSI&Oacute;N DE GARANT&Iacute;A DEL FABRICANTE:</span>
                            <table border="0" cellpadding="0" cellspacing="0" style="font-size:8px; text-align:justify;">
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(a)</td>
                                    <td style="width: 90%">
                                        Carta de reclamo al Asegurador emitida por el Tomador solicitando se aplique la cobertura de extensi&oacute;n de garantia del fabricante.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(b)</td>
                                    <td style="width: 90%">
                                        Copia de la carta del Asegurado el Tomador solicitando se extienda la Garantia del Fabricante del bien adquerido con su tarjeta de cr&eacute;dito y/o d&eacute;bito.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(c)</td>
                                    <td style="width: 90%">
                                        Copia del carnet de identidad del tarjetahabiente.
                                    </td>
                                </tr> 
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(d)</td>
                                    <td style="width: 90%">
                                        Copia del voucher y factura de compra del bien adquirido.
                                    </td>
                                </tr>                                                    
                                <tr>
                                    <td style="width: 10%" align="center" valign="top">(e)</td>
                                    <td style="width: 90%">
                                       Copia de la garantia del Fabricante.
                                    </td>
                                </tr>   
                            </table>	
                            
                            En caso de ser necesario la compañia se reserva el derecho de exigir cualquier otra informaci&oacute;n adicional que pudiese ayudar a determinar el pago del Siniestro. 			
                        </td>
                    </tr>
                </table>
            </div><br /><br />
            <div style="<?=$content3;?>">
           		SANTA CRUZ: Av Monse&ntilde;or Rievero, 223-2 &bull; Tel&eacute;fono: 371-6565 &bull; Fax: 371-6905 &bull; LA PAZ: Calle Capit&aacute;n Ravelo, 2334 &bull; Tel&eacute;fono: 244-2942 &bull; Fax: 244-2905 &bull; COCHABAMBA: Av. Libertador, 1150 Tel&eacute;fono: 444-8750 &bull; Fax: 444-8754
           </div>
        </div> 
        
        
    </div> 
  <?php	
  $html = ob_get_clean();
  return $html;
}
?>