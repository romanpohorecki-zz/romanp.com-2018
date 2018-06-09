/** Image Box **/

$(document).ready(function() {
	
	/*** Save Image ***/
	$(".saveImage").click(function(e){
		e.preventDefault();
		$("#imageUploadForm").submit();
	})
	
	/*** Action Links - Close ***/
	$("#popupTop a.close").click(function(e){
		e.preventDefault();
		var image_id = $("#image_id").val(); 
		var collection_id = $("#collection_id").val(); 
		window.parent.executeoncloseuploadimage(image_id);
		try {
			window.parent.executeoncloseeditimage(collection_id);
		}
		catch(err){ // do nothing
		}
		
		window.parent.Shadowbox.close();		
	})
	
	$('#image_upload').uploadify({
	    'uploader'  : BASEURL+'assets/js/libs/uploadify/uploadify.swf',
	    'script'    : BASEURL+'/assets/js/libs/uploadify/uploadify.php',
	    'cancelImg' : BASEURL+'assets/js/libs/uploadify/cancel.png',
	    'buttonImg' : $("#image_id").val() ? BASEURL+'assets/js/libs/uploadify/replace-image.png' : BASEURL+'assets/js/libs/uploadify/new-image.png',
	    'width'		: 208,
	    'height'	: 40,
	    'auto'      : true,
	    'fileExt'   : '*.jpg;*.jpeg;*.gif;*.png',
	    'buttonText':"Select File",
	    'queueID'   : 'progressBar',
	    'onComplete'  : function(event, ID, fileObj, response, data) {
	    	
	        if(response.indexOf("Error")!=-1){
	        	$("#errorContainer").html(response)
	        }
	        else {
	              var img_url = BASEURL+"uploads/images/tmp/"+response;
	              $(".imageContainer img").attr('src', img_url);
	              $("#imageSrc").val(response);
	          }
	     }
	});
})