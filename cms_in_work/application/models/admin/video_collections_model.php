<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class Video_collections_model extends MyModel{
	
	/**
	 * Add new Collection
	 */
	function add($params){
		if(!$this->add_validate($params))
			return false;
			
		// create his folder
		$dirname = url_title(trim($params['collection_name']), 'underscore', TRUE);
		
		if(is_dir("uploads/videos/".$dirname)){
			$dirname = $dirname."_".time();
		}		
		
		mkdir("uploads/videos/".$dirname, 0755);	
			
		// insert into collections	
		$data = array(
		   'title' 		=> $params['collection_name'],
		   'folder'	 	=> $dirname,
		   'created'	=> time()
		);
		
		$this->db->insert('video_collections', $data);
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
		
		$this->db->where('collection_id', $params['collection_id'])->update('video_collections', $data);
		
		$this->session->set_flashdata("success", "Collection Name successfully updated.");
		redirect("admin/videos/collection/".$params['collection_id']);		
	}
	
	function edit_validate(&$params){
		if(!$params['collection_id']){
			$this->add_error("Invalid ID.");
			return false;	
		}else {
			$result = $this->db->get_where('video_collections', array('collection_id'=>(int) $params['collection_id']));
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
		$result = $this->db->get_where('video_collections', array('collection_id'=>(int) $collection_id));
		return $result->row_array(); 
	}
	
	/**
	 * Get Collections
	 */
	function get_collections($params=array()){
		
		$result = $this->db->get('video_collections');
		$collections = $result->result_array();
		// count number of videos from each collection
		foreach ($collections AS $key => $row){
			$total_videos = $this->db->where('collection_id',$row['collection_id'])->count_all_results('videos');
			$collections[$key]['total_videos'] = $total_videos;	
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

		$result = $this->db->get_where('video_collections', array('collection_id'=>(int) $collection_id));
		if($result->num_rows()!=1){
			$this->session->set_flashdata("error", "Invalid Id.");
			redirect("admin/videos/collections");
		}	
		else			
			$collection = $result->row_array();
		
		// mark videos as deleted in database 
		$videos_res = $this->db->get_where('videos', array('collection_id'=>(int) $collection_id));
		if($videos_res->num_rows() > 0){
			$videos = $videos_res->result_array();
			foreach ($videos AS $video){
				$video_file = "uploads/videos/".$video['folder']."/".$video['filename'];
				@unlink($video_file);
				$this->db->where('video_id',(int) $video['video_id'])->delete('videos');
			}	
		}
		
		rmdir("uploads/videos/".$collection['folder']);
		
		// delete from collections
		$this->db->where('collection_id',(int) $collection_id)->delete('video_collections');
		
		return true;
	}
}