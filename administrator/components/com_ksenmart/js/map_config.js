jQuery(window).load(function(){

	jQuery('.form #ksenmart-map-layer').height('300px');	
	jQuery('.form #ksenmart-map-layer').width(jQuery('.form .rightcol').width());	
	KsenmartMap.options.coords_field='jform_shipping_coords';
	KsenmartMap.initMapSize=function(){};
	KsenmartMap.init();

	jQuery('body').on('keyup','.address_field',function(){
		if(KsenmartMap.options.changable){
			var address = KsenmartMap.getAddress();
            
			KsenmartMap.removePoint();
			KsenmartMap.setPointAddress(address);
		}
	});
	
});