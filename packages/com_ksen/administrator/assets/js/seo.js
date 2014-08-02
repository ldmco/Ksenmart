jQuery(document).ready(function(){

	jQuery('body').on('click','.seo-block-activable',function(){
		if (jQuery(this).find('.user').val()==1)
		{
			jQuery(this).addClass('inactive');
			updateSeoExample(jQuery(this).parents('.seo-blocks'));
			jQuery(this).remove();
			return true;
		}
		if (jQuery(this).is('.inactive'))
		{
			jQuery(this).removeClass('inactive');
			jQuery(this).find('.block_active').val(1);
		}	
		else	
		{
			jQuery(this).addClass('inactive');
			jQuery(this).find('.block_active').val(0);
		}	
		updateSeoExample(jQuery(this).parents('.seo-blocks'));
	});
	
	jQuery('body').on('click','.seo-blocks a.add',function(){
		var url=jQuery(this).attr('href');
		var width=500;
		var height=180;
		var section=jQuery(this).parents('.cat');
		url+='&section='+section.attr('id');
		section.find('.seo-block.inactive').each(function(){
			url+='&values[]='+jQuery(this).find('.key').val();
		});
		openPopupWindow(url,width,height);	
		return false;
	});
	
});

function updateSeoExample(block)
{
	var path=[];
	block.find('.seo-block').each(function(){
		if (!jQuery(this).is('.inactive'))
			path.push(jQuery(this).attr('alt'));
	});
	block.find('.seo-example span').text(path.join(separator));
}

function addUrlValue(section,value,user_value,user_key)
{
	if (user_key!='')
	{
		var seo_block=jQuery('.seo-block-mask .seo-block').clone()
		seo_block.attr('alt','/'+user_value);
		seo_block.find('span').text(user_value);
		seo_block.find('.block_active').attr('name','config['+section+']['+user_key+'][active]');
		seo_block.find('.activable').attr('name','config['+section+']['+user_key+'][activable]');
		seo_block.find('.sortable').attr('name','config['+section+']['+user_key+'][sortable]');
		seo_block.find('.user').attr('name','config['+section+']['+user_key+'][user]');
		seo_block.find('.title').attr('name','config['+section+']['+user_key+'][title]');
		seo_block.find('.title').val(user_value);
		jQuery('#'+section+' .seo-block-first').after(seo_block);
	}
	else
	{
		jQuery('#'+section+' .key[value="'+value+'"]').parents('.seo-block').removeClass('inactive');
		jQuery('#'+section+' .key[value="'+value+'"]').parents('.seo-block').find('.block_active').val(1);
	}	
	updateSeoExample(jQuery('#'+section+' .seo-blocks'));	
	return true;
}

function addTitleValue(section,value,user_value,user_key)
{
	if (user_key!='')
	{
		var seo_block=jQuery('.seo-block-mask .seo-block').clone()
		seo_block.attr('alt',user_value);
		seo_block.find('span').text(user_value);
		seo_block.find('.block_active').attr('name','config['+section+']['+user_key+'][active]');
		seo_block.find('.activable').attr('name','config['+section+']['+user_key+'][activable]');
		seo_block.find('.sortable').attr('name','config['+section+']['+user_key+'][sortable]');
		seo_block.find('.user').attr('name','config['+section+']['+user_key+'][user]');
		seo_block.find('.title').attr('name','config['+section+']['+user_key+'][title]');
		seo_block.find('.title').val(user_value);
		jQuery('#'+section+' .seo-blocks').prepend(seo_block);
	}
	else
	{
		jQuery('#'+section+' .key[value="'+value+'"]').parents('.seo-block').removeClass('inactive');
		jQuery('#'+section+' .key[value="'+value+'"]').parents('.seo-block').find('.block_active').val(1);
	}	
	updateSeoExample(jQuery('#'+section+' .seo-blocks'));	
	return true;
}