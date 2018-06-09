<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php //pr($data)?>

<script type="text/javascript">
	var CMSURL="<?php print site_url();?>";
	var BASEURL="<?php print base_url();?>";
	var image_name = "<?php print $data['image_name'];?>";
</script>
<?php print $this->carabiner->display();?>
<link type="text/css" rel="stylesheet" href="<?php print base_url().'assets/css/admin/popup.css'?>" media="screen" />

<div id="popupContainer">
	<div id="loadingAnimation"><img src="<?php print base_url()."assets/images/admin/ajax-loader.gif"?>"/></div>
	<div id="popupTop">
		<h1>Edit Crop</h1>
		<a href="javascript:void(0)" class="close">&nbsp;</a>
	</div>
	<div id="popupContent" style="display:inline-block; width:100%;">
		<div id="modal"></div>
		<form action="<?php print $data["post_url"]?>" method="post" class="form" id="imageForm">
			
			<div id="imageCropContainer">
				<img src="<?php print base_url()."cache/images/".$data["image_src"]?>" />
				
				<div id="messages"><?php print_messages($data)?></div>
			</div>
			
			<input id="img_src" type="hidden" value="<?php print base_url()."cache/images/".$data["image_src"]?>">
			<input id="thumb_src" type="hidden" value="<?php print base_url().$data["thumb"]?>">
			<input id="image_id" type="hidden" value="<?php print $data["image_id"]?>">
			<input id="entity_type" type="hidden" value="<?php print $data["entity_type"]?>">
			<input id="entity_id" type="hidden" value="<?php print $data["entity_id"]?>">
			<input id="coord_x1" type="hidden" value="0" name="coord_x1">
			<input id="coord_x2" type="hidden" value="0" name="coord_x2">
			<input id="coord_y1" type="hidden" value="0" name="coord_y1">
			<input id="coord_y2" type="hidden" value="0" name="coord_y2">
			<input id="resize_width" type="hidden" value="0" name="resize_width">
			<input id="resize_height" type="hidden" value="0" name="resize_height">
			
			<div id="imageCropDetailsContainer">
				<div class="form-group fullDDWidth">
			    	<label class="control-label">Select Crop*</label>
			    	<?php print generate_dd('crop_id', $crops, 0)?>						
				</div>
				<div class="form-group">
					<a href="javascript:void(0)" class="btn" id="doRevert">Revert to original</a>
				</div>
				<div class="form-group">
					<a href="javascript:void(0)" class="btn" id="doCrop" style="margin-right:6px">Save Crop</a>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
var crops = {
<?php $j=1; foreach ($crops_arr AS $key=>$value):?>
"<?php print $value["crop_id"]?>":{"width":<?php print $value["width"]?>, "height":<?php print $value["height"]?>} <?php if($j < count($crops_arr)) print ",";?>
<?php $j++; endforeach;?>
}
</script>
<style type="text/css">
#popupContent .form .dropDownBox {
	float:left;
}
</style>