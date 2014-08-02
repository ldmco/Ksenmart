jQuery(document).ready(function() {

    jQuery('.inputbox').change(function() {
        var name = jQuery(this).attr('name');
        var value = jQuery(this).val();
        KMSaveVariable(name, value);
    });

    jQuery('.inputbox').blur(function() {
        var name = jQuery(this).attr('name');
        var value = jQuery(this).val();
        KMSaveVariable(name, value);
    });

    jQuery('.catalog .item .edit-menu .add').on('click', function() {
        var width = Math.round(jQuery(window).width() * 9 / 10);
        var height = Math.round(jQuery(window).height() * 9 / 10);
        KMOpenPopupWindow(URI_ROOT + 'index.php?option=com_ksenmart&view=shopadmin&layout=product&id=0&category=' + cat_id + '&manufacturer=0&collection=0&tmpl=component', width, height);
        return false;
    });

    jQuery('.catalog .item .edit-menu .edit').on('click', function() {
        var product_id = jQuery(this).parents('.item').find('input[name="id"]').val();
        var width = Math.round(jQuery(window).width() * 9 / 10);
        var height = Math.round(jQuery(window).height() * 9 / 10);
        KMOpenPopupWindow(URI_ROOT + 'index.php?option=com_ksenmart&view=shopadmin&layout=product&id=' + product_id + '&category=' + cat_id + '&manufacturer=0&collection=0&tmpl=component', width, height);
        return false;
    });

    jQuery('.catalog .item .edit-menu .del').on('click', function() {
        var product_id = jQuery(this).parents('.item').find('input[name="id"]').val();
        jQuery.ajax({
            url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopadmin.del_products&products[]=' + product_id + ':product&tmpl=ksenmart',
            success: function(data) {
                alert(data);
                window.location.reload();
            }
        });
        return false;
    });

    jQuery(document).click(function(event) {
        if (jQuery('.popup:visible').length > 0) {
            if (!isChild(event.target, document.getElementById(jQuery('.popup:visible').attr('id'))))
                jQuery('.popup').hide();
        }
    });

    jQuery('.review.edit img, .review.add img').on('click', function() {
        var review = jQuery(this).parents('.review');
        var rate = jQuery(this).data().rate;

        review.find('#comment_rate').val(rate);
        review.find('img').attr('src', URI_ROOT + 'components/com_ksenmart/images/star2-small.png');

        for (var k = 1; k <= rate; k++) {
            review.find('img[data-rate="' + k + '"]').attr('src', URI_ROOT + 'components/com_ksenmart/images/star-small.png');
        }
    });

    jQuery('.phone_country').keydown(function(e) {
        keynum = e.which;
        keynum = parseInt(keynum);
        var no_digit = false;
        if (keynum == 13)
            return false;
        if (keynum >= 33 && keynum <= 40)
            return true;
        if (keynum == 187)
            no_digit = true;
        if (keynum == 8)
            no_digit = true;
        if (keynum == 17)
            return true;
        if (keynum == 45)
            return true;
        if (keynum == 46)
            no_digit = true;
        if (keynum >= 96 && keynum <= 105) {
            keynum -= 48;
        }
        if (!no_digit) {
            keychar = String.fromCharCode(keynum);
            numcheck = /\d/;
            var res = numcheck.test(keychar);
        } else
            res = true;
        return res;
    });

    jQuery('.phone_code').keydown(function(e) {
        keynum = e.which;
        keynum = parseInt(keynum);
        var no_digit = false;
        if (keynum == 13)
            return false;
        if (keynum >= 33 && keynum <= 40)
            return true;
        if (keynum == 8)
            no_digit = true;
        if (keynum == 17)
            return true;
        if (keynum == 45)
            return true;
        if (keynum == 46)
            no_digit = true;
        if (keynum >= 96 && keynum <= 105) {
            keynum -= 48;
        }
        if (!no_digit) {
            keychar = String.fromCharCode(keynum);
            numcheck = /\d/;
            var res = numcheck.test(keychar);
        } else
            res = true;
        return res;
    });

    jQuery('.phone').keydown(function(e) {
        keynum = e.which;
        keynum = parseInt(keynum);
        var no_digit = false;
        if (keynum == 13)
            return false;
        if (keynum >= 33 && keynum <= 40)
            return true;
        if (keynum == 8)
            no_digit = true;
        if (keynum == 17)
            return true;
        if (keynum == 45)
            return true;
        if (keynum == 46)
            no_digit = true;
        if (keynum >= 96 && keynum <= 105) {
            keynum -= 48;
        }
        if (!no_digit) {
            keychar = String.fromCharCode(keynum);
            numcheck = /\d/;
            var res = numcheck.test(keychar);
        } else
            res = true;
        return res;
    });

    jQuery('.buy [type="submit"]').parents('form').on('submit', function() {

        var form = jQuery(this);
        var prd_id = form.find('input[name="id"]');
        var prd_price = form.find('input[name="price"]');
        var count = parseFloat(form.find('input[name="count"]').val());
        var flag = true;
        var product_packaging = parseFloat(form.find('input[name="product_packaging"]').val());

        count = Math.ceil(count / product_packaging) * product_packaging;
        count = count.toFixed(4);
        count = fixCount(count);

        form.find('.options .row').each(function() {
            if (jQuery(this).find('input[type="hidden"]').length > 0 && jQuery(this).find('input[type="hidden"]').val() == 0) {
                if (!jQuery(this).is('.row_active'))
                    jQuery(this).addClass('row_active');
                flag = false;
            } else if (jQuery(this).find('input[type="radio"]').length > 0 && jQuery(this).find('input[type="radio"]:checked').length == 0) {
                if (!jQuery(this).is('.row_active'))
                    jQuery(this).addClass('row_active');
                flag = false;
            }
        });
        if (!flag) {
            jQuery('body').animate({
                'scrollTop': form.offset().top
            }, 500);
            return false;
        }
        jQuery.ajax({
            url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.validate_in_stock&' + form.serialize() + '&tmpl=ksenmart',
            async: false,
            success: function(data) {
                if (data != '') {
                    KMShowMessage(data);
                    flag = false;
                }
            }
        });
        if (!flag)
            return false;

        if (order_process == 1) {
            jQuery.ajax({
                url: URI_ROOT + 'index.php?option=com_ksenmart&view=order&task=order.get_order_id&tmpl=ksenmart',
                success: function(data) {
                    var order_id = data;
                    if (order_id == 0) {
                        if (user_id == 0) {
                            KMOpenPopupWindow(URI_ROOT + 'index.php?option=com_ksenmart&view=order&' + form.serialize() + '&tmpl=component', 610, 300);
                        } else {
                            jQuery.ajax({
                                url: URI_ROOT + 'index.php?option=com_ksenmart&view=cart&task=cart.add_to_cart&layout=minicart&' + form.serialize() + '&tmpl=ksenmart',
                                success: function(data) {
                                    jQuery('#minicart').html(data);
                                    KMShowCartMessage('Товар добавлен в корзину');
                                }
                            });
                        }
                    } else {
                        jQuery.ajax({
                            url: URI_ROOT + 'index.php?option=com_ksenmart&view=cart&task=cart.add_to_cart&layout=minicart&' + form.serialize() + '&tmpl=ksenmart',
                            success: function(data) {
                                jQuery('#minicart').html(data);
                                KMShowCartMessage('Товар добавлен в корзину');
                            }
                        });
                    }
                }
            });
        } else {
            jQuery.ajax({
                url: URI_ROOT + 'index.php?option=com_ksenmart&view=cart&task=cart.add_to_cart&layout=minicart&' + form.serialize() + '&tmpl=ksenmart',
                success: function(data) {
                    jQuery('#minicart').html(data);
                    KMShowCartMessage('Товар добавлен в корзину');
                }
            });
        }
        return false;
    });

    jQuery('.buy-price2 .buy .button').on('click', function() {
        jQuery('.buy-price .buy .button').click();
        return false;
    });

    jQuery('.buy-price .buy a').on('click', function() {

        var form = jQuery(this).parents('form');
        var flag = true;

        form.find('.options .row').each(function() {
            if (jQuery(this).find('.cusel').length > 0 && jQuery(this).find('input').val() == 0) {
                if (!jQuery(this).is('.row_active'))
                    jQuery(this).addClass('row_active');
                flag = false;
            } else if (jQuery(this).find('input[type="radio"]').length > 0 && jQuery(this).find('input[type="radio"]:checked').length == 0) {
                if (!jQuery(this).is('.row_active'))
                    jQuery(this).addClass('row_active');
                flag = false;
            }
        });
        if (!flag) {
            jQuery('body').animate({
                'scrollTop': jQuery('#item').offset().top
            }, 500);
            return false;
        }
        KMOpenPopupWindow(URI_ROOT + 'index.php?option=com_ksenmart&view=order&' + form.serialize() + '&close_order=1&tmpl=component', 920, Math.round(jQuery(window).height() * 9 / 10));
        return false;
    });

    jQuery('.buy-price2 .buy a').on('click', function() {
        jQuery('.buy-price .buy a').click();
        return false;
    });

    jQuery('.infos .buy .button').on('click', function() {
        var prd_id = jQuery(this).attr('prd_id');
        var prd_price = jQuery(this).attr('prd_price');
        var form = jQuery(this).parents('form');
        if (order_process == 1) {
            jQuery.ajax({
                url: URI_ROOT + 'index.php?option=com_ksenmart&view=order&task=order.get_order_id&tmpl=ksenmart',
                success: function(data) {
                    var order_id = data;
                    if (order_id == 0) {
                        KMOpenPopupWindow(URI_ROOT + 'index.php?option=com_ksenmart&view=order&' + form.serialize() + '&tmpl=component', 920, Math.round(jQuery(window).height() * 9 / 10));
                    } else {
                        jQuery.ajax({
                            url: URI_ROOT + 'index.php?option=com_ksenmart&view=cart&task=cart.add_to_cart&layout=minicart&' + form.serialize() + '&tmpl=ksenmart',
                            success: function(data) {
                                jQuery('#minicart').html(data);
                                KMShowCartMessage('Товар добавлен в корзину');
                            }
                        });
                    }
                }
            });
        } else {
            jQuery.ajax({
                url: URI_ROOT + 'index.php?option=com_ksenmart&view=cart&task=cart.add_to_cart&layout=minicart&' + form.serialize() + '&tmpl=ksenmart',
                success: function(data) {
                    jQuery('#minicart').html(data);
                    KMShowCartMessage('Товар добавлен в корзину');
                }
            });
        }
        return false;
    });

    jQuery('.infos .buy a').on('click', function() {
        KMOpenPopupWindow(URI_ROOT + 'index.php?option=com_ksenmart&view=order&' + form.serialize() + '&close_order=1&tmpl=component', 920, Math.round(jQuery(window).height() * 9 / 10));
        return false;
    });

    jQuery('body').on('click', '.km-message .link_shop', function() {
        jQuery('.km-message').remove();
    });

    jQuery('body').on('click', '.km-popup .close', function() {
        jQuery(this).parents('.km-popup').fadeOut(400);
    });

    jQuery('body').on('click', '.km-iframe-close', function() {
        jQuery('#km-iframe-overlay').remove();
        jQuery('#km-iframe-window').remove();
    });

    jQuery('body').on('click', '.popup_close', function() {
        closeKMMessage(0);
    });

    jQuery('.add_shop_review a').click(function() {
        var new_address = jQuery('.review');
        if (new_address.is(':visible')) {
            new_address.slideUp(500);
        } else {
            new_address.slideDown(500);
        }
        jQuery('body, html').animate({
            scrollTop: 0
        }, 500);
        return false;
    });

    jQuery('body').on('click', '.item-cart .del a, #minicart .remove a', function() {
        var link = jQuery(this);
        var count = 0;
        var item_id = jQuery(this).data().item_id;

        jQuery.ajax({
            url: URI_ROOT + 'index.php?option=com_ksenmart&view=cart&layout=minicart&task=cart.update_cart&item_id=' + item_id + '&count=' + count + '&tmpl=ksenmart',
            success: function(data) {
                KMShowMessage('<h2>Заказ обновлен.</h2>');
                link.parents('.item-cart').remove();
                if (jQuery('#cart .item-cart .del').length == 0) {
                    jQuery('#cart').html('<h1 class="clear_cart">Ваш заказ пуст</h1>');
                    jQuery('#order').html('');
                } else {
                    update_prices();
                }
                jQuery('#minicart').html(data);
            }
        });

        return false;
    });
});

