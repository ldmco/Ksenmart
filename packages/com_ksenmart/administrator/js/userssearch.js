jQuery(document).ready(function() {

    jQuery('.form div.edit').height(jQuery(window).height() - 40);

    jQuery('.cat .img .min_img').on('mouseover', function() {
        if (jQuery(this).parents('.list_item').is(':not(.ui-sortable-helper)'))
            jQuery(this).parents('.img').find('.medium_img').show();
    });

    jQuery('.cat .img .min_img').on('mouseout', function() {
        if (jQuery(this).parents('.list_item').is(':not(.ui-sortable-helper)'))
            jQuery(this).parents('.img').find('.medium_img').hide();
    });

    jQuery('.show_product_photo').on('click', function() {
        SqueezeBox.setContent('image', jQuery(this).attr('href'));
        return false;
    });

    jQuery('.list_item .add').on('click', function() {
        var product_id = jQuery(this).parents('.list_item').find('.id').val();
        if (jQuery('.drop div').length == 0)
            jQuery('.drop').html('');
        if (jQuery('.drop div[rel="' + product_id + '"]').length == 0) {
            var html = '';
            html += '<div rel="' + product_id + '">';
            html += '<a class="del"></a>';
            html += jQuery(this).parents('.list_item').find('.min_img').parent().html();
            html += '	<input type="hidden" name="ids[]" value="' + product_id + '">';
            html += '</div>';
            jQuery('.drop').append(html);
        }
        return false;
    });

    jQuery('.drop .del').on('click', function() {
        jQuery(this).parent().remove();
        if (jQuery('.drop div').length == 0)
            jQuery('.drop').html(Joomla.JText._('ksm_users_add_search_string'));
        return false;
    });

    jQuery('.form .save').on('click', function() {
        var data = jQuery('#add-form').serialize();
        var items_to = jQuery('#add-form').find('input[name="items_to"]').val();
        if (jQuery('.drop div').length > 0) {
            jQuery.ajax({
                url: 'index.php?option=com_ksenmart&tmpl=ksenmart',
                data: data,
                dataType: 'json',
                async: false,
                success: function(responce) {
                    parent.jQuery('#' + items_to).find('.no-items').hide();
                    parent.jQuery('#' + items_to).append(responce.html);
                    if (typeof parent.afterAddingUsers == 'function') {
                        parent.afterAddingUsers();
                    }
                    parent.closePopupWindow();
                }
            });
        }
        return false;
    });

});