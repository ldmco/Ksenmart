	jQuery(function(){
	jQuery('#menu-custom > li').eq(0).addClass('one');
	jQuery('#menu-custom > li').eq(1).addClass('two');
	jQuery('#menu-custom > li').eq(2).addClass('three');
	jQuery('#menu-custom > li').eq(3).addClass('four');
	jQuery('#menu-custom > li').eq(4).addClass('five');
	jQuery('#menu-custom > li').eq(5).addClass('six');
	jQuery('#menu-custom > li').eq(6).addClass('one');
	jQuery('#menu-custom > li').eq(7).addClass('two');
	jQuery('#menu-custom > li').eq(8).addClass('three');
	jQuery('#menu-custom > li').eq(9).addClass('four');
	jQuery('#menu-custom > li').eq(10).addClass('five');
	jQuery('#menu-custom > li').eq(11).addClass('six');
	});
	
	
jQuery(document).ready(function() {
	
    jQuery('.favorities .ajax_block_product, .watched .ajax_block_product').prepend('<a class="close btn btn-danger l-lose_t_product" href="javascript:void(0);" title="">Убрать</a>');
    
    jQuery('.favorities .ajax_block_product .l-lose_t_product, .watched .ajax_block_product .l-lose_t_product').on('click', function(){
        var item = jQuery(this).parents('.ajax_block_product');
        var type = jQuery(this).parents('.tab-pane').data().type;
        var id   = item.data().id;

        if(typeof id != 'undefined'){
            jQuery.ajax({
                type: 'POST',
                url: URI_ROOT+'index.php?option=com_ksenmart&tmpl=ksenmart&task=profile.lose_t_product',
                data: {
                    id: id,
                    type: type
                },
                success: function(data){
                    if(data == ''){
                        item.fadeOut(400, function(){
                            jQuery(this).remove();
                        });
                    }
                }
            });
        }        
    });
	
	    jQuery('#featured_products .list_carousel li').last().addClass ('last');
	    jQuery('#product_comments_block_tab > div').last().addClass('last');
	    jQuery('#viewed-products_block_left ul li').last().addClass('last');
	   
	    jQuery('#product_list li:first-child').addClass('first_item');
		jQuery('#product_list li:nth-child(3n+1)').addClass('product_list_1');
		jQuery('#product_list li:nth-child(1)').addClass('num-1');
		jQuery('#product_list li:nth-child(2)').addClass('num-2');
		jQuery('#product_list li:nth-child(3)').addClass('num-3');
		jQuery('#product_list li:last-child').addClass('last_item');
		
		jQuery('.mod_ksm_filter span.layered_close a').live('click', function(e)
		{
			if (jQuery(this).html() == '&lt;')
			{
				jQuery('#'+jQuery(this).attr('rel')).show(600);
				jQuery(this).html('v');
				jQuery(this).parent().removeClass('closed');
			}
			else
			{
				jQuery('#'+jQuery(this).attr('rel')).hide(600);
				jQuery(this).html('&lt;');
				jQuery(this).parent().addClass('closed');
			}
			
			e.preventDefault();
		});		
});

//   TOGGLE FOOTER

jQuery(window).load(function(){
	if (jQuery(document.body).width()< 751){
		jQuery('.modules .block h4').on('click', function(){
			jQuery(this).toggleClass('active').parent().find('.toggle_content').slideToggle('medium');
		})
		jQuery('.modules').addClass('accordion').find('.toggle_content').slideUp('fast');
		}else{
		jQuery('.modules h4').removeClass('active').off().parent().find('.toggle_content').slideDown('fast');
		jQuery('.modules').removeClass('accordion');
		}
  
});
var responsiveflag = false;
function accordion(status){	
		if(status == 'enable'){
			jQuery('.modules .block h4').on('click', function(){
				jQuery(this).toggleClass('active').parent().find('.toggle_content').slideToggle('medium');
			})
			jQuery('.modules').addClass('accordion').find('.toggle_content').slideUp('fast');
		}else{
			jQuery('.modules h4').removeClass('active').off().parent().find('.toggle_content').slideDown('fast');
			jQuery('.modules').removeClass('accordion');
		}
	}		
function toDo(){
	   if (jQuery(document.body).width() < 751 && responsiveflag == false){
		    accordion('enable');
			responsiveflag = true;		
		}
		else if (jQuery(document.body).width() > 751){
			accordion('disable');
	        responsiveflag = false;
		}
}	
toDo();
jQuery(window).resize(function(){toDo();});


//   TOGGLE PAGE PRODUCT (TAB)

jQuery(window).load(function(){
	if (jQuery(document.body).width()< 480){
		jQuery('.page_product_box h3').on('click', function(){
			jQuery(this).toggleClass('active').parent().find('.toggle_content').slideToggle('medium');
		})
		jQuery('.page_product_box').addClass('accordion');
	
		}else{
		jQuery('.page_product_box h3').removeClass('active').off().parent().find('.toggle_content').slideDown('fast');
		jQuery('.page_product_box').removeClass('accordion');
		}
  
});
var responsiveflag = false;
function accordion(status){	
		if(status == 'enable'){
			jQuery('.page_product_box h3').on('click', function(){
				jQuery(this).toggleClass('active').parent().find('.toggle_content').slideToggle('medium');
			})
			jQuery('.page_product_box').addClass('accordion').find('.toggle_content').slideUp('fast');
		}else{
			jQuery('.page_product_box h3').removeClass('active').off().parent().find('.toggle_content').slideDown('fast');
			jQuery('.page_product_box').removeClass('accordion');
		}
	}		
