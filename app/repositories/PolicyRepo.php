<?php

class PolicyRepo
{
	protected $cx;
	
	public function __construct($cx)
	{
		$this->cx = $cx;
	}

	public function getQuoteData($idc, $idef)
	{
		$data = array();

		$sql = 'select 
			sdc.id_cotizacion as idc,
			sdc.certificado_provisional as cp,
			sdc.cobertura,
			sdc.id_prcia,
			sdc.monto,
			sdc.moneda,
			sdc.plazo,
			sdc.tipo_plazo,
			scl.id_cliente,
			scl.nombre as cl_nombre,
			scl.paterno as cl_paterno,
			scl.materno as cl_materno,
			scl.ap_casada as cl_casada,
			scl.genero as cl_genero,
			scl.estado_civil as cl_estado_civil,
			scl.tipo_documento as cl_tipo_documento,
			scl.ci as cl_ci,
			scl.complemento as cl_complemento,
			scl.extension as cl_extension,
			scl.fecha_nacimiento as cl_fecha_nacimiento,
			scl.pais as cl_pais,
			scl.lugar_nacimiento as cl_lugar_nacimiento,
			scl.lugar_residencia as cl_lugar_residencia,
			scl.localidad as cl_localidad,
			scl.direccion as cl_direccion,
			scl.telefono_domicilio as cl_telefono_domicilio,
			scl.telefono_celular as cl_telefono_celular,
			scl.email as cl_email,
			scl.id_ocupacion as cl_ocupacion,
			scl.desc_ocupacion as cl_desc_ocupacion,
			scl.telefono_oficina as cl_telefono_oficina,
			scl.peso as cl_peso,
			scl.estatura as cl_estatura,
			scl.edad as cl_edad,
			sdd.monto_banca_comunal as cl_monto_bc,
			sdd.tasa as cl_tasa,
			sdd.porcentaje_credito as cl_porcentaje_credito,
			scl.saldo_deudor as cl_saldo,
			sdd.id_detalle,
			sdr.id_respuesta,
			sdr.respuesta as cl_respuesta,
			sdr.observacion as cl_observacion,
			sdc.id_pr_extra,
			sdc.modalidad
		from
			s_de_cot_cabecera as sdc
				inner join
			s_de_cot_detalle as sdd ON (sdd.id_cotizacion = sdc.id_cotizacion)
				inner join
			s_de_cot_cliente as scl ON (scl.id_cliente = sdd.id_cliente)
				inner join
			s_de_cot_respuesta as sdr ON (sdr.id_detalle = sdd.id_detalle)
				inner join 
			s_entidad_financiera as sef ON (sef.id_ef = sdc.id_ef)
		where
			sdc.id_cotizacion = "' . $idc . '"
				and sef.id_ef = "' . $idef . '"
				and sef.activado = true
		order by sdd.id_detalle asc
		;';

		if (($rs = $this->cx->query($sql)) !== false) {
			if ($rs->num_rows > 0) {
				while ($row = $rs->fetch_array(MYSQLI_ASSOC)) {
					$data[] = $row;
				}
			}
		}

		return $data;
	}

