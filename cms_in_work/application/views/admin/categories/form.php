<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="imagesOverlay"></div>
	
<div id="breadcrumbs"><?php print $breadcrumbs?></div>	
<div class="actionLinks">
	<a href="#" class="save">Save</a>
	<?php if($data['category_id']):?>
	<a href="<?php print site_url("admin/projects/add?category_id=".$data['category_id']);?>" class="new">New Project</a>
	<a href="<?php print site_url("work/".$data['category_id']."/".url_title($data['title']))?>" target="_blank" class="preview">Preview</a>
	<a href="javascript:void(0)" class="publishedUnpublished <?php $data['published']==1? print 'published' : print 'unpublished' ?>"><?php $data['published']==1? print 'Published' : print 'Unpublished' ?></a>
	<?php if(empty($data['projects'])):?>
	<a href="<?php print site_url('admin/categories/delete/'.$data['category_id'])?>" class="delete" data-message="Are you sure you want to delete this Category?">Delete</a>
	<?php endif;?>
	<?php endif;?>
</div>

<form role="form" action="<?php print site_url("admin/categories/".$data['action'])?>" method="post" class="form" id="categoryForm">
<input type="hidden" name="save" value="1">
<input type="hidden" id="category_id" name="category_id" value="<?php print $data['category_id']?>">

<div id="mainContent">
	<div class="content" id="containerCenter">
		
		<div id="messages"><?php print_messages($data)?></div>
						
		<div class="formContent">
			<div class="form-group col-sm-2 noPadding">
				<label for="sort_order_category">Sort Order</label>
				<input type="text" class="form-control" id="sort_order_category" name="sort_order_category" value="<?php print $data['sort_order']?>">
			</div>
			<div class="form-group" style="clear:both">
				<label for="title">Title <b>*</b></label>
				<input type="text" class="form-control required" id="title" name="title" value="<?php print $data['title']?>">
			</div>
		</div>		
		
		<br>
		<h3>Projects List</h3>
		<?php if(count($data['projects'])>0):?>	
		<ul id="sortable" class="list">
			<li class="listHead">
				<div style="width:80%"><span>Name</span></div>
				<div style="width:10%"><span>Published</span></div>
				<div style="width:10%"><span>Sort Order</span></div>
			</li>
			<?php foreach ($data['projects'] AS $project):?>
			<li class="listRow" rel="<?php print $project["project_id"]?>">
				<div style="width:80%"><span><a href="<?php print site_url("admin/projects/edit/".$project["project_id"])?>"><?php print $project['title']?></a></span></div>
				<div style="width:10%"><span><a href="<?php print $project['project_id']?>" title="<?php print $projects['active_text']?>" class="activeStatus <?php print $project['active_class']?>" style="margin:10px 0 0 0px;"></a></span></div>
				<div style="width:10%" class="sort_order_row"><span><?php print $project['sort_order']?></span></div>
			</li>
			<?php endforeach;?>
		</ul>		
		<?php else:?><br>
		<div class="info"><p>No projects added.</p></div>	
		<?php endif;?>
		
		
	</div>
</div>
</form>