<?php
function de_em_certificate($link, $row, $rsDt, $url, $implant, $type, $fac, $reason = '') {
    $emitir = (boolean)$row['emitir'];

    if ($emitir === true) {
        $row['fecha_emision'] = $row['fecha_emision'];
    } else {
        $row['fecha_emision'] = $row['fecha_creacion'];
    }

    $row['fecha_emision'] = $row['u_departamento'] . ', ' . date('d/m/Y', strtotime($row['fecha_emision']));
	
	$nCl = $rsDt->num_rows;
    $_coverage = $row['cobertura'];

    $coverage = array('', '', '');
    switch ($_coverage) {
    case 'IM':
        if ($nCl === 1) {
            $coverage[0] = 'X';
        } elseif ($nCl === 2) {
            $coverage[1] = 'X';
        }
        break;
    case 'BC':
        $coverage[2] = '';
        break;
    }

    $k = 0;
    
    ob_start();
?>
<div id="container-c" style="width: 785px; height: auto; 
    border: 0px solid #0081C2; padding: 5px;">
    <div id="main-c" style="width: 775px; font-weight: normal; font-size: 12px; 
        font-family: Arial, Helvetica, sans-serif; color: #000000;">
<?php
if($_coverage === 'IM'){
?>
        <div style="width: 775px; border: 0px solid #FFFF00; text-align:center;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-family: Arial;">
                <tr>
                  <td style="width:100%; text-align:left;">
                     <img src="<?=$url;?>images/<?=$row['logo_cia'];?>" height="60"/>
                  </td> 
                </tr>
                <tr>
                  <td style="width:100%; font-weight:bold; text-align:center; font-size: 80%;">
                     DECLARACIÓN JURADA DE SALUD Nº<br />SOLICITUD DE SEGURO DE DESGRAVAMEN HIPOTECARIO
                  </td> 
                </tr>
            </table>     
        </div>
        <br/>
        
        <div style="width: 775px; border: 0px solid #FFFF00;">
			<span style="font-weight:bold; font-size:75%;">
            Estimado Cliente, agradeceremos completar la información que se requiere a continuación: (utilice letra clara)<br>
<?php
     $titular=array();
     if($rsDt->data_seek(0)){ 
	     while($rowcl = $rsDt->fetch_array(MYSQLI_ASSOC)){
		    $k += 1;
			$titular[$k]=$rowcl['nombre'].' '.$rowcl['paterno'].' '.$rowcl['materno'];  
?>             
            DATOS PERSONALES: (TITULAR <?=$k;?>):</span> 
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial; 
                padding-top:4px; padding-bottom:3px;">
                <tr> 
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                        <tr>
                          <td style="width:13%;">Nombres Completo: </td>
                          <td style="border-bottom: 1px solid #333; width:87%;">
                             <?=$rowcl['nombre_completo'];?> 
                          </td>
                        </tr>
                     </table>
                  </td>      
                </tr>
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:19%;">Lugar y Fecha de Nacimiento: </td>
                          <td style="border-bottom: 1px solid #333; width:81%;">
                             <?=$rowcl['lugar_nacimiento'].' '.$rowcl['fecha_nacimiento'];?> 
                          </td>
                        </tr>
                      </table>                                  
                  </td>
                </tr>   
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:13%;">Carnet de Identidad: </td>
                          <td style="border-bottom: 1px solid #333; width:39%;">
                             <?=$rowcl['dni'].$rowcl['complemento'].$rowcl['extension'];?> 
                          </td>
                          <td style="width:4%;">Edad: </td>
                          <td style="width:11%; border-bottom: 1px solid #333; text-align:center;">
                              <?=$rowcl['edad'];?>
                          </td>
                          <td style="width:4%;">Peso: </td>
                          <td style="width:11%; border-bottom: 1px solid #333; text-align:center;">
                              <?=$rowcl['peso'];?>
                          </td>
                          <td style="width:6%;">Estatura: </td>
                          <td style="width:12%; border-bottom: 1px solid #333; text-align:center;">
                              <?=$rowcl['estatura'];?>
                          </td>   
                        </tr>
                      </table> 
                  </td>              
                </tr>
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                      <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                           <td style="width: 6%;">Dirección: </td>
                           <td style="width: 41%; border-bottom: 1px solid #333;">
                             <?=$rowcl['direccion'];?>
                           </td>
                           <td style="width: 6%;">Tel Dom: </td>
                           <td style="width: 15%; border-bottom: 1px solid #333;">
                             <?=$rowcl['telefono_domicilio'];?>
                           </td>
                           <td style="width: 7%;">Telf Oficina: </td>
                           <td style="width: 15%; border-bottom: 1px solid #333;">
                             <?=$rowcl['telefono_oficina']?>
                           </td>
                         </tr>
                      </table> 
                  </td> 
                </tr>
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                          <td style="width:7%;">Ocupación: </td>
                          <td style="width:93%; border-bottom: 1px solid #333;">
                             <?=$rowcl['ocupacion'];?>
                          </td> 
                         </tr> 
                      </table>
                  </td>     
                </tr> 
            </table>
<?php
		 }
	 }
	 if($nCl === 1){
		 $titular[2]='';
?>            
            <span style="font-weight:bold; font-size:75%;">DATOS PERSONALES: (TITULAR 2)</span>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial; 
                padding-top:4px; padding-bottom:3px;">
                <tr> 
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                        <tr>
                          <td style="width:13%;">Nombres Completo: </td>
                          <td style="border-bottom: 1px solid #333; width:87%;">&nbsp;
                              
                          </td>
                        </tr>
                     </table>
                  </td>      
                </tr>
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:19%;">Lugar y Fecha de Nacimiento: </td>
                          <td style="border-bottom: 1px solid #333; width:81%;">&nbsp;
                              
                          </td>
                        </tr>
                      </table>                                  
                  </td>
                </tr>   
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:13%;">Carnet de Identidad: </td>
                          <td style="border-bottom: 1px solid #333; width:39%;">&nbsp;
                              
                          </td>
                          <td style="width:4%;">Edad: </td>
                          <td style="width:11%; border-bottom: 1px solid #333;">&nbsp;
                              
                          </td>
                          <td style="width:4%;">Peso: </td>
                          <td style="width:11%; border-bottom: 1px solid #333;">&nbsp;
                              
                          </td>
                          <td style="width:6%;">Estatura: </td>
                          <td style="width:12%; border-bottom: 1px solid #333;">&nbsp;
                              
                          </td>   
                        </tr>
                      </table> 
                  </td>              
                </tr>
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                      <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                           <td style="width: 7%;">Dirección: </td>
                           <td style="width: 50%; border-bottom: 1px solid #333;">&nbsp;
                             
                           </td>
                           <td style="width: 7%;">Tel Dom: </td>
                           <td style="width: 14%; border-bottom: 1px solid #333;">&nbsp;
                             
                           </td>
                           <td style="width: 8%;">Telf Oficina: </td>
                           <td style="width: 14%; border-bottom: 1px solid #333;">&nbsp;
                             
                           </td>
                         </tr>
                      </table> 
                  </td> 
                </tr>
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                          <td style="width:7%;">Ocupación: </td>
                          <td style="width:93%; border-bottom: 1px solid #333;">&nbsp;
                             
                          </td> 
                         </tr> 
                      </table>
                  </td>     
                </tr> 
            </table>
<?php
	 }
?>            
            <span style="font-weight:bold; font-size:75%;">DEL CRÉDITO SOLICITADO:</span> 
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial; 
                padding-top:4px; padding-bottom:6px;">
               <tr><td colspan="3" style="width:100%; text-align:left;">Usted(es) solicita(n) el seguro de tipo:</td></tr>
               <tr><td style="width:100%; padding:3px;" colspan="3"></td></tr>
               <tr>
                  <td style="width:15%; text-align:left;">Individual</td>
                  <td style="width:6%;">
                    <div style="width: 25px; height: 12px; border: 1px solid #000; text-align:center;">
                      <?=$coverage[0];?>
                     </div> 
                  </td>
                  <td style="width:79%; text-align:left;">
                      si marca esta opción, sólo debe completar la información requerida al TITULAR 1
                  </td>
               </tr>
               <tr><td style="width:100%; padding:3px;" colspan="3"></td></tr>
               <tr>
                  <td style="width:15%; text-align:left;">Mancomunada</td>
                  <td style="width:6%;">
                    <div style="width: 25px; height: 12px; border: 1px solid #000; text-align:center;">
                      <?=$coverage[1];?>
                     </div> 
                  </td>
                  <td style="width:79%; text-align:left;">
                      si marca esta opción, sólo debe completar la información requerida al TITULAR 1 y TITULAR 2
                  </td>
               </tr>
               <tr>
                  <td style="width:100%; padding-top:8px;" colspan="3">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                          <td style="width:20%;">Monto Actual solicitado en <?=$row['moneda'];?>: </td>
                          <td style="width:30%; border-bottom: 1px solid #333;">
                             <?=$row['monto_solicitado'];?>
                          </td>
                          <td style="width:20%;">Monto Actual Acumulado <?=$row['moneda']?>: </td>
                          <td style="width:30%; border-bottom: 1px solid #333;">
                             <?=$row['cumulo_deudor'];?>
                          </td>  
                         </tr> 
                      </table>
                  </td>
               </tr>
               <tr>
                  <td style="width:100%; padding-top:6px;" colspan="3">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                          <td style="width:18%;">Plazo del presente crédito: </td>
                          <td style="width:82%; border-bottom: 1px solid #333;">
                             <?=$row['plazo'].' '.$row['tipo_plazo'];?>
                          </td>  
                         </tr> 
                      </table>
                  </td>
               </tr>    
            </table>     
            
            <span style="font-weight:bold; font-size:75%;">CUESTIONARIO</span>
            <table 
               cellpadding="0" cellspacing="0" border="0" 
               style="width: 100%; height: auto; font-size: 75%; font-family: Arial;">
               <tr>
                  <td style="width:63%;"></td>
                  <td style="width:16%; text-align:center;" colspan="4">TITULAR 1</td>
                  <td style="width:5%;">&nbsp;</td>
                  <td style="width:16%; text-align:center;" colspan="4">TITULAR 2</td>
               </tr>
<?php
      if($rsDt->data_seek(0)){
		  $cont = 0;
          $rsCl_1 = array();
          $rsCl_2 = array();
		  while($rowrp=$rsDt->fetch_array(MYSQLI_ASSOC)){
			  $cont += 1;
			  if($cont === 1) {
				  $rsCl_1 = json_decode($rowrp['respuesta'],TRUE);
			  } elseif($cont === 2) {
				  $rsCl_2 = json_decode($rowrp['respuesta'],TRUE);
			  }
		  }  
	  }
        
        $resp1_yes = $resp1_no = '';    $resp2_yes = $resp2_no = '';
        foreach ($row['questions'] as $key => $question) {
            if (count($rsCl_1) > 0) {
                $respCl = $rsCl_1[$question['orden']];
                if ($question['id_pregunta'] == $respCl['id']) {
                    if ($respCl['value'] === 1) {
                        $resp1_yes = 'X';
                    } elseif($respCl['value'] === 0) {
                        $resp1_no = 'X';
                    }
                }
            }

            if (count($rsCl_2) > 0) {
                $respCl = $rsCl_2[$question['orden']];
                if ($question['id_pregunta'] == $respCl['id']) {
                    if ($respCl['value'] === 1) {
                        $resp2_yes = 'X';
                    } elseif($respCl['value'] === 0) {
                        $resp2_no = 'X';
                    }
                }
            }
?>
                <tr>
                  <td style="width:63%; text-align:left;">
                      <?=$question['orden'].' '.$question['pregunta'];?>
                  </td>
                  <td style="width:3%;">SI</td>
                  <td style="width:5%;">
                     <div style="width: 20px; height: 12px; border: 1px solid #000; text-align:center;">
                      <?=$resp1_yes;?>
                     </div> 
                  </td>
                  <td style="width:3%;">NO</td>
                  <td style="width:5%;">
                     <div style="width: 20px; height: 12px; border: 1px solid #000; text-align:center;">
                      <?=$resp1_no;?>
                     </div> 
                  </td>
                  <td style="width:5%;">&nbsp;</td>
                  <td style="width:3%;">SI</td>
                  <td style="width:5%;">
                     <div style="width: 20px; height: 12px; border: 1px solid #000; text-align:center;">
                      <?=$resp2_yes;?>
                     </div> 
                  </td>
                  <td style="width:3%;">NO</td>
                  <td style="width:5%;">
                     <div style="width: 20px; height: 12px; border: 1px solid #000; text-align:center;">
                      <?=$resp2_no;?>
                     </div> 
                  </td>
               </tr>
<?php
        }
?>               
                
            </table> <br>
            
            <span style="font-weight:bold; font-size:75%;">SI ALGUNA DE SUS RESPUESTAS ES AFIRMATIVA, FAVOR BRINDAR DETALLES:</span>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial; 
                padding-top:4px;">
<?php
     if($rsDt->data_seek(0)){
		 $cont=0;
		 while($rowobs=$rsDt->fetch_array(MYSQLI_ASSOC)){
			$cont += 1;   
?>                
               <tr>
                <td style="width:10%;">TITULAR <?=$cont;?>: </td>
                <td style="width:90%; border-bottom: 1px solid #333;">
                  <?=$rowobs['observacion'];?>
                </td> 
               </tr>
<?php
             if($cont<$nCl){
				echo'<tr><td colspan="2" style="width:100%; padding:3px;"></td></tr>'; 
			 }
		 }
		 if($nCl===1){
?>
              <tr><td colspan="2" style="width:100%; padding:3px;"></td></tr>
              <tr>
                <td style="width:10%;">TITULAR 2: </td>
                <td style="width:90%; border-bottom: 1px solid #333;">&nbsp;
                  
                </td> 
               </tr>
<?php
		 }
	 }
?>                
            </table><br>
            <div style="font-size: 75%; text-align:justify;">  
                Declaro(amos) haber contestado con total veracidad, máxima buena fe a todas las preguntas del presente cuestionario y no haber omitido u ocultado hechos y/o circunstancias que hubiera podido influir en la celebración del contrato de seguro. Las declaraciones de salud que hacen anulable el Contrato de Seguros y en la que el asegurado pierde su derecho a indemnización, se enmarcan en los articulos 992, 993, 999 y 1038 del Código de Comercio.<br>
              Relevo expresamente del secreto profesional y legal, a cualquier médico que me hubiese asistido y/o tratado de dolencias y le autorizo (amos) a revelar a Nacional Vida Seguros de Personas S.A. todos los datos y antecedentes patológicos que pudiera (amos) tener o de los que hubiera (amos) adquirido conocimiento al prestarme sus servicios. Entiendo que de presentarse alguna eventualidad contemplada bajo la póliza de seguro como consecuencia de alguna enfermedad existente a la fecha de la firma de este documento o cuando haya alcanzado la edad límite estipulada en la póliza, la compañía aseguradora quedará liberada de toda la responsabilidad en lo que respecta a mí (nuestro) seguro. Finalmente, declaro (amos) conocer en su totalidad lo estipulado en mi (nuestra) póliza de seguro      
            </div><br>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial; 
                padding-top:4px;">
               <tr>
                <td style="width:10%;">Lugar y Fecha: </td>
                <td style="width:30%; border-bottom: 1px solid #333;">
                  <?=$row['fecha_emision'];?>
                </td>
                <td style="width:7%;">Firma:</td>
                <td style="width:23%; border-bottom: 1px solid #333;">&nbsp;
                  
                </td>
                <td style="width:7%;">Firma:</td>
                <td style="width:23%; border-bottom: 1px solid #333;">&nbsp;
                  
                </td> 
               </tr>
               <tr>
                <td style="width:10%;"></td>
                <td style="width:30%; border-bottom: 0px solid #333;">&nbsp;
                  
                </td>
                <td style="width:7%;"></td>
                <td style="width:23%; text-align:center;">
                  TITULAR 1
                </td>
                <td style="width:7%;"></td>
                <td style="width:23%; text-align:center;">
                  TITULAR 2
                </td> 
               </tr>
            </table>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial; 
                padding-top:10px;">
               <tr>
                <td style="width:10%;">&nbsp;</td>
                <td style="width:30%; border-bottom: 0px solid #333;">&nbsp;
                  
                </td>
                <td style="width:7%;">Nombre:</td>
                <td style="width:23%; border-bottom: 1px solid #333;">
                  <?=$titular[1];?>
                </td>
                <td style="width:7%;">Nombre:</td>
                <td style="width:23%; border-bottom: 1px solid #333;">
                  <?=$titular[2];?>
                </td> 
               </tr>
            </table> 
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial; 
                padding-top:20px;">
               <tr>
                <td style="width:10%;">No. de Crédito</td>
                <td style="width:35%; border-bottom: 1px solid #333;">&nbsp;
                  
                </td>
                <td style="width:25%;"></td>
                                
                <td style="width:30%; border-bottom: 1px solid #333;">&nbsp;
                  
                </td> 
               </tr>
               <tr>
                <td style="width:10%;"></td>
                <td style="width:35%; border-bottom: 0px solid #333;">&nbsp;
                  
                </td>
                <td style="width:25%;"></td>
                                
                <td style="width:30%; border-bottom: 0px solid #333; text-align:center; font-size:75%;">
                  OFICIAL DE CRÉDITO<br>FIRMA Y SELLO
                </td> 
               </tr>
            </table>
            <div style="'width: 100%; height: auto; margin: 0 0 5px 0;">
<?php
           if((boolean)$row['facultativo']===true){
			   if((boolean)$row['aprobado']===true){
?>
                 <table border="0" cellpadding="1" cellspacing="0" style="width: 100%; font-size: 8px; font-weight: normal; font-family: Arial; margin: 2px 0 0 0; padding: 0; border-collapse: collapse; vertical-align: bottom;">
                    <tr>
                        <td colspan="7" style="width:100%; text-align: center; font-weight: bold; background: #e57474; color: #FFFFFF;">Caso Facultativo</td>
                    </tr>
                    <tr>
                        
                        <td style="width:5%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Aprobado</td>
                        <td style="width:5%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Tasa de Recargo</td>
                        <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Porcentaje de Recargo</td>
                        <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Tasa Actual</td>
                        <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Tasa Final</td>
                        <td style="width:69%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Observaciones</td>
                    </tr>
                    <tr>
                        
                        <td style="width:5%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=strtoupper($row['aprobado']);?></td>
                        <td style="width:5%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=strtoupper($row['tasa_recargo']);?></td>
                        <td style="width:7%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['porcentaje_recargo'];?> %</td>
                        <td style="width:7%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['tasa_actual'];?> %</td>
                        <td style="width:7%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['tasa_final'];?> %</td>
                        <td style="width:69%; text-align: justify; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['motivo_facultativo'];?> |<br /><?=$row['observacion'];?></td>
                    </tr>
               </table>
            
<?php
			   }else{
?>
                  <table border="0" cellpadding="1" cellspacing="0" style="width: 80%; font-size: 9px; border-collapse: collapse; font-weight: normal; font-family: Arial; margin: 2px 0 0 0; padding: 0; border-collapse: collapse; vertical-align: bottom;">         
                   <tr>
                      <td  style="text-align: center; font-weight: bold; background: #e57474; color: #FFFFFF;">
                        Caso Facultativo
                      </td>
                     </tr>
                     <tr>
                      <td style="text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">
                        Observaciones
                      </td>
                     </tr>
                     <tr>
                      <td style="text-align: justify; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['motivo_facultativo'];?></td>
                     </tr>
                </table>
<?php				   
			   
			   }
		   }
?>
            </div>
            
            <div style="'width: 100%; height: auto; margin: 0 0 5px 0;">
<?php
			 $queryVar = 'set @anulado = "Polizas Anuladas: ";';
			 if($link->query($queryVar,MYSQLI_STORE_RESULT)){
				 $canceled="select 
								max(@anulado:=concat(@anulado, prefijo, '-', no_emision, ', ')) as cert_canceled
							from
								s_de_em_cabecera
							where
								anulado = 1
									and id_cotizacion = '".$row['id_cotizacion']."';";
				 if($resp = $link->query($canceled,MYSQLI_STORE_RESULT)){
					 $regis = $resp->fetch_array(MYSQLI_ASSOC);
					 echo '<span style="font-size:8px;">'.trim($regis['cert_canceled'],', ').'</span>';
				 }else{
					 echo "Error en la consulta "."\n ".$link->errno. ": " . $link->error;
				 }
			 }else{
			   echo "Error en la consulta "."\n ".$link->errno. ": " . $link->error;   
			 }
?>
            </div>
            <div style="font-size: 60%; text-align:center; margin-top:20px;">  
                <b>NACIONAL VIDA SEGUROS DE PERSONAS S.A.</b>, SANTA CRUZ Tel. Piloto: (591-3) 371-6262 * Fax: (591-3) 371-6505<br>LA PAZ Tel. Piloto (591-2) 244-2942 * Fax: (591-2) 244-2905 - COCHABAMBA Tel. Piloto: (591-4) 445-7100 * Fax: (591-4 445-7104)<br>
                SUCRE Tel.Piloto (591-4) 642-5196 * Fax: (591-4) 642-5197-TARIJA Tel. (591-4) 666-6229 * Beni Tel/fax (591-3) 463-4109 * MONTERO Tel. (591-3) 922-6012<br>
                206-934901-2000 03 006-3012     
            </div>  	
        </div>            
<?php 
       if($type!=='MAIL' && (boolean)$row['emitir']===true && (boolean)$row['anulado']===false){
?>        
            <page><div style="page-break-before: always;">&nbsp;</div></page>
            
            <div style="width: 775px; border: 0px solid #FFFF00;">
                <table 
                    cellpadding="0" cellspacing="0" border="0" 
                    style="width: 100%; height: auto; font-size: 70%; font-family: Arial;">
                    <tr>
                      <td>
                        <div style="text-align: center; ">
                           <strong>CERTIFICADO INDIVIDUAL DE SEGURO SEGURO DE VIDA DE DESGRAVAMEN N°</strong><br>
                           Formato aprobado por la Autoridad de Fiscalización y Control de Pensiones y Seguros -APS 
                           mediante R.A No.081 del 10/03/00<br>
                           POLIZA DE SEGURO DE DESGRAVAMEN HIPORTECARIO N° POL-DH-LP-00103-2013-01<br>Codigo 206-934901-2000 03 006 4008
                        </div><br>
                        NACIONAL VIDA Seguros de Personas S.A., (denominada en adelante la “Compañía “), por el presente CERTIFICADO INDIVIDUAL DE SEGURO hace constar que la persona nominada en la declaración jurada de salud / solicitud de seguro de desgravamen hipotecario, que consta en el anverso, (denominado en adelante el “Asegurado”), está protegido por la Póliza de Seguro de Vida de Desgravamen arriba mencionada, de acuerdo a las siguientes Condiciones Particulares:
                        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                          <tr>
                            <td style="width:2%; font-weight:bold;">1.</td>
                            <td style="width:98%;">
                               <b>CONTRATANTE Y BENEFICIARIO A TÍTULO ONEROSO</b><br>
                               Fundación Diaconia - Fondo Rotativo de Inversión y Fomento
                            </td>
                          </tr>
                          <tr>
                            <td style="width:2%; font-weight:bold;" valign="top">2.</td>
                            <td style="width:98%;">
                               <b>COBERTURAS Y CAPITALES ASEGURADOS:</b><br>
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                    <td style="width: 3%;" valign="top">a.</td>
                                    <td style="width: 97%;">
                                      <b>Muerte por cualquier causa:</b><br>
                                      Capital Asegurado: Saldo insoluto de la deuda a la fecha del fallecimiento
                                      <table cellpadding="0" cellspacing="0" border="0" 
                                        style="width: 100%; font-size:100%;">
                                        <tr>
                                           <td style="width: 15%;">Límites de edad:</td>
                                           <td style="width: 15%;">De Ingreso:<br>De Permanencia</td>
                                           <td style="width: 70%;">Desde los 15 años hasta los 70	años (hasta un día 
                                           antes	de cumplir 71 años)<br>Máxima 70 años (hasta un día antes de 
                                           cumplir 71 años)</td>
                                        </tr>
                                      </table>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="width: 3%;" valign="top">b.</td>
                                    <td style="width: 97%;">
                                      <b>Incapacidad Total Permanente:</b>
                                      <table cellpadding="0" cellspacing="0" border="0" 
                                         style="width: 100%; font-size:100%;">
                                         <tr>
                                           <td style="width: 15%;">Límites de edad:</td>
                                           <td style="width: 15%;">De Ingreso:<br/>De Permanencia</td>
                                           <td style="width: 70%;">Desde los 15 años hasta los 65 años (hasta un	día 
                                           antes de cumplir 66	años)<br/>Hasta los 65 años (hasta un día antes de cumplir 
                                           66 años)</td>
                                         </tr>
                                      </table>  
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="width: 3%;" valign="top">c.</td>
                                    <td style="width: 97%;">
                                          <b>Sepelio: $us 300.00</b>
                                    </td>
                                  </tr>
                               </table>
                            </td>
                          </tr>
                          <tr>
                            <td style="width:2%; font-weight:bold;">3.</td>
                            <td style="width:98%;" align="left">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                 <tr>
                                   <td style="width:10%; font-weight:bold;">EXCLUSIONES:</td>
                                   <td style="width:90%;">-Para edades entre 15 a 49 años aplicable	solo para ereditar 
                                   mayores	a $us. 7.902.30</td>
                                 </tr>
                                 <tr>
                                   <td style="width:10%;"></td>
                                   <td style="width:90%;">-Para edades entre 50 a 64 años	aplicable	solo para ereditar 
                                   mayores	a $us.	5.747.13</td>
                                 </tr>
                                 <tr>
                                   <td style="width:10%;"></td>
                                   <td style="width:90%;">-Para edades entre 65 a 70 años	aplicable	solo para ereditar 
                                   mayores	a $us.	5.747.13</td>
                                 </tr>
                               </table>
                               Este seguro no será aplicable en ninguna de	las siguientes circunstancias:
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                <tr>
                                  <td style="width:2%;">a)</td>
                                  <td style="width:98%;">Si el asegurado participa como conductor o	acompañante en competencias de automóviles, motocicletas, lanchas de motor o avioneta, prácticas de paracaídas;</td>
                                </tr>
                                <tr>
                                  <td style="width:2%;">b)</td>
                                  <td style="width:98%;">Si el asegurado realiza operaciones o viajes	submarinos o en transportes aéreos no autorizados para transporte de pasajeros;</td>
                                </tr>
                                <tr>
                                  <td style="width:2%;">c)</td>
                                  <td style="width:98%;">Si el asegurado participa como elemento activo en guerra internacional o civil, rebelión, sublevación, guerrilla, motín, huelga, revolución y toda emergencia como consecuencia de alteración del orden público, a no ser que se pruebe que la muerte ocurrió independientemente de la existencia de tales condiciones anormales;</td>
                                </tr>
                                <tr>
                                  <td style="width:2%;">d)</td>
                                  <td style="width:98%;">Enfermedad grave pre-existente al inicio del seguro, o enfermedad congènita.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%;">e)</td>
                                  <td style="width:98%;">Suicidio o invalidez total y permanente como consecuencia del intento de suicidio practicados por el asegurado dentro de los primeros 6 meses de vigencia de su cobertura; en consecuencia, este riesgo quedará cubierto a partir del primer día del séptimo mes de la cobertura para cada operación asegurada.</td>
                                </tr>
                               </table>
                            </td>
                          </tr>
                          <tr>
                            <td style="width:2%; font-weight:bold;">4.</td>
                            <td style="width:98%; text-align:justify;">
                              <b>TASA MENSUAL:</b><br>
                              Tasa Total Mensual: 0.55 por mil mensual, ésta tasa puede variar de acuerdo al tipo de crédito, al riesgo particular que represente el asegurado y/o a las renovaciones futuras de la póliza.
                            </td>
                          </tr>
                          <tr>
                            <td style="width:2%; font-weight:bold;" valign="top">5.</td>
                            <td style="width:98%; text-align:justify;">
                              <b>PROCEDIMIENTO A SEGUIR EN CASO DE SINIESTRO:</b><br>
                              Para reclamar el pago de cualquier indemnización con cargo a esta póliza, el Contratante 
                              deberá remitir a la Compañía su solicitud junto con los documentos a presentar en caso de 
                              fallecimiento o invalidez. La Compañía podrá, a sus expensas, recabar informes o pruebas 
                              complementarias.<br>
                              Una vez recibidos los documentos a presentar en caso de fallecimiento o invalidez, la 
                              Compañía notificará dentro de los cinco (05) días siguientes, su conformidad o denegación del
                              pago de la indemnización, sobre la base de lo estipulado en las condiciones de la póliza 
                              matriz.<br>
                              En caso de conformidad, la Compañía satisfará la indemnización al Contratante y Beneficiario 
                              a título oneroso, dentro de los cinco (05) días siguientes al término del plazo anterior y 
                              contra la firma del finiquito correspondiente.
                            </td>
                          </tr>
                          <tr>
                            <td style="width:2%; font-weight:bold;" valign="top">6.</td>
                            <td style="width:98%; text-align:justify;">
                              <b>DOCUMENTOS A PRESENTAR EN CASO DE SINIESTRO<br> 
                              PARA MUERTE POR CUALQUIER CAUSA:</b>
                              <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Certificado de Defunción - Original.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Certificado de Nacimiento o Carnet de Identidad o Run o Libreta de Servicio Militar del asegurado. Fotocopia Simple</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Liquidación de cartera con el monto indemnizable</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Extracto de Crédito.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Contrato de préstamo - Fotocopia simple.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Certificado Médico único	de	Defunción - Fotocopia simple. Para edades entre 15 a 49 años y creditos mayores a $us. 7.902.30	-Para edades entre 50 a 70 años y creditos mayores a $us. 5.747.13 </td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Historial Clínica, si corresponde (Para casos de muerte natural) -Para edades entre 15 a 49 años y creditos mayores a $us. 7.902.30	-Para edades entre 50 a 70 años y creditos mayores a $us. 5.747.13 </td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Informe de la autoridad competente (Para casos de muerte accidental) -Para edades entre 15 a 49 años y creditos mayores a $us. 7.902.30 -Para edades entre 50 a 70 años y creditos mayores a $us. 5.747.13</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Declaración Jurada de Salud - Para edades entre 15 a 49 años y creditos mayores a $us. 7.902.30 - Para edades entre 50 a 70 años y creditos mayores a $us. 5.747.13</td>
                                </tr>
                                <tr>
                                  <td style="width:100%; text-align:left; font-weight:bold;" colspan="2">PARA EL PAGO DE 
                                  GASTOS DE SEPELIO</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Certificado de Nacimiento o Carnet de Identidad o Run del 
                                  Beneficiario (s) - Fotocopia simple.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Certificado de Defunción - Original.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Comprobante del pago al Beneficiario realizada por el Tomador.</td>
                                </tr>
                                <tr>
                                  <td style="width:100%; text-align:left; font-weight:bold;" colspan="2">PARA INVALIDEZ 
                                  TOTAL PERMANENTE:</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Certificado de Nacimiento o Carnet de Identidad o Run del 
                                  Asegurado. - Fotocopia simple.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Liquidación de cartera con el monto indemnizable.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Extracto de Crédito.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Contrato de préstamo o Comprobante de Desembolso - Fotocopia 
                                  simple.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Dictamen de Invalidez emitido por un médico calificador con 
                                  registro en la Autoridad de Fiscalización y Control de Pensiones y Seguros APS. Este 
                                  documento será gestionado por la aseguradora siempre y cuando se presente la 
                                  documentación médica requerida por la compañía.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Historial clínico O en SU defecto un informe médico- Para edades 
                                  entre 15 a 49 años y creditos mayores a $us. 7.902.30	-	Para edades entre 50 a 70 años 
                                  y creditos mayores a $us. 5.747.13</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Declaración Jurada de Salud- Para edades entre 15 a 49 años y 
                                  creditos mayores a $us. 7.902.30 - Para edades entre 50 a 70 años y creditos mayores a 
                                  $us. 5.747.13</td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                          <tr>
                            <td style="width:2%; font-weight:bold;" valign="top">7.</td>
                            <td style="width:98%; text-align:justify;">
                              <b>ADHESIÓN VOLUNTARIA DEL ASEGURADO</b><br>
                              El asegurado al momento de concretar el crédito con el Contratante, declara su consentimiento
                              voluntario para ser asegurado bajo la póliza arriba indicada. La indemnización en caso de 
                              siniestro será a favor del Contratante hasta el monto del saldo insoluto del crédito a la 
                              fecha del fallecimiento o a la fecha de dictamen de invalidez del asegurado.
                            </td>
                          </tr>
                          <tr>
                            <td style="width:2%; font-weight:bold;" valign="top">8.</td>
                            <td style="width:98%; text-align:justify;">
                              <b>CONTRATO PRINCIPAL (PÓLIZA MATRIZ)</b><br>
                              Todos los beneficios a los cuales tiene derecho el Asegurado, están sujetos a lo estipulado 
                              en las Condiciones Generales, Especiales y Particulares de la póliza matriz en virtud de la 
                              cual se regula el seguro de vida desgravamen,. La firma del asegurado en el documento de la 
                              Declaración Jurada de Salud, implica la expresa aceptación por su parte de todas las 
                              condiciones de la póliza matriz, tanto en lo que le beneficia como en lo que lo obliga, 
                              siempre y cuando se concrete el crédito y/o al momento de la aceptación por parte de la 
                              compañía aseguradora en los casos en los que corresponda.
                            </td>
                          </tr>
                          <tr>
                            <td style="width:2%; font-weight:bold;" valign="top">9.</td>
                            <td style="width:98%; text-align:justify;">
                               <b>COMPAÑÍA ASEGURADORA </b><br>
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                      <td style="width: 15%; text-align:left;" valign="top">Razón Social:</td>
                                      <td style="width: 40%; font-weight:bold;">
                                          NACIONAL VIDA SEGUROS DE PERSONAS S.A.
                                      </td>
                                      <td style="width: 15%;">&nbsp;
                                        
                                      </td>
                                      <td style="width: 15%;">&nbsp;
                                        
                                      </td>
                                      <td style="width: 15%;">&nbsp;
                                        
                                      </td>
                                  </tr>
                                  <tr>
                                      <td style="width: 15%; text-align:left;" valign="top">Dirección:</td>
                                      <td style="width: 40%; text-align:left;">
                                         Calle Capitán Ravelo No. 2334
                                      </td>
                                      <td style="width: 15%; text-align:left;">
                                        Teléfono: 2442942
                                      </td>
                                      <td style="width: 15%;">&nbsp;
                                        
                                      </td>
                                      <td style="width: 15%; text-align:left;">
                                        Fax : 2442905
                                      </td>
                                  </tr>
                              </table> 
                            </td>
                          </tr>
                          <tr>
                            <td style="width:2%; font-weight:bold;" valign="top">10.</td>
                            <td style="width:98%; text-align:justify;">
                               <b>CORREDOR DE SEGUROS</b><br>
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                      <td style="width: 15%; text-align:left;" valign="top">Razón Social:</td>
                                      <td style="width: 40%; text-align:left;">
                                          Génesis Brokers Ltda.
                                      </td>
                                      <td style="width: 15%;">&nbsp;
                                        
                                      </td>
                                      <td style="width: 15%;">&nbsp;
                                        
                                      </td>
                                      <td style="width: 15%;">&nbsp;
                                        
                                      </td>
                                  </tr>
                                  <tr>
                                      <td style="width: 15%; text-align:left;" valign="top">Dirección:</td>
                                      <td style="width: 40%; text-align:left;">
                                        Calle Femando Guachalla N° 369 2do Piso
                                      </td>
                                      <td style="width: 15%; text-align:left;">
                                        Teléfono: 244-0772
                                      </td>
                                      <td style="width: 15%;">&nbsp;
                                        
                                      </td>
                                      <td style="width: 15%; text-align:left;">
                                        Fax: 244-2824
                                      </td>
                                  </tr>
                               </table>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2" style="width:100%; text-align:justify; font-size:80%;">
                              <b>NOTA IMPORTANTE</b><br>
                              LA POLIZA MATRIZ SURTIRA SUS EFECTOS PARA EL SOLICITANTE QUIEN SE CONVERTIRA EN ASEGURADO A 
                              PARTIR DEL MOMENTO EN QUE EL CREDITO SE CONCRETE, SALVO EN LOS SIGUIENTES CASOS: A) QUE EL 
                              SOLICITANTE DEBA CUMPLIR CON OTROS REQUISITOS DE ASEGURABILIDAD ESTABLECIDOS EN LAS 
                              CONDICIONES DE LA POLIZA Y A REQUERIMIENTO DE LA COMPAÑIA ASEGURADORA. B) QUE EL SOLICITANTE 
                              HAYA RESPONDIDO POSITIVAMENTE ALGUNA DE LAS PREGUNTAS DE LA DECLARACION JURADA DE SALUD (CON 
                              EXCEPCION DE LA PREGUNTA 1). PARA AMBOS CASOS SE INICIARÁ LA COBERTURA DE SEGURO A PARTIR DE 
                              LA ACEPTACION DE LA COMPAÑIA
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2" style="width:100%;">
                              <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%; margin-top:20px;">
                                 <tr>
                                   <td style="width: 25%; text-align:center;">
                                    
                                   </td>
                                   <td style="width: 50%;">
                                      <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; 
                                        font-size:100%;">
                                        <tr>
                                         <td style="width: 25%;">
                                           <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; 
                                             font-size:100%;">
                                             <tr>
                                              <td style="width: 100%; border-bottom: 1px solid #333;">&nbsp;</td>
                                             </tr>
                                           </table>
                                         </td>
                                         <td style="width: 4%;">,</td>
                                         <td style="width: 13%;">
                                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; 
                                             font-size:100%;">
                                             <tr>
                                               <td style="width: 100%; border-bottom: 1px solid #333;">&nbsp;</td>
                                             </tr>
                                            </table>
                                         </td>
                                         <td style="width: 10%; text-align:center;">de</td>
                                         <td style="width: 25%;">
                                           <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; 
                                             font-size:100%;">
                                             <tr>
                                              <td style="width: 100%; border-bottom: 1px solid #333;">&nbsp;</td>
                                             </tr>
                                           </table>
                                         </td>
                                         <td style="width: 10%; text-align:center;">de</td>
                                         <td style="width: 13%;">
                                           <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; 
                                             font-size:100%;">
                                             <tr>
                                              <td style="width: 100%; border-bottom: 1px solid #333;">&nbsp;</td>
                                             </tr>
                                           </table>
                                         </td>
                                        </tr>
                                      </table>
                                   </td>
                                   <td style="width: 25%; text-align:center;">
                                  
                                   </td>
                                 </tr>
                                 <tr>
                                   <td style="width:25%;"></td>
                                   <td style="width:50%; text-align:center;">NACIONAL VIDA SEGUROS PERSONAS S.A.
                                   <br>FIRMAS AUTORIZADAS</td>
                                   <td style="width:25%;"></td>
                                 </tr>
                              </table>
                              <table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
                                <tr>
                                 <td style="width:30%; text-align:right;"><img src="<?=$url;?>img/firma-nv-1.jpg"/></td>
                                 <td style="width:40%;"></td>
                                 <td style="width:30%; text-align:left;"><img src="<?=$url;?>img/firma-nv-2.jpg"/></td>
                                </tr> 
                              </table>
                            </td>
                          </tr>
                        </table>  
                      </td>
                    </tr>
                 </table>     
            </div>  
