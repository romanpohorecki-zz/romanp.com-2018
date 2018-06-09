<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class Images_model extends MyModel{
	
	/**
	 * Return version of image from given $entity_type
	 */
	function get_version($entity_type="", $entity_id=0, $image_id=0){
		return $this->db->get_where("images_versions", array("entity_type"=>$entity_type, "entity_id"=>$entity_id, "image_id"=>$image_id))->row_array();
	}
	
}