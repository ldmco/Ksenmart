jQuery(document).ready(function(){

	jQuery('.edit').height(jQuery(window).height()-40);
	
	jQuery('.save').click(function(){
		var form=jQuery(this).parents('.form');
		var title=form.find('input[name="jform[title]"]').val();
		if (title=='')
		{
			KMShowMessage(Joomla.JText._('KSM_COUNTRIES_COUNTRY_INVALID_TITLE_LBL'));
			return false;
		}
		form.submit();
		return false;
	});

});
 
