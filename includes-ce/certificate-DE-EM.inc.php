<?php
function de_em_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
    $emitir = (boolean)$row['emitir'];

    if ($emitir === true) {
        $row['fecha_emision'] = $row['fecha_emision'];
    } else {
        $row['fecha_emision'] = $row['fecha_creacion'];
    }

    $row['fecha_emision'] = $row['u_departamento'] . ', ' . date('d/m/Y', strtotime($row['fecha_emision']));

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
    $nbc = '';
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

            $nbc = ' - ' . $k;
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

        $res = json_decode($rowDt['respuesta'], true);
        $obs = json_decode($rowDt['observacion'], true);

        if ($show_tit === true) {
?>
       <div style="width: 775px; border: 0px solid #FFFF00; ">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
                <tr>
                    <td style="width: 80%; text-align: left; font-style: italic; 
                        font-size: 180%; " >
                        Declaración de Salud y/o Solicitud de Seguro de Desgravamen
                    </td>
                    <td style="width: 20%;">
                        <img src="<?=$url;?>images/<?=$row['logo_cia'];?>" 
                            height="40" class="container-logo" align="left" />
                    </td>
                </tr>
                <!--<tr>
                    <td style="width: 100%;" colspan="2">
                        (Llenar si el Saldo Deudor en créditos con el Tomador + el crédito 
                        solicitado es mayor a US$. 15,000.00)
                    </td>
                </tr>-->
                <tr>
                    <td style="width: 100%; text-align: right; font-size: 120%; color: #F00;" colspan="2">
                        N° <?=$row['no_emision'] . $nbc;?>
                    </td>
                </tr>
            </table>
            <br>

            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 85%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 20%; font-size: 130%; font-weight: bold; ">Tipo de Seguro</td>
                    <td style="width: 5%;" align="center">
                        <div style="width: 10px; height: 10px; border: 1px solid #000;">
                            <?=$coverage[0];?>
                        </div>
                    </td>
                    <td style="width: 20%;">Individual</td>
                    <td style="width: 5%;" align="center">
                        <div style="width: 10px; height: 10px; border: 1px solid #000;">
                            <?=$coverage[1];?>
                        </div>
                    </td>
                    <td style="width: 20%;">Mancomunada</td>
                    <td style="width: 5%;" align="center">
                        <div style="width: 10px; height: 10px; border: 1px solid #000;">
                            <?=$coverage[2];?>
                        </div>
                    </td>
                    <td style="width: 25%;">Otros</td>
                </tr>
            </table>
            <br>

            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 50%;">
                        Favor utilice letra de imprenta y legible.
                        <!--Si hubiese más solicitantes (cotitulares), deben completar Declaraciones de Salud adicionales.-->
                    </td>
                    <td style="width: 50%;">
                        <table cellpadding="0" cellspacing="0" border="0" 
                            style="width: 100%; font-size: 100%; ">
                            <tr>
                                <td style="width: 20%;">Lugar y Fecha</td>
                                <td style="width: 80%; border-bottom: 1px solid #000;
                                    border-right: 1px solid #000; font-weight: bold; font-size: 120%; ">
                                    <?=$row['fecha_emision'];?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!--<tr>
                    <td style="width: 100%;" colspan="2">
                        Favor utilice letra de imprenta y legible.
                    </td>
                </tr>-->
            </table>
        </div>
        <br>

        <div style="text-align: left; font-size: 90%; font-weight: bold;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 100%; background: #000; color: #fff;" colspan="4" >
                        DATOS PERSONALES - TITULAR
                    </td>
                </tr>
                <tr>
                    <td style="width: 25%; height: 10px; ">Nombres</td>
                    <td style="width: 25%;">Apellido Paterno</td>
                    <td style="width: 25%;">Apellido Materno</td>
                    <td style="width: 25%;">Apellido de Casada</td>
                </tr>
                <tr>
                    <td style="width: 25%; height: 10px; border-left: 1px solid #000; 
                        border-bottom: 1px solid #000;">
                        <?=$rowDt['nombre'];?>&nbsp;
                    </td>
                    <td style="width: 25%; border-left: 1px solid #000; border-bottom: 1px solid #000;">
                        <?=$rowDt['paterno'];?>&nbsp;
                    </td>
                    <td style="width: 25%; border-left: 1px solid #000; border-bottom: 1px solid #000;">
                        <?=$rowDt['materno'];?>&nbsp;
                    </td>
                    <td style="width: 25%; border-left: 1px solid #000; border-bottom: 1px solid #000;">
                        <?=$rowDt['ap_casada'];?>&nbsp;
                    </td>
                </tr>
            </table>

            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 33%;">Fecha de nacimiento</td>
                    <td style="width: 34%;">Peso (Kg.)</td>
                    <td style="width: 33%;">Talla (m.)</td>
                </tr>
                <tr>
                    <td style="width: 33%; height: 10px; border-left: 1px solid #000; 
                        border-bottom: 1px solid #000;">
                        <?=date('d/m/Y', strtotime($rowDt['fecha_nacimiento']));?>
                    </td>
                    <td style="width: 34%; border-left: 1px solid #000; border-bottom: 1px solid #000;">
                        <?=$rowDt['peso'];?>
                    </td>
                    <td style="width: 33%; border-left: 1px solid #000; border-bottom: 1px solid #000;">
                        <?=$rowDt['estatura'] / 100;?>
                    </td>
                </tr>
            </table>

            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 30%; height: 10px; font-weight: bold; ">Tipo de Documento</td>
                    <td style="width: 20%;"></td>
                    <td style="width: 15%;"></td>
                    <td style="width: 35%;">Sexo</td>
                </tr>
                <tr>
                    <td style="width: 30%; height: 10px; ">
                        <table cellpadding="0" cellspacing="0" border="0" 
                            style="width: 100%; font-size: 100%; ">
                            <tr>
                                <td style="width: 20%;" align="center">
                                    <div style="width: 10px; height: 10px; border: 1px solid #000;">
                                        <?=$ci[0];?>
                                    </div>
                                </td>
                                <td style="width: 30%;">CI</td>
                                <td style="width: 10%;">
                                    <div style="width: 10px; height: 10px; border: 1px solid #000;">
                                        <?=$ci[1];?>
                                    </div>
                                </td>
                                <td style="width: 20%;">Otro</td>
                                <td style="width: 20%; text-align: right;">
                                    N° Ext.
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 20%; border-left: 1px solid #000; border-bottom: 1px solid #000;">
                        <?=$rowDt['dni'] . $rowDt['complemento'];?>
                    </td>
                    <td style="width: 15%; border-left: 1px solid #000; border-bottom: 1px solid #000;
                        border-right: 1px solid #000;">
                        <?=$rowDt['extension'];?>
                    </td>
                    <td style="width: 35%; ">
                        <table cellpadding="0" cellspacing="0" border="0" 
                            style="width: 100%; font-size: 100%; ">
                            <tr>
                                <td style="width: 25%;" align="center">
                                    Mujer
                                </td>
                                <td style="width: 25%;">
                                    <div style="width: 10px; height: 10px; border: 1px solid #000;">
                                        <?=$sex[1];?>
                                    </div>
                                </td>
                                <td style="width: 25%;">Hombre</td>
                                <td style="width: 25%;">
                                    <div style="width: 10px; height: 10px; border: 1px solid #000;">
                                        <?=$sex[0];?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 55%; height: 10px; ">Dirección</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 17%;">Barrio / Zona</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 18%;">Ciudad / Localidad</td>
                </tr>
                <tr>
                    <td style="width: 55%; height: 10px; border-left: 1px solid #000; 
                        border-bottom: 1px solid #000; border-right: 1px solid #000;">
                        <?=$rowDt['direccion'];?>&nbsp;
                    </td>
                    <td style="width: 5%; "></td>
                    <td style="width: 17%; border-left: 1px solid #000; 
                        border-bottom: 1px solid #000; border-right: 1px solid #000;">
                        &nbsp;
                    </td>
                    <td style="width: 5%; "></td>
                    <td style="width: 18%; border-left: 1px solid #000; 
                        border-bottom: 1px solid #000; border-right: 1px solid #000;">
                        <?=$rowDt['lugar_residencia'];?>&nbsp;
                    </td>
                </tr>
            </table>

            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 12%; height: 10px; ">Telf. Dom.</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 11%;">Telf. Oficina</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 12%;">Tel. Celular</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 50%;">Actividad principal que desempeña</td>
                </tr>
                <tr>
                    <td style="width: 12%; height: 10px; border-left: 1px solid #000; 
                        border-bottom: 1px solid #000; border-right: 1px solid #000;">
                        <?=$rowDt['telefono_domicilio'];?>&nbsp;
                    </td>
                    <td style="width: 5%; "></td>
                    <td style="width: 11%; border-left: 1px solid #000; 
                        border-bottom: 1px solid #000; border-right: 1px solid #000;">
                        <?=$rowDt['telefono_oficina'];?>&nbsp;
                    </td>
                    <td style="width: 5%; "></td>
                    <td style="width: 12%; border-left: 1px solid #000; 
                        border-bottom: 1px solid #000; border-right: 1px solid #000;">
                        <?=$rowDt['telefono_celular'];?>&nbsp;
                    </td>
                    <td style="width: 5%; "></td>
                    <td style="width: 50%; border-left: 1px solid #000; 
                        border-bottom: 1px solid #000; border-right: 1px solid #000;">
                        <?=$rowDt['ocupacion'] . ' - ' . $rowDt['desc_ocupacion'];?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 100%;" colspan="7">
                        <span style="font-weight: bold;">Nota</span> : 
                        Los nombres y apellidos deben aparecer como en el documento de identidad.
                    </td>
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
        <div style="width: 775px; border: 0px solid #FFFF00; ">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 100%; background: #000; color: #fff;" colspan="5" >
                        DECLARACIÓN DE SALUD DE LA PERSONA (Marque la respuesta según corresponda)
                    </td>
                </tr>
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
                        <!--¿Ha padecido o padece cualquier enfermedad que requiera tratamiento, medicación o control frecuente?-->
                        ¿Ha padecido o padece cualquier enfermedad?
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
                    <td style="width: 70%;">
                        ¿Tiene algún defecto físico o congénito?
                    </td>
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
                    <td style="width: 70%;">
                        ¿Le han detectado algún tumor o efectuado alguna prueba para descartar cáncer?
                    </td>
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
                    <td style="width: 70%;">
                        ¿Ha sido sometido o le recomendaron alguna operación quirúrgica (en los últimos 5 años)?
                    </td>
                    <td style="width: 5%; border-left: 1px solid #000; border-bottom: 1px solid #000;">
                        <div style="width: 10px; height: 10px; margin-left: 12px; border: 1px solid #000;">
                            <?=$response[4][0];?>
                        </div>
                    </td>
                    <td style="width: 5%; border-right: 1px solid #000; border-bottom: 1px solid #000;">
                        <div style="width: 10px; height: 10px; margin-left: 12px; border: 1px solid #000;">
                            <?=$response[4][1];?>
                        </div>
                    </td>
                    <td style="width: 5%;"></td>
                </tr>
                <tr>
                    <td colspan="5">
                        <br>
                        En caso de marcar SÍ en alguna de las preguntas 1 a 4, detallar las mismas 
                        señalando además cuándo ocurrió, duración, tratamiento, fecha de curación,
                        secuelas, observaciones u otros.
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

        <div style="width: 775px; border: 0px solid #FFFF00; font-size: 75%;">
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

            a)<span style="margin:0 15px;">Declaro</span> que las respuestas que he consignado 
            en esta solicitud son verdaderas y completas y que es de mi conocimiento que 
            cualquier declaración falsa, inexacta, omitida u oculta, hará perder todos los 
            beneficios del seguro.
            <br>
            b)<span style="margin:0 15px;">Igualmente</span> declaro haber leído y estar de 
            acuerdo con el Certificado de Cobertura Individual, que entrará en vigencia una 
            vez aceptada la solicitud y desembolsado el crédito.
            <br>
            c)<span style="margin:0 15px;">Declaro</span> beneficiario a título oneroso de 
            esta póliza al Tomador, para el pago de la suma asegurada existente, en caso de 
            siniestro cubierto de acuerdo a los términos y condiciones del Seguro.
            <br>
            d)<span style="margin:0 15px;">Autorizo</span> a los médicos, clínicas, hospitales 
            y otros centros de salud que me hayan atendido o que me atiendan en el futuro, para 
            que proporcionen a Crediseguro S.A. Seguros Personales, todos los resultados de los 
            informes referentes a mi salud, en caso de enfermedad o accidente, para lo cual 
            releva a dichos médicos y centros médicos en relación con su secreto profesional, 
            de toda responsabilidad en que pudiera incurrir al proporcionar tales informes. 
            Asimismo, autorizo a Crediseguro S.A. Seguros Personales a proporcionar estos 
            resultados al Tomador.
            <br>
            e)<span style="margin:0 15px;">El</span> ASEGURADO se compromete a realizarse las 
            pruebas médicas que solicite La Compañía, incluyendo las de VIH/SIDA, de ser el caso, 
            y autoriza a cualquier médico, hospital, clínica, compañía de seguros u otra 
            institución o persona que tenga conocimiento o registros de su persona o salud, 
            para que pueda dar cualquier información solicitada por La Compañía, incluyendo la 
            referida al VIH/SIDA.
            <br>
            f)<span style="margin:0 15px;">EL</span> ASEGURADO acepta la presentación, con 
            calidad de Declaración Jurada, de la documentación de respaldo que solicitara La 
            Compañía misma que será requerida en virtud a la obligación normativa regulatoria 
            que éste mantiene respecto a los controles e informes que realiza por instrucción 
            de la Unidad de Investigaciones Financieras u otras entidades fiscalizadoras.
            <br>
            La firma de la presente Solicitud manifiesta de manera explícita voluntaria mi intención 
            de tomar el seguro, cuyas condiciones generales conozco y cumplir con el pago de las primas 
            correspondientes, sólo si la solicitud fuera aceptada por la Compañía.
            <br>
            <span style="font-weight: bold;">NOTA</span>: La Compañía se reserva el derecho de 
            solicitar examen (es) médico (s) o confirmación adicional.
            <br><br><br><br><br><br><br>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 33%;"></td>
                    <td style="width: 34%; border-top: 1px solid #000; text-align: center;">
                        FIRMA DEL SOLICITANTE (TITULAR)
                        <br><br>
                    </td>
                    <td style="width: 33%;"></td>
                </tr>
            </table>

        </div>
