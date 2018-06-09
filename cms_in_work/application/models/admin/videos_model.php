<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class Videos_model extends MyModel{
	
	/**
	 * Add new Video
	 */
	function add($params){
		if(!$this->add_validate($params))
			return false;
			
		// insert into videos	
		$data = array(
		   	'title' 		=> $params['title'],
		   	'collection_id'	=> $params['collection_id'],
		   	'filename'		=> $params['filename'],
		   	'filetype'		=> $params['filetype'],
		   	'folder'		=> $params['folder'],
			'created'		=> time()
		);
		
		$this->db->insert('videos', $data);
		$video_id = $this->db->insert_id(); 
		
		return true;		
	}
	
	/**
	 * Add Video validate 
	 */
	function add_validate(&$params){
		
		if(!$params['collection_id'])
			$this->add_error("Please Select Collection.");
		else {
			$result = $this->db->get_where('video_collections', array('collection_id'=>(int) $params['collection_id']));
			if($result->num_rows() !=1){
				$this->add_error("Invalid Collection.");
				return false;	
			}
			else {
				$result = $result->row_array();
				$params['folder'] = $result['folder'];					
			}	
		}		
			
		return $this->validate();
	}
	
	
	/**
	 * Get Video
	 */
	function get_video($video_id=0){
		$result = $this->db->get_where('videos', array('video_id'=>(int) $video_id));
		return $result->row_array(); 
	}
	
	/**
	 * Count videos
	 */
	function count_videos($params=array()){
		
		if($params['collection_id'])
			$this->db->where('collection_id', (int) $params['collection_id']);
		
		$this->db->from('videos');
		return $this->db->count_all_results(); 	
	}
	
	/**
	 * Get pagination =>  return pagination
	 */
	function get_pagination(){		
		return $this->pagination;		
	}
	
	/**
	 * Get videos
	 */
	function get_videos($params=array()){
				
		$offset = (int) ($params['cur_page']-1) * $params['per_page'];
		$this->db->flush_cache();
		
		// fix CI bug
		$this->db->_reset_write();
			
		if($params['collection_id'])
			$this->db->where('collection_id', (int) $params['collection_id']);
			
		$result = $this->db->select('videos.*')->get('videos', $params['per_page'], $offset);
							
		$videos = $result->result_array();
		foreach($videos AS $key=>$video){
			// rewrite videos src
			$videos[$key]['video_src'] = base_url()."uploads/videos/".$video['folder']."/".$video['filename'];
		}
		
		// Pagination
		$this->load->library('pagination');
		$this->pagination->initialize($params); //pr($params); 
		$this->pagination = $this->pagination->create_links();		
		
		return $videos;
	}	
	
	/**
	 * Delete Video
	 */
	function delete($params){		
		$result = $this->db->get_where('videos', array('video_id'=>(int) $params['video_id']));
		if($result->num_rows()>0){
			$result = $result->row_array();
			$file = "uploads/videos/".$result['folder']."/".$result['filename'];
			@unlink($file);
			$this->db->where('video_id',(int) $params['video_id'])->delete('videos');
			return true;
		}
		return false;
	}
}