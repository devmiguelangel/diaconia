<?php
function de_sc_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
	$conexion = $link;
	
	$fontSize = 'font-size: 75%;';
	$fontsizeh2 = 'font-size: 80%';
	$width_ct = 'width: 700px;';
	$width_ct2 = 'width: 695px;';
	$marginUl = 'margin: 0 0 0 20px; padding: 0;';
	
	$tipo_cambio = $link->get_rate_exchange(true);
	/*
	$url_img = $url;
	if($type == 'PDF'){
		$marginUl = 'margin: 0 0 0 -20px; padding:0;';
		$fontSize = 'font-size: 75%;';
		$fontsizeh2 = 'font-size: 40%';
		$width_ct = 'width: 785px;';
		$width_ct2 = 'width: 775px;';
		$url_img = '';
	}
	if($type == 'PDF'){
	   $imagen = getimagesize($url_img.'../images/'.$row['logo_cia']); 
	   $dir_cia = $url_img.'../images/'.$row['logo_cia'];
	   $dir_logo = $url_img.'../img/logo-sud.jpg';  
    }else{
	   $imagen = getimagesize($url_img.'images/'.$row['logo_cia']);
	   $dir_cia = $url_img.'images/'.$row['logo_cia'];
	   $dir_logo = $url_img.'img/logo-sud.jpg';   
	}*/
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
               <img src="<?=$url;?>images/<?=$row['logo_ef'];?>"/>
           </td>
           <td style="width:32%;"></td>
           <td style="width:34%; text-align:right;">
               <img src="<?=$url;?>images/<?=$row['logo_cia'];?>" style="width:100%;"/>
           </td>
         </tr>
         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
           <td style="width:34%;">SLIP DE COTIZACIÓN<br/>No. DE-<?=$row['no_cotizacion'];?></td>
           <td style="width:32%;"></td> 
           <td style="width:34%; text-align:right;">
