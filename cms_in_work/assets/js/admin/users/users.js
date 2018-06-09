/** List of users **/

$(document).ready(function() {
	
	/*** Activate/Deactivate Users ***/
	$(".activeStatus").click(function(e){
		e.preventDefault();
		var user_id = $(this).attr('href');
		if($(this).hasClass('active')){
			var new_class = 'inactive';
			$(this).removeClass('active');
			$(this).addClass('inactive');
			$(this).attr('title', 'Click to activate');
		}
		else {
			var new_class = 'active';
			$(this).removeClass('inactive');
			$(this).addClass('active');
			$(this).attr('title', 'Click to deactivate');
		} 
		
		$.post(CMSURL+"admin/users/activate", {'user_id':user_id, 'new_status':new_class}, function(data){
			$("#usersList .success").remove();
			$("#usersList").prepend(get_message('success', data.success));
		},'json')
	})
	
	/*** Rows per page***/
	$(".pagination #listLimit_container ul li").on('click',function(){
		$.post(CMSURL+"admin/users/list_users", {'users_list_limit':$(this).html()}, function(){
			window.location = CMSURL+"admin/users/list_users";
		})
	})
})