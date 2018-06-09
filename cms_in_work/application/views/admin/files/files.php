<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="messages"><?php print_messages($data)?></div>
<?php if(count($data['files'])>0):?>
	
	<?php foreach ($data['files'] AS $file):?>
		<div class="fileItem" id="item_<?php print $file['file_id']?>"  >
			<div class='fileContainer'>
				<img class="img" src="<?php print base_url()."assets/images/cms/icons/file-small.png";?>"/>
			</div>
			<div class="fileTitle"><?php print $file['title']?></div>
	        <div class="actionButtons">
        		<a href="<?php print site_url("admin/files/file/".$file['file_id']."/".$file['collection_id']) ?>" class="editFile" rel="shadowbox;player=iframe;width=480;height=510"></a>
        		<a href='javascript:void(0);' onclick="javascript:deleteFile('<?php print $file['file_id']?>')" class="deleteFile"></a>
        	</div>
		</div>
	<?php endforeach;?>
	
	<div class="pagination" style="display:none;">
		<div class="paginationInfo">Page <?php print $data['cur_page']?> of <?php print $data['total_pages'];?></div>
		<div class="paginationLinks"><?php print $data['pagination'];?></div>
	</div>		
<?php else:?>
	<div class="info"><p>No files to display.</p></div>	
<?php endif;?>
