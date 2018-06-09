<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php print base_url();?>"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title><?php print $meta_title;?></title>
<meta name="description" content="<?php print $meta_description;?>"/>
<meta name="keywords" content="<?php print $meta_keywords?>"/>
<link href='http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
<?php print $this->carabiner->display();?>
<script type="text/javascript">
	<?php print $js?>
</script>	
</head> 
<body class="<?php print $body_class?>">
<?php print $content;?>
</body>
</html>