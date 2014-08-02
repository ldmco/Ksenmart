jQuery(document).ready(function(){

	jQuery('.edit').height(jQuery(window).height()-100);
	jQuery('.edit').width(jQuery(window).width()-40);
	
	if (jQuery('input[name="value"]').val()=='seo-user-value')
		jQuery('.user_value_row').show();
	
	jQuery('input[name="value"]').change(function(){
		if (jQuery(this).val()=='seo-user-value')
			jQuery('.user_value_row').show();
		else	
			jQuery('.user_value_row').hide();
	});
	
	jQuery('form').on('submit', function(){
		var form = jQuery(this);
		var section = form.find('input[name="section"]').val();
		var value = form.find('input[name="value"]').val();
		var user_value = form.find('input[name="user_value"]').val();
		var user_key='';	
		if (value=='seo-user-value')	
		{
			var time=new Date().getTime();
			user_key='user_value_'+time;
		}	
		if (value=='seo-user-value' && user_value=='')
		{
			KMShowMessage(Joomla.JText._('KSM_SEO_SEOURLVALUE_INVALID_TITLE_LBL'));
			return false;
		}
		window.parent.addUrlValue(section,value,user_value,user_key);
		window.parent.closePopupWindow();	
	});
	
});