<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class Collections_model extends MyModel{
	
	/**
	 * Add new Collection
	 */
	function add($params){
		if(!$this->add_validate($params))
			return false;
			
		// create his folder
		$dirname = url_title(trim($params['collection_name']), 'underscore', TRUE);
		
		if(is_dir("uploads/images/originals/".$dirname)){
			$dirname = $dirname."_".time();
		}		
		
		mkdir("uploads/images/originals/".$dirname, 0755);	
		mkdir("uploads/images/thumbs/".$dirname, 0755);	
		
			
		// insert into collections	
		$data = array(
		   'title' 		=> $params['collection_name'],
		   'folder'	 	=> $dirname,
		   'created'	=> time()
		);
		
		$this->db->insert('collections', $data);
		$collection_id = $this->db->insert_id(); 
			
		if($collection_id){			
			return true;
		}	
		else
			 $this->add_error("Error in saving.");
		return false;		
	}
	
	/**
	 * Add Collection validate 
	 */
	function add_validate(&$params){
		if(!$params['collection_name'])
			$this->add_error("Please enter Collection Name.");
			
		return $this->validate();
	}
	
	/**
	 * Edit Collection
	 */
	function edit($params){
		if(!$this->edit_validate($params))
			return false;
			
		// update collections	
		$data = array(
		   'title' 		=> $params['collection_name']
		);
		
		$this->db->where('collection_id', $params['collection_id'])->update('collections', $data);
		
		$this->session->set_flashdata("success", "Collection Name successfully updated.");
		redirect("admin/images/collection/".$params['collection_id']);		
	}
	
	function edit_validate(&$params){
		if(!$params['collection_id']){
			$this->add_error("Invalid ID.");
			return false;	
		}else {
			$result = $this->db->get_where('collections', array('collection_id'=>(int) $params['collection_id']));
			if($result->num_rows() !=1){
				$this->add_error("Invalid ID.");
				return false;	
			}
		}
		
		if(!$params['collection_name'])
			$this->add_error("Please enter Collection Name.");
					
		return $this->validate();
	}
	
	
	/**
	 * Get Collection
	 */
	function get_collection($collection_id=0){
		$result = $this->db->get_where('collections', array('collection_id'=>(int) $collection_id));
		return $result->row_array(); 
	}
	
	/**
	 * Get Collections
	 */
	function get_collections($params=array()){
		
		$result = $this->db->get('collections');
		$collections = $result->result_array();
		// count number of images from each collection
		foreach ($collections AS $key => $row){
			$total_images = $this->db->where('collection_id',$row['collection_id'])->count_all_results('images');
			$collections[$key]['total_images'] = $total_images;	
		}
		
		if(empty($params['sortby']))
			$params['sortby'] = 'title';
		
		if(empty($params['sortdir']))
			$params['sortdir'] = 'asc';
		
		$collections = array_sort($collections, $params['sortby'], $params['sortdir']);		
		return $collections; 
	}
	
	/**
	 * Delete Collections
	 */
	function delete($collection_id){

		$result = $this->db->get_where('collections', array('collection_id'=>(int) $collection_id));
		if($result->num_rows()!=1){
			$this->session->set_flashdata("error", "Invalid Id.");
			redirect("admin/images/collections");
		}	
		else			
			$collection = $result->row_array();
		
		// mark images as deleted in database 
		$images_res = $this->db->get_where('images', array('collection_id'=>(int) $collection_id));
		if($images_res->num_rows() > 0){
			$images = $images_res->result_array();
			foreach ($images AS $image){
				$image_file = "uploads/images/originals/".$image['folder']."/".$image['image_src'];
				$thumb_file = "uploads/images/thumbs/".$image['folder']."/".$image['image_src'];
				@unlink($image_file);
				@unlink($thumb_file);
				$this->db->where('image_id',(int) $image['image_id'])->delete('images');
			}	
		}
		
		rmdir("uploads/images/originals/".$collection['folder']);	
		rmdir("uploads/images/thumbs/".$collection['folder']);
		
		// delete from collections
		$this->db->where('collection_id',(int) $collection_id)->delete('collections');
		
		return true;
	}
}