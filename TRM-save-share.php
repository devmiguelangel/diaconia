<?php
require_once('sibas-db.class.php');
$link = new SibasDB();
$idc = $link->real_escape_string(trim(base64_decode($_GET['idc'])));
$cia = $link->real_escape_string(trim(base64_decode($_GET['cia'])));

$sqlCia = 'select 
		strc.id_cotizacion as idc,
		sef.id_ef as idef,
		scia.id_compania as idcia,
		scia.nombre as cia_nombre,
		scia.logo as cia_logo,
		strc.garantia as c_garantia,
		sfp.forma_pago as c_forma_pago,
		strc.plazo as c_plazo,
		strc.tipo_plazo as c_tipo_plazo,
		strc.certificado_provisional as cp
	from
		s_trm_cot_cabecera as strc
			inner join
		s_entidad_financiera as sef ON (sef.id_ef = strc.id_ef)
			inner join
		s_ef_compania as sec ON (sec.id_ef = sef.id_ef)
			inner join
		s_compania as scia ON (scia.id_compania = sec.id_compania)
			inner join
	    s_forma_pago as sfp ON (sfp.id_forma_pago = strc.id_forma_pago)
	where
		strc.id_cotizacion = "'.$idc.'"
			and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
			and sef.activado = true
			and sec.producto = "TRM"
			and scia.id_compania = "'.$cia.'"
			and scia.activado = true
	order by scia.id_compania asc
	;';

if(($rsCia = $link->query($sqlCia, MYSQLI_STORE_RESULT))){
	if($rsCia->num_rows === 1){
		$rowCia = $rsCia->fetch_array(MYSQLI_ASSOC);
		$rsCia->free();
		
		$arr_share = array();
		$year = $link->get_year_final($rowCia['c_plazo'], $rowCia['c_tipo_plazo']);
		$primaT = 0;
		$tasaT = 0;
		
		$date_begin = date('d-m-Y', strtotime(date('15-m-Y'). '+ 1 month'));
		$percent = number_format((100 / $year), 4, '.', ',');
		$date_payment = '';
		
		for($i = 0; $i < $year; $i++){
			$date_payment = date('d/m/Y', strtotime($date_begin. '+ '.$i.' year'));
			$arr_share[] = ($i + 1).'|'.$date_payment.'|'.$percent;
		}
		
		if(($rsMt = $link->get_tasa_trm($rowCia['idcia'], $rowCia['idef'], $rowCia['idc'], $year)) !== FALSE){
			while($rowVh = $rsMt->fetch_array(MYSQLI_ASSOC)){
				$primaT += $rowVh['v_prima'];
			}
			$rsMt->free();
		}
		
		$sqlSh = 'UPDATE s_trm_cot_cabecera 
			SET `cuota` = "'.$link->real_escape_string(json_encode($arr_share)).'", `prima_total` = '.$primaT.'
			WHERE id_cotizacion = "'.$rowCia['idc'].'" AND id_ef = "'.$rowCia['idef'].'" ;';
		
		if($link->query($sqlSh) === TRUE) {
			$url = 'trm-quote.php?ms='.$_GET['ms'].'&page='.$_GET['page'].'&pr='.base64_encode('TRM|05').'&idc='.base64_encode($rowCia['idc']).'&flag='.md5('i-new').'&cia='.base64_encode($rowCia['idcia']);
?>
<script type="text/javascript">
$(document).ready(function(e) {
	redirect('<?=$url;?>', 0);
});
</script>
<?php
		} else {
			echo 'Las Cuotas no se pudieron registrar';
		}
	}else {
		echo 'No se puede registrar las Cuotas |';
	}
}else {
	echo 'No se puede registrar las Cuotas';
}

?>