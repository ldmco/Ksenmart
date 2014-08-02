var DiscountTypesModule='';

jQuery(document).ready(function(){

	DiscountTypesModule=new KMListModule({
		'module':'mod_km_discount_types',
		'view':'discounts',
		'table':'discounts',
		'sortable':false
	});

	DiscountTypesModule.list=DiscountsList;

});