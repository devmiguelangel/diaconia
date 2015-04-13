<?php
function de_em_pec_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
	$conexion = $link;
	
	$fuenteTXT = 'font-size: 8px;';		$widthMAIN = 'width: 795px;';	$marginCONTAINER = 'margin: 0 auto;';
	$fuenteDet = 'font-size: 6px;';
	
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
	$cssCheck1 = 'border: 1px solid #000000; margin-left:5px; width: 8px; height: 8px; text-align: center; ';
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
	ob_start();
?>
<div id="container-c" style="<?=$cssContainer;?>">
  <div id="main-c" style="<?=$cssMain;?>">
  
	<h1 style="<?=$cssH1;?> margin-top: 45px;">
		CERTIFICADO DE SEGURO DE VIDA EN GRUPO<br />
    </h1>
    <div style="<?=$cssContent1;?>">
		<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width: 30%; <?=$cssTable1TrTd;?> text-align:left;">
					No de Certificado VG-<?=$row['no_emision'];?>
				</td>
				<td style="width: 40%; <?=$cssTable1TrTd;?>"></td>
				<td style="width: 30%; <?=$cssTable1TrTd;?> text-align:right;">
					<strong><?=$row['text_copia'].$row['num_copia'];?></strong>
				</td>
			</tr>
		</table>
	</div>
    <div style="<?=$cssContent2;?>">
		<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width: 100%; <?=$cssTable1TrTd;?>"><h2 style="<?=$cssH2;?>">Ud(s). Solicita(n) el seguro (alcance de cobertura) de tipo:</h2></td>
			</tr>
			<tr>
				<td style="width: 100%; <?=$cssTable1TrTd;?>">
<?php
	$cobertura = formatCheck($row['tipo_seguro']);
?>
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 4.5%; padding: 0; <?=$cssTable1TrTd;?>"><span style="<?=$cssTittle;?>">Individual</span></td>
							<td style="width: 2%; padding: 0; <?=$cssTable1TrTd;?>"><div style="<?=$cssCheck1;?>"><?=$cobertura[0];?></div></td>
							<td style="width: 43.5%; padding: 0; <?=$cssTable1TrTd;?>">Si marca esta opción, solo debe completar la información requerida al TITULAR 1</td>
							<td style="width: 6.5%; padding: 0; <?=$cssTable1TrTd;?>"><span style="<?=$cssTittle;?>">Mancomunada</span></td>
							<td style="width: 2%; padding: 0; <?=$cssTable1TrTd;?>"><div style="<?=$cssCheck1;?>"><?=$cobertura[1];?></div></td>
							<td style="width: 41.5%; padding: 0; <?=$cssTable1TrTd;?>">Si marca esta opción, debe completar la información requerida al TITULAR 1 y al TITULAR 2</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="width: 100%; <?=$cssTable1TrTd;?>">
					Si hubiesen más solicitantes (codeudores), éstos deben completar declaraciones de salud adicionales
				</td>
			</tr>
		</table>
	</div>
    <div style="<?=$cssContent2;?>">
		<span style="<?=$cssTittle;?>">DATOS PERSONALES</span>
