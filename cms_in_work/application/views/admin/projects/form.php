<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="imagesOverlay"></div>
	
<div id="breadcrumbs"><?php print $breadcrumbs?></div>	
<div class="actionLinks">
	<a href="#" class="save">Save</a>
	<?php if($data['project_id']):?>
	<a href="<?php print site_url("work/".$data['project_id']."/".url_title($data['title']))?>" target="_blank" class="preview">Preview</a>
	<a href="javascript:void(0)" class="publishedUnpublished <?php $data['published']==1? print 'published' : print 'unpublished' ?>"><?php $data['published']==1? print 'Published' : print 'Unpublished' ?></a>
	<a href="<?php print site_url('admin/projects/delete/'.$data['project_id'])?>" class="delete" data-message="Are you sure you want to delete this Project?">Delete</a>
	<?php endif;?>
</div>

<form role="form" action="<?php print site_url("admin/projects/".$data['action'])?>" method="post" class="form" id="projectForm">
<input type="hidden" name="save" value="1">
<input type="hidden" id="project_id" name="project_id" value="<?php print $data['project_id']?>">
<input type="hidden" id="category_id" name="category_id" value="<?php print $data['category']['category_id']?>">
<input type="hidden" id="entity_id" name="entity_id" value="<?php print (int) $data['project_id']?>">
<input type="hidden" id="entity_type" name="entity_type" value="project">

<div id="mainContent">
	<div class="content" id="containerCenter">
		
		<div id="messages"><?php print_messages($data)?></div>
						
		<div class="formContent">
			<div class="form-group col-sm-2 noPadding">
				<label for="sort_order_project">Sort Order</label>
				<input type="text" class="form-control" id="sort_order_project" name="sort_order_project" value="<?php print $data['sort_order']?>">
			</div>
			<div class="form-group" style="clear:both">
				<label for="title">Title <b>*</b></label>
				<input type="text" class="form-control required" id="title" name="title" value="<?php print $data['title']?>">
			</div>
		</div>
		<div class="form-group" style="clear:both;margin-bottom:0">
			<label for="description">Description</label>
			<textarea class="tinymce" name="description"><?php print $data["description"]?></textarea>
		</div>		
	</div>
</div>

<div id="sections">
<?php if(is_array($data["sections"]) && !empty($data["sections"])):?>
	<?php $i=1; foreach ($data["sections"] AS $index=>$section):
		$section['index'] = $i;
		$this->load->view("admin/projects/section_images.php", array("data"=>$section));
	$i++; endforeach;?>
<?php endif;?>
</div>
</form>
