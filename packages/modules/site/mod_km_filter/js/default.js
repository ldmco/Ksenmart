var priceTimer = false;
var priceTime = 1000;
var priceObj = null;

jQuery(document).ready(function () {

    if (typeof(view) !== "undefined") {

        jQuery('.ksm-module-filter form').each(function () {
            var form = jQuery(this);

            if (!form.find('.ksm-module-filter-block-prices-tracker').length) {
                return;
            }

            var price_min = form.data().price_min;
            var price_max = form.data().price_max;
            var price_less = form.data().price_less;
            var price_more = form.data().price_more;

            form.find('.ksm-module-filter-block-prices-tracker').slider({
                range: true,
                min: price_min,
                max: price_max,
                step: 100,
                values: [price_less, price_more],
                slide: function (event, ui) {
                    var price_less_el = jQuery(ui.handle).closest('.ksm-module-filter-block-prices').find('input[name="price_less"]');
                    var price_more_el = jQuery(ui.handle).closest('.ksm-module-filter-block-prices').find('input[name="price_more"]');
                    price_less_el.val(ui.values[0]);
                    price_more_el.val(ui.values[1]);
                },
                stop: function (event, ui) {
                    priceObj = ui.handle;
                    priceTimer = false;
                    priceTimer = setTimeout("KMChangeFilter(priceObj)", priceTime);
                }
            });

            form.find('.ksm-module-filter-block-prices input[type="text"]').keydown(function (e) {
                priceObj = this;

                keynum = e.which;
                keynum = parseInt(keynum);
                if (keynum == 13) return false;
                if (keynum >= 33 && keynum <= 40) return true;
                if (keynum == 8) return true;
                if (keynum == 17) return true;
                if (keynum == 45) return true;
                if (keynum == 46) return true;
                if (keynum >= 96 && keynum <= 105) {
                    keynum -= 48;
                }
                keychar = String.fromCharCode(keynum);
                numcheck = /\d/;
                var res = numcheck.test(keychar);
                if (res) {
                    priceTimer = false;
                    priceTimer = setTimeout("KMChangeFilter(priceObj)", priceTime);
                }
                return res;
            });
        });
    }

    if (jQuery.fn.chosen != undefined) {
        jQuery('.ksm-module-filter-block select').chosen();
    }

    jQuery('.ksm-module-filter').on('click', '.ksm-module-filter-selected', function (e) {
        e.preventDefault();

        var type = jQuery(this).data().type;
        var id = jQuery(this).data().item_id;
        jQuery('.ksm-module-filter-block-' + type + ' .ksm-module-filter-block-listing-item[data-id="' + id + '"] label').click();
        jQuery('.ksm-module-filter-block-' + type + ' .ksm-module-filter-block-listing-item[data-id="' + id + '"]').closest('select').find('option:first-child').prop('selected', true).end().trigger('liszt:updated');
        //jQuery('.ksm-module-filter-block-'+type+' .ksm-module-filter-block-listing-item[data-id="'+id+'"]').closest('select').trigger("liszt:updated");
        jQuery(this).remove();
        if (!jQuery('.ksm-module-filter-selected').length) {
            jQuery('.ksm-module-filter-selecteds').hide();
        }
        updateSelected();
    });

    jQuery('.ksm-module-filter').on('click', '.ksm-module-filter-button-clear', function (e) {
        e.preventDefault();

        var form = jQuery(this).closest('form');

        var price_min = form.data().price_min;
        var price_max = form.data().price_max;
        form.find('input[name="price_less"]').val(price_min);
        form.find('input[name="price_more"]').val(price_max);
        form.find('.ksm-module-filter-block-manufacturers, .ksm-module-filter-block-properties, .ksm-module-filter-block-countries').find('.active').removeClass('active');
        form.find('.ksm-module-filter-block-manufacturers, .ksm-module-filter-block-properties, .ksm-module-filter-block-countries').find('input[type="checkbox"], input[type="radio"]').removeAttr('checked');
        form.find('.ksm-module-filter-block-manufacturers, .ksm-module-filter-block-properties, .ksm-module-filter-block-countries').find('select').val('');

        KMChangeFilter(this);
    });

});

