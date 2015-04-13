<script type="text/javascript">
$(document).ready(function(e) {
	$("#fde-customer").validateForm({
		action: 'DE-customer-record.php'
	});
	
	$(".dc-date-birth").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
		yearRange: "c-100:c+100"
	});
	
	$(".dc-date-birth").datepicker($.datepicker.regional[ "es" ]);
	
	$("#dc-next").click(function(e){
		e.preventDefault();
		location.href = 'de-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=base64_encode('DE|03');?>&idc=<?=$_GET['idc']?>';
	});
	
	$("input[type='text'].fbin, textarea.fbin").keyup(function(e){
		var arr_key = new Array(37, 39, 8, 16, 32, 18, 17, 46, 36, 35, 186);
		var _val = $(this).prop('value');
		
		if($.inArray(e.keyCode, arr_key) < 0 && $(this).hasClass('email') === false){
			$(this).prop('value',_val.toUpperCase());
		}
	});
	
});
</script>
<?php
require_once('sibas-db.class.php');
$link = new SibasDB();

$bc = false;
if ($link->checkBancaComunal($_GET['idc']) === true) {
	$bc = true;
}

$max_item = 0;
if (($row_cov = $link->getTypeCoverage($_GET['idc'])) !== false) {
	if (1 === (int)$row_cov['cobertura']) {
		$max_item = 1;
	} else {
		MaxItem:
		if (($rowDE = $link->get_max_amount_optional($_SESSION['idEF'], 'DE')) !== FALSE) {
			$max_item = (int)$rowDE['max_item'];
		}
	}
} else {
	goto MaxItem;
}


$swCl = FALSE;

$arr_cl = array();
$temp_data = array(
	'code' => '',
	'name' => '',
	'patern' => '',
	'matern' => '',
	'married' => '',
	'status' => '',
	'type_doc' => '',
	'doc_id' => '',
	'comp' => '',
	'ext' => '',
	'country' => '',
	'birth' => '',
	'place_birth' => '',
	'place_res' => '',
	'locality' => '',
	'address' => '',
	'phone_1' => '',
	'phone_2' => '',
	'email' => '',
	'phone_office' => '',
	'occupation' => '',
	'occ_desc' => '',
	'gender' => '',
	'weight' => '',
	'height' => '',
	'amount' => 0,
	'amount_bc' => '',
	);

$arr_cl[0] = $temp_data;

$title_btn = 'Agregar Titular';
$err_search = '';
$web_service = false;

