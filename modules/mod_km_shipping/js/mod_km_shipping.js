jQuery(document).ready(function(){

	jQuery('#region').change(function(){
		var region_id=jQuery(this).val();
		jQuery.ajax({
			url: URI_ROOT+'index.php?option=com_ksenmart&task=shopprofile.getDataShippingModule&view=shopprofile&tmpl=ksenmart',
            data: {region_id: region_id},
			success:function(data){
				jQuery('.deliv-payment-info').html(data);
			}
		});	
	});
	
});