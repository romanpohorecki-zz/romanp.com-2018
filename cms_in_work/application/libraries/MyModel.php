<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MyModel extends CI_Model{
    
	public $errors = array();
	private $pagination = '';
	public $language_prefix = "";
	
	/** 
	* Class Constructor
	*/
	public function __construct()
	{		
		parent::__construct();
		
		$language = $this->session->userdata("language"); 
		if(empty($language) || $language == "en")
			$this->language_prefix  = "en_";
		elseif($language == "es")
			$this->language_prefix  = "es_";
	}
	
	/**
	 * Add error
	 */
	function add_error($error){
		$this->errors[] = $error;	
	}
	
	/**
	 * Get errors
	 */
	function get_errors(){
		return $this->errors;
	}
	
	/**
	 * Validate function
	 * @return false if processing errors occured / true otherwise.
	 */
	function validate(){
		return count($this->errors) > 0 ? false : true;		
	}
}