function setReport(obj) {
    var item = jQuery(obj).parents('li:first');
    if (!item.is('.active')) {
        jQuery('.mod_km_reports li').removeClass('active');
        item.addClass('active');
        var report = jQuery(obj).val();
        var layout = 'default_' + report;
        jQuery.ajax({
            url: 'index.php?option=com_ksenmart&view=reports&layout=' + layout + '&report=' + report + '&tmpl=ksenmart',
            success: function(html) {
                jQuery('#reports_content').html(html);
            }
        });
    }
}