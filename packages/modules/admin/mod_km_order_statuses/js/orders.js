var OrderStatusesModule = '';

jQuery(document).ready(function(){

	OrderStatusesModule=new KMListModule({
		'module':'mod_km_order_statuses',
		'list':OrdersList,
		'view':'orders',
		'table':'orderstatuses',
		'sortable':false
	});

});