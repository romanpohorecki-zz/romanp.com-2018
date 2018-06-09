<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
	var CMSURL="<?php print site_url();?>";
	var BASEURL="<?php print base_url();?>";
	var file_name = "<?php print $data['file_name'];?>";
</script>
<?php print $this->carabiner->display();?>

<div id="popupContainer">
	<div id="popupTop">
		<h1><?php print $data['page_title'];?></h1>
		<a href="javascript:void(0)" class="close">&nbsp;</a>
	</div>
	<div id="popupContent">	
		<div id="messages"><?php print_messages($data)?></div>	
		<form action="<?php print site_url("admin/files/file/".(int) $data['file_id']."/".$data['collection_id_present'])?>" method="post" class="form" id="fileUploadForm">
			<input type="hidden" id="file_id" name="file_id" value="<?php print $data['file_id']?>">
			<div class="row uploadFileForm">
				<div class="fileButtonContainer">
					<a href="javascript:void(0)" class="saveFile buttonFilePopup">Save</a><br><br>  
					<input id="file_upload" name="file_upload" type="file"/>
				</div>
				<input type="hidden" name="file_src" id="fileSrc" value="<?php print $data['file_src']?>">
				<br>
				<span class="fileContainer" style="height:30px"><?php print $data['file'];?></span>
				<span id="errorContainer"></span>
			</div>
			<div id="progressBar"></div>
			<div class="row">
				<label for="titleLabel">Title <b>*</b></label>
				<input type="text" id="titleLabel" name="title" value="<?php print htmlentities($data['title'])?>" class="formField">
			</div>
			<?php if(!$data['collection_id_present']):?>
			<div class="row">
				<label>Collection <b>*</b></label>
				<div class="dropDownBox"><?php print form_dropdown('collection_id', $data['collections'], $data['collection_id'], 'class="formSelect"');?></div>
			</div>
			<?php else:?>
				<input type="hidden" id="collection_id" name="collection_id" value="<?php print $data['collection_id']?>">
			<?php endif;?>
		</form>
	</div>
</div>