function magicalText(wrapp) {
    jQuery(wrapp).each(function() {
        var wrapp = jQuery(this);

        if (typeof wrapp.data().title != 'undefined') {
            var title = wrapp.data().title;
        } else {
            var title = 'Редактировать';
        }

        wrapp.append('<div class="follow">' + title + '</div>');
        var follow = wrapp.children('.follow');

        var wW = wrapp.width();
        var wH = wrapp.height();

        var oT = wrapp.offset().top;
        var oL = wrapp.offset().left;

        var fW = follow.width();
        var fH = follow.height();

        wrapp.on('mousemove', function(e) {
            follow.show();
            x = e.pageX - 7;
            y = e.pageY - fH / 2;

            if (x >= wW + oL - fW) {
                x = wW + oL - fW;
            }
            if (x <= oL) {
                x = oL;
            }
            if (y >= wH + oT - fH) {
                y = wH + oT - fH;
            }
            if (y <= oT) {
                y = oT;
            }

            follow.css('top', y).css('left', x);
        });

        wrapp.on('mouseout', function(e) {
            follow.hide();
        });
    });
}

function closeKMMessage(delay) {
    if (delay != 0) {
        delay = 2500;
    }
    jQuery('.km-message').fadeIn(500).delay(delay).fadeOut(500, function() {
        jQuery(this).remove()
    });
}

