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
	case 'TRD|01':
		require('TRD-data-property.php');
		break;
	case 'TRD|02':
		if(isset($_GET['idc'])){
			require('TRD-data-customer.php');
		}
		break;
	case 'TRD|03':
		if(isset($_GET['idc'])){
			require('TRD-result-quote.php');
		}
		break;
	case 'TRD|04':
		if(isset($_GET['idc']) && isset($_GET['flag'])){
			require('TRD-save-share.php');
		}
		break;
	case 'TRD|05':
		if((isset($_GET['ide']) || isset($_GET['idc'])) && isset($_GET['flag'])){
			require('TRD-data-issue.php');
		}
		break;
	}
}else{
	if($ms !== NULL){
		switch($product){
		case 'TRD|01':
			if(isset($_GET['idc'])){
				require('TRD-data-property.php');
			}
			break;
		case 'TRD|02':
			if(isset($_GET['idc'])){
				require('TRD-data-customer.php');
			}
			break;
		case 'TRD|03':
			if(isset($_GET['idc'])){
				require('TRD-result-quote.php');
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