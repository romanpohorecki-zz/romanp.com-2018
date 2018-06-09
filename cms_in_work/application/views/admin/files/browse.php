<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>


<script type="text/javascript" src="<?php print base_url().'assets/js/admin/files/'.$js_script.'.js'?>"></script>
<div class="clearfix browseFileTop actionLinks" style="position:static;border-bottom:1px solid #ccc;height:60px;width:1040px;">
	<a href="javascript:void(0)" class="returnFiles" id="returnWithFiles"></a>
	<div class="collectionSearch">
		<input type="text" id="searchKey" name="searchKey" value="" class="formField">
		<a href="javascript:void(0)" class="searchButton">Search</a>
	</div>
</div>

<div id="imagesOverlayBar"></div>
<div id="imagesOverlayContent"><p class="info" style="margin:10px">Please select a collection from left bar.</p></div>