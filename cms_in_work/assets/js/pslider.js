/**
 * PSlider - custom slider
 * @author Nicu Petra
 */

var globalActiveSlider = [];
var globalImages = [];
var globalTotalImages = [];
var globalSlideshow = [];

(function(jQuery){
	
    jQuery.fn.pSlider = function(options) {
    	
        return {
        	init : function(id){        		
        		var parent = $('#sliderContainer_'+id);
        		globalSlideshow[id] = $('.slideshow', parent);
        		
        		// put all images into images array => then set left position for each image
        		var images = [];
        		var contor = 0;
        		var previous_left = 0;
        		var last_img_width = 0;
            	jQuery('img', globalSlideshow[id]).each(function(index, each){       			
        			images[index] = jQuery(this).attr('src');
        			
        			if(contor==0){
        				var left = 0
        			}
        			else {
        				var left = previous_left + last_img_width + 10; 
        			}
        			previous_left = left;        			
        			last_img_width = parseInt(jQuery(this).width());
        			
        			jQuery(this).css('left', left);
        			contor++;
        		});
            	globalImages[id] = images;
            	globalTotalImages[id] = jQuery('img', globalSlideshow[id]).length;  
            	globalActiveSlider[id] = 0;  
        	},
        	resize : function(id){
        		
        		var contor = 0;
        		var previous_left = 0;
        		var last_img_width = 0;
        		
            	jQuery('img', globalSlideshow[id]).each(function(index, each){	
        			if(contor==0){
        				var left = 0
        			}
        			else {
        				var left = previous_left + last_img_width + 10; 
        			}
        			previous_left = left;        			
        			last_img_width = parseInt(jQuery(this).width());
        			
        			jQuery(this).css('left', left);
        			contor++;
        		});
        	}, 
            next : function(id){
            	
            	// automatically finish any active animation 
            	jQuery('img', globalSlideshow[id]).finish();
            	
            	// check for end => then set next slider 
            	if(globalActiveSlider[id]+1==globalTotalImages[id])
                	this.setSlide(id, 0, 'next');
            	else 
                	this.setSlide(id, globalActiveSlider[id]+1, 'next');
            },
            prev : function(id){
            	
            	// automatically finish any active animation 
            	jQuery('img', globalSlideshow[id]).finish();
            	
            	// check for start => then set previous slider
            	if(globalActiveSlider[id]==0)
            		this.setSlide(id, globalTotalImages[id]-1, 'prev');
            	else 
            		this.setSlide(id, globalActiveSlider[id]-1, 'prev');
            },
            setSlide: function(id, i, direction){
            	
            	// compare with current active slider
            	if(i==globalActiveSlider[id])
            		return;
            	
            	// set new active slider
            	globalActiveSlider[id] = i;
            	
            	// Moving forward
            	if(direction=='next'){  
            		// width of first element
            		var minus_width = parseInt(jQuery('img', globalSlideshow[id]).eq(0).width()) + 10;
            		var left_side_of_last_img = parseInt(jQuery('img', globalSlideshow[id]).eq(globalTotalImages[id]-1).css('left'));
            		var width_of_last_img = parseInt(jQuery('img', globalSlideshow[id]).eq(globalTotalImages[id]-1).width());
            		
            		jQuery('img', globalSlideshow[id]).each(function(index, element){   			
            		    if(index==0){
            				
            				jQuery(element).animate({"left": "-="+minus_width+"px"}, slideSpeed, 'easeInSine', function(){
            					var clone = jQuery('img', globalSlideshow[id]).eq(0).clone().hide().css('left', (left_side_of_last_img - minus_width + width_of_last_img + 10));            					
            					globalSlideshow[id].append(clone);
            					clone.fadeIn();
            					
            					// remove old image
            					jQuery('img', globalSlideshow[id]).eq(0).remove();
                        	});
            			}
            			else {
    						jQuery(element).animate({"left": "-="+(minus_width)+"px"}, slideSpeed, 'easeInSine');            				
            			}
            		});
            	}
            	// Moving backward
            	else if(direction='prev'){
            		// width of last element
            		var plus_width = parseInt(jQuery('img', globalSlideshow[id]).eq(globalTotalImages[id]-1).width()) + 10;
            		var width_of_last_img = parseInt(jQuery('img', globalSlideshow[id]).eq(globalTotalImages[id]-1).width());
            		
            		// first clone the last element and on first position
            		var clone = jQuery('img', globalSlideshow[id]).eq(globalTotalImages[id]-1).clone().css('left', (-width_of_last_img - 10));            					
					globalSlideshow[id].prepend(clone);
					
					// remove old image
					jQuery('img', globalSlideshow[id]).eq(globalTotalImages[id]).remove();
					
            		// then move all of them to the right with + plus_width
					jQuery('img', globalSlideshow[id]).each(function(index, element){
						jQuery(element).animate({"left": "+="+(plus_width)+"px"}, slideSpeed, 'easeInSine');
            		});
            	}
            	
            	// set marker 
            	this.setMarker(id);
            },
            setViaMarker : function(id,i){
            	
            	// compare with current active slider
            	if(i==globalActiveSlider[id])
            		return;
            	            	
            	// next 
                else if(i>globalActiveSlider[id]){
                	for(t=globalActiveSlider[id]; t<i; t++){
                		this.next(id);
                		jQuery('img', globalSlideshow[id]).finish();
                	}                	
                }
            	// prev
            	else if(i<globalActiveSlider[id]){            		
            		for(t=globalActiveSlider[id]; t>i; t--){
                		this.prev(id);
                		jQuery('img', globalSlideshow[id]).finish();
                	}
            	}
            },
            setMarker : function(id){            	
            	$('.markers li', $('#sliderContainer_'+id)).removeClass('marker-active'); 
            	$('.markers li[rel="'+globalActiveSlider[id]+'"]', $('#sliderContainer_'+id)).addClass('marker-active'); 
            }
       };
    };
})(jQuery);

