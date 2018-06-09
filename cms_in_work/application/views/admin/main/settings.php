<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
	
<div id="imagesOverlay"></div>
	
<div id="breadcrumbs"><?php print $breadcrumbs?></div>	
<div class="actionLinks">
	<a href="#" class="save">Save</a>
</div>

<div id="mainContent">
	<div class="content">
		<div id="messages"><?php print_messages($data)?></div>
		<div class="formContent">	
			<form role="form" action="<?php print site_url("admin/settings")?>" method="post" class="form" id="settingsForm">
				<input type="hidden" name="save" value="1">
				
				<div class="form-group">
					<label for="settingWebsite">Website Name <b>*</b></label>
					<input type="text" class="form-control" id="settingWebsite" name="data[website]" value="<?php print $data['data']['website']?>">
				</div>		
				<div class="form-group">
					<label>Site Offline</label><br>
					<label class="radio-inline"><input type="radio" name="data[website_offline]" value="1" <?php $data['data']['website_offline']==1? print "CHECKED":""?>>Yes</label>
					<label class="radio-inline"><input type="radio" name="data[website_offline]" value="0" <?php $data['data']['website_offline']==0? print "CHECKED":""?>>No</label>
				</div>
				<div class="form-group">
			    	<label for="settingOfflineMsg" class="control-label">Offline Message</label>
			    	<textarea name="data[website_offline_msg]" class="form-control" id="settingOfflineMsg" rows="3"><?php print $data['data']['website_offline_msg']?></textarea>
				</div>
				<div class="form-group">
					<label for="settingMaintenance">Maintenance IP</label>
					<input type="text" class="form-control" id="settingMaintenance" name="data[maintenance_ip]" value="<?php print $data['data']['maintenance_ip']?>">
				</div>
				<div class="form-group">
					<label for="settingMetaTitle">Global Meta Title</label>
					<input type="text" class="form-control" id="settingMetaTitle" name="data[meta_title]" value="<?php print $data['data']['meta_title']?>">
				</div>
				<div class="form-group">
			    	<label for="settingMetaDescription" class="control-label">Global Meta Description</label>
			    	<textarea name="data[meta_description]" class="form-control" id="settingMetaDescription" rows="3"><?php print $data['data']['meta_description']?></textarea>
				</div>
				<div class="form-group">
			    	<label class="control-label">Global Meta Keywords</label>
			    	<textarea name="data[meta_keywords]" class="form-control" id="settingOfflineMsg" rows="3"><?php print $data['data']['meta_keywords']?></textarea>
				</div>
				<div class="form-group">
			    	<label for="settingSlidingSpeed" class="control-label">Sliding Speed (ms)</label>
			    	<input type="text" class="form-control" id="settingSlidingSpeed" name="data[slide_speed]" value="<?php print $data['data']['slide_speed']?>">
				</div>
				<div class="form-group">
			    	<label class="control-label">CMS Logo</label>
			    	<input type="hidden" id="entity_id" name="entity_id" value="0">
					<input type="hidden" id="entity_type" name="entity_type" value="admin_logo">
			    	<div class="section">						
						<div class="sectionRow">
							<a href="javascript:void(0)" class="insertImageToPage btn btnSize" onclick="selectSingleImage(this)" <?php !empty($data['data']['admin_logo']) ? print "style='display:none'" : ""?>>Add Image</a>
							<div class="collectionItem" <?php print !empty($data['data']['admin_logo']) ? "style='margin:0' ": "style='display:none;margin:0'"?>>
								<a class="removeImage" href="javascript:void(0)" data-image_id="<?php if($data['data']["admin_logo"]) print $data['data']["admin_logo"]?>" onclick="unassignSingleImage(this)"></a>
								<div class="imageContainer">
									<input type="hidden" class="hidden" name="data[admin_logo]" value="<?php if($data['data']["admin_logo"]) print $data['data']["admin_logo"]?>"/>
									<img class="img" src="<?php if($data['data']["admin_logo_image"]["image_src"]) print $data['data']["admin_logo_image"]["image_src"]?>"/>
									<div class="imageButtons" style="height:37px;">
										<a href="<?php print site_url("admin/images/crop_edit/admin_logo/0/".(int)$data['data']["admin_logo"]);?>" class="cropImage" rev="0" rel="shadowbox;player=iframe;width=790;height=600"></a>		
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>