jQuery(document).ready(function(){
	
    jQuery('body').on('click', '.ksm-catalog-layout a', function(){
        
        var layout_block = jQuery('.ksm-catalog-items');
        var layout = jQuery(this).data().layout;
        var old_layout = layout_block.attr('data-layout');
        
        jQuery('.ksm-catalog-layout').removeClass('active');
        jQuery(this).parents('.ksm-catalog-layout').addClass('active');
        
        layout_block.attr('data-layout', layout);
        layout_block.removeClass('ksm-catalog-items-'+old_layout).addClass('ksm-catalog-items-'+layout);

        jQuery.ajax({
            url: URI_ROOT+'index.php?option=com_ksenmart&task=catalog.setLayoutView',
            data: {layout: layout},
            success: function(response){
            }
        });
    });
	
});