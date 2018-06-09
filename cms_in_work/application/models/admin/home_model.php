<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class Home_model extends MyModel{
	
	/**
	 * Get data
	 */
	public function get_data(){
		
		$result = $this->db->get('home');
		$result = $result->result_array();
		$return = array();
		foreach($result AS $row){
			$return[$row['name']] = $row['value'];
		}
		
		// banner image
		if($return["mobile_header"]){
			$this->load->model("admin/images_model", "images");
			$version_image = $this->images->get_version("homepage_home", 0, $return["mobile_header"]);
			if(!empty($version_image)){
		
				// get image size / pixels from version image
				$file = "cache/images/".$version_image['image_src'];
				if(is_file($file)){
					$sizes = getimagesize($file);
					$version_image['pixels'] = $sizes[0]."x".$sizes[1];
						
					$sizebytes = filesize($file);
					$version_image['kilobytes'] = format_bytes($sizebytes);
		
					$version_image['image_src'] = base_url().$file;
				}
					
				$return["mobile_header_image"] = $version_image;
			}
		}
		
		return $return;		
	}
	
	
	/**
	 * Home
	 */
	function save(&$params){
			
		$allowed = array('headline', 'about', 'mobile_header');
		
		foreach ($params AS $name=>$value){
			if(in_array($name, $allowed)){
				$this->db->update('home', array('value'=>$value), array('name'=>$name));	
			}			
		}
				
		return true;
	}
}