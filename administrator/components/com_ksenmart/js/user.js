jQuery(document).ready(function() {

    jQuery('.edit').height(jQuery(window).height() - 40);

    jQuery('form').on('submit', function() {
        var form = jQuery(this);
        var name = form.find('input[name="jform[name]"]').val();
        if (name == '') {
            KMShowMessage(Joomla.JText._('KSM_USERS_USER_INVALID_NAME_LBL'));
            return false;
        }
        form.unbind('submit');
        form.submit();
        return true;
    });

});