<?php
list($year, $mon, $day) = explode('-',$row['fecha_creacion']);
$row['fecha_creacion'] = date('d/m/Y', mktime(0, 0, 0, $mon, $day + 2, $year));
?>
            Cotización válida hasta el: <?=$row['fecha_creacion'];?>
          </td>
         </tr>
         <tr>
           <td colspan="3" style="width:100%; text-align:center;">
             SLIP DE COTIZACIÓN<br/>
             DECLARACION JURADA DE SALUD<br/>
             SOLICITUD DE SEGURO DE DESGRAVAMEN HIPOTECARIO
           </td>
         </tr>
     </table><br/>
	 
     <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; <?=$fontSize;?>">
         <tr>
           <td style="width:50%; text-align:right;"><b>Tipo Cobertura:</b></td>
           <td style="width:50%;">Individual - Mancomuno</td>
         </tr>
         <tr>
           <td style="width:50%; text-align:right;"><b>Monto Actual Solicitado:</b></td>
           <td style="width:50%;"><?=$row['monto'].' '.$row['moneda'];?></td>
         </tr>
         <tr>
           <td style="width:50%; text-align:right;"><b>Plazo del Presente Crédito:</b></td>
           <td style="width:50%;"><?=$row['plazo'].' '.$row['tipoplazo'];?></td>
         </tr>
         <tr>
           <td style="width:50%; text-align:right;"><b>Producto:</b></td>
           <td style="width:50%;"><?=$row['producto'];?></td>
         </tr>
      </table><br/>
      <?php
            $titulares=array();
			$j=1;
			$num_titulares=$rsDt->num_rows;
			
			while($regiDt=$rsDt->fetch_array(MYSQLI_ASSOC)){
                $jsonData = $regiDt['respuesta'];
                $phpArray = json_decode($jsonData);
		    ?>
                <div style="width: auto;	height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; <?=$fontsizeh2;?>">Datos del titular <?=$j;?></div>                             
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; <?=$fontSize;?>">
                   <tr style="font-weight:bold;">
                     <td style="width:25%; text-align:center; font-weight:bold;">Apellido Paterno</td>
                     <td style="width:25%; text-align:center; font-weight:bold;">Apellido Materno</td>
                     <td style="width:25%; text-align:center; font-weight:bold;">Nombres</td>
                     <td style="width:25%; text-align:center; font-weight:bold;">Apellido de Casada</td>
                   </tr>
                   <tr>
                     <td style="width:25%; text-align:center;"><?=$regiDt['paterno'];?></td>
                     <td style="width:25%; text-align:center;"><?=$regiDt['materno'];?></td>
                     <td style="width:25%; text-align:center;"><?=$regiDt['nombre'];?></td>
                     <td style="width:25%; text-align:center;"><?=$regiDt['ap_casada'];?></td>
                   </tr>
                    <tr style="font-weight:bold;">
                     <td style="width:25%; text-align:center; font-weight:bold;">Lugar de Nacimiento</td>
                     <td style="width:25%; text-align:center; font-weight:bold;">Pais</td>
                     <td style="width:25%; text-align:center; font-weight:bold;">Fecha de Nacimiento</td>
                     <td style="width:25%; text-align:center; font-weight:bold;">Lugar de Residencia</td>
                   </tr>
                   <tr>
                     <td style="width:25%; text-align:center;"><?=$regiDt['lugar_nacimiento'];?></td>
                     <td style="width:25%; text-align:center;"><?=$regiDt['pais'];?></td>
                     <td style="width:25%; text-align:center;"><?=$regiDt['fecha_nacimiento'];?></td>
                     <td style="width:25%; text-align:center;"><?=$regiDt['lugar_residencia'];?></td>
                   </tr>
                   <tr style="font-weight:bold;">
                     <td style="width:25%; text-align:center; font-weight:bold;">Documento de Identidad o Pasaporte</td>
                     <td style="width:25%; text-align:center; font-weight:bold;">Edad</td>
                     <td style="width:25%; text-align:center; font-weight:bold;">Peso (kg)</td>
                     <td style="width:25%; text-align:center; font-weight:bold;">Estatura (cm)</td>
                   </tr>
                   <tr>
                     <td style="width:25%; text-align:center;"><?=$regiDt['ci'];?></td>
                     <td style="width:25%; text-align:center;"><?=$regiDt['edad'];?></td>
                     <td style="width:25%; text-align:center;"><?=$regiDt['peso'];?></td>
                     <td style="width:25%; text-align:center;"><?=$regiDt['estatura'];?></td>
                   </tr>
                   <tr style="font-weight:bold;">
                     <td colspan="2" style="width:50%; text-align:center; font-weight:bold;">Dirección del Domicilio</td>
                     <td style="width:25%; text-align:center; font-weight:bold;">Tel. Domicilio</td>
                     <td style="width:25%; text-align:center; font-weight:bold;">Tel. Oficina</td>
                   </tr>
                   <tr>
                     <td colspan="2" style="width:50%; text-align:center;"><?=$regiDt['direccion'];?></td>
                     <td style="width:25%; text-align:center;"><?=$regiDt['telefono_domicilio'];?></td>
                     <td style="width:25%; text-align:center;"><?=$regiDt['telefono_oficina'];?></td>
                   </tr>
                   <?php
                     if($row['producto']!='BANCA COMUNAL'){
				   ?>
                   <tr style="font-weight:bold;">
                     <td colspan="2" style="width:50%; text-align:center; font-weight:bold;">Ocupación</td>
                     <td colspan="2" style="width:50%; text-align:center; font-weight:bold;">Descripción</td>
                   </tr>
                   <tr>
                     <td colspan="2" style="width:50%; text-align:center;"><?=$regiDt['ocupacion'];?></td>
                     <td colspan="2" style="width:50%; text-align:center;"><?=$regiDt['desc_ocupacion'];?></td>
                   </tr>
                   <?php
			         }else{
				   ?>
                      <tr style="font-weight:bold;">
                        <td style="width:25%; text-align:center; font-weight:bold;">Ocupación</td>
                        <td style="width:25%; text-align:center; font-weight:bold;">Descripción</td>
                        <td style="width:25%; text-align:center; font-weight:bold;">Monto Banca Comunal</td>
                        <td style="width:25%; text-align:center; font-weight:bold;">Participacion %</td>
                      </tr>
                      <tr>
                        <td style="width:25%; text-align:center;"><?=$regiDt['ocupacion'];?></td>
                        <td style="width:25%; text-align:center;"><?=$regiDt['desc_ocupacion'];?></td>
                        <td style="width:25%; text-align:center;"><?=$regiDt['monto_banca_comunal'];?></td>
                        <td style="width:25%; text-align:center;"><?=$regiDt['porcentaje_credito'];?></td>
                      </tr>
                   <?php
					 }
				   ?>
                </table>		
		<?php
		        $titulares[1][$j]=$regiDt['nombre'].' '.$regiDt['paterno'].' '.$regiDt['materno'];
				$titulares[2][$j]=$row['monto'].' '.$row['moneda'];
				$titulares[3][$j]=$row['tasa_final'];
				 echo'<div style="width: auto;	height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; '.$fontsizeh2.'">Cuestionario</div>
						<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; '.$fontSize.'">';
						  $c=0;
						  $error=array();
						  foreach ($phpArray as $key => $value) {
							  $vec=explode('|',$value);
							  $id_pregunta=$vec[0];
							  $respuesta=$vec[1];
							  $select4="select
										  pregunta,
										  respuesta,
										  orden
										from
										  s_pregunta
										where
										  id_pregunta=".$id_pregunta.";"; 			  
							  //$regi4 = mysqli_fetch_array((mysqli_query($conexion,$select4)),MYSQLI_ASSOC);
							  $res4 = $conexion->query($select4, MYSQLI_STORE_RESULT);
							  $regi4 = $res4->fetch_array(MYSQLI_ASSOC); 
							  echo'<tr>
								   <td style="width:5%; text-align:left;">'.$regi4['orden'].'</td>
								   <td style="width:80%; text-align:left;">'.$regi4['pregunta'].'</td>';
							  if($respuesta==$regi4['respuesta']){
									if($respuesta==1){ 
										echo'<td style="width:15%; text-align:right;">si</td>';
									}elseif($respuesta==0){
										echo'<td style="width:15%; text-align:right;">no</td>';
									}	
							  }else{
									if($respuesta==1){ 
										echo'<td style="width:15%; text-align:right;">si</td>';
									}elseif($respuesta==0){
										echo'<td style="width:15%; text-align:right;">no</td>';
									}
									$error[$c]=$regi4['orden'];
									$c++;
							  }
							 echo'</tr>';
						  }
				   echo'</table>
				        <div style="width: auto;	height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; '.$fontsizeh2.'">Detalle</div>
						<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; '.$fontSize.'">
						  <tr><td>';
					         if(!empty($regiDt['observacion'])){
								 if($link->getTypeIssueIdeproDE ($row['monto'], $row['moneda'], (float)$tipo_cambio) !== 'FC'){
										 echo'<div style="text-align:justify; border:1px solid #C68A8A; background:#FFEBEA; padding:8px;">
										 No cumple con la(s) pregunta(s) ';
										 foreach($error as $valor){
											  echo $valor.',&nbsp;';      
										 }
										 unset($error);
										echo'&nbsp;del cuestionario<br/><br/>
										<b>Nota:</b>&nbsp;Al no cumplir con una o mas preguntas del cuestionario del presente slip, 
					  la compa&ntilde;&iacute;a de seguros solicitar&aacute; ex&aacute;menes m&eacute;dicos para la autorizaci&oacute;n de aprobaci&oacute;n del seguro o en su defecto podr&aacute; declinar la misma.
										</div>'; 
								 }
							 }else{
								 if($link->getTypeIssueIdeproDE ($row['monto'], $row['moneda'], (float)$tipo_cambio) !== 'FC'){
									 echo'<div style="text-align:justify; border:1px solid #3B6E22; background:#6AA74F; padding:8px; color:#ffffff;">
											 Cumple con las preguntas del cuestionario 
										  </div>';
								 }
							 }	   
					 echo'</td></tr>
				        </table>';
						echo'<div style="width: auto; height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; '.$fontsizeh2.'">Indice de Masa Corporal</div>';
							 $res=($regiDt['peso']+100)-$regiDt['estatura'];
							 if( (($res>=0) and ($res<=15))  or (($res<0) and ($res>=-15)) ){
								$dato=1;
							 }elseif($res<-15){
								$dato=2;
							 }elseif($res>15){
								$dato=3;
							 }
					   echo'<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; '.$fontSize.'">
							  <tr>
								<td style="width:30%; text-align:left;">'.imc($dato).'</td>
								<td style="width:70%; text-align:right;">
								  <table border="0" cellspacing="0" cellpadding="0" style="width:30%; font-size:9px;">
									 <tr><td colspan="3" style="color:#ffffff; background:#0075AA; font-weight:bold; text-align:center; width:100%;">Datos</td></tr>
									 <tr><td><b>Estatura</b></td><td style="text-align:right">'.$regiDt['estatura'].'</td><td style="text-align:right"><b>cm</b></td></tr>
									 <tr><td><b>Peso</b></td><td style="text-align:right">'.$regiDt['peso'].'</td><td style="text-align:right"><b>kg</b></td></tr>
								  </table>
								</td> 
							  </tr>';
							  
							 echo'<tr><td style="width:100%;" colspan="2">&nbsp;</td></tr>
								  <tr><td colspan="2" style="width:100%;">';
									 if($dato==1){
										if($link->getTypeIssueIdeproDE ($row['monto'], $row['moneda'], (float)$tipo_cambio) !== 'FC'){
											 echo'<div style="text-align:justify; border:1px solid #3B6E22; background:#6AA74F; padding:8px; color:#ffffff;">
												 Cumple con la estatura y peso adecuado. 
											  </div>';
										}
									 }else{
										if($link->getTypeIssueIdeproDE ($row['monto'], $row['moneda'], (float)$tipo_cambio) !== 'FC'){ 
										   echo'<div style="text-align:justify; border:1px solid #C68A8A; background:#FFEBEA; padding:8px;">
												 <b>Nota:</b>&nbsp;Al no cumplir con el peso y la estatura adecuados, 
		  la compa&ntilde;&iacute;a de seguros solicitar&aacute; ex&aacute;menes m&eacute;dicos para la autorizaci&oacute;n de aprobaci&oacute;n del seguro o en su defecto podr&aacute; declinar la misma.
												</div>';
										}
									 }
								  echo'</td></tr>'; 
					   echo'</table>';
				  $j++;				 		
			}
		?>
       <div style="font-size:80%; width: 100%; height: auto; margin: 7px 0;">
            Declaro(amos) que las respuestas que he(mos) consignado en esta solicitud son verdaderas y completas y que es 
            de mi (nuestro) conocimiento que cualquier declaraci&oacute;n inexacta, omisi&oacute;n u ocultaci&oacute;n 
            har&aacute; perder todos los beneficios del seguro de acuerdo con el art&iacute;culo 1138 del C&oacute;digo 
            de Comercio.<br/><br/>
             
            Asimismo autorizo(amos) a los m&eacute;dicos, cl&iacute;nicas, hospitales y otros centros de salud que me (nos)
            hayan atendido o que me (nos) atiendan en el futuro, para que proporcionen a <?=$row['compania'];?>, todos los 
            resultados de los informes referentes a mi (nuestra) salud, en caso de enfermedad o accidente, para lo cual 
            releva a dichos m&eacute;dicos y centros m&eacute;dicos en relaci&oacute;n con su secreto profesional, de toda
            responsabilidad en que pudiera incurrir al proporcionar tales informes. Asimismo, autorizo(amos) 
            a <?=$row['compania'];?> a proporcionar &eacute;stos resultados a <?=$row['ef_nombre']?><br/><br/>
                                    
            <b>CONTRATANTE:</b> <?=$row['ef_nombre']?><br/>
		    <b>BENEFICIARIO A TITULO ONEROSO:</b> Crédito con Educación Rural CRECER<br/>
		
		    <b>CREDISEGURO S.A. SEGUROS PERSONALES</b> domiciliada en Av. José Ballivian No. 1059 (Calacoto), tercer piso,
            zona Sur de la ciudad de La Paz, teléfono 2175000, fax 2775716 (LA COMPAÑÍA), certifica que la persona 
            prestataria del TOMADOR nominado en el contrato de Crédito del que forma parte este documento y que cumpla con
            los límites de edad y requisitos de asegurabilidad de la póliza se encuentra asegurada bajo la presente Póliza 
            (ASEGURADO), contratada por Crédito con Educación Rural CRECER (EL TOMADOR).<br/>
            La cobertura se inicia con el desembolso del Crédito, siempre que se haya cumplido con los requisitos de 
            asegurabilidad y cuenten con la autorización de LA COMPAÑÍA.<br/>

            <b>MANCOMUNOS</b> En caso de Créditos mancomunados, cada uno de los deudores es responsable por el 100% de la
            deuda. En caso de fallecimiento de uno de los mancómunos responsable mancomunadamente por el Crédito, LA 
            COMPAÑÍA indemnizará el 100% del capital asegurado al Beneficiario a la primera muerte, siempre y cuando ambos 
            mancómunos sean aceptados por LA COMPAÑÍA, firmen el contrato de Crédito, sean declarados en los listados 
            mensuales, y se pague la prima correspondiente.<br/><br/>
            
            <table border="0" cellpadding="0" cellspacing="0" style="width:80%; font-size:9px;">
              <tr>
                <td style="width:60%; text-align:center; background:#000; color:#FFF;">COBERTURA PRINCIPAL</td>
                <td style="width:20%; text-align:center; background:#000; color:#FFF;">Capital Asegurado</td>
              </tr>
              <tr>  
                <td style="width:60%;">Muerte (Natural o Accidental)</td>
                <td style="width:20%; text-align:center;">Saldo Deudor</td>
              </tr>
              <tr>   
                <td style="width:60%; text-align:center; background:#000; color:#FFF;">COBERTURAS ADICIONALES</td>
                <td style="width:20%; text-align:center; background:#000; color:#FFF;">Capital Asegurado</td>
              </tr>
              <tr>   
                <td style="width:60%; text-align:justify;">Pago anticipado por Invalidez Total y Permanente: A los efectos de 
                la presente cobertura se considera Invalidez Total y Permanente el hecho de que el ASEGURADO, antes de 
                llegar a los 65 años de edad, quede incapacitado en por lo menos un 65%, a causa de un estado crónico, 
                debido a enfermedad, o a lesión o a la pérdida de miembros o funciones, que impida ejecutar cualquier
                trabajo y siempre que el carácter de tal incapacidad sea reconocido.
                </td><td style="width:20%; text-align:center;" valign="top">Saldo Deudor</td>
              </tr>
            </table><br/>
                      
            <b>PROCEDIMIENTO EN CASO DE SINIESTRO:</b><br/>
            El Beneficiario a título oneroso, tan pronto y a más tardar dentro de los noventa (90) días calendario de tener
            conocimiento del fallecimiento de alguno de los ASEGURADOS, deberá comunicar tal hecho a LA COMPAÑÍA, salvo 
            fuerza mayor o impedimento justificado de acuerdo al artículo 1028 del Código de Comercio adjuntando pruebas
            del siniestro correspondiente. En caso de muerte presunta, ésta deberá acreditarse de acuerdo a ley.<br/>
            Una vez recibidos los documentos probatorios del fallecimiento del ASEGURADO, LA COMPAÑÍA en caso de 
            conformidad, pagará el Capital Asegurado correspondiente al Beneficiario.<br/>
            El asegurado o beneficiario, según el caso, tienen la obligación de facilitar, a requerimiento de LA COMPAÑÍA
            todas las informaciones que tengan sobre los hechos y circunstancias del siniestro, a suministrar las 
            evidencias conducentes a la determinación de la causa, identidad de las personas o intereses asegurados y 
            cuantía de los daños, así como permitir las indagaciones pertinentes necesarias a tal objeto de acuerdo a lo 
            establecido en el artículo 1031 del Código de Comercio.<br/>
            LA COMPAÑÍA podrá solicitar o recabar informes o pruebas complementarias. LA COMPAÑÍA debe pronunciarse sobre
            el derecho de EL TOMADOR dentro de los treinta (30) días de recibidos todos los informes, evidencias, 
            documentos y/o requerimientos adicionales acerca de los hechos y circunstancias del siniestro. Esta solicitud 
            no podrá excederse por mas de dos veces a partir de la primera solicitud de informes y evidencias debiendo LA
            COMPAÑÍA pronunciarse dentro del plazo establecido y de manera definitiva sobre el derecho del ASEGURADO 
            después de la entrega por parte del ASEGURADO del último requerimiento de información en base a lo establecido             en la Ley 365 de fecha 23 de abril de 2013, Disposiciones Adicionales Segunda, Párrafo II. LA COMPAÑÍA
            procederá al pago del beneficio en el plazo máximo de 15 días posteriores al aviso del siniestro o tan pronto 
            sean llenados los requerimientos señalados.<br/>
            La obligación de pagar el Capital Asegurado deberá ser cumplida por LA COMPAÑÍA en un solo acto, por su valor
            total y en dinero.<br/>
            El beneficiario deberá presentar a LA COMPAÑÍA la siguiente documentación además del Formulario de Aviso de 
            Siniestro debidamente llenado y Certificado de Cobertura:<br/>
            <b>Para Muerte por cualquier causa:</b><br/>
            <ol style="<?=$marginUl;?> list-style-type:upper-alpha;">
                <li>Fotocopia del Certificado de Nacimiento o Fotocopia del Carnet de Identidad del ASEGURADO.</li>
                <li>Certificado de Defunción Original</li>
                <li>Certificado Médico de Defunción Original o copia legalizada.</li>
                <li>Para el caso de fallecimiento accidental, informe de la autoridad competente que atendió el mismo.</li>
                <li>Liquidación de cartera con el monto indemnizable</li>
                <li>Fotocopia del contrato de Crédito y respaldos de desembolso.</li>
                <li>Extracto del préstamo por tipo de Crédito.</li>
            </ol><br/>
            LA COMPAÑÍA se reserva el derecho de exigir a las autoridades competentes y a su costa, la autopsia o la
            exhumación del cadáver para establecer las causas de la muerte. El beneficiario y/o sucesores deben prestar su 
            colaboración y concurso para la obtención de las correspondientes autorizaciones oficiales. Si el beneficiario
            y/o los sucesores se negaran a permitir dicha autopsia o exhumación, o la retardaran en la forma que ella sea
            útil para el fin perseguido, el beneficiario perderá el derecho a la indemnización del Capital Asegurado por 
            esta Póliza.<br/>
            <b>Para Invalidez Total y Permanente</b><br/>
            Los incisos a), d), e), f), g) para Muerte y el Certificado INSO (Instituto Nacional de Salud Ocupacional) o 
            en su defecto de otra institución o médico que esté debidamente autorizado por la Autoridad Competente la 
            cual determine el grado de invalidez.<br/>
            <b>PRIMA Y FORMA DE PAGO:</b><br/>
            <b>Prima:</b> De acuerdo a las tasas establecidas para cada ASEGURADO, EL TOMADOR recaudará las Primas
            individuales de los ASEGURADOS a partir del día 01/01/2015.<br/>
            EL TOMADOR paga a LA COMPAÑÍA la prima colectiva de toda la cartera sujeta a cobertura en la periodicidad
            establecida en las Condiciones Particulares de la póliza.<br/>
            Para todos los créditos desembolsados por EL TOMADOR antes de la fecha de vigencia de este documento, se
            respetarán los términos y condiciones de seguro respecto a la afiliación y pago de siniestros pactados entre EL
            TOMADOR y la aseguradora anterior (Stock).<br/>
            Nota.- El ASEGURADO contará con cobertura, mientras sus cuotas mensuales se encuentren pagadas.      
      </div>
       
       <div style="font-size:80%; width: 100%; height: auto; margin: 7px 0;">
           <?php
			   /*
			   echo'<div style="width: auto; height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; '.$fontsizeh2.'">Tasa Mensual</div>';
			   
			   echo'<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; '.$fontSize.'">
					  <tr style="text-align:center;">
						<td style="width:30%;"><b>NOMBRE</b></td>
						<td style="width:30%;"><b>VALOR ASEGURADO</b></td>
						<td style="width:40%;"><b>TASA FINAL</b></td>
					  </tr>';
					  if($num_titulares==1){
						  echo'<tr style="text-align:center;">
								 <td style="width:30%;">'.$titulares[1][1].'</td>
								 <td style="width:30%;">'.$titulares[2][1].'</td>
								 <td style="width:20%;">'.$titulares[3][1].'</td>
							  </tr>';
					  }else{
						 echo'<tr style="text-align:center;">
								 <td style="width:30%;">'.$titulares[1][1].'</td><td style="width:30%;">'.$titulares[2][1].'</td><td rowspan="2" style="width:20%;">'.$titulares[3][1].'</td>
							  </tr>
							  <tr style="text-align:center;">
								 <td style="width:30%;">'.$titulares[1][2].'</td><td style="width:30%;">'.$titulares[2][2].'</td>
							  </tr>'; 
					  }
			  echo'</table><br/><br/><br/><br/><br/>';
			  */
		   ?>
            
            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; <?=$fontSize;?>">
			  <?php
                if($num_titulares==2){
                   echo'<tr>
                     <td style="width:20%; text-align:center;">'.$titulares[1][1].'</td>
                     <td style="width:20%; text-align:center;">'.$titulares[1][2].'</td>
                     <td style="width:20%; text-align:center;">'.date('d-m-Y').'</td>
                   </tr>
                   <tr>
                     <td style="width:20%; text-align:center;"><b>Titular 1</b></td>
                     <td style="width:20%; text-align:center;"><b>Titular 2</b></td>
                     <td style="width:20%; text-align:center;"><b>Fecha Actual</b></td>
                   </tr>';
                }elseif($num_titulares==1){
                     echo'<tr>
                     <td style="width:30%; text-align:center;">'.$titulares[1][1].'</td>
                     
                     <td style="width:30%; text-align:center;">'.date('d-m-Y').'</td>
                   </tr>
                   <tr>
                     <td style="width:30%; text-align:center;"><b>Titular 1</b></td>
                     
                     <td style="width:30%; text-align:center;"><b>Fecha Actual</b></td>
                   </tr>';
                }
              ?>
            </table>
       </div>
	
   </div>
</div>
<?php
	$html = ob_get_clean();
	return $html;
}

function imc($dato){

		switch ($dato) :
			case 1: return 'Peso Normal';
			case 2: return 'Desnutricion';
			case 3: return 'Sobrepeso y Obesidad';
		endswitch;
	}
?>