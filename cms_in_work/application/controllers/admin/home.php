<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/AdminController.php';

class Home extends AdminController {
	
	public function __construct()
	{	
		// call parent contructor	
		parent::__construct();
	}
	
	function index(){
		
		$this->load->model("admin/home_model", "home");
		
		$this->carabiner->js("admin/home/form.js");
		$this->carabiner->js("admin/images/popup.js");
		
		$this->carabiner->js('libs/tinymce/tinymce.min.js');
		$this->carabiner->js('libs/tinymce/jquery.tinymce.min.js');
	
		if($this->input->post()!=array()){
			$params = $this->input->post();
			if(!$this->home->save($params)){
				$params['error'] = $this->home->get_errors();
			}else {
				$this->session->set_flashdata("success", "Content successfully saved.");
				redirect('admin/home/index');
			}
		}else {
			$params = $this->home->get_data();
		}
		
		if($this->session->flashdata('success'))
			$params['success'] = $this->session->flashdata('success');
	
		// BreadCrumbs
		$breadcrumbs = array('Home'=>'');
	
		$this->template->write('meta_title',$this->config->item('website')." -  Home", true);
		$this->template->write_view('content', 'admin/home/form.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));
		$this->template->render();
	}
}