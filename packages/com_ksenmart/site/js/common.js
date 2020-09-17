jQuery(document).ready(function() {

    jQuery('body').on('submit', '.ksm-block form', function(e) {
		if (!e.target.checkValidity()) 
		{
            e.preventDefault();
            alert(Joomla.JText._('KSM_MESSAGE_FILL_FORM'));
        }
	});
	
    jQuery('body').on('submit', '.ksm-catalog-item-buy-form', function(e) {
        e.preventDefault();

        var form = jQuery(this);
        var product_packaging = form.find('[name="product_packaging"]');

        if (product_packaging.length) {

            var prd_id = form.find('input[name="id"]');
            var prd_price = form.find('input[name="price"]');
            var count = parseFloat(form.find('input[name="count"]').val());
            var flag = true;
            var product_packaging = parseFloat(form.find('input[name="product_packaging"]').val());

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
            jQuery.ajax({
                url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.validate_in_stock&' + form.serialize() + '&tmpl=ksenmart',
				cache: false,
                async: false,
                success: function(data) {
                    if (data != '') {
                        KMShowMessage(data);
                        flag = false;
                    }
                }
            });
            if (!flag)
                return;

            if (order_process == 1) {
                jQuery.ajax({
                    url: URI_ROOT + 'index.php?option=com_ksenmart&task=cart.get_order_id&tmpl=ksenmart',
					cache: false,
                    success: function(data) {
                        var order_id = data;
                        if (order_id == 0) {
                            if (user_id == 0) {
                                KMOpenPopupWindow(URI_ROOT + 'index.php?option=com_ksenmart&view=cart&layout=preorder&' + form.serialize() + '&tmpl=component', 610, 300);
                            } else {
                                jQuery.ajax({
                                    url: URI_ROOT + 'index.php?option=com_ksenmart&view=cart&task=cart.add_to_cart&' + form.serialize(),
									cache: false,
                                    success: function(data) {
										if (window.KSMUpdateMinicart)
										{
											KSMUpdateMinicart();
										}

                                        KMShowCartMessage();
                                    }
                                });
                            }
                        } else {
                            jQuery.ajax({
                                url: URI_ROOT + 'index.php?option=com_ksenmart&view=cart&task=cart.add_to_cart&' + form.serialize(),
								cache: false,
                                success: function(data) {
									if (window.KSMUpdateMinicart)
									{
										KSMUpdateMinicart();
									}
									
                                    KMShowCartMessage();
                                }
                            });
                        }
                    }
                });
            } else {
                jQuery.ajax({
                    url: URI_ROOT + 'index.php?option=com_ksenmart&view=cart&task=cart.add_to_cart&' + form.serialize(),
					cache: false,
                    success: function(data) {
						if (window.KSMUpdateMinicart)
						{
							KSMUpdateMinicart();
						}
						
                        KMShowCartMessage();
                    }
                });
            }
        }
    });

    jQuery('body').on('click', '.ksm-message .ksm-cart-message-link-shop', function() {
        jQuery('.ksm-message').remove();
    });

    jQuery('body').on('click', '.ksm-iframe-close', function() {
        jQuery('#ksm-iframe-overlay').remove();
        jQuery('#ksm-iframe-window').remove();
    });

});

function closeKMMessage(delay) {
    if (delay != 0) {
        delay = 2500;
    }
    jQuery('.ksm-message').fadeIn(500).delay(delay).fadeOut(500, function() {
        jQuery(this).remove()
    });
}

function KMShowCartMessage() {
    closeKMMessage(0);
	var html = '';

	html += '<div class="ksm-message ksm-cart-message ksm-block">';
	html += '	<div class="ksm-cart-message-image animate">';	
	html += '		<span class="ksm-cart-message-image-tip"></span>';
	html += '		<span class="ksm-cart-message-image-long"></span>';
	html += '		<div class="ksm-cart-message-image-placeholder"></div>';
	html += '		<div class="ksm-cart-message-image-fix"></div>';
	html += '	</div>';
	html += '	<div class="ksm-cart-message-text">' + Joomla.JText._('KSM_CART_PRODUCT_ADDED_TO_CART') + '</div>';
	html += '	<div class="ksm-cart-message-buttons">';
	html += '		<a class="ksm-btn ksm-cart-message-link-shop">'+Joomla.JText._('KSM_CART_CONTINUE_SHOPPING')+'</a>';
	html += '		<a href="' + km_cart_link + '" class="ksm-cart-message-link-cart">'+Joomla.JText._('KSM_CART_CHECKOUT_ORDER')+'</a>';
	html += '	</div>';
	html += '</div>';

    jQuery('body').append(html);
    closeKMMessage();
}

function KMShowMessage(message) {
    closeKMMessage(0);
	var html = '';
	
	html += '<div class="ksm-message ksm-block">';
	html += '	<div class="ksm-message-body"><h3>' + message + '</h3></div>';
	html += '</div>';	
	
	jQuery('body').append(html);
    closeKMMessage();
}

function KMOpenPopupWindow(url, width, height) {
    jQuery('body').append('<div id="ksm-iframe-overlay"></div><div id="ksm-iframe-window"><span class="ksm-iframe-close"></span><iframe scrolling="no" src="' + url + '"></iframe></div>');
    jQuery('#ksm-iframe-window').css({
        'width': width,
        'height': height,
        'margin-left': Math.round(-width / 2),
        'margin-top': Math.round(-height / 2)
    });
    jQuery('#ksm-iframe-window iframe').css({
        'width': width,
        'height': height,
        'border': 'none'
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
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=set_session_variable&tmpl=ksenmart',
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
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=get_session_variable&tmpl=ksenmart',
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

function KMSendActivity() {
    var date = new Date();
    var time = Math.round(date.getTime() / 1000);
    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=set_user_activity&tmpl=ksenmart',
        data: {
            time: time
        },
        async: false,
        type: "post",
        success: function(data) {}
    });
}


function KMGetLayouts(data) {
	if(data.layouts != undefined){
		for (var key in data.layouts){
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
        success: function(responce) {
            for (var key in responce) {
                jQuery('.' + key + '-plugin-renew').remove();
                jQuery('.' + key).replaceWith(responce[key]);
            }
        }
    });
}

var activityTimer = setInterval("KMSendActivity();", 60000);