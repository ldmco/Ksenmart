jQuery(document).ready(function(){

	jQuery('#icons').sortable({
		distance: 10,
		stop: function(event, ui){
			var component_sort=[];
			ui.item.parent().find('a').each(function(){
				component_sort.push(jQuery(this).attr('component_id'));
			});
			var url='index.php?option=com_ksenmart&task=panel.sort_components&tmpl=ksenmart';
			$.post(url,{component_sort:component_sort});
		}
	});
	
	jQuery('#icons a').resizable({
		helper: 'ksenmart-panel-list-resizable-helper',
		maxHeight: 209,
		maxWidth: 479,
		minHeight: 209,
		minWidth: 239,
		stop: function(event, ui){
			var width=ui.size.width;
			if (width>375)
			{
				jQuery(this).removeClass('standart');
				jQuery(this).addClass('double');
			}
			else
			{
				jQuery(this).removeClass('double');
				jQuery(this).addClass('standart');
			}			
			jQuery(this).css('width','');	
			var component_size={};
			jQuery(this).parent().find('a').each(function(){
				if (jQuery(this).is('.standart'))
					var size='standart';
				else	
					var size='double';
				component_size[jQuery(this).attr('component_id')]=size;
			});
			var url='index.php?option=com_ksenmart&task=panel.resize_components&tmpl=ksenmart';
			$.post(url,{component_size:component_size});			
		}		
	});	

});