function KMShowCartMessage(text) {
    closeKMMessage(0);
    var heading = '<div class="modal-header"><h3 id="myModalLabel">' + text + '</h3></div>';
    var bottom = '<div class="km-message-bottom btn-toolbar row-fluid"><div class="btn-group span6"><a class="link_shop btn btn-info btn-large span12 popup_close">Продолжить покупки</a></div><div class="btn-group span6"><a href="' + km_cart_link + '" class="link_cart btn btn-success btn-large span12">Оформить заказ</a></div></div>';

    jQuery('body').append('<div class="km-message mdl_add_cart modal noTransition">' + heading + bottom + '</div>');
    closeKMMessage(2500);
}

function KMShowMessage(message) {
    jQuery('.km-message').remove();
    jQuery('body').append('<div class="km-message modal noTransition"><div class="modal-body"><h3 id="myModalLabel">' + message + '</h3></div></div>');
    closeKMMessage();
}

function KMShowPopup(parent, obj, rel_left, rel_top) {
    var top = parent.offset().top - jQuery(window).scrollTop();
    var bottom = jQuery(window).height() - top - parent.height();
    jQuery('.km-popup').hide();
    if (obj.height() > bottom)
        top = parent.offset().top - obj.height();
    else
        top = parent.offset().top + parent.height();
    var min_right = 30;
    if (parent.offset().left + obj.width() + min_right > jQuery(window).width())
        var left = jQuery(window).width() - min_right - obj.width();
    else
        var left = parent.offset().left;
    obj.css({
        'left': left - rel_left,
        'top': top - rel_top
    });
    jQuery(obj).fadeIn(400);
}

