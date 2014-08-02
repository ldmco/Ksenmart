jQuery(document).ready(function(){
    
    console.log(1);

	var form=jQuery('#window-inner .order_form');
	var region_id=form.find('input[name="region"]').val();
	form.find('.shipping').each(function(){
		var sh_regions=jQuery(this).find('input').attr('regions');
		if (sh_regions.indexOf('|'+region_id+'|')==-1 && region_id!=0)
		{
			jQuery(this).hide();
		}	
	});
	if (form.find('.shipping:visible').length==0)
		jQuery('.no_shippings').removeClass('hide');
	form.find('.payment').each(function(){
		var pm_regions=jQuery(this).find('input').attr('regions');
		if (pm_regions.indexOf('|'+region_id+'|')==-1 && region_id!=0)
		{
			jQuery(this).hide();
		}	
	});
	if (form.find('.payment:visible').length==0)
		jQuery('.no_payments').removeClass('hide');	
	
	jQuery('#window-inner .order_button').on('click',function(){
		var form=jQuery('#window-inner .order_form');
		var name=form.find('input[name="name"]').val();
		var email=form.find('input[name="email"]').val();
		var address=form.find('input[name="address"]').val();
		var phone=form.find('input[name="phone"]').val();
		if (name=='')
		{
			alert('Введите имя');
			return false;
		}
		if (email=='')
		{
			alert('Введите E-mail');
			return false;
		}
		if (!isValidEmail(email))
		{
			alert("Введите корректный E-mail");
			return false;	
		}		

		if (address=='')
		{
			alert('Введите адрес доставки');
			return false;
		}		
		if (form.find('.shipping input:checked').not(':hidden').length==0)
		{
			alert('Выберите способ доставки');
			return false;		
		}
		if (form.find('.payment input:checked').not(':hidden').length==0)
		{
			alert('Выберите способ оплаты');
			return false;		
		}		
		form.submit();
	});
	
	
	jQuery('#order_region').change(function(){
		var form=jQuery('.order_form');
		var region_id=jQuery(this).val();
		form.find('.shipping').show();
		form.find('.payment').show();
		if (region_id==0)
		{
			if (form.find('.shipping:visible').length>0)
				jQuery('.no_shippings').addClass('hide');	
			if (form.find('.payment:visible').length>0)				
				jQuery('.no_payments').removeClass('hide');	
		}	
		else
		{
			form.find('.shipping').each(function(){
				var sh_regions=jQuery(this).find('input').attr('regions');
				if (sh_regions.indexOf('|'+region_id+'|')==-1)
				{
					jQuery(this).hide();
				}	
			});
			if (form.find('.shipping:visible').length==0)
				jQuery('.no_shippings').removeClass('hide');
			form.find('.payment').each(function(){
				var pm_regions=jQuery(this).find('input').attr('regions');
				if (pm_regions.indexOf('|'+region_id+'|')==-1)
				{
					jQuery(this).hide();
				}	
			});
			if (form.find('.payment:visible').length==0)
				jQuery('.no_payments').removeClass('hide');				
		}	
		KsenmartMap.setStatus();
		KsenmartMap.setInfo();	
	});	
	
	jQuery('input[name="shipping_type"]').click(function(){
		KsenmartMap.setStatus();
		KsenmartMap.setInfo();
	});		
	
});