<?php
	   }
       if ($fac === TRUE) {
		   $url .= 'index.php?ms='.md5('MS_DE').'&page='.md5('P_fac').'&ide='.base64_encode($row['id_emision']).'';
?>		
          <br/>
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
}elseif($_coverage === 'BC'){
	$k=0;
	while($rowcl=$rsDt->fetch_array(MYSQLI_ASSOC)){
		$k += 1;
		$rsCl_1 = json_decode($rowcl['respuesta'],TRUE);
?>        
        <div style="width: 775px; border: 0px solid #FFFF00; text-align:center;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-family: Arial;">
                <tr>
                  <td style="width:100%; text-align:left;">
                     <img src="<?=$url;?>images/<?=$row['logo_cia'];?>" height="60"/>
                  </td> 
                </tr>
                <tr>
                  <td style="width:100%; font-weight:bold; text-align:center; font-size: 80%;">
                     DECLARACIÓN JURADA DE SALUD Nº<br />SOLICITUD DE SEGURO DE DESGRAVAMEN HIPOTECARIO
                  </td> 
                </tr>
            </table>     
        </div>
        <br/>
        
        <div style="width: 775px; border: 0px solid #FFFF00;">
			<span style="font-weight:bold; font-size:75%;">
            Estimado Cliente, agradeceremos completar la información que se requiere a continuación: (utilice letra clara)<br>
            DATOS PERSONALES: (TITULAR 1):</span> 
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial; 
                padding-top:4px; padding-bottom:3px;">
                <tr> 
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                        <tr>
                          <td style="width:13%;">Nombres Completo: </td>
                          <td style="border-bottom: 1px solid #333; width:87%;">
                            <?=$rowcl['nombre_completo'];?>  
                          </td>
                        </tr>
                     </table>
                  </td>      
                </tr>
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:19%;">Lugar y Fecha de Nacimiento: </td>
                          <td style="border-bottom: 1px solid #333; width:81%;">
                              <?=$rowcl['lugar_nacimiento'].' '.$rowcl['fecha_nacimiento'];?>
                          </td>
                        </tr>
                      </table>                                  
                  </td>
                </tr>   
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:13%;">Carnet de Identidad: </td>
                          <td style="border-bottom: 1px solid #333; width:39%;">
                            <?=$rowcl['dni'].$rowcl['complemento'].$rowcl['extension'];?>  
                          </td>
                          <td style="width:4%;">Edad: </td>
                          <td style="width:11%; border-bottom: 1px solid #333; text-align:center;">
                              <?=$rowcl['edad'];?>
                          </td>
                          <td style="width:4%;">Peso: </td>
                          <td style="width:11%; border-bottom: 1px solid #333; text-align:center;">
                              <?=$rowcl['peso'];?>
                          </td>
                          <td style="width:6%;">Estatura: </td>
                          <td style="width:12%; border-bottom: 1px solid #333; text-align:center;">&nbsp;
                              <?=$rowcl['estatura'];?>
                          </td>   
                        </tr>
                      </table> 
                  </td>              
                </tr>
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                      <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                           <td style="width: 6%;">Dirección: </td>
                           <td style="width: 41%; border-bottom: 1px solid #333;">
                             <?=$rowcl['direccion'];?>
                           </td>
                           <td style="width: 6%;">Tel Dom: </td>
                           <td style="width: 15%; border-bottom: 1px solid #333;">
                             <?=$rowcl['telefono_domicilio'];?>
                           </td>
                           <td style="width: 7%;">Telf Oficina: </td>
                           <td style="width: 15%; border-bottom: 1px solid #333;">
                             <?=$rowcl['telefono_oficina'];?>
                           </td>
                         </tr>
                      </table> 
                  </td> 
                </tr>
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                          <td style="width:7%;">Ocupación: </td>
                          <td style="width:93%; border-bottom: 1px solid #333;">
                             <?=$rowcl['ocupacion'];?>
                          </td> 
                         </tr> 
                      </table>
                  </td>     
                </tr> 
            </table>
            <span style="font-weight:bold; font-size:75%;">DATOS PERSONALES: (TITULAR 2)</span>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial; 
                padding-top:4px; padding-bottom:3px;">
                <tr> 
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                        <tr>
                          <td style="width:13%;">Nombres Completo: </td>
                          <td style="border-bottom: 1px solid #333; width:87%;">&nbsp;
                              
                          </td>
                        </tr>
                     </table>
                  </td>      
                </tr>
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:19%;">Lugar y Fecha de Nacimiento: </td>
                          <td style="border-bottom: 1px solid #333; width:81%;">&nbsp;
                              
                          </td>
                        </tr>
                      </table>                                  
                  </td>
                </tr>   
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:13%;">Carnet de Identidad: </td>
                          <td style="border-bottom: 1px solid #333; width:39%;">&nbsp;
                              
                          </td>
                          <td style="width:4%;">Edad: </td>
                          <td style="width:11%; border-bottom: 1px solid #333;">&nbsp;
                              
                          </td>
                          <td style="width:4%;">Peso: </td>
                          <td style="width:11%; border-bottom: 1px solid #333;">&nbsp;
                              
                          </td>
                          <td style="width:6%;">Estatura: </td>
                          <td style="width:12%; border-bottom: 1px solid #333;">&nbsp;
                              
                          </td>   
                        </tr>
                      </table> 
                  </td>              
                </tr>
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                      <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                           <td style="width: 7%;">Dirección: </td>
                           <td style="width: 50%; border-bottom: 1px solid #333;">&nbsp;
                             
                           </td>
                           <td style="width: 7%;">Tel Dom: </td>
                           <td style="width: 14%; border-bottom: 1px solid #333;">&nbsp;
                             
                           </td>
                           <td style="width: 8%;">Telf Oficina: </td>
                           <td style="width: 14%; border-bottom: 1px solid #333;">&nbsp;
                             
                           </td>
                         </tr>
                      </table> 
                  </td> 
                </tr>
                <tr>
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                          <td style="width:7%;">Ocupación: </td>
                          <td style="width:93%; border-bottom: 1px solid #333;">&nbsp;
                             
                          </td> 
                         </tr> 
                      </table>
                  </td>     
                </tr> 
            </table>
            
            <span style="font-weight:bold; font-size:75%;">DEL CRÉDITO SOLICITADO:</span> 
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial; 
                padding-top:4px; padding-bottom:6px;">
               <tr><td colspan="3" style="width:100%; text-align:left;">Usted(es) solicita(n) el seguro de tipo:</td></tr>
               <tr><td style="width:100%; padding:3px;" colspan="3"></td></tr>
               <tr>
                  <td style="width:15%; text-align:left;">Individual</td>
                  <td style="width:6%;">
                    <div style="width: 25px; height: 12px; border: 1px solid #000; text-align:center;">
                      &nbsp;
                     </div> 
                  </td>
                  <td style="width:79%; text-align:left;">
                      si marca esta opción, sólo debe completar la información requerida al TITULAR 1
                  </td>
               </tr>
               <tr><td style="width:100%; padding:3px;" colspan="3"></td></tr>
               <tr>
                  <td style="width:15%; text-align:left;">Mancomunada</td>
                  <td style="width:6%;">
                    <div style="width: 25px; height: 12px; border: 1px solid #000; text-align:center;">
                      &nbsp;
                     </div> 
                  </td>
                  <td style="width:79%; text-align:left;">
                      si marca esta opción, sólo debe completar la información requerida al TITULAR 1 y TITULAR 2
                  </td>
               </tr>
               <tr>
                  <td style="width:100%; padding-top:8px;" colspan="3">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                          <td style="width:21%;">Monto Actual solicitado en <?=$row['moneda'];?>: </td>
                          <td style="width:29%; border-bottom: 1px solid #333;">
                             <?=$rowcl['monto_bc'];?>
                          </td>
                          <td style="width:21%;">Monto Actual Acumulado <?=$row['moneda'];?>: </td>
                          <td style="width:29%; border-bottom: 1px solid #333;">
                             <?=$rowcl['cumulo'];?>
                          </td>  
                         </tr> 
                      </table>
                  </td>
               </tr>
               <tr>
                  <td style="width:100%; padding-top:6px;" colspan="3">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                          <td style="width:18%;">Plazo del presente crédito: </td>
                          <td style="width:82%; border-bottom: 1px solid #333;">
                             <?=$row['plazo'].' '.$row['tipo_plazo'];?>
                          </td>  
                         </tr> 
                      </table>
                  </td>
               </tr>    
            </table>     
            
            <span style="font-weight:bold; font-size:75%;">CUESTIONARIO</span>
            <table 
               cellpadding="0" cellspacing="0" border="0" 
               style="width: 100%; height: auto; font-size: 75%; font-family: Arial;">
               <tr>
                  <td style="width:63%;"></td>
                  <td style="width:16%; text-align:center;" colspan="4">TITULAR 1</td>
                  <td style="width:5%;">&nbsp;</td>
                  <td style="width:16%; text-align:center;" colspan="4">TITULAR 2</td>
               </tr>
<?php
    $resp1_yes = $resp1_no = '';
    foreach ($row['questions'] as $key => $question) {
        if (count($rsCl_1) > 0) {
            $respCl = $rsCl_1[$question['orden']];
            if ($question['id_pregunta'] == $respCl['id']) {
                if ($respCl['value'] === 1) {
                    $resp1_yes = 'X';
                } elseif($respCl['value'] === 0) {
                    $resp1_no = 'X';
                }
            }
        }
?>              
            <tr>
                <td style="width:63%; text-align:left;">
                    <?=$question['orden'].' '.$question['pregunta'];?>
                </td>
                <td style="width:3%;">SI</td>
                <td style="width:5%;">
                    <div style="width: 20px; height: 12px; border: 1px solid #000; text-align:center;">
                        <?=$resp1_yes;?>
                    </div>
                </td>
                <td style="width:3%;">NO</td>
                <td style="width:5%;">
                    <div style="width: 20px; height: 12px; border: 1px solid #000; text-align:center;">
                        <?=$resp1_no;?>
                    </div>
                </td>
                <td style="width:5%;">&nbsp;</td>
                <td style="width:3%;">SI</td>
                <td style="width:5%;">
                    <div style="width: 20px; height: 12px; border: 1px solid #000; text-align:center;">
                        &nbsp;
                    </div>
                </td>
                <td style="width:3%;">NO</td>
                <td style="width:5%;">
                    <div style="width: 20px; height: 12px; border: 1px solid #000; text-align:center;">
                        &nbsp;
                    </div>
                </td>
            </tr>
<?php
    }
?>               
               
               
               <!--<tr><td colspan="10" style="width:100%; text-align:left; font-weight:bold;">DURANTE LOS ÚLTIMOS CINCO AÑOS:</td></tr>-->
               
               
               
            </table> 
            
            <span style="font-weight:bold; font-size:75%;">SI ALGUNA DE SUS RESPUESTAS ES AFIRMATIVA, FAVOR BRINDAR DETALLES:</span>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial; 
                padding-top:4px;">
               <tr>
                <td style="width:10%;">TITULAR 1: </td>
                <td style="width:90%; border-bottom: 1px solid #333;">
                  <?=$rowcl['observacion'];?>
                </td> 
               </tr>
               <tr><td colspan="2" style="width:100%; padding:3px;"></td></tr>
               <tr>
                <td style="width:10%;">TITULAR 2: </td>
                <td style="width:90%; border-bottom: 1px solid #333;">&nbsp;
                  
                </td> 
               </tr>  
            </table><br>
            <div style="font-size: 75%; text-align:justify;">  
                Declaro(amos) haber contestado con total veracidad, máxima buena fe a todas las preguntas del presente cuestionario y no haber omitido u ocultado hechos y/o circunstancias que hubiera podido influir en la celebración del contrato de seguro. Las declaraciones de salud que hacen anulable el Contrato de Seguros y en la que el asegurado pierde su derecho a indemnización, se enmarcan en los articulos 992, 993, 999 y 1038 del Código de Comercio.<br>
              Relevo expresamente del secreto profesional y legal, a cualquier médico que me hubiese asistido y/o tratado de dolencias y le autorizo (amos) a revelar a Nacional Vida Seguros de Personas S.A. todos los datos y antecedentes patológicos que pudiera (amos) tener o de los que hubiera (amos) adquirido conocimiento al prestarme sus servicios. Entiendo que de presentarse alguna eventualidad contemplada bajo la póliza de seguro como consecuencia de alguna enfermedad existente a la fecha de la firma de este documento o cuando haya alcanzado la edad límite estipulada en la póliza, la compañía aseguradora quedará liberada de toda la responsabilidad en lo que respecta a mí (nuestro) seguro. Finalmente, declaro (amos) conocer en su totalidad lo estipulado en mi (nuestra) póliza de seguro      
            </div><br>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial; 
                padding-top:4px;">
               <tr>
                <td style="width:10%;">Lugar y Fecha: </td>
                <td style="width:30%; border-bottom: 1px solid #333;">
                  <?=$row['u_departamento'].' '.$row['fecha_emision'];?>
                </td>
                <td style="width:7%;">Firma:</td>
                <td style="width:23%; border-bottom: 1px solid #333;">&nbsp;
                  
                </td>
                <td style="width:7%;">Firma:</td>
                <td style="width:23%; border-bottom: 1px solid #333;">&nbsp;
                  
                </td> 
               </tr>
               <tr>
                <td style="width:10%;"></td>
                <td style="width:30%; border-bottom: 0px solid #333;">&nbsp;
                  
                </td>
                <td style="width:7%;"></td>
                <td style="width:23%; text-align:center;">
                  TITULAR 1
                </td>
                <td style="width:7%;"></td>
                <td style="width:23%; text-align:center;">
                  TITULAR 2
                </td> 
               </tr>
            </table>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial; 
                padding-top:10px;">
               <tr>
                <td style="width:10%;">&nbsp;</td>
                <td style="width:28%; border-bottom: 0px solid #333;">&nbsp;
                  
                </td>
                <td style="width:7%;">Nombre:</td>
                <td style="width:25%; border-bottom: 1px solid #333;">
                  <?=$rowcl['nombre'].' '.$rowcl['paterno'].' '.$rowcl['materno'];?>
                </td>
                <td style="width:7%;">Nombre:</td>
                <td style="width:23%; border-bottom: 1px solid #333;">&nbsp;
                  
                </td> 
               </tr>
            </table> 
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial; 
                padding-top:20px;">
               <tr>
                <td style="width:10%;">No. de Crédito</td>
                <td style="width:35%; border-bottom: 1px solid #333;">&nbsp;
                  
                </td>
                <td style="width:25%;"></td>
                                
                <td style="width:30%; border-bottom: 1px solid #333;">&nbsp;
                  
                </td> 
               </tr>
               <tr>
                <td style="width:10%;"></td>
                <td style="width:35%; border-bottom: 0px solid #333;">&nbsp;
                  
                </td>
                <td style="width:25%;"></td>
                                
                <td style="width:30%; border-bottom: 0px solid #333; text-align:center;">
                  OFICIAL DE CRÉDITO<br>FIRMA Y SELLO
                </td> 
               </tr>
            </table>
            <div style="'width: 100%; height: auto; margin: 0 0 5px 0;">
<?php
           if((boolean)$row['facultativo']===true){
			   if((boolean)$row['aprobado']===true){
?>
                 <table border="0" cellpadding="1" cellspacing="0" style="width: 100%; font-size: 8px; font-weight: normal; font-family: Arial; margin: 2px 0 0 0; padding: 0; border-collapse: collapse; vertical-align: bottom;">
                    <tr>
                        <td colspan="7" style="width:100%; text-align: center; font-weight: bold; background: #e57474; color: #FFFFFF;">Caso Facultativo</td>
                    </tr>
                    <tr>
                        
                        <td style="width:5%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Aprobado</td>
                        <td style="width:5%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Tasa de Recargo</td>
                        <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Porcentaje de Recargo</td>
                        <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Tasa Actual</td>
                        <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Tasa Final</td>
                        <td style="width:69%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Observaciones</td>
                    </tr>
                    <tr>
                        
                        <td style="width:5%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=strtoupper($row['aprobado']);?></td>
                        <td style="width:5%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=strtoupper($row['tasa_recargo']);?></td>
                        <td style="width:7%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['porcentaje_recargo'];?> %</td>
                        <td style="width:7%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['tasa_actual'];?> %</td>
                        <td style="width:7%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['tasa_final'];?> %</td>
                        <td style="width:69%; text-align: justify; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['motivo_facultativo'];?> |<br /><?=$row['observacion'];?></td>
                    </tr>
               </table>
            
<?php
			   }else{
?>
                  <table border="0" cellpadding="1" cellspacing="0" style="width: 80%; font-size: 9px; border-collapse: collapse; font-weight: normal; font-family: Arial; margin: 2px 0 0 0; padding: 0; border-collapse: collapse; vertical-align: bottom;">         
                   <tr>
                      <td  style="text-align: center; font-weight: bold; background: #e57474; color: #FFFFFF;">
                        Caso Facultativo
                      </td>
                     </tr>
                     <tr>
                      <td style="text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">
                        Observaciones
                      </td>
                     </tr>
                     <tr>
                      <td style="text-align: justify; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$row['motivo_facultativo'];?></td>
                     </tr>
                </table>
<?php				   
			   
			   }
		   }
?>
            </div>
            
            <div style="'width: 100%; height: auto; margin: 0 0 5px 0;">
<?php
			 $queryVar = 'set @anulado = "Polizas Anuladas: ";';
			 if($link->query($queryVar,MYSQLI_STORE_RESULT)){
				 $canceled="select 
								max(@anulado:=concat(@anulado, prefijo, '-', no_emision, ', ')) as cert_canceled
							from
								s_de_em_cabecera
							where
								anulado = 1
									and id_cotizacion = '".$row['id_cotizacion']."';";
				 if($resp = $link->query($canceled,MYSQLI_STORE_RESULT)){
					 $regis = $resp->fetch_array(MYSQLI_ASSOC);
					 echo '<span style="font-size:8px;">'.trim($regis['cert_canceled'],', ').'</span>';
				 }else{
					 echo "Error en la consulta "."\n ".$link->errno. ": " . $link->error;
				 }
			 }else{
			   echo "Error en la consulta "."\n ".$link->errno. ": " . $link->error;   
			 }
?>
            </div>
            <div style="font-size: 60%; text-align:center; margin-top:20px;">  
                <b>NACIONAL VIDA SEGUROS DE PERSONAS S.A.</b>, SANTA CRUZ Tel. Piloto: (591-3) 371-6262 * Fax: (591-3) 371-6505<br>LA PAZ Tel. Piloto (591-2) 244-2942 * Fax: (591-2) 244-2905 - COCHABAMBA Tel. Piloto: (591-4) 445-7100 * Fax: (591-4 445-7104)<br>
                SUCRE Tel.Piloto (591-4) 642-5196 * Fax: (591-4) 642-5197-TARIJA Tel. (591-4) 666-6229 * Beni Tel/fax (591-3) 463-4109 * MONTERO Tel. (591-3) 922-6012<br>
                206-934901-2000 03 006-3012     
            </div>  	
        </div>            
<?php
        if($type!=='MAIL' && (boolean)$row['emitir']===true && (boolean)$row['anulado']===false){
?>        
            <page><div style="page-break-before: always;">&nbsp;</div></page>
            
            <div style="width: 775px; border: 0px solid #FFFF00;">
                <table 
                    cellpadding="0" cellspacing="0" border="0" 
                    style="width: 100%; height: auto; font-size: 70%; font-family: Arial;">
                    <tr>
                      <td>
                        <div style="text-align: center; ">
                           <strong>CERTIFICADO INDIVIDUAL DE SEGURO SEGURO DE VIDA DE DESGRAVAMEN N°</strong><br/>
                           Formato aprobado por la Autoridad de Fiscalización y Control de Pensiones y Seguros -APS 
                           mediante R.A No.081 del 10/03/00<br>
                           POLIZA DE SEGURO DE DESGRAVAMEN HIPORTECARIO N° POL-DH-LP-00103-2013-01<br>Codigo 206-934901-2000 03 006 4008
                        </div><br/>
                        NACIONAL VIDA Seguros de Personas S.A., (denominada en adelante la “Compañía “), por el presente CERTIFICADO INDIVIDUAL DE SEGURO hace constar que la persona nominada en la declaración jurada de salud / solicitud de seguro de desgravamen hipotecario, que consta en el anverso, (denominado en adelante el “Asegurado”), está protegido por la Póliza de Seguro de Vida de Desgravamen arriba mencionada, de acuerdo a las siguientes Condiciones Particulares:
                        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                          <tr>
                            <td style="width:2%; font-weight:bold;">1.</td>
                            <td style="width:98%;">
                               <b>CONTRATANTE Y BENEFICIARIO A TÍTULO ONEROSO</b><br>
                               Fundación Diaconia - Fondo Rotativo de Inversión y Fomento
                            </td>
                          </tr>
                          <tr>
                            <td style="width:2%; font-weight:bold;" valign="top">2.</td>
                            <td style="width:98%;">
                               <b>COBERTURAS Y CAPITALES ASEGURADOS:</b><br>
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                    <td style="width: 3%;" valign="top">a.</td>
                                    <td style="width: 97%;">
                                      <b>Muerte por cualquier causa:</b><br>
                                      Capital Asegurado: Saldo insoluto de la deuda a la fecha del fallecimiento
                                      <table cellpadding="0" cellspacing="0" border="0" 
                                        style="width: 100%; font-size:100%;">
                                        <tr>
                                           <td style="width: 15%;">Límites de edad:</td>
                                           <td style="width: 15%;">De Ingreso:<br>De Permanencia</td>
                                           <td style="width: 70%;">Desde los 15 años hasta los 70	años (hasta un día 
                                           antes	de cumplir 71 años)<br>Máxima 70 años (hasta un día antes de 
                                           cumplir 71 años)</td>
                                        </tr>
                                      </table>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="width: 3%;" valign="top">b.</td>
                                    <td style="width: 97%;">
                                      <b>Incapacidad Total Permanente:</b>
                                      <table cellpadding="0" cellspacing="0" border="0" 
                                         style="width: 100%; font-size:100%;">
                                         <tr>
                                           <td style="width: 15%;">Límites de edad:</td>
                                           <td style="width: 15%;">De Ingreso:<br/>De Permanencia</td>
                                           <td style="width: 70%;">Desde los 15 años hasta los 65 años (hasta un	día 
                                           antes de cumplir 66	años)<br/>Hasta los 65 años (hasta un día antes de cumplir 
                                           66 años)</td>
                                         </tr>
                                      </table>  
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="width: 3%;" valign="top">c.</td>
                                    <td style="width: 97%;">
                                          <b>Sepelio: $us 300.00</b>
                                    </td>
                                  </tr>
                               </table>
                            </td>
                          </tr>
                          <tr>
                            <td style="width:2%; font-weight:bold;">3.</td>
                            <td style="width:98%;" align="left">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                 <tr>
                                   <td style="width:10%; font-weight:bold;">EXCLUSIONES:</td>
                                   <td style="width:90%;">-Para edades entre 15 a 49 años aplicable	solo para ereditar 
                                   mayores	a $us. 7.902.30</td>
                                 </tr>
                                 <tr>
                                   <td style="width:10%;"></td>
                                   <td style="width:90%;">-Para edades entre 50 a 64 años	aplicable	solo para ereditar 
                                   mayores	a $us.	5.747.13</td>
                                 </tr>
                                 <tr>
                                   <td style="width:10%;"></td>
                                   <td style="width:90%;">-Para edades entre 65 a 70 años	aplicable	solo para ereditar 
                                   mayores	a $us.	5.747.13</td>
                                 </tr>
                               </table>
                               Este seguro no será aplicable en ninguna de	las siguientes circunstancias:
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                <tr>
                                  <td style="width:2%;">a)</td>
                                  <td style="width:98%;">Si el asegurado participa como conductor o	acompañante en competencias de automóviles, motocicletas, lanchas de motor o avioneta, prácticas de paracaídas;</td>
                                </tr>
                                <tr>
                                  <td style="width:2%;">b)</td>
                                  <td style="width:98%;">Si el asegurado realiza operaciones o viajes	submarinos o en transportes aéreos no autorizados para transporte de pasajeros;</td>
                                </tr>
                                <tr>
                                  <td style="width:2%;">c)</td>
                                  <td style="width:98%;">Si el asegurado participa como elemento activo en guerra internacional o civil, rebelión, sublevación, guerrilla, motín, huelga, revolución y toda emergencia como consecuencia de alteración del orden público, a no ser que se pruebe que la muerte ocurrió independientemente de la existencia de tales condiciones anormales;</td>
                                </tr>
                                <tr>
                                  <td style="width:2%;">d)</td>
                                  <td style="width:98%;">Enfermedad grave pre-existente al inicio del seguro, o enfermedad congènita.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%;">e)</td>
                                  <td style="width:98%;">Suicidio o invalidez total y permanente como consecuencia del intento de suicidio practicados por el asegurado dentro de los primeros 6 meses de vigencia de su cobertura; en consecuencia, este riesgo quedará cubierto a partir del primer día del séptimo mes de la cobertura para cada operación asegurada.</td>
                                </tr>
                               </table>
                            </td>
                          </tr>
                          <tr>
                            <td style="width:2%; font-weight:bold;">4.</td>
                            <td style="width:98%; text-align:justify;">
                              <b>TASA MENSUAL:</b><br>
                              Tasa Total Mensual: 0.55 por mil mensual, ésta tasa puede variar de acuerdo al tipo de crédito, al riesgo particular que represente el asegurado y/o a las renovaciones futuras de la póliza.
                            </td>
                          </tr>
                          <tr>
                            <td style="width:2%; font-weight:bold;" valign="top">5.</td>
                            <td style="width:98%; text-align:justify;">
                              <b>PROCEDIMIENTO A SEGUIR EN CASO DE SINIESTRO:</b><br>
                              Para reclamar el pago de cualquier indemnización con cargo a esta póliza, el Contratante 
                              deberá remitir a la Compañía su solicitud junto con los documentos a presentar en caso de 
                              fallecimiento o invalidez. La Compañía podrá, a sus expensas, recabar informes o pruebas 
                              complementarias.<br>
                              Una vez recibidos los documentos a presentar en caso de fallecimiento o invalidez, la 
                              Compañía notificará dentro de los cinco (05) días siguientes, su conformidad o denegación del
                              pago de la indemnización, sobre la base de lo estipulado en las condiciones de la póliza 
                              matriz.<br>
                              En caso de conformidad, la Compañía satisfará la indemnización al Contratante y Beneficiario 
                              a título oneroso, dentro de los cinco (05) días siguientes al término del plazo anterior y 
                              contra la firma del finiquito correspondiente.
                            </td>
                          </tr>
                          <tr>
                            <td style="width:2%; font-weight:bold;" valign="top">6.</td>
                            <td style="width:98%; text-align:justify;">
                              <b>DOCUMENTOS A PRESENTAR EN CASO DE SINIESTRO<br> 
                              PARA MUERTE POR CUALQUIER CAUSA:</b>
                              <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Certificado de Defunción - Original.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Certificado de Nacimiento o Carnet de Identidad o Run o Libreta de Servicio Militar del asegurado. Fotocopia Simple</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Liquidación de cartera con el monto indemnizable</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Extracto de Crédito.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Contrato de préstamo - Fotocopia simple.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Certificado Médico único	de	Defunción - Fotocopia simple. Para edades entre 15 a 49 años y creditos mayores a $us. 7.902.30	-Para edades entre 50 a 70 años y creditos mayores a $us. 5.747.13 </td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Historial Clínica, si corresponde (Para casos de muerte natural) -Para edades entre 15 a 49 años y creditos mayores a $us. 7.902.30	-Para edades entre 50 a 70 años y creditos mayores a $us. 5.747.13 </td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Informe de la autoridad competente (Para casos de muerte accidental) -Para edades entre 15 a 49 años y creditos mayores a $us. 7.902.30 -Para edades entre 50 a 70 años y creditos mayores a $us. 5.747.13</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Declaración Jurada de Salud - Para edades entre 15 a 49 años y creditos mayores a $us. 7.902.30 - Para edades entre 50 a 70 años y creditos mayores a $us. 5.747.13</td>
                                </tr>
                                <tr>
                                  <td style="width:100%; text-align:left; font-weight:bold;" colspan="2">PARA EL PAGO DE 
                                  GASTOS DE SEPELIO</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Certificado de Nacimiento o Carnet de Identidad o Run del 
                                  Beneficiario (s) - Fotocopia simple.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Certificado de Defunción - Original.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Comprobante del pago al Beneficiario realizada por el Tomador.</td>
                                </tr>
                                <tr>
                                  <td style="width:100%; text-align:left; font-weight:bold;" colspan="2">PARA INVALIDEZ 
                                  TOTAL PERMANENTE:</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Certificado de Nacimiento o Carnet de Identidad o Run del 
                                  Asegurado. - Fotocopia simple.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Liquidación de cartera con el monto indemnizable.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Extracto de Crédito.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Contrato de préstamo o Comprobante de Desembolso - Fotocopia 
                                  simple.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Dictamen de Invalidez emitido por un médico calificador con 
                                  registro en la Autoridad de Fiscalización y Control de Pensiones y Seguros APS. Este 
                                  documento será gestionado por la aseguradora siempre y cuando se presente la 
                                  documentación médica requerida por la compañía.</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Historial clínico O en SU defecto un informe médico- Para edades 
                                  entre 15 a 49 años y creditos mayores a $us. 7.902.30	-	Para edades entre 50 a 70 años 
                                  y creditos mayores a $us. 5.747.13</td>
                                </tr>
                                <tr>
                                  <td style="width:2%; font-weight:bold;" valign="top">-</td>
                                  <td style="width:98%;">Declaración Jurada de Salud- Para edades entre 15 a 49 años y 
                                  creditos mayores a $us. 7.902.30 - Para edades entre 50 a 70 años y creditos mayores a 
                                  $us. 5.747.13</td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                          <tr>
                            <td style="width:2%; font-weight:bold;" valign="top">7.</td>
                            <td style="width:98%; text-align:justify;">
                              <b>ADHESIÓN VOLUNTARIA DEL ASEGURADO</b><br>
                              El asegurado al momento de concretar el crédito con el Contratante, declara su consentimiento
                              voluntario para ser asegurado bajo la póliza arriba indicada. La indemnización en caso de 
                              siniestro será a favor del Contratante hasta el monto del saldo insoluto del crédito a la 
                              fecha del fallecimiento o a la fecha de dictamen de invalidez del asegurado.
                            </td>
                          </tr>
                          <tr>
                            <td style="width:2%; font-weight:bold;" valign="top">8.</td>
                            <td style="width:98%; text-align:justify;">
                              <b>CONTRATO PRINCIPAL (PÓLIZA MATRIZ)</b><br>
                              Todos los beneficios a los cuales tiene derecho el Asegurado, están sujetos a lo estipulado 
                              en las Condiciones Generales, Especiales y Particulares de la póliza matriz en virtud de la 
                              cual se regula el seguro de vida desgravamen,. La firma del asegurado en el documento de la 
                              Declaración Jurada de Salud, implica la expresa aceptación por su parte de todas las 
                              condiciones de la póliza matriz, tanto en lo que le beneficia como en lo que lo obliga, 
                              siempre y cuando se concrete el crédito y/o al momento de la aceptación por parte de la 
                              compañía aseguradora en los casos en los que corresponda.
                            </td>
                          </tr>
                          <tr>
                            <td style="width:2%; font-weight:bold;" valign="top">9.</td>
                            <td style="width:98%; text-align:justify;">
                               <b>COMPAÑÍA ASEGURADORA </b><br>
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                      <td style="width: 15%; text-align:left;" valign="top">Razón Social:</td>
                                      <td style="width: 40%; font-weight:bold;">
                                          NACIONAL VIDA SEGUROS DE PERSONAS S.A.
                                      </td>
                                      <td style="width: 15%;">&nbsp;
                                        
                                      </td>
                                      <td style="width: 15%;">&nbsp;
                                        
                                      </td>
                                      <td style="width: 15%;">&nbsp;
                                        
                                      </td>
                                  </tr>
                                  <tr>
                                      <td style="width: 15%; text-align:left;" valign="top">Dirección:</td>
                                      <td style="width: 40%; text-align:left;">
                                         Calle Capitán Ravelo No. 2334
                                      </td>
                                      <td style="width: 15%; text-align:left;">
                                        Teléfono: 2442942
                                      </td>
                                      <td style="width: 15%;">&nbsp;
                                        
                                      </td>
                                      <td style="width: 15%; text-align:left;">
                                        Fax : 2442905
                                      </td>
                                  </tr>
                              </table> 
                            </td>
                          </tr>
                          <tr>
                            <td style="width:2%; font-weight:bold;" valign="top">10.</td>
                            <td style="width:98%; text-align:justify;">
                               <b>CORREDOR DE SEGUROS</b><br>
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                      <td style="width: 15%; text-align:left;" valign="top">Razón Social:</td>
                                      <td style="width: 40%; text-align:left;">
                                          Génesis Brokers Ltda.
                                      </td>
                                      <td style="width: 15%;">&nbsp;
                                        
                                      </td>
                                      <td style="width: 15%;">&nbsp;
                                        
                                      </td>
                                      <td style="width: 15%;">&nbsp;
                                        
                                      </td>
                                  </tr>
                                  <tr>
                                      <td style="width: 15%; text-align:left;" valign="top">Dirección:</td>
                                      <td style="width: 40%; text-align:left;">
                                        Calle Femando Guachalla N° 369 2do Piso
                                      </td>
                                      <td style="width: 15%; text-align:left;">
                                        Teléfono: 244-0772
                                      </td>
                                      <td style="width: 15%;">&nbsp;
                                        
                                      </td>
                                      <td style="width: 15%; text-align:left;">
                                        Fax: 244-2824
                                      </td>
                                  </tr>
                               </table>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2" style="width:100%; text-align:justify; font-size:80%;">
                              <b>NOTA IMPORTANTE</b><br>
                              LA POLIZA MATRIZ SURTIRA SUS EFECTOS PARA EL SOLICITANTE QUIEN SE CONVERTIRA EN ASEGURADO A 
                              PARTIR DEL MOMENTO EN QUE EL CREDITO SE CONCRETE, SALVO EN LOS SIGUIENTES CASOS: A) QUE EL 
                              SOLICITANTE DEBA CUMPLIR CON OTROS REQUISITOS DE ASEGURABILIDAD ESTABLECIDOS EN LAS 
                              CONDICIONES DE LA POLIZA Y A REQUERIMIENTO DE LA COMPAÑIA ASEGURADORA. B) QUE EL SOLICITANTE 
                              HAYA RESPONDIDO POSITIVAMENTE ALGUNA DE LAS PREGUNTAS DE LA DECLARACION JURADA DE SALUD (CON 
                              EXCEPCION DE LA PREGUNTA 1). PARA AMBOS CASOS SE INICIARÁ LA COBERTURA DE SEGURO A PARTIR DE 
                              LA ACEPTACION DE LA COMPAÑIA
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2" style="width:100%;">
                              <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%; margin-top:20px;">
                                 <tr>
                                   <td style="width: 25%; text-align:center;">
                                    
                                   </td>
                                   <td style="width: 50%;">
                                      <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; 
                                        font-size:100%;">
                                        <tr>
                                         <td style="width: 25%;">
                                           <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; 
                                             font-size:100%;">
                                             <tr>
                                              <td style="width: 100%; border-bottom: 1px solid #333;">&nbsp;</td>
                                             </tr>
                                           </table>
                                         </td>
                                         <td style="width: 4%;">,</td>
                                         <td style="width: 13%;">
                                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; 
                                             font-size:100%;">
                                             <tr>
                                               <td style="width: 100%; border-bottom: 1px solid #333;">&nbsp;</td>
                                             </tr>
                                            </table>
                                         </td>
                                         <td style="width: 10%; text-align:center;">de</td>
                                         <td style="width: 25%;">
                                           <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; 
                                             font-size:100%;">
                                             <tr>
                                              <td style="width: 100%; border-bottom: 1px solid #333;">&nbsp;</td>
                                             </tr>
                                           </table>
                                         </td>
                                         <td style="width: 10%; text-align:center;">de</td>
                                         <td style="width: 13%;">
                                           <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; 
                                             font-size:100%;">
                                             <tr>
                                              <td style="width: 100%; border-bottom: 1px solid #333;">&nbsp;</td>
                                             </tr>
                                           </table>
                                         </td>
                                        </tr>
                                      </table>
                                   </td>
                                   <td style="width: 25%; text-align:center;">
                                  
                                   </td>
                                 </tr>
                                 <tr>
                                   <td style="width:25%;"></td>
                                   <td style="width:50%; text-align:center;">NACIONAL VIDA SEGUROS PERSONAS S.A.
                                   <br>FIRMAS AUTORIZADAS</td>
                                   <td style="width:25%;"></td>
                                 </tr>
                              </table>
                              <table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
                                <tr>
                                 <td style="width:30%;" align="right"><img src="../compania/firma-1.jpg"/></td>
                                 <td style="width:40%;"></td>
                                 <td style="width:30%;" align="left"><img src="../compania/firma-1.jpg"/></td>
                                </tr> 
                              </table>
                            </td>
                          </tr>
                        </table>  
                      </td>
                    </tr>
                 </table>     
            </div>  
<?php
		}
		
	}
	if ($k < $nCl) {
		echo'<page><div style="page-break-before: always;">&nbsp;</div></page>';
	}
	if ($fac === TRUE) {
		   $url .= 'index.php?ms='.md5('MS_DE').'&page='.md5('P_fac').'&ide='.base64_encode($row['id_emision']).'';
?>	
	       <br/>
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
}
?>
    </div>
</div>
<?php
    $html = ob_get_clean();
    return $html;
}
?>