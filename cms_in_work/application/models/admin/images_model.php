<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH.'libraries/MyModel.php';

class Images_model extends MyModel{
	
	/**
	 * Add new Image
	 */
	function add($params){
		if(!$this->add_validate($params))
			return false;
			
		// insert into collections	
		$data = array(
		   	'title' 		=> $params['title'],
		   	'caption'	 	=> $params['caption'],
		   	'url'	 		=> $params['url'],
		   	'collection_id'	=> $params['collection_id'],
		   	'folder'		=> $params['folder'],
			'created'		=> time()
		);
		
		$this->db->insert('images', $data);
		$image_id = $this->db->insert_id(); 
			
		if($image_id){
			
			ini_set("max_execution_time", 0);
			ini_set("memory_limit", '256M');
			
			// get extension
			$ext = substr($params['image_src'], strrpos($params['image_src'], '.')); 
			$file_name = substr($params['image_src'], 0, strrpos($params['image_src'], '.'));
			$file_name = url_title(trim($file_name), 'underscore', TRUE);
			
			$img_name = $file_name."_".$image_id.$ext;
			$image_name = $img_name; // save name in database
			$img_name = $params['folder']."/".$img_name; // add image to directory
			
			$file = "uploads/images/tmp/".$params['image_src'];
			$new_file = "uploads/images/originals/".$img_name;
			$thumbfile = "uploads/images/thumbs/".$img_name;
			
			@copy($file, $new_file); // create regular copy
			@copy($file, $thumbfile); // create copy used for thumbnails
			@unlink($file); // unlink uploaded file (tmp)
			
			// resize check / aspect ration
			$do_resize = false;
			list($thumb_width, $thumb_height) = getimagesize($thumbfile);
			$ratio = $thumb_width / $thumb_height;
			if($thumb_width>200){
				$thumb_width = 200;
				$thumb_height = $thumb_height * $ratio;
				$do_resize = true;
			}
			
			$config['image_library'] = 'gd2';
			$config['source_image'] = $thumbfile;
			$config['create_thumb'] = TRUE;
			$config['maintain_ratio'] = TRUE;
			$config['width'] = $thumb_width;
			$config['height'] = $thumb_height;
			$config['quality'] = '90%';
			$config['thumb_marker'] = "";
			$this->load->library('image_lib', $config);

			
			if($do_resize)
				$this->image_lib->resize();
			
			// set permissions
			@chmod($new_file, 0755); 
			@chmod($thumbfile, 0755);
			
			$this->db->where('image_id', $image_id)->update('images', array('image_src'=>$image_name)); // save his name to database
			
			if($params['redirect'] !== false){
				$this->session->set_flashdata("success", "Image successfully uploaded.");
				redirect("admin/images/image/".$image_id."/".$params['collection_id']);	
			}	
		}	
		else
			 $this->add_error("Error in saving.");
		return false;		
	}
	
	/**
	 * Add Image validate 
	 */
	function add_validate(&$params){
		
		if(!$params['collection_id'])
			$this->add_error("Please Select Collection.");
		else {
			$result = $this->db->get_where('collections', array('collection_id'=>(int) $params['collection_id']));
			if($result->num_rows() !=1){
				$this->add_error("Invalid Collection.");
				return false;	
			}
			else {
				$result = $result->row_array();
				$params['folder'] = $result['folder'];					
			}	
		}		
		if(!$params['image_src'])
			$this->add_error("Please upload a file.");	
		return $this->validate();
	}
	
