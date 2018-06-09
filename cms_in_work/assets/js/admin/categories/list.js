/** 
 * Categories list   
 **/
$(document).ready(function() {
	
	/*** Publish/Unpublish Pages ***/
	$(".activeStatus").click(function(e){
		e.preventDefault();
		var category_id = $(this).attr('href');
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
		
		$.post(CMSURL+"admin/categories/publish", {'category_id':category_id, 'new_status':new_class}, function(data){
			$("#categoriesList .success").remove();
			$("#categoriesList").prepend(get_message('success', data.success));
		},'json')
	})
	
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
		
		$.post(CMSURL+"admin/categories/order_categories", {'positions':positions}, function(data){},'json')	
	}
});