function isValidEmail(email, strict) {
    if (!strict) email = email.replace(/^\s+|\s+$/g, '');
    return (/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i).test(email);
}

function validatePhone(phone_country, phone_code, phone) {
    if (phone_country.length == 1) {
        KMShowMessage('Введите код страны вашего телефона , например : +7');
        return false;
    }
    if (phone_country[0] != '+') {
        KMShowMessage('Введите корректно код страны вашего телефона , например : +7');
        return false;
    }
    if (phone_code == '' || phone_code.length < 3) {
        KMShowMessage('Введите код города вашего телефон , например : 495');
        return false;
    }
    if (phone == '' || phone.length < 4) {
        KMShowMessage('Введите номер вашего телефона , например : 1234567');
        return false;
    }
    return true;
}

function isChild(e, p) {
    while (e) {
        if (e == p)
            return true;
        else
            e = e.parentNode;
    }
    return false;
}

function KMOpenPopupWindow(url, width, height) {
    jQuery('body').append('<div id="km-iframe-overlay"></div><div id="km-iframe-window"><span class="km-iframe-close"></span><iframe scrolling="no" src="' + url + '"></iframe></div>');
    jQuery('#km-iframe-window').css({
        'width': width,
        'height': height,
        'margin-left': Math.round(-width / 2),
        'margin-top': Math.round(-height / 2)
    });
    jQuery('#km-iframe-window iframe').css({
        'width': width,
        'height': height,
        'border': 'none'
    });
}