<?php
			if($rsDt->data_seek(0)){
				while($regi2=$rsDt->fetch_array(MYSQLI_ASSOC)){
?>
                <table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width: 100%; padding: 2px 0; <?=$cssBorderF;?>" colspan="4"><span style="<?=$cssTittle;?>">INFORMACION SOBRE EL TITULAR <?=$regi2['titular_num'];?></span></td>
                    </tr>
                    <tr>
                        <td style="width: 25%; <?=$cssBA;?>">
                            1.- Apellido Paterno
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['paterno'];?></p>
                        </td>
                        <td style="width: 25%; <?=$cssBA;?>">
                            2.- Apellido Materno
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['materno'];?></p>
                        </td>
                        <td style="width: 25%; <?=$cssBA;?>">
                            3.- Apellido de Casada
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['ap_casada'];?></p>
                        </td>
                        <td style="width: 25%; <?=$cssBA;?>">
                            4.- Nombres Completos
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['nombre'];?></p>
                        </td>
                    </tr>
                </table>
                <?php
                    $sexoA = formatCheck($regi2['sexo']);
                ?>
                <table style="<?=$cssTable1;?><?=$cssBorderT;?>" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width: 20%; <?=$cssBA2;?>">
                            5.- Sexo
                            <p style="<?=$cssDatos;?>">Masculino(&nbsp; <?=$sexoA[0];?> &nbsp;)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Femenino(&nbsp; <?=$sexoA[1];?>&nbsp;)</p>
                        </td>
                        <td style="width: 18%; <?=$cssBA2;?>">
                            6.- Fecha de Nacimiento
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['fecha_nacimiento'];?></p>
                        </td>
                        <td style="width: 5%; <?=$cssBA2;?>">
                            7.- Edad
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['edad'];?></p>
                        </td>
                        <td style="width: 25%; <?=$cssBA2;?>">
                            8.- Ciudad y Pais de Nacimiento
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['lugar_nacimiento'];?></p>
                        </td>
                        <td style="width: 32%; <?=$cssBA2;?>">
                            9.- Tipo y Número de Carnet de Identidad
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['ci'];?></p>
                        </td>
                    </tr>
                </table>
                <table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width: 7%; <?=$cssBA2;?>">
                            10.- Peso (Kg)
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['peso'];?></p>
                        </td>
                        <td style="width: 7%; <?=$cssBA2;?>">
                            11.- Estatura
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['estatura'];?></p>
                        </td>
                        <td style="width: 28%; <?=$cssBA2;?>">
                            12.- Dirección y número de Domicilio
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['direccion'];?></p>
                        </td>
                        <td style="width: 14%; <?=$cssBA2;?>">
                            13.- Teléfonos
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['telefono'];?></p>
                        </td>
                        <td style="width: 26%; <?=$cssBA2;?>">
                            14.- Ocupación
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['ocupacion'];?></p>
                        </td>
                        <td style="width: 18%; <?=$cssBA2;?>">
                            15.- Porcentaje Crédito
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['porcentaje_credito'];?></p>
                        </td>
                    </tr>
                </table>
                <table style="<?=$cssTable1;?>">
                    <tr>
                        <td style="width: 60%; <?=$cssBA2;?>">
                            16.- ¿Cúal es su actividad cotidiana ligada al crédito que usted solicita?
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['desc_ocupacion'];?></p>
                        </td>
                        <td style="width: 40%; <?=$cssBA2;?>">
                            17.- Para escribir y/o firmar ¿qué mano utiliza?
                            <p style="<?=$cssDatos;?>">&nbsp;<?=$regi2['mano_utilizada'];?></p>
                        </td>
                    </tr>
                </table><br />
            
        <?php	
			  }
			  if($row['num_cliente']!=2){
			   ?>
                  <table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width: 100%; padding: 2px 0; <?=$cssBorderF;?>" colspan="4"><span style="<?=$cssTittle;?>">INFORMACION SOBRE EL TITULAR 2</span></td>
                    </tr>
                    <tr>
                        <td style="width: 25%; <?=$cssBA;?>">
                            1.- Apellido Paterno
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 25%; <?=$cssBA;?>">
                            2.- Apellido Materno
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 25%; <?=$cssBA;?>">
                            3.- Apellido de Casada
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 25%; <?=$cssBA;?>">
                            4.- Nombres Completos
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                    </tr>
                </table>
                <table style="<?=$cssTable1;?><?=$cssBorderT;?>" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width: 20%; <?=$cssBA2;?>">
                            5.- Sexo
                            <p style="<?=$cssDatos;?>">Masculino(&nbsp;&nbsp;)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Femenino(&nbsp;&nbsp;)</p>
                        </td>
                        <td style="width: 18%; <?=$cssBA2;?>">
                            6.- Fecha de Nacimiento
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 5%; <?=$cssBA2;?>">
                            7.- Edad
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 25%; <?=$cssBA2;?>">
                            8.- Ciudad y Pais de Nacimiento
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 32%; <?=$cssBA2;?>">
                            9.- Tipo y Número de Carnet de Identidad
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                    </tr>
                </table>
                <table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width: 7%; <?=$cssBA2;?>">
                            10.- Peso (Kg)
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 7%; <?=$cssBA2;?>">
                            11.- Estatura
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 28%; <?=$cssBA2;?>">
                            12.- Dirección y número de Domicilio
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 14%; <?=$cssBA2;?>">
                            13.- Teléfonos
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 26%; <?=$cssBA2;?>">
                            14.- Ocupación
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 18%; <?=$cssBA2;?>">
                            15.- Porcentaje Crédito
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                    </tr>
                </table>
                <table style="<?=$cssTable1;?>">
                    <tr>
                        <td style="width: 60%; <?=$cssBA2;?>">
                            16.- ¿Cúal es su actividad cotidiana ligada al crédito que usted solicita?
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                        <td style="width: 40%; <?=$cssBA2;?>">
                            17.- Para escribir y/o firmar ¿qué mano utiliza?
                            <p style="<?=$cssDatos;?>">&nbsp;</p>
                        </td>
                    </tr>
                </table>
               <?php	  
			  }
		  }
		?>    
	</div>
    
