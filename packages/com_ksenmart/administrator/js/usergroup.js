jQuery(document).ready(function(){

	jQuery('.edit').height(jQuery(window).height()-40);

	jQuery('form').on('submit', function(){
		var form = jQuery(this);
		var title=form.find('input[name="jform[title]"]').val();
		if (title=='')
		{
			KMShowMessage(Joomla.JText._('KSM_USERS_USERGROUP_INVALID_TITLE_LBL'));
			return false;
		}
		form.submit();
		return false;
	});
	
});