<?php
require 'configuration.class.php';
class SibasDB extends MySQLi
{
	private $config, $host, $user, $password, $db, $sql, $rs, $row;
    public static $AGENCY = false;

	public
		$product = array(
			0 => 'DE|Desgravamen', 
			1 => 'AU|Automotores', 
			2 => 'TRD|Todo Riesgo Domiciliario',
			3 => 'TRM|Todo Riesgo Equipo Móvil'),
		$hand = array(
			0 => 'DE|Derecha', 
			1 => 'IZ|Izquierda'),
		$avc = array(
			0 => 'AV|Avenida', 
			1 => 'CA|Calle'),
		$currency = array(
			0 => 'BS|Bolivianos', 
			1 => 'USD|Dolares Estadounidenses'),
		$moviment = array(
			0 => 'PU|Primera/Única', 
			1 => 'AD|Adicional', 
			2 => 'LC|Línea de Crédito'),
		$category = array(
			0 => 'OTH|Otros', 
			1 => 'RAC|Rent a Car'),
		$use = array(
			0 => 'PB|Público', 
			1 => 'PR|Particular'),
		$traction = array(
			0 => '4X2|4x2', 
			1 => '4X4|4x4', 
			2 => 'VHP|Vehículo Pesado'),
		$typeClient = array(
			0 => 'NAT|Natural', 
			1 => 'JUR|Jurídico'),
		$typeProperty = array(
			0 => 'HOME|Casa', 
			1 => 'DEPT|Departamento', 
			2 => 'BLDN|Edificio', 
			3 => 'LOCL|Local Comercial/Oficina'),
		$useProperty = array(
				0 => 'DMC|Domiciliario', 
				1 => 'COM|Comercial'),
		$stateProperty = array(
			0 => 'FINS|Terminado',
			1 => 'CONS|En construcción',
			2 => 'PRAR|En proceso de remodelación, ampliación o refacción'),
		$modDE = array (
			0 => 'CC|Capital Constante',
			1 => 'CD|Decreciente'), 
		$modAU = array (
			0 => 'NO|Normal',
			1 => 'UN|Unificada'),
		$modTRD = array (
			0 => 'PR|Prendaria',
			1 => 'HP|Hipotecaria',
			2 => 'CT|Construcción'),
		$modTRM = array (
			0 => 'RM|Rotura de Maquinaria'),
		$modTH = array (
			0 => 'TC|Tarjeta de Crédito',
			1 => 'TD|Tarjeta de Débito')
		
		;
	
	public function SibasDB()
	{
		/*$self = strtolower($_SERVER['HTTP_HOST']);
		$res = strpos($self, 'abrenet.com');
		
		if($res !== FALSE){
			$this->user = 'admin';
			$this->password = 'CoboserDB@3431#';
		}else{
			$this->user = 'root';
			$this->password = '';
		}
		
		$this->host = 'localhost';
		$this->db = 'sibas';*/
		
		$this->config = new ConfigurationSibas();
		$this->host = $this->config->host;
		$this->user = $this->config->user;
		$this->password = $this->config->password;
		$this->db = $this->config->db;
		
		@parent::__construct($this->host, $this->user, $this->password, $this->db);
		
		if(mysqli_connect_error()){
			die('Error de Conexion (' .mysqli_connect_errno().' ) '.mysqli_connect_error());
		}
		
	}

    public static function getAgency ()
    {
        return self::$AGENCY;
    }

