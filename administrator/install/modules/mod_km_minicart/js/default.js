jQuery(document).ready(function(){
    
	var minicart = jQuery("#minicart");
    var offset   = minicart.offset();

	jQuery(window).scroll(function(){
		if (jQuery(window).scrollTop() > offset.top) {
			minicart.addClass('active').css({'top': '40px', 'position': 'fixed'});
            if(jQuery(window).width() > 767){
                minicart.addClass('active').css('width', minicart.parents('.span4').width());
            }
		} else {
			minicart.removeClass('active').css({'top': offset.top, 'position': 'static'});
		};
	});
    
	jQuery('.catalog .item').draggable({
		start: function(event, ui){},
		drag: function(event, ui){

            var minicart = jQuery('#minicart');
			var itop     = ui.helper.offset().top;
			var ileft    = ui.helper.offset().left;
			var iwidth   = ui.helper.width();
			var iheight  = ui.helper.height();	
			var ctop     = minicart.offset().top;
			var cleft    = minicart.offset().left;
			var cwidth   = minicart.width();
			var cheight  = minicart.height();
            
			if((itop<(ctop+cheight) && (itop+iheight)>ctop) && (ileft<(cleft+cwidth) && (ileft+iwidth)>cleft)){
				ui.helper.addClass('active_item');
			}else{
				ui.helper.removeClass('active_item');
                minicart.addClass('active');
            }
		},
		stop: function(event, ui) {
			ui.helper.css({'left':'0px','top':'0px'});		
            if(ui.helper.is('.active_item')){
                if(ui.helper.find('[type="submit"]').length != 0){
                    ui.helper.find('form').submit();
                }
            }
			ui.helper.removeClass('active_item');
            jQuery('#minicart').removeClass('active');
		}	
	});
    
    jQuery('body').on('click', '.l-show_minicart', function(){
        jQuery(this).parents('#minicart').toggleClass('active').children('.minicart_content').slideToggle(200, function(){
            jQuery('.login.user_panel_top').fadeToggle();
        });
    });
});	