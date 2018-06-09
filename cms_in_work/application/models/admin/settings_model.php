<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class Settings_model extends MyModel{
	
	/**
	 * Get settings from database
	 */
	function get_settings(){
		$result = $this->db->get('settings');
		return $result->result_array(); 	
	}
	
	/**
	 * Get settings formated
	 */
	function get_settings_array(){
		$result = $this->db->get('settings');
		$result = $result->result_array();
		$return = array(); 
		foreach($result AS $row){
			$return[$row['name']] = $row['value'];
		}
		return $return;	
	}

	/**
	 * Update settings 
	 */
	function update(&$params){
		
		if(!$this->update_validate($params))
			return false;
			
		foreach ($params['data'] AS $key=>$value){
			$this->db->set('value', $value)->where('name', $key)->update('settings');
		}				
		return true;
	}
	
	/**
	 * Update settings validate
	 */
	function update_validate(&$params){		 
		if(!$params['data']['website'])
			$this->add_error("Please enter Website Name.");
	
		return $this->validate();
	}	
}