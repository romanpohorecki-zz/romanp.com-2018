<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if(strlen($data['searchkey'])>0):?>
	<h2 style="padding-bottom:25px">Searched for "<?php print $data['searchkey']?>"</h2>
<?php endif;?>
<?php if(count($data['images'])>0):?>
	<div id="popupImagesList">	
	<?php foreach ($data['images'] AS $image):?>
		<div class="collectionItem" id="item_<?php print $image['image_id']?>" data-image_id="<?php print $image['image_id']?>">
			<div class="imageContainer">
				<a href="javascript:void(0)" class="imageVisibility" onclick="imageSelectToggle(this)" data-image_id="<?php print $image['image_id']?>"></a>
				<img class="img" src="<?php print $image['image_src'];?>" alt="<?php print $image['title']?>" title="<?php print $image['title']?>" />
				<div class="imageButtons">
	        		<a href='javascript:void(0);' onclick="javascript:deleteImage('<?php print $image['image_id']?>')" class="deleteImage"></a>
	        		<a href="<?php print site_url("admin/images/image/".$image['image_id']."/".$image['collection_id']) ?>" class="editImage" rel="shadowbox;player=iframe;width=745;height=400"></a>
	        	</div>
			</div>
			<div class="imageInfo">
				<span class="resolution"><?php print $image['pixels']?></span>
				<span class="size"><?php print $image['kilobytes']?></span>
			</div>
		</div>	
	<?php endforeach;?>
		<a style="display:none" href="<?php print site_url('admin/images/image/0/'.$data['collection_id'])?>" class="uploadImageButton" rel="shadowbox;player=iframe;width=745;height=400">Upload Image</a>
	</div>		
<?php else:?>
	<div class="info" style="margin:10px;"><p>No images to display.</p></div><br>
	<a style="display:none" href="<?php print site_url('admin/images/image/0/'.$data['collection_id'])?>" class="uploadImageButton" rel="shadowbox;player=iframe;width=745;height=400">Upload Image</a>
<?php endif;?>