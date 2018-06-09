<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
	var CMSURL="<?php print site_url();?>";
	var BASEURL="<?php print base_url();?>";
</script>
<?php print $this->carabiner->display();?>
<link type="text/css" rel="stylesheet" href="<?php print base_url().'assets/css/admin/popup.css'?>" media="screen" />

<div id="popupContainer">
	<div id="popupTop">
		<h1><?php print $data['title']?></h1>
		<a href="javascript:void(0)" class="close">&nbsp;</a>
	</div>
	<div id="popupContent">
		<video width="320" height="240" controls>
		  	<source src="<?php print base_url().'uploads/videos/'.$data['folder'].'/'.$data['filename']?>" type="<?php print $data['filetype']?>">
			Your browser does not support the video tag.
		</video>
	</div>
</div>