<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_images_display_modes(){
	$CI = get_instance();
	return $CI->config->item("images_display_modes");
}

function get_admin_logo(){
	$CI = get_instance();
	$logo = $CI->config->item("admin_logo");
	
	if(!empty($logo)){
		$CI->load->model("admin/images_model", "images");
		$image = $CI->images->get_version("admin_logo", 0, $logo);
		if(!empty($image)){
			// version image
			return base_url()."cache/images/".$image['image_src'];
		}
		return base_url().'assets/images/admin/company_logo.png';
	}
	
	return base_url().'assets/images/admin/company_logo.png';
}

function generate_random_id(){
	return md5(uniqid().rand(0,999));
}


function t($label=""){
	$CI = get_instance();
	$language = $CI->session->userdata("language");
	
	if(!empty($language) && $language=="es"){
		
		$translations = $CI->config->item("translations");
		if(!empty($translations[$label]))
			return $translations[$label];
		else
			return $label;	
	}
	else 
		return $label;
}

function get_profile_url($data=array()){
	if($data["st_staff"] == 1 || $data["st_executive"] == 1)
		return site_url("work/executives/".$data["member_id"]."/".url_title($data["first_name"]." ".$data["last_name"]));
	elseif($data["st_former_executive"] == 1)
		return site_url("about/former_executives/".$data["member_id"]."/".url_title($data["first_name"]." ".$data["last_name"]));
	else 
		return site_url("resources/directory/".$data["member_id"]."/".url_title($data["first_name"]." ".$data["last_name"]));
}

function check_error($field="", $errors){
	if(is_array($errors)){
		foreach ($errors AS $error=>$message){
			if($error == $field){
				echo "<span class='spanError'>".$message."</span>";
				return;	
			}	
		}
	}
}

function prepare_library_url($entity_type, $entity_id, $exclude_page_value=false){
	
	$CI = get_instance();
	
	$default_segments = array("category_id"=>"", "article_subject"=>"");
	$segments = $CI->uri->uri_to_assoc(3);
	
	$return_url = site_url("resources/library");
	foreach ($default_segments AS $key=>$value){ 
		if($segments[$key] || $key==$entity_type){
			if($key==$entity_type && $entity_id==0)
				continue;
			
			($key==$entity_type) ? $appendthis = $entity_id : $appendthis = $segments[$key];
			$return_url.= "/".$key."/".$appendthis;
		}
	}
	$return_url.= "/page".($exclude_page_value? "":"/1");
	return $return_url;
}

function prepare_gsd_url($entity_type, $entity_id, $exclude_page_value=false){
	
	$CI = get_instance();
	
	$default_segments = array("member_type"=>"", "product_type"=>"", "country"=>"");
	$segments = $CI->uri->uri_to_assoc(3);
	
	$return_url = site_url("resources/directory");
	foreach ($default_segments AS $key=>$value){ 
		if($segments[$key] || $key==$entity_type){
			if($key==$entity_type && $entity_id==0)
				continue;
			
			($key==$entity_type) ? $appendthis = $entity_id : $appendthis = $segments[$key];
			$return_url.= "/".$key."/".$appendthis;
		}
	}
	$return_url.= "/page".($exclude_page_value? "":"/1");
	return $return_url;
}

function get_resolution(){
	if(isset($_COOKIE["resolution"]))
		return 	$_COOKIE["resolution"];
	else 
		return 960; //assume it can load whole site for now!	
}

function link_selected($string="", $add_class=false){
	
	$CI = get_instance();
	
	$segment = $CI->uri->segment(1);
	$two_segments = $CI->uri->segment(1)."/".$CI->uri->segment(2);
	
	if($segment == $string || $two_segments == $string){
		if($add_class)
			return "class='active'";
		else 	
			return "active";
	}
}



/**
 *  Set menu link as selected 
 */
function check_selected($str='', $strict = true, $add_class = true, $return_class=false){
	
	$CI = get_instance();
	if($strict && trim($CI->uri->uri_string(), '/') == trim('admin/'.$str, '/')){
		if($return_class)
			return "selected";
		else { 
			if($add_class)
				echo "class='selected'";
			else 	
				echo "selected";
		}	
		return;	
	}
	elseif($CI->uri->segment(2)== $str && $strict==false) {
		if($return_class)
			return "selected";
		else {
			if($add_class)
				echo "class='selected'";
			else 	
				echo "selected";
		}	
		return;
	}elseif($CI->uri->segment(2)== $str) {
		if($return_class)
			return "selected";
		else {
			if($add_class)
				echo "class='selected'";
			else 	
				echo "selected";
		}		
	}
}

/**
 *  Set filter as selected 
 */
function check_filter($data='', $str=''){
	if($data==$str)
		echo "class='selected'";
}


/**
 * Format Bytes
 */
