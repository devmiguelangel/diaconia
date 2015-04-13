<h1>Conoce más de <?=$HOST_CLIENT['cliente'];?></h1>
<?php
$rsContent = $link->get_slider_content($_SESSION['idEF'], 'AR', $token);
while($rowContent = $rsContent->fetch_array(MYSQLI_ASSOC)){
?>
<article class="article-main">
	<figure>
		<img src="images/<?=$rowContent['imagen'];?>"/>
	</figure>
	<div><?=$rowContent['descripcion'];?></div>
</article>
<?php
}
?>

