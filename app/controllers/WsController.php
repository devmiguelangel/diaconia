<?php

require_once __DIR__ . '/../../sibas-db.class.php';
require_once __DIR__ . '/../models/Diaconia.php';
require_once __DIR__ . '/../repositories/WsRepo.php';
require_once __DIR__ . '/ClientController.php';

class WsController extends Diaconia
{
    protected 
        $cx,
        $idef,
        $ws,
        $bc,
        $dni,
        $ClientController,
        $depto;

    protected
        $gender = array(
            1 => 'M',
            2 => 'F'
        ),
        $status = array(
            'SOLTERO(A)'        => 'SOL',
            'CASADO(A)'         => 'CAS',
            'VIUDO(A)'          => 'VIU',
            'DIVORCIADO(A)'     => 'DIV',
            'CONCUBINATO'       => 'CON',
            'SEPARADO(A)'       => 'SEP',
            'ABANDONO CONYUGUE' => 'A-C',
        );

    public function __construct($idef, $ws, $bc, $dni)
    {
        $this->ClientController = new ClientController();
        $this->depto = $this->ClientController->getDepto();

        $this->cx   = new SibasDB();
        $this->idef = $this->cx->real_escape_string(trim(base64_decode($idef)));
        $this->ws   = $ws;
        $this->bc   = $bc;
        $this->dni  = $this->cx->real_escape_string(trim($dni));
    }

    public function getClientData(&$arr_cl)
    {
        $WsRepo = new WsRepo($this->cx, $this->ws, $this->bc, $this->dni);
        $data   = $WsRepo->getData();

        if (count($data['client']) > 0) {
            $arr_cl = array();

            foreach ($data['client'] as $key => $value) {
                if ($this->ws) {
                    $value['status']    = $this->status[$value['status']];
                    $value['gender']    = $this->gender[$value['gender']];
                    $value['ext']       = $this->getExtension($value['ext']);

                    $dataOcc = $this->ClientController->getOccupationCaedec($value['caedec']);
                    
                    if (count($dataOcc) === 4) {
                        $value['occupation']  = $dataOcc['id_ocupacion'];
                        $value['occ_desc']    = trim($dataOcc['ocupacion']);
                    }
                }
                
                $arr_cl[] = $value;
            }
        }

        return $data;
    }

    private function setClientData($data)
    {
        foreach ($data as $key => $row) {
            # code...
        }
    }

    private function getExtension($ext) {
        $id = '';
        
        foreach ($this->depto as $key => $value) {
            if ((boolean)$value['tipo_ci'] && $value['codigo'] === $ext) {
                $id = $value['id_depto'];

                break;
            }
        }

        return $id;
    }
}

?>