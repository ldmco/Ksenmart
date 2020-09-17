jQuery(document).ready(function() {
	
	jQuery('.ksm-product-gallery-thumb-link').on('click', function(e){
		e.preventDefault();
		
		var img_id = jQuery(this).data().img_id;
		
		jQuery('.ksm-product-gallery-thumb').removeClass('active');
		jQuery(this).parents('.ksm-product-gallery-thumb').addClass('active');
		jQuery('.ksm-product-gallery-big').removeClass('active');
		jQuery('.ksm-product-gallery-big[data-img_id="'+img_id+'"]').addClass('active');
	});	

	jQuery('.ksm-product-tab-nav').on('click', function(e){
		e.preventDefault();
		
		var tab_id = jQuery(this).find('a').attr('href');
		
		jQuery('.ksm-product-tab-nav').removeClass('active');
		jQuery(this).addClass('active');
		
		jQuery('.ksm-product-tabs-content').removeClass('active');
		jQuery('.ksm-product-tabs-content'+tab_id).addClass('active');
	});
	
    jQuery('.ksm-product-to-fav').on('click', function(e) {
		e.preventDefault();
		
        if (user_id != 0) 
		{
            var prd_id = jQuery(this).data().prd_id;
            jQuery.ajax({
                url: URI_ROOT + 'index.php?option=com_ksenmart&task=product.add_favorites&id=' + prd_id,
                success: function(data) {
                    KMShowMessage(Joomla.JText._('KSM_PRODUCT_FAVORITE_MESSAGE'));
                }
            });
        } 
		else 
		{
			KMShowMessage(Joomla.JText._('KSM_NEED_AUTH_MESSAGE'));
        }
    });	

    jQuery('.ksm-set-link-to-buy').on('click', function(e) {
		e.preventDefault();
		
        jQuery('body').animate({'scrollTop': jQuery('.ksm-set-buy').offset().top}, 500);
    });

});