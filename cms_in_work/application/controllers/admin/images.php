<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/AdminController.php';

class Images extends AdminController {
	
	public function __construct()
	{	
		// call parent contructor	
		parent::__construct();
		$this->load->model("admin/images_model", "images");
		$this->load->model("admin/collections_model", "collections");
	}	

	/**
	 * COLLECTIONS
	 */
	function index(){
		$this->collections();
	}
		
	function collection_add(){
		
		$this->carabiner->js("admin/images/collection_add.js");
		
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
				
		$this->load->view("admin/images/collection_add", array("data"=>$params));			
	}	
	
	function collections($sortby='title', $sortdir='asc'){
		
		$params = array();
		
		$this->carabiner->js("admin/images/collections.js");
		
		if($this->session->flashdata('success')){
			$params['success'] = $this->session->flashdata('success');
		}	
			
		$params['sortby'] = $sortby;	
		$params['sortdir'] = $sortdir;	
		$params['sortdir'] == 'asc' ? $params['nextsortdir'] = 'desc' : $params['nextsortdir'] = 'asc';	
			
		// get collections
		$params['collections'] = $this->collections->get_collections($params);
			
		// BreadCrumbs
		$breadcrumbs = array("Images"=>'');
		
		$this->template->write('meta_title',$this->config->item('website')." - Media", true);
		$this->template->write_view('content', 'admin/images/collections.php', array("data"=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));		
		$this->template->render();
	}
	
	function collection_delete($collection_id=0){
		
		if($this->collections->delete($collection_id))
			$this->session->set_flashdata("success", "Collection successfully deleted.");
		
		redirect('admin/images/collections');
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
			redirect("admin/images/collections");
						
		$this->carabiner->js("admin/images/collection.js");		
		
		if($this->input->post()){
			$params	= $this->input->post();
			if(!$this->collections->edit($params))
				$params['error'] = $this->collections->get_errors();
		}else {
			$params['collection_name'] = $collection['title'];			
		}
		
		$params['collection_id'] = $collection_id;			
		
		// BreadCrumbs
		$breadcrumbs = array("Images"=>site_url("admin/images/collections"), $collection['title']=>'');
		
		$this->template->write('meta_title',$this->config->item('website')." - Media", true);
		$this->template->write_view('content', 'admin/images/collection.php', array('data'=>$params, "breadcrumbs"=>get_breadcrumbs($breadcrumbs)));		
		$this->template->render();	
	}
	
	function download_collection($collection_id=0){
		
		$collection = $this->collections->get_collection($collection_id);
		if(!$collection['title'])
			redirect("admin/images/collections");
			
		$this->load->library('zip');	
		$this->load->library('MY_Zip');	
		
		$path = 'uploads/images/originals/'.$collection['folder']."/";
		$this->zip->get_files_from_folder($path, $folder_in_zip);
		$this->zip->download($collection['folder'].'.zip');
	}
	/***************************************/
	

	/*
	 * Image functions
	 */
	function image($image_id=0, $collection_id=0){
		
		$this->carabiner->js("admin/images/image.js");
		$this->carabiner->js("libs/uploadify/swfobject.js");
		$this->carabiner->js("libs/uploadify/jquery.uploadify.v2.1.4.min.js");
		$this->carabiner->css("libs/uploadify.css");
		
		$params = array();
		
		if($this->input->post()){
			$params	= $this->input->post();
			if(!$image_id){
				if(!$this->images->add($params)){
					$params['error'] = $this->images->get_errors();
					if($params['image_src'])
						$params['image'] = base_url()."uploads/images/tmp/".$params['image_src'];
				}		
			}
			else {
				$params['image_id'] = $image_id;
				if(!$this->images->edit($params)){
					$params['error'] = $this->images->get_errors();
					if($params['image_src'])
						$params['image'] = base_url()."uploads/images/tmp/".$params['image_src'];
				}	
			}
		}
		
		// UPDATE
		if($image_id>0){
			$image = $this->images->get_image($image_id);
			
			if(!$image['image_id'])
				redirect("admin/images/image");
				
			$params['title'] = $image['title'];
			$params['caption'] = $image['caption'];
			$params['url'] = $image['url'];
			$params['image_id'] = $image_id;

			if(!$params['image'])
				$params['image'] = base_url()."uploads/images/originals/".$image['folder']."/".$image['image_src'];
			
			if($this->session->flashdata('success'))
				$params['success'] = $this->session->flashdata('success');
				
			$params['page_title'] = 'Edit Image';	
		}
		// New
		else {
			$params['page_title'] = 'Upload New Image';
			if(!$params['image'])
				$params['image'] = base_url()."assets/images/admin/default.png";
		}
		
		if(!$params['collection_id'])
			$params['collection_id'] = $collection_id;
			
		// Collection $collection_id present ??? for Page Add Image feature	
		if($collection_id)
			$params['collection_id_present'] = $collection_id;
		else 
			$params['collection_id_present'] = 0;
			
		// collection DD	
		$collections = $this->collections->get_collections();
		$params['collections'] = array();
		foreach ($collections AS $collection){
			$params['collections'][$collection['collection_id']] = $collection['title'];	
		}	
				
		$this->load->view("admin/images/image", array("data"=>$params));			
	}
	