    public function getAgencySubsidiary($idef, $subsidiary)
    {
    	if (empty($subsidiary) === true) {
    		$subsidiary = '%' . $subsidiary . '%';
    	}
    	
    	$this->sql = 'select 
    		sa.id_agencia,
    		sa.agencia,  
    		sa.codigo
    	from 
    		s_agencia as sa
    			inner join
    		s_departamento as sd ON (sd.id_depto = sa.id_depto)
    			inner join
    		s_entidad_financiera as sef ON (sef.id_ef = sa.id_ef)
    	where 
    		sd.id_depto like "' . $subsidiary . '"
    			and sef.id_ef = "' . base64_decode($idef) . '" 
    	order by sa.agencia asc 
    	;';

    	if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)) !== false) {
    		if ($this->rs->num_rows > 0) {
    			return $this->rs;
    		}
    	}

    	return false;
    }

    public function getUserSubsidiary($idef, $subsidiary, $agency = '', $user = '')
    {
    	if (empty($subsidiary) === true) {
    		$subsidiary = '%' . $subsidiary . '%';
    	}

    	$this->sql = 'select 
    		su.id_usuario,
    		su.usuario,
    		su.nombre 
    	from 
    		s_usuario as su
    			inner join
    		s_departamento as sd ON (sd.id_depto = su.id_depto)
    			left join
    		s_agencia as sa ON (sa.id_agencia = su.id_agencia)
    			inner join 
    		s_entidad_financiera as sef ON (sef.id_ef = sa.id_ef)
    	where 
    		sef.id_ef = "' . base64_decode($idef) . '"
    			and sd.id_depto like "' . $subsidiary . '"
    			and sa.id_agencia like "%' . $agency . '%"
    			and su.id_usuario != "' . $user . '"
    	;';
    	//echo $this->sql;

    	if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)) !== false) {
    		if ($this->rs->num_rows > 0) {
    			return $this->rs;
    		}
    	}

    	return false;
    }


    public function getAgencyUser ($idef, $idUser, $flag = false)
    {
        $this->sql = 'select
          sa.id_agencia,
          sa.agencia as nombre_agencia
        from s_agencia as sa
          inner join
            s_usuario as su on (su.id_agencia = sa.id_agencia)
          inner join
            s_entidad_financiera as sef on (sef.id_ef = sa.id_ef)
        where sef.id_ef = "' . base64_decode($idef) . '"
              and sef.activado = true
              and su.id_usuario = "' . base64_decode($idUser) . '"
        ;';

        if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)) !== false) {
            if ($this->rs->num_rows > 0) {
                return $this->rs;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
	
	public function get_id_root()
	{
		$this->sql = 'select 
			su.id_usuario as idUser, su.usuario
		from
			s_usuario as su
				inner join
			s_usuario_tipo as sut ON (sut.id_tipo = su.id_tipo)
		where
			sut.codigo = "ROOT"
		limit 0 , 1
		;';
		
		if(($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))){
			if($this->rs->num_rows === 1) {
				return $this->rs->fetch_array(MYSQLI_ASSOC);
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	public function verify_type_user($idUser, $idef) 
	{
		$this->sql = 'select 
		    su.id_usuario as u_id,
		    su.usuario as u_usuario,
		    su.nombre as u_nombre,
		    sut.tipo as u_tipo,
		    su.cambio_password as cw,
		    sut.codigo as u_tipo_codigo,
		    sdep.id_depto as u_depto,
		    sdep.departamento as u_nombre_depto,
		    sa.id_agencia,
		    sa.agencia
		from
		    s_usuario as su
		        inner join
		    s_ef_usuario as seu ON (seu.id_usuario = su.id_usuario)
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = seu.id_ef)
		        inner join
		    s_usuario_tipo as sut ON (sut.id_tipo = su.id_tipo)
				left join
			s_departamento as sdep ON (sdep.id_depto = su.id_depto)
				left join
			s_agencia as sa ON (sa.id_agencia = su.id_agencia)
		where
		    su.id_usuario = "'.base64_decode($idUser).'"
		        and su.activado = true
		        and sef.id_ef = "'.base64_decode($idef).'"
		        and sef.activado = true
		;';
		//echo $this->sql;
		if(($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))){
			if($this->rs->num_rows === 1) {
				$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
				$this->rs->free();
				return $this->row;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function getDataUser($user, $idef = '')
	{
		$this->sql = 'select 
			su.id_usuario,
			su.usuario,
			su.nombre,
			sut.codigo as tipo,
			sef.id_ef
		from 
			s_usuario as su
				inner join
			s_ef_usuario as seu ON (seu.id_usuario = su.id_usuario)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = seu.id_ef)
				inner join
			s_usuario_tipo as sut ON (sut.id_tipo = su.id_tipo)
		where 
			su.usuario = "' . $user . '"
				and sef.activado = true
				-- and sef.id_ef = "' . base64_decode($idef) . '"
		;';

		if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($this->rs->num_rows === 1) {
				$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
				$this->rs->free();

				return $this->row;
			}
		}

		return false;
	}

	public function get_data_user($idUser, $idef) 
	{
		$this->sql = 'select 
		    su.id_usuario as idu,
		    su.nombre as u_nombre,
		    su.usuario as u_usuario,
		    su.email as u_email
		from
		    s_usuario as su
		        inner join
		    s_ef_usuario as seu ON (seu.id_usuario = su.id_usuario)
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = seu.id_ef)
		where
		    su.id_usuario = "'.base64_decode($idUser).'"
		        and sef.id_ef = "'.base64_decode($idef).'"
		;';
		
		if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))) {
			if ($this->rs->num_rows === 1) {
				$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
				$this->rs->free();
				return $this->row;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	public function getNameHostEF ($idef)
	{
		$this->sql = 'select 
		    sef.host_ws
		from
		    s_entidad_financiera as sef
		where
		    sef.id_ef = "' . base64_decode($idef) . '"
		        and sef.activado = true
		limit 0 , 1
		;';
		
		if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($this->rs->num_rows === 1) {
				$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
				$this->rs->free();
				return $this->row['host_ws'];
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function get_product_menu($idef)
	{
		$this->sql = 'select
				sh.producto, sh.producto_nombre,
				(case sh.producto
			        when "DE" then 1
			        when "AU" then 2
			        when "TRD" then 3
			        when "TRM" then 4
			    end) as tabnum
			from
				s_sgc_home as sh
					inner join
				s_entidad_financiera as sef ON (sef.id_ef = sh.id_ef)
			where
				sef.id_ef = "'.base64_decode($idef).'"
					and sef.activado = true
					and sh.producto != "H"
			order by sh.producto asc
			;';
		
		if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))) {
			if ($this->rs->num_rows > 0) {
				return $this->rs;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

    public function setDatabcCot($idc)
    {
    	$amount = $percentage = $tasa = $tasa_pr = 0;

    	$this->sql = 'select 
			sdd.id_detalle,
			sdd.monto_banca_comunal,
			std.tasa_final as tasa_producto
		from s_de_cot_cabecera as sdc
			inner join 
				s_de_cot_detalle as sdd on (sdd.id_cotizacion = sdc.id_cotizacion)
			inner join 
				s_de_cot_cliente as scl on (scl.id_cliente = sdd.id_cliente)
			inner join 
				s_producto_cia as spc on (spc.id_prcia = sdc.id_prcia)
			inner join 
				s_tasa_de as std on (std.id_prcia = spc.id_prcia)
		where sdc.id_cotizacion = "' . $idc . '"
		;';

		if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($this->rs->num_rows > 0) {
				$err = false;

				while ($this->row = $this->rs->fetch_array(MYSQLI_ASSOC)) {
					$amount += $this->row['monto_banca_comunal'];
					$tasa_pr = $this->row['tasa_producto'];
				}

				$sql = 'update s_de_cot_cabecera as sdc
				set sdc.monto = ' . $amount . '
				where sdc.id_cotizacion = "' . $idc . '"
				;';

				if ($this->query($sql) === true) {
					if ($this->rs->data_seek(0) === true) {
						while ($this->row = $this->rs->fetch_array(MYSQLI_ASSOC)) {
							$percentage = ($this->row['monto_banca_comunal'] / $amount) * 100;
							$tasa = ($percentage * $tasa_pr) / 100;

							$sql = 'update s_de_cot_detalle as sdd
							set 
								sdd.porcentaje_credito = ' . $percentage . ',
								sdd.tasa = ' . $tasa . '
							where sdd.id_detalle = "' . $this->row['id_detalle'] . '"
							;';

							if ($this->query($sql) === false) {
								$err = true;
							}
						}
					}
				} else {
					$err = true;
				}

				if ($err === false) {
					return true;
				} elseif ($err === true) {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
    }

    public function checkPolicyFac ($data = array(), $client = array())
    {
        if (count($data) === 6) {
            $this->sql = 'select
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
                  and sdec.id_ef = "' . base64_decode($data['id_ef']) . '"
                  and sdec.prefijo = "' . $data['prefix'] . '"
                  and sdec.monto_solicitado = ' . $data['amount'] . '
                  and sdec.moneda = "' . $data['currency'] . '"
                  and sdec.plazo = ' . $data['term'] . '
                  and sdec.tipo_plazo = "' . $data['type_term'] . '"
            ;';

            if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)) !== false) {
                if ($this->rs->num_rows === count($client)) {
                    $flag = true;
                    $k = 0;

                    while ($this->row = $this->rs->fetch_array(MYSQLI_ASSOC)) {
                        $k += 1;

                        $result = array_diff($client[$k], $this->row);
                        if (count($result) > 0) {
                            $flag = false;
                        }
                    }

                    return $flag;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getExtenssionCode($code)
    {
        $this->sql = 'select
          sd.id_depto,
          sd.departamento as d_deparatmento,
          sd.codigo as d_codigo
        from s_departamento as sd
        where
          sd.codigo = "' . $code . '"
        ;';

        if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)) !== false) {
            if ($this->rs->num_rows === 1) {
                $this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
                $this->rs->free();
                return $this->row;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
	
	public function get_question($idef, $product = 'DE')
	{
		$this->sql = 'select 
		    spr.id_pregunta, spr.orden, spr.pregunta, spr.respuesta
		from
		    s_pregunta as spr
		        inner join
		    s_ef_compania as sec ON (sec.id_ef_cia = spr.id_ef_cia)
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sec.id_ef)
		        inner join
		    s_compania as scia ON (scia.id_compania = sec.id_compania)
		where
		    sef.id_ef = "'.base64_decode($idef).'"
		        and sef.activado = true
		        and scia.activado = true
		        and spr.producto = "'.$product.'"
		        and spr.activado = true
		order by spr.orden asc
		;';
		
		if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))) {
			if ($this->rs->num_rows > 0) {
				return $this->rs;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	public function get_method_payment($product, $idef)
	{
		$this->sql = 'select 
			sfp.id_forma_pago, sfp.forma_pago, sfp.codigo
		from
			s_forma_pago as sfp
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = sfp.id_ef)
		where
			sef.id_ef = "'.base64_decode($idef).'"
				and sef.activado = true
				and sfp.producto = "'.$product.'"
		order by sfp.forma_pago asc
		;';
		
		if(($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))) {
			if($this->rs->num_rows > 0) { 
				return $this->rs;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	public function get_policy($idef, $product = 'DE')
	{
		$this->sql = 'select 
			sp.id_poliza, sp.no_poliza
		from
			s_poliza sp
				inner join
			s_ef_compania as sec ON (sec.id_ef_cia = sp.id_ef_cia)
				inner join
			s_compania as scia ON (scia.id_compania = sec.id_compania)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = sec.id_ef)
		where
			sef.id_ef = "'.base64_decode($idef).'"
				and sef.activado = true
				and sp.producto = "'.$product.'"
				and curdate() between sp.fecha_ini and sp.fecha_fin
		;';
		
		if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))) {
			if ($this->rs->num_rows > 0) {
				return $this->rs;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

    public function get_occupation_code($idef, $code, $product) {
        $this->sql = 'select
          so.id_ocupacion as id_ocupacion,
          so.ocupacion as o_ocupacion,
          so.codigo as o_codigo
        from s_ocupacion as so
          inner join
            s_entidad_financiera as sef on (sef.id_ef = so.id_ef)
        where
          sef.id_ef = "' . base64_decode($idef) . '"
            and sef.activado = true
            and so.producto = "' . $product . '"
            and so.codigo = "' . $code . '"
        ;';

        if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)) !== false) {
            if ($this->rs->num_rows === 1) {
                $this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
                $this->rs->free();
                return $this->row;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
	
	public function get_imc($weight, $height, $flag = FALSE)
	{
		$sw = 0;
		$imc = (($weight + 100) - $height);
		if(($imc >= 0 and $imc <= 15) || ($imc < 0 && $imc >= -15)) {
			$sw = 1;
		} elseif($imc < -15) {
			$sw = 2;
		} elseif($imc > 15) {
			$sw = 3;
		}
		
		if($flag === FALSE){
			if($sw === 1) {
				return FALSE;
			} elseif($sw === 2 || $sw === 3) {
				return TRUE;
			}
		} elseif($flag === TRUE){
			switch($sw){
				case 1:
					return 'Peso Normal';
					break;
				case 2:
					return 'Desnutrición';
					break;
				case 3:
					return 'Sobrepeso y Obesidad';
					break;
			}
		}
	}
	
	public function get_cumulus($amount, $currency, $idef, $pr = 'DE')
	{
		$this->sql = 'select 
			sh.producto,
			(case "'.$currency.'"
				when
					"BS"
				then
					if('.$amount.' > sh.max_emision_bs,
						sh.max_emision_bs,
						1)
				when
					"USD"
				then
					if('.$amount.' > sh.max_emision_usd,
						sh.max_emision_usd,
						1)
			end) as cumulo
		from
			s_sgc_home as sh
				inner join s_entidad_financiera as sef ON (sef.id_ef = sh.id_ef)
		where
			sh.producto = "'.$pr.'"
				and sef.id_ef = "'.base64_decode($idef).'"
				and sef.activado = true
		;';
		//echo $this->sql;
		if (($this->rs = $this->query($this->sql,MYSQLI_STORE_RESULT))) {
			if($this->rs->num_rows === 1){
				$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
				$this->rs->free();
				return (int)$this->row['cumulo'];
			}else{
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	public function verify_amount($amount, $currency, $amount_max_bs, $amount_max_usd)
	{
		switch ($currency) {
		case 'BS':
			if ($amount > $amount_max_bs) {
				return true;
			}
			break;
		case 'USD':
			if ($amount > $amount_max_usd) {
				return true;
			}
			break;
		}

		return false;
	}
	
	public function get_type_exch($idef)
	{
		$this->sql = 'select 
				stc.id_tc
			from
				s_tipo_cambio as stc
					inner join
				s_entidad_financiera as sef ON (sef.id_ef = stc.id_ef)
			where
				sef.id_ef = "'.base64_decode($idef).'"
					and sef.activado = true
					and stc.activado = true
			;';
		$this->rs = $this->query($this->sql,MYSQLI_STORE_RESULT);
		if($this->rs->num_rows === 1){
			$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
			$this->rs->free();
			return (int)$this->row['id_tc'];
		}else{
			return FALSE;
		}
	}
	
	public function get_tasa($cia, $product, $idef, $pr = 'DE')
	{
		$this->sql = '';
		switch ($pr){
			case 'DE':
				$this->sql = 'select 
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
					spc.id_prcia = '.$product.' and spr.activado = true
						and scia.id_compania = "'.$cia.'"
						and scia.activado = true
						and sef.id_ef = "'.base64_decode($idef).'"
						and sec.activado = true
				;';
				break;
			case 'AU':
				
				break;
			case 'TR':
				break;
		}
		if (($this->rs = $this->query($this->sql,MYSQLI_STORE_RESULT))) {
			if($this->rs->num_rows === 1){
				$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
				$this->rs->free();
				return $this->row['tasa_final'];
			}else{
				return 0;
			}
		} else {
			return 0;
		}
	}
	
	public function get_prima($amount, $tasa, $pr = 'DE') 
	{
		$prima = ($amount * $tasa) / 100;
		return $prima;
	}
				
	public function getTypeIssueIdeproDE ($amount, $currency, $tc)
	{
		$arrTypeIssue = array (
			0 => array ('min' => 0, 		'max' => 15000),
			1 => array ('min' => 15001, 	'max' => 250000),
			2 => array ('min' => 250001, 	'max' => 714285),
			3 => array ('min' => 714285)
			);
		
		switch ($currency) {
		case 'BS':
			if (($amount / $tc) > $arrTypeIssue[0]['min'] && ($amount / $tc) <= $arrTypeIssue[0]['max']) {
				return 'FC';
			} elseif (($amount / $tc) >= $arrTypeIssue[1]['min'] && ($amount / $tc) <= $arrTypeIssue[1]['max']) {
				return 'DE';
			} elseif (($amount / $tc) >= $arrTypeIssue[2]['min'] && ($amount / $tc) <= $arrTypeIssue[2]['max']) {
				return 'FA';
			} elseif (($amount / $tc) >= $arrTypeIssue[3]['min']) {
				return 'RE';
			}
			break;
		case 'USD':
			if ($amount > $arrTypeIssue[0]['min'] && $amount <= $arrTypeIssue[0]['max']) {
				return 'FC';
			} elseif ($amount >= $arrTypeIssue[1]['min'] && $amount <= $arrTypeIssue[1]['max']) {
				return 'DE';
			} elseif ($amount >= $arrTypeIssue[2]['min'] && $amount <= $arrTypeIssue[2]['max']) {
				return 'FA';
			} elseif ($amount >= $arrTypeIssue[3]['min']) {
				return 'RE';
			}
			break;
		}
	}

	public function getNameState($state)
	{
		$this->sql = 'select se.estado 
		from s_estado as se 
		where 
			se.id_estado = ' . $state . '
		;';

		if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($this->rs->num_rows === 1) {
				$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
				$this->rs->free();
				return $this->row['estado'];
			}
		}

		return '';
	}

	public function getApprovedBc($ide)
	{
		$n_cl = $n_fc = $n_ap = $n_pe = $n_re = 0 ;
		$arr_f = array();
		$arr_p = array();

		$this->sql = 'select 
			sdd.detalle_p,
			sdd.detalle_f,
			sdd.aprobado,
			sdd.facultativo 
		from s_de_em_cabecera as sde
			inner join 
				s_de_em_detalle as sdd on (sdd.id_emision = sde.id_emision)
			inner join 
				s_cliente as sc on (sc.id_cliente = sdd.id_cliente)
		where sde.id_emision = "' . $ide . '" ;';

		if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)) !== false) {
			$n_cl = $this->rs->num_rows;
			if ($n_cl > 0) {
				while ($this->row = $this->rs->fetch_array(MYSQLI_ASSOC)) {
					if ((boolean)$this->row['aprobado'] === true 
						&& empty($this->row['detalle_f']) === true) {
						$n_ap += 1;
					}

					if ((boolean)$this->row['facultativo'] === true) {
						$n_fc += 1;
					}

					$arr_f = json_decode($this->row['detalle_f'], true);
					$arr_p = json_decode($this->row['detalle_p'], true);

					if (empty($arr_f) === true) {
						if (count($arr_p) > 0 
							|| (empty($this->row['detalle_p']) === true 
								&& (boolean)$this->row['aprobado'] === false)) {
							$n_pe += 1;
						}
					} else {
						if (count($arr_f) > 0) {
							if ($arr_f['aprobado'] === 'SI') {
								$n_ap += 1;
							} else {
								$n_re += 1;
							}
						}
					}
				}
				$this->rs->free();
			}
		}

		return array(
			'n_cl' => $n_cl, 
			'n_fc' => $n_fc, 
			'n_ap' => $n_ap,
			'n_pe' => $n_pe,
			'n_re' => $n_re
			);
	}
	
	public function get_state(&$arr_state, $row, $token, $product, $issue)
	{
		$state_bank = 0;
		if($token === 2) {
			$state_bank = (int)$row['estado_banco'];
		}
		$pr = strtolower($product);

		switch($row['estado']){
			case 'A':
				$arr_state['txt'] = 'APROBADO';
				if($token < 2){
					if ($issue === TRUE) {
						$arr_state['action'] = 'Emitir';
						$arr_state['link'] = 'fac-issue-policy.php?ide=' . base64_encode($row['ide']) 
							. '&pr=' . base64_encode($product);
					}
				}
				$arr_state['obs'] = 'APROBADO';
				$arr_state['bg'] = '';
				
				if($token === 4){
					$arr_state['action'] = 'Anular Certificado';
					$arr_state['link'] = 'cancel-policy.php?ide=' . base64_encode($row['ide']) 
						. '&pr=' . base64_encode($product);
				}
				
				break;
			case 'R':
				$arr_state['txt'] = 'RECHAZADO';
				$arr_state['obs'] = 'RECHAZADO';
				$arr_state['bg'] = '';
				
				/* */
				if ($product === 'AU' && $issue === true) {
					$sqlAu = 'select 
						sae.id_emision as ide,
						count(sad.id_emision) as noVh,
						sum(if(saf.id_facultativo is null
								and sad.facultativo = true
								and sad.aprobado = false,
							1,
							0)) as pendiente,
						sum(if(saf.aprobado = "SI"
								or (sad.facultativo = false
								and sad.aprobado = true),
							1,
							0)) as aprobado_si,
						sum(if(saf.aprobado = "NO"
								or (sad.aprobado = false
								and saf.id_facultativo is not null), 
								1, 0)) as aprobado_no
					from
						s_au_em_cabecera as sae
							inner join
						s_au_em_detalle as sad ON (sad.id_emision = sae.id_emision)
							left join
						s_au_facultativo as saf ON (saf.id_vehiculo = sad.id_vehiculo
							and saf.id_emision = sae.id_emision)
					where
						sae.id_emision = "'.$row['ide'].'"
							and sae.facultativo = true
							and sae.emitir = false
							and sae.anulado = false
					;';
					if (($this->rs = $this->query($sqlAu)) !== false) {
						if ($this->rs->num_rows === 1) {
							$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
							$this->rs->free();
							if ((int)$this->row['aprobado_si'] > 0 && (int)$this->row['pendiente'] === 0) {
								$arr_state['action'] = 'Emitir';
								$arr_state['link'] = 'fac-issue-policy.php?ide=' 
									. base64_encode($row['ide']) . '&pr=' . base64_encode($product);
							}
						}
					}
				}
				/* */
				break;
			case 'O':
				$arr_state['txt'] = 'OBSERVADO';
				$arr_state['bg'] = 'background: #009148; color: #FFF;';
				break;
			case 'S':
				$arr_state['txt'] = 'SUBSANADO/PENDIENTE';
				$arr_state['bg'] = 'background: #FFFF2D; color: #666;';
				break;
			case 'P':
				$arr_state['txt'] = 'PENDIENTE';

				if($token === 2){
					if ($state_bank === 3 && (int)$row['estado_facultativo'] === 0) {
						$arr_state['txt'] = 'PRE APROBADO';
					} elseif($state_bank === 2 && (int)$row['estado_facultativo'] === 0) {
						$arr_state['txt'] = 'APROBADO';
					}
				} elseif ($token === 5 || $token === 3) {
					if ((int)$row['estado_facultativo'] === 0) {
						$arr_state['txt'] = 'PRE APROBADO';
					}
					Approve:
                    if ($token === 5) {
                        $arr_state['action'] = 'APROBAR/RECHAZAR SOLICITUD';
                        $arr_state['link'] = 'implant-approve-policy.php?ide='.base64_encode($row['ide']).'&pr='.base64_encode($product);
                    } elseif ($token === 3) {
                        goto PreApprove;
                    }
				}
				$arr_state['bg'] = 'background: #FF3C3C; color: #FFF;';
				break;
			case 'F':
				if (($token === 2 || $token === 3 || $token === 5 || $token === 6) && (int)$row['estado_facultativo'] === 0) {
					$arr_state['txt'] = 'APROBADO FREE COVER';
				} elseif($token === 4) {
					$arr_state['txt'] = 'APROBADO FREE COVER';
				}
				if($token === 3){
                    PreApprove:
					$arr_state['action'] = 'Editar Datos';
					$arr_state['link'] = $pr . '-quote.php?ms=' . md5('MS_' . $product) 
						. '&page=91a74b6d637860983cc6c1d33bf4d292&pr=' 
						. base64_encode($product . '|05') . '&ide=' . base64_encode($row['ide']) 
						. '&flag=' . md5('i-read') . '&cia=' . base64_encode($row['id_compania']);
				} elseif($token === 4){
					$arr_state['action'] = 'Anular Certificado';
					$arr_state['link'] = 'cancel-policy.php?ide=' . base64_encode($row['ide']) . '&pr=' . base64_encode($product);
				} elseif ($token === 5) {
					goto Approve;
				} elseif ($token === 7) {
                    $arr_state['txt'] = 'SOLICITUD PENDIENTE';
                }
				break;
		}
		
		if($row['observacion'] === 'E' && $row['estado'] !== 'A'){
			$arr_state['obs'] = $row['estado_pendiente'];
			if($token === 1){
				if ($row['estado'] === 'S') {
					goto Response;
				}
				$arr_state['link'] = $pr.'-quote.php?ms=&page=&pr='.base64_encode($product.'|05').'&ide='.base64_encode($row['ide']).'&cia='.base64_encode($row['id_compania']).'&flag='.md5('i-read').'&target='.md5('ERROR-C');
				$arr_state['action'] = 'Editar Certificado';
			} elseif ($token === 0) {
				if ($row['estado'] === 'S') {
					goto Response;
				}
			}
		}elseif($row['observacion'] === 'NE' && $row['estado'] !== 'A' && $row['estado'] !== 'R'){
			$arr_state['obs'] = $row['estado_pendiente'];
			if($row['estado_codigo'] === 'AC' && $row['estado'] !== 'S' && $token === 1){
				$arr_state['link'] = 'fac-'.$pr.'-observation.php?ide='.base64_encode($row['ide']).'&resp='.md5('R0');
				$arr_state['action'] = 'Responder';
				if ($product === 'AU') {
					$arr_state['link'] .= '&idvh='.base64_encode($row['idVh']);
				}
			}elseif($row['estado_codigo'] && $row['estado'] === 'S'){
				Response:
				$arr_state['link'] = 'fac-'.$pr.'-observation.php?ide='.base64_encode($row['ide']).'&resp='.md5('R1');
				$arr_state['action'] = 'Respondido';
				if ($product === 'AU') {
					$arr_state['link'] .= '&idvh='.base64_encode($row['idVh']);
				}
			}
		}elseif($row['observacion'] === NULL && $row['estado'] !== 'A' && $row['estado'] !== 'R'){
			$arr_state['obs'] = 'NINGUNA';
			
			if ($token === 5) {
				
			}
		}
		
		if($token === 2){
			switch($state_bank){
				case 1: $arr_state['txt_bank'] = 'ANULADO'; break;
				case 2: $arr_state['txt_bank'] = 'EMITIDO'; break;
				case 3: $arr_state['txt_bank'] = 'NO EMITIDO'; break;
			}
		}
		
		if ($token === 4 && ($product === 'AU' || $product === 'TRD')) {
			$arr_state['action'] = 'Anular Certificado';
			$arr_state['link'] = 'cancel-policy.php?ide=' . base64_encode($row['ide']) . '&pr=' . base64_encode($product);
		}
		
		if ($token === 6 && ($product === 'AU' || $product === 'TRD')) {
			$arr_state['action'] = '<br>Cambiar Certificado Provisional';
			$arr_state['link'] = 'provisional-certificate.php?ide='.base64_encode($row['ide']).'&pr='.base64_encode($product);
		}
	}
	
	public function get_financial_institution_user($idUser)
	{
		$this->sql = 'select 
			sef.id_ef as idef, sef.nombre as ef_nombre
		from
			s_entidad_financiera as sef
				inner join
			s_ef_usuario as seu ON (seu.id_ef = sef.id_ef)
				inner join
			s_usuario as su ON (su.id_usuario = seu.id_usuario)
		where
			su.id_usuario = "'.base64_decode($idUser).'"
		order by sef.id_ef asc
		;';
		//echo $this->sql;
		if(($this->rs = $this->query($this->sql,MYSQLI_STORE_RESULT))){
			if($this->rs->num_rows > 0) {
				return $this->rs;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	public function get_financial_institution_offline($code)
	{
		if(empty($code) === FALSE){
			$this->sql = 'select 
				sef.id_ef as idef,
				sef.nombre as cliente,
				sef.logo as cliente_logo
			from
				s_entidad_financiera as sef
			where
				sef.codigo = "'.$code.'"
			order by sef.id_ef asc
			;';
			
			if(($this->rs = $this->query($this->sql,MYSQLI_STORE_RESULT))) {
				if($this->rs->num_rows === 1) {
					return $this->rs->fetch_array(MYSQLI_ASSOC);
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		}else {
			return FALSE;
		}
	}

	public function get_financial_institution_ins () 
	{
		$this->sql = 'select 
		    sef.id_ef as idef,
		    sef.nombre as cliente,
		    sef.logo as cliente_logo
		from
		    s_entidad_financiera as sef
		        inner join
		    s_sgc_home as sh ON (sh.id_ef = sef.id_ef)
		group by sef.id_ef
		order by sef.id_ef asc
		limit 0 , 1
		;';
		
		if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))) {
			if ($this->rs->num_rows === 1) {
				return $this->rs->fetch_array(MYSQLI_ASSOC);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function get_pr_extra ($id_ef_cia, $amount, $currency) 
	{
		$token = false;
		$this->sql = 'select 
			spe.id_pr_extra,
			spe.id_ef_cia,
			spe.rango as pr_rango,
			spe.pr_hospitalario,
			spe.pr_vida,
			spe.pr_cesante,
			spe.pr_prima
		from
			s_de_producto_extra as spe
				inner join
			s_ef_compania as sec ON (sec.id_ef_cia = spe.id_ef_cia)
		where
			sec.id_ef_cia = "'.$id_ef_cia.'"
				and sec.activado = true
		;';
		
		if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($this->rs->num_rows > 0) {
				while ($this->row = $this->rs->fetch_array(MYSQLI_ASSOC)) {
					$rank = json_decode($this->row['pr_rango'], true);
					
					switch ($currency) {
					case 'BS':
						if ($amount >= $rank[0] && $amount <= $rank[1]) {
							return $this->row;
						}
						break;
					case 'USD':
						if ($amount >= $rank[2] && $amount <= $rank[3]) {
							return $this->row;
						}
						break;
					}
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/***************************AUTOMOTORES***********************/
	public function get_max_detail($product)
	{
		$this->sql = 'SELCET sh.max_detalle 
				FROM s_sgc_home as sh 
					INNER JOIN s_entidad_financiera as sef ON (sef.id_ef = sh.id_ef)
				WHERE
					sh.producto = "'.$product.'" AND sef.id_ef = "'.base64_decode($_SESSION['idEF']).'" AND sef.activado = true ;';
		
		if(($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))){
			if($this->rs->num_rows === 1){
				$this->row = $this->rs->fetch_array(MYSQLI_STORE_RESULT);
				return (int)$this->row['max_detalle'];
			}else
				return 0;
		}else
			return 0;
	}
	
	public function get_type_vehicle($idef)
	{
		$this->sql = 'select 
				stv.id_tipo_vh as id_vh, stv.vehiculo
			from
				s_au_tipo_vehiculo as stv
					inner join
				s_entidad_financiera as sef ON (sef.id_ef = stv.id_ef)
			where
				sef.id_ef = "'.base64_decode($idef).'"
					and sef.activado = true
			order by stv.id_tipo_vh asc;';
		
		if(($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))) {
			if($this->rs->num_rows > 0) {
				return $this->rs;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	public function get_make($idef, $first = FALSE)
	{
		$limit = '';
		if($first === TRUE) {
			$limit = 'limit 0, 1';
		}
			
		$this->sql = 'select 
				sma.id_marca, sma.marca
			from
				s_au_marca as sma
					inner join
				s_entidad_financiera as sef ON (sef.id_ef = sma.id_ef)
			where
				sef.id_ef = "'.base64_decode($idef).'"
					and sef.activado = true
			order by sma.marca asc
			'.$limit.'
			;';
		
		if(($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))) {
			if($this->rs->num_rows > 0) {
				return $this->rs;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	public function get_model($idef, $marca)
	{
		$this->sql = 'select 
				smo.id_modelo, smo.modelo
			from
				s_au_modelo as smo
					inner join
				s_au_marca as sma ON (sma.id_marca = smo.id_marca)
					inner join
				s_entidad_financiera as sef ON (sef.id_ef = sma.id_ef)
			where
				sma.id_marca = "'.$marca.'"
					and sef.id_ef = "'.base64_decode($idef).'"
					and sef.activado = true
			order by smo.modelo asc
			;';
		
		if(($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))) {
			if($this->rs->num_rows > 0) {
				return $this->rs;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	public function set_model($marca, $modelo)
	{
		$idMo = uniqid('@S#1$2013',true);
		$this->sql = 'INSERT INTO s_au_modelo (`id_modelo`, `id_marca`, `modelo`)
			VALUES ("'.$idMo.'", "'.base64_decode($marca).'", "'.$modelo.'") ;';
		if($this->query($this->sql) === TRUE) {
			return base64_encode($idMo);
		} else {
			return FALSE;
		}
	}
	
	public function get_year_cot($idef, $product = 'AU')
	{
		$this->sql = 'select 
			sh.anio,
			date_format(curdate(), "%Y") as anio_max,
			date_format(date_sub(curdate(),
						interval sh.anio year),
					"%Y") as anio_min
		from
			s_sgc_home as sh
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = sh.id_ef)
		where
			sef.id_ef = "'.base64_decode($idef).'"
				and sef.activado = true
				and sh.producto = "'.$product.'"
		;';
		if(($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))) {
			if($this->rs->num_rows === 1) {
				return $this->rs->fetch_array(MYSQLI_ASSOC);
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	public function get_year_final($term, $type_term)
	{
		$year = 0;
		switch($type_term){
			case 'Y': $year = $term; break;
			case 'M': $year = round($term / 12, 0, PHP_ROUND_HALF_UP); break;
			case 'D': $year = round($term / 365, 0, PHP_ROUND_HALF_UP); break;
		}
		return (int)$year;
	}
	
	public function get_tasa_au($cia, $idef, $idc, $year)
	{
		$this->sql = 'select 
				sac.id_cotizacion as idc,
				sad.id_vehiculo as idVh,
				sta.anio as t_anio,
				sta.tasa as t_tasa,
				sai.incremento as t_incremento,
				(sta.tasa + sai.incremento) as t_tasa_final,
				(sad.valor_asegurado * (sta.tasa + sai.incremento)) / 100 as v_prima
			from
				s_au_cot_cabecera as sac
					inner join
				s_au_cot_detalle as sad ON (sad.id_cotizacion = sac.id_cotizacion)
					inner join
				s_entidad_financiera as sef ON (sef.id_ef = sac.id_ef)
					inner join
				s_ef_compania as sec ON (sec.id_ef = sef.id_ef)
					inner join
				s_compania as scia ON (scia.id_compania = sec.id_compania)
					inner join
				s_tasa_au as sta ON (sta.id_ef_cia = sec.id_ef_cia)
					inner join
				s_au_incremento as sai ON (sai.id_tasa = sta.id_tasa
					and sai.categoria = sad.categoria)
					inner join
			    s_forma_pago as sfp ON (sfp.id_forma_pago = sac.id_forma_pago)
			where
				sac.id_cotizacion = "'.$idc.'"
					and sef.id_ef = "'.$idef.'"
					and sef.activado = true
					and scia.id_compania = "'.$cia.'"
					and sec.producto = "AU"
					and scia.activado = true
					and sta.anio = (case sfp.codigo
					when "AN" then if('.$year.' < 3, '.$year.', 3)
					when "PC" then if('.$year.' < 3, '.$year.', 3)
					when "CO" then 1
				end)
			order by sad.id_vehiculo asc
			;';
		//echo $this->sql;
		if(($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))) {
			if($this->rs->num_rows > 0) {
				return $this->rs;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	public function get_tasa_year_au($cia, $idef, $category, $year, $payment)
	{
		$this->sql = 'select 
				sta.tasa as t_tasa,
				(sta.tasa + sai.incremento) as t_tasa_final,
				sta.anio as t_anio
			from
				s_tasa_au as sta
					inner join
				s_ef_compania as sec ON (sec.id_ef_cia = sta.id_ef_cia)
					inner join
				s_compania as scia ON (scia.id_compania = sec.id_compania)
					inner join
				s_entidad_financiera as sef ON (sef.id_ef = sec.id_ef)
					inner join
			    s_au_incremento as sai ON (sai.id_tasa = sta.id_tasa)
			where
				scia.id_compania = "'.base64_decode($cia).'"
					and scia.activado = true
					and sef.id_ef = "'.base64_decode($idef).'"
					and sef.activado = true
					and sta.anio = (case "'.$payment.'"
					when "AN" then if('.$year.' < 3, '.$year.', 3)
					when "PC" then if('.$year.' < 3, '.$year.', 3)
					when "CO" then 1
				end)
					and sai.categoria = "'.$category.'"
			;';
		
		if(($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))) {
			if($this->rs->num_rows === 1){
				$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
				$this->rs->free();
				return $this->row;
			}else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	public function get_tasa_trd($cia, $idef, $idc, $payment, $year)
	{
		$this->sql = 'select 
		    strc.id_cotizacion as idc,
		    strd.id_inmueble as idIn,
		    (if("'.$payment.'" != "PC",
		        (case
		            when '.$year.' = 1 then st.tasa_anio
		            when '.$year.' > 1 then st.tasa_restante
		        end),
		        st.tasa_estandar)) as i_tasa,
		    (strd.valor_asegurado * (if("'.$payment.'" != "PC",
		        (case
		            when '.$year.' = 1 then st.tasa_anio
		            when '.$year.' > 1 then st.tasa_restante
		        end),
		        st.tasa_estandar))) / 100 as i_prima
		from
		    s_trd_cot_cabecera as strc
		        inner join
		    s_trd_cot_detalle as strd ON (strd.id_cotizacion = strc.id_cotizacion)
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = strc.id_ef)
		        inner join
		    s_ef_compania as sec ON (sec.id_ef = sef.id_ef)
		        inner join
		    s_compania as scia ON (scia.id_compania = sec.id_compania)
		        inner join
		    s_tasa_trd as st ON (st.id_ef_cia = sec.id_ef_cia)
		        inner join
		    s_forma_pago as sfp ON (sfp.id_forma_pago = strc.id_forma_pago)
		where
		    strc.id_cotizacion = "'.$idc.'"
		        and sef.id_ef = "'.$idef.'"
		        and sef.activado = true
		        and scia.id_compania = "'.$cia.'"
		        and scia.activado = true
		        and sec.producto = "TRD"
		order by strd.id_inmueble asc
		;';
		//echo $this->sql;
		if(($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))) {
			if($this->rs->num_rows > 0) {
				return $this->rs;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	public function get_tasa_year_trd($cia, $idef, $payment, $year)
	{
		$this->sql = 'select 
		    (if("'.$payment.'" != "PC",
		        (case
		            when '.$year.' = 1 then st.tasa_anio
		            when '.$year.' > 1 then st.tasa_restante
		        end),
		        st.tasa_estandar)) as t_tasa_final
		from
		    s_tasa_trd as st
		        inner join
		    s_ef_compania as sec ON (sec.id_ef_cia = st.id_ef_cia)
		        inner join
		    s_compania as scia ON (scia.id_compania = sec.id_compania)
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sec.id_ef)
		where
		    sef.id_ef = "'.base64_decode($idef).'"
		        and sef.activado = true
		        and scia.id_compania = "'.base64_decode($cia).'"
		        and scia.activado = true
		        and sec.producto = "TRD"
		;';
		
		if(($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))) {
			if($this->rs->num_rows === 1){
				$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
				$this->rs->free();
				return $this->row;
			}else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	public function get_tasa_trm($cia, $idef, $idc, $year)
	{
		$this->sql = 'select 
		    strc.id_cotizacion as idc,
		    sta.anio as t_anio,
		    sta.tasa as t_tasa,
		    (strc.valor_asegurado_total * sta.tasa) / 100 as v_prima
		from
		    s_trm_cot_cabecera as strc
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = strc.id_ef)
		        inner join
		    s_ef_compania as sec ON (sec.id_ef = sef.id_ef)
		        inner join
		    s_compania as scia ON (scia.id_compania = sec.id_compania)
		        inner join
		    s_tasa_trm as sta ON (sta.id_ef_cia = sec.id_ef_cia)
		        inner join
		    s_forma_pago as sfp ON (sfp.id_forma_pago = strc.id_forma_pago)
		where
			strc.id_cotizacion = "'.$idc.'"
				and sef.id_ef = "'.$idef.'"
				and sef.activado = true
				and scia.id_compania = "'.$cia.'"
				and sec.producto = "TRM"
				and scia.activado = true
				and sta.anio = (case sfp.codigo
				when "AN" then if('.$year.' < 3, '.$year.', 3)
				when "PC" then if('.$year.' < 3, '.$year.', 3)
				when "CO" then 1
			end)
		;';
		//echo $this->sql;
		if(($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))) {
			if($this->rs->num_rows === 1) {
				return $this->rs;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	public function get_tasa_year_trm($cia, $idef, $year, $payment)
	{
		$this->sql = 'select 
				sta.tasa as t_tasa_final,
				sta.anio as t_anio
			from
				s_tasa_trm as sta
					inner join
				s_ef_compania as sec ON (sec.id_ef_cia = sta.id_ef_cia)
					inner join
				s_compania as scia ON (scia.id_compania = sec.id_compania)
					inner join
				s_entidad_financiera as sef ON (sef.id_ef = sec.id_ef)
			where
				scia.id_compania = "'.base64_decode($cia).'"
					and scia.activado = true
					and sef.id_ef = "'.base64_decode($idef).'"
					and sef.activado = true
					and sta.anio = (case "'.$payment.'"
					when "AN" then if('.$year.' < 3, '.$year.', 3)
					when "PC" then if('.$year.' < 3, '.$year.', 3)
					when "CO" then 1
				end)
			;';
		
		if(($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))) {
			if($this->rs->num_rows === 1){
				$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
				$this->rs->free();
				return $this->row;
			}else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	public function verify_billing($product, $idef)
	{
		$this->sql = 'select 
				sh.producto, sh.facturacion
			from
				s_sgc_home as sh
					inner join
				s_entidad_financiera as sef ON (sef.id_ef = sh.id_ef)
			where
				sef.id_ef = "'.base64_decode($idef).'"
					and sef.activado = true
					and sh.producto = "'.$product.'"
			;';
		if(($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))) {
			if($this->rs->num_rows === 1) {
				$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
				$this->rs->free();
				switch((int)$this->row['facturacion']) {
					case 0: return 'NO'; break;
					case 1: return 'SI'; break;
				}
			}else {
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	
	public function verify_implant($idef, $pr = '') 
	{
		if (empty($pr) === TRUE) {
			$pr = '%'.$pr.'%';
		}
				
		$this->sql = 'select 
		    count(sh.producto) as nPr
		from
		    s_sgc_home as sh
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sh.id_ef)
		where
		    sh.implante = true
		    	and sh.producto like "'.$pr.'"
		        and sef.id_ef = "'.base64_decode($idef).'"
		        and sef.activado = true
		;';
		
		if ($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)) {
			$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
			$this->rs->free();
			$nPr = (int)$this->row['nPr'];
			if ($nPr > 0) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	public function verify_agency_issuing($idUser, $idef, $pr)
	{
		$this->sql = 'select 
		    count(sag.id_agencia) as flag
		from
		    s_usuario as su
		        inner join
		    s_agencia as sag ON (sag.id_agencia = su.id_agencia)
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sag.id_ef)
		where
		    su.id_usuario = "'.base64_decode($idUser).'"
		        and sef.id_ef = "'.base64_decode($idef).'"
		        and sef.activado = true
		        and sag.emision = true
		;';
		//echo $this->sql;
		if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))) {
			$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
			$this->rs->free();
			$flag = (int)$this->row['flag'];
			if ($flag === 1) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
		
	} 
	
	public function get_user_implant($idUser, $idef, $pr)
	{
		$this->sql = 'select 
		    su.id_usuario as u_id,
		    su.usuario as u_usuario,
		    su.nombre as u_nombre,
		    su.email as u_email,
		    siu.id_usuario as i_id,
		    siu.usuario as i_usuario,
		    siu.nombre as i_nombre,
		    siu.email as i_email,
		    sef.id_ef as ef_id,
		    sef.nombre as ef_nombre,
		    sef.logo as ef_logo,
		    siua.id_agencia as ag_id,
			sag.agencia as ag_agencia
		from
		    s_usuario as su
		        inner join
		    s_agencia as sag ON (sag.id_agencia = su.id_agencia)
		        inner join
		    s_im_usuario_agencia as siua ON (siua.id_agencia = sag.id_agencia)
		        inner join
		    s_usuario as siu ON (siu.id_usuario = siua.id_usuario)
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sag.id_ef)
		where
		    su.id_usuario = "'.base64_decode($idUser).'"
		        and sef.id_ef = "'.base64_decode($idef).'"
		        and sef.activado = true
		;';
		//echo $this->sql;
		if(($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))) {
			if($this->rs->num_rows > 0) {
				return $this->rs;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	public function get_agency_implant($idUser, $idef, $type = 'IMP')
	{
		$this->sql = 'select 
		    su.id_usuario as i_id,
		    su.usuario as i_usuario,
		    su.nombre as i_nombre,
		    sag.id_agencia as ag_id,
		    sag.agencia as ag_agencia
		from
		    s_usuario as su
		        inner join
		    s_im_usuario_agencia as siua ON (siua.id_usuario = su.id_usuario)
		        inner join
		    s_agencia as sag ON (sag.id_agencia = siua.id_agencia)
		        inner join
		    s_usuario_tipo as sut ON (sut.id_tipo = su.id_tipo)
		        inner join
		    s_ef_usuario as seu ON (seu.id_usuario = su.id_usuario)
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = seu.id_ef)
		where
		    su.id_usuario = "'.base64_decode($idUser).'"
		        and sut.codigo = "'.$type.'"
		        and sef.id_ef = "'.base64_decode($idef).'"
		        and sef.activado = true
		order by sag.id_agencia asc
		;';
		
		if(($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))) {
			if($this->rs->num_rows > 0) {
				return $this->rs;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

    public function getCard ()
	{
		$this->sql = 'select 
		    st.id_tarjeta, st.tarjeta, st.codigo
		from
		    s_th_tarjeta st
		order by st.id_tarjeta asc
		;';
		
		if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($this->rs->num_rows > 0) {
				return $this->rs;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function getMakeCard ($idef) 
	{
		$this->sql = 'select 
		    stm.id_marca, stm.marca as m_marca
		from
		    s_th_marca as stm
		        inner join
		    s_ef_compania as sec ON (sec.id_ef_cia = stm.id_ef_cia)
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sec.id_ef)
		where
		    sef.id_ef = "'.base64_decode($idef).'"
		        and sef.activado = true
		        and stm.activado = true
		order by stm.id_marca asc
		;';
		
		if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($this->rs->num_rows > 0) {
				return $this->rs;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function verifyModality ($idef, $product) 
	{
		$this->sql = 'select 
		    sef.id_ef as idef,
		    sh.producto as ef_producto,
		    sh.modalidad as ef_modalidad
		from
		    s_sgc_home as sh
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sh.id_ef)
		where
		    sef.id_ef = "'.base64_decode($idef).'"
		        and sef.activado = true
		        and sh.modalidad = true
		        and sh.producto = "'.$product.'"
		;';
		
		if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($this->rs->num_rows === 1) {
				$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
				$this->rs->free();
				if (true === (boolean)$this->row['ef_modalidad']) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function getModality ($idef, $product) 
	{
		$this->sql = 'select 
		    smo.id_modalidad,
		    smo.modalidad as m_modalidad,
		    smo.codigo as m_codigo
		from
		    s_modalidad as smo
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = smo.id_ef)
		where
		    sef.id_ef = "'.base64_decode($idef).'"
		        and sef.activado = true
		        and smo.producto = "'.$product.'"
		        and smo.activado = true
		;';
		
		if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($this->rs->num_rows > 0) {
				return $this->rs;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function getRegistrationNumber ($idef, $product, $token, $prefix = '') 
	{
		$arrTable = array (
			'DE'	=> array (0 => 's_de_cot_cabecera',		1 => 's_de_em_cabecera',	2 => ''),
			'AU'	=> array (0 => 's_au_cot_cabecera',		1 => 's_au_em_cabecera',	2 => 's_au_em_detalle'),
			'TRD'	=> array (0 => 's_trd_cot_cabecera',	1 => 's_trd_em_cabecera',	2 => 's_trd_em_detalle'),
			'TRM'	=> array (0 => 's_trm_cot_cabecera',	1 => 's_trm_em_cabecera',	2 => 's_trm_em_detalle'),
			'TH'	=> array (0 => 's_th_cot_cabecera',		1 => ''),
			);
		
		$field = $table = '';
		$fieldPrefix = 'tbl1.prefijo';
		$table = $arrTable[$product][$token];
		$flag = true;
		
		switch ($token) {
		case 0:
			$field = 'tbl1.no_cotizacion';
			$flag = false;
			break;
		case 1:
			$field = 'tbl1.no_emision';
			break;
		case 2:
			$field = 'tbl2.no_detalle';
			$fieldPrefix = 'tbl2.prefijo';
			$table = $arrTable[$product][1];
			break;
		}

		$this->sql = 'select 
		    max(' . $field . ') + 1 as record
		from
		    ' . $table . ' as tbl1 ';
		if ($token === 2) {
			$this->sql .= '
				inner join
		    ' . $table = $arrTable[$product][2] . ' as tbl2 ON (tbl2.id_emision = tbl1.id_emision) ';
		}
		$this->sql .= '
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = tbl1.id_ef)
		where
		    sef.id_ef = "' . base64_decode($idef) . '"
				and sef.activado = true ';
		if ($token > 0 || empty($prefix) === false) {
			$this->sql .= '
				and ' . $fieldPrefix . ' = "' . $prefix . '" ';
		}
		$this->sql .= ';';
		
		if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($this->rs->num_rows === 1) {
				$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
				if ($this->row['record'] === null) {
					return 1;
				} else {
					return (int)$this->row['record'];
				}
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}

	// Prefix
	public function getPrefixPolicyIdeproDE ($currency, $modality, &$prefix)
	{
		$arrPrefixPolicy = array (
			1 => array (0 => 'CCB', 1 => 'POL-DH-LP-00000104-2013-00'),
			2 => array (0 => 'CCD', 1 => 'POL-DH-LP-00000105-2013-00'),
			3 => array (0 => 'CDB', 1 => 'POL-DH-LP-00000106-2013-00'),
			4 => array (0 => 'CDD', 1 => 'POL-DH-LP-00000107-2013-00')
			);
		
		switch ($currency) {
		case 'BS':
			switch ($modality) {
			case 'CC':
				$prefix = $arrPrefixPolicy[1];
				break;
			case 'CD':
				$prefix = $arrPrefixPolicy[3];
				break;
			}
			break;
		case 'USD':
			switch ($modality) {
			case 'CC':
				$prefix = $arrPrefixPolicy[2];
				break;
			case 'CD':
				$prefix = $arrPrefixPolicy[4];
				break;
			}
			break;
		}
	}
	
	public function getPrefixPolicyBanecoTH ($cardType, &$prefix) {
		$arrPrefixPolicy = array (
			1 => array (0 => 'PTC', 1 => 'PTC-SC-00001-01-2010'),
			2 => array (0 => 'PTD', 1 => 'PTC-SC-00002-02-2011')
			);
		
		switch ($cardType) {
		case 'TC':
			$prefix = $arrPrefixPolicy[1];
			break;
		case 'TD':
			$prefix = $arrPrefixPolicy[2];
			break;
		}
	}

	public function getPrefixPolicyBanecoTRM (&$prefix) {
		$arrPrefixPolicy = array (
			1 => array (0 => 'RMQ', 1 => 'RMQ-SC-00018-03-2011')
			);
		
		$prefix = $arrPrefixPolicy[1];
	}
	
	public function getPrefixPolicyBanecoTRD ($modality, $methodPayment, &$prefix) {
		$arrPrefixPolicy = array (
			1 => array (0 => 'TRDM',	1 => 'TRD-SC-00108-03-2011'),
			2 => array (0 => 'TRDU',	1 => 'TRD-SC-00109-03-2011'),
			3 => array (0 => 'INCM',	1 => 'INC-SC-00026-03-2011'),
			4 => array (0 => 'INCU',	1 => 'INC-SC-00027-03-2011'),
			5 => array (0 => 'CAR',		1 => 'CAR-SC-00025-00-2011')
			);
		
		switch ($modality) {
		case 'PR':
			switch ($methodPayment) {
			case 'AN':	//Mensual
				$prefix = $arrPrefixPolicy[1];
				break;
			case 'CO':	//Unica
				$prefix = $arrPrefixPolicy[2];
				break;
			}
			break;
		case 'HP':
			switch ($methodPayment) {
			case 'AN':	//Mensual
				$prefix = $arrPrefixPolicy[3];
				break;
			case 'CO':	//Unica
				$prefix = $arrPrefixPolicy[4];
				break;
			}
			break;
		case 'CT':
			$prefix = $arrPrefixPolicy[5];
			break;
		}
	}

	public function getPrefixPolicyBanecoAU($modality, $methodPayment, $use, &$prefix) {
		$arrPrefixPolicy = array (
			1 => array (0 => 'AUPRM',	1 => 'AUL-SC-00761-03-2011'),
			2 => array (0 => 'AUPBM',	1 => 'AUL-SC-00762-03-2011'),
			3 => array (0 => 'AUPRU',	1 => 'AUL-SC-00763-03-2011'),
			4 => array (0 => 'AUPBU',	1 => 'AUL-SC-00764-03-2011'),
			5 => array (0 => 'AULU',	1 => 'AUL-SC-00777-00-2011')
			);
		
		switch ($modality) {
		case 'NO':
			switch ($methodPayment) {
			case 'AN':	//Mensual
				switch ($use) {
				case 'PR':
					$prefix = $arrPrefixPolicy[1];
					break;
				case 'PB':
					$prefix = $arrPrefixPolicy[2];
					break;
				}
				break;
			case 'CO':	//Anualizado
				switch ($use) {
				case 'PR':
					$prefix = $arrPrefixPolicy[3];
					break;
				case 'PB':
					$prefix = $arrPrefixPolicy[4];
					break;
				}
				break;
			}
			break;
		case 'UN':
			$prefix = $arrPrefixPolicy[5];
			break;
		}
	}

	// End Prefix
	
	/*************************HOME**********************/
	//LOGO ENTIDAD FINANCIERA
	
	public function get_financial_institution($user_id, $token)
	{
		if($token === FALSE && $user_id === NULL){
			$this->sql = 'SELECT cliente, cliente_logo FROM s_sgc_home WHERE producto = "H" LIMIT 0, 1 ;';
		}elseif($token === TRUE && $user_id !== NULL){
			$this->sql = 'SELECT su.id_usuario, sef.id_ef, sef.nombre as cliente, sef.logo as cliente_logo
				FROM 
					s_usuario as su
						INNER JOIN 
					s_ef_usuario as seu ON (seu.id_usuario = su.id_usuario)
						INNER JOIN 
					s_entidad_financiera as sef ON (sef.id_ef = seu.id_ef)
				WHERE 
					su.id_usuario = "'.$user_id.'" AND su.activado = true AND sef.id_ef = "'.base64_decode($_SESSION['idEF']).'" AND sef.activado = true
				LIMIT 0, 1
				;';
		}
		if(($this->rs = $this->query($this->sql,MYSQLI_STORE_RESULT))){
			$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
			$this->rs->free();
			return $this->row;
		}else{
			return array('', '');
		}
	}
	
	public function getFinancialInstitutionCompany ($idef)
	{
		$this->sql = 'select 
		    scia.id_compania as idcia,
		    scia.nombre as c_nombre,
		    scia.logo as c_logo
		from
		    s_compania as scia
		        inner join
		    s_ef_compania as sec ON (sec.id_compania = scia.id_compania)
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sec.id_ef)
		where
		    sef.id_ef = "' . base64_decode($idef) . '"
		        and sef.activado = true
		        and sec.activado = true
		        and scia.activado = true
		group by scia.id_compania
		;';
		
		if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($this->rs->num_rows > 0) {
				return $this->rs;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	//SLIDERS
	public function get_slider_content($idef, $type, $token)
	{
		$this->sql = 'SELECT ss.id_slider, ss.imagen, ss.descripcion 
			FROM s_sgc_slider as ss
				INNER JOIN s_sgc_home as sh ON (sh.id_home = ss.id_home )
				INNER JOIN s_entidad_financiera as sef ON (sef.id_ef = sh.id_ef )
			WHERE ss.tipo = "'.$type.'"
					AND sh.producto = "H"
					AND sef.id_ef = "'.base64_decode($idef).'"
					AND sef.activado = TRUE
			ORDER BY ss.id_slider ASC ;';
		//echo $this->sql;
		if(($this->rs = $this->query($this->sql,MYSQLI_STORE_RESULT))){
			return $this->rs;
		}else{
			return '';
		}
	}
	
	//CONTENIDO - NOSOTROS
	public function get_home_content($idef, $product, $token)
	{
		$this->sql = 'select 
		    sh.html,
		    sh.imagen,
		    sh.nosotros,
		    sh.producto_nombre as home_title,
		    sh.certificado_provisional as cp
		from
		    s_sgc_home as sh
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sh.id_ef)
		where
		    producto = "'.$product.'"
		        and sef.id_ef = "'.base64_decode($idef).'"
		        and sef.activado = true
		;';
		//echo $this->sql;
		if(($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT))){
			if ($this->rs->num_rows === 1) {
				$this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
				$this->rs->free();
				return $this->row;
			} else {
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	
	//FORMULARIOS
	public function get_home_forms($idef)
	{
		$this->sql = 'select 
			sf.id_formulario,
			sf.archivo as f_archivo,
			sf.titulo as f_titulo,
			sh.producto as f_producto,
			(case sh.producto
				when "DE" then "Desgravamen"
				when "AU" then "Automotores"
				when "TR" then "Todoriesgo"
			end) as f_producto_text
		from
			s_sgc_formulario as sf
				inner join
			s_sgc_home as sh ON (sh.id_home = sf.id_home)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = sh.id_ef)
		where
			sh.id_home != "H"
				and sef.id_ef = "'.base64_decode($idef).'"
				and sef.activado = true
		order by sh.id_home asc
		;';
		
		if(($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)))
			return $this->rs;
		else
			return FALSE;
	}
	
	//PREGUNTAS FRECUENTES
	public function get_faqs($idef)
	{
		$this->sql = 'select 
			sh.id_home,
			sh.producto,
			sh.producto_nombre,
			sh.preguntas_frecuentes
		from
			s_sgc_home as sh
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = sh.id_ef)
		where
			sef.id_ef = "'.base64_decode($idef).'"
				and sef.activado = true
				and sh.producto != "H"
		order by sh.producto asc
		;';
		
		if(($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)))
			if($this->rs->num_rows > 0){
				return $this->rs;
			}else
				return FALSE;
		else
			return FALSE;
	}
	
}
?>