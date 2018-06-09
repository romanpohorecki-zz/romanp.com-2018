<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class Project_model extends MyModel{
	
	/**
	 * Add project
	 */
	function add(&$params){
		if(!$this->add_validate($params))
			return false;
			
		// insert into projects
		$data = array(
			'title' 		=> $params['title'],
			'description' 	=> $params['description'],
			'category_id' 	=> $params['category_id'],
			'sort_order' 	=> (int)$params['sort_order_project'],
			'published' 	=> 1,
		);
	
		$this->db->insert('projects', $data);
		$project_id = $this->db->insert_id();
		
		// sections
		if(count($params['section_id'])){
			foreach ($params['section_id'] AS $section_id){
								
				$sort_order = count($params['sort_order'][$section_id])>0 ? $params['sort_order'][$section_id] : null;
				
				// insert new sections
				$insert_array = array(
					'section_id'	=> $section_id,
					'project_id'	=> $project_id,
					'iframe'		=> $params['iframes'][$section_id],
					'images'		=> serialize($params['images'][$section_id]),
					'images_sort_order' => serialize($sort_order)
				);
				$this->db->insert('project_sections', $insert_array);
		
				//unset from $previous_sections
				unset($previous_sections[$section_id]);
			}
		}
		
		return $project_id ? $project_id : false;
	}
	
	/**
	 * Add project validate
	 */
	function add_validate(&$params){
		if(!$params['title'])
			$this->add_error("Please enter Title.");
			
		return $this->validate();
	}
	
	/**
	 * Edit project
	 */
	function edit(&$params){
		
		if(!$this->edit_validate($params))
			return false;
	
		$data = array(
			'title' 		=> $params['title'],
			'description' 	=> $params['description'],			
			'sort_order' 	=> (int)$params['sort_order_project']
		);
		
		$this->db->where('project_id', $params['project_id'])->update('projects', $data);
	
		
		$not_removed_images = array();
		
		// get previous sections
		$previous_sections = array();
		$previous_sections_res = $this->db->where(array("project_id"=>$params['project_id']))->get("project_sections");
		if($previous_sections_res->num_rows()>0){
			$previous_sections_res= $previous_sections_res->result_array();
			foreach ($previous_sections_res AS $sec){
				$previous_sections[$sec['section_id']] =$sec['section_id'];
			}
		}
		// delete old section 
		$this->db->delete('project_sections', array('project_id'=>$params['project_id']));
		
		// sections
		if(count($params['section_id'])){
			foreach ($params['section_id'] AS $section_id){
				
				$images = count($params['images'][$section_id])>0 ? $params['images'][$section_id] : null;    	
				$sort_order = count($params['sort_order'][$section_id])>0 ? $params['sort_order'][$section_id] : null;    	
				
				// insert new sections
				$insert_array = array(
					'section_id'	=> $section_id,
					'project_id'	=> $params['project_id'],
					'iframe'		=> $params['iframes'][$section_id],
					'images'		=> serialize($images),								
					'images_sort_order' => serialize($sort_order)								
				);
				$this->db->insert('project_sections', $insert_array);
				
				//unset from $previous_sections
				unset($previous_sections[$section_id]);
			}
		}
		
		// remove cached versions for deleted images
		if(count($not_removed_images)>0)
			$this->db->where_not_in('image_id', $not_removed_images);
	
		$cached_versions_res = $this->db->where(array("entity_type"=>"project", "entity_id"=>$params['project_id']))->get("images_versions");
		if($cached_versions_res->num_rows()>0){
			$cached_versions= $cached_versions_res->result_array();
			foreach ($cached_versions AS $version){
				@unlink("cache/images/".$version["image_src"]);
				$this->db->delete("images_versions", array("entity_type"=>"project", "entity_id"=>$params['project_id'], "image_id"=>$version["image_id"]));
			}
		}
		
		
		// remove images from sections => if there are key in $previous_sections => then the section was removed and we need to clear version images
		if(count($previous_sections)){
			foreach ($previous_sections AS $section){
				$cached_versions_res = $this->db->where(array("entity_type"=>$section, "entity_id"=>0))->get("images_versions");
				if($cached_versions_res->num_rows()>0){
					$cached_versions= $cached_versions_res->result_array();
					foreach ($cached_versions AS $version){
						@unlink("cache/images/".$version["image_src"]);
						$this->db->delete("images_versions", array("entity_type"=>$section, "entity_id"=>0, "image_id"=>$version["image_id"]));
					}
				}	
			}
		}
		
	
		return true;
	}
	
	/**
	 * Edit projects validate
	 */
	function edit_validate(&$params){
		
		if(!$params['project_id']){
			$this->add_error("Invalid ID.");
			return false;
		}else {
			$result = $this->db->get_where('projects', array('project_id'=>(int) $params['project_id']));
			if($result->num_rows() !=1){
				$this->add_error("Invalid ID.");
				return false;
			}
		}
	
		if(!$params['title'])
			$this->add_error("Please enter Title.");
			
		return $this->validate();
	}
	
	
	/**
	 * Get project detail
	 */
	function get_project($project_id=0){
	
		$this->load->model("admin/images_model", "images");
		$result = $this->db->get_where('projects', array('project_id'=>(int) $project_id));
		$return = array();
		if($result->num_rows()>0){
			$return = $result->row_array();
			
			// get sections			
			$sections_res = $this->db->where(array("project_id"=>$project_id))->get("project_sections");
			if($sections_res->num_rows()>0){
				$sections= $sections_res->result_array();
				foreach ($sections AS $key=>$section){
					$images = unserialize($section['images']);
					unset($sections[$key]['images']);
					
					if(is_array($images) && count($images)){
						foreach ($images AS $skey => $image_id){

							$version_image = $this->images->get_version($section['section_id'], 0, $image_id);
							if(!empty($version_image)){
									
								// get image size / pixels from version image
								$file = "cache/images/".$version_image['image_src'];
								if(is_file($file)){
									$sizes = getimagesize($file);
									$version_image['pixels'] = $sizes[0]."x".$sizes[1];
										
									$sizebytes = filesize($file);
									$version_image['kilobytes'] = format_bytes($sizebytes);
										
									$version_image['image_src'] = base_url().$file;
								}	
							}	
							$images[$skey] = $version_image;
						}
						$sections[$key]['images'] = $images;
					}
				}
				$return['sections'] = $sections;
			}
						
			return $return;
		}
		else
			return array();
	}
	
	/**
	 * Get projects
	 */
	function get_projects($params=array()){
		$this->load->model("admin/images_model", "images");
		
		$result = $this->db->select('projects.*')
							->where('category_id', $params['category_id'])							
							->order_by($params['sortby'], $params['sortdir'])
							->get('projects');
							
		$projects = $result->result_array();
		foreach($projects AS $key=>$project){
			if($project['published']){
				$projects[$key]['active_class'] = "active";
				$projects[$key]['active_text'] = "Click to unpublish";
			}else {
				$projects[$key]['active_class'] = "inactive";
				$projects[$key]['active_text'] = "Click to publish";
			}
		}
		
		// Pagination
		$this->load->library('pagination');
		$this->pagination->initialize($params);
		$this->pagination = $this->pagination->create_links();		
		
		return $projects;
	}
	

	/**
	 * Get pagination =>  return pagination
	 */
	function get_pagination(){
		return $this->pagination;
	}
	
	/**
	 * Count all projects
	 */
	function count_projects($params=array()){
		$this->db->from('projects');
		$this->db->where('category_id', $params['category_id']);
		return $this->db->count_all_results();
	}
	
	/**
	 * Delete projects
	 */
	function delete($project_id){
	
		// remove images versions
		$versions_res = $this->db->get_where("images_versions", array("entity_type"=>"project", "entity_id"=>$project_id));
		if($versions_res->num_rows()>0){
			$versions = $versions_res->result_array();
			foreach ($versions AS $version){
				@unlink("cache/images/".$version["image_src"]);
			}
		}
		// delete from versions
		$this->db->where(array("entity_type"=>"project", "entity_id"=>$project_id))->delete('images_versions');
		
				
		// delete section images		
		$sections_res = $this->db->where(array("project_id"=>$project_id))->get("project_sections");
		if($sections_res->num_rows()>0){
			$sections= $sections_res->result_array();
			foreach ($sections AS $section){
				$cached_versions_res = $this->db->where(array("entity_type"=>$section['section_id'], "entity_id"=>0))->get("images_versions");
				if($cached_versions_res->num_rows()>0){
					$cached_versions= $cached_versions_res->result_array();
					foreach ($cached_versions AS $version){
						@unlink("cache/images/".$version["image_src"]);
						$this->db->delete("images_versions", array("entity_type"=>$section['section_id'], "entity_id"=>0, "image_id"=>$version["image_id"]));
					}
				}
			}
		}
				
		
		// delete sections
		$this->db->where('project_id',(int) $project_id)->delete('project_sections');
	
		// delete from pages
		$this->db->where('project_id',(int) $project_id)->delete('projects');
	
		return true;
	}
	
	/**
	 * Publish/Unpublish Pages
	 */
	function publish($project_id, $new_status){
		if($new_status=="active"){
			$status = 1;
			$msg_status = 'published';
		}else {
			$status = 0;
			$msg_status = 'unpublished';
		}
		$this->db->set('published', $status)->where('project_id', (int) $project_id)->update('projects');
		return array('success'=>'Project was '.$msg_status);
	}
	
	/**
	 * Function used to save projects order
	 */
	function order_projects($params){	
		foreach ($params['positions'] AS $index=>$id){
			$data = array(
				'sort_order' => $index+1
			);
			$this->db->where('project_id', $id);
			$this->db->update('projects', $data);
		}
	}
}