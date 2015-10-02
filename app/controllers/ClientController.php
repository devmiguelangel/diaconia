<?php

require_once __DIR__ . '/../../sibas-db.class.php';
require_once __DIR__ . '/../models/Diaconia.php';
require_once __DIR__ . '/../repositories/ClientRepo.php';
require_once __DIR__ . '/../controllers/QuoteController.php';

class ClientController extends Diaconia
{
    protected $cx;
    protected
        $status = array(
            'SOL' => 'Soltero(a)', 
            'CAS' => 'Casado(a)', 
            'VIU' => 'Viudo(a)',
            'DIV' => 'Divorciado(a)', 
            'CON' => 'Concubino(a)',
            'SEP' => 'Separado(a)',
            'A-C' => 'Abandono/Conyugue',
        );

    protected
        $type_doc = array(
            'CI'    => 'Carnet de Identidad', 
            'RUN'   => 'RUN', 
            'PA'    => 'Pasaporte', 
            'CE'    => 'Carnet Extranjero'
        );

    protected
        $gender = array(
            'M' => 'Masculino', 
            'F' => 'Femenino'
        );

    protected
        $hand = array(
            'DE' => 'Derecha', 
            'IZ' => 'Izquierda'
        );

    protected
        $avc = array(
            'AV' => 'Avenida', 
            'CA' => 'Calle'
        );
    
    public function __construct()
    {
        $this->cx = new SibasDB();
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getTypeDoc()
    {
        return $this->type_doc;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getHand()
    {
        return $this->hand;
    }

    public function getAvc()
    {
        return $this->avc;
    }

    public function getDepto()
    {
        $data = array();

        $sql = 'SELECT 
            sdep.id_depto,
            sdep.departamento,
            sdep.codigo,
            sdep.tipo_ci,
            sdep.tipo_re,
            sdep.tipo_dp,
            sdep.id_ef
        FROM
            s_departamento as sdep
                left join
            s_entidad_financiera as sef ON (sef.id_ef = sdep.id_ef)
        where
            sef.id_ef is null
        ORDER BY id_depto ASC
        ;';
        
        if (($rs = $this->cx->query($sql,MYSQLI_STORE_RESULT))) {
            if ($rs->num_rows > 0) {
                while ($row = $rs->fetch_array(MYSQLI_ASSOC)) {
                    $data[] = $row;
                }
            }
        }

        return $data;
    }

    public function getOccupation($idef, $product = 'DE')
    {
        $data = array();
        $idef   = $this->cx->real_escape_string(trim(base64_decode($idef)));

        $sql = 'SELECT 
            soc.id_ocupacion, 
            soc.categoria, 
            soc.ocupacion,
            soc.codigo
        FROM 
            s_ocupacion as soc
                INNER JOIN 
            s_entidad_financiera as sef ON (sef.id_ef = soc.id_ef)
        WHERe 
            soc.producto = "' . $product . '"
                and sef.id_ef = "' . $idef . '"
                and sef.activado = true
        ORDER BY id_ocupacion ASC
        ;';
        
        if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT))) {
            if ($rs->num_rows > 0) {
                while ($row = $rs->fetch_array(MYSQLI_ASSOC)) {
                    $data[] = $row;
                }
            }
        }

