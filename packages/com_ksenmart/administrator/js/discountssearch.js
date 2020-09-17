jQuery(document).ready(function() {

    jQuery('.form div.edit').height(jQuery(window).height() - 40);

    jQuery('.list_item .add').on('click', function() {
        var product_id = jQuery(this).parents('.list_item').find('.id').val();
        if (jQuery('.drop div').length == 0)
            jQuery('.drop').html('');
        if (jQuery('.drop div[rel="' + product_id + '"]').length == 0) {
            var html = '';
            html += '<div rel="' + product_id + '">';
            html += '<a class="del"></a>';
            html += jQuery(this).parents('.list_item').find('.name .descr').text();
            html += '	<input type="hidden" name="ids[]" value="' + product_id + '">';
            html += '</div>';
            jQuery('.drop').append(html);
        }
        return false;
    });

    jQuery('body').on('click', '.drop .del', function() {
        jQuery(this).parent().remove();
        if (jQuery('.drop div').length == 0)
            jQuery('.drop').html(Joomla.JText._('ksm_discounts_add_search_string'));
        return false;
    });

    jQuery('.form .save').on('click', function() {
        var ids = [];
        jQuery('#add-form .drop div input').each(function(){
            ids.push(jQuery(this).val());
        });
        if (typeof parent.afterAddingDiscounts == 'function') {
            parent.afterAddingDiscounts(ids);
        }
        parent.closePopupWindow();

        return false;
    });

});