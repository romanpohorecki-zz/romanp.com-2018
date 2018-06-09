/** Browse and pickup just one file **/

$(document).ready(function(){	
	
	// search event
	$("#searchKey").on('keyup',function(event){
		var keycode = (event.keyCode ? event.keyCode : event.which);
		if(keycode == '13')
			$('#searchKeyButton').click();
	})
	
	// search event
	$("#searchKeyButton").on('click', function(e){
		e.preventDefault();
		searchkey = $("#searchKey").val();
		
		if(searchkey.length < 2){
			display_message('Information','Please enter at least 2 characters!');
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
	})
	
	// pagination
	$(".paginationLinks a").on('click', function(e){
		e.preventDefault();
		$("#imagesOverlayContent").load($(this).attr('href'), function(){
			checkSelected();
		});
	})
	
	// Select Action
	$("#popupImagesList .imageVisibility").on('click', function(e){
		e.preventDefault();
		var id = parseInt($(this).attr('href'), 10);
		
		// remove from usedImages var
		if($(this).hasClass('imageSelected')){
			selectedImage=''; // remove from selected			
			$(this).addClass('imageNotSelected');
			$(this).removeClass('imageSelected');
		} 
		// Add to selectedImages
		else {
			selectedImage = id;
			// remove selected status from previous image
			$("#popupImagesList .imageVisibility").removeClass('imageSelected');
			$("#popupImagesList .imageVisibility").addClass('imageNotSelected');
			// add selected class
			$(this).addClass('imageSelected');
			$(this).removeClass('imageNotSelected');
		}
		$(".returnImages").addClass("changed");		
	});
	
	
	/**
	 * Load a collection
	 */
	loadCollection = function(collection_id, element){
		
		$("#imagesOverlayBar .collectionsList li a").removeClass("selected");
		$(element).addClass("selected");
		
		// fix width of overlay content
		$("#imagesOverlayContent").width(parseInt($("#rightContainer").width()) - 260);
		
		// load images in right section
		$("#imagesOverlayContent").load(CMSURL+"admin/images/browse_content/1/"+collection_id, function(){
			
			// add selected class
			checkSelected();
			Shadowbox.setup();
			searchkey = '';
			$("#searchKey").val('');
			
			// set a height for images list box 
			$("#popupImagesList").height($(window).height()-155 - 25);
		});
	}
})

var searchkey = '';

function checkSelected(){
	$("#popupImagesList div.imageItem").each(function(){
		var id = $(this).attr('id').slice(5, $(this).attr('id').length);
		id = parseInt(id, 10);
		
		// already used -> mark 
		if(id == selectedImage)
			$(".imageVisibility", this).addClass('imageSelected');
		else 
			$(".imageVisibility", this).addClass('imageNotSelected');
	})
}