<?php
        $currency = array('', '');
        switch ($row['moneda']) {
        case 'USD':
            $currency[0] = 'X';
            break;
        case 'BS':
            $currency[1] = 'X';
            break;
        }

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
        <div style="width: 775px; border: 0px solid #FFFF00; ">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 100%; background: #000; color: #fff;" colspan="4">
                        DATOS A SER LLENADOS POR LA ENTIDAD FINANCIERA
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%; height: 5px;"></td>
                    <td style="width: 10%;"></td>
                    <td style="width: 45%;"></td>
                    <td style="width: 40%;"></td>
                </tr>
                <tr>
                    <td style="width: 5%;"></td>
                    <td style="width: 10%;">Tipo de Crédito:</td>
                    <td style="width: 45%; border-right: 1px solid #000; 
                        border-bottom: 1px solid #000; height: 15px;">
                        <?=$row['tipo_credito'];?>
                    </td>
                    <td style="width: 40%;"></td>
                </tr>
                <tr>
                    <td style="width: 5%;"></td>
                    <td style="width: 10%; height: 15px;">Moneda:</td>
                    <td style="width: 45%;">
                        <table cellpadding="0" cellspacing="0" border="0" 
                            style="width: 100%; font-size: 100%;" >
                            <tr>
                                <td style="width: 20%; text-align: center;">Dólares</td>
                                <td style="width: 20%;">
                                    <div style="width: 10px; height: 10px; border: 1px solid #000;">
                                        <?=$currency[0];?>
                                    </div>
                                </td>
                                <td style="width: 20%;">Bolivianos</td>
                                <td style="width: 20%;">
                                    <div style="width: 10px; height: 10px; border: 1px solid #000;">
                                        <?=$currency[1];?>
                                    </div>
                                </td>
                                <td style="width: 20%;"></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 40%;"></td>
                </tr>
            </table>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 5%;"></td>
                    <td style="width: 45%;">1. Cúmulo de desembolsos anteriores (no incluye esta operación)</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 45%;">Funcionario (nombre completo)</td>
                </tr>
                <tr>
                    <td style="width: 5%;"></td>
                    <td style="width: 45%; height: 11px; border-left: 1px solid #000;
                        border-bottom: 1px solid #000; border-right: 1px solid #000;">
                        <?=number_format($rowDt['saldo'], 2, '.', ',');?>
                    </td>
                    <td style="width: 5%;"></td>
                    <td style="width: 45%; height: 11px; border-left: 1px solid #000;
                        border-bottom: 1px solid #000; border-right: 1px solid #000;">
                        <?=$row['u_nombre'];?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%;"></td>
                    <td style="width: 45%;">2. Monto actual solicitado</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 45%;">Sucursal</td>
                </tr>
                <tr>
                    <td style="width: 5%;"></td>
                    <td style="width: 45%; height: 11px; border-left: 1px solid #000;
                        border-bottom: 1px solid #000; border-right: 1px solid #000;">
                        <?=number_format($row['monto_solicitado'], 2, '.', ',');?>
                    </td>
                    <td style="width: 5%;"></td>
                    <td style="width: 45%; height: 11px; border-left: 1px solid #000;
                        border-bottom: 1px solid #000; border-right: 1px solid #000;">
                        <?=$row['u_departamento'];?>
                    </td>
                </tr>

                <tr>
                    <td style="width: 5%;"></td>
                    <td style="width: 45%;">Monto actual acumulado (1+2)</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 45%;">Plazo del presente crédito</td>
                </tr>
                <tr>
                    <td style="width: 5%;"></td>
                    <td style="width: 45%; height: 11px; border-left: 1px solid #000;
                        border-bottom: 1px solid #000; border-right: 1px solid #000;">
                        <?=number_format($rowDt['cumulo'], 2, '.', ',');?>
                    </td>
                    <td style="width: 5%;"></td>
                    <td style="width: 45%; height: 11px; border-left: 1px solid #000;
                        border-bottom: 1px solid #000; border-right: 1px solid #000;">
                        <?=$row['plazo'] . ' ' . $row['tipo_plazo'];?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%;"></td>
                    <td style="width: 45%;"></td>
                    <td style="width: 5%;"></td>
                    <td style="width: 45%;">
                        <table cellpadding="0" cellspacing="0" border="0" 
                            style="width: 100%; font-size: 100%;">
                            <tr>
                                <td style="width: 20%;"></td>
                                <td style="width: 60%;">Sello y Firma</td>
                                <td style="width: 20%;"></td>
                            </tr>
                            <tr>
                                <td style="width: 20%; height: 15px; "></td>
                                <td style="width: 60%; border-left: 1px solid #000; 
                                    border-bottom: 1px solid #000; border-right: 1px solid #000;"></td>
                                <td style="width: 20%;"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <!--<table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 100%; background: #000; color: #fff;" colspan="10">
                        DATOS A SER LLENADOS POR LA ENTIDAD FINANCIERA
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%;" colspan="2">
                        Tipo de Operación Solicitada:
                    </td>
                    <td style="width: 5%;"></td>
                    <td style="width: 10%; text-align: right;">Única/Primera</td>
                    <td style="width: 5%;">
                        <div style="width: 10px; height: 10px; border: 1px solid #000;">
                            X
                        </div>
                    </td>
                    <td style="width: 10%; text-align: right;">Adicional</td>
                    <td style="width: 5%;">
                        <div style="width: 10px; height: 10px; border: 1px solid #000;"></div>
                    </td>
                    <td style="width: 15%; text-align: right;">Línea de Crédito</td>
                    <td style="width: 5%;">
                        <div style="width: 10px; height: 10px; border: 1px solid #000;"></div>
                    </td>
                    <td style="width: 20%;">Funcionario:</td>
                </tr>
                <tr>
                    <td style="width: 10%;">Tipo de Crédito</td>
                    <td style="width: 10%; text-align: right;">Hipotecario</td>
                    <td style="width: 5%;">
                        <div style="width: 10px; height: 10px; border: 1px solid #000;">
                            <?=$product[0];?>
                        </div>
                    </td>
                    <td style="width: 10%; text-align: right;">Comercial</td>
                    <td style="width: 5%;">
                        <div style="width: 10px; height: 10px; border: 1px solid #000;">
                            <?=$product[1];?>
                        </div>
                    </td>
                    <td style="width: 10%; text-align: right;">Consumo</td>
                    <td style="width: 5%;">
                        <div style="width: 10px; height: 10px; border: 1px solid #000;">
                            <?=$product[2];?>
                        </div>
                    </td>
                    <td style="width: 15%; text-align: right;">Otros *</td>
                    <td style="width: 5%;">
                        <div style="width: 10px; height: 10px; border: 1px solid #000;">
                            <?=$product[3];?>
                        </div>
                    </td>
                    <td style="width: 20%; border-left: 1px solid #000; border-bottom: 1px solid #000;">
                        <?=$row['u_nombre'];?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 10%;">Moneda:</td>
                    <td style="width: 10%; text-align: right;">Dólares</td>
                    <td style="width: 5%;">
                        <div style="width: 10px; height: 10px; border: 1px solid #000;">
                            <?=$currency[0];?>
                        </div>
                    </td>
                    <td style="width: 10%;">Bolivianos</td>
                    <td style="width: 5%;">
                        <div style="width: 10px; height: 10px; border: 1px solid #000;">
                            <?=$currency[1];?>
                        </div>
                    </td>
                    <td style="width: 10%;"></td>
                    <td style="width: 5%;"></td>
                    <td style="width: 15%;"></td>
                    <td style="width: 5%;"></td>
                    <td style="width: 20%;">Nombres</td>
                </tr>
                <tr>
                    <td style="width: 50%;" colspan="6">
                        1. Cúmulo de desembolsos anteriores (no incluye esta operación)
                    </td>
                    <td style="width: 5%;"></td>
                    <td style="width: 15%;"></td>
                    <td style="width: 5%;"></td>
                    <td style="width: 20%; border-left: 1px solid #000; border-bottom: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td style="width: 55%; border-left: 1px solid #000; 
                        border-bottom: 1px solid #000; border-right: 1px solid #000;" colspan="7">
                        <?=number_format($rowDt['saldo'], 2, '.', ',');?>
                    </td>
                    <td style="width: 15%;"></td>
                    <td style="width: 5%;"></td>
                    <td style="width: 20%;">Apellidos</td>
                </tr>
            </table>-->
            <!--<table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 55%;">2. Monto actual solicitado</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 20%;">Sucursal</td>
                    <td style="width: 20%;">Teléfono</td>
                </tr>
                <tr>
                    <td style="width: 55%; height: 10px; border-left: 1px solid #000; 
                        border-bottom: 1px solid #000; border-right: 1px solid #000;">
                        <?=number_format($row['monto_solicitado'], 2, '.', ',');?>
                    </td>
                    <td style="width: 5%;"></td>
                    <td style="width: 20%; border-left: 1px solid #000; border-bottom: 1px solid #000;">
                        <?=$row['u_departamento'];?>
                    </td>
                    <td style="width: 20%; border-left: 1px solid #000; border-bottom: 1px solid #000;">
                        <?=$row['fono_agencia'];?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 55%;">Monto actual acumulado (1+2)</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 20%;">Plazo del presente crédito</td>
                    <td style="width: 20%;"></td>
                </tr>
                <tr>
                    <td style="width: 55%; height: 10px; border-left: 1px solid #000; 
                        border-bottom: 1px solid #000; border-right: 1px solid #000;">
                        <?=number_format($rowDt['cumulo'], 2, '.', ',');?>
                    </td>
                    <td style="width: 5%;"></td>
                    <td style="width: 40%; border-left: 1px solid #000; border-bottom: 1px solid #000;" 
                        colspan="2">
                        <?=$row['plazo'] . ' ' . $row['tipo_plazo'];?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 55%;">Observaciones / Aclaraciones</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 20%;"></td>
                    <td style="width: 20%;"></td>
                </tr>
                <tr>
                    <td style="width: 55%; height: 10px; "></td>
                    <td style="width: 5%;"></td>
                    <td style="width: 40%; " colspan="2">
                        <table cellpadding="0" cellspacing="0" border="0"  style="width: 100%;">
                            <tr>
                                <td style="width: 25%;"></td>
                                <td style="width: 50%;">Sello y Firma</td>
                                <td style="width: 25%;"></td>
                            </tr>
                            <tr>
                                <td style="width: 25%; height: 10px; "></td>
                                <td style="width: 50%; border-left: 1px solid #000; 
                                    border-bottom: 1px solid #000; border-right: 1px solid #000;"></td>
                                <td style="width: 25%;"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="width: 100%;" colspan="4">
                        * Debe aclararse a qué tipo de crédito se refiere.
                    </td>
                </tr>
            </table>-->
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

