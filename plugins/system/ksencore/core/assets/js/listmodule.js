var KMListModule = function(variables){
	
	this.module='';
	this.list='';
	this.view='';
	this.table='';
	this.sortable=false;
	
	this.init = function(){
		var Module=this;	
		for (var i in variables) Module[i] = variables[i];
		Module.setListModuleClose();
		Module.setListModuleSortable();
		Module.setListModuleHover();
		Module.setListModuleDelete();
	}
	
	this.setListModuleClose = function(){
		var Module=this;	
		jQuery('body').on('click','.'+Module.module+' .km-list-left-module-title .sh',function(){
			if (jQuery(this).is('.hides'))
			{
				jQuery(this).removeClass('hides');
				jQuery(this).addClass('show');
				jQuery(this).parents('.km-list-left-module').find('.km-list-left-module-content').slideUp(500);
			}
			else
			{
				jQuery(this).removeClass('show');
				jQuery(this).addClass('hides');
				jQuery(this).parents('.km-list-left-module').find('.km-list-left-module-content').slideDown(500);	
			}	
			return false;
		});	
	}	
	
	this.setListModuleSortable = function(){
		var Module=this;
		if (!Module.sortable) return;
		jQuery('.'+Module.module+' ul').sortable({
			stop:function(){
				var items={},data={},ordering=[],ids=[];
				data['task']='sort_list_items';
				data['table']=Module.table;
				jQuery('.'+Module.module+' li').each(function(k){
					items[jQuery(this).find('input').val()]=k;
				});				
				data['items']=items;
				jQuery.ajax({
					url:'index.php?option='+KS.extension,
					data:data,
					dataType:'json',
					success:function(responce){
						if (responce.errors != 0)
						{
							KMShowMessage(responce.message.join('<br>'));
						}	
					}
				});
			}
		});	
	}
	
	this.setListModuleHover = function(){
		var Module=this;
		jQuery('body').on('mouseover','.'+Module.module+' label',function(){
			jQuery(this).find('p').css('visibility','visible');
		});
		jQuery('body').on('mouseout','.'+Module.module+' label',function(){
			jQuery(this).find('p').css('visibility','hidden');
		});	
	}
	
	this.setListModuleDelete = function(){
		var Module=this;
		jQuery('body').on('click','.'+Module.module+' .delete',function(){
			var item=jQuery(this).parents('li:first');
			if (confirm(Joomla.JText._('KSM_DELETE_CONFIRMATION'))) {
				var url=jQuery(this).attr('href');
				jQuery.ajax({
					url:url,
					dataType:'json',
					success:function(responce){
						if (responce.errors == 0)
						{
							var flag=item.is('.active')?true:false;
							if (jQuery('.'+Module.module+' li').length>1)
								item.remove();
							else
								Module.refresh();
							if (flag)
								Module.list.loadListPage(1);			
						}
						else
						{
							KMShowMessage(responce.message.join('<br>'));
						}
					}
				});			
			}
			return false;		
		});			
	}
	
	this.setItem = function(obj){
		var Module=this;
		var item=jQuery(obj).parents('li:first');
		if (item.is('.active'))
			item.removeClass('active');
		else	
			item.addClass('active');
		Module.list.loadListPage(1);	
	}	
	
	this.refresh = function(){
		var Module=this;
		jQuery.ajax({
			url:'index.php?option='+KS.extension+'&view='+Module.view+'&task=update_module&module='+Module.module,
			dataType:'json',
			async:false,
			success:function(responce){
				if (responce.errors == 0)
				{
					jQuery('.'+Module.module).replaceWith(responce.html);
					Module.setListModuleSortable();
				}
				else
				{
					KMShowMessage(responce.message.join('<br>'));
				}
			}
		});	
	}
	
	this.init();

}