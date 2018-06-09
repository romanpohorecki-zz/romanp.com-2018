<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class Auth_model extends MyModel{

	/**
	 * Login 
	 */
	function login(&$params){
		
		if(!$this->login_validate($params))
			return false;
		
		$result = $this->db->select('users.*, groups.type AS group_type')
							->join('groups', 'groups.group_id=users.group_id', 'inner')
							->get_where('users', array('email'=> $params['email'], 'password'=>md5($params['password'])));
		if($result->num_rows() > 0){
			
			$user_array = $result->row_array();
			if($user_array['active'] == 0){
				$this->add_error("You account is not active. Please contact administrator.");
				return false;
			}
			
			// update last_login
			$this->db->set('last_login', time())->where('user_id', $user_array['user_id'])->update('users');
			
			// set language
			$user_array["language"] = "en";
			if(!empty($params["language"]) && in_array($params["language"], array("en", "fr"))){
				$user_array["language"] = $params["language"];	
			}
			
			$this->session->set_userdata($user_array); 
			redirect("admin");
		}			
		else{
			$this->add_error("Invalid E-mail address or Password!");
			return false;
		}		
	}
	
	/**
	 * Login validate
	 */
	function login_validate(&$params){

		if(!$params['email'])
			$this->add_error("Please enter E-mail address.");
		if(!$params['password'])
			$this->add_error("Please enter Password.");
		
		return $this->validate();
	}
	
	/**
	 * Logout
	 */
	function logout(){
		$this->session->sess_destroy();
		redirect("admin/login");			
	}	
}