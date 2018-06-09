<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="breadcrumbs"><?php print $breadcrumbs?></div>
<div class="actionLinks">
	<a href="<?php print site_url("admin/projects/add");?>" class="new">New Project</a>
</div>

<div id="mainContent">
	<div class="content" id="projectsList">
		<div id="messages"><?php print_messages($data)?></div>
						
						
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
		<?php else:?>
		<div class="info"><p>No projects added.</p></div>	
		<?php endif;?>
	</div>
</div>	