<?php
		$arrTitleBN = array('PRIMARIO', 'CONTINGENTE');
		
		if ($rsDt->data_seek(0) === true) {
			$titular = 0;
			while ($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)) {
				$titular += 1;
				$sqlBN = 'select 
					max(if(sdb.cobertura = "PR", sdb.id_beneficiario, "")) as pr_id,
					max(if(sdb.cobertura = "PR", sdb.nombre, "")) as pr_nombre,
					max(if(sdb.cobertura = "PR", sdb.paterno, "")) as pr_paterno,
					max(if(sdb.cobertura = "PR", sdb.materno, "")) as pr_materno,
					max(if(sdb.cobertura = "PR", sdb.parentesco, "")) as pr_parentesco,
					max(if(sdb.cobertura = "PR", sdb.ci, "")) as pr_ci,
					max(if(sdb.cobertura = "PR", sdb.id_depto, "")) as pr_ext,
					max(if(sdb.cobertura = "CO", sdb.id_beneficiario, "")) as co_id,
					max(if(sdb.cobertura = "CO", sdb.nombre, "")) as co_nombre,
					max(if(sdb.cobertura = "CO", sdb.paterno, "")) as co_paterno,
					max(if(sdb.cobertura = "CO", sdb.materno, "")) as co_materno,
					max(if(sdb.cobertura = "CO", sdb.parentesco, "")) as co_parentesco,
					max(if(sdb.cobertura = "CO", sdb.ci, "")) as co_ci,
					max(if(sdb.cobertura = "CO", sdb.id_depto, "")) as co_ext
				from
					s_de_beneficiario as sdb
						inner join
					s_de_em_detalle as sdd ON (sdd.id_detalle = sdb.id_detalle)
				where
					sdb.id_detalle = "'.$rowDt['id_detalle'].'"
				;';
				
				if (($rsBN = $link->query($sqlBN, MYSQLI_STORE_RESULT))) {
					if ($rsBN->num_rows === 1) {
						while ($rowBN = $rsBN->fetch_array(MYSQLI_ASSOC)) {
?>
	<div style="<?=$cssContent2;?>">
		<span style="<?=$cssTittle;?>">
			BENEFICIARIOS TITULAR <?=$titular;?>.: 
		</span>
		
		<table style="<?=$cssTable1;?> margin-top: 12px;" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width: 8%; text-align: center;"><?=$arrTitleBN[0];?></td>
                <td style="width: 23%; text-align: center;">
                    <table style="<?=$cssTable1;?>">
                        <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$rowBN['pr_paterno'];?></td></tr>
                    </table>
                </td>
                <td style="width: 23%; text-align: center;">
                    <table style="<?=$cssTable1;?>">
                        <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$rowBN['pr_materno'];?></td></tr>
                    </table>
                </td>
                <td style="width: 20%; text-align: center;">
                    <table style="<?=$cssTable1;?>">
                        <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$rowBN['pr_nombre'];?></td></tr>
                    </table>
                </td>
                <td style="width: 13%; text-align: center;">
                    <table style="<?=$cssTable1;?>">
                        <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$rowBN['pr_parentesco'];?></td></tr>
                    </table>
                </td>
                <td style="width: 13%; text-align: center;">
                    <table style="<?=$cssTable1;?>">
                        <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$rowBN['pr_ci'];?></td></tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width: 8%; text-align: center;"></td>
                <td style="width: 23%; text-align: center;" align="center">Apellido Paterno</td>
                <td style="width: 23%; text-align: center;" align="center">Apellido Materno</td>
                <td style="width: 20%; text-align: center;" align="center">Nombres</td>
                <td style="width: 13%; text-align: center;" align="center">Parentesco</td>
                <td style="width: 13%; text-align: center;" align="center">C.I./RUN</td>
            </tr>
            <tr>
                <td style="width: 8%;"></td><td style="width: 23%;"></td><td style="width: 23%;"></td>
                <td style="width: 20%;"></td><td style="width: 13%;"></td><td style="width: 13%;">&nbsp;</td>
            </tr>
            <tr>
				<td style="width: 8%; text-align: center;"><?=$arrTitleBN[1];?></td>
                <td style="width: 23%; text-align: center;">
                    <table style="<?=$cssTable1;?>">
                        <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$rowBN['co_paterno'];?></td></tr>
                    </table>
                </td>
                <td style="width: 23%; text-align: center;">
                    <table style="<?=$cssTable1;?>">
                        <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$rowBN['co_materno'];?></td></tr>
                    </table>
                </td>
                <td style="width: 20%; text-align: center;">
                    <table style="<?=$cssTable1;?>">
                        <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$rowBN['co_nombre'];?></td></tr>
                    </table>
                </td>
                <td style="width: 13%; text-align: center;">
                    <table style="<?=$cssTable1;?>">
                        <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$rowBN['co_parentesco'];?></td></tr>
                    </table>
                </td>
                <td style="width: 13%; text-align: center;">
                    <table style="<?=$cssTable1;?>">
                        <tr><td style="width: 100%; text-align: center; <?=$cssBorderB;?>">&nbsp;<?=$rowBN['co_ci'];?></td></tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width: 8%; text-align: center;"></td>
                <td style="width: 23%; text-align: center;" align="center">Apellido Paterno</td>
                <td style="width: 23%; text-align: center;" align="center">Apellido Materno</td>
                <td style="width: 20%; text-align: center;" align="center">Nombres</td>
                <td style="width: 13%; text-align: center;" align="center">Parentesco</td>
                <td style="width: 13%; text-align: center;" align="center">C.I./RUN</td>
            </tr>
            <tr>
                <td style="width: 8%;"></td><td style="width: 23%;"></td><td style="width: 23%;"></td>
                <td style="width: 20%;"></td><td style="width: 13%;"></td><td style="width: 13%;">&nbsp;</td>
            </tr>
		</table>
	</div>
<?php
						}
					}
				}
				
			}
		}
