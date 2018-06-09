<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="actionLinks">
	<a href="javascript:void(0)" class="insertToPage" onclick="returnWithImages()">Add Selected Images To Page</a>
	
	<div style="float:left;margin-right:30px;display:none" class="showImageUpload"><input type="file" name="multipleUpload" value="" id="multipleUpload"></div>
	
	<div class="collectionSearch">
		<input type="text" id="searchKey" name="searchKey" value="" class="formField">
		<a href="javascript:void(0)" class="searchButton btn" onclick="searchIntoCollection()">Search</a>
	</div>
</div>

<div id="imagesOverlayBar">
	<?php if(count($collections)>0):?>
	<ul class="collectionsList">
		<li class="listHead">Images</li>
		<?php foreach ($collections AS $collection):?>
		<li>
			<a href="javascript:void(0)" onclick="loadCollection(<?php print $collection['collection_id']?>, this)" data-collection_id="<?php print $collection['collection_id']?>"><?php print $collection['title'];?></a>
		</li>
		<?php endforeach;?>
	</ul>	
	<?php else:?>
	<p class="info">You don't have any collection added. Please visit /Library -> Images section first.</p>	
	<?php endif;?>
</div>
<div id="imagesOverlayContent"><p class="info" style="margin:10px">Please select a collection from left bar.</p></div>