<?php
            if ($emitir === true) {
?>
        <page><div style="page-break-before: always;">&nbsp;</div></page>
        <div style="width: 775px; border: 0px solid #FFFF00; ">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-weight: bold; font-family: Arial;">
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
                        Anexo 1 del Contrato de Crédito N°............
                        <br>
                        POLIZA DE SEGURO DE VIDA DESGRAVAMEN - CRECER
                        <br><br>
                        CERTIFICADO DE COBERTURA INDIVIDUAL 
                        <br>
                        <span style="font-weight: normal;">
                            Registrada en Autoridad de Fiscalización y Control de Pensiones y 
                            Seguros (APS) mediante
                            <br>
                            Resolución  Administrativa APS/DS/N° 734-2014 con Código de Registro 
                            N° 209-934909-2014 10 012 4001
                        </span>
                    </td>
                </tr>
            </table>
        </div>
        <br>
        
        <div style="width: 775px; border: 0px solid #FFFF00; font-size: 80%; ">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 85%; font-family: Arial;">
                <tr>
                    <td style="width: 50%; text-align: justify; padding: 5px;" valign="top">
                        <span style="font-weight: bold;">CONTRATANTE:</span> 
                        ASOCIACIÓN CRÉDITO CON EDUCACIÓN RURAL “CRECER” 
                        <br>
                        <span style="font-weight: bold;">PÓLIZA:</span> CRS-DESG-004
                        <br>
                        <span style="font-weight: bold;">ASEGURADO:</span> 
                        Prestatario(a) de el contratante, nominado en el 
                        Contrato de Crédito del que forma parte este Anexo y que 
                        cumpla con los límites de edad y requisitos de asegurabilidad 
                        de la Póliza.
                        <br>
                        <span style="font-weight: bold;">
                            BENEFICIARIO A TITULO ONEROSO: 
                        </span>
                        ASOCIACIÓN CRÉDITO CON EDUCACIÓN RURAL “CRECER”
                        <br>
                        <span style="font-weight: bold;">
                            CREDISEGURO S.A. SEGUROS PERSONALES
                        </span>
                         domiciliada en Av. José 
                        Ballivian No. 1059 (Calacoto), tercer piso, zona Sur de la 
                        ciudad de La Paz, teléfono 2175000, fax 2775716 (LA COMPAÑÍA), 
                        certifica que la persona prestataria del TOMADOR nominado en el 
                        contrato de Crédito del que forma parte este documento y que 
                        cumpla con los límites de edad y requisitos de asegurabilidad 
                        de la póliza se encuentra asegurada bajo la presente Póliza 
                        (ASEGURADO), contratada por ASOCIACIÓN CRÉDITO CON EDUCACIÓN 
                        RURAL CRECER  (EL TOMADOR).
                        <br>
                        La cobertura se inicia con el desembolso del Crédito, siempre 
                        que se haya cumplido con los requisitos de asegurabilidad y 
                        cuenten con la autorización de LA COMPAÑÍA.
                        <br>
                        <span style="font-weight: bold;">MANCOMUNOS</span> 
                        En caso de Créditos mancomunados, cada uno de los 
                        deudores es responsable por el 100% de la deuda. En caso de 
                        fallecimiento de uno de los mancómunos responsable mancomunadamente 
                        por el Crédito, LA COMPAÑÍA indemnizará el 100% del capital 
                        asegurado al Beneficiario a la primera muerte, siempre y cuando 
                        ambos mancómunos sean aceptados por LA COMPAÑÍA, firmen el contrato 
                        de Crédito, sean declarados en los listados mensuales, y se 
                        pague la prima correspondiente.
                        <br>
                        <span style="font-weight: bold;">COBERTURAS Y CAPITAL ASEGURADO: </span>
                        <table 
                            cellpadding="0" cellspacing="0" border="0" 
                            style="width: 100%; height: auto; font-size: 100%; 
                                font-weight: bold; font-family: Arial;">
                            <tr style="background: #000000;">
                                <td style="width: 75%; background: #000000; color: #ffffff; text-align: center;
                                    border: 1px solid #000;">
                                    COBERTURA PRINCIPAL 
                                </td>
                                <td style="width: 25%; background: #000000; color: #ffffff; text-align: center;
                                    border: 1px solid #000;">
                                    Capital Asegurado 
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 75%; font-weight: normal; border-left: 1px solid #000;">
                                    Muerte (Natural o Accidental) 
                                </td>
                                <td style="width: 25%; font-weight: normal; border-right: 1px solid #000;
                                    text-align: center;">
                                    Saldo Deudor 
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 75%; background: #000; color: #fff; text-align: center;
                                    border: 1px solid #000;">
                                    COBERTURAS ADICIONALES 
                                </td>
                                <td style="width: 25%; background: #000; color: #fff; text-align: center;
                                    border: 1px solid #000;">
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 75%; font-weight: normal; text-align: justify;
                                    border: 1px solid #000; border-right: 0 none;">
                                    Pago anticipado por Invalidez Total y Permanente: 
                                    A los efectos de la presente cobertura se considera 
                                    Invalidez Total y Permanente el hecho de que el 
                                    ASEGURADO, antes de llegar a los 65 años de edad, 
                                    quede incapacitado en por lo menos un 65%, a causa 
                                    de un estado crónico, debido a enfermedad, o a 
                                    lesión o a la pérdida de miembros o funciones, 
                                    que impida ejecutar cualquier trabajo y siempre 
                                    que el carácter de tal incapacidad sea reconocido.
                                </td>
                                <td style="width: 25%; font-weight: normal; border: 1px solid #000;
                                    border-left: 0 none; text-align: center;">
                                    Saldo Deudor
                                </td>
                            </tr>
                        </table>
                        
                        <span style="font-weight: bold;"></span>
                        <span style="font-weight: bold;">LIMITES DE EDAD POR COBERTURAS</span>
                        <br>
                        <span style="font-weight: bold;">Para Muerte (Natural o Accidental):</span> 
                        Ingreso desde los 15 años cumplidos hasta los 70 años 
                        cumplidos al momento del inicio de la Cobertura, edad 
                        límite de permanencia hasta los 75 años cumplidos.
                        <br>
                        <span style="font-weight: bold;">Para Invalidez Total y Permanente:</span> 
                        Ingreso desde los 15 años cumplidos hasta los 64 años 
                        cumplidos al momento del inicio de la Cobertura, edad 
                        límite de permanencia hasta los 65 años cumplidos.
                        <br>
                        <span style="font-weight: bold;">EXCLUSIONES:</span> 
                        <br>
                        <span style="font-weight: bold;">
                            Para la Cobertura de Muerte (Natural o Accidental):
                        </span> 
                        El contrato de seguro no cubre el fallecimiento del ASEGURADO 
                        cuando el deceso se produjera como consecuencia de:
                        <br>
                        a)<span style="margin-left: 10px;"> </span>
                        Pena de muerte o participación, directa o indirecta en 
                        calidad de autor o cómplice en cualquier acto delictivo. 
                        <br>
                        b)<span style="margin-left: 10px;"> </span>  
                        Guerra, guerra civil, invasión o acción de un enemigo 
                        extranjero, hostilidades u operaciones bélicas, ya sea bajo 
                        declaración de guerra o no. 
                        <br>
                        c)<span style="margin-left: 10px;"> </span>  
                        Confiscación, nacionalización, requisición hecha u 
                        ordenada por cualquier autoridad pública, nacional o local, 
                        ley marcial o Estado de sitio, rebelión, revolución insurrección, 
                        sedición, insubordinación, poder militar o usurpado, huelgas, 
                        motines, asonada, conmoción civil o popular de cualquier clase, 
                        daño malicioso, vandalismo, sabotaje y/o terrorismo, siempre 
                        que el ASEGURADO tenga participación activa en dichos hechos. 
                        <br>
                        d)<span style="margin-left: 10px;"> </span>  
                        Enfermedades prexistentes, o lesiones, o dolencias prexistentes, 
                        entendiéndose por tales cualquier lesión o enfermedad, o 
                        dolencia que afecte al ASEGURADO, que haya sido conocida por 
                        él y que haya sido diagnosticada con anterioridad a la 
                        incorporación del ASEGURADO a la Póliza. 
                        <br>
                        En todos estos casos, si ocurriese la muerte del Asegurado como 
                        consecuencia de una causa excluida, no corresponderá la 
                        devolución de Prima alguna.  
                        <br>
                        <span style="font-weight: bold;">Exclusiones para Invalidez Total y Permanente:</span>
                        <br>
                        a)<span style="margin-left: 10px;"> </span>   
                        Participación directa o indirecta, en calidad de autor o 
                        cómplice en cualquier acto delictivo.
                        <br>
                        b)<span style="margin-left: 10px;"> </span>   
                        Guerra, invasión, actos de enemigos extranjeros, 
                        hostilidades u operaciones bélicas, sea que haya habido 
                        o no declaración de guerra, servicio militar, revolución, 
                        insurrección, sublevación, rebelión, sedición, motín; o 
                        hechos que las leyes califican como delitos contra la 
                        Seguridad del Estado. 
                        <br>
                        c)<span style="margin-left: 10px;"> </span>   
                        Enfermedades, o lesiones, o dolencias prexistentes, 
                        entendiéndose por tales cualquier lesión o enfermedad, 
                        o dolencia que afecte al ASEGURADO, que haya sido conocida 
                        por él y que haya sido diagnosticada con anterioridad a la 
                        fecha de incorporación del ASEGURADO a la Póliza.
                        <br>
                        d)<span style="margin-left: 10px;"> </span>   
                        La práctica o el desempeño de alguna actividad, 
                        profesión u oficio claramente riesgoso, que no haya sido 
                        declarado por el ASEGURADO al momento de contratar el seguro 
                        o durante su vigencia y que no haya sido aceptado por la 
                        compañía mediante anexo expreso, sujeto a extra prima.
                        <br>
                        e)<span style="margin-left: 10px;"> </span>   
                        Falsas declaraciones, omisión o reticencia del ASEGURADO 
                        que puedan influir en la comprobación de su estado de invalidez.
                        <br> 
                        <span style="font-weight: bold;">PROCEDIMIENTO EN CASO DE SINIESTRO: </span>
                        <br>
                        El Beneficiario a título oneroso, tan pronto y a más tardar 
                        dentro de los noventa (90) días calendario de tener conocimiento 
                        del fallecimiento de alguno de los ASEGURADOS, deberá comunicar 
                        tal hecho a LA COMPAÑÍA, salvo fuerza mayor o impedimento 
                        justificado de acuerdo al artículo 1028 del Código de Comercio 
                        adjuntando pruebas del siniestro correspondiente. En caso de 
                        muerte presunta, ésta deberá acreditarse de acuerdo a ley.
                        <br>
                        Una vez recibidos los documentos probatorios  del fallecimiento 
                        del ASEGURADO, LA COMPAÑÍA en caso de conformidad, pagará 
                        el Capital Asegurado correspondiente al Beneficiario.
                        <br>
                        El asegurado o beneficiario, según el caso, tienen la 
                        obligación de facilitar, a requerimiento de LA COMPAÑÍA 
                        todas las informaciones que tengan sobre los hechos y 
                        circunstancias del siniestro, a suministrar las evidencias 
                        conducentes a la determinación de la causa, identidad de 
                        las personas o intereses asegurados y cuantía de los daños, 
                        así como permitir las indagaciones pertinentes necesarias a 
                        tal objeto de acuerdo a lo establecido en el artículo 1031 
                        del Código de Comercio. 
                        <br>
                        LA COMPAÑÍA podrá solicitar o recabar informes o pruebas 
                        complementarias. LA COMPAÑÍA debe pronunciarse sobre el 
                        derecho de EL TOMADOR dentro de los cinco (5) días de 
                        recibidos todos los informes, evidencias, documentos y/o 
                        requerimientos adicionales acerca de los hechos y circunstancias del 
                    </td>
                    <td style="width: 50%; text-align: justify; padding: 5px;" valign="top">
                        siniestro. Esta solicitud no podrá excederse por mas de 
                        dos veces a partir de la primera solicitud de informes y 
                        evidencias debiendo LA COMPAÑÍA pronunciarse dentro del 
                        plazo establecido y de manera definitiva sobre el derecho 
                        del ASEGURADO después de la entrega por parte del ASEGURADO 
                        del último requerimiento de información en base a lo 
                        establecido en la Ley 365 de fecha 23 de abril de 2013, 
                        Disposiciones Adicionales Segunda, Párrafo II. LA COMPAÑÍA 
                        procederá al pago del beneficio en el plazo máximo de 15 
                        días posteriores al aviso del siniestro o tan pronto sean 
                        llenados los requerimientos señalados.
                        <br>
                        La obligación de pagar el Capital Asegurado deberá ser 
                        cumplida por LA COMPAÑÍA en un solo acto, por su valor 
                        total y en dinero. Y quedará sujeta a los términos y 
                        condiciones establecidos en los Artículos 1031, 1033 y 
                        1034 del Código de Comercio.
                        <br>
                        El beneficiario deberá presentar a LA COMPAÑÍA la 
                        siguiente documentación además del Formulario de Aviso 
                        de Siniestro debidamente llenado y Certificado de Cobertura:
                        <br>
                        <span style="font-weight: bold;">Para Muerte por cualquier causa:</span>
                        <br>
                        a)<span style="margin-left: 10px;"> </span>  
                        Fotocopia del Certificado de Nacimiento o Fotocopia 
                        del Carnet de Identidad del ASEGURADO.
                        <br>
                        b)<span style="margin-left: 10px;"> </span>  
                        Certificado de Defunción Original
                        <br>
                        c)<span style="margin-left: 10px;"> </span>  
                        Liquidación de cartera con el monto indemnizable
                        <br>
                        d)<span style="margin-left: 10px;"> </span>  
                        Fotocopia del contrato de préstamo y la Planilla de 
                        Desembolso o la Planilla de solicitud de Préstamo en 
                        caso de Banca Comunal. 
                        <br>
                        e)<span style="margin-left: 10px;"> </span>  
                        Extracto del préstamo por modalidad de Crédito.
                        <br>
                        LA COMPAÑÍA se reserva el derecho de exigir a las autoridades 
                        competentes y a su costa, la autopsia o la exhumación del 
                        cadáver para establecer las causas de la muerte. El beneficiario 
                        y/o sucesores deben prestar su colaboración y concurso para 
                        la obtención de las correspondientes autorizaciones oficiales. 
                        Si el beneficiario y/o los sucesores se negaran a permitir 
                        dicha autopsia o exhumación, o la retardaran en la forma que 
                        ella sea útil para el fin perseguido, el beneficiario perderá 
                        el derecho a la indemnización del Capital Asegurado por esta Póliza.
                        <br>
                        <span style="font-weight: bold;">Para Invalidez Total y Permanente</span>
                        <br>
                        a)<span style="margin-left: 10px;"> </span>  
                        Fotocopia del Certificado de Nacimiento o Fotocopia 
                        del Carnet de Identidad del ASEGURADO.
                        <br>
                        b)<span style="margin-left: 10px;"> </span>  
                        Liquidación de cartera con el monto indemnizable
                        <br>
                        c)<span style="margin-left: 10px;"> </span>  
                        Fotocopia del contrato de préstamo incluyendo la 
                        Planilla de Desembolso o la Planilla de solicitud de 
                        Préstamo para los Créditos de Banca Comunal. 
                        <br>
                        d)<span style="margin-left: 10px;"> </span>  
                        Extracto del préstamo por tipo de Crédito.
                        <br>
                        e)<span style="margin-left: 10px;"> </span>  
                        Informe del médico tratante correspondiente.
                        <br>
                        f)<span style="margin-left: 10px;"> </span>  
                        Certificado INSO (Instituto Nacional de Salud Ocupacional) 
                        o en su defecto de otra institución o médico que esté debidamente 
                        autorizado por la Autoridad Competente la cual determine el grado 
                        de invalidez.
                        <br>
                        <span style="font-weight: bold;">PRIMA Y FORMA DE PAGO:</span>
                        <br>
                        <span style="font-weight: bold;">Prima: </span>
                        De acuerdo a las tasas establecidas para cada ASEGURADO 
                        y a las declaraciones mensuales de EL TOMADOR. 
                        <br>
                        ELTOMADOR paga a LA COMPAÑÍA la prima colectiva de toda 
                        la cartera sujeta a cobertura en la periodicidad establecida 
                        en las Condiciones Particulares de la póliza. 
                        <br>
                        Para todos los créditos desembolsados por EL TOMADOR antes 
                        de la fecha de vigencia de este documento, se respetarán 
                        los términos y condiciones de seguro respecto a la afiliación 
                        y pago de siniestros pactados entre EL TOMADOR y la 
                        aseguradora anterior (Stock).    
                        <br>
                        Nota.- El ASEGURADO contará con cobertura, mientras sus 
                        cuotas mensuales se encuentren pagadas.
                        <br>
                        <span style="font-weight: bold;">Forma de Pago: </span>
                        Mensual al contado a través de EL TOMADOR.
                        <br>
                        <span style="font-weight: bold;">
                            CONDICION DE ADHESIÓN, DECLARACIONES Y AUTORIZACIONES: 
                        </span>
                        <br>
                        El ASEGURADO, se adhiere voluntariamente al Seguro de 
                        Vida Desgravamen mediante su firma en el presente certificado 
                        o el Contrato de Crédito con EL TOMADOR, esto implica que 
                        el conocimiento, aceptación, adhesión voluntaria del ASEGURADO 
                        al presente Seguro, y la recepción del presente Certificado de 
                        Cobertura.
                        <br>
                        <span style="font-weight: bold;">Declaración: </span>
                        <br>
                        El ASEGURADO declara conocer y aceptar todas las Condiciones de la Póliza.
                        <br>
                        <span style="font-weight: bold;">Declaración de Salud: </span>
                        <br>
                        El ASEGURADO que no está sujeto al llenado de la Declaración de 
                        Salud, declara que a la fecha no tiene conocimiento de alguna 
                        enfermedad prexistente y goza de buena salud.
                        <br>
                        <span style="font-weight: bold;">Autorización expresa: </span>
                        El ASEGURADO autoriza expresamente a LA COMPAÑÍA a solicitar, 
                        obtener y dar información respecto a su información y 
                        antecedentes financieros, de seguros y de salud, a través 
                        de ellos o terceras personas. 
                        <br>
                        EL ASEGURADO acepta la presentación, con calidad de Declaración 
                        Jurada, de la documentación de respaldo que solicitara LA 
                        COMPAÑIA misma que será requerida en virtud a la obligación 
                        normativa regulatoria que éste mantiene respecto a los controles 
                        e informes que realiza por instrucción de la Autoridad de 
                        Fiscalización y Control de Pensiones y Seguros, Unidad de 
                        Investigaciones Financieras u otras entidades fiscalizadoras.
                        <br>
                        <span style="font-weight: bold;">TODOS LOS BENEFICIOS A LOS 
                        CUALES EL ASEGURADO TIENE DERECHO SE SUJETAN A LO ESTIPULADO 
                        EN LAS CONDICIONES GENERALES, PARTICULARES Y/O ESPECIALES DE 
                        LA PÓLIZA DE DESGRAVAMEN NUMERO CRS-DESG-004 DE LA CUAL 
                        EL PRESENTE CERTIFICADO FORMA PARTE INTEGRANTE. </span>
                        El ASEGURADO podrá requerir a LA COMPAÑÍA o a EL TOMADOR 
                        cualquier información adicional que considere necesaria.
                        <br>
                        <span style="font-weight: bold;">Lugar y fecha: </span>
                        De acuerdo al contrato de Crédito del que este documento forma parte. 
                        <br>
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
                    </td>
                </tr>
            </table>
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