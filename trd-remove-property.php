<div style="width:300px; font-size:80%; text-align:center;">
<?php
require('sibas-db.class.php');
if(isset($_GET['idc']) && isset($_GET['idPr']) && isset($_GET['ms']) && isset($_GET['page']) && isset($_GET['pr'])){
	if($_GET['pr'] === base64_encode('TRD|01')){
		$link = new SibasDB();
		
		$idc = $link->real_escape_string(trim(base64_decode($_GET['idc'])));
		$idPr = $link->real_escape_string(trim(base64_decode($_GET['idPr'])));
		
		$sql = 'DELETE strd FROM s_trd_cot_detalle as strd
				INNER JOIN 
			s_trd_cot_cabecera as strc ON (strc.id_cotizacion = strd.id_cotizacion)
			WHERE strc.id_cotizacion = "'.$idc.'" AND strd.id_inmueble = "'.$idPr.'"
			;';
		
		if($link->query($sql) === TRUE) {
			echo 'El Inmueble fue eliminado correctamente <br>Por favor espere...';
		} else {
			echo 'El Inmueble no puede ser eliminado';
		}
		
		echo '<meta http-equiv="refresh" content="3;url=trd-quote.php?ms='.$_GET['ms'].'&page='.$_GET['page'].'&pr='.$_GET['pr'].'&idc='.base64_encode($idc).'">';
	}else {
		echo 'Usted no puede eliminar este Inmueble';
	}
}else {
	echo 'Error. El Inmueble no puede ser eliminado';
}
echo '<meta http-equiv="refresh" content="3">';
?>
</div>