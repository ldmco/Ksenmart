var ManufacturersModule='';

jQuery(document).ready(function(){

	ManufacturersModule=new KMListModule({
		'module':'mod_km_manufacturers',
		'list':ProductsList,
		'view':'catalog',
		'table':'manufacturers',
		'sortable':true
	});

});