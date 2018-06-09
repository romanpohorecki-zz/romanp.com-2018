<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class Category_model extends MyModel{
	
	/**
	 * Add category
	 */
	function add(&$params){
		if(!$this->add_validate($params))
			return false;
			
		// insert into categories
		$data = array(
			'title' 		=> $params['title'],
			'sort_order' 	=> (int)$params['sort_order_category'],
			'published' 	=> 1,
		);
	
		$this->db->insert('categories', $data);
		$category_id = $this->db->insert_id();
		
		return $category_id ? $category_id : false;
	}
	
	/**
	 * Add category validate
	 */
	function add_validate(&$params){
		if(!$params['title'])
			$this->add_error("Please enter Title.");
			
		return $this->validate();
	}
	
	/**
	 * Edit category
	 */
	function edit(&$params){
		
		if(!$this->edit_validate($params))
			return false;
	
		$data = array(
			'title' 		=> $params['title'],
			'sort_order' 	=> (int)$params['sort_order_category']
		);
		
		$this->db->where('category_id', $params['category_id'])->update('categories', $data);
		
		return true;
	}
	
	/**
	 * Edit categories validate
	 */
	function edit_validate(&$params){
		
		if(!$params['category_id']){
			$this->add_error("Invalid ID.");
			return false;
		}else {
			$result = $this->db->get_where('categories', array('category_id'=>(int) $params['category_id']));
			if($result->num_rows() !=1){
				$this->add_error("Invalid ID.");
				return false;
			}
		}
	
		if(!$params['title'])
			$this->add_error("Please enter Title.");
			
		return $this->validate();
	}
	
	
	/**
	 * Get category detail
	 */
	function get_category($category_id=0){
	
		$this->load->model("admin/images_model", "images");
		$result = $this->db->get_where('categories', array('category_id'=>(int) $category_id));
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
	function get_categories($params=array()){
		$this->load->model("admin/images_model", "images");
	
		$offset = (int) ($params['cur_page']-1) * $params['per_page'];
		
		$result = $this->db->select('categories.*')							
							->order_by($params['sortby'], $params['sortdir'])
							->get('categories', $params['per_page'], $offset);
							
		$categories = $result->result_array();
		foreach($categories AS $key=>$category){
			if($category['published']){
				$categories[$key]['active_class'] = "active";
				$categories[$key]['active_text'] = "Click to unpublish";
			}else {
				$categories[$key]['active_class'] = "inactive";
				$categories[$key]['active_text'] = "Click to publish";
			}
		}
		
		// Pagination
		$this->load->library('pagination');
		$this->pagination->initialize($params);
		$this->pagination = $this->pagination->create_links();		
		
		return $categories;
	}
	

	/**
	 * Get pagination =>  return pagination
	 */
	function get_pagination(){
		return $this->pagination;
	}
	
	/**
	 * Count all categories
	 */
	function count_categories(){
		return $this->db->count_all('categories');
	}
	
	/**
	 * Delete categories
	 */
	function delete($category_id){
	
		// delete from pages
		$this->db->where('category_id',(int) $category_id)->delete('categories');
	
		return true;
	}
	
	/**
	 * Publish/Unpublish Pages
	 */
	function publish($category_id, $new_status){
		if($new_status=="active"){
			$status = 1;
			$msg_status = 'published';
		}else {
			$status = 0;
			$msg_status = 'unpublished';
		}
		$this->db->set('published', $status)->where('category_id', (int) $category_id)->update('categories');
		return array('success'=>'Category was '.$msg_status);
	}
	
	/**
	 * Function used to save categories order
	 */
	function order_categories($params){	
		foreach ($params['positions'] AS $index=>$id){
			$data = array(
				'sort_order' => $index+1
			);
			$this->db->where('category_id', $id);
			$this->db->update('categories', $data);
		}
	}
}