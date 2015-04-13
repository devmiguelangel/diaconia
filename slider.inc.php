<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<!-- REVOLUTION BANNER CSS SETTINGS -->
<link rel="stylesheet" type="text/css" href="rs-plugin/css/settings.css" media="screen" />

<!-- jQuery KenBurn Slider  -->	
<script type="text/javascript" src="rs-plugin/js/jquery.themepunch.plugins.min.js"></script>
<script type="text/javascript" src="rs-plugin/js/jquery.themepunch.revolution.min.js"></script>

<script type="text/javascript">
var tpj=jQuery;
tpj.noConflict();

tpj(document).ready(function() {
	if (tpj.fn.cssOriginal!=undefined)
		tpj.fn.css = tpj.fn.cssOriginal;
	
	tpj('.fullwidthbanner').revolution({
		delay:9000,
		startwidth:890,
		startheight:450,
		
		onHoverStop:"on",	// Stop Banner Timet at Hover on Slide on/off
		
		thumbWidth:100,		// Thumb With and Height and Amount (only if navigation Tyope set to thumb !)
		thumbHeight:50,
		thumbAmount:4,
		
		hideThumbs:200,
		navigationType:"both",		//bullet, thumb, none, both	 (No Shadow in Fullwidth Version !)
		navigationArrows:"verticalcentered",		//nexttobullets, verticalcentered, none
		navigationStyle:"round",		//round,square,navbar
		
		touchenabled:"on",			// Enable Swipe Function : on/off
		
		navOffsetHorizontal:0,
		navOffsetVertical:20,
		
		fullWidth:"on",
		
		shadow:0		//0 = no Shadow, 1,2,3 = 3 Different Art of Shadows -  (No Shadow in Fullwidth Version !)
	});
});
</script>

<section id="banner">
	<div class="fullwidthbanner">
		<ul>
<?php
$rsSlider = $link->get_slider_content($_SESSION['idEF'], 'SL', $token);
$arrEffect = array(0 => 'fade', 1 => 'boxfade', 2 => 'curtain-1', 3 => 'curtain-3', 4 => 'curtain-2');
while($rowSlider = $rsSlider->fetch_array(MYSQLI_ASSOC)){
	$effect = mt_rand(0, 4);
?>
			<li data-transition="<?=$arrEffect[$effect];?>" data-slotamount="10" data-thumb="img/thumbs/thumb1.jpg">
				<img src="images/<?=$rowSlider['imagen'];?>" />
<?php
	if(empty($rowSlider['descripcion']) === FALSE){
?>
				<div class="caption lft big_orange" data-x="400" data-y="100" data-speed="400" data-start="1700" data-easing="easeOutExpo"><?=$rowSlider['descripcion'];?></div>
<?php
	}
?>
				<!--<div class="caption lft big_white" data-x="400" data-y="100" data-speed="400" data-start="1700" data-easing="easeOutExpo">Kickstart Your Website</div>
				<div class="caption lft big_orange" data-x="400" data-y="137" data-speed="400" data-start="1900" data-easing="easeOutExpo"><span style="font-weight:normal;">With</span><em> Slider Revolution</em></div>
				<div class="caption lfr medium_grey" data-x="510" data-y="210" data-speed="300" data-start="2500" data-easing="easeOutExpo">Unlimited Transitions</div>-->
			</li>
<?php
}
?>
			<!--
			<li data-transition="fade" data-slotamount="10" data-thumb="img/thumbs/thumb1.jpg">
				<img src="img/slides/thumb1.jpg" />
				<div class="caption lft big_white" data-x="400" data-y="100" data-speed="400" data-start="1700" data-easing="easeOutExpo">Kickstart Your Website</div>
				<div class="caption lft big_orange" data-x="400" data-y="137" data-speed="400" data-start="1900" data-easing="easeOutExpo"><span style="font-weight:normal;">With</span><em> Slider Revolution</em></div>
				<div class="caption lfr medium_grey" data-x="510" data-y="210" data-speed="300" data-start="2500" data-easing="easeOutExpo">Unlimited Transitions</div>
			</li>
			
			<li data-transition="boxfade" data-slotamount="7" data-thumb="img/thumbs/thumb2.jpg"> 
				<img src="img/slides/thumb2.jpg" />	
				<div class="caption lfb big_white" data-x="120" data-y="330" data-speed="600" data-start="1800" data-easing="easeOutExpo">Strawberries are like a glimpse of summer.</div>										
			</li>
			
			<li data-transition="curtain-1" data-slotamount="6" data-thumb="img/thumbs/thumb3.jpg"> 
				<img src="img/slides/thumb3.jpg" />	
				<div class="caption lft boxshadow" data-x="200" data-y="80" data-speed="900" data-start="1300" data-easing="easeOutExpo"><iframe src="http://player.vimeo.com/video/32001208?title=0&amp;byline=0&amp;portrait=0" width="460" height="259"></iframe></div>	
				<div class="caption lfb small_text" data-x="335" data-y="358" data-speed="300" data-start="1600" data-easing="easeOutExpo">Just an awesome Vimeo Video</div>
			</li>
			
			<li data-transition="curtain-3" data-slotamount="15" data-thumb="img/thumbs/thumb4.jpg"> 
				<img src="img/slides/wideimage.jpg" />
				<div class="caption lfr very_big_white" data-x="500" data-y="100" data-speed="300" data-start="1200" data-easing="easeOutExpo">GET THE</div>	
				<div class="caption lfr very_big_white" data-x="500" data-y="160" data-speed="300" data-start="1300" data-easing="easeOutExpo">PARTY</div>	
				<div class="caption lfr very_big_white" data-x="500" data-y="220" data-speed="300" data-start="1400" data-easing="easeOutExpo">STARTED</div>	
				<div class="caption lfb big_white" data-x="500" data-y="300" data-speed="300" data-start="1500" data-easing="easeOutExpo"><a href="#">SIGN UP HERE</a></div>
			</li>
			
			<li data-transition="curtain-2" data-slotamount="15" data-thumb="img/thumbs/thumb5.jpg"> 
				<img src="img/slides/wideimage2.jpg" />	
				<div class="caption lft very_large_text" data-x="50" data-y="160" data-speed="300" data-start="1200" data-easing="easeOutExpo">BUTTON STYLES INCLUDED!</div>	
				<div class="caption sfb" data-x="50" data-y="250" data-speed="1000" data-start="1500" data-easing="easeOutBack"><a href="#" target="_blank" class="button red small">Red Button</a></div>
				<div class="caption sfb" data-x="170" data-y="250" data-speed="1000" data-start="1600" data-easing="easeOutBack"><a href="#" target="_blank" class="button orange small">Orange Button</a></div>
				<div class="caption sfb" data-x="315" data-y="250" data-speed="1000" data-start="1700" data-easing="easeOutBack"><a href="#" target="_blank" class="button green small">Green Button</a></div>
				<div class="caption sfb" data-x="450" data-y="250" data-speed="1000" data-start="1800" data-easing="easeOutBack"><a href="#" target="_blank" class="button blue small">Blue Button</a></div>
				<div class="caption sfb" data-x="570" data-y="250" data-speed="1000" data-start="1900" data-easing="easeOutBack"><a href="#" target="_blank" class="button lightgrey small">Light Button</a></div>
				<div class="caption sfb" data-x="700" data-y="250" data-speed="1000" data-start="2000" data-easing="easeOutBack"><a href="#" target="_blank" class="button darkgrey small">Dark Button</a></div>
			</li>
			-->
		</ul>		
		<div class="tp-bannertimer"></div>												
	</div>	
</section>