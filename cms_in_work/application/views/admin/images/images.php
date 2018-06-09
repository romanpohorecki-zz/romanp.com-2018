<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="messages"><?php print_messages($data)?></div>
<?php if(count($data['images'])>0):?>	
	<?php foreach ($data['images'] AS $image):?>
	<div class="collectionItem" id="item_<?php print $image['image_id']?>"  >
		<div class="imageContainer">
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

	<div class="pagination" style="display:none;">
		<div class="paginationInfo">Page <?php print $data['cur_page']?> of <?php print $data['total_pages'];?></div>
		<div class="paginationLinks"><?php print $data['pagination'];?></div>
	</div>		
<?php else:?>
	<div class="info"><p>No images to display.</p></div>	
<?php endif;?>
