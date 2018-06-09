<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<section id="about" class='width-100 clear-both scrollMe'>
	<a name="about"></a>
	<div class='relative tile ratio-16-9 overflow-hidden cover-background' <?php print (isset($data['homepage_portrait_image'])) ? 'style="background-image: url('.$data['homepage_portrait_image']['image_src'].')"' : ''?>>
		<div class='slideshow tile-content width-1000 overflow-hidden'>
		</div>
	</div>
	<div class='about-description centering-horizontal scrollMe'>
		<div class='padding-10'><?php print $data['homepage_text']?></div>
	</div>	
</section>