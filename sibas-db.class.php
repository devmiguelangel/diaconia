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

    public function getResetPassword($id_user, &$data)
    {
        $data = array(
            'mess'      => false,
            'action'    => false,
            'days'      => 0
        );

        $this->sql = 'select 
            su.id_usuario,
            date_password
        from
            s_usuario as su
        where
            su.id_usuario = "' . base64_decode($id_user) . '"
                and su.activado = true
                and su.cambio_password = true
        limit 0, 1
        ;';
        // echo $this->sql;
        if (($this->rs = $this->query($this->sql, MYSQLI_STORE_RESULT)) !== false) {
            if ($this->rs->num_rows === 1) {
                $this->row = $this->rs->fetch_array(MYSQLI_ASSOC);
                $this->rs->free();

                $date_pass  = new DateTime(date('Y-m-d', strtotime($this->row['date_password'])));
                $date_now   = new DateTime(date('Y-m-d'));
                $interval   = $date_pass->diff($date_now);
                // $data        = (array)$interval;
                $day_lap    = (int)$interval->format('%a%');
                $day_rem    = 90 - $day_lap;

                if ($day_rem <= 0) {
                    $data['action'] = true;

                    $sql = 'update s_usuario
                    set 
                        activado = false
                    where
                        id_usuario = "' . base64_decode($id_user) . '"
                    ;';

                    if ($this->query($sql)) {
                    }
                } elseif ($day_rem <= 7) {
                    $data['mess'] = true;
                    $data['days'] = $day_rem;
                }

                return true;
            }
        }

        return false;
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