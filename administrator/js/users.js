jQuery(document).ready(function(){

	jQuery('body').on('click','.list_item .user_subsriber input[type="checkbox"]',function(){
		var data={};
		data['task']='users.set_user_subsriber';
		data['user_id']=jQuery(this).parents('.list_item').find('.id').val();
		if (jQuery(this).is(':checked'))
			data['value']=1;
		else
			data['value']=0;
		jQuery.ajax({
			url:'index.php?option=com_ksenmart',
			data:data,
			dataType:'json',			
			success:function(responce){
			}
		});	
	});
	
});