<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class Crops_model extends MyModel{
	
	/**
	 * Add crop
	 */
	function add(&$params){
		if(!$this->add_validate($params))
			return false;
			
		// insert into crops	
		$data = array(
		   'title' 	=> $params['title'],
		   'width'	=> $params['width'],
		   'height'	=> $params['height']
		);
		
		$this->db->insert('crops', $data);
		$crop_id = $this->db->insert_id(); 
		
		if($crop_id){
			$this->session->set_flashdata("success", "Crop successfully created.");
			redirect("admin/crops/edit/".$crop_id);	
		}
		else
			 $this->add_error("Error in saving.");
		return false;
	}
	
	/**
	 * Add crop validate 
	 */
	function add_validate(&$params){
		if(!$params['title'])
			$this->add_error("Please enter Title.");
		if(!is_numeric($params['width']))
			$this->add_error("Please enter Width.");	
		if(!is_numeric($params['height']))
			$this->add_error("Please enter Height.");				
		return $this->validate();
	}
	
	/**
	 * Edit crop
	 */
	function edit(&$params){
		if(!$this->edit_validate($params))
			return false;
 	
		$data = array(
		   	'title' 	=> $params['title'],
		    'width'	=> $params['width'],
			'height'	=> $params['height']
		);
		 
		$this->db->where('crop_id', $params['crop_id'])->update('crops', $data);
		return true;
	}
	
	/**
	 * Edit crop validate 
	 */
	function edit_validate(&$params){
		if(!$params['crop_id']){
			$this->add_error("Invalid ID.");
			return false;	
		}else {
			$result = $this->db->get_where('crops', array('crop_id'=>(int) $params['crop_id']));
			if($result->num_rows() !=1){
				$this->add_error("Invalid ID.");
				return false;	
			}
		}
		if(!$params['title'])
			$this->add_error("Please enter Title.");
		if(!is_numeric($params['width']))
			$this->add_error("Please enter Width.");	
		if(!is_numeric($params['height']))
			$this->add_error("Please enter Height.");
		return $this->validate();
	}
		
	/**
	 * Get crops
	 */
	function get_crops($params=array()){		
		return $this->db->select('crops.*')->order_by("title")->get('crops')->result_array();
	}
	
	/**
	 * Get crop
	 */
	function get_crop($crop_id=0){
		return $this->db->where('crop_id', (int) $crop_id)->get('crops')->row_array();
	}
	
	/**
	 * Delete
	 */
	function delete($crop_id){
		
		$this->db->where('crop_id',(int) $crop_id)->delete('crops');
		
		return true;
	}	
	
	/**
	 * Get crops for dd list
	 */
	function get_dd_crops($params=array()){		
		
		$result = $this->db->order_by("title", "ASC")->get_where("crops");
		$crops = $result->result_array();
		$return = array();
		$return[0] = "Select One";
		foreach($crops AS $key=>$crop){
			$return[$crop['crop_id']] = $crop['title'];
		}
		return $return;
	}
}