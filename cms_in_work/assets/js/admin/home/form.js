/** Home **/

$(document).ready(function() {
	
	/*** Action Links - SAVE ***/
	$(".actionLinks a.save").click(function(e){
		e.preventDefault();
		$("#homeForm").submit();
	});
	
	/**
	 * Validate page name
	 */
	$("#homeForm").validate({
		rules: {
			headline: {
		        required: true
		    }
		},    
		messages: {
			headline: {
		        required: "Please enter Headline",
		    }
		}
	});	
})