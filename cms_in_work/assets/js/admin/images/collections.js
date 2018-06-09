/** Collections List **/

$(document).ready(function() {
	
	$("#searchImages").click(function(e){
		e.preventDefault();
		
		var searchkey = $("#searchMedia").val();
		if(searchkey.length < 2){
			display_message('Information','Please enter at least 2 characters!');
			return;
		}
		
		$("#containerBreadcrumbs").find('span').remove();
		$("#containerBreadcrumbs").append('<span>Search for "'+$("#searchMedia").val()+'"</span>');
		
		$("#containerBreadcrumbs a:first").removeClass("lastlink");
		$("#containerBreadcrumbs a:last").addClass("lastlink");
		
		reloadImages();
	})
	
	// pagination
	$(".paginationLinks a").on('click',function(e){
		e.preventDefault();
		var page_url = $(this).attr('href');
		$("#collectionsList").load(page_url, function(){
			Shadowbox.setup();
		});	
	})
})

// Reload images
function reloadImages(searchkey){
	
	var searchkey = $("#searchMedia").val();
	$("#collectionsList").load(CMSURL+"admin/images/get_images/0", {'searchkey':searchkey}, function(){
		Shadowbox.setup();
	});
}

//delete image
function deleteImage(image_id){
	if(!image_id)
		return '';
	
	$("#modal").dialog({
		'title': 'Confirm',
		'width': 330,
		'height':150,
		'modal': true,
		'buttons': {
			Confirm: function() {
				$("#modal").dialog("close");
				$.post(CMSURL+"admin/images/delete", {'image_id':image_id}, function(){
					$("#item_"+image_id).slideUp('fast', function(){
						$(this).remove();
						reloadImages();
					})
				});
			},
			Close: function() {
				$(this).dialog("close");
			}
		}
	}).html('Are you sure you want to delete this image');	
}

//Execute when upload images windows is closing 
function executeoncloseuploadimage(){
	reloadImages()
}