<?php
function trd_sc_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
	$conexion = $link;
	
	$fontSize = 'font-size: 80%;';
	$fontsizeh2 = 'font-size: 80%';
	$width_ct = 'width: 700px;';
	$width_ct2 = 'width: 695px;';
	$marginUl = 'margin: 0 0 0 20px; padding: 0;';
	$marginUl2 = 'margin: 0 0 0 0; padding: 0;';
		
	//$imagen = getimagesize($url.'images/'.$row['logo_cia']);
	$dir_cia = $url.'images/'.$row['logo_cia'];
	$dir_logo = $url.'images/'.$row['logo_ef'];   
	//$ancho = $imagen[0];            
    //$alto = $imagen[1];
	
	ob_start();
?>
<div id="container-main" style="<?=$width_ct;?> height: auto; padding: 5px;">
   <div id="container-cert" style="<?=$width_ct2;?> font-weight: normal; font-size: 80%; font-family: Arial, Helvetica, sans-serif; color: #000000;">
   
     <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; <?=$fontSize;?>">
         <tr>
           <td style="width:34%;">
              <img src="<?=$dir_logo;?>" height="75"/>
           </td>
           <td style="width:32%;"></td>
           <td style="width:34%; text-align:right;">
              <img src="<?=$dir_cia;?>" height="75"/>
           </td>
         </tr>
         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
           <td style="width:34%;">SLIP DE COTIZACIÓN<br/>Cotizacion No <?=$row['no_cotizacion'];?></td>
           <td style="width:32%;"></td> 
           <td style="width:34%; text-align:right;">Cotización válida hasta el: 05-12-2013</td>
         </tr>
     </table><br/>
     <div style="width: auto;	height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; <?=$fontsizeh2;?>">Datos del Titular</div>
	 <?php
      if($row['tipo_cliente']=='Natural'){ 
	 ?>
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; <?=$fontSize;?>">
             <tr style="font-weight:bold;">
               <td style="width:25%; text-align:center; font-weight:bold;">Apellido Paterno</td>
               <td style="width:25%; text-align:center; font-weight:bold;">Apellido Materno</td>
               <td style="width:25%; text-align:center; font-weight:bold;">Nombres</td>
               <td style="width:25%; text-align:center; font-weight:bold;">Apellido de Casada</td>
             </tr>
             <tr>
               <td style="width:25%; text-align:center;"><?=$row['paterno'];?></td>
               <td style="width:25%; text-align:center;"><?=$row['materno'];?></td>
               <td style="width:25%; text-align:center;"><?=$row['nombre'];?></td>
               <td style="width:25%; text-align:center;"><?=$row['ap_casada'];?></td>
             </tr>
              <tr style="font-weight:bold;">
               <td style="width:25%; text-align:center; font-weight:bold;">Calle o Avenida</td>
               <td style="width:25%; text-align:center; font-weight:bold;">Dirección</td>
               <td style="width:25%; text-align:center; font-weight:bold;">Numero</td>
               <td style="width:25%; text-align:center; font-weight:bold;">Ciudad o localidad</td>
             </tr>
             <tr>
               <td style="width:25%; text-align:center;"><?=$row['avenida'];?></td>
               <td style="width:25%; text-align:center;"><?=$row['direccion'];?></td>
               <td style="width:25%; text-align:center;"><?=$row['no_domicilio'];?></td>
               <td style="width:25%; text-align:center;"><?=$row['localidad'];?></td>
             </tr>
             <tr style="font-weight:bold;">
               <td style="width:25%; text-align:center; font-weight:bold;">Telefono Domicilio</td>
               <td style="width:25%; text-align:center; font-weight:bold;">Telefono Oficina</td>
               <td style="width:50%; text-align:center; font-weight:bold;" colspan="2">Telefono Celular</td>
             </tr>
             <tr>
               <td style="width:25%; text-align:center;"><?=$row['telefono_domicilio'];?></td>
               <td style="width:25%; text-align:center;"><?=$row['telefono_oficina'];?></td>
               <td style="width:50%; text-align:center;" colspan="2"><?=$row['telefono_celular'];?></td>
             </tr>
             <tr style="font-weight:bold;">
               <td style="width:25%; text-align:center; font-weight:bold;">Direccion laboral</td>
               <td style="width:25%; text-align:center; font-weight:bold;">Descripción ocupación</td>
               <td style="width:50%; text-align:center; font-weight:bold;" colspan="2">Ocupación</td>
             </tr>
             <tr>
               <td style="width:25%; text-align:center;"><?=$row['direccion_laboral'];?></td>
               <td style="width:25%; text-align:center;"><?=$row['desc_ocupacion'];?></td>
               <td style="width:50%; text-align:center;" colspan="2"><?=$row['ocupacion'];?></td>
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
               <td style="width:50%; text-align:center; font-weight:bold;">Direccion Laboral</td>
               
             </tr>
             <tr>
               <td style="width:50%; text-align:center;"><?=$row['telefono_oficina'];?></td>
               <td style="width:50%; text-align:center;"><?=$row['direccion_laboral'];?></td>
               
             </tr>
          </table><br/>		
     <?php
	  }
	 ?>
     
     <div style="width: auto; height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; <?=$fontsizeh2;?>">Interés Asegurado - Ubicación del Riesgo</div>
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; <?=$fontSize;?>">
            <?php
              while($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)){	  
			?>
                <tr>
                 <td style="width:25%; text-align:center;"><b>Tipo Inmueble</b></td>
                 <td style="width:25%; text-align:center;"><b>Uso Inmueble</b></td>
                 <td style="width:25%; text-align:center;"><b>Estado Inmueble</b></td>
                 <td style="width:25%; text-align:center;"><b>Valor Asegurado (USD)</b></td>
                </tr>
                <tr>
                 <td style="width:25%; text-align:center;"><?=$rowDt['tipo_inmueble'];?></td>
                 <td style="width:25%; text-align:center;"><?=$rowDt['uso_inmueble'];?></td>
                 <td style="width:25%; text-align:center;"><?=$rowDt['estado_inmueble'];?></td>
                 <td style="width:25%; text-align:center;"><?=number_format($rowDt['valor_asegurado'],2,".",",");?></td>
                </tr>
                <tr>
                 <td style="width:25%; text-align:center;"><b>Departamento</b></td>
                 <td style="width:25%; text-align:center;"><b>Zona</b></td>
                 <td style="width:25%; text-align:center;"><b>Ciudad o Localidad</b></td>
                 <td style="width:25%; text-align:center;"><b>Dirección</b></td> 
                </tr>
                <tr> 
                 <td style="width:25%; text-align:center;"><?=$rowDt['departamento'];?></td>
                 <td style="width:25%; text-align:center;"><?=$rowDt['zona'];?></td>
                 <td style="width:25%; text-align:center;"><?=$rowDt['localidad'];?></td>
                 <td style="width:25%; text-align:center;"><?=$rowDt['direccion'];?></td>
                </tr> 
            <?php
			  }
			?>
        </table><br/>
     
     <div style="width: auto; height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; <?=$fontsizeh2;?>">Datos de Solicitud</div>
     <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; <?=$fontSize;?>">
         <tr>
           <td style="width:50%; text-align:right;"><b>Compañía de Seguros:</b></td>
           <td style="width:50%;"><?=$row['compania'];?></td>
         </tr>
         <tr>
           <td style="width:50%; text-align:right;"><b>Seguro a contratar:</b></td>
           <td style="width:50%;">Todo Riesgo Domiciliario</td>
         </tr>
         <tr>
           <td style="width:50%; text-align:right;"><b>Periodo de contratacion:</b></td>
           <td style="width:50%;"><?=$row['tip_plazo_text'];?></td>
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
                  
       <div style="width: 100%; height: auto; margin: 7px 0; <?=$fontSize;?> text-align:justify;">
            
            <b>Tasas:</b><br/>

            <ol style="<?=$marginUl;?> list-style-type:upper-alpha;">
                <li>0.8%o (por mil) anual para inmuebles terminados.</li>
            </ol>
            
            <b>FORMA DE PAGO:</b><br/><br/>
            De acuerdo a la periodicidad de pago establecida en el contrato de cr&eacute;dito, la fecha de vencimiento de la prima de seguro ser&aacute; la misma fecha de vencimiento del pago de cuotas del cr&eacute;dito<br/><br/>
          
            <b>CONDICION DE ADHESION AL SEGURO:</b><br/><br/>
