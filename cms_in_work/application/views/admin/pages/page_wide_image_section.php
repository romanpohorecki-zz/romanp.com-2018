<div class="section">
	<a href="javascript:void(0)" class="removeSection btn btnSize" onclick="removeSection(this)">Delete Section</a>
	<input type="hidden" name="section[<?php print $data["key"]?>][type]" value="wide_image"/>	
	<input type="hidden" name="section[<?php print $data["key"]?>][section_id]" value="<?php print (isset($data['section_id'])) ? $data["section_id"] : 0;?>"/>
	<div class="sectionRow">
		<a href="javascript:void(0)" class="insertImageToPage btn btnSize" onclick="selectSingleImage(this)" <?php !empty($data["image"]) ? print "style='display:none'" : ""?>>Add Image</a>
		<div class="collectionItem" data-entity_type="<?php print $data["section_id"]?>" data-entity_id="0"  <?php !empty($data["image"]) ? "": print "style='display:none'"?>>
			<a class="removeImage" href="javascript:void(0)" data-image_id="<?php if($data["image"]["image_id"]) print $data["image"]["image_id"]?>" onclick="unassignSingleImage(this)"></a>
			<div class="imageContainer">
				<input type="hidden" class="hidden" name="section[<?php print $data["key"]?>][wide_image_id]" value="<?php if($data["image"]["image_id"]) print $data["image"]["image_id"]?>"/>
				<input type="hidden" class="hiddenUrl" name="section[<?php print $data["key"]?>][wide_image_src]" value="<?php if($data["image"]["image_src"]) print $data["image"]["image_src"]?>"/>
				<img class="img" src="<?php if($data["image"]["image_src"]) print $data["image"]["image_src"]?>"/>
				<div class="imageButtons" style="height:37px;">
					<a href="<?php print site_url("admin/images/crop_edit/page/".(int)$data["page_id"]."/".(int)$data["image"]["image_id"]);?>" class="cropImage" rev="0" rel="shadowbox;player=iframe;width=790;height=600"></a>		
				</div>
			</div>
			<div class="imageInfo">
				<span class="resolution"><?php print $data["image"]["pixels"]?></span>
				<span class="size"><?php print $data["image"]["kilobytes"]?></span>
			</div>
			<div class="imageCaption">
				<?php print $data["image"]["caption"]?>
			</div>
			<div class="imageUrl">
				<?php print $data["image"]["url"]?>
			</div>
			<div class="imageDisplayMode">
			    <?php print generate_dd('display_mode', get_images_display_modes(), $data["image"]["display_mode"], '', 'Image Class')?>
			</div>
		</div>
	</div>
</div>