if(isset($_POST['dsc-dni'])){
	$dni = $link->real_escape_string(trim($_POST['dsc-dni']));
    $web_service = $link->checkWebService($_SESSION['idEF'], 'DE');

    if (true === $web_service) {
        require ('classes/WebServiceIdepro.php');

        $ws = new WebServiceIdepro();

        if ($ws->webServiceConnect($dni) === true) {
            $data = $ws->getResult();
            if (is_array($data) === true) {
                $temp_data['code'] = '';
				$temp_data['name'] = $data['cl_nombre'];
				$temp_data['patern'] = $data['cl_paterno'];
				$temp_data['matern'] = $data['cl_materno'];
				$temp_data['married'] = $data['cl_ap_casada'];
				$temp_data['status'] = $data['cl_estado_civil'];
				$temp_data['type_doc'] = 'CI';
				$temp_data['doc_id'] = $data['cl_ci'];
				$temp_data['comp'] = $data['cl_complemento'];
				$temp_data['ext'] = '';
                if (($rowExt = $link->getExtenssionCode($data['cl_extension'])) !== false) {
                    $temp_data['ext'] = $rowExt['id_depto'];
                }
                $temp_data['birth'] = $data['cl_fecha_nacimiento'];
				$temp_data['address'] = $data['cl_direccion'];
				$temp_data['occupation'] = '';

                if (($rowOcc = $link->get_occupation_code(
                        $_SESSION['idEF'], $data['cl_caedec'], 'DE')) !== false) {
                    $temp_data['occupation'] = $rowOcc['id_ocupacion'];
                }
                $temp_data['occ_desc'] = $data['cl_caedec_desc'];
				$temp_data['phone_1'] = $data['cl_tel_domicilio'];
				$temp_data['phone_2'] = $data['cl_celular'];
				$temp_data['phone_office'] = '';
				$temp_data['email'] = $data['cl_email'];
				$temp_data['gender'] = $data['cl_genero'];
				$temp_data['amount'] = $data['cl_saldo'];

				$temp_data['country'] = '';
				$temp_data['place_birth'] = '';
				$temp_data['place_res'] = '';
				$temp_data['locality'] = '';
				$temp_data['weight'] = '';
				$temp_data['height'] = '';
				$temp_data['amount_bc'] = '';

				$arr_cl[0] = $temp_data;
            } else {
                $err_search = $data;
            }
        } else {
            $err_search = $ws->message;
        }
    } else {
        $sqlSc = 'select
			scl.id_cliente,
			scl.nombre as cl_nombre,
			scl.paterno as cl_paterno,
			scl.materno as cl_materno,
			scl.ap_casada as cl_ap_casada,
			scl.estado_civil as cl_estado_civil,
			scl.tipo_documento as cl_tipo_documento,
			scl.ci as cl_dni,
			scl.complemento as cl_complemento,
			scl.extension as cl_extension,
			scl.fecha_nacimiento as cl_fecha_nacimiento,
			scl.pais as cl_pais,
			scl.lugar_nacimiento as cl_lugar_nacimiento,
			scl.lugar_residencia as cl_lugar_residencia,
			scl.localidad as cl_localidad,
			scl.direccion as cl_direccion,
			scl.telefono_domicilio as cl_tel_domicilio,
			scl.telefono_celular as cl_tel_celular,
			scl.telefono_oficina as cl_tel_oficina,
			scl.email as cl_email,
			scl.id_ocupacion as cl_ocupacion,
			scl.desc_ocupacion as cl_desc_ocupacion,
			scl.genero as cl_genero,
			scl.saldo_deudor as cl_saldo,
			sdd.monto_banca_comunal as cl_monto_bc
		from
			s_de_cot_cliente as scl
				inner join
					s_de_cot_detalle as sdd on (sdd.id_cliente = scl.id_cliente)
				inner join
				    s_entidad_financiera as sef on (sef.id_ef = scl.id_ef)
		where
			scl.ci = "'.$dni.'"
				and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
				and sef.activado = true
		limit 0 , 1
		;';

        if(($rsSc = $link->query($sqlSc,MYSQLI_STORE_RESULT)) !== false){
            if($rsSc->num_rows === 1){
                $rowSc = $rsSc->fetch_array(MYSQLI_ASSOC);
                $rsSc->free();

				$temp_data['code'] = '';
				$temp_data['name'] = $rowSc['cl_nombre'];
				$temp_data['patern'] = $rowSc['cl_paterno'];
				$temp_data['matern'] = $rowSc['cl_materno'];
				$temp_data['married'] = $rowSc['cl_ap_casada'];
				$temp_data['status'] = $rowSc['cl_estado_civil'];
				$temp_data['type_doc'] = $rowSc['cl_tipo_documento'];
				$temp_data['doc_id'] = $rowSc['cl_dni'];
				$temp_data['comp'] = $rowSc['cl_complemento'];
				$temp_data['ext'] = $rowSc['cl_extension'];
				$temp_data['country'] = $rowSc['cl_pais'];
				$temp_data['birth'] = $rowSc['cl_fecha_nacimiento'];
				$temp_data['place_birth'] = $rowSc['cl_lugar_nacimiento'];
				$temp_data['place_res'] = $rowSc['cl_lugar_residencia'];
				$temp_data['locality'] = $rowSc['cl_localidad'];
				$temp_data['address'] = $rowSc['cl_direccion'];
				$temp_data['phone_1'] = $rowSc['cl_tel_domicilio'];
				$temp_data['phone_2'] = $rowSc['cl_tel_celular'];
				$temp_data['phone_office'] = $rowSc['cl_tel_oficina'];
				$temp_data['email'] = $rowSc['cl_email'];
				$temp_data['occupation'] = $rowSc['cl_ocupacion'];
				$temp_data['occ_desc'] = $rowSc['cl_desc_ocupacion'];
				$temp_data['gender'] = $rowSc['cl_genero'];
				$temp_data['weight'] = '';
				$temp_data['height'] = '';
				$temp_data['amount'] = $rowSc['cl_saldo'];
				//$temp_data['amount_bc'] = $rowSc['cl_monto_bc'];
				$arr_cl[0] = $temp_data;

            }else{
				$err_search = 'El Titular no Existe !';
            }
        }else{
            $err_search = 'El Titular no Existe';
        }
    }
} elseif (isset($_POST['dsc-sc'])) {
	if (!empty($_POST['dc-attached'])) {
		$file = $link->real_escape_string(trim(base64_decode($_POST['dc-attached'])));
		$file = 'files/' . $file;

		if (file_exists($file) === true) {
			if (is_file($file) === true) {
				if (($lines = file($file, FILE_SKIP_EMPTY_LINES)) !== false) {
					$pos = 0;
					foreach ($lines as $templine) {
						$temp_data = array();
						$especiales = array("-", "s/n");
	    				$reemplazos = array("", "");

						$data = explode('|', $templine);
						$data[5] = (int)$data[5];
						$data[6] = (int)$data[6];
						$data[21] = (int)$data[21];

						$temp_data['code'] = $data[0];		// Codigo
						$temp_data['name'] = $data[1];		// Nombre
						$temp_data['patern'] = $data[2];	// Apellido Paterno
						$temp_data['matern'] = $data[3];	// Apellido Materno
						$temp_data['married'] = $data[4];	// Apellido Casada
						$temp_data['status'] = '';
						switch ($data[5]) {					// Estado Civil
						case 1:
							$temp_data['status'] = 'SOL';
							break;
						case 2:
							$temp_data['status'] = 'CAS';
							break;
						case 3:
							$temp_data['status'] = 'VIU';
							break;
						case 4:
							$temp_data['status'] = 'DIV';
							break;
						case 5:
							$temp_data['status'] = 'CON';
							break;
						}
						$temp_data['type_doc'] = '';
						switch ($data[6]) {					// Tipo de Documento
						case 1:
							$temp_data['type_doc'] = 'CI';
							break;
						case 2:
							$temp_data['type_doc'] = 'RUN';
							break;
						case 3:
							$temp_data['type_doc'] = 'CE';
							break;
						}

						$temp_data['doc_id'] = $data[7];	// CI
						$temp_data['comp'] = $data[8];		// Complemento
						$temp_data['ext'] = '';	
						if (($rowExt = $link->getExtenssionCode($data[9])) !== false) {
			                $temp_data['ext'] = $rowExt['id_depto'];		// Complemento
			            }
						$temp_data['country'] = $data[11];					// Pais
						$temp_data['birth'] = date('Y-m-d', 
							strtotime(str_replace('/', '-', $data[10])));	// Fecha de nacimiento
						$temp_data['place_birth'] = $data[12];				// Lugar de nacimiento
						$temp_data['place_res'] = $data[13];				// Lugar de residencia
						$temp_data['locality'] = $data[14];					// Localidad
						$temp_data['address'] = $data[15];					// Direccion
						$temp_data['phone_1'] = str_replace($especiales, 
							$reemplazos, $data[16]);						// Telefono domicilio
						$temp_data['phone_2'] = str_replace($especiales, 
							$reemplazos, $data[17]);						// Telefono celular
						$temp_data['email'] = '';							// Email
						$temp_data['phone_office'] = str_replace(
							$especiales, $reemplazos, $data[18]);			// Telefono oficina

						$occ_code = '';										// Ocupacion
						switch ($data[19]) {
						case 'PRIVADA':
							$occ_code = 'PR';
							break;
						case 'PUBLICA':
							$occ_code = 'PU';
							break;
						case 'RURAL':
							$occ_code = 'RU';
							break;
						}

						$temp_data['occupation'] = '';						// Ocupacion
						if (($rowOcc = $link->get_occupation_code(
			                    $_SESSION['idEF'], $occ_code, 'DE')) !== false) {
			                $temp_data['occupation'] = $rowOcc['id_ocupacion'];
			            }
						$temp_data['occ_desc'] = $data[20];					// Descripcion Ocupacion
						$temp_data['gender'] = '';							// Genero
						switch ($data[21]) {
						case 1:
							$temp_data['gender'] = 'M';
							break;
						case 2:
							$temp_data['gender'] = 'F';
							break;
						}
						$temp_data['weight'] = $data[22];			// Peso
						$temp_data['height'] = $data[23];			// Estatura
						$temp_data['amount'] = $data[24];			// Monto Solicitado
						$temp_data['amount_bc'] = $data[25];		// Monto Banca Comunal

						$arr_cl[$pos] = $temp_data;
						$pos += 1;
					}

					if (unlink($file) === true) {
						//var_dump($arr_cl);
					}
				} else {
					$err_search = 'Error: Adjunte un archivo.';
				}
			} else {
				$err_search = 'Error: Adjunte un archivo!';
			}
		} else {
			$err_search = 'Error: Adjunte un archivo';
		}

		/*$array = file('files/' . );
        $especiales = array("-", "s/n");
	    $reemplazos = array("", "");

		foreach ($array as $clave => $valor){
			$data = explode("|", $valor);
			$dc_code_client = $data[0];
			$dc_name = $data[1];
			$dc_lnpatern = $data[2];
            $dc_lnmatern = $data[3];
            $dc_lnmarried = $data[4];
            $dc_status = '';

			if ($data[5] == 1) {
				$dc_status = 'SOL';
			} elseif ($data[5] == 2) {
				$dc_status = 'CAS';
			} elseif ($data[5] == 3) {
				$dc_status = 'VIU';
			} elseif ($data[5] == 4) {
				$dc_status = 'DIV';
			} elseif ($data[5] == 5) {
				$dc_status = 'CON';
			}

			$dc_type_doc = '';
			if ($data[6] == 1) {
				$dc_type_doc = 'CI';
			} elseif ($data[6] == 2) {
				$dc_type_doc = 'RUN';
			} elseif ($data[6] == 3) {
				$dc_type_doc = 'CE';
			}
			
            $dc_doc_id = $data[7];
            $dc_comp = $data[8];
			$dc_ext = '';

			if (($rowExt = $link->getExtenssionCode($data[9])) !== false) {
                $dc_ext = $rowExt['id_depto'];
            }

			$fc = explode('/', $data[10]);
			$dc_birth = $fc[2] . '-' . $fc[1] . '-' . $fc[0];
			$dc_country = $data[11];
			$dc_place_birth = $data[12];
			$dc_workplace = $data[13];
			$dc_locality = $data[14];
			$dc_address = $data[15];
			$dc_phone_1 = str_replace($especiales, $reemplazos, $data[16]);
			$dc_phone_2 = $data[17];
			$dc_phone_office = $data[18];
			$code = '';

			if ($data[19] == 'PRIVADA') {
				$code = 'PR';
		    } elseif ($data[19] == 'PUBLICA') {
				$code = 'PU';
			} elseif ($data[19] == 'RURAL') {
				$code = 'RU';
			}

			$dc_occupation = '';
			if (($rowOcc = $link->get_occupation_code(
                    $_SESSION['idEF'], $code, 'DE')) !== false) {
                $dc_occupation = $rowOcc['id_ocupacion'];
            }
			$dc_desc_occ = $data[20];
			$dc_gender = '';

			if($data[21] == 1) {
			   $dc_gender = 'M';
			} elseif ($data[21] == 2) {
			   $dc_gender = 'F';
			}
			$dc_weight = $data[22];
			$dc_height = $data[23];
			$dc_amount = $data[24];
		}
		unlink('files/'.base64_decode($_POST['dc-attached']));*/
	} else {
		$err_search = 'Error: Seleccione un archivo';
	}
}

