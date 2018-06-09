<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="imagesOverlay"></div>
	
<div id="breadcrumbs"><?php print $breadcrumbs?></div>	
<div class="actionLinks">
	<a href="#" class="save">Save</a>
	<?php if($data['page_id']):?>
	<a href="<?php print site_url("pages/".$data['page_id']."/".url_title($data['title']))?>" target="_blank" class="preview">Preview</a>
	<a href="javascript:void(0)" class="publishedUnpublished <?php $data['published']==1? print 'published' : print 'unpublished' ?>"><?php $data['published']==1? print 'Published' : print 'Unpublished' ?></a>
	<a href="<?php print site_url('admin/pages/delete/'.$data['page_id'])?>" class="delete" data-message="Are you sure you want to delete this Page?">Delete</a>
	<?php endif;?>
</div>

<form role="form" action="<?php print site_url("admin/pages/".$data['action'])?>" method="post" class="form" id="pageForm">
<input type="hidden" name="save" value="1">
<input type="hidden" id="page_id" name="page_id" value="<?php print $data['page_id']?>">
<input type="hidden" id="entity_id" name="entity_id" value="<?php print (int) $data['page_id']?>">
<input type="hidden" id="entity_type" name="entity_type" value="page">

<div id="mainContent">
	<div class="content" id="containerCenter">
		
		<div id="messages"><?php print_messages($data)?></div>
						
		<div class="formContent">
			<div class="form-group">
				<label for="title">Title <b>*</b></label>
				<input type="text" class="form-control required" id="title" name="title" value="<?php print $data['title']?>">
			</div>
		</div>
	</div>
</div>		
		
<div id="sections">
	<?php if(is_array($data["section"]) && !empty($data["section"])):?>
		<?php foreach ($data["section"] AS $index=>$section):
			$section["key"] = $section["section_id"];
			$section["page_id"] = $data['page_id'];
			if($section["type"]=="regular") {
				$this->load->view("admin/pages/page_section.php", array("data"=>$section));
			}	
			elseif($section["type"]=="wide_image"){
				$this->load->view("admin/pages/page_wide_image_section.php", array("data"=>$section));
			}	
		endforeach;?>
	<?php endif;?>
</div>
<div class="twinButtons">
	<input type="button" class="btn btn-primary btnSize" value="Add text" onclick="addNewSection();"/>
	<input type="button" class="btn btn-danger btnSize" value="Add image" onclick="addWideImageSection();"/>
</div>

</form>