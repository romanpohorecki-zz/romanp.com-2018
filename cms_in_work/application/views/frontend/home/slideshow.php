<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="sliderContainer scrollMe" id="sliderContainer_<?php print $id?>">
	<div class='width-100 block clear-both padding-bottom-80'>
		<div class='relative tile ratio-2-1 mobile-ratio-1-1 overflow-hidden'>
			<div class='slideshow width-1000 overflow-hidden'>
				<?php $i=0; foreach ($images AS $image):?>
				<img class="height-100 padding-0 float-left <?php print $i==0 ? 'image-active' : ''?>" src='<?php print $image['image_src']?>' rel="<?php print $i?>" style="left:-9999px;"/>
				<?php $i++; endforeach;?>
			</div>
			<div class='info-text'>
				<div class='button-close hover-cursor float-right toggleInfo_<?php print $id?>'></div>
				<?php print $project['description']?>
			</div>			
			<div class='arrows'>
				<div class='arrow-left hover-cursor transition-1' onclick="jQuery().pSlider().prev(<?php print $id?>);"></div>
				<div class='arrow-right hover-cursor transition-1' onclick="jQuery().pSlider().next(<?php print $id?>);"></div>
			</div>
		</div>
		<div class='info'>
			<h2 class='inline-block float-left'><?php print $project['title']?></h2>
			<?php if(!empty($project['description'])):?>
			<div class='inline-block button-info float-left transition-1 toggleInfo_<?php print $id?>'>info</div>
			<?php endif;?>
			<ul class='inline-block markers float-right cursor-hover'>
				<?php $i=0; foreach ($images AS $image):?>
				<li class='transition-1 <?php print $i==0 ? 'marker-active' : ''?>' rel="<?php print $i?>" onclick="jQuery().pSlider().setViaMarker(<?php print $id?>, <?php print $i?>);"></li>
				<?php $i++; endforeach;?>
			</ul>
		</div>
	</div>
</div>


<script type="text/javascript">
var total_images_<?php print $id?> = <?php print count($images);?>;
var loaded_images_<?php print $id?> = 0;
$('#sliderContainer_<?php print $id?> .slideshow img').load(function(){
	loaded_images_<?php print $id?>++;
	if(total_images_<?php print $id?> == loaded_images_<?php print $id?> && loaded_images_<?php print $id?>>0){
		jQuery().pSlider().init(<?php print $id?>);
	}			
});

$(document).ready(function(){    
	/**
	 * Toggle Info Box
	 */	
	$('.toggleInfo_<?php print $id?>').click(function(){		
		var target = $('.info-text', $('#sliderContainer_<?php print $id?>'));
		if($(target).css('display')=='none'){
			$(target).slideDown();
		}
		else {
			$(target).slideUp();
		}
	});	
    
	/**
	 * Swipe event
	 */			
	swipeCall = function(event, direction, distance, duration, fingerCount, fingerData){
		if(direction=='right'){
			jQuery().pSlider().prev(<?php print $id?>);
		}
		else if(direction=='left'){
			jQuery().pSlider().next(<?php print $id?>);
		}  
	}
	$('#sliderContainer_<?php print $id?>').swipe({fingers:'all', swipeLeft:swipeCall, swipeRight:swipeCall, allowPageScroll:"auto"});
 });

$(window).load(function(){
	jQuery().pSlider().init(<?php print $id?>);
});
</script>