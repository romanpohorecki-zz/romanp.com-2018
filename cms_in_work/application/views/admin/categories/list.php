<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="breadcrumbs"><?php print $breadcrumbs?></div>
<div class="actionLinks">
	<a href="<?php print site_url("admin/categories/add");?>" class="new">New Category</a>
</div>

<div id="mainContent">
	<div class="content" id="categoriesList">
		<div id="messages"><?php print_messages($data)?></div>
						
						
		<?php if(count($data['categories'])>0):?>	
		<ul id="sortable" class="list">
			<li class="listHead">
				<div style="width:80%"><span>Name</span></div>
				<div style="width:10%"><span>Published</span></div>
				<div style="width:10%"><span>Sort Order</span></div>
			</li>
			<?php foreach ($data['categories'] AS $category):?>
			<li class="listRow" rel="<?php print $category["category_id"]?>">
				<div style="width:80%"><span><a href="<?php print site_url("admin/categories/edit/".$category["category_id"])?>"><?php print $category['title']?></a></span></div>
				<div style="width:10%"><span><a href="<?php print $category['category_id']?>" title="<?php print $categories['active_text']?>" class="activeStatus <?php print $category['active_class']?>" style="margin:10px 0 0 0px;"></a></span></div>
				<div style="width:10%" class="sort_order_row"><span><?php print $category['sort_order']?></span></div>
			</li>
			<?php endforeach;?>
		</ul>		
		<?php else:?>
		<div class="info"><p>No categories added.</p></div>	
		<?php endif;?>
	</div>
</div>	