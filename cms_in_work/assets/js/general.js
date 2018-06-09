/**
 * General library
 */

jQuery(document).ready(function() {
	
	/**
	 * Active section on menu click
	 */
	$('.menuGroup a').on('click', function(){				
		$('.menuGroup a').removeClass('active');
		$(this).addClass('active');
		
		// close mobile menu
		if(parseInt($(window).width())<=700)
			$('.menuGroup').toggle();
	});
	
	/**
	 * Scroll handler
	 */ 
	$(window).on('scroll', function(){
		
		if($('body').attr('id')=='home'){
		
			var bottomScroll = $(document).height() - $(window).height();
			
			if(bottomScroll == $(window).scrollTop()){
				if($('.menuGroup a[class="active"]').data('name')!='contact'){
					if($('.menuGroup a').hasClass('active'))
						$('.menuGroup a').removeClass('active');
					$('.menuGroup a[data-name="contact"]').addClass('active');
				}	
			}
			else {
				// Active menu item depending on page position
				var sections = ['about','contact'];
				$.each(sections, function(index, section){
					var scrollPos = $(window).scrollTop();
					var sectionPos = $('#'+section).offset().top; 
					var sectionHeigth = $('#'+section).height();
					if(scrollPos+1 > sectionPos && scrollPos < sectionPos + sectionHeigth) {				
						if($('.menuGroup a[class="active"]').data('name')!=section){
							if($('.menuGroup a').hasClass('active'))
								$('.menuGroup a').removeClass('active');
							$('.menuGroup a[data-name="'+section+'"]').addClass('active');
						}				
					}			
				});
			}
		}
	});
		
	
	/**
	 * Menu toggle
	 */
	$('.button-menu').click(function(){
		$('.menuGroup').slideToggle(600);
	});
});

$(window).bind('mousewheel DOMMouseScroll', function(event){
//	scrollStart();
});
//document.addEventListener("touchmove", scrollStart, false);
//document.addEventListener("mousedown", scrollStart, false);