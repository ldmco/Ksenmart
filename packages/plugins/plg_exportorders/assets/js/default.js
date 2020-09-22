jQuery(document).ready(function () {
    jQuery('body').on('click', '.export_orders', function(){
        var data = {};
        data['task'] = 'pluginAction';
        data['action'] = 'getCSV';
        data['plugin'] = 'exportorders';
        data['format'] = 'json';
        data['tmpl'] = 'ksenmart';
        var fdata = jQuery('#list-filters').serialize();
        data['fdata'] = fdata;
        jQuery.ajax({
            url: '/index.php?option=com_ksenmart&'+fdata,
            data: data,
            type: "post",
            success: function(response) {
                window.location = response;
            }
        });
    });
});
