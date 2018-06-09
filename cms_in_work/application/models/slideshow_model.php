<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class Slideshow_model extends MyModel{
	
	/**
	 * Get data
	 */
	public function get_data(){
		
		$result = $this->db->order_by('sort_order', 'ASC')->get('slideshow');
		$result = $result->result_array();		
		$return = array();
		foreach($result AS $row){			
			if($row["image_id"]){
				$this->load->model("admin/images_model", "images");
				$version_image = $this->images->get_version("homepage_slideshow", 0, $row["image_id"]);
				if(!empty($version_image)){
					// get image size / pixels from version image
					$file = "cache/images/".$version_image['image_src'];
					if(is_file($file)){
						$version_image['image_src'] = $file;
					}	
					$row["image"] = $version_image;
				}
			}
			$return[]= $row;
		}		
		return $return;		
	}	
}