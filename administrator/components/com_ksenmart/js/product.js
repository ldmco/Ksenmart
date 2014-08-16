jQuery(document).ready(function() {

    jQuery('.edit').height(jQuery(window).height() - 40);

    jQuery('body').on('click', '.add_childs', function() {
        jQuery('.form input[name="close"]').val(0);
        jQuery('.form input[name="jform[is_parent]"]').val(1);
        jQuery('.form [type="submit"]').click();
        return false;
    });

    jQuery('form').on('submit', function() {
        var form = jQuery(this);
        var title = form.find('input[name="jform[title]"]').val();
        if (title == '') {
            KMShowMessage(Joomla.JText._('KSM_CATALOG_PRODUCT_INVALID_TITLE_LBL'));
            return false;
        }
        form.unbind('submit');
        form.submit();
        return true;
    });
});