/**
 * Settings Library
 */
$(document).ready(function(){
		
	/*** Action Links - SAVE ***/
	$(".actionLinks a.save").click(function(e){
		e.preventDefault();
		$("#settingsForm").submit();
	});
});