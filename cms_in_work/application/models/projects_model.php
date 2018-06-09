<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class Projects_model extends MyModel{
	
	
	/**
	 * Get projects
	 */
	function get_first_projects($params=array()){
	
		$return = array();
		
		// get main categories 
		$categories_res = $this->db->order_by('sort_order', 'ASC')->where('published', 1)->get('categories');
		$categories = $categories_res->result_array();
		if(count($categories)){
			foreach ($categories AS $id=>$category){
				// load first project 
				$first_project = $this->db->order_by('sort_order', 'ASC')->where('published', 1)->where('category_id', $category['category_id'])->get('projects')->row_array();
				if(!empty($first_project)){										
					// load project images
					$section = $this->db->where('project_id', $first_project['project_id'])->get('project_sections')->row_array(); // get first result
					if(!empty($section)){
						$images = unserialize($section['images']);
						if(count($images)){
							$tmp_images = array();
							foreach ($images AS $skey => $image_id){						
								$version_image = $this->images->get_version($section['section_id'], 0, $image_id);
								if(!empty($version_image)){
									$version_image['image_src'] = base_url()."cache/images/".$version_image['image_src'];
								}
								$tmp_images[]= $version_image;
							}
							
							// add category to return array
							$category['project'] = $first_project;
							$category['images'] = $tmp_images;
							$return[]=$category;
						}
					}
				}		
			}
		}
		
		return $return;
	}
	
	
	/**
	 * Get all projects from a given category
	 */
	function get_category_projects($category_id=0){
	
		$return = array();
	
		// get main categories
		$projects_res = $this->db->order_by('sort_order', 'ASC')->where('published', 1)->where('category_id', $category_id)->get('projects');
		$projects = $projects_res->result_array();
		if(count($projects)){
			foreach ($projects AS $id=>$project){				
				// load project images
				$section = $this->db->where('project_id', $project['project_id'])->get('project_sections')->row_array(); // get first result
				if(!empty($section)){
					$images = unserialize($section['images']);
					if(count($images)){
						$tmp_images = array();
						foreach ($images AS $skey => $image_id){
							$version_image = $this->images->get_version($section['section_id'], 0, $image_id);
							if(!empty($version_image)){
								$version_image['image_src'] = base_url()."cache/images/".$version_image['image_src'];
							}
							$tmp_images[]= $version_image;
						}
							
						// add category to return array
						
						$project['images'] = $tmp_images;
						$return[]=$project;
					}
				}
				
			}
		}
	
		return $return;
	}
}