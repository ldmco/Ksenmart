var maskList, maskOpts;
var KSMCartUpdateTimer = false;

jQuery(document).ready(function () {

    if (jQuery.fn.chosen != undefined) {
        jQuery('select').chosen();
    }

    jQuery('.ksm-cart').on('click', '#ksm-cart-show-order', function () {
        jQuery('.ksm-cart-order').slideDown('normal', function () {
            var destination = jQuery('.ksm-cart-order').offset().top;
            jQuery('body').animate({
                scrollTop: destination
            }, 1100);
        });
        jQuery('#ksm-cart-show-order').fadeOut();
        if (jQuery('.ksm-cart-order-step-head:not(.active)').length) {
            jQuery('.ksm-cart-total input[type="submit"]').hide();
        }
    });

    jQuery('.ksm-cart').on('click', '.ksm-cart-item-quant-minus', function () {
        var input = jQuery(this).parents('.ksm-cart-item-quant').find('[type="text"]');
        var item = input.parents('.ksm-cart-item');
        var count = parseFloat(input.val());
        var product_packaging = parseFloat(item.data().product_packaging);
        if (count > product_packaging) {
            count -= product_packaging;
            count = Math.ceil(count / product_packaging) * product_packaging;
            count = count.toFixed(4);
            count = fixCount(count);
            input.val(count);
            KSMSetCartUpdateTimer(true);
        }
    });

    jQuery('.ksm-cart').on('click', '.ksm-cart-item-quant-plus', function () {
        var input = jQuery(this).parents('.ksm-cart-item-quant').find('[type="text"]');
        var item = input.parents('.ksm-cart-item');
        var count = parseFloat(input.val());
        var product_packaging = parseFloat(item.data().product_packaging);
        count += product_packaging;
        count = Math.ceil(count / product_packaging) * product_packaging;
        count = count.toFixed(4);
        count = fixCount(count);
        input.val(count);
        KSMSetCartUpdateTimer(true);
    });

    jQuery('.ksm-cart').on('keypress', '.ksm-cart-item-quant-input', function (e) {
        if (e.keyCode == 13) {
            var input = jQuery(this);
            var item = input.parents('.ksm-cart-item');
            var count = parseFloat(input.val());
            var product_packaging = parseFloat(item.data().product_packaging);
            count = Math.ceil(count / product_packaging) * product_packaging;
            count = count.toFixed(4);
            count = fixCount(count);
            if (count < product_packaging) {
                count = product_packaging;
            }
            input.val(count);
            KSMSetCartUpdateTimer(true);
        }
    });

    jQuery('.ksm-cart').on('click', '.ksm-cart-order-next-step', function (e) {
        e.preventDefault();

        var validator = jQuery('.ksm-cart-order-form').validate();
        var result = true;
        jQuery('.ksm-cart-order-form').find('input,textarea,select').filter('[required]').each(function (e) {
            var id = jQuery(this).attr('id');
            result = validator.element('#' + id);
        });
        if (!result) return false;

        var step_id = jQuery('.ksm-cart-order-steps').data().step_id;
        step_id++;
        var data = {};

        data['layouts'] = {
            '0': 'default_steps'
        };
        data['view'] = 'cart';
        data['step_id'] = step_id;

        KMGetLayouts(data);

        if (jQuery.fn.chosen != undefined) {
            jQuery('select').chosen();
        }

        setTimeout(function () {
            jQuery('#customer_phone').inputmasks(maskOpts);
        }, 100);
    });

    jQuery('.ksm-cart').on('click', '.ksm-cart-block-edit', function (e) {
        e.preventDefault();

        var step_id = jQuery(this).data().step_id;
        var data = {};

        data['layouts'] = {
            '0': 'default_steps'
        };
        data['view'] = 'cart';
        data['step_id'] = step_id;

        KMGetLayouts(data);

        if (jQuery.fn.chosen != undefined) {
            jQuery('select').chosen();
        }

        setTimeout(function () {
            jQuery('#customer_phone').inputmasks(maskOpts);
        }, 100);
    });

    jQuery('.ksm-cart-order-form').submit(function (e) {
        jQuery('.ksm-cart-order-form').validate();
    });

    jQuery('body').on('click', '.ksm-cart-item-del-link', function (e) {
        e.preventDefault();

        jQuery(this).parents('.ksm-cart-item').remove();
        if (jQuery('.ksm-cart-item').length == 0) {
            jQuery('.ksm-cart').html('<h2>' + Joomla.JText._('KSM_CART_EMPTY_TITLE') + '</h2>');
        }
        KSMSetCartUpdateTimer(true);
        if (window.KSMUpdateMinicart) {
            KSMUpdateMinicart();
        }
    });

    maskList = jQuery.masksSort(jQuery.masksLoad(URI_ROOT + "components/com_ksenmart/js/phone-codes.json"), ['#'], /[0-9]|#/, "mask");
    maskOpts = {
        inputmask: {
            definitions: {
                '#': {
                    validator: "[0-9]", cardinality: 1
                }
            }, showMaskOnHover: false, autoUnmask: true
        }, match: /[0-9]/, replace: '#', list: maskList, listKey: "mask", onMaskChange: function (maskObj, completed) {
            if (completed) {
                var hint = maskObj.name_ru;
                if (maskObj.desc_ru && maskObj.desc_ru != "") {
                    hint += " (" + maskObj.desc_ru + ")";
                }
            }
            jQuery(this).attr("placeholder", jQuery(this).inputmask("getemptymask"));

            var field_value = jQuery(this).val();
            var name = jQuery(this).attr('name');
            setOrderUserField(name, field_value);
        }
    };

    jQuery('.ksm-cart #customer_phone').inputmasks(maskOpts);

    jQuery('body').on('change', '.ksm-cart [name="address_id"]', function (e) {
        e.preventDefault();
        var id = jQuery(this).find('option:selected').data().id;
        var city = jQuery(this).find('option:selected').data().city;
        var zip = jQuery(this).find('option:selected').data().zip;
        var street = jQuery(this).find('option:selected').data().street;
        var house = jQuery(this).find('option:selected').data().house;
        var floor = jQuery(this).find('option:selected').data().floor;
        var flat = jQuery(this).find('option:selected').data().flat;

        jQuery('.ksm-cart-order-step-address [name="address_fields[city]"]').val(city);
        jQuery('.ksm-cart-order-step-address [name="address_fields[zip]"]').val(zip);
        jQuery('.ksm-cart-order-step-address [name="address_fields[street]"]').val(street);
        jQuery('.ksm-cart-order-step-address [name="address_fields[house]"]').val(house);
        jQuery('.ksm-cart-order-step-address [name="address_fields[floor]"]').val(floor);
        jQuery('.ksm-cart-order-step-address [name="address_fields[flat]"]').val(flat);

        jQuery.ajax({
            type: 'POST',
            url: URI_ROOT + 'index.php?option=com_ksenmart&task=cart.set_select_address_id&tmpl=ksenmart',
            data: {
                id: id, city: city, zip: zip, street: street, house: house, floor: floor, flat: flat
            },
            success: function (data) {
            }
        });
    });

    jQuery('.ksm-cart').on('change', 'input[name*="address_fields"], input[name*="customer_fields"], textarea[name="note"]', function () {
        var field_value = jQuery(this).val();
        var name = jQuery(this).attr('name');
        setOrderUserField(name, field_value);
    });

    (function (factory) {
        if (typeof define === "function" && define.amd) {
            define(["jquery", "../jquery.validate"], factory);
        } else if (typeof module === "object" && module.exports) {
            module.exports = factory(require("jquery"));
        } else {
            factory(jQuery);
        }
    }(function ($) {

        $.extend($.validator.messages, {
            required: Joomla.JText._('KSM_CART_VALIDATE_REQUIRED'),
            remote: Joomla.JText._('KSM_CART_VALIDATE_REMOTE'),
            email: Joomla.JText._('KSM_CART_VALIDATE_EMAIL'),
            url: Joomla.JText._('KSM_CART_VALIDATE_URL'),
            date: Joomla.JText._('KSM_CART_VALIDATE_DATE'),
            dateISO: Joomla.JText._('KSM_CART_VALIDATE_DATEISO'),
            number: Joomla.JText._('KSM_CART_VALIDATE_NUMBER'),
            digits: Joomla.JText._('KSM_CART_VALIDATE_DIGITS'),
            creditcard: Joomla.JText._('KSM_CART_VALIDATE_CREDITCARD'),
            equalTo: Joomla.JText._('KSM_CART_VALIDATE_EQUALTO'),
            extension: Joomla.JText._('KSM_CART_VALIDATE_EXTENSION'),
            maxlength: jQuery.validator.format(Joomla.JText._('KSM_CART_VALIDATE_MAXLENGTH')),
            minlength: jQuery.validator.format(Joomla.JText._('KSM_CART_VALIDATE_MINLENGTH')),
            rangelength: jQuery.validator.format(Joomla.JText._('KSM_CART_VALIDATE_RANGELENGTH')),
            range: jQuery.validator.format(Joomla.JText._('KSM_CART_VALIDATE_RANGE')),
            max: jQuery.validator.format(Joomla.JText._('KSM_CART_VALIDATE_MAX')),
            min: jQuery.validator.format(Joomla.JText._('KSM_CART_VALIDATE_MIN'))
        });

    }));
});

function KSMCartUpdate() {
    var data = {};

    items = {};
    jQuery('.ksm-cart-item').each(function () {
        var item_id = jQuery(this).data().item_id;
        var count = jQuery(this).find('.ksm-cart-item-quant-input').val();
        items[item_id] = count;
    });

    data['items'] = items;
    data['layouts'] = {
        '0': 'default_on_display_after_content', '1': 'default_total', '2': 'default_content', '3': 'default_shipping'
    };
    data['task'] = 'cart.update_cart';
    for (var key in data.layouts) {
        jQuery('.' + data.layouts[key]).css('position', 'relative');
        jQuery('.' + data.layouts[key]).append('<div class="ksm-layout-loading"></div>');
    }

    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart', data: data, dataType: 'json', success: function (response) {
            for (var item_id in response.items) {
                if (response.items.hasOwnProperty(item_id) && /^0$|^[1-9]\d*$/.test(item_id) && item_id <= 4294967294) {
                    jQuery('.ksm-cart-item[data-item_id="' + item_id + '"] .ksm-cart-item-price').html(response.items[item_id].price_val);
                    jQuery('.ksm-cart-item[data-item_id="' + item_id + '"] .ksm-cart-item-sum').html(response.items[item_id].sum_val);
                }
            }
            jQuery('.ksm-cart-items-total-discount-price').html(response.discount_sum_val);
            jQuery('.ksm-cart-items-total-sum-price').html(response.products_sum_val);
            jQuery('.ksm-cart-total-discount-price').html(response.discount_sum_val);
            jQuery('.ksm-cart-total-sum-price').html(response.total_sum_val);

            for (var key in response.layouts) {
                jQuery('.' + key + '-plugin-renew').remove();
                jQuery('.' + key).replaceWith(response.layouts[key]);
            }

            if (response.message) {
                KMShowMessage(response.message);
            }
            if (window.KSMUpdateMinicart) {
                KSMUpdateMinicart();
            }
        }
    });
}

