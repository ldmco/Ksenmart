jQuery(window).load(function(){

	KsenmartMap.options.width  = Math.round(jQuery(window).width()*9/10);
	KsenmartMap.options.height = Math.round(jQuery(window).height()*9/10);
	KsenmartMap.init();
	
	jQuery('body').on('click', '#ksm-map-show', function(){
		jQuery('#ksm-map').show();
	});

});