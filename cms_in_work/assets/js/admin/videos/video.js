/** Collections Add **/

$(document).ready(function() {
	
	/*** Action Links - Close ***/
	$("#popupTop a.close").click(function(e){
		e.preventDefault();
		window.parent.Shadowbox.close();		
	})
})