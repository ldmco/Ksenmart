jQuery(document).ready(function() {

    jQuery('.ksenmart-updater_parts li').on('click', function() {
        if (jQuery(this).is('.active')) {
            jQuery(this).removeClass('active');
            ShowLoading();
            jQuery.ajax({
                url: 'index.php?option=com_ksenmart&task=updater.update_part&component=' + jQuery(this).attr('component') + '&active=0&tmpl=ksenmart',
                success: function(data) {
                    HideLoading();
                }
            });
        } else {
            jQuery(this).addClass('active');
            ShowLoading();
            jQuery.ajax({
                url: 'index.php?option=com_ksenmart&task=updater.update_part&component=' + jQuery(this).attr('component') + '&active=1&tmpl=ksenmart',
                success: function(data) {
                    HideLoading();
                }
            });
        }
    });

    jQuery('.saves').on('click', function() {
        var form = jQuery(this).parents('form');
        jQuery.ajax({
            url: 'index.php?option=com_ksenmart&task=updater.check_license&tmpl=ksenmart',
            success: function(data) {
                if (data != '') {
                    alert(data);
                    return false;
                } else {
                    form.submit();
                }
            }
        });
    });

});