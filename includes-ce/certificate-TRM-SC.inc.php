<?php
function trm_sc_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
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
             </tr>
             <tr>
               <td style="width:25%; text-align:center;"><?=$row['paterno'];?></td>
               <td style="width:25%; text-align:center;"><?=$row['materno'];?></td>
               <td style="width:25%; text-align:center;"><?=$row['nombre'];?></td>
             </tr>
             <tr style="font-weight:bold;">
               <td style="width:25%; text-align:center; font-weight:bold;">CI</td>
               <td style="width:25%; text-align:center; font-weight:bold;">Telefono Domicilio</td>
               <td style="width:50%; text-align:center; font-weight:bold;">Telefono Celular</td>
             </tr>
             <tr>
               <td style="width:25%; text-align:center;"><?=$row['CI'];?></td>
               <td style="width:25%; text-align:center;"><?=$row['telefono_domicilio'];?></td>
               <td style="width:50%; text-align:center;"><?=$row['telefono_celular'];?></td>
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
               <td style="width:50%; text-align:center;"><?=$row['telefono_oficina'];?></td>
               <td style="width:50%; text-align:center;"><?=$row['email'];?></td>
               
             </tr>
          </table><br/>		
     <?php
	  }
	 ?>
     
     <div style="width: auto; height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; <?=$fontsizeh2;?>">Datos de Solicitud</div>
     <?php
	   $prima_total=0;
	   if($row['prima_total']==0){
			if($rsDt->data_seek(0)){
			   while($regi = $rsDt->fetch_array(MYSQLI_ASSOC)){
				   $plazoanio = $conexion->get_year_final($row['plz_anio'], $row['tip_plz_code']);
				   $tasanual = $conexion->get_tasa_year_trm(base64_encode($row['id_compania']), base64_encode($row['idef']),  $plazoanio, $row['fmp_code']);
				   $prima_equimovil=($tasanual['t_tasa_final']*$regi['valor_asegurado'])/100;
				   $prima_total=$prima_total+$prima_equimovil;
			   }	
			}
	   }else{
		  $prima_total=$row['prima_total'];   
	   }
	 ?>
     <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; <?=$fontSize;?>">
         <tr>
           <td style="width:50%; text-align:right;"><b>Compañía de Seguros:</b></td>
           <td style="width:50%;"><?=$row['compania'];?></td>
         </tr>
         <tr>
           <td style="width:50%; text-align:right;"><b>Seguro a contratar:</b></td>
           <td style="width:50%;">Todo Riesgo Equipo Movil</td>
         </tr>
         <tr>
           <td style="width:50%; text-align:right;"><b>Periodo de contratacion:</b></td>
           <td style="width:50%;"><?=$row['tipo_plazo_text'];?></td>
         </tr>
         <tr>
           <td style="width:50%; text-align:right;"><b>Prima Anual:</b></td>
           <td style="width:50%;"><?=number_format($prima_total,2,".",",");?> USD</td>
         </tr>
         <tr>
           <td style="width:50%; text-align:right;"><b>Prima total:</b></td>
           <td style="width:50%;"><?=number_format($prima_total*$row['cant_plazo'],2,".",",");?> USD</td>
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
     
     
     <div style="width: auto; height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; <?=$fontsizeh2;?>">Datos Equipo Movil</div>
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; <?=$fontSize;?>">
            <tr>
            <td style="width:60%; text-align:center;"><b>Material</b></td>
            <td style="width:20%; text-align:center;"><b>Valor Asegurado (USD)</b></td>
            <td style="width:10%; text-align:center;"><b>Tasa Anual (%o)</b></td>
            <td style="width:10%; text-align:center;"><b>Prima</b></td>
            </tr>
            <?php
			  if($rsDt->data_seek(0)){ 
				  $sum_facu=0;
				  while($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)){
					  $plazo_anio = $conexion->get_year_final($row['plz_anio'], $row['tip_plz_code']);
					  $tasa_anual = $conexion->get_tasa_year_trm(base64_encode($row['id_compania']), base64_encode($row['idef']),  $plazo_anio, $row['fmp_code']);
					  $prima_equimovil=($tasa_anual['t_tasa_final']*$rowDt['valor_asegurado'])/100;
					  $sum_facu=$sum_facu+$rowDt['valor_asegurado'];
				?>
					<tr>
					<td style="width:60%; text-align:center;"><?=$rowDt['material'];?></td>
					<td style="width:20%; text-align:center;"><?=number_format($rowDt['valor_asegurado'],2,".",",");?></td>
					<td style="width:10%; text-align:center;"><?=$tasa_anual['t_tasa_final'];?></td>
					<td style="width:10%; text-align:center;"><?=number_format($prima_equimovil,2,".",",");?></td>
					</tr> 
            <?php
				 }
			  }
			?>
        </table><br/>
        <?php
        if($sum_facu>$row['monto_facultativo']){
		?>	
           <div style="font-size:8pt; text-align:justify; margin-top:5px; margin-bottom:0px; border:1px solid #C68A8A; background:#FFEBEA; padding:8px;">
                  La presente cotizaci&oacute;n  referencial tiene uno o mas &iacute;tems cuyo valor supera los <?=number_format($row['monto_facultativo'],2,".",",")?> USD o la suma de todos los &iacute;tems supera los <?=number_format($row['monto_facultativo'],2,".",",")?> USD, por lo tanto la aseguradora se reserva el derecho de solicitar mayor documentaci&oacute;n.
           </div><br/>
        <?php
		}
		?>
     
                  
       <div style="width: 700px; height: auto; margin: 7px 0; <?=$fontSize;?> text-align:justify;">
            
            TODA PROPIEDAD REAL DE PROPIEDAD DEL CONTRATANTE/BENEFICIARIO (BISA LEASING S.A) OTORGADA EN ARRENDAMIENTO FINANCIERO AL  ASEGURADO Y/O BAJO SU CUSTODIA, CUIDADO Y/O CONTROL Y POR LA CUAL SEA LEGALMENTE RESPONSABLE Y/O EN  LA CUAL  PUDIERA TENER INTERÉS, TAL COMO EXISTEN AHORA O SE ADQUIERAN MÁS ADELANTE, EN CUALQUIER FORMA QUE LA POSEA, MANTENGA EN CUSTODIA Y/O BIENES QUE SE ENCUENTREN EN CUSTODIA DE TERCEROS U OTROS Y/O POR LA CUAL SEA  O PUDIESE SER RESPONSABLE.
			      <ol style="margin-left:10px; padding-top:8px; padding-bottom:8px; list-style-type:disc;"> 	  
					<li>EN CASO DE BIENES INMUEBLES:
					    <ol style="margin-left:5px; padding-top:8px; padding-bottom:8px; list-style-type:circle;">
						  <li>INCLUYENDO EN TODOS LOS CASOS,OBRAS CIVILES Y SUS INSTALACIONES, INCLUYENDO LUMINARIAS, ALFOMBRADO (SIEMPRE Y CUANDO ESTÉN INCLUIDAS EN EL AVALÚO TÉCNICO), REVESTIMIENTOS; VIDRIOS, ACCESORIOS SANITARIOS, MUROS PERIMETRALES, TANQUES; ESTACIONAMIENTOS, ÁREAS DE DEPÓSITO Y LA PARTE PROPORCIONAL DE ÁREAS COMUNES, CUANDO CORRESPONDA.</li>
						  <li>PARA RIESGOS DOMICILIARIOS SE INCLUYE EL CONTENIDO HASTA UN 10% DEL VALOR ASEGURADO MÁXIMO HASTA $US. 20.000.- LOS MISMOS QUE DEBERÁN ESTAR DECLARADOS EN EL FORMULARIO DEL CLIENTE PRESENTADO POR BISA LEASING, EXCLUYENDO PRENDAS DE VESTIR,  COMESTIBLES, DINERO Y/O VALORES Y/O SIMILARES, JOYAS Y/O SIMILARES, OBRAS DE ARTE, ANTIGÜEDADES Y/O SIMILARES.</li>
						</ol>
				    </li>		  
    				<li>MAQUINARIA PESADA MÓVIL (GRÚAS, PALAS MECÁNICAS, EXCAVADORAS, CAMIONES CONCRETEROS, MOTONIVELADORAS, TRACTORES, Y OTROS SIMILARES), INCLUYENDO SUS EQUIPOS AUXILIARES QUE SE ENCUENTRES DECLARADOS DENTRO DE LA MATERIA ASEGURADA, YA SEA QUE ESTÉN CONECTADOS O NO AL EQUIPO O MAQUINARIA OBJETO DEL SEGURO O QUE SE ENCUENTREN OPERANDO O DURANTE SU TRAYECTO POR SUS PROPIOS MEDIOS O NO DENTRO O FUERA DE LOS PREDIOS.</li>
					<li>MERCADERÍAS.</li>
				  </ol><br/>	 
  
             <table border="0" cellpadding="0" cellspacing="0" style="width: 50%;">
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