jQuery(document).ready(function(){

	jQuery('.edit').height(jQuery(window).height()-40);

	jQuery('form').on('submit', function(){
		var form = jQuery(this);
		form.submit();
		return false;
	});

});