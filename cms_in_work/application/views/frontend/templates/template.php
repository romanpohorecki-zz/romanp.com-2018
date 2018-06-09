<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title><?php print $meta_title;?></title>
<meta name="description" content="<?php print $meta_description;?>"/>
<meta name="keywords" content="<?php print $meta_keywords?>"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800' rel='stylesheet' type='text/css'>
<link rel="shortcut icon" href="<?php print base_url()?>favicon.ico" type="image/x-icon"/>
<?php print $this->carabiner->display();?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js" charset="UTF-8"></script>
<script type="text/javascript">var CMSURL = "<?php print site_url()?>";<?php print $js_code?></script>
</head>
<body id="<?php print $body_class?>">	
	<?php print $content?>
</body>
</html>