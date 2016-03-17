<?php
$product = '';
if(isset($_GET['pr'])){
	$product = base64_decode($_GET['pr']);
}else{
	echo '<meta http-equiv="refresh" content="0;url=index.php" >';
}

include('header.inc.php');
/*if ($token === true) {
    include('session-timer.inc.php');
}*/
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
if($token === TRUE){
	switch($product){
		case 'DE|01':
			require('DE-data-loan.php');
			break;
		case 'DE|02':
			if(isset($_GET['idc'])){
				require('DE-data-customer.php');
			}
			break;
		case 'DE|03':
			if(isset($_GET['idc'])){
				require('DE-data-question.php');
			}
			break;
		case 'DE|04':
			if(isset($_GET['idc'])){
				require('DE-result-quote.php');
			}
			break;
		case 'DE|05':
			if((isset($_GET['ide']) || isset($_GET['idc'])) && isset($_GET['flag'])){
				require('DE-data-issue.php');
			}
			break;
	}
}else{
	if($ms !== NULL){
		switch($product){
			case 'DE|01':
				require('DE-data-loan.php');
				break;
			case 'DE|02':
				if(isset($_GET['idc'])){
					require('DE-data-customer.php');
				}
				break;
			case 'DE|03':
				if(isset($_GET['idc'])){
					require('DE-data-question.php');
				}
				break;
			case 'DE|04':
				if(isset($_GET['idc'])){
					require('DE-result-quote.php');
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