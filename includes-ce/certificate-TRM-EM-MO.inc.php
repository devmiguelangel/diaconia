<?php
function trm_em_certificate_mo($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
	$fuentePDF = '';
	//if($pdf == TRUE){ $fuentePDF = 'font-size: 9px;'; }else{ $fuentePDF = ''; }
	ob_start();
	
	if ($rsDt->data_seek(0) === true) {
		while ($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)) {
			$prefix = json_decode($rowDt['prefix'], true);
?>
<div class="container-c-des" style="width:790px;">
<?php
/*--------------------STYLE--------------------*/
$main_c_des='width: 770px; height: auto; margin-top:0; margin-left:15px; padding: 0;';

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
$table='width: 100%; font-weight: normal; font-size: 10px; '.$fuentePDF.' font-family: Arial; margin: 2px 0 0 0; padding: 0; border-collapse: collapse; vertical-align: bottom;';
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
		<div style="<?=$container_1;?>">
        <div style="<?=$header;?>">
        	<h1 style="<?=$header_h1;?>">
                CERTIFICADO INDIVIDUAL DE COBERTURA<br>

				P&Oacute;LIZA DE SEGURO COLECTIVO DE RAMOS TECNICOS PARA GARANTIAS PRENDARIAS CON PRIMA MENSUAL<br>
            </h1>           
            <h4 style="<?=$header_h4;?>">
				APROBADO SEG&Uacute;N RESOLUCI&Oacute;N ASFI N&deg; 236/2010 DE FECHA 26 DE MARZO DE 2010<br>
				C&Oacute;DIGO ASIGNADO: 115-910761-2010 02 076-4001
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
						<strong>
							<?=$rowDt['prefijo'] . '-' . $rowDt['no_detalle'];?>
						</strong>
                    </h4>
                    </td>
                </tr>
           </table>	
        </div>
        
        
        	<div style="<?=$content;?>">
            <h2 style="<?=$h2;?>">
            	Se deja expresa constancia mediante el presente certificado, que la 
            	maquinaria o el equipo con valor de reposici&oacute;n a nuevo de 
            	acuerdo a aval&uacute;o de $us <?=number_format($rowDt['valor_asegurado'], 2, '.', ',');?> 
            	constituido en garant&iacute;a hipotecaria del banco Econ&oacute;mico 
            	S.A. por el cr&eacute;dito otorgado N&deg; <?=$rowDt['no_detalle'];?> 
            	a favor de: <?=$row['cliente_nombre'];?> se encuentra amparado bajo 
            	la p&oacute;liza contra Ramos T&eacute;cticos para garantias 
            	Prendarias N&deg; <?=$prefix['policy'];?> de acuerdo a los 
            	t&eacute;rminos y condiciones estipulados en el mencionado 
            	Contrato de Seguros suscrito entre el "Tomador" y el "Asegurador".
            </h2>
            	<table border="0" cellpadding="0" cellspacing="0" style="text-align: justify; font-size: 8px; width: 100%;">
                    <tr>
                        <td style="width: 50%; padding: 5px;" valign="top">
                            <span style="<?=$font_bold;?>">COBERTURAS:</span><br />
                            <span style="<?=$font_bold;?>">CONVENIO I: TODO RIESGO PARA EQUIPO Y MAQUINARIA DE CONTRATISTAS RIESGOS ASEGURADOS</span><br />
                            <ol style="list-style:square;">
                            	<li>Incendio y/o Rayo.</li>
                                <li>Colicion con objetos en movimiento o estacionarios, volcamientos, hundimiento de terreno, deslizamiento de tierra, descarrilamiento.</li>
                                <li>Accidentes que ocurran pese a un manejo correcto, asi como los que sobrevengan por descuido, inpericia o negligencia del conductor (salvo actos intencionales o negligencia manifiesta del asegurado o sus representantes).</li>
                                <li>Explosi&oacute;n (excluyendo la explosi&oacute;n originadas en calderas de vapor o motores de combusti&oacute;n interna).</li>
                                <li>Robo con violencia y/o asalto, as&iacute; como tambi&eacute;n los da&ntilde;os causados por dicho delito o su intento.</li>
                                <li>Accidentes que ocurran durante el montaje, desmontaje y traslado de la maquinaria dentro de los predios del prestatario o arrendatario asegurado o fuera de ellos, mientras la maquinaria de viaje por sus propios medios de un sitio de operaciones a otro o depositada en cualquier otro lugar que no sean los del prestatario o arrendatario, incluyendo traslado sobre otros veh&iacute;culos.</li>
                                <li>Perdidas o da&ntilde;os causados por inundacion, maremoto, cicl&oacute;n, huracan, tempestad vientos, terremoto, temblor, erupci&oacute;n volcanica o cualquier otra convulsi&oacute;n de la naturaleza.</li>
                                <li>Perdidas o da&ntilde;os causados por cualquier otro riesgo que no se encuentre especificamente excluido de las condiciones generales para el seguro de equipo y maquinaria, segun texto contractual, aprobado por la Autoridad de Supervisi&oacute;n del Sistema Financiero (ASFI).</li>
                            </ol>

                            <span style="<?=$font_bold;?>">COBERTURA ESPECIAL</span><br />
                            No estante en lo establecido en las condiciones generales de la p&oacute;liza, se deja expresa constancia que el presente seguro cubrir&aacute; en caso de siniestro cubierto bajo la presente p&oacute;liza la rotura de vidrios.<br />

                            <span style="<?=$font_bold;?>">COBERTURA ADICIONAL INCORPORADA</span><br />
                            Da&ntilde;os a la maquinaria asegurada a causa de huelga, motin y conmoci&oacute;n civil<br />
                            
                            <span style="<?=$font_bold;?>">CONVENIO II: COBERTURA PARA ROTURA DE MAQUINARIA<br />
                            RIESGOS ASEGURADOS</span><br />
                            <ol style="list-style:square;">
                            	<li>Impericia, negligencia y actos mal intencionados individuales del personal asegurado o de extraños</li>
                                <li>La acci&oacute;n directa o indirecta de la energia el&eacute;ctrica como resultado de cortocircuito, arco voltaicos y otros efectos similares, as&iacute; como los debidos a perturbaciones el&eacute;ctricas consecuentes a la caida del rayo directo o en las proximidades de la instalaci&oacute;n</li>
                                <li>Errores de dise&ntilde;o, c&aacute;lculo o montaje, defectos de fundaci&oacute;n, de material de construcci&oacute;n, de mano de obra y empleo de materiales defectuosos</li>
                                <li>Falta de agua y otros aparatos productores de vapor</li>
                                <li>Fuerza centrifuga, pero solamente la p&eacute;rdia o da&ntilde;o sufrido por desgarramiento en la misma maquinaria</li>
                                <li>Cuerpos extra&ntilde;os que se introduzcan en los bienes asegurados o los golpeen</li>
                                <li>Defectos de engrase, aflojamiento, esfuerzos anormales, fatiga molecular y auto calentamiento</li>
                                <li>Fallo en los dispositivos de regulacion</li>
                                <li>Tempestad, granizo, helada y deshielo</li>
                                <li>Cualquier otra causa no excluida expresamente</li>
                            </ol>                                
                        </td>
                        
                        <td style="width: 50%; padding: 5px;" valign="top">  
	                        Este seguro cubre la maquinaria asegurada &uacute;nicamente dentro del predio del asegurado, se encuentre funcionando o parada, as&iacute; como durante su desmontaje y montaje subsiguiente con objeto de proceder a su limpieza o reacondicionamiento<br />
                            <span style="<?=$font_bold;?>">CONVENIO II:<br />
                            COBERTURAS ADICIONALES</span><br />
                            <ol style="list-style:square;">
                            	<li>Cl&aacute;usula 001 Huelga, Motin y Conmocion Civil</li>
                                <li>Cl&aacute;usula 312 cobertura para el Equipo Movil (Riesgo de Casco) transporte incluido</li>
                                <li>Cl&aacute;usula 313 coberturas para incendio interno, explosi&oacute;n quimica interna y caida directa de rayo. Se deja constancia que bajo la cobertura de esta cl&aacute;usula la presente p&oacute;liza se extender&aacute; a cubrir los bienes asegurados contra los riesgo de imposi&oacute;n y explosi&oacute;n f&iacute;sica</li>
                                <li>Cl&aacute;usula 314 cobertura par explosi&oacute;n en motores de conbustion interna y generadores refrigerados por hidrogeno y/o por cualquiero otro medio refrigerante</li>
                                <li>Cl&aacute;usula 316 inundaci&oacute;n y enlodamiento</li>
                                <li>Cl&aacute;usula 318 bombas sumergidas y bombas para pozos profundos</li>
                            </ol>
                             
                             <span style="<?=$font_bold;?>">COBERTURA COMPLEMENTARIA</span><br />			
                             La Compa&ntilde;&iacute;a indemnizar&aacute; al asegurado las p&eacute;rdidas o da&ntilde;os sobre los bienes descritos en los reportes mensuales proporcionados por el tomador causados por incendio o explosi&oacute;n externos a los bienes asegurados e impart directo del rayo incluyendo da&ntilde;os por la acci&oacute;n directa o indirecta de la energia el&eacute;ctrica y atmosf&eacute;rica.<br />
                             <span style="<?=$font_bold;?>">CONVENIO III: COBERTURA CONTRA TODO RIESGO DE EQUIPO ELECTRONICO RIESGOS ASEGURADOS</span><br />			
                             <ol style="list-style:square;">
                             	<li>Todo riesgo de Equipo electr&oacute;nico seg&uacute;n condicionado estandar de la M&uuml;nchener Ruck, incluyendo da&ntilde;os por acci&oacute;n directa e indirecta de la energia el&eacute;ctrica y atmosferica</li>
                                <li>Incendio, impacto de rayo, explosi&oacute;n, implosi&oacute;n</li>
                                <li>Humo, hollin, gases o liquidos o polvos corrosivos</li>
                                <li>Inundacion; acci&oacute;n del agua y humedad, siempre que no provengan de condiciones atmosfericas normales ni del ambiente en que se encuentren los bienes asegurados</li>
                                <li>Corto circuito, azogamiento, arco voltaico, perturbaciones por campos magneticos: asilamiento insuficiente, sobreteneciones causadas por rayos, tostaci&oacute;n de aislamientos</li>
                                <li>Errores de construcci&oacute;n, fallas de montaje, defectos de material</li>
                                <li>Errores de manejo, descuido, impericia, as&iacute; como da&ntilde;os malintencionados y dolo de terceros</li>
                                <li>Robo con violencia</li>
                                <li>Granizo, helada, tempestad</li>
                                <li>Otros accidentes no excluidos en las condiciones generales ni en las condiciones especiales y/o particulares</li>
                             </ol>
                             <span style="<?=$font_bold;?>">COBERTURAS ADICIONALES</span><br />			
                             <ol style="list-style:square;">
                             	<li>Terremoto, Temblor, Erupcion Volcanica</li>
                                <li>Ventarron, Huracan, Tempestad y Granizo</li>
                                <li>Cl&aacute;usula 001 - Huelgas, Motines, Conmociones Civiles</li>
                                <li>Cl&aacute;usula 504 - Cobertura de Equipos M&oacute;viles Portatiles</li>
                                <li>Cl&aacute;usula 507 - Cobertura del Valor nuevo (Para equipos obsoletos la indemnizaci&oacute;n ser&aacute; equivalente al costo de reemplazo de los equipos obsoletos por otros equivalentes en el mercado en capacidad y extensi&oacute;n)</li>
                             </ol>
                        </td>
                    </tr>
                </table>
                
                <h2 style="<?=$h2;?>"><span style="<?=$font_bold;?>">TASAS Y FORMAS DE PAGOS:</span> Tasas aplicables sobre los valores asegurados establecidos en los Aval&uacute;os y/o inventarios Valorados. La prima ser&aacute; cancelada por el "Tomador" cada mes vencido y a su vez el "Tomador" cargar&aacute;eñ costo de este seguro por adelantado en cuotas de amortizaci&oacute;n de cada prestatario en funci&oacute;n de su cronograma de pago.</h2>
                                
                <h4 style="<?=$h4_s;?>">TASA &nbsp; &nbsp; &nbsp; &nbsp; 0.833 &nbsp; &nbsp; &nbsp; &nbsp; %(POR MIL)</h4>
                
                <h3 style="<?=$h3;?>">MATERIA ASEGURADA</h3>
                	<ol style="list-style:square; font-size:9px; text-align:justify; font-weight:normal;">
                    	<li><span style="<?=$font_bold;?>">CONVENIO I - </span>Maquinaria pesada m&oacute;vil (Gr&uacute;as, palas mec&aacute;nicas, excavadoras, camiones concretos, motoniveladoras, trctores, equipos de perforaci&oacute;n de pozos de agua, y otros similares), incluyendo sus equipos auxiliares, ya sean que est&eacute;n conectados o no al equipo o maquinaria obejto del seguro o que se encuentren operando o durante su trayecto por sus propios medios o no dentro o fuera de los predios.</li>
                        <li><span style="<?=$font_bold;?>">CONVENIO II - </span>Maquinas generadoreas de energia (Caldera, turbinas, generadores), maquinas e instalaciones distribuidoras de energia el&eacute;ctrica (transformadores, intalaciones de alta y baja tensi&oacute;n), maquinas de producci&oacute;n y equipos auxiliares (Motores, bombas, recipientes, homogenizadores, compresores, disyuntores, silos, maquinaria industrial y otros similares). Est&eacute;n en funcionamiento o no o durante el desmontaje para fines de mantenimiento y reparaci&oacute;n, su remontaje y puessta en marcha.</li>
                        <li><span style="<?=$font_bold;?>">CONVENIO III - </span>Equipos electr&oacute;nicos de procesamiento de datos, equipos de radiacion el&eacute;ctrica para uso m&eacute;dico, sistema de telecomunicaciones y otros equipos que funcionen con baja tensi&oacute;n de corriente el&eacute;ctrica, est&eacute;n en funcionamiento o no o durante el desmontaje para fines de mantenimiento y reparacion, su remontajo y puesta en marcha.</li>
                    </ol>
                    
                <h3 style="<?=$h3;?>">ACTIVIDADES DECLARADAS Y/O GIROS DEL NEGOCIO</h3>
                <h2 style="<?=$h2;?>">Sin prohibici&oacute;n ni exclusi&oacute;n, ni restricci&oacute;n de giros de negocio y/o actividades que se desarrollan para la utilizaci&oacute;n de los bienes asegurados; excluyendo equipos y/o maquinarias petroleras y/o usados para dicho fin; vehiculos motorizados dise&ntilde;ados exclusivamente para circular en caminos y carreteras, destinados al transporte de pasajeros o mercader&iacute;as, partes o pertenencias de los mismos, embarcaciones o cualquier otro tipo flotante; maquinarias o equipos ubicados y trabajando en subterraneos, socavones, minas y agua.</h2><br />
                
                <h3 style="<?=$h3;?>">CONTRATO PRINCIPAL (P&Oacute;LIZA MATRIZ)</h3>
                <h2 style="<?=$h2;?>">LATINA SEGUROS PATRIMONIALES S.A., Compa&ntilde;&iacute;a de Seguros y Reaseguros asegura al Prestatario, seg&uacute;n las condiciones descritas en el Contrato del Seguro de Ramos T&eacute;cnicos para Garant&iacute;as Prendarias; celebrando entre LATINA Seguros Patrimoniales S.A. y el Banco Economico S.A. condiciones que forman parte integrante del seguro aqu&iacute; concedido.<br />
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
          
           		<table cellpadding="0" cellspacing="0" border="0" style="<?=$table;?> width:100%; text-align:left;" align="center">
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
                            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%"><tr>
								<td style="width: 100%; border-bottom: 1px solid #000;">
									<?=$row['cliente_nombre'];?>
								</td>
                            </tr></table>
                        </td>                	
                        <td style="width:50%;">
                        <h4 style="<?=$footer_h1;?> font-size:11px;">
                        Fecha de Afiliaci&oacute;n: 
                        <?=date('d/m/Y', strtotime($row['fecha_real_emision']));?>
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
                                    <td>
                                    	FIRMA DEL ASEGURADO<br>
                                    	C.I. <?=$row['cl_ci'] . ' ' . $row['cl_extension'];?>
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
                    <strong>REGLAS DEL CONTRATO DE SEGURO DE RAMOS TECNICOS PARA GARANTIAS PRENDARIAS</strong>
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
                            &nbsp; En el momento en que el bien materia del seguro pierde su condici&oacute;n de garantia prendaria del tomador por cualquier motivo distinto al anteriormente mencionado.<br />
                            
                            <span>3.04</span>
                            &nbsp; Por incumplimiento en el pago de sus primas.<br />
                            
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
                            &nbsp; La responsabilidad m&aacute;xima del Asegurador en caso de siniestro son los valores de reposici&oacute;n del equipo o maquinaria que es el monto de adquisici&oacute;n de un bien nuevo de la misma clase y capacidad incluyendo costos de transporte, montaje y derechos aduaneros si los hubiere de acuerdo al avaluo en poder del Tomador, los cuales ser&aacute;n tomados cmo base de cualquier indemnizaci&oacute;n procedente de la presente p&oacute;liza, constituyendose para todos los efectos como valores unicos e inalterables. En ning&uacute;n caso, la suma indemnizable por el Asegurador bajo este seguro superar&aacute; el valor comercial mencionado para cada materia asegurada. Independientemente a lo se&ntilde;alado, el limite m&aacute;ximo de responsabilidad del Asegurador por las garantias de cada una de las materias aseguradas incorporadas por el Tomador no superar&aacute; en ning&uacute;n caso la suma de US$ 350.000,00 (TRESCIENTOS CINCUENTA MIL 00/100 DOLARES AMERICANOS).<br />
                            
                            <span>6.02</span>
                            &nbsp; En caso de que el "Tomador", como administrador colectivo, cargase primas por capitales asegurados superiores a la responsabilidad m&aacute;xima del "Asegurador", la responsabilidad del "Tomador" y el "Asegurador" frente al asegurado y/o sus representantes se limitaran a la devoluci&oacute;n de las primas cobradas y pagadas en exceso.<br />
                            
                            <span>VII.</span>
                            <span style="<?=$title_regla;?>">RESTRICCIONES Y EXCLUSIONES</span><br />
                            No obstante las restricciones  exclusiones estipuladas en las condiciones generales de la p&oacute;liza se enumeran a continuaci&oacute;n con car&aacute;cter enunciativo y limitativo las siguientes:<br />
                            APLICABLE A TODOS LOS CONVENIOS
                            <ol style="list-style:square">
                            	<li>Por hechos producidos en conexi&oacute;n o con motivo de hostilidades, acciones u operacions de guerra o invasi&oacute;nde enemigo extranjero (haya o no declaraci&oacute;n de estado de guerra) o guerra interna, revoluci&oacute;n, rebeli&oacute;n, insurrecci&oacute;n, terrorismo y otros hechos y delitos contra la seguridad interior o exterior del pais, aunque no sea a mano armada o bien de la administraci&oacute;n y gobierno de cualquier territorio o zona de estado de sitio o suspensi&oacute;n de garant&iacute;as o bajo el control de autoridades militares, o de acontecimientos que originen estas situaciones de hecho o de derecho o que ellos deriven directa o indirectamente relacionados con ellos, como quiera o donde quera que se originen.</li>
                                <li>La emisi&oacute;n de radiaciones ionizantes, o contaminaci&oacute;n de la radioactividad de cualquier combustible nuclear o de cualquier desperdicio proveniente de la combusti&oacute;n de dicho combustible.</li>
                                <li>Da&ntilde;os que no sean originados en forma s&uacute;bita e imprevista.</li>
                                <li>Actos de autoridad, tales como: decomiso, apropiaci&oacute;n, expropiaci&oacute;n o requisici&oacute;n.</li>
                                <li>Las perdidas o da&ntilde;os que directa o indirectamente provengan de siniestros causados por dolo, mala fe o culpa grave del asegurado, sus apoderados, sus representantes legales, personal directivo a quienes se les haya otorgado la direcci&oacute;n y control de la empresa, sus beneficiarios y personas por quienes sea civilmente responsable.</li>
                                <li>Los da&ntilde;os al interes asegurado, causado intencionalmente o motivados por resentimiento, odio o venganza hacia el asegurado, sus familiares o dependientes sin proponerse el autor o autores del delito provecho o lucro alguna; entendiendoel provecho o lucro como beneficio econ&oacute;mico.</li>
                                <li>Los da&ntilde;os o perdidas indirectas, consecuenciales o perdida de beneficio de cualquier tipo</li>
                                <li>Contaminaci&oacute;n, poluci&oacute;n, s&uacute;bita o gradual</li>
                                <li>Cualquier reclamo objeto de cobertura de ramos diferentes</li>
                                <li>Los hechos intencionales del asegurado y responsabilidad de orden penal no estar&aacute;, en ning&uacute;n caso a cargo de la Compa&ntilde;&iacute;a </li>
                                <li>Se excluyen equipos y/o maquinarias petroleras y/o usados para dicho fin; veh&iacute;culos motorizados dise&ntilde;ados exclusivamente para circular en caminos y carreteras destinados al transporte de pasajeros o mercader&iacute;as, partes o pertenencias de los mismos; embarcaciones y cualquier otro tipo flotante; maquinarias o equipos ubicados trabajando en subterr&aacute;neos, socavones, minas y agua.</li>
                            </ol>
                            APLICABLE UNICAMENTE AL CONVENIO I (TODO RIESGO PARA MAQUINAS Y EQUIPOS DE CONTRATISTAS)
                            <ol style="list-style:square">
                            	<li>Da&ntilde;os o perdidas por defectos el&eacute;ctricos o mec&aacute;nicos internos, faltas, roturas o desperfectos, congelaci&oacute;n del medio refrigerante o de otros liquidos, lubricacion deficiente o escases de aceite o del medio refrigerante; sin embargo, si a consecuencia de una falla o interrupci&oacute;n de esta indole se produjera un accidente que probocara da&ntilde;os externo a los bienes asegurados dichos da&ntilde;os externos son indemnizados por este seguro.</li>
                            </ol>
                        </td>
                        <td style="width:1%; border-left:1px solid #000;">&nbsp;</td>
                        <td style="width: 49%; padding: 5px;" valign="top">  
	                        <ol style="list-style:square">
                                <li>Da&ntilde;os o perdidas de piezas y accesorios sujetos al desgaste, tales como: brocas, taladros, cuchillas o demas herramientas de cortar, hojas de sierra, matrices, moldes, punzones, herramientas de moler y triturar, tamices y coladores, cables, correas, cadenas, bandas transportadoreas y elevadoras, baterias, neumaticos, alambres y cables para conexiones, tobos flexibles, material para fugas y empaquetadoras a reemplazar regularmente.</li>
                                <li>Da&ntilde;os o perdidas por explosi&oacute;n de calderas o recipientes a presion de vapor o de liquidos internos o de motores de combusti&oacute;n interna</li>
                                <li>Da&ntilde;os o perdidas que sean la consecuencia directa de las influencias continuas de la operaci&oacute;n, como ser: desgaste y deformaci&oacute;n, corrosi&oacute;n, gerrumbre, deterioro a causa de uso o falta de uso y de las condiciones atmosfericas normales.</li>
                                <li>Da&ntilde;os o perdidas causados a cualquier prueba de operaci&oacute;n que sean sometidos los bienes asegurados o si fueren utilizados para otro fin distinto al cual fueron construidos.</li>
                                <li>Da&ntilde;os o perdidas  debido a cualquier falla o defecto que ya exist&iacute;a al momento de contratarse el presente seguro y eran conocidas por el asegurador o por sus representantes, aunque los aseguradores hubieran tenido o no conocimiento de tales fallas o defectos.</li>
                                <li>Da&ntilde;os o perdidas directa o indirectamente y/u ocurridos o agravados por actos intencionales o negligencia manifiesta del asegurado o sus representates legales.</li>
                                <li>Da&ntilde;os o perdidas que se descubran solamente al efectuar un inventario fisico o revisiones de control.</li>
                            </ol>	
                            
                            APLICABLE UNICAMENTE AL CONVENIO II (ROTURA DE MAQUINARIA)
                            <ol style="list-style:square">
                            	<li>Defectos existentes al iniciarse el seguro, de los cuales tengan conocimiento el asegurado, sus representantes o personas responsables de la direcci&oacute;n t&eacute;cnica.</li>
                                <li>Derrumbe o remoci&oacute;n de escombros despues de un incendio.</li>
                                <li>Explosiones nucleares, contaminaci&oacute;n radioactiva y robo de cualquier clase</li>
                                <li>Fen&oacute;menos de naturalez tales como: terremoto, temblores, erupciones volcanicas, huracanes, ciclones, desbordamientos, enfangamientos, hundimientos, desprendimiento de tierras y rocas.</li>
                                <li>Desgaste, deterioro o deformaciones paulatinas como consecuencia del usao o del funcionamiento normal, da&ntilde;os al material de las maquinarias hidr&aacute;ulicas causados por al formacion de cavidades formadas por el liquido (cavidaci&oacute;n), erosiones, corrosiones, herrumbre e incrustaciones.</li>
                                <li>Perdidas o Da&ntilde;os de las cuales fuesen legal o contractualmente el fabricante o el vendedor del bien asegurado.</li>
                                <li>Averias causadas por pruebas, carga excesiva intencional o por experimentos que impliquen condiciones anormales.</li>
                                <li>Robo y hurto</li>
                            </ol>
                            
                            CONDICIONES PRECEDENTES (APLICABLES AL CONVENIO III DE EQUIPO ELECTRONICO)
                            <ol style="list-style:square">
                            	<li>instalaci&oacute;n acorde a normas y requerimientos t&eacute;cnicos de fabricantes y representantes.</li>
                                <li>Los equipos deben contar con aterramiento, polos a tiera, jabalina de cobre.</li>
                                <li>Los equipos deben contar con estabilizadores de voltaje apropiados para el equipo</li>
                                <li>Mantener el local en las condiciones t&eacute;cnicas recomendadas por los fabricantes y/o proveedores de los equipos.</li>
                                <li>Efectuar el mantenimiento seg&uacute;n lo indica el fabricante.</li>
                            </ol>
                            Cualquier da&ntilde;o o p&eacute;rdida, cuyo origen se deba al incumplimiento de las condiciones citadas eximir&aacute; de responsabilidad a la compa&ntilde;&iacute;a y por lo tanto no ser&aacute; indemnizable.<br />
                            
                            <span>VIII.</span>
                            <span style="<?=$title_regla;?>">PROCEDIMIENTO EN CASO DE SINIESTROS</span><br />
                            En caso de siniestros contemplados bajo el presente contrato, el asegurado debe obrar de la siguiente manera:
                           
                            <ol style="list-style:square">
                            	<li>Poner en conocimiento inmediato dentro de los 3 primeros d&iacute;as del hecho a la Compa&ntilde;&iacute;a o a Sudamericana S.R.L., para la correspondiente inspecci&oacute;n.</li>
                            </ol>
                            
                            Posterior al aviso, deber&aacute; presentar la siguiente documentacion:
                            
                            <ol style="list-style:square">
                                <li>Enviar por escrito una carta, mencionado las circunstancias del hecho, naturaleza y extensi&oacute;n de las perdidas o da&ntilde;os.</li>
                                <li>Tomar todas las medidas dentro de sus posibilidades para minorar a un m&iacute;nimo la extensi&oacute;n de la p&eacute;rdida o da&ntilde;o.</li>
                                <li>Conservar las partes da&ntilde;adas y ponerlas a disposici&oacute;n de un representante o experto de los aseguradores.</li>
                                <li>Informe t&eacute;cnico emitido por los bomberos(en caso de incendio).</li>
                                <li>Informe de tr&aacute;nsito en caso de impacto de vehiculos propios o ajenos.</li>
                                <li>Informe en conclusiones de la Policia en caso de P&eacute;rdidas o Da&ntilde;os a causa de los Riesgos Politicos cubiertos.</li>
                                <li>Informe de diligencia del Policia Judicial e caso de Robo o su tentativa.</li>
                                <li>Informe metereol&oacute;gico ofical en caso de p&eacute;rdidas o da&ntilde;os como consecuencia de Riesgos de la Naturaleza cubiertos.</li>
                                <li>Informe t&eacute;cnico de acuerdo al tipo de da&ntilde;o ocasionado (en caso de da&ntilde;os por agua)</li>
                                <li>Aval&uacute;o de la garant&iacute;a asegurada en poder del Tomador que garantiza la operaci&oacute;n crediticia.</li>
                                <li>Copia del Contrato de pr&eacute;stamo</li>
                                <li>Dos Presupuestos detallados de refacci&oacute;n de los da&ntilde;os amparables emitidos por reparadores</li>
                                <li>Cualquier otro informe o evidencia que el Asegurador pueda requerir con el objeto de determinar la procedencia del siniestro y cuantificaci&oacute;n de la p&eacute;rdida amparable.</li>
                                <li>Pagar la franquicia o Deducible a la compa&ntilde;&iacute;a seg&uacute;n el da&ntilde;o que tenga el bien</li>
                                <li>Convenio I: US$ 500.-</li>
                                <li>Convenio II: US$ 350.-</li>
                                <li>Convenio III: US$ 100.-</li>
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
	}

	$html = ob_get_clean();
	return $html;
}
?>