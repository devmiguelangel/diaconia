<?php
require('sibas-db.class.php');

session_start();
$arrResult = array(0 =>  '', 1 => '');
if(isset($_GET['idCl'])){
	$link = new SibasDB();

	$result = '';
	$flag = FALSE;
	$np = NULL;
	$np_aux1 = '';
	$readonly = 'readonly';
	if(isset($_GET['np'])){
		$np = $link->real_escape_string(trim($_GET['np']));
		$np_aux1 = $np;
		if(empty($np) === TRUE){
			$np = 1;
			$np_aux1 .= $np.'|';
		}else{
			$np = explode('|', $np);
			$np = max($np) + 1;
			$np_aux1 .= $np.'|';
		}
		$flag = TRUE;
		$readonly = '';
	}

	$idCl = $link->real_escape_string(trim(base64_decode($_GET['idCl'])));
	$arr_Product = array(
			0 => 'DE|Desgravamen',
			1 => 'AU|Automotores',
			2 => 'TRD|Todo Riesgo Domiciliario',
			3 => 'TRM|Todo Riesgo Equipo Movil',
			4 => 'CCB|Desgravamen',
			5 => 'CCD|Desgravamen',
			6 => 'CDB|Desgravamen',
			7 => 'CDD|Desgravamen',
			8 => 'VG|Desgravamen');
	$arr_Term = array(0 => 'Y|Años', 1 => 'M|Meses', 2 => 'W|Semanas', 3 => 'D|Días');
	$arr_Currency = array(0 => 'BS|Bolivianos', 1 => 'USD|Dolares');
	$arr_Loan = array(
			0 => 'PEBBCC|Prestamo Externo BBCC',
			1 => 'PIBBCC|Prestamo Interno BBCC',
			2 => 'CO|Credito Oportuno',
			3 => 'CI|Crédito Individual',
			);
	if($flag === FALSE){
		$sql = 'select
			sde.id_emision as ide,
			sde.no_emision as rc_no_emision,
			spl.no_poliza as rc_no_poliza,
			sde.prefix,
			sde.prefijo as rc_producto,
			sde.no_operacion as rc_no_operacion,
			sde.plazo as rc_plazo,
			sde.tipo_plazo as rc_tipo_plazo,
			sde.fecha_emision as rc_fecha_desembolso,
			sde.monto_solicitado as rc_monto_desembolso,
			sde.moneda as rc_moneda
		from
			s_de_em_cabecera as sde
				inner join
			s_de_em_detalle as sdd ON (sdd.id_emision = sde.id_emision)
				inner join
			s_cliente as scl ON (scl.id_cliente = sdd.id_cliente)
				inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sde.id_ef)
				left join
			s_poliza as spl ON (spl.id_poliza = sde.id_poliza)
		where
			scl.id_cliente = "'.$idCl.'"
				and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
		        and sef.activado = true
				and sde.emitir = true
				and sde.anulado = false
		group by sde.id_emision
		union select
			sae.id_emision as ide,
			sae.no_emision as rc_no_emision,
			spl.no_poliza as rc_no_poliza,
			"" as prefix,
			"AU" as rc_producto,
			sae.no_operacion as rc_no_operacion,
			sae.plazo as rc_plazo,
			sae.tipo_plazo as rc_tipo_plazo,
			sae.fecha_emision as rc_fecha_desembolso,
			sum(sad.valor_asegurado) as rc_monto_desembolso,
			"USD" as rc_moneda
		from
			s_au_em_cabecera as sae
				inner join
			s_au_em_detalle as sad ON (sad.id_emision = sae.id_emision)
				inner join
			s_cliente as scl ON (scl.id_cliente = sae.id_cliente)
				inner join
		    s_entidad_financiera as sef ON (sef.id_ef = sae.id_ef)
				inner join
			s_poliza as spl ON (spl.id_poliza = sae.id_poliza)
		where
			scl.id_cliente = "'.$idCl.'"
				and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
		        and sef.activado = true
				and sae.emitir = true
				and sae.anulado = false
		group by sae.id_emision
		union select
			stre.id_emision as ide,
			stre.no_emision as rc_no_emision,
			spl.no_poliza as rc_no_poliza,
			"" as prefix,
			"TRD" as rc_producto,
			stre.no_operacion as rc_no_operacion,
			stre.plazo as rc_plazo,
			stre.tipo_plazo as rc_tipo_plazo,
			stre.fecha_emision as rc_fecha_desembolso,
			sum(strd.valor_asegurado) as rc_monto_desembolso,
			"USD" as rc_moneda
		from
			s_trd_em_cabecera as stre
				inner join
			s_trd_em_detalle as strd ON (strd.id_emision = stre.id_emision)
				inner join
			s_cliente as scl ON (scl.id_cliente = stre.id_cliente)
				inner join
		    s_entidad_financiera as sef ON (sef.id_ef = stre.id_ef)
				inner join
			s_poliza as spl ON (spl.id_poliza = stre.id_poliza)
		where
			scl.id_cliente = "'.$idCl.'"
				and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
		        and sef.activado = true
				and stre.emitir = true
				and stre.anulado = false
		group by stre.id_emision
		union select
			strme.id_emision as ide,
			strme.no_emision as rc_no_emision,
			spl.no_poliza as rc_no_poliza,
			"" as prefix,
			"TRM" as rc_producto,
			strme.no_operacion as rc_no_operacion,
			strme.plazo as rc_plazo,
			strme.tipo_plazo as rc_tipo_plazo,
			strme.fecha_emision as rc_fecha_desembolso,
			sum(strmd.valor_asegurado) as rc_monto_desembolso,
			"USD" as rc_moneda
		from
			s_trm_em_cabecera as strme
				inner join
			s_trm_em_detalle as strmd ON (strmd.id_emision = strme.id_emision)
				inner join
			s_cliente as scl ON (scl.id_cliente = strme.id_cliente)
				inner join
		    s_entidad_financiera as sef ON (sef.id_ef = strme.id_ef)
				inner join
			s_poliza as spl ON (spl.id_poliza = strme.id_poliza)
		where
			scl.id_cliente = "'.$idCl.'"
				and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
		        and sef.activado = true
				and strme.emitir = true
				and strme.anulado = false
		group by strme.id_emision
		;';

		$k = 0;
		$kk = '';
		if(($rs = $link->query($sql,MYSQLI_STORE_RESULT))){
			if($rs->num_rows > 0){
				while($row = $rs->fetch_array(MYSQLI_ASSOC)){
					$k += 1;	$kk .= $k.'|';
					$result .= get_policy_record($row, $arr_Product, $arr_Term, $arr_Currency, $arr_Loan, $k, $readonly, $flag);
				}

				$arrResult[0] = $kk;
				$arrResult[1] = $result;
			}
		}
		echo json_encode($arrResult);
	}else{
		$row = array(
			'ide' => '',
			'rc_no_emision' => '',
			'rc_no_poliza' => '',
			'rc_tipo_credito' => '',
			'rc_saldo_capital' => '',
			'rc_monto_desembolso' => '',
			'rc_moneda' => ''
			);

		$arrResult[0] = $np_aux1;
		$arrResult[1] = get_policy_record($row, $arr_Product, $arr_Term, $arr_Currency, $arr_Loan, $np, $readonly, $flag);
		echo json_encode($arrResult);
	}
}