if(isset($_GET['idCl'])){
	$swCl = TRUE;
	$title_btn = 'Actualizar datos';
	
	$sqlUp = 'select 
			scl.id_cliente,
			scl.paterno,
			scl.materno,
			scl.nombre,
			scl.ap_casada,
			scl.fecha_nacimiento,
			scl.lugar_nacimiento,
			scl.ci,
			scl.extension,
			scl.complemento,
			scl.tipo_documento,
			scl.estado_civil,
			scl.lugar_residencia,
			scl.localidad,
			scl.direccion,
			scl.pais,
			scl.id_ocupacion,
			scl.desc_ocupacion,
			scl.telefono_domicilio,
			scl.telefono_oficina,
			scl.telefono_celular,
			scl.email,
			scl.peso,
			scl.estatura,
			scl.genero,
			scl.saldo_deudor as cl_saldo,
			sdd.monto_banca_comunal as cl_monto_bc
		from
			s_de_cot_cliente as scl
				inner join 
			s_de_cot_detalle as sdd on (sdd.id_cliente = scl.id_cliente)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = scl.id_ef)
		where
			scl.id_cliente = "'.base64_decode($_GET['idCl']).'"
				and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
				and sef.activado = true
				and sdd.id_cotizacion = "' . base64_decode($_GET['idc']) . '"
		;';
	
	$rsUp = $link->query($sqlUp);

	if($rsUp->num_rows === 1){
		$rowUp = $rsUp->fetch_array(MYSQLI_ASSOC);
		$rsUp->free();

		$temp_data['code'] = '';
		$temp_data['name'] = $rowUp['nombre'];
		$temp_data['patern'] = $rowUp['paterno'];
		$temp_data['matern'] = $rowUp['materno'];
		$temp_data['married'] = $rowUp['ap_casada'];
		$temp_data['status'] = $rowUp['estado_civil'];
		$temp_data['type_doc'] = $rowUp['tipo_documento'];
		$temp_data['doc_id'] = $rowUp['ci'];
		$temp_data['comp'] = $rowUp['complemento'];
		$temp_data['ext'] = $rowUp['extension'];
		$temp_data['country'] = $rowUp['pais'];
		$temp_data['birth'] = $rowUp['fecha_nacimiento'];
		$temp_data['place_birth'] = $rowUp['lugar_nacimiento'];
		$temp_data['place_res'] = $rowUp['lugar_residencia'];
		$temp_data['locality'] = $rowUp['localidad'];
		$temp_data['address'] = $rowUp['direccion'];
		$temp_data['phone_1'] = $rowUp['telefono_domicilio'];
		$temp_data['phone_2'] = $rowUp['telefono_celular'];
		$temp_data['email'] = $rowUp['email'];
		$temp_data['phone_office'] = $rowUp['telefono_oficina'];
		$temp_data['occupation'] = $rowUp['id_ocupacion'];
		$temp_data['occ_desc'] = $rowUp['desc_ocupacion'];
		$temp_data['gender'] = $rowUp['genero'];
		$temp_data['weight'] = $rowUp['peso'];
		$temp_data['height'] = $rowUp['estatura'];
		$temp_data['amount'] = $rowUp['cl_saldo'];
		$temp_data['amount_bc'] = $rowUp['cl_monto_bc'];

		$arr_cl[0] = $temp_data;
	}
}
?>
<h3>Datos del Titular</h3>
<?php
$nCl = 0;
if($swCl === FALSE){
	$sqlCl = 'select 
		scl.id_cliente,
		scl.nombre as cl_nombre,
		scl.paterno as cl_paterno,
		scl.materno as cl_materno,
		concat(scl.ci, scl.complemento, " ", sde.codigo) as cl_dni,
		date_format(scl.fecha_nacimiento, "%d/%m/%Y") as cl_fn,
		(case scl.genero
			when "M" then "Hombre"
			when "F" then "Mujer"
		end) as cl_genero,
		sdd.porcentaje_credito as cl_pc
	from
		s_de_cot_cliente as scl
			inner join
		s_departamento as sde ON (sde.id_depto = scl.extension)
			inner join
		s_de_cot_detalle as sdd ON (sdd.id_cliente = scl.id_cliente)
			inner join
		s_de_cot_cabecera as sdc ON (sdc.id_cotizacion = sdd.id_cotizacion)
			inner join 
		s_entidad_financiera as sef ON (sef.id_ef = scl.id_ef)
	where
		sdc.id_cotizacion = "'.base64_decode($_GET['idc']).'"
			and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
			and sef.activado = true
	order by sdd.id_detalle asc
	limit 0, '.$max_item.'
	;';

	$rsCl = $link->query($sqlCl,MYSQLI_STORE_RESULT);
	$nCl = $rsCl->num_rows;
	if ($nCl < $max_item) {
		if($web_service === true){
?>
<form id="fde-sc" name="fde-sc" action="" method="post" class="form-quote">
	<label>Documento de Identidad: <span>*</span></label>
	<div class="content-input" style="width:auto;">
		<input type="text" id="dsc-dni" name="dsc-dni" autocomplete="off" value="" style="width:120px;" class="required text fbin">
	</div>
	<input type="submit" id="dsc-sc" name="dsc-sc" value="Buscar Titular" class="btn-search-cs">
    <div class="mess-err-sc"><?=$err_search;?></div>
</form>
<hr>
<?php
		} else {
?>
<form id="fde-sc" name="fde-sc" action="" method="post" class="form-quote" enctype="multipart/form-data">
	<label>Archivo: <span>*</span></label>
	<div class="content-input" style="width:auto;">
		<!--<input type="file" id="file-import" name="file-import"  style="width:310px;" class="required text fbin"/>-->
		<a href="javascript:;" id="a-dc-attached" class="attached">Seleccione archivo</a>
		<div class="attached-mess" style="width:220px;">
			El formato del archivo a subir debe ser TXT
		</div>
		<script type="text/javascript">
			set_ajax_upload('dc-attached', 'DE');
		</script>
	</div>
	<input type="submit" id="dsc-sc" name="dsc-sc" value="Importar" class="btn-search-cs"/>
	<input type="hidden" id="dc-attached" name="dc-attached" value="" class="required"/>
	<div class="mess-err-sc"><?=$err_search;?></div>
</form>
<hr>
<?php
		}
	}
}
?>

