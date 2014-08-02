jQuery(document).ready(function() {

    jQuery('.mod_km_exportimport_types .km-list-left-module-title .sh').on('click', function() {
        if (jQuery(this).is('.hides')) {
            jQuery(this).removeClass('hides');
            jQuery(this).addClass('show');
            jQuery(this).parents('.km-list-left-module').find('.km-list-left-module-content').slideUp(500);
        } else {
            jQuery(this).removeClass('show');
            jQuery(this).addClass('hides');
            jQuery(this).parents('.km-list-left-module').find('.km-list-left-module-content').slideDown(500);
        }
        return false;
    });

});