?>
    <hr />
    <div style="<?=$cssContent2;?>">
    	<span style="<?=$cssTittle;?>">
			COBERTURAS. 
		</span>
    	<table style="<?=$cssTable1;?> margin-top: 5px;" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width: 20%; text-align: center; font-weight: bold;" align="center"></td>
				<td style="width: 20%; text-align: center; font-weight: bold;" align="center"></td>
				<td style="width: 20%; text-align: center; font-weight: bold;" align="center"></td>
				<td style="width: 40%; text-align: center; font-weight: bold;" align="center">&nbsp;</td>
			</tr>
			<tr>
				<td style="width: 20%; ">HOSPITALARIO</td>
				<td style="width: 20%; ">
					<?=number_format($row['pr_hospitalario'], 2, '.', ',') . ' USD';?>
				</td>
				<td style="width: 20%; ">&nbsp;</td>
				<td style="width: 40%; ">&nbsp;</td>
			</tr>
			<tr>
				<td style="width: 20%; ">VIDA</td>
				<td style="width: 20%; ">
					<?=number_format($row['pr_vida'], 2, '.', ',') . ' USD';?>
				</td>
				<td style="width: 20%; ">&nbsp;</td>
				<td style="width: 40%; ">&nbsp;</td>
			</tr>
			<tr>
				<td style="width: 20%; ">CESANTIA</td>
				<td style="width: 20%; ">
					<?=number_format($row['pr_cesante'], 2, '.', ',') . ' USD';?>
				</td>
				<td style="width: 20%; ">&nbsp;</td>
				<td style="width: 40%; ">&nbsp;</td>
			</tr>
			<tr>
				<td style="width: 20%; padding-top: 10px; font-weight: bold;">PRIMA TOTAL</td>
				<td style="width: 20%; padding-top: 10px; font-weight: bold;">
					<?=number_format($row['pr_prima'], 2, '.', ',') . ' USD';?></td>
				<td style="width: 20%; "></td>
				<td style="width: 40%; "></td>
			</tr>
		</table>
    </div>
    <hr />
    
    <div style="<?=$cssContent2;?>">
		<span style="<?=$cssTittle;?>">LUGAR Y FECHA: </span><?=$row['user_departamento'];?>, <?=$row['fecha_emision'];?><br />
		<table style="<?=$cssTable1;?> margin-top: 35px;" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width: 50%;" align="center">FIRMA DEL SOLICITANTE (TITULAR 1)</td>
				<td style="width: 50%;" align="center">FIRMA DEL SOLICITANTE (TITULAR 2)</td>
			</tr>
		</table>
	</div>
    <div style="<?=$cssContent2;?>">
		<span style="<?=$cssTittle;?>">NOTA: </span>La Compañia se reserva el derecho de solicitar examen(es) médico(s) o información adicional.<br />
		<span style="<?=$cssTittle;?>">DEL CRÉDITO SOLICITADO (A ser completado por la Entidad Financiera)</span>
		<?php
            $tOperacion = formatCheck($row['tipo_operacion']);
            $tCredito = formatCheck($row['tipo_credito']);
            $moneda = formatCheck($row['moneda']);
        ?>
		<table style="<?=$cssTable1;?>" border="0" cellpadding="1" cellspacing="0">
			<tr>
				<td style="width: 40%;"><span style="<?=$cssTittle;?>">TIPO DE OPERACIÓN / MOVIMIENTO: </span></td>
				<td style="width: 15%;"></td>
				<td style="width: 15%;">
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 85%; text-align: right;">Primera/Única &nbsp;</td>
							<td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$tOperacion[0];?></div></td>
						</tr>
					</table>
				</td>
				<td style="width: 15%;">
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 85%; text-align: right;">Adicional &nbsp;</td>
							<td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$tOperacion[1];?></div></td>
						</tr>
					</table>
				</td>
				<td style="width: 15%;">
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 85%; text-align: right;">Línea de Crédito &nbsp;</td>
							<td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$tOperacion[2];?></div></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="width: 40%;"><span style="<?=$cssTittle;?>">TIPO DE CRÉDITO: </span></td>
				<td style="width: 15%;">
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 85%; text-align: right;">Hipotecario &nbsp;</td>
							<td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$tCredito[0];?></div></td>
						</tr>
					</table>
				</td>
				<td style="width: 15%;">
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 85%; text-align: right;">Comercial &nbsp;</td>
							<td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$tCredito[1];?></div></td>
						</tr>
					</table>
				</td>
				<td style="width: 15%;">
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 85%; text-align: right;">Consumo &nbsp;</td>
							<td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$tCredito[2];?></div></td>
						</tr>
					</table>
				</td>
				<td style="width: 15%;">
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 85%; text-align: right;">Otros &nbsp;</td>
							<td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$tCredito[3];?></div></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="width: 40%;"><span style="<?=$cssTittle;?>">MONEDA: </span></td>
				<td style="width: 15%;">
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 85%; text-align: right;">Dólares &nbsp;</td>
							<td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$moneda[0];?></div></td>
						</tr>
					</table>
				</td>
				<td style="width: 15%;">
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 85%; text-align: right;">Bolivianos &nbsp;</td>
							<td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$moneda[1];?></div></td>
						</tr>
					</table>
				</td>
				<!--
                <td style="width: 15%;">
					<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 85%; text-align: right;">UFV &nbsp;</td>
							<td style="width: 10%;"><div style="<?=$cssCheck1;?>"><?=$moneda[2];?></div></td>
						</tr>
					</table>
				</td>-->
				<td style="width: 15%;"></td>
			</tr>
		</table>
		<table style="<?=$cssTable1;?> margin-top: 8px;" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width: 23%;"><span style="<?=$cssTittle;?>">SALDO DEUDOR ACTUAL DEL ASEGURADO: </span></td>
				<td style="width: 15%;">
					<table style="<?=$cssTable1;?>">
						<tr><td style="width: 100%; <?=$cssBorderB;?>">&nbsp;<?=number_format($row['saldo_deudor'],2,'.',',');?> Bs.</td></tr>
					</table>
				</td>
				<td style="width: 6%"><span style="<?=$cssTittle;?>">SUCURSAL: </span></td>
				<td style="width: 12.5%">
					<table style="<?=$cssTable1;?>">
						<tr><td style="width: 100%; <?=$cssBorderB;?>">&nbsp;<?=$row['user_departamento'];?></td></tr>
					</table>
				</td>
				<td style="width: 6%"><span style="<?=$cssTittle;?>">TELÉFONO: </span></td>
				<td style="width: 12.5%">
					<table style="<?=$cssTable1;?>">
						<tr><td style="width: 100%; <?=$cssBorderB;?>">&nbsp;<?=$row['fono_sucursal'];?></td></tr>
					</table>
				</td>
				<td style="width: 25%"></td>
			</tr>
		</table>
		<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width: 17%"><span style="<?=$cssTittle;?>">MONTO ACTUAL SOLICITADO: </span></td>
				<td style="width: 23%">
					<table style="<?=$cssTable1;?>">
						<tr><td style="width: 100%; <?=$cssBorderB;?>">&nbsp;<?=number_format($row['monto_solicitado'],2,'.',',');?></td></tr>
					</table>
				</td>
				<td style="width: 60%"></td>
			</tr>
		</table>
		<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width: 17%"><span style="<?=$cssTittle;?>">MONTO ACTUAL ACUMULADO: </span></td>
				<td style="width: 26%">
					<table style="<?=$cssTable1;?>">
						<tr><td style="width: 100%; <?=$cssBorderB;?>">&nbsp;<?=number_format($row['monto_actual_acumulado'],2,'.',',');?> Bs.</td></tr>
					</table>
				</td>
				<td style="width: 7%"><span style="<?=$cssTittle;?>">FUNCIONARIO: </span></td>
				<td style="width: 20%">
					<table style="<?=$cssTable1;?>">
						<tr><td style="width: 100%; <?=$cssBorderB;?>">&nbsp;<?=$row['u_nombre'];?></td></tr>
					</table>
				</td>
				<td style="width: 30%"></td>
			</tr>
		</table>
		<table style="<?=$cssTable1;?>" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width: 18%"><span style="<?=$cssTittle;?>">PLAZO DEL PRESENTE CRÉDITO: </span></td>
				<td style="width: 39%">
					<table style="<?=$cssTable1;?>">
						<tr><td style="width: 100%; <?=$cssBorderB;?>">&nbsp;<?=$row['tipo_plazo'];?></td></tr>
					</table>
				</td>
				<td style="width: 16%"><span style="<?=$cssTittle;?>">NOMBRE, SELLO Y  FIRMA: </span></td>
				<td style="width: 27%">
					<table style="<?=$cssTable1;?>">
						<tr><td style="width: 100%; <?=$cssBorderB;?>">&nbsp;</td></tr>
					</table>
				</td>
			</tr>
		</table>
	</div><br />
     
     <div style="page-break-after: always;">&nbsp;</div>
     
     	<div style="<?=$cssContent2;?>">
              <h1 style="<?=$cssH1;?> margin-top: 15px;">
                  SEGUROS PRO VIDA S.A.<br />
                  FIRMAS AUTORIZADAS
              </h1>
              <table style="<?=$cssTable1;?> <?=$fuenteDet;?>" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                      <td style="width: 20%;"></td>
                      <td style="width: 20%; text-align:center;">
                          <img src="img/firma-ds.jpg" width="84" height="97" />
                      </td>
                      <td style="width: 20%;"></td>
                      <td style="width: 20%; text-align:center;">
                          <img src="img/firma-rs.jpg" width="46" height="97" />
                      </td>
                      <td style="width: 20%;"></td>
                  </tr>
                  <tr>
                      <td style="width: 20%;"></td>
                      <td style="width: 20%; text-align: center;">Dario Soraide Jimenez<br/>Jefe de Producción</td>
                      <td style="width: 20%;"></td>
                      <td style="width: 20%; text-align: center;">Ramiro Salinas Soruco<br/>Gerente General</td>
                      <td style="width: 20%;"></td>
                  </tr>
              </table>
          </div>
<?php
	if ($fac === TRUE) {
		$url .= 'index.php?ms='.md5('MS_DE').'&page='.md5('P_fac').'&ide='.base64_encode($row['id_emision']).'';
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
		$url .= 'index.php?ms='.md5('MS_DE').'&page='.md5('P_app_imp').'&ide='.base64_encode($row['id_emision']).'';
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
?>
	</div>
</div>
<?php
	$html = ob_get_clean();
	return $html;
}

?>