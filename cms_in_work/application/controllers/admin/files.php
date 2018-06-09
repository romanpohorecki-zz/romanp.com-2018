<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/AdminController.php';

class Files extends AdminController {
	
	public function __construct()
	{	
		// call parent contructor	
		parent::__construct();
		$this->load->model("admin/files_model", "files");
		$this->load->model("admin/file_collections_model", "file_collections");
	}
	
	function index(){
		$this->collections();
	}
	
	/*
	 * Add new collection
	 */
	function collection_add(){
		
		$this->carabiner->js("admin/files/collection_add.js");
		
		$params = array();
		if($this->input->post()){
			$params	= $this->input->post();
			if(!$this->file_collections->add($params))
				$params['error'] = $this->file_collections->get_errors();
			else	
				$params['success'] = true;
		}
		$params['types'] = array('artwork'=>'Artwork', 'background'=> 'Backgrounds', 'headline'=> 'Headline files', 'news'=>'News files', 'return'=> 'Return files','videos'=>'Videos');
				
		$this->load->view("admin/files/collection_add", array("data"=>$params));			
	}
	
	
	/**
	 * Collections
	 */
	function collections($sortby='title', $sortdir='asc'){
		
		$this->carabiner->js("admin/files/collections.js");
		
		if($this->session->flashdata('success'))
			$params['success'] = $this->session->flashdata('success');
		
		$params = array();	
		$params['sortby'] = $sortby;	
		$params['sortdir'] = $sortdir;	
		$params['sortdir'] == 'asc' ? $params['nextsortdir'] = 'desc' : $params['nextsortdir'] = 'asc';	
			
		// get collections
		$params['collections'] = $this->file_collections->get_collections($params);
			
		// BreadCrumbs
		$breadcrumbs = array("Files"=>'');
		
		$this->template->write('meta_title',$this->config->item('website')." - Files", true);
		$this->template->write_view('content', 'admin/files/collections.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));		
		$this->template->render();
	}

	/*
	 * Delete Collection
	 */
	function collection_delete(){
		
		if(!$this->input->is_ajax_request())
			die('Error');
			
		$collection = $this->input->post('collection');
		$this->file_collections->delete($collection);
	}
	
	/**
	 * Collection
	 */
	function collection($collection_id=0){
 		
		$this->carabiner->js("uploadify/swfobject.js");
		$this->carabiner->js("uploadify/jquery.uploadify.v2.1.4.min.js");
		$this->carabiner->css("uploadify.css");
		
		$params = array();
		
		if($this->session->flashdata('success'))
			$params['success'] = $this->session->flashdata('success');
		
		$collection = $this->file_collections->get_collection($collection_id);
		if(!$collection['title'])
			redirect("admin/files/collections");
		
		if($collection['type'] != 'videos')		
			$this->carabiner->js("admin/files/collection.js");
		else 	
			$this->carabiner->js("admin/videos/collection.js");
		
		if($this->input->post()){
			$params	= $this->input->post();
			if(!$this->file_collections->edit($params))
				$params['error'] = $this->file_collections->get_errors();
		}else {
			$params['collection_name'] = $collection['title'];
		}
		
		$params['collection_id'] = $collection_id;
		$params['types'] = array('artwork'=>'Artwork', 'background'=> 'Backgrounds', 'headline'=> 'Headline files', 'news'=>'News files', 'return'=> 'Return files','videos'=>'Videos');	
		
		// BreadCrumbs
		$breadcrumbs = array("Files"=>site_url("admin/files/collections"), $collection['title']=>'');
		
		$this->template->write('meta_title',$this->config->item('website')." - Files", true);
		$this->template->write_view('content', 'admin/files/collection.php', array('data'=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));		
		$this->template->render();	
	}
	
	/*
	 * Add new file
	 */
	function file($file_id=0, $collection_id=0){
		
		$this->carabiner->js("admin/files/file.js");
		$this->carabiner->js("uploadify/swfobject.js");
		$this->carabiner->js("uploadify/jquery.uploadify.v2.1.4.min.js");
		$this->carabiner->css("uploadify.css");
		
		$params = array();
		
		if($this->input->post()){
			$params	= $this->input->post();
			if(!$file_id){
				if(!$this->files->add($params)){
					$params['error'] = $this->files->get_errors();
					if($params['file_src'])
						$params['file'] = base_url()."uploads/files/tmp/".$params['file_src'];
				}		
			}
			else {
				$params['file_id'] = $file_id;
				if(!$this->files->edit($params)){
					$params['error'] = $this->files->get_errors();
					if($params['file_src'])
						$params['file'] = base_url()."uploads/files/tmp/".$params['file_src'];
				}	
			}
		}
		
		// UPDATE
		if($file_id>0){
			$file = $this->files->get_file($file_id);
			
			if(!$file['file_id'])
				redirect("admin/files/file");
				
			$params['title'] = $file['title'];
			$params['caption'] = $file['caption'];
			$params['file_id'] = $file_id;

			if(!$params['file'])
				$params['file'] = base_url()."uploads/files/".$file['folder']."/".$file['file_src'];
			
			if($this->session->flashdata('success'))
				$params['success'] = $this->session->flashdata('success');
				
			$params['page_title'] = 'Edit file';	
		}
		// New
		else {
			$params['page_title'] = 'Upload New file';
			if(!$params['file'])
				$params['file'] = "";
		}
		
		if(!$params['collection_id'])
			$params['collection_id'] = $collection_id;
			
		// Collection $collection_id present ??? for Page Add file feature	
		if($collection_id)
			$params['collection_id_present'] = $collection_id;
		else 
			$params['collection_id_present'] = 0;
			
		// collection DD	
		$collections = $this->file_collections->get_collections();
		$params['collections'] = array();
		foreach ($collections AS $collection){
			$params['collections'][$collection['collection_id']] = $collection['title'];	
		}	
				
		$this->load->view("admin/files/file", array("data"=>$params));			
	}
	
	/*
	 * Return list of files
	 */
	function get_files($collection_id=0,$page=1){
		
		$params = $this->input->post();
		$params['collection_id'] = $collection_id;
		$params['base_url'] = site_url("admin/files/get_files/".$collection_id."/");
		$params['per_page'] = 15;
		$params['cur_page'] = $page;
		$params['uri_segment'] = 5;
		$params['total_rows'] = $this->files->count_files($params);
		$params['total_pages'] = ceil($params['total_rows']/$params['per_page']);		
		$params['files'] = $this->files->get_files($params);
		$params['pagination'] = $this->files->get_pagination();
		
		$this->load->view("admin/files/files", array("data"=>$params));
	}
	
	/*
	 * Delete file
	 */
	function delete(){
		$params	= $this->input->post();
		$this->files->delete($params);	
	}
	
	/*
	 * Upload multiple => actually this function will upload one by one :)  
	 */
	function upload_multiple(){	
		if($this->input->post()){
			$params	= $this->input->post();
			$params['title'] = $this->input->post('file_src');			
			$params['redirect'] = false;
			$this->files->add($params);
		}
		return true;
	}	
	
	/*
	 * LOAD FILES BROWSE WINDOWS
	 */
	function browse($context='browse_single'){
		
		// WE use this in order to load proper js file
		switch ($context){
			case "browse": $js_file = "browse"; break;
			case "browse_single": $js_file = "browse_single"; break;
		}
		
		$this->load->view("admin/files/browse", array("js_script"=>$js_file));
	}
	
/*
	 * Load left bar
	 */
	function browse_bar($type="title"){
		$params = array();
		$params['sortby'] = $type;
		$params['sortdir'] = 'asc';
		$collections = $this->file_collections->get_collections($params);
		$this->load->view("admin/files/browse_bar", array('collections'=>$collections,'sort_order'=>$type));
	}
	
/*
	 * Load images 
	 */
	function browse_content($page=1, $collection_id=0, $searchkey=''){
		
		$params = array();
		if($collection_id>0)
			$params['collection_id'] = $collection_id;
		if(strlen(trim($searchkey))>0)
			$params['searchkey'] = $searchkey;
					
		$params['base_url'] = site_url("admin/files/browse_content");
		$params['suffix'] = "/".$params['collection_id']."/".$params['searchkey'];
		$params['per_page'] = $this->files->count_files($params);;
		$params['cur_page'] = $page;
		$params['total_rows'] = $params['per_page'];
		$params['total_pages'] = 1;
		$params['files'] = $this->files->get_files($params);
		$params['pagination'] = $this->files->get_pagination();
		
		$this->load->view("admin/files/browse_content", array('data'=>$params));
	}
	
	function file_info($file_id=0){
		if($file_id>0){
			$file = $this->files->get_file($file_id);
			echo json_encode($file);
		}	
	}
}