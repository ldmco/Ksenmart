jQuery(document).ready(function(){
    jQuery('body').on('click', '.layout_show', function(){
        
        var layout_block = jQuery('.layout_block');
        var layout      = jQuery(this).data().layout;
        var old_layout  = layout_block.data().layout;
        var li          = layout_block.find('ul').children('li');
        
        jQuery(this).parents('ul').children('li').removeClass('active');
        jQuery(this).parents('li').addClass('active');
        
        layout_block.attr('data-layout', layout);
        layout_block.data().layout = layout;
        layout_block.removeClass('layout_'+old_layout).addClass('layout_'+layout);

        jQuery.ajax({
            type: 'POST',
            url: URI_ROOT+'index.php?option=com_ksenmart&task=catalog.setLayoutView',
            data: {layout: layout},
            success: function(data){
            }
        });
    });
});