	public function getPolicyData($ide, $idef)
	{
		$data = array();

		$sql = 'select 
			sdc.id_emision as idc,
			sdc.id_cotizacion,
			sdc.no_emision,
			sdc.prefijo,
			sdc.certificado_provisional as cp,
			sdc.cobertura,
			sdc.id_prcia,
			sdc.monto_solicitado as monto,
			sdc.monto_deudor,
			sdc.monto_codeudor,
			sdc.cumulo_deudor,
			sdc.cumulo_codeudor,
			sdc.moneda,
			sdc.plazo,
			sdc.tipo_plazo,
			sdc.operacion,
			sdc.no_operacion,
			sdc.id_poliza,
			scl.id_cliente,
			scl.nombre as cl_nombre,
			scl.paterno as cl_paterno,
			scl.materno as cl_materno,
			scl.ap_casada as cl_casada,
			scl.genero as cl_genero,
			scl.estado_civil as cl_estado_civil,
			scl.tipo_documento as cl_tipo_documento,
			scl.ci as cl_ci,
			scl.complemento as cl_complemento,
			scl.extension as cl_extension,
			scl.fecha_nacimiento as cl_fecha_nacimiento,
			scl.pais as cl_pais,
			scl.lugar_nacimiento as cl_lugar_nacimiento,
			scl.lugar_residencia as cl_lugar_residencia,
			scl.localidad as cl_localidad,
			scl.direccion as cl_direccion,
			scl.telefono_domicilio as cl_telefono_domicilio,
			scl.telefono_celular as cl_telefono_celular,
			scl.email as cl_email,
			scl.id_ocupacion as cl_ocupacion,
			scl.desc_ocupacion as cl_desc_ocupacion,
			scl.telefono_oficina as cl_telefono_oficina,
			scl.peso as cl_peso,
			scl.estatura as cl_estatura,
			scl.edad as cl_edad,
			scl.avenida as cl_avenida,
			scl.no_domicilio as cl_nd,
			scl.direccion_laboral as cl_direccion_laboral,
			scl.mano as cl_mano,
			sdd.monto_banca_comunal as cl_monto_bc,
			sdd.tasa as cl_tasa,
			sdd.porcentaje_credito as cl_porcentaje_credito,
			sdd.saldo as cl_saldo,
			sdd.cumulo as cl_cumulo,
			sdd.id_detalle,
			sdr.id_respuesta,
			sdr.respuesta as cl_respuesta,
			sdr.observacion as cl_observacion,
			sdc.facultativo,
			sdc.motivo_facultativo,
			sdct.id_pr_extra,
			sdc.modalidad
		from
			s_de_em_cabecera as sdc
				inner join
			s_de_cot_cabecera as sdct ON (sdct.id_cotizacion = sdc.id_cotizacion)
				inner join
			s_de_em_detalle as sdd ON (sdd.id_emision = sdc.id_emision)
				inner join
			s_cliente as scl ON (scl.id_cliente = sdd.id_cliente)
				inner join
			s_de_em_respuesta as sdr ON (sdr.id_detalle = sdd.id_detalle)
				inner join 
			s_entidad_financiera as sef ON (sef.id_ef = sdc.id_ef)
		where
			sdc.id_emision = "' . $ide . '"
				and sef.id_ef = "' . $idef . '"
				and sef.activado = true
		order by sdd.id_detalle asc
		;';
		
		if (($rs = $this->cx->query($sql)) !== false) {
			if ($rs->num_rows > 0) {
				while ($row = $rs->fetch_array(MYSQLI_ASSOC)) {
					$data[] = $row;
				}
			}
		}

		return $data;
	}

	public function getTasa($cia, $pr_cr, $idef, $product = 'DE')
	{
		$sql = 'select 
			spc.id_prcia, st.tasa_final
		from
			s_tasa_de as st
				inner join
			s_producto_cia as spc ON (spc.id_prcia = st.id_prcia)
				inner join
			s_ef_compania as sec ON (sec.id_ef_cia = spc.id_ef_cia)
				inner join
			s_compania as scia ON (scia.id_compania = sec.id_compania)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = sec.id_ef)
				inner join
			s_producto as spr ON (spr.id_producto = spc.id_producto)
		where
			spc.id_prcia = ' . $pr_cr . ' 
				and spr.activado = true
				and scia.id_compania = "' . $cia . '"
				and scia.activado = true
				and sef.id_ef = "' . $idef . '"
				and sec.activado = true
		;';

