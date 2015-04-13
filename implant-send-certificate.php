<?php
if (isset($_POST['ide']) && isset($_POST['pr'])) {
	session_start();
	require('classes/certificate-sibas.class.php');
	
	$link = new SibasDB();
	$ide = $link->real_escape_string(trim(base64_decode($_POST['ide'])));
	$pr = $link->real_escape_string(trim(base64_decode($_POST['pr'])));
	
	$category = $sql = '';
	switch ($pr) {
		case 'DE':
			$sql = 'select certificado_provisional as cp from s_de_em_cabecera where id_emision = "'.$ide.'" ;';
			break;
		case 'AU':
			$sql = 'select certificado_provisional as cp from s_au_em_cabecera where id_emision = "'.$ide.'" ;';
			break;
		case 'TRD':
			$sql = 'select certificado_provisional as cp from s_trd_em_cabecera where id_emision = "'.$ide.'" ;';
			break;
		case 'TRM':
			$sql = 'select certificado_provisional as cp from s_trm_em_cabecera where id_emision = "'.$ide.'" ;';
			break;
	}
	
	if (($rsIm = $link->get_user_implant($_SESSION['idUser'], $_SESSION['idEF'], $pr)) !== FALSE 
			&& ($rs = $link->query($sql, MYSQLI_STORE_RESULT))) {
			
		$arr_host = array();
		$arr_address = array();
		
		while ($rowIm = $rsIm->fetch_array(MYSQLI_ASSOC)) {
			$arr_host['from'] = $rowIm['u_email'];
			$arr_host['fromName'] = $rowIm['u_nombre'];
			
			$arr_address[] = array('address' => $rowIm['i_email'], 'name' => $rowIm['i_nombre']);
		}
		
		if($rs->num_rows === 1){
			$row = $rs->fetch_array(MYSQLI_ASSOC);
			$rs->free();
			
			$cp = (boolean)$row['cp'];
		
			if ($cp === true) {
				$category = 'CP';
			} else {
				$category = 'CE';
			}
		}
		
		$ce = new CertificateSibas($ide, NULL, NULL, $pr, 'MAIL', $category, 1, 0, TRUE);
		$ce->host = $arr_host;
		$ce->address = $arr_address;
		
		if ($ce->Output() === true) {
			echo 'La Solicitud de Aprobación fué enviada con éxito.<br>Por favor espere...';
		} else {
			echo 'La Solicitud de Aprobación no pudo ser enviada, intentelo mas tarde.';
		}
	} else {
		echo 'No existen Intermediarios.';
	}
}
?>