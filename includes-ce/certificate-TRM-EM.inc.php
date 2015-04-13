<?php
function trm_em_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
	//include_once('sibas-db.class.php');
	//$conexion = new SibasDB();
	$conexion = $link;
	
	$fontSizeHead = 'font-size: 75%;';
	$fontSize = 'font-size: 65%;';
	$marginUl = 'margin: 0 0 0 20px; padding: 0;';
	ob_start();
?>
<div id="container-main" style="width: 785px; height: auto; border: 0px solid #0081C2; padding: 5px;">
	<div id="container-cert" style="width: 775px; font-weight: normal; font-size: 80%; font-family: Arial, Helvetica, sans-serif; color: #000000;">
    
        <div style="height: 80px;" align="right">
			<img src="<?=$url;?>images/<?=$row['logo_cia'];?>" height="75"/>
		</div><br />
		<div style="font-weight: bold; <?=$fontSizeHead;?> text-align: center; margin: 0 0 5px 0;">
			SEGURO DE TODO RIESGO DE DA&Ntilde;OS A LA PROPIEDAD<br />
			POLIZA NRO. MR-1<?=str_pad($row['no_emision'],6,'0',STR_PAD_LEFT);?><br />
			<span style="text-decoration: underline;">CONDICIONES PARTICULARES</span><br /><br />
			CODIGO SPVS NO. 109-910101-2006 07 252<br />
			R.A. 740/06
		</div>
        
        <div style="font-weight: bold; font-size: 75%; text-align: right; margin: 0 0 5px 0;">
			<?=$row['text_copia'].$row['num_copia'];?>
		</div>
        <br/>
        <!--
        <div style="padding: 5px; text-align: center; width: 60%; margin: 0 auto; background: #e89797; border: 2px solid #e89797; font-weight: bold; <?=$fontSizeHead;?>">
			CASO FACULTATIVO: CERTIFICADO INVALIDO
		</div><br />
        -->
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; <?=$fontSize;?>">
			<tr>
				<td style="width: 5%;"></td>
				<td style="width: 25%; font-weight: bold; vertical-align: top;">CONTRATANTE: </td>
				<td style="width: 65%;">
					<?=$row['ef_nombre']?>
					<br/><br/><br/>
				</td>
				<td style="width: 5%;"></td>
			</tr>
			<tr>
				<td style="width: 5%;"></td>
				<td style="width: 25%; font-weight: bold; vertical-align: top;">PAGADOR: </td>
				<td style="width: 65%;">
					<?=$row['ef_nombre']?>
					<br/><br/><br/>
				</td>
				<td style="width: 5%;"></td>
			</tr>
			<tr>
				<td style="width: 5%;"></td>
				<td style="width: 25%; font-weight: bold; vertical-align: top;">ARRENDATARIO: </td>
				<td style="width: 65%;">
					<?=mb_strtoupper($row['cliente_nombre'],'utf-8');?>
					<br/><br/>
				</td>
				<td style="width: 5%;"></td>
			</tr>
			<tr>
				<td style="width: 5%;"></td>
				<td style="width: 25%; font-weight: bold; vertical-align: top;"> </td>
				<td style="width: 65%; font-size: 120%;">
					<div class="attached-link">
						<a href="<?=$url;?>files/<?=$row['ci_archivo'];?>" target="_blank" title="Descargar CI/NIT" alt="Descargar CI/NIT">Descargar CI/NIT</a>
					</div>
					<br/><br/><br/>
				</td>
				<td style="width: 5%;"></td>
			</tr>
		</table>
		<br/>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width: 5%;"></td>
				<td style="width: 25%; font-weight: bold; vertical-align: top;">MATERIA DEL SEGURO: </td>
				<td style="width: 65%;">
					<div><em>
						TODA PROPIEDAD REAL DE PROPIEDAD DEL CONTRATANTE/BENEFICIARIO (BISA LEASING S.A) OTORGADA EN ARRENDAMIENTO FINANCIERO AL ASEGURADO Y/O BAJO SU CUSTODIA, CUIDADO Y/O CONTROL Y POR LA CUAL SEA LEGALMENTE RESPONSABLE Y/O EN LA CUAL PUDIERA TENER INTER&Eacute;S, TAL COMO EXISTEN AHORA O SE ADQUIERAN M&Aacute;S ADELANTE, EN CUALQUIER FORMA QUE LA POSEA, MANTENGA EN CUSTODIA Y/O BIENES QUE SE ENCUENTREN EN CUSTODIA DE TERCEROS U OTROS Y/O POR LA CUAL SEA O PUDIESE SER RESPONSABLE.
					</em></div>
					<ul style="<?=$marginUl;?> width:95%;">
						<li><em>EN CASO DE BIENES INMUEBLES:</em>
							<ul style="list-style-type: square; width:95%;">
								<li>
									<em>INCLUYENDO EN TODOS LOS CASOS,OBRAS CIVILES Y SUS INSTALACIONES, INCLUYENDO LUMINARIAS, ALFOMBRADO (SIEMPRE Y CUANDO EST&Eacute;N INCLUIDAS EN EL AVAL&Uacute;O T&Eacute;CNICO), REVESTIMIENTOS; VIDRIOS, ACCESORIOS SANITARIOS, MUROS PERIMETRALES, TANQUES; ESTACIONAMIENTOS, &Aacute;REAS DE DEP&Oacute;SITO Y LA PARTE PROPORCIONAL DE &Aacute;REAS COMUNES, CUANDO CORRESPONDA.						
									</em>
								</li>
								<li>
									<em>PARA RIESGOS DOMICILIARIOS SE INCLUYE EL CONTENIDO HASTA UN 10% DEL VALOR ASEGURADO M&Aacute;XIMO HASTA $US. 20.000.- LOS MISMOS QUE DEBER&Aacute;N ESTAR DECLARADOS EN EL FORMULARIO DEL CLIENTE PRESENTADO POR BISA LEASING, EXCLUYENDO PRENDAS DE VESTIR,  COMESTIBLES, DINERO Y/O VALORES Y/O SIMILARES, JOYAS Y/O SIMILARES, OBRAS DE ARTE, ANTIG&Uuml;EDADES Y/O SIMILARES.
									</em>
								</li>
							</ul>
						</li>
						<li><em>MAQUINARIA PESADA M&Oacute;VIL (GR&Uacute;AS, PALAS MEC&Aacute;NICAS, EXCAVADORAS, CAMIONES CONCRETEROS, MOTONIVELADORAS, TRACTORES, Y OTROS SIMILARES), INCLUYENDO SUS EQUIPOS AUXILIARES QUE SE ENCUENTRES DECLARADOS DENTRO DE LA MATERIA ASEGURADA, YA SEA QUE EST&Eacute;N CONECTADOS O NO AL EQUIPO O MAQUINARIA OBJETO DEL SEGURO O QUE SE ENCUENTREN OPERANDO O DURANTE SU TRAYECTO POR SUS PROPIOS MEDIOS O NO DENTRO O FUERA DE LOS PREDIOS.
						</em></li>
						<li><em>MERCADER&Iacute;AS.</em></li>
					</ul>
				</td>
				<td style="width: 5%;"></td>
			</tr>
		</table>
        <br/>
        
        <div>
			<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
				<tr>
					<td style="width: 5%;"></td>
					<td style="width: 25%; font-weight: bold; vertical-align: top;"></td>
					<td style="width: 65%;"></td>
					<td style="width: 5%;"></td>
				</tr>
			</table>
			<br />
			<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
				<tr>
					<td style="width: 5%;"></td>
					<td style="width: 40%; font-weight: bold; text-align: left; border: 1px solid #DEDEDE;">MATERIA ASEGURADA</td>
					<td style="width: 15%; font-weight: bold; text-align: center; border: 1px solid #DEDEDE;">VALOR ASEGURADO</td>
					<td style="width: 10%; font-weight: bold; text-align: center; border: 1px solid #DEDEDE;">TASA</td>
					<td style="width: 15%; font-weight: bold; text-align: center; border: 1px solid #DEDEDE;">PRIMA ANUAL</td>
					<td style="width: 10%; font-weight: bold; text-align: left; border: 1px solid #DEDEDE;">
						<div class="attached-link">
							Adjunto
						</div>
					</td>
					<td style="width: 5%;"></td>
				</tr>
               <?php
                $prima_total = $row['prima_total'];
				$pt = 0;
				$tasa_final = $row['tr_tasa'];
				$tasaPM = $tasa_final * 10;
				if($rsDt->data_seek(0)){
					while($regDt = $rsDt->fetch_array(MYSQLI_ASSOC)){
						$prima = $regDt['prima'];
						if($row['tr_estado'] == 1){
							$prima = ($regDt['valor_asegurado']*$row['tr_tasa_final'])/100;
							$pt += $prima;
							$tasa_final = $row['tr_tasa_final'];
							$tasaPM = $tasa_final * 10;
						}else{
							$pt = $row['prima_total'];
						}
						
			   ?>
                       <tr>
                          <td style="width: 5%;"></td>
                          <td style="width: 40%; text-align: left; border: 1px solid #DEDEDE;"><?=$regDt['material'];?></td>
                          <td style="width: 15%; text-align: center; border: 1px solid #DEDEDE;">
                              $US <?=number_format($regDt['valor_asegurado'],2,'.',',');?>
                          </td>
                          <td style="width: 10%; text-align: center; border: 1px solid #DEDEDE;"><?=$tasaPM;?> %<span style="font-size:90%; vertical-align:bottom;">o</span></td>
                          <td style="width: 15%; text-align: center; border: 1px solid #DEDEDE;">
                              $US <?=number_format($prima,2,'.',',');?>
                          </td>
                          <td style="width: 10%; text-align: left; border: 1px solid #DEDEDE;">
                              <div class="attached-link">
                                  <a href="<?=$url;?>files/<?=$regDt['mt_archivo'];?>" target="_blank" title="Descargar Archivo Adjunto" alt="Descargar Archivo Adjunto">Descargar Adjunto</a>
                              </div>
                          </td>
                          <td style="width: 5%;"></td>
                      </tr>
               <?php
					}
					$plazo_anio = $conexion->get_year_final($row['plz_anio'], $row['tip_plz_code']);
					$prima_total = $pt * $plazo_anio;
			   ?>		
				     <tr>
                        <td style="width: 5%;"></td>
                        <td style="width: 40%; font-weight: bold; text-align: center; border: 1px solid #DEDEDE;">TOTAL</td>
                        <td style="width: 15%; font-weight: bold; text-align: center; border: 1px solid #DEDEDE;">
                            $US <?=number_format($row['valor_asegurado_total'],2,'.',',');?>
                        </td>
                        <td style="width: 10%; font-weight: bold; text-align: center; border: 1px solid #DEDEDE;">
                            <?=$tasaPM;?> %<span style="font-size:90%; vertical-align:bottom;">o</span>
                        </td>
                        <td style="width: 15%; font-weight: bold; text-align: center; border: 1px solid #DEDEDE;">
                            $US <?=number_format($pt,2,'.',',');?>
                        </td>
                        <td style="width: 10%; font-weight: bold; text-align: center; border: 1px solid #DEDEDE;"></td>
                        <td style="width: 5%;"></td>
                    </tr>
                	
			   <?php		
				}
			   ?>
            </table>
		</div>
		<br /><br>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width: 5%;"></td>
				<td style="width: 25%; font-weight: bold; vertical-align: top;">COBERTURAS: </td>
				<td style="width: 65%;">
					<div>
						<span style="text-decoration: underline;"><strong>SECCION I TODO RIESGO DE DA&Ntilde;OS A LA PROPIEDAD</strong></span>
					</div>
					<div><em>
						TODO RIESGO DE DA&Ntilde;OS A LA PROPIEDAD, INCLUYENDO TERREMOTO, TEMBLOR Y/O MOVIMIENTOS S&Iacute;SMICOS AL IGUAL QUE EL INCENDIO RESULTANTE DE ESTOS, DESLIZAMIENTOS, ASENTAMIENTOS NO GRADUALES, HUNDIMIENTO, CORRIMIENTOS DE TIERRA, CA&Iacute;DA DE ROCAS Y OTROS RIESGOS DE LA NATURALEZA CUALQUIERA SEA SU CAUSA; TERRORISMO Y RIESGOS POL&Iacute;TICOS Y SOCIALES INCLUYENDO HUELGAS, MOTINES, CONMOCI&Oacute;N CIVIL, DA&Ntilde;O MALICIOSO, VANDALISMO, SABOTAJE, ASONADA, DISTURBIOS DE ACUERDO TEXTO DE CL&Aacute;USULA.
					</em></div>
					<div><em>ROBO CON VIOLENCIA, ATRACO Y EVENTOS RELACIONADOS CON ELLOS</em><em>(APLICABLE &Uacute;NICAMENTE A RIESGOS DOMICILIARIOS Y MERCADER&Iacute;A)</em><em>.</em></div><br />
				</td>
				<td style="width: 5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%; " >
					<div><span style="text-decoration: underline;">
						<strong>SECCI&Oacute;N II: TODO RIESGO DE EQUIPO ELECTRONICO</strong>
					</span></div>
					<div><em>
						TODO RIESGO DE EQUIPO ELECTR&Oacute;NICO, INCLUYENDO COMPONENTES ELECTROMEC&Aacute;NICOS; EQUIPOS M&Oacute;VILES Y/O PORT&Aacute;TILES, SUS ACCESORIOS E INSTALACIONES, EQUIPOS PERIF&Eacute;RICOS, INCLUYENDO:&nbsp;&nbsp;&nbsp;&nbsp;
					</em></div>
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom:0; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%; " >
					<ul style="<?=$marginUl;?> width:95%;">
						<li><em>ROBO CON VIOLENCIA, ATRACO Y EVENTOS RELACIONADOS CON ELLOS</em></li>
						<li><em>AVER&Iacute;AS CAUSADAS POR PRUEBAS, CARGA EXCESIVA O POR EXPERIMENTOS QUE IMPLIQUEN CONDICIONES NORMALES SIEMPRE Y CUANDO LA MISMA SEA S&Uacute;BITA, ACCIDENTAL E IMPREVISTA.</em></li>
						<li><em>DA&Ntilde;OS EMERGENTES A LA ENERG&Iacute;A EL&Eacute;CTRICA INCLUYENDO CORTES</em><em> DE ELECTRICIDAD.</em></li>
						<li><em>DESPERFECTOS EST&Eacute;TICOS, COMO RASPADURAS EN SUPERFICIES PINTADAS, PULIDAS O BARNIZADAS.</em></li>
						<li><em>INCENDIO, RAYO, EXPLOSI&Oacute;N DE CUALQUIER TIPO, INCLUYENDO LOS DA&Ntilde;OS CAUSADOS POR EXTINCI&Oacute;N DE INCENDIO Y OPERACIONES DE SALVAMENTO. </em></li>
					</ul>
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-top:0; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%;" >
					<ul style="<?=$marginUl;?> width:95%;">
						<li><em>QUEMADURAS Y CARBONIZACI&Oacute;N, HUMO, HOLL&Iacute;N</em></li>
						<li><em>FUERZAS DE LA NATURALEZA COMO TEMPESTAD, INUNDACI&Oacute;N, GRANIZO, CORRIMIENTO DE TIERRA, CUBIERTOS POR LA SECCI&Oacute;N I DEL PRESENTE SEGURO.</em></li>
						<li><em>CUALQUIER INFLUENCIA DE AGUA CUBIERTA POR LA SECCI&Oacute;N I DEL PRESENTE SEGURO. EXCLUYE HUMEDAD Y CORROSI&Oacute;N POR TRATARSE DE DA&Ntilde;OS GRADUALES.</em></li>
						<li><em>EQUIPOS M&Oacute;VILES Y/O PORT&Aacute;TILES, HASTA $US. 10.000.</em></li>
					</ul><br />
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%;" >
					<div><span style="text-decoration: underline;">
						<strong>SECCI&Oacute;N III: TODO RIESGO Y/O DA&Ntilde;O FISICO POR ROTURA DE MAQUINARIA </strong>
					</span></div>
					<div><em>TODO RIESGO Y/O DA&Ntilde;O F&Iacute;SICO POR ROTURA DE MAQUINARIA, DA&Ntilde;OS EMERGENTES A LA ENERG&Iacute;A EL&Eacute;CTRICA, DA&Ntilde;OS F&Iacute;SICOS A LA MAQUINARIA, SUS INSTALACIONES Y EQUIPOS AUXILIARES DE PROTECCI&Oacute;N, CONTROL Y SUMINISTRO DE SERVICIOS (AIRE, AGUA, VAPOR, ENERG&Iacute;A EL&Eacute;CTRICA, GAS NATURAL), INCLUYENDO: 
					</em></div>
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom:0; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%;" >
					<ul style="<?=$marginUl;?> width:95%;">
						<li><em>ROBO CON VIOLENCIA</em></li>
						<li><em>MAL MANEJO, NEGLIGENCIA, IMPERICIA, IGNORANCIA, ACTOS MAL INTENCIONADOS, POR PARTE DE LOS EMPLEADOS Y/O DE TERCEROS
						</em></li>
						<li><em>ERRORES, DEFECTOS Y DESPERFECTOS DE CONSTRUCCI&Oacute;N Y DE USO DE MATERIALES DEFECTUOSOS</em></li>
						<li><em>DEFECTOS Y DESPERFECTOS Y/O ERRORES EN DISE&Ntilde;O, CALCULO Y MONTAJE Y/O MANO DE OBRA DEFECTUOSA, INCLUYENDO DEFECTOS DE ENGRASE
						</em></li>
						<li><em>ROTURA POR FUERZAS CENTRIFUGAS</em></li>
						<li><em>FALTA DE AGUA EN CALDEROS O RECIPIENTES BAJO PRESI&Oacute;N (CALENTAMIENTO EXCESIVO)</em></li>
						<li><em>INCIDENTES DURANTE EL TRABAJO, COMO MALOS AJUSTES, AFLOJAMIENTO DE PARTES Y PIEZAS </em></li>
						<li><em>FALLAS Y/O DESPERFECTOS EN MEDIDAS DE PREVENCI&Oacute;N Y SEGURIDAD</em></li>
					</ul>
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
       
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-top:0; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%;" >
					<ul style="<?=$marginUl;?> width:95%;">
						<li><em>INDUCCI&Oacute;N, CUALQUIERA SEA SU ORIGEN</em></li>
						<li><em>CUERPOS EXTRA&Ntilde;OS QUE SE INTRODUZCAN EN LOS BIENES ASEGURADOS O LOS GOLPEEN </em></li>
						<li><em>ESFUERZOS ANORMALES Y AUTO CALENTAMIENTO</em></li>
						<li><em>DA&Ntilde;OS POR LA ACCI&Oacute;N DIRECTA O INDIRECTA DE LA ENERG&Iacute;A EL&Eacute;CTRICA U ATMOSF&Eacute;RICA Y CA&Iacute;DA DIRECTA DE RAYO.
						</em></li>
						<li><em>INCENDIO INTERNO E IMPLOSI&Oacute;N, INCLUYE EXPLOSI&Oacute;N QU&Iacute;MICA INTERNA.</em></li>
						<li><em>EXPLOSI&Oacute;N EN MOTORES DE COMBUSTI&Oacute;N INTERNA.</em></li>
						<li><em>INUNDACI&Oacute;N O ENLODAMIENTO POR ROTURA O ESTALLIDO DE PRESAS (TUBER&Iacute;AS DE PRESI&Oacute;N SUMINISTRANDO AGUA DE ACCIONAMIENTO) V&Aacute;LVULAS DE CIERRE Y/O BOMBAS DE RETORNO. 
						</em></li>
						<li><em>BOMBAS SUMERGIDAS Y BOMBAS PARA POZOS PROFUNDOS.</em></li>
						<li><em>EL SEGURO SE EXTIENDE A CUBRIR LOS COMPONENTES ELECTR&Oacute;NICOS QUE FORMEN PARTE DE LA MAQUINARIA.
						</em></li>
					</ul><br />
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%;" >
					<div><span style="text-decoration: underline;">
						<strong>SECCI&Oacute;N IV:TODO RIESGO DE EQUIPO MOVIL</strong>
					</span></div>
					<div><em>TODO RIESGO DE EQUIPO M&Oacute;VIL INCLUYENDO COMPONENTES ELECTR&Oacute;NICOS, RAYO Y EXPLOSI&Oacute;N, TERRORISMO, HUELGAS, MOTINES, CONMOCI&Oacute;N CIVIL, DA&Ntilde;O MALICIOSO, VANDALISMO, SABOTAJE, SAQUEO Y/O TUMULTOS POPULARES, Y:
					</em></div>
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
         
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%;" >
					<ul style="<?=$marginUl;?> width:95%;">
						<li><em>ACCIDENTES QUE SURJAN DURANTE EL MONTAJE Y/O DESMONTAJE A CONSECUENCIA DE SU MANTENIMIENTO PARA FINES DE LIMPIEZA Y REACONDICIONAMIENTO Y TRASLADOS DENTRO DE LOS PREDIOS DEL ASEGURADO Y/O MIENTRAS VIAJEN POR SUS PROPIOS MEDIOS O SEAN TRANSPORTADOS DE UN SITIO DE OPERACI&Oacute;N A OTRO, INCLUYENDO DA&Ntilde;OS POR VUELCOS, CHOQUE, EMBARRANCAMIENTO Y/O INCENDIO DEL MEDIO TRANSPORTADOR L.A.P.
						</em></li>
						<li><em>IMPACTO DE VEH&Iacute;CULOS</em></li>
						<li><em>ROBO CON VIOLENCIA Y/O ASALTO, AS&Iacute; COMO TAMBI&Eacute;N LOS DA&Ntilde;OS CAUSADOS POR DICHO DELITO O SU INTENTO.
						</em></li>
						<li><em>RIESGOS POL&Iacute;TICOS Y SOCIALES</em></li>
						<li><em>ROTURA DE VIDRIOS.</em></li>
						<li><em>GASTOS EXTRAORDINARIOS HASTA EL 20% DE LA SUMA ASEGURADA.</em></li>
						<li><em>COLISI&Oacute;N CON OBJETOS EN MOVIMIENTO O ESTACIONARIOS, VOLCAMIENTOS, HUNDIMIENTO DE TERRENO, DESLIZAMIENTO DE TIERRA, DESCARRILAMIENTO.
						</em></li>
						<li><em>ACCIDENTES QUE OCURRAN PESE A UN MANEJO CORRECTO, AS&Iacute; COMO LOS QUE SOBREVENGAN POR DESCUIDO, IMPERICIA O NEGLIGENCIA DEL CONDUCTOR (SALVO ACTOS INTENCIONALES O NEGLIGENCIA MANIFIESTA DEL ASEGURADO O SUS REPRESENTANTES).
						</em></li>
						<li><em>P&Eacute;RDIDAS O DA&Ntilde;OS CAUSADOS POR INUNDACI&Oacute;N, CICL&Oacute;N, HURAC&Aacute;N TEMPESTAD, VIENTOS, TERREMOTO, TEMBLOR, ERUPCI&Oacute;N VOLC&Aacute;NICA O POR OTRA CONVULSI&Oacute;N DE LA NATURALEZA
						</em></li>
					</ul><br />
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%;" >
					<div><span style="text-decoration: underline;">
						<strong>SECCI&Oacute;N V: RESPONSABILIDAD CIVIL GENERAL O EXTRACONTRACTUAL</strong>
					</span></div>
					<div><em>INCLUYENDO:</em></div>
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%;" >
					<ul style="<?=$marginUl;?> width:95%;">
						<li><em>RESPONSABILIDAD CIVIL OPERACIONAL</em></li>
						<li><em>COBERTURA AUTOM&Aacute;TICA PARA NUEVOS PREDIOS E INSTALACIONES, INCLUYENDO MAQUINARIA Y/O EQUIPOS</em></li>
						<li><em>RESPONSABILIDAD CIVIL DE ASCENSORES, ESCALERAS MEC&Aacute;NICAS Y SIMILARES</em></li>
						<li><em>RESPONSABILIDAD CIVIL CONTRACTUAL</em></li>
						<li><em>RESPONSABILIDAD CIVIL CRUZADA</em></li>
						<li><em>RESPONSABILIDAD CIVIL PATRONAL </em></li>
						<li><em>RESPONSABILIDAD CIVIL DE CONTRATISTAS Y SUBCONTRATISTAS</em></li>
					</ul><br />
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width: 25%; font-weight: bold; vertical-align: top;">VALORES ASEGURADOS: </td>
				<td style="width:65%;" >
					<div><strong><em>PARA BIENES INMUEBLES: </em></strong></div>
					
					<div><em>VALOR DE REPOSICI&Oacute;N A NUEVO DEL INMUEBLE, SEG&Uacute;N EL AVAL&Uacute;O T&Eacute;CNICO (EN PODER DEL CONTRATANTE / BENEFICIARIO)&nbsp;&nbsp; EFECTUADO&nbsp;&nbsp; POR UN PERITO EVALUADOR ASIGNADO POR BISA LEASING S.A. (NO SE CONSIDERARA EL VALOR DEL TERRENO) 
					</em></div><br />
					
					<div><strong><em>PARA BIENES MUEBLES: </em></strong></div>
					<div><em>VALOR DE REPOSICI&Oacute;N DE ACUERDO A FACTURA COMERCIAL Y/O AVAL&Uacute;O Y/O DOCUMENTO EQUIVALENTE.
					</em></div><br />
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%;" >
					<div><strong>PARA MERCADER&Iacute;A: </strong></div>
					<div><em>VALOR DE COSTO EN BOLIVIA, DE ACUERDO A DOCUMENTACI&Oacute;N DE RESPALDO.</em></div><br />
					
					<div><strong>PARA EQUIPOS ELECTR&Oacute;NICOS: </strong></div>
					<div><em>VALOR DE REPOSICI&Oacute;N A NUEVO (INCLUYENDO TODO EL COSTO HASTA SU PUESTA EN MARCHA), DE ACUERDO A FACTURA COMERCIAL Y/O AVAL&Uacute;O Y/O DOCUMENTO EQUIVALENTE. 
					</em></div><br />
					<div><strong>PARA ROTURA DE MAQUINARIA Y EQUIPO M&Oacute;VIL:</strong></div>
					<div><em>VALOR DE REPOSICI&Oacute;N A NUEVO, (INCLUYENDO TODO EL COSTO HASTA SU PUESTA EN MARCHA), DE ACUERDO A FACTURA COMERCIAL Y/O AVAL&Uacute;O Y/O DOCUMENTO EQUIVALENTE.
					</em></div><br />
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%;" >
					<div><strong>PARA BIENES CON ANTIG&Uuml;EDAD DE M&Aacute;S DE 5 A&Ntilde;OS O BIENES REACONDICIONADOS: </strong></div>
					<div><em>EL VALOR DE REPOSICI&Oacute;N A NUEVO O SU VALOR DE ADQUISICI&Oacute;N, DE ACUERDO A FACTURA (QUE INCLUYA M&Iacute;NIMAMENTE LOS COSTOS DE COMPRA, IMPUESTOS, FLETES Y COSTOS DE REACONDICIONAMIENTO) SIEMPRE Y CUANDO ESTE VALOR DE ADQUISICI&Oacute;N SEA POR LO MENOS EQUIVALENTE A UN 80% DEL VALOR DE REPOSICI&Oacute;N A NUEVO.
					</em></div><br />
					
					<div><strong>PARA RESPONSABILIDAD CIVIL COMPRENSIVA:</strong></div>
					<div><em>US$. 50.000.- POR EVENTO Y EN EL ANUAL AGREGADO.</em></div>
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
		<br>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width: 25%; font-weight: bold; vertical-align: top;">CLAUSULAS ADICIONALES: </td>
				<td style="width:65%;" >
					<ul style="<?=$marginUl;?> width:95%;">
						<li><em>GASTOS DE INVESTIGACI&Oacute;N Y SALVAMENTO, HASTA EL 5% DEL VALOR DEL RECLAMO CON UN M&Aacute;XIMO DE USD 10.000.
						</em></li>
						<li><em>DE GASTOS EXTRAORDINARIOS, HASTA EL 10% DEL VALOR DEL RECLAMO, M&Aacute;XIMO US$ 100.000.-</em></li>
						<li><em>DA&Ntilde;OS OCASIONADOS POR SALVAMENTO Y LA EXTINCI&Oacute;N DE INCENDIOS, HASTA EL 5% DEL VALOR DEL RECLAMO, M&Aacute;XIMO USD 10.000.-
						</em></li>
						<li><em>DE FLETE A&Eacute;REO, HASTA EL 5% DEL VALOR DEL RECLAMO, M&Aacute;XIMO US$ 5.000.-</em></li>
						<li><em>DE ELEGIBILIDAD DE AJUSTADORES</em></li>
						<li><em>DE AMPLIACI&Oacute;N DE AVISO DE SINIESTRO HASTA 10 D&Iacute;AS APLICABLES AL CONTRATANTE/BENEFICIARIO
						</em></li>
						<li><em>DE ADELANTO DEL 50% EN CASO DE SINIESTRO UNA VEZ DECLARADO PROCEDENTE EL RECLAMO Y HABI&Eacute;NDOSE ESTABLECIDO EL MONTO APROXIMADO DE LA P&Eacute;RDIDA
						</em></li>
						<li><em>DE REHABILITACI&Oacute;N AUTOM&Aacute;TICA DE LA SUMA ASEGURADA.</em></li>
						<li><em>DE ERRORES Y OMISIONES.</em></li>
						<li><em>DE INCLUSIONES Y EXCLUSIONES.</em></li>
						<li><em>DE TRASLADO TEMPORAL, INCLUYENDO USO, MANTENIMIENTO, REPARACI&Oacute;N Y DA&Ntilde;OS DURANTE SU TRANSPORTE.
						</em></li>
						<li><em>AMPLIACI&Oacute;N DE VIGENCIA A PRORRATA, BAJO LOS MISMOS T&Eacute;RMINOS Y CONDICIONES INCLUYENDO TASAS PACTADAS, HASTA 90 D&Iacute;AS.
						</em></li>
						<li><em>PRORRATA EN CASO DE RESCISI&Oacute;N POR PARTE DEL ASEGURADO, SUJETO A NO SINIESTRALIDAD DURANTE LA VIGENCIA.
						</em></li>
						<li><em>CL&Aacute;USULA DE HUNDIMIENTO.</em></li>
					</ul><br />
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%;" >
					<div><span style="text-decoration: underline;">
						<strong>APLICABLES A LA SECCI&Oacute;N IV (EQUIPO M&Oacute;VIL)</strong>
					</span></div>
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%;" >
					<ul style="<?=$marginUl;?> width:95%;">
						<li><em>COBERTURA PARA EL TR&Aacute;NSITO POR SUS PROPIOS MEDIOS, SIEMPRE Y CUANDO EL EQUIPO M&Oacute;VIL SE TRASLADE DE UN PROYECTO A OTRO, O A SU GARAJE.
						</em></li>
						<li><em>CL&Aacute;USULA DE AMPLIACI&Oacute;N DEL PLAZO PARA AVISO DE SINIESTRO. (10 D&Iacute;AS APLICABLES AL TOMADOR).
						</em></li>
						<li><em>DE REHABILITACI&Oacute;N AUTOM&Aacute;TICA DE LA SUMA ASEGURADA.</em></li>
						<li><em>SE ACLARA QUE EN CASO DE OCURRIR UNA P&Eacute;RDIDA TOTAL, O UNA AFECTACI&Oacute;N DE LA COBERTURA DE RESPONSABILIDAD CIVIL, Y SI SE IDENTIFICASE QUE EL ENCARGADO DEL EQUIPO M&Oacute;VIL SE ENCONTRASE CON ALG&Uacute;N GRADO ALCOH&Oacute;LICO O BAJO EFECTOS DE MEDICAMENTOS, NARC&Oacute;TICOS U OTROS, O QUE EL SINIESTRO SEA CONSECUENCIA DE INFRACCIONES QUE GENEREN EL INCUMPLIMIENTO A LAS DISPOSICIONES DEL REGLAMENTO Y C&Oacute;DIGO DE TR&Aacute;NSITO, EL SINIESTRO SER&Aacute; CUBIERTO EN FAVOR DE BISA LEASING, SIN EMBARGO, LA COMPA&Ntilde;&Iacute;A SE RESERVA EL DERECHO DE REPETIR CONTRA EL ARRENDATARIO FINANCIERO, SUS REPRESENTANTES, O QUIEN CORRESPONDA.
						</em></li>
					</ul><br />
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%;" >
					<div><span style="text-decoration: underline;">
						<strong>APLICABLES A LA SECCI&Oacute;N V (RESPONSABILIDAD CIVIL)</strong>
					</span></div>
					<ul style="<?=$marginUl;?> width:95%;">
						<li><em>CL&Aacute;USULA DE ELEGIBILIDAD DE AJUSTADORES</em></li>
						<li><em>CL&Aacute;USULA DE EXCLUSI&Oacute;N DE GUERRA, SEG&Uacute;N NMA 464</em></li>
						<li><em>CL&Aacute;USULA DE EXCLUSI&Oacute;N DE TERRORISMO</em></li>
						<li><em>CL&Aacute;USULA DE CONDICIONES PARA TRABAJOS DE SOLDADURA Y OXICORTE MWP.</em></li>
						<li><em>CL&Aacute;USULA DE REDES DE SERVICIOS SUBTERR&Aacute;NEOS</em></li>
						<li><em>GASTOS DE DEFENSA EN JUICIO HASTA USD 1.000 INCLUIDOS EN LA SUMA ASEGURADA.</em></li>
					</ul>
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
		<br>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width: 25%; font-weight: bold; vertical-align: top;">DEDUCIBLES:</td>
				<td style="width:65%;" >
					<div><strong>SECCI&Oacute;N I:POR EVENTO Y/O RECLAMO</strong></div>
					<ul style="<?=$marginUl;?> width:95%;">
						<li><em>RIESGOS POL&Iacute;TICOS Y TERRORISMO: 1% DEL VALOR ASEGURADO POR UBICACI&Oacute;N, CON UN M&Iacute;NIMO DE US$ 200.-
						</em></li>
						<li><em>TERREMOTO, TEMBLOR Y MOVIMIENTOS S&Iacute;SMICOS: 1% DEL VALOR ASEGURADO POR UBICACI&Oacute;N, CON UN M&Iacute;NIMO DE US$ 200.-
						</em></li>
						<li><em>PARA ROBO CON VIOLENCIA AL CONTENIDO: US$ 200.- (APLICABLE &Uacute;NICAMENTE A RIESGOS DOMICILIARIOS)
						</em></li>
						<li><em>PARA LAS DEM&Aacute;S COBERTURAS&nbsp;&nbsp; US$ 100.-</em></li>
						<li><em>PARA TODAS LAS COBERTURAS DE MERCADER&Iacute;A: 1% SOBRE EL VALOR DEL SINIESTRO CON UN M&Iacute;NIMO DE US$ 250.-
						</em></li>
					</ul><br />
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%;" >
					<div><strong>SECCIONES II Y III: POR EVENTO Y/O RECLAMO</strong></div>
					<ul style="<?=$marginUl;?> width:95%;">
						<li><em>EQUIPO MEDICO: DE ACUERDO A LA SIGUIENTE TABLA DE VALORES:</em></li>
						<li><em>PARA EQUIPOS CON UN VALOR ASEGURADO MAYOR A US$ 50.000.- 2% DEL VALOR DEL SINIESTRO. </em></li>
						<li><em>DEM&Aacute;S EQUIPOS 3% DEL VALOR DEL SINIESTRO M&Iacute;NIMO US$ 500.-</em></li>
						<li><em>EQUIPO DE TELECOMUNICACIONES: 2% DEL VALOR DEL SINIESTRO M&Iacute;NIMO US$. 250.-</em></li>
						<li><em>EQUIPOS M&Oacute;VILES Y/O PORT&Aacute;TILES: 2% DEL VALOR DEL RECLAMO CON UN M&Iacute;NIMO DE US$. 300 POR EVENTO Y/O RECLAMO.
						</em></li>
						<li><em>DEM&Aacute;S AMPARADOS: 2% DEL VALOR DEL RECLAMO CON UN M&Iacute;NIMO DE US$. 250.- POR EVENTO Y/O RECLAMO
						</em></li>
					</ul><br />
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%;" >
					<div><strong>SECCI&Oacute;N IV: POR EVENTO Y/O RECLAMO</strong></div>
					<ul style="<?=$marginUl;?> width:95%;">
						<li><em>PARA LA COBERTURA DE VIDRIOS US$. 50.- POR EVENTO Y/O RECLAMO</em></li>
						<li><em>DEM&Aacute;S COBERTURAS:</em>
							<ul style="list-style: square; width:95%;">
								<li><em>PARA EQUIPOS CON VALORES ASEGURADOS HASTA US$ 50.000, 2% DEL VALOR DEL SINIESTRO.</em></li>
								<li><em>PARA EQUIPOS CON VALORES ASEGURADOS HASTA US$ 250.000, 1.5% DEL VALOR DEL SINIESTRO.</em></li>
								<li><em>PARA EQUIPOS CON VALORES ASEGURADOS MAYORES A US$ 250.000, 1% DEL VALOR DEL SINIESTRO</em></li>
							</ul>
						</li>
					</ul>
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%;" >
					<div><strong>SECCI&Oacute;N IV: POR EVENTO Y/O RECLAMO</strong></div>
					<ul style="<?=$marginUl;?> width:95%;">
						<li><em>RESPONSABILIDAD CIVIL US$ 100.- POR EVENTO Y/O RECLAMO (SOLO PARA DA&Ntilde;OS MATERIALES).</em></li>
					</ul>
					<div><em>LOS DEDUCIBLES EST&Aacute;N SUJETOS A LA COBERTURA CONTRATADA</em></div>
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
		<br>

		<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width: 25%; font-weight: bold; vertical-align: top;">EXCLUSIONES: </td>
				<td style="width:65%;" >
					<div><em>-HURTO Y/O RATER&Iacute;A</em></div>
					<div><em>-DE ACUERDO AL CONDICIONADO GENERAL DE LA P&Oacute;LIZA.</em></div>
					<div><strong>SECCI&Oacute;N II:</strong></div>
					<div><em>-SAT&Eacute;LITES ESPACIALES</em></div>
					<div><em>-SOFTWARE Y LICENCIAS</em></div>
					<div><em>-DA&Ntilde;OS POR VIRUS</em></div>
					<div><em>-DA&Ntilde;OS MEC&Aacute;NICOS Y EL&Eacute;CTRICOS INTERNOS</em></div>
					<div><strong>-</strong><em>DEM&Aacute;S EXCLUSIONES DE ACUERDO AL CONDICIONADO GENERAL DE LA P&Oacute;LIZA.</em></div>
					<div><strong>SECCI&Oacute;N III:</strong></div>
					<div><strong>-</strong><em>DE ACUERDO AL CONDICIONADO GENERAL DE LA P&Oacute;LIZA.</em></div>
					<div><strong>SECCI&Oacute;N IV:</strong></div>
					<div><em>-EQUIPOS QUE OPEREN BAJO TIERRA</em></div>
					<div><em>-EQUIPOS QUE TENGAN PLACAS DE CIRCULACI&Oacute;N</em></div>
					<div><em>-RIESGOS DE PERFORACI&Oacute;N; RIESGOS PETROLEROS Y RIESGOS DE&nbsp;&nbsp; GAS</em></div>
					<div><strong>-</strong><em>DEM&Aacute;S EXCLUSIONES DE ACUERDO AL CONDICIONADO GENERAL DE LA P&Oacute;LIZA.</em></div>
					<div><strong>SECCI&Oacute;N V:</strong></div>
					<div><strong>-</strong><em>DE ACUERDO AL CONDICIONADO GENERAL DE LA P&Oacute;LIZA.</em></div>
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
		<br>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width: 25%; font-weight: bold; vertical-align: top;">REQUISITOS:</td>
				<td style="width:65%;" >
					<div><em>AVAL&Uacute;O T&Eacute;CNICO FIRMADO POR EL PERITO DESIGNADO POR BISA LEASING O DOCUMENTO EQUIVALENTE, DONDE SE ESPECIFIQUE LA MATERIA DEL SEGURO. </em></div> <br />
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
		
		<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width: 25%; font-weight: bold; vertical-align: top;">NOTAS ESPECIALES: </td>
				<td style="width:65%;" >
					<div><em>EL ASEGURADO AUTORIZA A LA COMPA&Ntilde;&Iacute;A DE SEGUROS A ENVIAR EL REPORTE A LA CENTRAL DE RIESGOS DEL MERCADO DE SEGUROS ACORDE A LAS NORMATIVAS REGLAMENTARIAS DE LA AUTORIDAD DE FISCALIZACI&Oacute;N Y CONTROL DE PENSIONES Y SEGUROS &ndash; APS.
					</em></div><br />
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width: 25%; font-weight: bold; vertical-align: top;">VIGENCIA: </td>
				<td style="width:65%;" >
					<div><em><?=$row['plazo_dias'];?></em>
						<em> D&Iacute;AS A PARTIR DE LAS 12:01 P.M. HORAS DEL D&Iacute;A <?=strtoupper(get_date_format($row['fecha_iniv']));?>, 
						HASTA LA MISMA HORA DEL <?=strtoupper(get_date_format($row['fecha_finv']));?>.
					</em></div><br />
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width: 25%; font-weight: bold; vertical-align: top;">PRIMA TOTAL: </td>
				<td style="width:65%;" >
					<div><strong><?=strtoupper($row['forma_pago']);?></strong></div>
					<div><em>US$. <?=number_format($prima_total,2,'.',',');?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>(INCLUYE IMPUESTOS DE LEY)</strong></em></div>
					<br />
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width: 25%; font-weight: bold; vertical-align: top;">FORMA DE PAGO: </td>
				<td style="width:65%;" >
					
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
		
		<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%;" >
					<div><em><?=strtoupper($row['forma_pago']);?></em></div><br />
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%;" >
					<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; font-size:100%;">
						<tr>
							<td style="width: 100%;">
								<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; font-size:100%;">
									<tr>
										<td style="width: 25%; text-align: center; font-weight: bold; border: 1px solid #d0d0d0;">
											Año
										</td>
										<td style="width: 25%; text-align: center; font-weight: bold; border: 1px solid #d0d0d0;">
											Fecha de Pago
										</td>
										<td style="width: 25%; text-align: center; font-weight: bold; border: 1px solid #d0d0d0;">
											Cuota
										</td>
										<td style="width: 25%;"></td>
									</tr>
									<?php
                                      $jsonData=$row['cuota'];
                                      $phpArray = json_decode($jsonData,true);
									  foreach ($phpArray as $key => $value) {
                                         $cuota=explode('|',$value)
                                    ?>
                                        <tr>
                                            <td style="width: 25%; text-align: center; border: 1px solid #d0d0d0;">
                                                <?=$cuota[0];?>
                                            </td>
                                            <td style="width: 25%; text-align: center; border: 1px solid #d0d0d0;">
                                                <?=$cuota[1];?>
                                            </td>
                                            <td style="width: 25%; text-align: center; border: 1px solid #d0d0d0;">
                                                <?=$cuota[2];?>
                                            </td>
                                            <td style="width: 25%;"></td>
                                        </tr>
									<?php
									  }
                                    
                                    ?>
								</table>
							</td>
						</tr>
					</table>
					<br />
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
        
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width: 25%; font-weight: bold; vertical-align: top;">OBSERVACIONES: </td>
				<td style="width:65%;" >
				<?php
				if($row['tr_estado'] == 1){
			    ?>
					<table border="0" cellpadding="1" cellspacing="0" style="width: 100%; border-collapse: collapse; font-size:100%;">
						<tr>
							<td colspan="6" style="width:100%; text-align: center; font-weight: bold; background: #e89797; border: 1px solid #dedede;">
								Caso Facultativo
							</td>
						</tr>
						<tr>
							<td style="width:5%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e89797;">Aprobado</td>
							<td style="width:5%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e89797;">
								Tasa de Recargo
							</td>
							<td style="width:15%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e89797;">
								Porcentaje de Recargo
							</td>
							<td style="width:15%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e89797;">
								Tasa Actual
							</td>
							<td style="width:15%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e89797;">
								Tasa Final
							</td>
							<td style="width:40%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e89797;">
								Observaciones
							</td>
						</tr>
						<tr>
							<td style="width:5%; text-align: center; background: #e89797; border: 1px solid #dedede;">
								<?=strtoupper($row['aprobado']);?>
							</td>
							<td style="width:5%; text-align: center; background: #e89797; border: 1px solid #dedede;">
								<?=strtoupper($row['tasa_recargo']);?>
							</td>
							<td style="width:15%; text-align: center; background: #e89797; border: 1px solid #dedede;"><?=$row['porcentaje_recargo'];?> %</td>
							<td style="width:15%; text-align: center; background: #e89797; border: 1px solid #dedede;"><?=$row['tasa_actual'];?> %</td>
							<td style="width:15%; text-align: center; background: #e89797; border: 1px solid #dedede;"><?=$row['tr_tasa_final'];?> %</td>
							<td style="width:45%; text-align: justify; background: #e89797; border: 1px solid #dedede;"><?=$row['motivo_facultativo'];?> |<br /><?=$row['tr_observacion_f'];?></td>
						</tr>
					</table>
					<br />
			   <?php
				 }else{
					 if((boolean)$row['facultativo']===TRUE){  
				?>	 
                       <table border="0" cellpadding="1" cellspacing="0" style="width: 100%; border-collapse: collapse; font-size:100%;">
                            <tr>
                                <td colspan="6" style="width:100%; text-align: center; font-weight: bold; background: #e89797; border: 1px solid #dedede; color:#FFF;">
                                    Caso Facultativo
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e89797;">
                                    Observaciones
                                </td>
                            </tr>
                            <tr>
                                
                                <td style="text-align: justify; background: #e78484; border: 1px solid #dedede; color:#FFF;"><?=$row['motivo_facultativo'];?></td>
                            </tr>
                       </table>     
               <?php
					 }
				 }
			   ?>
                </td>
			    <td style="width:5%;"></td>
			</tr>
		</table>
       
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: justify; <?=$fontSize;?>">
			<tr>
				<td style="width:5%;"></td>
				<td style="width:25%;" >&nbsp;</td>
				<td style="width:65%;" >
					<div><em>EL ASEGURADO ACEPTA TENER CONOCMIENTO DE TODOS LOS CONDICIONADOS, CLAUSULAS Y ANEXOS DE LA PRESENTE POLIZA.
					</em></div>
					<br />
				</td>
				<td style="width:5%;"></td>
			</tr>
		</table>
		<br>
        <!-- 
        <div style="padding: 5px; text-align: center; width: 60%; margin: 0 auto; background: #e89797; border: 2px solid #e89797; font-weight: bold; <?=$fontSizeHead;?>">
			CASO FACULTATIVO: CERTIFICADO INVALIDO
		</div>
        <br />
        -->
        <div style="border:0px solid #666; <?=$fontSize;?>">
           <?php
             if((boolean)$row['emitir']===true){
		   ?>
			  <div style="font-style:italic; text-align:center;">LA PAZ, <?=strtoupper(get_date_format($row['fecha_real_emision']));?></div>
            <?php
			 }else{
			?>
              <div style="font-style:italic; text-align:center;">&nbsp;</div>
            <?php
			 }
			?>
			<br />
			<div style="font-weight:bold; text-align:center;"> BISA SEGUROS Y REASEGUROS S.A. </div>
			<br>
			
			<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: center; table-layout:fixed; ">
				<tr>
					<td style="width:15%;">&nbsp;</td>
					<td style="width:35%; text-align:center;">
						<img src="<?=$url;?>img/fbl-01.jpg" width="147" height="80" />
					</td>
					<td style="width:35%; text-align:center;">
						<img src="<?=$url;?>img/fbl-02.jpg" width="157" height="80" />
					</td>
					<td style="width:15%;">&nbsp;</td>
				</tr>
			</table>
			<div style="font-weight:bold; text-align:center; ">FIRMAS AUTORIZADAS</div>
		</div> 
       
        
	</div>
