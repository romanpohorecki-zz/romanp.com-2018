<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/AdminController.php';

class Pages extends AdminController {
	
	public function __construct()
	{	
		// call parent contructor	
		parent::__construct();
		$this->load->model("admin/pages_model", "pages");
		$this->load->model("admin/users_model", "users");
		$this->load->model("admin/images_model", "images");		
	}
	function index(){
		$this->list_pages();
	}
	
	/**
	 * List pages
	 */
	function list_pages($page=1, $sortby='title', $sortdir='asc'){
 		
		$this->carabiner->js("admin/pages/pages.js");
		
		$params = array();
		
		if($this->session->flashdata('success'))
			$params['success'] = $this->session->flashdata('success');

		if($this->input->post('pages_list_limit'))
			$this->session->set_userdata('pages_list_limit', $this->input->post('pages_list_limit'));	

		$params['sortby'] = $sortby;	
		$params['sortdir'] = $sortdir;	
		$params['sortdir'] == 'asc' ? $params['nextsortdir'] = 'desc' : $params['nextsortdir'] = 'asc';
		$params['base_url'] = site_url("admin/pages/list_pages/");
		$params['per_page'] = ($this->session->userdata('pages_list_limit'))? $this->session->userdata('pages_list_limit') : $this->config->item('list_limit');
		$params['cur_page'] = $page;
		$params['uri_segment'] = 4;
		$params['total_rows'] = $this->pages->count_pages();
		$params['total_pages'] = 1;
		$params['suffix'] = "/".$sortby."/".$sortdir;
		$params['pages'] = $this->pages->get_pages($params);
		$params['pagination'] = $this->pages->get_pagination();
		$params['list_limit_array'] = array(10=>10,15=>15,20=>20,25=>25,50=>50,100=>100);
		
		// BreadCrumbs
		$breadcrumbs = array("Main Pages" =>'');
		
		$this->template->write('meta_title',$this->config->item('website')." - List of Pages", true);
		$this->template->write_view('content', 'admin/pages/pages.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));
		$this->template->render();	
	}
	
	/**
	 * Add page	 
	 */
	function add($template_id=0){
		
		$this->carabiner->js("admin/pages/page.js");
		$this->carabiner->js("admin/images/popup.js");
		
		$this->carabiner->js('libs/tinymce/tinymce.min.js');
		$this->carabiner->js('libs/tinymce/jquery.tinymce.min.js');
						
		$params = array();
		if($this->input->post('save')){
			$params = $this->input->post();
			if(!$this->pages->add($params)){
				$params['error'] = $this->pages->get_errors();
			}			
		}
		// set up default values
		else {
			$params['published'] = 0; 
			$params['template_id'] = 2;	
		}
		
		$params['action'] = 'add';	
		$params['page_title'] = 'Add Page';		
		
		if($template_id){
			$params['template_id'] = $template_id;
		}
						
		// BreadCrumbs
		$breadcrumbs = array("Main Pages" =>site_url("admin/pages"), "New Page"=>'');
		
		$this->template->write('meta_title',$this->config->item('website')." - Add Page", true);
		$this->template->write_view('content', 'admin/pages/page.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));		
		$this->template->render();
	}
	
	/**
	 * Edit Page	 
	 */
	function edit($page_id=0){
		
		$this->carabiner->js("admin/pages/page.js");			
		$this->carabiner->js("admin/images/popup.js");
		
		
		$this->carabiner->js('libs/tinymce/tinymce.min.js');
		$this->carabiner->js('libs/tinymce/jquery.tinymce.min.js');
		
		if(!$page_id)
			redirect("admin/pages");
		
		$params = array();
		if($this->input->post('save')){
			$params = $this->input->post();
			if(!$this->pages->edit($params))
				$params['error'] = $this->pages->get_errors();
			else {
				$this->session->set_flashdata('success', 'Page successfully updated.');
				redirect('admin/pages/edit/'.$page_id);
			}
			
			$page = $this->pages->get_page($page_id);
			$params["published"] = $page["published"];
			
		}else {
			// get information about page from database
			$params = $this->pages->get_page($page_id);
			
			if($this->session->flashdata('success'))
				$params['success'] = $this->session->flashdata('success');	
		}
		
		$params['action'] = 'edit/'.$page_id;		
		$params['page_title'] = 'Edit Page - '.$params['title'];
		
		// BreadCrumbs
		$breadcrumbs = array($params['title'] =>"");
										
		$this->template->write('meta_title',$this->config->item('website')." - Edit Page", true);
		$this->template->write_view('content', 'admin/pages/page.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));		
		$this->template->render();
	}
	
	/*
	 * Delete Page
	 */
	function delete($page_id=0){

		if($this->pages->delete($page_id))
			$this->session->set_flashdata("success", "Page successfully deleted.");
		
		redirect('admin/pages');
	}
	
	/**
	 * Publish/unpublish Pages
	 */
	function publish(){
		
		if(!$this->input->is_ajax_request())
			die('Error');
		$page = $this->input->post('page_id');
		$new_status = $this->input->post('new_status');
		$response = $this->pages->publish($page, $new_status);
		echo json_encode($response);		
	}	
	
	/**
	 * Insert sections functions
	 */
	function addNewSection(){
		$key = generate_random_id();
		$data = array("key"=>$key);
		echo json_encode(array("key"=>$key, "html"=>$this->load->view("admin/pages/page_section.php", array("data"=>$data), true)));
	}	
	function addWideImageSection(){
		$key = generate_random_id();
		$data = array("key"=>$key);
		echo json_encode(array("key"=>$key, "html"=>$this->load->view("admin/pages/page_wide_image_section.php", array("data"=>$data), true)));
	}
	/******************/
}