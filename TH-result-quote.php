<?php
require_once('sibas-db.class.php');
$link = new SibasDB();
$idc = $link->real_escape_string(trim(base64_decode($_GET['idc'])));

$sqlCia = 'select 
	sthc.id_cotizacion as idc,
	sef.id_ef as idef,
	scia.id_compania as idcia,
	sec.id_ef_cia as idec,
	sthc.id_tarjeta as th_tarjeta,
	scia.nombre as cia_nombre,
	scia.logo as cia_logo,
	sthc.certificado_provisional as cp
from
	s_th_cot_cabecera as sthc
		inner join
	s_entidad_financiera as sef ON (sef.id_ef = sthc.id_ef)
		inner join
	s_ef_compania as sec ON (sec.id_ef = sef.id_ef)
		inner join
	s_compania as scia ON (scia.id_compania = sec.id_compania)
where
	sthc.id_cotizacion = "'.$idc.'"
		and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
		and sef.activado = true
		and sec.producto = "TH"
		and scia.activado = true
order by scia.id_compania asc
;';

if(($rsCia = $link->query($sqlCia, MYSQLI_STORE_RESULT))){
	if($rsCia->num_rows > 0){
		$cp = $WR = false;
?>
<h3>Seguro de Automotores - Tenemos las siguientes ofertas</h3>
<h4>Escoge el plan que mas te convenga</h4>
<?php
		while($rowCia = $rsCia->fetch_array(MYSQLI_ASSOC)){
			$sql = 'select 
			    sthp.id_prima, sthp.prima
			from
			    s_th_prima as sthp
			        inner join
			    s_ef_compania as sec ON (sec.id_ef_cia = sthp.id_ef_cia)
			        inner join
			    s_entidad_financiera as sef ON (sef.id_ef = sec.id_ef)
			        inner join
			    s_compania as scia ON (scia.id_compania = sec.id_compania)
			where
			    sec.id_ef_cia = "'.$rowCia['idec'].'"
			        and sec.activado = true
					and sef.id_ef = "'.$rowCia['idef'].'"
					and sef.activado = true
			;';
			
			if (($rs = $link->query($sql, MYSQLI_STORE_RESULT)) !== false) {
				if ($rs->num_rows === 1) {
					$row = $rs->fetch_array(MYSQLI_ASSOC);
					
					$cp = (boolean)$rowCia['cp'];
					$primaT = 0;
					$tasaT = 0;
					
					$card = json_decode($row['prima'], true);
					if (array_key_exists(base64_encode($rowCia['th_tarjeta']), $card) === true) {
						foreach ($card as $key => $value) {
							if (base64_decode($key) === $rowCia['th_tarjeta']) {
								$primaT = $value;
								break;
							}
						}
					}
?>
<section style="text-align:center;">
	<div class="result-quote">
		<div class="rq-img">
			<img src="images/<?=$rowCia['cia_logo'];?>" alt="<?=$rowCia['cia_nombre'];?>" title="<?=$rowCia['cia_nombre'];?>">
		</div>
		<span class="rq-tasa">
			Prima: <br>
			<span class="value">USD <?=number_format($primaT,2,'.',',');?> </span><br>
			Mensual
		</span>
		<a href="certificate-detail.php?idc=<?=base64_encode($idc);?>&category=<?=base64_encode('CE');?>&cia=<?=base64_encode($rowCia['idcia']);?>&pr=<?=base64_encode('TH');?>&type=<?=base64_encode('PRINT');?>" class="fancybox fancybox.ajax btn-see-slip">Ver Certificado</a>
<?php
if($token === true){
	/*
	 * <a href="au-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=base64_encode('AU|04');?>&idc=<?=$_GET['idc'];?>&flag=<?=md5('i-new');?>&cia=<?=base64_encode($rowCia['idcia']);?>" class="btn-send">Emitir</a>
	 */
}
?>
	</div>
</section>
<?php
				}
			}
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