var CountriesModule=new KMListModule({
	'module':'mod_km_countries',
	'view':'catalog',
	'table':'countries',
	'sortable':true
});

jQuery(document).ready(function(){

	CountriesModule.list=ProductsList;

});