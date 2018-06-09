<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
	
<div id="breadcrumbs"><?php print $breadcrumbs?></div>
<div class="actionLinks">
	<a href="<?php print site_url("admin/users/add");?>" class="new">New User</a>
</div>

<div id="mainContent" class="fixedPosition">
	<div class="content" id="usersList">
		<div id="messages"><?php print_messages($data)?></div>
		<?php if(count($data['users'])>0):?>
		<table cellspacing="0" cellpadding="0" class="list">
		<thead>
			<tr>			
				<th width="60%" <?php check_filter($data['sortby'], 'firstname')?>><a href="<?php print site_url("admin/users/list_users/1/firstname/".$data['nextsortdir'])?>">Name</a></th>
				<th width="35%" <?php check_filter($data['sortby'], 'email')?>><a href="<?php print site_url("admin/users/list_users/1/email/".$data['nextsortdir'])?>">Email</a></th>
				<th width="5%" <?php check_filter($data['sortby'], 'active')?>><a href="<?php print site_url("admin/users/list_users/1/active/".$data['nextsortdir'])?>">Active</a></th>
			</tr>
		</thead>	
		<?php foreach ($data['users'] AS $user):?>
		<tr>
			<td><a href="<?php print $user['edit_link']?>"><?php print $user['name']?></a></td>
			<td><a href="mailto:<?php print $user['email'];?>"><?php print $user['email']?></a></td>
			<td align="center"><a href="<?php print $user['user_id']?>" title="<?php print $user['active_text']?>" class="activeStatus <?php print $user['active_class']?>">&nbsp;</a></td>
		</tr>
		<?php endforeach;?>
		</table>
		<?php else:?>
		<div class="info"><p>No users added.</p></div>	
		<?php endif;?>
	</div>
</div>