	/**
	 * Edit Image
	 */
	function edit($params){
		if(!$this->edit_validate($params))
			return false;
			
		// update collections	
		$data = array(
		   'title' 			=> $params['title'],
		   'caption'	 	=> $params['caption'],
		   'url'	 		=> $params['url']
		);
		
		$this->db->where('image_id', $params['image_id'])->update('images', $data);
		
		// image move / rename
		if(strlen($params['image_src'])>0){
			
			ini_set("max_execution_time", 0);
			ini_set("memory_limit", '256M');
			
			// get extension
			$ext = substr($params['image_src'], strrpos($params['image_src'], '.')); 
			$file_name = substr($params['image_src'], 0, strrpos($params['image_src'], '.'));
			$file_name = url_title(trim($file_name), 'underscore', TRUE);
			
			$img_name = $file_name."_".$params['image_id'].$ext;
			$image_name = $img_name; // save his name in database
			$img_name = $params['folder']."/".$img_name; // add image to his directory
						
			$file = "uploads/images/tmp/".$params['image_src'];
			$new_file = "uploads/images/originals/".$img_name;
			$thumbfile = "uploads/images/thumbs/".$img_name;
			
			@copy($file, $new_file); 
			@copy($file, $thumbfile); // create copy used for thumbnails
			@unlink($file); // remove tmp image 
			
			// old images
			$old_file = "uploads/images/originals/".$params["current_image"]; // previous image 
			$old_thumb = "uploads/images/thumbs/".$params["current_image"]; // previous thumb
			@unlink($old_file); // remove previous uploaded image 
			@unlink($old_thumb); // remove previous uploaded thumb
			
			
			// resize check / aspect ration
			$do_resize = false;
			list($thumb_width, $thumb_height) = getimagesize($thumbfile);
			$ratio = $thumb_width / $thumb_height;
			if($thumb_width>200){
				$thumb_width = 200;
				$thumb_height = $thumb_height * $ratio;
				$do_resize = true;
			}
			
			$config['image_library'] = 'gd2';
			$config['source_image'] = $thumbfile;
			$config['create_thumb'] = TRUE;
			$config['maintain_ratio'] = TRUE;
			$config['width'] = $thumb_width;
			$config['height'] = $thumb_height;
			$config['quality'] = '97%';
			$config['thumb_marker'] = "";
			$this->load->library('image_lib', $config);
			
			if($do_resize)
				$this->image_lib->resize();
						
			// set permissions
			@chmod($new_file, 0755); 
			@chmod($thumbfile, 0755);
			
			$this->db->where('image_id', $params['image_id'])->update('images', array('image_src'=>$image_name));
		}
		
		$this->session->set_flashdata("success", "Image successfully edited.");
		redirect("admin/images/image/".$params['image_id']."/".$params['collection_id']);	
	}
	
	/**
	 * Edit Image validate 
	 */
	function edit_validate(&$params){
		if(!$params['image_id']){
			$this->add_error("Invalid ID.");
			return false;	
		}else {
			$result = $this->db->get_where('images', array('image_id'=>(int) $params['image_id']));
			if($result->num_rows() !=1){
				$this->add_error("Invalid ID.");
				return false;	
			}else {
				$result = $result->row_array();
				$params['folder'] = $result['folder'];
				$params["current_image"] = $result['image_src'];
			}
		}
				
		return $this->validate();
	}
	
	/**
	 * Get Image
	 */
	function get_image($image_id=0){
		$result = $this->db->get_where('images', array('image_id'=>(int) $image_id));
		return $result->row_array(); 
	}
	
	/**
	 * Count images
	 */
	function count_images($params=array()){
		
		// image browse filter	
		if($params['searchkey']){
			$this->db->like('title', $params['searchkey']);
			$this->db->or_like('caption', $params['searchkey']);
		}

		if($params['collection_id'])
			$this->db->where('collection_id', (int) $params['collection_id']);
		
		$this->db->from('images');
		return $this->db->count_all_results(); 	
	}
	
	/**
	 * Get pagination =>  return pagination
	 */
	function get_pagination(){		
		return $this->pagination;		
	}
	
	/**
	 * Get images
	 */
	function get_images($params=array()){
				
		$offset = (int) ($params['cur_page']-1) * $params['per_page'];
		$this->db->flush_cache();
		
		// fix CI bug
		$this->db->_reset_write();
			
		if($params['collection_id'])
			$this->db->where('collection_id', (int) $params['collection_id']);

		// image browse filter	
		if($params['searchkey']){
			$this->db->like('title', $params['searchkey']);
			$this->db->or_like('caption', $params['searchkey']);
		}		
			
		$result = $this->db->select('images.*')->get('images', $params['per_page'], $offset);
							
		$images = $result->result_array();
		foreach($images AS $key=>$image){
			
			// path to original image => extract size/pixels
			$file = "uploads/images/originals/".$image['folder']."/".$image['image_src'];
			if(is_file($file)){
				$sizes = getimagesize($file);
				$images[$key]['pixels'] = $sizes[0]."x".$sizes[1];
			
				$sizebytes = filesize($file);
				$images[$key]['kilobytes'] = format_bytes($sizebytes);
			}
			
			// rewrite images src
			$images[$key]['image_src'] = base_url()."uploads/images/thumbs/".$image['folder']."/".$image['image_src'];
		}
		
		// Pagination
		$this->load->library('pagination');
		$this->pagination->initialize($params); //pr($params); 
		$this->pagination = $this->pagination->create_links();		
		
		return $images;
	}
	
	
	/**
	 * Delete Image
	 */
	function delete($params){		
		$result = $this->db->get_where('images', array('image_id'=>(int) $params['image_id']));
		if($result->num_rows()>0){
			$result = $result->row_array();
			$file = "uploads/images/originals/".$result['folder']."/".$result['image_src'];
			$thumbfile = "uploads/images/thumbs/".$result['folder']."/".$result['image_src'];
			@unlink($file);
			@unlink($thumbfile);
			$this->db->where('image_id',(int) $params['image_id'])->delete('images');
			return true;
		}
		return false;
	}
	
	
	
	
	/**
	 * Copy images into cache
	 */ 
	function copy_to_cache($params=array()){
		
		$image = $this->get_image($params["image_id"]);
		
		$array = array(
			"image_id"		=>	$params["image_id"],
			"entity_type"	=>	$params["entity_type"],
			"entity_id"		=>	$params["entity_id"],
			"caption"		=>	$image["caption"],
			"url"			=>	$image["url"]
		);
		
		$file = "uploads/images/originals/".$image['folder']."/".$image['image_src'];
		$tmp_array = array();
		if(is_file($file)){
			$sizes = getimagesize($file);
			$tmp_array['pixels'] = $sizes[0]."x".$sizes[1];
				
			$sizebytes = filesize($file);
			$tmp_array['kilobytes'] = format_bytes($sizebytes);
		}		

		// check if this image was not previous included
		$result = $this->db->get_where("images_versions", array("entity_type"=>$params["entity_type"], "entity_id"=>$params["entity_id"], "image_id"=>$params["image_id"]));
		if($result->num_rows()==0){
			$this->db->insert("images_versions", $array);
			$version_id = $this->db->insert_id();
		}
		// return version id
		else {
			
			$version = $result->row_array();
			$version_id = $version["version_id"];
		}
		
		
		$new_image = str_replace($image["image_id"], $version_id, $image["image_src"]);
			
		$this->db->update("images_versions", array("image_src"=>$new_image), array("version_id"=>$version_id));
		
		$source_file = "uploads/images/originals/".$image["folder"]."/".$image["image_src"];
		$destination_file = "cache/images/".$new_image;
		copy($source_file, $destination_file);
		chmod($destination_file, 0755);
		
		// add new image_src 
		$array["image_src"] = $new_image;
		
		return array_merge($array, $tmp_array);
	}
	
