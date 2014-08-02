jQuery(document).ready(function() {

    if (use_pagination == 0) {
        jQuery(window).scroll(function() {
            var height1 = eval(jQuery(window).scrollTop()) + eval(jQuery(window).height());
            var height2 = eval(jQuery('.catalog-items').offset().top) + eval(jQuery('.catalog-items').height());
            if (height2 - height1 < 200) {
                var url = URI_ROOT + 'index.php?option=com_ksenmart&view=profile&layout=items&limitstart=' + (page * limit) + '&tmpl=ksenmart';
                jQuery.ajax({
                    url: url,
                    success: function(data) {
                        jQuery('.catalog-items').append(data);
                    }
                });
                page++;
            }
        });
    }

    jQuery('.item .del a').on('click', function() {
        var prd_id = jQuery(this).attr('prd_id');
        jQuery(this).parents('.item').remove();
        jQuery.ajax({
            url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.del_watched&id=' + prd_id + '&tmpl=ksenmart'
        });
    });

});