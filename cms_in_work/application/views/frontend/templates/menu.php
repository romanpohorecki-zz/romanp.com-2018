<?php $instance = &get_instance();
$segment_1 = $instance->uri->segment(1);
$segment_2 = $instance->uri->segment(2);?>

<nav>
	<div class='relative'>		
		<a class='logo border-0' href='<?php print site_url()?>' <?php print (isset($logo['homepage_main_logo_image'])) ? 'style="background-image: url('.base_url().$logo['homepage_main_logo_image']['image_src'].')"' : ''?>>S3 Architects</a>		
		<div class='button-menu'></div>
		<div class="menuGroup">
			<div class='menu-1'>
				<a class='about' href='<?php print site_url()?>#about' class='' data-name='about'>about</a>
				<a class='contact' href='<?php print site_url()?>#contact' data-name='contact'>contact</a>
			</div>
			<?php if(!empty($categories)):?>
			<div class='menu-2'>
				<?php foreach ($categories AS $category):?>
				<a class='<?php print (!empty($segment_1) && $segment_1=='projects' && $segment_2==$category['category_id']) ? 'active' : ''?>' href='<?php print site_url('projects/'.$category['category_id'].'/'.url_title($category['title']))?>'><?php print $category['title']?></a>
				<?php endforeach;?>
			</div>
			<?php endif;?>
		</div>
	</div>
</nav>