<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php //pr($data)?>

<section id="contact" class='padding-top-60 padding-bottom-60 scrollMe'>
	<a name="contact"></a>
	<?php if(isset($data['logo_image'])):?>
	<a class='logo-footer border-0 centering-horizontal block margin-bottom-60' href='<?php print site_url()?>'>
		<img src="<?php print $data['logo_image']['image_src']?>" alt="contact"/>
	</a>
	<?php endif?>
	<ul class='list-style-none centering-horizontal block padding-0 text-center'>
		<?php if(!empty($data['address'])):?>
		<li><?php print $data['address']?></li>
		<br>
		<?php endif;?>
		<?php if(!empty($data['phone'])):?>
		<li><?php print $data['phone']?></li>
		<?php endif;?>
		<?php if(!empty($data['fax'])):?>
		<li>fax: <?php print $data['fax']?></li>
		<?php endif;?>
	</ul>
	<?php if(!empty($data['email'])):?>
	<a class='button-email centering-horizontal margin-top-60 margin-bottom-60 text-center block' href="mailto:<?php print $data['email']?>"><?php print $data['email']?></a>
	<?php endif;?>
	<?php if(!empty($data['copyright'])):?>
	<p class='width-100 text-center'><?php print $data['copyright']?></p>
	<?php endif;?>
</section>