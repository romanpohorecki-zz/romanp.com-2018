<div class="section" data-section_id="<?php print $data["section_id"]?>">
	<input type="hidden" class="sectionId" name="section_id[<?php print $data["section_id"]?>]" value="<?php print $data['section_id']?>"/>
	<div class="sectionRow" style="padding-top:30px">
		<h4 style="clear:both">Project Images</h4>
		<div class="multipleImages sortableImages" id="<?php print $data["section_id"]?>">
		<?php if(!empty($data['images'])):?>
			<?php $i=0; foreach ($data['images'] AS $image):?>
			<div class="collectionItem" data-entity_type="<?php print $data["section_id"]?>" data-entity_id="0">
				<a class="removeImage" href="javascript:void(0)" data-image_id="<?php print $image['image_id']?>" onclick="removeGroupImage(this)"></a>
				<div class="imageContainer">
					<input type="hidden" class="hidden" name="images[<?php print $data['section_id']?>][<?php print $image['image_id']?>]" value="<?php print $image['image_id']?>">
					<input type="hidden" class="sort_order" name="sort_order[<?php print $data['section_id']?>][<?php print $image['image_id']?>]" value="<?php print $i?>">
					<img class="img" src="<?php print $image['image_src']?>">
					<div class="imageButtons" style="height:37px;">
						<a href="<?php print site_url('admin/images/crop_edit/'.$image['entity_type'].'/'.$image["entity_id"].'/'.(int)$image["image_id"]);?>" class="cropImage" rev="0" rel="shadowbox;player=iframe;width=790;height=600"></a>		
					</div>
				</div>
				<div class="imageInfo">
					<span class="resolution"><?php print $image['pixels']?></span>
					<span class="size"><?php print $image['kilobytes']?></span>
				</div>
				<div class="imageCaption"><?php print $image['caption']?></div>
				<div class="imageUrl"><?php print $image['url']?></div>
				<div class="imageDisplayMode">
				    <?php print generate_dd('display_mode', get_images_display_modes(), $image["display_mode"], '', 'Image Class')?>
				</div>
			</div>
			<?php $i++; endforeach;?>	
		<?php endif;?>
		</div>
		<a href="javascript:void(0)" class="insertImageToPage btn btnSize" onclick="selectGroupImages('<?php print $data["section_id"]?>','<?php print $data["section_id"]?>', 0)">Add Images</a>
	</div>
</div>