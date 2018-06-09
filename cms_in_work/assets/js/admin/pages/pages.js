/** List of Pages **/

$(document).ready(function() {
	
	/*** Publish/Unpublish Pages ***/
	$(".activeStatus").click(function(e){
		e.preventDefault();
		var page_id = $(this).attr('href');
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
		
		$.post(CMSURL+"admin/pages/publish", {'page_id':page_id, 'new_status':new_class}, function(data){
			$("#pagesList .success").remove();
			$("#pagesList").prepend(get_message('success', data.success));
		},'json')
	});
	
	/*** Rows per page***/
	$(".pagination #listLimit_container ul li").on('click',function(){
		$.post(CMSURL+"admin/pages/list_pages", {'pages_list_limit':$(this).html()}, function(){
			window.location = CMSURL+"admin/pages/list_pages";
		})
	});
})