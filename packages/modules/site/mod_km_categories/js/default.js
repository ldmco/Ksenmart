jQuery(document).ready(function() {
    jQuery('.ksm-categories a').click(function() {
        var level = jQuery(this).attr('level');
        var next_level = eval(level) + 1;
        var next_level = jQuery(this).parent().find('.menu-list-' + next_level);
        if (!next_level.is('.hide') || next_level.find('li').length == 0)
            return true;
        jQuery('.ksm-categories .menu-list-' + level + ' ul').slideUp(400, function() {
            jQuery(this).addClass('hide');
        });
        next_level.slideDown(400, function() {
            jQuery(this).removeClass('hide');
        });
        return false;
    });
});