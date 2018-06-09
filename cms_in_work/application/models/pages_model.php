<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class Pages_model extends MyModel{
	
	/**
	 * Return homepage ID
	 */
	function get_homepage(){
		$result = $this->db->get_where('pages', array('homepage'=>1));
		$result = $result->row_array();
		return $result['page_id'];
	}
	
	/**
	 * Get Page
	 */
	function get_page($page_id=0){
		$result = $this->db->get_where('pages', array('page_id'=>(int) $page_id));
		$return = array();
		if($result->num_rows()>0){
			$return = $result->row_array();
						
			// get sections
			$results = $this->db->order_by('sort_order', 'ASC')->get_where('page_sections', array('page_id'=>(int) $page_id));
			if($results->num_rows()>0){
				
				$return['sections'] = array();
				$sections = $results->result_array();
				
				foreach ($sections AS $section){
					
					// language
					$section["text"] = $section["text"];
					
					// image set
					if(isset($section['wide_image_id'])){
						$image = $this->images->get_version("page", $page_id, $section['wide_image_id']);
						if(!empty($image)){
							$section["image_id"] = $image["image_id"];
							$section["image_src"] = base_url()."cache/images/".$image["image_src"];
							$section["caption"] = $image["caption"];
						}
					}
					
					$return['sections'][] = $section;
				}
			}
			
			return $return;
		}	 
		else 
			return array(); 	
	}

	/**
	 * Get template
	 */
	function get_template($template_id=0){
		$result = $this->db->get_where('templates', array('template_id'=>(int) $template_id));
		if($result->num_rows()>0)
			return $result->row_array();
		else 
			return array();		
	}
	
		
	function getTweets(){
		
		die;
		
		// try to take from database
		$result = $this->db->get_where("twitter_feeds", array("name"=>"feed"))->row_array();
		if($result["request_time"] + 60*5 > time()){
			return json_decode($result["value"]);
		}
		
		require_once(APPPATH.'libraries/TwitterAPIExchange.php');			
		$settings = array(
		    'oauth_access_token' => "1553908370-KueNqFPcYQKnCKXz69dvxQXF7HOgulm8yRiXDt2",
		    'oauth_access_token_secret' => "FwglZe2yHn4Dje9ufZYn831ttvtgOd6waNQfO0xtUi0IR",
		    'consumer_key' => "FgizNf2upO1OKqwSbJNCAC2Hp",
		    'consumer_secret' => "44nDpKj8u1LkIkv5vkLWuNszCyHPVmZy4o6S5BXqK3tuTpu7Yg"
		);
			
		$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
		$getfield = '?screen_name=PSGFILMS&count=50&include_entities=true&include_rts=true&exclude_replies=true';
		$requestMethod = 'GET';
		$twitter = new TwitterAPIExchange($settings);
		$response =  $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
		
		// update database
		$this->db->update("twitter_feeds", array("value"=>$response, "request_time"=>time()), array("name"=>"feed"));
		return json_decode($response);
	}
	
}