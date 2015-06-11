function setExportImportType(obj) {
    var item = jQuery(obj).parents('li:first');
    if (!item.is('.active')) {
        jQuery('.mod_km_exportimport_types li').removeClass('active');
        item.addClass('active');
        var type = jQuery(obj).val();
        jQuery.ajax({
            url: 'index.php?option=com_ksenmart&view=exportimport&layout=default_text&type=' + type + '&tmpl=ksenmart',
            success: function(html) {
                jQuery('#exportinport_content').html(html);
                cuSel({
                    changedEl: 'select.sel'
                });
            }
        });
    }
}