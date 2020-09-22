jQuery(document).ready(function () {



});

function KMCartChangeB2cplShipping(obj, code) {
    var shipping_id = jQuery(obj).val();

    var data = {};

    data['layouts'] = {
        '0': 'default_steps'
    };
    data['view'] = 'cart';
    data['shipping_id'] = shipping_id;
    data['tarif_id'] = code;

    KMGetLayouts(data);

    if (jQuery.fn.chosen != undefined) {
        jQuery('select').chosen();
    }

    setTimeout(function () {
        jQuery('#customer_phone').inputmasks(maskOpts);
    }, 100);
}

function KMCartB2cplChangePickup(obj) {
    var tarif_id = jQuery(obj).val();

    jQuery('.ksm-cart-order-shipping-method').removeClass('active');
    jQuery(obj).closest('.ksm-cart-order-shipping-method').addClass('active');

    var data = {};

    data['layouts'] = {
        '0': 'default_total'
    };
    data['view'] = 'cart';
    data['tarif_id'] = tarif_id;

    KMGetLayouts(data);
}