<?php
function de_pes_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
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
               <img src="<?=$url;?>images/<?=$row['logo_ef'];?>" width="200"/>
           </td>
           <td style="width:32%;"></td>
           <td style="width:34%; text-align:right;">
               <img src="<?=$url;?>images/<?=$row['logo_cia'];?>" width="100"/>
           </td>
         </tr>
         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
           <td style="width:34%;">No. VG-<?=$row['no_cotizacion'];?></td>
           <td style="width:32%;"></td> 
           <td style="width:34%; text-align:right;">Cotización válida hasta el: 05-12-2013</td>
         </tr>
         <tr>
           <td colspan="3" style="width:100%; text-align:center;">
             SOLICITUD DE SEGURO DE VIDA EN GRUPO
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
                <div style="width: auto;	height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; <?=$fontsizeh2;?>">Datos del titular <?=$regiDt['cont_titular'];?></div>                             
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
		        $titulares[1][$j] = $regiDt['nombre'].' '.$regiDt['paterno'].' '.$regiDt['materno'];
				$titulares[2][$j] = number_format($row['monto'], 2, '.', ',').' '.$row['moneda'];
				$titulares[3][$j] = $row['tasa_final'];
				 echo'<div style="width: auto;	height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; '.$fontsizeh2.'">Cuestionario</div>
						<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; '.$fontSize.'">';
						  $c=0;
						  $error=array();
						  foreach ($phpArray as $key => $value) {
							  $vec=explode('|',$value);
							  $num_pregunta=$vec[0];
							  $respuesta=$vec[1];
							  $select4="select
										  pregunta,
										  respuesta,
										  orden
										from
										  s_pregunta
										where
										  orden=".$num_pregunta.";"; 			  
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
								<td style="width:30%; text-align:left;">'.imc($dato).'</td>
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
		
		<hr />
		
		<div style="width: auto; height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; <?=$fontsizeh2;?>">
			COBERTURAS
		</div>
		<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; <?=$fontSize;?>">
			<tr style="text-align:center;">
				<td style="width:30%;"><b></b></td>
				<td style="width:40%;"><b></b></td>
				<td style="width:30%;"><b></b></td>
			</tr>
			<tr style="text-align:center;">
				<td style="width:30%;">HOSPITALARIO</td>
				<td style="width:40%;"><?=number_format($row['pr_hospitalario'], 2, '.', ',') . ' USD';?></td>
				<td style="width:30%;"></td>
			</tr>
			<tr style="text-align:center;">
				<td style="width:30%;">VIDA</td>
				<td style="width:40%;"><?=number_format($row['pr_vida'], 2, '.', ',') . ' USD';?></td>
				<td style="width:30%;"></td>
			</tr>
			<tr style="text-align:center;">
				<td style="width:30%;">CESANTIA</td>
				<td style="width:40%;"><?=number_format($row['pr_cesante'], 2, '.', ',') . ' USD';?></td>
				<td style="width:30%;"></td>
			</tr>
			<tr style="text-align:center;">
				<td style="width:30%; padding-top: 10px; font-weight: bold;">PRIMA TOTAL</td>
				<td style="width:40%; padding-top: 10px; font-weight: bold;"><?=number_format($row['pr_prima'], 2, '.', ',') . ' USD';?></td>
				<td style="width:30%;"></td>
			</tr>
		</table>
		<hr />
		
		
       <div style="font-size:80%; width: 100%; height: auto; margin: 7px 0;">
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
?>