		if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT)) !== false) {
			if($rs->num_rows === 1){
				$row = $rs->fetch_array(MYSQLI_ASSOC);
				$rs->free();
				return $row['tasa_final'];
			}
		}

		return 0;
	}

	public function getPrima($amount, $tasa) 
	{
		$prima = ($amount * $tasa) / 100;
		return $prima;
	}

	public function checkPolicyFac($vars = [], $client = [])
    {
    	$sql = 'select
          sc.nombre as cl_nombre,
          sc.paterno as cl_paterno,
          sc.materno as cl_materno,
          sc.ci as cl_ci,
          sc.extension as cl_extension
        from s_de_em_cabecera as sdec
          inner join
            s_de_em_detalle as sded on (sded.id_emision = sdec.id_emision)
          inner join
        	s_cliente as sc on (sc.id_cliente = sded.id_cliente)
       	  left join
            s_de_facultativo as sdf on (sdf.id_emision = sdec.id_emision)
        where sdec.emitir = false
              and sdec.anulado = false
              and sdec.aprobado = true
              and sdec.facultativo = true
              and (if(sdf.aprobado is null,
                   true,
                   if(sdf.aprobado = "SI" and sdec.emitir = false,
                      true,
                      false))) = true
              and sdec.id_ef = "' . base64_decode($vars['idef']) . '"
              and sdec.prefijo = "' . $vars['prefix'] . '"
              and sdec.monto_solicitado = ' . $vars['dcr_amount'] . '
              and sdec.moneda = "' . $vars['dcr_currency'] . '"
              and sdec.plazo = ' . $vars['dcr_term'] . '
              and sdec.tipo_plazo = "' . $vars['dcr_type_term'] . '"
        ;';

        if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT)) !== false) {
            if ($rs->num_rows === count($client)) {
                $flag = true;
                $k = 0;

                while ($row = $rs->fetch_array(MYSQLI_ASSOC)) {
                    $k += 1;

                    $result = array_diff($client[$k], $this->row);
                    if (count($result) > 0) {
                        $flag = false;
                    }
                }

                return $flag;
            }
        }

        return false;
    }

    public function postPolicyData($vars, $arr_cl)
    {
    	$detail = true;

    	$sql = 'insert into s_de_em_cabecera 
		(id_emision, no_emision, id_ef, id_cotizacion, 
			certificado_provisional, no_operacion, prefijo, 
			prefix, cobertura, id_prcia, modalidad, 
			monto_solicitado, moneda, monto_deudor, 
			monto_codeudor, cumulo_deudor, cumulo_codeudor, 
			id_tc, plazo, tipo_plazo, id_usuario, 
			fecha_creacion, hora_creacion, anulado, and_usuario, 
			fecha_anulado, motivo_anulado, emitir, fecha_emision, 
			id_compania, id_poliza, operacion, facultativo, 
			motivo_facultativo, tasa, prima_total, no_copia, 
			no_copia_cob, leido, id_certificado) 
		values 
		("' . $vars['id'] . '", "' . $vars['record'] . '", 
			"' . $vars['idef'] . '", "' . $vars['idc'] . '", 
			false, "' . $vars['dcr_opp'] . '", "' . $vars['prefix'] . '", 
			"", "' . $vars['dcr_coverage'] . '", "' . $vars['dcr_prcia'] . '", 
			' . $vars['dcr_modality'] . ', "' . $vars['dcr_amount'] . '", 
			"' . $vars['dcr_currency'] . '", "' . $vars['dcr_amount_de'] . '", 
			"' . $vars['dcr_amount_cc'] . '", "' . $vars['dcr_amount_acc'] . '", 
			"' . $vars['dcr_amount_acc_2'] . '", "' . $vars['tipo_cm'] . '", 
			"' . $vars['dcr_term'] . '", "' . $vars['dcr_type_term'] . '", 
			"' . $vars['user'] . '", "' . date('Y-m-d') . '", 
			"' . date('H:i:s') . '", false, "' . $vars['user'] . '", 
			"", "", false, "", "' . $vars['dcr_cia'] . '", 
			' . $vars['dcr_policy'] . ', "' . $vars['dcr_type_mov'] . '", 
			' . (int)$vars['FAC'] . ', "' . $vars['fac_reason'] . '", 
			"' . $vars['tasa'] . '", "' . $vars['prima'] . '", 
			0, 0, false, "' . $vars['certificate'] . '") 
		;';

		if ($this->cx->query($sql)) {
			foreach ($arr_cl as $key => $client) {
				if ($this->verifyClient($vars, $client)) {
					if ($this->putClientData($vars, $client, true)) {
					} else {
						$detail = false;
						break;
					}
				} else {
					if ($this->postClientData($vars, $client)) {
					} else {
						$detail = false;
						break;
					}
				}
			}

			if ($detail) {
				return true;
			}
		}

    	return false;
    }

    public function putPolicyData($vars, $arr_cl)
    {
    	$detail = true;

    	$sql = 'update s_de_em_cabecera 
		set no_operacion = "' . $vars['dcr_opp'] . '", 
			id_prcia = "' . $vars['dcr_prcia'] . '", 
			modalidad = ' . $vars['dcr_modality'] . ', 
			monto_solicitado = "' . $vars['dcr_amount'] . '", 
			moneda = "' . $vars['dcr_currency'] . '", 
			monto_deudor = "' . $vars['dcr_amount_de'] . '", 
			monto_codeudor = "' . $vars['dcr_amount_cc'] . '", 
			cumulo_deudor = "' . $vars['dcr_amount_acc'] . '", 
			cumulo_codeudor = "' . $vars['dcr_amount_acc_2'] . '", 
			plazo = "' . $vars['dcr_term'] . '", 
			tipo_plazo = "' . $vars['dcr_type_term'] . '", 
			id_poliza = ' . $vars['dcr_policy'] . ', 
			operacion = "' . $vars['dcr_type_mov'] . '", 
			facultativo = ' . (int)$vars['FAC'] . ', 
			motivo_facultativo = "' . $vars['fac_reason'] . '", 
			tasa = ' . $vars['tasa'] . ', prima_total = ' . $vars['prima'] . ', 
			leido = false, id_certificado = "' . $vars['certificate'] . '"
		where id_emision = "' . $vars['id'] . '"
		;';

		if ($this->cx->query($sql)) {
			foreach ($arr_cl as $key => $client) {
				if ($this->putClientData($vars, $client)) {
				} else {
					$detail = false;
					break;
				}
				
			}

			if ($detail) {
				return true;
			}
		}

		return false;
    }

    private function postClientData($vars, $client)
    {
    	$sql = 'INSERT INTO s_cliente 
		(id_cliente, id_ef, tipo, razon_social, 
			paterno, materno, nombre, ap_casada, 
			fecha_nacimiento, lugar_nacimiento, ci, 
			extension, complemento, tipo_documento, 
			estado_civil, ci_archivo, lugar_residencia, 
			localidad, avenida, direccion, no_domicilio, 
			direccion_laboral, pais, id_ocupacion, 
			desc_ocupacion, telefono_domicilio, 
			telefono_oficina, telefono_celular, email, 
			peso, estatura, genero, edad, mano) 
		VALUES 
		("' . $client['cl_id'] . '", 
			"' . $vars['idef'] . '", 0, "", 
			"' . $client['cl-patern'] . '", 
			"' . $client['cl-matern'] . '", 
			"' . $client['cl-name'] . '", 
			"' . $client['cl-married'] . '", 
			"' . $client['cl-date'] . '", 
			"' . $client['cl-place-birth'] . '", 
			"' . $client['cl-doc-id'] . '", 
			' . $client['cl-ext'] . ', 
			"' . $client['cl-comp'] . '", 
			"' . $client['cl-type-doc'] . '", 
			"' . $client['cl-status'] . '", 
			"", ' . $client['cl-place-res'] . ', 
			"' . $client['cl-locality'] . '", 
			"' . $client['cl-avc'] . '", 
			"' . $client['cl-address-home'] . '", 
			"' . $client['cl-nhome'] . '", 
			"' . $client['cl-address-work'] . '", 
			"' . $client['cl-country'] . '", 
			' . $client['cl-occupation'] . ', 
			"' . $client['cl-desc-occ'] . '", 
			"' . $client['cl-phone-1'] . '", 
			"' . $client['cl-phone-office'] . '", 
			"' . $client['cl-phone-2'] . '", 
			"' . $client['cl-email'] . '", 
			' . $client['cl-weight'] . ', 
			' . $client['cl-height'] . ', 
			"' . $client['cl-gender'] . '", 
			TIMESTAMPDIFF(YEAR, "' . $client['cl-date'] . '", curdate()), 
			"' . $client['cl-hand'] . '") ;';
		if ($this->cx->query($sql)) {
			if ($this->postDetailData($vars, $client)) {
				return true;
			}
		}

		return false;
    }

    private function postDetailData($vars, $client)
    {
    	$sql = 'INSERT INTO s_de_em_detalle 
		(id_detalle, id_emision, id_cliente, porcentaje_credito, 
			tasa, monto_banca_comunal, saldo, cumulo, 
			facultativo, motivo_facultativo, aprobado, titular) 
		VALUES 
		("' . $client['cl-d-idd'] . '", "' . $vars['id'] . '", "' . $client['cl_id'] . '", 
			' . $client['cl-share'] . ', ' . $client['cl-tasa'] . ', 
			' . $client['cl-amount-bc'] . ', ' . $client['cl-residue'] . ', 
			' . $client['cl-accumulation'] . ', ' . (int)$client['cl-fac'] . ', 
			"' . $client['cl-fac-reason'] . '", ' . (int)$client['cl-approved'] . ', 
			"' . $client['cl-titular'] . '") ;';
		
		if ($this->cx->query($sql)) {
			if ($this->postClientResponse($vars, $client)) {
				return true;
			}
		}

		return false;
    }

    private function putClientData($vars, $client, $token = false)
    {
    	$sql = 'update s_cliente 
		set paterno = "' . $client['cl-patern'] . '", 
			materno = "' . $client['cl-matern'] . '", 
			nombre = "' . $client['cl-name'] . '", 
			ap_casada = "' . $client['cl-married'] . '", 
			fecha_nacimiento = "' . $client['cl-date'] . '", 
			lugar_nacimiento = "' . $client['cl-place-birth'] . '", 
			extension = ' . $client['cl-ext'] . ', 
			complemento = "' . $client['cl-comp'] . '", 
			tipo_documento = "' . $client['cl-type-doc'] . '", 
			estado_civil = "' . $client['cl-status'] . '", 
			lugar_residencia = ' . $client['cl-place-res'] . ', 
			localidad = "' . $client['cl-locality'] . '", 
			avenida = "' . $client['cl-avc'] . '", 
			direccion = "' . $client['cl-address-home'] . '", 
			no_domicilio = "' . $client['cl-nhome'] . '", 
			direccion_laboral = "' . $client['cl-address-work'] . '", 
			pais = "' . $client['cl-country'] . '", 
			id_ocupacion = ' . $client['cl-occupation'] . ', 
			desc_ocupacion = "' . $client['cl-desc-occ'] . '", 
			telefono_domicilio = "' . $client['cl-phone-1'] . '", 
			telefono_oficina = "' . $client['cl-phone-office'] . '", 
			telefono_celular = "' . $client['cl-phone-2'] . '", 
			email = "' . $client['cl-email'] . '", 
			genero = "' . $client['cl-gender'] . '", 
			edad = TIMESTAMPDIFF(YEAR, "' . $client['cl-date'] . '", curdate()), 
			mano = "' . $client['cl-hand'] . '", peso = ' . $client['cl-weight'] . ', 
			estatura = ' . $client['cl-height'] . '
		WHERE id_cliente = "' . $client['cl_id'] . '" ;';

		if ($this->cx->query($sql)) {
			if ($token) {
				if ($this->postDetailData($vars, $client)) {
					return true;
				}
			} else {
				if ($this->putDetailData($vars, $client)) {
					return true;
				}
			}
		}

		return false;
    }

    private function putDetailData($vars, $client)
    {
    	$sql = 'update s_de_em_detalle 
		set saldo = "' . $client['cl-residue'] . '", 
			cumulo = "' . $client['cl-accumulation'] . '", 
			facultativo = "' . (int)$client['cl-fac'] . '", 
			motivo_facultativo = "' . $client['cl-fac-reason'] . '", 
			aprobado = "' . (int)$client['cl-approved'] . '"
		where id_detalle = "' . $client['cl-q-idd'] . '"
			and id_emision = "' . $vars['id'] . '"
		;';

    	if ($this->cx->query($sql)) {
    		if ($this->putClientResponse($vars, $client)) {
    			return true;
    		}
    	}

    	return false;
    }

    private function verifyClient($vars, &$client)
    {
    	$sql = 'select 
			scl.id_cliente as idCl, scl.ci as cl_ci, scl.extension as cl_extension
		from
			s_cliente as scl
				inner join s_entidad_financiera as sef ON (sef.id_ef = scl.id_ef)
		where
			scl.ci = "' . $client['cl-doc-id'] . '" 
				and scl.extension = ' . $client['cl-ext'] . ' 
				and scl.tipo = 0 
				and sef.id_ef = "' . $vars['idef'] . '"
		;';

		if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($rs->num_rows === 1) {
				$row = $rs->fetch_array(MYSQLI_ASSOC);
				$rs->free();

				$client['cl_id'] = $row['idCl'];
				return true;
			}
		}

		return false;
    }

    private function postClientResponse($vars, $client)
    {
    	$sql = 'INSERT INTO s_de_em_respuesta 
		(id_respuesta, id_detalle, respuesta, 
			observacion, enfermedad, fecha_tratamiento, 
			duracion, tratante, estado) 
		SELECT "' . uniqid('@S#1$2013' . rand(0, 9), true) . '", 
		"' . $client['cl-d-idd'] . '", respuesta, 
		"' . $client['cl-q-resp'] . '", enfermedad, 
		fecha_tratamiento, duracion, tratante, 
		estado FROM s_de_cot_respuesta 
		WHERE id_respuesta = "' . $client['cl-q-idr'] . '" 
			AND id_detalle = "' . $client['cl-q-idd'] . '" ;';

		if ($this->cx->query($sql)) {
			return true;
		}

    	return false;
    }

    private function putClientResponse($vars, $client)
    {
    	$sql = 'update s_de_em_respuesta 
		set observacion = "' . $client['cl-q-resp'] . '"
		where 
			id_respuesta = "' . $client['cl-q-idr'] . '" 
				and id_detalle = "' . $client['cl-q-idd'] . '" ;';

		if ($this->cx->query($sql)) {
			return true;
		}

    	return false;
    }

}

?>