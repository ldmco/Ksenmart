var PaymentTypesModule='';

jQuery(document).ready(function(){

	PaymentTypesModule=new KMListModule({
		'module':'mod_km_payment_types',
		'view':'payments',
		'table':'payments',
		'sortable':false
	});

	PaymentTypesModule.list=PaymentsList;

});