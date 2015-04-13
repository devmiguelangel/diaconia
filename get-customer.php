<?php
require('sibas-db.class.php');
if(isset($_GET['text']) && isset($_GET['product']) && isset($_GET['type'])){
	$link = new SibasDB();
	$text = $link->real_escape_string(trim($_GET['text']));
	$product = $link->real_escape_string(trim($_GET['product']));
	$type = $link->real_escape_string(trim($_GET['type']));

	$sql = 'select
		scl.id_cliente as idCl,
		(case scl.tipo
			when
				0
			then
				concat(scl.nombre,
						" ",
						scl.paterno,
						" ",
						scl.materno)
			when 1 then scl.razon_social
		end) as cl_nombre_completo,
		scl.paterno as cl_paterno,
		scl.materno as cl_materno,
		scl.nombre as cl_nombre,
		scl.ap_casada as cl_ap_casada,
		scl.ci as cl_ci,
		scl.complemento as cl_complemento,
		scl.tipo_documento as cl_tipo_documento,
		sdp.codigo as cl_extension,
		sdp.id_depto as cl_id_ext
	from
		s_cliente as scl
			inner join
		s_departamento as sdp ON (sdp.id_depto = scl.extension)
	where
		(case scl.tipo
			when
				0
			then
				concat(scl.nombre,
						" ",
						scl.paterno,
						" ",
						scl.materno)
			when 1 then scl.razon_social
		end) like "%'.$text.'%"
			or scl.ci like "%'.$text.'%"
	order by scl.id_cliente asc
	;';
	//echo $sql;
	if(($rs = $link->query($sql,MYSQLI_STORE_RESULT))){
		if($rs->num_rows > 0){
			$result = '<script type="text/javascript">
				$(document).ready(function(e) {
					$(".data-search").click(function(e){
						e.preventDefault();
						var _ci = $(this).attr("data-ci");
						var _patern = $(this).attr("data-patern");
						var _matern = $(this).attr("data-matern");
						var _name = $(this).attr("data-name");
						var _married = $(this).attr("data-married");
						var _idCl = $(this).attr("data-id");
						var _idExt = $(this).attr("data-id-ext");
						var _typeDoc= $(this).attr("data-type-doc");

						$("#rc-id-client").prop("value", _idCl);
						$("#rc-dni").prop("value", _ci);
						$("#rc-patern").prop("value", _patern);
						$("#rc-matern").prop("value", _matern);
						$("#rc-name").prop("value", _name);
						$("#rc-married").prop("value", _married);
						$("#rc-ext").prop("value", _idExt);
						$("#rc-type-doc").prop("value", _typeDoc);

						$.getJSON("get-policy-autocomplete.php", {idCl: _idCl}, function(result){
							$("#rc-npolicy").prop("value", result[0]);
							$(".list-cl tbody").html(result[1]);
							$("#content-script").html("<script type=\"text/\javascript\">\
									$(document).ready(function(e) {\
										$(\"input.sn-mark\").iCheck({\
											checkboxClass: \"icheckbox_square-blue\",\
											radioClass: \"iradio_square-blue\",\
											increaseArea: \"20%\"\
										});\
										$(\"input.sn-mark\").on(\"ifToggled\", function(event){\
											var nr = $(this).prop(\"value\");\
											var np = $(\"#rc-npolicy\").prop(\"value\");\
											if($(this).is(\":checked\") === false){\
												$(\"#rc-\"+nr+\"-ide, #rc-\"+nr+\"-ncertified, #rc-\"+nr+\"-npolicy, #rc-\"+nr+\"-loan-type, #rc-\"+nr+\"-amount, #rc-\"+nr+\"-nocredit, #rc-\"+nr+\"-amount, #rc-\"+nr+\"-amount-type \").prop(\"disabled\", true);\
												np = np.replace(nr + \"|\", \"\");\
											}else{\
												$(\"#rc-\"+nr+\"-ide, #rc-\"+nr+\"-ncertified, #rc-\"+nr+\"-npolicy, #rc-\"+nr+\"-loan-type, #rc-\"+nr+\"-amount, #rc-\"+nr+\"-nocredit, #rc-\"+nr+\"-amount-type \").prop(\"disabled\", false);\
												np += nr + \"|\";\
											}\
											$(\"#rc-npolicy\").prop(\"value\", np);\
										});\
										\
									});\
								</\script>");
						});
					});
				});
				</script>
				<ul class="autocomplete-result">';
			while($row = $rs->fetch_array(MYSQLI_ASSOC)){
				$result .= '<li><a href="#" class="data-search" data-id="'.base64_encode($row['idCl']).'" data-ci="'.$row['cl_ci'] .'" data-patern="'.$row['cl_paterno'].'" data-matern="'.$row['cl_materno'].'" data-name="'.$row['cl_nombre'].'" data-married="'.$row['cl_ap_casada'].'" data-id-ext="'.$row['cl_id_ext'].'"  data-type-doc="'.$row['cl_tipo_documento'].'">'.$row['cl_ci'].' '.$row['cl_extension'].'<br>'.$row['cl_nombre_completo'].'</a></li>';
			}
			$result .= '</ul>';
			echo $result;
		}else{
			echo '<div style="padding:5px 8px; text-align:center;">No existen resultados</div>';
		}
	}else{
		echo '<div style="padding:5px 8px; text-align:center;">No existen resultados</div>';
	}
}
/*
$(\".sn-mark\").click(function(e){\
	var nr = $(this).prop(\"value\");\
	var np = $(\"#rc-npolicy\").prop(\"value\");\
	if($(this).is(\":checked\") === false){\
		$(\"#rc-\"+nr+\"-ide, #rc-\"+nr+\"-ncertified, #rc-\"+nr+\"-npolicy, #rc-\"+nr+\"-product, #rc-\"+nr+\"-noperation, #rc-\"+nr+\"-term, #rc-\"+nr+\"-term-type, #rc-\"+nr+\"-date, #rc-\"+nr+\"-amount, #rc-\"+nr+\"-amount-type \").prop(\"disabled\", true);\
		np = np.replace(nr + \"|\", \"\");\
	}else{\
		$(\"#rc-\"+nr+\"-ide, #rc-\"+nr+\"-ncertified, #rc-\"+nr+\"-npolicy, #rc-\"+nr+\"-product, #rc-\"+nr+\"-noperation, #rc-\"+nr+\"-term, #rc-\"+nr+\"-term-type, #rc-\"+nr+\"-date, #rc-\"+nr+\"-amount, #rc-\"+nr+\"-amount-type \").prop(\"disabled\", false);\
		np += nr + \"|\";\
	}\
	$(\"#rc-npolicy\").prop(\"value\", np);\
});\
*/
?>