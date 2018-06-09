<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="breadcrumbs"><?php print $breadcrumbs?></div>
<div class="actionLinks">
	<a href="#" class="save">Save</a>
	<input type="file" name="upload_video" style="display:none">
	<a href="javascript:void(0)" class="uploadVideo new">Upload Video</a>
	<a href="<?php print site_url('admin/videos/collection_delete/'.$data['collection_id'])?>" class="delete" data-message="Are you sure you want to delete this collection? All videos will be permanently deleted!">Delete Collection</a>
</div>

<div id="mainContent">
	<div class="content">
		<div id="collectionEdit" class="formContent">
			<div id="messages" style="padding-right:50px;"><?php print_messages($data)?></div>
			<form action="<?php site_url("admin/videos/collection/".$data['collection_id'])?>" method="post" class="form" id="collectionForm">
				<input type="hidden" name="collection_id" value="<?php print $data['collection_id']?>" id="collection_id" />
				<div class="form-group">
					<label for="collection_name">Collection Name <b>*</b></label>
			        <input type="text" id="collection_name" name="collection_name" value="<?php print $data['collection_name']?>" class="form-control" style="width:592px;">
			    </div>
			</form>
		</div>
		<div id="collectionVideosList" class="clearfix"></div>
	</div>
</div>