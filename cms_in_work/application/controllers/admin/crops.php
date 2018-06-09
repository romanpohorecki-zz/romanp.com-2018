<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/AdminController.php';

class Crops extends AdminController {
	
	public function __construct()
	{	
		// call parent contructor	
		parent::__construct();
		$this->load->model("admin/crops_model", "crops");
	}
	function index(){
		$this->list_crops();
	}
	
	/**
	 * List crops
	 */
	function list_crops($page=1, $sortby='sort_order_crop', $sortdir='asc'){
 		
		$this->carabiner->js("admin/crops/crops.js");
		
		$params = array();
		$params['crops'] = $this->crops->get_crops($params);
		
		if($this->session->flashdata('success'))
			$params['success'] = $this->session->flashdata('success');
		
		// BreadCrumbs
		$breadcrumbs = array("Image Crops"  =>'');
		
		$this->template->write('meta_title',$this->config->item('website')." - Image Crops", true);
		$this->template->write_view('content', 'admin/crops/crops.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));
		$this->template->render();	
	}
	
	/**
	 * New Crop	 
	 */
	function add($template_id=0){
		
		$this->carabiner->js("admin/crops/crop.js");
						
		$params = array();
		if($this->input->post('save')){
			$params = $this->input->post();
			if(!$this->crops->add($params)){
				$params['error'] = $this->crops->get_errors();
			}
		}
		
		$params['action'] = 'add';
						
		// BreadCrumbs
		$breadcrumbs = array("Image Crops" =>site_url("admin/crops"), "New Crop"=>'');
		
		$this->template->write('meta_title',$this->config->item('website')." - New Crop", true);
		$this->template->write_view('content', 'admin/crops/crop.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));		
		$this->template->render();
	}
	
	/**
	 * Edit crop	 
	 */
	function edit($crop_id=0){
		
		$this->carabiner->js("admin/crops/crop.js");		
		
		if(!$crop_id)
			redirect("admin/crops");
		
		$params = array();
		if($this->input->post('save')){
			$params = $this->input->post();
			if(!$this->crops->edit($params))
				$params['error'] = $this->crops->get_errors();
			else 	
				$params['success'] = 'Crop successfully updated.';	
		}else {
			// get information about crop from database
			$params = $this->crops->get_crop($crop_id);			
			if($this->session->flashdata('success'))
				$params['success'] = $this->session->flashdata('success');	
		}
		
		$params['action'] = 'edit/'.$crop_id;
				
		// BreadCrumbs
		$breadcrumbs = array("Image crops" =>site_url("admin/crops"),  $params['title']=>'');
						
		$this->template->write('meta_title',$this->config->item('website')." - Edit crop", true);
		$this->template->write_view('content', 'admin/crops/crop.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));		
		$this->template->render();
	}
	
	/*
	 * Delete crop
	 */
	function delete($crop_id=0){
		
		if($this->crops->delete($crop_id))
			$this->session->set_flashdata("success", "Image crop successfully deleted.");
		
		redirect('admin/crops');
	}
}