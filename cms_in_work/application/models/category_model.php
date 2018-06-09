<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class Category_model extends MyModel{
		
	/**
	 * Get category
	 */
	function get_category($category_id=0){
	
		$this->load->model("admin/images_model", "images");
		$result = $this->db->get_where('categories', array('category_id'=>(int) $category_id, 'published'=>1));
		$return = array();
		if($result->num_rows()>0){
			$return = $result->row_array();			
			return $return;
		}
		else
			return array();
	}
		
	/**
	 * Get categories
	 */
	function get_categories(){
		$result = $this->db->order_by('sort_order', 'ASC')->where('published', 1)->get('categories');			
		$categories = $result->result_array();
		return $categories;
	}
}