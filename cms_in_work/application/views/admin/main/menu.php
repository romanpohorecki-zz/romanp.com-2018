<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<ul id="adminMenu">
	<li><strong>PAGES</strong></li>
	<li><a href="<?php print site_url("admin/home")?>" class="<?php check_selected('home',true, false);?>">Home</a></li>
	<li><a href="<?php print site_url("admin/categories")?>" class="<?php check_selected('projects',true, false); check_selected('categories',true, false)?>">Projects</a></li>
	<li><a href="<?php print site_url("admin/contact")?>" class="<?php check_selected('contact',true, false);?>">Contact</a></li>
	
	<li class="addTopSpace"><strong>LIBRARY</strong></li>
	<li><a href="<?php print site_url("admin/images/collections")?>" <?php check_selected("images", false)?>>Images</a></li>
	<li><a href="<?php print site_url("admin/videos/collections")?>" <?php check_selected("videos", false)?>>Videos</a></li>
	
	<li class="addTopSpace"><strong>DASHBOARD</strong></li>	
	<li><a href="<?php print site_url("admin/settings")?>" <?php check_selected("settings", false)?>>Site Settings</a></li>
	<li><a href="<?php print site_url("admin/pages/list_pages")?>" <?php check_selected("pages/list_pages")?> style="display:none">Main Pages</a></li>
	<li><a href="<?php print site_url("admin/crops")?>" <?php check_selected("crops")?>>Image Crops</a></li>
	<li><a href="<?php print site_url("admin/users")?>" <?php check_selected("users")?>>Users</a></li>
</ul>