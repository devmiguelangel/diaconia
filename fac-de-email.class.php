<?php
require_once('sibas-db.class.php');
require('PHPMailer/class.phpmailer.php');

class FACEmailDE{
	private $sql, $rs, $row, $sqlc, $rsc, $rowc, 
		$ide, $idd, $bc, $flag, $body, $emailCC, $bg = '', $color;
	public $cx, $err;
	
	public function FACEmailDE($flag = FALSE){
		$this->cx = new SibasDB();
		$this->flag = $flag;
	}
	
	public function send_mail_fac($ide, $emailCC = '', $color, $bc = false, $idd = ''){
		$this->ide = $this->cx->real_escape_string($ide);
		$this->emailCC = explode(',', $emailCC);
		$this->color = $color;
		$this->bc = $bc;
		$this->idd = $idd;

		switch ($this->color) {
			case 1:
				$this->bg = '#006697';
				break;
			case 2:
				$this->bg = '#009646';
				break;
		}
		
		$this->sql = "select 
			sde.id_emision as ide,
			sde.prefijo,
			sde.id_ef as idef,
			sde.id_compania as idcia,
			sde.no_emision,
			su.id_usuario as u_id,
			su.usuario,
			su.email,
			su.nombre,
			sdd.id_detalle,
			concat(sc.nombre,
					' ',
					sc.paterno,
					' ',
					sc.materno) as cl_nombre,
			 if(sdf.aprobado is null,
				if(sdp.id_pendiente is not null,
					case sdp.respuesta
						when 1 then 'S|Subsanado/Pendiente'
						when 0 then 'O|Observado'
					end,
					'P|Pendiente'),
				case sdf.aprobado
					when 'SI' then 'A|Aprobado'
					when 'NO' then 'R|Rechazado'
				end) as estado,
			case
				when sds.codigo = 'ED' then 'E'
		        when sds.codigo != 'ED' then 'NE'
				else null
			end as observacion,
			sdf.aprobado as f_aprobado,
			sdf.tasa_recargo as f_tasa_recargo,
			sdf.porcentaje_recargo as f_porcentaje_recargo,
			sdf.tasa_actual as f_tasa_actual,
			sdf.tasa_final as f_tasa_final,
			sdf.observacion as f_observacion,
			suf.nombre as f_nombre,
			suf.usuario as f_usuario,
			suf.email as f_email,
			sds.id_estado,
			sds.codigo,
			sds.estado as estado_pendiente,
			sdp.observacion as p_observacion,
			sdp.respuesta as p_respuesta,
			sdp.obs_respuesta as p_obs_respuesta,
			scm.tipo as cm_tipo,
			scm.titulo as cm_titulo,
			scr.otros as cm_observacion,
			sup.nombre as p_nombre,
			sup.usuario as p_usuario,
			sup.email as p_email,
			sef.nombre as ef_nombre,
		    sef.logo as ef_logo,
		    sdd.detalle_f,
		    sdd.detalle_p
		from
			s_de_em_cabecera as sde
				inner join
			s_de_em_detalle as sdd ON (sdd.id_emision = sde.id_emision)
				inner join
			s_cliente as sc ON (sc.id_cliente = sdd.id_cliente)
				inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sde.id_ef)
				left join
			s_de_facultativo as sdf ON (sdf.id_emision = sde.id_emision)
				left join
			s_de_pendiente as sdp ON (sdp.id_emision = sde.id_emision)
				left join
			s_estado as sds ON (sds.id_estado = sdp.id_estado)
				left join
			s_cm_respuesta as scr ON (scr.id_emision = sde.id_emision and scr.id_cliente = sc.id_cliente)
				left join
			s_cm_certificado as scm ON (scm.id_cm = scr.id_cm)
				inner join
			s_usuario as su ON (su.id_usuario = sde.id_usuario)
				left join
		    s_usuario as sup ON (sup.id_usuario = sdp.pro_usuario)
				left join
		    s_usuario as suf ON (suf.id_usuario = sdf.pro_usuario)
		where
			sde.id_emision = '".$this->ide."'";
		if ($this->bc === true) {
			$this->sql .= " and sdd.id_detalle = '" . $this->idd . "' ";
		}
		$this->sql ."order by sc.id_cliente asc
		;";
		
