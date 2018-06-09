/** Browse Windows **/

$(document).ready(function(){	
	
	containerHeight = '60px';
	
	$("#containerContent").css('top', containerHeight);

	// Load left bar 
	$("#imagesOverlayBar").load(CMSURL+"admin/images/browse_bar", function(){
		$("#imagesOverlayBar").height($(window).height()-155);
		Shadowbox.setup();
		$("table.list tr:nth-child(even)").addClass("odd");
		$("table.list tr td").css("opacity", "0.5");
	});
		
	// Apply sort filter
	$(".collectionsList th a").die('click');
	$(".collectionsList th a").live('click',function(){
		var sortType = $(this).attr("rel");		
		$("#imagesOverlayBar").load(CMSURL+"admin/images/browse_bar/"+sortType, function(){
			$("table.list tr:nth-child(even)").addClass("odd");
			$("table.list tr td").css("opacity", "0.5");
		});
	})	
	
	// click on link event
	$("#imagesOverlayBar .collectionsList a.collectionRow").die('click');
	$("#imagesOverlayBar .collectionsList a.collectionRow").live('click', function(e){
		e.preventDefault();
		
		$("#imagesOverlayBar .collectionsList tr td a").css("font-weight", "normal");
		$("#imagesOverlayBar .collectionsList tr td a").css("color", "#777");
		$("#imagesOverlayBar .collectionsList tr td").css("opacity","0.5");
		
		$("td", $(this).parent().parent()).css("opacity","1");
		$("a", $(this).parent().parent()).css("font-weight","bold")
		$("a", $(this).parent().parent()).css("color","#000")
		
		selectedCollection = parseInt($(this).attr('href'), 10); 
		// load images in right section
		$("#imagesOverlayContent").load(CMSURL+"admin/images/browse_content/1/"+selectedCollection, function(){
			// prevent resizing down!!!
			if(parseInt($("#imagesOverlayContent").css('height'))>820){
				fix_containerContent_height($("#imagesOverlayContent").css('height'));
			}
			// add selected class
			checkSelected();
			Shadowbox.setup();
			searchkey = '';
			$("#searchKey").val('');
			
			// set a height for images list box 
			$("#popupImagesList").height($(window).height()-155 - 25);
			
		});
	})
	
	$("#searchKey").die('keyup');
	$("#searchKey").live('keyup',function(event){
		var keycode = (event.keyCode ? event.keyCode : event.which);
		if(keycode == '13')
			$('.searchButton').click();
	})
	
	// search event
	$(".searchButton").die('click');
	$(".searchButton").live('click', function(e){
		e.preventDefault();
		searchkey = $("#searchKey").val();
		
		if(searchkey.length < 2){
			display_message('Information','Please enter at least 2 characters!');
			return;
		}	
		
		$("#imagesOverlayContent").load(CMSURL+"admin/images/browse_content/1/0/"+searchkey, function(){
			
			// prevent resizing down!!!
			if(parseInt($("#imagesOverlayContent").css('height'))>820){
				fix_containerContent_height($("#imagesOverlayContent").css('height'));
			}
			checkSelected();
			Shadowbox.setup();
			selectedCollection = 0;
			
			// set a height for images list box 
			$("#popupImagesList").height($(window).height()-155 - 65);
		}).html("www");
		$("#imagesOverlayBar .collectionsList a").removeClass('selected'); // remove selected effect on links
	})
	
	// pagination 
	$(".paginationLinks a").die('click');
	$(".paginationLinks a").live('click', function(e){
		e.preventDefault();
		var page_url = $(this).attr('href');
		$("#imagesOverlayContent").load($(this).attr('href'), function(){
			checkSelected();
			Shadowbox.setup();
			var vars = page_url.split("/");
			vars.pop();
			vars.pop();			
			PAGE_ID = vars.pop();
		});
	})
	
	// Select Action
	$("#popupImagesList .imageVisibility").die('click');
	$("#popupImagesList .imageVisibility").live('click', function(e){
		e.preventDefault();
		var id = parseInt($(this).attr('href'), 10);
		// remove from usedImages Array
		if($(this).hasClass('imageSelected')){
			usedImages.splice(usedImages.indexOf(id), 1); // remove from used
			addedImages.splice(addedImages.indexOf(id), 1); // remove newly selected
			removedImages.push(id); // save removed 
			$(this).addClass('imageNotSelected');
			$(this).removeClass('imageSelected');
		} 
		// Add to usedImages
		else {
			usedImages.push(id);
			addedImages.push(id);
			removedImages.splice(removedImages.indexOf(id), 1);
			$(this).addClass('imageSelected');
			$(this).removeClass('imageNotSelected');
		}
		$(".returnImages").addClass("changed");
	})
	
})

var PAGE_ID =1;
var selectedCollection = 0;
var searchkey = ''

function executeoncloseeditimage(collection_id){
	if(parseInt(collection_id,10)>0)
		selectedCollection = parseInt(collection_id,10);
	
	var	page_id = PAGE_ID;
	if(page_id.length == 0 || isNaN(page_id))
		page_id = 1;
	
	$("#imagesOverlayContent").load(CMSURL+"admin/images/browse_content/"+page_id+"/"+selectedCollection+"/"+searchkey, function(){
		// prevent resizing down!!!
		if(parseInt($("#imagesOverlayContent").css('height'))>820){
			fix_containerContent_height($("#imagesOverlayContent").css('height'));
		}
		// add selected class
		checkSelected();
		Shadowbox.setup();
	});
}

// Add selected class
function checkSelected(){
	$("#popupImagesList div.imageItem").each(function(){
		var id = $(this).attr('id').slice(5, $(this).attr('id').length);
		id = parseInt(id, 10);
		
		// already used -> mark 
		if($.inArray(id, usedImages) >= 0)
			$(".imageVisibility", this).addClass('imageSelected');
		else 
			$(".imageVisibility", this).addClass('imageNotSelected');
	})
}

function fix_containerContent_height(height){
	$("#containerContent").css('min-height', height);
}