<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class File_collections_model extends MyModel{
	
	/**
	 * Add new Collection
	 */
	function add($params){
		if(!$this->add_validate($params))
			return false;
			
		// create his folder
		$dirname = url_title(trim($params['collection_name']), 'underscore', TRUE);
		
		if(is_dir("uploads/files/".$dirname)){
			$dirname = $dirname."_".time();
		}		
		
		mkdir("uploads/files/".$dirname, 0755);	
			
		// insert into file collections	
		$data = array(
		   'title' 		=> $params['collection_name'],
		   'folder'	 	=> $dirname,
		   'created'	=> time()
		);
		
		$this->db->insert('file_collections', $data);
		$collection_id = $this->db->insert_id(); 
			
		if($collection_id){
			$this->session->set_flashdata("success", "Collection successfully created.");
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
			
		// update file collections	
		$data = array(
		   'title' 		=> $params['collection_name']
		);
		
		$this->db->where('collection_id', $params['collection_id'])->update('file_collections', $data);
		
		$this->session->set_flashdata("success", "Collection successfully edited.");
		redirect("admin/files/collection/".$params['collection_id']);		
	}
	
	function edit_validate(&$params){
		if(!$params['collection_id']){
			$this->add_error("Invalid ID.");
			return false;	
		}else {
			$result = $this->db->get_where('file_collections', array('collection_id'=>(int) $params['collection_id']));
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
		$result = $this->db->get_where('file_collections', array('collection_id'=>(int) $collection_id));
		return $result->row_array(); 
	}
	
	/**
	 * Get file Collections
	 */
	function get_collections($params=array()){
		
		if(!$params['sortby'])
			$params['sortby'] = 'title';
		if(!$params['sortdir'])
			$params['sortdir'] = 'asc';	
		
		$result = $this->db->get('file_collections');
		$collections = $result->result_array();
		// count number of files from each collection
		foreach ($collections AS $key => $row){
			$total_files = $this->db->where('collection_id',$row['collection_id'])->count_all_results('files');
			$collections[$key]['total_files'] = $total_files;	
		}

		$collections = array_sort($collections, $params['sortby'], $params['sortdir']);
		
		return $collections; 
	}
	
	/**
	 * Delete file Collections
	 */
	function delete($collection_id){

		$result = $this->db->get_where('file_collections', array('collection_id'=>(int) $collection_id));
		if($result->num_rows()!=1){
			$this->session->set_flashdata("error", "Invalid Id.");
			redirect("admin/files/collections");
		}	
		else			
			$collection = $result->row_array();
		
		// mark files as deleted in database 
		$files_res = $this->db->get_where('files', array('collection_id'=>(int) $collection_id));
		if($files_res->num_rows() > 0){
			$files = $files_res->result_array();
			foreach ($files AS $file){
				$file_file = "uploads/files/".$file['folder']."/".$file['file_src'];
				@unlink($file_file);
				$this->db->where('file_id',(int) $file['file_id'])->delete('files');
			}	
		}
		
		rmdir("uploads/files/".$collection['folder']);
		
		// delete from file collections
		$this->db->where('collection_id',(int) $collection_id)->delete('file_collections');
		
		$this->session->set_flashdata("success", "Collection successfully deleted.");
	}
}