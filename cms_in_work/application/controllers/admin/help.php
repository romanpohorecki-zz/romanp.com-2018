<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/AdminController.php';

class Help extends AdminController {
	
	public function __construct()
	{	
		// call parent contructor	
		parent::__construct();
	}	
	
	/**
	 * Display nice help informations
	 */
	function index($about=''){
		
		$this->carabiner->js("admin/help/help.js");

 		if(strlen(trim($about)) == 0){
 			echo "Error1";
 			die;	
 		}
 		$about_file = APPPATH."views/admin/help/".$about.".php";
 		if(is_file($about_file))
			$this->load->view("admin/help/".$about);
 		else {
 			echo "Error2";
 			die;	
 		}	
	}
}