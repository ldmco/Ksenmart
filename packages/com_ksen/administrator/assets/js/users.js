jQuery(document).ready(function(){

	jQuery('body').on('click','.prod-parent-closed',function(){
		var item=jQuery(this).parents('.list_item');
		var data={};
		data['id']=item.find('.id').val();
		data['task']='users.get_user_orders';
		jQuery(this).removeClass('prod-parent-closed');
		jQuery(this).addClass('prod-parent-opened');
		jQuery(this).addClass('order-opened');
		jQuery.ajax({
			url:'index.php?option=com_ksen',
			data:data,
			dataType:'json',
			async:false,
			success:function(responce){	
				if (responce.errors == 0)
				{
					item.after(responce.html);
					jQuery('.user_orders_'+data['id']).show();				
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
		jQuery('.user_orders_'+id).remove();
		return false;
	});
	
	jQuery('body').on('click','.show_product_photo',function(){
		SqueezeBox.setContent( 'image', jQuery(this).attr('href') );
		return false;
	});		

	jQuery('body').on('click','.list_item .user_subsriber input[type="checkbox"]',function(){
		var data={};
		data['task']='users.set_user_subsriber';
		data['user_id']=jQuery(this).parents('.list_item').find('.id').val();
		if (jQuery(this).is(':checked'))
			data['value']=1;
		else
			data['value']=0;
		jQuery.ajax({
			url:'index.php?option=com_ksen',
			data:data,
			dataType:'json',			
			success:function(responce){
			}
		});	
	});
	
});