/**
 * Fix things on browser resize event
 */
function resizedw(){
	if(globalSlideshow){
		// finish all animations 
		jQuery('.slideshow img').finish();		
		$.each(globalSlideshow, function(index,nothing){
			jQuery().pSlider().resize(index);
		});
	}
}

/**
 * Trigger resize 
 */
var doit; 
jQuery(window).resize(function(){
	clearTimeout(doit);
	doit = setTimeout(resizedw, 200);
});


$(document).ready(function(){
	
	// init variables
	var totalindexcount = $('.scrollMe').length;
	var uiReady = true;
	var movedToBottom = false;
	var index = 0;
	
	/**
	 * Bind mouse wheel event
	 */
	$('body').bind('mousewheel',function(event, delta, deltaX, deltaY) {
		event.preventDefault();
		if (deltaY > 0) {
		    moveUp();
		} else if (deltaY < 0) {
		    moveDown();
		}
		return false;
	});
	
	/**
	 * Move Up
	 */
	var moveUp = function() {
		if (index < totalindexcount && index > 0 && uiReady == true) {
			if (movedToBottom == true) {
				movedToBottom = false;
				moveVertical();
			} else {
				index--;
				moveVertical();
			}
		} else if (index < totalindexcount && index > -1 && uiReady == true) {
			moveVertical();
		}
	}

	/**
	 * Move Down
	 */
	var moveDown = function() {
		if (index < (totalindexcount-1) && index > -1 && uiReady == true) {
			index++;
			moveVertical();
		}
	}
	
	/**
	 * Move vertical function
	 */
	function moveVertical() {
		if (index < totalindexcount && index > -1 && uiReady == true) {
			uiReady = false;
			$(window).scrollTo('.scrollMe:eq('+index+')', 600, {easing: 'easeInOutExpo', offset: - 100, axis: 'y', onAfter:function(){
				uiReady = true;
				}
			});
		}
	}	
});