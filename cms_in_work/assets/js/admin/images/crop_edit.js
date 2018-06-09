/** Crop Image Library **/
var ias = "";
$(document).ready(function() {
	
	/*** Save Image ***/
	$(".saveImage").click(function(e){
		e.preventDefault();
		$("#imageForm").submit();
	})
	
	/*** Action Links - Close ***/
	$("#popupTop a.close").click(function(e){
		e.preventDefault();
		try {
			window.parent.executeonclosecropimage();
		}
		catch(err){ // do nothing
		}	
		window.parent.Shadowbox.close();		
	})
	
	$(".dropdown-menu li").on('click',function(){

		var crop_id = $(this).data("id");
					
		if(crops[crop_id] && crop_id>0){
			
			var x2 = crops[crop_id]["width"];
			var y2 = crops[crop_id]["height"];
			
			var tmp = new Image();
			tmp.src = $("#imageCropContainer img").attr("src");
			var img_width = tmp.width;
			var img_height = tmp.height;
			
			// set resize dimensions (second operation done after crop)
			$("#resize_width").val(x2);
			$("#resize_height").val(y2);
			
			if(img_width > 500){
				var decrease_ratio = img_width / 500;
				x2 = x2 / decrease_ratio;
				y2 = y2 / decrease_ratio;
			}
						
			// set values on Crop Template change
			$("#coord_x1").val(0);
		    $("#coord_x2").val(x2);
		    $("#coord_y1").val(0);
		    $("#coord_y2").val(y2)			
		
			$('#imageCropContainer img').imgAreaSelect({
				aspectRatio: x2+":"+y2,	
				x1:0,
				y1:0,
				x2:x2,
				y2:y2,
				handles: true,
				onInit: oninit,
				onSelectChange: adjustDimensions, 
				onCancelEvent:cancelEvent
			});
		}
		else {
			$('#imageCropContainer img').imgAreaSelect({hide:true});
		}
		
	});
	
	// get instance
	function oninit(){
		ias = $('#imageCropContainer img').imgAreaSelect({instance:true});
	}
	
	$("#doCrop").click(function(){
		
		if($("#coord_x2").val()==0){
			BootstrapDialog.alert('Please make a selection');
			return false;
		}
		
		$("#loadingAnimation").fadeIn();
		$.post(CMSURL+"admin/images/crop", {"resize_width":$("#resize_width").val(),"resize_height":$("#resize_height").val(),"entity_id":$("#entity_id").val(), "entity_type":$("#entity_type").val(),"image_id":$("#image_id").val(),"coord_x1":$("#coord_x1").val(),"coord_x2":$("#coord_x2").val(),"coord_y1":$("#coord_y1").val(),"coord_y2":$("#coord_y2").val()}, function(data){
			if(data.success){
				
				$("#coord_x1").val(0);
				$("#coord_x2").val(0);
				$("#coord_y1").val(0);
				$("#coord_y2").val(0); 
				
				$('#imageCropContainer img').imgAreaSelect({hide:true});
				$('#imageCropContainer img').attr("src", $('#img_src').val()+"?"+new Date().getTime());
				$("#loadingAnimation").fadeOut();
			}
		},"json");
	})
	
	$("#doRevert").click(function(){
		$("#loadingAnimation").fadeIn();
		$.post(CMSURL+"admin/images/revert", {"entity_id":$("#entity_id").val(), "entity_type":$("#entity_type").val(),"image_id":$("#image_id").val()}, function(data){
			if(data.success){	
				
				$("#coord_x1").val(0);
				$("#coord_x2").val(0);
				$("#coord_y1").val(0);
				$("#coord_y2").val(0);
				
				$('#imageCropContainer img').imgAreaSelect({hide:true});
				$('#imageCropContainer img').attr("src", $('#img_src').val()+"?"+new Date().getTime());
				$("#loadingAnimation").fadeOut();
			}
		},"json");
	})
	
	function adjustDimensions(img, selection) {
		
	    if (!selection.width || !selection.height)
	        return;
	    
	    $("#coord_x1").val(selection.x1);
	    $("#coord_x2").val(selection.x2);
	    $("#coord_y1").val(selection.y1);
	    $("#coord_y2").val(selection.y2);    
	}
	
	function cancelEvent(){
		 $("#coord_x1").val(0);
		 $("#coord_x2").val(0);
		 $("#coord_y1").val(0);
		 $("#coord_y2").val(0);
	}
})