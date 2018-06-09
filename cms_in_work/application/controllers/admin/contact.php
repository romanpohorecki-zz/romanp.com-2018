<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/AdminController.php';

class Contact extends AdminController {
	
	public function __construct()
	{	
		// call parent contructor	
		parent::__construct();
	}
	
	function index(){
		
		$this->load->model("admin/contact_model", "contact");
		
		$this->carabiner->js("admin/contact/form.js");
		$this->carabiner->js("admin/images/popup.js");
		
		$this->carabiner->js('libs/tinymce/tinymce.min.js');
		$this->carabiner->js('libs/tinymce/jquery.tinymce.min.js');
	
		if($this->input->post()!=array()){
			$params = $this->input->post();
			if(!$this->contact->save($params)){
				$params['error'] = $this->contact->get_errors();
			}else {
				$this->session->set_flashdata("success", "Contact section successfully saved.");
				redirect('admin/contact/index');
			}
		}else {
			$params = $this->contact->get_data();
		}
		
		if($this->session->flashdata('success'))
			$params['success'] = $this->session->flashdata('success');
	
		// BreadCrumbs
		$breadcrumbs = array('Contact'=>'');
	
		$this->template->write('meta_title',$this->config->item('website')." -  Contact", true);
		$this->template->write_view('content', 'admin/contact/form.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));
		$this->template->render();
	}
}