var maskList, maskOpts;
jQuery(document).ready(function() {

    jQuery('#order_info_show').on('click', function() {
        jQuery('.order_info_block').slideDown('normal', function() {
            var destination = jQuery('.order_info_block').offset().top;
            jQuery('body').animate({
                scrollTop: destination
            }, 1100);
        });
        jQuery('#order_info_show').fadeOut();
    });

    jQuery('input[name="registration"]').on('click', function() {
        if (jQuery(this).is(':checked'))
            jQuery('.password_row').show();
        else
            jQuery('.password_row').hide();
    });

    jQuery('.quant .minus').on('click', function() {
        var input = jQuery(this).parents('.quant').find('[type="text"]');
        var count = parseFloat(input.val());
        var product_packaging = parseFloat(input.attr('product_packaging'));
        if (count > product_packaging) {
            count -= product_packaging;
            count = Math.ceil(count / product_packaging) * product_packaging;
            count = count.toFixed(4);
            count = fixCount(count);
            input.val(count);
            update_count(input);
        }
    });

    jQuery('.quant .plus').on('click', function() {
        var input = jQuery(this).parents('.quant').find('[type="text"]');
        var count = parseFloat(input.val());
        var product_packaging = parseFloat(input.attr('product_packaging'));
        count += product_packaging;
        count = Math.ceil(count / product_packaging) * product_packaging;
        count = count.toFixed(4);
        count = fixCount(count);
        input.val(count);
        update_count(input);
    });
    /*
jQuery('.quantt input').on('mouseout',function(e){
    e.preventDefault();
    update_count(jQuery(this));
});*/

    jQuery('.quantt input').on('keypress', function(e) {
        //e.preventDefault();
        if (e.keyCode == 13) {
            var input = jQuery(this);
            var count = parseFloat(input.val());
            var item_id = input.data().item_id;
            var old_count = parseFloat(input.attr('count'));
            var product_id = input.attr('product_id');
            var flag = true;
            var product_packaging = parseFloat(input.attr('product_packaging'));
            count = Math.ceil(count / product_packaging) * product_packaging;
            count = count.toFixed(4);
            count = fixCount(count);
            input.val(count);
            if (old_count != count) {
                jQuery.ajax({
                    url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.validate_in_stock&id=' + product_id + '&count=' + (count - old_count) + '&tmpl=ksenmart',
                    async: false,
                    success: function(data) {
                        if (data != '') {
                            KMShowMessage(data);
                            flag = false;
                        }
                    }
                });
                if (!flag) {
                    input.val(old_count);
                    return false;
                }

                jQuery.ajax({
                    url: URI_ROOT + 'index.php?option=com_ksenmart&view=cart&layout=minicart&task=cart.update_cart&item_id=' + item_id + '&count=' + count + '&tmpl=ksenmart',
                    success: function(data) {
                        KMShowMessage('<h2>Заказ обновлен.</h2>');
                        if (count == 0) {
                            input.parents('.item').remove();
                            if (jQuery('#cart .item .del').length == 0) {
                                jQuery('#cart').html('<h1 class="clear_cart">Ваш заказ пуст</h1>');
                                jQuery('#order').html('');
                            }
                        } else {
                            input.attr('count', count);
                            update_prices();
                        }
                        jQuery('#minicart').html(data);
                    }
                });
            }
            return false;
        }
    });

    maskList = jQuery.masksSort(jQuery.masksLoad(URI_ROOT + "components/com_ksenmart/js/phone-codes.json"), ['#'], /[0-9]|#/, "mask");
    maskOpts = {
        inputmask: {
            definitions: {
                '#': {
                    validator: "[0-9]",
                    cardinality: 1
                }
            },
            //clearIncomplete: true,
            showMaskOnHover: false,
            autoUnmask: true
        },
        match: /[0-9]/,
        replace: '#',
        list: maskList,
        listKey: "mask",
        onMaskChange: function(maskObj, completed) {
            if (completed) {
                var hint = maskObj.name_ru;
                if (maskObj.desc_ru && maskObj.desc_ru != "") {
                    hint += " (" + maskObj.desc_ru + ")";
                }
                jQuery("#descr").html(hint);
            } else {
                jQuery("#descr").html("Введите номер");
            }
            jQuery(this).attr("placeholder", jQuery(this).inputmask("getemptymask"));

            var field_value = jQuery(this).val();
            var name = jQuery(this).attr('name');
            setOrderUserField(name, field_value);
        }
    };

    jQuery('#customer_phone').inputmasks(maskOpts);

    jQuery('body').on('click', '.select_address tr', function(e) {
        e.preventDefault();
        var id = jQuery(this).data().id;
        var city = jQuery(this).data().city;
        var zip = jQuery(this).data().zip;
        var street = jQuery(this).data().street;
        var house = jQuery(this).data().house;
        var floor = jQuery(this).data().floor;
        var flat = jQuery(this).data().flat;

        jQuery('.address_fields_b').find('[name="address_fields[city]"]').val(city);
        jQuery('.address_fields_b').find('[name="address_fields[zip]"]').val(zip);
        jQuery('.address_fields_b').find('[name="address_fields[street]"]').val(street);
        jQuery('.address_fields_b').find('[name="address_fields[house]"]').val(house);
        jQuery('.address_fields_b').find('[name="address_fields[floor]"]').val(floor);
        jQuery('.address_fields_b').find('[name="address_fields[flat]"]').val(flat);

        jQuery(this).find('[type="radio"]')[0].checked = true;

        jQuery.ajax({
            type: 'POST',
            url: URI_ROOT + 'index.php?option=com_ksenmart&task=cart.set_select_address_id&tmpl=ksenmart',
            data: {
                id: id,
                city: city,
                zip: zip,
                street: street,
                house: house,
                floor: floor,
                flat: flat
            },
            success: function(data) {}
        });
    });

    jQuery('#cart').on('change', '.address_field, input[name*="customer_fields"], #customer_phone', function() {
        var field_value = jQuery(this).val();
        var name = jQuery(this).attr('name');
        setOrderUserField(name, field_value);
    });
});

function update_count($this) {
    var input = $this;
    var count = parseFloat(input.val());
    var item_id = input.data().item_id;
    var old_count = parseFloat(input.attr('count'));
    var product_id = input.attr('product_id');
    var flag = true;
    var product_packaging = parseFloat(input.attr('product_packaging'));

    count = Math.ceil(count / product_packaging) * product_packaging;
    count = count.toFixed(4);
    count = fixCount(count);

    if (count < 0) {
        count = count * (-1);
    }
    input.val(count);
    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.validate_in_stock&id=' + product_id + '&count=' + (count - old_count) + '&tmpl=ksenmart',
        async: false,
        success: function(data) {
            if (data != '') {
                KMShowMessage(data);
                flag = false;
            }
        }
    });
    if (!flag) {
        input.val(old_count);
        return false;
    }
    if (old_count != count) {
        jQuery.ajax({
            url: URI_ROOT + 'index.php?option=com_ksenmart&view=cart&layout=minicart&task=cart.update_cart&item_id=' + item_id + '&count=' + count + '&tmpl=ksenmart',
            success: function(data) {
                KMShowMessage('<h2>Заказ обновлен.</h2>');
                if (count == 0) {
                    input.parents('.item').remove();
                    if (jQuery('#cart .item .del').length == 0) {
                        jQuery('#cart').html('<h1 class="clear_cart">Ваш заказ пуст</h1>');
                        jQuery('#order').html('');
                    }
                } else {
                    input.attr('count', count);
                }
                jQuery('#minicart').html(data);
            }
        });
    }
    update_prices();
}

