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
	case 'TH|01':
		require('TH-data-customer.php');
		break;
	case 'TH|03':
		if(isset($_GET['idc'])){
			require('TH-result-quote.php');
		}
		break;
	}
}else{
	if($ms !== NULL){
		switch($product){
		case 'TH|01':
			require('TH-data-customer.php');
			break;
		}
	} else {
		include('index-content.inc.php');
	}
}
?>
	</section>
</div>	
<?php
include('footer.inc.php');
?>