function setOrderUserField(name, field_value) {
    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=cart.updateOrderUserField&tmpl=ksenmart',
        type: 'POST',
        data: {
            name: name, field_value: field_value
        },
        success: function (data) {
        }
    });
}

function setOrderField(column, field) {
    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=cart.updateOrderField&tmpl=ksenmart', type: 'POST', data: {
            column: column, field: field
        }, success: function (data) {
        }
    });
}

function KMCartChangeRegion(obj) {
    var region_id = jQuery(obj).val();
    var data = {};

    data['layouts'] = {
        '0': 'default_steps'
    };
    data['view'] = 'cart';
    data['region_id'] = region_id;

    KMGetLayouts(data);

    setTimeout(function () {
        jQuery('#customer_phone').inputmasks(maskOpts);
    }, 100);
}

function KMCartChangeShipping(obj) {
    var shipping_id = jQuery(obj).val();

    var data = {};

    data['layouts'] = {
        '0': 'default_steps'
    };
    data['view'] = 'cart';
    data['shipping_id'] = shipping_id;

    KMGetLayouts(data);

    if (jQuery.fn.chosen != undefined) {
        jQuery('select').chosen();
    }

    setTimeout(function () {
        jQuery('#customer_phone').inputmasks(maskOpts);
    }, 100);
}

function KMCartChangePayment(obj) {
    var payment_id = jQuery(obj).val();

    var data = {};

    data['layouts'] = {
        '0': 'default_total'
    };
    data['view'] = 'cart';
    data['payment_id'] = payment_id;

    KMGetLayouts(data);
}

function KSMSetCartUpdateTimer(delay) {
    delay = delay ? 1000 : 0;

    clearTimeout(KSMCartUpdateTimer);
    KSMCartUpdateTimer = setTimeout(KSMCartUpdate, delay);
}