jQuery(document).ready(function() {

    jQuery('.edit').height(jQuery(window).height() - 40);

    jQuery('.print').click(function() {
        var form = jQuery(this).parents('.form');
        form.find('#popup-window2').show();
        return false;
    });

    jQuery('#popup-window2 .print-button').click(function() {
        var form = jQuery('.form');
        var order_id = form.find('.id').val();
        jQuery('#popup-window2 input:checked').each(function() {
            var layout = jQuery(this).val();
            window.open('index.php?option=com_ksenmart&view=orders&layout=' + layout + '&id=' + order_id + '&tmpl=ksenmart-full', layout, 'menubar=no,location=no,resizable=yes,scrollbars=yes');
        });
        return false;
    });

    jQuery('form').on('submit', function() {
        var form = jQuery(this);
        form.submit();
        return false;
    });

});

function getOrderItems() {
    var items = [];
    jQuery('.order-positions .position').each(function() {
        var position = jQuery(this);
        if (!position.is('.empty-position')) {
            var properties = {};
            position.find('.properties input').each(function() {
                if (!((jQuery(this).attr('type') == 'radio' || jQuery(this).attr('type') == 'checkbox') && !jQuery(this).is(':checked'))) {
                    var property_id = jQuery(this).attr('name');
                    var reg = /properties\]\[(\d+)\]\[\]/g;
                    property_id = reg.exec(property_id);
                    property_id = property_id[1];
                    var value_id = jQuery(this).val();
                    if (!properties[property_id])
                        properties[property_id] = [];
                    properties[property_id].push(value_id);
                }
            });
            var item = {
                'id': position.find('.pos-id').val(),
                'product_id': position.find('.pos-prd-id').val(),
                'basic_price': position.find('.pos-prd-basic-price').val(),
                'price': position.find('.pos-prd-price').val(),
                'count': position.find('.pos-count').val(),
                'properties': properties,
            };
            items.push(item);
        }
    });
    if (items.length == 0)
        items.push('empty');
    return items;
}

function onChangeRegion() {
    var data = {};
    var vars = {};
    var form = jQuery('.form');
    data['model'] = 'orders';
    data['form'] = 'order';
    data['fields'] = ['shipping_id', 'payment_id', 'customer_fields', 'address_fields', 'costs'];
    vars["user_id"] = form.find("#jformuser_id").val();
    vars['region_id'] = form.find('#jformregion_id').val();
    vars['shipping_id'] = form.find('#jformshipping_id').val();
    vars['items'] = getOrderItems();
    data['vars'] = vars;
    data['id'] = form.find('.id').val();
    KMRenewFormFields(data);
}

function onChangeShipping() {
    var data = {};
    var vars = {};
    var form = jQuery('.form');
    data['model'] = 'orders';
    data['form'] = 'order';
    data['fields'] = ['customer_fields', 'address_fields', 'costs'];
    vars["user_id"] = form.find("#jformuser_id").val();
    vars['region_id'] = form.find('#jformregion_id').val();
    vars['shipping_id'] = form.find('#jformshipping_id').val();
    vars['items'] = getOrderItems();
    data['vars'] = vars;
    data['id'] = form.find('.id').val();
    KMRenewFormFields(data);
}