jQuery(document).ready(function() {

    var minicart = jQuery("#minicart.scroll");

    if (minicart.length > 0) {
        var offset = minicart.offset();

        jQuery(window).scroll(function() {
            if (jQuery(window).scrollTop() > offset.top) {
                minicart.addClass('active').css({
                    'top': '40px',
                    'position': 'fixed'
                });
                if (jQuery(window).width() > 767) {
                    minicart.addClass('active').css('width', minicart.parents('.span4').width());
                }
            } else {
                minicart.removeClass('active').css({
                    'top': offset.top,
                    'position': 'static'
                });
            };
        });
    }

    jQuery('body').on('click', '.l-show_minicart', function() {
        jQuery(this).parents('#minicart').toggleClass('active').children('.minicart_content').slideToggle(200, function() {
            jQuery('.login.user_panel_top').fadeToggle();
        });
    });
});