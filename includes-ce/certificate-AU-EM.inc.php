<?php
function au_em_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
	$conexion = $link;
	
	$fuenteTXT = 'font-size: 9px;';		$widthMAIN = 'width: 795px;';	$marginCONTAINER = 'margin: 0 auto;';
	$fuenteDet = 'font-size: 8px;';
	
	//STYLE
	//	#container-c
	$cssContainer = 'width: 795px; height: auto; '.$marginCONTAINER.' padding: 0;';
	
	//	#main-c{
	$cssMain = $widthMAIN.' height: auto; margin: 0 auto; padding: 0; font-weight: normal; '.$fuenteTXT.' font-family: Arial;';
	
	//	.h1-c
	$cssH1 = 'font-weight: normal; '.$fuenteTXT.' font-family: Arial; text-align: center; margin: 0; padding: 0;';
	//	.h2-c
	$cssH2 = $cssH1.' font-weight: bold; text-align: left; margin: 0; padding: 0;';
	
	//	.content-c1
	$cssContent1 = 'width: 100%; font-weight: normal; '.$fuenteTXT.' font-family: Arial; margin: 0; padding: 0;';
	//	.content-c2
	$cssContent2 = $cssContent1.' margin: 0; padding: 2px 0;';
	
	//	.table-c
	$cssTable1 = 'font-weight: normal; '.$fuenteTXT.' font-family: Arial; width: 100%; margin: 0; padding: 0; border-collapse: collapse;';
		$cssTable1TrTd = 'vertical-align: bottom; padding: 1px 0;';
	
	//	.border-fc
	$cssBorderF = 'border: 1px solid #000000; ';
	//	.align-tc tr td{
	$cssAlignTop = 'vertical-align: top;';
	$cssBA = $cssBorderF.$cssAlignTop;
	
	//	.border-tc
	$cssBorderT = $cssBorderF.' border-top: 0 none;';
	$cssBA2 = $cssBorderF.$cssAlignTop.$cssBorderT;
	
	//	.border-bc tr td{
	$cssBorderB = 'border-bottom: 1px solid #000000;';
	
	//	.tittle-c
	$cssTittle = 'font-weight: bold; text-align: left; margin: 0; padding: 0;';
	
	//	.check-c
	$cssCheckDisplay = 'display: inline-block; #display: inline; _display: inline; zoom: 1; ';
	$cssCheck1 = 'border: 1px solid #000000; width: 10px; height: 8px; text-align: center; ';
	/*
	if($email == false){
		$cssCheck1 .= $cssCheckDisplay;
	}*/
	//	.check-c2{
	$cssCheck2 = 'margin-left: -3px; '.$cssCheck1;
	
	//	.datos-c{
	$cssDatos = 'display:block; margin: 0; padding: 1px 0 1px 5px; height: 10px;';
	
	//	.text-page-c{
	$cssTextPage = 'width: 50%; padding: 0 3px; text-align: justify; '.$fuenteTXT.' vertical-align: top;';
	
	//	.margin-top-cp{
	$cssMarginTop = 'margin-top: 3px;';
	
	//	.text-exclusion-c tr td{
	$cssTextExclusion = 'vertical-align: top; '.$fuenteTXT.'';
	
	//	.tab-c{
	$cssTab = 'margin-left: 30px; display: inline-block; #display: inline; _display: inline; zoom: 1;';
	
	$redimensionar = 'max-width: 55%; height: auto;';	
	ob_start();
