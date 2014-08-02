var ShippingMethodsModule='';

jQuery(document).ready(function(){

	ShippingMethodsModule=new KMListModule({
		'module':'mod_km_shipping_methods',
		'view':'shippings',
		'table':'shippings',
		'sortable':false
	});

	ShippingMethodsModule.list=ShippingsList;

});