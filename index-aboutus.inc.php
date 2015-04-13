<?php
$HOST_ABOUT_US = $link->get_home_content($_SESSION['idEF'], 'H', $token);
?>
<h2>Nosotros <?=$HOST_CLIENT['cliente'];?></h2>
<div class="content-ins"><?=$HOST_ABOUT_US['nosotros'];?></div><!--
--><aside class="content-quote">
	<div class="quote-img"><img src="images/<?=$HOST_ABOUT_US['imagen'];?>"></div>
</aside>