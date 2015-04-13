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
		case 'TRM|01':
			require('TRM-data-material.php');
			break;
		case 'TRM|02':
			if(isset($_GET['idc'])){
				require('TRM-data-customer.php');
			}
			break;
		case 'TRM|03':
			if(isset($_GET['idc'])){
				require('TRM-result-quote.php');
			}
			break;
		case 'TRM|04':
			if(isset($_GET['idc']) && isset($_GET['flag'])){
				require('TRM-save-share.php');
			}
			break;
		case 'TRM|05':
			if((isset($_GET['ide']) || isset($_GET['idc'])) && isset($_GET['flag'])){
				require('TRM-data-issue.php');
			}
			break;
	}
}else{
	if($ms !== NULL){
		switch($product){
			case 'TRM|01':
				require('TRM-data-material.php');
				break;
			case 'TRM|02':
				if(isset($_GET['idc'])){
					require('TRM-data-customer.php');
				}
				break;
			case 'TRM|03':
				if(isset($_GET['idc'])){
					require('TRM-result-quote.php');
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