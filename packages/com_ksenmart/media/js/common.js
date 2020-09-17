jQuery(document).ready(function () {
    var form_flag = true;

    jQuery('body').on('submit', '.ksm-block form', function (e) {
        if (!e.target.checkValidity()) {
            e.preventDefault();
            form_flag = false;
            alert(Joomla.JText._('KSM_MESSAGE_FILL_FORM'));
        } else {
            form_flag = true;
        }
    });

    jQuery('body').on('submit', '.ksm-catalog-item-buy-form', function (e) {
        e.preventDefault();
        if (!form_flag) return false;

        var form = jQuery(this);
        var product_packaging = form.find('[name="product_packaging"]');

        if (product_packaging.length) {
            var count = parseFloat(form.find('input[name="count"]').val());
            var flag = true;
            product_packaging = parseFloat(form.find('input[name="product_packaging"]').val());

            count = Math.ceil(count / product_packaging) * product_packaging;
            count = count.toFixed(4);
            count = fixCount(count);

            if (!count) {
                count = 1;
            }

            if (!flag) {
                jQuery('body').animate({
                    'scrollTop': form.offset().top
                }, 500);
                return;
            }

            if (order_process == 1) {
                jQuery.ajax({
                    url: URI_ROOT + 'index.php?option=com_ksenmart&task=cart.get_order_id&tmpl=ksenmart',
                    cache: false,
                    success: function (data) {
                        var order_id = data;
                        if (order_id == 0) {
                            if (user_id == 0) {
                                KMOpenPopupWindow(URI_ROOT + 'index.php?option=com_ksenmart&view=cart&layout=preorder&' + form.serialize() + '&tmpl=component', 610, 400);
                            } else {
                                jQuery.ajax({
                                    url: URI_ROOT + 'index.php?option=com_ksenmart&view=cart&task=cart.add_to_cart&' + form.serialize(),
                                    cache: false,
                                    dataType: 'json',
                                    success: function (response) {
                                        if (response.status == '' || response.status == 'true') {
                                            KMShowCartMessage(response.html);
                                        } else {
                                            KMShowMessage(response.status);
                                        }
                                        if (window.KSMUpdateMinicart) {
                                            KSMUpdateMinicart();
                                        }
                                    }
                                });
                            }
                        } else {
                            jQuery.ajax({
                                url: URI_ROOT + 'index.php?option=com_ksenmart&view=cart&task=cart.add_to_cart&' + form.serialize(),
                                cache: false,
                                dataType: 'json',
                                success: function (response) {
                                    if (response.status == '') {
                                        KMShowCartMessage(response.html);
                                    } else {
                                        KMShowMessage(response.status);
                                    }
                                    if (window.KSMUpdateMinicart) {
                                        KSMUpdateMinicart();
                                    }
                                }
                            });
                        }
                    }
                });
            } else {
                jQuery.ajax({
                    url: URI_ROOT + 'index.php?option=com_ksenmart&view=cart&task=cart.add_to_cart&' + form.serialize(),
                    cache: false,
                    dataType: 'json',
                    success: function (response) {
                        if (response.status == '' || response.status == 'true') {
                            KMShowCartMessage(response.html);
                        } else {
                            KMShowMessage(response.status);
                        }
                        if (window.KSMUpdateMinicart) {
                            KSMUpdateMinicart();
                        }
                    }
                });
            }
        }
    });

    jQuery('body').on('click', '.ksm-cart-message-link-shop', function () {
        jQuery('.ksm-message').remove();
        jQuery(window.parent.document).find('.ksm-message').remove();
    });

    jQuery('body').on('click', '.ksm-iframe-close', function () {
        jQuery('#ksm-iframe-overlay').remove();
        jQuery('#ksm-iframe-window').remove();
    });

    jQuery('body').on('click', '.km-modal', function (e) {
        e.preventDefault();

        var rel = jQuery(this).attr('rel');
        var url = jQuery(this).attr('href');
        rel = JSON.parse(rel);
        if (rel.x.indexOf('%') != -1) {
            rel.x = parseInt(rel.x);
            rel.x = Math.round(jQuery(window).width() * rel.x / 100);
        } else
            rel.x = parseInt(rel.x);
        if (rel.y.indexOf('%') != -1) {
            rel.y = parseInt(rel.y);
            rel.y = Math.round(jQuery(window).height() * rel.y / 100);
        } else
            rel.y = parseInt(rel.y);
        openPopupWindow(url, rel.x, rel.y);

        return false;
    });

    jQuery('body').on('click', '.ksm-catalog-pagination-more', function (e) {
        e.preventDefault();

        var url = document.location.href;
        var start = parseInt(_GET('start'));
        var count = parseInt(jQuery(this).closest('.ksm-catalog').find('.ksm-catalog-item').length);
        if (start != null) {
            count += start;
        }
        url = UpdateQueryString('start', count, url);
        jQuery.ajax({
            url: url + '&tmpl=ksenmart', success: function (data) {
                var $data = jQuery(data);
                if ($data.find('.ksm-catalog-item').length == 0) {
                    jQuery('.ksm-catalog-pagination-more').hide();
                } else {
                    jQuery('.ksm-catalog .ksm-catalog-items').append($data.find('.ksm-catalog-items').html());
                }
            }
        });
    });

    jQuery('body').on('click', '.ksm-message-close', function (e) {
        e.preventDefault();

        closeKMMessage(0);
    })

});