		if(($this->rs = $this->cx->query($this->sql,MYSQLI_STORE_RESULT))){
			if($this->rs->num_rows > 0){
				$arrCl = array();
				$k = 0;
				while($this->row = $this->rs->fetch_array(MYSQLI_ASSOC)){
					$k += 1;
					$arrCl[$k] = $this->row['cl_nombre'];
				}
				
				if($this->rs->data_seek(0) === TRUE){
					$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
					
					if ($this->bc === true) {
						$detalle_f = json_decode($this->row['detalle_f'], true);
						if (is_array($detalle_f) === true) {
							$this->row['f_email'] = $detalle_f['email'];

							switch ($detalle_f['aprobado']) {
							case 'SI':
								$this->row['estado'] = 'A|Aprobado';
								$this->row['f_aprobado'] = $detalle_f['aprobado'];
								$this->row['f_tasa_recargo'] = $detalle_f['tasa_recargo'];
								$this->row['f_porcentaje_recargo'] = $detalle_f['porcentaje_recargo'];
								$this->row['f_tasa_final'] = $detalle_f['tasa_final'];
								$this->row['f_observacion'] = $detalle_f['observacion'];
								break;
							case 'NO':
								$this->row['estado'] = 'R|Rechazado';
								$this->row['f_aprobado'] = $detalle_f['aprobado'];
								$this->row['f_observacion'] = $detalle_f['observacion'];
								break;
							}
						}
					}

					$state = explode('|', $this->row['estado']);
					$arr_user = array();
					$subject = $this->row['ef_nombre'] . ' ';

					
					if($this->flag === FALSE){
						$this->body = $this->html_body($arrCl);
						
						if($state[0] === 'A' || $state[0] === 'R'){
							$arr_user[0] = $this->row['f_email'];
							$arr_user[1] = $this->row['f_nombre'];
							$subject .= $state[1];
						}else{
							$arr_user[0] = $this->row['p_email'];
							$arr_user[1] = $this->row['p_nombre'];
							$subject .= $this->row['estado_pendiente'];
						}
						$arr_user[2] = $this->row['email'];
						$arr_user[3] = $this->row['nombre'];
						
						$subject .= ': Respuesta de la aseguradora';
					}else{
						$this->body = $this->html_body_response($arrCl);
						
						$arr_user[0] = $this->row['email'];
						$arr_user[1] = $this->row['nombre'];
						if($state[0] === 'A' || $state[0] === 'R'){
							$arr_user[2] = $this->row['f_email'];
							$arr_user[3] = $this->row['f_nombre'];
						}else{
							$arr_user[2] = $this->row['p_email'];
							$arr_user[3] = $this->row['p_nombre'];
						}
						
						$subject .= ': Respuesta del Oficial de Credito';
					}
					
					$mail = new PHPMailer();
					$mail->Host = 'emontano@sudseguros.com';//$arr_user[0];
					$mail->From = 'emontano@sudseguros.com';//$arr_user[0];
					$mail->FromName = $this->row['ef_nombre'];
					$mail->Subject = $subject . ' a Caso Facultativo No. ' 
						. $this->row['prefijo'] . '-' . $this->row['no_emision'] . ' ' . $arrCl[1];
					
					$_IMP = $this->cx->verify_implant(base64_encode($this->row['idef']), 'DE');
					if ($_IMP === TRUE) {
						if (($rsUi = $this->cx->get_user_implant(
							base64_encode($this->row['u_id']), 
							base64_encode($this->row['idef']), 'DE')) !== FALSE) {
							while ($rowUi = $rsUi->fetch_array(MYSQLI_ASSOC)) {
								$mail->addAddress($rowUi['i_email'], $rowUi['i_nombre']);
							}
						}
					}
					
					$mail->addAddress($arr_user[2], $arr_user[3]);
					$mail->addCC($arr_user[0], $arr_user[1]);
					
					$this->sqlc = 'SELECT sc.correo, sc.nombre 
						FROM s_correo as sc
							INNER JOIN s_entidad_financiera as sef ON (sef.id_ef = sc.id_ef)
						WHERE (sc.producto = "DE" or sc.producto = "FDE") 
								AND sef.id_ef = "' . $this->row['idef'] . '" 
								AND sef.activado = true ;';
					if(($this->rsc = $this->cx->query($this->sqlc, MYSQLI_STORE_RESULT))){
						if($this->rsc->num_rows > 0){
							while($this->rowc = $this->rsc->fetch_array(MYSQLI_ASSOC)){
								$mail->addCC($this->rowc['correo'], $this->rowc['nombre']);
							}
						}
					}
					
					if(count($this->emailCC) > 0){
						for($i = 0; $i < count($this->emailCC); $i++){
							if(trim($this->emailCC[$i],',') != ''){
								$mail->addCC($this->emailCC[$i], $this->emailCC[$i]);
							}
						}
					}
					
					$mail->Body = $this->body;
					$mail->AltBody = $this->body;
					
					if ($mail->send()) {
						return TRUE;
					} else {
						return FALSE;
					}
				}
			}else{
				
			}
		}
	}
	
	private function html_body($arrCl){
		$state = explode('|', $this->row['estado']);
		ob_start();
?>
<div style="width:600px; border:1px solid #CCCCCC; color:#000000; 
	font-weight:bold; font-size:12px; text-align:left;">
	<div style="padding:5px 10px; background:<?=$this->bg;?>; color:#FFFFFF;">N&Uacute;MERO DE EMISI&Oacute;N</div>
    <div style="padding:5px 10px;"><?=$this->row['prefijo'] . '-' . $this->row['no_emision'];?></div>
<?php
		if($state[0] === 'A' || $state[0] === 'R'){
?>
	<div style="padding:5px 10px; background:<?=$this->bg;?>; color:#FFFFFF;">APROBADO</div>
    <div style="padding:5px 10px;"><?=$this->row['f_aprobado'];?></div>
<?php
			if($state[0] === 'A'){
?>
    <div style="padding:5px 10px; background:<?=$this->bg;?>; color:#FFFFFF;">TASA DE RECARGO</div>
    <div style="padding:5px 10px;">
    	<?=$this->row['f_tasa_recargo'] . ' - ' . $this->row['f_porcentaje_recargo'] . ' %';?>
    </div>
    <div style="padding:5px 10px; background:<?=$this->bg;?>; color:#FFFFFF;">TASA FINAL</div>
    <div style="padding:5px 10px;"><?=$this->row['f_tasa_final'];?></div>
<?php
			}
?>
	<div style="padding:5px 10px; background:<?=$this->bg;?>; color:#FFFFFF;">OBSERVACIONES: </div>
	<div style="padding:5px 10px;"><?=$this->row['f_observacion'];?></div>
<?php
		}else{
			$obsPe = '';
			if ($this->row['codigo'] === 'EM') {
				if ($this->row['cm_tipo'] === 'CUESTIONARIO') {
					if (!isset($_SESSION['idEF'])) {
						session_start();
					}
					
					$self = $_SERVER['HTTP_HOST'];
					$url = 'http://'.$self.'/';
					
					$host_ws = '';
					if (($host_ws = $this->cx->getNameHostEF($_SESSION['idEF'])) !== false) {
						$host_ws .= '.';
					}
					
					if (strpos($self, 'localhost') !== false 
						|| filter_var($self, FILTER_VALIDATE_IP) !== false) {
						$url .= trim($host_ws, '.') . '/';
					} elseif (strpos($self, $host_ws . 'abrenet.com') === false) {
						$url .= trim($host_ws, '.') . '/';
					} else {
						$url .= '';
					}

					$idd_link = '';
					if ($this->bc === true) {
						$idd_link = '&idd=' . base64_encode($this->row['id_detalle']);
					}
					
					$obsPe = '<a href="' . $url . 'medical-certificate.php?ide='
						. base64_encode($this->row['ide'])
						. $idd_link
						. '&cia=' . base64_encode($this->row['idcia'])
						. '&ef=' . base64_encode($this->row['idef'])
						. '&type=' . base64_encode('PDF')
						. '" target="_blank">' . $this->row['cm_titulo'] . '</a>';
				} elseif ($this->row['cm_tipo'] === 'EDITOR') {
					$obsPe = $this->row['cm_observacion'];
				}
			} else {
				$obsPe = $this->row['p_observacion'];
			}
?>
	<div style="padding:5px 10px; background:<?=$this->bg;?>; color:#FFFFFF;">ESTADO DE LA SOLICITUD</div>
    <div style="padding:5px 10px;"><?=$state[1];?></div>
    <div style="padding:5px 10px; background:<?=$this->bg;?>; color:#FFFFFF;">MOTIVO PENDIENTE</div>
    <div style="padding:5px 10px;"><?=$this->row['estado_pendiente'];?></div>
    <div style="padding:5px 10px; background:<?=$this->bg;?>; color:#FFFFFF;">DETALLES: </div>
    <div style="padding:5px 10px;"><?=$obsPe;?></div>
<?php
		}
?>
    <div style="padding:5px 10px; background:<?=$this->bg;?>; color:#FFFFFF;">DEUDOR: </div>
    <div style="padding:5px 10px;"><?=$arrCl[1];?></div>
<?php
		if(count($arrCl) === 2){
?>
	<div style="padding:5px 10px; background:<?=$this->bg;?>; color:#FFFFFF;">CODEUDOR: </div>
    <div style="padding:5px 10px;"><?=$arrCl[2];?></div>
<?php
		}
?>
</div>
<?php
		$html = ob_get_clean();
		return $html;
	}
	
	private function html_body_response($arrCl){
		$state = explode('|', $this->row['estado']);
		ob_start();
?>
<div style="width:600px; border:1px solid #CCCCCC; color:#000000; 
	font-weight:bold; font-size:12px; text-align:left;">
	<div style="padding:5px 10px; background:<?=$this->bg;?>; color:#FFFFFF;">N&Uacute;MERO DE EMISI&Oacute;N</div>
    <div style="padding:5px 10px;"><?=$this->row['prefijo'] . '-' . $this->row['no_emision'];?></div>

	<div style="padding:5px 10px; background:<?=$this->bg;?>; color:#FFFFFF;">ESTADO DE LA SOLICITUD</div>
    <div style="padding:5px 10px;"><?=$state[1];?></div>
    <div style="padding:5px 10px; background:<?=$this->bg;?>; color:#FFFFFF;">MOTIVO PENDIENTE</div>
    <div style="padding:5px 10px;"><?=$this->row['estado_pendiente'];?></div>
    <div style="padding:5px 10px; background:<?=$this->bg;?>; color:#FFFFFF;">DETALLES: </div>
    <div style="padding:5px 10px;"><?=$this->row['p_observacion'];?></div>
    <div style="padding:5px 10px; background:<?=$this->bg;?>; color:#FFFFFF;">RESPUESTA DEL OFICIAL DE CR&Eacute;DITO: </div>
    <div style="padding:5px 10px;"><?=$this->row['p_obs_respuesta'];?></div>

    <div style="padding:5px 10px; background:<?=$this->bg;?>; color:#FFFFFF;">DEUDOR: </div>
    <div style="padding:5px 10px;"><?=$arrCl[1];?></div>
<?php
		if(count($arrCl) === 2){
?>
	<div style="padding:5px 10px; background:<?=$this->bg;?>; color:#FFFFFF;">CODEUDOR: </div>
    <div style="padding:5px 10px;"><?=$arrCl[2];?></div>
<?php
		}
?>
</div>
<?php
		$html = ob_get_clean();
		return $html;
	}
}
?>