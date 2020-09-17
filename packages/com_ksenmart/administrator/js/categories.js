jQuery(document).ready(function(){	
	jQuery('body').on('click','.prod-parent-closed',function(){
		var item=jQuery(this).parents('.list_item');
		var data={};
		var id =item.find('.id').val();
		jQuery('.child_item_'+id).show();	
		jQuery(this).removeClass('prod-parent-closed');
		jQuery(this).addClass('prod-parent-opened');
	
		return false;
	});
	
	jQuery('body').on('click','.prod-parent-opened',function(){
		var id=jQuery(this).parents('.list_item').find('.id').val();
		jQuery(this).removeClass('prod-parent-opened');
		jQuery(this).addClass('prod-parent-closed');
		jQuery('.child_item_'+id).hide();
		return false;
	});
});