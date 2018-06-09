<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// default template 
$template['active_template'] = 'home';

// FRONTEND template & regions
$template['home']['template'] = 'frontend/templates/template.php'; 
$template['home']['regions'] = array(
   	'js_code',
   	'content',
   	'body_class',
	'meta_title',
   	'meta_description',
   	'meta_keywords'
);
$template['home']['parser'] = 'parser';
$template['home']['parser_method'] = 'parse';
$template['home']['parse_template'] = FALSE;



// BACKEND template & regions
$template['admin']['template'] = 'admin/templates/template.php'; 
$template['admin']['regions'] = array(  
	'meta_title',
   	'meta_description',
   	'meta_keywords',
	'page_class',
   	'content',
	'breadcrumbs',
	'menu',
	'js'
);
$template['admin']['parser'] = 'parser';
$template['admin']['parser_method'] = 'parse';
$template['admin']['parse_template'] = FALSE;


// BACKEND clean template
$template['admin_clean']['template'] = 'admin/templates/template_clean.php'; 
$template['admin_clean']['regions'] = array(  
   	'meta_title',
   	'meta_description',
   	'meta_keywords',
   	'content',
	'js',
	'body_class'
);
$template['admin_clean']['parser'] = 'parser';
$template['admin_clean']['parser_method'] = 'parse';
$template['admin_clean']['parse_template'] = FALSE;


/* End of file template.php */
/* Location: ./system/application/config/template.php */