
jQuery(document).ready(function() {
    var properties = {};
    var product = jQuery('.ksm-product');
	jQuery('.ksm-product-gallery-thumb-link').on('click', function(e){
		e.preventDefault();
		
		var img_id = jQuery(this).data().img_id;
		
		jQuery('.ksm-product-gallery-thumb').removeClass('active');
		jQuery(this).parents('.ksm-product-gallery-thumb').addClass('active');
		jQuery('.ksm-product-gallery-big').removeClass('active');
		jQuery('.ksm-product-gallery-big[data-img_id="'+img_id+'"]').addClass('active');
	});

    jQuery('.ksm-product #ksm-product-parent-childs-property').change(function() {
        var url = jQuery(this).val();
        if (url != '')
            window.location.href = url;
    });

    product.on('change','[name*="property_"]', function() {
        var form = jQuery(this).parents('form');
        var val_prop_id = jQuery(this).val();
        var price = form.find('[name="price"]').val();
        var id = form.find('[name="id"]').val();
        var count = form.find('[name="count"]').val();
        var product_packaging = form.find('[name="product_packaging"]').val();
        var propertiesE = form.find('.ksm-product-properties select option, .ksm-product-properties input[type="radio"], .ksm-product-properties input[type="checkbox"]');
		
		if (jQuery(this).is('[type="checkbox"]')){
			jQuery(this).parents('.ksm-product-property').find('[type="checkbox"]').each(function(){
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

        var data = {};
		data['layouts'] = {
			'0': 'product_prices'
		};
		data['view'] = 'product';
		data['format'] = 'raw';
		data['id'] = id;
		data['properties'] = properties;

		KMGetLayouts(data);
    });

    jQuery('.ksm-product-spy-price').on('click', function(e) {
		e.preventDefault();
		
        if (user_id != 0) 
		{
            var prd_id = jQuery(this).data().prd_id;
            jQuery.ajax({
                url: URI_ROOT + 'index.php?option=com_ksenmart&task=product.add_watched&id=' + prd_id,
                success: function() {
                    KMShowMessage(Joomla.JText._('KSM_PRODUCT_WATCH_MESSAGE'));
                }
            });
        } 
		else 
		{
			KMShowMessage(Joomla.JText._('KSM_NEED_AUTH_MESSAGE'));
        }
    });

    product.on('click', '.ksm-product-to-fav', function(e) {
		e.preventDefault();
		
        if (user_id != 0) 
		{
            var prd_id = jQuery(this).data().prd_id;
            jQuery.ajax({
                url: URI_ROOT + 'index.php?option=com_ksenmart&task=product.add_favorites&id=' + prd_id,
                success: function() {
                    KMShowMessage(Joomla.JText._('KSM_PRODUCT_FAVORITE_MESSAGE'));
                }
            });
        } 
		else 
		{
			KMShowMessage(Joomla.JText._('KSM_NEED_AUTH_MESSAGE'));
        }
    });

    product.on('click', '.ksm-product-quant-minus', function() {
        var input = jQuery('.ksm-product-quant-input');
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

    product.on('click', '.ksm-product-quant-plus', function() {
        var input = jQuery('.ksm-product-quant-input');
        var count = parseFloat(input.val());
        var product_packaging = parseFloat(jQuery(this).parents('form').find('input[name="product_packaging"]').val());
        count += product_packaging;
        count = Math.ceil(count / product_packaging) * product_packaging;
        count = count.toFixed(4);
        count = fixCount(count);
        input.val(count);

        return false;
    });

    product.on('click', '.ksm-product-tab-nav', function(e){
		console.log('Test');
		e.preventDefault();
		
		var tab_id = jQuery(this).find('a').attr('href');
		
		jQuery('.ksm-product-tab-nav').removeClass('active');
		jQuery(this).addClass('active');
		
		jQuery('.ksm-product-tabs-content').removeClass('active');
		jQuery('.ksm-product-tabs-content'+tab_id).addClass('active');
	});
	
});