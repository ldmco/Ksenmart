jQuery(document).ready(function() {

    jQuery('.edit').height(jQuery(window).height() - 40);
    jQuery('#jform_from_date').datepicker();
    jQuery('#jform_to_date').datepicker();

    jQuery('form').on('submit', function() {
        var form = jQuery(this);
        var title = form.find('input[name="jform[title]"]').val();
        if (title == '') {
            KMShowMessage(Joomla.JText._('KSM_DISCOUNTS_DISCOUNT_INVALID_TITLE_LBL'));
            return false;
        }
        form.unbind('submit');
        form.submit();
        return true;
    });

});

function setDiscountType(obj) {
    var type = jQuery(obj).val();
    var name = jQuery(obj).parent().text();
    jQuery('.popup-window li.active').removeClass('active');
    jQuery(obj).parent().parent().addClass('active');
    jQuery('.popup-window').fadeOut(400);
    jQuery('#add-alg').text(name);
    jQuery.ajax({
        url: 'index.php?option=com_ksenmart&view=discounts&layout=discount_params&type=' + type + '&tmpl=ksenmart',
        success: function(data) {
            jQuery('.params-set').html(data);
            var params = {
                changedEl: "select.sel",
            }
            cuSel(params);
        }
    });
}

function addDiscountAction(obj) {
    var type = jQuery(obj).attr('rel');
    jQuery('.popup-window').fadeOut(400);
    jQuery.ajax({
        url: 'index.php?option=com_ksenmart&view=discounts&task=discounts.get_action_params&type=' + type + '&tmpl=ksenmart',
        success: function(data) {
            jQuery('.actions-ul').append(data);
            var params = {
                changedEl: "select.sel",
            }
            cuSel(params);
        }
    });
}

function removeDiscountAction(obj) {
    jQuery(obj).parents('li').remove();
}