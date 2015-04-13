<?php
function de_em_certificate_aps($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
    $emitir = (boolean)$row['emitir'];

    if ($emitir === true) {
        $row['fecha_emision'] = $row['fecha_emision'];
    } else {
        $row['fecha_emision'] = $row['fecha_creacion'];
    }

   // $row['fecha_emision'] = $row['u_departamento'] . ', ' . date('d/m/Y', strtotime($row['fecha_emision']));

    ob_start();
?>
<div id="container-c" style="width: 785px; height: auto;
    border: 0px solid #0081C2; padding: 5px;">
    <div id="main-c" style="width: 775px; font-weight: normal; font-size: 12px;
        font-family: Arial, Helvetica, sans-serif; color: #000000;">
<?php
    $nCl = $rsDt->num_rows;
    $_coverage = (int)$row['cobertura'];

    $coverage = array('', '', '');
    switch ($_coverage) {
    case 1:
        if ($nCl === 1) {
            $coverage[0] = 'X';
        } elseif ($nCl === 2) {
            $coverage[1] = 'X';
        }
        break;
    case 2:
        $coverage[2] = 'X';
        break;
    }

    $k = 0;
    while ($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)) {
        $show_fac = true;
        $show_tit = true;
        $k += 1;

        $status = $rowDt['estado_civil'];
        $from = '';

        /*if ($status === 'CAS' || $status === 'VIU') {
            $rowDt['materno'] = '';
        } else {
            $rowDt['ap_casada'] = '';
        }*/

        if ($_coverage === 2) {
            if ((boolean)$rowDt['facultativo'] === true) {
                $detalle_f = array();
                $detalle_p = array();

                $detalle_f = json_decode($rowDt['detalle_f'], true);

                if ((boolean)$rowDt['aprobado'] === true && $emitir === true) {
                    if (count($detalle_f) > 0) {
                        Det_fac:
                        $row['aprobado'] = $detalle_f['aprobado'];
                        $row['tasa_recargo'] = $detalle_f['tasa_recargo'];
                        $row['porcentaje_recargo'] = $detalle_f['porcentaje_recargo'];
                        $row['tasa_actual'] = $detalle_f['tasa_actual'];
                        $row['tasa_final'] = $detalle_f['tasa_final'];
                        $row['observacion'] = $detalle_f['observacion'];
                    }
                } else {
                    if ($emitir === true) {
                        $show_tit = false;
                        $k = $k - 1;
                    }

                    $row['motivo_facultativo'] = $rowDt['motivo_facultativo'];

                    if (count($detalle_f) > 0 && $emitir === true) {
                        goto Det_fac;
                    }
                    //$detalle_p = json_decode($rowDt['detalle_p'], true);

                    // if (count($detalle_p) > 0) {
                    // }
                }
            } else {
                $show_fac = false;
            }
        }

        $ci = array('', '');
        switch ($rowDt['tipo_documento']) {
        case 'CI':
            $ci[0] = 'X';
            break;
        default:
            $ci[1] = 'X';
            break;
        }

        $sex = array('', '');
        switch ($rowDt['genero']) {
        case 'M':
            $sex[0] = 'X';
            break;
        case 'F':
            $sex[1] = 'X';
            break;
        }
		
        switch ($row['moneda']) {
        case 'USD':
            $currency = 'Dolares';
            break;
        case 'BS':
            $currency = 'Bolivianos';
            break;
        }

        $res = json_decode($rowDt['respuesta'], true);
        $obs = json_decode($rowDt['observacion'], true);

        if ($show_tit === true) {
?>
      	<div style="width: 775px; border: 0px solid #FFFF00; ">
            <table
                cellpadding="0" cellspacing="0" border="0"
                style="width: 100%; height: auto; font-family: Arial;">
                <tr>
                    <td style="width: 80%; text-align: left; font-style: italic;
                        font-size: 100%; " >
                        Declaración de Salud y/o Solicitud de Seguro de Desgravamen
                    </td>
                    <td style="width: 20%;">
                        <img src="<?=$url;?>images/<?=$row['logo_cia'];?>"
                            height="40" class="container-logo" align="left" />
                    </td>
                </tr>
		    </table>

            <table
                cellpadding="0" cellspacing="0" border="0"
                style="width: 100%; height: auto; font-size: 90%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 100%; text-align: center; font-size: 100%;
                        font-weight: bold; ">
                        CERTIFICADO DE COBERTURA INDIVIDUAL<br>
						SEGURO DE DESGRAVAMEN<br>
						Resolución Administrativa de Registro No ____________________________<br>
						Código de Registro _____________________________________________<br>
						PÓLIZA N° <?=$row['no_emision'];?>
                    </td>
                </tr>
            </table>
         </div>

        <br>
        <br>

         <div style="width: 775px; text-align: left; font-size: 80%; font-weight: bold;">
            <table
                cellpadding="0" cellspacing="0" border="0"
                style="width: 100%; height: auto; font-size: 100%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 14%; height: 10px;">ASEGURADO:</td>
                    <td style="width: 34%; height: 10px; border-bottom: 1px solid #000;">
                      <?=$row['ef_nombre'];?>                    </td>
                    <td style="width: 2%;"></td>
                    <td style="width: 19%; height: 10px;">MONEDA:</td>
                    <td style="width: 31%; height: 10px; border-bottom: 1px solid #000;"><?=$currency;?></td>
                </tr>
                <tr>
                    <td style="width: 14%; height: 10px;">BENEFICIARIO:</td>
                    <td style="width: 34%; height: 10px; border-bottom: 1px solid #000;"><?=$rowDt['nombre']. ' '.$rowDt['paterno']. ' '.$rowDt['materno'];?></td>
                    <td style="width: 2%;"></td>
                    <td style="width: 19%; height: 10px;">NOMBRE INTERMEDIARIO:</td>
                    <td style="width: 31%; height: 10px; border-bottom: 1px solid #000;">Sudamericana S.R.L</td>
                </tr>
                <tr>
                    <td style="width: 14%; height: 10px;">INICIO DE VIGENCIA:</td>
                    <td style="width: 34%; height: 10px; border-bottom: 1px solid #000;">
                    Desde las 00:01 de. <?=date('d/m/Y', strtotime($row['fecha_emision']));?>                    </td>
                    <td style="width: 2%;"></td>
                    <td style="width: 19%; height: 10px; ">DIRECCIÓN INTERMEDIARIO:</td>
                    <td style="width: 31%; height: 10px; border-bottom: 1px solid #000;">La Paz, Prolongación Cordero Nº 163 - San Jorge</td>
                </tr>
                <tr>
                    <td style="width: 14%; height: 10px;">CIUDAD:</td>
                    <td style="width: 34%; height: 10px; border-bottom: 1px solid #000;"><?=$row['u_departamento'];?></td>
                    <td style="width: 2%;"></td>
                    <td style="width: 19%; height: 10px;">TELÉFONO INTERMEDIARIO:</td>
                    <td style="width: 31%; height: 10px; border-bottom: 1px solid #000;">(591) -2-2433500</td>
                </tr>
            </table>
          </div>
        
          <br>

          <div style="width: 775px; border: 0px solid #FFFF00; font-size: 85%;">
            <table
                cellpadding="0" cellspacing="0" border="0"
                style="width: 100%; height: auto; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 100%; background: #000; color: #fff;">
                        VIGENCIA DE LA COBERTURA INDIVIDUAL DEL ASEGURADO
                    </td>
                </tr>
            </table>
            <br>

            <div style="text-align:justify">La vigencia individual de la cobertura para cada 
            Asegurado será mensual renovable automáticamente, iniciándose el momento del
            desembolso del crédito por parte de la entidad de intermediación financiera 
            a favor del asegurado y finalizando el momento de la extinción de la
            operación crediticia.
            Esta vigencia se interrumpirá en caso de incumplimiento de pago de 
            la prima correspondiente, treinta días después de la fecha de vencimiento de pago.
            </div>
            <br>

            <table
                cellpadding="0" cellspacing="0" border="0"
                style="width: 100%; height: auto; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 100%; background: #000; color: #fff;">
                        CAPITAL ASEGURADO
                    </td>
                </tr>
            </table>
            <br>
            El Capital Asegurado durante la vigencia de la Póliza será el monto insoluto de la deuda de los Asegurados a favor del Beneficiario.
            <br><br>

            <table
                cellpadding="0" cellspacing="0" border="0"
                style="width: 100%; height: auto; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 100%; background: #000; color: #fff;">
                       PRIMA TOTAL
                    </td>
                </tr>
            </table>
            <br>
            El resultado de aplicar la Tasa Mensual total por mil al Capital Asegurado.
            <br><br>


            <table
                cellpadding="0" cellspacing="0" border="0"
                style="width: 100%; height: auto; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 100%; background: #000; color: #fff;">
                      COBERTURAS Y TASAS
                    </td>
                </tr>
            </table>
            
            <br>

            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; height: auto; font-weight: bold; font-family: Arial;">
              <tr>
                <td style="width: 25%;"></td>
                <td style="width: 50%;">
                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; height: auto; font-size: 100%;
                                    font-weight: bold; font-family: Arial;">
                        <tr>
                            <td style="width: 50%; background: #000000; color: #ffffff; text-align: center; border: 1px solid #000;">COBERTURAS</td>
                            <td style="width: 50%; background: #000000; color: #ffffff; text-align: center; border: 1px solid #000;">TASA TOTAL MENSUAL POR MIL</td>
                        </tr>
                        <tr>
                            <td style="width: 50%; font-weight: normal; border-left: 1px solid #000; text-align: center">Muerte por cualquier causa</td>
                            <td style="width: 50%; font-weight: normal; border-right: 1px solid #000; text-align: center">A</td>
                        </tr>
                        <tr>
                            <td style="width: 50%; font-weight: normal; border: 1px solid #000; border-right: 0 none; border-bottom: 0 none; text-align: center">Total Tasa Total mensual por mil</td>
                            <td style="width: 50%; font-weight: normal; border: 1px solid #000; border-left: 0 none; border-bottom: 0 none; text-align: center;">A+B</td>
                        </tr>
                        <tr>
                            <td style="width: 50%; font-weight: bold; border: 1px solid #000; border-right: 0 none; text-align: center">Total Tasa Total mensual por mil</td>
                            <td style="width: 50%; font-weight: bold; border: 1px solid #000; border-left: 0 none;  text-align: center;">A+B</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 25%;"></td>
              </tr>
            </table>

            <br><br>

            <table
                cellpadding="0" cellspacing="0" border="0"
                style="width: 100%; height: auto; font-size: 100%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 100%; background: #000; color: #fff;">
                       PERMANENCIA DEL SEGURO
                    </td>
                </tr>
            </table>

            <br>

            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; height: auto; font-size: 100%; font-weight: bold; font-family: Arial;">
              <tr>
                <td style="width: 25%;"></td>
                <td style="width: 50%;">
                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; height: auto; font-size: 100%;
                                    font-weight: bold; font-family: Arial;">
                        <tr>
                            <td style="width: 50%; background: #000000; color: #ffffff; text-align: center; border: 1px solid #000;">Fallecimiento</td>
                            <td style="width: 50%; background: #000000; color: #ffffff; text-align: center; border: 1px solid #000;">Invalidez Total y Permanente</td>
                        </tr>
                        <tr>
                            <td style="width: 50%; font-weight: normal; border: 1px solid #000; border-right: 0 none; text-align: center">Hasta Cumplir los <span style="font-weight: bold;">75</span> años</td>
                            <td style="width: 50%; font-weight: normal; border: 1px solid #000; border-left: 0 none;  text-align: center;">Hasta Cumplir los <span style="font-weight: bold;">75</span> años</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 25%;"></td>
              </tr>
            </table>

            <br><br>

            <table
                cellpadding="0" cellspacing="0" border="0"
                style="width: 100%; height: auto; font-size: 100%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 100%; background: #000; color: #fff; text-decoration:underline;">
                       OBSERVACIONES
                    </td>
                </tr>
            </table>
            <br>
            Las primas de este seguro no constituyen hecho generador de tributo según el Art. No. 54 de la Ley de Seguros 1883 del 25 de junio de 1998.
            <br><br>
            Lugar y Fecha, <?=$row['u_departamento'] . ', ' . date('d/m/Y', strtotime($row['fecha_emision']));?><br>


            <br><br><br><br><br><br><br>
            <table
                cellpadding="0" cellspacing="0" border="0"
                style="width: 100%; height: auto; font-size: 80%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 33%;"></td>
                    <td style="width: 34%; border-top: 1px solid #000; text-align: center;">
                        FIRMAS AUTORIZADAS
                        <br><br>
                    </td>
                    <td style="width: 33%;"></td>
                </tr>
            </table>
        </div>


		<br>
<?php
        $response = array(
            1 => array('', ''),
            2 => array('', ''),
            3 => array('', ''),
            4 => array('', ''),
            5 => array('', ''),
            6 => array('', ''),
            7 => array('', ''),
            8 => array('', ''),
            );

        $res_client = json_decode($rowDt['respuesta'], true);

        foreach ($res_client as $key => $value) {
            $res = explode('|', $value);

            switch ((int)$res[1]) {
            case 1:
                $response[(int)$res[0]] = array('X', '');
                break;
            case 0:
                $response[(int)$res[0]] = array('', 'X');
                break;
            }
        }
?>
        
		<page><div style="page-break-before: always;">&nbsp;</div></page>
				
		<div style="width: 775px; border: 0px solid #FFFF00; ">
          
            <table
                cellpadding="0" cellspacing="0" border="0"
                style="width: 100%; height: auto; font-size: 90%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 80%;"></td>
                    <td style="width: 20%;">
                        <img src="<?=$url;?>images/<?=$row['logo_cia'];?>"
                            height="40" class="container-logo" align="left" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100%; text-align: center; font-size: 100%;
                        font-weight: bold; " colspan="2">
                        SOLICITUD DE SEGURO Y DECLARACIÓN DE SALUD<br>
						DECLARACIÓN JURADA DE SALUD
                        </td>
                </tr>
            </table>  
                    
			<br>
            
			<table
                cellpadding="0" cellspacing="0" border="0"
                style="width: 100%; height: auto; font-size: 80%; font-weight: bold; font-family: Arial;">
                <!--<tr>
                    <td style="width: 100%; background: #000; color: #fff;" colspan="5" >
                        DATOS PERSONALES - TITULAR                    </td>
                </tr>-->
			    <tr>
                    <td style="width: 13%; height: 10px;">Nombre Completo:</td>
                    <td style="width: 35%; height: 10px; border-bottom: 1px solid #000;">
                    <?=$rowDt['nombre']. ' '.$rowDt['paterno']. ' '.$rowDt['materno'];?>
                    </td>
					<td style="width: 2%;"></td>
                    <td style="width: 14%; height: 10px;">Capital a Asegurar:</td>
                    <td style="width: 36%; height: 10px; border-bottom: 1px solid #000;">
                    <?=number_format($rowDt['cumulo'], 2, '.', ',');?>                    </td>
                </tr>
				<tr>
                    <td style="width: 13%; height: 10px;">Carnet de Identidad:</td>
                    <td style="width: 35%; height: 10px; border-bottom: 1px solid #000;">
                      <?=$rowDt['dni']. ' ' .$rowDt['extension'];?>                    </td>
					<td style="width: 2%;"></td>
                    <td style="width: 14%; height: 10px;">Fecha de Nacimiento:</td>
                    <td style="width: 36%; height: 10px; border-bottom: 1px solid #000;">
                    <?=date('d/m/Y', strtotime($rowDt['fecha_nacimiento']));?>                    </td>
                </tr>
				<tr>
                    <td style="width: 13%; height: 10px;">Entidad Financiera:</td>
                    <td style="width: 35%; height: 10px; border-bottom: 1px solid #000;">
                      <?=$row['ef_nombre'];?>                    </td>
					<td style="width: 2%;"></td>
                    <td style="width: 14%; height: 10px; ">Ocupación principal:</td>
                    <td style="width: 36%; height: 10px; border-bottom: 1px solid #000;">
                    <?=$rowDt['ocupacion'] . ' - ' . $rowDt['desc_ocupacion'];?>                    </td>
                </tr>
            </table>
           
            <div style="width: 775px; border: 0px solid #FFFF00; ">
                            
                <br>
    <?php
                if((boolean)$row['facultativo'] === true){
                    if ($show_fac === true) {
                        if((boolean)$rowDt['aprobado'] === true && $emitir === true){
    ?>
                <table border="0" cellpadding="1" cellspacing="0"
                    style="width: 100%; font-size: 8px; border-collapse: collapse;">
                    <tr>
                        <td colspan="7" style="width:100%; text-align: center;
                            font-weight: bold; background: #e57474; color: #FFFFFF;">Caso Facultativo</td>
                    </tr>
                    <tr>
                        <td style="width:5%; text-align: center; font-weight: bold;
                            border: 1px solid #dedede; background: #e57474;">Aprobado</td>
                        <td style="width:12%; text-align: center; font-weight: bold;
                            border: 1px solid #dedede; background: #e57474;">Tasa de Recargo</td>
                        <td style="width:14%; text-align: center; font-weight: bold;
                            border: 1px solid #dedede; background: #e57474;">Porcentaje de Recargo</td>
                        <td style="width:12%; text-align: center; font-weight: bold;
                            border: 1px solid #dedede; background: #e57474;">Tasa Actual</td>
    
                        <td style="width:12%; text-align: center; font-weight: bold;
                            border: 1px solid #dedede; background: #e57474;">Tasa Final</td>
                        <td style="width:45%; text-align: center; font-weight: bold;
                            border: 1px solid #dedede; background: #e57474;">Observaciones</td>
                    </tr>
                    <tr>
                        <td style="width:5%; text-align: center; background: #e78484;
                            color: #FFFFFF; border: 1px solid #dedede;"><?=$row['aprobado'];?></td>
                        <td style="width:12%; text-align: center; background: #e78484;
                            color: #FFFFFF; border: 1px solid #dedede;"><?=$row['tasa_recargo'];?></td>
                        <td style="width:14%; text-align: center; background: #e78484;
                            color: #FFFFFF; border: 1px solid #dedede;"><?=$row['porcentaje_recargo'];?> %</td>
                        <td style="width:12%; text-align: center; background: #e78484;
                            color: #FFFFFF; border: 1px solid #dedede;"><?=$row['tasa_actual'];?> %</td>
                        <td style="width:12%; text-align: center; background: #e78484;
                            color: #FFFFFF; border: 1px solid #dedede;"><?=$row['tasa_final'];?> %</td>
                        <td style="width:45%; text-align: justify; background: #e78484;
                            color: #FFFFFF; border: 1px solid #dedede;"><?=$row['observacion'];?></td>
                    </tr>
                </table>
    <?php
                    } else {
    ?>
                <table border="0" cellpadding="1" cellspacing="0"
                    style="width: 100%; font-size: 8px; border-collapse: collapse;">
                    <tr>
                        <td colspan="7" style="width:100%; text-align: center; font-weight: bold;
                            background: #e57474; color: #FFFFFF;">Caso Facultativo</td>
                    </tr>
                    <tr>
                        <td style="width:100%; text-align: center; font-weight: bold;
                            border: 1px solid #dedede; background: #e57474;">Observaciones</td>
                    </tr>
                    <tr>
                        <td style="width:100%; text-align: justify; background: #e78484;
                            color: #FFFFFF; border: 1px solid #dedede;"><?=$row['motivo_facultativo'];?></td>
                    </tr>
               </table>
         <?php
                    }
                }
            }
    ?>
            </div>
            
   
            <table
                cellpadding="0" cellspacing="0" border="0"
                style="width: 100%; height: auto; font-size: 80%; font-weight: bold; font-family: Arial;">
                <!--<tr>
                    <td style="width: 100%; background: #000; color: #fff;" colspan="5" >
                        DECLARACIÓN DE SALUD DE LA PERSONA (Marque la respuesta según corresponda)
                    </td>
                </tr>
				-->
                <tr>
                    <td style="width: 15%; height: 10px; "></td>
                    <td style="width: 70%;"></td>
                    <td style="width: 10%; text-align: center; border-top: 0px solid #000;
                        border-bottom: 0px solid #000; border-left: 0px solid #000;
                        border-right: 0px solid #000;" colspan="2"></td>
                    <td style="width: 5%;"></td>
                </tr>
                <tr>
                    <td style="width: 15%; height: 10px; "></td>
                    <td style="width: 70%;"></td>
                    <td style="width: 5%; text-align: center; border-top: 1px solid #000;
                        border-left: 1px solid #000;">SI</td>
                    <td style="width: 5%; text-align: center; border-top: 1px solid #000;
                        border-right: 1px solid #000;">NO</td>
                    <td style="width: 5%;"></td>
                </tr>
                <tr>
                    <td style="width: 15%; height: 10px; text-align: right;">
                        1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style="width: 70%;">
                        ¿Tiene o ha tenido alguna enfermedad que requirió hospitalización?
                    </td>
                    <td style="width: 5%; border-left: 1px solid #000;">
                        <div style="width: 10px; height: 10px; margin-left: 12px; border: 1px solid #000;">
                            <?=$response[1][0];?>
                        </div>
                    </td>
                    <td style="width: 5%; border-right: 1px solid #000;">
                        <div style="width: 10px; height: 10px; margin-left: 12px; border: 1px solid #000;">
                            <?=$response[1][1];?>
                        </div>
                    </td>
                    <td style="width: 5%;"></td>
                </tr>
                <tr>
                    <td style="width: 15%; height: 10px; text-align: right;">
                        2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style="width: 70%;">¿Tiene o ha tenido algún tipo de cáncer?</td>
                    <td style="width: 5%; border-left: 1px solid #000;">
                        <div style="width: 10px; height: 10px; margin-left: 12px; border: 1px solid #000;">
                            <?=$response[2][0];?>
                        </div>
                    </td>
                    <td style="width: 5%; border-right: 1px solid #000;">
                        <div style="width: 10px; height: 10px; margin-left: 12px; border: 1px solid #000;">
                            <?=$response[2][1];?>
                        </div>
                    </td>
                    <td style="width: 5%;"></td>
                </tr>
                <tr>
                    <td style="width: 15%; height: 10px; text-align: right;">
                        3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style="width: 70%;">¿Tiene o ha tenido problemas o enfermedades cardíacas?</td>
                    <td style="width: 5%; border-left: 1px solid #000;">
                        <div style="width: 10px; height: 10px; margin-left: 12px; border: 1px solid #000;">
                            <?=$response[3][0];?>
                        </div>
                    </td>
                    <td style="width: 5%; border-right: 1px solid #000;">
                        <div style="width: 10px; height: 10px; margin-left: 12px; border: 1px solid #000;">
                            <?=$response[3][1];?>
                        </div>
                    </td>
                    <td style="width: 5%;"></td>
                </tr>
                <tr>
                    <td style="width: 15%; height: 10px; text-align: right;">
                        4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style="width: 70%;">¿Ha sido sometido a alguna operación quirúrgica en los últimos tres años?</td>
                    <td style="width: 5%; border-left: 1px solid #000;">
                        <div style="width: 10px; height: 10px; margin-left: 12px; border: 1px solid #000;">
                            <?=$response[4][0];?>
                        </div>
                    </td>
                    <td style="width: 5%; border-right: 1px solid #000;">
                        <div style="width: 10px; height: 10px; margin-left: 12px; border: 1px solid #000;">
                            <?=$response[4][1];?>
                        </div>
                    </td>
                    <td style="width: 5%;"></td>
                </tr>
                <tr>
                    <td style="width: 15%; height: 10px; text-align: right;">
                        5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style="width: 70%;">¿Fuma más de diez cigarrillos diarios?</td>
                    <td style="width: 5%; border-left: 1px solid #000;">
                        <div style="width: 10px; height: 10px; margin-left: 12px; border: 1px solid #000;">
                            <?=$response[5][0];?>
                        </div>
                    </td>
                    <td style="width: 5%; border-right: 1px solid #000;">
                        <div style="width: 10px; height: 10px; margin-left: 12px; border: 1px solid #000;">
                            <?=$response[5][1];?>
                        </div>
                    </td>
                    <td style="width: 5%;"></td>
                </tr>
                <tr>
                    <td style="width: 15%; height: 10px; text-align: right;">
                        6&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style="width: 70%;">
                    	¿Tiene Sida o es portador del virus de la inmunodeficiencia humana - VIH?</td>
                    <td style="width: 5%; border-left: 1px solid #000; border-bottom: 1px solid #000;">
                        <div style="width: 10px; height: 10px; margin-left: 12px; border: 1px solid #000;">
                            <?=$response[6][0];?>
                        </div>
                    </td>
                    <td style="width: 5%; border-right: 1px solid #000; border-bottom: 1px solid #000;">
                        <div style="width: 10px; height: 10px; margin-left: 12px; border: 1px solid #000;">
                            <?=$response[6][1];?>
                        </div>
                    </td>
                    <td style="width: 5%;"></td>
                </tr>
                <tr>
                    <td colspan="5">
                        <br>
						<!--
                        En caso de marcar SÍ en alguna de las preguntas 1 a 4, detallar las mismas
                        señalando además cuándo ocurrió, duración, tratamiento, fecha de curación,
                        secuelas, observaciones u otros.
						-->
						En caso que alguna respuesta sea afirmativa, favor especificar:
                    </td>
                </tr>
                <!--<tr>
                    <td style="width: 15%; height: 10px; ">TITULAR</td>
                    <td style="width: 85%; border-bottom: 1px solid #000;" colspan="4">
                        <?=$rowDt['observacion'];?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 15%; height: 10px; "></td>
                    <td style="width: 85%; border-bottom: 1px solid #000;" colspan="4"></td>
                </tr>
                -->
            </table>
            <table
                cellpadding="0" cellspacing="0" border="0"
                style="width: 100%; height: auto; font-size: 80%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 5%;"></td>
                    <td style="width: 95%; height: 8px; border-bottom: 1px solid #000;">
                        <?=$rowDt['observacion'];?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%;"></td>
                    <td style="width: 95%; height: 8px; border-bottom: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td style="width: 5%;"></td>
                    <td style="width: 95%; height: 8px; border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
            <br>
        </div>


  		<div style="width: 775px; border: 0px solid #FFFF00; font-size: 85%; font-family: Arial; text-align:justify">
            <table
                cellpadding="0" cellspacing="0" border="0"
                style="width: 100%; height: auto; font-size: 100%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 100%; background: #000; color: #fff;">
                        DECLARACIONES Y AUTORIZACIONES
                    </td>
                </tr>
            </table>           
            <br>
			Declaro que las respuestas que he consignado en este Formulario de Solicitud de Seguro de Desgravamen y Declaración de Salud son verdaderas y completas.
			<br><br>
			Autorizo, a los médicos, clínicas, hospitales y otros centros de salud que me hayan atendido para que proporcionen a la Entidad Aseguradora, todos los resultados de los informes referentes a mi salud, en caso de enfermedad o accidentes, para lo cual releva a dichos médicos y centros médicos, en relación con su secreto profesional, de toda responsabilidad en que pudiera incurrir al proporcionar tales informes.
			<br><br><br><br><br><br>
            <table
                cellpadding="0" cellspacing="0" border="0"
                style="width: 100%; height: auto; font-size: 80%; font-weight: bold; font-family: Arial;">
               
			    <tr>
                    <td style="width: 33%; text-align: center;">N° de Crédito:_______________________________</td>
                    <td style="width: 34%;"></td>
                    <td style="width: 33%; text-align: center;;">Solicitante:________________________________</td>
                </tr>
			                  
			    <tr>
                    <td style="width: 33%; height: 20px;"></td>
                    <td style="width: 34%; height: 20px;"></td>
                    <td style="width: 33%; height: 20px;"></td>
                </tr>
			   
			    <tr>
                    <td style="width: 33%;"></td>
                    <td style="width: 34%; border-top: 1px solid #000; text-align: center;">
                        (Firma y Sello)
                        <br><br>
                    </td>
                    <td style="width: 33%;"></td>
                </tr>
            </table>
        </div>


		<br><br><br>
        <table
            cellpadding="0" cellspacing="0" border="0"
            style="width: 100%; height: auto; font-size: 90%; font-weight: bold; font-family: Arial;">
            <tr>
                <td style="width: 100%; text-align: center; font-size: 100%;
                    font-weight: bold; ">
                   	SOLICITUD DEL SEGURO DE DESGRAVAMEN
                </td>
            </tr>
        </table>
		<br>

        <div style="width: 775px; border: 0px solid #FFFF00; font-size: 85%; text-align:justify; font-family: Arial"> 

            Mediante el presente formulario, en conformidad con la Declaración de Salud que precede,
            solicito a <?=$row['compania'];?>, como Entidad Aseguradora, se le otorgue el Seguro de 
            Desgravamen (Desgravación de Préstamo), con referencia al préstamo que al presente
            gestiono ante <?=$row['ef_nombre'];?> de la ciudad de <?=$row['u_departamento'];?>
            por el plazo de <?=$row['plazo'] . ' ' . $row['tipo_plazo'];?>, 
            con destino a ............           
            <br><br>
            Para los efectos que corresponda declaro y doy mi absoluta conformidad a todas y cada una 
            de las condiciones y estipulaciones establecidas por la Entidad Aseguradora, sobre concesión,
            vigencia y caducidad del citado seguro, según el Reglamento Aprobado, obligándome a pagar por
            adelantado las primas mensuales del seguro solicitado.
            <br><br>
            En la fecha, y a tiempo de suscribir esta solicitud, dejo constancia de haber cancelado en
            las Oficinas de la Entidad Financiera (o en Oficinas de la Entidad Aseguradora), la suma de
            ............... que representa la primera prima mensual de dicho seguro.
            <br><br>           
			Empero, la recepción de la mencionada prima mensual no obliga a la Entidad Aseguradora de otorgar 
            la cobertura correspondiente en tanto no se haya desembolsado el crédito, y no se haya 
            efectuado previamente la calificación por parte de la Entidad Aseguradora de este formulario
            de Declaración de Salud y Solicitud de Seguro en los términos y condiciones señalados en
            el Condicionado General y Particular de la Póliza.
            <br><br>
			Yo <?=$rowDt['nombre']. ' '.$rowDt['paterno']. ' '.$rowDt['materno'];?>
            <br><br>
			Lugar y Fecha, <?=$row['u_departamento'] . ', ' . date('d/m/Y', strtotime($row['fecha_emision']));?>
            
        </div>

<?php

        if ($_coverage === 2) {
            $row['monto_solicitado'] = $rowDt['monto_bc'];
        }

        $product = array('', '', '', '');
        switch ((int)$row['id_prcia']) {
        case 6:
            $product[0] = 'X';
            break;
        case 7:
            $product[0] = 'X';
            break;
        case 2:
            $product[1] = 'X';
            break;
        case 5:
            $product[2] = 'X';
            break;
        default:
            $product[3] = 'X';
            break;
        }

?>

<?php
            if ($emitir === true) {
?>
        <page><div style="page-break-before: always;">&nbsp;</div></page>
        <div style="width: 775px; border: 0px solid #FFFF00; ">
            <table
                cellpadding="0" cellspacing="0" border="0"
                style="width: 100%; height: auto; font-size: 90%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 80%;"></td>
                    <td style="width: 20%;">
                        <img src="<?=$url;?>images/<?=$row['logo_cia'];?>"
                            height="40" class="container-logo" align="left" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100%; text-align: center; font-size: 100%;
                        font-weight: bold; " colspan="2">
                        CONDICIONES DE LA POLIZA
                        </td>
                </tr>
            </table>
        </div>
        <br>

        <div style="width: 775px; border: 0px solid #FFFF00; font-size: 90%; ">
            <table
                cellpadding="0" cellspacing="0" border="0"
                style="width: 100%; height: auto; font-size: 85%; font-family: Arial;">
                <tr>
                    <td style="width: 50%; text-align: justify; padding: 5px;" valign="top">
                    
                   	    <span style="font-weight: bold;">COBERTURA DEL SEGURO: 
                        </span>
                        El Capital Asegurado será pagado, por la Entidad Aseguradora, cuando ocurra
                        uno de los siguientes eventos:
                        <br><br>                       
                       
                        <table 
                            cellpadding="0" cellspacing="0" border="0" 
                            style="width: 100%; height: auto;">
                          <tr>
                            <td style="width:3%" valign="top">&nbsp;-&nbsp;</td>
                            <td style="width:97%; text-align: justify;" ><span style="font-weight: bold;">Fallecimiento: </span>
                            Es la muerte por cualquier causa del Asegurado, pagando el 
                            capital asegurado al Beneficiario, si esto ocurre durante 
                            la vigencia de la Póliza.        </td>
                          </tr>
                          <tr>
                            <td style="width:3%" valign="top">&nbsp;-&nbsp;</td>
                            <td style="width:97%; text-align: justify;"><span style="font-weight: bold;">Invalidez Total y Permanente: </span>
                            Se considera Invalidez Total y permanente al estado de situación física
                            del Asegurado que como consecuencia de una enfermedad o accidente presenta 
                            una pérdida o disminución de su fuerza física o intelectual igual o superior
                            al 60% de su capacidad de trabajo, siempre que el carácter de tal incapacidad
                            sea reconocido y formalizado, por el Instituto Nacional de Salud Ocupacional (INSO).						
                            <br>
                            Se define capacidad de trabajo como la capacidad del Asegurado para realizar
                            los actos esenciales de cualquier ocupación para la cual esté preparado de
                            acuerdo con su educación, capacitación o experiencia.
                            <br>
                            También se considera como invalidez total y permanente la pérdida total e
                            irreparable de la vista de ambos ojos, la amputación total de ambos brazos,
                            ambas piernas, ambas manos o de ambos pies, o la amputación total de un brazo 
                            o una mano conjuntamente con una pierna o un pie.        
                            </td>
                          </tr>
                        </table>
                        
                        <br>

                                                                      
                      
                        <span style="font-weight: bold;">EXCLUSIONES DE COBERTURA: </span>
                        La Entidad Aseguradora no cubre y estará eximida de toda responsabilidad, 
                        en caso que la muerte o invalidez total y permanente del Asegurado
                        sobrevenga directa o indirectamente como consecuencia de:
                        
                        <br><br>
                        
                        
                        <table 
                            cellpadding="0" cellspacing="0" border="0" 
                            style="width: 100%; height: auto;">
                          <tr>
                            <td style="width:3%" valign="top">a)&nbsp;</td>
                            <td style="width:97%; text-align: justify">Intervención directa o indirecta del 
                            	Asegurado en actos delictuosos, que le ocasionen la muerte.</td>
                          </tr>
                          <tr>
                            <td style="width:3%" valign="top">b)&nbsp;</td>
                            <td style="width:97%; text-align: justify">Guerra internacional o civil (declarada o no), revolución, invasión,
                       			actos de enemigos extranjeros, hostilidades u operaciones bélicas, insurrección,
                        		sublevación, rebelión, sedición, motín o hechos que las leyes califican como delitos
                        		contra la seguridad del Estado.</td>
                          </tr>
                          <tr>
                            <td style="width:3%" valign="top">c)&nbsp;</td>
                            <td style="width:97%; text-align: justify">Fisión, fusión nuclear o contaminación radioactiva.</td>
                          </tr>
                        </table>                        

                        <br>                       
                        
                        <span style="font-weight: bold;">OBLIGACIÓN DE DECLARAR DEL ASEGURADO:
                        </span>
                        El asegurado está obligado a declarar objetiva y verazmente 
                        los hechos y circunstancias que tengan importancia para la
                        determinación del estado de riesgo, tal como lo conozca; 
                        a través del formulario de declaración de salud proporcionado
                        por el asegurador.
                        <br>
                        La reticencia o inexactitud en las declaraciones del asegurado
                        hacen anulable el contrato de seguro, siempre y cuando dicha 
                        reticencia o inexactitud suponga ocultación de antecedentes de 
                        tal importancia que, de ser conocidos por el asegurador, éste no habría 
                        celebrado el contrato o de hacerlo, lo hubiera hecho en condiciones 
                        distintas. El asegurador deberá demostrar este aspecto de alegar 
                        reticencia o inexactitud.
                        <br>
                        El asegurador no podrá alegar reticencia o inexactitud y declarar 
                        la nulidad del contrato, en los siguientes casos.
                		<br><br>
                        
                        
                        <table 
                            cellpadding="0" cellspacing="0" border="0" 
                            style="width: 99%; height: auto;">
                          <tr>
                            <td style="width:3%" valign="top">1.</td>
                            <td style="width:97%; text-align: justify">Si la reticencia o inexactitud no 
                            fueran de tanta relevancia como las que de ser conocidas por el asegurador, 
                            éste no incorporaría al asegurado en el contrato de seguro, o de hacerlo, 
                            lo haría en condiciones distintas.</td>
                          </tr>
                          <tr>
                            <td style="width:3%" valign="top">2.</td>
                            <td style="width:97%; text-align: justify">Si el asegurador extiende la 
                            póliza al asegurado sin exigir la Declaración de Salud en relación 
                            al estado de riesgo.                       
                            </td>
                          </tr>
                          <tr>
                            <td style="width:3%" valign="top">3.</td>
                            <td style="width:97%; text-align: justify">Si el asegurado al momento de
                             su declaración de salud no conocía el estado del riesgo
                            </td>
                          </tr>
                          <tr>
                            <td style="width:3%" valign="top">4.</td>
                            <td style="width:97%; text-align: justify">Si el asegurador no pidió antes de la 
                            extensión del Certificado de Cobertura Individual, las aclaraciones en
                            puntos manifiestamente vagos y/o imprecisos de las declaraciones.
                            </td>
                          </tr>
                          <tr>
                            <td style="width:3%" valign="top">5.</td>
                            <td style="width:97%; text-align: justify">Si el asegurador por otros 
                            medios tuvo conocimiento del verdadero estado del riesgo.                        
                            </td>
                          </tr>
                          <tr>
                            <td style="width:3%" valign="top">6.</td>
                            <td style="width:97%">Si la reticencia o inexactitud no tiene relación con 
                            la producción del siniestro o sus efectos.                        
                            </td>
                          </tr>
                          <tr>
                            <td style="width:3%" valign="top">7.</td>
                            <td style="width:97%; text-align: justify">La validez de esta póliza y su cobertura
                            no será discutida después de transcurridos los dos años desde el momento 
                            de la fecha de desembolso del crédito.
                            </td>
                          </tr>
                          <tr>
                            <td style="width:2%" valign="top">8.</td>
                            <td style="width:97%; text-align: justify">Si dentro de los dos años desde la 
                            fecha de desembolso del crédito, el asegurador no ha intentado la impugnación o 
                            anulación de dicha cobertura por reticencia o inexactitud en las declaraciones del
                            asegurado, el asegurador pasado dicho plazo, está impedido de pretender
                            la impugnación o anulabilidad.
                        </td>
                          </tr>
                          <tr>
                            <td style="width:3%" valign="top">9.</td>
                            <td style="width:97%; text-align: justify">Por consiguiente, ninguna Declaración
                            hecha por el asegurado bajo esta póliza relativa a su asegurabilidad, se utilizará 
                            para impugnar o pretender la anulabilidad de la validez de la cobertura individual
                            respecto a la cual se hizo tal declaración, después de que la cobertura individual 
                            del seguro haya estado en vigor durante un periodo de dos años desde el momento del
                            desembolso de crédito.                        
                            </td>
                          </tr>
                          <tr>
                            <td style="width:3%" valign="top">10.</td>
                            <td style="width:97%; text-align: justify">El único motivo por el cual la
                            Entidad Aseguradora podrá impugnar o pretender  la anulabilidad de la cobertura 
                            individual pasado el plazo señalado, será por falta de pago de primas.
                        	</td>
                          </tr>
                        </table>                        
                        
                        <br>                      
                      
                        <span style="font-weight: bold;">INDISPUTABILIDAD:</span>                        
                        La validez de esta póliza y su cobertura no será discutida después
                        de transcurridos los dos años desde el momento de la fecha de desembolso
                        del crédito. Si dentro de los dos años desde la fecha de desembolso del crédito,
                        el asegurador no ha intentado la impugnación o anulación de dicha cobertura por 
                        reticencia o inexactitud en las declaraciones del asegurado, el asegurador 
                        pasado dicho plazo, está impedido de pretender la impugnación o anulabilidad.
						<br>
                        Por consiguiente, ninguna Declaración hecha por el asegurado bajo esta 
                        póliza relativa a su asegurabilidad, se utilizará para impugnar o pretender 
                        la anulabilidad de la validez de la cobertura individual respecto a la cual 
                        se hizo tal declaración, después de que la cobertura individual del seguro 
                        haya estado en vigor durante un periodo de dos años desde el momento del desembolso
                        de crédito. El único motivo por el cual la Entidad Aseguradora podrá impugnar 
                        o pretender la anulabilidad de la cobertura individual pasado el plazo señalado,
                        será por la falta de pago de primas.
                        <br>                      
                    </td>
                    <td style="width: 50%; text-align: justify; padding: 5px;" valign="top">
                        <span style="font-weight: bold;">SUICIDIO:</span>
                        El suicidio de ocurrir después de dos años desde el desembolso del crédito,
                        no libera de sus obligaciones a la Entidad Aseguradora de pagar la indemnización
                        correspondiente.
                        <br>
                        <span style="font-weight: bold;">PRIMA Y REHABILITACION:</span>
                        Es obligación del asegurado pagar la prima conforme a lo convenido. 
                        El contrato caduca si no se pagan las primas en los términos convenidos, 
                        sin embargo, la caducidad no se produce de hecho sino después de transcurrido
                        el plazo de treinta días de la fecha de vencimiento para su pago.
						<br>
						Si el seguro caduca por falta de pago primas, el asegurado o el tomador 
                        del seguro puede, en cualquier momento, rehabilitar el contrato, con el 
                        pago de las primas atrasadas, sin la necesidad de examen médico.
     					<br>
                        <span style="font-weight: bold;">PROCEDIMIENTO EN CASO DE SINIESTRO:</span>
                        El asegurado o beneficiario, tan pronto y a más tardar dentro de los treinta (30)
                        días calendario de tener conocimiento del siniestro, deben comunicar tal hecho
                        al asegurador, salvo fuerza mayor o impedimento justificado.
                        <br>                       
                        El asegurador podrá liberarse de sus obligaciones cuando compruebe que el asegurado o
                        beneficiario, según el caso, omita dar el aviso dentro del plazo establecido, con el fin de
                        impedir la comprobación oportuna de las circunstancias del siniestro o el de la magnitud
                        de los daños.
						<br>
                        A fin de establecer la procedencia o no de la cobertura del siniestro, la información
                        requerida por el asegurador estará limitada a información y evidencia relacionadas a 
                        los hechos y circunstancias del siniestro que permitan determinar al asegurador la causa
                        del siniestro. Toda información requerida por el asegurador que no se encuentre enmarcada
                        a la limitación establecida, no será considerada para efectos del establecimiento del
                        derecho del asegurado.
                        <br>                        
                        
                        <span style="font-weight: bold;">Documentación para el pago del Capital Asegurado en
                        caso de Muerte del Asegurado:</span>
                        <br><br>
                        
                        
