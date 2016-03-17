<?php
if(($rsFq = $link->get_faqs($_SESSION['idEF'])) !== FALSE){
?>
<script type="text/javascript">
$(document).ready(function(e) {
    $("#tabs").tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
	$( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
});
</script>
<style type="text/css">
.ui-tabs-vertical { width: 55em; }
.ui-tabs-vertical .ui-tabs-nav { padding: .2em .1em .2em .2em; float: left; width: 20%; }
.ui-tabs-vertical .ui-tabs-nav li { clear: left; width: 100%; border-bottom-width: 1px !important; border-right-width: 0 !important; margin: 0 -1px .2em 0; }
.ui-tabs-vertical .ui-tabs-nav li a { display:block; font-weight:bold; }
.ui-tabs-vertical .ui-tabs-nav li.ui-tabs-active { padding-bottom: 0; padding-right: .1em; border-right-width: 1px; border-right-width: 1px; }
.ui-tabs-vertical .ui-tabs-panel { padding: 1em; float: left; width: 75%;}

#tabs{ font-size:75%; width:auto; }

#tabs-1 ul li a{ font-size:75%; }
</style>
<div id="tabs">
	<ul>
<?php
	if($rsFq->data_seek(0) === TRUE){
		$k = 0;
		while($rowFq = $rsFq->fetch_array(MYSQLI_ASSOC)){
			$k += 1;
?>
		<li><a href="#tabs-<?=$k;?>"><?=$rowFq['producto_nombre'];?></a></li>
<?php
		}
	}
?>
	</ul>
<?php
	if($rsFq->data_seek(0) === TRUE){
		$k = 0;
		while($rowFq = $rsFq->fetch_array(MYSQLI_ASSOC)){
			$k += 1;
?>
	<div id="tabs-<?=$k;?>">
		<h2><?=$rowFq['producto_nombre'];?></h2>
    	<p><?=$rowFq['preguntas_frecuentes'];?></p>
	</div>
<?php
		}
	}
?>
</div>
<?php
}else{
	echo 'No existen preguntas frecuentes';
}
?>