<?php
require_once('sibas-db.class.php');
class MedicalCertified{
	private $cx, $sql, $rs, $row, $sqlc, $rsc, $rowc, 
			$sqlq, $rsq, $rowq, $sqlt, $rst, $rowt, $ide, $idd, 
			$cia, $ef, $bc = false, $idd_link = '';
	protected $flag, $title, $type;
	public $err;
	
	public function MedicalCertified($ide, $cia, $ef, $idd, $flag = false, $type = '0') {
		$this->cx = new SibasDB();
		$this->cia = $this->cx->real_escape_string(trim(base64_decode($cia)));
		$this->ef = $this->cx->real_escape_string(trim(base64_decode($ef)));
		$this->ide = $this->cx->real_escape_string(trim(base64_decode($ide)));
		$this->idd = $this->cx->real_escape_string(trim(base64_decode($idd)));
		if (empty($this->idd) === false) {
			$this->bc = true;
			$this->idd_link = '&idd=' . base64_encode($this->idd);
		}
		$this->flag = $flag;
		$this->type = $this->cx->real_escape_string(trim(base64_decode($type)));
		
		$this->err = $this->get_query_cm();
	}
	
	private function get_query_cm() {
		$this->sql = 'select 
			scm.id_cm,
			scm.tipo,
			scm.titulo,
			scia.id_compania,
			scia.nombre,
			scia.logo,
			sef.nombre as ef_nombre,
		    sef.logo as ef_logo
		from
			s_cm_certificado as scm
				inner join
		    s_ef_compania as sec ON (sec.id_ef_cia = scm.id_ef_cia)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = sec.id_ef)
				inner join
			s_compania as scia ON (scia.id_compania = sec.id_compania)
			 ';
		if ($this->flag === true) {
			$this->sql .= 'inner join
			    s_cm_respuesta as scr ON (scr.id_cm = scm.id_cm) ';
		}
		
		$this->sql .= 'where ';
		if ($this->flag === false) {
			$this->sql .= 'scm.activado = true
				and scia.id_compania = "'.$this->cia.'"
				and sef.id_ef = "'.$this->ef.'"
				and scia.activado = true
		        and sef.activado = true';
		} else {
			$this->sql .= 'scr.id_emision = "'.$this->ide.'" limit 0, 1';
		}

