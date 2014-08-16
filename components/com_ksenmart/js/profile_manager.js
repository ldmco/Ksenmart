jQuery(document).ready(function(){
    
    magicalText('.edit_order_status');
    
	jQuery('.order_tr').click(function(){
		var data_count = jQuery(this).data().count;
		var profile_order = jQuery(this).parents('tbody').find('.order_dropdows_'+data_count);

		if(profile_order.hasClass('active')){
			profile_order.hide();
            profile_order.toggleClass('active');
		}else{
            jQuery(this).parents('tbody').find('.profile_order').removeClass('active').hide();
			profile_order.show();
            profile_order.toggleClass('active');
		}
		return true;
	});
    
    var c_order_status = null;
    var form           = null;
    
    jQuery('.edit_order_status').on('click', function(e){
        e.stopPropagation();
        
        c_order_status = jQuery(this).children('.current_order_status');
        form           = jQuery(this).children('form');
        
        c_order_status.addClass('hide');
        form.removeClass('hide');
        form.parent().find('.follow').remove();
    });
    
    jQuery('.edit_order_status-cancel').on('click', function(e){
        e.stopPropagation();
        
        c_order_status.removeClass('hide');
        form.addClass('hide');
        magicalText('.edit_order_status');
    });
    
    jQuery('.edit_order_status-save').on('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        
        var tr         = jQuery(this).parents('tr');
        var select     = form.find('[name="status"]');
        var option_val = select.children('option:selected').text();

        jQuery.ajax({
            type: 'POST',
            url: URI_ROOT+'index.php?option=com_ksenmart&task=management.updateOrderStatus&tmpl=ksenmart',
            data: {
                status_id: select.val(),
                order_id: tr.data().order_id
            },
            success: function(data){
                c_order_status.removeClass('hide').text(option_val);
                form.addClass('hide');
                magicalText('.edit_order_status');
            }
        });
    });
});