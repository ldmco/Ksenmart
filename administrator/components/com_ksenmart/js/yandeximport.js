jQuery(document).ready(function() {

    jQuery('#products_cats li span').on('click', function() {
        if (jQuery(this).parent().is('.active'))
            jQuery(this).parent().removeClass('active');
        else
            jQuery(this).parent().addClass('active');
    });

    jQuery('.show_cat').on('click', function() {
        var cat_id = jQuery(this).parent().parent().attr('cat_id');
        jQuery('#products_cats li[parent="' + cat_id + '"]').removeClass('hide');
        jQuery(this).removeClass('show_cat');
        jQuery(this).addClass('hide_cat');
        return false;
    });

    jQuery('.hide_cat').on('click', function() {
        var cat_id = jQuery(this).parent().parent().attr('cat_id');
        jQuery('#products_cats li[parent="' + cat_id + '"]').addClass('hide');
        jQuery(this).removeClass('hide_cat');
        jQuery(this).addClass('show_cat');
        return false;
    });

    jQuery('.check_all_cats').on('click', function() {
        jQuery('#products_cats li').addClass('active');
    });

    jQuery('.discharge_cats').on('click', function() {
        jQuery('#products_cats li').removeClass('active');
    });

    jQuery('#save_yandeximport').click(function() {
        var str = '';
        var form = jQuery(this).parent().parent();
        form.find('#products_cats li.active').each(function() {
            str += '|' + jQuery(this).attr('cat_id') + '|';
        });
        form.find('input[name="categories"]').val(str);
        ShowLoading();
        jQuery.post('index.php?option=com_ksenmart&view=yandeximport&task=yandeximport.save', form.serialize(), function() {
            HideLoading();
        })
    });

});