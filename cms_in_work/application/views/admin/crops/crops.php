<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="breadcrumbs"><?php print $breadcrumbs?></div>
<div class="actionLinks">
	<a href="<?php print site_url("admin/crops/add");?>" class="new">New Crop</a>
</div>

<div id="mainContent">
	<div class="content" id="cropsList">
		<div id="messages"><?php print_messages($data)?></div>
		<?php if(count($data['crops'])>0):?>	
		<ul class="list">
			<li class="listHead">
				<div style="width:60%"><span>Title</span></div>
				<div style="width:20%"><span>Width</span></div>
				<div style="width:20%"><span>Height</span></div>
			</li>
			<?php foreach ($data['crops'] AS $crop):?>
			<li class="listRow" rel="<?php print $crop["crop_id"]?>">
				<div style="width:60%"><span><a href="<?php print site_url("admin/crops/edit/".$crop["crop_id"])?>"><?php print $crop['title']?></a></span></div>
				<div style="width:20%"><span><a href="<?php print site_url("admin/crops/edit/".$crop["crop_id"])?>"><?php print $crop['width']?></a></span></div>
				<div style="width:20%"><span><a href="<?php print site_url("admin/crops/edit/".$crop["crop_id"])?>"><?php print $crop['height']?></a></span></div>
			</li>
			<?php endforeach;?>
		</ul>		
		<?php else:?>
		<div class="info"><p>No image crops added.</p></div>	
		<?php endif;?>
	</div>
</div>	