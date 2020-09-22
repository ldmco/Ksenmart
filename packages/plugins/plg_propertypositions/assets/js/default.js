jQuery(document).ready(function() {
    var price_properties = [];

    jQuery('.ksm-product').on('change','[name*="property_"]', function() {
        price_properties = jQuery('.ksm-product .ksm-product-prices [name*="property_"]');
    });
    jQuery('.ksm-product').ajaxSuccess(function() {
        var new_price_properties = jQuery('.ksm-product .ksm-product-prices [name*="property_"]');
        if (price_properties != new_price_properties) {
            price_properties.each(function () {
                if (jQuery(this).is('[type="radio"]')) {
                    if (jQuery(this).prop('checked')) {
                        var p_name = jQuery(this).attr('name');
                        var p_val = jQuery(this).val();
                        jQuery('.ksm-product .ksm-product-prices [name=' + p_name + ']').removeAttr("checked");
                        jQuery('.ksm-product .ksm-product-prices [value=' + p_val + ']').attr("checked", "checked");
                    }
                } else if(jQuery(this).is('[type="checkbox"]')){
                    var p_val = jQuery(this).val();
                    if (jQuery(this).prop('checked'))
                        jQuery('.ksm-product .ksm-product-prices [value=' + p_val + ']').attr("checked", "checked");
                    else
                        jQuery('.ksm-product .ksm-product-prices [value=' + p_val + ']').removeAttr("checked");
                } else {
                    var p_name = jQuery(this).attr('name');
                    var p_val = jQuery(this).val();
                    if (jQuery('.ksm-product .ksm-product-prices [name=' + p_name + ']').val() != p_val) {
                        jQuery('.ksm-product .ksm-product-prices [name=' + p_name + ']').val(p_val);
                    }
                }
            });
        }
    });

});