		$this->sql .= ' ;';
			//echo $this->sql;
		if (($this->rs = $this->cx->query($this->sql,MYSQLI_STORE_RESULT))) {
			if ($this->rs->num_rows === 1) {
				$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
				$this->rs->free();
				$this->title = $this->row['titulo'];
				
				$this->sqlt = 'select 
					sde.no_emision,
					sdc.id_cliente as idCl,
					concat(sdc.nombre,
							" ",
							sdc.paterno,
							" ",
							sdc.materno) as cl_nombre,
					sdc.ci as cl_ci,
					sdc.complemento as cl_complemento,
					sdep.codigo as cl_extension,
					sdep.departamento as cl_ciudad,
					sdc.direccion as cl_direccion,
					sdc.telefono_domicilio as cl_telefono,
					sdc.telefono_celular as cl_celular,
					(case sdd.titular
						when "DD" then "Deudor"
						when "CC" then "Codeudor"
					end) as cl_titular,
					su.nombre as u_nombre,
					sdu.departamento as u_sucursal';
				if ($this->flag === true) {
					$this->sqlt .= ', scr.no_cm as cm_no,
						scr.centro_atencion as cm_centro_atencion,
						scr.persona_contacto as cm_persona_contacto,
						scr.respuesta as cm_respuesta,
						scr.otros as cm_otros,
						scr.fecha_creacion as cm_fecha
					';
				}
				$this->sqlt .= '
						from
					s_de_em_cabecera as sde
						inner join
					s_de_em_detalle as sdd ON (sdd.id_emision = sde.id_emision)
						inner join 
					s_cliente as sdc ON (sdc.id_cliente = sdd.id_cliente) ';
				if ($this->flag === true) {
					$this->sqlt .= 'inner join
					s_cm_respuesta as scr ON (scr.id_cliente = sdc.id_cliente 
						and scr.id_emision = sde.id_emision)';
				}
					
				$this->sqlt .= 'inner join 
					s_entidad_financiera as sef ON (sef.id_ef = sde.id_ef)
						inner join 
					s_usuario as su ON (su.id_usuario = sde.id_usuario)
						inner join 
					s_departamento as sdu ON (sdu.id_depto = su.id_depto)
						inner join
					s_departamento as sdep ON (sdep.id_depto = sdc.extension)
				where
					sde.id_emision = "'.$this->ide.'"';
				if ($this->bc === true) {
					$this->sqlt .= ' and sdd.id_detalle = "' . $this->idd . '" ';
				}
				$this->sqlt .= ' and sef.activado = true
					order by sdc.id_cliente asc
					limit 0 , 2
					;';
				//echo $this->sqlt;
				if (($this->rst = $this->cx->query($this->sqlt,MYSQLI_STORE_RESULT))) {
					if ($this->rst->num_rows > 0 && $this->rst->num_rows <= 2) {
						if (strtoupper($this->row['tipo']) === 'CUESTIONARIO') {
							$this->sqlc = 'select 
								scc.id_cc, scu.id_cuestionario, scu.titulo
							from
								s_cm_cert_cues as scc
									inner join
								s_cm_cuestionario as scu ON (scu.id_cuestionario = scc.id_cuestionario)
									inner join
								s_cm_certificado as scm ON (scm.id_cm = scc.id_cm)
							where
								scm.id_cm = '.$this->row['id_cm'].'
									and scc.activado = true
							order by scc.orden asc
							;';
							//echo $this->sqlc;
							if (($this->rsc = $this->cx->query($this->sqlc,MYSQLI_STORE_RESULT))) {
								if ($this->rsc->num_rows > 0) {
									return false;	//CUESTIONARIO
								} else {
									return true;
								}
							} else {
								return true;
							}
						} elseif (strtoupper($this->row['tipo']) === 'EDITOR') {
							return false;	//EDITOR
						} else {
							return true;
						}
					} else {
						return true;
					}
				} else {
					return true;
				}
			} else {
				return true;
			}
		} else {
			return true;
		}
	}
	
	private function get_query_qs($id_cc) {
		$this->sqlq = 'select 
			scg.id_grupo, scp.id_pregunta, scp.pregunta, scp.tipo
		from
			s_cm_grupo as scg
				inner join
			s_cm_cert_cues as scc ON (scc.id_cc = scg.id_cc)
				inner join
			s_cm_pregunta as scp ON (scp.id_pregunta = scg.id_pregunta)
		where
			scg.id_cc = '.$id_cc.'
				and scg.activado = true
		order by scg.orden asc
		;';
		
		if (($rsq = $this->cx->query($this->sqlq,MYSQLI_STORE_RESULT))) {
			if ($rsq->num_rows > 0) {
				return $rsq;
			}
		}
		return false;
	}
	
	public function get_certified() {
		switch (strtoupper($this->row['tipo'])) {
		case 'EDITOR':
			if ($this->flag === false) {
				return $this->get_editor();
			} else {
				if ($this->type === 'PRINT') {
					return $this->get_editor_result();
				} elseif ($this->type === 'PDF') {
					$content = $this->get_editor_result();
					$this->set_medical_certificate_pdf($content);
				}
			}
			break;
		case 'CUESTIONARIO':
			if ($this->flag === false) {
				return $this->get_questionnaire();
			} else {
				if ($this->type === 'PRINT') {
					return $this->get_questionnaire_result();
				} elseif($this->type === 'PDF'){
					$content = $this->get_questionnaire_result();
					$this->set_medical_certificate_pdf($content);
				}
			}
			break;
		}
	}
	
	private function get_editor() {
		ob_start();
?>
<script type="text/javascript">
$(document).ready(function(e) {
    get_tinymce('fc-observation');
	
	$("#fp-cancel").click(function(e){
		$("#ctr-certified").hide();
		$("#ctr-process").slideDown();
	});
	
	$("#form-cm").validateForm({
		action: 'DE-medical-certificate-record.php',
		nameLoading: '.loading-01',
	});
});
</script>
<form id="form-cm" name="form-cm" class="f-process f-cm" style="width:90%; margin:0 auto;">
	<h4 class="h4"><?=$this->title;?></h4>
<?php
	if ($this->rst->data_seek(0) === true) {
		$k = 1;
		$this->rowt = $this->rst->fetch_array(MYSQLI_ASSOC);
?>
	<input type="hidden" name="fc-cm-<?=$k;?>" id="fc-cm-<?=$k;?>-1" class="fp-rb" value="1" rel="<?=$k;?>" />
	<input type="hidden" id="fc-idCl-<?=$k;?>" name="fc-idCl-<?=$k;?>" value="<?=base64_encode($this->rowt['idCl']);?>">
    <div class="ctr-obs" align="center">
        <label class="fp-lbl" style="text-align:left;">Observaciones: <span>*</span></label><br>
        <textarea id="fc-observation" name="fc-observation" class="required"></textarea><br>
	
		<input type="hidden" id="ide" name="ide" value="<?=base64_encode($this->ide);?>">
        <input type="hidden" id="fp-nCl" name="fp-nCl" value="<?=$k;?>">
        <input type="hidden" id="fp-idCm" name="fp-idCm" value="<?=base64_encode($this->row['id_cm']);?>">
		<input type="hidden" id="token" name="token" value="<?=md5('editor');?>">
		<input type="submit" id="fp-process" name="fp-process" value="Guardar" class="fp-btn">
        <input type="reset" id="fp-reset" name="fp-reset" value="Resetear" class="fp-btn">
        <input type="button" id="fp-cancel" name="fp-cancel" value="Cancelar" class="fp-btn">
        
        <div class="loading loading-01">
            <img src="img/loading-01.gif" width="35" height="35" />
        </div>
	</div>
    
</form>
<?php
	}
		$cm = ob_get_clean();
		return $cm;
	}
	
	private function get_questionnaire() {
		ob_start();
?>
<script type="text/javascript">
$(document).ready(function(e) {
    $("#fc-cl").change(function(){
		
	});
});
</script>
<style type="text/css">
.f-cm .fp-lbl{
	/*width:auto;*/
}
.f-cm .fp-lbl-cl{
	width:auto;
}
.fm-cm .fp-lbl .nmb-list{
	list-style:circle;
}

.f-cm input[type="text"]{
	width:200px;
}

.f-cm textarea{
	width:400px;
	height:30px;
	resize:none;
	overflow:auto;
	font-size:100%;
}

.f-cm input[type="checkbox"]{
	cursor:pointer;
}

.f-cm .fp-rb{
	vertical-align:text-bottom;
}

.f-process .tbl-qs{
	width:90%;
	font-size:70%;
	margin:0 auto;
	border:1px solid #990;
}

.f-process .tbl-qs tr td{
	width:33%;
	padding:2px 0;
	border:1px solid #999;
}

.f-process .ctr-qs{
	width:100%;
}

.f-process .ctr-qs tr td{border:0 none; font-weight:bold;}

.f-process .ctr-qs tr td .cb-qs-cm{
	width:10px;
	height:10px;
	margin-left:2px;
	padding:2px;
	border:1px solid #333;
	background:#FFF;
}

.f-process .ctr-qs tr td .cb-qs-cm div{width:10px; height:10px; background:#FFF;}

.tbl-check-cm{
	width:100%;
	font-size:80%;
	
}

.f-process .tbl-check-cm tr td{
	font-weight:bold;
}
</style>
<script type="text/javascript">
$(document).ready(function(e) {
	$("#form-cm").validateForm({
		action: 'DE-medical-certificate-record.php',
		cm: true,
		nameLoading: '.loading-01'
	});
	
	$('#form-cm input').iCheck({
		checkboxClass: 'icheckbox_flat-red',
		radioClass: 'iradio_flat-red'
	});
	
	$('#form-cm input.fp-rb').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green',
		increaseArea: '20%' // optional
	});
	
	$('#form-cm input.fp-rb').on('ifToggled', function(event){
		var check = parseInt($(this).prop('value'));
		var rel = $(this).attr('rel');
		switch(check){
			case 0:
				$("#ctr-cm-"+rel).slideUp();
				$("#fc-center-attention-"+rel+", #fc-contact-person-"+rel).removeClass('required');
				break;
			case 1:
				$("#ctr-cm-"+rel).slideDown();
				$("#fc-center-attention-"+rel+", #fc-contact-person-"+rel).addClass('required');
				break;
		}
	});
	
	/*$(".fp-rb").click(function(){
		var check = parseInt($(this).prop('value'));
		var rel = $(this).attr('rel');
		switch(check){
			case 0:
				$("#ctr-cm-"+rel).slideUp();
				$("#fc-center-attention-"+rel+", #fc-contact-person-"+rel).removeClass('required');
				break;
			case 1:
				$("#ctr-cm-"+rel).slideDown();
				$("#fc-center-attention-"+rel+", #fc-contact-person-"+rel).addClass('required');
				break;
		}
	});*/
	
	$("#fp-reset").click(function(){
		$("#ctr-cm-1, #ctr-cm-2").slideUp();
	});
	
	$("#fp-cancel").click(function(e){
		$("#ctr-certified").hide();
		$("#ctr-process").slideDown();
	});
});
</script>
<form id="form-cm" name="form-cm" class="f-process f-cm" style="width:95%; margin:0 auto;">
	<h4 class="h4"><?=$this->title.'<br>Orden de Atención Medica';?></h4>
