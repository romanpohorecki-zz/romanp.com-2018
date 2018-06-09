<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="breadcrumbs"><?php print $breadcrumbs?></div>	
<div class="actionLinks">
	<a href="#" class="save">Save</a>
	<?php if($data['crop_id']):?><a href="<?php print site_url('admin/crops/delete/'.$data['crop_id'])?>" class="delete" data-message="Are you sure you want to delete this Image Crop?">Delete</a><?php endif;?>
</div>

<div id="mainContent">
	<div class="content" id="containerCenter">
		<div id="messages"><?php print_messages($data)?></div>
		<div class="formContent">	
			<form role="form" action="<?php print site_url("admin/crops/".$data['action'])?>" method="post" class="form" id="cropForm">
				<input type="hidden" name="save" value="1">
				<input type="hidden" id="crop_id" name="crop_id" value="<?php print $data['crop_id']?>">
				<div class="form-group">
					<label for="title">Title <b>*</b></label>
					<input type="text" class="form-control" id="title" name="title" value="<?php print $data['title']?>">
				</div>
				<div class="form-group">
					<label for="width">Width(px) <b>*</b></label>
					<input type="text" class="form-control" id="width" name="width" value="<?php print $data['width']?>">
				</div>
				<div class="form-group">
					<label for="height">Height(px) <b>*</b></label>
					<input type="text" class="form-control" id="height" name="height" value="<?php print $data['height']?>">
				</div>
			</form>	
		</div>
	</div>
</div>