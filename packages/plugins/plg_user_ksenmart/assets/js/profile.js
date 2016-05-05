jQuery(document).ready(function(){
	
	var ksm_new_address_index = -1;
	
	jQuery('body').on('click', '.ksm-profile-addresses-item-edit', function(e){
		e.preventDefault();
		var item = jQuery(this).parents('.ksm-profile-addresses-item:first');
		
		if (item.find('.ksm-profile-addresses-item-form').is(':hidden'))
		{
			jQuery('.ksm-profile-addresses-item-form').slideUp(200);
			item.find('.ksm-profile-addresses-item-form').slideDown(200);
		}
		else
		{
			item.find('.ksm-profile-addresses-item-form').slideUp(200);
		}
	});
	
	jQuery('body').on('click', '.ksm-profile-addresses-item-del', function(e){
		e.preventDefault();	
		var item = jQuery(this).parents('.ksm-profile-addresses-item:first');
		
		item.remove();
	});	

	jQuery('body').on('click', '.ksm-profile-addresses-item-add button', function(e){
		e.preventDefault();
		var item = jQuery('.ksm-profile-addresses-item-new');
		
		if (item.find('.ksm-profile-addresses-item-form').is(':hidden'))
		{
			jQuery('.ksm-profile-addresses-item-form').slideUp(200);
			item.find('.ksm-profile-addresses-item-form').slideDown(200);
		}
		else
		{
			item.find('.ksm-profile-addresses-item-form').slideUp(200);
		}
	});	
	
	jQuery('body').on('click', '.ksm-profile-addresses-item-new button', function(e){
		e.preventDefault();	
		var item = jQuery('.ksm-profile-addresses-item-new');
		var clone = item.clone();
		var flag = false;
		
		item.find('input[type="text"]').each(function(){
			if (jQuery(this).val() != '')
			{
				flag = true;
				return true;
			}
		});
		if (!flag)
		{
			return false;
		}
		
		item.find('.ksm-profile-addresses-item-form').hide();
		item.find('input[type="text"]').val('');
		item.find('input[type="checkbox"]').prop('checked', false);
		clone.find('.ksm-profile-addresses-item-form').hide();
		clone.removeClass('ksm-profile-addresses-item-new');
		clone.find('.ksm-profile-addresses-item-form-row:last').remove();
		clone.find('input').each(function(){
			var name = jQuery(this).attr('data-name');
			name = name.replace(/index/g, ksm_new_address_index);
			jQuery(this).removeAttr('data-name');
			jQuery(this).attr('name', name);
		});
		KSMProfileSetAddressString(clone);
		clone.insertBefore('.ksm-profile-addresses-item-new');
		ksm_new_address_index--;
	});	
	
	jQuery('body').on('change', '.ksm-profile-addresses-item input[type="text"]', function(e){
		e.preventDefault();
		var item = jQuery(this).parents('.ksm-profile-addresses-item:first');
		
		if (item.is('.ksm-profile-addresses-item-new'))
		{
			return false;
		}
		
		KSMProfileSetAddressString(item);
	});	
	
	jQuery('body').on('click', '.ksm-profile-products-item-button-remove', function(e){
		e.preventDefault();	
		var item = jQuery(this).parents('.ksm-profile-products-item:first');
		var block = item.parents('.ksm-profile-products:first');
		
		item.remove();
		if (block.find('.ksm-profile-products-item').length == 0)
		{
			block.find('.ksm-profile-products-noinfo').show();
		}
	});		
	
	jQuery('body').on('click', '.ksm-profile-reviews-item-del', function(e){
		e.preventDefault();	
		var item = jQuery(this).parents('.ksm-profile-reviews-item:first');
		var block = item.parents('.ksm-profile-reviews-block:first');
		var main_block = item.parents('.ksm-profile-reviews:first');
		
		item.remove();
		if (block.find('.ksm-profile-reviews-item').length == 0)
		{
			block.remove();
		}
		if (main_block.find('.ksm-profile-reviews-item').length == 0)
		{
			main_block.find('.ksm-profile-reviews-noinfo').show();
		}		
	});	
	
	jQuery('body').on('click', '.ksm-profile-orders-item', function(e){
		e.preventDefault();	
		var order_id = jQuery(this).data().order_id;
		var block = jQuery(this).parents('.ksm-profile-orders:first');
		
		if (block.find('.ksm-profile-orders-item-detail[data-order_id="'+order_id+'"]').is(':hidden'))
		{
			block.find('.ksm-profile-orders-item-detail').hide();
			block.find('.ksm-profile-orders-item-detail[data-order_id="'+order_id+'"]').show();
		}
		else
		{
			block.find('.ksm-profile-orders-item-detail[data-order_id="'+order_id+'"]').hide();
		}		
	});
	
	function KSMProfileSetAddressString(item)
	{
		var zip = item.find('input[name*="zip"]').val();
		var city = item.find('input[name*="city"]').val();
		var street = item.find('input[name*="street"]').val();
		var house = item.find('input[name*="house"]').val();
		var entrance = item.find('input[name*="entrance"]').val();
		var floor = item.find('input[name*="floor"]').val();
		var flat = item.find('input[name*="flat"]').val();
		
		var addr_parts = [];
		var address = '';
		
		if (zip != '')
		{
			addr_parts.push(zip);
		}				
		if (city != '')
		{
			city = Joomla.JText._('PLG_USER_KSENMART_ADDRESSES_CITY_TXT').replace(/%s/g, city);
			addr_parts.push(city);
		}
		if (street != '')
		{
			street = Joomla.JText._('PLG_USER_KSENMART_ADDRESSES_STREET_TXT').replace(/%s/g, street);
			addr_parts.push(street);			
		}
		if (house != '')
		{
			house = Joomla.JText._('PLG_USER_KSENMART_ADDRESSES_HOUSE_TXT').replace(/%s/g, house);
			addr_parts.push(house);			
		}
		if (entrance != '')
		{
			entrance = Joomla.JText._('PLG_USER_KSENMART_ADDRESSES_ENTRANCE_TXT').replace(/%s/g, entrance);
			addr_parts.push(entrance);			
		}
		if (floor != '')
		{
			floor = Joomla.JText._('PLG_USER_KSENMART_ADDRESSES_FLOOR_TXT').replace(/%s/g, floor);
			addr_parts.push(floor);			
		}
		if (flat != '')
		{
			flat = Joomla.JText._('PLG_USER_KSENMART_ADDRESSES_FLAT_TXT').replace(/%s/g, flat);
			addr_parts.push(flat);			
		}

		address = addr_parts.join(', ');
		item.find('span:first').html(address);

		return true;		
	}
	
});