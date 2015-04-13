<?php
function trd_em_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
	$type = $link->typeProperty;
	$use = $link->useProperty;
	$state = $link->stateProperty;
	
	$nType = count($type);
	$nUse = count($use);
	$nState = count($state);
	
	$respType = array();	$respUse = array();	$respState = array();
	
	if ((boolean)$row['cl_tipo'] === true) {
		$row['cl_paterno'] = $row['cl_razon_social'];
	}
	ob_start();
?>
<div id="container-c" style="width: 785px; height: auto; border: 0px solid #0081C2; padding: 5px;">
	<div id="main-c" style="width: 775px; font-weight: normal; font-size: 12px; font-family: Arial, Helvetica, sans-serif; color: #000000;">
<?php
	if ($rsDt->data_seek(0) === true) {
		while ($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)) {
			for ($k = 0; $k < $nType; $k++) {
				$_type = explode('|', $type[$k]);
				if ($_type[0] === $rowDt['pr_tipo']) {
					$respType[$k] = 'X';
				} else {
					$respType[$k] = '&nbsp;';
				}
			}
			
			for ($k = 0; $k < $nUse; $k++) {
				$_use = explode('|', $use[$k]);
				if ($_use[0] === $rowDt['pr_uso']) {
					$respUse[$k] = 'X';
				} else {
					$respUse[$k] = '&nbsp;';
				}
			}
			
			for ($k = 0; $k < $nState; $k++) {
				$_state = explode('|', $state[$k]);
				if ($_state[0] === $rowDt['pr_estado']) {
					$respState[$k] = 'X';
				} else {
					$respState[$k] = '&nbsp;';
				}
			}
?>
		<table style="width:100%;" border="0" cellpadding="0" cellspacing="0" >
        	<tr>
            	<td style="width:30%;" align="left" valign="top">
                	<img src="<?=$url;?>images/<?=$row['ef_logo'];?>" height="80" class="container-logo" align="left" />
                </td>
                <td style="width:40%; height:120px;" valign="bottom">
                	<div style="font-weight: bold; text-align: center; margin: 40px 0 5px 0; font-size:100%;">
                        Solicitud P&oacute;liza de Seguro Todo Riesgo.<br />
                        Da&ntilde;os a la Propiedad y/o Todo Riesgo <br>
                        de Construcci&oacute;n<br>
                    </div>
                </td>
                <td style="width:30%;" align="right" valign="top">
                	<img src="<?=$url;?>images/<?=$row['cia_logo'];?>" height="80" class="container-logo" align="right" />
				</td>
            </tr>
            <tr>
            	<td colspan="3" style="width:100%; text-align:right; font-size:80%;">
                	C&oacute;digo de Registro SPVS. 101-910159-2006 10 200-3001
                </td>
            </tr>
        </table>
		<br /><br><br>
        
        <span style="font-weight:bold;">1. Datos del Titular:</span> <br>

        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; font-size:80%;">
			<tr>
            	<td style="width:10%;">Nombres: </td>
                <td style="width:25%; text-align:center;"><?=$row['cl_paterno'];?></td>
                <td style="width:22%; text-align:center;"><?=$row['cl_materno'];?></td>
                <td style="width:22%; text-align:center;"><?=$row['cl_nombre'];?></td>
                <td style="width:21%; text-align:center;"><?=$row['cl_ap_casada'];?></td>
            </tr>
            <tr>
            	<td style="width:10%;"></td>
                <td style="width:25%; border-top:1px solid #999; text-align:center;">Apellido Paterno</td>
                <td style="width:22%; border-top:1px solid #999; text-align:center;">Apellido Materno</td>
                <td style="width:22%; border-top:1px solid #999; text-align:center;">Nombres</td>
                <td style="width:21%; border-top:1px solid #999; text-align:center;">Apellido de Casada</td>
            </tr>
		</table>
		<br />
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; font-size:80%;">
			<tr>
            	<td style="width:10%;">Direcci&oacute;n: </td>
                <td style="width:32%; text-align:center;"><?=$row['cl_avc'].' - '.$row['cl_direccion'];?></td>
                <td style="width:29%; text-align:center;"><?=$row['cl_no_domicilio'];?></td>
                <td style="width:29%; text-align:center;"><?=$row['cl_localidad'];?></td>
            </tr>
            <tr>
            	<td style="width:10%;"></td>
                <td style="width:32%; border-top:1px solid #999; text-align:center;">Av. o Calle</td>
                <td style="width:29%; border-top:1px solid #999; text-align:center;">N&uacute;mero</td>
                <td style="width:29%; border-top:1px solid #999; text-align:center;">Ciudad o Localidad</td>
            </tr>
		</table>
		<br />
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; font-size:80%;">
			<tr>
            	<td style="width:10%;">Tel&eacute;fono: </td>
                <td style="width:20%; text-align:center;"><?=$row['cl_tel_domicilio'];?></td>
                <td style="width:20%; text-align:center;"><?=$row['cl_tel_oficina'];?></td>
                <td style="width:20%; text-align:center;"><?=$row['cl_tel_celular'];?></td>
                <td style="width:30%; text-align:center;"></td>
            </tr>
            <tr>
            	<td style="width:10%;"></td>
                <td style="width:20%; border-top:1px solid #999; text-align:center;">Domicilio</td>
                <td style="width:20%; border-top:1px solid #999; text-align:center;">Oficina</td>
                <td style="width:20%; border-top:1px solid #999; text-align:center;">Celular</td>
                <td style="width:30%; text-align:center;"></td>
            </tr>
		</table>
		<br /><br>

		<span style="font-weight:bold;">2. Inter&eacute;s Asegurado:</span><br>
		
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; font-size:80%;">
			<tr>
            	<td style="width:20%;"></td>
                <td style="width:10%; text-align:left;">Tipo:</td>
                <td style="width:20%; text-align:left;">
                	<table border="0" cellpadding="0" cellspacing="0" style="width:100%; margin-bottom:5px;">
                    	<tr>
                        	<td style="width:70%;">Casa</td>
                            <td style="width:30%;">
                            	<div style="width:15px; height:15px; border:1px solid #333; text-align:center;">
									<?=$respType[0];?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width:20%; text-align:left;">
                	<table border="0" cellpadding="0" cellspacing="0" style="width:100%; margin-bottom:5px;">
                    	<tr>
                        	<td style="width:70%;">Departemento</td>
                            <td style="width:30%;">
                            	<div style="width:15px; height:15px; border:1px solid #333; text-align:center;">
									<?=$respType[1];?>
                                </div>
							</td>
                        </tr>
                    </table>
                </td>
                <td style="width:30%; text-align:left;">
                	<table border="0" cellpadding="0" cellspacing="0" style="width:100%; margin-bottom:5px;">
                    	<tr>
                        	<td style="width:70%;">Edificio</td>
                            <td style="width:30%;">
                            	<div style="width:15px; height:15px; border:1px solid #333; text-align:center;">
									<?=$respType[2];?>
                                </div>
							</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
            	<td style="width:20%;"></td>
                <td style="width:10%; text-align:left;">Uso: </td>
                <td style="width:20%; text-align:left;">
                	<table border="0" cellpadding="0" cellspacing="0" style="width:100%; margin-bottom:5px;">
                    	<tr>
                        	<td style="width:70%;">Domiciliario</td>
                            <td style="width:30%;">
                            	<div style="width:15px; height:15px; border:1px solid #333; text-align:center;">
									<?=$respUse[0];?>
                                </div>
							</td>
                        </tr>
                    </table>
                </td>
                <td style="width:20%; text-align:left;">
                	<table border="0" cellpadding="0" cellspacing="0" style="width:100%; margin-bottom:5px;">
                    	<tr>
                        	<td style="width:70%;">Comercial</td>
                            <td style="width:30%;">
		                        <div style="width:15px; height:15px; border:1px solid #333; text-align:center;">
									<?=$respUse[1];?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width:30%; text-align:left;"></td>
            </tr>
            <tr>
            	<td style="width:20%;"></td>
                <td style="width:10%; text-align:left;">Estado:</td>
                <td style="width:20%; text-align:left;">
                	<table border="0" cellpadding="0" cellspacing="0" style="width:100%; margin-bottom:5px;">
                    	<tr>
                        	<td style="width:70%;">Terminado</td>
                            <td style="width:30%;">
	                            <div style="width:15px; height:15px; border:1px solid #333; text-align:center;">
									<?=$respState[0];?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width:20%; text-align:left;">
                	<table border="0" cellpadding="0" cellspacing="0" style="width:100%; margin-bottom:5px;">
                    	<tr>
                        	<td style="width:70%;">En construcci&oacute;n</td>
                            <td style="width:30%;">
                            	<div style="width:15px; height:15px; border:1px solid #333; text-align:center;">
									<?=$respState[1];?>
                                </div>
							</td>
                        </tr>
                    </table>
                </td>
                <td style="width:30%; text-align:left;">
                	<table border="0" cellpadding="0" cellspacing="0" style="width:100%; margin-bottom:5px;">
                    	<tr>
                        	<td style="width:70%;">En proceso de remodelaci&oacute;n, ampliaci&oacute;n o refacci&oacute;n</td>
                            <td style="width:30%;">
                            	<div style="width:15px; height:15px; border:1px solid #333; text-align:center;">
									<?=$respState[2];?>
                                </div>
							</td>
                        </tr>
                    </table>
                </td>
            </tr>
		</table>
        
        <p style="font-size:80%; text-align:justify;">
        	Contenido del inmueble (hasta el 10% del valor declarado para el inmueble), aplicable solo para riesgos domiciliarios, y de acuerdo a la declaraci&oacute;n de contenido que debe ser presentada por el asegurado.
        </p>
        <br><br>
        
        <span style="font-weight:bold;">3. Ubicaci&oacute;n del Riesgo:</span><br>

		<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; font-size:80%;">
			<tr>
            	<td style="width:15%; padding:5px 0;">Departamento: </td>
                <td style="width:85%; padding:5px 0; border-bottom:1px solid #999;"><?=$rowDt['pr_departamento'];?></td>
			</tr>
           	<tr>
            	<td style="width:15%; padding:5px 0;">Zona: </td>
                <td style="width:85%; padding:5px 0; border-bottom:1px solid #999;"><?=$rowDt['pr_zona'];?></td>
			</tr>
           	<tr>
            	<td style="width:15%; padding:5px 0;">Direcci&oacute;n: </td>
                <td style="width:85%; padding:5px 0; border-bottom:1px solid #999;"><?=$rowDt['pr_direccion'];?></td>
			</tr>
		</table><br><br>

        <span style="font-weight:bold;">4. Valor Total Asegurado:</span><br>
        
		<p style="font-size:80%; text-align:justify;">
        	<?=number_format($rowDt['pr_valor_asegurado'], 2, '.', ',');?> USD.<br>
        	Valor Comercial del Inmueble seg&uacute; Aval&uacute;o T&eacute;cnico, o Valor del Presupuesto de la obra, en caso de Construcci&oacute;n.<br>
			(En caso de un bien inmueble terminadoque ser&aacute; ampliado o refacionado, se debe incluir el valor del bien terminado y el valor del presupuesto de la refacci&oacute;n).
        </p><br><br>
        
        <span style="font-weight:bold;">5. Tasas </span><br>
		
		<p style="font-size:80%; text-align:justify;">
        	<?=number_format($row['tasa'], 2, '.', ',');?>% por a&ntilde;o para inmuebles terminados.<br>
            <?=number_format($row['tasa'], 2, '.', ',');?>% por a&ntilde;o por el primer a&ntilde;o del cr&eacute;dito para inmuebles en construcci&oacute;n, a partir del segundo a&ntilde;o del cr&eacute;dito la tasa a aplicar es<br>
			<?=number_format($row['tasa'], 2, '.', ',');?>% por a&ntilde;o.
        </p><br><br>
        
        <span style="font-weight:bold;">5. Forma de Pago</span> <br>
        
        <p style="font-size:80%; text-align:justify;">
        	<?=$row['forma_pago'];?>, y de acuerdo a la periodicidad de pago establecida en el contrato de cr&eacute;dito, la fecha de vencimiento de la prima de seguro ser&aacute; la misma fecha de vencimiento del pago de cuotas del cr&eacute;dito.
        </p><br><br><br><br>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; font-size:80%;">
        	<tr>
            	<td style="width:25%; border-top:1px solid #333; text-align:center;">Firma del Titular Asegurado</td>
                <td style="width:12%;"></td>
                <td style="width:25%;"></td>
                <td style="width:13%;"></td>
                <td style="width:25%;"></td>
            </tr>
		</table><br>
       	<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; font-size:80%;">
        	<?php
              if((boolean)$row['emitir']===true){
			?>
                <tr>
                  <td style="width:25%; border-bottom:1px solid #333; text-align:center;">C.I. <?=$row['cl_ci'];?></td>
                  <td style="width:12%; text-align:right;">Expedido en:</td>
                  <td style="width:25%; border-bottom:1px solid #333; text-align:center;"><?=$row['expedido']?></td>
                  <td style="width:13%; text-align:right;">Lugar y fecha:</td>
                  <td style="width:25%; border-bottom:1px solid #333; text-align:center;">
                      <?=$row['u_depto'].', '.date('d/m/Y', strtotime($row['fecha_emision']));?>
                  </td>
                </tr>
            <?php
			  }else{
			?>
               <tr>
            	<td style="width:25%; border-bottom:1px solid #333; text-align:center;">C.I. <?=$row['cl_ci'];?></td>
                <td style="width:12%; text-align:right;">Expedido en:</td>
                <td style="width:25%; border-bottom:1px solid #333; text-align:center;"><?=$row['expedido']?></td>
                <td style="width:13%; text-align:right;">Lugar y fecha:</td>
                <td style="width:25%; border-bottom:1px solid #333; text-align:center;">&nbsp;</td>
               </tr>
            <?php
			  }
			?>
		</table>
        
        <page><div style="page-break-before: always;">&nbsp;</div></page>
<?php
		}
	}
