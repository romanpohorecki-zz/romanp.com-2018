<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/AdminController.php';

class Main extends AdminController {
	
	public function __construct()
	{	
		// call parent contructor	
		parent::__construct();							
	}
	
	function index(){		
		$this->dashboard();	
	}
	
	function set_language(){
		$language = $this->input->post("language");
		if(!empty($language) && in_array($language, array("en", "es"))){
			$this->session->set_userdata("language", $language);
			echo json_encode(array("success"=>true));
			die;
		}
		echo json_encode(array("success"=>false));
		die;
	}
		
	/**
	 * Login 
	 */
	function login(){

		$params = array();
		if($this->input->post()!=array()){
			
			$this->load->model("admin/auth_model", "auth");						
			$params = $this->input->post();
			if(!$this->auth->login($params))
				$params['error'] = $this->auth->get_errors();
		}
						
		$this->template->write('body_class', 'loginBody');
		$this->template->write('meta_title',$this->config->item('website')." - Login", true);
		$this->template->write_view('content', 'admin/main/login.php', array("data"=>$params));		
		$this->template->render();
	}	
	
	/**
	 * Logout 
	 */
	function logout(){
		$this->load->model("admin/auth_model", "auth");						
		$this->auth->logout();
	}	
		
	/**
	 * Dashboard
	 */
	function dashboard(){
		
		$breadcrumbs = array("Welcome"=>'');
		
		$this->template->write_view('content', 'admin/main/dashboard.php', array("breadcrumbs"=>get_breadcrumbs($breadcrumbs)));
		
		$this->template->write('meta_title',$this->config->item('website')." - Dashboard", true);
		$this->template->render();
	}
	
	/**
	 * Settings
	 */
	function settings($redir=''){
		
		$this->carabiner->js("admin/images/popup.js");
		
		check_admin(true); // check if account is in administrators group
		
		$this->carabiner->js("admin/main/settings.js");
		$this->load->model("admin/settings_model", "settings");
		$params = array();
		if($this->input->post('save')){
			$params = $this->input->post();			
			if(!$this->settings->update($params))
				$params['error'] = $this->settings->get_errors();
			else
				redirect("admin/settings/redir");	
		}else {
			$settings = array();
			$settings = $this->settings->get_settings();
			foreach ($settings AS $arr){
				$params['data'][$arr['name']] = $arr['value'];
			}
		}
		
		// Admin logo
		if(!empty($params['data']['admin_logo'])){
			$this->load->model('admin/images_model', 'images');
			$image = $this->images->get_version("admin_logo", 0, $params['data']['admin_logo']);
			if(!empty($image)){
			
				// version image
				$file = "cache/images/".$image['image_src'];
					
				// rewrite iamge_src to use version file
				$image['image_src'] = base_url().$file;

				$params['data']['admin_logo_image'] = $image;
			}
		}		
		
		if($redir)
			$params['success'] = 'Settings saved.';
		
		// BreadCrumbs
		$breadcrumbs = array("Settings" =>'');
		$this->template->write('breadcrumbs', get_breadcrumbs($breadcrumbs), true);
		
		$this->template->write('meta_title',$this->config->item('website')." - Settings", true);
		$this->template->write_view('content', 'admin/main/settings.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));		
		$this->template->render();
	}
}