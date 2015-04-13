<?php
function rc_certificate($link, $row, $rsDt, $url) {

  ob_start();
?>
<div id="container-main" style="width=772px; height: auto; padding: 5px;">
    <div id="container-cert" style="font-weight: normal; font-size: 80%; font-family: Arial, Helvetica, sans-serif; color: #000000;">

    <table style="width:100%;">
        <tr>
            <td style="width:100%; padding:3px 0; " colspan="4">
                 <img src="<?=$url;?>images/<?=$row['ef_logo'];?>"
                            height="50" class="container-logo" align="right" />
            </td>
        </tr>

        <tr>
            <td style="width:100%; padding:3px 0; background:#92D050" colspan="4">
                <div style="padding:3px 8px; color:#FFFFFF; font-weight:bold; text-align:center">FORMULARIO DE DENUNCIA DE SINIESTRO </div>
            </td>
        </tr>


        <tr>
            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000;">Fecha del Siniestro: </div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; border: 1px solid #000;"><?=date('d/m/Y', strtotime($row['s_fecha_siniestro'])); ?></div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; text-align:right">Nro de Siniestro: </div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; border: 1px solid #000;"><?=$row['s_no_siniestro'];?></div>
            </td>
        </tr>

        <tr>
            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000;">Tipo de evento: </div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; border: 1px solid #000;">&nbsp;</div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; text-align:right">Agencia: </div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; border: 1px solid #000;"><?=$row['s_agencia'];?></div>
            </td>
        </tr>

        <tr>
            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000;">Lugar del Evento: </div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; border: 1px solid #000;">&nbsp;</div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; text-align:right">Sucursal: </div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; border: 1px solid #000;"><?=$row['s_sucursal'];?></div>
            </td>
        </tr>

        <tr>
            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000;">Fecha de denuncia a CRECER: </div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; border: 1px solid #000;">&nbsp;</div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; text-align:right">Fecha de elaboración: </div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; border: 1px solid #000;"><?=date('d/m/Y', strtotime($row['s_fecha_elaboracion'])); ?></div>
            </td>
        </tr>

        <tr>
            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000;">Persona quien realiza la denuncia: </div>
            </td>

            <td style="width:25%; padding:2px 0;" colspan="3">
                <div style="padding:3px 8px; color:#000000; border: 1px solid #000; text-align:center"><?=$row['s_denuncia_persona'];?></div>
            </td>

        </tr>
        <tr>
            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000;">Breve descripción del hecho: </div>
            </td>

            <td style="width:75%; padding:2px 0;" colspan="3">
                <div style="padding:3px 8px; color:#000000; border: 1px solid #000;"><?=$row['s_circunstancia'];?>&nbsp;</div>
            </td>

        </tr>

    </table>

    <table style="width:100%;" >
        <tr style="border: 1px solid #000;">
            <td style="width:100%; padding:3px 0; background:#4F6228" colspan="4">
                <div style="padding:3px 8px; color:#FFFFFF; font-weight:bold; text-align:center">DATOS DEL FALLECID@</div>
            </td>
        </tr>

        <tr>
            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000;">Apellido Paterno: </div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; border: 1px solid #000; background:#D7E4BC"><?=$row['s_paterno'];?></div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; text-align:left">Tipo de documento de Identidad: </div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; border: 1px solid #000; background:#D7E4BC"><?=$row['s_tipo_documento'];?></div>
            </td>
        </tr>
        <tr>
            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000;">Apellido Materno: </div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; border: 1px solid #000; background:#D7E4BC"><?=$row['s_materno'];?>&nbsp;</div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; text-align:left">Nro de documento de Identidad: </div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; border: 1px solid #000; background:#D7E4BC"><?=$row['s_ci'];?></div>
            </td>
        </tr>
        <tr>
            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000;">Apellido de casada: </div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; border: 1px solid #000; background:#D7E4BC"><?=$row['s_ap_casada'];?>&nbsp;</div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; text-align:left">Nombre Completo: </div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; border: 1px solid #000; background:#D7E4BC"><?=$row['s_nombre_completo'];?></div>
            </td>
        </tr>
        <tr>
            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000;">Nombres: </div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; border: 1px solid #000; background:#D7E4BC"><?=$row['s_nombre'];?></div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000;">Nº <?=$row['s_tipo_documento'];?>: </div>
            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; border: 1px solid #000; background:#D7E4BC"><?=$row['s_ci'];?></div>
            </td>
        </tr>

    </table>


     <table style="width:100%;">

        <tr style="border: 1px solid #000;">
            <td style="width:100%; padding:3px 0; background:#4F6228">
               <div style="padding:3px 8px; color:#FFFFFF; font-weight:bold; text-align:center">DATOS DEL CRÉDITO </div>
            </td>
        </tr>

    </table>

    <table style="width:100%; font-size: 11px;">
        <thead>
            <tr>
                <td style="width:23%; padding:2px 2px; text-align:center; font-weight:bold;">Tipo de Crédito</td>
                <td style="width:2%; padding:2px 2px; text-align:center; font-weight:bold;"></td>
                <td style="width:23%; padding:2px 2px; text-align:center; font-weight:bold;">Saldo a Capital</td>
                <td style="width:2%; padding:2px 2px; text-align:center; font-weight:bold;"></td>
                <td style="width:23%; padding:2px 2px; text-align:center; font-weight:bold;">Nº de Crédito</td>
                <td style="width:2%; padding:2px 2px; text-align:center; font-weight:bold;"></td>
                <td style="width:23%; padding:2px 2px; text-align:center; font-weight:bold;">Moneda</td>

            </tr>
        </thead>
        <tbody>
<?php
            $total_reserve_bs=0;
            $total_reserve_usd=0;

            while($rowSd = $rsDt->fetch_array(MYSQLI_ASSOC)){

?>
                <tr>
                    <td style="width:23%;  padding:2px 0;"><div style="padding:3px 8px; text-align:left; color:#000000; border: 1px solid #000; background:#C0C0C0"><?=$rowSd['d_tipo_credito'];?></div></td>
                    <td style="width:2%;  padding:2px 0;"><div style="padding:3px 8px;"></div></td>
                    <td style="width:23%;  padding:2px 0;"><div style="padding:3px 8px; font-weight:bold; text-align:center; color:#000000; border: 1px solid #000; background:#FFFF00"><?=number_format($rowSd['d_monto_desembolso'],2,'.',',');?></div></td>
                    <td style="width:2%;  padding:2px 0;"><div style="padding:3px 8px;"></div></td>
                    <td style="width:23%;  padding:2px 0;"><div style="padding:3px 8px; font-weight:bold; text-align:center; color:#000000; border: 1px solid #000; background:#FFFF00"><?=$rowSd['d_no_credito'];?></div></td>
                    <td style="width:2%;  padding:2px 0;"><div style="padding:3px 8px;"></div></td>
                    <td style="width:23%;  padding:2px 0;"><div style="padding:3px 8px; font-weight:bold; text-align:center; color:#000000; border: 1px solid #000; background:#FFFF00"><?=$rowSd['d_moneda'];?></div></td>
                </tr>
<?php
                if($rowSd['d_cod_moneda'] == "BS")
                    $total_reserve_bs = $total_reserve_bs + $rowSd['d_monto_desembolso'];
                elseif($rowSd['d_cod_moneda'] == "USD")
                    $total_reserve_usd = $total_reserve_usd + $rowSd['d_monto_desembolso'];
            }
?>
        </tbody>

    </table>

    <hr>

    <table style="width:100%;" >

        <tr>
            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; font-weight:bold">TOTAL RESERVA BS: </div>            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; border: 1px solid #000; background:#C2D69A; text-align:right; font-weight:bold"><?= number_format($total_reserve_bs,2,'.',','); ?></div>            </td>

            <td style="width:25%; padding:2px 0;">&nbsp;</td>

            <td style="width:25%; padding:2px 0;">&nbsp;</td>
        </tr>
        <tr>
            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; font-weight:bold">TOTAL RESERVA USD: </div>            </td>

            <td style="width:25%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; border: 1px solid #000; background:#D7E4BC; text-align:right; font-weight:bold"><?= number_format($total_reserve_usd,2,'.',','); ?></div>            </td>

            <td style="width:25%; padding:2px 0;">&nbsp;</td>
            <td style="width:25%; padding:2px 0;">&nbsp;</td>
        </tr>

    </table>

    <table style="width:100%;" >
        <tr>
            <td style="width:100%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; text-align:left">* Saldo de Capital a la fecha de fallecimiento</div>
            </td>
        </tr>
    </table>

    <hr>

    <table style="width:100%;" >
        <tr>
            <td style="width:50%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; text-align:right;">Nombre del funcionario que prepara el formulario: </div>            </td>

          <td style="width:50%; padding:2px 0;">
          <div style="padding:3px 8px; color:#000000; border: 1px solid #000; text-align:center"><?=$row['u_nombre'];?></div></td>
        </tr>
        <tr>
            <td style="width:50%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; text-align:right;">E - Mail: </div>            </td>

            <td style="width:50%; padding:2px 0;">
            <div style="padding:3px 8px; color:#000000; border: 1px solid #000; text-align:center"><?=$row['u_email'];?></div></td>
        </tr>
        <tr>
            <td style="width:50%; padding:2px 0;">
                <div style="padding:3px 8px; color:#000000; text-align:right;">Cargo: </div>            </td>

            <td style="width:50%; padding:2px 0;">
            <div style="padding:3px 8px; color:#000000; border: 1px solid #000;">&nbsp;<?=$row['u_cargo'];?></div></td>
        </tr>
    </table>
<br>
    <div style="padding:3px 8px; background:yellow; color:#000000; text-align:center; width:772px;">
        <span style="font-weight:bold">ACLARACIÓN IMPORTANTE:</span> Esta es una denuncia preliminar, la cual podría ser modificada <span style="color:#FF0000"> de acuerdo a la documentación que se presente.</span> Asimismo, le pedimos ignorar el presente correo electrónico en caso de que este siniestro ya se esté atendiendo.
    </div>

</div>

</div>

<?php
    $html = ob_get_clean();
    return $html;
}
?>