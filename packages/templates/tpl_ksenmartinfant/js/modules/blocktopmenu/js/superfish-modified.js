jQuery(function() {
		var container_width = jQuery('body').find('.container').width();
		if (container_width < 1170){

	
		/* Mobile */
		jQuery('#menu-wrap').addClass('mobile').find('#menu-trigger').show();		
		
		jQuery('.mobile #menu-trigger').on('click touchstart',
			function() {
			var catUl = jQuery(this).next('ul#menu-custom');
			var anotherFirst = jQuery('.header-button').find('ul');
			var anotherSecond = jQuery('#header').find('#cart_block');
			if (anotherFirst.is(':visible')) {
				anotherFirst.slideUp(),
				jQuery('.header-button').find('.arrow_header_top').removeClass('mobile-open')
			}
			if (anotherSecond.is(':visible')) {
				anotherSecond.slideUp();
				jQuery('#header_user').removeClass('close-cart')
			}
			if(catUl.is(':hidden')) {
				catUl.slideDown(),
				jQuery(this).addClass('menu-custom-icon')	
			}
			else {
				catUl.slideUp(),
				jQuery(this).removeClass('menu-custom-icon');
			}
			return false
		}
		)
		jQuery('#menu-wrap.mobile #menu-custom').on('click touchstart', function(e){
			e.stopPropagation();
		});
		
		jQuery('.main-mobile-menu ul ul').addClass('menu-mobile-2'); 
		jQuery('#menu-custom ul ').addClass('menu-mobile-2'); 
		jQuery('#menu-custom  li').has('.menu-mobile-2').prepend('<span class="open-mobile-2"></span>');
		jQuery("#menu-custom   .open-mobile-2").toggle(
			function() {
				alert('1');
				jQuery(this).parent().find('.menu-mobile-2').slideToggle("slow"),{
					duration: 'slow',
					easing: 'linear'
				};
				jQuery(this).addClass('mobile-close-2');
			},
			function() {
				alert('2');
				jQuery(this).parent().find('.menu-mobile-2').slideToggle("slow"),{
					duration: 'slow',
					easing: 'linear'
				};
				jQuery(this).removeClass('mobile-close-2');
			})
		jQuery(document).on('click  touchstart', function(){
			jQuery('.mobile #menu-trigger').removeClass('menu-custom-icon').next('ul#menu-custom').slideUp();	
		})
			}
		if (container_width > 1170){
			jQuery('#menu-wrap').removeClass('mobile'),
			jQuery('#menu-wrap').find('#menu-trigger').hide();			
		}
		
}); 


 


