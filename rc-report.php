<?php
include('header.inc.php');
?>
<script type="text/javascript">
if(history.forward(1)){location.replace(history.forward(1))}

$(document).ready(function(e) {
    $(".fancybox").fancybox({

	});
});
</script>
<div id="content-main">
	<section id="main">
<?php
if($ms === md5('MS_RC') && $token === TRUE){
	switch($page){
		case md5('P_report'):
			require('RC-report.inc.php');
			break;
		case md5('P_state'):
			require('RC-state.inc.php');
			break;
		case md5('P_records'):
			require('RC-records.inc.php');
			break;
		default:
			echo '<meta http-equiv="refresh" content="0;url=index.php" >';
			break;
	}
}else{
	echo '<meta http-equiv="refresh" content="0;url=index.php" >';
}
?>
	</section>
</div>
<?php
include('footer.inc.php');
?>