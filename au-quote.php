<script type="text/javascript">
if(history.forward(1)){location.replace(history.forward(1))}
</script>
<?php
$product = '';
if(isset($_GET['pr'])){
	$product = base64_decode($_GET['pr']);
}else{
	echo '<meta http-equiv="refresh" content="0;url=index.php" >';
}

include('header.inc.php');
?>
<script type="text/javascript">
$(document).ready(function(e) {
    $(".fancybox").fancybox({
		
	});
});
</script>
<div id="content-main">
	<section id="main">
<?php
if($token === TRUE){
	switch($product){
	case 'AU|01':
		require('AU-data-vehicle.php');
		break;
	case 'AU|02':
		if(isset($_GET['idc'])){
			require('AU-data-customer.php');
		}
		break;
	case 'AU|03':
		if(isset($_GET['idc'])){
			require('AU-result-quote.php');
		}
		break;
	case 'AU|04':
		if(isset($_GET['idc']) && isset($_GET['flag'])){
			require('AU-save-share.php');
		}
		break;
	case 'AU|05':
		if((isset($_GET['ide']) || isset($_GET['idc'])) && isset($_GET['flag'])){
			require('AU-data-issue.php');
		}
		break;
	}
}else{
	if($ms !== NULL){
		switch($product){
			case 'AU|01':
				if(isset($_GET['idc'])){
					require('AU-data-vehicle.php');
				}
				break;
			case 'AU|02':
				if(isset($_GET['idc'])){
					require('AU-data-customer.php');
				}
				break;
			case 'AU|03':
				if(isset($_GET['idc'])){
					require('AU-result-quote.php');
				}
				break;
		}
	}else{
		include('index-content.inc.php');
	}
}
?>
	</section>
</div>	
<?php
include('footer.inc.php');
?>