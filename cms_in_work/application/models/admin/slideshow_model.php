<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class Slideshow_model extends MyModel{
	
	
	/**
	 * Save slideshow
	 */
	function save(&$params){
		// delete all previous
		$this->db->where('slideshow_id >', '0')->delete('slideshow');
		if(count($params['images']['slideshow'])){
			foreach ($params['images']['slideshow'] AS $slide){
				$this->db->insert('slideshow', array('image_id'=>$slide, 'sort_order'=>$params['sort_order']['slideshow'][$slide]));
			}
		}
		return true;
	}
	
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
						$sizes = getimagesize($file);
						$version_image['pixels'] = $sizes[0]."x".$sizes[1];
			
						$sizebytes = filesize($file);
						$version_image['kilobytes'] = format_bytes($sizebytes);
			
						$version_image['image_src'] = base_url().$file;
					}
						
					$row["image"] = $version_image;
				}
			}
						
			
			$return[$row['image_id']] = $row;
		}
		
		return $return;		
	}	
}