<?php
	if ($this->rst->data_seek(0) === true) {
		$k = 0;
		while ($this->rowt = $this->rst->fetch_array(MYSQLI_ASSOC)) {
			$k += 1;
?>
	<hr>
	<label class="fp-lbl" style="width:auto;"><?=$this->rowt['cl_nombre'];?></label>
	<label class="fp-lbl" style="width:auto;">Certificado Medico</label>

	<input type="radio" name="fc-cm-<?=$k;?>" id="fc-cm-<?=$k;?>-1" class="fp-rb" 
		value="1" rel="<?=$k;?>" />
    <label class="fp-lbl" for="fc-cm-<?=$k;?>-1" style="width:auto; cursor:pointer;">SI</label>
    
    <input type="radio" name="fc-cm-<?=$k;?>" id="fc-cm-<?=$k;?>-2" class="fp-rb" 
    	value="0" checked rel="<?=$k;?>" />
    <label class="fp-lbl" for="fc-cm-<?=$k;?>-2" style="width:auto; cursor:pointer;">NO</label>

	<div id="ctr-cm-<?=$k;?>" style="width:700px; display:none; ">
    	<input type="hidden" id="fc-idCl-<?=$k;?>" name="fc-idCl-<?=$k;?>" 
    		value="<?=base64_encode($this->rowt['idCl']);?>">

		<label class="fp-lbl fp-lbl-cl">Carnet de Identidad: </label>
        <input type="text" id="fc-ci-<?=$k;?>" name="fc-ci-<?=$k;?>" 
        	value="<?=$this->rowt['cl_ci'].' '.$this->rowt['cl_extension'];?>" autocomplete="off" readonly>
		
        <label class="fp-lbl fp-lbl-cl">Dirección: </label>
        <input type="text" id="fc-address-<?=$k;?>" name="fc-address-<?=$k;?>" 
        	value="<?=$this->rowt['cl_direccion'];?>" autocomplete="off" readonly><br>
        
        <label class="fp-lbl fp-lbl-cl">Regional: </label>
        <input type="text" id="fc-regional-<?=$k;?>" name="fc-regional-<?=$k;?>" 
        	value="<?=$this->rowt['cl_ciudad'];?>" autocomplete="off" readonly>
        
        <label class="fp-lbl fp-lbl-cl">Teléfono: </label>
        <input type="text" id="fc-phone-<?=$k;?>" name="fc-phone-<?=$k;?>" 
        	value="<?=$this->rowt['cl_telefono'];?>" autocomplete="off" readonly><br>
        
        <label class="fp-lbl">Centro de Atención: </label>
        <input type="text" id="fc-center-attention-<?=$k;?>" name="fc-center-attention-<?=$k;?>" 
        	value="" autocomplete="off" class="" style="width:300px;"><br>
        
        <label class="fp-lbl">Persona de Contacto: </label>
        <input type="text" id="fc-contact-person-<?=$k;?>" name="fc-contact-person-<?=$k;?>" 
        	value="" autocomplete="off" class=""  style="width:300px;"><br>
<?php
		if ($this->rsc->data_seek(0) === true) {
			$arrQS = array();
			$arrOB = array();
			if ($this->flag === true) {
				$arrQS = json_decode($this->rowt['cm_respuesta'], true);
				$arrOB = json_decode($this->rowt['cm_otros'], true);
			}
			$j = 0;
			while ($this->rowc = $this->rsc->fetch_array(MYSQLI_ASSOC)) {
				$j += 1;
?>
		<label class="fp-lbl" style="width:auto;">
			<span class="nmb-list"><?=$this->arabigo2romano($j);?></span> 
			<?=$this->rowc['titulo'];?>
		</label><br>
<?php
				if (($this->rsq = $this->get_query_qs($this->rowc['id_cc'])) !== false) {
					$nQs = $this->rsq->num_rows;
					$row = $nQs / 3;
					$mod = $nQs % 3;
					$i = 0;
?>
		<table class="tbl-qs">
<?php
					while ($this->rowq = $this->rsq->fetch_array(MYSQLI_ASSOC)) {
						if ($i === 0) {
							echo '<tr>';
						}
						
						if ($this->rowq['tipo'] === 'cb') {
							$wTD = 'width:33%;';
							if ($nQs === 1) {
								$wTD = 'width:100%;';
							}
?>
		<td style=" <?=$wTD;?> ">
        	<table class="ctr-qs">
        		<tr>
					<td style="width:10%;">
	                    <input type="checkbox" id="fc-qs-<?=$k;?>-<?=$this->rowq['id_pregunta'];?>" 
	                    	name="fc-qs-<?=$k;?>-<?=$this->rowq['id_pregunta'];?>" 
	                    	value="<?=$this->rowq['id_pregunta'];?>" class="cb-qs-<?=$k;?>">
	                </td>
                	<td style="width:90%;"><?=$this->rowq['pregunta'];?></td>
				</tr>
			</table>
		</td>
<?php
							$i += 1;
						} elseif ($this->rowq['tipo'] === 'txtarea') {
							if ($mod === 0) {
								echo '<td style="border:1px solid #999; width:34%;"></td>';
							} elseif ($mod === 1) {
								echo '';
							} elseif ($mod === 2) {
								echo '<td style="border:1px solid #999; width:33%;"></td>
									<td style="border:1px solid #999; width:34%;"></td>';
							}
							
							echo '</tr><tr>';
?>
		<td colspan="3" style="width:100%;">
        	<table class="ctr-qs">
        		<tr>
					<td style="width:10%;">
	                    <input type="checkbox" id="fc-qs-<?=$k;?>-<?=$this->rowq['id_pregunta'];?>" 
	                    	name="fc-qs-<?=$k;?>-<?=$this->rowq['id_pregunta'];?>" 
	                    	value="<?=$this->rowq['id_pregunta'];?>" class="cb-qs-<?=$k;?> <?=$this->rowq['tipo'];?>">
	                </td>
	                <td style="width:90%;"><?=$this->rowq['pregunta'];?>
	                	<textarea id="fc-obs-<?=$k;?>-<?=$this->rowq['id_pregunta'];?>"></textarea>
	                </td>
				</tr>
			</table>
		</td>
<?php
							$i += 1;
						}
						
						if ($i === 3 || $nQs === 1 || $this->rowq['tipo'] === 'txtarea') {
							echo '</tr>';
							$i = 0;
						}
					}

					if ($i !== 0) {
						switch ($i) {
						case 1:
							echo '<td style="border:1px solid #999; width:33%;"></td>
								<td style="border:1px solid #999; width:34%;"></td>';
							break;
						case 2:
							echo '<td style="border:1px solid #999; width:34%;"></td>';
							break;
						}
						echo '</tr>';
					}
?>
        </table>
<?php
				}
			}
		}
?>
    </div>
<?php
		}
	}
