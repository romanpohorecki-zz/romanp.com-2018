<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php print base_url();?>"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title><?php print $meta_title;?></title>
<meta name="description" content="<?php print $meta_description;?>"/>
<meta name="keywords" content="<?php print $meta_keywords?>"/>
<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,400italic,700italic' rel='stylesheet' type='text/css'>
<link rel="shortcut icon" href="<?php print base_url()?>favicon.ico" type="image/x-icon"/>
<?php print $this->carabiner->display();?>
<script type="text/javascript">
<?php print $js?>
Shadowbox.init({
    handleOversize: "drag",
    modal: true
});
</script>	
</head>
<body>
<div id="modal"></div>

<div id="fixedHeader">
	<div class="cmsLogo">
		<a href="<?php print site_url('admin')?>"><img src="<?php print base_url().'assets/images/admin/logo-cms.png'?>" alt=""/></a>	
	</div>
	<div class="logInfo">
		<span><?php print $this->session->userdata('email')?></span>
		<a href="<?php print site_url("admin/logout")?>">logout</a>
	</div>
</div>

<div id="container" class="clearfix">
	<div id="leftContainer">
		<div class="companyLogo">
			<img src="<?php print get_admin_logo();?>" alt=""/>	
		</div>			
		<?php print $menu?>		
	</div>
	<div id="rightContainer">
		<?php print $content;?>
	</div>	
</div>
</body>
</html>