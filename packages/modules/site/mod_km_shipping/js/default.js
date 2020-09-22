jQuery(document).ready(function(){

	jQuery('#ksm-module-shipping-region-select').on('change', function(){
		var region_id=jQuery(this).val();
		var data = {
			option: 'com_ajax',
			module: 'km_shipping',
			method: 'setRegion',
			format: 'raw',			
			Itemid: Itemid,
			region_id: region_id
		};
		
		jQuery.ajax({
			url: URI_ROOT+'index.php',
            data: data,
			success:function(response){
				jQuery('.ksm-module-shipping .ksm-module-shipping-info').html(response);
			}
		});	
	});
	
});