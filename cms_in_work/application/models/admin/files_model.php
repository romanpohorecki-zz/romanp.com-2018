<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class files_model extends MyModel{
	
	/**
	 * Add new file
	 */
	function add($params){
		if(!$this->add_validate($params))
			return false;
			
		// insert into collections	
		$data = array(
		   	'title' 		=> $params['title'],
		   	'collection_id'	=> $params['collection_id'],
		   	'folder'		=> $params['folder'],
			'created'		=> time()
		);
		
		$this->db->insert('files', $data);
		$file_id = $this->db->insert_id(); 
			
		if($file_id){
			
			ini_set("max_execution_time", 0);
			ini_set("memory_limit", '256M');
			
			// get extension
			$ext = substr($params['file_src'], strrpos($params['file_src'], '.')); 
			$file_name = substr($params['file_src'], 0, strrpos($params['file_src'], '.'));
			$file_name = url_title(trim($file_name), 'underscore', TRUE);
			
			$img_name = $file_name."_".$file_id.$ext;
			$file_name = $img_name; // save his name in database
			$img_name = $params['folder']."/".$img_name; // add file to his directory
			
			$file = "uploads/files/tmp/".$params['file_src'];
			$new_file = "uploads/files/".$img_name;
			
			@copy($file, $new_file); // create regular copy
			@unlink($file); // unlink uploaded file (tmp)
			
			// set permissions
			@chmod($new_file, 0755); 
			
			$this->db->where('file_id', $file_id)->update('files', array('file_src'=>$file_name)); // save his name to database
			
			if($params['redirect'] !== false){
				$this->session->set_flashdata("success", "File successfully uploaded.");
				redirect("admin/files/file/".$file_id."/".$params['collection_id']);	
			}	
		}	
		else
			 $this->add_error("Error in saving.");
		return false;		
	}
	
	/**
	 * Add file validate 
	 */
	function add_validate(&$params){
		
		if(!$params['title'])
			$this->add_error("Please enter Title.");
		if(!$params['collection_id'])
			$this->add_error("Please Select Collection.");
		else {
			$result = $this->db->get_where('file_collections', array('collection_id'=>(int) $params['collection_id']));
			if($result->num_rows() !=1){
				$this->add_error("Invalid Collection.");
				return false;	
			}
			else {
				$result = $result->row_array();
				$params['folder'] = $result['folder'];					
			}	
		}		
		if(!$params['file_src'])
			$this->add_error("Please upload a file.");	
		return $this->validate();
	}
	
	/**
	 * Edit file
	 */
	function edit($params){
		if(!$this->edit_validate($params))
			return false;
			
		// update collections	
		$data = array('title' 			=> $params['title']);
		
		$this->db->where('file_id', $params['file_id'])->update('files', $data);
		
		// file move / rename
		if(strlen($params['file_src'])>0){
			
			ini_set("max_execution_time", 0);
			ini_set("memory_limit", '256M');
			
			// get extension
			$ext = substr($params['file_src'], strrpos($params['file_src'], '.')); 
			$file_name = substr($params['file_src'], 0, strrpos($params['file_src'], '.'));
			$file_name = url_title(trim($file_name), 'underscore', TRUE);
			
			$img_name = $file_name."_".$params['file_id'].$ext;
			$file_name = $img_name; // save his name in database
			$img_name = $params['folder']."/".$img_name; // add file to his directory
						
			$file = "uploads/files/tmp/".$params['file_src'];
			$new_file = "uploads/files/".$img_name;
			
			@copy($file, $new_file);
			@unlink($file); // remove tmp file 
			
			// set permissions
			@chmod($new_file, 0755);
			
			$this->db->where('file_id', $params['file_id'])->update('files', array('file_src'=>$file_name));
		}
		
		$this->session->set_flashdata("success", "File successfully edited.");
		redirect("admin/files/file/".$params['file_id']."/".$params['collection_id']);	
	}
	
	/**
	 * Edit file validate 
	 */
	function edit_validate(&$params){
		if(!$params['file_id']){
			$this->add_error("Invalid ID.");
			return false;	
		}else {
			$result = $this->db->get_where('files', array('file_id'=>(int) $params['file_id']));
			if($result->num_rows() !=1){
				$this->add_error("Invalid ID.");
				return false;	
			}else {
				$result = $result->row_array();
				$params['folder'] = $result['folder'];
				$params["current_file"] = $result['file_src'];
			}
		}
				
		if(!$params['title'])
			$this->add_error("Please enter Title.");			
		return $this->validate();
	}
	
	/**
	 * Get file
	 */
	function get_file($file_id=0){
		$result = $this->db->get_where('files', array('file_id'=>(int) $file_id));
		return $result->row_array(); 
	}
	
	/**
	 * Count files
	 */
	function count_files($params=array()){
		
		// file browse filter	
		if($params['searchkey']){
			$this->db->like('title', $params['searchkey']);
		}

		if($params['collection_id'])
			$this->db->where('collection_id', (int) $params['collection_id']);
		
		$this->db->from('files');
		return $this->db->count_all_results(); 	
	}
	
	/**
	 * Get pagination =>  return pagination
	 */
	function get_pagination(){		
		return $this->pagination;		
	}
	
	/**
	 * Get files
	 */
	function get_files($params=array()){
				
		$offset = (int) ($params['cur_page']-1) * $params['per_page'];
		$this->db->flush_cache();
		
		// fix CI bug
		$this->db->_reset_write();
			
		if($params['collection_id'])
			$this->db->where('collection_id', (int) $params['collection_id']);

		// file browse filter	
		if($params['searchkey']){
			$this->db->like('title', $params['searchkey']);
		}		
			
		$result = $this->db->get('files', $params['per_page'], $offset);
							
		$files = $result->result_array();
		foreach($files AS $key=>$file){
			$files[$key]['file_src'] = base_url()."uploads/files/".$file['folder']."/".$file['file_src'];
		}
		
		// Pagination
		$this->load->library('pagination');
		$this->pagination->initialize($params);  
		$this->pagination = $this->pagination->create_links();		
		
		return $files;
	}
	
	
	/**
	 * Delete file
	 */
	function delete($params){		
		$result = $this->db->get_where('files', array('file_id'=>(int) $params['file_id']));
		if($result->num_rows()>0){
			$result = $result->row_array();
			$file = "uploads/files/".$result['folder']."/".$result['file_src'];
			@unlink($file);
			$this->db->where('file_id',(int) $params['file_id'])->delete('files');
			return true;
		}
		return false;
	}
		
}