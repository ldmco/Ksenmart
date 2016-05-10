function KSMUpdateMinicart()
{
	var data = {
		option: 'com_ajax',
		module: 'km_minicart',
		method: 'updateMinicart',
		format: 'raw',			
		Itemid: Itemid
	};
	
	jQuery.ajax({
		url: URI_ROOT+'index.php',
		data: data,
		success:function(response){
			jQuery('.ksm-module-minicart').replaceWith(response);
		}
	});		
}
