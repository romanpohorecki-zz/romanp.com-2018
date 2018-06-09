<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class Pages_model extends MyModel{
	
	/**
	 * Add Page
	 */
	function add(&$params){
		if(!$this->add_validate($params))
			return false;
			
		// insert into pages	
		$data = array(
		   'title' 				=> $params['title'],
		   'meta_title' 		=> $params['meta_title'],
		   'meta_description' 	=> $params['meta_description'],
		   'meta_keywords' 		=> $params['meta_keywords'],
		   'template_id'		=> 1,
		   'published' 			=> 1,
		   'created'			=> time(),
		   'last_update' 		=> time()
		);
		
		$this->db->insert('pages', $data);
		$page_id = $this->db->insert_id(); 
		
		if($page_id){

			// save sections
			if(is_array($params["section"]) && !empty($params["section"])){
				$sort_order = 1;	
				foreach ($params["section"] AS $index=>$section){
					$array = array(
						"page_id"		=> $page_id,
						"text"			=> $section["text"],
						"sort_order"	=> $sort_order,
						"type"			=> $section["type"],
						"wide_image_id" => $section['wide_image_id']
					);
					$this->db->insert("page_sections", $array);
					$sort_order++;
				}
			}
			
			$this->session->set_flashdata("success", "Page successfully created.");
			redirect("admin/pages/edit/".$page_id);	
		}
		else
			 $this->add_error("Error in saving.");
		return false;
	}
	
	/**
	 * Add Page validate 
	 */
	function add_validate(&$params){
		if(!$params['title'])
			$this->add_error("Please enter Page Title.");
					
		return $this->validate();
	}
	
	/**
	 * Edit page
	 */
	function edit(&$params){
		if(!$this->edit_validate($params))
			return false;
 	
		$data = array(
		   	'title' 			=> $params['title'],
		   	'meta_title' 		=> $params['meta_title'],
		   	'meta_description' 	=> $params['meta_description'],
		   	'meta_keywords' 	=> $params['meta_keywords'],
		   	'last_update' 		=> time()
		);
		 
		$this->db->where('page_id', $params['page_id'])->update('pages', $data);

		// store removed images => will be removed bellow
		$not_removed_images = array();
				
		// store sections which should be deleted during save
		$existing_sections = array();
		$existing_sections_res = $this->db->get_where('page_sections', array('page_id'=>(int) $params['page_id']))->result_array();
		if(count($existing_sections_res)){
			foreach ($existing_sections_res AS $section){
				$existing_sections[$section['section_id']] = $section['section_id'];
			}
		}
		
		// SAVE SECTIONS		
		if(is_array($params["section"]) && !empty($params["section"])){
			$sort_order = 1;	
			foreach ($params["section"] AS $index=>$section){
				
				// update
				if($section['section_id']){
					
					// unset from existing section
					unset($existing_sections[$section['section_id']]); 
					
					$array = array(
						"text"				=> $section["text"],
						"sort_order"		=> $sort_order,
						"wide_image_id" 	=> $section['wide_image_id']
					);
					$this->db->update("page_sections", $array, array('page_id'=>$params['page_id'], 'section_id'=>$section['section_id']));
				}
				// insert 
				else {
					
					$array = array(
						"section_id"	=> $index,
						"page_id"		=> $params['page_id'],
						"text"			=> $section["text"],
						"sort_order"	=> $sort_order,
						"type"			=> $section["type"],
						"wide_image_id" => $section['wide_image_id']
					);
					$this->db->insert("page_sections", $array);					
				}
				
				// add section image in list
				if($section["wide_image_id"]){
					$not_removed_images[]= $section["image_id"];	
				}
				
				$sort_order++;
			}
		}
		/*******************************/
		
		// CLEAN UP OPERATIONS		
		
		// Remove old unused anymore sections
		if(count($existing_sections)){
			$this->db->where('page_id', $params['page_id'])->where_in('section_id', $existing_sections)->delete('page_sections');
		}
		
		// remove cached versions for deleted images
		if(count($not_removed_images)>0)
			$this->db->where_not_in('image_id', $not_removed_images);
		
		$cached_versions_res = $this->db->where(array("entity_type"=>"page", "entity_id"=>$params['page_id']))->get("images_versions");
		if($cached_versions_res->num_rows()>0){
			$cached_versions= $cached_versions_res->result_array();
			foreach ($cached_versions AS $version){
				@unlink("cache/images/".$version["image_src"]);
				$this->db->delete("images_versions", array("entity_type"=>"page", "entity_id"=>$params['page_id'], "image_id"=>$version["image_id"]));
			}			
		}
		
		return true;
	}
	
	/**
	 * Edit page validate 
	 */
	function edit_validate(&$params){
		if(!$params['page_id']){
			$this->add_error("Invalid ID.");
			return false;	
		}else {
			$result = $this->db->get_where('pages', array('page_id'=>(int) $params['page_id']));
			if($result->num_rows() !=1){
				$this->add_error("Invalid ID.");
				return false;	
			}
		}
		
		if(!$params['title'])
			$this->add_error("Please enter Page Title.");
		
		return $this->validate();
	}
		
	/**
	 * Get pages
	 */
	function get_pages($params=array()){		
		$offset = (int) ($params['cur_page']-1) * $params['per_page'];
		
		$result = $this->db->select('pages.*')							
							->order_by($params['sortby'], $params['sortdir'])
							->get('pages', $params['per_page'], $offset);
							
		$pages = $result->result_array();
		foreach($pages AS $key=>$page){
			$pages[$key]['edit_link'] = site_url("admin/pages/edit/".$page['page_id']);
			if($page['published']){
				$pages[$key]['active_class'] = "active";
				$pages[$key]['active_text'] = "Click to unpublish";
			}else {
				$pages[$key]['active_class'] = "inactive";
				$pages[$key]['active_text'] = "Click to publish";
			}
		}
		
		// Pagination
		$this->load->library('pagination');
		$this->pagination->initialize($params);
		$this->pagination = $this->pagination->create_links();		
		
		return $pages;
	}
	
	/**
	 * Get pagination =>  return pagination
	 */
	function get_pagination(){		
		return $this->pagination;		
	}
	
	/**
	 * Count pages
	 */
	function count_pages(){
		return $this->db->count_all('pages'); 	
	}
	
	/**
	 * Get Page
	 */
	function get_page($page_id=0){
		$this->load->model("images");
		$result = $this->db->where('pages.page_id', (int) $page_id)->get('pages');
		$return = array();
		if($result->num_rows()>0){
			$return = $result->row_array();
			
			// get sections
			$result = $this->db->order_by('sort_order')->get_where('page_sections', array('page_id'=>(int) $page_id));
			if($result->num_rows()>0){
				$sections = $result->result_array();
				$ret_sections = array();
				foreach ($sections AS $key=>$section){
					
					if(isset($section['wide_image_id'])){
						$image = $this->images->get_version("page", $page_id, $section['wide_image_id']);
						if(!empty($image)){
							// get image size / pixels from version image
							$file = "cache/images/".$image['image_src'];
							if(is_file($file)){
								$sizes = getimagesize($file);
								$image['pixels'] = $sizes[0]."x".$sizes[1];
									
								$sizebytes = filesize($file);
								$image['kilobytes'] = format_bytes($sizebytes);					
							}
							
							// original image => get thumbnail
							$original_image = $this->images->get_image($section['wide_image_id']);
								
							// rewrite images src
							//$image['image_src'] = base_url()."uploads/images/thumbs/".$original_image['folder']."/".$original_image['image_src'];
							$image['image_src'] = base_url().$file; // fix CMS bug!!!!
							
							$section["image"] = $image;
						}
					}
					$ret_sections[$section["sort_order"]]= $section;
				}
				$return['section'] = $ret_sections;
			}
			
			return $return;
		}	 
		else 
			return array(); 	
	}
	
	/**
	 * Delete
	 */
	function delete($page_id){
						
		// remove images versions
		$versions_res = $this->db->get_where("images_versions", array("entity_type"=>"page", "entity_id"=>$page_id));
		if($versions_res->num_rows()>0){
			$versions = $versions_res->result_array();
			foreach ($versions AS $version){
				@unlink("cache/images/".$version["image_src"]);
			}
		}
		// delete from versions			
		$this->db->where(array("entity_type"=>"page", "entity_id"=>$page_id))->delete('images_versions');
		
		// delete sections
		$this->db->where(array("page_id"=>$page_id))->delete('page_sections');
		
		// delete from pages
		$this->db->where('page_id',(int) $page_id)->delete('pages');
		
		return true;
	}	

	/**
	 * Publish/Unpublish Pages
	 */
	function publish($page_id, $new_status){
		if($new_status=="active"){
			$status = 1;
			$msg_status = 'published';
		}else {
			$status = 0;
			$msg_status = 'unpublished';	
		}
		$this->db->set('published', $status)->where('page_id', (int) $page_id)->update('pages');
		return array('success'=>'Page successfully '.$msg_status);
	}		
	
	/**
	 * Return templates list
	 */
	function get_templates(){
		$result = $this->db->get('templates');
		if($result->num_rows()>0){
			$templates = $result->result_array();
			$array = array(); 
			foreach ($templates AS $template){
				$array[$template['template_id']] = $template['name'];	
			}
			return $array; 
		}	
		else 
			return array();		
	} 
}