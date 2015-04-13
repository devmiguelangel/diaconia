<?php
require_once('sibas-db.class.php');
$link = new SibasDB();

$user = '';
$type = '';

$data_subsidiary = array();
$data_agency = array();
$data_user = array();

if (isset($_SESSION['idUser']) && isset($_SESSION['idEF'])) {
	if (($rowUser = $link->verify_type_user($_SESSION['idUser'], $_SESSION['idEF'])) !== false) {
		$user = $rowUser['u_id'];
		$type = $rowUser['u_tipo_codigo'];

		switch ($type) {
		case 'LOG':
			$data_user[] = array(
				'id' 	=> $rowUser['u_id'],
				'user' 	=> $rowUser['u_usuario'],
				'name' 	=> $rowUser['u_nombre'],
				);

			$data_subsidiary[] = array(
				'id'	=> base64_encode($rowUser['u_depto']),
				'depto'	=> $rowUser['u_nombre_depto'],
				);

			$data_agency[] = array(
				'id' 	 => base64_encode($rowUser['id_agencia']),
				'agency' => $rowUser['agencia'],
				);
			break;
		case 'REP':
			$data_user[] = array(
				'id' 	=> '',
				'user' 	=> '',
				'name' 	=> 'Todos',
				);

			if (is_null($rowUser['u_depto']) === true) {
				$data_subsidiary[] = array(
					'id'	=> '',
					'depto'	=> 'Todos',
					);

				if (($rsSb = $link->get_depto()) !== false) {
					while ($rowSb = $rsSb->fetch_array(MYSQLI_ASSOC)) {
						if ((boolean)$rowSb['tipo_dp'] === true) {
							$data_subsidiary[] = array(
								'id'	=> base64_encode($rowSb['id_depto']),
								'depto'	=> $rowSb['departamento'],
								);
						}
					}
				}

				if (($rsAgency = $link->getAgencySubsidiary(
					$_SESSION['idEF'], '')) !== false) {
					$data_agency[] = array(
							'id' 	 => '',
							'agency' => 'Todos',
							);
					while ($rowAgency = $rsAgency->fetch_array(MYSQLI_ASSOC)) {
						$data_agency[] = array(
							'id' 	 => base64_encode($rowAgency['id_agencia']),
							'agency' => $rowAgency['agencia'],
							);
					}
				}

				if (($rsUss = $link->getUserSubsidiary($_SESSION['idEF'], '')) !== false) {
					while ($rowUss = $rsUss->fetch_array(MYSQLI_ASSOC)) {
						$data_user[] = array(
							'id' 	=> $rowUss['id_usuario'],
							'user' 	=> $rowUss['usuario'],
							'name' 	=> $rowUss['nombre'],
							);
					}
				}

			} else {
				$data_subsidiary[] = array(
					'id'	=> base64_encode($rowUser['u_depto']),
					'depto'	=> $rowUser['u_nombre_depto'],
					);

				if (is_null($rowUser['id_agencia']) === true) {
					if (($rsAgency = $link->getAgencySubsidiary(
						$_SESSION['idEF'], $rowUser['u_depto'])) !== false) {
						$data_agency[] = array(
								'id' 	 => '',
								'agency' => 'Todos',
								);
						while ($rowAgency = $rsAgency->fetch_array(MYSQLI_ASSOC)) {
							$data_agency[] = array(
								'id' 	 => base64_encode($rowAgency['id_agencia']),
								'agency' => $rowAgency['agencia'],
								);
						}
					}

					if (($rsUss = $link->getUserSubsidiary($_SESSION['idEF'], $rowUser['u_depto'])) !== false) {
						while ($rowUss = $rsUss->fetch_array(MYSQLI_ASSOC)) {
							$data_user[] = array(
								'id' 	=> $rowUss['id_usuario'],
								'user' 	=> $rowUss['usuario'],
								'name' 	=> $rowUss['nombre'],
								);
						}
					}
				} else {
					$data_agency[] = array(
						'id' 	 => base64_encode($rowUser['id_agencia']),
						'agency' => $rowUser['agencia'],
						);

					if (($rsUss = $link->getUserSubsidiary(
						$_SESSION['idEF'], $rowUser['u_depto'], $rowUser['id_agencia'], $rowUser['u_id'])) !== false) {
						while ($rowUss = $rsUss->fetch_array(MYSQLI_ASSOC)) {
							$data_user[] = array(
								'id' 	=> $rowUss['id_usuario'],
								'user' 	=> $rowUss['usuario'],
								'name' 	=> $rowUss['nombre'],
								);
						}
					}
				}
			}
			break;
		}
	}
}
?>