	/*
	 * Return list of images
	 */
	function get_images($collection_id=0,$page=1){
		
		$params = $this->input->post();
		$params['collection_id'] = $collection_id;
		$params['base_url'] = site_url("admin/images/get_images/".$collection_id."/");
		$params['per_page'] = 1111111;
		$params['cur_page'] = $page;
		$params['uri_segment'] = 5;
		$params['total_rows'] = $this->images->count_images($params);
		$params['total_pages'] = ceil($params['total_rows']/$params['per_page']);		
		$params['images'] = $this->images->get_images($params);
		$params['pagination'] = $this->images->get_pagination();
		
		$this->load->view("admin/images/images", array("data"=>$params));
	}
	
	/*
	 * Return informations about a image
	 */
	function info(){
		$image_id = $this->input->post('image_id');
		$response = $this->images->get_image($image_id);
		echo json_encode($response);
	}
	
	/*
	 * Delete image
	 */
	function delete(){
		$params	= $this->input->post();
		$this->images->delete($params);	
	}
	
	/*
	 * Upload multiple => actually this function will upload one by one :)  
	 */
	function upload_multiple(){	
		if($this->input->post()){
			$params	= $this->input->post();
			$params['title'] = $this->input->post('image_src');			
			$params['redirect'] = false;
			$this->images->add($params);
		}
		return true;
	}
	/****************************/
	
	
	/*
	 * LOAD FILES BROWSE WINDOWS
	*/
	function browse(){
		$collections = $this->collections->get_collections();
		
		$this->load->view("admin/images/browse", array('collections'=>$collections));
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
					
		$params['base_url'] = site_url("admin/images/browse_content");
		$params['suffix'] = "/".$params['collection_id']."/".$params['searchkey'];
		$params['per_page'] = $this->images->count_images($params);;
		$params['cur_page'] = $page;
		$params['total_rows'] = $this->images->count_images($params);
		if($params['per_page']>0)
			$params['total_pages'] = ceil($params['total_rows']/$params['per_page']);
		else
			$params['total_pages'] = 0;
		$params['images'] = $this->images->get_images($params);
		//$params['pagination'] = $this->images->get_pagination();
		
		$this->load->view("admin/images/browse_content", array('data'=>$params));
	}
	
	
	// load content from a image loaded by Ajax
	function grab_ajax_image($image_id=0, $next_id=0){
		$image = $this->images->get_image($image_id);
		echo '<a class="removeImage" href="javascript:deleteImage('.$next_id.')"></a><div class="imageContainer"><img title="'.$image['title'].'" alt="'.$image['title'].'" src="'.base_url().'uploads/images/thumbs/'.$image['folder'].'/'.$image['image_src'].'" class="img"></div><div class="imageTitle">'.$image['title'].'</div><div class="actionButtons"><a rel="shadowbox;player=iframe;width=770;height=500" rev="'.$next_id.'" class="editButton" href="'.base_url().'/admin/images/image/'.$image_id.'/'.$image['collection_id'].'">Edit</a></div>';		
	}
	
	// return thumbnail source as JSON
	function get_thumbnail_source($image_id=0){
		$image = $this->images->get_image($image_id);
		echo json_encode(array("source"=> base_url()."uploads/images/thumbs/".$image['folder']."/".$image["image_src"], "title"=>$image['title']));
		die;		
	}
	
	
	
