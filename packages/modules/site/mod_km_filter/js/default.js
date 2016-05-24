var priceTimer = false;
var priceTime = 1000;

jQuery(document).ready(function() {

	if (typeof(view) !== "undefined"){

		jQuery('.ksm-module-filter-block-prices-tracker').slider({
			range: true,
			min: price_min,
			max: price_max,
			step: 100,
			values: [price_less, price_more],
			slide: function(event, ui) {
				jQuery('.ksm-module-filter-block-slider input[name="price_less"]').val(ui.values[0]);
				jQuery('.ksm-module-filter-block-slider input[name="price_more"]').val(ui.values[1]);
			},
			stop: function(event, ui) {
				priceTimer = false;
				priceTimer = setTimeout("KMChangeFilter('prices')", priceTime);
			}
		});

		jQuery('.ksm-module-filter-block-prices input[type="text"]').keydown(function(e) {
			keynum = e.which;
			keynum = parseInt(keynum);
			if (keynum == 13)
				return false;
			if (keynum >= 33 && keynum <= 40)
				return true;
			if (keynum == 8)
				return true;
			if (keynum == 17)
				return true;
			if (keynum == 45)
				return true;
			if (keynum == 46)
				return true;
			if (keynum >= 96 && keynum <= 105) {
				keynum -= 48;
			}
			keychar = String.fromCharCode(keynum);
			numcheck = /\d/;
			var res = numcheck.test(keychar);
			if (res) {
				priceTimer = false;
				priceTimer = setTimeout("KMChangeFilter('prices')", priceTime);
			}
			return res;
		});
		
	}

});

jQuery(window).load(function() {

	if (typeof(view) !== "undefined"){
		var form = jQuery('.ksm-module-filter form');
		var filter_url = URI_ROOT + 'index.php?option=com_ksenmart&view=catalog';
		var formdata = form.serialize();
		filter_url += '&' + formdata;
		if (view != 'catalog' && view != 'product') {
			return false;
		}

		KMSetFilter(filter_url, false);

		return true;
	}
});

function KMChangeFilter(obj) {
    var form = jQuery('.ksm-module-filter form');
    var item = jQuery(obj).is('.ksm-module-filter-block-listing-item') ? jQuery(obj) : jQuery(obj).parents('.ksm-module-filter-block-listing-item');
	var filter_button = jQuery(obj).is('.ksm-module-filter-button-filter');
    var filter_url = URI_ROOT + 'index.php?option=com_ksenmart&view=catalog';
    var page_url = 'index.php?option=com_ksenmart&view=catalog';
	
	if (item.find('input').is('[type="radio"]')) {
		if (item.is('.active')) {
			item.removeClass('active');
			item.find('input').removeAttr('checked');
		} else {
			item.parents('.ksm-module-filter-block').find('.active').removeClass('active');
			item.addClass('active');
		}	
	} else {
		if (item.is('.active')) {
			item.removeClass('active');
		} else {
			item.addClass('active');
		}
	}	

    var price_less = parseInt(jQuery('.ksm-module-filter input[name="price_less"]').val());
    var price_more = parseInt(jQuery('.ksm-module-filter input[name="price_more"]').val());
    if (isNaN(price_less))
        price_less = 0;
    if (isNaN(price_more))
        price_more = 0;
    if (price_less < price_min) {
        price_less = price_min;
        jQuery('.ksm-module-filter input[name="price_less"]').val(price_less);
    }
    if (price_less > price_max) {
        price_less = price_max;
        jQuery('.ksm-module-filter input[name="price_less"]').val(price_less);
    }
    if (price_more < price_min) {
        price_more = price_min;
        jQuery('.ksm-module-filter input[name="price_more"]').val(price_more);
    }
    if (price_more > price_max) {
        price_more = price_max;
        jQuery('.ksm-module-filter input[name="price_more"]').val(price_more);
    }
    if (price_less > price_more) {
        var tmp = price_less;
        price_less = price_more;
        price_more = tmp;
        jQuery('.ksm-module-filter input[name="price_less"]').val(price_less);
        jQuery('.ksm-module-filter input[name="price_more"]').val(price_more);
    }
    if (price_min != price_max) {
        var slider_price_less = jQuery('.ksm-module-filter-block-prices-tracker').slider('values', 0);
        var slider_price_more = jQuery('.ksm-module-filter-block-prices-tracker').slider('values', 1);
        if (price_less != slider_price_less)
            jQuery('.ksm-module-filter-block-prices-tracker').slider('values', 0, price_less);

        if (price_more != slider_price_more)
            jQuery('.ksm-module-filter-block-prices-tracker').slider('values', 1, price_more);
    }

    var formdata = form.serialize();
    filter_url += '&' + formdata;
	filter_url += '&Itemid=' + shopItemid;
    page_url += '&' + formdata;
    page_url += '&Itemid=' + shopItemid;

    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.get_route_link&tmpl=ksenmart',
        async: false,
        data: {
            url: page_url
        },
        success: function(data) {
            page_url = data;
        }
    });
    if (view != 'catalog') {
        window.location.href = page_url;
        return false;
    }

	if (show_filter_button == 0 || filter_button){
		history.pushState(null, null, page_url);
		KMSetFilter(filter_url, true);
	} else {
		KMSetFilter(filter_url, false);
	}

    return true;
}

