/** 
 * Page Library 
 **/
$(document).ready(function() {
	
	/**
	 * Detect if content has been changed
	 */
	$('#pageForm input').bind('textchange', function (event, previousText) {
		$('.actionLinks .save').addClass('changed');
	});
	$('#pageForm textarea').bind('textchange', function (event, previousText) {
		$('.actionLinks .save').addClass('changed');
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
					
			$.post(CMSURL+"admin/pages/publish", {'page_id':$("#page_id").val(), 'new_status':'inactive'}, function(data){
				$("#containerCenter .success").remove();
				$("#containerCenter").prepend(get_message('success', data.success));
			},'json');
		}
		else {
			$(this).removeClass('unpublished');
			$(this).addClass('published');
			$(this).html('Published');
					
			$.post(CMSURL+"admin/pages/publish", {'page_id':$("#page_id").val(), 'new_status':'active'}, function(data){
				$("#containerCenter .success").remove();
				$("#containerCenter").prepend(get_message('success', data.success));
			},'json');
		}
	});
		
	/** 
	 * Action Links - SAVE
	 **/
	$(".actionLinks a.save").click(function(e){
		e.preventDefault();
		
		// clear previous error messages
		$("#messages").html('');	
		
		$("#pageForm").submit();
	});
	
	/**
	 * Validate page name
	 */
	$("#pageForm").validate({
		rules: {
			title: {
		        required: true
		    }
		},    
		messages: {
		    title: {
		        required: "Please enter Name",
		    }
		}
	});
	
	
	/**
	 * Alternate section background
	 */
	$(".section:nth-child(even)").addClass('alternateBG');
	
	
	/**
	 * Remove section
	 */
	removeSection = function(element){
		
		$(element).closest(".section").remove();
		
		// alternate section background
		$(".section").removeClass('alternateBG');
		$(".section:nth-child(even)").addClass('alternateBG');	
	}
	
	/**
	 * Add wide image section
	 */
	addWideImageSection = function(){
		
		$.post(CMSURL+"admin/pages/addWideImageSection", function(data){		
			
			$("#sections").append(data.html);
			
			// alternate section background
			$(".section").removeClass('alternateBG');
			$(".section:nth-child(even)").addClass('alternateBG');		
		},"json");
	}
	
	/**
	 * Add text section
	 */
	addNewSection = function(){
		
		$.post(CMSURL+"admin/pages/addNewSection", function(data){
			
			$("#sections").append(data.html);
			
			// alternate section background
			$(".section").removeClass('alternateBG');
			$(".section:nth-child(even)").addClass('alternateBG');
			
			initTinyMCE($("textarea[name='section["+data.key+"][text]']"));
		},"json");
	}
	
	/**
	 * New page => Insert one section
	 */
	if($('#sections').children().length==0)
		addNewSection();
});