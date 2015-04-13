<?php
require_once('RC-certificate-sibas-html.class.php');
/**
 *
 */
class CertificateQuery extends CertificateHtml {

	protected function __construct() {
	/*
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
	*/

		switch ($this->category) {
		case 'RC':		//	Siniestro
			$this->get_query_rc();
			break;
		}

		if ($this->error === FALSE) {
			parent::__construct();
		}
	}
	//REPORTE SINIESTRO
	private function get_query_rc(){

		$this->set_query_rc();

	}

	//QUERYS SLIP DE COTIZACION
	private function set_query_rc(){		//DESGRAVAMEN


	  $this->sqlPo='select
				ssi.id_siniestro as idRc,
				ssi.no_siniestro as s_no_siniestro,
				ssi.fecha_registro as s_fecha_registro,
				ssi.id_cliente as idCl,
				concat(ssi.ci,
								" ",
								sdep1.codigo) as s_ci,
				case ssi.tipo_documento
				   when "CI" then "Carnet de Identidad"
				   when "RUN" then "RUN"
				   when "PA" then "Pasaporte"
				   when "CE" then "Carnet Extranjero"
				end as s_tipo_documento,
				ssi.paterno as s_paterno,
				ssi.materno as s_materno,
				ssi.nombre as s_nombre,
				ssi.ap_casada as s_ap_casada,
				concat(ssi.nombre,
								" ",
								ssi.paterno,
								" ",
								ssi.materno,
								" ",
								ssi.ap_casada) as s_nombre_completo,
				ssi.imagen as s_imagen,
				ssi.fecha_siniestro as s_fecha_siniestro,
				ssi.circunstancia as s_circunstancia,
				ssi.denuncia_persona as s_denuncia_persona,
				ssi.denuncia_telefono as s_denuncia_telefono,
				ssi.denuncia_email as s_denuncia_email,
				ssi.fecha_elaboracion as s_fecha_elaboracion,
				su.id_usuario as s_usuario,
				su.nombre as u_nombre,
				su.email as u_email,
				su.cargo as u_cargo,
				sdp.departamento as s_sucursal,
				sag.agencia as s_agencia,
				sef.id_ef as idef,
				sef.nombre as ef_nombre,
			    sef.logo as ef_logo
			from
				s_siniestro as ssi
					inner join
				s_entidad_financiera as sef ON (sef.id_ef = ssi.id_ef)
					inner join
				s_usuario as su ON (su.id_usuario = ssi.denunciado_por)
					inner join
				s_departamento as sdp ON (sdp.id_depto = ssi.sucursal)
					left join
				s_agencia as sag ON (sag.id_agencia = ssi.agencia)
					left join
				s_departamento as sdep1 ON (sdep1.id_depto = ssi.extension)
			where
				ssi.id_siniestro = "'.$this->idrc.'"
					and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
					and sef.activado = true
			limit 0 , 1';
	  //echo $this->sqlPo;
	  if($this->rsPo = $this->cx->query($this->sqlPo, MYSQLI_STORE_RESULT)){//DESGRAVAMEN
		  if($this->rsPo->num_rows === 1){
			 $this->rowPo = $this->rsPo->fetch_array(MYSQLI_ASSOC);
			 $this->rsPo->free();

			 $this->sqlDt='select
						ssi.id_siniestro as idRc,
						ssd.id_detalle as idRd,
						ssd.id_emision as ide,
						ssd.no_emision as d_no_emision,

						(case ssd.tipo_credito
							when "PEBBCC" then "Prestamo Externo BBCC"
							when "PIBBCC" then "Prestamo Interno BBCC"
							when "CO" then "Crédito Oportuno"
							when "CI" then "Crédito Individual"
						end) as d_tipo_credito,

						ssd.no_credito as d_no_credito,

						ssd.monto_desembolso as d_monto_desembolso,

						ssd.moneda as d_cod_moneda,

						(case ssd.moneda
							when "BS" then "Bolivianos"
							when "USD" then "Dólares"
						end) as d_moneda

					from
						s_siniestro_detalle as ssd
							inner join
						s_siniestro as ssi ON (ssi.id_siniestro = ssd.id_siniestro)
					where
						ssi.id_siniestro = "'.$this->idrc.'"
					order by ssd.id_detalle asc
					;';
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

}

?>