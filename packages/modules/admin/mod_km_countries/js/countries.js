var CountriesModule='';

jQuery(document).ready(function(){

	CountriesModule=new KMListModule({
		'module':'mod_km_countries',
		'view':'countries',
		'table':'countries',
		'sortable':true
	});

	CountriesModule.list=RegionsList;

});