function setSeoType(obj) {
    var item = jQuery(obj).parents('li:first');
    if (!item.is('.active')) {
        jQuery('.mod_km_seo_types li').removeClass('active');
        item.addClass('active');
        var seo_type = jQuery(obj).val();
        var layout = 'default_' + seo_type;
        jQuery.ajax({
            url: 'index.php?option=com_ksen&view=seo&layout=' + layout + '&seo_type=' + seo_type + '&tmpl=ksenmart&extension=com_ksenmart',
            success: function(html) {
                jQuery('#seo_content').html(html);
                var params = {
                    changedEl: "select.sel",
                }
                cuSel(params);
            }
        });
    }
}