<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="breadcrumbs"><?php print $breadcrumbs?></div>
<div class="actionLinks">
	<a class="saveCollection save" href="javascript:void(0)">Save</a>
		
	<a href="<?php print site_url("admin/files/file/0/".$data['collection_id'])?>" class="uploadFile" rel="shadowbox;player=iframe;width=480;height=530" style="width:54px;">Upload file</a>
	<div style="float:left"><input type="file" name="multipleUpload" value="" id="multipleUpload" style="float:left"></div>
	<a style="display:none" href="<?php print site_url("admin/files/download_collection/".$data['collection_id']);?>" class="grey download" target="_blank" style="width:60px;">Download Collection</a>
	
	<a href="javascript:void(0)" class="delete" id="removeCollection">Delete Collection</a>
</div>

<div id="mainContent">
	<div class="content">
		<div id="progressBar"></div>
		<input type="hidden" name="collection_id" value="<?php print $data['collection_id']?>" id="collection_id">
		<div id="collectionEdit" class="clearfix">
			<div id="messages"><?php print_messages($data)?></div>
			<form action="<?php print site_url("admin/files/collection/".$data['collection_id'])?>" method="post" class="form" id="collectionForm">
				<input type="hidden" name="collection_id" value="<?php print $data['collection_id']?>">
				<div class="row">
					<label for="titleLabel" style="width:60px">Name <b>*</b></label>
					<input type="text" id="titleLabel" name="collection_name" value="<?php print $data['collection_name']?>" class="formField">
				</div>
			</form>
		</div>
		<div id="filesList"></div>
	</div>
</div>