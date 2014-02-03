jQuery(document).ready(function(){
	
	jQuery('.edit').height(jQuery(window).height()-40);
	
	jQuery('body').on('click','.add_childs', function(){
		jQuery('.form input[name="close"]').val(0);
		jQuery('.form input[name="jform[is_parent]"]').val(1);
		jQuery('.form .save').click();
		return false;
	});
	
	jQuery('.save').on('click', function(){
		var form=jQuery(this).parents('.form');
		var title=form.find('input[name="jform[title]"]').val();
		if (title=='')
		{
			KMShowMessage(Joomla.JText._('KSM_CATALOG_PRODUCT_INVALID_TITLE_LBL'));
			return false;
		}
		form.submit();
		return false;
	});

});