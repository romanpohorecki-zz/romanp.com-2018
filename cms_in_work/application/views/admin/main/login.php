<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="loginBox">
	<div class="loginBoxInner">
		<div class="companyLogo">
			<img src="<?php print get_admin_logo();?>" alt=""/>	
		</div>			
		<form action="<?php site_url("admin/login")?>" method="post" class="form">		
			<div class="form-group">
		        <input type="text" name="email" class="form-control" id="inputEmail" placeholder="E-mail">
		    </div>
			<div class="form-group">
		        <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Password">
		    </div>
		    <button type="submit" name="login" class="btn btnLogin">LOGIN</button>
		</form>
		
		<div class="loginErrors">
			<?php if(count($data['error'])):?>
				<?php foreach ($data['error'] AS $error):?>
				<p><?php print $error?></p>		
				<?php endforeach;?>
			<?php endif;?>
		</div>
		<div class="cmsLogo">
			<img src="<?php print base_url().'assets/images/admin/logo-cms.png'?>" alt=""/>	
		</div>
	</div>
</div>