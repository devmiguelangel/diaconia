<?php
function au_cp_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
	$conexion = $link;
	
	$fontSize = 'font-size: 80%;';
	$fontsizeh2 = 'font-size: 80%';
	$width_ct = 'width: 700px;';
	$width_ct2 = 'width: 695px;';
	$marginUl = 'margin: 0 0 0 20px; padding: 0;';
	$redimensionar = 'max-width: 55%; height: auto;';	
	//$imagen = getimagesize($url.'images/'.$row['logo_cia']);
	//$dir_cia = $url.'images/'.$row['logo_cia'];
	//$dir_logo = $url.'images/'.$row['logo_ef'];   
	//$ancho = $imagen[0];            
    //$alto = $imagen[1];
	
	ob_start();
?>
<div id="container-main" style="<?=$width_ct;?> height: auto; padding: 5px;">
   <div id="container-cert" style="<?=$width_ct2;?> font-weight: normal; font-size: 80%; font-family: Arial, Helvetica, sans-serif; color: #000000;">
   
     <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; <?=$fontSize;?>">
         <tr>
           <td style="width:34%;">
              <img src="<?=$url;?>images/<?=$row['logo_ef'];?>" height="60"/>
           </td>
           <td style="width:32%;"></td>
           <td style="width:34%; text-align:right;">
              <img src="<?=$url;?>images/<?=$row['logo_cia'];?>" height="60"/>
           </td>
         </tr>
         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
           <td style="width:34%;">CERTIFICADO PROVISIONAL<br/>Cotizacion No <?=$row['no_cotizacion'];?></td>
           <td style="width:32%;"></td> 
           <td style="width:34%; text-align:right;">&nbsp;</td>
         </tr>
     </table><br/>
     <div style="width: auto;	height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; <?=$fontsizeh2;?>">Datos del Arrenadatario</div>
	 <?php
      if($row['tipo_cliente']=='Natural'){ 
	 ?>
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; <?=$fontSize;?>">
             <tr style="font-weight:bold;">
               <td style="width:33%; text-align:center; font-weight:bold;">Apellido Paterno</td>
               <td style="width:33%; text-align:center; font-weight:bold;">Apellido Materno</td>
               <td style="width:34%; text-align:center; font-weight:bold;">Nombres</td>
             </tr>
             <tr>
               <td style="width:33%; text-align:center;"><?=$row['paterno'];?></td>
               <td style="width:33%; text-align:center;"><?=$row['materno'];?></td>
               <td style="width:34%; text-align:center;"><?=$row['nombre'];?></td>
             </tr>
              <tr style="font-weight:bold;">
               <td style="width:33%; text-align:center; font-weight:bold;">Documento de Identidad</td>
               <td style="width:33%; text-align:center; font-weight:bold;">Genero</td>
               <td style="width:34%; text-align:center; font-weight:bold;">Fecha de Nacimiento</td>
             </tr>
             <tr>
               <td style="width:33%; text-align:center;"><?=$row['ci'];?></td>
               <td style="width:33%; text-align:center;"><?=$row['genero'];?></td>
               <td style="width:34%; text-align:center;"><?=$row['fecha_nacimiento'];?></td>
             </tr>
             <tr style="font-weight:bold;">
               <td style="width:33%; text-align:center; font-weight:bold;">Telefono Domicilio</td>
               <td style="width:33%; text-align:center; font-weight:bold;">Telefono Celular</td>
               <td style="width:34%; text-align:center; font-weight:bold;">&nbsp;</td>
             </tr>
             <tr>
               <td style="width:33%; text-align:center;"><?=$row['telefono_domicilio'];?></td>
               <td style="width:33%; text-align:center;"><?=$row['telefono_celular'];?></td>
               <td style="width:34%; text-align:center;">&nbsp;</td>
             </tr>
          </table><br/>		
     <?php
	  }elseif($row['tipo_cliente']=='Juridico'){
	 ?>
          <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; <?=$fontSize;?>">
             <tr style="font-weight:bold;">
               <td style="width:50%; text-align:center; font-weight:bold;">Nombre o Razón Social</td>
               <td style="width:50%; text-align:center; font-weight:bold;">NIT</td>
               
             </tr>
             <tr>
               <td style="width:50%; text-align:center;"><?=$row['razon_social'];?></td>
               <td style="width:50%; text-align:center;"><?=$row['ci'];?></td>
               
             </tr>
              <tr style="font-weight:bold;">
               <td style="width:50%; text-align:center; font-weight:bold;">Telefono Oficina</td>
               <td style="width:50%; text-align:center; font-weight:bold;">Email</td>
               
             </tr>
             <tr>
               <td style="width:50%; text-align:center;"><?=$row['telefono_domicilio'];?></td>
               <td style="width:50%; text-align:center;"><?=$row['u_email'];?></td>
               
             </tr>
          </table><br/>		
     <?php
	  }
	 ?>
     <div style="width: auto; height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; <?=$fontsizeh2;?>">Datos de Solicitud</div>
     <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; <?=$fontSize;?>">
         <tr>
           <td style="width:50%; text-align:right;"><b>Compañía de Seguros:</b></td>
           <td style="width:50%;"><?=$row['compania'];?></td>
         </tr>
         <tr>
           <td style="width:50%; text-align:right;"><b>Seguro a contratar:</b></td>
           <td style="width:50%;">Automotores</td>
         </tr>
         <tr>
           <td style="width:50%; text-align:right;"><b>Periodo de contratacion:</b></td>
           <td style="width:50%;"><?=$row['tipo_plazo'];?></td>
         </tr>
         <tr>
           <td style="width:50%; text-align:right;"><b>Prima anual:</b></td>
           <td style="width:50%;"><?=number_format($row['prima_total'],2,".",",");?> USD</td>
         </tr>
         <tr>
           <td style="width:50%; text-align:right;"><b>Prima total:</b></td>
           <td style="width:50%;"><?=number_format(($row['prima_total']*$row['cant_plazo']),2,".",",");?> USD</td>
         </tr>
         <tr>
           <td style="width:50%; text-align:right;"><b>Inicio de vigencia:</b></td>
           <td style="width:50%;"><?=$row['ini_vigencia'];?></td>
         </tr>
         <tr>
           <td style="width:50%; text-align:right;"><b>Fin de vigencia:</b></td>
           <td style="width:50%;"><?=$row['fin_vigencia'];?></td>
         </tr>
      </table><br/>
      
      <div style="width: auto; height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; <?=$fontsizeh2;?>">Datos del Vehiculo</div>
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; <?=$fontSize;?>">
            <tr>
            <td style="width:10%; text-align:center;"><b>Tipo de vehiculo</b></td>
            <td style="width:10%; text-align:center;"><b>Marca</b></td>
            <td style="width:10%; text-align:center;"><b>Modelo</b></td>
            <td style="width:10%; text-align:center;"><b>Cero km.</b></td>
            <td style="width:10%; text-align:center;"><b>Año</b></td>
            <td style="width:10%; text-align:center;"><b>Placa</b></td>
            <td style="width:10%; text-align:center;"><b>Categoria</b></td>
            <td style="width:10%; text-align:center;"><b>Valor Asegurado</b></td>
            <td style="width:10%; text-align:center;"><b>Tasa anual</b></td>
            <td style="width:10%; text-align:center;"><b>Prima</b></td>
            </tr>
            <?php
			  $sum_facu=0;
              while($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)){
				  //$plazo_anio = $conexion->get_year_final($row['plz_anio'], $row['tip_plz_code']);
				  //$tasa_anual = $conexion->get_tasa_year_au(base64_encode($row['id_compania']), base64_encode($row['idef']), $rowDt['category'], $plazo_anio, $row['frm_pago_code']);
				  $prima_vehiculo=($rowDt['tasa']*$rowDt['valor_asegurado'])/100;
				  //if($rowDt['facultativo']==1){$sum_facu++;}
			?>
                <tr>
                <td style="width:10%; text-align:center;"><?=$rowDt['vehiculo'];?></td>
                <td style="width:10%; text-align:center;"><?=$rowDt['marca'];?></td>
                <td style="width:10%; text-align:center;"><?=$rowDt['modelo'];?></td>
                <td style="width:10%; text-align:center;"><?=$rowDt['km'];?></td>
                <td style="width:10%; text-align:center;"><?=$rowDt['anio'];?></td>
                <td style="width:10%; text-align:center;"><?=$rowDt['placa'];?></td>
                <td style="width:10%; text-align:center;"><?=$rowDt['categoria'];?></td>
                <td style="width:10%; text-align:center;"><?=number_format($rowDt['valor_asegurado'],2,".",",");?></td>
                <td style="width:10%; text-align:center;"><?=$rowDt['tasa'];?></td>
                <td style="width:10%; text-align:center;"><?=$prima_vehiculo;?></td>
                </tr> 
            <?php
			    $reason.=$rowDt['motivo_facultativo'].' | ';
			  }
			?>
        </table><br/>
        <?php
        //if($sum_facu>0){
		?>	
           <!--<div style="font-size:8pt; text-align:justify; margin-top:5px; margin-bottom:0px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px;">
                  La presente cotizaci&oacute;n referencial contiene uno o mas veh&iacute;culos cuya antig&uuml;edad supera los <?=$row['anio_max'];?> a&ntilde;os o cuyo monto supera el maximo valor permitido <?=number_format($row['monto_facultativo'],2,".",",");?> USD, por lo tanto la aseguradora se reserva el derecho de solicitar inspecci&oacute;n, adicion de medidas de seguridad, solicitud de reaseguro y otros.
           </div><br/>-->
        <?php
		//}
		?>
        <div style="width: auto; height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; <?=$fontsizeh2;?>">Forma de Pago</div>
        <?php
           $arr_share=json_decode($row['cuota'],true);	   		  
		   echo'<table border="0" cellpadding="0" cellspacing="0" style="width: 60%; '.$fontSize.'" align="center">
		        <tr>
				  <td style="width:30%; text-align:center;"><b>Año</b></td>
				  <td style="width:30%; text-align:center;"><b>Fecha de Pago</b></td>
				  <td style="width:30%; text-align:center;"><b>Cuota</b></td>
				</tr>';
		   foreach ($arr_share as $valor) {
			   $vec=explode('|',$valor);
			   echo'<tr>
					  <td style="width:30%; text-align:center;">'.$vec[0].'</td>
					  <td style="width:30%; text-align:center;">'.$vec[1].'</td>
					  <td style="width:30%; text-align:center;">'.number_format($vec[2],2,".",",").'</td>
					</tr>';
		   }
		   echo'</table><br/>';
		?>
      
       <p style="width: 100%; height: auto; margin: 7px 0;">
           <table width="700" style="border-collapse:0px; border-spacing:0px; <?=$fontSize;?>" align="center">
              <tr>
                <td style="width:45%;" valign="top">
                   <ol style="margin-left:10px; padding-top:8px; padding-bottom:8px; list-style-type:disc;">
                        <li>Responsabilidad Civil Hasta $us 30.000.00</li>
						<li>
							P&eacute;rdida Total por Robo al 100% hasta los valores asegurados establecidos para cada &iacute;tem, entendi&eacute;ndose que robo incluye desaparici&oacute;n misteriosa, atraco y otros que signifiquen desaparici&oacute;n del o los veh&iacute;culos asegurados
						</li>
						<li>P&eacute;rdida Total por Accidente al 100%</li>
                        <li>Da&ntilde;os Propios con franquicia de:
							<ul style="margin: 0 0 0 10px; padding: 0; list-style-type:circle;">
								<li>Plaza de circulaci&oacute;n en LPZ, SCZ y CBBA: $us. 50.-</li>
								<li>Resto del pa&iacute;s: $us 25.-</li>
							</ul>
						</li>
                        <li>Huelgas, Conmociones Civiles, Da&ntilde;os Maliciosos, Vandalismo y Sabotaje y Terrorismo con franquicia de $us 50.-</li>
						<li>Robo Parcial de partes y piezas al 80% (incluyendo accesorios declarados)</li>
						<li>Responsabilidad Civil Consecuencial Hasta $us. 3.000.-</li>
						<li>Cobertura por incendio, Rayo y/o Explosi&oacute;n, ca&iacute;da de rayo, sin la aplicaci&oacute;n de franquicia.</li>
                        <li>Accidentes personales: Por personas y de acuerdo a la capacidad del veh&iacute;culo.
							<ul style="margin: 0 0 0 10px; padding: 0; list-style-type:circle;">
								<li>Muerte $us. 5.000.-</li>
								<li>Invalidez total y/o permanente $us. 5.000.-</li>
								<li>Gastos M&eacute;dicos $us. 1.000.-</li>
								<li>Sepelio $us. 3.000.-</li>
							</ul>
						</li>
                        <li>Responsabilidad civil a ocupantes</li>
						<li>Accesorios hasta su valor declarado sin ninguna limitaci&oacute;n</li>
						<li>Da&ntilde;os causados por intento de robo al 80%</li>
                   </ol><br/>
                   <b>CL&Aacute;USULAS ADICIONALES</b>
                   <ol style="margin-left:0px; padding-top:8px; padding-bottom:8px; list-style-type:disc;">
						<li>Extraterritorialidad gratuita por toda la vigencia de la p&oacute;liza, previa comunicaci&oacute;n a la compa&ntilde;&iacute;a  y aplicable a todas las coberturas.
						</li>
						<li>Servicio de Asistencia Jur&iacute;dica incluyendo:
							<ul style="margin: 0 0 0 10px; padding: 0; list-style-type:circle;">
								<li>Asistencia de audiencias de Tr&aacute;nsito</li>
								<li>Preparaci&oacute;n y presentaci&oacute;n de memoriales</li>
								<li>Asistencia a audiencias de Conciliaci&oacute;n</li>
								<li>Gastos y costas judiciales (por acci&oacute;n civil)</li>
								<li>Presentaci&oacute;n de fianzas judiciales (por acci&oacute;n civil)</li>
							</ul>
						</li>
					</ol><br/>
                    <b>ASISTENCIA A LAS PERSONAS</b>
                    <ol style="margin-left:0px; padding-top:8px; padding-bottom:8px; list-style-type:disc;">
						<li>
							Transporte o repatriaci&oacute;n sanitaria en caso de fallecimiento de cualquiera de los ocupantes del veh&iacute;culo en caso de accidente de tr&aacute;nsito
						</li>
						<li>
							Transporte o repatriaci&oacute;n sanitaria de los acompa&ntilde;antes en caso de fallecimiento a consecuencia de accidente de tr&aacute;nsito.
						</li>
					</ol><br/>
                    <b>ASISTENCIA AL VEH&Iacute;CULO     LAS 24 HORAS Y DURANTE TODA LA VIGENCIA DENTRO DE TODO EL TERRITORIO BOLIVIANO EXCEPTO PANDO</b>
                    <ol style="margin-left:0px; padding-top:8px; padding-bottom:8px; list-style-type:disc;">
						<li>Remolque o transporte del veh&iacute;culo en caso de accidente hasta $us. 200.-</li>
						<li>Desplazamiento por la inmovilizaci&oacute;n y/o robo del veh&iacute;culo en caso que los beneficiarios e encuentren a m&aacute;s de 25km. de su domicilio
						</li>
						<li>
							Dep&oacute;sito y custodia del veh&iacute;culo en caso de accidente, aver&iacute;a mec&aacute;nica o robo hasta un l&iacute;mite de $us. 50.-
						</li>
						<li>
							Servicio de conductor profesional en caso de accidente o fallecimiento del asegurado en caso de imposibilidad de conducir.
						</li>
						<li>
							Localizaci&oacute;n y env&iacute;o de piezas de recambio necesarias para la reparaci&oacute;n cuando no fuera posible su obtenci&oacute;n.
						</li>
						<li>Transmisi&oacute;n de mensajes urgente.</li>
						<li>L&iacute;nea de emergencia gratuita 24 hrs. /365 d&iacute;as</li>
					</ol><br/>
                    <b>OTROS BENEFICIOS ADICIONALES:</b>
                    <ol style="margin-left:0px; padding-top:8px; padding-bottom:8px; list-style-type:disc;">
						<li>Gesti&oacute;n de multas o penalidades de Tr&aacute;nsito, una vez al a&ntilde;o Hasta $us. 15.- (A reembolso).</li>
						<li>
							La devoluci&oacute;n del veh&iacute;culo reparado podr&aacute; ser realizado donde el Asegurado lo solicite (dentro del radio urbano).
						</li>
						<li>
							Cobertura de Gastos M&eacute;dicos: Segunda opini&oacute;n m&eacute;dica para lesiones traumatol&oacute;gicas, a consecuencia de un accidente cuyo siniestro sea cubierto.
						</li>
						<li>
							Si al solicitar los servicios de Gr&uacute;a, &eacute;sta tarda m&aacute;s de 1 hora en acudir, la compa&ntilde;&iacute;a indemnizar&aacute; al asegurado la suma de $us. 20.- por los perjuicios ocasionados, s&oacute;lo en el Radio Urbano.
						</li>
					</ol>
                </td>
                <td style="width:10%"></td>
                <td style="width:45%;">
                   <b>CL&Aacute;USULAS ADICIONALES</b>
                    <ol style="margin-left:10px; padding-top:8px; padding-bottom:8px; list-style-type:disc;">
						<li>Ausencia de control para todos los arrendatarios de Bisa Leasing.</li>
						<li>De alcoholemia permitida hasta o igual 0.7 Gr. Por litro de sangre.</li>
						<li>De Rehabilitaci&oacute;n Autom&aacute;tica de la Suma Asegurada</li>
						<li>De prorrata en caso de rescisi&oacute;n del contrato por parte del Asegurado.</li>
						<li>
							De Eliminaci&oacute;n de la Copia Legalizada de Tr&aacute;nsito para la P&oacute;liza excepto en casos de Responsabilidad Civil y P&eacute;rdida Total.
						</li>
						<li>De eliminaci&oacute;n de la presentaci&oacute;n del formulario de Denuncia Policial, en casos menores a $us. 500.-</li>
						<li>De da&ntilde;os a consecuencia de la naturaleza o desastres naturales.</li>
						<li>De Ampliaci&oacute;n de Aviso de Siniestro a 10 D&iacute;as h&aacute;biles.</li>
						<li>De Libre elegibilidad de Ajustadores</li>
						<li>
							De Tr&aacute;nsito en V&iacute;as, sendas y/o terrenos no autorizados y/o no habilitados incluyendo r&iacute;os y  lechos de r&iacute;os, aplicable a todas las coberturas y da&ntilde;os a partes del veh&iacute;culo a consecuencia de da&ntilde;os por agua.
						</li>
						<li>De inclusiones y exclusiones a prorrata.</li>
						<li>
							De Flete A&eacute;reo y/o expreso, y/o Courier (Overnight) sin l&iacute;mite o aplicaci&oacute;n de franquicia o coaseguro para el asegurado.
						</li>
						<li>De libre elegibilidad de talleres.</li>
						<li>De anticipo del 50% en caso de siniestro.</li>
						<li>
							Cobertura para bolsas de aire por da&ntilde;os a consecuencia de accidente de tr&aacute;nsito, robo y/o intento de robo sin ninguna limitaci&oacute;n.
						</li>
						<li>Periodo de gracia de 30 d&iacute;as en el pago de sus primas, sin la p&eacute;rdida de cobertura.</li>
						<li>De errores u omisiones en la descripci&oacute;n de las caracter&iacute;sticas de la Materia Asegurada.</li>
						<li>
							De ampliaci&oacute;n de vigencia a prorrata hasta 120 d&iacute;as sin modificaci&oacute;n de t&eacute;rminos, condiciones, tasas y primas pactadas en el contrato inicial.
						</li>
						<li>De aviso de anulaci&oacute;n de contrato por parte de la Aseguradora 30 d&iacute;as de anticipaci&oacute;n.</li>
						<li>
							Renovaci&oacute;n anual autom&aacute;tica, bajo los mismos t&eacute;rminos y condiciones, siempre y cuando la siniestralidad no exceda el 60 % de la prima neta anual.
						</li>
						<li>
							De cobertura para eventos cuando el conductor del veh&iacute;culo asegurado cuente con licencia de conducir, pero al momento de la ocurrencia del evento no la porte (un evento al a&ntilde;o).
						</li>
						<li>De piezas y partes genuinas.</li>
						<li>
							Auto reemplazo por 10 d&iacute;as a partir del onceavo d&iacute;a de ocurrido el siniestro y a consecuencia de un accidente.
						</li>
						<li>
							De ampliaci&oacute;n del l&iacute;mite de edad mientras el asegurado cuente con licencia de conducir vigente y con autorizaci&oacute;n de autoridad competente.
						</li>
						<li>De gastos de investigaci&oacute;n, salvataje y gastos extraordinarios.</li>
						<li>De da&ntilde;o estructural.</li>
						<li>Cobertura en caso de 1er. accidente con licencia vencida.</li>
						<li>
							De siniestros a consecuencia de P&eacute;rdida Total por accidente y/o robo a veh&iacute;culos cuya antig&uuml;edad no exceda el primer a&ntilde;o o los 10.000 KM de recorrido, se deber&aacute; considerar como valor de indemnizaci&oacute;n, el valor de compra de un veh&iacute;culo cero kil&oacute;metros.
						</li>
						<li>Ampliaci&oacute;n del l&iacute;mite de velocidad permitida.</li>
						<li>
							Se deja sin efecto la presentaci&oacute;n del Test de Alcoholemia para accidentes ocurridos en el &aacute;rea rural  o pueblos alejados de las ciudades principales. En su reemplazo la Aseguradora aceptar&aacute; la presentaci&oacute;n del informe de la autoridad competente de la localidad  en la que haya ocurrido el siniestro o localidades m&aacute;s cercanas.
						</li>
						<li>
							De asistencia al veh&iacute;culo y/o auxilio mec&aacute;nico a nivel nacional  incluyendo servicio de gr&uacute;a  y de ambulancia en caso de accidente.
						</li>
						<li>De pago de permanencia en garajes o dep&oacute;sitos.</li>
						<li>
							Si aplicar&aacute; una depreciaci&oacute;n autom&aacute;tica del 10% por a&ntilde;o al momento de la indemnizaci&oacute;n en caso de p&eacute;rdida total, para las p&oacute;lizas de vigencia mayor a un ano.  Se aclara que la tasa aplicada para el pago de prima por periodos mayores a un a&ntilde;o cuenta con un descuento por la depreciaci&oacute;n anual del 10% que tendr&aacute; el veh&iacute;culo.
						</li>
					</ol><br/>
                    <b>CONDICIONES ESPECIALES</b>
                     <ol style="margin-left:10px; padding-top:8px; padding-bottom:8px; list-style-type:disc;">
						<li>El valor asegurado corresponder&aacute; al valor comercial.</li>
						<li>
							La cobertura de robo total contratada, se extender&aacute; a cubrir los da&ntilde;os y/o perdidas parciales ocurridas como consecuencia del robo total perpetrado, en la eventualidad de haberse logrado el recupero del veh&iacute;culo dentro de los 45 d&iacute;as.
						</li>
						<li>
							El presente seguro se extiende a cubrir todos los da&ntilde;os y/o p&eacute;rdidas que sufran los veh&iacute;culos asegurados como consecuencia de cualquier servicio adicional que preste la compa&ntilde;&iacute;a de seguros (instalaciones, auxilio mec&aacute;nico, gr&uacute;a, rastreo, etc.).
						</li>
						<li>
							Aceptaci&oacute;n del riesgo al que est&aacute;n expuestos los bienes, en funci&oacute;n a las actividades que desarrolla el Contratante.
						</li>
						<li>
							Se deja claramente especificado que lo establecido en las Condiciones Particulares de la p&oacute;liza, prevalece en todo momento y circunstancia las Condiciones Generales, contenido de cl&aacute;usulas.
						</li>
					</ol>
                
                </td>
              </tr>
           </table>
       </p> 
       
       <p style="width: 100%; height: auto; margin: 7px 0; text-align:justify;">
       
          <table border="0" cellpadding="0" cellspacing="0" style="width: 50%; <?=$fontSize;?>">
			  <tr>
                <td colspan="2">
                  <b>Requisitos para la emisi&oacute;n del seguro - Automotores</b>
                </td>
              </tr>
			  <tr>
               <td>
			     Formulario de actualizacion de datos del cliente
               </td>
			   <td>
                 Obligatorio
               </td>
			  </tr>
			  <tr>
                <td>
			     Carta de nombramiento
                </td>
			    <td>
                  Obligatorio
                </td>
              </tr>
			  <tr>
                <td>
			      Fotocopia de CI o NIT o Fundaempresa
                </td>
			    <td>
                  Obligatorio
                </td>
              </tr>
			  <tr>
                <td>
                  Aval&uacute;o (cuando el veh&iacute;culo sea objeto de garant&iacute;a)
                </td>
			    <td>
                  Obligatorio
                </td>
              </tr>
			  <tr>
                <td>
			     Fotocopia de RUAT
                </td>
			    <td>
                 Obligatorio
                </td>
              </tr>
		  </table>
       </p>
       	
   </div>
   
   <?php
	if ($fac === TRUE) {
		$url .= 'index.php?ms='.md5('MS_AU').'&page='.md5('P_fac').'&ide='.base64_encode($row['id_emision']).'';
?>
<br>
<div style="width:500px; height:auto; padding:10px 15px; font-size:11px; font-weight:bold; text-align:left;">
	No. de Slip de Cotizaci&oacute;n: <?=$row['no_cotizacion'];?>
</div><br>
<div style="width:500px; height:auto; padding:10px 15px; border:1px solid #FF2D2D; background:#FF5E5E; color:#FFF; font-size:10px; font-weight:bold; text-align:justify;">
	Observaciones en la solicitud del seguro:<br><br><?=$reason;?>
</div>
<div style="width:500px; height:auto; padding:10px 15px; font-size:11px; font-weight:bold; text-align:left;">
	Para procesar la solicitud ingrese al siguiente link con sus credenciales de usuario:<br>
	<a href="<?=$url;?>" target="_blank">Procesar caso facultativo</a>
</div>

<?php
	}

	if ($implant === TRUE) {
		$url .= 'index.php?ms='.md5('MS_AU').'&page='.md5('P_app_imp').'&ide='.base64_encode($row['id_emision']).'';
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
?>	
</div>
<?php
	$html = ob_get_clean();
	return $html;
}
?>