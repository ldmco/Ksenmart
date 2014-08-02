jQuery(document).ready(function(){

	if (jQuery('.ksm-manufacturers li.active').length>0)
		jQuery('.ksm-manufacturers ul').show();

	jQuery('.ksm-manufacturers h3').click(function(){
		if (jQuery('.ksm-manufacturers ul').is(':visible'))
			jQuery('.ksm-manufacturers ul').hide();
		else	
			jQuery('.ksm-manufacturers ul').show();
	});

});