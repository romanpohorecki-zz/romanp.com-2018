<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/FrontController.php';

class Projects extends FrontController {
	
	public function __construct()
	{	
		// call parent contructor	
		parent::__construct();	
		$this->load->model("projects_model", "project");
		$this->load->model("images_model", "images");
	}
		
	/**
	 * Project Detail
	 */
	function index($category_id=0){
		
		$this->carabiner->js('libs/jquery.touchSwipe.min.js');
		$this->carabiner->js('pslider.js');
		
		$this->template->write('body_class', 'projects');
		
		// Load first project from each category
		$projects = $this->project->get_category_projects($category_id);
		if(!empty($projects)){
			$i = 0;
			foreach ($projects AS $project){
				$this->template->write_view('content', 'frontend/home/slideshow', array('project'=>$project, 'images'=>$project['images'], 'id'=>$i));
				$i++;
			}
		}
	
		// META INFO
		$this->template->write('meta_title', /*$page['meta_title'] ? $page['meta_title']:$this->config->item("meta_title")*/ 'Projects | S3 Architects', true);
		$this->template->write('meta_description', $page['meta_description'] ? $page['meta_description']:$this->config->item("meta_description"), true);
		$this->template->write('meta_keywords', $page['meta_keywords'] ? $page['meta_keywords']:$this->config->item("meta_keywords"), true);
		$this->template->render();
	}
}