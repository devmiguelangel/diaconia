<?php
require_once('sibas-db.class.php');
require('classes/certificate-sibas.class.php');
session_start();

if (isset($_GET['ide']) && isset($_GET['pr'])) {
	$link = new SibasDB();
	$ide = $link->real_escape_string(trim(base64_decode($_GET['ide'])));
	$pr = $link->real_escape_string(trim(base64_decode($_GET['pr'])));
	
	$cp = false;
	$category = NULL;
	
	$_FAC = NULL;
	$table = $field = '';
	switch ($pr) {
		case 'DE':
			$table = 's_de_em_cabecera';
			break;
		case 'AU':
			$table = 's_au_em_cabecera';
			break;
		case 'TRD':
			$table = 's_trd_em_cabecera';
			break;
		case 'TRM':
			$table = 's_trm_em_cabecera';
			break;
	}
	
	$sql = 'select 
	    sr.id_emision as ide,
	    sr.no_emision,
	    sr.facultativo as f_facultativo,
	    sr.motivo_facultativo as f_motivo_facultativo,
		sr.certificado_provisional as cp
	from
	    '.$table.' as sr
			inner join 
	    s_entidad_financiera as sef ON (sef.id_ef = sr.id_ef)
	where
	    sr.id_emision = "'.$ide.'"
	        and sr.aprobado = false
	        and sr.rechazado = false
	        and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
	        and sef.activado = true
	;';
	//echo $sql;
	if (($rs = $link->query($sql, MYSQLI_STORE_RESULT))) {
		if ($rs->num_rows === 1) {
			$row = $rs->fetch_array(MYSQLI_ASSOC);
			$rs->free_result();
			$_FAC = (boolean)$row['f_facultativo'];
			$reason = $row['f_motivo_facultativo'];
			
			$cp = (boolean)$row['cp'];
		
			if ($cp === true) {
				$category = 'CP';
			} else {
				$category = 'CE';
			}
			
			if (isset($_GET['send']) && isset($_GET['approve'])) {
				$arrAU = array(0 => 0, 1 => 'R', 2 => '');
				
				$approve = (int)$link->real_escape_string(trim(base64_decode($_GET['approve'])));
				switch ($approve) {
					case 1: $arrAU[2] = 'Aprobada'; $field = 'aprobado'; break;
					case 0: $arrAU[2] = 'Rechazada'; $field = 'rechazado'; break;
				}
				
				$sqlAp = 'UPDATE '.$table.'	
					SET '.$field.' = TRUE, apr_usuario = "'.base64_decode($_SESSION['idUser']).'" ';
				if ($_FAC === FALSE && $approve === 1) {
					$sqlAp .= ', emitir = true, fecha_emision = curdate(), leido = 0 ';
				}
				$sqlAp .= 'WHERE id_emision = "'.$row['ide'].'" ;';
				
				if ($link->query($sqlAp) === TRUE) {
					$arrAU[0] = 1;
					$arrAU[2] = 'La Póliza N° '.$pr.'-'.$row['no_emision'].' fue '.$arrAU[2];
					
					if ($_FAC === TRUE && $approve === 1) {
						$arrAU[2] .= ', se envió la Solicitud de Aprobación a la Compañía';
					} else {
						
					}
					
					$type = '';
					if ($approve === 1) {
						$arr_host = array();
						if (($rowIm = $link->get_data_user($_SESSION['idUser'], $_SESSION['idEF'])) !== FALSE) {
							$arr_host['from'] = $rowIm['u_email'];
							$arr_host['fromName'] = $rowIm['u_nombre'];
						}
						
						if ($_FAC === TRUE) {
							$type = 'MAIL';
						} else {
							$type = 'ATCH';
						}

						$ce = new CertificateSibas($row['ide'], NULL, NULL, $pr, $type, $category, 1, 0, FALSE, $_FAC, $reason);
						$ce->host = $arr_host;
						
						if ($ce->Output() === TRUE) {
							$arrAU[2] .= ' y se envió el Correo Electrónico';
						} else {
							$arrAU[2] .= ' pero no se envió el Correo Electrónico';
						}
					}
				} else {
					$arrAU[2] = 'La Póliza N° '.$pr.'-'.$row['no_emision'].' no pudo ser '.$arrAU[2];
				}
				echo json_encode($arrAU);
			} else {
?>
<script type="text/javascript">
$(document).ready(function(e) {
    $("#f-papproval").validateForm({
		action: 'implant-approve-policy.php',
		method: 'GET',
		nameLoading: '.loading-02'
	});
	
	$('input').iCheck({
		checkboxClass: 'icheckbox_flat-red',
		radioClass: 'iradio_flat-red'
	});
});
</script>
<h4 class="h4">Póliza N° <?=$pr.'-'.$row['no_emision'];?></h4>
<form id="f-papproval" name="f-papproval" class="form-quote form-customer" style="width:500px; text-align:center;">
	<label class="check" style="width: auto;">
		<input type="radio" id="approve-1" name="approve" value="<?=base64_encode(1);?>" checked /> Aprobar
	</label>
	<label class="check" style="width: auto;">
		<input type="radio" id="approve-2" name="approve" value="<?=base64_encode(0);?>" /> Rechazar
	</label>
	<br />
	
	<textarea id="fca-reason" name="fca-reason" style="width:90%; height:200px; display: none;" class="not-required fbin"><?=$row['f_motivo_facultativo'];?></textarea>
    <input type="hidden" id="ide" name="ide" value="<?=base64_encode($row['ide']);?>" >
    <input type="hidden" id="pr" name="pr" value="<?=base64_encode($pr);?>" >
    <input type="hidden" id="send" name="send" value="<?=md5('SEND');?>" >
    <input type="submit" id="fca-save" name="fca-save" value="Enviar" class="btn-issue" >
    
    <div class="loading loading-02">
		<img src="img/loading-01.gif" width="35" height="35" />
	</div>
</form>
<?php
			}
		} else {
			echo 'La Póliza no Existe';
		}
	} else {
		echo 'La Póliza no puede ser emitida |';
	}
} else {
	echo 'La Póliza no puede ser aprobada';
}

?>