function get_policy_record($row, $arr_Product, $arr_Term, $arr_Currency, $arr_Loan, $k, $readonly, $flag){
	if($readonly === '') $row['ide'] = $k;
	if($flag === FALSE) $row['rc_monto_desembolso'] = (int)$row['rc_monto_desembolso'];
/*
	$prefix = '';
	if (empty($row['rc_no_poliza'])) {
		$prefix = json_decode($row['prefix'], true);

		if (is_array($prefix) === true) {
			$row['rc_no_poliza'] = $prefix['policy'];
		}
	}*/
	ob_start();
?>

<tr>
<?php
	if($flag === FALSE) echo '<td><input type="checkbox" id="sn-mark-'.$k.'" name="sn-mark-'.$k.'" value="'.$k.'" class="sn-mark" checked></td>';
?>

   <!--

    <td>
    	<input type="hidden" id="rc-<?=$k;?>-ide" name="rc-<?=$k;?>-ide" value="<?=base64_encode($row['ide']);?>">
<?php
	//if($flag === FALSE){
	//	echo '<input type="text" id="rc-'.$k.'-ncertified" name="rc-'.$k.'-ncertified" autocomplete="off" value="'.$row['rc_producto'].'-'.$row['rc_no_emision'].'" class="required fbin" '.$readonly.'>';
	//}else{
	//	echo '<input type="text" id="rc-'.$k.'-ncertified" name="rc-'.$k.'-ncertified" autocomplete="off" value="" class="required fbin" '.$readonly.'>';
	//}
?>
    </td>
-->
    <td>


    	<input type="hidden" id="rc-<?=$k;?>-ide" name="rc-<?=$k;?>-ide" value="<?=base64_encode($row['ide']);?>">

     <?php
		if($flag === FALSE){
		echo '<input type="hidden" id="rc-'.$k.'-ncertified" name="rc-'.$k.'-ncertified" autocomplete="off" value="'.$row['rc_producto'].'-'.$row['rc_no_emision'].'" class=" fbin" '.$readonly.'>';
		}else{
		echo '<input type="hidden" id="rc-'.$k.'-ncertified" name="rc-'.$k.'-ncertified" autocomplete="off" value="1" class=" fbin" '.$readonly.'>';
		}
	?>

        <select id="rc-<?=$k;?>-loan-type" name="rc-<?=$k;?>-loan-type" class="required fbin">

<?php
				//if($readonly === '')
					echo '<option value="">Seleccione...</option>';
				for($i = 0; $i < count($arr_Loan); $i++){
					$CURR = explode('|', $arr_Loan[$i]);
					//if($row['rc_moneda'] === $CURR[0])
					//	echo '<option value="'.$CURR[0].'" selected>'.$CURR[1].'</option>';
					//elseif($readonly === '')
						echo '<option value="'.$CURR[0].'">'.$CURR[1].'</option>';
				}
?>
        </select>
    </td>
    <!--
    <td>
        <select id="rc-<?=$k;?>-product" name="rc-<?=$k;?>-product" class="required fbin">
<?php
				if($readonly === '')
					echo '<option value="">Seleccione...</option>';
				for($i = 0; $i < count($arr_Product); $i++){
					$PR = explode('|', $arr_Product[$i]);
					if($row['rc_producto'] === $PR[0])
						echo '<option value="'.$PR[0].'" selected>'.$PR[1].'</option>';
					elseif($readonly === '')
						echo '<option value="'.$PR[0].'">'.$PR[1].'</option>';
				}
?>
        </select>
    </td>
	-->

    <td>
        	<input type="text" id="rc-<?=$k;?>-amount" name="rc-<?=$k;?>-amount"
			autocomplete="off" value="<?= $row['rc_monto_desembolso']?>"
			class="required amount-ce fbin">
    </td>

    <td>
        <input type="text" id="rc-<?=$k;?>-nocredit" name="rc-<?=$k;?>-nocredit" autocomplete="off" value="" class="required fbin" >
    </td>

    <td>

        <select id="rc-<?=$k;?>-amount-type" name="rc-<?=$k;?>-amount-type" class="required fbin">
<?php
				if($readonly === '')
					echo '<option value="">Seleccione...</option>';
				for($i = 0; $i < count($arr_Currency); $i++){
					$CURR = explode('|', $arr_Currency[$i]);
					if($row['rc_moneda'] === $CURR[0])
						echo '<option value="'.$CURR[0].'" selected>'.$CURR[1].'</option>';
					elseif($readonly === '')
						echo '<option value="'.$CURR[0].'">'.$CURR[1].'</option>';
				}
?>
        </select>
    </td>
</tr>
<?php
	$html = ob_get_clean();
	return $html;
}
?>