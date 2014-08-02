jQuery(document).ready(function() {

    jQuery('.edit').height(jQuery(window).height() - 40);

    jQuery('form').on('submit', function() {
        var form = jQuery(this);
        var title = form.find('input[name="jform[title]"]').val();
        if (title == '') {
            KMShowMessage(Joomla.JText._('KSM_PAYMENTS_PAYMENT_INVALID_TITLE_LBL'));
            return false;
        }
        form.unbind('submit');
        form.submit();
        return true;
    });

});

function setPaymentType(obj) {
    var type = jQuery(obj).val();
    var name = jQuery(obj).parent().text();
    jQuery('#popup-window2 li.active').removeClass('active');
    jQuery(obj).parent().parent().addClass('active');
    jQuery('.popup-window').fadeOut(400);
    jQuery('#add-alg').text(name);
    jQuery.ajax({
        url: 'index.php?option=com_ksenmart&view=payments&layout=payment_params&type=' + type + '&tmpl=ksenmart',
        success: function(data) {
            jQuery('.params-set').html(data);
            var params = {
                changedEl: "select.sel",
            }
            cuSel(params);
        }
    });
}