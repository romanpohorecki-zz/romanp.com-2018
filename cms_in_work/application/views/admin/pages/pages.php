<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="breadcrumbs"><?php print $breadcrumbs?></div>
<div class="actionLinks">
	<a href="<?php print site_url("admin/pages/add");?>" class="new">New Page</a>
</div>

<div id="mainContent" class="fixedPosition">
	<div class="content" id="pagesList">
		<div id="messages"><?php print_messages($data)?></div>
		<?php if(count($data['pages'])>0):?>
		<table cellspacing="0" cellpadding="0" class="list">
		<thead>
			<tr>
				<th width="54%" <?php check_filter($data['sortby'], 'title')?>><a href="<?php print site_url("admin/pages/list_pages/1/title/".$data['nextsortdir'])?>">Page Title</a></th>
				<th width="5%" <?php check_filter($data['sortby'], 'published')?>><a href="<?php print site_url("admin/pages/list_pages/1/published/".$data['nextsortdir'])?>">Published</a></th>
				<th width="20%" <?php check_filter($data['sortby'], 'created')?>><a href="<?php print site_url("admin/pages/list_pages/1/created/".$data['nextsortdir'])?>">Created</a></th>
				<th width="20%" <?php check_filter($data['sortby'], 'last_update')?>><a href="<?php print site_url("admin/pages/list_pages/1/last_update/".$data['nextsortdir'])?>">Modified</a></th>
			</tr>
		</thead>	
		<?php foreach ($data['pages'] AS $page):?>
		<tr>
			<td><a href="<?php print $page['edit_link']?>"><?php print $page['title']?></a></td>
			<td align="center"><a href="<?php print $page['page_id']?>" title="<?php print $page['active_text']?>" class="activeStatus <?php print $page['active_class']?>">&nbsp;</a></td>
			<td><?php print date("m/d/Y G:i:s", $page['created'])?></td>
			<td><?php print date("m/d/Y G:i:s", $page['last_update'])?></td>
		</tr>
		<?php endforeach;?>
		</table>
		<div class="pagination" style="display:none">
			<div class="paginationInfo">Page <?php print $data['cur_page']?> of <?php print $data['total_pages'];?> (<?php print count($data['pages'])?> Records)</div>
			<div class="paginationLinks"><?php print $data['pagination'];?></div>
			<div class="paginationLimit"><span>Display #</span><div class="ddContainer"><?php print form_dropdown('list_limit', $data['list_limit_array'], $data['per_page'], 'id="listLimit" class="formSelectSmallest"');?></div></div>
		</div>		
		<?php else:?>
		<div class="info"><p>No pages added.</p></div>	
		<?php endif;?>
	</div>
</div>