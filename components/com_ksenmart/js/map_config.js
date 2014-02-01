jQuery(window).load(function(){

	KsenmartMap.options.width  = Math.round(jQuery(window).width()*9/10);
	KsenmartMap.options.height = Math.round(jQuery(window).height()*9/10);
	KsenmartMap.init();
	
	jQuery('body').on('click', '#mapselect', function(){
		jQuery('#ksenmart-map').modal('show');
	});
	
	jQuery('body').on('keyup','.address_field',function(){
		if(KsenmartMap.options.changable){
			var address = KsenmartMap.getAddress();
            
			KsenmartMap.removePoint();
			KsenmartMap.setPointAddress(address);
		}
	});
});