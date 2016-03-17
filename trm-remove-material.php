<div style="width:300px; font-size:80%; text-align:center;">
<?php
require('sibas-db.class.php');
if(isset($_GET['idc']) && isset($_GET['idMt']) && isset($_GET['ms']) && isset($_GET['page']) && isset($_GET['pr'])){
	if($_GET['pr'] === base64_encode('TRM|01')){
		$link = new SibasDB();
		
		$idc = $link->real_escape_string(trim(base64_decode($_GET['idc'])));
		$idMt = $link->real_escape_string(trim(base64_decode($_GET['idMt'])));
		
		$sql = 'DELETE strd FROM s_trm_cot_detalle as strd
				INNER JOIN 
			s_trm_cot_cabecera as strc ON (strc.id_cotizacion = strd.id_cotizacion)
			WHERE strc.id_cotizacion = "'.$idc.'" AND strd.id_material = "'.$idMt.'"
			;';
		
		if($link->query($sql) === TRUE) {
			echo 'La Materia Asegurada fue eliminada correctamente <br>Por favor espere...';
		} else {
			echo 'La Materia Asegurada no puede ser eliminada';
		}
		
		echo '<meta http-equiv="refresh" content="3;url=trm-quote.php?ms='.$_GET['ms'].'&page='.$_GET['page'].'&pr='.$_GET['pr'].'&idc='.base64_encode($idc).'">';
	}else {
		echo 'Usted no puede eliminar este Material';
	}
}else {
	echo 'Error. El Material no puede ser eliminado';
}
echo '<meta http-equiv="refresh" content="3">';
?>
</div>