function KMClosePopupWindow() {
    jQuery('#km-iframe-overlay').remove();
    jQuery('#km-iframe-window').remove();
}

function KMSaveVariable(name, value) {
    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.save_variable&name=' + name + '&value=' + value + '&tmpl=ksenmart',
        success: function(data) {}
    });
}

function fixCount(count) {
    if (Math.round(count) == count) {
        count = Math.round(count);
        return count;
    }
    if (count.indexOf('.') != -1) {
        var new_count = '';
        var flag = true;
        for (var k = count.length - 1; k >= 0; k--) {
            if (count[k] == 0 && flag)
                continue;
            else
                flag = false;
            new_count = count[k] + new_count;
        }
        count = parseFloat(new_count);
    }
    return count;
}

function KMSetSessionVariable(name, value) {
    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.set_session_variable&tmpl=ksenmart',
        data: {
            name: name,
            value: value
        },
        async: false,
        type: "post",
        success: function(data) {}
    });
}

function KMGetSessionVariable(name) {
    var value = '';
    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.get_session_variable&tmpl=ksenmart',
        data: {
            name: name
        },
        async: false,
        type: "post",
        success: function(data) {
            value = data;
        }
    });
    return value;
}

function KMSetSessionData(session_data) {
    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.set_session_data&tmpl=ksenmart',
        data: {
            session_data: session_data
        },
        type: "post",
        success: function(data) {

        }
    });
}

function KMGetSessionData() {
    var value = '';
    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.get_session_data&tmpl=ksenmart',
        async: false,
        type: "post",
        success: function(data) {

            value = data;
        }
    });
    return value;
}

function KMSendActivity() {
    var date = new Date();
    var time = Math.round(date.getTime() / 1000);
    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.set_user_activity&tmpl=ksenmart',
        data: {
            time: time
        },
        async: false,
        type: "post",
        success: function(data) {}
    });
}


function KMGetLayouts(data) {
    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=get_layouts&view=cart&tmpl=ksenmart',
        data: data,
        dataType: 'json',
        async: false,
        success: function(responce) {
            for (var key in responce) {
                jQuery('.' + key + '-plugin-renew').remove();
                jQuery('.' + key).replaceWith(responce[key]);
            }
        }
    });
}

var activityTimer = setInterval("KMSendActivity();", 60000);