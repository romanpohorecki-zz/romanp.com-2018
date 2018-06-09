<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if($data['success']): ?>
<script type="text/javascript">
	window.parent.location.reload();
</script>
<?php return;?>
<?php endif;?>
<?php print $this->carabiner->display();?>
<link type="text/css" rel="stylesheet" href="<?php print base_url().'assets/css/admin/popup.css'?>" media="screen" />

<div id="popupContainer">
	<div id="popupTop">
		<h1>New Collection</h1>
		<a href="javascript:void(0)" class="close">&nbsp;</a>
	</div>
		
	<div id="popupContent">			
		<div id="messages"><?php print_messages($data)?></div>
		<form action="<?php site_url("admin/videos/collection_add")?>" method="post" class="form" id="collectionForm">		
			<div class="form-group">
				<label for="collection_name">Name <b>*</b></label>
		        <input type="text" id="collection_name" name="collection_name" value="<?php print $data['collection_name']?>" class="form-control">
		    </div>
		    <button type="submit" name="submit" class="btn btnLogin btnSize">Save</button>
		</form>
	</div>
</div>