function openPopupWindow(url, width, height) {
    jQuery('body').append('<div id="popup-overlay_1">' + '</div>' + '<div id="popup-window_1">' + '<iframe scrolling="no" src="' + url + '"></iframe>' + '</div>');
    jQuery('#popup-window_1 iframe').css({
        'width': width, 'height': height, 'margin-left': Math.round(-width / 2), 'margin-top': Math.round(-height / 2)
    });
}

function closePopupWindow() {
    jQuery(document).find('#popup-overlay_1').remove();
    jQuery(document).find('#popup-window_1').remove();
}

function closeKMMessage(delay) {
    if (delay == undefined) {
        delay = 2500;
    }
    jQuery('.ksm-message').fadeIn(500).delay(delay).fadeOut(500, function () {
        jQuery(this).remove()
    });
}

function KMShowCartMessage(response) {
    closeKMMessage(0);
    var html = '<div class="ksm-message ksm-cart-message ksm-block">' + response + '</div>';
    jQuery('body').append(html);
    closeKMMessage(10000);
}

function KMShowMessage(title, message) {
    closeKMMessage(0);
    var html = '';

    html += '<div class="ksm-message ksm-block">';
    html += '<span class="ksm-message-close close"></span>';
    if (message == undefined) {
        html += '	<div class="ksm-message-body"><h3>' + title + '</h3></div>';
    } else {
        html += '	<div class="ksm-message-header"><h3>' + title + '</h3></div>';
        html += '	<div class="ksm-message-body">' + message + '</div>';
    }
    html += '</div>';

    jQuery('body').append(html);
    closeKMMessage(10000);
}

function KMOpenPopupWindow(url, width, height, htmlclass) {
    jQuery('body').append('<div id="ksm-iframe-overlay"></div><div id="ksm-iframe-window"><span class="ksm-iframe-close"></span><iframe class="' + htmlclass + '" scrolling="" src="' + url + '"></iframe></div>');
    if (width.indexOf('%') != -1) {
        width = parseInt(width);
        width = Math.round(jQuery(window).width() * width / 100);
    }
    if (height.indexOf('%') != -1) {
        height = parseInt(height);
        height = Math.round(jQuery(window).height() * height / 100);
    }
    jQuery('#ksm-iframe-window').css({
        'width': width, 'height': height, 'margin-left': Math.round(-width / 2), 'margin-top': Math.round(-height / 2)
    });
    jQuery('#ksm-iframe-window iframe').css({
        'width': width, 'height': height, 'border': 'none'
    });
}

function KMClosePopupWindow() {
    jQuery('#ksm-iframe-overlay').remove();
    jQuery('#ksm-iframe-window').remove();
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
                continue; else
                flag = false;
            new_count = count[k] + new_count;
        }
        count = parseFloat(new_count);
    }
    return count;
}

function KMSetSessionVariable(name, value) {
    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=set_session_variable&tmpl=ksenmart', data: {
            name: name, value: value
        }, async: false, type: "post", success: function (data) {
        }
    });
}

function KMGetSessionVariable(name) {
    var value = '';
    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=get_session_variable&tmpl=ksenmart', data: {
            name: name
        }, async: false, type: "post", success: function (data) {
            value = data;
        }
    });
    return value;
}

function KMSendActivity() {
    var date = new Date();
    var time = Math.round(date.getTime() / 1000);
    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=set_user_activity&tmpl=ksenmart', data: {
            time: time
        }, async: false, type: "post", success: function (data) {
        }
    });
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

function KMGetLayouts(data) {
    if (data.layouts != undefined) {
        for (var key in data.layouts) {
            jQuery('.' + data.layouts[key]).css('position', 'relative');
            jQuery('.' + data.layouts[key]).append('<div class="ksm-layout-loading"></div>');
        }
    }
    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=get_layouts&tmpl=ksenmart',
        cache: false,
        data: data,
        dataType: 'json',
        async: false,
        success: function (responce) {
            for (var key in responce) {
                jQuery('.' + key + '-plugin-renew').remove();
                jQuery('.' + key).replaceWith(responce[key]);
            }
        }
    });
}

function _GET(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [null, ''])[1].replace(/\+/g, '%20')) || null;
}

function UpdateQueryString(key, value, url) {
    if (!url) {
        url = window.location.href;
    }
    var re = new RegExp("([?&])" + key + "=.*?(&|#|$)(.*)", "gi"), hash;

    if (re.test(url)) {
        if (typeof value !== 'undefined' && value !== null) {
            return url.replace(re, '$1' + key + "=" + value + '$2$3');
        } else {
            hash = url.split('#');
            url = hash[0].replace(re, '$1$3').replace(/(&|\?)$/, '');
            if (typeof hash[1] !== 'undefined' && hash[1] !== null) {
                url += '#' + hash[1];
            }
            return url;
        }
    } else {
        if (typeof value !== 'undefined' && value !== null) {
            var separator = url.indexOf('?') !== -1 ? '&' : '?';
            hash = url.split('#');
            url = hash[0] + separator + key + '=' + value;
            if (typeof hash[1] !== 'undefined' && hash[1] !== null) {
                url += '#' + hash[1];
            }
            return url;
        } else {
            return url;
        }
    }
}

var activityTimer = setInterval("KMSendActivity();", 60000);