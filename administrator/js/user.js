jQuery(document).ready(function(){

	jQuery('.edit').height(jQuery(window).height()-40);
	
	jQuery('.save').click(function(){
		var form=jQuery(this).parents('.form');
		var name=form.find('input[name="jform[name]"]').val();
		if (name=='')
		{
			KMShowMessage(Joomla.JText._('KSM_USERS_USER_INVALID_NAME_LBL'));
			return false;
		}
		form.submit();
		return false;
	});

});