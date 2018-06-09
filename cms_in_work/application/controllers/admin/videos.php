<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/AdminController.php';

class Videos extends AdminController {
	
	public function __construct()
	{	
		// call parent contructor	
		parent::__construct();
		$this->load->model("admin/videos_model", "videos");
		$this->load->model("admin/video_collections_model", "collections");
	}	

	/**
	 * COLLECTIONS
	 */
	function index(){
		$this->collections();
	}
	
	function collections($sortby='title', $sortdir='asc'){
		
		$params = array();
		
		//$this->carabiner->js("admin/videos/collections.js");
		
		if($this->session->flashdata('success')){
			$params['success'] = $this->session->flashdata('success');
		}
		
		$params['sortby'] = $sortby;
		$params['sortdir'] = $sortdir;
		$params['sortdir'] == 'asc' ? $params['nextsortdir'] = 'desc' : $params['nextsortdir'] = 'asc';
		
		// get collections
		$params['collections'] = $this->collections->get_collections($params);
		
		// BreadCrumbs
		$breadcrumbs = array("Videos"=>'');
		
		$this->template->write('meta_title',$this->config->item('website')." - Media", true);
		$this->template->write_view('content', 'admin/videos/collections.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));
		$this->template->render();
	}
		
	function collection_add(){
		
		$this->carabiner->js("admin/videos/collection_add.js");
		
		$params = array();
		if($this->input->post()){
			$params	= $this->input->post();
			if(!$this->collections->add($params))
				$params['error'] = $this->collections->get_errors();
			else{	
				$params['success'] = true;
				$this->session->set_flashdata("success", "Collection successfully created.");
			}	
		}
				
		$this->load->view("admin/videos/collection_add", array("data"=>$params));			
	}	
	
	
	
	function collection_delete($collection_id=0){
		
		if($this->collections->delete($collection_id))
			$this->session->set_flashdata("success", "Collection successfully deleted.");
		
		redirect('admin/videos/collections');
	}

	function collection($collection_id=0){
 		
		$this->carabiner->js("libs/uploadify/swfobject.js");
		$this->carabiner->js("libs/uploadify/jquery.uploadify.v2.1.4.min.js");
		$this->carabiner->css("libs/uploadify.css");
		
		$params = array();
		
		if($this->session->flashdata('success'))
			$params['success'] = $this->session->flashdata('success');
		
		$collection = $this->collections->get_collection($collection_id);
		if(!$collection['title'])
			redirect("admin/videos/collections");
						
		$this->carabiner->js("admin/videos/collection.js");		
		
		if($this->input->post()){
			$params	= $this->input->post();
			if(!$this->collections->edit($params))
				$params['error'] = $this->collections->get_errors();
		}else {
			$params['collection_name'] = $collection['title'];			
		}
		
		$params['collection_id'] = $collection_id;			
		
		// BreadCrumbs
		$breadcrumbs = array("Videos"=>site_url("admin/videos/collections"), $collection['title']=>'');
		
		$this->template->write('meta_title',$this->config->item('website')." - Media", true);
		$this->template->write_view('content', 'admin/videos/collection.php', array('data'=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));		
		$this->template->render();	
	}
	

	/*
	 * Video upload function
	 */
	function video($collection_id=0){
		
		// upload error
		if ($_FILES['file']['error']) {
			echo json_encode(array('success'=>0, 'msg'=>'Upload error'));
			exit();
		}
		
		// file type validation
		$mimetypes = array('video/mov', 'video/flv', 'video/avi', 'video/mp4', 'video/mpeg', 'video/mpg');
		if (!in_array($_FILES['file']['type'], $mimetypes)) {
			echo json_encode(array('success'=>0, 'msg'=>'Invalid file type'));
			exit();
		}
		
		// load collection
		$collection = $this->collections->get_collection($collection_id);
		if(empty($collection)){
			echo json_encode(array('success'=>0, 'msg'=>'Invalid collection'));
			exit();
		}	
		
		$file_name = substr($_FILES['file']['name'], 0, strripos($_FILES['file']['name'], "."));
		$ext = substr($_FILES['file']['name'], strripos($_FILES['file']['name'], ".")+1);
		
		$filename = $file_name.'_'.time().'.'.$ext;
		
		$tmp_file = $_FILES['file']['tmp_name'];
		$to_file = 'uploads/videos/'.$collection['folder'].'/'.$filename;
		
		if(move_uploaded_file($tmp_file, $to_file)){
			
			// store to DB
			$params	= array(
				'title'			=> $_FILES['file']['name'],
				'collection_id'	=> $collection_id,
				'filename'		=> $filename,
				'filetype'		=> $_FILES['file']['type']
			);
			
			if(!$this->videos->add($params)){
				echo json_encode(array('success'=>0, 'msg'=>'Upload error3'));
				exit();
			}
			
			
			echo json_encode(array('success'=>1, 'msg'=>'done'));
			exit();
		}
		else {
			echo json_encode(array('success'=>0, 'msg'=>'Upload error3'));
			exit();
		}
	}
	
	/*
	 * Return list of videos
	 */
	function get_videos($collection_id=0,$page=1){
		
		$params = $this->input->post();
		$params['collection_id'] = $collection_id;
		$params['base_url'] = site_url("admin/videos/get_videos/".$collection_id."/");
		$params['per_page'] = 1111111;
		$params['cur_page'] = $page;
		$params['uri_segment'] = 5;
		$params['total_rows'] = $this->videos->count_videos($params);
		$params['total_pages'] = ceil($params['total_rows']/$params['per_page']);		
		$params['videos'] = $this->videos->get_videos($params);
		$params['pagination'] = $this->videos->get_pagination();
				
		$this->load->view("admin/videos/videos", array("data"=>$params));
	}
	
	/**
	 * Play video into a popup
	 */
	function play($video_id=null){
		$this->carabiner->js("admin/videos/video.js");
		$video = $this->videos->get_video($video_id);
		$this->load->view("admin/videos/play", array("data"=>$video));
	}
	
	/*
	 * Return informations about a video
	 */
	function info(){
		$video_id = $this->input->post('video_id');
		$response = $this->videos->get_video($video_id);
		echo json_encode($response);
	}
	
	/*
	 * Delete video
	 */
	function delete(){
		$params	= $this->input->post();
		$this->videos->delete($params);	
	}
}