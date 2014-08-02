jQuery(document).ready(function(){
	
	jQuery('.form div.edit').height(jQuery(window).height()-40);
	
	jQuery('body').on('click','.show_product_photo',function(){
		SqueezeBox.setContent( 'image', jQuery(this).attr('href') );
		return false;
	});	
	
	jQuery('body').on('click','.prod-parent-closed',function(){
		var item=jQuery(this).parents('.list_item');
		var data={};
		data['id']=item.find('.id').val();
		data['layout']='search';
		data['task']='catalog.get_childs';
		jQuery(this).removeClass('prod-parent-closed');
		jQuery(this).addClass('prod-parent-opened');
		jQuery.ajax({
			url:'index.php?option=com_ksenmart',
			data:data,
			dataType:'json',
			async:false,
			success:function(responce){	
				if (responce.errors == 0)
				{
					item.after(responce.html);
					jQuery('.child_item_'+data['id']).show();				
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
		jQuery(this).addClass('prod-parent-closed');
		jQuery('.child_item_'+id).remove();
		return false;
	});	
	
	jQuery('body').on('click','.list_item .add',function(){
		var product_id=jQuery(this).parents('.list_item').find('.id').val();	
		if (jQuery('.drop div').length==0)
			jQuery('.drop').html('');
		if (jQuery('.drop div[rel="'+product_id+'"]').length==0)	
		{
			var html='';
			html+='<div rel="'+product_id+'">';
			html+=	'<a class="del"></a>';	
			html+=	jQuery(this).parents('.list_item').find('.min_img').parent().html();	
			html+='	<input type="hidden" name="ids[]" value="'+product_id+'">';
			html+='</div>';
			jQuery('.drop').append(html);
		}	
		return false;
	});
	
	jQuery('.drop').on('click','.del',function(){
		jQuery(this).parent().remove();
		if (jQuery('.drop div').length==0)
			jQuery('.drop').html(Joomla.JText._('ksm_catalog_add_search_string'));		
		return false;
	});	
	
	jQuery('.form .save').on('click',function(){
		
		var data=jQuery('#add-form').serialize();
		var items_to=jQuery('#add-form').find('input[name="items_to"]').val();
		if (jQuery('.drop div').length>0)
		{
			jQuery.ajax({
				url:'index.php?option=com_ksenmart&tmpl=ksenmart',
				data:data,
				dataType:'json',
				async:false,
				success:function(responce){
					parent.jQuery('#'+items_to).find('.no-items').hide();
					parent.jQuery('#'+items_to).append(responce.html);		
					if (typeof parent.afterAddingItems == 'function') {
						parent.afterAddingItems();
					}					
					parent.closePopupWindow();	
				}
			});
		}
		return false;
	});	
	
});