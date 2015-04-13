<?php
require_once('sibas-db.class.php');
$link = new SibasDB();
$idc = $link->real_escape_string(trim(base64_decode($_GET['idc'])));

$sqlCia = 'select 
		sac.id_cotizacion as idc,
		sef.id_ef as idef,
		scia.id_compania as idcia,
		scia.nombre as cia_nombre,
		scia.logo as cia_logo,
		sac.garantia as c_garantia,
		sfp.forma_pago as c_forma_pago,
		sac.plazo as c_plazo,
		sac.tipo_plazo as c_tipo_plazo,
		sac.certificado_provisional as cp
	from
		s_au_cot_cabecera as sac
			inner join
		s_entidad_financiera as sef ON (sef.id_ef = sac.id_ef)
			inner join
		s_ef_compania as sec ON (sec.id_ef = sef.id_ef)
			inner join
		s_compania as scia ON (scia.id_compania = sec.id_compania)
			inner join
	    s_forma_pago as sfp ON (sfp.id_forma_pago = sac.id_forma_pago)
	where
		sac.id_cotizacion = "'.$idc.'"
			and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
			and sef.activado = true
			and sec.producto = "AU"
			and scia.activado = true
	order by scia.id_compania asc
	;';

if(($rsCia = $link->query($sqlCia, MYSQLI_STORE_RESULT))){
	if($rsCia->num_rows > 0){
		$year = 0;
		$cp = $WR = FALSE;
		while($rowCia = $rsCia->fetch_array(MYSQLI_ASSOC)){
			$cp = (boolean)$rowCia['cp'];
			$year = $link->get_year_final($rowCia['c_plazo'], $rowCia['c_tipo_plazo']);
			$primaT = 0;
			$tasaT = 0;
			
			if(($rsVh = $link->get_tasa_au($rowCia['idcia'], $rowCia['idef'], $rowCia['idc'], $year)) !== FALSE){
				while($rowVh = $rsVh->fetch_array(MYSQLI_ASSOC)){
					$primaT += $rowVh['v_prima'];
				}
				$rsVh->free();
			}
?>
<h3>Seguro de Automotores - Tenemos las siguientes ofertas</h3>
<h4>Escoge el plan que mas te convenga</h4>
<section style="text-align:center;">
	<div class="result-quote">
		<div class="rq-img">
			<img src="images/<?=$rowCia['cia_logo'];?>" alt="<?=$rowCia['cia_nombre'];?>" title="<?=$rowCia['cia_nombre'];?>">
		</div>
		<span class="rq-tasa">
			Prima: <br>
			<span class="value">USD <?=number_format($primaT,2,'.',',');?> </span><br>
			<?=$rowCia['c_forma_pago'];?>
		</span>
		<a href="certificate-detail.php?idc=<?=base64_encode($idc);?>&cia=<?=base64_encode($rowCia['idcia']);?>&pr=<?=base64_encode('AU');?>&type=<?=base64_encode('PRINT');?>" class="fancybox fancybox.ajax btn-see-slip">Ver Slip de Cotización</a>
<?php
if($token === TRUE && (boolean)$rowCia['c_garantia'] === TRUE){
?>
		<a href="au-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=base64_encode('AU|04');?>&idc=<?=$_GET['idc'];?>&flag=<?=md5('i-new');?>&cia=<?=base64_encode($rowCia['idcia']);?>" class="btn-send">Emitir</a>
<?php
}
?>
	</div>
</section>
<?php
		}
		$rsCia->free();
	}else {
		echo 'No se puede obtener las Compañias |';
	}
}else {
	echo 'No se puede obtener las Compañias';
}
?>
<br>
<br>

<div class="contact-phone">
	Todas las ofertas tienen las mismas condiciones, elige la compañía de tu elección<br><br>
	* Para cualquier duda o consulta, contacta a la Línea Gratuita de Sudamericana S.R.L. 800-10-3070
</div>