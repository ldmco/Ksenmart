jQuery(document).ready(function(){

	jQuery('body').on('click','.prod-parent-closed',function(){
		var item=jQuery(this).parents('.list_item');
		var data={};
		data['id']=item.find('.id').val();
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
	
	jQuery('body').on('click','.prod-set-closed',function(){
		var item=jQuery(this).parents('.list_item');
		var data={};
		data['id']=item.find('.id').val();
		data['task']='catalog.get_set_childs';
		jQuery(this).removeClass('prod-set-closed');
		jQuery(this).addClass('prod-set-opened');
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
	
	jQuery('body').on('click','.prod-set-opened',function(){
		var id=jQuery(this).parents('.list_item').find('.id').val();
		jQuery(this).removeClass('prod-set-opened');
		jQuery(this).addClass('prod-set-closed');
		jQuery('.child_item_'+id).remove();
		return false;
	});	

	jQuery('body').on('mouseover','.cat .img .min_img',function(){
		if (jQuery(this).parents('.list_item').is(':not(.ui-sortable-helper)'))
			jQuery(this).parents('.img').find('.medium_img').show();
	});
	
	jQuery('body').on('mouseout','.cat .img .min_img',function(){
		if (jQuery(this).parents('.list_item').is(':not(.ui-sortable-helper)'))
			jQuery(this).parents('.img').find('.medium_img').hide();
	});	
	
	jQuery('body').on('click','.show_product_photo',function(){
		SqueezeBox.setContent( 'image', jQuery(this).attr('href') );
		return false;
	});	
	
	jQuery('body').on('click','.list_item .add_to_set',function(){
		var item_id=jQuery(this).parents('.list_item').find('.id').val();	
		if (jQuery('.drop div').length==0)
			jQuery('.drop').html('');
		if (jQuery('.drop div[rel="'+item_id+'"]').length==0)	
		{
			var html='';
			html+='<div rel="'+item_id+'">';
			html+=	'<a class="del"></a>';	
			html+=	jQuery(this).parents('.list_item').find('.min_img').parent().html();	
			html+='	<input type="hidden" name="ids[]" value="'+item_id+'">';
			html+='</div>';
			jQuery('.drop').append(html);
		}	
		return false;
	});
	
	jQuery('body').on('click','.drop .del',function(){
		jQuery(this).parent().remove();
		if (jQuery('.drop div').length==0)
			jQuery('.drop').html(Joomla.JText._('ksm_catalog_add_set_string'));		
		return false;
	});	
	
	jQuery('#cat .top .drag .ok').click(function(){
		var data=jQuery('#add-set-form').serialize();
		var width=Math.round(jQuery(window).width()*9/10);
		var height=Math.round(jQuery(window).height()*9/10);		
		openPopupWindow('index.php?option=com_ksenmart&view=catalog&layout=set&'+data+'&tmpl=component',width,height);
	});	
	
	jQuery('table.cat').on('mousemove', function(event){
		if (jQuery('.cat tbody .ui-sortable-helper').length>0)
		{
			if (event.pageY<jQuery('.cat thead').offset().top+42)
			{
				if(jQuery('.sortable-helper').length == 0){
					jQuery('#content .cat').before('<table class="sortable-helper" cellspacing="0"></table>');
				}
				
				jQuery('.sortable-helper').html(jQuery('.cat tbody .ui-sortable-helper'));
				jQuery('.sortable-helper .ui-sortable-helper').html(jQuery('.sortable-helper .ui-sortable-helper').find('.min_img'));
				jQuery('.sortable-helper .ui-sortable-helper').css({'width':'40px','margin-left':event.pageX-jQuery('.sortable-helper .ui-sortable-helper').offset().left-20,'margin-top':event.pageY-jQuery('.sortable-helper .ui-sortable-helper').offset().top-20});
			}
		}	
	});
});