<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="imagesOverlay"></div>
<div id="breadcrumbs"><?php print $breadcrumbs?></div>
<div class="actionLinks">
	<a href="<?php print site_url("admin/contact");?>" class="save">Save</a>
</div>

<div id="mainContent">
	<div class="content">
		<div id="messages"><?php print_messages($data)?></div>		
		<form role="form" action="<?php print site_url("admin/contact/index")?>" method="post" class="form" id="contactForm">
			<div class="formContent">				
				<div class="form-group">	
					<input type="hidden" id="entity_id" name="entity_id" value="0">
					<input type="hidden" id="entity_type" name="entity_type" value="contact_logo">		
			    	<label class="control-label">Contact Logo</label>
					<div class="sectionRow">
						<a href="javascript:void(0)" class="insertImageToPage btn btnSize" onclick="selectSingleImage(this)" <?php !empty($data['logo']) ? print "style='display:none'" : ""?>>Add Image</a>
						<div class="collectionItem" data-entity_type="homepage_contact" data-entity_id="0" <?php print !empty($data['logo']) ? "style='margin:0' ": "style='display:none;margin:0'"?>>
							<a class="removeImage" href="javascript:void(0)" data-image_id="<?php if($data["logo"]) print $data["logo"]?>" onclick="unassignSingleImage(this)"></a>
							<div class="imageContainer">
								<input type="hidden" class="hidden" name="logo" value="<?php if($data["logo"]) print $data["logo"]?>"/>
								<img class="img" src="<?php if($data["logo_image"]["image_src"]) print $data["logo_image"]["image_src"]?>"/>
								<div class="imageButtons" style="height:37px;">
									<a href="<?php print site_url("admin/images/crop_edit/homepage_contact/0/".(int)$data["logo"]);?>" class="cropImage" rev="0" rel="shadowbox;player=iframe;width=790;height=600"></a>		
								</div>
							</div>
							<div class="imageInfo">
								<span class="resolution"><?php print $data["logo_image"]["pixels"]?></span>
								<span class="size"><?php print $data["logo_image"]["kilobytes"]?></span>
							</div>
							<div class="imageCaption">
								<?php print $data["logo_image"]["caption"]?>
							</div>
							<div class="imageUrl">
								<?php print $data["logo_image"]["url"]?>
							</div>
							<div class="imageDisplayMode">
							    <?php print generate_dd('display_mode', get_images_display_modes(), $data["logo_image"]["display_mode"], '', 'Image Class')?>
							</div>
						</div>
					</div>
				</div>					
				<div class="form-group" style="clear:both;padding-top:30px">
					<label for="address">Address</label>
					<input type="text" class="form-control" id="address" name="address" value="<?php print $data['address']?>">
				</div>				
				<div class="form-group" style="clear:both">
					<label for="phone">Phone</label>
					<input type="text" class="form-control" id="phone" name="phone" value="<?php print $data['phone']?>">
				</div>	
				<div class="form-group" style="clear:both">
					<label for="fax">Fax</label>
					<input type="text" class="form-control" id="fax" name="fax" value="<?php print $data['fax']?>">
				</div>
				<div class="form-group" style="clear:both">
					<label for="email">Email</label>
					<input type="text" class="form-control" id="email" name="email" value="<?php print $data['email']?>">
				</div>
				<div class="form-group" style="clear:both">
					<label for="copyright">Copyright Text</label>
					<input type="text" class="form-control" id="copyright" name="copyright" value="<?php print $data['copyright']?>">
				</div>
			</div>
		</form>		
	</div>
</div>	