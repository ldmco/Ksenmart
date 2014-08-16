jQuery(document).ready(function(){

	jQuery('#from_date').datepicker();
	jQuery('#to_date').datepicker();

	jQuery('body').on('click','.prod-parent-closed',function(){
		var item=jQuery(this).parents('.list_item');
		var data={};
		data['id']=item.find('.id').val();
		data['task']='orders.get_order_items';
		jQuery(this).removeClass('prod-parent-closed');
		jQuery(this).addClass('prod-parent-opened');
		jQuery(this).addClass('order-opened');
		jQuery.ajax({
			url:'index.php?option=com_ksenmart',
			data:data,
			dataType:'json',
			async:false,
			success:function(responce){	
				if (responce.errors == 0)
				{
					item.after(responce.html);
					jQuery('.order_items_'+data['id']).show();				
				}
				else
				{
					KMShowMessage(responce.message.join('<br>'));
				}			
			}			
		});		
		return false;
	});
	
	jQuery('body').on('click','.prod-parent-opened',function(){
		var id=jQuery(this).parents('.list_item').find('.id').val();
		jQuery(this).removeClass('prod-parent-opened');
		jQuery(this).removeClass('order-opened');
		jQuery(this).addClass('prod-parent-closed');
		jQuery('.order_items_'+id).remove();
		return false;
	});
	
	jQuery('body').on('click','.show_product_photo',function(){
		SqueezeBox.setContent( 'image', jQuery(this).attr('href') );
		return false;
	});	
	
	jQuery('body').on('click','.top .ok',function(){
		var from_date=jQuery('#from_date').val();
		var to_date=jQuery('#to_date').val();
		jQuery('input[name="from_date"]').val(from_date);
		jQuery('input[name="to_date"]').val(to_date);
		OrdersList.loadListPage(1);
		return false;
	});	
	
});