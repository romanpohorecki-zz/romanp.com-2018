/** Contact **/

$(document).ready(function() {
	
	/*** Action Links - SAVE ***/
	$(".actionLinks a.save").click(function(e){
		e.preventDefault();
		$("#contactForm").submit();
	});
})