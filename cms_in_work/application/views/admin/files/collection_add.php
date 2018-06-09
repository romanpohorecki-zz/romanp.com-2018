<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if($data['success']): ?>
<script type="text/javascript">
	window.parent.location.reload();
</script>
<?php endif;?>
<?php print $this->carabiner->display();?>

<div id="popupContainer">
	<div id="popupTop">
		<h1>New Collection</h1>
		<a href="javascript:void(0)" class="close">&nbsp;</a>
	</div>
	
	<div class="actionLinks">
		<a class="save" href="#">Save</a>
	</div>
		
	<div id="popupContent">			
		<div id="messages"><?php print_messages($data)?></div>	
		<form action="<?php print site_url("admin/files/collection_add")?>" method="post" class="form" id="collectionForm">
			<div class="row">
				<label for="titleLabel">Name <b>*</b></label>
				<input type="text" id="titleLabel" name="collection_name" value="<?php print $data['collection_name']?>" class="formField">
			</div>			
		</form>
	</div>
</div>