function update_prices() {
    var prd_price = 0;
    var item_price = 0;
    var item_count = 0;
    var deliverycost = jQuery('#deliverycost').val();

    jQuery('#cart .item-cart').each(function() {
        var item = jQuery(this);

        item_price = item.find('.quantt input').attr('price');
        item_count = item.find('.quantt input').val();
        jQuery.ajax({
            url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.get_transform_price&price=' + (item_price * item_count) + '&tmpl=ksenmart',
            async: false,
            success: function(data) {
                item.find('.totall').html(data);
            }
        });
        prd_price += item_price * item_count;
    });
    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.get_transform_price&price=' + prd_price + '&tmpl=ksenmart',
        async: false,
        success: function(data) {
            jQuery('.total_cost_items span').html(data);
            jQuery('.total span').html(data);
        }
    });

    var data = {};
    data['layouts'] = {
        '0': 'default_on_display_after_content',
        '1': 'default_total'
    };

    KMGetLayouts(data);
}

function setOrderUserField(name, field_value) {
    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=cart.updateOrderUserField&tmpl=ksenmart',
        type: 'POST',
        data: {
            name: name,
            field_value: field_value
        },
        success: function(data) {}
    });
}

function setOrderField(column, field) {
    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=cart.updateOrderField&tmpl=ksenmart',
        type: 'POST',
        data: {
            column: column,
            field: field
        },
        success: function(data) {}
    });
}

function KMCartChangeRegion(obj) {
    var region_id = jQuery(obj).val();
    var data = {};

    data['layouts'] = {
        '0': 'default_shipping',
        '2': 'default_payments',
        '1': 'default_total'
    };
    data['region_id'] = region_id;

    setOrderField('region_id', region_id);
    KMGetLayouts(data);

    setTimeout(function() {
        jQuery('#customer_phone').inputmasks(maskOpts);
    }, 100);
}

function KMCartChangeShipping(obj) {
    var shipping_id = jQuery(obj).val();

    var data = {};

    data['layouts'] = {
        '0': 'default_shipping',
        '1': 'default_total'
    };
    data['shipping_id'] = shipping_id;

    setOrderField('shipping_id', shipping_id);
    KMGetLayouts(data);

    setTimeout(function() {
        jQuery('#customer_phone').inputmasks(maskOpts);
    }, 100);
}

function KMCartChangePayment(obj) {
    var payment_id = jQuery(obj).val();

    var data = {};

    data['layouts'] = {
        '0': 'default_total'
    };
    data['payment_id'] = payment_id;

    setOrderField('payment_id', payment_id);
    KMGetLayouts(data);
}