El Asegurado se adhiere voluntariamente a los t&eacute;rminos establecidos en la presente P&oacute;liza de Seguro Todo Riesgo de Da&ntilde;os a la Propiedad y declara conocer y estar de acuerdo con las condiciones del contrato de seguro. Asimismo, acepta la obligaci&oacute;n de pago de prima para mantener vigente la cobertura de la p&oacute;liza. La falta de pago de primas dar&aacute; lugar a la suspensi&oacute;n inmediata de la cobertura.<br/><br/>

            <b>COBERTURAS:</b><br/><br/>
Todo Riesgo de Da&ntilde;os a la Propiedad incluyendo cl&aacute;usula de terremoto, temblor, erupciones volc&aacute;nicas y movimientos s&iacute;smicos al igual que el incendio resultante de estos; incluyendo da&ntilde;os para cubrir p&eacute;rdidas y da&ntilde;os directos ocasionados por derrumbe, deslizamiento y asentamiento, cl&aacute;usula para cubrir p&eacute;rdidas y da&ntilde;os por corrimiento de tierra, cl&aacute;usula para cubrir p&eacute;rdidas y/o da&ntilde;os directos ocasionados por ca&iacute;da de rocas, seguro para motines y/o huelgas y/o conmoci&oacute;n civil y/o vandalismo, sabotaje y terrorismo LPO437, hasta el valor asegurado en la p&oacute;liza, que es igual al saldo deudor del cliente al momento del siniestro. Para valores asegurados superiores a $us. 4.000.000,00 se deber&aacute; consultar a la compa&ntilde;&iacute;a.<br/>
		Robo con violencia al contenido hasta el 10% del valor comercial del inmueble, solo para la alternativa (a) del Valor Asegurado..<br/><br/>
		Cl&aacute;usula de robo con violencia a primer riesgo al contenido hasta el 10% del valor comercial del inmueble.<br/><br/>
        <b>DEDUCIBLES:</b><br/>
        <ol style="<?=$marginUl;?> list-style-type:upper-alpha;">			
         <li>Terrorismo y Riesgos Pol&iacute;ticos: 1% del valor del reclamo, m&iacute;nimo de $us. 100,00</li>
         <li>Dem&aacute;s riesgos: $us. 50,00</li>
        </ol><br/>
        
        <b>CLAUSULAS APLICABLES:</b><br/>
        <ol style="<?=$marginUl;?> list-style-type:upper-alpha;">		
        <li>Anexo para cubrir colapso de techos y paredes</li>
          <li>Cl&aacute;usula de Gastos extraordinarios, hasta el 20% del Valor Asegurado</li>
          <li>Cl&aacute;usula de Reemplazo</li>
          <li>Cl&aacute;usula de anticipo del 50% del Siniestro </li>
          <li>Cl&aacute;usula de Elegibilidad de Ajustadores</li>
          <li>Cl&aacute;usula de Honorarios de Arquitectos, Ingenieros y Top&oacute;grafos, hasta el 20% del Valor Asegurado</li>
          <li>Cl&aacute;usula de Rehabilitaci&oacute;n Autom&aacute;tica de la Suma Asegurada, aclarando que se encuentra sujeta al pago de la extra prima correspondiente</li>
          <li>Cl&aacute;usula de Remoci&oacute;n de Escombros </li>
          <li>Anexo de Flete A&eacute;reo</li>
          <li>Cl&aacute;usula de Rescisi&oacute;n del Contrato a Prorrata </li>
          <li>Anexo de Ampliaci&oacute;n de Aviso de Siniestro a 15 d&iacute;as  </li>
          <li>Da&ntilde;os por incendio y/o rayo directo o indirecto en aparatos electr&oacute;nicos</li>
          <li>Cl&aacute;usula de Terremoto, temblor y erupciones volc&aacute;nicas</li>
          <li>Cl&aacute;usula de Seguro para Motines y/o Huelgas y/o Conmoci&oacute;n Civil y/o Da&ntilde;o Malicioso y/o Vandalismo, Sabotaje y Terrorismo (LPO437)</li>
          <li>Cl&aacute;usula de Robo con violencia a primer riesgo</li>
          <li>Cl&aacute;usula de Derrumbe y/o Deslizamiento</li>
          <li>Cl&aacute;usula de Hundimiento</li>
          <li>Cl&aacute;usula de Gastos de Investigaci&oacute;n y Salvamento</li>
          <li>Cl&aacute;usula de Definici&oacute;n de Evento</li>
          <li>Cl&aacute;usula de Errores y Omisiones</li>
          <li>Cl&aacute;usula de Subrogaci&oacute;n</li>
          <li>Anexo de Renovaci&oacute;n Autom&aacute;tica, hasta finalizar el cr&eacute;dito</li>
        </ol><br/> <!---->
        </div>
        
        <div style="page-break-before: always;">&nbsp;</div>
        
        <div style="width: 700px; height: auto; margin: 7px 0; text-align:justify; <?=$fontSize;?>">
            
            <b>CONDICIONES GENERALES Y EXCLUSIONES:</b><br/><br/>
    De acuerdo al Condicionado General de Seguros y Reaseguros Credinform International S.A., para seguro Todo Riesgo de Da&ntilde;os a la Propiedad.<br/><br/>
    
            <b>PROCEDIMIENTO EN CASO DE P&Eacute;RDIDA:</b><br/><br/>
            
            <ol style="<?=$marginUl;?> list-style-type:lower-alpha;">
                <li>Notificar el siniestro por escrito a la Compa&ntilde;&iacute;a,</li> 
                <li>Tomar todas las acciones dentro de sus medios para minimizar la p&eacute;rdida o da&ntilde;o.</li>
                <li>Denunciar el hecho a la polic&iacute;a en caso de incendio, robo, sospecha
                 de robo o acto malintencionado o malicioso o de car&aacute;cter pol&iacute;tico.</li>
                <li>Conservar las partes afectadas y tenerlas disponibles para la
                    inspecci&oacute;n de los representantes de la Compa&ntilde;&iacute;a.</li>
                <li>Preparar una declaraci&oacute;n firmada acerca del siniestro que contenga
                un detalle, dentro de lo razonablemente posible, acerca de sus
                causas, los &iacute;tems o partes afectadas y su valorizaci&oacute;n.</li>
                <li>Detalles de cualquier otro Seguro que pudiera existir.</li>
                <li>El Asegurado deber&aacute;, a su propia costa, proporcionar a la Compa&ntilde;&iacute;a
                cuando &eacute;sta se lo solicite, todos los detalles, planos, duplicados,
                copias, documentos, pruebas e informaci&oacute;n con relaci&oacute;n al siniestro, 
                as&iacute; como acerca de su origen y de las causas y circunstancias del da&ntilde;o.</li>
                <li>El Asegurado no est&aacute; facultado para abandonar ning&uacute;n bien siniestrado a la Compa&ntilde;&iacute;a, sea que &eacute;sta haya tomado posesi&oacute;n de &eacute;l o no.</li>
                <li>En todo caso de siniestro, la Compa&ntilde;&iacute;a se reserva el derecho de reponer los objetos destruidos o averiados, en lugar de pagar la indemnizaci&oacute;n reclamada si as&iacute; lo creyere conveniente; y debe tener entendido que despu&eacute;s que se indemnice, de uno u otro modo, el importe del siniestro, la suma asegurada sufrir&aacute; la correspondiente reducci&oacute;n.</li>
                <li>Las p&eacute;rdidas ser&aacute;n satisfechas en la moneda en que se haya efectuado el Seguro, lo m&aacute;s tarde, sesenta d&iacute;as despu&eacute;s de liquidado el da&ntilde;o.</li>
                <li>En caso de una p&eacute;rdida parcial, la suma asegurada quedar&aacute; reducida en el mismo importe que la indemnizaci&oacute;n pagada, en este caso, el Asegurado puede mantener completo el Seguro original con el pago de una prima proporcional al monto de indemnizaci&oacute;n recibida por el tiempo que reste por transcurrir el Seguro.</li> 
                </ol><br/>
               
                <b>IMPORTANTE:</b><br/><br/>
               <ol style="<?=$marginUl;?> list-style-type:disc;">
					<li>La responsabilidad indemnizatoria de la Compa&ntilde;&iacute;a est&aacute; limitada como m&aacute;ximo al Valor Total Asegurado o declarado, el cual no puede ser superior a USD. 4.000.000,00 para Todo Riesgo de Da&ntilde;os a la Propiedad y 10% del valor asegurado del inmueble para robo con violencia al contenido, hasta un m&aacute;ximo de $us 10.000 (solo para la alternativa (a) del Valor Asegurado) &oacute; sus equivalentes en Moneda Nacional (Bolivianos)</li>				
					 <li><b>Los siguientes riesgos deben ser consultados individualmente a la compa&ntilde;&iacute;a</b>
						<ul style="<?=$marginUl2;?>">
						<li>Riesgos Industriales y maquinaria, maquinaria para productos en l&iacute;nea</li>
						<li>Textileras</li>
						<li>Fabricas de pl&aacute;stico, plastoformo, polietileno, papel, cart&oacute;n, albog&oacute;n </li>
						<li>Restaurant, discotecas y karaokes</li>
						<li>Ferias, exposici&oacute;n y eventos</li>
						<li>Sustancias inflamables y pinturas</li>
						<li>Industrias qu&iacute;micas  y farmac&eacute;uticas, laboratorios. </li>
						</ul>
					</li>
				</ol><br/><br/>
             <!--
			 <?php   
              if($filaVig[4]=='terminado'){
		     ?>		 
		       <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; <?=$fontSize;?>">
				  <tr>
                    <td colspan="2">
                     <b>Requisitos para la emisi&oacute;n del seguro - Todo Riesgo</b>
                    </td>
                  </tr>
			      <?php
                  if($filaVig[3]=='domiciliario'){
				  ?>	  
				    <tr>
                     <td>
					   Formulario de inventariaci&oacute;n de contenido&nbsp;
				       <a href="archivo/formulario_inventariacion.pdf" target="_blank">Ver Formulario</a>
                     </td>
					 <td>
                       Si corresponde
                     </td>
                    </tr>
		   	      <?php
                  }elseif($filaVig[3]=='comercial'){
				  ?>	  
			        <tr>
                      <td>
					    Formulario de inventariaci&oacute;n de contenido
                      </td>
					  <td>
                        Si corresponde
                      </td>
                    </tr>
			      <?php 
                   }		 
				  ?>
		          <tr>
                    <td>
				     Formulario de actualizacion de datos del cliente&nbsp;
				     <a href="archivo/formulario_ident_cliente.pdf" target="_blank">Ver Formulario</a>
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
				     Aval&uacute;o
                    </td>
				  <td>
                    Obligatorio
                  </td>
                 </tr>
			   </table>
		     <?php 
              }
             ?>
             -->   
        
             <table border="0" cellpadding="0" cellspacing="0" style="width: 700px; <?=$fontSize;?>">
             <tr>
               <td style="text-align:center; width:33%;"><?=$row['nombre'].' '.$row['paterno'].' '.$row['materno']?></td>
               <td style="text-align:center; width:34%;"><?=$row['ci']?></td>
               <td style="text-align:center; width:33%;"><?=date('d-m-Y');?></td>
             </tr> 
             <tr>
               <td style="text-align:center; width:33%;"><b>Firma del Titular Asegurado</b></td>
               <td style="text-align:center; width:34%;"><b>C.I.</b></td>
               <td style="text-align:center; width:33%;"><b>Fecha de Solicitud</b></td>
             </tr>
           </table>
        </div> 
        
       
       <div style="width: 100%; height: auto; margin: 7px 0; text-align:justify;">
       
         
       </div>
       	
   </div>
</div>
<?php
	$html = ob_get_clean();
	return $html;
}
?>