jQuery(window).load(function () {

    if (typeof(view) !== "undefined") {
        if (view != 'catalog' && view != 'product') {
            return false;
        }

        var formsdata = {};
        formsdata.enabledsProperties = [];
        var counter = {
            'properties': 0, 'manufacturers': 0, 'countries': 0
        };

        jQuery('.ksm-module-filter form').each(function () {
            var form = jQuery(this);
            var formdata = KMSerializeObject(form, counter);
            jQuery(this).find('.ksm-module-filter-block-property').each(function () {
                var property_id = jQuery(this).data().property_id;

                if (typeof(property_id) != 'undefined') {
                    formsdata.enabledsProperties.push(property_id);
                }
            });

            jQuery.extend(true, formsdata, formdata);
        });

        KMSetFilter(formsdata, false);

        return true;
    }
});

function updateSelected() {
    if (!jQuery('.ksm-module-filter-selecteds').length) return;
    var data = {
        option: 'com_ajax', module: 'km_filter', method: 'getSelected', format: 'json', Itemid: Itemid
    };
    var counter = {
        'properties': 0, 'manufacturers': 0, 'countries': 0
    };
    jQuery('.ksm-module-filter form').each(function () {
        var form = jQuery(this);
        var formdata = KMSerializeObject(form, counter);

        jQuery.extend(true, data, formdata);
    });

    jQuery.ajax({
        url: URI_ROOT + 'index.php', data: data, success: function (response) {
            if (response.success) {
                jQuery('.ksm-module-filter-selecteds').replaceWith(response.data.html);
            }
        }
    });
}

function KMChangeFilter(obj) {
    if (jQuery(obj).closest('.disabled').length) return false;
    var item = jQuery(obj).is('.ksm-module-filter-block-listing-item') ? jQuery(obj) : jQuery(obj).parents('.ksm-module-filter-block-listing-item');
    var filter_button = jQuery(obj).is('.ksm-module-filter-button-filter');
    var current_form = jQuery(obj).closest('form');
    var show_filter_button = current_form.data().show_filter_button;

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
    updateSelected();

    if (current_form.find('.ksm-module-filter-block-prices-tracker').length) {
        var price_less_el = current_form.find('.ksm-module-filter-block-slider input[name="price_less"]');
        var price_more_el = current_form.find('.ksm-module-filter-block-slider input[name="price_more"]');
        var price_less = parseInt(price_less_el.val());
        var price_more = parseInt(price_more_el.val());

        current_form.data('price_less', price_less);
        current_form.data('price_more', price_more);

        if (typeof price_less == "undefined" || typeof price_more == "undefined") {
            if (isNaN(price_less)) price_less = 0;
            if (isNaN(price_more)) price_more = 0;
            if (price_less < price_min) {
                price_less = price_min;
                price_less_el.val(price_less);
            }
            if (price_less > price_max) {
                price_less = price_max;
                price_less_el.val(price_less);
            }
            if (price_more < price_min) {
                price_more = price_min;
                price_more_el.val(price_more);
            }
            if (price_more > price_max) {
                price_more = price_max;
                price_more_el.val(price_more);
            }
            if (price_less > price_more) {
                var tmp = price_less;
                price_less = price_more;
                price_more = tmp;
                price_less_el.val(price_less);
                price_more_el.val(price_more);
            }
            if (price_min != price_max) {
                var slider_price_less = current_form.find('.ksm-module-filter-block-prices-tracker').slider('values', 0);
                var slider_price_more = current_form.find('.ksm-module-filter-block-prices-tracker').slider('values', 1);
                if (price_less != slider_price_less) current_form.find('.ksm-module-filter-block-prices-tracker').slider('values', 0, price_less);

                if (price_more != slider_price_more) current_form.find('.ksm-module-filter-block-prices-tracker').slider('values', 1, price_more);
            }
        }
    }

    var formsdata = {};

    var counter = {
        'properties': 0, 'manufacturers': 0, 'countries': 0
    };

    jQuery('.ksm-module-filter form').each(function () {
        var form = jQuery(this);
        var formdata = KMSerializeObject(form, counter);

        jQuery.extend(true, formsdata, formdata);
    });

    var page_url = 'index.php?option=com_ksenmart&view=catalog&' + jQuery.param(formsdata) + '&Itemid=' + shopItemid;

    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.get_route_link&tmpl=ksenmart',
        async: false,
        data: {
            url: page_url
        },
        success: function (data) {
            page_url = data;
        }
    });
    if (view != 'catalog' && (show_filter_button == 0 || filter_button)) {
        window.location.href = page_url;
        return false;
    }

    formsdata.enabledsProperties = [];
    jQuery('.ksm-module-filter form').each(function () {
        jQuery(this).find('.ksm-module-filter-block-property').each(function () {
            var property_id = jQuery(this).data().property_id;

            if (typeof(property_id) != 'undefined') {
                formsdata.enabledsProperties.push(property_id);
            }
        });
    });

    if (show_filter_button == 0 || filter_button || obj == 'clear') {
        history.pushState(null, null, page_url);
        KMSetFilter(formsdata, true);
    } else {
        KMSetFilter(formsdata, false);
    }

    return true;
}