function format_bytes($bytes) {
   if ($bytes < 1024) return $bytes.' B';
   elseif ($bytes < 1048576) return round($bytes / 1024, 2).' kb';
   elseif ($bytes < 1073741824) return round($bytes / 1048576, 2).' mb';
   elseif ($bytes < 1099511627776) return round($bytes / 1073741824, 2).' gb';
   else return round($bytes / 1099511627776, 2).' tb';
}

/**
 * Function that will check if user is admin
 * false => @return false/Forbidden 
 */
function check_admin($ret_forbidden = false){
	
	$CI = get_instance();
	if($CI->session->userdata('group_type') != 'admin'){
		if($ret_forbidden)
			show_403();
		else 
			return false;	
	}
	else 	
		return true;
}

/**
 * 
 */
function show_403($message='You don\'t have required permissions to access this page.', $heading = 'Access Denied')
{
	$_error =& load_class('Exceptions', 'core');
	echo $_error->show_error($heading, $message, 'error_403', 403);
	exit;
}

/**
 * Print a preformated array
 */
function pr($array = array(), $die=true){
	echo "<pre>";
	print_r($array);
	echo "</pre>";
	if($die)
		die;
} 

/**
 * Breadcrumbs
 */
function get_breadcrumbs($array=array()){
	$return = '';	
	foreach ($array AS $name => $link){		
		$return .= '<a href="'.($link ? $link : 'javascript:void(0)').'">'.$name.'</a>';		
	}	
	return $return;
}

/**
 * Return path of assets directory 
 */
function assets_url(){
	return "assets";
}



/**
 * Print error/success messages
 */
function print_messages($data = array()){

	$return = '';

	// error message
	if(isset($data['error'])){
		$return .='<div class="error msgBox">';
		if(is_array($data['error'])){
			foreach ($data['error'] AS $error){
				$return .= "<p>".$error."</p>";
			}
		}else {
			$return .= "<p>".$data['error']."</p>";
		}
		$return .='</div>';
	}

	// warning message
	if(isset($data['warning'])){
		$return .='<div class="warning msgBox">';
		if(is_array($data['warning'])){
			foreach ($data['warning'] AS $warning){
				$return .= "<p>".$warning."</p>";
			}
		}else {
			$return .= "<p>".$data['warning']."</p>";
		}
		$return .='</div>';
	}

	// info message
	if(isset($data['info'])){
		$return .='<div class="info msgBox">';
		if(is_array($data['info'])){
			foreach ($data['info'] AS $info){
				$return .= "<p>".$info."</p>";
			}
		}else {
			$return .= "<p>".$data['info']."</p>";
		}
		$return .='</div>';
	}

	// success message
	if(isset($data['success'])){
		$return .='<div class="success msgBox">';
		if(is_array($data['success'])){
			foreach ($data['success'] AS $success){
				$return .= "<p>".$success."</p>";
			}
		}else {
			$return .= "<p>".$data['success']."</p>";
		}
		$return .='</div>';
	}

	print $return;
}


function get_info($info){
	$return .='<div class="info msgBox">';
	$return .= "<p>".$info."</p>";
	$return .='</div>';	
	return $return;
}

function secure_cms_tag($tag){
	return preg_match("^\[![a-z0-9A-Z!_-]+!\]^",$tag);		
}

function array_sort($array, $on, $order='desc'){
	$new_array = array();
    $sortable_array = array();
 
    if (count($array) > 0) {
    	foreach ($array as $k => $v) {
        	if (is_array($v)) {
            	foreach ($v as $k2 => $v2) {
                	if ($k2 == $on) {
                    	$sortable_array[$k] = $v2;
                    }
                }
            } else {
            	$sortable_array[$k] = $v;
            }
        }
 
        switch($order){
			case 'asc':
            	asort($sortable_array);
              	break;
            case 'desc':
                arsort($sortable_array);
            break;
        }
 
        foreach($sortable_array as $k => $v) {
        	$new_array[] = $array[$k];
        }
    }
    return $new_array;
}

/**
 * Generate HTML code for a stylized DD
 */
function generate_dd($field='', $options=array(), $selected='', $id='', $default_text='Select One'){

	// ID match DD name when is not provided
	if(empty($id))
		$id = $field;

	$html = '<div class="btn-group btn-input clearfix" data-hidden="'.$id.'">'.
			'<button type="button" class="btn btn-default dropdown-toggle form-control" data-toggle="dropdown">'.
			'<span data-bind="label">'.(!empty($selected) ? $options[$selected] : $default_text).'</span> <span class="caret"></span>'.
			'</button>'.
			'<input type="hidden" name="'.$field.'" value="'.(!empty($selected) ? $selected : '').'" id="'.$id.'"/>'.
			'<ul class="dropdown-menu" role="menu">';
	foreach ($options AS $key=>$value){
		$html .=    '<li data-id="'.$key.'"><a href="#">'.$value.'</a></li>';
	}
	$html .=  '</ul>'.
			'</div>';

	return $html;
}