	/**
	 * Return version of image from given $entity_type
	 */
	function get_version($entity_type="", $entity_id=0, $image_id=0){
		return $this->db->get_where("images_versions", array("entity_type"=>$entity_type, "entity_id"=>$entity_id, "image_id"=>$image_id))->row_array();
	}
	
	/**
	 * Crop images
	 */
	function crop($params=array()){
		
		$version = $this->get_version($params["entity_type"], $params["entity_id"], $params["image_id"]);
		$image = $this->get_image($params["image_id"]);
		
		$cropped_file = "cache/images/".$version["image_src"]; 
		list($cropped_file_width, $cropped_file_height) = getimagesize($cropped_file);

		$ratio = 1; 
		if($cropped_file_width > 500)
			$ratio = $cropped_file_width/500;
			
		$crop_width = ($params["coord_x2"] - $params["coord_x1"]) * $ratio;   
		$crop_height = ($params["coord_y2"] - $params["coord_y1"]) * $ratio;
		$x = $params["coord_x1"] * $ratio;
		$y = $params["coord_y1"] * $ratio;
		
		$config['image_library'] = 'gd2';
		$config['source_image'] = $cropped_file;
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = FALSE;
		$config['x_axis'] = $x;
		$config['y_axis'] = $y;
		$config['width'] = $crop_width;
		$config['height'] = $crop_height;
		$config['quality'] = '100%';
		$this->load->library('image_lib', $config);
		
		$this->image_lib->crop();
		
		// Resize image to given dimensions
		if($params["resize_width"]){
			$config = array();
			$config['image_library'] = 'gd2';
			$config['source_image'] = $cropped_file;
			$config['create_thumb'] = FALSE;
			$config['maintain_ratio'] = FALSE;
			$config['width'] = $params["resize_width"];
			$config['height'] = $params["resize_height"];
			$config['quality'] = '100%';
			$this->load->library('image_lib', $config);
			$this->image_lib->initialize($config);
			$this->image_lib->resize();
		}
		
		@chmod($cropped_file, 0755);
				
		return true;
	}
	
	/**
	 * Save caption 
	 */
	function save_version_caption($params=array()){
		$this->db->update("images_versions", array("caption"=>$params["caption"]), array("entity_type"=>$params["entity_type"] ,"entity_id"=>$params["entity_id"], "image_id"=>$params["image_id"]));
		return true;
	}
	
	/**
	 * Save url
	 */
	function save_version_url($params=array()){
		$this->db->update("images_versions", array("url"=>$params["url"]), array("entity_type"=>$params["entity_type"] ,"entity_id"=>$params["entity_id"], "image_id"=>$params["image_id"]));
		return true;
	}
	
	/**
	 * Save display mode
	 */
	function save_display_mode($params=array()){
		$this->db->update("images_versions", array("display_mode"=>$params["display_mode"]), array("entity_type"=>$params["entity_type"] ,"entity_id"=>$params["entity_id"], "image_id"=>$params["image_id"]));
		return true;
	}
}