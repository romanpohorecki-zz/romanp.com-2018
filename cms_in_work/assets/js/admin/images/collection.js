/** Collection **/

$(document).ready(function() {
	
	/*** Action Links - SAVE ***/
	$(".actionLinks a.save").click(function(e){
		e.preventDefault();
		$("#collectionForm").submit();
	});
	
	// Load images
	reloadImages();
	
	// pagination
	$(".paginationLinks a").on('click',function(e){
		e.preventDefault();
		var page_url = $(this).attr('href');
		$("#collectionImagesList").load(page_url, function(){
			Shadowbox.setup();
			 var vars = page_url.split("/");
			 PAGE_ID = vars.pop();
		});	
	})
	 	
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
	    'multi'     : true,
	    'queueID'   : 'progressBar',
	    'queueSizeLimit' : 50,
	    'sizeLimit' : 4194304,
	    'onComplete': function(event, ID, fileObj, response, data) {
	        if(response.indexOf("Error")!=-1){
	        	$("#errorContainer").html(response)
	        }
	        else {
	        	var collection_id = $("#collection_id").val();
	        	$.post(CMSURL+"admin/images/upload_multiple", {'image_src':response,'collection_id':collection_id}, function(){
	        		if(uploadHasEnded == true)
	        			reloadImages();	
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
})

var PAGE_ID = 1;

// Reload images
function reloadImages(page_id){
	
	if(!page_id)
		page_id = PAGE_ID;
	if(isNaN(page_id))
		page_id = 1;
	
	var collection_id = $("#collection_id").val();
	
	$.post(CMSURL+"admin/images/get_images/"+collection_id+"/"+page_id, function(data){		
		$("#collectionImagesList").html(data);
		Shadowbox.setup();
		
		setTimeout('fixRightContainer()', 10);
	});
}

// Execute when upload images windows is closing 
function executeoncloseuploadimage(){
	reloadImages()
}

// delete image
function deleteImage(image_id){
	if(!image_id)
		return '';
	
	BootstrapDialog.confirm('Are you sure you want to delete this image?', function(result){
        if(result) {
        	
        	console.log(result);
        	
        	//dialog.close();
        	$.post(CMSURL+"admin/images/delete", {'image_id':image_id}, function(){
				$("#item_"+image_id).slideUp('fast', function(){
					$(this).remove();
					reloadImages();
				})
			});
        }
    });
}