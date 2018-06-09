<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if(strlen($data['searchkey'])>0):?>
	<h2 style="padding-bottom:25px">Searched for "<?php print $data['searchkey']?>"</h2>
<?php endif;?>
<?php if(count($data['files'])>0):?>
	<div id="popupImagesList">	
	<?php foreach ($data['files'] AS $file):?>
		<?php 
			$class = "fileSelected";    	
	    	if($file['active'] == 0)
	    		$class = "fileNotSelected";
    	?>
		<div class="fileItem" id="item_<?php print $file['file_id']?>"  >
			<a href="<?php print $file['file_id']?>" class="fileVisibility"></a>
			<div class='fileContainer'>
				<img class="img" src="<?php print base_url()."assets/images/cms/icons/file-small.png"?>"/>
			</div>
	        <div class="fileTitle"><?php print $file['title']?></div>
		</div>
	<?php endforeach;?>	
	</div>
	
	<div class="pagination" style="clear:both;margin-left:10px">
		<!--  <div class="paginationInfo">Page <?php print $data['cur_page']?> of <?php print $data['total_pages'];?></div>-->
		<div class="paginationLinks"><?php print $data['pagination'];?></div>
	</div>		
<?php else:?>
	<div class="info" style="margin:10px;"><p>No files to display.</p></div>	
<?php endif;?>