	// Create a new set of images / overwrite old one
	function create_images(){
		
		ini_set("max_execution_time", 0);
		ini_set("memory_limit", '64M');
		
		// grab all images
		$images_res = $this->db->get("images");
		$images = $images_res->result_array();
		
		// load resize library
		$this->load->library('image_lib');
				
		foreach ($images AS $image){
			
			$image_original = "uploads/images/".$image['folder']."/".$image['image_src'];
			$new_file = "uploads/images/mobile_images/".$image['folder']."/".$image['image_src'];
			@copy($image_original, $new_file);
			
			$config = array();
			$config['image_library'] = 'gd2';
			$config['source_image'] = $new_file;
			$config['create_thumb'] = TRUE;
			$config['maintain_ratio'] = TRUE;
			$config['width'] = 480;
			$config['height'] = '';
			$config['quality'] = '95%';
			$config['thumb_marker'] = "";
			// reinit library
			$this->image_lib->initialize($config);
			
			// determine new file dimensions
			list($new_file_width, $new_file_height) = getimagesize($new_file);
			// chekc if resize is needed 
			if($new_file_width > 480)
				$this->image_lib->resize();
			
			// set permissions
			@chmod($new_file, 0755); 
		}
		
		die("End!");	
	}
	
	
	/**
	 * Copy image to cache
	 */
	function copy_to_cache(){
		
		$params = $this->input->post();
		
		if(!$params["entity_id"]){
			
			switch($params["entity_type"]){
				case "page"		:	$tablename = "pages"; break;
				case "member"	:	$tablename = "members"; break;
				case "news"		:	$tablename = "news"; break;
				case "channel"	:	$tablename = "channels"; break;
				case "project"	:	$tablename = "projects"; break;
			}
			
			if(isset($tablename)){		
				$id_res = $this->db->query("SELECT AUTO_INCREMENT FROM information_schema.tables WHERE TABLE_NAME='".$tablename."' AND TABLE_SCHEMA='".$this->db->database."'");
				if($id_res->num_rows()==1){
					$id_arr = $id_res->row_array();
					$params["entity_id"] = $id_arr["AUTO_INCREMENT"];	
				}
				else {
					echo "Error: Unable to generate new ID";
					exit();
				}
			}
			// allow ID =0 
			/*else {
				echo "Error: Unable to generate new ID";
				exit();
			}*/
		}
		
		$response = $this->images->copy_to_cache($params);
		if(is_array($response)){
						
			// append display_mode_html
			$response['display_mode_html'] = generate_dd('display_mode', get_images_display_modes(), $response["display_mode"], '', 'Image Class');		
			
			echo json_encode($response);
			exit();
		}
	}	
	
	
	
	/**
	 * Crop/edit images
	 */
	function crop_edit($entity_type="", $entity_id=0, $image_id=0){
		
		$this->carabiner->js("admin/images/crop_edit.js");
		$this->carabiner->js("libs/jquery.imgareaselect.js");
		$this->carabiner->css("libs/imgareaselect-default.css");
		
		$version = $this->images->get_version($entity_type, $entity_id, $image_id);
		if(empty($version))
			die("Image not exist.");
			
		/*if($this->input->post()!=array()){
			$this->images->save_caption($entity_type, $entity_id, $image_id);
			$version["caption"] = $this->input->post("caption");
			$version["success"] = "Caption saved.";
		}
		*/
		
		$this->load->model("admin/crops_model", "crops");
		$crops = $this->crops->get_dd_crops();	
		$crops_arr = $this->crops->get_crops();	
		
		$this->load->view("admin/images/crop_edit", array("data"=>$version, "crops"=>$crops, "crops_arr"=>$crops_arr));
	}
	
	function crop(){
		
		$this->images->crop($this->input->post());
				
		echo json_encode(array("success"=>true));
		exit();
	}
	
	function revert(){
		$this->images->copy_to_cache($this->input->post());
		echo json_encode(array("success"=>true));
		exit();
	}
	
	function save_version_caption(){
		$this->images->save_version_caption($this->input->post());
		echo json_encode(array("success"=>true));
		exit();
	}
	
	function save_version_url(){
		$this->images->save_version_url($this->input->post());
		echo json_encode(array("success"=>true));
		exit();
	}
	
	function save_display_mode(){
		$this->images->save_display_mode($this->input->post());
		echo json_encode(array("success"=>true));
		exit();
	}	
}