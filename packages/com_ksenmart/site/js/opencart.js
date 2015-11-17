var maskList, maskOpts;
jQuery(document).ready(function() {

	jQuery('#order_info_show').on('click', function() {
		jQuery('.order_info_block').removeClass('hide');
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

	var intervalId = null;
	jQuery('#cart').on('click, mousedown', '.quant .minus', function() {
		var input = jQuery(this).parents('.quant').find('[type="text"]');
		intervalId = setInterval(function() {
			delayUpdateClear(input.data('item_id'));
			quantityUpdate(input, 'down');
		}, 75);
	}).on('mouseup mouseleave', '.quant .minus', function(e) {
		var input = jQuery(this).parents('.quant').find('[type="text"]');
		clearInterval(intervalId);
	});

	jQuery('#cart').on('click, mousedown', '.quant .plus', function() {
		var input = jQuery(this).parents('.quant').find('[type="text"]');
		intervalId = setInterval(function() {
			delayUpdateClear(input.data('item_id'));
			quantityUpdate(input, 'up');
		}, 75);
	}).on('mouseup mouseleave', '.quant .plus', function(e) {
		var input = jQuery(this).parents('.quant').find('[type="text"]');
		clearInterval(intervalId);
	});

	jQuery('#cart').on('click', '.quantt input', function(e) {
		delayUpdateClear(jQuery(this).data('item_id'));
	});

	jQuery('#cart').on('keydown', '.quantt input', function(event) {
		if (event.keyCode == 13) {
			event.preventDefault();

			var input = jQuery(this);

			var count = input.val().replace(',', '.');
			count = parseFloat(count);
			var old_count = parseFloat(input.attr('count'));

			var product_packaging = parseFloat(input.attr('product_packaging'));

			count = Math.ceil(count / product_packaging) * product_packaging;
			count = count.toFixed(4);
			count = fixCount(count);
			input.val(count);

			if (old_count != count) {
				staticUpdatePrices();

				delayUpdate(function() {
					update_count(input);
				}, input.data('item_id'));
			}

			return false;
		}
	});

	jQuery('#cart').on('change mouseleave', '.quantt input', function(e) {
		var input = jQuery(this);

		var count = input.val().replace(',', '.');
		count = parseFloat(count);
		var old_count = parseFloat(input.attr('count'));

		var product_packaging = parseFloat(input.attr('product_packaging'));

		count = Math.ceil(count / product_packaging) * product_packaging;
		count = count.toFixed(4);
		count = fixCount(count);
		input.val(count);

		if (old_count != count) {
			staticUpdatePrices();

			delayUpdate(function() {
				update_count(input);
			}, input.data('item_id'));
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

function quantityUpdate(input, direction) {

	var count = parseFloat(input.val());
	var product_packaging = parseFloat(input.attr('product_packaging'));
	if (direction == 'up') {
		count += product_packaging;
		count = Math.ceil(count / product_packaging) * product_packaging;
	} else {
		if (count <= product_packaging) {
			return false;
		}
		count -= product_packaging;
	}

	count = count.toFixed(4);
	count = fixCount(count);
	input.val(count);
	staticUpdatePrices();

	delayUpdate(function() {
		update_count(input);
	}, input.data('item_id'));
}

var delayUpdateToken = [];

function delayUpdateClear(id) {
	clearTimeout(delayUpdateToken[id]);
	// delayUpdateToken.splice(id, 1);
}

function delayUpdate(trigger, id) {
	delayUpdateClear(id);
	delayUpdateToken[id] = setTimeout(trigger, 2000);
}

function staticUpdatePrices() {
	var cart = jQuery('#cart');
	var cartTotal = 0;
	cart.find('.item-cart').each(function(index, el) {
		var count = jQuery(el).find('.quantt input').val();
		var price = jQuery(el).find('.pricee .price_num').text().replace(' ', '');
		var itemTotal = Math.round(price * count, 0);
		jQuery(el).find('.totall .price_num').text(itemTotal.formatMoney(0, '.', ' '));
		cartTotal += itemTotal;
	});
	cart.find('.total_cost_items .price_num').text(cartTotal.formatMoney(0, '.', ' '));
}

function update_count($this) {
	var input = $this;
	var count = parseFloat(input.val());
	var item_id = input.data('item_id');
	var old_count = parseFloat(input.attr('count'));
	var product_id = input.attr('product_id');
	var flag = true;
	var product_packaging = parseFloat(input.attr('product_packaging'));

	count = Math.round(count / product_packaging, 3) * product_packaging;
	count = count.toFixed(4);
	count = fixCount(count);

	if (count < 0) {
		count = count * (-1);
	}
	input.val(count);
	jQuery.ajax({
		url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.validate_in_stock&id=' + product_id + '&count=' + (count - old_count) + '&tmpl=ksenmart',
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
			dataType: 'JSON',
			success: function(response) {
				if (response.success) {
					var item = input.parents('.item-cart');

					item.find('.pricee .price_num').text(response.data.orderItem.price);
					input.attr('price', response.data.orderItem.price);
					input.attr('count', response.data.orderItem.count);

					// KMShowMessage('<h2>Заказ обновлен.</h2>');
					if (count == 0) {
						item.remove();
						if (jQuery('#cart .item .del').length == 0) {
							jQuery('#cart').html('<h1 class="clear_cart">Ваш заказ пуст</h1>');
							jQuery('#order').html('');
						}
					} else {
						input.attr('count', count);
					}
					jQuery('#minicart').html(response.data.minicart);
				};
			}
		});
	}
	update_prices();
}

Number.prototype.formatMoney = function(c, d, t) {
	var n = this,
		c = isNaN(c = Math.abs(c)) ? 2 : c,
		d = d == undefined ? "." : d,
		t = t == undefined ? "," : t,
		s = n < 0 ? "-" : "",
		i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
		j = (j = i.length) > 3 ? j % 3 : 0;
	return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

function update_prices() {
	var prd_price = 0;
	var item_price = 0;
	var item_count = 0;
	var deliverycost = jQuery('#deliverycost').val();

	var data = {};
	data['layouts'] = {
		'0': 'default_on_display_after_content',
		// '1': 'default_items',
		'2': 'default_total_cost_items',
		'3': 'default_total',
	};

	KMGetLayouts(data);
}

function setOrderUserField(name, field_value, callback) {
	jQuery.ajax({
		url: URI_ROOT + 'index.php?option=com_ksenmart&task=cart.updateOrderUserField&tmpl=ksenmart',
		type: 'POST',
		data: {
			name: name,
			field_value: field_value
		},
		success: callback,
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

// Замыкание
(function() {
	/**
	 * Корректировка округления десятичных дробей.
	 *
	 * @param {String}  type  Тип корректировки.
	 * @param {Number}  value Число.
	 * @param {Integer} exp   Показатель степени (десятичный логарифм основания корректировки).
	 * @returns {Number} Скорректированное значение.
	 */
	function decimalAdjust(type, value, exp) {
		// Если степень не определена, либо равна нулю...
		if (typeof exp === 'undefined' || +exp === 0) {
			return Math[type](value);
		}
		value = +value;
		exp = +exp;
		// Если значение не является числом, либо степень не является целым числом...
		if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
			return NaN;
		}
		// Сдвиг разрядов
		value = value.toString().split('e');
		value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
		// Обратный сдвиг
		value = value.toString().split('e');
		return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
	}

	// Десятичное округление к ближайшему
	if (!Math.round10) {
		Math.round10 = function(value, exp) {
			return decimalAdjust('round', value, exp);
		};
	}
	// Десятичное округление вниз
	if (!Math.floor10) {
		Math.floor10 = function(value, exp) {
			return decimalAdjust('floor', value, exp);
		};
	}
	// Десятичное округление вверх
	if (!Math.ceil10) {
		Math.ceil10 = function(value, exp) {
			return decimalAdjust('ceil', value, exp);
		};
	}
})();