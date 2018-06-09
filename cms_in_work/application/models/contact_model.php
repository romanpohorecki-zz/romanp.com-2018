<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class Contact_model extends MyModel{
	
	/**
	 * Get data
	 */
	public function get_data(){
		
		return array();
		
		$result = $this->db->get('contact');
		$result = $result->result_array();
		$return = array();
		foreach($result AS $row){
			$return[$row['name']] = $row['value'];
		}
		
		// banner image
		if($return["logo"]){
			$this->load->model("admin/images_model", "images");
			$version_image = $this->images->get_version("contact_logo", 0, $return["logo"]);
			if(!empty($version_image)){
		
				// get image size / pixels from version image
				$file = "cache/images/".$version_image['image_src'];
				if(is_file($file)){	
					$version_image['image_src'] = $file;
				}
					
				$return["logo_image"] = $version_image;
			}
		}
		
		return $return;		
	}	
}