function toDo(){
	   if (jQuery(document.body).width() < 480 && responsiveflag == false){
		    accordion('enable');
			responsiveflag = true;
				
		}
		else if (jQuery(document.body).width() > 480){
			accordion('disable');
	        responsiveflag = false;
		}
}	
toDo();
jQuery(window).resize(function(){toDo();});

//   TOGGLE RIGHT COLUMN

jQuery(window).load(function(){
	if (jQuery(document.body).width() < 751){
		jQuery('#right_column h4').on('click', function(){
			jQuery(this).toggleClass('active').parent().find('.toggle_content').slideToggle('medium');
		})
		jQuery('#right_column').addClass('accordion').find('.toggle_content').slideUp('fast');
		}else{
		jQuery('#right_column h4').removeClass('active').off().parent().find('.toggle_content').slideDown('fast');
		jQuery('#right_column').removeClass('accordion');
		}
  
});
var responsiveflag = false;
function accordion(status){	
		if(status == 'enable'){
			jQuery('#right_column h4').on('click', function(){
				jQuery(this).toggleClass('active').parent().find('.toggle_content').slideToggle('medium');
			})
			jQuery('#right_column').addClass('accordion').find('.toggle_content').slideUp('fast');
		}else{
			jQuery('#right_column h4').removeClass('active').off().parent().find('.toggle_content').slideDown('fast');
			jQuery('#right_column').removeClass('accordion');
		}
	}		
function toDo(){
	   if (jQuery(document.body).width() < 751 && responsiveflag == false){
		    accordion('enable');
			responsiveflag = true;
				
		}
		else if (jQuery(document.body).width() > 751){
			accordion('disable');
	        responsiveflag = false;
		}
}	
toDo();
jQuery(window).resize(function(){toDo();});
/*********************************************************** top menu dropdown **********************************/
jQuery(document).ready(function(){ 
	jQuery('.header-button').on('click touchstart', function(){
		
		var subUl = jQuery(this).find('ul');
		var anyAther = jQuery('#header').find('#cart_block');
		var anyAnother1 = jQuery('#menu-wrap.mobile #menu-custom'); // close other menus if opened
		if (anyAther.is(':visible')) {
			anyAther.slideUp(),
			jQuery('#header_user').removeClass('close-cart')
		}
		if (anyAnother1.is(':visible')) {
			anyAnother1.slideUp(),
			jQuery('.mobile #menu-trigger').removeClass('menu-custom-icon');
		} // close ather menus if opened
		if(subUl.is(':hidden')) {
			subUl.slideDown(),
			jQuery(this).find('.arrow_header_top').addClass('mobile-open')	
		}
		else {
			subUl.slideUp(),
			jQuery(this).find('.arrow_header_top').removeClass('mobile-open')
		}
		jQuery('.header-button').not(this).find('ul').slideUp(),
		jQuery('.header-button').not(this).find('span.arrow_header_top').removeClass('mobile-open');
		return false
	});
	/*********************************************************** header-cart menu dropdown **********************************/
	if( (typeof ajaxcart_allowed !== "undefined") && ajaxcart_allowed==1) {
	jQuery('#header_user').on('click touchstart', function(){
		var cartContent = jQuery('#header').find('#cart_block');
		var anyAnother = jQuery('.header-button').find('ul'); // close other menus if opened
		var anyAnother1 = jQuery('#menu-wrap.mobile #menu-custom'); // close other menus if opened
		if (anyAnother.is(':visible')) {
			anyAnother.slideUp();
			jQuery('.header-button').find('.arrow_header_top').removeClass('mobile-open')
		}
		if (anyAnother1.is(':visible')) {
			anyAnother1.slideUp(),
			jQuery('.mobile #menu-trigger').removeClass('menu-custom-icon');
		}
		if (cartContent.is(':hidden')){
			cartContent.slideDown(),
			jQuery(this).addClass('close-cart')
		}
		else {
			cartContent.slideUp(),
			jQuery(this).removeClass('close-cart')
		}
		return false
	});
	}
	jQuery('#header #cart_block, .header-button ul, div.alert_cart a').on('click touchstart', function(e){
		e.stopPropagation();
	});
	jQuery(document).on('click touchstart', function(){
		jQuery('#header').find('#cart_block').slideUp(),
		jQuery('.header-button').find('ul').slideUp(),
		jQuery('#header_user').removeClass('close-cart'),
		jQuery('.header-button').find('.arrow_header_top').removeClass('mobile-open')
   });
});
jQuery(document).ready(function() {
	jQuery('#order .addresses .address,#order-opc .addresses .address').removeAttr('style');
	
	if (jQuery('#system-message .alert div').length>0){
		var system_message = jQuery('#system-message .alert div').html();
		KMShowMessage(system_message);
	}	
})