<script type="text/javascript">
$(document).ready(function(e) {
    $(".tab-home").click(function(e){
		e.preventDefault();
		var pr = $(this).attr('rel');
		var ef = $("#fde-ef").prop('value');
		var user = $("#fde-idUser").prop('value');
		var type_user = $("#fde-type-user").prop('value');
		var sel = $(this).attr('data-sel');
		var max = $("#max").prop('value');
		
		for (var i = 1; i <= max; i++) {
			$("#tab-"+i).removeClass('tab-home-active-'+i);
		}
		
		$(this).addClass('tab-home-active-'+sel);
		
		$.ajax({
			url:'FAC-'+pr+'-result.inc.php',
			type:'GET',
			data:'fde=&fde-nc=&fde-user=&fde-client=&fde-dni=&fde-comp=&fde-ext=&fde-date-b=&fde-date-e=&fde-id-user='+user+'&fde-type-user='+type_user+'&token=<?=md5('2');?>&fde-ef[]='+ef,
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
				//$(".f-records :submit").prop('disabled', false);
			}
		});
		return false;
	});
	
	$("label.check-label").click(function(e){
		var data_check = parseInt($(this).attr('data-check'));
		//alert(data_check)
		//alert($("#checkboxG1").is(':checked'));
		switch(data_check){
			case 0:
				$(this).css('background-position', '0 -28px');
				//$("#checkboxG1").prop('checked', true);
				data_check = 1;
				break;
			case 1:
				$(this).css('background-position', '0 0');
				//$("#checkboxG1").prop('checked', false);
				data_check = 0;
				break;
		}
		
		$(this).attr('data-check', data_check);
	});
	
});
</script>
<?php
require_once('sibas-db.class.php');
$fwd = '';
$_pr = 0;
$_pr_aux = 0;
$max = 0;


$_TYPE_USER = $link->verify_type_user($_SESSION['idUser'], $_SESSION['idEF']);
?>

<h3 class="h3">Mis casos facultativos</h3>
<div class="mess-oc">
    <label class="check-label check-mess" style="background-position:0 0;">Leido</label>
	<label class="check-label check-mess" style="background-position:0 -24px;">No Leido</label>
    
	<span class="days-process" style="background: #f31d1d;"></span>Mayor a 10 dias
    <span class="days-process" style="background: #f4ec1c;"></span>3 - 10 dias
    <span class="days-process" style="background: #18b745;"></span>0 - 2 dias
</div>
<table class="content-tab">
    <thead>
        <tr>
<?php
if (($rsMenu = $link->get_product_menu($_SESSION['idEF'])) !== FALSE) {
	$k = 0;
	while ($rowMenu = $rsMenu->fetch_array(MYSQLI_ASSOC)) {
		$k += 1;
		if ($k > $max) {
			$max = $k;
		}
		if (isset($_GET['fwd'])) {
			if (md5($rowMenu['producto']) === $_GET['fwd']) {
				$_pr_aux = $k;
			}
		}
		if ($rowMenu['producto'] !== 'TRD' && $rowMenu['producto'] !== 'TH') {
?>
			<td style="width: 220px;">
                <a href="#" class="tab-home tab-home-active-<?=$k;?>" id="tab-<?=$k;?>" rel="<?=$rowMenu['producto'];?>" data-sel="<?=$k;?>"><?=$rowMenu['producto_nombre'];?></a>
            </td>
<?php
		}
	}
}
?>
            <td style="width: auto;">
            	<input type="hidden" id="pid" name="pid" value="1" />
                <input type="hidden" id="fde-ef" name="fde-ef" value="<?=$_SESSION['idEF'];?>">
                <input type="hidden" id="fde-idUser" name="fde-idUser" value="<?=$_SESSION['idUser'];?>">
                <input type="hidden" id="fde-type-user" name="fde-type-user" value="<?=base64_encode($_TYPE_USER['u_tipo_codigo']);?>" />
                <input type="hidden" id="max" value="<?=$max;?>" >
			</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="4" style="border: 0 none;">
                <div style="height: 15px; background: #222222; border: 1px solid #6b6b6b; border-top-width: 0px;"></div>
            </td>
        </tr>
    </tbody>
</table>
<div class="result-container">
    <div class="result-loading"></div>
    <div class="result-search"></div>
</div>
<?php
if(isset($_GET['fwd'])){
	$fwd = $_GET['fwd'];
	$_pr = $_pr_aux;
} else {
	$_pr = 1;
}

if($_pr > 0){
?>
<script type="text/javascript">
$(document).ready(function(e) {
	$("#tab-<?=$_pr;?>").trigger("click");
});
</script>
<?php
}

?>