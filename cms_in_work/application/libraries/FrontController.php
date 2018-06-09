<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class FrontController extends CI_Controller{
    
	/** 
	* Class Constructor
	*/
	public function __construct()
	{		
		parent::__construct();
				
		// language set on link switch
		$uri_string = uri_string();
		if(!empty($uri_string) && in_array($uri_string, array('en','es'))){
			$this->session->set_userdata("language", $uri_string);
		}
		
		// get settings from database and put in $config_vars
		$this->load->model("admin/settings_model", "settings");
		$settings = array();
		$settings = $this->settings->get_settings();
		foreach ($settings AS $arr){
			$this->config->set_item($arr['name'], $arr['value']);	
		}
		
		if($this->config->item('website_offline') == 1 && $this->config->item('maintenance_ip') != $_SERVER['REMOTE_ADDR']){
			echo $this->config->item('website_offline_msg');
			die;
		}
		
		// js code
		$this->template->write('js_code', 'var slideSpeed='.$this->config->item('slide_speed'));
		
		// menu
		$this->load->model('category_model', 'category');
		$categories = $this->category->get_categories();
		
		$this->load->model("about_model", "about");
		$about = $this->about->get_data();
		$this->template->write_view('content', 'frontend/templates/menu', array("categories"=>$categories, 'logo'=> $about));
		
		// setup default meta tags
		$this->template->write('meta_title',$this->config->item('meta_title'));
		$this->template->write('meta_description',$this->config->item('meta_description'));
		$this->template->write('meta_keywords',$this->config->item('meta_keywords'));
		
		// CSS & JS
		$this->carabiner->css("atomic.css");
		$this->carabiner->css("ratios.css");
		$this->carabiner->css("style.css");
		$this->carabiner->js("libs/jquery.min.js");
		$this->carabiner->js("general.js");
		$this->carabiner->js("libs/jquery.mousewheel.min.js");
		$this->carabiner->js("libs/jquery.scrollTo.min.js");
	}
}