?>
		<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; font-size:80%;">
        	<tr>
            	<td style="width:50%; text-align:justify;">
                	<span style="font-weight:bold;">7. Riesgos Cubiertos:</span><br>
                    <span style="font-weight:bold;">&nbsp;&nbsp;A valor total</span>
                    <ul style="list-style:disc; margin-left:10px; width:95%;">
                    	<li>
                        	Todo Riesgo de Da&ntilde;os a la Propiedad (Valor Comercial del Inmueble 
                            seg&uacute;n Aval&uacute;o, hasta un l&iacute;mite de $us 2.000.000).
                        </li>
                        <li>
                        	Todo Riesgo de Construcci&oacute;n (Valor del presupuesto de la obra, hasta un l&iacute;mite de $us 500.000).
                        </li>
                    </ul><br>
                    
					<span style="font-weight:bold;">&nbsp;&nbsp;A primer riesgo absoluto (para riesgos domiciliarios)</span>
                    <ul style="list-style:disc; margin-left:10px; width:95%;">
                    	<li>
                        	Robo y/o Asalto con violencia al Contenido, hasta el 10% del valor total 
                            asegurado, con un l&iacute;mite m&aacute;ximo de $us. 100.000 (s&oacute;lo 
                            para riesgos domiciliarios, y siempre y cuando el bien asegurado no se 
                            encuentre en construcci&oacute;n, refacci&oacute;n, remodelaci&oacute;n o 
                            ampliaci&oacute;n y sujeto a la presentaci&oacute;n de la declaraci&oacute;n 
                            de contenido valorado).
                        </li>
                    </ul><br><br>
                    
                    <span style="font-weight:bold;">8. Deducibles:</span><br>
                    <ul style="list-style:disc; margin-left:10px; width:95%;">
                    	<li>
                        	Terremoto, 1% sobre el valor asegurado por ubicaci&oacute;n, m&iacute;nimo $us 200.
                        </li>
                        <li>
                        	Huelgas, motines, asonadas, conmosiones civiles, vandalismo y terrorismo, 
                            1% sobre el valor asegurado por ubicaci&oacute;n, m&iacute;nimo $us 200.
                        </li>
                        <li>
                        	Robo y/o Asalto con violencia a primer riesgo, $us 200 por evento y/o
                            reclamo , (para reisgos domiciliarios).
                        </li>
                        <li>
                        	Dem&aacute;s coberturas $us. 50 por evento y/o reclamo si el riesgo es 
                            domiciliario y $us. 100 si el riesgo es comercial.
                        </li>
                        <li>
                        	Todo riesgo de construcci&oacute;n, 1% sobre el valor del reclamo m&iacute;nimo $us 200.
                        </li>
                    </ul><br><br>
                    
                    <span style="font-weight:bold;">9. Condiciones Generales y Exclusiones:</span><br>
                    <ul style="list-style:disc; margin-left:10px; width:95%;">
                    	<li>
                        	De acuerdo al Condicionado General de la Boliviana Ciacruz de Seguros y 
                            Reaseguros S.A., para Todo Riesgo, Daños a la Propiedad y/o Todo Riesgo
                            de Contrucci&oacute;n, COD 101-910159-200-10-200 (adjunto a la presente solicitud).
                        </li>
                    </ul><br><br>
                    
                    <span style="font-weight:bold;">10. Cl&aacute;usulas Adicionales:</span><br>
                    <ul style="list-style:disc; margin-left:10px; width:95%;">
                    	<li>
                        	Terremoto,  temblor y erupciones volc&aacute;nicas.
                        </li>
                        <li>
                        	Riesgos pol&iacute;ticos y Terrorismo HMACC - AMIT.
                        </li>
                        <li>
                        	Todo Riesgo de Construcci&oacute;n.
                        </li>
                        <li>
                        	Robo con violencia a primer riesgo.
                        </li>
                        <li>
                        	Derrumbe y deslizamiento.
                        </li>
                        <li>
                        	Hundimiento.
                        </li>
                        <li>
                        	Gastos de Investigaci&oacute;n y Salvamento.
                        </li>
                        <li>
                        	Gastos extraordinarios hasta el 10% del valor del reclamo.
                        </li>
                        <li>
                        	Flete a&eacute;reo hasta el 5% del valor del reclamo.
                        </li>
                        <li>
                        	Elegibilidad de Ajustadores.
                        </li>
                        <li>
                        	Ampliaci&oacute;n de aviso de siniestro hasta 10 d&iacute;as.
                        </li>
                        <li>
                        	Adelanto del 50% en caso de siniestro.
                        </li>
                        <li>
                        	Rehabilitaci&oacute;n de la suma asegurada.
                        </li>
                        <li>
                        	Definici&oacute;n de Evento.
                        </li>
                        <li>
                        	Accionamiento Accidental de Instalaciones de Rociadores y/o Sprinklers.
                        </li>
                        <li>
                        	Subrogaci&oacute;n de derechos a favor del Banco Nacional de Bolivia S.A.,
                            hasta el valor del saldo capital del cr&eacute;dito, a la fecha de siniestro.
                        </li>
                    </ul>
                </td>
                <td style="width:50%; text-align:justify;">
                	<span style="font-weight:bold;">11. Riesgos Excluidos:</span><br>
                    <ul style="list-style:disc; margin-left:10px; width:95%;">
                    	<li>
                        	Terrenos, fundamentos (cuando se trata de fundamentos solos no asi como
                            parte de un edificio o instalaci&oacute;n construida), carreteras, puentes,
                            t&uacute;neles, embalses, represas, canales, tanques de agua (solo 
                            como objeto individual no cuando forma parte de un edificio o instalaci&oacute;n
                            ), muelles, diques, pozos, oleoductos, tuber&iacute;as (como parte de la
                            red de transmisi&oacute;n de un sistema, no as&iacute; las tuber&iacute;as
                            que forman parte del edificio o instalaci&oacute;n), escolleras, puertos,
                            rompe olas, dársenas, instalaciones, portuarias.
                        </li>
                        <li>
                        	Riesgos Algodoneros, excepto los edificios administrativos.
                        </li>
                        <li>
                        	Riesgos Mineros en General, excepto los edificios administrativos.
                        </li>
                        <li>
                        	Empresas ferroviarias, excepto los edificios administrativos.
                        </li>
                        <li>
                        	Riesgos azucareros, excepto los edificios administrativos.
                        </li>
                        <li>
                        	Empresas de energ&iacute;a el&eacute;ctrica, excepto los edificios administrativos.
                        </li>
                        <li>
                        	Estaciones de radio y/o televisi&oacute;n, antenas y riesgos de telecomunicaciones.
                        </li>
                    	<li>
                        	Empresas petrol&iacute;feras y petroqu&iacute;micas de todo tipo,
                            excepto los edificios administrativos.
                        </li>
                        <li>
                        	Boites, discotecas, bares, karaokes (siempre y cuando no este comprendidos en un hotel).
                        </li>
                        <li>
                        	Dep&oacute;sitos de papel usado, recortes de papel o recortes de cart&oacute;n.
                        </li>
                        <li>
                        	Dep&oacute;sitos de pasto seco, leña, carb&oacute;n, fardos o parvas de forraje.
                        </li>
                        <li>
                        	F&aacute;bricas, dep&oacute;sitos o negocios de articulos de espuma de poliuretano
                            (espuma de goma) incluyendo fabricas o negocios de colchones.
                        </li>
                        <li>
                        	F&aacute;bricas, dep&oacute;sitos o negocios de articulos de pirotecnia, 
                            polvorines, explosivos y/o armamentos.
                        </li>
                        <li>
                        	Antig&uuml;edades e inmuebles declarados como patrimonio hist&oacute;rico,
                            en los que no se pueden realizar modificaciones por normativa de los
                            gobiernos municipales u otras instituciones.
                        </li>
                        <li>
                        	Bancos de sangre, o cualquier actividad relacionada, excepto edificios administrativos.
                        </li>
                        <li>
                        	Todo tipo de riesgo agr&iacute;cola, excepto los edificios administrativos.
                        </li>
                        <li>
                        	Aceiteras con extracci&oacute;n de solventes.
                        </li>
                        <li>
                        	Carpinter&iacute;as, aserraderos.
                        </li>
                        <li>
                        	F&abricas de art&iacute;culos pl&aacute;sticos con utilizaci&oacute;n de solventes.
                        </li>
                        <li>
                        	F&abricas de pinturas, pinturer&iacute;as o dep&oacute;sitos de pintura.
                        </li>
                        <li>
                        	F&abricas de textiles y textiles de todo tipo, excepto los edificios administrativos.
                        </li>
                        <li>
                        	Transporte y manipuleo de gases.
                        </li>
                        <li>
                        	Empacadoras de frutas con c&aacute;maras frigor&iacute;ficas.
                        </li>
                        <li>
                        	C&aacute;maras frigor&iacute;ficas para cualquier actividad.
                        </li>
                        <li>
                        	Supermercados, hipermercados, centros comerciales (solo cuando se traten 
                            de centros comerciales completos).
                        </li>
                        <li>
                        	Dep&oacute;sitos fiscales o de terceros, o de mercader&iacute;a en general
                            o de operaciones de warrants o de transportistas.
                        </li>
                        <li>
                        	Cualquier tipo de estadio deportivo, incluyendo plazas de toros, cines,
                            ferias, teatros, (excepto coliseos o teatros de colegios y/o cualquier
                            tipo de entidad educativa).
                        </li>
                        <li>
                        	Caf&eacute; internets.
                        </li>
                        <li>
                        	Cualquier tipo de riesgo industrial.
                        </li>
                    </ul>
                </td>
            </tr>
        </table><br>

        <div style="width:93%; font-size:80%; text-align:justify; margin:20px; padding:5px 10px; border:1px solid #333; ">
        	<div style="text-align:center;">NOTA IMPORTANTE</div><br>
        	Sr. Cliente del Banco Nacional de Bolivia S.A., una vez su cr&eacute;dito haya sido 
            desembolsado, Usted debe buscar a su oficial de cr&egrave;dito para recoger el 
            Certificado de Cobertura Individual, que avala la cobertura de su inmueble.<br>
			Se acuerda que ni el Banco Nacional de Bolivia S.A. ni La Boliviana Ciacruz de 
            Seguros y Reaseguros S.A. asumir&aacute;n responsabilidad por la guarda y conservaci&oacute;n
            de su Certificado, que es de su exclusiva responsabilidad.<br>
			Sin perjuicio de lo anterior, se deja constancia que el presente formulario, de 
            Solicitud contiene el mismo tenor que el refereido Certificado de Cobertura Individual
            y, en su caso, podr&aacute; servir de referente en caso de extrav&iacute;o del Certificado
            de Cobertura Individual.
        </div><br><br>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; font-size:80%;">
        	<tr>
            	<td style="width:25%; border-bottom:1px solid #333; text-align:center;"></td>
                <td style="width:12%;"></td>
                <td style="width:25%; border-bottom:1px solid #333; text-align:center;"><?=$row['cl_ci'];?></td>
                <td style="width:13%;"></td>
                <td style="width:25%; border-bottom:1px solid #333; text-align:center;">
                	<table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                    	<?php
                          if((boolean)$row['emitir']===true){
						?>
                            <tr>
                                <td style="width:32%; text-align:center;"><?=date('d', strtotime($row['fecha_emision']));?></td>
                                <td style="width:2%;">/</td>
                                <td style="width:32%; text-align:center;"><?=date('m', strtotime($row['fecha_emision']));?></td>
                                <td style="width:2%;">/</td>
                                <td style="width:32%; text-align:center;"><?=date('Y', strtotime($row['fecha_emision']));?></td>
                            </tr>
                        <?php
						  }else{
						?>
                           <tr>
                                <td style="width:32%; text-align:center;">&nbsp;</td>
                                <td style="width:2%;">/</td>
                                <td style="width:32%; text-align:center;">&nbsp;</td>
                                <td style="width:2%;">/</td>
                                <td style="width:32%; text-align:center;">&nbsp;</td>
                            </tr>
                        <?php
						  }
						?>
                    </table>
                </td>
            </tr>
            <tr>
            	<td style="width:25%; text-align:center;">Firma del Titular Asegurado</td>
                <td style="width:12%;"></td>
                <td style="width:25%; text-align:center;">C.I. (incluir extensi&oacute;n)</td>
                <td style="width:13%;"></td>
                <td style="width:25%; text-align:center;">
                	<table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                    	<tr>
                        	<td style="width:32%; text-align:center;">D&iacute;a</td>
                            <td style="width:2%;">&nbsp;</td>
                            <td style="width:32%; text-align:center;">Mes</td>
                            <td style="width:2%;">&nbsp;</td>
                            <td style="width:32%; text-align:center;">A&ntilde;o</td>
                        </tr>
                    </table>
                </td>
            </tr>
		</table>
        
	</div>
</div>
<?php
	if ($implant === TRUE) {
		$url .= 'index.php?ms='.md5('MS_TRD').'&page='.md5('P_app_imp').'&ide='.base64_encode($row['id_emision']).'';
?>
<br>
<div style="width:500px; height:auto; padding:10px 15px; border:1px solid #00FFFF; background:#9FE0FF; color:#303030; font-size:10px; font-weight:bold; text-align:justify;">
	Solicitud de Aprobaci&oacute;n: P&oacute;liza: <?=$row['prefijo'].'-'.$row['no_emision'];?><br><br>
</div>
<div style="width:500px; height:auto; padding:10px 15px; font-size:11px; font-weight:bold; text-align:left;">
	Para procesar la solicitud ingrese al siguiente link con sus credenciales de usuario:<br>
	<a href="<?=$url;?>" target="_blank">Procesar Solicitud de Aprobaci&oacute;n</a>
</div>
<?php
	}
	$html = ob_get_clean();
	return $html;
}
?>