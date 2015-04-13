<?php
function de_cp_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
	$conexion = $link;
	
	$fontSize = 'font-size: 75%;';
	$fontsizeh2 = 'font-size: 80%';
	$width_ct = 'width: 700px;';
	$width_ct2 = 'width: 695px;';
	$marginUl = 'margin: 0 0 0 20px; padding: 0;';
	
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
               <img src="<?=$url;?>images/<?=$row['logo_entidad'];?>" width="200"/>
           </td>
           <td style="width:32%;"></td>
           <td style="width:34%; text-align:right;">
               <img src="<?=$url;?>images/<?=$row['logo_compania'];?>" width="100"/>
           </td>
         </tr>
         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
           <td style="width:34%;">CERTIFICADO PROVISIONAL<br/>Cotización No. <?=$row['no_cotizacion'];?></td>
           <td style="width:32%;"></td> 
           <td style="width:34%; text-align:right;">Cotización válida hasta el: 05-12-2013</td>
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
           <td style="width:50%;"><?=$row['monto_solicitado'].' '.$row['moneda_text'];?></td>
         </tr>
         <tr>
           <td style="width:50%; text-align:right;"><b>Plazo del Presente Crédito:</b></td>
           <td style="width:50%;"><?=$row['tipo_plazo'];?></td>
         </tr>
         <tr>
           <td style="width:50%; text-align:right;"><b>Producto:</b></td>
           <td style="width:50%;"><?=$row['tipo_credito'];?></td>
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
                <div style="width: auto;	height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; <?=$fontsizeh2;?>">Datos del titular <?=$regiDt['titular_num'];?></div>                             
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
                   <tr style="font-weight:bold;">
                     <td colspan="2" style="width:50%; text-align:center; font-weight:bold;">Ocupación</td>
                     <td colspan="2" style="width:50%; text-align:center; font-weight:bold;">Descripción</td>
                   </tr>
                   <tr>
                     <td colspan="2" style="width:50%; text-align:center;"><?=$regiDt['ocupacion'];?></td>
                     <td colspan="2" style="width:50%; text-align:center;"><?=$regiDt['desc_ocupacion'];?></td>
                   </tr>
                </table>		
		<?php
		        $titulares[1][$j]=$regiDt['nombre'].' '.$regiDt['paterno'].' '.$regiDt['materno'];
				$titulares[2][$j]=$row['monto_solicitado'].' '.$row['moneda'];
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
							 }else{
								 echo'<div style="text-align:justify; border:1px solid #3B6E22; background:#6AA74F; padding:8px; color:#ffffff;">
							             Cumple con las preguntas del cuestionario 
						              </div>';
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
								<td style="width:30%; text-align:left;">'.imc_cp($dato).'</td>
								<td style="width:70%; text-align:right;">
								  <table border="0" cellspacing="0" cellpadding="0" style="width:30%; font-size:9px;">
									 <tr><td colspan="3" style="color:#ffffff; background:#0075AA; font-weight:bold; text-align:center; width:100%;">Datos</td></tr>
									 <tr><td><b>Estatura</b></td><td style="text-align:right">'.$regiDt['estatura'].'</td><td style="text-align:right"><b>cm</b></td></tr>
									 <tr><td><b>Peso</b></td><td style="text-align:right">'.$regiDt['peso'].'</td><td style="text-align:right"><b>kg</b></td></tr>
								  </table>
								</td> 
							  </tr> 
							</table>';
				  $j++;				 		
			}
		?>
       <div style="font-size:80%; width: 100%; height: auto; margin: 7px 0;">
            Declaro(amos) que las respuestas que he(mos) consignado en esta solicitud son verdaderas y completas y que es de mi (nuestro) conocimiento que cualquier declaraci&oacute;n inexacta, omisi&oacute;n u ocultaci&oacute;n har&aacute; perder todos los beneficios del seguro de acuerdo con el art&iacute;culo 1138 del C&oacute;digo de Comercio.<br/><br/>
             
            Asimismo autorizo(amos) a los m&eacute;dicos, cl&iacute;nicas, hospitales y otros centros de salud que me (nos) hayan atendido o que me (nos) atiendan en el futuro, para que proporcionen a <?=$row['compania'];?>, todos los resultados de los informes referentes a mi (nuestra) salud, en caso de enfermedad o accidente, para lo cual releva a dichos m&eacute;dicos y centros m&eacute;dicos en relaci&oacute;n con su secreto profesional, de toda responsabilidad en que pudiera incurrir al proporcionar tales informes. Asimismo, autorizo(amos) a <?=$row['compania'];?> a proporcionar &eacute;stos resultados a <?=$row['ef_nombre']?><br/><br/>
            
            <b>Nota importante:</b><br/>
            El solicitante acepta que la presente  declaraci&oacute;n jurada de salud es valida para las condiciones finales del cr&eacute;dito aprobado por el banco. el solicitante se convierte en asegurado una vez que se concreta el cr&eacute;dito o al momento de la aceptaci&oacute;n por parte de la compa&ntilde;&iacute;a aseguradora en los casos en los que corresponda.<br/><br/>
            
            El presente Slip de Cotizaci&oacute;n, tan solo representa un documento referencial. En caso que el Asegurado desee conocer inextenso las normas, t&eacute;rminos y condiciones del seguro, &eacute;ste deber&aacute; referirse &uacute;nicamente a la P&oacute;liza original, la misma que se encuentra en posesi&oacute;n del Tomador.<br/><br/>
            
            <b>TOMADOR: </b><?=$row['ef_nombre']?><br/>
		    <b>ACTIVIDAD GENERAL: </b>Varias y sin limitaciones<br/><br/>
		
		    <b>COBERTURAS:</b><br/>
		    <b>PRINCIPAL:</b><br/><br/>
                       
            <ol style="<?=$marginUl;?> list-style-type:upper-alpha;">
                <li>Muerte por cualquier Causa, que no est&eacute; excluida en el Condicionado General de la P&oacute;liza incluido el suicidio despu&eacute;s de dos a&ntilde;os de vigencia ininterrumpida de la cobertura individual del seguro.</li>
            </ol><br/>
            <b>ADICIONALES:</b><br/><br/>
            <ol style="<?=$marginUl;?> list-style-type:upper-alpha;" start="2">
                <li>Pago anticipado del capital asegurado en caso de invalidez total y permanente por accidente o enfermedad, en forma irreversible por lo menos en un 60% seg&uacute;n el manual de normas autorizado por la AUTORIDAD DE FISCALIZACION Y CONTROL SOCIAL DE PENSIONES,  en actual vigencia.</li>
                <li>Gastos de Sepelio, son los gastos que demanden los herederos legales o nominados por el Sepelio de un Asegurado (titular o c&oacute;nyuge), como consecuencia del fallecimiento por una enfermedad o un accidente cubierto, por un monto de USD 200.- por asegurado, establecido para esta cobertura, para el titular o c&oacute;nyuge.</li>
                <li>DESEMPLEO PARA TRABAJADORES DEPENDIENTES: El pago de la indemnizaci&oacute;n se realizar&aacute; de acuerdo a cuotas mensuales y si la forma de pago del prestatario es diferente a la mensual, se convertir&aacute;n a cuotas mensuales y se cubrir&aacute;n el equivalente a 4 cuotas mensuales, con un m&aacute;ximo de USD 1,000 por cuota y por persona. La forma de pago al contratante ser&aacute; realizada bajo un &uacute;nico pago.</li>
            </ol><br/>
            
            <b>CAPITALES ASEGURADOS:</b>
		    <b>COBERTURA PRINCIPAL:</b><br/><br/>
            <ol style="<?=$marginUl;?> list-style-type:upper-alpha;">
                <li>Saldo Deudor.</li>
            </ol><br/>
            
            <b>COBERTURAS ADICIONALES:</b><br/>
            <ol style="<?=$marginUl;?> list-style-type:upper-alpha;" start="2">
                <li> Saldo deudor</li>
				<li> USD 200.00</li>
				<li> 4 cuotas de acuerdo con el plan de pagos del cr&eacute;dito</li>
            </ol><br/>
           </div>      
      <div style="font-size:80%; width: 100%; height: auto; margin: 7px 0;">   
            <b>EXCLUSIONES:</b> La Compa&ntilde;&iacute;a no cubre y esta eximida de toda responsabilidad en caso de fallecimiento del asegurado en los siguientes casos:<br/><br/>
            <ul style="<?=$marginUl;?> list-style-type:lower-alpha;">
                <li>	Si el asegurado participa como conductor o acompa&ntilde;ante en competencias de autom&oacute;viles, motocicletas, lanchas de motor o avionetas, practicas de paraca&iacute;das.</li>
				<li>	Si el asegurado realiza operaciones o viajes submarinos o en transportes a&eacute;reos no autorizados para transporte de pasajeros, vuelos no regulares.</li>
				<li>	Enfermedades pre-existentes conocidas al inicio del seguro o enfermedad cong&eacute;nita, para cr&eacute;ditos mayores a USD. 5,000.00 &oacute; Bs. 35,000.00 dentro de los plazos establecidos en el art&iacute;culo 1138 del C&oacute;digo de Comercio.</li>
				<li>	SIDA/HIV para siniestros a partir de USD. 5,000.00 o Bs. 35,000.00</li>
				<li>	Guerra, invasi&oacute;n, revoluci&oacute;n, sublevaci&oacute;n, mot&iacute;n o hechos que las leyes clasifiquen como delitos contra la seguridad del Estado, siempre y cuando el asegurado no participe activamente.</li>
				<li>	Suicidio practicado por el asegurado dentro del primer a&ntilde;o de vigencia ininterrumpida de su cobertura. En consecuencia este riesgo quedara cubierto a partir del primer d&iacute;a del segundo a&ntilde;o de vigencia para cada operaci&oacute;n asegurada.</li>
				<li>	Fisi&oacute;n o fusi&oacute;n nuclear</li>
            </ul><br/>
            
            <b>PERIODO DE CARENCIA PARA SINIESTROS HASTA USD. 5,000.00 &Oacute; BS. 35,000.00:</b><br/>
		Se establece un periodo de carencia de 90 d&iacute;as para fallecimiento por enfermedad, computables a partir de la fecha de desembolso del cr&eacute;dito.<br/><br/>
        
            <b>PERIODO DE CARENCIA PARA SINIESTROS DE USD. 5,000.00 &Oacute; BS. 35,000.00 EN ADELANTE:</b><br/>
            Se establece un periodo de carencia de 90 d&iacute;as para fallecimiento por enfermedad, computables a partir de la fecha de desembolso del cr&eacute;dito.<br/><br/>
            
             <b>EXCLUSIONES PARA DESEMPLEO:</b> La Compa&ntilde;&iacute;a no cubre y esta eximida de toda responsabilidad cuando la cesant&iacute;a del asegurado se deba a:<br/><br/>
             <ul style="<?=$marginUl;?> list-style-type:lower-alpha;">
                <li>	Renuncia del Trabajador</li>
                <li>	Muerte del Trabajador</li>
                <li>	Vencimiento del plazo convenido en el Contrato de Trabajo</li>
                <li>	Conclusi&oacute;n de Trabajo o servicio que dio origen al Contrato de Trabajo </li>
                <li>	Falta de probidad v&iacute;as de hecho, injurias o conducta inmoral debidamente comprobada.</li>
                <li>	Negociaciones que ejecute el trabajador dentro del giro del negocio y que hubieran sido prohibidas por escrito por el empleador en el respectivo contrato de trabajo.</li>
                <li>	No concurrencia del trabajador a sus labores sin causa justificada durante 6 d&iacute;as requeridos.</li>
                <li>	Abandono de trabajo por parte del trabajador</li>
                <li>	El perjuicio material causado intencionalmente en las instalaciones, maquinarias, herramientas, &uacute;tiles de trabajo, productos o mercader&iacute;as.</li>
                <li>	Incumplimiento grave de las obligaciones que impone el Contrato de Trabajo</li>
                <li>	Acto delictivo cometido por el asegurado, que derive en la situaci&oacute;n de Cesant&iacute;a del mismo.</li>
                <li>	Embriaguez o uso de narc&oacute;ticos que deriven en la situaci&oacute;n de Cesant&iacute;a del Asegurado.</li>
                <li>	Participaci&oacute;n activa del asegurado en acto terrorista, entendi&eacute;ndose como acto terrorista toda conducta calificada como tal por Ley, as&iacute; como el uso de fuerza o violencia o amenaza de esta, por parte de cualquier persona o grupo, motivada por causas pol&iacute;ticas, religiosas, ideol&oacute;gicas, o similares, con la intenci&oacute;n de ejercer influencia sobre cualquier Gobierno o de atemorizar a la poblaci&oacute;n a cualquier segmento de la misma.</li>
                <li>	Suspensi&oacute;n de relaci&oacute;n laboral sin goce de haberes por inicio de proceso administrativo interno.</li>
                <li>	Sustituci&oacute;n como resultado de un proceso disciplinario a administrativo por responsabilidad por la funci&oacute;n P&uacute;blica o Proceso Judicial  con cesant&iacute;a condenatoria ejecutoriada.</li>
                <li>	Cuando el Asegurado se acoja a los beneficios de la Jubilaci&oacute;n </li>
                <li>	Cuando el Asegurado se acoja a los beneficios de los Seguros de riesgo profesional y Riesgo com&uacute;n del Sistema Integral de Pensiones SIP.</li>
            </ul><br/>
      
         <b>EDAD DE ADMISI&Oacute;N Y PERMANENCIA:</b><br/>
		
		<b>A.) Muerte por cualquier causa, B.) Desempleo y C.) Sepelio</b><br/>
		De admisi&oacute;n: M&iacute;nima: 18 a&ntilde;os	  M&aacute;xima: 70 a&ntilde;os (Hasta cumplir 71 a&ntilde;os)<br/>
		De Permanencia:		              M&aacute;xima: 76 a&ntilde;os (Hasta cumplir 77 a&ntilde;os)<br/><br/>
		
		<b>D. Invalidez Total y Permanente :</b><br/>
		De admisi&oacute;n: M&iacute;nima: 18 a&ntilde;os	  M&aacute;xima: 64 a&ntilde;os (Hasta cumplir 65 a&ntilde;os)<br/>
		De Permanencia:		              M&aacute;xima: 70 a&ntilde;os (Hasta cumplir 71 a&ntilde;os)<br/><br/>
       </div>
  
        <?php
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
		  echo'</table>';
		?>
       <div style="font-size:80%; width: 100%; height: auto; margin: 7px 0;">  
           <b>PRIMA:</b> De acuerdo a declaraciones mensuales del contratante<br/>
            <b>FORMA DE PAGO:</b> Contado a trav&eacute;s de <?=$row['ef_nombre']?><br/>
            <b>BENEFICIARIO:</b> <?=$row['ef_nombre']?> A T&Iacute;TULO ONEROSO<br/><br/>
            
            <b>OBSERVACIONES:</b> Las primas del seguro no constituyen hecho generador de tributo seg&uacute;n el Art. No. 54 de la Ley de Seguros 1883 del 25 de Junio de 1998 y a la Resoluci&oacute;n Ministerial Nro. 880 del 28 de Junio de 1999. Autorizo a la compa&ntilde;&iacute;a mi reporte a la Central de Riesgos del Mercado de Seguros, acorde a las normativas reglamentarias de la Autoridad de Fiscalizaci&oacute;n y Control de Pensiones y Seguros.<br/><br/>
            
            <b>AVISO DE SINIESTRO:</b> En caso de fallecimiento o invalidez total y permanente del Asegurado, el tomador, tan pronto y a m&aacute;s tardar dentro de los 90 d&iacute;as calendario siguientes de tener conocimiento del siniestro, debe comunicar el mismo a la Compa&ntilde;&iacute;a, salvo fuerza mayor o impedimento justificado, caso contrario la Compa&ntilde;&iacute;a se libera de cualquier responsabilidad indemnizatoria por extemporaneidad.<br/><br/>
            
            <b>PAGO DE SINIESTRO:</b> En caso de muerte del Asegurado, la indemnizaci&oacute;n del capital asegurado ser&aacute; pagada a <?=$row['ef_nombre']?>, en su calidad de beneficiario a t&iacute;tulo oneroso, como m&aacute;ximo a los 15 d&iacute;as a partir de la presentaci&oacute;n de documentos, de acuerdo al siguiente detalle:<br/><br/>
            
            <b>Para siniestros de USD. 5,000.00 &oacute; 35,000.00 en adelante:</b><br/><br/>
             <ul style="<?=$marginUl;?> list-style-type:disc;">
             <li>Certificado de Defunci&oacute;n o Certificado de la persona de jerarqu&iacute;a en la comunidad del asegurado. </li>
             <li>	Fotocopia de C. I. y/o Fotocopia de certificado de nacimiento y/o Fotocopia del Carnet de identidad RUN y/o Fotocopia de la libreta del servicio militar debidamente legalizado por el Jefe de Agencia.</li>
             <li>	Liquidaci&oacute;n de cartera con el monto indemnizable.</li>
             <li>	Informe del Oficial de Cr&eacute;dito dando fe del suceso y descripci&oacute;n breve de lo acontecido (causa de la muerte), con el respectivo visto bueno de su inmediato superior.</li>
             <li>	Historia Cl&iacute;nica si existiera </li>
             <li>	Para el caso de Invalidez: Dictamen de Invalidez emitido por un m&eacute;dico calificador con registro en la Autoridad de Fiscalizaci&oacute;n y Control de Pensiones y Seguros (APS).</li>
            </ul><br/>
            
            <?php
                if ( ( ($row['monto_solicitado'] <= 5000) and ($row['moneda'] == "USD") ) or ( ($row['monto_solicitado']<= 35000) and ($row['moneda'] == "BS") ) ){
                    echo'<b>Para siniestros con desembolso mayores a USD. 5,000.00 &oacute; Bs. 35,000.00</b><br/><br/>
                         <ul style="'.$marginUl.' list-style-type:disc;">
                          <li>Original o Fotocopia de la Declaraci&oacute;n Jurada de Salud.</li>
                          <li> Original de Certificado de Defunci&oacute;n o excepcionalmente Certificado de la persona de jerarqu&iacute;a en la comunidad del asegurado.</li>
                          <li>Fotocopia del Certificado Forense o Informe de la Autoridad Competente, si el fallecimiento ocurriese de manera violenta, accidental o en ejecuci&oacute;n de un hecho delictivo.</li>
                          <li>Certificado Médico &Uacute;nico de defunci&oacute;n o Certificado M&eacute;dico indicando la causa primaria, secundaria y agravante de fallecimiento.</li>
                          <li>Fotocopia de C. I. y/o Fotocopia de certificado de nacimiento y/o Fotocopia del Carnet de identidad RUN y/o Fotocopia de la libreta del servicio militar del asegurado debidamente visado por el Jefe de Agencia.</li>
                          <li>Liquidaci&oacute;n de cartera con el monto indemnizable.</li>
                          <li>Para el caso de Invalidez: Dictamen de Invalidez emitido por un m&eacute;dico calificador con registro en la Autoridad de Fiscalizaci&oacute;n y Control de Pensiones y Seguros (APS).</li>
                          <li>Para Gastos de Sepelio, Formulario de Declaraci&oacute;n de Beneficiarios o en ausencia de &eacute;ste la Declaratoria de Herederos.</li>
                        </ul><br/>';
                }
             ?>
             
             <b>Nota.</b> La p&oacute;liza establece que el beneficio de sepelio ser&aacute; pagado por la Compa&ntilde;&iacute;a directamente a ECOFUTURO FONDO FINANCIERO PRIVADO quien ser&aacute; responsable del pago ante el beneficiario del asegurado fallecido.<br/><br/>
            
            <b>PAGO DE SINIESTRO EN CASO DE DESEMPLEO:</b> Producida la cesant&iacute;a involuntaria el asegurado deber&aacute; comunicar por escrito a la compa&ntilde;&iacute;a dentro de un plazo de m&aacute;ximo de treinta (30) d&iacute;as  contados desde su conocimiento. El cumplimiento extempor&aacute;neo de esta obligaci&oacute;n har&aacute; perder el derecho a la indemnizaci&oacute;n establecida en la presente cl&aacute;usula de Cobertura Adicional, salvo caso de fuerza mayor o impedimento justificado.<br/><br/>
            
            Los documentos que se deben presentar para la liquidaci&oacute;n del reclamo son los siguientes:<br/><br/>
            <ul style="<?=$marginUl;?> list-style-type:disc;">
                <li>	Copia del Contrato de Trabajo</li>
                <li>	Carta de Despido</li>
                <li>	Original de Finiquito visado por el Ministerio de Trabajo (Para asegurados dependientes del sector p&uacute;blico, cuando corresponda, se aceptar&aacute; el original del Memorandum de retiro en reemplazo del Finiquito)</li>
                <li>	Plan de Pagos del Cr&eacute;dito</li>
                <li>	Fotocopia del Carnet de Identidad.</li>
                <li>	Copia del contrato de pr&eacute;stamo</li>
            </ul><br/>
            <b>DOCUMENTOS PARA EL PAGO</b><br/><br/>
            <ul style="<?=$marginUl;?> list-style-type:disc;">
                <li>Certificado de Incapacidad Temporal emitido por un m&eacute;dico calificador con registro en la APS.</li>
                <li>Plan de Pagos del Cr&eacute;dito</li>
                <li>Copia del contrato de pr&eacute;stamo</li>
            </ul><br/>
            
            Todo lo que no est&eacute; previsto por el Certificado de Cobertura Individual, se sujetar&aacute; a lo establecido en las Condiciones Particulares, Condiciones Generales y dem&aacute;s documentos Anexos a la presente P&oacute;liza de Seguro de Desgravamen Hipotecario en Grupo, el C&oacute;digo de Comercio, la Ley de Seguros y por las disposiciones legales vigentes en la materia.
            <?php
               if ( ( ($row['monto_solicitado'] <= 5000) and ($row['moneda'] == "USD") ) or ( ($row['monto_solicitado']<= 35000) and ($row['moneda'] == "BS") ) ){
                    echo'<h2 style="width: auto; height: auto; text-align: center; margin: 7px 0; padding: 0; font-weight: bold; '.$fontsizeh2.'"><u>SEGURO DE VIDA EN GRUPO &shy; ANUAL RENOVABLE</u></h2><br/>';
                    
                    echo'Esta secci&oacute;n tan solo representa un documento referencial. En caso que el Asegurado desee conocer inextenso las normas, t&eacute;rminos y condiciones del seguro, &eacute;ste deber&aacute; referirse &uacute;nicamente a la P&oacute;liza original, la misma que se encuentra en posesi&oacute;n del Tomador.<br/><br/>
                    
                    <b>TOMADOR:</b>		ECOFUTURO S.A. FFP<br/>
                    <b>ASEGURADO:</b>	Datos que figuran en Titular 1 y/o Titular 2 en la declaraci&oacute;n de salud (reverso) y/o Cotitulares adicionales<br/><br/>
                    
                    <b>GRUPO ASEGURADO:</b>	Clientes del Tomador<br/>
                    <b>ACTIVIDAD GENERAL:</b>	Varias y Sin Limitaciones<br/><br/>
                    
                    <b>COBERTURAS:</b><br/><br/>
                    <ul style="'.$marginUl.' list-style-type:upper-alpha;">
                     <li>Muerte por cualquier Causa del Asegurado, no excluida en las Condiciones Generales de la P&oacute;liza.</li>
                     <li>Invalidez Total y Permanente del Asegurado, no excluida en la Cobertura Complementaria respectiva.</li>
                    </ul><br/>
                    
                    <b>EXCLUSIONES:</b><br/>
                    Sin Exclusiones<br/><br/>
                    <b>BENEFICIARIOS:</b><br/>
                    Los Declarados por el Asegurado o los Herederos Legales<br/><br/>
                    
                    <b>SINIESTRO:</b><br/>
                    En caso de fallecimiento &oacute; invalidez total y permanente; el Tomador, tan pronto y a m&aacute;s tardar dentro de los 90 d&iacute;as calendario siguientes de tener conocimiento del siniestro, debe comunicar el mismo a la Compa&ntilde;&iacute;a, salvo fuerza mayor o impedimento justificado, caso contrario la Compa&ntilde;&iacute;a se libera de cualquier responsabilidad indemnizatoria por extemporaneidad.<br/><br/>
                    
                    En caso de muerte &oacute; invalidez del Asegurado, la indemnizaci&oacute;n del capital asegurado ser&aacute; pagada a los beneficiarios declarados o en ausencia de &eacute;stos a los Herederos Legales, como m&aacute;ximo a los 10 d&iacute;as h&aacute;biles despu&eacute;s de haber presentado los documentos probatorios de la muerte del Asegurado, de acuerdo al siguiente detalle:<br/><br/>
                    
                    <b>MUERTE POR CUALQUIER CAUSA</b><br/><br/>
                    <ul style="'.$marginUl.' list-style-type:disc;">
                        <li>	Certificado de Defunci&oacute;n o Certificado de la persona de jerarqu&iacute;a en la comunidad del asegurado.</li>
                        <li>	Fotocopia de C. I. y/o Fotocopia de certificado de nacimiento. y/o Fotocopia del Carnet de identidad RUN y/o Fotocopia de la libreta del servicio militar debidamente legalizado por el Jefe de Agencia.</li>
                        <li>	Liquidaci&oacute;n de cartera con el monto indemnizable.</li>
                        <li>	Formulario de Declaraci&oacute;n de Beneficiarios o en ausencia de &eacute;ste la Declaratoria de Herederos.</li>
                    </ul><br/><br/>
                    
                    <b>INVALIDEZ TOTAL Y PERMANENTE</b><br/><br/>
                    <ul style="'.$marginUl.' list-style-type:disc;">
                        <li>	Fotocopia de C. I. y/o Fotocopia de certificado de nacimiento. y/o Fotocopia del Carnet de identidad RUN y/o Fotocopia de la libreta del servicio militar debidamente legalizado por el Jefe de Agencia.</li>
                        <li>	Liquidaci&oacute;n de cartera con el monto indemnizable.</li>
                        <li>	Dictamen de un m&eacute;dico calificador con registro en la APS (Autoridad de Fiscalizaci&oacute;n y Control de Pensiones y Seguros), el cual determine el grado de invalidez.</li>
                        <li>	Formulario de Declaraci&oacute;n de Beneficiarios o en ausencia de &eacute;ste la Declaratoria de Herederos.</li>
                    </ul><br/>';
                    
               }
		  
		?>
       </div>
       <div style="font-size:80%; width: 100%; height: auto; margin: 7px 0;">
           <?php
			  if ( ( ($row['monto_solicitado'] <= 5000) and ($row['moneda'] == "USD") ) or ( ($row['monto_solicitado']<= 35000) and ($row['moneda'] == "BS") ) ){
				   echo'<b>PLAZO PARA PAGO DE SINIESTROS</b><br/>
		El plazo para el pago de siniestros ser&aacute; realizado dentro de los 10 d&iacute;as calendario de recibidos todos los documentos requeridos por la compa&ntilde;&iacute;a. Dicho pago se realizar&aacute; a trav&eacute;s de la emisi&oacute;n de cheques a nombre de los beneficiarios declarados &oacute; en ausencia a nombre de los Herederos Legales.<br/><br/>
		
		Todo lo que no est&eacute; previsto por el Certificado de Cobertura Individual, se sujetar&aacute; a lo establecido en las Condiciones Particulares, Condiciones Generales y dem&aacute;s documentos Anexos a la presente P&oacute;liza de Seguro de Vida en Grupo, el C&oacute;digo de Comercio, la Ley de Seguros y por las disposiciones legales vigentes en la materia.<br/><br/><br/><br/><br/><br/><br/><br/>';
			  }else{
				 echo'<br/><br/><br/><br/><br/><br/><br/><br/>';  
			  }
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

function imc_cp($dato){

		switch ($dato) :
			case 1: return 'Peso Normal';
			case 2: return 'Desnutricion';
			case 3: return 'Sobrepeso y Obesidad';
		endswitch;
	}
?>