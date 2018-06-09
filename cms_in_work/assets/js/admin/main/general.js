/**
 * General CMS Library
 */
$(window).load(function(){
	// fix on page load
	fixBreadcrumbs();
});
$(document).ready(function(){
	
	/**
	 * Fix long breadcrumbs issue
	 */
	fixBreadcrumbs = function(){
		
		//  reset width's set
		$('#breadcrumbs a').width('auto')
		
		var granted_width = 150;
		var bc_width = $('#breadcrumbs').width();
		var total_el = $('#breadcrumbs a').length;
		var total_width = 0;
		$('#breadcrumbs a').each(function(index,element){
			total_width+= $(this).innerWidth();
		});
		
		// apply fix
		if(total_width>bc_width){
				
			// ON WORKING .........
			// get last element
			var last = $('#breadcrumbs a').eq(total_el-1);
			var last_inner_width = last.innerWidth();
			
			var padding = last.innerWidth() - last.width();
			
			// verify if in case we shorten last element ... the others will fit
			if(total_width - last_inner_width <= bc_width){
				var calculated_last_width = bc_width - (total_width - last_inner_width);
				last.width(calculated_last_width - padding);
			}
			// we need to shorter another element eq(-2)
			else {
				
				var calculated_last_width = bc_width - (total_width - $('#breadcrumbs a').eq(total_el-2).innerWidth());
				$('#breadcrumbs a').eq(total_el-2).width(calculated_last_width - padding);
				
				
				// get width of previous  elements ....this being last ....
				var tmp_width = 0;
				$('#breadcrumbs a').each(function(index,element){
					if(index <= total_el - 2){
						console.log(element);
						
						tmp_width+= $(this).innerWidth();
					}	
				});
				
				var diff = bc_width - tmp_width;
				$('#breadcrumbs a').eq(total_el-2).width(diff);
			}
		}
		
	}
	
	// fix cache issue
	$.ajaxSetup({ cache: false });
	
	// fixing left side div height
	fixRightContainer = function(){
		$("#rightContainer").width(parseInt($(window).width())-230);
	}
	// fix on page load
	fixRightContainer();
		
	// detect if content has been changed
	$('input.form-control').on('input', function () {
		$('.actionLinks .save').addClass('changed');
	});
	$('textarea.form-control').on('keyup', function () {
		$('.actionLinks .save').addClass('changed');
	});
	
	
	// save action
	$('.actionLinks .save').click(function(e){
		e.preventDefault();
		
		// clear previous error messages
		$("#messages").html('');		
		
		var form_id = $(this).data('form');
		if(form_id){
			$('#'+form_id).submit();
		}
	});
	
	// Jquery Validator defaults
	jQuery.validator.setDefaults({
		focusInvalid: false,
		onfocusout: false,
		onkeyup: false,
		ignore: "",
		errorPlacement: function(error, element) {			
			writeError(error.text()+'<br>');
		}
	});
	
	// Append close button to error messages boxes
	appendCloseToBox = function(){
		if($('.msgBox').find('.close_msg').remove()){
			$(".msgBox").append('<a class="close_msg" onclick="closeMsgBox(this)">Close</a>');
		}
	}
	appendCloseToBox();
	
	// close message box
	closeMsgBox = function(el){
		$(el).parent().fadeOut("slow");
	}
	
	// delete confirmation
	$('.actionLinks .delete').click(function(e){
		e.preventDefault();
		var element = $(this);
		BootstrapDialog.confirm(element.data('message'), function(result){
            if(result) {
                window.location = element.attr('href');
            }
        });
	});
	
	// Publish/Unpublish from add/edit forms  
	$('.actionLinks .publish_unpublish').on('click', function(e){
		e.preventDefault();

		var new_status = $(this).hasClass('published') ? 0 : 1;
		var _this = this;
		
		$.post($(this).attr('href'), {'status':new_status}, function(data){
			if(data.success){
				if($(_this).hasClass('published')){
					$(_this).removeClass('published');
					$(_this).addClass('unpublished');
					$(_this).html('Unpublished');
				}
				else {
					$(_this).removeClass('unpublished');
					$(_this).addClass('published');
					$(_this).html('Published');
				}
				writeSuccess(data.msg);
			}	
		},'json');
	});
	
	// Publish/Unpublish from lists
	$('.list .publish_unpublish').on('click', function(e){
		e.preventDefault();

		var new_status = $(this).hasClass('iconActive') ? 0 : 1;
		var _this = this;
		
		$.post($(this).attr('href'), {'status':new_status}, function(data){
			if(data.success){
				if($(_this).hasClass('iconActive')){
					$(_this).removeClass('iconActive');
					$(_this).addClass('iconInactive');
				}
				else {
					$(_this).removeClass('iconInactive');
					$(_this).addClass('iconActive');
				}
				writeSuccess(data.msg);
			}	
		},'json');
	});
	
	// write success function 
	writeSuccess = function(msg){
		$('#messages').html('<div class="success msgBox">'+msg+'</div>');
		appendCloseToBox();
	}
	
	// write error function 
	writeError = function(msg){
		var exist = $('#messages').find('.error').length;
		if(exist>0){
			$('#messages .error').append(msg);
		}
		else {
			$('#messages').append('<div class="error msgBox"></div>');
			$('.error').html(msg);
		}
		appendCloseToBox();
	}
	
	// add classes to alternate rows on listings
	$("table.list tr:nth-child(even)").addClass("odd");
	
	// alternate rows on all lists
	$("ul.list li:nth-child(even)").addClass("odd");	
	$("#sortable").sortable({items: 'li.listRow', update: function(event, ui) { update_sort_order()}});
	
	// update sort order on all lists 
	update_sort_order = function(){
		
		$("ul#sortable li").removeClass("odd");
		$("ul#sortable li:nth-child(even)").addClass("odd");
		
		var positions = new Array();
		var contor = 0;
		$("ul#sortable li.listRow").each(function(){
			
			$(".sort_order_row", this).html("<span>"+(contor+1)+"</span>");
			
			var el_id = $(this).attr("rel");
			positions[contor]= el_id;
			contor++;
		})
		
		var module = $('#mainContent').data('module');
		if(!module){
			alert('Module not found.')
			return;
		}
		$.post(CMSURL+'admin/'+module+'/order_results', {'positions':positions}, function(data){},'json')	
	}
	
	// Drop down menus
	$(document.body).on('click', '.dropdown-menu li', function(event){
		var $target = $(event.currentTarget);
		$target.closest('.btn-group')
			.find('[data-bind="label"]').text($target.text()).end()
		    .children('.dropdown-toggle').dropdown('toggle').end()
		$('#'+$target.closest('.btn-group').data('hidden')).val($target.data('id'));
		return false;
	});
	
	/**
	 * TinyMCE init function
	 */
	initTinyMCE = function(selector){		
		if(!selector)
			selector = 'textarea.tinymce';
		
		$(selector).tinymce({
			plugins: [
				"advlist autolink lists link image charmap print preview anchor",
				"searchreplace visualblocks code fullscreen",
				"insertdatetime media table contextmenu paste"
			],
			toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
			autosave_ask_before_unload: false,
			max_height: 300,
			height : 180,
			min_height: 160,
			max_width:1010,
			width:1010,
			resize: "both",
			setup: function (editor) {
		        editor.on('keyup', function (e) {  
		        	$('.actionLinks .save').addClass('changed');
		        });
		    }
		});	
	}
	// call TinyMCE init function on page load
	if(typeof tinymce != 'undefined'){
		initTinyMCE();
	}
	
	
	/**
	 * Filters toggle
	 */
	$('.filters #filtersToggle').click(function(){
		if($('.filters form').hasClass('hide')){
			$('.filters form').removeClass('hide');
			$(this).html('(hide form)');
		}
		else {
			$('.filters form').addClass('hide')
			$(this).html('(show form)');			
		}
	});	
	
	removeFilters = function(url){
		window.location = url;
	}
	
	// Image captions default
	$('.imageCaption').each(function(index, element){
		if($.trim($(this).html())==''){
			$(this).html('caption');
		}
	});
	// Image URL default
	$('.imageUrl').each(function(index, element){
		if($.trim($(this).html())==''){
			$(this).html('URL');
		}
	});
	
});

/**
 * Return message box
 */
function get_message(type, message){
	return '<div class="'+type+'"><p>'+message+'</p><a class="close_msg msgBox" onclick="closeMsgBox(this)">Close</a></div>';
}

// Resize event
$(window).resize(function(){
	fixRightContainer();
	fixBreadcrumbs();
});