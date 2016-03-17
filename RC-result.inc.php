<script type="text/javascript">
$(document).ready(function(e) {
	$(".row").rcCxt({
		context: ''
	});
});
</script>

<?php
require('sibas-db.class.php');


$xls = FALSE;
if(isset($_GET['xls'])) {
	if($_GET['xls'] === md5('TRUE')) {
		$xls = TRUE;
	}
}

if($xls === true){

	header("Content-Type:   application/vnd.ms-excel; charset=iso-8859-1");
	header("Content-Disposition: attachment; filename=siniestros.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}

$s_ns = '';
$s_user = '';
//$s_name = '';
$s_dni = '';
$s_branch = '';

$bg = '';
$nSi = 0;


?>

<table class="result-list">
	<thead>
    	<tr>
        	<td>No. Siniestro</td>
            <td>Nombre Completo</td>
            <td>Tipo de Documento</td>
            <td>Nro de Documento</td>
            <td>Fecha de Registro</td>
            <td>Fecha de Siniestro</td>
            <td>Circunstancia</td>
            <td>Persona Denuncia</td>
            <td><?=htmlentities('Persona Teléfono', ENT_QUOTES, 'UTF-8');?></td>
            <td>Persona Email</td>
            <td><?=htmlentities('Tipo de Crédito', ENT_QUOTES, 'UTF-8');?></td>
            <td>Saldo a Capital</td>
            <td><?=htmlentities('Nº de Crédito', ENT_QUOTES, 'UTF-8');?></td>
            <td>Moneda</td>
            <td><?=htmlentities('Fecha de Elaboración', ENT_QUOTES, 'UTF-8');?></td>
            <td>Denunciado por</td>
            <td>Email</td>
            <td>Cargo</td>
            <td>Sucursal</td>
            <td>Agencia</td>
		</tr>
	</thead>
<?php
if(isset($_GET['ms']) && isset($_GET['page']) && $_GET['idef']){
	$link = new SibasDB();

	$idef = $link->real_escape_string(trim(base64_decode($_GET['idef'])));
	$s_ns = $link->real_escape_string(trim($_GET['frc-ns']));

	$s_user = '';
	if (isset($_GET['frc-agency'])) {
	$s_user = $link->real_escape_string(trim($_GET['frc-user']));
	}else{
		$_GET['frc-user']='';
	}

	$s_subsidiary = '';
	if (isset($_GET['frc-subsidiary'])) {
		$s_subsidiary = $link->real_escape_string(trim(base64_decode($_GET['frc-subsidiary'])));
		if (empty($s_subsidiary) === true) {
			$s_subsidiary = '%' . $s_subsidiary . '%';
		}
	}else{
		$_GET['frc-subsidiary']='';
	}


	$s_agency = '';
	if (isset($_GET['frc-agency'])) {
	$s_agency = $link->real_escape_string(trim(base64_decode($_GET['frc-agency'])));
	}else{
		$_GET['frc-agency']='';
	}

	$s_name = '';
	if (isset($_GET['frc-name'])) {
	$s_name = $link->real_escape_string(trim($_GET['frc-name']));
	}

	$s_dni = '';
	if (isset($_GET['frc-dni'])) {
	$s_dni = $link->real_escape_string(trim($_GET['frc-dni']));
	}

	$s_rep_person = '';
	if (isset($_GET['frc-rep-person'])) {
	$s_rep_person  = $link->real_escape_string(trim($_GET['frc-rep-person']));
	}

	$s_date_b = '';
	if (isset($_GET['frc-date-b'])) {
	$s_date_b = $link->real_escape_string(trim($_GET['frc-date-b']));
	}

	$s_date_e = '';
	if (isset($_GET['frc-date-e'])) {
	$s_date_e = $link->real_escape_string(trim($_GET['frc-date-e']));
	}

	if(empty($s_date_b) === TRUE) {
		$s_date_b = '2000-01-01';
	}
	if(empty($s_date_e) === TRUE) {
		$s_date_e = '2100-01-01';
	}


	$sqlSi = 'select
			ssi.id_siniestro as ids,
			count(ssd.id_siniestro) as noSi,
			ssi.no_siniestro as s_no_siniestro,
			date_format(ssi.fecha_registro, "%d/%m/%Y") as s_fecha_registro,
			ssi.id_cliente as idCl,
			ssi.tipo_documento as s_tipo_documento,
			ssi.ci as s_ci,
			sdp1.codigo as extension,
			concat(ssi.nombre,
								" ",
								ssi.paterno,
								" ",
								ssi.materno,
								" ",
								ssi.ap_casada) as s_nombre_completo,
			date_format(ssi.fecha_siniestro, "%d/%m/%Y") as s_fecha_siniestro,
			ssi.circunstancia as s_circunstancia,
			ssi.denuncia_persona as s_denuncia_persona,
			ssi.denuncia_telefono as s_denuncia_telefono,
			ssi.denuncia_email as s_denuncia_email,
			date_format(ssi.fecha_elaboracion, "%d/%m/%Y")  as s_fecha_elaboracion,
			su.id_usuario as s_usuario,
			su.nombre as s_usuario_nombre,
			su.email as s_usuario_email,
			su.cargo as s_cargo,
			sdp.departamento as s_sucursal,
		    sag.agencia as s_agencia
		from
			s_siniestro as ssi
				inner join
			s_siniestro_detalle as ssd ON (ssd.id_siniestro = ssi.id_siniestro)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = ssi.id_ef)
				inner join
			s_usuario as su ON (su.id_usuario = ssi.denunciado_por)
				inner join
			s_departamento as sdp ON (sdp.id_depto = ssi.sucursal)
				left join
			s_departamento as sdp1 ON (sdp1.id_depto = ssi.extension)
				left join
			s_agencia as sag ON (sag.id_agencia = ssi.agencia)
		where
			sef.id_ef = "'.$idef.'"
				and sef.activado = true
				and ssi.no_siniestro like "%'.$s_ns.'%"
				and (ssi.nombre like "%'.$s_name.'%"
				or ssi.paterno like "%'.$s_name.'%"
				or ssi.materno like "%'.$s_name.'%")
		';
		if(strlen($s_dni)>0)
		$sqlSi.= 'and ssi.ci like "%'.$s_dni.'%"';

		$sqlSi.= 'and ssi.denuncia_persona like "%'.$s_rep_person.'%"
		and ssi.fecha_registro between "'.$s_date_b.'" and "'.$s_date_e.'"
		and ssi.sucursal like "%' . $s_subsidiary . '%"
		and ssi.agencia like "%' . base64_decode($s_agency) . '%"
		and ssi.denunciado_por like "%' . base64_decode($s_user) . '%"
		group by ssi.id_siniestro
		order by ssi.id_siniestro desc
		;';
 //echo $sqlSi;
	if(($rsSi = $link->query($sqlSi,MYSQLI_STORE_RESULT))){
		if($rsSi->num_rows > 0){
			$swBG = FALSE;
			$unread = '';
?>
	<tbody>
<?php
			while($rowSi = $rsSi->fetch_array(MYSQLI_ASSOC)){
				$nSi = (int)$rowSi['noSi'];

				if($swBG === FALSE){
					$bg = 'background: #EEF9F8;';
				}elseif($swBG === TRUE){
					$bg = 'background: #D1EDEA;';
				}

				$rowSpan = FALSE;
				if($nSi > 1)
					$rowSpan = TRUE;

				if($xls === TRUE) {
					$rowSpan = '';
				}


				$sqlSd = 'select
						ssi.id_siniestro as ids,
						ssd.id_detalle as idsd,
						ssd.id_emision as ide,
						ssd.no_emision as d_no_emision,

						(case ssd.tipo_credito
							when "PEBBCC" then "Prestamo Externo BBCC"
							when "PIBBCC" then "Prestamo Interno BBCC"
							when "CO" then "Crédito Oportuno"
							when "CI" then "Crédito Individual"
						end) as d_tipo_credito,

						ssd.no_credito as d_no_credito,
						ssd.monto_desembolso as d_monto_desembolso,
						(case ssd.moneda
							when "BS" then "Bolivianos"
							when "USD" then "Dólares"
						end) as d_moneda
					from
						s_siniestro_detalle as ssd
							inner join
						s_siniestro as ssi ON (ssi.id_siniestro = ssd.id_siniestro)
					where
						ssi.id_siniestro = "'.$rowSi['ids'].'"
					order by ssd.id_detalle asc
					;
					;';

				if(($rsSd = $link->query($sqlSd,MYSQLI_STORE_RESULT))){
					if($rsSd->num_rows === $nSi){
						while($rowSd = $rsSd->fetch_array(MYSQLI_ASSOC)){
							if($rowSpan === TRUE){
								$rowSpan = 'rowspan="'.$nSi.'"';
							}elseif($rowSpan === FALSE){
								$rowSpan = '';
							}elseif($rowSpan === 'rowspan="'.$nSi.'"'){
								$rowSpan = 'style="display:none;"';
							}
?>
		<tr style=" <?=$bg;?> " class="row quote" rel="0"
			data-nc="<?=base64_encode($rowSi['ids']);?>" >

        	<td <?=$rowSpan;?>><?=$rowSi['s_no_siniestro'];?></td>
            <td><?=htmlentities($rowSi['s_nombre_completo'], ENT_QUOTES, 'UTF-8');?></td>
            <td ><?=$rowSi['s_tipo_documento'];?></td>
            <td ><?=$rowSi['s_ci'];?> <?=$rowSi['extension'];?></td>
            <td ><?=$rowSi['s_fecha_registro'];?></td>
            <td ><?=$rowSi['s_fecha_siniestro'];?></td>
            <td ><?=$rowSi['s_circunstancia'];?></td>
            <td><?=htmlentities($rowSi['s_denuncia_persona'], ENT_QUOTES, 'UTF-8');?></td>
            <td ><?=$rowSi['s_denuncia_telefono'];?></td>
            <td ><?=$rowSi['s_denuncia_email'];?></td>
            <td><?=htmlentities($rowSd['d_tipo_credito'], ENT_QUOTES, 'UTF-8');?></td>
            <td ><?=$rowSd['d_monto_desembolso'];?></td>
            <td ><?=$rowSd['d_no_credito'];?></td>
            <td><?=htmlentities($rowSd['d_moneda'], ENT_QUOTES, 'UTF-8');?></td>
            <td ><?=$rowSi['s_fecha_elaboracion'];?></td>
            <td><?=htmlentities($rowSi['s_usuario_nombre'], ENT_QUOTES, 'UTF-8');?></td>
            <td ><?=$rowSi['s_usuario_email'];?></td>
            <td><?=htmlentities($rowSi['s_cargo'], ENT_QUOTES, 'UTF-8');?></td>
            <td ><?=$rowSi['s_sucursal'];?></td>
            <td ><?=$rowSi['s_agencia'];?></td>
		</tr>
<?php
						}
					}
				}
				if($swBG === FALSE)
					$swBG = TRUE;
				elseif($swBG === TRUE)
					$swBG = FALSE;
			}
?>
	</tbody>

	<tfoot>
    <tr>
        	<td colspan="29" style="text-align:left;">
<?php
		if($xls === FALSE){

?>

<?php
			echo '<a href="RC-result.inc.php?ms=' . $_GET['ms']
					. '&page=' . $_GET['page'] . '&xls=' . md5('TRUE')
					. '&idef=' . $_GET['idef']
					. '&frc-ns=' . $_GET['frc-ns']
					. '&frc-user=' .  $_GET['frc-user']
					. '&frc-subsidiary=' . $_GET['frc-subsidiary']
					. '&frc-agency=' . $_GET['frc-agency']
					. '&frc-name=' .  $_GET['frc-name']
					. '&frc-dni=' .  $_GET['frc-dni']
					. '&frc-rep-person=' .  $_GET['frc-rep-person']
					. '&frc-date-b=' .  $_GET['frc-date-b']
					. '&frc-date-e=' .  $_GET['frc-date-e']
					. '" class="send-xls" target="_blank">Exportar a Formato Excel</a>';
		}
?>
			</td>
        </tr>
    </tfoot>


<?php
		}
	}
}
?>
</table>