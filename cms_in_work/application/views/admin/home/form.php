<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="imagesOverlay"></div>
<div id="breadcrumbs"><?php print $breadcrumbs?></div>
<div class="actionLinks">
	<a href="<?php print site_url("admin/home");?>" class="save">Save</a>
</div>

<div id="mainContent">
	<div class="content">
		<div id="messages"><?php print_messages($data)?></div>		
		<form role="form" action="<?php print site_url("admin/home/index")?>" method="post" class="form" id="homeForm">
			<div class="formContent">				
				<div class="form-group" style="width:240px;float:left">	
					<input type="hidden" id="entity_id" name="entity_id" value="0">
					<input type="hidden" id="entity_type" name="entity_type" value="homepage_home">		
			    	<label class="control-label">Mobile Header</label>
					<div class="sectionRow">
						<a href="javascript:void(0)" class="insertImageToPage btn btnSize" onclick="selectSingleImage(this)" <?php !empty($data['mobile_header']) ? print "style='display:none'" : ""?>>Add Image</a>
						<div class="collectionItem" data-entity_type="homepage_home" data-entity_id="0" <?php print !empty($data['mobile_header']) ? "style='margin:0' ": "style='display:none;margin:0'"?>>
							<a class="removeImage" href="javascript:void(0)" data-image_id="<?php if($data["mobile_header"]) print $data["mobile_header"]?>" onclick="unassignSingleImage(this)"></a>
							<div class="imageContainer">
								<input type="hidden" class="hidden" name="mobile_header" value="<?php if($data["mobile_header"]) print $data["mobile_header"]?>"/>
								<img class="img" src="<?php if($data["mobile_header_image"]["image_src"]) print $data["mobile_header_image"]["image_src"]?>"/>
								<div class="imageButtons" style="height:37px;">
									<a href="<?php print site_url("admin/images/crop_edit/homepage_home/0/".(int)$data["mobile_header"]);?>" class="cropImage" rev="0" rel="shadowbox;player=iframe;width=790;height=600"></a>		
								</div>
							</div>
							<div class="imageInfo">
								<span class="resolution"><?php print $data["mobile_header_image"]["pixels"]?></span>
								<span class="size"><?php print $data["mobile_header_image"]["kilobytes"]?></span>
							</div>
							<div class="imageCaption">
								<?php print $data["mobile_header_image"]["caption"]?>
							</div>
							<div class="imageUrl">
								<?php print $data["mobile_header_image"]["url"]?>
							</div>
							<div class="imageDisplayMode">
							    <?php print generate_dd('display_mode', get_images_display_modes(), $data["mobile_header_image"]["display_mode"], '', 'Image Class')?>
							</div>
						</div>
					</div>
				</div>
				
				<div class="form-group" style="clear:both">
					<label for="phone">Headline </label>
					<input type="text" class="form-control" id="headline" name="headline" value="<?php print $data['headline']?>">
				</div>	
				
				<div class="form-group" style="clear:both;">
					<label for="homepage_text">About <b>*</b></label>
					<textarea class="tinymce" name="about"><?php print (isset($data["about"])) ? $data["about"] : '';?></textarea>					
				</div>
			</div>
		</form>		
	</div>
</div>	