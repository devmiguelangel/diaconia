<?php
require_once('certificate-sibas-html.class.php');
/**
 * 
 */
class CertificateQuery extends CertificateHtml {
	
	protected function __construct() {
		switch ($this->category) {
		case 'SC':		//	Slip de Cotización
			$this->get_query_sc();
			break;
		case 'CE':		//	Certificado
			$this->get_query_ce();
			break;
		case 'CP':		//	Certificado Provisional
			$this->get_query_cp();
			break;
		case 'PES':		//	Slip Producto extra
			$this->get_query_pes();
			break;
		case 'PEC':		//	Certificado Producto Extra
			$this->get_query_pec();
			break;
		}
		
		if ($this->error === FALSE) {
			parent::__construct();
		}
	}
	
	//SLIP DE COTIZACION
	private function get_query_sc(){
	    switch($this->product){
		case 'DE':
			if ($this->modality === false) {
				$this->set_query_de_sc();
			} else {
				$this->set_query_de_sc_mo();
			}
		    break;
	    case 'AU':
		    $this->set_query_au_sc();
		    break;
		case 'TRD':
		    $this->set_query_trd_sc();
		    break;
		case 'TRM';
		    $this->set_query_trm_sc();
		    break;
	    }
	}
	
	//SLIP PRODUCTO EXTRA
	private function get_query_pes(){
	    switch($this->product){
			case 'DE':
			    $this->set_query_de_pes();
			    break;
	    }	
	}
	
	//	CERTIFICADOS
	private function get_query_ce() {
		switch ($this->product) {
		case 'DE':
			if ($this->modality === false) {
				$this->set_query_de_em();
			} else {
				$this->set_query_de_em_mo();
			}
			break;
		case 'AU':
			if ($this->modality === false) {
				$this->set_query_au_em();
			} else {
				$this->set_query_au_em_mo();
			}
			break;
		case 'TRD':
			if ($this->modality === false) {
				$this->set_query_trd_em();
			} else {
				$this->set_query_trd_em_mo();
			}
			break;
		case 'TRM':
			if ($this->modality === false) {
				$this->set_query_trm_em();
			} else {
				$this->set_query_trm_em_mo();
			}
			break;
		case 'TH';
			if ($this->modality === false) {
				
			} else {
				$this->set_query_th_em_mo();
			}
		    break;
		}
	}
	
	//CERTIFICADOS PROVISIONALES
	private function get_query_cp(){
		switch($this->product){
		case 'DE':
			$this->set_query_de_cp();
			break;
		case 'AU':
		   $this->set_query_au_cp();
		   break;
		case 'TRD':
		   $this->set_query_trd_cp();
			break;
		case 'TRM':
			$this->set_query_trm_cp();
			break;
		} 
	}
	
	//	CERTIFICADOS PRODUCTO EXTRA
	private function get_query_pec() {
		switch ($this->product) {
		case 'DE':
			$this->set_query_de_em_pec();
			 break;
		}
	}
	
	//QUERYS SLIP DE COTIZACION
	private function set_query_de_sc(){		//DESGRAVAMEN
	  $this->sqlPo="select 
			scc.id_cotizacion,
			scc.id_ef as idef,
			scc.no_cotizacion,
			scc.cobertura,
			scc.monto,
			scc.moneda,
			scc.plazo,
			case scc.tipo_plazo
				when 'Y' then 'años'
				when 'D' then 'dias'
				when 'M' then 'meses'
				when 'W' then 'semanas'
			end as tipoplazo,
			scc.fecha_creacion,
			spc.nombre as producto,
			std.tasa_final,
			sus.email as u_email,
			sus.nombre as u_nombre,
			scia.nombre as compania,
			scia.logo as logo_cia,
			ef.nombre as ef_nombre,
			ef.logo as logo_ef,
			stpc.valor_boliviano as tipo_cambio
		from
			s_de_cot_cabecera as scc
				inner join
			s_producto_cia as spc ON (spc.id_prcia = scc.id_prcia)
				inner join
			s_tasa_de as std ON (std.id_prcia = scc.id_prcia)
				inner join
			s_usuario as sus ON (sus.id_usuario = scc.id_usuario)
			    inner join 
			s_ef_compania as efc on (efc.id_ef=scc.id_ef)
			    inner join 
			s_compania as scia on (scia.id_compania=efc.id_compania)
			    inner join 
			s_entidad_financiera as ef on (ef.id_ef=scc.id_ef)
			    inner join
		    s_ef_compania as sefc on (sefc.id_ef=ef.id_ef and sefc.producto='".$this->product."')
			    inner join
		    s_compania as sc on (sc.id_compania=sefc.id_compania)
			    inner join
			s_tipo_cambio as stpc on (stpc.id_ef=scc.id_ef)					    
		where
			scc.id_cotizacion = '".$this->idc."' and sc.id_compania='".$this->idcia."' and stpc.activado=true
		limit 0 , 1;";
	  //echo $this->sqlPo;
		if($this->rsPo = $this->cx->query($this->sqlPo, MYSQLI_STORE_RESULT)){//DESGRAVAMEN
			if($this->rsPo->num_rows === 1){
				$this->rowPo = $this->rsPo->fetch_array(MYSQLI_ASSOC);
				$this->rsPo->free();
			 
			 	$this->sqlDt="select 
					scd.id_detalle,
					scd.id_cotizacion,
					scd.id_cliente,
					scd.titular,
					case scd.titular
						when 'DD' then '1'
						when 'CC' then '2'
					end as cont_titular,
					scd.porcentaje_credito,
					scd.monto_banca_comunal,
					acc.paterno,
					acc.materno,
					acc.nombre,
					acc.ap_casada,
					acc.fecha_nacimiento,
					acc.lugar_nacimiento,
					if(sdep.codigo = 'PE',
						concat('E-',
								acc.ci,
								' ',
								acc.complemento,
								' ',
								sdep.codigo),
						concat(acc.ci,
								' ',
								acc.complemento,
								' ',
								sdep.codigo)) as ci,
					acc.tipo_documento,
					acc.estado_civil,
					sdto.departamento as lugar_residencia,
					acc.localidad,
					acc.direccion,
					acc.pais,
					sdo.ocupacion,
					acc.desc_ocupacion,
					acc.telefono_domicilio,
					acc.telefono_oficina,
					acc.telefono_celular,
					case acc.genero
						when 'M' then 'Masculino'
						when 'F' then 'Femenino'
					end as genero,
					acc.edad,
					acc.peso,
					acc.estatura,
					sdcr.respuesta,
					sdcr.observacion
				from
					s_de_cot_detalle as scd
						inner join
					s_de_cot_cliente as acc ON (acc.id_cliente = scd.id_cliente)
						inner join
					s_ocupacion as sdo ON (sdo.id_ocupacion = acc.id_ocupacion)
						inner join
					s_departamento as sdep ON (sdep.id_depto = acc.extension)
						left join
					s_departamento as sdto ON (sdto.id_depto = acc.lugar_residencia)
						inner join
					s_de_cot_respuesta as sdcr ON (sdcr.id_detalle = scd.id_detalle)
				where
					scd.id_cotizacion = '".$this->idc."'
				order by scd.id_detalle asc
				;";
			 //echo $this->sqlDt;
			 if($this->rsDt = $this->cx->query($this->sqlDt, MYSQLI_STORE_RESULT)){
				  if($this->rsDt->num_rows > 0){
					  $this->error = FALSE;
				  }else{
				      $this->error = TRUE;
				  }
			 }else{				  
			    $this->error = TRUE; 
			 }
		  }else{ 
		     $this->error = TRUE;
		  }
	  }else{
		 $this->error = TRUE; 
	  }				  	
	}
	
	// SLIP DE COTIZACION MODALIDADES
	private function set_query_de_sc_mo () {
		$this->sqlPo = "select 
			scc.id_cotizacion,
			scc.id_ef as idef,
			scc.no_cotizacion,
			scc.cobertura,
			scc.monto,
			scc.moneda,
			(if(scc.moneda='BS',scc.monto,(scc.monto*stpc.valor_boliviano))) as monto_def,
			scc.plazo,
			case scc.tipo_plazo
				when 'Y' then 'años'
				when 'D' then 'dias'
				when 'M' then 'meses'
				when 'W' then 'semanas'
			end as tipoplazo,
			spc.nombre as producto,
			std.tasa_final,
			sus.email as u_email,
			sus.nombre as u_nombre,
			scia.nombre as compania,
			scia.logo as logo_cia,
			ef.nombre as ef_nombre,
			ef.logo as logo_ef,
			stpc.valor_boliviano as tipo_cambio
		from
			s_de_cot_cabecera as scc
				inner join
			s_producto_cia as spc ON (spc.id_prcia = scc.id_prcia)
				inner join
			s_tasa_de as std ON (std.id_prcia = scc.id_prcia)
				inner join
			s_usuario as sus ON (sus.id_usuario = scc.id_usuario)
			    inner join 
			s_ef_compania as efc on (efc.id_ef=scc.id_ef)
			    inner join 
			s_compania as scia on (scia.id_compania=efc.id_compania)
			    inner join 
			s_entidad_financiera as ef on (ef.id_ef=scc.id_ef)
			    inner join
		    s_ef_compania as sefc on (sefc.id_ef=ef.id_ef and sefc.producto='".$this->product."')
			    inner join
		    s_compania as sc on (sc.id_compania=sefc.id_compania)
			    inner join
			s_tipo_cambio as stpc on (stpc.id_ef=scc.id_ef)					    
		where
			scc.id_cotizacion = '".$this->idc."' and sc.id_compania='".$this->idcia."'
		limit 0 , 1 ;";
		//echo $this->sqlPo;
		if($this->rsPo = $this->cx->query($this->sqlPo, MYSQLI_STORE_RESULT)){
			if($this->rsPo->num_rows === 1){
				$this->rowPo = $this->rsPo->fetch_array(MYSQLI_ASSOC);
				$this->rsPo->free();
				
				$this->sqlDt = "select 
					scd.id_detalle,
					scd.id_cotizacion,
					scd.id_cliente,
					scd.titular,
					case scd.titular
						when 'DD' then '1'
						when 'CC' then '2'
					end as cont_titular,
					acc.paterno,
					acc.materno,
					acc.nombre,
					acc.ap_casada,
					acc.fecha_nacimiento,
					acc.lugar_nacimiento,
					if(sdep.codigo = 'PE',
						concat('E-',
								acc.ci,
								' ',
								acc.complemento,
								' ',
								sdep.codigo),
						concat(acc.ci,
								' ',
								acc.complemento,
								' ',
								sdep.codigo)) as ci,
					acc.tipo_documento,
					acc.estado_civil,
					sdto.departamento as lugar_residencia,
					acc.localidad,
					acc.direccion,
					acc.pais,
					sdo.ocupacion,
					acc.desc_ocupacion,
					acc.telefono_domicilio,
					acc.telefono_oficina,
					acc.telefono_celular,
					case acc.genero
						when 'M' then 'Masculino'
						when 'F' then 'Femenino'
					end as genero,
					acc.edad,
					acc.peso,
					acc.estatura,
					sdcr.respuesta,
					sdcr.observacion
				from
					s_de_cot_detalle as scd
						inner join
					s_de_cot_cliente as acc ON (acc.id_cliente = scd.id_cliente)
						inner join
					s_ocupacion as sdo ON (sdo.id_ocupacion = acc.id_ocupacion)
						inner join
					s_departamento as sdep ON (sdep.id_depto = acc.extension)
						inner join
					s_departamento as sdto ON (sdto.id_depto = acc.lugar_residencia)
						inner join
					s_de_cot_respuesta as sdcr ON (sdcr.id_detalle = scd.id_detalle)
				where
					scd.id_cotizacion = '".$this->idc."' ;";
				
			 	//echo $this->sqlDt;
			 	if($this->rsDt = $this->cx->query($this->sqlDt, MYSQLI_STORE_RESULT)){
					if($this->rsDt->num_rows > 0){
			 			$this->error = FALSE;
			 		}else{
			 			$this->error = TRUE;
			 		}
			 	}else{
			 		$this->error = TRUE;
			 	}
			}else{
				$this->error = TRUE;
			}
		}else{
			$this->error = TRUE;
		}
	}
	
