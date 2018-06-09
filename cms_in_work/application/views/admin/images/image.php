<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
	var CMSURL="<?php print site_url();?>";
	var BASEURL="<?php print base_url();?>";
	var image_name = "<?php print $data['image_name'];?>";
</script>
<?php print $this->carabiner->display();?>
<link type="text/css" rel="stylesheet" href="<?php print base_url().'assets/css/admin/popup.css'?>" media="screen" />

<div id="popupContainer">
	<div id="popupTop">
		<h1><?php print $data['page_title'];?></h1>
		<a href="javascript:void(0)" class="close">&nbsp;</a>
	</div>
	<div id="popupContent">
		<form action="<?php print site_url("admin/images/image/".(int) $data['image_id']."/".$data['collection_id_present'])?>" method="post" class="form" id="imageUploadForm">
			<input type="hidden" id="image_id" name="image_id" value="<?php print $data['image_id']?>">
			
			<div class="imagePopupLeft uploadFileForm">				
				<input type="hidden" name="image_src" id="imageSrc" value="<?php print $data['image_src']?>">
				<span class="imageContainer"><img src="<?php print $data['image'];?>"></span>
				<span id="errorContainer"></span>
				<div id="messages"><?php print_messages($data)?></div>
			</div>
			
			<div class="imagePopupRight">
				<div id="progressBar"></div>
				<div class="imageButtonContainer">
					<input id="image_upload" name="image_upload" type="file"/>
				</div>	
									
			    <?php if(!$data['collection_id_present']):?>
			    <div class="form-group">
			    	<label class="col-sm-2 control-label">Collection*</label>
			    	<div class="col-sm-6">
			    		<?php print generate_dd('collection_id', $data['collections'], !empty($data['collection_id']) ? $data['collection_id'] : '')?>	
					</div>
				</div>
				<?php else:?>
					<input type="hidden" id="collection_id" name="collection_id" value="<?php print $data['collection_id']?>">
				<?php endif;?>
				<div class="form-group">
					<label for="caption">Caption</label>
			        <input type="text" id="caption" name="caption" value="<?php print htmlentities($data['caption'])?>" class="form-control">
			    </div>	
			    <div class="form-group">
					<label for="url">Url</label>
			        <input type="text" id="url" name="url" value="<?php print htmlentities($data['url'])?>" class="form-control">
			    </div>	
			    <button type="submit" name="save" class="btn btnLogin">SAVE</button>	
			</div>
		</form>
	</div>
</div>