function KMSetFilter(data, replace_content) {
    if (replace_content) {
        jQuery('.ksm-catalog').css('position', 'relative');
        jQuery('.ksm-catalog').append('<div class="ksm-layout-loading"></div>');
    } else {
        data['htmlflag'] = 0;
    }
    jQuery.ajax({
        url: URI_ROOT + 'index.php?option=com_ksenmart&view=catalog&task=catalog.filter_products',
        dataType: 'json',
        data: data,
        success: function (data) {
            jQuery('.ksm-layout-loading').remove();
            if (replace_content) jQuery('.ksm-catalog').replaceWith(jQuery('<div>' + data.html + '</div>').find('.ksm-catalog'));

            var properties = data.properties;
            var manufacturers = data.manufacturers;
            var countries = data.countries;
            var total = data.total;

            jQuery('.ksm-module-filter form').each(function () {
                var form = jQuery(this);
                var props = [];

                form.find('.ksm-module-filter-block-manufacturers .ksm-module-filter-block-listing-item').addClass('disabled');
                form.find('.ksm-module-filter-block-manufacturers .ksm-module-filter-block-listing-item input').prop('disabled', true);
                for (var k = 0; k < manufacturers.length; k++) {
                    form.find('.ksm-module-filter-block-manufacturers .ksm-module-filter-block-listing-item[data-id="' + manufacturers[k] + '"]').removeClass('disabled');
                    form.find('.ksm-module-filter-block-manufacturers .ksm-module-filter-block-listing-item[data-id="' + manufacturers[k] + '"] input').prop('disabled', false);
                }
                if (manufacturers.length > 0) {
                    form.find('.ksm-module-filter-block-manufacturers').removeClass('disabled');
                } else {
                    form.find('.ksm-module-filter-block-manufacturers').addClass('disabled');
                }

                form.find('.ksm-module-filter-block-countries .ksm-module-filter-block-listing-item input').prop('disabled', true);
                for (var k = 0; k < countries.length; k++) {
                    form.find('.ksm-module-filter-block-countries .ksm-module-filter-block-listing-item[data-id="' + countries[k] + '"]').removeClass('disabled');
                    form.find('.ksm-module-filter-block-countries .ksm-module-filter-block-listing-item[data-id="' + countries[k] + '"] input').prop('disabled', false);
                }
                if (countries.length > 0) {
                    form.find('.ksm-module-filter-block-countries').removeClass('disabled');
                } else {
                    form.find('.ksm-module-filter-block-countries').addClass('disabled');
                }

                form.find('.ksm-module-filter-block-property').each(function () {
                    jQuery(this).find('.ksm-module-filter-block-listing-item').addClass('disabled');
                    jQuery(this).find('.ksm-module-filter-block-listing-item input').prop('disabled', true);
                    jQuery(this).find('select').prop('disabled', true);
                    jQuery(this).addClass('disabled');
                });
                jQuery.each(properties, function (key) {
                    props = properties[key];
                    for (var k = 0; k < props.length; k++) {
                        form.find('.ksm-module-filter-block-property-' + key + ' .ksm-module-filter-block-listing-item[data-id="' + props[k] + '"]').removeClass('disabled');
                        form.find('.ksm-module-filter-block-property-' + key + ' .ksm-module-filter-block-listing-item[data-id="' + props[k] + '"] input').prop('disabled', false);
                    }
                    if (props.length > 0) {
                        form.find('.ksm-module-filter-block-property-' + key).removeClass('disabled');
                        form.find('.ksm-module-filter-block-property-' + key + ' select').prop('disabled', false);
                    }
                });

                KMRefreshFilter(form);
            });

        }
    });
}

