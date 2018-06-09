/** News form **/

$(document).ready(function() {
	
	/*** Action Links - SAVE ***/
	$(".actionLinks a.save").click(function(e){
		e.preventDefault();
		$("#projectForm").submit();
	});
	
	/**
	 * Validate page name
	 */
	$("#projectForm").validate({
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
					
			$.post(CMSURL+"admin/projects/publish", {'project_id':$("#project_id").val(), 'new_status':'inactive'}, function(data){
				$("#containerCenter .success").remove();
				$("#containerCenter").prepend(get_message('success', data.success));
			},'json');
		}
		else {
			$(this).removeClass('unpublished');
			$(this).addClass('published');
			$(this).html('Published');
					
			$.post(CMSURL+"admin/projects/publish", {'project_id':$("#project_id").val(), 'new_status':'active'}, function(data){
				$("#containerCenter .success").remove();
				$("#containerCenter").prepend(get_message('success', data.success));
			},'json');
		}
	});
	
	
	/**
	 * Remove section
	 */
	removeSection = function(element){
		
		$(element).closest(".section").remove();
		
		// update section names
		var index = 1;
		$("h2.sectionNumber").each(function(){
			
			$(this).attr('data-index', index).html('Section '+index);
			
			index++;
		});
		
		// alternate section background
		$(".section").removeClass('alternateBG');
		$(".section:nth-child(even)").addClass('alternateBG');	
	}
		
	/**
	 * Add section
	 */
	addSection = function(index){
		
		if(typeof index != 'undefined'){
			var next_index = index;
		}
		else {
			
			index = 0;
			// get max index
			$("h2.sectionNumber").each(function(){
				if(parseInt($(this).attr('data-index'))>index)
					index = parseInt($(this).attr('data-index'));
			});
			
			var next_index = index + 1;
		}
		
		$.post(CMSURL+"admin/projects/addSection",{'index': next_index}, function(data){
			
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
		addSection(1);
	
	// alternate section background
	$(".section").removeClass('alternateBG');
	$(".section:nth-child(even)").addClass('alternateBG');	
})