<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="breadcrumbs"><?php print $breadcrumbs?></div>
<div class="actionLinks">
	<a href="#" class="save">Save</a>
	<?php if(!empty($data['user_id'])):?><a href="<?php print site_url('admin/users/delete/'.$data['user_id'])?>" class="delete" data-message="Are you sure you want to delete this user?">Delete</a><?php endif;?>
</div>

<div id="mainContent">
	<div class="content">
		<div id="messages"><?php print_messages($data)?></div>
		<div class="formContent">	
			<form role="form" action="<?php print site_url("admin/users/".$data['action'])?>" method="post" class="form" id="userForm">
				<input type="hidden" id="user_id" name="user_id" value="<?php print $data['user_id']?>">
				<input type="hidden" name="save" value="1">
				<div class="checkbox">
					<label><input type="checkbox" id="active" value="1" name="active"  <?php $data['active']>0? print "CHECKED":""?>> Active</label>
				</div>
				<div class="form-group">
					<label for="firstname">First Name <b>*</b></label>
					<input type="text" class="form-control" id="firstname" name="firstname" value="<?php print $data['firstname']?>">
				</div>
				<div class="form-group">
					<label for="lastname">Last Name <b>*</b></label>
					<input type="text" class="form-control" id="lastname" name="lastname" value="<?php print $data['lastname']?>">
				</div>
				<div class="form-group">
					<label for="email">Email address<b>*</b></label>
					<input type="text" class="form-control" id="email" name="email" value="<?php print $data['email']?>">
				</div>
				<div class="form-group">
					<label for="password">Password <b>*</b></label>
					<input type="password" class="form-control" id="password" name="password" value="<?php print $data['password']?>">
				</div>
				<div class="form-group">
					<label for="password2">Confirm Password <b>*</b></label>
					<input type="password" class="form-control" id="password2" name="password2" value="<?php print $data['password2']?>">
				</div>
				<input type="hidden" name="group_id" value="1">
			</form>	
		</div>
	</div>
</div>	