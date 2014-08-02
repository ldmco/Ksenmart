var OrderStatusesModule=new KMListModule({
	'module':'mod_km_order_statuses',
	'view':'orders',
	'table':'orderstatuses',
	'sortable':false
});

jQuery(document).ready(function(){

	OrderStatusesModule.list=OrdersList;

});