<table 
                            cellpadding="0" cellspacing="0" border="0" 
                            style="width: 100%; height: auto; font-size: 100%; 
                                font-family: Arial;">
                          <tr>
                            <td style="width:3%" valign="top">&nbsp;-&nbsp;</td>
                            <td style="width:97%; text-align: justify;" >Certificado de Defunción extendido por
                            Oficial de Registro Civil. Si el Asegurado hubiera fallecido fuera del país,
                            el indicado Certificado deberá llevar las legalizaciones correspondientes del 
                            consulado boliviano del país donde hubiera ocurrido el hecho, y el de la autoridad 
                            competente en territorio del Estado Plurinacional de Bolivia.</td>
                          </tr>
                          <tr>
                            <td style="width:3%" valign="top">&nbsp;-&nbsp;</td>
                            <td style="width:97%; text-align: justify;">Declaración Médica de invalidez, 
                            emitida por un médico calificador debidamente registrado en la APS, o por el
                            Instituto Nacional de Salud Ocupacional (INSO).
                        	</td>
                          </tr>
                          <tr>
                            <td style="width:3%" valign="top">&nbsp;-&nbsp;</td>
                            <td style="width:97%; text-align: justify;">Documento de identificación del asegurado.</td>
                          </tr>
                        </table>
                        
                        <br>
                        
                        <span style="font-weight: bold;">Documentación para el pago del Capital Asegurado 
                        en caso de Invalidez Total y Permanente:</span>                      
                        
                        <br><br>
                        
                        <table 
                            cellpadding="0" cellspacing="0" border="0" 
                            style="width: 100%; height: auto;">
                          <tr>
                            <td style="width:3%" valign="top">&nbsp;-&nbsp;</td>
                            <td style="width:97%"> Informe detallado del Médico o médicos que hayan tratado
                            al asegurado, con indicación del origen, desarrollo y de las consecuencias de la 
                            enfermedad o de las lesiones causantes de la invalidez total y permanente.</td>
                          </tr>
                          <tr>
                            <td style="width:3%" valign="top">&nbsp;-&nbsp;</td>
                            <td style="width:97%">Declaración Médica de invalidez, emitida por un médico calificador
                            debidamente registrado en la APS, o por el Instituto Nacional de Salud Ocupacional (INSO).
                        	</td>
                          </tr>
                          <tr>
                            <td style="width:3%" valign="top">&nbsp;-&nbsp;</td>
                            <td style="width:97%">Documento de identificación del asegurado.</td>
                          </tr>
                        </table>                                          
                        
                        <br>
                        El asegurador debe pronunciarse sobre el derecho del asegurado o beneficiario
                        dentro de los treinta (30) días calendario siguientes de recibida la información 
                        y evidencia requeridas. Dicho plazo fenece con la aceptación o rechazo del siniestro
                        o con la solicitud del asegurador al asegurado que se completen los requerimientos y
                        no vuelve a correr hasta que el asegurado haya cumplido con tales requerimientos. La 
                        solicitud de complementos, por parte del asegurador no podrá extenderse por más de 
                        dos veces a partir de la primera solicitud de informes y evidencias, debiendo 
                        pronunciarse dentro del plazo establecido y de manera definitiva sobre el derecho
                        del asegurado, después de la entrega por parte del asegurado del último requerimiento
                        de información. El silencio del asegurador, vencido el termino para pronunciarse o 
                        vencidas las solicitudes de complementación, importa la aceptación de la cobertura del 
                        siniestro, caducando el derecho del asegurador a oponer cualquier exclusión o condición 
                        de la póliza.
                        <br>
                        Establecido el derecho del asegurado, el asegurador debe pagar la obligación según
                        el presente contrato, dentro de los quince (15) días calendario siguientes. El asegurador
                        incurre en mora vencido este plazo y procederá el pago adicional de intereses sobre el
                        capital no pagado entre la fecha límite de pago y la fecha de pago efectivo, que se
                        calcularán diariamente aplicando la tasa promedio ponderada del sistema financiero para
                        depósitos a plazo fijo en moneda nacional a 180 días, publicada por el Banco Central del
                        Estado Plurinacional de Bolivia.                                              
                        <br>                        
                        <span style="font-weight: bold;">PERDIDA DEL DERECHO A LA IMDEMNIZACIÓN: </span>
                        El asegurado o el beneficiario pierde el derecho a la indemnización o
                        prestaciones del seguro, cuando:
                        <br><br>
                        
                        <table 
                            cellpadding="0" cellspacing="0" border="0" 
                            style="width: 100%; height: auto;">
                          <tr>
                            <td style="width:3%" valign="top">1.</td>
                            <td style="width:97%; text-align: justify">Provoquen dolosamente el siniestro.</td>
                          </tr>
                          <tr>
                            <td style="width:3%" valign="top">2.</td>
                            <td style="width:97%; text-align: justify">Oculten o alteren, maliciosamente,
                            en la verificación del siniestro, los hechos y circunstancias relacionados 
                            al aviso del siniestro y la documentación requerida por la Entidad Aseguradora. </td>
                          </tr>
                          <tr>
                            <td style="width:3%" valign="top">3.</td>
                            <td style="width:97%; text-align: justify">Recurran a pruebas falsas con el ánimo
                            de obtener un beneficio ilícito.</td>
                          </tr>
                        </table>
                                                  
                        <br>    
                                            
                        En cualquiera de estos casos, el asegurado pierde además el derecho
                        a la devolución de las primas o valores garantizados, sin perjuicio 
                        de las sanciones penales.
                        <br>
						<span style="font-weight: bold;">CONTROVERSIAS: </span>
                        Toda controversia de hecho podrá resolverse mediante peritaje. Si por la
                        vía del peritaje no se llegara a un acuerdo sobre la controversia, la misma se
                        definirá por la vía del arbitraje.
                        <br>
                        Las controversias de derecho suscritas entre las partes sobre la naturaleza 
                        y alcance del contrato de seguro, serán resueltas en única e inapelable 
                        instancia, por la vía del arbitraje, de acuerdo a lo previsto en la Ley 1770 
                        (Ley de Conciliación y Arbitraje). La Autoridad de Fiscalización y Control de
                        Pensiones y Seguros podrá fungir como instancia de conciliación, para todo el 
                        siniestro cuya cuantía no supere el monto de UFV 100.000,000.- (Cien Mil 00/100 
                        Unidades de Fomento a la Vivienda). Si por esta vía no existiera un acuerdo,
                        la Autoridad de Fiscalización y Control de Pensiones y Seguros podrá conocer y 
                        resolver la controversia por resolución administrativa debidamente motivada.
                        <!--
                        <br>
                        <br>
                        <br>
                        <table cellpadding="0" cellspacing="0" border="0"
                            style="width: 100%; font-size: 100%;">
                            <tr>
                                <td style="width: 60%;">
                                    <img src="<?=$url;?>img/firma-crecer.jpg"
                                        height="70" class="container-logo" align="left" />
                                </td>
                                <td style="width: 20%; text-align: center; vertical-align: bottom; ">Titular</td>
                                <td style="width: 20%; text-align: center; vertical-align: bottom; "></td>
                            </tr>
                        </table>
                        -->
                    </td>
                </tr>
            </table>
        </div>
        
        
        <page><div style="page-break-before: always;">&nbsp;</div></page>
        
        <div style="width: 775px; border: 0px solid #FFFF00; ">
            <table
                cellpadding="0" cellspacing="0" border="0"
                style="width: 100%; height: auto; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 80%;"></td>
                    <td style="width: 20%;">
                        <img src="<?=$url;?>images/logosudecs.jpg"
                            height="20" class="container-logo" align="left" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100%; text-align: center; font-size: 90%;
                        font-weight: bold; " colspan="2">
                        ENTREGA DE CERTIFICADO DE DESGRAVAMEN
                        </td>
                </tr>
            </table>
        </div>
        <br>

        <div style="width: 775px; border: 0px solid #FFFF00; font-size: 85%; text-align:justify"> 

   			A tiempo de agradecerles por la confianza depositada en SUDAMERICANA S.R.L., hacemos 
            la entrega del Certificado Único de Desgravamen y las condiciones de la póliza de 
            Desgravamen con la Compañía de Seguros <?=$row['compania'];?> :
            <ol style="list-style:disc;" >
                <li><span style="font-weight: bold;">Certificado Único de Desgravamen: el cual contempla:</span>
                    <ol style="list-style:circle; width:96%;">
                        <li>Nombre del Asegurado y Beneficiario.</li>
                        <li>Inicio de Vigencia.</li>
                        <li>Nombre y Dirección del Intermediario.</li>
                        <li>Coberturas y Capital asegurado durante la vigencia.</li>
                        <li>Tasa por riesgo y total; la prima a ser pagada por el asegurado,
                        será calculada de acuerdo a la tasa mensual descrita en el Certificado 
                        de Desgravamen, aplicada al Saldo Insoluto de forma mensual.</li>
                        <li>Límites de edad de permanencia en el seguro de acuerdo a cobertura.</li>
                        <li>Vigencia del certificado: Mensual Renovable.</li>
                    </ol>
                </li>
                <li><span style="font-weight: bold;">Condiciones de la Póliza que se explica 
                    claramente en los siguientes puntos:</span>
                    <ol style="list-style:circle; width:96%;">
                        <li><span style="font-weight: bold;">Fallecimiento:</span>
                        Es la Muerte por cualquier causa del asegurado, pagando el 
                        capital asegurado al beneficiario, si esto ocurre durante la
                        vigencia de la póliza.</li>
                        <li><span style="font-weight: bold;">Invalidez Total y Permanente:</span>
                        Se considera Invalidez Total y Permanente al estado de situación
                        física del asegurado que como consecuencia de una enfermedad o
                        accidente presenta una pérdida o disminución de su fuerza
                        física o intelectual igual o superior al 60% de su capacidad
                        de trabajo de acuerdo a las condiciones de la póliza.</li>
                        <li><span style="font-weight: bold;">Exclusiones de Cobertura:</span>
                            <ol style="list-style:disc; width:96%;">
                                <li>Intervención directa o indirecta del asegurado en actos delictuosos,
                                que le ocasionen la muerte.</li>
                                <li>Guerra internacional o civil, revolución, invasión, actos de enemigos
                                extranjeros, hostilidades u    operaciones bélicas, insurrección, sublevación,
                                rebelión, sedición, motín o hechos que las leyes califican como delitos contra
                                la seguridad del estado.</li>
                                <li>Fisión, fusión nuclear o contaminación radioactiva.</li>
                            </ol>
                        </li>
                        <li>Obligaciones de declarar del asegurado.</li>
                        <li>Indisputabilidad de la póliza.</li>
                        <li>Condiciones de ocurrir un evento de Suicidio.</li>
                        <li>Prima y rehabilitación del seguro.</li>
                        <li><span style="font-weight: bold;">Procedimientos en Caso de Siniestro:</span>
                        El asegurado o beneficiario, tan pronto y a mas tardar dentro de los treinta (30)
                        días calendario de tener conocimiento del siniestro, deben comunicar tal hecho 
                        al asegurador, salvo fuerza mayor o impedimento justificado, de acuerdo a lo que
                        estipulan las condiciones de la póliza. El asegurador debe pronunciarse sobre el 
                        derecho del asegurado o beneficiario dentro de los treinta (30) días calendario 
                        siguientes de recibida la información y evidencia requerida requeridas precedentemente,
                        dicho plazo no corre termino hasta que se presente la totalidad de la información 
                        y evidencias requeridas de acuerdo a las condiciones de la póliza, el asegurador
                        debe pagar a obligación dentro de los quinces (15) días siguientes.
                        El asegurador incurre en mora vencido este plazo de acuerdo a condicionado de la póliza.
                        </li>
                        <li>Documentación necesaria para el pago del capital asegurado en caso
                        de muerte del asegurado.</li>
                        <li>Documentación necesaria para el pago del capital asegurado en caso
                        de Invalidez Total y Permanente.</li>
                        <li>Perdida del Derecho a la indemnización, provoquen dolosamente el siniestro,
                        oculten o alteren, maliciosamente, en la verificación del siniestro, los hechos
                        y circunstancias relacionados al aviso del siniestro y la documentación 
                        requerida por la entidad aseguradora, recurran a pruebas falsas con ánimo de obtener
                        un beneficio ilícito.</li>
                        <li>La <span style="font-weight: bold;">Reticencia o Inexactitud en las declaraciones 
                        del asegurado</span>, como también las declaraciones falsas o reticentes hechas con 
                        dolo o mala fe hacen nulo el certificado individual de cobertura. En este caso no tendrá
                        derecho a cobertura y tampoco a devolución de prima, por lo que hacen anulable el contrato
                        de seguro, de acuerdo a lo que estipulan las condiciones de la póliza.</li>
                        <li>Controversias: Toda controversia de hecho podrá resolverse mediante peritaje. 
                        Si por la vía del peritaje no se llegara a un acuerdo la misma se definirá por la
                        vía del arbitraje, hasta UFV (100.000.000 Cien Mil unidades de fomento a la vivienda)
                        la Autoridad de Fiscalización y Control de Pensiones y Seguros podrá conoce y resolver
                        la controversia por resolución administrativa.</li>
                    </ol>
                </li>
            </ol>
            
            <span style="font-weight: bold;">El Capital asegurado se la pagara a la entidad a la
            Entidad financiera tomadora del seguro siempre y cuando se dé total y debido cumplimento</span>
            a todos los puntos descritos en las condiciones particulares de la póliza, condiciones 
            generales de la póliza, certificado único de desgravamen, condiciones de la póliza y
            solicitud de seguro y declaración de salud, que son de conformidad y de conocimiento 
            del asegurado, quien da su conformidad a todas y cada una de las condiciones y estipulaciones
            establecidas por la Entidad Aseguradora.  
          	<br><br>
            <span style="font-weight: bold;">Sudamericana S.R.L en las obligaciones que le corresponde
            y como parte de su servicio al cliente, se pone a su total disposición para aclarar cualquier
            aspecto adicional que necesite respecto a la documentación entregada, como para la atención y
            asesoramiento de siniestros que afecten las coberturas mencionadas en la presente nota, para
            lo cual ponemos a su disposición nuestra línea 800-103070 y los numero de nuestras oficinas 
            que se encuentran al pie de página.</span>
            
            <br><br>
         
        	<table cellpadding="0" cellspacing="0" border="0"
                  style="width: 100%">
                  <tr>
                      <td style="width: 50%; border:1px solid #000;">
                          Nombres y Firma Sudamericana
                      </td>
                      <td style="width: 50%; border:1px solid #000; border-left:0px none; ">
                      <br>
                      Nombre Completo: <?=$rowDt['nombre']. ' '.$rowDt['paterno']. ' '.$rowDt['materno'];?>
                      <br>
                      CI: <?=$rowDt['dni']. ' ' .$rowDt['extension'];?>
                      <br><br><br>
                      <span style="font-weight: bold;">Firma del Cliente</span>
                      </td>
                  </tr>
             </table>
             
            <br><br>
            
            <div style="font-size:90%;">
            <span style="font-weight: bold;">La Paz, </span>   
            Prolongación Cordero Nº 163 - San Jorge - Telf.: (591) -2-2433500 Fax (591)-2-2128329
            <br>       
            <span style="font-weight: bold;">Santa Cruz, </span>   
            Equipetrol Calle Nº 8 Este Nº 25 Entre Canal Isuto y Avenida San Martin 
            Telf: (591)-3-3416055 Fax: (591)-3-3416056
            <br>       
            <span style="font-weight: bold;">Cochabamba, </span>   
            Calle Junín No. 990 esq. Costanera, Telf. (591)-4-4521280, Fax. (591)-4-4521281
            <br>       
            <span style="font-weight: bold;">Sucre, </span>   
            Calle Bolivar Nº 266 Ed. Bolivar Pb Telf.: (591)-4-6429081 (591)-4-6913797 Fax (591)-4-6435348
            <br>       
            <span style="font-weight: bold;">Potosi, </span>   
            Calle Chayanta No. 1044 Galería Confort Ambiente No. 5, Zona Central telfs. 6227293, 6225768 Fax. 6223145
            <br>       
            <span style="font-weight: bold;">Tarija, </span>   
            Calle Santa Cruz No. 784, entre Bolivar e Ingavi, Tel./Fax 6672014
            <br> 
            </div>      
                       
        </div>        
        
<?php
            }

            if ($k < $nCl) {
?>
        <page><div style="page-break-before: always;">&nbsp;</div></page>
<?php
            }
        }
    }
?>



        <!--<div style="width: 775px; border: 0px solid #FFFF00; ">
        </div>

        <div style="width: 775px; border: 0px solid #FFFF00; ">
        </div>

        <div style="width: 775px; border: 0px solid #FFFF00; ">
        </div>-->
    </div>
</div>
<?php
    $html = ob_get_clean();
    return $html;
}
?>