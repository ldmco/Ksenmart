var ManufacturersModule='';

jQuery(document).ready(function(){

	ManufacturersModule=new KMListModule({
		'module':'mod_km_manufacturers',
		'list':ProductsList,
		'view':'search',
		'table':'manufacturers',
		'sortable':false
	});

});