?>
<div id="container-c" style="<?=$cssContainer;?>">
  <div id="main-c" style="<?=$cssMain;?>">
   <?php	
    $nrg=1;	
	$num_data=$rsDt->num_rows;
    while($regDt=$rsDt->fetch_array(MYSQLI_ASSOC)){ 
	  if(((boolean)$row['emitir']===false) || ( (boolean)$row['emitir']===true && (boolean)$regDt['aprobado']===true)){
	  
	  
	  if($nrg==1){ 
      ?>		
        <div style="<?=$cssContent2;?>">  
       <?php
	  }else{
	   ?>
          
        <div style="<?=$cssContent2;?> margin-top: 50px;">
       <?php   
	  }
	   ?>    
          <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
               <tr>
                 <td style="width:25%;">
                    <img src="<?=$url;?>images/<?=$row['logo_ef'];?>" width="100"/>
                 </td>
                 <td style="width:50%;" valign="bottom">
                   <div style="font-size:14px; font-weight:bold; text-align:center;">Solicitud de Póliza de Seguro de Automotores - BNB</div>
                 </td>
                 <td style="width:25%; text-align:right;">
                    <img src="<?=$url;?>images/<?=$row['logo_cia'];?>" width="100"/>
                 </td>
               </tr>
           </table>
          <br/>
           <div style="width:100%; margin: 7px 0; font-size:85%; text-align:right;">
              Código de Registro SPVS. 101-910159-2006 10 200-3001
           </div>
          <br/>
    
          
         <div style="width: auto;	height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; font-size:10px;">1. Datos del Titular:</div><br/>
         <table cellpadding="0" cellspacing="0" style="width:100%; font-size:9px;">
            <tr>
              <td style="width:10%;">Nombre:</td>
              <td style="width:22%; text-align:center;"><?=$row['paterno']?></td>
              <td style="width:22%; text-align:center;"><?=$row['materno']?></td>
              <td style="width:22%; text-align:center;"><?=$row['nombre']?></td>
              <td style="width:23%; text-align:center;"><?=$row['ap_casada']?></td>
            </tr>
            <tr>
              <td style="width:10%;"></td>
              <td style="width:22%; border-top:#000 solid 1px; text-align:center;">Apellido Paterno</td>
              <td style="width:22%; border-top:#000 solid 1px; text-align:center;">Apellido Materno</td>
              <td style="width:22%; border-top:#000 solid 1px; text-align:center;">Nombres</td>
              <td style="width:23%; border-top:#000 solid 1px; text-align:center;">Apellido de Casada</td> 
            </tr>
            <tr><td colspan="5">&nbsp;</td></tr>
            <tr>
             <td style="width:10%;">Direccion:</td>
             <td colspan="2" style="width:44%; text-align:center;"><?=$row['direccion']?></td>
             <td style="width:22%; text-align:center;"><?=$row['no_domicilio']?></td>
             <td style="width:23%; text-align:center;"><?=$row['localidad']?></td>
            </tr>
            <tr>
             <td style="width:10%;">&nbsp;</td>
             <td colspan="2" style="width:44%; border-top:#000 solid 1px; text-align:center;">Avenida o Calle</td>
             <td style="width:22%; border-top:#000 solid 1px; text-align:center;">Número</td>
             <td style="width:23%; border-top:#000 solid 1px; text-align:center;">Ciudad o Localidad</td>
            </tr>
            <tr><td colspan="5">&nbsp;</td></tr>
            <tr>
              <td style="width:10%;">Teléfono:</td>
              <td style="width:22%; text-align:center;"><?=$row['telefono_domicilio']?></td>
              <td style="width:22%; text-align:center;"><?=$row['telefono_oficina']?></td>
              <td style="width:22%; text-align:center;"><?=$row['telefono_celular']?></td>
              <td style="width:23%;"></td>
            </tr>
            <tr>
              <td style="width:10%;"></td>
              <td style="width:22%; border-top:#000 solid 1px; text-align:center;">Domicilio</td>
              <td style="width:22%; border-top:#000 solid 1px; text-align:center;">Oficina</td>
              <td style="width:22%; border-top:#000 solid 1px; text-align:center;">Celular</td>
              <td style="width:23%;">&nbsp;</td> 
            </tr>
         </table>
         <br/>    
       
         <?php		   
               echo'<div style="width: auto; height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; font-size:10px;">2. Datos del Vehículo:</div><br/>';
               
               $usovehi = formatCheckTVH($regDt['uso_vehiculo']);
               $traccion = formatCheckTVH($regDt['traccion']);
              
               $select2="select
                            id_tipo_vh,
                            id_ef,
                            vehiculo
                          from
                            s_au_tipo_vehiculo
                          where
                            id_ef='".$row['idef']."';";
               $res2=$conexion->query($select2,MYSQLI_STORE_RESULT);
               $num_regi=$res2->num_rows;
               if ($num_regi%2==0){
                    //no hacemos nada es par
               }else{
                  //sumamos uno para que sea par
                  $num_regi=$num_regi+1;	
               }
               
               $cc=1; $vec=array();
               while($regi2=$res2->fetch_array(MYSQLI_ASSOC) or $cc<=$num_regi){
                   if($regi2['id_tipo_vh']!=''){
                      $vec[$cc]=$regi2['id_tipo_vh'].'|'.$regi2['vehiculo'];
                   }else{
                      $vec[$cc]='';
                   }
                   $cc++;
               }
               
               //ARMAMOS LA TABLA CON LOS DATOS
               $i=1; $k=1; $fila=1; $vh=array();
               
                echo'<table border="0" cellpadding="0" cellspacing="0" style="width:100%; font-size:9px;">
                      <tr>
                        <td style="width:10%; text-align:left;" valign="top">Tipo de Vehiculo</td>
                        <td style="width:90%;">';
                           echo'<table style="width:100%; font-size:9px;" border="0" cellpadding="0" cellspacing="0">';
                                 while($i<=$num_regi){
                                     if($fila==1){
                                         echo'<tr>';
                                         $fila++;
                                     }
                                     $j=1;
                                     while($j<=4){
                                        if($vec[$k]!=''){
                                            $vh=explode('|',$vec[$k]);
                                            echo'<td style="width:25%;">
                                                   <table style="width:100%;" border="0" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                          <td style="width: 10%;">';
                                                          if($regDt['id_tipo_vh']==$vh[0]){
                                                            echo'<div style="'.$cssCheck1.'">X</div>';
                                                          }else{
                                                            echo'<div style="'.$cssCheck1.'"></div>';  
                                                          }
                                                     echo'</td>
                                                          <td style="width: 90%; text-align: left;">&nbsp;'.$vh[1].'</td>
                                                        </tr>
                                                    </table>
                                                 </td>';
                                        }else{
                                           echo'<td style="width:25%;">&nbsp;</td>';     
                                        }
                                         $j++; $k++; 
                                     }
                                     //$k--;
                                     $i=$k;
                                     if($fila!=1){
                                         echo'</tr>';
                                         $fila=1;
                                     }
                                     /*if($fila==1){
                                         echo'<tr>';
                                         $fila++;
                                     }
                                     if($k<=4){
                                         if($vec[$i]!=''){
                                            $vh=explode('|',$vec[$i]);  
                                            echo'<td style="width:25%;">
                                                   <table style="width:100%;" border="0" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                          <td style="width: 10%;">';
                                                          if($regDt['id_tipo_vh']==$vh[0]){
                                                            echo'<div style="'.$cssCheck1.'">X</div>';
                                                          }else{
                                                            echo'<div style="'.$cssCheck1.'"></div>';  
                                                          }
                                                     echo'</td>
                                                          <td style="width: 90%; text-align: left;">&nbsp;'.$vh[1].'</td>
                                                        </tr>
                                                    </table>
                                                 </td>';
                                         }else{
                                            echo'<td style="width:25%;">&nbsp;</td>';   
                                         }
                                         $k++;
                                     }else{
                                         $k=1;
                                         $i--;
                                         if($fila!=1){
                                             echo'</tr>';
                                             $fila=1;
                                         }
                                     }*/
                                     $i++;
                                 }
                           echo'</table>
                        </td>
                      </tr>
                     </table><br/>';       	  
             ?>
            
             <table style="<?=$cssTable1;?>" border="0" cellpadding="1" cellspacing="0">
               <tr>
                 <td style="width:10%; font-weight:bold;">Marca:</td>
                 <td style="width:15%;"><?=$regDt['marca'];?></td>
                 <td style="width:10%; font-weight:bold;">Modelo:</td>
                 <td style="width:15%;"><?=$regDt['modelo'];?></td>
                 <td style="width:10%; font-weight:bold;">Año:</td>
                 <td style="width:15%;"><?=$regDt['anio'];?></td>
                 <td style="width:10%; font-weight:bold;">Placa:</td>
                 <td style="width:15%;"><?=$regDt['placa'];?></td>
               </tr>
               <tr><td colspan="8">&nbsp;</td></tr>
               <tr>
                 <td style="width:10%; font-weight:bold;">Chasis No.:</td>
                 <td style="width:15%;"><?=$regDt['chasis'];?></td>
                 <td style="width:10%; font-weight:bold;">Motor No.:</td>
                 <td style="width:15%;"><?=$regDt['motor'];?></td>
                 <td style="width:10%; font-weight:bold;">Color:</td>
                 <td style="width:15%;"><?=$regDt['color'];?></td>
                 <td style="width:10%;">&nbsp;</td>
                 <td style="width:15%;">&nbsp;</td>
               </tr>
             </table><br/>
             <table style="width:90%; font-size:9px;" border="0" cellpadding="1" cellspacing="0">
                <tr>
                  <td style="width:15%;">Uso del Vehiculo:</td>
                  <td style="width:15%; text-align:left;">
                     <table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                              <td style="width: 90%; text-align: right;">Publico &nbsp;</td>
                              <td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$usovehi[0];?></div></td>
                          </tr>
                      </table>
                  </td>
                  <td style="width:15%;">
                     <table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                              <td style="width: 90%; text-align: right;">Privado &nbsp;</td>
                              <td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$usovehi[1];?></div></td>
                          </tr>
                      </table>
                  </td>
                  <td style="width:15%; text-align:right;">Traccion:</td>
                  <td style="width:15%;">
                     <table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                              <td style="width: 90%; text-align: right;">4x2 &nbsp;</td>
                              <td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$traccion[0];?></div></td>
                          </tr>
                      </table>
                  </td>
                  <td style="width:15%;">
                    <table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                              <td style="width: 90%; text-align: right;">4x4 &nbsp;</td>
                              <td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$traccion[1];?></div></td>
                          </tr>
                      </table>
                  </td> 
                </tr>
                <tr><td colspan="6">&nbsp;</td></tr>
                <tr>
                  <td style="width:15%;">Vehiculo Pesado:</td>
                  <td style="width:15%;">
                     <table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                              <td style="width: 90%; text-align: right;">&nbsp;</td>
                              <td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$traccion[2];?></div></td>
                          </tr>
                      </table>
                  </td>
                  <td style="width:15%;">&nbsp;</td>
                  <td style="width:15%;">&nbsp;</td>
                  <td style="width:15%;">&nbsp;</td>
                  <td style="width:15%;">&nbsp;</td> 
                </tr>
             </table><br/>
             
             <div style="width: auto;	height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; font-size:10px;">3. Valor Total Asegurado</div><br/>
             
              <table style="width:80%; font-size:9px;" border="0" cellpadding="1" cellspacing="0">
               <tr>
                 <td rowspan="2" style="width:20%;">USD&nbsp;<?=number_format($regDt['valor_asegurado'],2,".",",");?></td>
                 <td style="width:60%;">(Valor Comercial del Vehiculo según Avalúo Técnico o nota de Entrga de la Casa Importadora)</td>
               </tr>
               <tr>
                 <td style="width:60%;">(El valor podrá ser referencial, pero debe ser reconfirmado para la emisión de la Póliza)</td>
               </tr>
             </table>
            <br/>
         
            <div style="width: auto;	height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; font-size:10px;">4. Tasas Anuales</div><br/>
          <table border="0" cellpadding="0" cellspacing="0" style="width:60%; font-size:9px;">
            <tr>
              <td style="width:10%;" valign="top">2,50%</td>
              <td style="width:50%;">Las tasas de las polizas de vigencia multianual incluirán además un descuento por vigencia de largo plazo</td>
            </tr>
            <tr>
              <td style="width:10%;">&nbsp;</td>
              <td style="width:15%;">Prima mínima:</td>
              <td style="width:35%;">USD 150.- para vehículos 4x2 y livianos</td> 
            </tr>
            <tr>
              <td style="width:10%;">&nbsp;</td>
              <td style="width:15%;">&nbsp;</td>
              <td style="width:35%;">USD 200.- para vehículos 4x4 o pesados</td> 
            </tr>
          </table><br/>
          
           <div style="width: auto;	height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; font-size:10px;">5. Forma de Pago</div><br/>
           <table border="0" cellpadding="0" cellspacing="0" style="width:75%; font-size:85%;">
             <tr>
               <td style="width:15%;">&nbsp;</td>
               <td style="width:45%; text-align:left;">
                 <b>Pólizas con vigencia multianual</b><br/>
                 <b>a.</b><span style="margin-left:10px;">Pago Total Anticipado al contado de la prima, financiada por el asegurado.</span><br/> 
                 <b>b.</b><span style="margin-left:10px;">Pago Total Anticipado al contado de la prima, financiada por el asegurado.</span><br/> 
                 <b>c.</b><span style="margin-left:10px;">Pago Combinado.</span> 
               </td>
               <td style="width:15%; text-align:left;">
                 <b>Pólizas Anuales</b><br/>
                 <b>a.</b>&nbsp;Pago al Contado
               </td> 
             </tr>
            </table><br/>
            <?php
             if($regDt['facultativo']==1){
				 if((boolean)$regDt['aprobado']===true){
			?>
           		 <table border="0" cellpadding="1" cellspacing="0" style="width: 100%; font-size: 85%; border-collapse: collapse;">
                    <tr>
                        <td colspan="7" style="width:100%; text-align: center; font-weight: bold; background: #e57474; color: #FFFFFF;">Caso Facultativo</td>
                    </tr>
                    <tr>
                        
                        <td style="width:5%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Aprobado</td>
                        <td style="width:5%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Tasa de Recargo</td>
                        <td style="width:15%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Porcentaje de Recargo</td>
                        <td style="width:15%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Tasa Actual</td>
                        <td style="width:15%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Tasa Final</td>
                        <td style="width:45%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Observaciones</td>
                    </tr>
                    <tr>
                        
                        <td style="width:5%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=strtoupper($regDt['vh_aprobado']);?></td>
                        <td style="width:5%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=strtoupper($regDt['vh_tasa_recargo']);?></td>
                        <td style="width:15%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$regDt['vh_porcentaje'];?> %</td>
                        <td style="width:15%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$regDt['vh_tasa_actual'];?> %</td>
                        <td style="width:15%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$regDt['vh_tasa_final'];?> %</td>
                        <td style="width:45%; text-align: justify; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$regDt['motivo_facultativo'];?> |<br /><?=$regDt['vh_observacion'];?></td>
                    </tr>
               </table>
            <?php
				 }else{
			?>		<table border="0" cellpadding="1" cellspacing="0" style="width: 80%; font-size: 85%; border-collapse: collapse;">         
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
                        
                        
                        <td style="text-align: justify; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$regDt['motivo_facultativo'];?></td>
                    </tr>
                    </table>
			
            <?php 		 
				 }
			 }
			?>
            <table border="0" cellpadding="0" cellspacing="0" style="width:100%; margin-top:40px;">
              <tr>
                <td style="width:50%; text-align:left;">
                   <table border="0" cellpadding="0" cellspacing="0" style="width:70%; font-size:10px;">
                      <tr><td style="width:70%; border-bottom:#000 solid 1px;"></td></tr>
                      <tr><td style="width:70%; text-align:center;">Firma del titular Asegurado</td></tr>
                   </table>
                </td>
                <td style="width:50%;">&nbsp;</td>
              </tr>
              <tr>
                <td style="width:50%; text-align:left">
                   <table border="0" cellpadding="0" cellspacing="0" style="width:70%; font-size:9px; margin-top:40px;">
                      <tr><td style="width:10%;">C.I.</td><td style="width:60%; border-bottom:#000 solid 1px;"><?=$row['ci']?></td></tr>
                   </table>
                </td>
                <td style="width:50%;">
                   <table border="0" cellpadding="0" cellspacing="0" style="width:75%; font-size:9px; margin-top:20px;" align="right">
                   <?php
                     if((boolean)$row['emitir']===true){
				   ?>
                      <tr><td style="width:25%;">Lugar y fecha,</td><td style="width:50%; border-bottom:#000 solid 1px;"><?=$row['departamento'].' - '.date('d', strtotime($row['fecha_emision'])).'/'.date('m', strtotime($row['fecha_emision'])).'/'.date('Y', strtotime($row['fecha_emision']));?></td></tr>
                      
                    <?php
					 }else{
					?>
                       <tr><td style="width:25%;">Lugar y fecha,</td><td style="width:50%; border-bottom:#000 solid 1px;">&nbsp;</td></tr>
                    <?php
                     }
					?>  
                   </table>
                </td>
              </tr>
            </table>
        </div>
	 <?php
	   if($num_data>1){
     ?>
        <div style="page-break-after: always;">&nbsp;</div>
     <?php
	   }
	   $nrg++;
	  }
    }
   ?>
     
     <div style="page-break-after: always;">&nbsp;</div>
     
     <div style="<?=$cssContent2;?> margin-top: 50px; <?=$fuenteDet;?>">
       <table style="<?=$cssTable1;?> margin-top: 5px; <?=$fuenteDet;?>" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="<?=$cssTextPage;?> <?=$fuenteDet;?>">
						<b style="<?=$cssMarginTop;?>">6. Riesgos Cubiertos:</b><br/>
                        Hasta el Valor Comercial del Vehículo<br/>
                        <table style="<?=$cssTable1;?> <?=$cssTextExclusion?> <?=$fuenteDet;?>" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Pérdida Total:<br/>
                                  - Por Accidente al 100%<br/>
                                  - Por Robo al 80%
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Responsabilidad Civil como límite simple y combinado, Hasta $us. 10,000.-
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Responsabilidad civil de Ocupantes hasta $us. 5.000.- por persona
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Responsabilidad Consecuencial hasta $us. 2.000.- por vehículo
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Daños Propios, Huelgas, Conmoción Civil, Daño Malicioso, Vandalismo, Sabotaje y Terrorismo c/Franquicia por evento y/o reclamo de:
                                 - $us 50.- para certificados o pólizas emitidas en La Paz, Santa Cruz y Cochabamba, y
                                 - $us 25.- para certificados o pólizas emitidas en el resto del país. 
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Robo Parcial al 80%.
								</td>
							</tr>
							<tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Accidentes Personales hasta 5 ocupantes: con Coberturas de Muerte Accidental y/o Invalidez Total y/o parcial Permanente Hasta $us. 10.000.- por ocupante (Para Vehículos de Uso Particular).</td>
							</tr>
                            <tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Accidentes Personales con Cobertura Para Muerte Accidental y/o Invalidez Total y/o Parcial Permanente Hasta $us. 2.500.- por ocupante, y Gastos Médicos Hasta $us. 500.- por ocupante (Para Vehículos de Uso Público).</td>
							</tr>
                            <tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Accesosrios Hasta $us. 500.-
								</td>
							</tr>
                            <tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Gastos de Sepelio $us. 500.- por persona
								</td>
							</tr>
						</table>
                        La Compñía Aseguradora podrá extender la cobertura de Pérdida Total por robo al 100% siempre y cuando el Asegurado pague la extraprima que la Compañía Aseguradora exija, caso contrario la cobertura quedará reducida automáticamente hasta el 80%. 
						<br />
						<b style="<?=$cssMarginTop;?>">7. Franquicia:</b><br/>
						Daños Propios, Huelgas, Conmoción civil, Daño Malicioso, Vandalismo, Sabotaje y Terrorismo c/Franquicia por evento y/o reclamo de:
                          <table style="<?=$cssTable1;?> <?=$cssTextExclusion?> <?=$fuenteDet;?>" border="0" cellspacing="0" cellpadding="0">
                              <tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">$us. 50.- para certificados o pólizas emitidas en La Paz, Santa Cruz y Cochabamba, y
								</td>
							  </tr>
                              <tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">$us. 25.- para certificados o pólizas emitidas en el resto del país
								</td>
							  </tr>
                          </table>
						<br/>
                        <b style="<?=$cssMarginTop;?>">8. Coaseguro:</b><br/>
                          <table style="<?=$cssTable1;?> <?=$cssTextExclusion?> <?=$fuenteDet;?>" border="0" cellspacing="0" cellpadding="0">
                              <tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Pérdida total por robo: 20% por evento y/o reclamo.
								</td>
							  </tr>
                              <tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Robo de partes o piezas: 20% por evento y/o reclamo.
								</td>
							  </tr>
                          </table>
                        <br/>
                        <b style="<?=$cssMarginTop;?>">9. Cláusulas Adicionales:</b><br/>
                          <table style="<?=$cssTable1;?> <?=$cssTextExclusion?> <?=$fuenteDet;?>" border="0" cellspacing="0" cellpadding="0">
                              <tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Eleigibilidad de talleres.
								</td>
							  </tr>
                              <tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Ampliación de aviso de siniestro hasta 10 días.
								</td>
							  </tr>
                              <tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Daños a causa de la naturaleza.
								</td>
							  </tr>
                              <tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Tránsito en vías no autorizadas.
								</td>
							  </tr>
                              <tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Altas y bajas (inclusiones y exclusiones).
								</td>
							  </tr>
                              <tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Rescisión de contrato a prorrata.
								</td>
							  </tr>
                              <tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Eliminación de la denuncia policial, y presentación de la copia legalaizada de la denuncia, excepto para casos de Responsabilidad Civil y Pérdida Total por Robo.
								</td>
							  </tr>
                              <tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Flete aéreo.
								</td>
							  </tr>
                              <tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Rehabilitación automática de la suma asegurada.
								</td>
							  </tr>
                              <tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Responsabilidad Civil de Ocupantes.
								</td>
							  </tr>
                              <tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Responsabilidad Civil consecuencial.
								</td>
							  </tr>
                              <tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Depreciación anual del 10% (En pólizas con vigencia mayor a un año).
								</td>
							  </tr>
                              <tr>
								<td style="width:3%;" align="left" valign="top">&bull;</td>
								<td style="width:96%;">Subrogación de derechos a favor del Banco Nacional de Bolivia S.A. hasta el valor del saldo a capital del crédito, a la fecha de siniestro.
								</td>
							  </tr>
                          </table><br/>
                          
                        <b style="<?=$cssMarginTop;?>">Beneficios Automáticos:</b><br/>
                        <table style="<?=$cssTable1;?> <?=$cssTextExclusion?> <?=$fuenteDet;?>" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td style="width:3%;" align="left" valign="top">&bull;</td>
                          <td style="width:96%;"><b>Auxilio Mecánico:</b> anivel nacional las 24 hrs., incluyendo:<br/>
                          - Auxilio de Grúa (Reembolso de gastos de uso de grúa hasta USD 350.- al año, en uno o más eventos)<br/>
                          - Call Center las 24 horas del día (800102727)<br/>
                          - Servicios y descuentos en Talleres TOP.<br/>
                          - Servicios de carga de batería.<br/>
                          </td>
                        </tr>
                        </table>
                        
						</td>
						<td style="<?=$cssTextPage;?> <?=$fuenteDet;?>">
						    <table style="<?=$cssTable1;?> <?=$cssTextExclusion?> <?=$fuenteDet;?>" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td style="width:3%;" align="left" valign="top">&nbsp;</td>
                                  <td style="width:96%;">
                                  - Servicios de inflado y/o recambio de neumáticos.<br/>
                                  - Auxilio en cerrajería del vehículo.<br/>
                                  - Auxilio mecánico básico.<br/>
                                  - Abastecimiento de combustible.<br/>
                                  -Depósito y custodia del vehículo reparado, en caso de que el accidente o avería ocurra fuera del domicilio habitual del asegurado, hasta 10 días hábiles.<br/>
                                  - Transmisión de mensajes urgentes mediante el Call Center 800102727.
                                  </td>
                                </tr>
                                
                                <tr>
                                  <td style="width:3%;" align="left" valign="top">&bull;</td>
                                  <td style="width:96%;"><b>Servicio de Asistencia Jurídica en caso de siniestro cubierto:</b> , que incluye:
                                  - Asistencia a Audiencias de Tránsito
                                  - Preparación y presentación de Memoriales.
                                  - Servicios y descuentos en Talleres TOP.
                                  - Asistencia a Audiencias de Conciliación.
                                  
                                  </td>
                                </tr>
                                <tr>
                                  <td style="width:3%;" align="left" valign="top">&bull;</td>
                                  <td style="width:96%;"><b>Extraterritorialidad:</b> Gratuita, durante la vigencia de la cobertura individual, siempre y cuando el Asegurado se encuentre al día en sus pagos.
                                  
                                  </td>
                                </tr>
                                </table>
                                <br/>
                                 
                                <b style="<?=$cssMarginTop;?>">10. Requisitos de asegurabilidad:</b><br/>
                                <table style="<?=$cssTable1;?> <?=$cssTextExclusion?> <?=$fuenteDet;?>" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                      <td style="width:3%;" align="left" valign="top">&bull;</td>
                                      <td style="width:96%;">Inspección de pre-riesgo, a realizarse por funcionarios de la Compañía Aseguradora dentro de las siguientes 48 horas de haberse realizado el requerimiento, siempre y cuando el vehículo asegurado ya cuente con recorrido. Vehículos 0 Km: No será requerimiento la inspección pre-riesgo, siempre y cuando exista una factura comercial emitida por una casa importadora autorizada.
                                      
                                      </td>
                                    </tr>
                                    <tr>
                                      <td style="width:3%;" align="left" valign="top">&bull;</td>
                                      <td style="width:96%;">Medidas de seguridad gratuitas, (sólo para vehículos con plaza de circulación La Paz y Cochabmaba), de acuerdo a los siguientes parámetros:
                                      - No es necesaria las instalación gratuita de medidas de seguridad (sujeción de partes y piezas) como requisito de asegurabilidad, para vehiculos 0 Km o para vehículos con antigüedad mayor a 10 años.
                                      -Para los vehículos con antigüedad de hasta 10 años: Todo trabajo de medidas de seguridad (de acuerdo a requerimiento de la Compañía Aseguradora) deberá hacerse mediante los proveedores autorizados en las ciudades del eje troncal (Volvo en La Paz e ICS para Cochabamba).
                                      - Se aclara que no es necesaria la instalación de medidas de seguridad, en el caso de vehículos pesados.
                                      - Por otra parte, aclaramos que las medidas de seguridad que se instalarán gratuitamente en: <b>VEHÍCULOS DE MARCAS JAPONESAS</b> (TOYOTA, SUBARU, SUZUKI, NISSAN, MITSUBISGI y otros) Y <b>VEHÍCULOS DE MARCAS AMERICANAS</b> (FORD, CHEVROLET, HUMMER, CHRYSLER, JEEP, DODGE y otros), con antigüedad de hasta 10 años, son:<br/> 
                                      <b>Plazas de Circulación: LA PAZ Y COCHABAMBA:</b> Cerebro electrónico, cabezales, Marco Espejos, Flujometro, Tablero de instrumentos, volante, Bolsas de aire, BC llave, Bornes batería, Protector de cable de capot.<br/>
                                      En <b>VEHÍCULOS DE MARCAS EUROPEAS</b> con antigüedad de hasta 10 años y con plazas de circulación de La Paz, Cochabamba, solo se instalarán marcos de seguridad para espejos retrovisores.     
                                      
                                      </td>
                                    </tr>
                                    <tr>
                                      <td style="width:3%;" align="left" valign="top">&bull;</td>
                                      <td style="width:96%;">Se aclara que estos parámetros pueden ser modificados por la Compañía de Seguros en cualquier momento, previo acuerdo con el Banco Nacional de Bolivia S.A.
                                      
                                      </td>
                                    </tr>
                                </table>
                                <br/>
                                
                                <b style="<?=$cssMarginTop;?>">11. Condiciones Generales y Exclusiones:</b><br/>
                                De acuerdo al Condicionado General de la Boliviana Ciacruz de Seguros y Reaseguros S.A., para el Seguro Automotores BNB, adjunto a la solicitud de este seguro.				
                                <br/>
                                
                                <b style="<?=$cssMarginTop;?>">11. Nota Aclaratoria Importante:</b><br/>
                                
                                <table style="<?=$cssTable1;?> <?=$cssTextExclusion?> <?=$fuenteDet;?>" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td style="width:3%;" align="left" valign="top">&bull;</td>
                                        <td style="width:96%;">La Presente Solicitud de Seguro tendrá validez bajo las siguientes condiciones:<br/>
                                        - Para vehículos nuevos: A partir de la fecha de desembolso, y cumplimiento de los requisitos de asegurabilidad.<br/>
                                        - Para vehículos usados: A pertir de la fecha de desembolso, previa inspección por parte de la Compañía Aseguradora, y al cumplimiento de requisitos de asegurabilidad.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:3%;" align="left" valign="top">&bull;</td>
                                        <td style="width:96%;">Cualquier indemnización que no supere el 10% del Valor Asegurado (hasta USD 5.000) podrá ser cancelada de forma directa al asegurado.
                                        </td>
                                    </tr>
                                    
                                </table>
							
						</td>
					</tr>
			</table><br/>
            
            <div style="font-size:9px; width:100%; padding:5px; margin-top:9px; border:#000 solid 1px;">
             <p style="text-align:center; font-weight:bold;">NOTA IMPORTANTE</p>
             Sr. cliente del Banco Nacional de Bolivia S.A., una vez su crédito haya sido desembolsado, Usted debe buscar a su oficial de credito para recoger el Certificado de Cobertura individual, que avala la cobertura de su automotor.<br/>
             Se acuerda que ni el Banco Nacional de Bolivia S.A. ni la boliviana Ciacruz de Seguros y Reaseguros S.A. asumirán responsabilidad alguna por la guarda y conservación de su Certificado, que es de su exclusiva responsabilidad.<br/>
             Sin Perjuicio de lo anterior, se deja constancia que el presente formulario de Solicitud contiene el mismo tenor que el referido Certificado de Cobertura Individual y en su caso, podrá servir de referente en caso de extravio del Certificado de Cobertura Individual. 
            </div><br/>
            
            <div style="width:60%; margin-top:45px;">
            <table border="0" cellpadding="0" cellspacing="0" style="width:100%; font-size:9px;">
               <tr>
                 <td colspan="4" style="width:50%; text-align:center; border-top:#000 solid 1px;">FIRMA DEL TITULAR ASEGURADO</td>
                 <td style="width:8%;">&nbsp;</td>
                 <td style="width:17%;">&nbsp;</td>
                 <td style="width:8%;">&nbsp;</td>
                 <td style="width:17%;">&nbsp;</td>
               </tr>
               <tr><td colspan="8" style="width:100%;">&nbsp;</td></tr>
               <?php
                 if((boolean)$row['emitir']===true){
			   ?>
                   <tr>
                     <td style="width:8%; text-align:right;">C.I.</td>
                     <td style="width:17%; border-bottom:#000 solid 1px;"><?=$row['ci']?></td>
                     <td style="width:8%; text-align:right;">Día</td>
                     <td style="width:17%; border-bottom:#000 solid 1px; text-align:center;"><?=date('d', strtotime($row['fecha_emision']));?></td>
                     <td style="width:8%; text-align:right;">Mes</td>
                     <td style="width:17%; border-bottom:#000 solid 1px; text-align:center;"><?=date('m', strtotime($row['fecha_emision']));?></td>
                     <td style="width:8%; text-align:right;">Año</td>
                     <td style="width:17%; border-bottom:#000 solid 1px; text-align:center;"><?=date('Y', strtotime($row['fecha_emision']));?></td>
                   </tr>
                <?php
				 }else{
				?>   
                   <tr>
                     <td style="width:8%; text-align:right;">C.I.</td>
                     <td style="width:17%; border-bottom:#000 solid 1px;"><?=$row['ci']?></td>
                     <td style="width:8%; text-align:right;">Día</td>
                     <td style="width:17%; border-bottom:#000 solid 1px; text-align:center;">&nbsp;</td>
                     <td style="width:8%; text-align:right;">Mes</td>
                     <td style="width:17%; border-bottom:#000 solid 1px; text-align:center;">&nbsp;</td>
                     <td style="width:8%; text-align:right;">Año</td>
                     <td style="width:17%; border-bottom:#000 solid 1px; text-align:center;">&nbsp;</td>
                   </tr>
                <?php
				 }
				?>
            </table>
            </div>
            
     </div>  
      
       
  </div>  
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
	
	$html = ob_get_clean();
	return $html;
}

function formatCheckTVH($data){
	return explode('-', $data);
}
?>