function KMSetFilter(filter_url, replace_content) {
    var form = jQuery('.ksm-module-filter form');

    jQuery.ajax({
        url: filter_url + '&task=catalog.filter_products',
        dataType: 'json',
        success: function(data) {
            if (replace_content)
                jQuery('.ksm-catalog').replaceWith(data.html);

            var properties = data.properties;
            var manufacturers = data.manufacturers;
            var countries = data.countries;
            var props = [];


			form.find('.ksm-module-filter-block-manufacturers .ksm-module-filter-block-listing-item').hide();
			for (var k = 0; k < manufacturers.length; k++)
			{
				form.find('.ksm-module-filter-block-manufacturers .ksm-module-filter-block-listing-item[data-id="' + manufacturers[k] + '"]').show();				
			}				
			if (manufacturers.length > 0)
			{
				form.find('.ksm-module-filter-block-manufacturers').show();
			}
			else
			{
				form.find('.ksm-module-filter-block-manufacturers').hide();
			}

			form.find('.ksm-module-filter-block-countries .ksm-module-filter-block-listing-item').hide();
			for (var k = 0; k < countries.length; k++)
			{
				form.find('.ksm-module-filter-block-countries .ksm-module-filter-block-listing-item[data-id="' + countries[k] + '"]').show();				
			}				
			if (countries.length > 0)
			{
				form.find('.ksm-module-filter-block-countries').show();
			}
			else
			{
				form.find('.ksm-module-filter-block-countries').hide();
			}

            form.find('.ksm-module-filter-block-property').each(function(){
				jQuery(this).find('.ksm-module-filter-block-listing-item').hide();
				jQuery(this).hide();
            });
            for (var key in properties) 
			{
                props = properties[key];
                for(var k = 0; k < props.length; k++) 
				{
					form.find('.ksm-module-filter-block-property-'+key+' .ksm-module-filter-block-listing-item[data-id="' + props[k] + '"]').show();
                }
				if (props.length > 0)
				{
					form.find('.ksm-module-filter-block-property-'+key).show();
				}
            }
        }
    });
}

function KMClearFilter(){
	var form = jQuery('.ksm-module-filter form');
	
	form.find('input[name="price_less"]').val(price_min);
	form.find('input[name="price_more"]').val(price_max);	
	form.find('.ksm-module-filter-block-manufacturers, .ksm-module-filter-block-properties, .ksm-module-filter-block-countries').find('.active').removeClass('active');
	form.find('.ksm-module-filter-block-manufacturers, .ksm-module-filter-block-properties, .ksm-module-filter-block-countries').find('input[type="checkbox"], input[type="radio"]').removeAttr('checked');
	form.find('.ksm-module-filter-block-manufacturers, .ksm-module-filter-block-properties, .ksm-module-filter-block-countries').find('select').val('');
	
	KMChangeFilter('clear');
}