<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/AdminController.php';

class Projects extends AdminController {
	
	public function __construct()
	{	
		// call parent contructor	
		parent::__construct();
		$this->load->model("admin/project_model", "project");
		$this->load->model("admin/category_model", "category");
	}
	
	function index(){
		$this->list_projects();
	}
	
	function list_projects($page=1, $sortby='sort_order', $sortdir='asc'){
		
		$this->carabiner->js("admin/projects/list.js");
		
		// get projects
		$params['sortby'] = $sortby;	
		$params['sortdir'] = $sortdir;	
		$params['sortdir'] == 'asc' ? $params['nextsortdir'] = 'desc' : $params['nextsortdir'] = 'asc';
		$params['base_url'] = site_url("admin/projects/list_projects/");
		$params['per_page'] = 100;
		$params['cur_page'] = $page;
		$params['uri_segment'] = 4;
		$params['total_rows'] = $this->project->count_projects();
		$params['total_pages'] = 1;
		$params['suffix'] = "/".$sortby."/".$sortdir;
		$params['projects'] = $this->project->get_projects($params);
		$params['pagination'] = $this->project->get_pagination();
		
		if($this->session->flashdata('success'))
			$params['success'] = $this->session->flashdata('success');
		
		// BreadCrumbs
		$breadcrumbs = array('Projects' =>'');
		
		$this->template->write('meta_title',$this->config->item('website')." - Projects", true);
		$this->template->write_view('content', 'admin/projects/list.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));
		$this->template->render();
	}
		
	/**
	 * Add projects	 
	 */
	function add($template_id=0){
		
		$this->carabiner->js("admin/projects/form.js");
		$this->carabiner->js("admin/images/popup.js");
		
		$this->carabiner->js('libs/tinymce/tinymce.min.js');
		$this->carabiner->js('libs/tinymce/jquery.tinymce.min.js');
		$this->carabiner->js('libs/bootstrap-datepicker.js');
		
		$this->carabiner->css('libs/datepicker.css');
		
		if(!$this->input->get('category_id'))
			redirect('admin/categories');
						
		$params = array();
		if($this->input->post('save')){
			$params = $this->input->post();
			if(!$project_id = $this->project->add($params)){
				$params['error'] = $this->project->get_errors();
			}
			else {
				$this->session->set_flashdata('success', 'Project successfully created.');
				redirect('admin/projects/edit/'.$project_id);
			}			
		}
		
		$params['action'] = 'add?category_id='.$this->input->get('category_id');	
		$params['page_title'] = 'New Projects';		
		$params['category'] = $this->category->get_category($this->input->get('category_id'));		
						
		// BreadCrumbs
		$breadcrumbs = array("Projects Categories" =>site_url("admin/categories"), $params['category']['title'] =>site_url("admin/categories/edit/".$this->input->get('category_id')), "New Project"=>'');
		
		$this->template->write('meta_title',$this->config->item('website')." - New Projects", true);
		$this->template->write_view('content', 'admin/projects/form.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));		
		$this->template->render();
	}
		
	
	/**
	 * Edit Projects	 
	 */
	function edit($project_id=0){
		
		$this->carabiner->js("admin/projects/form.js");			
		$this->carabiner->js("admin/images/popup.js");
		
		$this->carabiner->js('libs/tinymce/tinymce.min.js');
		$this->carabiner->js('libs/tinymce/jquery.tinymce.min.js');
		$this->carabiner->js('libs/bootstrap-datepicker.js');
		
		$this->carabiner->css('libs/datepicker.css');
		
		if(!$project_id)
			redirect("admin/projects");
		
		$params = array();
		if($this->input->post('save')){
			$params = $this->input->post();
			if(!$this->project->edit($params))
				$params['error'] = $this->project->get_errors();
			else {
				$this->session->set_flashdata('success', 'Project successfully updated.');
				redirect('admin/projects/edit/'.$project_id);
			}
			
			$projects = $this->project->get_project($project_id);
			$params["published"] = $projects["published"];
			
		}else {
			// get information projects projects from database
			$params = $this->project->get_project($project_id);
			
			if($this->session->flashdata('success'))
				$params['success'] = $this->session->flashdata('success');	
		}
				
		$params['action'] = 'edit/'.$project_id;		
		$params['page_title'] = 'Edit Projects - '.$params['title'];
		$params['category'] = $this->category->get_category($params['category_id']);
		
		// BreadCrumbs
		$breadcrumbs = array("Projects Categories" =>site_url("admin/categories"), $params['category']['title'] =>site_url("admin/categories/edit/".$params['category_id']), $params['title']=>'');
										
		$this->template->write('meta_title',$this->config->item('website')." - Edit Project", true);
		$this->template->write_view('content', 'admin/projects/form.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));		
		$this->template->render();
	}
	
	/*
	 * Delete Projects
	 */
	function delete($project_id=0){

		if($this->project->delete($project_id))
			$this->session->set_flashdata("success", "Project successfully deleted.");
		
		redirect('admin/projects');
	}
	
	/**
	 * Publish/unpublish projects
	 */
	function publish(){
		
		if(!$this->input->is_ajax_request())
			die('Error');
		$project = $this->input->post('project_id');
		$new_status = $this->input->post('new_status');
		$response = $this->project->publish($project, $new_status);
		echo json_encode($response);		
	}	

	/**
	 * Insert section
	 */
	function addSection(){
		$key = generate_random_id();
		$data = array("section_id"=>$key);
		$data['index'] = $this->input->post('index');
		echo json_encode(array("key"=>$key, "html"=>$this->load->view("admin/projects/section_images.php", array("data"=>$data), true)));
	}
	
	/**
	 * Order projects
	 */
	function order_projects(){
	
		$params = $this->input->post();
		$this->project->order_projects($params);
	}
}