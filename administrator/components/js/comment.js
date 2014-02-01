jQuery(document).ready(function(){

	jQuery('.edit').height(jQuery(window).height()-40);

	jQuery('.save').click(function(){
		var form=jQuery(this).parents('.form');
		form.submit();
		return false;
	});

});