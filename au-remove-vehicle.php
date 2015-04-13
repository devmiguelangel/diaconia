<div style="width:300px; font-size:80%; text-align:center;">
<?php
require('sibas-db.class.php');
if(isset($_GET['idc']) && isset($_GET['idVh']) && isset($_GET['ms']) && isset($_GET['page']) && isset($_GET['pr'])){
	if($_GET['pr'] === base64_encode('AU|01')){
		$link = new SibasDB();
		
		$idc = $link->real_escape_string(trim(base64_decode($_GET['idc'])));
		$idVh = $link->real_escape_string(trim(base64_decode($_GET['idVh'])));
		
		$sql = 'DELETE sad FROM s_au_cot_detalle as sad
				INNER JOIN 
			s_au_cot_cabecera as sac ON (sac.id_cotizacion = sad.id_cotizacion)
			WHERE sac.id_cotizacion = "'.$idc.'" AND sad.id_vehiculo = "'.$idVh.'"
			;';
		
		if($link->query($sql) === TRUE) {
			echo 'El vehiculo fue eliminado correctamente <br>Por favor espere...';
		} else {
			echo 'El Vehículo no puede ser eliminado';
		}
		
		echo '<meta http-equiv="refresh" content="3;url=au-quote.php?ms='.$_GET['ms'].'&page='.$_GET['page'].'&pr='.$_GET['pr'].'&idc='.base64_encode($idc).'">';
	}else {
		echo 'Usted no puede eliminar este Vehículo';
	}
}else {
	echo 'Error. El Vehículo no puede ser eliminado';
}
echo '<meta http-equiv="refresh" content="3">';
?>
</div>