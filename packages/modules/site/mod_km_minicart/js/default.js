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

jQuery(document).ready(function() {
	jQuery('body').on('click', '.ksm-module-minicart-icon', function (e) {
		e.preventDefault();

        jQuery('.ksm-module-minicart-inner').toggle();
	});
});
