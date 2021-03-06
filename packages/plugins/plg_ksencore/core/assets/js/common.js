jQuery(document).ready(function () {

    jQuery('body').on('click', '.km-modal', function (e) {
        e.preventDefault();
        var rel = jQuery(this).attr('rel');
        var url = jQuery(this).attr('href');
        var width = jQuery(window).width();
        rel = JSON.parse(rel);
        if (width < 768) {
            if (rel.x.indexOf('%') != -1) rel.x = '100%';
            if (rel.y.indexOf('%') != -1) rel.y = '100%';
        }
        if (rel.x.indexOf('%') != -1) {
            rel.x = parseInt(rel.x);
            rel.x = Math.round(width * rel.x / 100);
        } else
            rel.x = parseInt(rel.x);
        if (rel.y.indexOf('%') != -1) {
            rel.y = parseInt(rel.y);
            rel.y = Math.round(jQuery(window).height() * rel.y / 100);
        } else
            rel.y = parseInt(rel.y);
        if (jQuery(this).closest('.disabled_ext').length)
            openPopupDisabledWindow(url, rel.x, rel.y);
        else
            openPopupWindow(url, rel.x, rel.y);
        return false;
    });

    jQuery('.linka').click(function () {
        var class_name = '.form div.' + jQuery(this).attr('rel');
        if (jQuery(class_name).is(':visible')) {
            jQuery(class_name).hide();
        } else {
            jQuery(class_name).show();
        }
        return false;
    });

    jQuery('.slide_module').on('click', 'ul li a', function () {
        var a = jQuery(this);
        if (a.hasClass('sh')) {
            if (a.hasClass('show')) {
                a.removeClass('show');
                a.addClass('hides');
                a.parents("li:first").find("ul:first").addClass('opened');
                a.parents("li:first").find("ul:first").slideDown(300);
            } else if (a.hasClass('hides')) {
                a.removeClass('hides');
                a.addClass('show');
                a.parents("li:first").find("ul:first").removeClass('opened');
                a.parents("li:first").find("ul:first").slideUp(300);
            }
        }

        return false;
    });
});

function createPopup(title, p_class, save_button) {
    var html = '<div class="overlay ' + p_class + '"></div><form class="form" id="' + p_class + '" method="POST" action="index.php?option=com_ksenmart"><div class="popup ' + p_class + '"><header class="heading clearfix"><div class="title">' + title + '</div><div class="buttonPannel clearfix">';

    html += '<input type="button" class="btn close js-popup_close" value="" />';
    if (save_button) {
        html += '<input type="submit" value="Сохранить" class="btn save" />';
    }

    html += '</div></header><div class="body clearfix"></div></div></form>';

    if (jQuery('.popup').length == 0) {
        jQuery('html').css('overflow-y', 'hidden');
    }
    jQuery('body').append(html);
}

function messagesBlockResize(e) {
    if (e.length > 0) {
        var windowHeight = jQuery(window).height();
        var sendFormHeight = jQuery('#send_form').height();

        e.css('height', windowHeight - sendFormHeight - 230);
        e.scrollTop(e[0].scrollHeight);
    }
    return false;
}

function popupBlockResize(e) {
    var windowHeight = jQuery(window).height();
    var popup_height = e.height();

    if (popup_height >= windowHeight - 200) {
        e.css('height', windowHeight - 200);
    }
}

function createPopupNotice(text, p_class) {
    var notice = '<div class="notice">' + text + '</div>';
    jQuery(p_class).children('.body').prepend(notice);
    jQuery(p_class + ' .notice').fadeIn(400).delay(5000).fadeOut(400, function () {
        jQuery(this).remove()
    });
}

function createPopupNoticeTextarea(text, p_class) {
    var notice = '<div class="notice">' + text + '</div>';
    jQuery(p_class).children('.body').children('#send_form').prepend(notice);
    jQuery(p_class + ' .notice').fadeIn(400).delay(5000).fadeOut(400, function () {
        jQuery(this).remove()
    });
}

function closePopup(e) {
    var popup = e.parents('.popup');
    var popup_class = popup.attr('class').replace('popup ', '');
    popup_class = popup_class.replace(' ', '.');

    popup.fadeOut(400, function () {
        jQuery(this).parent().remove();
        jQuery('.overlay.' + popup_class).fadeOut(400, function () {
            jQuery(this).remove();
            if (jQuery('.popup').length == 0) {
                jQuery('html').css('overflow-y', 'scroll');
            }
        });
    });
}

function shModuleContent(obj, content) {
    if (jQuery(obj).is('.hides')) {
        jQuery(obj).removeClass('hides');
        jQuery(obj).addClass('show');
        jQuery(content).slideUp(500);
    } else {
        jQuery(obj).removeClass('show');
        jQuery(obj).addClass('hides');
        jQuery(content).slideDown(500);
    }
}

function shSlideModuleContent(obj) {
    var module = jQuery(obj).parents('.slide_module');
    if (module.is('.active'))
        module.removeClass('active');
    else
        module.addClass('active');
}

function shModuleChilds(obj, content) {
    if (jQuery(obj).is('.hides')) {
        jQuery(obj).removeClass('hides');
        jQuery(obj).addClass('show');
        jQuery(content).slideUp(500);
    } else {
        jQuery(obj).removeClass('show');
        jQuery(obj).addClass('hides');
        jQuery(content).slideDown(500);
    }
}