function KMClearFilter(obj) {
    var form = jQuery(obj).closest('form');

    var price_min = form.data().price_min;
    var price_max = form.data().price_max;
    form.find('input[name="price_less"]').val(price_min);
    form.find('input[name="price_more"]').val(price_max);
    form.find('.ksm-module-filter-block-manufacturers, .ksm-module-filter-block-properties, .ksm-module-filter-block-countries').find('.active').removeClass('active');
    form.find('.ksm-module-filter-block-manufacturers, .ksm-module-filter-block-properties, .ksm-module-filter-block-countries').find('input[type="checkbox"], input[type="radio"]').removeAttr('checked');
    form.find('.ksm-module-filter-block-manufacturers, .ksm-module-filter-block-properties, .ksm-module-filter-block-countries').find('select').val('');

    KMChangeFilter(obj);
}

function KMRefreshFilter(form) {
    form.find('input:checkbox').trigger('refresh');

    if (form.find('.ksm-module-filter-block-prices-tracker').length) {
        var price_min = form.data().price_min;
        var price_max = form.data().price_max;
        var price_less = form.data().price_less;
        var price_more = form.data().price_more;

        form.find('.ksm-module-filter-block-prices-tracker').slider({
            range: true,
            min: price_min,
            max: price_max,
            step: 100,
            values: [price_less, price_more],
            slide: function (event, ui) {
                var price_less_el = jQuery(ui.handle).closest('.ksm-module-filter-block-prices').find('input[name="price_less"]');
                var price_more_el = jQuery(ui.handle).closest('.ksm-module-filter-block-prices').find('input[name="price_more"]');
                price_less_el.val(ui.values[0]);
                price_more_el.val(ui.values[1]);
            },
            stop: function (event, ui) {
                priceObj = ui.handle;
                priceTimer = false;
                priceTimer = setTimeout("KMChangeFilter(priceObj)", priceTime);
            }
        });
    }

    if (jQuery.fn.chosen != undefined) {
        //jQuery('.ksm-module-filter-block select').chosen();
        jQuery('.ksm-module-filter-block select').trigger("liszt:updated");
    }
}

function KMSerializeObject(form, counter) {
    var o = {};
    var a = form.serializeArray();
    var ranges = {};
    jQuery.each(a, function () {
        if (!this.value || this.value == '') {
            return;
        }
        if (this.name.indexOf('range_properties') + 1) {
            var default_value = form.find('[name="' + this.name + '"]').data().default;
            var property_id = parseInt(this.name);
            if (default_value == this.value) {
                if (this.name.indexOf('min') + 1) {
                    ranges[property_id] = this;
                }
                return;
            }
            if (this.name.indexOf('max') + 1 && ranges[property_id] !== undefined) {
                o[ranges[property_id].name] = ranges[property_id].value;
            }
        }
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            if (!KMInArray(this.value, o[this.name])) {
                if (this.name.indexOf('properties') + 1) {
                    o[this.name][counter['properties']] = this.value;
                    counter['properties']++;
                } else if (this.name.indexOf('manufacturers') + 1) {
                    o[this.name][counter['manufacturers']] = this.value;
                    counter['manufacturers']++;
                } else {
                    o[this.name].push(this.value);
                }
            }
        } else {
            if (this.name.indexOf('properties') + 1) {
                o[this.name] = [];
                o[this.name][counter['properties']] = this.value;
                counter['properties']++;
            } else if (this.name.indexOf('manufacturers') + 1) {
                o[this.name] = [];
                o[this.name][counter['manufacturers']] = this.value;
                counter['manufacturers']++;
            } else {
                o[this.name] = this.value;
            }
        }
    });
    return o;
}

function KMInArray(needle, haystack) {
    var length = haystack.length;
    for (var i = 0; i < length; i++) {
        if (haystack[i] == needle) return true;
    }
    return false;
}