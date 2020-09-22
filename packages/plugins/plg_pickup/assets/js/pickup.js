function KMCartChangePickup(obj) {
    var pickup_id = jQuery(obj).val();

    var data = {};

    data['layouts'] = {
        '0': 'default_total'
    };
    data['view'] = 'cart';
    data['pickup_id'] = pickup_id;

    KMGetLayouts(data);
}