<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class AdminController extends CI_Controller{
    
	/** 
	* Class Constructor
	*/
	public function __construct()
	{		
		parent::__construct();
		
		// get settings from database and put in $config_vars
		$this->load->model("admin/settings_model", "settings");		
		$settings = array();
		$settings = $this->settings->get_settings();
		foreach ($settings AS $arr){
			$this->config->set_item($arr['name'], $arr['value']);	
		}
						
		// redirect to login page 
		if(!$this->session->userdata('user_id') && trim($this->uri->uri_string(),"/") != "admin/login")
			redirect("admin/login");
 
		if(!$this->session->userdata('user_id'))				
			$this->template->set_template('admin_clean');
		// set admin template && write content in zones	
		else{
			$this->template->set_template('admin');
			$this->template->write_view('menu','admin/main/menu.php');
		}	
		
		$this->template->write('js','var CMSURL = "'.site_url().'";');		
		$this->template->write('js','var BASEURL = "'.base_url().'";');		
			
		// setup default meta tags
		$this->template->write('meta_title',$this->config->item('meta_title'));
		$this->template->write('meta_description',$this->config->item('meta_description'));
		$this->template->write('meta_keywords',$this->config->item('meta_keywords'));

		// load css/js files
		$this->carabiner->css("admin/admin.css");
		$this->carabiner->css("libs/bootstrap.min.css");
		$this->carabiner->css("libs/bootstrap-theme.min.css");
		$this->carabiner->css("libs/bootstrap-dialog.min.css");
		$this->carabiner->css("libs/shadowbox.css");
		
		$this->carabiner->js("libs/jquery.min.js");
		$this->carabiner->js("libs/bootstrap.min.js");
		$this->carabiner->js("libs/jquery-ui.min.js");
		$this->carabiner->js("libs/jquery.validate.min.js");
		$this->carabiner->js("libs/bootstrap-dialog.min.js");
		$this->carabiner->js("libs/shadowbox.js");
		$this->carabiner->js("admin/main/general.js");
		
		$this->carabiner->js("libs/uploadify/swfobject.js");
		$this->carabiner->js("libs/uploadify/jquery.uploadify.v2.1.4.min.js");
		$this->carabiner->css("libs/uploadify.css");
		
	}
}