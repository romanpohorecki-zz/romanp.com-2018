<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="breadcrumbs"><?php print $breadcrumbs?></div>
<div class="actionLinks">
	<a href="#" class="save">Save</a>
	<a href="<?php print site_url("admin/images/image/0/".$data['collection_id'])?>" class="green uploadImage" rel="shadowbox;player=iframe;width=480;height=530" style="display:none">Upload Image</a>
	<div style="float:left;margin-right:30px;"><input type="file" name="multipleUpload" value="" id="multipleUpload"></div>
	<a href="<?php print site_url("admin/images/download_collection/".$data['collection_id']);?>" class="download" target="_blank">Download Collection</a>
	<a href="<?php print site_url('admin/images/collection_delete/'.$data['collection_id'])?>" class="delete" data-message="Are you sure you want to delete this collection? All images will be permanently deleted!">Delete Collection</a>
</div>

<div id="mainContent2">
	<div class="content">
		<div id="progressBar"></div>
		<div id="collectionEdit" class="formContent">
			<div id="messages" style="padding-right:50px;"><?php print_messages($data)?></div>
			<form action="<?php site_url("admin/images/collection/".$data['collection_id'])?>" method="post" class="form" id="collectionForm">
				<input type="hidden" name="collection_id" value="<?php print $data['collection_id']?>" id="collection_id" />
				<div class="form-group">
					<label for="collection_name">Collection Name <b>*</b></label>
			        <input type="text" id="collection_name" name="collection_name" value="<?php print $data['collection_name']?>" class="form-control" style="width:592px;">
			    </div>
			</form>
		</div>
		<div id="collectionImagesList" class="clearfix"></div>
	</div>
</div>