<form id="fde-customer" name="fde-customer" action="" method="post" class="form-quote form-customer">
<?php
if($swCl === FALSE){
	if($nCl > 0){
	?>
		<table class="list-cl">
			<thead>
				<tr>
					<td style="width:5%;"></td>
					<td style="width:10%;">Documento de Identidad</td>
					<td style="width:15%;">Nombres</td>
					<td style="width:16%;">Ap. Paterno</td>
					<td style="width:16%;">Ap. Materno</td>
					<td style="width:10%;">Fecha de Nacimiento</td>
					<td style="width:11%;">Genero</td>
					<td style="width:5%;">% Crédito</td>
					<td style="width:12%;"></td>
				</tr>
			</thead>
			<tbody>
	<?php
		$cont = 1;
		while($rowCl = $rsCl->fetch_array(MYSQLI_ASSOC)){
	?>
				<tr>
					<td style="font-weight:bold;">T<?=$cont;?></td>
					<td><?=$rowCl['cl_dni'];?></td>
					<td><?=$rowCl['cl_nombre'];?></td>
					<td><?=$rowCl['cl_paterno'];?></td>
					<td><?=$rowCl['cl_materno'];?></td>
					<td><?=$rowCl['cl_fn'];?></td>
					<td><?=$rowCl['cl_genero'];?></td>
					<td><?=$rowCl['cl_pc'];?></td>
					<td><a href="de-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=$_GET['pr'];?>&idc=<?=$_GET['idc'];?>&idCl=<?=base64_encode($rowCl['id_cliente']);?>" title="Editar Información"><img src="img/edit-user-icon.png" width="40" height="40" alt="Editar Información" title="Editar Información"></a></td>
				</tr>
<?php
			$cont += 1;
		}
		$rsCl->free();
?>
			</tbody>
		</table>
		
		<div class="mess-cl">
<?php
		for ($i=1; $i <= $nCl; $i++) { 
			echo 'T' . $i . ': Titular ' . $i . '<br>';
		}
?>
		</div>
		<input type="button" id="dc-next" name="dc-next" value="Continuar" class="btn-next" >
		<hr>
	<?php
	}
}
if($nCl < $max_item || $swCl === TRUE){
	$client = count($arr_cl);
?>
	<p style="margin: 10px 0; font-size: 80%; font-style: italic; 
		text-align: center; font-weight: bold; color: #037984; ">
		"Los campos marcados con asterisco <span style="color: #f00;" >(*)</span> son obligatorios".
	</p>

<?php
	for ($k = 0; $k < $client ; $k++) { 
		$data = $arr_cl[$k];

?>
	<div class="form-col">
		<label>Nombres: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-name-<?=$k?>" name="dc-name-<?=$k?>" autocomplete="off" 
				value="<?=$data['name'];?>" class="required text fbin">
		</div><br>		
		
		<label>Apellido Paterno: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-ln-patern-<?=$k?>" name="dc-ln-patern-<?=$k?>" 
				autocomplete="off" value="<?=$data['patern'];?>" class="required text fbin">
		</div><br>
		
		<label>Apellido Materno: </label>
		<div class="content-input">
			<input type="text" id="dc-ln-matern-<?=$k?>" name="dc-ln-matern-<?=$k?>" 
				autocomplete="off" value="<?=$data['matern'];?>" class="not-required text fbin">
		</div><br>
		
		<label>Apellido de Casada: </label>
		<div class="content-input">
			<input type="text" id="dc-ln-married-<?=$k?>" name="dc-ln-married-<?=$k?>" 
				autocomplete="off" value="<?=$data['married'];?>" class="not-required text fbin">
		</div><br>

		<label>Estado Civil: <span>*</span></label>
		<div class="content-input">
			<select id="dc-status-<?=$k?>" name="dc-status-<?=$k?>" class="required fbin">
            	<option value="">Seleccione...</option>
<?php
$arr_status = $link->status;
for($i = 0; $i < count($arr_status); $i++){
	$status = explode('|',$arr_status[$i]);
	if($status[0] === $data['status']) {
		echo '<option value="'.$status[0].'" selected>'.$status[1].'</option>';
    } else {
		echo '<option value="'.$status[0].'">'.$status[1].'</option>';
    }
}
?>
			</select>
		</div><br>

		<label>Tipo de Documento: <span>*</span></label>
		<div class="content-input">
			<select id="dc-type-doc-<?=$k?>" name="dc-type-doc-<?=$k?>" class="required fbin">
				<option value="">Seleccione...</option>
<?php
$arr_type_doc = $link->typeDoc;
for($i = 0; $i < count($arr_type_doc); $i++){
	$type_doc = explode('|',$arr_type_doc[$i]);
	if($type_doc[0] === $data['type_doc'])
		echo '<option value="'.$type_doc[0].'" selected>'.$type_doc[1].'</option>';
	else
		echo '<option value="'.$type_doc[0].'">'.$type_doc[1].'</option>';
}
?>
			</select>
		</div><br>
		
		<label>Documento de Identidad: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-doc-id-<?=$k?>" name="dc-doc-id-<?=$k?>" 
				autocomplete="off" value="<?=$data['doc_id'];?>" class="required dni fbin">
		</div><br>
		
		<label>Complemento: </label>
		<div class="content-input">
			<input type="text" id="dc-comp-<?=$k?>" name="dc-comp-<?=$k?>" 
				autocomplete="off" value="<?=$data['comp'];?>" class="not-required dni fbin" 
				style="width:60px;">
		</div><br>
		
		<label>Extensión: <span>*</span></label>
		<div class="content-input">
			<select id="dc-ext-<?=$k?>" name="dc-ext-<?=$k?>" class="required fbin">
            	<option value="">Seleccione...</option>
<?php
$rsDep = null;
if (($rsDep = $link->get_depto()) === FALSE) {
	$rsDep = null;
}
if ($rsDep->data_seek(0) === TRUE) {
	while($rowDep = $rsDep->fetch_array(MYSQLI_ASSOC)){
		if((boolean)$rowDep['tipo_ci'] === TRUE){
			if($rowDep['id_depto'] === $data['ext'])
				echo '<option value="'.$rowDep['id_depto'].'" selected>'.$rowDep['departamento'].'</option>';
			else
				echo '<option value="'.$rowDep['id_depto'].'">'.$rowDep['departamento'].'</option>';
		}
	}
}
?>
			</select>
		</div><br>
		
		<label>Fecha de Nacimiento: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-date-birth-<?=$k?>" name="dc-date-birth-<?=$k?>" 
				autocomplete="off" value="<?=$data['birth'];?>" class="required fbin dc-date-birth" 
				readonly style="cursor:pointer;">
		</div><br>
		
		<label>País: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-country-<?=$k?>" name="dc-country-<?=$k?>" 
				autocomplete="off" value="<?=$data['country'];?>" class="required text fbin">
		</div><br>
		
		<label>Lugar de Nacimiento: <span></span></label>
		<div class="content-input">
			<input type="text" id="dc-place-birth-<?=$k?>" name="dc-place-birth-<?=$k?>" 
				autocomplete="off" value="<?=$data['place_birth'];?>" class="not-required fbin">
		</div><br>
		
		<label>Lugar de Residencia: <span></span></label>
		<div class="content-input">
			<select id="dc-place-res-<?=$k?>" name="dc-place-res-<?=$k?>" class="not-required fbin">
				<option value="">Seleccione...</option>
<?php
if ($rsDep->data_seek(0) === TRUE) {
	while($rowDep = $rsDep->fetch_array(MYSQLI_ASSOC)){
		if((boolean)$rowDep['tipo_dp'] === TRUE){
			if($rowDep['id_depto'] === $data['place_res'])
				echo '<option value="'.$rowDep['id_depto'].'" selected>'.$rowDep['departamento'].'</option>';
			else
				echo '<option value="'.$rowDep['id_depto'].'">'.$rowDep['departamento'].'</option>';
		}
	}
}
?>
			</select>
		</div><br>
		
		<label>Localidad: <span></span></label>
		<div class="content-input">
			<input type="text" id="dc-locality-<?=$k?>" name="dc-locality-<?=$k?>" 
				autocomplete="off" value="<?=$data['locality'];?>" class="not-required text-2 fbin">
		</div><br>
	</div><!--
	--><div class="form-col">
		<label>Dirección: <span>*</span></label><br>
		<textarea id="dc-address-<?=$k?>" name="dc-address-<?=$k?>" 
			class="required fbin"><?=$data['address'];?></textarea><br>
		
		<label>Teléfono 1: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-phone1-<?=$k?>" name="dc-phone1-<?=$k?>" 
				autocomplete="off" value="<?=$data['phone_1'];?>" class="required phone fbin">
		</div><br>
		
		<label>Teléfono 2: </label>
		<div class="content-input">
			<input type="text" id="dc-phone2-<?=$k?>" name="dc-phone2-<?=$k?>" 
				autocomplete="off" value="<?=$data['phone_2'];?>" class="not-required phone fbin">
		</div><br>
		
		<label>Teléfono oficina: </label>
		<div class="content-input">
			<input type="text" id="dc-phone-office-<?=$k?>" name="dc-phone-office-<?=$k?>" 
				autocomplete="off" value="<?=$data['phone_office'];?>" class="not-required phone fbin">
		</div><br>
		
		<label>Email: </label>
		<div class="content-input">
			<input type="text" id="dc-email-<?=$k?>" name="dc-email-<?=$k?>" 
				autocomplete="off" value="<?=$data['email'];?>" class="not-required email fbin">
		</div><br>
		
		<label>Ocupación: <span>*</span></label>
		<div class="content-input">
			<select id="dc-occupation-<?=$k?>" name="dc-occupation-<?=$k?>" class="required fbin">
				<option value="">Seleccione...</option>
<?php
if (($rsOcc = $link->get_occupation($_SESSION['idEF'])) !== FALSE) {
	while($rowOcc = $rsOcc->fetch_array(MYSQLI_ASSOC)){
		if($rowOcc['id_ocupacion'] === $data['occupation']) {
			echo '<option value="'.base64_encode($rowOcc['id_ocupacion']).'" selected>'.$rowOcc['ocupacion'].'</option>';
		} else {
			echo '<option value="'.base64_encode($rowOcc['id_ocupacion']).'">'.$rowOcc['ocupacion'].'</option>';
		}
	}
}
?>
			</select>
		</div><br>
		
		<label style="width:auto;">Descripción Ocupación: <span>*</span></label><br>
		<textarea id="dc-desc-occ-<?=$k?>" name="dc-desc-occ-<?=$k?>" 
			class="required fbin"><?=$data['occ_desc'];?></textarea><br>
		
		<label>Género: <span>*</span></label>
		<div class="content-input">
			<select id="dc-gender-<?=$k?>" name="dc-gender-<?=$k?>" class="required fbin">
				<option value="">Seleccione...</option>
<?php
$arr_gender = $link->gender;
for($i = 0; $i < count($arr_gender); $i++){
	$gender = explode('|',$arr_gender[$i]);
	if($gender[0] === $data['gender'])
		echo '<option value="'.$gender[0].'" selected>'.$gender[1].'</option>';
	else
		echo '<option value="'.$gender[0].'">'.$gender[1].'</option>';
}
?>
			</select>
		</div><br>
		
		<label>Peso (kg): <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-weight-<?=$k?>" name="dc-weight-<?=$k?>" 
				autocomplete="off" value="<?=$data['weight'];?>" class="required wh fbin">
		</div><br>
		
		<label>Estatura (cm): <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-height-<?=$k?>" name="dc-height-<?=$k?>" 
				autocomplete="off" value="<?=$data['height'];?>" class="required wh fbin">

            <input type="hidden" id="dc-amount-<?=$k?>" name="dc-amount-<?=$k?>" 
            	value="<?=base64_encode($data['amount']);?>">
		</div><br>
<?php
	if ($bc === true) {
?>
		<label>Monto<br>Banca Comunal: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-amount-bc-<?=$k?>" name="dc-amount-bc-<?=$k?>" 
				autocomplete="off" value="<?=$data['amount_bc'];?>" class="required real fbin">
		</div><br>
<?php
	}
?>
	</div>
	<hr>
<?php
	}
?>	
    <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
	<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
	<input type="hidden" id="pr" name="pr" value="<?=base64_encode('DE|02');?>">
	<input type="hidden" id="dc-idc" name="dc-idc" value="<?=$_GET['idc'];?>" >
	<input type="hidden" id="dc-token" name="dc-token" value="<?=base64_encode('dc-OK');?>" >
    <input type="hidden" id="id-ef" name="id-ef" value="<?=$_SESSION['idEF'];?>" >
    <input type="hidden" id="dc-bc" name="dc-bc" value="<?=base64_encode((int)$bc);?>">
    <input type="hidden" id="dc-ncl" name="dc-ncl" value="<?=base64_encode($client);?>">
<?php
if($swCl === TRUE) {
	echo '<input type="hidden" id="dc-idCl" name="dc-idCl" value="'.$_GET['idCl'].'" >';
}
?>
	<input type="submit" id="dc-customer" name="dc-customer" value="<?=$title_btn;?>" class="btn-next" >
<?php
}
?>	
	<div class="loading">
		<img src="img/loading-01.gif" width="35" height="35" />
	</div>
</form>