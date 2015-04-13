<?php
header("Expires: Thu, 27 Mar 1980 23:59:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require('sibas-db.class.php');
require('session.class.php');
$session = new Session();
$session->getSessionCookie();
$token = $session->check_session();

if(isset($_GET['ids'])){
	$link = new SibasDB();

//	$product = $link->real_escape_string(trim($_GET['ids']));
//	$pr = strtolower($product);
	$ids = $link->real_escape_string(trim(base64_decode($_GET['ids'])));
	$menu = '<ul class="cxt-menu">';

	//$idVh = $link->real_escape_string(trim(base64_decode($_GET['idv'])));
	//$idPr = $link->real_escape_string(trim(base64_decode($_GET['idp'])));
	//$idMt = $link->real_escape_string(trim(base64_decode($_GET['idm'])));
/*
	$idc = '';
	$token = (int)$link->real_escape_string(trim($_GET['token']));
	$ms = $link->real_escape_string(trim($_GET['ms']));
	$page = $link->real_escape_string(trim($_GET['page']));
	$quote = (boolean)$link->real_escape_string(trim($_GET['quote']));
	$issue = (boolean)$link->real_escape_string(trim(base64_decode($_GET['issue'])));
	$bc = false;
	$idd = '';
	$link_dd = '';

	if (isset($_GET['idd'])) {
		$bc = true;
		$idd = $link->real_escape_string(trim(base64_decode($_GET['idd'])));
	}
*/
/*
	$arr_f = array();
	$arr_p = array();

	$cp = false;
	$category = NULL;
	$category2 = NULL;
	$modality = true;

	$titleCert = 'Certificado';
	$titleCert2 = '';

	$arr_state = array('txt' => '', 'txt_bank' => '', 'action' => '', 'obs' => '', 'link' => '', 'bg' => '');
	$menu = '<ul class="cxt-menu">';
*/
	//if($quote === FALSE){
	//	if($token === 0){
			$menu .= '<li>
				<a href="RC-certificate-detail.php?idRc='.base64_encode($ids).'&type='.base64_encode('PRINT').'&category='.base64_encode('RC').'" class="fancybox fancybox.ajax fde-process">Ver Detalle del Siniestro</a>
			</li>';
	//



/*
		}else{

		}

		$sql = '';

		switch ($product) {
			case 'DE':
				$sql = "select
					sde.id_emision as ide,
					sde.id_cotizacion as idc,
					sde.no_emision as r_no_emision,
					sde.id_compania,
					su.usuario,
					if(sdf.aprobado is null,
						if(sdp.id_pendiente is not null,
							case sdp.respuesta
								when 1 then 'S'
								when 0 then 'O'
							end,
							if((sde.emitir = 0 and sde.aprobado = 1)
                               or sde.facultativo = 1,
                               'P',
                                if(sde.emitir = 0 and sde.aprobado = 0
                                  and sde.facultativo = 0,
                                  'P',
                                  'F')
                               )),
						case sdf.aprobado
							when 'SI' then 'A'
							when 'NO' then 'R'
						end) as estado,
					case
						when sds.codigo = 'ED' then 'E'
				        when sds.codigo != 'ED' then 'NE'
						else null
					end as observacion,
					sds.id_estado,
					sds.estado as estado_pendiente,
					sds.codigo as estado_codigo,
					if(sde.anulado = 1,
						1,
						if(sde.emitir = 1, 2, 3)) as estado_banco,
					sde.facultativo as estado_facultativo,
					sde.leido,
					sde.apr_usuario as a_usuario,
					sde.certificado_provisional as cp,
					sde.modalidad
				from
					s_de_em_cabecera as sde
						left join
					s_de_facultativo as sdf ON (sdf.id_emision = sde.id_emision)
						left join
					s_de_pendiente as sdp ON (sdp.id_emision = sde.id_emision)
						left join
					s_estado as sds ON (sds.id_estado = sdp.id_estado)
						inner join
					s_usuario as su ON (su.id_usuario = sde.id_usuario)
				where
					sde.id_emision = '".$ide."'";
				if($token === 0) {
					$sql .= "and not exists( select
							sdf2.id_emision
						from
							s_de_facultativo as sdf2
						where
							sdf2.id_emision = sdf.id_emision)";
				} elseif ($token !== 5
                          && $token !== 3
                          && $token !== 4
                          && $token !== 2
                          && $token !== 6
                          && $token !== 7) {
					$sql .= 'and sde.facultativo = true ';
				}

				$sql .= "limit 0, 1
					;";
				break;
			case 'AU':
				$sql = "select
					sae.id_emision as ide,
					sae.id_cotizacion as idc,
				    sad.id_vehiculo as idVh,
				    sae.no_emision as r_no_emision,
					sae.id_compania,
					su.usuario,
				    if(saf.aprobado is null,
				        if(sap.id_pendiente is not null,
				            case sap.respuesta
				                when 1 then 'S'
				                when 0 then 'O'
				            end,
				            if(sae.emitir = true, 'A', 'P')),
				        case saf.aprobado
				            when 'SI' then 'A'
				            when 'NO' then 'R'
				        end) as estado,
				    case
				        when sds.codigo = 'ED' then 'E'
				        when sds.codigo != 'ED' then 'NE'
				        else null
				    end as observacion,
				    sds.id_estado,
				    sds.estado as estado_pendiente,
					sds.codigo as estado_codigo,
				    if(sae.anulado = 1,
						1,
						if(sae.emitir = 1, 2, 3)) as estado_banco,
					sae.facultativo as estado_facultativo,
				    sad.leido,
				    sae.apr_usuario as a_usuario,
					sae.certificado_provisional as cp
				from
				    s_au_em_detalle as sad
				        inner join
				    s_au_em_cabecera as sae ON (sae.id_emision = sad.id_emision)
						left join
				    s_au_facultativo as saf ON (saf.id_vehiculo = sad.id_vehiculo
				        and saf.id_emision = sae.id_emision)
				        left join
				    s_au_pendiente as sap ON (sap.id_vehiculo = sad.id_vehiculo
				        and sap.id_emision = sae.id_emision)
				        left join
				    s_estado as sds ON (sds.id_estado = sap.id_estado)
						inner join
					s_usuario as su ON (su.id_usuario = sae.id_usuario)
				where
					sad.id_vehiculo = '".$idVh."'
				    	and sad.id_emision = '".$ide."' ";
	        	if($token === 0){
					$sql .= "and sad.aprobado = false
						and not exists( select
							saf2.id_emision, saf2.id_vehiculo
						from
							s_au_facultativo as saf2
						where
							saf2.id_emision = sae.id_emision
								and saf2.id_vehiculo = sad.id_vehiculo)";
				} elseif ($token !== 5
                          && $token !== 3
                          && $token !== 4
                          && $token !== 2
                          && $token !== 6
                          && $token !== 7) {
					$sql .= 'and sad.facultativo = true ';
				}
				$sql .= " limit 0, 1 ;";
				break;
			case 'TRD':
				$sql = "select
				    stre.id_emision as ide,
				    stre.id_cotizacion as idc,
				    stre.no_emision as r_no_emision,
				    stre.id_compania,
				    su.usuario,
				    if((stre.emitir = 0 and stre.aprobado = 0)
				            or stre.facultativo = 1,
				        'P',
				        'F') as estado,
				    if(stre.anulado = 1,
				        1,
				        if(stre.emitir = 1, 2, 3)) as estado_banco,
				    null as observacion,
				    stre.facultativo as estado_facultativo,
				    stre.leido,
				    stre.apr_usuario as a_usuario,
					stre.certificado_provisional as cp
				from
				    s_trd_em_cabecera as stre
				        inner join
				    s_usuario as su ON (su.id_usuario = stre.id_usuario)
				where stre.id_emision = '".$ide."'
				limit 0, 1
				;";
				break;
			case 'TRM':
				$sql = "select
				    stre.id_emision as ide,
				    stre.id_cotizacion as idc,
				    stre.no_emision as r_no_emision,
					stre.id_compania,
				    if(strf.aprobado is null,
				        if(strp.id_pendiente is not null,
				            case strp.respuesta
				                when 1 then 'S'
				                when 0 then 'O'
				            end,
				            if((stre.emitir = 0 and stre.aprobado = 1)
				                    or stre.facultativo = 1,
				                'P',
				                'F')),
				        case strf.aprobado
				            when 'SI' then 'A'
				            when 'NO' then 'R'
				        end) as estado,
				    case
				        when sds.codigo = 'ED' then 'E'
				        when sds.codigo != 'ED' then 'NE'
				        else null
				    end as observacion,
				    sds.id_estado,
				    sds.estado as estado_pendiente,
					sds.codigo as estado_codigo,
				    if(stre.anulado = 1,
						1,
						if(stre.emitir = 1, 2, 3)) as estado_banco,
					stre.facultativo as estado_facultativo,
				    stre.prefijo,
				    stre.leido,
					stre.certificado_provisional as cp
				from
				    s_trm_em_cabecera as stre
				        left join
				    s_trm_facultativo as strf ON (strf.id_emision = stre.id_emision)
				        left join
				    s_trm_pendiente as strp ON (strp.id_emision = stre.id_emision)
				        left join
				    s_estado as sds ON (sds.id_estado = strp.id_estado)
				where
				    stre.id_emision = '".$ide."' ";
				if($token === 0) {
					$sql .= "and not exists( select
			            strf2.id_emision
			        from
			            s_trm_facultativo as strf2
			        where
			            strf2.id_emision = strf.id_emision)";
				} elseif ($token !== 5
                          && $token !== 3
                          && $token !== 4
                          && $token !== 2
                          && $token !== 6
                          && $token !== 7) {
					$sql .= 'and stre.facultativo = true ';
				}
				$sql .= "limit 0 , 1
				";
				break;
		}
		//echo $sql;

		if(($rs = $link->query($sql,MYSQLI_STORE_RESULT))){
			if($rs->num_rows === 1){
				$row = $rs->fetch_array(MYSQLI_ASSOC);
				$rs->free();

				$idc = $row['idc'];
				$cp = (boolean)$row['cp'];
				if ($cp === true) {
					$category = base64_encode('CP');
					$titleCert = 'Certificado Provisional';
				} else {
					$category = base64_encode('CE');
					$titleCert = 'Certificado';
					if ($product === 'DE' && $row['modalidad'] === null) {
						$modality = false;
						$category2 = base64_encode('PEC');
						$titleCert = 'Certificado Desgravamen';
						$titleCert2 = 'Certificado Vida en Grupo';
					}
				}

				//echo (int)$issue;
				$link->get_state($arr_state, $row, $token, $product, $issue);

				if ($product === 'DE' && $bc === true) {
					$sqlDt = 'select
						sdd.id_detalle,
						sdd.aprobado,
						sdd.facultativo,
						sdd.detalle_f,
						sdd.detalle_p
					from s_de_em_detalle as sdd
					where sdd.id_emision = "' . $ide . '"
					;';

					if (($rsDt = $link->query($sqlDt, MYSQLI_STORE_RESULT)) !== false) {
						if ($rsDt->num_rows > 0) {
							while ($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)) {
								if ($rowDt['id_detalle'] === $idd) {
									$arr_f = json_decode($rowDt['detalle_f'], true);
									$arr_p = json_decode($rowDt['detalle_p'], true);

									$link_dd = '&idd=' . base64_encode($idd);

									if (empty($arr_f) === true) {
										if ($token === 0 || $token === 1) {
											$arr_state['txt'] = 'PENDIENTE';
										} else {
											$arr_state['txt'] = 'APROBADO';
										}
										$arr_state['obs'] = 'NINGUNA';
										$arr_state['action'] = '';

										if (count($arr_p) > 0) {
											$arr_state['obs'] = $arr_p['estado'];

											if ($arr_p['respuesta'] === null) {
												$arr_state['txt'] = 'OBSERVADO';

												if ($token === 1) {
													switch ($arr_p['id_estado']) {
													case 3:
														$arr_state['action'] = 'Responder';
														$arr_state['link'] =
															'fac-de-observation.php?ide='
															. base64_encode($row['ide']).'&idd='
															. base64_encode($idd) . '&resp='.md5('R0');
														break;
													case 4:
														$fancybox = '';
														$arr_state['action'] = 'Editar Certificado';
														//$row['observacion'] = 'E';
														$arr_state['link'] =
															'de-quote.php?ms=&page=&pr='
															. base64_encode($product . '|05')
															. '&ide=' . base64_encode($row['ide'])
															. '&cia=' . base64_encode($row['id_compania'])
															. '&flag=' . md5('i-read') . '&target=' . md5('ERROR-C')
															. '&idd=' . base64_encode($idd);
														break;
													default:
														# code...
														break;
													}
												}
											} else {
												$arr_state['txt'] = 'SUBSANADO/PENDIENTE';
												$arr_state['action'] = 'Respondido';
												$row['estado'] = 'S';
												$arr_state['link'] =
													'fac-de-observation.php?ide='
													. base64_encode($row['ide']) .'&idd='
													. base64_encode($idd) . '&resp='.md5('R1');
											}
										}
									} else {
										if (count($arr_f) > 0) {
											if ($arr_f['aprobado'] === 'SI') {
												$arr_state['obs'] = $arr_state['txt'] = 'APROBADO';
											} else {
												$arr_state['obs'] = $arr_state['txt'] = 'RECHAZADO';
											}

											$arr_state['action'] = '';
											$arr_state['link'] = '';

											$arr_data = $link->getApprovedBc($ide);

											if ($arr_data['n_ap'] > 0 && $arr_data['n_pe'] === 0 && $token === 1) {
												$arr_state['action'] = 'Emitir';
												$arr_state['link'] =
													'fac-issue-policy.php?ide='
													. base64_encode($row['ide'])
													. '&pr=' . base64_encode($product);
											}
										}
									}

									break;
								}
							}

							$rsDt->free();
						}
					}
				}

				$menu .= '<li><span class="cm-link"><span class="view-ste">Estado => '
					. $arr_state['txt'] . '</span></span></li>';

				if($arr_state['obs'] === 'NINGUNA' || (boolean)$row['estado_facultativo'] === false){
					$menu .= '<li><span class="cm-link"><span class="view-obs">Observación => '
						. $arr_state['obs'] . '</span></span></li>';
				}else{
					$menu .= '<li><a href="fac-' . $pr . '-observation.php?ide='
						. base64_encode($ide) . '&idvh=' . base64_encode($idVh)
						. $link_dd . '" class="fancybox fancybox.ajax observation">
						<span class="view-obs">Observación => ' . $arr_state['obs']
						. '</span></a></li>';
				}
				//echo $token.' - '.$row['observacion'].' - '.(int)$issue;
				if(empty($arr_state['action']) === false){
					$fancybox = 'fancybox fancybox.ajax observation';
					//echo $row['observacion'].' - '.$token;

					if(($row['observacion'] === 'E' || $token === 3)
						&& $row['estado'] !== 'A'
						&& $token !== 4 ) {
						$fancybox = '';

						if ($row['estado'] === 'S') {
							$fancybox = 'fancybox fancybox.ajax observation';
						}
					}

					if ($product === 'DE' || $product === 'AU' || $product === 'TRD' || $product === 'TRM') {
						$menu .= '<li><a href="' . $arr_state['link'] . '" class="'
							. $fancybox . '"><span class="view-act">Acción => '
							. $arr_state['action'] . '</span></a></li>';
					}
				}

				if($token === 1){
					$txtMark = '';
					switch((int)$row['leido']){
						case 1:	$txtMark = 'Marcar como no Leído';	break;
						case 0:	$txtMark = 'Marcar como Leído';	break;
					}

					$menu .= '<li><a href="mark-read-unread.php?ide='.base64_encode($ide).'&idvh='.base64_encode($idVh).'&flag='.base64_encode((int)$row['leido']).'&fwd='.base64_encode($product).'" class="fancybox fancybox.ajax fde-process">'.$txtMark.'</a></li>';
				}
			}
		}
		//echo $product;
		$link->close();
		if($token !== 3 && $token !== 7) {
			$menu .= '<li><a href="certificate-detail.php?ide='.base64_encode($ide).'&pr='.base64_encode($product).'&type='.base64_encode('PRINT').'&category='.$category.'" class="fancybox fancybox.ajax observation">Ver '.$titleCert.'</a></li>';

			if ($product === 'DE' && $modality === false) {
				//$menu .= '<li><a href="certificate-detail.php?ide='.base64_encode($ide).'&pr='.base64_encode($product).'&type='.base64_encode('PRINT').'&category='.$category2.'" class="fancybox fancybox.ajax observation">Ver '.$titleCert2.'</a></li>';
			}
		}

		if ($product !== 'DE') {
			$menu .= '<li><a href="certificate-detail.php?idc='.base64_encode($idc).'&cia='.base64_encode($row['id_compania']).'&pr='.base64_encode($product).'&type='.base64_encode('PRINT').'" class="fancybox fancybox.ajax observation">Ver Slip de Cotización</a></li>';
		}

	}else{
		$idc = $ide;
		if($token === 3 || $token === 2){
			$sql = 'select
			    scot.id_cotizacion as idc,
			    scot.no_cotizacion as no_ct,
				scia.id_compania,
			    scot.fecha_creacion,
			    if(datediff(curdate(), scot.fecha_creacion) <= sh.limite_cotizacion,
			        0,
			        1) as limite
			from
			    s_'.$pr.'_cot_cabecera as scot
			        inner join
			    s_entidad_financiera as sef ON (sef.id_ef = scot.id_ef)
					inner join
				s_ef_compania as sec ON (sec.id_ef = sef.id_ef)
					inner join
				s_compania as scia ON (scia.id_compania = sec.id_compania)
			        inner join
			    s_sgc_home as sh ON (sh.id_ef = scot.id_ef)
			where
			    scot.id_cotizacion = "'.$idc.'"
			        and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
			        and sef.activado = true
			        and sh.producto = "'.$product.'"
					 and sec.producto = "'.$product.'"
			;';
			//echo $sql;
			if(($rs = $link->query($sql, MYSQLI_STORE_RESULT))){
				if ($rs->num_rows === 1) {
					$row = $rs->fetch_array(MYSQLI_ASSOC);
					$limit = (int)$row['limite'];
					if ($token === 3) {
						if($limit === 0) {
							$pr_href = $product.'|';
							if ($product === 'DE') {
								$pr_href .= '04';
							} else {
								$pr_href .= '03';
							}

							$menu .= '<li><a href="'.$pr.'-quote.php?ms='.md5('MS_'.$product).'&page='.md5('P_quote').'&pr='.base64_encode($pr_href).'&idc='.base64_encode($idc).'">Emitir Cotización</a></li>';
						} elseif($limit === 1) {
							$menu .= '<li><span class="cm-link"><span class="view-ste">FECHA LÍMITE DE EMISIÓN CADUCADA</span></span></li>';
						}
					}
				}
			}
		}

		if ($token !== 3) {
			$menu .= '<li><a href="certificate-detail.php?idc='.base64_encode($idc).'&cia='.base64_encode($row['id_compania']).'&pr='.base64_encode($product).'&type='.base64_encode('PRINT').'" class="fancybox fancybox.ajax observation">Ver Slip de Cotización</a></li>';
		}
	}

	$menu .= '</ul>';
*/
	echo $menu;
}
?>