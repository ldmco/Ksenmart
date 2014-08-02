jQuery(document).ready(function() {

    jQuery('.edit').height(jQuery(window).height() - 40);

    jQuery('input[name="jform[type]"]').on('change', function() {
        switch (jQuery(this).val()) {
            case 'select':
                jQuery('.form .default').hide();
                jQuery('.form .values').show();
                jQuery('.form .view').parent().show();
                break;
            case 'text':
                jQuery('.form .default').show();
                jQuery('.form .values').hide();
                jQuery('.form .view').parent().hide();
                break;
        }
    });

    jQuery('form').on('submit', function() {
        var form = jQuery(this);
        var title = form.find('input[name="jform[title]"]').val();
        if (title == '') {
            KMShowMessage(Joomla.JText._('KSM_PROPERTIES_PROPERTY_INVALID_TITLE_LBL'));
            return false;
        }
        form.unbind('submit');
        form.submit();
        return true;
    });

});