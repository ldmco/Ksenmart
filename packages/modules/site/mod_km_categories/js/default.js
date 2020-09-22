jQuery(document).ready(function() {

    jQuery('.ksm-module-categories .ksm-module-categories-item-caret').on('click', function() {
        var item = jQuery(this).parents('.ksm-module-categories-item:first');
        var link = item.find('.ksm-module-categories-item-link:first');
        var level = link.data().level;
        var next_level = level + 1;
        var next_level_block = link.parents('.ksm-module-categories-item:first').find('.ksm-module-categories-level-'+next_level);

        jQuery('.ksm-module-categories .ksm-module-categories-level').each(function(){
            if (!jQuery.contains(jQuery(this)[0], next_level_block[0]))
            {
                jQuery(this).slideUp(200, function() {
                    jQuery(this).parents('.ksm-module-categories-item:first').removeClass('ksm-module-categories-item-opened');
                    jQuery(this).addClass('ksm-module-categories-level-hide');
                });
            }
        });

        if (item.is('.ksm-module-categories-item-opened'))
        {
            return true;
        }

        next_level_block.slideDown(200, function() {
            next_level_block.parents('.ksm-module-categories-item-deeper:first').addClass('ksm-module-categories-item-opened');
            jQuery(this).removeClass('ksm-module-categories-level-hide');
        });

        return false;
    });

});