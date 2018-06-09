<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/AdminController.php';

class Categories extends AdminController {
	
	public function __construct()
	{	
		// call parent contructor	
		parent::__construct();
		$this->load->model("admin/category_model", "category");
		$this->load->model("admin/project_model", "project");
	}
	
	function index(){
		$this->list_categories();
	}
	
	function list_categories($page=1, $sortby='sort_order', $sortdir='asc'){
		
		$this->carabiner->js("admin/categories/list.js");
		
		// get categories			
		$params['sortby'] = $sortby;	
		$params['sortdir'] = $sortdir;	
		$params['sortdir'] == 'asc' ? $params['nextsortdir'] = 'desc' : $params['nextsortdir'] = 'asc';
		$params['base_url'] = site_url("admin/categories/list_categories/");
		$params['per_page'] = 1000;
		$params['cur_page'] = 1;
		$params['uri_segment'] = 4;
		$params['total_rows'] = $this->category->count_categories();
		$params['total_pages'] = 1;
		$params['suffix'] = "/".$sortby."/".$sortdir;
		$params['categories'] = $this->category->get_categories($params);
		$params['pagination'] = $this->category->get_pagination();
		
		if($this->session->flashdata('success'))
			$params['success'] = $this->session->flashdata('success');
		
		// BreadCrumbs
		$breadcrumbs = array('Projects Categories' =>'');
		
		$this->template->write('meta_title',$this->config->item('website')." - Projects Categories", true);
		$this->template->write_view('content', 'admin/categories/list.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));
		$this->template->render();
	}
		
	/**
	 * Add categories	 
	 */
	function add($template_id=0){
		
		$this->carabiner->js("admin/categories/form.js");
		$this->carabiner->js("admin/images/popup.js");
		
		$this->carabiner->js('libs/tinymce/tinymce.min.js');
		$this->carabiner->js('libs/tinymce/jquery.tinymce.min.js');
		$this->carabiner->js('libs/bootstrap-datepicker.js');
		
		$this->carabiner->css('libs/datepicker.css');
						
		$params = array();
		if($this->input->post('save')){
			$params = $this->input->post();
			if(!$category_id = $this->category->add($params)){
				$params['error'] = $this->category->get_errors();
			}
			else {
				$this->session->set_flashdata('success', 'Category successfully created.');
				redirect('admin/categories/edit/'.$category_id);
			}			
		}
		
		$params['action'] = 'add';	
		$params['page_title'] = 'New Categories';		
						
		// BreadCrumbs
		$breadcrumbs = array("Projects Categories" =>site_url("admin/categories"), "New Category"=>'');
		
		$this->template->write('meta_title',$this->config->item('website')." - New Categories", true);
		$this->template->write_view('content', 'admin/categories/form.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));		
		$this->template->render();
	}
		
	
	/**
	 * Edit Categories	 
	 */
	function edit($category_id=0){
		
		$this->carabiner->js("admin/categories/form.js");			
		$this->carabiner->js("admin/images/popup.js");
		
		$this->carabiner->js('libs/tinymce/tinymce.min.js');
		$this->carabiner->js('libs/tinymce/jquery.tinymce.min.js');
		$this->carabiner->js('libs/bootstrap-datepicker.js');
		
		$this->carabiner->css('libs/datepicker.css');
		
		if(!$category_id)
			redirect("admin/categories");
		
		$params = array();
		if($this->input->post('save')){
			$params = $this->input->post();
			if(!$this->category->edit($params))
				$params['error'] = $this->category->get_errors();
			else {
				$this->session->set_flashdata('success', 'Category successfully updated.');
				redirect('admin/categories/edit/'.$category_id);
			}
			
			$categories = $this->category->get_category($category_id);
			$params["published"] = $categories["published"];
			
		}else {
			// get information categories categories from database
			$params = $this->category->get_category($category_id);
			
			if($this->session->flashdata('success'))
				$params['success'] = $this->session->flashdata('success');	
		}
		
		$params['action'] = 'edit/'.$category_id;		
		$params['page_title'] = 'Edit Categories - '.$params['title'];
		
		$p = array();
		$p['category_id'] = $category_id;
		$p['sortby'] = 'sort_order';
		$p['sortdir'] == 'asc';
		$p['base_url'] = site_url("admin/categories/edit/".$category_id);
		$p['per_page'] = 100;
		$p['cur_page'] = $page;
		$p['uri_segment'] = 4;
		$p['total_rows'] = $this->project->count_projects();
		$p['total_pages'] = 1;
		$p['suffix'] = "/".$sortby."/".$sortdir;
		$params['projects'] = $this->project->get_projects($p);
		
		
		// BreadCrumbs
		$breadcrumbs = array("Projects Categories" =>site_url("admin/categories"), $params['title']=>'');
										
		$this->template->write('meta_title',$this->config->item('website')." - Edit Category", true);
		$this->template->write_view('content', 'admin/categories/form.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));		
		$this->template->render();
	}
	
	/*
	 * Delete Categories
	 */
	function delete($category_id=0){

		if($this->category->delete($category_id))
			$this->session->set_flashdata("success", "Category successfully deleted.");
		
		redirect('admin/categories');
	}
	
	/**
	 * Publish/unpublish categories
	 */
	function publish(){
		
		if(!$this->input->is_ajax_request())
			die('Error');
		$category = $this->input->post('category_id');
		$new_status = $this->input->post('new_status');
		$response = $this->category->publish($category, $new_status);
		echo json_encode($response);		
	}	

	/**
	 * Insert section
	 */
	function addSection(){
		$key = generate_random_id();
		$data = array("section_id"=>$key);
		$data['index'] = $this->input->post('index');
		echo json_encode(array("key"=>$key, "html"=>$this->load->view("admin/categories/section_images.php", array("data"=>$data), true)));
	}
	
	/**
	 * Order categories
	 */
	function order_categories(){
	
		$params = $this->input->post();
		$this->category->order_categories($params);
	}	
}