function setActive(obj) {
    var item = jQuery(obj).parents('li:first');
    if (item.is('.active')) {
        item.removeClass('active');
        item.find('input:first').removeAttr('checked');
    } else {
        item.addClass('active');
        item.find('input:first').attr('checked', 'checked');
    }
}

function setActiveOne(obj) {
    var item = jQuery(obj).parents('li:first');
    if (item.is('.active')) {
        item.removeClass('active');
        item.find('input:first').removeAttr('checked');
    } else {
        //item.parents('.slide_module:first').find(':checked').removeAttr('checked');
        item.parents('.slide_module:first').find('.active').removeClass('active');
        item.addClass('active');
        item.find('input:first').attr('checked', 'checked');
    }
}

function setActiveOneRequired(obj) {
    var item = jQuery(obj).parents('li:first');
    if (item.is('.active')) {
        return false;
    } else {
        item.parents('.slide_module:first').find(':checked').removeAttr('checked');
        item.parents('.slide_module:first').find('.active').removeClass('active');
        item.addClass('active');
        item.find('input:first').attr('checked', 'checked');
    }
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

function KMShowLoading() {
    var width = jQuery('#element-box .m').width() + 20;
    var height = jQuery('#element-box .m').height() + 20;
    jQuery('#element-box .m').prepend('<div class="overlay"></div>');
    jQuery('.overlay').css({
        'position': 'absolute',
        'width': width,
        'height': height,
        'background': '#fff',
        'opacity': '0.7',
        'z-index': '1000'
    });
    jQuery('.overlay').html('<img src="/media/ksenmart/images/ajax-loader.gif">');
    var top = Math.round(height / 2) - 5;
    var left = Math.round(width / 2) - 8;
    jQuery('.overlay img').css({
        'margin-top': top,
        'margin-left': left
    });
}

function KMHideLoading() {
    jQuery('.overlay').remove();
}

var popup_count = 0;

function openPopupDisabledWindow(url, width, height) {
    popup_count++;

    jQuery('body').append('<div id="popup-window_' + popup_count + '" class="disabled_block">' + '<iframe scrolling="no" src="' + url + '"></iframe>' + '</div>');
    jQuery('#popup-window_' + popup_count + ' iframe').css({
        'width': 1000,
        'height': 400,
        'margin-left': Math.round(-1000 / 2),
        'margin-top': Math.round(-400 / 2)
    });
}

function openPopupWindow(url, width, height) {
    popup_count++;

    jQuery('body').append('<div id="popup-overlay_' + popup_count + '">' + '</div>' + '<div id="popup-window_' + popup_count + '">' + '<iframe scrolling="no" src="' + url + '"></iframe>' + '</div>');
    jQuery('#popup-window_' + popup_count + ' iframe').css({
        'width': width,
        'height': height,
        'margin-left': Math.round(-width / 2),
        'margin-top': Math.round(-height / 2)
    });
}

function closePopupWindow() {
    var pc = popup_count;
    popup_count--;
    jQuery(document).find('#popup-overlay_' + pc).remove();
    jQuery(document).find('#popup-window_' + pc).remove();

}

function changeOrder(obj, dest) {
    var orderByObj = jQuery(obj).find(':nth-child(2)');
    var orderDirObj = jQuery(obj).find(':nth-child(1)');

    if (jQuery(obj).is('.active')) {
        if (orderDirObj.val() == 'asc')
            orderDirObj.val('desc');
        else
            orderDirObj.val('asc');
    }
    orderBy = orderByObj.val();
    orderDir = orderDirObj.val();
    jQuery.post(
        '', {
            'task': 'product.change_filters',
            'orderBy': orderByObj.val(),
            'orderDir': orderDirObj.val()
        },
        function (data) {
            jQuery(document).scrollTop('0px');
            jQuery(dest).replaceWith(data);
            renews();
        },
        'html'
    );
}

function setFilter(obj, item, parent, dest) {
    if (jQuery(obj).is(':checked'))
        jQuery(item).addClass('active');
    else
        jQuery(item).removeClass('active');
    var data = jQuery(parent).serialize();
    jQuery.post(
        '',
        data,
        function (data) {
            jQuery(document).scrollTop('0px');
            jQuery(dest).replaceWith(data);
            renews();
        },
        'html'
    );
}

function showActions(obj) {
    jQuery(obj).find('.actions').css('visibility', 'visible');
}

function hideActions(obj) {
    jQuery(obj).find('.actions').css('visibility', 'hidden');
}

function KMFixProductCount(count) {
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

function KMShowMessage(message) {
    alert(message);
    return true;
    jQuery('.km-message').remove();
    jQuery('body').append('<div class="km-message">' + message + '</div>');
    jQuery('.km-message').fadeIn(500).delay(2500).fadeOut(500, function () {
        jQuery('.km-message').remove()
    });
}

function KMRenewFormFields(data) {
    data['task'] = 'get_form_fields';
    jQuery.ajax({
        url: 'index.php?option=com_ksenmart',
        data: data,
        dataType: 'json',
        async: false,
        success: function (responce) {
            for (var k = 0; k < data['fields'].length; k++)
                jQuery('.form .' + data['fields'][k]).html(responce[data['fields'][k]]);
            cuSel({
                changedEl: "select.sel"
            });
        }
    });
}