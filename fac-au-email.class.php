<?php
require_once('sibas-db.class.php');
require('PHPMailer/class.phpmailer.php');

class FACEmailAU{
	private $sql, $rs, $row, $sqlc, $rsc, $rowc, $ide, $idVh, $flag, $body, $emailCC;
	public $cx, $err;
	
	public function FACEmailAU($flag = FALSE){
		$this->cx = new SibasDB();
		$this->flag = $flag;
	}
	
	public function send_mail_fac($ide, $idVh, $emailCC = ''){
		$this->ide = $this->cx->real_escape_string($ide);
		$this->idVh = $this->cx->real_escape_string($idVh);
		$this->emailCC = explode(',', $emailCC);
		
		$this->sql = 'select 
			sae.id_emision as ide,
		    sad.id_vehiculo as idVh,
		    sae.id_ef as idef,
			sae.id_compania as idcia,
			sae.no_emision,
			su.id_usuario as u_id,
			su.usuario,
			su.email,
			su.nombre,
			sua.usuario as a_usuario,
		    sua.email as a_email,
		    sua.nombre as a_nombre,
			(case scl.tipo
		        when
		            0
		        then
		            concat(scl.nombre,
		                    " ",
		                    scl.paterno,
		                    " ",
		                    scl.materno)
		        when 1 then scl.razon_social
		    end) as cl_nombre,
		    stv.vehiculo as v_tipo_vehiculo,
		    sma.marca as v_marca,
		    smo.modelo as v_modelo,
		    sad.anio as v_anio,
		    sad.placa as v_placa,
			if(saf.aprobado is null,
				if(sap.id_pendiente is not null,
					case sap.respuesta
						when 1 then "S|Subsanado/Pendiente"
						when 0 then "O|Observado"
					end,
					"P|Pendiente"),
				case saf.aprobado
					when "SI" then "A|Aprobado"
					when "NO" then "R|Rechazado"
				end) as estado,
			case
				when sds.codigo = "ED" then "E"
		        when sds.codigo != "ED" then "NE"
				else null
			end as observacion,
			saf.aprobado as f_aprobado,
			saf.tasa_recargo as f_tasa_recargo,
			saf.porcentaje_recargo as f_porcentaje_recargo,
			saf.tasa_actual as f_tasa_actual,
			saf.tasa_final as f_tasa_final,
			saf.observacion as f_observacion,
			suf.nombre as f_nombre,
			suf.usuario as f_usuario,
			suf.email as f_email,
			sds.id_estado,
			sds.estado as estado_pendiente,
			sap.observacion as p_observacion,
			sap.respuesta as p_respuesta,
			sap.obs_respuesta as p_obs_respuesta,
			sup.nombre as p_nombre,
			sup.usuario as p_usuario,
			sup.email as p_email,
			sef.nombre as ef_nombre,
		    sef.logo as ef_logo
		from
		    s_au_em_detalle as sad
		        inner join
		    s_au_em_cabecera as sae ON (sae.id_emision = sad.id_emision)
		        inner join
		    s_au_tipo_vehiculo as stv ON (stv.id_tipo_vh = sad.id_tipo_vh)
		        inner join
		    s_au_marca as sma ON (sma.id_marca = sad.id_marca)
		        inner join
		    s_au_modelo as smo ON (smo.id_modelo = sad.id_modelo)
				inner join
			s_cliente as scl ON (scl.id_cliente = sae.id_cliente)
				inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sae.id_ef)
				inner join
			s_compania as scia ON (scia.id_compania = sae.id_compania)
				inner join
		    s_usuario as su ON (su.id_usuario = sae.id_usuario)
				left join
			s_usuario as sua ON (sua.id_usuario = sae.apr_usuario)
				left join
		    s_au_facultativo as saf ON (saf.id_vehiculo = sad.id_vehiculo
		        and saf.id_emision = sae.id_emision)
		        left join
		    s_au_pendiente as sap ON (sap.id_vehiculo = sad.id_vehiculo
		        and sap.id_emision = sae.id_emision)
		        left join
		    s_estado as sds ON (sds.id_estado = sap.id_estado)
				left join
		    s_usuario as sup ON (sup.id_usuario = sap.pro_usuario)
				left join
		    s_usuario as suf ON (suf.id_usuario = saf.pro_usuario)
		where
		    sae.id_emision = "'.$this->ide.'"
		    	and sad.id_vehiculo = "'.$this->idVh.'"
		limit 0, 1
		;';
		//echo $this->sql;
		if(($this->rs = $this->cx->query($this->sql,MYSQLI_STORE_RESULT))){
			if($this->rs->num_rows === 1){
				$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
				$this->rs->free_result();
				$arrCl = array();
				
				$k = 1;
				$arrCl[$k] = $this->row['cl_nombre'];
				
				$state = explode('|', $this->row['estado']);
				$arr_user = array();
				$subject = $this->row['ef_nombre'].' ';
				
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
					
					$subject .= ': Respuesta del Oficial de Crédito';
				}
				
				$mail = new PHPMailer();
				$mail->Host = $arr_user[0];
				$mail->From = $arr_user[0];
				$mail->FromName = $this->row['ef_nombre'];
				$mail->Subject = $subject.' a Caso Facultativo No. AU-'.$this->row['no_emision'].' '.$arrCl[1];
				
				$_IMP = $this->cx->verify_implant(base64_encode($this->row['idef']), 'AU');
				if ($_IMP === TRUE) {
					if (($rsUi = $this->cx->get_user_implant(base64_encode($this->row['u_id']), base64_encode($this->row['idef']), 'AU')) !== FALSE) {
						while ($rowUi = $rsUi->fetch_array(MYSQLI_ASSOC)) {
							$mail->addAddress($rowUi['i_email'], $rowUi['i_nombre']);
						}
					}
				}
				
				$mail->addAddress($arr_user[2], $arr_user[3]);
				if (is_null($this->row['a_usuario']) === FALSE) {
					$mail->addAddress($this->row['a_email'], $this->row['a_nombre']);
				}
				$mail->addCC($arr_user[0], $arr_user[1]);
				
				$this->sqlc = 'SELECT sc.correo, sc.nombre 
						FROM s_correo as sc
							INNER JOIN s_entidad_financiera as sef ON (sef.id_ef = sc.id_ef)
						WHERE sc.producto = "AU" AND sef.id_ef = "'.$this->row['idef'].'" AND sef.activado = true ;';
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
				
				if($mail->send() === TRUE) {
					return TRUE;
				} else {
					return FALSE;
				}
			}else{
				
			}
		}
	}
	
	private function html_body($arrCl){
		$state = explode('|', $this->row['estado']);
		ob_start();
?>
<div style="width:600px; border:1px solid #CCCCCC; color:#000000; font-weight:bold; font-size:12px; text-align:left;">
	<div style="padding:5px 10px; background:#006697; color:#FFFFFF;">N&Uacute;MERO DE EMISI&Oacute;N</div>
    <div style="padding:5px 10px;">AU-<?=$this->row['no_emision'];?></div>
    <div style="padding:5px 10px; background:#006697; color:#FFFFFF;">DATOS DEL VEH&Iacute;CULO</div>
    <div style="padding:5px 10px;">
    	<span>Tipo de Vehículo: </span><?=$this->row['v_tipo_vehiculo'];?><br>
    	<span>Marca: </span><?=$this->row['v_marca'];?><br>
    	<span>Modelo: </span><?=$this->row['v_modelo'];?><br>
    	<span>A&ntilde;o: </span><?=$this->row['v_anio'];?><br>
    	<span>Placa: </span><?=$this->row['v_placa'];?>
	</div>
<?php
		if($state[0] === 'A' || $state[0] === 'R'){
?>
	<div style="padding:5px 10px; background:#006697; color:#FFFFFF;">APROBADO</div>
    <div style="padding:5px 10px;"><?=$this->row['f_aprobado'];?></div>
<?php
			if($state[0] === 'A'){
?>
    <div style="padding:5px 10px; background:#006697; color:#FFFFFF;">TASA DE RECARGO</div>
    <div style="padding:5px 10px;"><?=$this->row['f_tasa_recargo'];?></div>
    <div style="padding:5px 10px; background:#006697; color:#FFFFFF;">TASA FINAL</div>
    <div style="padding:5px 10px;"><?=$this->row['f_tasa_final'];?></div>
<?php
			}
?>
	<div style="padding:5px 10px; background:#006697; color:#FFFFFF;">OBSERVACIONES: </div>
	<div style="padding:5px 10px;"><?=$this->row['f_observacion'];?></div>
<?php
		}else{
			$obsPe = '';
			$obsPe = $this->row['p_observacion'];
?>
	<div style="padding:5px 10px; background:#006697; color:#FFFFFF;">ESTADO DE LA SOLICITUD</div>
    <div style="padding:5px 10px;"><?=$state[1];?></div>
    <div style="padding:5px 10px; background:#006697; color:#FFFFFF;">MOTIVO PENDIENTE</div>
    <div style="padding:5px 10px;"><?=$this->row['estado_pendiente'];?></div>
    <div style="padding:5px 10px; background:#006697; color:#FFFFFF;">DETALLES: </div>
    <div style="padding:5px 10px;"><?=$obsPe;?></div>
<?php
		}
?>
    <div style="padding:5px 10px; background:#006697; color:#FFFFFF;">Arrendatario: </div>
    <div style="padding:5px 10px;"><?=$arrCl[1];?></div>
</div>
<?php
		$html = ob_get_clean();
		return $html;
	}
	
	private function html_body_response($arrCl){
		$state = explode('|', $this->row['estado']);
		ob_start();
?>
<div style="width:600px; border:1px solid #CCCCCC; color:#000000; font-weight:bold; font-size:12px; text-align:left;">
	<div style="padding:5px 10px; background:#006697; color:#FFFFFF;">N&Uacute;MERO DE EMISI&Oacute;N</div>
    <div style="padding:5px 10px;">AU-<?=$this->row['no_emision'];?></div>

	<div style="padding:5px 10px; background:#006697; color:#FFFFFF;">ESTADO DE LA SOLICITUD</div>
    <div style="padding:5px 10px;"><?=$state[1];?></div>
    <div style="padding:5px 10px; background:#006697; color:#FFFFFF;">MOTIVO PENDIENTE</div>
    <div style="padding:5px 10px;"><?=$this->row['estado_pendiente'];?></div>
    <div style="padding:5px 10px; background:#006697; color:#FFFFFF;">DETALLES: </div>
    <div style="padding:5px 10px;"><?=$this->row['p_observacion'];?></div>
    <div style="padding:5px 10px; background:#006697; color:#FFFFFF;">RESPUESTA DEL OFICIAL DE CR&Eacute;DITO: </div>
    <div style="padding:5px 10px;"><?=$this->row['p_obs_respuesta'];?></div>

    <div style="padding:5px 10px; background:#006697; color:#FFFFFF;">Arrendatario: </div>
    <div style="padding:5px 10px;"><?=$arrCl[1];?></div>
</div>
<?php
		$html = ob_get_clean();
		return $html;
	}
}
?>