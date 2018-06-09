/** Crops Library **/

$(document).ready(function() {
	
	$("input").on('click', function(){
		$(this).focus();
	})
	
	/*** Action Links - SAVE ***/
	$(".actionLinks a.save").click(function(e){
		e.preventDefault();
		$("#cropForm").submit();
	})
})