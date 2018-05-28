			(function($) {          
			    $(document).ready(function(){                    
			        $(window).scroll(function(){                          
			            if ($(this).scrollTop() > 300) {
			                $('#menu').fadeIn(500);
			                $('nav').fadeOut(500);
			            } else {
			                $('#menu').fadeOut(500);
			                $('nav').fadeIn(500);
			            }
			        });
			    });
			})(jQuery);
