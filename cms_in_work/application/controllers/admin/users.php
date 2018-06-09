<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/AdminController.php';

class Users extends AdminController {
	
	public function __construct()
	{	
		// call parent contructor	
		parent::__construct();
		$this->load->model("admin/users_model", "users");
	}
	function index(){
		$this->list_users();
	}
	
	/**
	 * List users
	 */
	function list_users($page=1, $sortby='firstname', $sortdir='asc'){
 
		check_admin(true); // check if account is in administrators group
		$this->carabiner->js("admin/users/users.js");
		
		$params = array();
		
		if($this->session->flashdata('success'))
			$params['success'] = $this->session->flashdata('success');

		if($this->input->post('users_list_limit'))
			$this->session->set_userdata('users_list_limit', $this->input->post('users_list_limit'));	

		$params['sortby'] = $sortby;	
		$params['sortdir'] = $sortdir;	
		$params['sortdir'] == 'asc' ? $params['nextsortdir'] = 'desc' : $params['nextsortdir'] = 'asc';	
		$params['base_url'] = site_url("admin/users/list_users/");
		$params['total_rows'] = $this->users->count_users();
		$params['per_page'] = $params['total_rows'];
		$params['cur_page'] = $page;
		$params['uri_segment'] = 4;
		$params['suffix'] = "/".$sortby."/".$sortdir;
		$params['total_pages'] = ceil($params['total_rows']/$params['per_page']);
		$params['users'] = $this->users->get_users($params);
		$params['pagination'] = $this->users->get_pagination();
		$params['list_limit_array'] = array(10=>10,15=>15,20=>20,25=>25,50=>50,100=>100);
		
		// BreadCrumbs
		$breadcrumbs = array("Users" =>'');
		
		$this->template->write('meta_title',$this->config->item('website')." - List of Users", true);
		$this->template->write_view('content', 'admin/users/users.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));		
		$this->template->render();	
	}
	
	/**
	 * Add user	 
	 */
	function add(){
			
		check_admin(true); // check if account is in administrators group
		
		$this->carabiner->js("admin/users/user.js");
		
		$params = array();
		if($this->input->post('save')){									
			$params = $this->input->post();
			if(!$this->users->add($params))
				$params['error'] = $this->users->get_errors();
		}else 
			$params['active'] = 1;
		
		$params['action'] = 'add';	
		$params['page_title'] = 'New User';	
		$params['groups'] = $this->users->get_groups();
		
		$params['password'] = '';
		$params['password2'] = '';
						
		// BreadCrumbs
		$breadcrumbs = array("Users" =>site_url("admin/users"), "New User"=>'');
		
		$this->template->write('meta_title',$this->config->item('website')." - New User", true);
		$this->template->write_view('content', 'admin/users/user.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));		
		$this->template->render();
	}
	
	/**
	 * Edit user	 
	 */
	function edit($user_id=0){
		
		check_admin(true); // check if account is in administrators group
		
		$this->carabiner->js("admin/users/user.js");
		
		if(!$user_id)
			redirect("admin/users");
		
		$params = array();
		if($this->input->post('save')){
			$params = $this->input->post();
			if(!$this->users->edit($params))
				$params['error'] = $this->users->get_errors();
			else 	
				$params['success'] = 'User successfully saved.';
		}else {
			// get information about user from database
			$params = $this->users->get_user($user_id);
			if($this->session->flashdata('success'))
				$params['success'] = $this->session->flashdata('success');	
		}
		
		$params['action'] = 'edit/'.$user_id;		
		$params['page_title'] = 'Edit User - '.$params['firstname']." ".$params['lastname'];		
		$params['groups'] = $this->users->get_groups(array('exclude'=>'public'));
		
		$params['password'] = '';
		$params['password2'] = '';
		
		// BreadCrumbs
		$breadcrumbs = array("Users" =>site_url("admin/users"), $params['firstname']." ".$params['lastname']=>'');
						
		$this->template->write('meta_title',$this->config->item('website')." - Edit User", true);
		$this->template->write_view('content', 'admin/users/user.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));		
		$this->template->render();
	}	
	
	/*
	 * Delete user
	 */
	function delete($user_id=0){
		
		check_admin(true); // check if account is in administrators group
		
		if($this->users->delete($user_id))
			$this->session->set_flashdata("success", "User successfully deleted.");
		
		redirect('admin/users');
	}	
	
	/**
	 * Activate/deactivate users
	 */
	function activate(){
		
		check_admin(true); // check if account is in administrators group
		
		if(!$this->input->is_ajax_request())
			die('Error');
		$user = $this->input->post('user_id');
		$new_status = $this->input->post('new_status');
		$response = $this->users->activate($user, $new_status);
		echo json_encode($response);		
	}
	
	/**
	 *	Access Groups 
	 */
	function groups($page=1){
 
		check_admin(true); // check if account is in administrators group
		
		$this->carabiner->js("admin/users/groups.js");
		
		$params = array();
		if($this->session->flashdata('success'))
			$params['success'] = $this->session->flashdata('success');
		
		$params['groups']=$this->users->get_groups_access();
		
		// BreadCrumbs
		$breadcrumbs = array("Users" => site_url("admin/users"),"Access Groups" =>'');
		$this->template->write('breadcrumbs', get_breadcrumbs($breadcrumbs), true);
		
		$this->template->write('meta_title',$this->config->item('website')." - Access Groups", true);
		$this->template->write_view('content', 'admin/users/groups.php', array("data"=>$params));
		$this->template->write('page_class', 'container_dashboard');		
		$this->template->render();	
	}
	
	/**
	 *	Access GroupsActivate/Deactivate
	 */
	function group_activate($page=1){
		
		check_admin(true); // check if account is in administrators group
		
		if(!$this->input->is_ajax_request())
			die('Error');
		$perm_id = $this->input->post('perm_id');
		$perm_type = $this->input->post('perm_type');
		$new_status = $this->input->post('new_status');
		$response = $this->users->group_activate($perm_id, $perm_type, $new_status);
		echo json_encode($response);
		
	}
	
}