	//QUERYS SLIP PRODUCTO EXTRA
	private function set_query_de_pes(){		//DESGRAVAMEN
		$this->sqlPo = "select 
		    sdc.id_cotizacion,
		    sef.id_ef as idef,
		    sdc.no_cotizacion,
		    sdc.cobertura,
		    sdc.monto,
		    sdc.moneda,
		    sdc.plazo,
		    case sdc.tipo_plazo
		        when 'Y' then 'años'
		        when 'D' then 'dias'
		        when 'M' then 'meses'
		        when 'W' then 'semanas'
		    end as tipoplazo,
		    spc.nombre as producto,
		    st.tasa_final,
		    su.email as u_email,
		    su.nombre as u_nombre,
		    scia.nombre as compania,
		    scia.logo as logo_cia,
		    sef.nombre as ef_nombre,
		    sef.logo as logo_ef,
		    spe.pr_hospitalario,
		    spe.pr_vida,
		    spe.pr_cesante,
		    spe.pr_prima
		from
		    s_de_cot_cabecera as sdc
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sdc.id_ef)
		        inner join
		    s_ef_compania as sec ON (sec.id_ef = sef.id_ef)
		        inner join
		    s_compania as scia ON (scia.id_compania = sec.id_compania)
		        inner join
		    s_producto_cia as spc ON (spc.id_prcia = sdc.id_prcia)
		        inner join
		    s_tasa_de as st ON (st.id_prcia = spc.id_prcia)
		        inner join
		    s_usuario as su ON (su.id_usuario = sdc.id_usuario)
		";
		if ($this->extra === NULL) {
			$this->sqlPo .= "inner join
    		s_de_producto_extra as spe ON (spe.id_pr_extra = sdc.id_pr_extra)
			where
			    sdc.id_cotizacion = '".$this->idc."'
			        and scia.id_compania = '".$this->idcia."'
    		";
		} else {
			$this->sqlPo .= "left join
		    s_de_producto_extra as spe ON (spe.id_ef_cia = sec.id_ef_cia)
			where
			    sdc.id_cotizacion = '".$this->idc."'
			        and scia.id_compania = '".$this->idcia."'
			        and spe.id_pr_extra = '".$this->cx->real_escape_string(trim($this->extra))."'
		    ";
		}
		$this->sqlPo .="
		limit 0 , 1
		;";
		//echo $this->sqlPo;
		if($this->rsPo = $this->cx->query($this->sqlPo, MYSQLI_STORE_RESULT)){	//DESGRAVAMEN
			if($this->rsPo->num_rows === 1){
				$this->rowPo = $this->rsPo->fetch_array(MYSQLI_ASSOC);
				$this->rsPo->free();
				
				$this->sqlDt="select 
				    scd.id_detalle,
				    scd.id_cotizacion,
				    scd.id_cliente,
				    scd.titular,
				    case scd.titular
				        when 'DD' then '1'
				        when 'CC' then '2'
				    end as cont_titular,
				    acc.paterno,
				    acc.materno,
				    acc.nombre,
				    acc.ap_casada,
				    acc.fecha_nacimiento,
				    acc.lugar_nacimiento,
				    if(sdep.codigo = 'PE',
				        concat('E-',
				                acc.ci,
				                ' ',
				                acc.complemento,
				                ' ',
				                sdep.codigo),
				        concat(acc.ci,
				                ' ',
				                acc.complemento,
				                ' ',
				                sdep.codigo)) as ci,
				    acc.tipo_documento,
				    acc.estado_civil,
				    sdto.departamento as lugar_residencia,
				    acc.localidad,
				    acc.direccion,
				    acc.pais,
				    sdo.ocupacion,
				    acc.desc_ocupacion,
				    acc.telefono_domicilio,
				    acc.telefono_oficina,
				    acc.telefono_celular,
				    case acc.genero
				        when 'M' then 'Masculino'
				        when 'F' then 'Femenino'
				    end as genero,
				    acc.edad,
				    acc.peso,
				    acc.estatura,
				    sdcr.respuesta,
				    sdcr.observacion
				from
				    s_de_cot_detalle as scd
				        inner join
				    s_de_cot_cliente as acc ON (acc.id_cliente = scd.id_cliente)
				        inner join
				    s_ocupacion as sdo ON (sdo.id_ocupacion = acc.id_ocupacion)
				        inner join
				    s_departamento as sdep ON (sdep.id_depto = acc.extension)
				        inner join
				    s_departamento as sdto ON (sdto.id_depto = acc.lugar_residencia)
				        inner join
				    s_de_cot_respuesta as sdcr ON (sdcr.id_detalle = scd.id_detalle)
				where
				    scd.id_cotizacion = '".$this->idc."'
				order by scd.id_detalle asc
				;";
				//echo $this->sqlDt;
				if ($this->rsDt = $this->cx->query($this->sqlDt, MYSQLI_STORE_RESULT)){
					if ($this->rsDt->num_rows > 0){
						$this->error = false;
					} else { $this->error = true; }
				} else { $this->error = true; }
			} else { $this->error = TRUE; }
		}else{ $this->error = TRUE; }
	}
	
	//QUERYS CERTIFICADOS EMISION
	private function set_query_de_em() {	//DESGRAVAMEN
		$this->sqlPo = 'select 
			sde.id_emision,
			sde.no_emision,
			sde.id_cotizacion,
			sde.no_emision,
			sde.no_poliza,
			sdc.no_cotizacion,
			sef.id_ef as idef,
			sef.nombre as ef_nombre,
			sc.nombre as compania,
			sc.logo as logo_cia,
			sef.logo as logo_ef,
			spc.id_prcia,
			spc.nombre as tipo_credito,
			count(sdd.id_cliente) as num_cliente,
			sde.no_operacion,
			sde.prefijo,
			sde.cobertura,
			sde.monto_solicitado,
			sde.moneda,
			sde.monto_deudor as saldo_deudor,
			sde.monto_codeudor as saldo_codeudor,
			sde.cumulo_deudor,
			sde.cumulo_codeudor,
			@monto_s := (case sde.moneda
				when "BS" then (sde.monto_solicitado * sdd.porcentaje_credito) / 100
				when "USD" then (((sde.monto_solicitado * 7) * sdd.porcentaje_credito) / 100)
			end) as monto_sol_bs,
			(case sde.tipo_plazo
				when "Y" then "años"
				when "D" then "dias"
				when "M" then "meses"
				when "W" then "semanas"
			end) as tipo_plazo,
			sde.plazo,
			su.id_usuario,
			sdep.departamento as u_departamento,
			su.nombre as u_nombre,
			su.email as u_email,
			su.fono_agencia,
			sde.fecha_creacion,
			sde.anulado,
			sdep.departamento,
			sde.fecha_anulado,
			sde.motivo_anulado,
			sde.emitir,
			sde.fecha_emision,
			sde.facultativo,
			sde.motivo_facultativo,
			sde.prima_total,
			sdf.aprobado,
			sdf.tasa_recargo,
			sdf.porcentaje_recargo,
			sdf.tasa_actual,
			if(sde.facultativo = true,
				sdf.tasa_final,
				sde.tasa) as tasa_final,
			sdf.observacion,
			sde.id_certificado
		from 
			s_de_em_cabecera as sde
				inner join
			s_de_cot_cabecera as sdc ON (sdc.id_cotizacion = sde.id_cotizacion)
				inner join
			s_de_em_detalle as sdd ON (sdd.id_emision = sde.id_emision)
				left join
			s_de_facultativo as sdf ON (sdf.id_emision = sde.id_emision)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = sde.id_ef)
				inner join
			s_compania as sc ON (sc.id_compania = sde.id_compania)
				inner join
			s_usuario as su ON (su.id_usuario = sde.id_usuario)
				inner join
			s_departamento as sdep ON (sdep.id_depto = su.id_depto)
				inner join
			s_producto_cia as spc ON (spc.id_prcia = sde.id_prcia)
		where sde.id_emision = "' . $this->ide . '" 
		;';
		//echo $this->sqlPo;
		if (($this->rsPo = $this->cx->query($this->sqlPo, MYSQLI_STORE_RESULT))) {
			if ($this->rsPo->num_rows === 1) {
				$this->rowPo = $this->rsPo->fetch_array(MYSQLI_ASSOC);
				$this->rsPo->free();
				
				$this->sqlDt = 'select 
					scl.id_cliente,
					scl.paterno,
					scl.materno,
					scl.nombre,
					scl.ap_casada,
					(if(scl.ap_casada="",
					    concat(scl.nombre," ",scl.paterno," ",scl.materno),
						 concat(scl.nombre," ",scl.paterno," de ",scl.ap_casada)
						 )) as nombre_completo,
					scl.genero,
					scl.fecha_nacimiento,
					scl.lugar_nacimiento,
					scl.tipo_documento,
					scl.ci as dni,
					scl.complemento,
					sdep1.codigo as extension,
					scl.estado_civil,
					sdep2.departamento as lugar_residencia,
					scl.localidad,
					scl.avenida,
					concat(scl.direccion,
						" ",
						(if(scl.no_domicilio = "",
							"S/N",
							scl.no_domicilio))) as direccion,
					scl.direccion_laboral,
					scl.pais,
					so.ocupacion,
					scl.desc_ocupacion,
					scl.telefono_domicilio,
					scl.telefono_oficina,
					scl.telefono_celular,
					scl.email,
					scl.peso,
					scl.estatura,
					scl.edad,
					sdd.porcentaje_credito,
					sdr.respuesta,
					sdr.observacion,
					sdd.monto_banca_comunal as monto_bc,
					sdd.saldo,
					sdd.cumulo,
					sdd.porcentaje_credito, 
					sdd.tasa, 
					sdd.facultativo, 
					sdd.motivo_facultativo, 
					sdd.aprobado, 
					sdd.detalle_f, 
					sdd.detalle_p, 
					sdd.titular
				from 
					s_cliente as scl
						inner join 
					s_de_em_detalle as sdd ON (sdd.id_cliente = scl.id_cliente)
						inner join
					s_de_em_respuesta as sdr ON (sdr.id_detalle = sdd.id_detalle)
						inner join
					s_departamento as sdep1 ON (sdep1.id_depto = scl.extension)
						left join
					s_departamento as sdep2 ON (sdep2.id_depto = scl.lugar_residencia)
						inner join
					s_ocupacion as so ON (so.id_ocupacion = scl.id_ocupacion)
				where
					sdd.id_emision = "' . $this->rowPo['id_emision'] . '" 
				order by sdd.id_detalle asc
				;';

				//echo $this->sqlDt;
				if (($this->rsDt = $this->cx->query($this->sqlDt, MYSQLI_STORE_RESULT))) {
					if ($this->rsDt->num_rows > 0) {
						$this->error = FALSE;
					} else { $this->error = TRUE; }
				} else { $this->error = TRUE; }
			} else { $this->error = TRUE; }
		} else { $this->error = TRUE; }
	}
	
	//CERTIFICADOS EMISION MODALIDAD 
	private function set_query_de_em_mo () {
		$this->sqlPo = "select 
		    sde.id_emision,
		    sde.no_emision,
		    sde.id_cotizacion,
		    sde.no_operacion,
		    sde.prefijo,
		    sde.prefix,
		    sde.cobertura,
		    sde.id_prcia,
		    spr.nombre as tipo_credito,
		    sde.monto_solicitado,
			(if(sde.moneda='USD',(sde.monto_solicitado*stc.valor_boliviano),sde.monto_solicitado)) as monto_conver,
		    (case sde.moneda
		        when 'BS' then 'Bs.'
		        when 'USD' then 'Sus.'
		    end) as moneda,
			(case sde.moneda
		        when 'BS' then 'expresado en bolivianos'
		        when 'USD' then 'expresado en dolares americanos'
		    end) as expresado_moneda,
			(case sde.moneda
		        when 'BS' then (10000*stc.valor_boliviano)
		        when 'USD' then 10000
		    end) as limite_maximo,
		    (if(sde.moneda='BS',sde.cumulo_deudor,(sde.cumulo_deudor/stc.valor_boliviano))) as cumulo_deudor,
		    (if(sde.moneda='BS',sde.cumulo_codeudor,(sde.cumulo_codeudor/stc.valor_boliviano))) as cumulo_codeudor,
		    sde.id_tc,
		    (case sde.tipo_plazo
		        when 'Y' then concat(sde.plazo, ' ', 'años')
		        when 'D' then concat(sde.plazo, ' ', 'dias')
		        when 'M' then concat(sde.plazo, ' ', 'meses')
		        when 'W' then concat(sde.plazo, ' ', 'semanas')
		    end) as tipo_plazo,
		    sde.id_usuario,
		    sdep.departamento as user_departamento,
		    su.nombre as u_nombre,
		    su.fono_agencia as fono_sucursal,
		    su.email as u_email,
		    sde.fecha_creacion,
		    sde.anulado,
		    sde.and_usuario,
		    sde.fecha_anulado,
		    sde.motivo_anulado,
		    sde.emitir,
		    sde.fecha_emision,
		    sde.id_compania,
		    sde.facultativo,
		    sde.motivo_facultativo,
		    sde.prima_total,
		    count(sdd.id_cliente) as num_cliente,
		    if(count(sdd.id_cliente) = 1,
		        'Individual',
		        'Mancomuno') as tipo_seguro,
		    sdf.aprobado,
		    sdf.tasa_recargo,
		    sdf.porcentaje_recargo,
		    sdf.tasa_actual,
		    if(sde.facultativo = 1,
		        sdf.tasa_final,
		        sde.tasa) as tasa_final,
		    sdf.observacion,
		    (case sde.no_copia
		        when 0 then 'ORIGINAL'
		        else 'COPIA No. '
		    end) as text_copia,
		    (case sde.no_copia
		        when 0 then ''
		        else (sde.no_copia + 1)
		    end) as num_copia,
		    sde.facultativo,
		    sdc.no_cotizacion,
		    sde.id_ef as idef,
		    sef.nombre as ef_nombre,
		    scia.nombre as compania,
			stc.valor_boliviano as tipo_cambio
		from
		    s_de_em_cabecera as sde
		        inner join
		    s_de_em_detalle as sdd ON (sdd.id_emision = sde.id_emision)
		        inner join
		    s_de_cot_cabecera as sdc ON (sdc.id_cotizacion = sde.id_cotizacion)
		        left join
		    s_de_facultativo as sdf ON (sdf.id_emision = sde.id_emision)
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sde.id_ef)
		        inner join
		    s_compania as scia ON (scia.id_compania = sde.id_compania)
		        inner join
		    s_producto_cia as spr ON (spr.id_prcia = sde.id_prcia)
		        inner join
		    s_usuario as su ON (su.id_usuario = sde.id_usuario)
		        inner join
		    s_departamento as sdep ON (sdep.id_depto = su.id_depto)
		        inner join
		    s_tipo_cambio as stc ON (stc.id_ef = sef.id_ef)
		where
			sde.id_emision = '" . $this->ide . "' ;";
		
		if (($this->rsPo = $this->cx->query($this->sqlPo, MYSQLI_STORE_RESULT)) !== false) {
			if ($this->rsPo->num_rows === 1) {
				$this->rowPo = $this->rsPo->fetch_array(MYSQLI_ASSOC);
				$this->rsPo->free();
				
				$this->sqlDt = "select 
				    scl.id_cliente,
				    scl.paterno,
				    scl.materno,
				    scl.nombre,
				    scl.ap_casada,
				    (if(scl.genero = 'M',
				        concat(scl.paterno, ' ', scl.materno),
				        if(scl.ap_casada = '',
				            concat(scl.paterno, ' ', scl.materno),
				            concat(scl.paterno, ' DE ', scl.ap_casada)))) as apellidos,
				    date_format(scl.fecha_nacimiento, '%d-%m-%Y') as fecha_nacimiento,
				    scl.lugar_nacimiento,
				    concat(scl.ci, ' ', scl.complemento) as ci_document,
				    (case sdep.codigo
				        when 'LP' then 'La Paz'
				        when 'CB' then 'Cochabamba'
				        when 'OR' then 'Oruro'
				        when 'PT' then 'Potosi'
				        when 'CH' then 'Chuquisaca'
				        when 'TJ' then 'Tarija'
				        when 'PA' then 'Pando'
				        when 'BE' then 'Beni'
				        when 'SC' then 'Santa Cruz'
				    end) as expedido,
				    scl.peso,
				    scl.estatura,
				    scl.edad,
				    scl.estado_civil,
				    concat(scl.direccion,
				            ' ',
				            (if(scl.no_domicilio = '',
				                'S/N',
				                scl.no_domicilio))) as direccion_domicilio,
				    scl.telefono_domicilio,
				    scl.telefono_oficina,
				    scl.telefono_celular,
				    scl.direccion_laboral,
				    sdo.ocupacion,
				    sder.respuesta,
				    sder.observacion,
				    sdd.titular as titular_txt,
				    (case sdd.titular
				        when 'DD' then 1
				        when 'CC' then 2
				    end) as titular_num,
				    sdd.id_detalle,
					dp.departamento as lugar_residencia
				from
				    s_de_em_cabecera as sde
				        inner join
				    s_de_em_detalle as sdd ON (sdd.id_emision = sde.id_emision)
				        inner join
				    s_cliente as scl ON (scl.id_cliente = sdd.id_cliente)
				        inner join
				    s_departamento as sdep ON (sdep.id_depto = scl.extension)
				        inner join
				    s_de_em_respuesta as sder ON (sder.id_detalle = sdd.id_detalle)
				        inner join
				    s_ocupacion as sdo ON (sdo.id_ocupacion = scl.id_ocupacion)
					    inner join
				   	s_departamento as dp on (dp.id_depto = scl.lugar_residencia)
			  	where
				  	sde.id_emision = '" . $this->rowPo['id_emision'] . "';";
				
				if (($this->rsDt = $this->cx->query($this->sqlDt, MYSQLI_STORE_RESULT)) !== false) {
					if ($this->rsDt->num_rows > 0) {
						$this->error = FALSE;
					} else { $this->error = TRUE; }
				} else { $this->error = TRUE; }
			} else { $this->error = TRUE; }
		} else { $this->error = TRUE; }
	}
	
	//QUERYS CERTIFICADOS PRODUCTO EXTRA
	private function set_query_de_em_pec () {	//DESGRAVAMEN
		$this->sqlPo = "select 
		    sdec.id_emision,
		    sdec.no_emision,
		    sdec.id_cotizacion,
		    sdec.no_operacion,
		    sdec.prefijo,
		    sdec.cobertura,
		    sdec.id_prcia,
		    (case sdec.id_prcia
		        when 4 then 'X---'
		        when 3 then '-X--'
		        when 2 then '--X-'
		        when 5 then '---X'
		    end) as tipo_credito,
		    sdec.monto_solicitado,
		    (case sdec.moneda
		        when 'USD' then 'X-'
		        when 'BS' then '-X'
		    end) as moneda,
		    sdec.moneda as moneda_cod,
		    sdec.monto_deudor as saldo_deudor,
		    sdec.monto_codeudor,
		    sdec.cumulo_deudor,
		    @monto_s:=(case sdec.moneda
		        when 'BS' then (sdec.monto_solicitado * sded.porcentaje_credito) / 100
		        when 'USD' then (((sdec.monto_solicitado * 7) * sded.porcentaje_credito) / 100)
		    end) as monto_sol_bs,
		    (case sdec.operacion
		        when 'PU' then @monto_s
		        when 'AD' then sdec.monto_deudor + @monto_s
		        when 'LC' then sdec.cumulo_deudor
		    end) as monto_actual_acumulado,
		    sdec.cumulo_codeudor,
		    sdec.id_tc,
		    (case sdec.tipo_plazo
		        when 'Y' then concat(sdec.plazo, ' ', 'años')
		        when 'D' then concat(sdec.plazo, ' ', 'dias')
		        when 'M' then concat(sdec.plazo, ' ', 'meses')
		        when 'W' then concat(sdec.plazo, ' ', 'semanas')
		    end) as tipo_plazo,
		    sdec.id_usuario,
		    depto.departamento as user_departamento,
		    su.nombre as u_nombre,
		    su.fono_agencia as fono_sucursal,
		    su.email as u_email,
		    sdec.fecha_creacion,
		    sdec.anulado,
		    sdec.and_usuario,
		    sdep.departamento,
		    sdec.fecha_anulado,
		    sdec.motivo_anulado,
		    sdec.emitir,
		    sdec.fecha_emision,
		    sdec.id_compania,
		    sp.no_poliza,
		    (case sdec.operacion
		        when 'PU' then 'X--'
		        when 'AD' then '-X-'
		        when 'LC' then '--X'
		    end) as tipo_operacion,
		    sdec.facultativo,
		    sdec.motivo_facultativo,
		    sdec.prima_total,
		    count(sded.id_cliente) as num_cliente,
		    if(count(sded.id_cliente) = 1,
		        'X-',
		        '-X') as tipo_seguro,
		    sdf.aprobado,
		    sdf.tasa_recargo,
		    sdf.porcentaje_recargo,
		    sdf.tasa_actual,
		    if(sdec.facultativo = 1,
		        sdf.tasa_final,
		        sdec.tasa) as tasa_final,
		    sdf.observacion,
		    (select 
		            max(@anulado:=concat(@anulado, ' DE-', id_emision, ', ')) as pa
		        from
		            s_de_em_cabecera
		        where
		            anulado = 1
		                and id_cotizacion = sdec.id_cotizacion) as poliza_anulada,
		    (case sdec.no_copia
		        when 0 then 'ORIGINAL'
		        else 'COPIA No. '
		    end) as text_copia,
		    (case sdec.no_copia
		        when 0 then ''
		        else (sdec.no_copia + 1)
		    end) as num_copia,
		    sdec.facultativo,
		    (if(@monto_s > 35000, 1, 0)) as verifica_vida,
		    sdcc.no_cotizacion,
		    sdec.id_ef as idef,
		    sef.nombre as ef_nombre,
		    sc.nombre as compania,
		    spe.pr_hospitalario,
			spe.pr_vida,
			spe.pr_cesante,
			spe.pr_prima
		from
		    s_de_em_cabecera as sdec
		        inner join
		    s_de_em_detalle as sded ON (sded.id_emision = sdec.id_emision)
		        inner join
		    s_de_cot_cabecera as sdcc ON (sdcc.id_cotizacion = sdec.id_cotizacion)
		        inner join
		    s_compania as sc ON (sc.id_compania = sdec.id_compania)
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sdec.id_ef)
		        inner join
		    s_usuario as su ON (su.id_usuario = sdec.id_usuario)
		        inner join
		    s_departamento as depto ON (depto.id_depto = su.id_depto)
		        inner join
		    s_departamento as sdep ON (sdep.id_depto = su.id_depto)
		        left join
		    s_de_facultativo as sdf ON (sdf.id_emision = sdec.id_emision)
		        inner join
		    s_poliza as sp ON (sp.id_poliza = sdec.id_poliza)
		        inner join
		    s_de_producto_extra as spe ON (spe.id_pr_extra = sdcc.id_pr_extra)
		where
		    sdec.id_emision = '".$this->ide."'
		;";
		//echo $this->sqlPo;
		if (($this->rsPo = $this->cx->query($this->sqlPo, MYSQLI_STORE_RESULT))) {
			if ($this->rsPo->num_rows === 1) {
				$this->rowPo = $this->rsPo->fetch_array(MYSQLI_ASSOC);
				$this->rsPo->free();
				
				$this->sqlDt = "select 
				    sc.id_cliente,
				    sded.id_detalle,
				    sc.paterno,
				    sc.materno,
				    sc.nombre,
				    sc.ap_casada,
				    (case sc.genero
				        when 'M' then 'x-'
				        when 'F' then '-x'
				    end) as sexo,
				    concat('Dia: ',
				            day(sc.fecha_nacimiento),
				            ' Mes: ',
				            month(sc.fecha_nacimiento),
				            ' Año: ',
				            year(sc.fecha_nacimiento)) as fecha_nacimiento,
				    sc.lugar_nacimiento,
				    if(sdep.codigo = 'PE',
				        concat(sc.tipo_documento,
				                ' ',
				                'E-',
				                sc.ci,
				                ' ',
				                sc.complemento),
				        concat(sc.tipo_documento,
				                ' ',
				                sc.ci,
				                ' ',
				                sc.complemento,
				                ' ',
				                sdep.codigo)) as ci,
				    sc.estado_civil,
				    sdto.departamento as lugar_residencia,
				    sc.localidad,
				    sc.avenida,
				    concat(sc.direccion,
				            ' ',
				            (if(sc.no_domicilio = '',
				                'S/N',
				                sc.no_domicilio))) as direccion,
				    sc.direccion_laboral,
				    sc.pais,
				    sdo.ocupacion,
				    sc.desc_ocupacion,
				    concat((if(sc.telefono_domicilio != '',
				                sc.telefono_domicilio,
				                '')),
				            ' ',
				            (if(sc.telefono_celular != '',
				                sc.telefono_celular,
				                '')),
				            ' ',
				            (if(sc.telefono_oficina != '',
				                sc.telefono_oficina,
				                ''))) as telefono,
				    sc.email,
				    sc.peso,
				    sc.estatura,
				    sc.edad,
				    sded.porcentaje_credito,
				    (case sc.mano
				        when 'DE' then 'DERECHA'
				        when 'IZ' then 'IZQUIERDA'
				    end) as mano_utilizada,
				    sder.respuesta,
				    sder.observacion,
				    sded.titular as titular_txt,
				    (case sded.titular
				        when 'DD' then 1
				        when 'CC' then 2
				    end) as titular_num
				from
				    s_de_em_cabecera as sde
				        inner join
				    s_de_em_detalle as sded ON (sded.id_emision = sde.id_emision)
				        inner join
				    s_cliente as sc ON (sc.id_cliente = sded.id_cliente)
				        inner join
				    s_departamento as sdep ON (sdep.id_depto = sc.extension)
				        inner join
				    s_departamento as sdto ON (sdto.id_depto = sc.lugar_residencia)
				        inner join
				    s_ocupacion as sdo ON (sdo.id_ocupacion = sc.id_ocupacion)
				        inner join
				    s_de_em_respuesta as sder ON (sder.id_detalle = sded.id_detalle)
				where
				    sded.id_emision = '".$this->ide."'
				order by sded.id_detalle asc
				; ";
				//echo $this->sqlDt;
				if (($this->rsDt = $this->cx->query($this->sqlDt, MYSQLI_STORE_RESULT))) {
					if ($this->rsDt->num_rows > 0) {
						$this->error = false;
					} else { $this->error = true; }
				} else { $this->error = false; }
			} else { $this->error = true; }
		} else { $this->error = true; }
	}
	
	
	
	//CERTIFICADO SLIP AUTOMOTORES
	private function set_query_au_sc (){
	    $this->sqlPo = "select 
						  aucc.id_cotizacion,
						  aucc.no_cotizacion,
						  aucc.id_ef as idef,
						  aucc.id_cliente,
						  aucc.certificado_provisional,
						  aucc.garantia,
						  aucc.tipo,
						  aucc.ini_vigencia,
						  aucc.fin_vigencia,
						  aucc.tipo_plazo as tip_plz_code,
						  aucc.plazo as plz_anio,
						  concat(aucc.plazo,
								  ' ',
								  case aucc.tipo_plazo
									  when 'Y' then 'Años'
									  when 'D' then 'Dias'
									  when 'M' then 'Meses'
									  when 'W' then 'Semanas'
								  end) as tipo_plazo,
						  @conversor:=(if(aucc.tipo_plazo = 'M',
							  ROUND(aucc.plazo / 12),
							  if(aucc.tipo_plazo = 'D',
								  ROUND(aucc.plazo / 365),
								  if(aucc.tipo_plazo = 'W',
									  ROUND(aucc.plazo / 52),
									  aucc.plazo)))) as resu_conversor,
						  @newanio:=(if(@conversor > 0, @conversor, 1)) as cant_plazo,		  
						  aucc.fecha_creacion,
						  aucc.id_usuario,
						  aucc.cuota,
						  aucc.prima_total,
						  sfp.forma_pago,
						  sfp.codigo as frm_pago_code,
						  sc.nombre as compania,
						  sc.logo as logo_cia,
						  sc.id_compania,
						  sef.nombre as ef_nombre,
						  sef.logo as logo_ef,
						  su.nombre as u_nombre,
						  su.email as u_email,
						  case clt.tipo 
						     when 0 then 'titular'
						     when 1 then 'empresa'
						  end as tipo_cliente,
						  clt.razon_social,
						  clt.paterno,
						  clt.materno,
						  clt.nombre,
						  clt.ap_casada,
						  concat(clt.ci,
								  ' ',
								  clt.complemento,
								  ' ',
								  sd.codigo) as ci,
						  case clt.genero
							 when 'M' then 'Masculino'
							 when 'F' then 'Femenino'
						  end as genero,
						  clt.fecha_nacimiento,
						  clt.telefono_domicilio,
						  clt.telefono_celular,
						  clt.email,
						  sefc.id_ef_cia,
						  sc.id_compania,
                          sh.monto_facultativo,
                          sh.anio as anio_max
					  from
						  s_au_cot_cabecera as aucc
							  inner join
						  s_forma_pago as sfp ON (sfp.id_forma_pago = aucc.id_forma_pago)
							  inner join
						  s_entidad_financiera as sef ON (sef.id_ef = aucc.id_ef)
							  inner join
						  s_ef_compania as sefc ON (sefc.id_ef = sef.id_ef and sefc.producto='".$this->product."')
							  inner join
						  s_compania as sc ON (sc.id_compania = sefc.id_compania)
							  inner join
						  s_usuario as su ON (su.id_usuario = aucc.id_usuario)
							  inner join
						  s_au_cot_cliente as clt ON (clt.id_cliente = aucc.id_cliente)
							  inner join
						  s_departamento as sd ON (sd.id_depto = clt.extension)
							  inner join
						  s_sgc_home as sh on (sh.id_ef=aucc.id_ef and sh.producto='".$this->product."')
					  where
						  aucc.id_cotizacion = '".$this->idc."'
							  and sc.id_compania = '".$this->idcia."';";
		//echo $this->sqlPo;					  			  
		if($this->rsPo = $this->cx->query($this->sqlPo, MYSQLI_STORE_RESULT)){
			if($this->rsPo->num_rows === 1){
				$this->rowPo=$this->rsPo->fetch_array(MYSQLI_ASSOC);
				$this->rsPo->free();
				
				$this->sqlDt="select
							  auc.id_vehiculo,
							  auc.id_cotizacion,
							  auc.categoria as category,
							  case auc.categoria
								when 'RAC' then 'Renta Car'
								when 'OTH' then 'Otros'
							  end as categoria,
							  auc.anio,
							  auc.placa,
							  auc.uso,
							  auc.traccion,
							  auc.km,
							  auc.valor_asegurado,
							  auc.facultativo,
							  autv.vehiculo,
							  aumr.marca,
							  aumod.modelo
							from
							  s_au_cot_detalle as auc
							  inner join s_au_tipo_vehiculo as autv on (autv.id_tipo_vh=auc.id_tipo_vh)
							  inner join s_au_marca as aumr on (aumr.id_marca=auc.id_marca)
							  inner join s_au_modelo as aumod on (aumod.id_modelo=auc.id_modelo)
							where
							  auc.id_cotizacion='".$this->idc."';";
				if($this->rsDt = $this->cx->query($this->sqlDt, MYSQLI_STORE_RESULT)){
					if ($this->rsDt->num_rows > 0) {
						$this->error = FALSE;
					}else{ 
					   $this->error = TRUE; 
					}
				}else{
				  $this->error=TRUE;
				}
			}else{
				$this->error=TRUE;
			}
		}else{
		   $this->error=TRUE;
		}					   	
	}
	
	//EMISION CERTIFICADO AUTOMOTORES
	private function set_query_au_em () {		// Automotores
		$this->sqlPo = "select
			  auec.id_emision,
			  auec.no_emision,
			  auec.id_ef as idef,
			  auec.id_cotizacion,
			  auec.id_cliente,
			  auec.no_operacion,
			  auec.prefijo,
			  auec.ini_vigencia,
			  auec.fin_vigencia,
			  auec.id_forma_pago,
			  auec.plazo,
			  auec.tipo_plazo,
			  auec.fecha_creacion,
			  auec.id_usuario,
			  auec.anulado,
			  auec.fecha_anulado,
			  auec.fecha_emision,
			  auec.id_compania,
			  auec.id_poliza,
			  auec.no_copia,
			  auec.facultativo,
			  auec.prima_total,
			  sclie.tipo as cl_tipo,
			  case sclie.tipo
				when 0 then 'Natural'
				when 1 then 'Juridico'
			  end tipo_cliente,
			  sclie.razon_social as cl_razon_social, 
			  sclie.paterno,
			  sclie.materno,
			  sclie.nombre,
			  sclie.ap_casada,
			  sclie.avenida,
			  sclie.direccion,
			  sclie.no_domicilio,
			  sclie.localidad,
			  sclie.telefono_domicilio,
			  sclie.telefono_oficina,
			  sclie.telefono_celular,
			  sclie.ci,
			  sef.nombre as ef_nombre,
			  sef.logo as logo_ef,
			  sc.nombre as compania,
			  sc.logo as logo_cia,
			  su.nombre as u_nombre,
			  su.email as u_email,
			  sd.departamento,
              aucot.no_cotizacion,
			  (case auec.no_copia
				  when 0 then 'ORIGINAL'
				  else 'COPIA No. '
			  end) as text_copia,
			  (case auec.no_copia
				  when 0 then ''
				  else (auec.no_copia + 1)
			  end) as num_copia,
			  auec.emitir  
			from
			  s_au_em_cabecera as auec
			  inner join s_cliente as sclie on (sclie.id_cliente=auec.id_cliente)
			  inner join s_entidad_financiera as sef on (sef.id_ef=auec.id_ef)
			  inner join s_compania as sc on (sc.id_compania=auec.id_compania)
			  inner join s_usuario as su on (su.id_usuario=auec.id_usuario)
			  inner join s_au_cot_cabecera as aucot on (aucot.id_cotizacion=auec.id_cotizacion)
			  inner join s_departamento as sd on (sd.id_depto=su.id_depto)
			where
			  auec.id_emision='".$this->ide."';";
		//echo $this->sqlPo;
		if (($this->rsPo = $this->cx->query($this->sqlPo, MYSQLI_STORE_RESULT))) {
			if ($this->rsPo->num_rows === 1) {
				$this->rowPo = $this->rsPo->fetch_array(MYSQLI_ASSOC);
				$this->rsPo->free();

				$this->sqlDt = "select 
						emd.id_vehiculo,
						emd.id_emision,
						emd.no_detalle,
						emd.prefijo,
						emd.prefix,
						emd.id_tipo_vh,
						emd.categoria,
						emd.id_marca,
						emd.id_modelo,
						emd.anio,
						emd.placa,
						case emd.uso
							when 'PB' then 'x-'
							when 'PR' then '-x'
						end as uso_vehiculo,
						case emd.traccion
							when '4x2' then 'x--'
							when '4x4' then '-x-'
							when 'VHP' then '--x'
						end as traccion,
						emd.km,
						emd.color,
						emd.motor,
						emd.chasis,
						emd.cap_ton,
						emd.no_asiento,
						emd.valor_asegurado,
						emd.tasa,
						emd.prima,
						emd.facultativo,
						emd.motivo_facultativo,
						emd.aprobado,
						case tipv.vehiculo
							when 'Automóvil' then 'x----------'
							when 'Camioneta' then '-x---------'
							when 'Minibus' then '--x--------'
							when 'Colectivo' then '---x-------'
							when 'Vagoneta' then '----x------'
							when 'Camión' then '-----x-----'
							when 'Motocicleta Quadratrack' then '------x----'
							when 'Omnibus' then '-------x---'
							when 'Jeep' then '--------x--'
							when 'Tractocamión' then '---------x-'
							when 'Micro Bus (9 a 25)' then '----------x'
						end as tipo_vechiculo,
						aumr.marca,
						aumod.modelo,
						auf.aprobado as vh_aprobado,
						auf.tasa_recargo as vh_tasa_recargo,
						auf.porcentaje_recargo as vh_porcentaje,
						auf.tasa_actual as vh_tasa_actual,
						auf.tasa_final as vh_tasa_final,
						auf.observacion as vh_observacion
					from
						s_au_em_detalle as emd
							inner join
						s_au_marca as aumr ON (aumr.id_marca = emd.id_marca)
							inner join
						s_au_modelo as aumod ON (aumod.id_modelo = emd.id_modelo)
							inner join
						s_au_tipo_vehiculo as tipv ON (tipv.id_tipo_vh = emd.id_tipo_vh)
							left join
						s_au_facultativo as auf ON (auf.id_emision = emd.id_emision and auf.id_vehiculo=emd.id_vehiculo)
							left join
						s_au_pendiente as aup ON (aup.id_emision = emd.id_emision and aup.id_vehiculo=emd.id_vehiculo)
					where
						emd.id_emision = '".$this->ide."'
					order by emd.id_vehiculo asc;";
				//echo $this->sqlDt;
				if (($this->rsDt = $this->cx->query($this->sqlDt, MYSQLI_STORE_RESULT))) {
					if ($this->rsDt->num_rows > 0) {
						$this->error = FALSE;
					} else { $this->error = TRUE; }
				} else { $this->error = TRUE; }
			} else { $this->error = TRUE; }
		} else { $this->error = TRUE; }
	}
	
	//SLIP COTIZACION TODO RIESGO DOMICILIARIO
	private function set_query_trd_sc(){
		$this->sqlPo="select 
						trdc.id_cotizacion,
						trdc.no_cotizacion,
						trdc.id_ef as idef,
						trdc.id_cliente,
						trdc.ini_vigencia,
						trdc.fin_vigencia,
						trdc.plazo,
						trdc.tipo_plazo,
						@plazo:=(case trdc.tipo_plazo
							when 'Y' then 'Años'
							when 'D' then 'Dias'
							when 'M' then 'Meses'
							when 'W' then 'Semanas'
						end ) as tipo_plazo_text,
						concat(trdc.plazo,' ',@plazo) as tip_plazo_text,
						trdc.fecha_creacion,
						trdc.id_usuario,
						trdc.prima_total,
						sc.nombre as compania,
						sc.logo as logo_cia,
						ef.nombre as ef_nombre,
						ef.logo as logo_ef,
						su.nombre as u_nombre,
						su.email as u_email,
						sfp.forma_pago,
						sfp.codigo as fmp_code,
						case trdclt.tipo
							when 0 then 'Natural'
							when 1 then 'Juridico'
						end as tipo_cliente,
						trdclt.razon_social,
						trdclt.paterno,
						trdclt.materno,
						trdclt.nombre,
						trdclt.ap_casada,
						trdclt.ci, 
						trdclt.avenida,
						trdclt.direccion,
						trdclt.no_domicilio,
						trdclt.localidad,
						trdclt.telefono_domicilio,
						trdclt.telefono_oficina,
						trdclt.telefono_celular,
						trdclt.desc_ocupacion,
						trdclt.direccion_laboral,
						socu.ocupacion
					from
						s_trd_cot_cabecera as trdc
							inner join
						s_entidad_financiera as ef ON (ef.id_ef = trdc.id_ef)
							inner join
						s_ef_compania as sefc ON (sefc.id_ef = ef.id_ef
							and sefc.producto = '".$this->product."')
							inner join
						s_compania as sc ON (sc.id_compania = sefc.id_compania)
							inner join
						s_usuario as su ON (su.id_usuario = trdc.id_usuario)
							inner join
						s_forma_pago as sfp ON (sfp.id_forma_pago = trdc.id_forma_pago)
							inner join
						s_trd_cot_cliente as trdclt ON (trdclt.id_cliente = trdc.id_cliente)
							left join
						s_ocupacion as socu ON (socu.id_ocupacion = trdclt.id_ocupacion
							and socu.producto = '".$this->product."')
					where
						trdc.id_cotizacion = '".$this->idc."'
							and sc.id_compania = '".$this->idcia."';";
		//echo $this->sqlPo;
		if($this->rsPo=$this->cx->query($this->sqlPo, MYSQLI_STORE_RESULT)){
			if($this->rsPo->num_rows === 1){
				$this->rowPo = $this->rsPo->fetch_array(MYSQLI_ASSOC);
				$this->rsPo->free();
				
				$this->sqlDt="select
								trdd.id_inmueble,
								trdd.id_cotizacion,
								case trdd.tipo_in
								  when 'HOME' then 'Casa' 
								  when 'DEPT' then 'Departamento' 
								  when 'BLDN' then 'Edificio' 
								  when 'LOCL' then 'Local Comercial/Oficina'
								end as tipo_inmueble, 
								case trdd.uso
								   when 'COM' then 'Comercial'
								   when 'DMC' then 'Domiciliario'
								   when 'OTH' then 'Otros'
								end as uso_inmueble,
								trdd.uso_otro,
								case trdd.estado
								   when 'FINS' then 'Terminado'
								   when 'CONS' then 'En construcción'
								   when 'PRAR' then 'En proceso de remodelación, ampliación o refacción'
								end as estado_inmueble,
								trdd.zona,
								trdd.localidad,
								trdd.direccion,
								trdd.valor_asegurado,
								sd.departamento
							  from
								s_trd_cot_detalle  as trdd
								inner join s_departamento as sd on (sd.id_depto=trdd.departamento)
							  where
								trdd.id_cotizacion='".$this->idc."';";
				if($this->rsDt=$this->cx->query($this->sqlDt, MYSQLI_STORE_RESULT)){
					if($this->rsDt->num_rows > 0){
				       $this->error=FALSE;	
					}else{
					   $this->error=TRUE;	
				    }
				}else{				
				  $this->error=TRUE;
				}
			}else{
			  $this->error=TRUE;	
			}
		}else{
		   $this->error=TRUE;	
		}					
	}
	
	//CERTIFICADO EMISIION TODO RIESGO DOMICILIARIO
	private function set_query_trd_em () {		// Todo Riesgo Domiciliario
		$this->sqlPo = 'select 
			stre.id_emision,
			strc.id_cotizacion as idc,
			sef.id_ef as idef,
			sef.nombre as ef_nombre,
			sef.logo as ef_logo,
			sh.producto as ef_producto,
			scia.nombre as cia_nombre,
			scia.logo as cia_logo,
			stre.no_emision,
			strc.no_cotizacion,
			stre.prefijo,
			stre.emitir,
			stre.fecha_emision,
			su.nombre as u_nombre,
			su.email as u_email,
			sdeu.departamento as u_depto,
			scl.tipo as cl_tipo,
			scl.razon_social as cl_razon_social,
			scl.paterno as cl_paterno,
			scl.materno as cl_materno,
			scl.nombre as cl_nombre,
			scl.ap_casada as cl_ap_casada,
			(case scl.tipo
				when 0 then concat(scl.ci, scl.complemento, " ", sdep.codigo)
				when 1 then scl.ci
			end) as cl_ci,
			"" as expedido,
			(case scl.avenida
				when "AV" then "Avenida"
				when "CA" then "Calle"
			end) as cl_avc,
			scl.direccion as cl_direccion,
			scl.no_domicilio as cl_no_domicilio,
			scl.localidad as cl_localidad,
			scl.telefono_domicilio as cl_tel_domicilio,
			scl.telefono_oficina as cl_tel_oficina,
			scl.telefono_celular as cl_tel_celular,
			sfp.forma_pago,
			stre.tasa,
			stre.prima_total
		from
			s_trd_em_cabecera as stre
				inner join
			s_trd_cot_cabecera as strc ON (strc.id_cotizacion = stre.id_cotizacion)
				inner join
			s_cliente as scl ON (scl.id_cliente = stre.id_cliente)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = stre.id_ef)
				inner join
			s_compania as scia ON (scia.id_compania = stre.id_compania)
				inner join
			s_usuario as su ON (su.id_usuario = stre.id_usuario)
				inner join
			s_sgc_home as sh ON (sh.id_ef = sef.id_ef)
				inner join
		    s_forma_pago as sfp ON (sfp.id_forma_pago = stre.id_forma_pago)
				inner join 
			s_departamento as sdep ON (sdep.id_depto = scl.extension)
				inner join 
			s_departamento as sdeu ON (sdeu.id_depto = su.id_depto)
		where
		    stre.id_emision = "'.$this->ide.'"
		        and sh.producto = "'.$this->product.'"
		;';
		
		if (($this->rsPo = $this->cx->query($this->sqlPo, MYSQLI_STORE_RESULT))) {
			if ($this->rsPo->num_rows === 1) {
				$this->rowPo = $this->rsPo->fetch_array(MYSQLI_ASSOC);
				$this->rsPo->free();
				
				$this->sqlDt = 'select 
					strd.id_inmueble as idPr,
					strd.no_detalle,
					strd.prefijo,
					strd.prefix,
					strd.tipo_in as pr_tipo,
					strd.uso as pr_uso,
					strd.estado as pr_estado,
					sdep.departamento as pr_departamento,
					strd.zona as pr_zona,
					strd.direccion as pr_direccion,
					strd.valor_asegurado as pr_valor_asegurado
				from
					s_trd_em_detalle as strd
						inner join
					s_trd_em_cabecera as stre ON (stre.id_emision = strd.id_emision)
						inner join
					s_departamento as sdep ON (sdep.id_depto = strd.departamento)
				where
					stre.id_emision = "'.$this->rowPo['id_emision'].'"
				order by strd.id_inmueble asc
				;';
				//echo $this->sqlDt;
				if (($this->rsDt = $this->cx->query($this->sqlDt, MYSQLI_STORE_RESULT))) {
					if ($this->rsDt->num_rows > 0) {
						$this->error = FALSE;
					} else { $this->error = TRUE; }
				} else { $this->error = TRUE; }
			} else { $this->error = TRUE; }
		} else { $this->error = TRUE; }
	}
	
	//SLIP DE COTIZACION TODO RIESGO EQUIPO MOVIL
	private function set_query_trm_sc(){
	    $this->sqlPo="select 
						trmc.id_cotizacion,
						trmc.no_cotizacion,
						trmc.id_ef as idef,
						trmc.id_cliente,
						trmc.tipo,
						trmc.ini_vigencia,
						trmc.fin_vigencia,
						trmc.id_forma_pago,
						trmc.plazo as plz_anio,
						trmc.tipo_plazo as tip_plz_code,
						@plazo:=(case trmc.tipo_plazo
							when 'Y' then 'Años'
							when 'D' then 'Dias'
							when 'M' then 'Meses'
							when 'W' then 'Semanas'
						end) as plazo_text,
						concat(trmc.plazo, ' ', @plazo) as tipo_plazo_text,
						@conversor:=(if(trmc.tipo_plazo = 'M',
							  ROUND(trmc.plazo / 12),
							  if(trmc.tipo_plazo = 'D',
								  ROUND(trmc.plazo / 365),
								  if(trmc.tipo_plazo = 'W',
									  ROUND(trmc.plazo / 52),
									  trmc.plazo)))) as resu_conversor,
						  @newanio:=(if(@conversor > 0, @conversor, 1)) as cant_plazo,	
						trmc.fecha_creacion,
						trmc.id_usuario,
						trmc.cuota,
						trmc.valor_asegurado_total,
						trmc.prima_total,
						trmc.facultativo,
						ef.nombre as ef_nombre,
						ef.logo as logo_ef,
						sc.nombre as compania,
						sc.logo as logo_cia,
						sc.id_compania,
						su.nombre as u_nombre,
						su.email as u_email,
						sfp.forma_pago,
						sfp.codigo as fmp_code,
						case trmclt.tipo
							when 0 then 'Natural'
							when 1 then 'Juridico'
						end as tipo_cliente,
						trmclt.razon_social,
						trmclt.paterno,
						trmclt.materno,
						trmclt.nombre,
						trmclt.ci,
						trmclt.telefono_domicilio,
						trmclt.telefono_oficina,
						trmclt.telefono_celular,
						trmclt.email,
						sh.monto_facultativo
					from
						s_trm_cot_cabecera as trmc
							inner join
						s_entidad_financiera as ef ON (ef.id_ef = trmc.id_ef)
							inner join
						s_ef_compania as sefc ON (sefc.id_ef = ef.id_ef
							and sefc.producto = '".$this->product."')
							inner join
						s_compania as sc ON (sc.id_compania = sefc.id_compania)
							inner join
						s_usuario as su ON (su.id_usuario = trmc.id_usuario)
							inner join
						s_forma_pago as sfp ON (sfp.id_forma_pago = trmc.id_forma_pago)
							inner join
						s_trm_cot_cliente as trmclt ON (trmclt.id_cliente = trmc.id_cliente)
						    inner join
						s_sgc_home as sh on (sh.id_ef=trmc.id_ef and sh.producto='".$this->product."')	
					where
						trmc.id_cotizacion = '".$this->idc."'
							and sc.id_compania = '".$this->idcia."';";	
		if($this->rsPo = $this->cx->query($this->sqlPo, MYSQLI_STORE_RESULT)){
			if($this->rsPo->num_rows === 1){
			   	$this->rowPo = $this->rsPo->fetch_array(MYSQLI_ASSOC);
				$this->rsPo->free();
				
				$this->sqlDt="select
								id_material,
								id_cotizacion,
								material,
								valor_asegurado
							  from
								s_trm_cot_detalle
							  where
								id_cotizacion='".$this->idc."';";
				//echo $this->sqlDt;				
				if($this->rsDt = $this->cx->query($this->sqlDt,MYSQLI_STORE_RESULT)){
					if($this->rsDt->num_rows > 0){
						$this->error=FALSE;
					}else{
						$this->error=TRUE; 
					} 
				}else{
					$this->error=TRUE;
				}				
			}else{
			    $this->error=TRUE;	
			}
		}else{
		   $this->error=TRUE;	
		} 					
	}
	
	//EMISION CERTIFICADO TODO RIESGO EQUIPO MOVIL
	private function set_query_trm_em () {		// Todo Riesgo Equipo Movil
		$this->sqlPo = "select 
						trmca.id_emision,
						trmca.no_emision,
						trmca.id_ef as idef,
						trmca.id_cotizacion,
						trmca.id_poliza,
						trmca.id_compania,
						trmca.id_poliza,
						trmca.prefijo,
						trmca.plazo as plz_anio,
						trmca.tipo_plazo as tip_plz_code,
						@valor:=(case trmca.tipo_plazo
							when 'Y' then 'Años'
							when 'D' then 'Dias'
							when 'M' then 'Meses'
							when 'W' then 'Semanas'
						end) as plazo_text,
						concat(trmca.plazo, ' ', @valor) as tipo_plazo_text,
						@conversor:=(if(trmca.tipo_plazo = 'M',
							ROUND(trmca.plazo / 12),
							if(trmca.tipo_plazo = 'D',
								ROUND(trmca.plazo / 365),
								if(trmca.tipo_plazo = 'W',
									ROUND(trmca.plazo / 52),
									trmca.plazo)))) as resu_conversor,
						@newanio:=(if(@conversor > 0, @conversor, 1)) as cant_plazo,
						trmca.ini_vigencia as fecha_iniv,
						(@fecf:=date_add(trmca.ini_vigencia,
							interval @newanio year)) as fecha_finv,
						datediff(@fecf, trmca.ini_vigencia) as plazo_dias,
						(if(trmf.aprobado is null
							or trmf.aprobado = '',
						if(trmpend.id_pendiente is not null,
							(case trmpend.respuesta
								when 1 then 4
								when 0 then 3
							end),
							5),
						(case trmf.aprobado
							when 'si' then 1
							when 'no' then 2
						end))) as tr_estado,
						trmca.fin_vigencia,
						trmca.emitir,
						trmca.fecha_emision as fecha_real_emision,
						trmca.prima_total,
						trmca.valor_asegurado_total,
						trmca.tasa as tr_tasa,
						trmca.facultativo,
						trmca.motivo_facultativo,
						(case trmca.no_copia
							when 0 then 'ORIGINAL'
							else 'COPIA No.'
						end) as text_copia,
						(case trmca.no_copia
							when 0 then ''
							else (trmca.no_copia + 1)
						end) as num_copia,
						case scl.tipo
							when 0 then 'Natural'
							when 1 then 'Juridico'
						end as tipo_cliente,
						if(scl.tipo = 1,
							scl.razon_social,
							if(scl.ap_casada != '',
								concat(scl.nombre,
										' ',
										scl.paterno,
										' de ',
										scl.ap_casada),
								concat(scl.nombre,
										' ',
										scl.paterno,
										' ',
										scl.materno)
								)) as cliente_nombre,
						scl.ci as cl_ci,
						if(scl.tipo = 1, 
							'',
							sdep.codigo) as cl_extension,
						scl.complemento as cl_complemento,
						scl.ci_archivo,
						su.nombre as u_nombre,
						su.email as u_email,
						sfp.forma_pago,
						sfp.codigo as fmp_code,
						trmf.aprobado,
						trmf.tasa_recargo,
						trmf.porcentaje_recargo,
						trmf.tasa_actual,
						trmf.tasa_final as tr_tasa_final,
						trmf.observacion as tr_observacion_f,
						sc.nombre as compania,
						sc.logo as logo_cia,
						sef.nombre as ef_nombre,
						sef.logo as logo_ef,
                        trmcot.cuota
					from
						s_trm_em_cabecera as trmca
							inner join
						s_cliente as scl ON (scl.id_cliente = trmca.id_cliente)
							inner join 
						s_departamento as sdep ON (sdep.id_depto = scl.extension)
							inner join
						s_usuario as su ON (su.id_usuario = trmca.id_usuario)
							inner join
						s_forma_pago as sfp ON (sfp.id_forma_pago = trmca.id_forma_pago)
							left join
						s_trm_facultativo as trmf ON (trmf.id_emision = trmca.id_emision)
							left join
						s_trm_pendiente as trmpend ON (trmpend.id_emision = trmca.id_emision)
							inner join
						s_compania as sc ON (sc.id_compania = trmca.id_compania)
							inner join
						s_entidad_financiera as sef ON (sef.id_ef = trmca.id_ef)
						    inner join
                        s_trm_cot_cabecera as trmcot ON (trmcot.id_cotizacion = trmca.id_cotizacion)
					where
						trmca.id_emision = '".$this->ide."'
					limit
					   0,1;";
		
		if (($this->rsPo = $this->cx->query($this->sqlPo, MYSQLI_STORE_RESULT))) {
			if ($this->rsPo->num_rows === 1) {
				$this->rowPo = $this->rsPo->fetch_array(MYSQLI_ASSOC);
				$this->rsPo->free();
				
				$this->sqlDt = "select
								  trmd.id_material,
								  trmd.id_emision,
								  trmd.no_detalle,
								  trmd.prefijo,
								  trmd.prefix,
								  trmd.material,
								  trmd.valor_asegurado,
								  trmd.tasa,
								  trmd.prima,
								  trmd.mt_archivo
								from
								  s_trm_em_detalle as trmd
								  inner join s_trm_em_cabecera as trmcab on (trmcab.id_emision=trmd.id_emision)
								  left join s_trm_facultativo as trmf on (trmf.id_emision=trmcab.id_emision)
								  left join s_trm_pendiente as trmp on (trmp.id_emision=trmcab.id_emision)  
								where
								  trmd.id_emision='".$this->rowPo['id_emision']."'; ";
				//echo $this->sqlDt;
				if (($this->rsDt = $this->cx->query($this->sqlDt, MYSQLI_STORE_RESULT))) {
					if ($this->rsDt->num_rows > 0) {
						$this->error = FALSE;
					} else { $this->error = TRUE; }
				} else { $this->error = TRUE; }
			} else { $this->error = TRUE; }
		} else { $this->error = TRUE; }
	}
	
	//CERTIFICADO PROVISIONAL DESGRAVAMEN
	private function set_query_de_cp () {
		$this->sqlPo = "select 
							sdec.id_emision,
							sdec.no_emision,
							sdec.id_cotizacion,
							(case sdec.id_prcia
								when 4 then 'Hipotecario'
								when 3 then 'consumo'
								when 2 then 'Comercial'
								when 5 then 'Otro'
							end) as tipo_credito,
							sdec.moneda,
							sdec.monto_solicitado,
							(case sdec.moneda
								when 'USD' then 'Usd'
								when 'BS' then 'Bs'
							end) as moneda_text,
							(case sdec.tipo_plazo
								when 'Y' then concat(sdec.plazo, ' ', 'años')
								when 'D' then concat(sdec.plazo, ' ', 'dias')
								when 'M' then concat(sdec.plazo, ' ', 'meses')
								when 'W' then concat(sdec.plazo, ' ', 'semanas')
							end) as tipo_plazo,
							count(sded.id_cliente) as num_cliente,
							if(sdec.facultativo = 1,
								sdf.tasa_final,
								sdec.tasa) as tasa_final,
							(if(@monto_s > 35000, 1, 0)) as verifica_vida,
							sdcc.no_cotizacion,
							sdec.id_ef as idef,
							sef.nombre as ef_nombre,
							sef.logo as logo_entidad,
							sc.nombre as compania,
							sc.logo as logo_compania,
							su.nombre as u_nombre,
							su.email as u_email
						from
							s_de_em_cabecera as sdec
								inner join
							s_poliza as sp ON (sp.id_poliza = sdec.id_poliza)
								inner join
							s_compania as sc ON (sc.id_compania = sdec.id_compania)
								inner join
							s_de_em_detalle as sded ON (sded.id_emision = sdec.id_emision)
								inner join
							s_usuario as su ON (su.id_usuario = sdec.id_usuario)
								inner join
							s_de_cot_cabecera as sdcc ON (sdcc.id_cotizacion = sdec.id_cotizacion)
								left join
							s_de_facultativo as sdf ON (sdf.id_emision = sdec.id_emision)
								inner join
							s_entidad_financiera as sef ON (sef.id_ef = sdec.id_ef)
								inner join
							s_sgc_home as sh ON (sh.id_ef = sef.id_ef)
						where
							sdec.id_emision = '".$this->ide."'
								and sh.producto = '".$this->product."';";
		//echo $this->sqlPo;
		if (($this->rsPo = $this->cx->query($this->sqlPo, MYSQLI_STORE_RESULT))) {
			if ($this->rsPo->num_rows === 1) {
				$this->rowPo = $this->rsPo->fetch_array(MYSQLI_ASSOC);
				$this->rsPo->free();
				
				$this->sqlDt = "select 
									sc.id_cliente,
									sc.paterno,
									sc.materno,
									sc.nombre,
									sc.ap_casada,
									sc.lugar_nacimiento,
									sc.pais,
									sc.fecha_nacimiento,
									sdto.departamento as lugar_residencia,
									if(sdep.codigo = 'PE',
										concat(sc.tipo_documento,
												' ',
												'E-',
												sc.ci,
												' ',
												sc.complemento),
										concat(sc.tipo_documento,
												' ',
												sc.ci,
												' ',
												sc.complemento,
												' ',
												sdep.codigo)) as ci,
									sc.edad,
									sc.peso,
									sc.estatura,
									concat(sc.direccion,
											' ',
											(if(sc.no_domicilio = '',
												'S/N',
												sc.no_domicilio))) as direccion,
									sc.telefono_domicilio,
									sc.telefono_oficina,
									sdo.ocupacion,
									sc.desc_ocupacion,
									sder.respuesta,
									sded.titular as titular_txt,
									(case sded.titular
										when 'DD' then 1
										when 'CC' then 2
									end) as titular_num
								from
									s_cliente as sc
										inner join
									s_de_em_detalle as sded ON (sded.id_cliente = sc.id_cliente)
										inner join
									s_departamento as sdep ON (sdep.id_depto = sc.extension)
										inner join
									s_departamento as sdto ON (sdto.id_depto = sc.lugar_residencia)
										inner join
									s_ocupacion as sdo ON (sdo.id_ocupacion = sc.id_ocupacion)
										inner join
									s_de_em_respuesta as sder ON (sder.id_detalle = sded.id_detalle)
								where
									sded.id_emision = '".$this->rowPo['id_emision']."';";
				//echo $this->sqlDt;
				if (($this->rsDt = $this->cx->query($this->sqlDt, MYSQLI_STORE_RESULT))) {
					if ($this->rsDt->num_rows > 0) {
						$this->error = FALSE;
					} else { $this->error = TRUE; }
				} else { $this->error = TRUE; }
			} else { $this->error = TRUE; }
		} else { $this->error = TRUE; }
	}
	
	//CERTIFICADO PROVISIONAL AUTOMOTORES
	private function set_query_au_cp(){
		 $this->sqlPo = "select
						  auec.id_emision,
						  auec.no_emision,
						  auec.id_ef as idef,
						  auec.id_cotizacion,
						  auec.id_cliente,
						  auec.no_operacion,
						  auec.prefijo,
						  auec.ini_vigencia,
						  auec.fin_vigencia,
						  auec.id_forma_pago,
						  auec.plazo as plz_anio,
						  auec.tipo_plazo as tip_plz_code,
						  concat(auec.plazo,
								  ' ',
								  case auec.tipo_plazo
									  when 'Y' then 'Años'
									  when 'D' then 'Dias'
									  when 'M' then 'Meses'
									  when 'W' then 'Semanas'
								  end) as tipo_plazo,
						  @conversor:=(if(auec.tipo_plazo = 'M',
							  ROUND(auec.plazo / 12),
							  if(auec.tipo_plazo = 'D',
								  ROUND(auec.plazo / 365),
								  if(auec.tipo_plazo = 'W',
									  ROUND(auec.plazo / 52),
									  auec.plazo)))) as resu_conversor,
						  @newanio:=(if(@conversor > 0, @conversor, 1)) as cant_plazo,		  
						  auec.fecha_creacion,
						  auec.id_usuario,
						  auec.anulado,
						  auec.fecha_anulado,
						  auec.fecha_emision,
						  auec.id_compania,
						  auec.id_poliza,
						  auec.no_copia,
						  auec.facultativo,
						  auec.prima_total,
						  case sclie.tipo
							when 0 then 'Natural'
							when 1 then 'Juridico'
						  end tipo_cliente, 
						  sclie.paterno,
						  sclie.materno,
						  sclie.nombre,
						  sclie.ap_casada,
						  sclie.avenida,
						  sclie.direccion,
						  sclie.no_domicilio,
						  sclie.localidad,
						  sclie.telefono_domicilio,
						  sclie.telefono_oficina,
						  sclie.telefono_celular,
						  sclie.ci,
						  case sclie.genero
						     when 'M' then 'Masculino'
							 when 'F' then 'Femenino'
						  end as genero,
						  sclie.fecha_nacimiento,
						  sclie.razon_social,
						  sef.nombre as ef_nombre,
						  sef.logo as logo_ef,
						  sc.nombre as compania,
						  sc.logo as logo_cia,
						  su.nombre as u_nombre,
						  su.email as u_email,
                          aucot.no_cotizacion,
						  aucot.cuota,
						  auec.emitir,
						  sfp.forma_pago,
						  sfp.codigo as frm_pago_code  
						from
						  s_au_em_cabecera as auec
						  inner join s_cliente as sclie on (sclie.id_cliente=auec.id_cliente)
						  inner join s_entidad_financiera as sef on (sef.id_ef=auec.id_ef)
						  inner join s_compania as sc on (sc.id_compania=auec.id_compania)
						  inner join s_usuario as su on (su.id_usuario=auec.id_usuario)
						  inner join s_au_cot_cabecera as aucot on (aucot.id_cotizacion=auec.id_cotizacion)
						  inner join s_forma_pago as sfp ON (sfp.id_forma_pago = auec.id_forma_pago)
						where
						  auec.id_emision='".$this->ide."';";
		//echo $this->sqlPo;					  			  
		if($this->rsPo = $this->cx->query($this->sqlPo, MYSQLI_STORE_RESULT)){
			if($this->rsPo->num_rows === 1){
				$this->rowPo=$this->rsPo->fetch_array(MYSQLI_ASSOC);
				$this->rsPo->free();
				
				$this->sqlDt="select 
								emd.id_vehiculo,
								emd.id_emision,
								emd.id_tipo_vh,
								emd.categoria as category,
							    case emd.categoria
							 	  when 'RAC' then 'Renta Car'
								  when 'OTH' then 'Otros'
							    end as categoria,
								emd.id_marca,
								emd.id_modelo,
								emd.anio,
								emd.placa,
								emd.uso as uso_vehiculo,
								emd.traccion as traccion,
								emd.km,
								emd.color,
								emd.motor,
								emd.chasis,
								emd.cap_ton,
								emd.no_asiento,
								emd.valor_asegurado,
								emd.tasa,
								emd.prima,
								emd.facultativo,
								emd.motivo_facultativo,
								emd.aprobado,
								tipv.vehiculo,
								aumr.marca,
								aumod.modelo,
								auf.aprobado as vh_aprobado,
								auf.tasa_recargo as vh_tasa_recargo,
								auf.porcentaje_recargo as vh_porcentaje,
								auf.tasa_actual as vh_tasa_actual,
								auf.tasa_final as vh_tasa_final,
								auf.observacion as vh_observacion
							from
								s_au_em_detalle as emd
									inner join
								s_au_marca as aumr ON (aumr.id_marca = emd.id_marca)
									inner join
								s_au_modelo as aumod ON (aumod.id_modelo = emd.id_modelo)
									inner join
								s_au_tipo_vehiculo as tipv ON (tipv.id_tipo_vh = emd.id_tipo_vh)
									left join
								s_au_facultativo as auf ON (auf.id_emision = emd.id_emision and auf.id_vehiculo=emd.id_vehiculo)
									left join
								s_au_pendiente as aup ON (aup.id_emision = emd.id_emision and aup.id_vehiculo=emd.id_vehiculo)
							where
								emd.id_emision = '".$this->ide."'
							order by emd.id_vehiculo asc;";
				if($this->rsDt = $this->cx->query($this->sqlDt, MYSQLI_STORE_RESULT)){
					if ($this->rsDt->num_rows > 0) {
						$this->error = FALSE;
					}else{ 
					   $this->error = TRUE; 
					}
				}else{
				  $this->error=TRUE;
				}
			}else{
				$this->error=TRUE;
			}
		}else{
		   $this->error=TRUE;
		}					   	
	}
	
	//CERTIFICADO PROVISIONAL TODO RIESGO DOMICILIARIO
	private function set_query_trd_cp(){
		 $this->sqlPo = 'select 
			stre.id_emision,
			strc.id_cotizacion as idc,
			sef.id_ef as idef,
			sef.nombre as ef_nombre,
			sef.logo as ef_logo,
			sh.producto as ef_producto,
			scia.nombre as cia_nombre,
			scia.logo as cia_logo,
			stre.no_emision,
			strc.no_cotizacion,
			stre.prefijo,
			stre.fecha_emision,
			stre.ini_vigencia,
			stre.fin_vigencia,
			@plazo:=(case stre.tipo_plazo
				when "Y" then "Años"
				when "D" then "Dias"
				when "M" then "Meses"
				when "W" then "Semanas"
			end ) as plazo_text,
			concat(stre.plazo," ",@plazo) as tipo_plazo_text,
			su.nombre as u_nombre,
			su.email as u_email,
			sdeu.departamento as u_depto,
			scl.tipo as cl_tipo,
			scl.razon_social as cl_razon_social,
			scl.paterno as cl_paterno,
			scl.materno as cl_materno,
			scl.nombre as cl_nombre,
			scl.ap_casada as cl_ap_casada,
			(case scl.tipo
				when 0 then concat(scl.ci, scl.complemento, " ", sdep.codigo)
				when 1 then scl.ci
			end) as cl_ci,
			(case scl.avenida
				when "AV" then "Avenida"
				when "CA" then "Calle"
			end) as cl_avc,
			scl.direccion as cl_direccion,
			scl.no_domicilio as cl_no_domicilio,
			scl.localidad as cl_localidad,
			scl.telefono_domicilio as cl_tel_domicilio,
			scl.telefono_oficina as cl_tel_oficina,
			scl.telefono_celular as cl_tel_celular,
			scl.direccion_laboral,
			scl.desc_ocupacion,
			ocup.ocupacion, 
			sfp.forma_pago,
			stre.tasa,
			stre.prima_total
		from
			s_trd_em_cabecera as stre
				inner join
			s_trd_cot_cabecera as strc ON (strc.id_cotizacion = stre.id_cotizacion)
				inner join
			s_cliente as scl ON (scl.id_cliente = stre.id_cliente)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = stre.id_ef)
				inner join
			s_compania as scia ON (scia.id_compania = stre.id_compania)
				inner join
			s_usuario as su ON (su.id_usuario = stre.id_usuario)
				inner join
			s_sgc_home as sh ON (sh.id_ef = sef.id_ef)
				inner join
		    s_forma_pago as sfp ON (sfp.id_forma_pago = stre.id_forma_pago)
				inner join 
			s_departamento as sdep ON (sdep.id_depto = scl.extension)
				inner join 
			s_departamento as sdeu ON (sdeu.id_depto = su.id_depto)
			    left join
			s_ocupacion as ocup ON (ocup.id_ocupacion = scl.id_ocupacion and ocup.producto="'.$this->product.'")
		where
		    stre.id_emision = "'.$this->ide.'"
		        and sh.producto = "'.$this->product.'";';
		//echo $this->sqlPo;
		if (($this->rsPo = $this->cx->query($this->sqlPo, MYSQLI_STORE_RESULT))) {
			if ($this->rsPo->num_rows === 1) {
				$this->rowPo = $this->rsPo->fetch_array(MYSQLI_ASSOC);
				$this->rsPo->free();
				
				$this->sqlDt = 'select 
					strd.id_inmueble as idPr,
					strd.tipo_in as pr_tipo,
					strd.uso as pr_uso,
					strd.estado as pr_estado,
					sdep.departamento as pr_departamento,
					strd.zona as pr_zona,
					strd.direccion as pr_direccion,
					strd.localidad as pr_localidad,
					strd.valor_asegurado as pr_valor_asegurado
				from
					s_trd_em_detalle as strd
						inner join
					s_trd_em_cabecera as stre ON (stre.id_emision = strd.id_emision)
						inner join
					s_departamento as sdep ON (sdep.id_depto = strd.departamento)
				where
					stre.id_emision = "'.$this->rowPo['id_emision'].'"
				order by strd.id_inmueble asc
				;';
				//echo $this->sqlDt;
				if (($this->rsDt = $this->cx->query($this->sqlDt, MYSQLI_STORE_RESULT))) {
					if ($this->rsDt->num_rows > 0) {
						$this->error = FALSE;
					} else { $this->error = TRUE; }
				} else { $this->error = TRUE; }
			} else { $this->error = TRUE; }
		} else { $this->error = TRUE; }
	}
	
	//CERTIFICADO PROVISIONAL TODO RIESGO EQUIPO MOVIL
	private function set_query_trm_cp () {		// Todo Riesgo Equipo Movil
		$this->sqlPo = "select 
						  trmca.id_emision,
						  trmca.no_emision,
						  trmca.id_cotizacion,
						  trmca.id_compania,
						  trmcot.no_cotizacion,
						  trmca.id_ef as idef,
						  trmca.plazo as plz_anio,
						  trmca.tipo_plazo as tip_plz_code,
						  @valor:=(case trmca.tipo_plazo
							  when 'Y' then 'Años'
							  when 'D' then 'Dias'
							  when 'M' then 'Meses'
							  when 'W' then 'Semanas'
						  end) as plazo_text,
						  concat(trmca.plazo, ' ', @valor) as tipo_plazo_text,
						  @conversor:=(if(trmca.tipo_plazo = 'M',
							  ROUND(trmca.plazo / 12),
							  if(trmca.tipo_plazo = 'D',
								  ROUND(trmca.plazo / 365),
								  if(trmca.tipo_plazo = 'W',
									  ROUND(trmca.plazo / 52),
									  trmca.plazo)))) as resu_conversor,
						  @newanio:=(if(@conversor > 0, @conversor, 1)) as cant_plazo,
						  trmca.ini_vigencia as fecha_iniv,
						  (@fecf:=date_add(trmca.ini_vigencia,
							  interval @newanio year)) as fecha_finv,
						  datediff(@fecf, trmca.ini_vigencia) as plazo_dias,
						  trmca.fin_vigencia,
						  trmca.emitir,
						  trmca.fecha_emision as fecha_real_emision,
						  trmca.prima_total,
						  trmca.valor_asegurado_total,
						  trmca.tasa as tr_tasa,
						  trmca.facultativo,
						  (case scl.tipo
							  when 0 then 'Natural'
							  when 1 then 'Juridico'
						  end) as tipo_cliente,
						  su.nombre as u_nombre,
						  su.email as u_email,
						  sfp.forma_pago,
						  sfp.codigo as fmp_code,
						  trmf.aprobado,
						  trmf.tasa_recargo,
						  trmf.porcentaje_recargo,
						  trmf.tasa_actual,
						  trmf.tasa_final as tr_tasa_final,
						  sc.nombre as compania,
						  sc.logo as logo_cia,
						  sef.nombre as ef_nombre,
						  sef.logo as logo_ef,
						  trmcot.cuota,
						  scl.razon_social,
						  scl.paterno,
						  scl.materno,
						  scl.nombre,
						  scl.ci,
						  scl.telefono_domicilio,
						  scl.telefono_celular,
						  scl.telefono_oficina,
						  scl.email,
                          sh.monto_facultativo
					  from
						  s_trm_em_cabecera as trmca
							  inner join
						  s_cliente as scl ON (scl.id_cliente = trmca.id_cliente)
							  inner join
						  s_usuario as su ON (su.id_usuario = trmca.id_usuario)
							  inner join
						  s_forma_pago as sfp ON (sfp.id_forma_pago = trmca.id_forma_pago)
							  left join
						  s_trm_facultativo as trmf ON (trmf.id_emision = trmca.id_emision)
							  left join
						  s_trm_pendiente as trmpend ON (trmpend.id_emision = trmca.id_emision)
							  inner join
						  s_compania as sc ON (sc.id_compania = trmca.id_compania)
							  inner join
						  s_entidad_financiera as sef ON (sef.id_ef = trmca.id_ef)
							  inner join
						  s_trm_cot_cabecera as trmcot ON (trmcot.id_cotizacion = trmca.id_cotizacion)
						      inner join
                          s_sgc_home as sh on (sh.id_ef=trmca.id_ef and sh.producto=trmca.prefijo)
					  where
						  trmca.id_emision = '".$this->ide."'
					  limit 0 , 1;";
		
		if (($this->rsPo = $this->cx->query($this->sqlPo, MYSQLI_STORE_RESULT))) {
			if ($this->rsPo->num_rows === 1) {
				$this->rowPo = $this->rsPo->fetch_array(MYSQLI_ASSOC);
				$this->rsPo->free();
				
				$this->sqlDt = "select
								  trmd.id_material,
								  trmd.id_emision,
								  trmd.material,
								  trmd.valor_asegurado,
								  trmd.tasa,
								  trmd.prima,
								  trmd.mt_archivo
								from
								  s_trm_em_detalle as trmd
								  inner join s_trm_em_cabecera as trmcab on (trmcab.id_emision=trmd.id_emision)
								  left join s_trm_facultativo as trmf on (trmf.id_emision=trmcab.id_emision)
								  left join s_trm_pendiente as trmp on (trmp.id_emision=trmcab.id_emision)  
								where
								  trmd.id_emision='".$this->rowPo['id_emision']."'; ";
				//echo $this->sqlDt;
				if (($this->rsDt = $this->cx->query($this->sqlDt, MYSQLI_STORE_RESULT))) {
					if ($this->rsDt->num_rows > 0) {
						$this->error = FALSE;
					} else { $this->error = TRUE; }
				} else { $this->error = TRUE; }
			} else { $this->error = TRUE; }
		} else { $this->error = TRUE; }
	}
	
	// CERTIFICADO EMISION TARJETAHABIENTE MODALIDAD
	private function set_query_th_em_mo () {
		$this->sqlPo = "select 
		    sthc.id_cotizacion,
		    sthc.no_cotizacion,
		    sthc.prefijo,
		    sthc.prefix,
		    sef.id_ef as idef,
		    sef.nombre as ef_nombre,
		    sef.logo as ef_logo,
		    stj.tarjeta as tj_tarjeta,
		    sthc.no_tarjeta as tj_no_tarjeta,
		    stm.marca as tj_marca,
		    sthc.fecha_creacion,
		    sthc.prima_total,
		    su.nombre as u_nombre,
		    su.email as u_email,
		    scl.tipo as cl_tipo,
		    scl.razon_social as cl_razon_social,
		    scl.nombre as cl_nombre,
		    scl.paterno as cl_paterno,
		    scl.materno as cl_materno,
		    scl.fecha_nacimiento as cl_fecha_nacimiento,
		    scl.ci as cl_ci,
		    sdep.codigo as cl_extension,
		    scl.complemento as cl_complemento,
		    scl.genero as cl_genero,
		    scl.telefono_domicilio as cl_tel_domicilio,
		    scl.telefono_oficina as cl_tel_oficina,
		    scl.telefono_celular as cl_tel_celular,
		    scl.email as cl_email
		from
		    s_th_cot_cabecera as sthc
		        inner join
		    s_th_cot_cliente as scl ON (scl.id_cliente = sthc.id_cliente)
		        inner join
		    s_th_tarjeta as stj ON (stj.id_tarjeta = sthc.id_tarjeta)
		        left join
		    s_th_marca as stm ON (stm.id_marca = sthc.id_marca)
		        inner join
		    s_usuario as su ON (su.id_usuario = sthc.id_usuario)
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sthc.id_ef)
				inner join
    		s_departamento as sdep ON (sdep.id_depto = scl.extension)
		where
		    sthc.id_cotizacion = '" . $this->idc . "'
		;";
		
		if (($this->rsPo = $this->cx->query($this->sqlPo, MYSQLI_STORE_RESULT))) {
			if ($this->rsPo->num_rows === 1) {
				$this->rowPo = $this->rsPo->fetch_array(MYSQLI_ASSOC);
				$this->rsPo->free();
				
				$this->error = false;
			} else { $this->error = TRUE; }
		} else { $this->error = TRUE; }
	}
	
	private function set_query_trm_em_mo () {
		$this->set_query_trm_em();
	}
	
	private function set_query_trd_em_mo () {
		$this->set_query_trd_em();
	}
	
	private function set_query_au_em_mo () {
		$this->set_query_au_em();
	}
}

?>