        return $data;
    }

    public function getOccupationCaedec($code)
    {
        $data = array();

        $sql = 'SELECT 
            soc.id_ocupacion,
            soc.categoria, 
            soc.ocupacion,
            soc.codigo
        FROM 
            s_ocupacion as soc
        WHERe 
            soc.producto = "DE"
                AND soc.codigo = "' . $code . '"
        LIMIT 0, 1
        ;';
        
        if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT))) {
            if ($rs->num_rows === 1) {
                $data = $rs->fetch_all(MYSQLI_ASSOC);
            }
        }

        return $data;
    }

    public function verifyYearUser($min_year, $max_year, $date)
    {
        $datetime1 = new DateTime($date);
        $datetime2 = new DateTime(date('Y-m-d'));

        $interval = $datetime1->diff($datetime2);
        $data = (array)$interval;

        $year = $data['y'];
        
        if ($year >= $min_year && $year <= $max_year) {
            return true;
        }

        return false;
    }

    public function postClient($data, &$mess)
    {
        $pr = $this->cx->real_escape_string(trim(base64_decode($data['pr'])));

        if ($pr === 'DE|02') {
            $arr_cl = array();

            $id_client  = 0;
            $nCl        = 0;
            $flag       = false;
            $birth_flag = true;
            $birth_mess = '';
            $bc         = false;
            $amount     = 0;

            $ms     = $this->cx->real_escape_string(trim($data['ms']));
            $page   = $this->cx->real_escape_string(trim($data['page']));
            $pr     = $this->cx->real_escape_string(trim($data['pr']));
            $data['idc']    = $this->cx->real_escape_string(trim(base64_decode($data['dc-idc'])));
            $data['idef']   = $this->cx->real_escape_string(trim(base64_decode($data['id-ef'])));
            $bc     = (boolean)$this->cx->real_escape_string(trim(base64_decode($data['dc-bc'])));
            $nCl    = (int)$this->cx->real_escape_string(trim($data['dc-ncl']));
            
            if ($nCl === 0) {
                return false;
            }

            if(isset($data['dc-idCl'])){
                $flag = true;
                $data['id'] = $this->cx->real_escape_string(trim(base64_decode($data['dc-idCl'])));
            }

            if (($data['data'] = $this->getDataProduct(base64_encode($data['idef']))) !== false) {
                if ($bc === true) {
                    $data['data']['edad_max'] = 75;
                }

                for ($k = 0; $k < $nCl; $k++) { 
                    if (isset($data['dc-name-'.$k], $data['dc-doc-id-'.$k])) {
                        $temp_data = array();
                        $temp_data['name']      = $this->cx->real_escape_string(trim($data['dc-name-'.$k]));
                        $temp_data['patern']    = $this->cx->real_escape_string(trim($data['dc-ln-patern-'.$k]));
                        $temp_data['matern']    = $this->cx->real_escape_string(trim($data['dc-ln-matern-'.$k]));
                        $temp_data['married']   = $this->cx->real_escape_string(trim($data['dc-ln-married-'.$k]));
                        $temp_data['status']    = $this->cx->real_escape_string(trim($data['dc-status-'.$k]));
                        $temp_data['type_doc']  = $this->cx->real_escape_string(trim($data['dc-type-doc-'.$k]));
                        $temp_data['doc_id']    = $this->cx->real_escape_string(trim($data['dc-doc-id-'.$k]));
                        $temp_data['comp']      = $this->cx->real_escape_string(trim($data['dc-comp-'.$k]));
                        $temp_data['ext']       = $this->cx->real_escape_string(trim($data['dc-ext-'.$k]));
                        $temp_data['country']   = $this->cx->real_escape_string(trim($data['dc-country-'.$k]));
                        $temp_data['birth']     = $this->cx->real_escape_string(trim($data['dc-date-birth-'.$k]));
                        $temp_data['place_birth'] = $this->cx->real_escape_string(trim($data['dc-place-birth-'.$k]));
                        $temp_data['place_res'] = 'null';
                        $aux_place_res = $data['dc-place-res-'.$k];
                        if (empty($aux_place_res) === false) {
                            $temp_data['place_res'] = $this->cx->real_escape_string(trim($aux_place_res));
                        }
                        $temp_data['locality']  = $this->cx->real_escape_string(trim($data['dc-locality-'.$k]));
                        $temp_data['address']   = $this->cx->real_escape_string(trim($data['dc-address-'.$k]));
                        $temp_data['phone_1']   = $this->cx->real_escape_string(trim($data['dc-phone1-'.$k]));
                        $temp_data['phone_2']   = $this->cx->real_escape_string(trim($data['dc-phone2-'.$k]));
                        $temp_data['email']     = $this->cx->real_escape_string(trim($data['dc-email-'.$k]));
                        $temp_data['phone_office']  = $this->cx->real_escape_string(trim($data['dc-phone-office-'.$k]));
                        $temp_data['occupation']    = 'null';
                        $aux_occupation = $data['dc-occupation-'.$k];
                        if (empty($aux_occupation) === false) {
                            $temp_data['occupation'] = '"' 
                                . $this->cx->real_escape_string(trim(base64_decode($aux_occupation))) . '"';
                        }
                        $temp_data['occ_desc']  = $this->cx->real_escape_string(trim($data['dc-desc-occ-'.$k]));
                        $temp_data['gender']    = $this->cx->real_escape_string(trim($data['dc-gender-'.$k]));
                        $temp_data['weight']    = $this->cx->real_escape_string(trim($data['dc-weight-'.$k]));
                        $temp_data['height']    = $this->cx->real_escape_string(trim($data['dc-height-'.$k]));
                        $temp_data['amount']    = 0;
                        if (isset($data['dc-amount-'.$k])) {
                            $temp_data['amount'] = 
                                $this->cx->real_escape_string(trim(base64_decode($data['dc-amount-'.$k])));
                        }
                        $temp_data['amount_bc'] = 0;
                        if (isset($data['dc-amount-bc-'.$k])) {
                            $temp_data['amount_bc'] = $this->cx->real_escape_string(trim($data['dc-amount-bc-'.$k]));
                        }
                        $temp_data['percentage'] = 100;
                        if(($temp_data['status'] !== 'CAS' && $temp_data['status'] !== 'VIU') 
                            || $temp_data['gender'] !== 'F'){
                            $temp_data['married'] = '';
                        }

                        $amount += $temp_data['amount_bc'];

                        $arr_cl[$k] = $temp_data;

                        $date1 = new DateTime($temp_data['birth']);
                        $date2 = new DateTime(date('Y-m-d'));
                        $interval = $date1->diff($date2);
                        $year = $interval->format('%y');

                        if ($this->verifyYearUser($data['data']['edad_min'], 
                            $data['data']['edad_max'], $temp_data['birth']) === false) {

                            $name = $temp_data['name'] . ' ' . $temp_data['patern'] . ' ' . $temp_data['matern'];
                            $birth_flag = false;
                            $birth_mess .= 'La Fecha de nacimiento del titular ' . $name 
                                . ' no esta en el rango permitido de edades <br>';
                        }
                    }
                }

                if ($birth_flag) {
                    $ClientRepo = new ClientRepo($this->cx);

                    if ($flag) {
                        $arr_cl[0]['id'] = $data['id'];
                        
                        if ($ClientRepo->putClientData($data, $arr_cl[0], $bc)) {
                            $mess[0] = 1;
                            $mess[1] = 'de-quote.php?ms=' . $ms . '&page=' . $page 
                                . '&pr=' . $pr . '&idc=' . base64_encode($data['idc']);
                            $mess[2] = 'Los Datos se actualizaron correctamente';

                            return true;
                        } else {
                            $mess[2] = 'No se pudo actualizar los datos';
                        }
                    } else {
                        if ($nCl === 1 && $bc === false) {
                            $data['dc'] = $this->getNumberClients($data['idc'], $data['idef'], false);
                        }

                        $rec = true;

                        foreach ($arr_cl as $key => $data_cl) {
                            if ($bc === true) {
                                $data_cl['dc'] = 'CC';
                            } else {
                                $data_cl['dc'] = $data['dc'];
                            }
                            
                            if ($ClientRepo->postClientData($data, $data_cl)) {
                                
                            } else {
                                $rec = false;
                                break;
                            }
                        }

                        if ($rec) {
                            if ($bc) {
                                $QuoteController = new QuoteController();
                                
                                if ($QuoteController->setDataBcCot($data['idc'])) {
                                    goto Resp2;
                                }
                            } else {
                                Resp2:
                                $mess[0] = 1;
                                $mess[1] = 'de-quote.php?ms=' . $ms . '&page=' . $page 
                                    . '&pr=' . $pr . '&idc=' . base64_encode($data['idc']);
                                $mess[2] = 'Cliente(s) registrado(s) con Éxito';

                                return true;
                            }
                        } else {
                            $mess[2] = 'No se pudo registrar al(los) Cliente(s)';
                        }
                    }
                } else {
                    $mess[2] = $birth_mess . '[ ' . $data['data']['edad_min'] 
                        . ' - ' . $data['data']['edad_max'] . ' ]';
                }
            } else {
                $mess[2] = 'Error .';
            }
        } else {
            $mess[2] = 'Error!';
        }

        return false;
    }

    public function getClient($idc, $idef, $idcl)
    {
        $idc    = $this->cx->real_escape_string(trim(base64_decode($idc)));
        $idef   = $this->cx->real_escape_string(trim(base64_decode($idef)));
        $idcl   = $this->cx->real_escape_string(trim(base64_decode($idcl)));

        $ClientRepo = new ClientRepo($this->cx);
        
        $data = $ClientRepo->getClientData($idc, $idef, $idcl);

        if (count($data) > 0) {
            return $data;
        }

        return false;
    }

    public function getListClient($idc, $idef, $max_item)
    {
        $ClientRepo = new ClientRepo($this->cx);

        $idc    = $this->cx->real_escape_string(trim(base64_decode($idc)));
        $idef   = $this->cx->real_escape_string(trim(base64_decode($idef)));

        $data = $ClientRepo->getListClientData($idc, $idef, $max_item);

        return $data;
    }

    public function getImc($weight, $height, $flag = false)
    {
        $sw = 0;
        $imc = (($weight + 100) - $height);
        if (($imc >= 0 and $imc <= 15) || ($imc < 0 && $imc >= -15)) {
            $sw = 1;
        } elseif ($imc < -15) {
            $sw = 2;
        } elseif ($imc > 15) {
            $sw = 3;
        }
        
        if ($flag === false) {
            if ($sw === 1) {
                return false;
            } elseif ($sw === 2 || $sw === 3) {
                return true;
            }
        } else {
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

}

?>