jQuery(document).ready(function() {

    jQuery('.ksenmart-settings-groups .settings-tab:first').addClass('active');
    jQuery('.ksenmart-settings-groups .settings-tab:first ul').show();
    jQuery('.ksenmart-settings-groups .settings-tab:first .show').removeClass('show').addClass('hides');
    jQuery('.ksenmart-settings-groups .settings-tab').each(function() {
        jQuery(this).find('li:first').addClass('active');
    });
    jQuery('.settings-tab-content').hide();
    jQuery('.settings-tab-content:first').show();
    jQuery('.settings-sub-tab-content').hide();
    jQuery('.settings-tab-content').each(function() {
        jQuery(this).find('.settings-sub-tab-content:first').show();
    });

    jQuery('.ksenmart-settings-groups .settings-tab label').on('click', function() {
        if (jQuery(this).parents('.settings-tab').not('.active')) {
            var id = jQuery(this).parents('.settings-tab').attr('id');
            jQuery('.ksenmart-settings-groups .settings-tab').removeClass('active');
            jQuery('.ksenmart-settings-groups .settings-tab ul').hide();
            jQuery('.ksenmart-settings-groups .settings-tab .hides').removeClass('hides').addClass('show');
            jQuery('.settings-tab-content').hide();
            jQuery(this).parents('.settings-tab').addClass('active');
            jQuery(this).parents('.settings-tab').find('ul').show();
            jQuery(this).parents('.settings-tab').find('.show').removeClass('show').addClass('hides');
            jQuery('#' + id + '-content').show();
        }
    });

    jQuery('.ksenmart-settings-groups .settings-sub-tab').on('click', function() {
        if (jQuery(this).not('.active')) {
            var id = jQuery(this).attr('id');
            var content = jQuery('#' + jQuery(this).parents('.settings-tab').attr('id') + '-content');
            jQuery(this).parents('.settings-tab').find('.settings-sub-tab').removeClass('active');
            content.find('.settings-sub-tab-content').hide();
            jQuery(this).addClass('active');
            jQuery('#' + id + '-content').show();
        }
        return false;
    });

});