<script type="text/javascript">
$(document).ready(function(e) {
	$(".date").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
		yearRange: "c-100:c+100"
	});

	$(".date").datepicker($.datepicker.regional[ "es" ]);

    $(".f-reports").submit(function(e){
		e.preventDefault();
		$(this).find(':submit').prop('disabled', true);

		var _data = $(this).serialize();
		$.ajax({
			url:'RC-result.inc.php',
			type:'GET',
			data:'frc=&'+_data,
			//dataType:"json",
			async:true,
			cache:false,
			beforeSend: function(){
				$(".result-search").hide();
				$(".result-loading").show();
			},
			complete: function(){
				$(".result-loading").hide();
				$(".result-search").show();
			},
			success: function(result){
				$(".result-search").html(result);
				$(".f-reports :submit").prop('disabled', false);
			}
		});
		return false;
	});
});
</script>
<?php
$class = 'rp-active';
?>
<h3>Reporte General de Siniestros</h3>
<table class="rp-link-container">
	<tr>
		<td style="width:20%;">
			<a href="#" class="rp-link <?=$class;?>" rel="<?=$k;?>">Siniestros</a>
		</td>
		<td style="width:40%; border-bottom:1px solid #CECECE;">
        	<input type="hidden" id="flag" name="flag" value="<?=md5('RG');?>">
		</td>
	</tr>
</table>
<?php
$display = 'display:block;';

?>
<div class="rc-records" style=" <?=$display;?> ">
	<form class="f-reports">
    	<label>N° de Siniestro: </label>
        <input type="text" id="frc-ns" name="frc-ns" value="" autocomplete="off">

            <br>

            <label>Sucursal: </label>
            <select id="frc-subsidiary" name="frc-subsidiary">
<?php
			foreach ($data_subsidiary as $key => $value) {
				echo '<option value="' . $value['id'] . '">' . $value['depto'] . '</option>';
			}
?>
            </select>

            <label style="width: auto;">Agencia: </label>
            <select id="frc-agency" name="frc-agency">
<?php
			foreach ($data_agency as $key => $value) {
				echo '<option value="' . base64_encode($value['id']) . '">' . $value['agency'] . '</option>';
			}
?>
            </select>

            <label style="width: auto;">Usuario: </label>
            <select id="frc-user" name="frc-user">
<?php
			foreach ($data_user as $key => $value) {
				echo '<option value="' . base64_encode($value['id']) . '">' . $value['name'] . '</option>';
			}
?>
            </select>


        <br>
        <label style="width:auto;">Nombre del Cliente: </label>
        <input type="text" id="frc-name" name="frc-name" value="" autocomplete="off">

        <label style="width:auto;">C.I. del Cliente: </label>
        <input type="text" id="frc-dni" name="frc-dni" value="" autocomplete="off">


        <label style="width:auto;">Persona Denuncia</label>
        <input type="text" id="frc-rep-person" name="frc-rep-person" value="" autocomplete="off">

		<br>
        <label style="width:auto;">Fecha Registro: </label>
        <label style="width:auto;">desde: </label>
        <input type="text" id="frc-date-b" name="frc-date-b" value="" autocomplete="off" class="date" readonly>

        <label style="width:auto;">hasta: </label>
        <input type="text" id="frc-date-e" name="frc-date-e" value="" autocomplete="off" class="date" readonly>

        <!--
        <label style="width:auto;">Fecha: </label>
        <label style="width:auto;">desde: </label>
        <input type="text" id="frc-date-b" name="frc-date-b" value="" autocomplete="off" class="date" readonly>

        <label style="width:auto;">hasta: </label>
        <input type="text" id="frc-date-e" name="frc-date-e" value="" autocomplete="off" class="date" readonly>
        -->

        <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
		<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
        <input type="hidden" id="idef" name="idef" value="<?=$_SESSION['idEF'];?>">
        <br>

        <div align="center">
        	<input type="submit" id="frc-search" name="frc-search" value="Buscar" class="frp-btn">
	        <input type="reset" id="frc-reset" name="frc-reset" value="Restablecer Campos"  class="frp-btn">
        </div>
    </form>

    <div class="result-container">
    	<div class="result-loading"></div>
        <div class="result-search"></div>
    </div>
</div>