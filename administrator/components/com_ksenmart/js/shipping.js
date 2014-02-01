jQuery(document).ready(function(){

	jQuery('.edit').height(jQuery(window).height()-40);
	
	jQuery('.save').click(function(){
		var form=jQuery(this).parents('.form');
		var title=form.find('input[name="jform[title]"]').val();
		if (title=='')
		{
			KMShowMessage(Joomla.JText._('KSM_SHIPPINGS_SHIPPING_INVALID_TITLE_LBL'));
			return false;
		}
		form.submit();
		return false;
	});
	
});

function setShippingType(obj)
{
	var type=jQuery(obj).val();
	var name=jQuery(obj).parent().text();
	jQuery('#popup-window2 li.active').removeClass('active');
	jQuery(obj).parent().parent().addClass('active');
	jQuery('.popup-window').fadeOut(400);
	jQuery('#add-alg').text(name);
	jQuery(".countries li").each(function(){
		if (!jQuery(this).is(".no-countries"))
			removeCountry(jQuery(this).attr("country_id"));
	});	
	jQuery.ajax({
		url:'index.php?option=com_ksenmart&view=shippings&layout=shipping_params&type='+type+'&tmpl=ksenmart',
		success:function(data){
			jQuery('.params-set').html(data);
			var params = { 
					changedEl: "select.sel", 
				} 
			cuSel(params);				
		}
	});
}