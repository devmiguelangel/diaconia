<?php 

require_once __DIR__ . '/../models/Diaconia.php';
require_once __DIR__ . '/../../sibas-db.class.php';

class DiaconiaController extends Diaconia
{
    protected $cx;
    protected $bc = false;

    public function __construct()
    {
        $this->cx = new SibasDB();
    }
    
    public function getProductData($idef)
    {
        return $this->getProduct($idef);
    }

    public function getBc()
    {
        return $this->bc;
    }

    public function checkBancaComunal($id, $type = false)
    {
        $id = $this->cx->real_escape_string(trim(base64_decode($id)));

        $tbl = array('s_de_cot_cabecera', 'id_cotizacion');
        
        if ($type === true) {
            $tbl = array('s_de_em_cabecera', 'id_emision');
        }

        $sql = 'select 
            count(spc.id_prcia) as flag
        from 
            ' . $tbl[0] . ' as sdc
                inner join
            s_producto_cia as spc on (spc.id_prcia = sdc.id_prcia)
        where 
            sdc.' . $tbl[1] . ' = "' . $id . '"
                and lower(spc.nombre) = "banca comunal"
                and sdc.cobertura = 2
        ;';
        
        if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT)) !== false) {
            if ($rs->num_rows === 1) {
                $row = $rs->fetch_array(MYSQLI_ASSOC);
                $rs->free();

                if ((int)$row['flag'] === 1) {
                    return true;
                }
            }
        }

        return false;
    }
    
    public function getMaxItem($id, $idef, $product = 'DE')
    {
        $id     = $this->cx->real_escape_string(trim(base64_decode($id)));
        $idef   = $this->cx->real_escape_string(trim(base64_decode($idef)));

        $sql = 'select 
            sdc.cobertura
        from 
            s_de_cot_cabecera as sdc
        where 
            sdc.id_cotizacion = "' . $id . '"
        limit 0, 1
        ;';

        if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT)) !== false) {
            if ($rs->num_rows === 1) {
                $row = $rs->fetch_array(MYSQLI_ASSOC);
                $rs->free();

                switch ((int)$row['cobertura']) {
                case 1:
                    return 2;
                    break;
                case 2:
                    if (($data = $this->getDataProduct(base64_encode($idef), $product)) !== false) {
                        return (int)$data['max_detalle'];
                    }
                    break;
                }
            }
        }

        return 0;
    }

    public function checkWs($idef, $product = 'DE')
    {
        $idef   = $this->cx->real_escape_string(trim(base64_decode($idef)));

        if (($data = $this->getProductData($idef, $product)) !== false) {
            return (boolean)$data['ws'];
        }

        return false;
    }
}

?>