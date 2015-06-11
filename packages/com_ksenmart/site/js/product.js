jQuery(document).ready(function() {
    jQuery('#products_gallery').slides({
        preload: true,
        preloadImage: 'img/loading.gif',
        effect: 'slide, fade',
        crossfade: true,
        slideSpeed: 200,
        fadeSpeed: 0,
        generateNextPrev: true,
        generatePagination: false,
        dynamicallyUpdateAnchors: false
    });

    jQuery('.l-reply').on('click', function() {
        var reply_block = jQuery('.reply_block');
        var id = jQuery(this).parents('.item.reviews').data().id;
        var destination = 0;

        reply_block.children('form').attr('data-id', id);
        reply_block.show();

        destination = reply_block.offset().top;
        jQuery('body').animate({
            scrollTop: destination
        }, 1100);
    });

    jQuery('.reply_block').children('form').on('submit', function(e) {
        e.preventDefault();

        var id = jQuery(this).data().id;
        var product_id = jQuery(this).data().product_id;
        var comment = jQuery(this).find('[name="reply"]').val();

        jQuery.ajax({
            type: 'POST',
            url: URI_ROOT + 'index.php?option=com_ksenmart&task=product.addCommentReply&tmpl=ksenmart',
            data: {
                id: id,
                comment: comment,
                product_id: product_id
            },
            success: function(data) {}
        });
    });

    jQuery('.unit .info #property_childs').change(function() {
        var url = jQuery(this).val();
        if (url != '')
            window.location.href = url;
    });

    var properties = {};
    jQuery('[name*="property_"]').on('change', function() {
        var form = jQuery(this).parents('form');
        var val_prop_id = jQuery(this).val();
        var prop_id = jQuery(this).data().prop_id;
        var price = form.find('[name="price"]').val();
        var id = form.find('[name="id"]').val();
        var count = form.find('[name="count"]').val();
        var product_packaging = form.find('[name="product_packaging"]').val();
        var propertiesE = form.find('select option, input[type="radio"], input[type="checkbox"]');
		if (jQuery(this).is('[type="checkbox"]')){
			jQuery(this).parents('.controls').find('[type="checkbox"]').each(function(){
				if (jQuery(this).val() != val_prop_id)
					jQuery(this).removeAttr('checked');
			});
		}

        propertiesE.each(function(indx, element) {
            var jqEl = jQuery(element);
            var valueId = jqEl.val();
            var propId = 0;

            if (element.localName == 'option') {
                propId = jqEl.parents('select').data().prop_id;
            } else {
                propId = jqEl.data().prop_id;
            }
            if (val_prop_id == valueId && (element.checked || element.selected)) {
                if (valueId != '') {
                    if (typeof properties[propId] == 'undefined') {
                        properties[propId] = {};
                    }
                    properties[propId][valueId] = {
                        'propId': propId,
                        'valueId': valueId,
                        'checked': element.checked
                    };
                }
            } else if (typeof element.checked != 'undefined' && !element.checked && typeof properties[propId] != 'undefined') {
                if (typeof properties[propId][valueId] != 'undefined' && !element.checked) {
                    delete properties[propId][valueId];
                }
            } else if (typeof properties[propId] != 'undefined' && !element.selected && typeof element.selected != 'undefined') {
                delete properties[propId][valueId];
            }
        });

        jQuery.ajax({
            type: 'POST',
            url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.get_product_price_with_properties&tmpl=ksenmart',
            data: {
                prop_id: prop_id,
                val_prop_id: val_prop_id,
                price: price,
                id: id,
                count: count,
                product_packaging: product_packaging,
                properties: properties
            },
            success: function(data) {
                data = data.split('^^^');
                form.find('.prices .price .price_num').text(data[0]);
                form.find('input[name="price"]').val(data[1]);
            }
        });
    });

    jQuery('.unit .prop .color').click(function() {
        var form = jQuery(this).parents('form');
        var prop_value_id = jQuery(this).parents('.item').attr('prop_value_id');
        jQuery(this).parents('.prop').find('.active').removeClass('active');
        jQuery(this).parents('.item').addClass('active');
        jQuery(this).parents('.prop').find('input').val(prop_value_id);
        jQuery.ajax({
            url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.get_product_price_with_properties&' + form.serialize() + '&tmpl=ksenmart',
            success: function(data) {
                data = data.split('^^^');
                form.find('.prices .price .price_num').text(data[0]);
                form.find('input[name="price"]').val(data[1]);
            }
        });
    });

    jQuery('.reviews .head a').click(function() {
        if (jQuery('#comment_form').is(':visible'))
            jQuery('#comment_form').slideUp(500);
        else
            jQuery('#comment_form').slideDown(500);
        return false;
    });

    jQuery('.spy_price').click(function() {
        if (user_id == 0) {
            jQuery('.popup').hide();
            jQuery(".form-login").fadeIn(400);
        } else {
            var prd_id = jQuery(this).data().prd_id;
            jQuery.ajax({
                url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.add_watched&id=' + prd_id,
                success: function(data) {
                    KMShowMessage('Теперь вы следите за этим товаром.');
                }
            });
        }
        return false;
    });

    jQuery('a.to-fav').click(function() {
        if (user_id == 0) {
            jQuery('.popup').hide();
            jQuery(".form-login").fadeIn(400);
        } else {
            var prd_id = jQuery(this).attr('prd_id');
            jQuery.ajax({
                url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.add_favorites&id=' + prd_id + '&tmpl=ksenmart',
                success: function(data) {
                    KMShowMessage('Товар добавлен вам в избранные');
                }
            });
        }
        return false;
    });

    jQuery('.unit .options .row').click(function() {
        jQuery(this).removeClass('row_active');
    });

    jQuery('.quant .minus').click(function() {
        var input = jQuery(this).parents('form').find('input[name="count"]');
        var count = parseFloat(input.val());
        var product_packaging = parseFloat(jQuery(this).parents('form').find('input[name="product_packaging"]').val());
        if (count > product_packaging) {
            count -= product_packaging;
            count = Math.ceil(count / product_packaging) * product_packaging;
            count = count.toFixed(4);
            count = fixCount(count);
            input.val(count);
        }

        return false;
    });

    jQuery('.quant .plus').click(function() {
        var input = jQuery(this).parents('form').find('input[name="count"]');
        var count = parseFloat(input.val());
        var product_packaging = parseFloat(jQuery(this).parents('form').find('input[name="product_packaging"]').val());
        count += product_packaging;
        count = Math.ceil(count / product_packaging) * product_packaging;
        count = count.toFixed(4);
        count = fixCount(count);
        input.val(count);

        return false;
    });
});