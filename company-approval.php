<?php
session_start();
//require_once('sibas-db.class.php');
require('classes/certificate-sibas.class.php');

date_default_timezone_set('America/La_Paz');

if(isset($_GET['pr']) && isset($_GET['ide'])){
	$link = new SibasDB();
	
	$ide = $link->real_escape_string(trim(base64_decode($_GET['ide'])));
	$pr = $link->real_escape_string(trim(base64_decode($_GET['pr'])));
	
	$cp = false;
	$category = NULL;
	
	$sql = '';
	$sqlUp = '';
	switch($pr){
		case 'DE':
			$sql = 'select 
				sde.id_emision as ide,
				sef.id_ef as idef,
				sde.no_emision,
				sde.prefijo,
				sde.id_cotizacion as idc,
				sdc.no_cotizacion,
				sde.motivo_facultativo,
				su.email as u_email,
			    su.nombre as u_nombre,
				sde.certificado_provisional as cp
			from
				s_de_em_cabecera as sde
					inner join
				s_de_cot_cabecera as sdc ON (sdc.id_cotizacion = sde.id_cotizacion)
					inner join
				s_entidad_financiera as sef ON (sef.id_ef = sde.id_ef)
					inner join
			    s_usuario as su ON (su.id_usuario = sde.id_usuario)
			where
				sde.id_emision = "'.$ide.'"
					and sde.emitir = false
					and sde.facultativo = true
					and sde.aprobado = false
					and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
			        and sef.activado = true
			;';
			
			$sqlUp = 'update s_de_em_cabecera 
			set 
				aprobado = true,
				hora_creacion = "' . date('H:i:s') . '" 
			where id_emision = "'.$ide.'" ;';
			break;
		case 'AU':
			$sql = 'select 
				sae.id_emision as ide,
				sef.id_ef as idef,
				sae.no_emision,
				sae.prefijo,
				sae.id_cotizacion as idc,
				sac.no_cotizacion,
				sae.motivo_facultativo,
				su.email as u_email,
			    su.nombre as u_nombre,
				sae.certificado_provisional as cp
			from
				s_au_em_cabecera as sae
					inner join
				s_au_cot_cabecera as sac ON (sac.id_cotizacion = sae.id_cotizacion)
					inner join
				s_entidad_financiera as sef ON (sef.id_ef = sae.id_ef)
					inner join
			    s_usuario as su ON (su.id_usuario = sae.id_usuario)
			where
				sae.id_emision = "'.$ide.'"
					and sae.emitir = false
					and sae.facultativo = true
					and sae.aprobado = false
					and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
			        and sef.activado = true
			;';
			$sqlUp = 'update s_au_em_cabecera set aprobado = true where id_emision = "'.$ide.'" ;';
			break;
		case 'TRD':
			$sql = 'select 
				stre.id_emision as ide,
				sef.id_ef as idef,
				stre.no_emision,
				stre.prefijo,
				stre.id_cotizacion as idc,
				strc.no_cotizacion,
				stre.motivo_facultativo,
				su.email as u_email,
			    su.nombre as u_nombre,
				stre.certificado_provisional as cp
			from
				s_trd_em_cabecera as stre
					inner join
				s_trd_cot_cabecera as strc ON (strc.id_cotizacion = stre.id_cotizacion)
					inner join
				s_entidad_financiera as sef ON (sef.id_ef = stre.id_ef)
					inner join
			    s_usuario as su ON (su.id_usuario = stre.id_usuario)
			where
				stre.id_emision = "'.$ide.'"
					and stre.emitir = false
					and stre.facultativo = true
					and stre.aprobado = false
					and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
			        and sef.activado = true
			;';
			$sqlUp = 'update s_trd_em_cabecera set aprobado = true where id_emision = "'.$ide.'" ;';
			break;
		case 'TRM':
			$sql = 'select 
			    stre.id_emision as ide,
			    sef.id_ef as idef,
			    stre.no_emision,
			    stre.prefijo,
			    stre.id_cotizacion as idc,
			    strc.no_cotizacion,
			    stre.motivo_facultativo,
			    su.email as u_email,
			    su.nombre as u_nombre,
				stre.certificado_provisional as cp
			from
			    s_trm_em_cabecera as stre
			        inner join
			    s_trm_cot_cabecera as strc ON (strc.id_cotizacion = stre.id_cotizacion)
			        inner join
			    s_entidad_financiera as sef ON (sef.id_ef = stre.id_ef)
			        inner join
			    s_usuario as su ON (su.id_usuario = stre.id_usuario)
			where
			    stre.id_emision = "'.$ide.'"
			        and stre.emitir = false
			        and stre.facultativo = true
			        and stre.aprobado = false
			        and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
			        and sef.activado = true
			;';
			$sqlUp = 'update s_trm_em_cabecera set aprobado = true where id_emision = "'.$ide.'" ;';
			break;
	}
	
	if(($rs = $link->query($sql,MYSQLI_STORE_RESULT))){
		if($rs->num_rows === 1){
			$row = $rs->fetch_array(MYSQLI_ASSOC);
			$rs->free();
			
			$cp = (boolean)$row['cp'];
		
			if ($cp === true) {
				$category = 'CP';
			} else {
				$category = 'CE';
			}
			
			if(isset($_GET['send']) && isset($_GET['fca-reason'])){
				$arrDE = array(0 => 0, 1 => 'R', 2 => '');
				
				$reason = $link->real_escape_string(trim($_GET['fca-reason']));
				
				if ($link->query($sqlUp) === TRUE && $link->affected_rows > 0) {
					$ce = new CertificateSibas($ide, NULL, NULL, $pr, 'MAIL', $category, 1, 0, FALSE, TRUE, $reason);
					if ($ce->Output() === TRUE) {
						$arrDE[0] = 1;
						$arrDE[1] = 'index.php';
						$arrDE[2] = 'La Solicitud fue enviada correctamente';
					} else {
						$arrDE[2] = 'No se pudo enviar la Solicitud';
					}
				} else {
					$arrDE[2] = 'La Solicitud no puede ser enviada';
				}
				echo json_encode($arrDE);
			}else{
?>
<script type="text/javascript">
$(document).ready(function(e) {
    $("#f-capproval").validateForm({
		action: 'company-approval.php',
		method: 'GET',
		nameLoading: '.loading-02'
	});
});
</script>
<h4 class="h4">Póliza N° <?=$pr.'-'.$row['no_emision'];?></h4>
<form id="f-capproval" name="f-capproval" class="form-quote" style="width:500px; text-align:center;">
	<textarea id="fca-reason" name="fca-reason" style="width:90%; height:200px;" class="required fbin"><?=$row['motivo_facultativo'];?></textarea>
    <input type="hidden" id="ide" name="ide" value="<?=base64_encode($row['ide']);?>" >
    <input type="hidden" id="pr" name="pr" value="<?=base64_encode($pr);?>" >
    <input type="hidden" id="send" name="send" value="<?=base64_encode('SEND');?>" >
    <input type="submit" id="fca-save" name="fca-save" value="Enviar" class="btn-issue" >
    
    <div class="loading loading-02">
		<img src="img/loading-01.gif" width="35" height="35" />
	</div>
</form>
<?php
			}
		}
	}
}else{
	echo 'No se puede enviar la Solicitud.';
}
?>