?>
    <div class="loading loading-01">
        <img src="img/loading-01.gif" width="35" height="35" />
    </div>
    
<?php
		if ($this->flag === FALSE) {
?>
	<div align="center">
		<input type="hidden" id="ide" name="ide" value="<?=base64_encode($this->ide);?>">
        <input type="hidden" id="fp-nCl" name="fp-nCl" value="<?=$k;?>">
        <input type="hidden" id="fp-idCm" name="fp-idCm" value="<?=base64_encode($this->row['id_cm']);?>">
		<input type="submit" id="fp-process" name="fp-process" value="Guardar" class="fp-btn">
        <input type="reset" id="fp-reset" name="fp-reset" value="Resetear" class="fp-btn">
        <input type="button" id="fp-cancel" name="fp-cancel" value="Cancelar" class="fp-btn">
	</div>
<?php
		}
?>
</form>
<?php
		$cm = ob_get_clean();
		return $cm;
	
	}
	
	private function get_editor_result() {
		ob_start();
?>
<div style="width:740px; height:auto; padding:15px 15px; margin:0; font-weight:bold; font-size:12px;">
<?php
		$this->rowt = $this->rst->fetch_array(MYSQLI_ASSOC);
?>
	<table style="width:100%;" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width:40%;" valign="bottom" align="left">
            	<img src="images/<?=$this->row['ef_logo'];?>" height="60">
            </td>
            <td style="width:20%;">&nbsp;</td>
			<td style="width:40%;" valign="bottom" align="right">
				<img src="images/<?=$this->row['logo'];?>" height="60">
			</td>
        </tr>
    </table><br>
    
    <div style="text-align:center; font-size:16px; width:100%;">
    	<?=$this->title;?> / SOLICITUD DE REQUISITOS N° <?=$this->rowt['cm_no'];?>
    </div><br>
    
	<table style="width:98%;" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width:15%; font-weight:bold;">TITULAR 1: </td>
            <td style="width:55%; color:#00557D; "><?=$this->rowt['cl_nombre'];?></td>
            <td style="width:15%; font-weight:bold;">CERTIFICADO N°: </td>
            <td style="width:15%; color:#00557D; ">DE - <?=$this->rowt['no_emision'];?></td>
        </tr>
        <tr>
            <td style="width:15%; font-weight:bold;">TITULAR 2: </td>
            <td style="width:50%;"></td>
            <td style="width:20%; font-weight:bold;">FECHA DE REGISTRO: </td>
            <td style="width:15%; color:#00557D; "><?=date('d/m/Y', strtotime($this->rowt['cm_fecha']));?></td>
        </tr>
        <tr>
        	<td style="width:15%; font-weight:bold;"><br>ESTIMADO(A)</td>
            <td colspan="3" style="width:85%; color:#00557D; "><br><?=$this->rowt['cl_nombre'];?></td>
        </tr>
    </table>
    <br>

    <div style="width:95%; font-weight:bold; text-align:justify;">
    	Con relación al caso de referencia, la compañia aseguradora le solicita 
    	realizar los siguientes exámenes médicos/aclaraciones/requisitos adicionales 
    	de acuerdo al siguiente detalle:
    </div>
    <br>
    
    <table style="width:100%;" border="0" cellpadding="0" cellspacing="0">
    	<tr>
        	<td style="width:100%; text-align:center;" align="center">
            	<div style="width:95%; height:350px; border:1px solid #069; 
            		padding:5px 5px; text-align:justify; font-size:10px;">
            		<?=$this->rowt['cm_otros'];?>
            	</div>
            </td>
        </tr>
    </table><br><br>

	<div style="width:95%; font-weight:bold; text-align:justify;">
    	Es importante mencionar que usted no cuenta con la cobertura de seguro 
    	hasta que cumpla con lo solicitado y la aseguradora de su aceptación por 
    	escrito.<br><br>
		Sin otro particular y a la espera de lo solicitado, lo saludo.<br><br>
		Atentamente.<br><br><br><br><br><br>
    </div>
    <table style="width:98%;" border="0" cellpadding="0" cellspacing="0">
    	<tr>
        	<td style="width:40%; border-bottom:1px solid #333;"></td>
            <td style="width:30%;"></td>
            <td style="width:15%;"></td>
            <td style="width:15%;"></td>
        </tr>
        <tr>
        	<td style="width:40%; text-align:center; font-weight:bold;">
        		SELLO Y FIRMA DEL OFICIAL DE CRÉDITO
        	</td>
            <td style="width:30%;"></td>
            <td style="width:15%; font-weight:bold;">SUCURSAL: </td>
            <td style="width:15%; font-weight:bold;">
            	<?=$this->rowt['u_sucursal'];?>
            </td>
        </tr>
        <tr>
        	<td style="width:40%; color:#00557D; text-align:center; font-weight:bold;">
        		<?=$this->rowt['u_nombre'];?>
        	</td>
            <td style="width:30%;"></td>
            <td style="width:15%;"></td>
            <td style="width:15%;"></td>
        </tr>
    </table>
<?php
	if ($this->type === 'PRINT') {
?>
<style type="text/css">
.link-cm{
	display:block; width:150px; height:auto; margin:5px 5px; padding: 10px 15px; 
	text-decoration:none; background:#1D1D1D; color:#FF3C3C; text-align:center; 
	font-size:15px;
}

.link-cm:hover{
	color:#099;
}
</style>
	<div align="right">
    	<a href="medical-certificate.php?ide=<?=
    	base64_encode($this->ide) . $this->idd_link;?>&cia=<?=
    	base64_encode($this->row['id_compania']);?>&ef=<?=
    	base64_encode($this->ef);?>&type=<?=
    	base64_encode('PDF');?>" class="link-cm" target="_blank">Descargar</a>
    </div>
<?php
	}
?>
</div>
<?php
		$cm = ob_get_clean();
		return $cm;
	}
	
	
	private function get_questionnaire_result() {
		ob_start();
?>
<div style="width:740px; height:auto; padding:15px 15px; margin:0; font-weight:normal; font-size:12px;">
<?php
	if ($this->rst->data_seek(0) === true) {
		$k = 0;
		$nCs = $this->rst->num_rows;
		while ($this->rowt = $this->rst->fetch_array(MYSQLI_ASSOC)) {
			$k += 1;
?>
	<table style="width:100%; margin-top:10px;" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width:40%;" valign="bottom" align="left">
            	<img src="images/<?=$this->row['ef_logo'];?>" height="60">
            </td>
            <td style="width:20%;">&nbsp;</td>
			<td style="width:40%;" valign="bottom" align="right">
				<img src="images/<?=$this->row['logo'];?>" height="60">
			</td>
        </tr>
    </table><br>
    
    <div style="text-align:center; font-size:14px; width:100%; font-weight:bold;">
    	<?=$this->title.'<br>Orden de Atención Medica';?> N° <?=$this->rowt['cm_no'];?>
    </div><br>
    
	<!-- <h4 class="h4"><?=$this->title.'<br>Orden de Atención Medica';?></h4> -->
	<table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
    	<tr>
        	<td style="width:100%;">
            	<span style="font-weight:bold;">TITULAR <?=$k;?>: </span><?=$this->rowt['cl_nombre'];?>
			</td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
        <tr>
            <td style="width:20%; font-weight:bold;">Carnet de Identidad: </td>
            <td style="width:30%;"><?=$this->rowt['cl_ci'].' '.$this->rowt['cl_extension'];?></td>
            <td style="width:15%; font-weight:bold;">Dirección: </td>
            <td style="width:35%;"><?=$this->rowt['cl_direccion'];?></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
        <tr>
            <td style="width:10%; font-weight:bold;">Regional: </td>
            <td style="width:40%;"><?=$this->rowt['cl_ciudad'];?></td>
            <td style="width:10%; font-weight:bold;">Teléfono: </td>
            <td style="width:40%;"><?=$this->rowt['cl_telefono'];?></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
        <tr>
            <td style="width:20%; font-weight:bold;">Centro de Atención: </td>
            <td style="width:80%;"><?=$this->rowt['cm_centro_atencion'];?></td>
        </tr>
        <tr>
            <td style="width:20%; font-weight:bold;">Persona de Contacto: </td>
            <td style="width:80%;"><?=$this->rowt['cm_persona_contacto'];?></td>
        </tr>
    </table><br>
<?php
		if ($this->rsc->data_seek(0) === true) {
			$arrQS = array();
			$arrOB = array();
		
			$arrQS = json_decode($this->rowt['cm_respuesta'],true);
			$arrOB = json_decode($this->rowt['cm_otros'],true);
			
			$j = 0;
			while ($this->rowc = $this->rsc->fetch_array(MYSQLI_ASSOC)) {
				$j += 1;
?>
		<br>
		<div style="text-align:left; font-size:11px; width:100%; font-weight:bold;">
        	<span><?=$this->arabigo2romano($j);?></span> <?=$this->rowc['titulo'];?>
        </div>   
<?php
				if (($this->rsq = $this->get_query_qs($this->rowc['id_cc'])) !== false) {
					$nQs = $this->rsq->num_rows;
					$row = $nQs / 3;
					$mod = $nQs % 3;
					$i = 0;
?>
		<table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
<?php
					while ($this->rowq = $this->rsq->fetch_array(MYSQLI_ASSOC)) {
						if ($i === 0) {
							echo '<tr>';
						}
						
						if ($this->rowq['tipo'] === 'cb') {
							$wTD = 'width:33%;';
							if ($nQs === 1) {
								$wTD = 'width:100%;';
							}
?>
		<td style="border:1px solid #999; padding:2px 2px; <?=$wTD;?> ">
        	<table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
        		<tr>
<?php

							$bgQs = '';
							for ($i1 = 0; $i1 < count($arrQS); $i1++) {
								$question = explode('|', $arrQS[$i1]);
								if ($question[1] === $this->rowq['id_pregunta']) {
									if ($question[0] === 'Y') {
										$bgQs = 'background: #333;';
									}
								}
							}
?>
					<td style="width:10%;">
	                	<div style="width:10px;	height:10px; margin-left:2px; padding:2px; 
	                		border:1px solid #333; background:#FFF;">
	                    	<div style="width:10px; height:10px; background:#FFF; <?=$bgQs;?> ">
	                        </div>
	                    </div>
					</td>
                	<td style="width:90%;"><?=$this->rowq['pregunta'];?></td>
				</tr>
			</table>
		</td>
<?php
							$i += 1;
						} elseif ($this->rowq['tipo'] === 'txtarea') {
							if ($mod === 0) {
								echo '<td style="border:1px solid #999; width:34%;"></td>';
							} elseif($mod === 1) {
								echo '';
							} elseif($mod === 2) {
								echo '<td style="border:1px solid #999; width:33%;"></td>
									<td style="border:1px solid #999; width:34%;"></td>';
							}
							
							echo '</tr><tr>';
?>
		<td colspan="3" style="border:1px solid #999; padding:2px 2px; width:100%;">
        	<table class="ctr-qs">
        		<tr>
<?php
							if ($this->flag === true) {
								$bgQs = '';
								$txtOb = '';
								for ($i1 = 0; $i1 < count($arrOB); $i1++) {
									$question = explode('|', $arrOB[$i1]);
									if ($question[0] === $this->rowq['id_pregunta']) {
										$bgQs = 'background: #333;';
										$txtOb = $question[1];
									}
								}
?>
					<td style="width:10%;">
	                    <div style="width:10px;	height:10px; margin-left:2px; padding:2px; 
	                    	border:1px solid #333; background:#FFF;">
	                    	<div style="width:10px; height:10px; background:#FFF; <?=$bgQs;?> ">
	                        </div>
	                    </div>
					</td>
	                <td style="width:90%;"><?=$this->rowq['pregunta'];?> | <?=$txtOb;?>
	                </td>
<?php
							}
?>
				</tr>
			</table>
		</td>
<?php
							$i += 1;
						}
						
						if ($i === 3 || $nQs === 1 || $this->rowq['tipo'] === 'txtarea') {
							echo '</tr>';
							$i = 0;
						}
					}

					if ($i !== 0) {
						switch ($i) {
						case 1:
							echo '<td style="border:1px solid #999; width:33%;"></td>
								<td style="border:1px solid #999; width:34%;"></td>';
							break;
						case 2:
							echo '<td style="border:1px solid #999; width:34%;"></td>';
							break;
						}
						echo '</tr>';
					}
?>
        </table>
<?php
				}
				
			}
		}
		if ($nCs > 1 && $k < $nCs) {
?>
	<hr>

	<page><div style="page-break-before: always;"></div></page>
<?php
		}
		}
		if ($this->type === 'PDF') {
?>
	<table style="width:100%; margin-top:100px;" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width:30%;"></td>
            <td style="width:40%; text-align: center;" valign="top">
            	<img src="img/firma-cm.jpg" width="200" height="100" >
            </td>
			<td style="width:30%;"></td>
        </tr>
    </table>
<?php
		}
	}

////////////////////////////////////
	if ($this->type === 'PRINT') {

?>
<style type="text/css">
.link-cm{
	display:block; width:150px; height:auto; margin:5px 5px; padding: 10px 15px; 
	text-decoration:none; background:#1D1D1D; color:#FF3C3C; text-align:center; 
	font-size:15px;
}

.link-cm:hover{
	color:#099;
}
</style>
	<div align="right">
    	<a href="medical-certificate.php?ide=<?=
    	base64_encode($this->ide) . $this->idd_link;?>&cia=<?=
    	base64_encode($this->row['id_compania']);?>&ef=<?=
    	base64_encode($this->ef);?>&type=<?=
    	base64_encode('PDF');?>" class="link-cm" target="_blank">Descargar</a>
    </div>
<?php
	}
?>
</div>
<?php
		$cm = ob_get_clean();
		return $cm;
	}
	
	private function set_medical_certificate_pdf($content) {
		set_time_limit(0);
		require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		try{
			$html2pdf = new HTML2PDF('P','Letter','en',true,'UTF-8',2);
			$html2pdf->WriteHTML($content);
			$html2pdf->Output($this->title.'.pdf');
		}catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
	}
	
	private function arabigo2romano($valor) {
		$romanos = array('M', 'CM', 'D', 'CD', 'C', 'XC', 'L', 'XL', 'X', 'IX', 'V', 'IV', 'I');
		$valores = array(1000, 900, 500, 400, 100, 90, 50, 40, 10, 9, 5, 4, 1);
		$resultado = '';
		
		for ($i = 0; $i < count($romanos); $i++) {
			while ($valor>=$valores[$i]) {
				$resultado.=$romanos[$i];
				$valor-=$valores[$i];
			}
		}
		
		return $resultado;
	}
}
?>