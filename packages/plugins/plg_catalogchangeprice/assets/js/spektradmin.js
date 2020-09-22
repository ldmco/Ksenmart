jQuery(document).ready(function(){
	
	jQuery('body').on('click', '.calculate_price', function(){
		createPopup('Калькулятор цен', 'calculator', false);
		var popup_calc = jQuery('.popup.calculator');
		var html = '';
		html += '<div class="row" style="margin-left: 0;">';
		html += '	<label class="inputname">Изменить цену на:</label>';
		html += '	<select style="width:60px;" name="sign_exchange" id="sign_exchange">';
		html += '		<option value="0">+</option>';
		html += '		<option value="1">-</option>';
		html += '	</select>';
		html += '	<input class="inputbox" name="exchange_price" type="text" id="exchange_price" />';
		html += '	<select style="width:80px;" name="unit_exchange" id="unit_exchange">';
		html += '		<option value="0">%</option>';
		html += '		<option value="1">RUB</option>';
		html += '	</select>';
		html += '</div>';
		html += '<div class="row" style="margin-left: 0;">';
		html += '<a class="buttons send_shange">Изменить цены</a>';
		html += '</div>';
		jQuery('.overlay.calculator').fadeIn(400);
		popup_calc.css({'max-width':'440px', 'margin-left':'-220px', 'top':'80px'});
		popup_calc.fadeIn(400);
		popup_calc.find('.body').html(html);
	});
	
	jQuery('body').on('click', '.send_shange', function(){
		var data = {};
		data['task'] = 'pluginAction';
		data['action'] = 'setPrices';
		data['plugin'] = 'spektrx';
		data['format'] = 'json';
		data['tmpl'] = 'ksenmart';
		data['sign_exchange'] = jQuery('.calculator #sign_exchange').val();
		data['exchange_price'] = jQuery('.calculator #exchange_price').val();
		data['unit_exchange'] = jQuery('.calculator #unit_exchange').val();
		var fdata = jQuery('#list-filters').serialize();
		data['fdata'] = fdata;
		if(data['exchange_price'] == '') return false;
		jQuery.ajax({
		url: '/index.php?option=com_ksenmart&'+fdata,
		data: data,
		type: "post",
		success: function(response) {
			location.reload();
		}
	});
	});
	
});