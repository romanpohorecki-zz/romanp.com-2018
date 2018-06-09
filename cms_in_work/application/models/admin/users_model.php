<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class Users_model extends MyModel{
	
	/**
	 * Add user
	 */
	function add(&$params){
		if(!$this->add_validate($params))
			return false;

		$data = array(
		   'email ' 		=> $params['email'],
		   'password' 		=> md5($params['password']),
		   'firstname' 		=> $params['firstname'],
		   'lastname' 		=> $params['lastname'],
		   'group_id' 		=> $params['group_id'],
		   'register_date'	=> time(),
		   'last_login' 	=> time(),
		   'active' 		=> (int) $params['active']
		);
		
		$this->db->insert('users', $data);
		$user_id = $this->db->insert_id(); 
		if($user_id){
			$this->session->set_flashdata("success", "User successfully saved.");
			redirect("admin/users/edit/".$user_id);	
		}
		else
			 $this->add_error("Error in saving.");
		return false;
	}
	
	/**
	 * Add user validate 
	 */
	function add_validate(&$params){
		if(!$params['firstname'])
			$this->add_error("Please enter First Name.");
		if(!$params['lastname'])
			$this->add_error("Please enter Last Name.");
		if(!$params['email'])
			$this->add_error("Please enter Email address.");
		elseif(!filter_var($params['email'], FILTER_VALIDATE_EMAIL))
			$this->add_error("Email address is not valid!");		
		else {
			$result = $this->db->where('email', $params['email'])->get('users');
			if($result->num_rows()>0)
				$this->add_error("Email address is already used by another user.");
		}				 
		if(!$params['password'])
			$this->add_error("Please enter Password.");
		if(!$params['password2'])
			$this->add_error("Please confirm your Password.");
		elseif($params['password'] != $params['password2'])	
			$this->add_error("Passwords don't match");
		if(!$params['group_id'])
			$this->add_error("Please select Access Group.");	
		return $this->validate();
	}
	
	/**
	 * Edit user
	 */
	function edit(&$params){
		if(!$this->edit_validate($params))
			return false;

		$data = array(
		   'email ' 		=> $params['email'],
		   'firstname' 		=> $params['firstname'],
		   'lastname' 		=> $params['lastname'],
		   'group_id' 		=> $params['group_id'],
		   'active' 		=> (int) $params['active']
		);
		
		if(strlen(trim($params['password']))>0)
			$data['password']=md5($params['password']); 
		
		$this->db->where('user_id', $params['user_id']);
		$this->db->update('users', $data);
		
		return true;
	}
	
	/**
	 * Edit user validate 
	 */
	function edit_validate(&$params){
		if(!$params['user_id']){
			$this->add_error("Invalid ID.");
			return false;	
		}else {
			$result = $this->db->get_where('users', array('user_id'=>(int) $params['user_id']));
			if($result->num_rows() !=1){
				$this->add_error("Invalid ID.");
				return false;	
			}
		}
		if(!$params['firstname'])
			$this->add_error("Please enter First Name.");
		if(!$params['lastname'])
			$this->add_error("Please enter Last Name.");
		if(!$params['email'])
			$this->add_error("Please enter Email address.");			
		elseif(!filter_var($params['email'], FILTER_VALIDATE_EMAIL))
			$this->add_error("Email address is not valid!");
		// heck for unicity	
		else {
			$result = $this->db->where('email', $params['email'])->where('user_id !=', $params['user_id'])->get('users');
			if($result->num_rows()>0)
				$this->add_error("Email address is already used by another user.");
		}					 
		if($params['password'] && !$params['password2'])
			$this->add_error("Please confirm your Password.");
		elseif($params['password'] != $params['password2'])	
			$this->add_error("Passwords don't match");
				
		if(!$params['group_id'])
			$this->add_error("Please select Access Group.");	
		
		return $this->validate();
	}
	

	/**
	 * Get user
	 */
	function get_user($user_id=0){
		$result = $this->db->get_where('users', array('user_id'=>(int) $user_id));
		if($result->num_rows() >0)
			return $result->row_array();
		else 
			return array(); 	
	}
	
	/**
	 * Get users
	 */
	function get_users($params=array()){		
		$offset = (int) ($params['cur_page']-1) * $params['per_page'];
		$result = $this->db->select('users.*,groups.name AS `group`')
							->join('groups', 'groups.group_id = users.group_id', 'left')
							->order_by($params['sortby'], $params['sortdir'])
							->get('users', $params['per_page'], $offset);
		$users = $result->result_array();
		
		foreach($users AS $key=>$user){
			$users[$key]['name'] = $user['firstname']." ".$user['lastname'];
			$users[$key]['edit_link'] = site_url("admin/users/edit/".$user['user_id']);
			
			if($user['active']){
				$users[$key]['active_class'] = "active";
				$users[$key]['active_text'] = "Click to deactivate";
			}else {
				$users[$key]['active_class'] = "inactive";
				$users[$key]['active_text'] = "Click to activate";
			}
		}
		
		// Pagination
		$this->load->library('pagination');
		$this->pagination->initialize($params);
		$this->pagination = $this->pagination->create_links();		
		
		return $users;
	}	
	
	/**
	 * Get pagination =>  return pagination
	 */
	function get_pagination(){		
		return $this->pagination;		
	}
	
	
	/**
	 * Count users
	 */
	function count_users(){
		return $this->db->count_all('users'); 	
	}	
	
	/**
	 * Get user groups
	 */
	function get_groups($params=array()){
		
		$filter = '';
		if($params['exclude'])
			$this->db->where_not_in('type', $params['exclude']);
			
		$result = $this->db->order_by('sort_order')->get('groups');
		$return = array();
		foreach ($result->result_array() AS $array){
			$return[$array['group_id']]= $array['name'];
		}
		return $return; 	
	}
	
	/**
	 * Delete
	 */
	function delete($user_id){		
		$this->db->where('user_id',(int) $user_id)->delete('users');
		return true;
	}	
	
	/**
	 * Activate/Deactivate Users
	 */
	function activate($user_id, $new_status){
		if($new_status=="active"){
			$status = 1;
			$msg_status = 'activated';
		}else {
			$status = 0;
			$msg_status = 'deactivated';	
		}
		$this->db->set('active', $status)->where('user_id', (int) $user_id)->update('users');
		return array('success'=>'User account successfully '.$msg_status);
	}
	
	/**
	 * get_groups_access
	 */
	function get_groups_access(){
		// get groups
		$result = $this->db->order_by('sort_order')->get('groups');
		$groups = $result->result_array();
		$perm_array = array('p_add', 'p_edit', 'p_delete', 'p_view'); // array with posssible permissions
		
		$return = array();
		foreach ($groups AS $key=>$group){
			
			$result = $this->db->where('group_id', $group['group_id'])->order_by('component_label')->get('permissions');
			$permissions = $result->result_array();
			
			foreach($permissions AS $id=>$permission){
				$groups[$key]['permissions'][$id]['component_label'] = $permission['component_label'];
				$groups[$key]['permissions'][$id]['perm_id'] = $permission['perm_id'];
				foreach ($perm_array AS $perm){
					$groups[$key]['permissions'][$id][$perm] = $this->format_active($permission, $perm);					
				}
			} 
		}		
		//pr($groups); die;		
		return $groups;
	}

	/*
	 * Format permission active array()
	 */
	function format_active($permission, $perm){
		$ret= array();
		if($permission[$perm]==1){
			$ret['active_class'] = "active";
			$ret['active_text'] = "Click to revoke access";
			$ret['active_link'] = $permission['perm_id'];
		}elseif($permission[$perm]==2) {
			$ret['active_class'] = "lock";
			$ret['active_text'] = "This permission can't be granted to selected Access Group";	
			$ret['active_link'] = "#";
		}else{
			$ret['active_class'] = "inactive";
			$ret['active_text'] = "Click to grant access";
			$ret['active_link'] = $permission['perm_id'];
		}
		return $ret;	
	}
	
	/**
	 * Activate/Deactivate Group permissions
	 */
	function group_activate($perm_id, $perm_type, $new_status){
		
		if($new_status != "active" && $new_status!="inactive")
			die;
		
		if($new_status=="active"){
			$status = 1;
			$msg_status = 'granted';
		}elseif($new_status=="inactive") {
			$status = 0;
			$msg_status = 'revoked';	
		}
		$this->db->set($perm_type, $status)->where('perm_id', (int) $perm_id)->update('permissions');
		return array('success'=>'Access '.$msg_status.' to selected permission');
	}
	
	/**
	 * Get Public Group
	 */
	function get_public_group(){			
		$result = $this->db->get_where('groups', array('type'=>"public"));
		return $result->row_array(); 	
	}	
	
}