</div>
<?php
	if ($fac === TRUE) {
		$url .= 'index.php?ms='.md5('MS_TRM').'&page='.md5('P_fac').'&ide='.base64_encode($row['id_emision']).'';
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
		$url .= 'index.php?ms='.md5('MS_TRM').'&page='.md5('P_app_imp').'&ide='.base64_encode($row['id_emision']).'';
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

function get_date_format($fecha){
	$date = date_create($fecha);
	
	$day = date_format($date, 'd');
	$month = date_format($date, 'F');
	$year = date_format($date, 'Y');
	
	return $day.' de '.get_month_es($month).' de '.$year;
}

function get_month_es($month){
	switch ($month) {
		case 'January':
			return 'Enero';
			break;
		case 'February':
			return 'Febrero';
			break;
		case 'March':
			return 'Marzo';
			break;
		case 'April':
			return 'Abril';
			break;
		case 'May':
			return 'Mayo';
			break;
		case 'June':
			return 'Junio';
			break;
		case 'July':
			return 'Julio';
			break;
		case 'August':
			return 'Agosto';
			break;
		case 'September':
			return 'Septiembre';
			break;
		case 'October':
			return 'Octubre';
			break;
		case 'November':
			return 'Noviembre';
			break;
		case 'December':
			return 'Diciembre';
			break;
	}
}
?>