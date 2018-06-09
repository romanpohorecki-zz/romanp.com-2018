/** Categories form **/

$(document).ready(function() {
	
	/*** Action Links - SAVE ***/
	$(".actionLinks a.save").click(function(e){
		e.preventDefault();
		$("#categoryForm").submit();
	});
	
	/**
	 * Validate page name
	 */
	$("#categoryForm").validate({
		rules: {
			title: {
		        required: true
		    }
		},    
		messages: {
		    title: {
		        required: "Please enter Title",
		    }
		}
	});
	
			
	/**
	 * Published /Unpublished
	 */
	$(".publishedUnpublished").click(function(e){
		e.preventDefault();
		
		if($(this).hasClass('published')){
			$(this).removeClass('published');
			$(this).addClass('unpublished');
			$(this).html('Unpublished');
					
			$.post(CMSURL+"admin/categories/publish", {'category_id':$("#category_id").val(), 'new_status':'inactive'}, function(data){
				$("#containerCenter .success").remove();
				$("#containerCenter").prepend(get_message('success', data.success));
			},'json');
		}
		else {
			$(this).removeClass('unpublished');
			$(this).addClass('published');
			$(this).html('Published');
					
			$.post(CMSURL+"admin/categories/publish", {'category_id':$("#category_id").val(), 'new_status':'active'}, function(data){
				$("#containerCenter .success").remove();
				$("#containerCenter").prepend(get_message('success', data.success));
			},'json');
		}
	});
	
	
	// add classes to alternate rows on listings
	$("ul#sortable li:nth-child(even)").addClass("odd");	
	$("#sortable").sortable({items: 'li.listRow', update: function(event, ui) { update_sort_order()}, 
		helper: "clone",
		appendTo: "body"
	});
	
	update_sort_order = function(){
		
		$("ul#sortable li.listHead div").removeClass("selected");
		$("ul#sortable li.listHead div#sort_order_head").addClass("selected");
		
		$("ul#sortable li").removeClass("odd");
		$("ul#sortable li:nth-child(even)").addClass("odd");
		
		var positions = new Array();
		var contor = 0;
		$("ul#sortable li.listRow").each(function(){			
			$(".sort_order_row", this).html("<span>"+(contor+1)+"</span>");			
			var el_id = $(this).attr("rel");
			positions[contor]= el_id;
			contor++;
		});
		
		$.post(CMSURL+"admin/projects/order_projects", {'positions':positions}, function(data){},'json')	
	}
	
	/*** Publish/Unpublish Pages ***/
	$(".activeStatus").click(function(e){
		e.preventDefault();
		var project_id = $(this).attr('href');
		if($(this).hasClass('active')){
			var new_class = 'inactive';
			$(this).removeClass('active');
			$(this).addClass('inactive');
			$(this).attr('title', 'Click to publish');
		}
		else {
			var new_class = 'active';
			$(this).removeClass('inactive');
			$(this).addClass('active');
			$(this).attr('title', 'Click to unpublish');
		} 
		
		$.post(CMSURL+"admin/projects/publish", {'project_id':project_id, 'new_status':new_class}, function(data){
			$("#messages .success").remove();
			$("#messages").prepend(get_message('success', data.success));
		},'json')
	});
	
});