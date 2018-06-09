/**
 * This library is responsible for image assign/insert into pages via popup / sorting of images / upload of image via uploadify
 **/

$(document).ready(function(){	
	
	// Make rows sortable
	$(".sortableImages").sortable({
		//cancel: 'div.mce-tinymce',
		appendTo: "body",
		helper: "clone",
		scroll: false,
		start: function(e, ui){
		    $(ui.item).find('.tinymce').each(function(){
		    	tinyMCE.execCommand( 'mceRemoveControl', false, $(this).attr('id') );
		    });
		},
		stop: function(e,ui) {
			$(ui.item).find('.tinymce').each(function(){
		    	tinyMCE.execCommand( 'mceAddControl', true, $(this).attr('id') );
		        $(this).sortable("refresh");
		    });  
			
			// detect next sort order
			var contor = 0;
			$('.collectionItem .sort_order', this).each(function(index, el){
				$(this).val(contor);
				contor++;
			});
		},
		'update' : function(){
			$('.actionLinks .save').addClass('changed');
		}
		
	});
	$(".sortableImages").disableSelection(); // text not selectable
	
	
	triggerImageUpload = function(){
		// upload multiple images at once
		$('#multipleUpload').uploadify({
			'buttonText': 'Upload Multiple',	
		    'uploader'  : BASEURL+'assets/js/libs/uploadify/uploadify.swf',
		    'script'    : BASEURL+'assets/js/libs/uploadify/uploadify.php',
		    'cancelImg' : BASEURL+'assets/js/libs/uploadify/cancel.png',
		    'buttonImg' : BASEURL+'assets/js/libs/uploadify/upload-images.png',
		    'width'		: 119,
		    'height'	: 33,
		    'fileExt'   : '*.jpg;*.jpeg;*.gif;*.png',
		    'auto'      : true,
		    'multi'     : false,
		    'queueID'   : 'progressBar',
		    'queueSizeLimit' : 50,
		    'sizeLimit' : 4194304,
		    'onComplete': function(event, ID, fileObj, response, data) {
		        if(response.indexOf("Error")!=-1){
		        	$("#errorContainer").html(response)
		        }
		        else {
		        	var collection_id = $('#imagesOverlayBar .collectionsList a.selected').data('collection_id');
		        	$.post(CMSURL+"admin/images/upload_multiple", {'image_src':response,'collection_id':collection_id}, function(){
		        		if(uploadHasEnded == true){
		        			loadCollection(collection_id);
		        		}		
		        	});
		        }
		     },
		     'onAllComplete' : function(event,data) {
		    	 uploadHasEnded = true;
		     },
		     'onOpen'    : function(event,ID,fileObj) {
		         uploadHasEnded = false;
		     },
		     'onError' : function(event, ID, fileObj, errorObj) {
		    	 alert(errorObj.type+"::"+errorObj.info);
		    	 }
		});
		var uploadHasEnded = false;
	}
	
	
	/**
	 * Function triggered when user click on "Add Selected Images To Page" button
	 */
	returnWithImages = function(){
		if(insertMode == "single")
			assignSingleImage();
		else if (insertMode == "group")
			insertImagesIntoGroup();
		
		// remove "Add Selected Images To Page" segment
		$("#breadcrumbs .removeMe").remove();
		
		// fix breadcrumbs
		fixBreadcrumbs();
	}		
	
	/**
	 * Load a collection
	 */
	loadCollection = function(collection_id, element){
		
		var current_selected = $('#imagesOverlayBar .collectionsList a.selected').data('collection_id');
		if(current_selected != collection_id){
			$("#imagesOverlayBar .collectionsList li a").removeClass("selected");
			$(element).addClass("selected");
		}	
		
		// fix width of overlay content
		$("#imagesOverlayContent").width(parseInt($("#rightContainer").width()) - 260);
		
		// load images in right side
		$("#imagesOverlayContent").load(CMSURL+"admin/images/browse_content/1/"+collection_id, function(){
			
			// add selected class
			checkSelected();
			Shadowbox.setup();
			searchkey = '';
			$("#searchKey").val('');
			
			// set a height for images list box 
			$("#popupImagesList").height($(window).height()-155 - 25);
			
			// show image upload button
			$('.showImageUpload').show();
		});
	}		
	
	
	/**
	 * Check if image is selected for a Single or Group
	 */
	checkSelected = function(){
		$("#popupImagesList div.collectionItem").each(function(){
			
			// extract id of image for comparation purpose
			var id = $(this).data('image_id');
			
			// SINGLE insert mode
			if(insertMode == "single"){
				if(id == selectedSingleImage)
					$(".imageVisibility", this).addClass('imageSelected');
				else 
					$(".imageVisibility", this).addClass('imageNotSelected');
			}
			// GROUP insert mode
			else if (insertMode == "group"){
				if($.inArray(id, usedImages) >= 0)
					$(".imageVisibility", this).addClass('imageSelected');
				else 
					$(".imageVisibility", this).addClass('imageNotSelected');
			}
		});
	}
	
	/**
	 * Toggle image select/deselect
	 */
	imageSelectToggle = function(element){
		
		var id = $(element).data('image_id');
		
		// SINGLE insert mode
		if(insertMode == "single"){
			// remove selected state	
			if($(element).hasClass('imageSelected')){
				selectedSingleImage= ''; // remove from selected			
				$(element).addClass('imageNotSelected');
				$(element).removeClass('imageSelected');
			} 
			// add selected state
			else {
				selectedSingleImage = id;
				
				// remove selected status from previous image
				$("#popupImagesList .imageVisibility").removeClass('imageSelected');
				$("#popupImagesList .imageVisibility").addClass('imageNotSelected');
				// add selected class
				$(element).addClass('imageSelected');
				$(element).removeClass('imageNotSelected');
			}
		}
		// GROUP insert mode
		else if (insertMode == "group"){
			// remove from usedImages Array
			if($(element).hasClass('imageSelected')){
				usedImages.splice(usedImages.indexOf(id), 1); // remove from used
				addedImages.splice(addedImages.indexOf(id), 1); // remove newly selected
				removedImages.push(id); // save removed 
				$(element).addClass('imageNotSelected');
				$(element).removeClass('imageSelected');
			} 
			// Add to usedImages
			else {
				usedImages.push(id);
				addedImages.push(id);
				removedImages.splice(removedImages.indexOf(id), 1);
				$(element).addClass('imageSelected');
				$(element).removeClass('imageNotSelected');
			}
		}
		
		$(".returnImages").addClass("changed");
	}
	
	
	
	
	/*********** SINGLE FUNCTIONS ************/
	/**
	 * Trigger images popup for Single assignment
	 */
	selectSingleImage = function(element, full_selector, entity_type, entity_id){
		
		// Insert Mode => single => only pickup an image / group => insert container + pickup image for each container
		insertMode = "single";
		
		// store scroll position from page => scroll to top ....
		scrollPosition = $(document).scrollTop();
		$('html, body').animate({
	        scrollTop: 0 
	    }, 0);
		
		
		if(full_selector)
			var parent = $(full_selector);
		else 
			var parent = $(element).parent();
		
		// set singleImageSection
		singleImageSection = $(".collectionItem", parent);
		
		// set selected image
		selectedSingleImage = $(".imageContainer .hidden", singleImageSection).val();
				
		// entity types
		(typeof entity_type != 'undefined') ? singleImageEntityType = entity_type : singleImageEntityType = $("#entity_type").val();
		(typeof entity_id != 'undefined') ? singleImageEntityId = entity_id : singleImageEntityId = $("#entity_id").val();
				
		// Add new span in breadcrumb
		$("#breadcrumbs").append('<a class="removeMe">Add Selected Image To Page</a>');
		// fix breadcrumbs
		fixBreadcrumbs();
		
		// Load windows
		$("#imagesOverlay").load(CMSURL+"admin/images/browse", function(){
			
			// init file upload			
			triggerImageUpload();
			
			$("body").css("overflow", "hidden");
			
			// mark as open
			$("#imagesOverlay").addClass('open');
			
			// fix size of overlay 
			fixOverlaySize();
		});	
	}
	
	/**
	 * Assign single image
	 */
	assignSingleImage = function(){
		
		$("#breadcrumbs a:first").addClass("lastlink");
		$("#breadcrumbs a:last").removeClass("lastlink");
		
		$("#imagesOverlay").html('').width(0).height(0).removeClass('open');
		
		// reset scroll into body + scroll back to old position before opening overlay		
		$("body").css("overflow", "visible");
		$('html, body').animate({
	        scrollTop: scrollPosition 
	    }, 0);
		
		// fix any resize issue
		fixRightContainer();
		
		// remove segment prepended into breadcrumbs 
		$("#breacrumbs .assignImages").remove();
		// fix breadcrumbs
		fixBreadcrumbs();
		
		var image_object = $(".imageContainer img", singleImageSection);
		
		// Load image 
		if(selectedSingleImage != $(image_object).val()){
			$.post(CMSURL+"admin/images/copy_to_cache", {"image_id":selectedSingleImage, "entity_type":singleImageEntityType, "entity_id":singleImageEntityId}, function(data){
				
				// set hidden and visible values/texts 								
				$(".imageContainer .hidden", singleImageSection).val(data.image_id);
				$(".imageContainer .hiddenUrl", singleImageSection).val(BASEURL+"cache/images/"+data.image_src);
				$(".imageCaption", singleImageSection).html(data.caption ? data.caption : 'caption');
				$(".imageUrl", singleImageSection).html(data.url ? data.url : 'URL');
				$(image_object).attr("src", BASEURL+"cache/images/"+data.image_src);				
				$(".imageButtons .cropImage", singleImageSection).attr("href", CMSURL+"admin/images/crop_edit/"+data.entity_type+"/"+data.entity_id+"/"+data.image_id);
				$(".imageInfo .resolution", singleImageSection).html(data.pixels);
				$(".imageInfo .imageUrl", singleImageSection).html(data.kilobytes);				
				// show singleImageSection after data was populated
				$(singleImageSection).show();
				// reset insertMode on return
				insertMode = "";
				
				$('.insertImageToPage', $(singleImageSection).parent()).hide();
				 
				$('.actionLinks .save').addClass('changed');
				Shadowbox.setup(); // fix Shadowbox
				
				// fix any resize issue
				fixRightContainer();
				
			}, "json");
		}
	}
	
	/**
	 * Unassign single image 
	 */
	unassignSingleImage = function(element){
		singleImageSection = $(element).parent();
		$(".imageContainer .hidden", singleImageSection).val("");
		$(".imageContainer .hiddenUrl", singleImageSection).val(""); 
		$(".imageContainer img", singleImageSection).attr("src", "");
		$(".imageTitle", singleImageSection).html("");
		$(".imageButtons .editImage", singleImageSection).attr("href", "javascript:void(0)");
		
		$(singleImageSection).fadeOut(400, function(){
			$('.insertImageToPage', $(singleImageSection).parent()).show();
		});
		
		$('.actionLinks .save').addClass('changed');
	}
	/************************************/
	
		
	/*******GROUP FUNCTIONS ***********/
	/**
	 * Trigger images popup for Single assignment
	 */
	selectGroupImages = function(selector, entity_type, entity_id){
		
		// Insert Mode => single => only pickup an image / group => insert container + pickup image for each container
		insertMode = "group";
		
		// store scroll position from page => scroll to top ....
		scrollPosition = $(document).scrollTop();
		$('html, body').animate({
	        scrollTop: 0 
	    }, 0);
		
		// set selectors
		groupSelector = $("#"+selector);
		groupSelectorId = selector;
		
		// entity types
		(typeof entity_type != 'undefined') ? groupEntityType = entity_type : groupEntityType = $("#entity_type").val();
		(typeof entity_id != 'undefined') ? groupEntityId = entity_id : groupEntityId = $("#entity_id").val();
						
		// GROUP selected images		
		$('.collectionItem .hidden',groupSelector).each(function(index, el){
			usedImages.push(parseInt($(el).val()));
		});
		
		// Add new link in breadcrumb
		$("#breadcrumbs").append('<a class="removeMe">Add Selected Images To Page</a>');
		// fix breadcrumbs
		fixBreadcrumbs();
		
		// Load windows
		$("#imagesOverlay").load(CMSURL+"admin/images/browse", function(){
			
			// init file upload
			triggerImageUpload();
			
			$("body").css("overflow", "hidden");
			
			// mark as open
			$("#imagesOverlay").addClass('open');
			
			// fix size of overlay 
			fixOverlaySize();
		});	
	}
	
	/**
	 * Insert selected images into page
	 */
	insertImagesIntoGroup = function(){
		
		$("#breadcrumbs a:first").addClass("lastlink");
		$("#breadcrumbs a:last").removeClass("lastlink");
		
		$("#imagesOverlay").html('').width(0).height(0).removeClass('open');
		
		// reset scroll into body + scroll back to old position before opening overlay		
		$("body").css("overflow", "visible");
		$('html, body').animate({
	        scrollTop: scrollPosition 
	    }, 0);
		
		// remove segment prepended into breadcrumbs 
		$("#breacrumbs .assignImages").remove();
		// fix breadcrumbs
		fixBreadcrumbs();
		
		// fix any resize issue
		fixRightContainer();
		
		// REMOVE images that have been deselected
		var len=removedImages.length;
		for(var i=0; i<len; i++) {
			var image_id = removedImages[i];
			
			$('.collectionItem .hidden',groupSelector).each(function(index, el){
				if(parseInt($(el).val())==image_id){
					$(this).closest('.collectionItem').remove();
				}
			});
		}
				
		// INSERT NEW IMAGES AT END
		var len=addedImages.length;
		for(var i=0; i<len; i++) {
			
			// detect next sort order
			var max_sort_order = 0;
			$('.collectionItem .sort_order',groupSelector).each(function(index, el){
				if(parseInt($(el).val())>max_sort_order){
					max_sort_order = parseInt($(el).val());
				}
			});
			var next_sort_order = max_sort_order + 1;
						
			var image_id = addedImages[i];

			$.ajaxSetup({async:false});
			$.post(CMSURL+"admin/images/copy_to_cache", {"image_id":image_id, "entity_type":groupEntityType, "entity_id":groupEntityId}, function(data){
				
				var html = '<div class="collectionItem" data-entity_type="'+groupEntityType+'" data-entity_id="'+groupEntityId+'">'+
				    			'<a class="removeImage" href="javascript:void(0)" data-image_id="'+data.image_id+'" onclick="removeGroupImage(this)"></a>'+
								'<div class="imageContainer">'+
									'<input type="hidden" class="hidden" name="images['+groupSelectorId+']['+data.image_id+']" value="'+data.image_id+'">'+
									'<input type="hidden" class="sort_order" name="sort_order['+groupSelectorId+']['+data.image_id+']" value="'+next_sort_order+'">'+
									'<img class="img" src="'+BASEURL+'cache/images/'+data.image_src+'">'+
									'<div class="imageButtons" style="height:37px;">'+
										'<a href="'+CMSURL+'admin/images/crop_edit/'+data.entity_type+'/'+data.entity_id+'/'+data.image_id+'" class="cropImage" rev="0" rel="shadowbox;player=iframe;width=790;height=600"></a>'+		
									'</div>'+
								'</div>'+
								'<div class="imageInfo">'+
									'<span class="resolution">'+(data.pixels? data.pixels:"")+'</span>'+
									'<span class="size">'+(data.kilobytes? data.kilobytes:"")+'</span>'+
								'</div>'+
								'<div class="imageCaption">'+(data.caption? data.caption: "caption")+'</div>'+
								'<div class="imageUrl">'+(data.url? data.url: "URL")+'</div>'+
								'<div class="imageDisplayMode">'+(data.display_mode_html? data.display_mode_html:"")+'</div>'+
							'</div>';
				
				$(groupSelector).append(html);
				Shadowbox.setup(); // fix Shadowbox
			}, "json");
			
			$('.actionLinks .save').addClass('changed');
		}

		addedImages = [] // reeinit
		removedImages = [] // reeinit
	}
	
	/**
	 * Remove Group image
	 */
	removeGroupImage = function(element){
		
		if(!element)
			return '';
		
		var image_id = $(element).data('image_id');
		var parent = $(element).closest('.collectionItem');
		
		$(parent).slideUp("fast", function(){
			$(this).remove();
			// clean arrays
			addedImages.splice(addedImages.indexOf(image_id), 1);
			usedImages.splice(usedImages.indexOf(image_id), 1);
			removedImages.splice(removedImages.indexOf(image_id), 1);					
		});	
		
		$('.actionLinks .save').addClass('changed');
	}
	
	/*******************************/
	
	
	
	
	
	
	
	
	
	/**
	 * Fix size of overlay screen
	 */
	fixOverlaySize = function(){
		
		// check if overlay is open
		if(!$('#imagesOverlay').hasClass('open'))
			return;
		
		// determine width & height => fix size of overlay
		var overlayWidth = parseInt($("#rightContainer").innerWidth(),10);
		var overlayHeight = parseInt($(window).innerHeight(),10) - 135;
		$("#imagesOverlay").css('width', overlayWidth).css('height', overlayHeight);
		
		// fix overlay bar
		$("#imagesOverlayBar").height($(window).height()-155);
		
		// fix width of overlay content
		$("#imagesOverlayContent").width(parseInt($("#rightContainer").width()) - 260);
	}
	
	/**
	 * Execute when upload image window is closed
	 */  
	executeoncloseuploadimage = function(){
		
		// reload active collection
		loadCollection($('#imagesOverlayBar .collectionsList a.selected').data('collection_id'));
	}
		
	/**
	 * Search an image into a collection
	 */
	searchIntoCollection = function(){
		
		var searchkey = $("#searchKey").val();
		if(searchkey.length < 2){
			BootstrapDialog.alert('Please enter at least 2 characters!');
		    return;
		}	
		
		$("#imagesOverlayContent").load(CMSURL+"admin/images/browse_content/1/0/"+searchkey, function(){
			
			checkSelected();
			Shadowbox.setup();
			selectedCollection = 0;
			
			// set a height for images list box 
			$("#popupImagesList").height($(window).height()-155 - 65);
		});
		$("#imagesOverlayBar .collectionsList a").removeClass('selected'); // remove selected effect on links
	}
	
	
	/**
	 * Bind search event on enter keyup
	 */
	$(document).on('keyup', $("#searchKey"), function(event){
		var keycode = (event.keyCode ? event.keyCode : event.which);
		if(keycode == '13')
			searchIntoCollection();
	});
	
	/**
	 * Delete collection image
	 */
	deleteImage = function(image_id){
		if(!image_id)
			return '';
		
		BootstrapDialog.confirm('Are you sure you want to delete this image?', function(result){
	        if(result) {
	        	
	        	//dialog.close();
	        	$.post(CMSURL+"admin/images/delete", {'image_id':image_id}, function(){
					$("#item_"+image_id).slideUp('fast', function(){
						$(this).remove();
					})
				});
	        }
	    });
	}
	
	/**
	 * SET IMAGE EDIT MODE => used to refreash image after crop
	 */ 
	$(".collectionItem .cropImage").on('click', function(){
		IMAGE_EDIT_ROW = $(this).closest(".collectionItem");
	});
	
	/**
	 * This is executed once crop popup is closed to refresh the cropped image
	 */
	executeonclosecropimage =function(){
		var thumb_src = $(".imageContainer img", IMAGE_EDIT_ROW).attr("src");
		$(".imageContainer img", IMAGE_EDIT_ROW).attr("src", thumb_src+"?"+new Date().getTime());
		return;
	}
	
	
	/**
	 * Transform .imageCaption into a text input in order to edit caption
	 */
	$(document).on('click', '.imageCaption', function(event){
		
		var element = event.target;
		
		// protection against trigger twice same time
		if($(element).hasClass('active'))
			return;
		
		var value = $.trim($(element).html());
		
		// reset value if text is "caption"
		if(value=='caption')
			value = '';
		
		$(element).addClass('active');
		$(element).html('<input type="text" name="version_caption_edit" value="" class="inlineInput"/>');
		$('.inlineInput', element).focus().val(value);
	});
	
	
	/**
	 * Transform .imageUrl into a text input in order to edit url
	 */
	$(document).on('click', '.imageUrl', function(event){
		
		var element = event.target;
		
		// protection against trigger twice same time
		if($(element).hasClass('active'))
			return;
		
		var value = $.trim($(element).html());
		
		// reset value if text is "URL"
		if(value=='URL')
			value = '';
		
		$(element).addClass('active');
		$(element).html('<input type="text" name="version_url_edit" value="" class="inlineInput"/>');
		$('.inlineInput', element).focus().val(value);
	});
	
	/**
	 * Edit caption - backend call on focus lose
	 */
	$(document).on('focusout', '[name="version_caption_edit"]', function(event){
		
		var element = event.target;
		
		// remove active class  
		$(element).parent().removeClass('active');
		
		// extract value
		var value = $(element).val();
		
		// get image id
		var image_id = $('.hidden', $(element).closest('.collectionItem')).val();
		
		// get entity type/id
		var entity_type = $(element).closest('.collectionItem').data('entity_type');
		var entity_id = $(element).closest('.collectionItem').data('entity_id');
		
		$.post(CMSURL+"admin/images/save_version_caption", {"image_id":image_id, "entity_type":entity_type, "entity_id":entity_id, "caption":value});
		
		// reset to text
		if(value=='')
			value = 'caption';
		$(element).parent().html(value);
	});
	
	/**
	 * Edit url - backend call on focus lose
	 */
	$(document).on('focusout', '[name="version_url_edit"]', function(event){
		
		var element = event.target;
		
		// remove active class  
		$(element).parent().removeClass('active');
		
		// extract value
		var value = $(element).val();
		
		// get image id
		var image_id = $('.hidden', $(element).closest('.collectionItem')).val();
		
		// get entity type/id
		var entity_type = $(element).closest('.collectionItem').data('entity_type');
		var entity_id = $(element).closest('.collectionItem').data('entity_id');
		
		$.post(CMSURL+"admin/images/save_version_url", {"image_id":image_id, "entity_type":entity_type, "entity_id":entity_id, "url":value});
		
		// reset to text
		if(value=='')
			value = 'URL';
		$(element).parent().html(value);		
	});
	
	/**
	 * Save image display mode on select
	 */
	$('#rightContainer').on('click', '.dropdown-menu li', function(event){
		
		// display mode
		var element = event.target;
		var target = $(element).closest('li');
		var display_mode = $(target).data("id");
		
		// get image id
		var image_id = $('.hidden', $(element).closest('.collectionItem')).val();
		
		// get entity type/id
		var entity_type = $(element).closest('.collectionItem').data('entity_type');
		var entity_id = $(element).closest('.collectionItem').data('entity_id');
		
		$.post(CMSURL+"admin/images/save_display_mode", {"image_id":image_id, "entity_type":entity_type, "entity_id":entity_id, "display_mode":display_mode});
	});
});

//Resize event
$(window).resize(function(){
	fixOverlaySize();
});


/*** GLOBAL VARIABLES ***/
// position of scroll window
var scrollPosition = 0;
// selector for container of single image
var singleImageSection = "";
// entity id for single image 
var singleImageEntityId = '';
// entity type for single image
var singleImageEntityType = '';

// selector for current GROUP 
var groupSelector = "";
//selector ID for current GROUP
var groupSelectorId = "";
// entity id for group 
var groupEntityId = '';
//entity type for group
var groupEntityType = '';

//ID of image selected on current single image
var selectedSingleImage = "";
// type of location where images are inserted single image/slideshow
var insertMode = "";

// array with images used on Group
var usedImages = [];
//array with images selected on Group
var addedImages = [];
//array with images removed from Group
var removedImages = [];
/************************/