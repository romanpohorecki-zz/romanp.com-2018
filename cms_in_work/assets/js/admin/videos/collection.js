/** Collection **/

$(document).ready(function() {
	
	/*** Action Links - SAVE ***/
	$(".actionLinks a.save").click(function(e){
		e.preventDefault();
		$("#collectionForm").submit();
	});
	
	// Load videos
	reloadVideos();
	
	// pagination
	$(".paginationLinks a").on('click',function(e){
		e.preventDefault();
		var page_url = $(this).attr('href');
		$("#collectionVideosList").load(page_url, function(){
			Shadowbox.setup();
			 var vars = page_url.split("/");
			 PAGE_ID = vars.pop();
		});	
	})
	
	// upload video
	$('.uploadVideo').on('click', function() {	
		$('input[name="upload_video"]').trigger('click');
	});
	$('input[name="upload_video"]').change(function(){
		var file_data = $('input[name="upload_video"]').prop('files')[0];   
	    var form_data = new FormData();                  
	    form_data.append('file', file_data);
	    $.ajax({
	        url: BASEURL+'admin/videos/video/'+$('#collection_id').val(), 
	        dataType: 'text',
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: form_data,                         
	        type: 'post',
	        success: function(php_script_response){
	        	reloadVideos();
	        	$('input[name="upload_video"]').val('');
	        }
	     });
	});		
})

var PAGE_ID = 1;

// Reload videos
function reloadVideos(page_id){
	
	if(!page_id)
		page_id = PAGE_ID;
	if(isNaN(page_id))
		page_id = 1;
	
	var collection_id = $("#collection_id").val();
	
	$.post(CMSURL+"admin/videos/get_videos/"+collection_id+"/"+page_id, function(data){		
		$("#collectionVideosList").html(data);
		Shadowbox.setup();
		
		setTimeout('fixRightContainer()', 10);
	});
}


// delete video
function deleteVideo(video_id){
	if(!video_id)
		return '';
	
	BootstrapDialog.confirm('Are you sure you want to delete this video?', function(result){
        if(result) {
        	//dialog.close();
        	$.post(CMSURL+"admin/videos/delete", {'video_id':video_id}, function(){
				reloadVideos();
			});
        }
    });
}