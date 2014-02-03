var priceTimer=false;
var priceTime=1000;
var search_width=210;

jQuery(document).ready(function(){

	jQuery('.search-price').keydown(function(e){
		keynum = e.which;
		keynum = parseInt(keynum);
		if (keynum == 13)
			return false;
		if (keynum >= 33 && keynum <= 40)
			return true;
		if (keynum == 8)
			return true;
		if (keynum == 17)
			return true;
		if (keynum == 45)
			return true;
		if (keynum == 46)
			return true;
		if (keynum >= 96 && keynum <= 105) {
			keynum -= 48;
		}
		keychar = String.fromCharCode(keynum);
		numcheck = /\d/;
		var res=numcheck.test(keychar);	
		if (res)
		{
			priceTimer=false;
			priceTimer=setTimeout("KMChangeFilter('','')",priceTime);
		}
		return res;		
	});
	
	jQuery('.catalog .specific a').on('click',function(){
		var category_id=jQuery(this).find('input').val();
		jQuery('.catalog .specific a').removeClass('active');
		jQuery(this).addClass('active');
		jQuery('.filter').find('input[name="categories[]"]').val(category_id);
		KMChangeFilter('','');
		return false;
	});
	
	jQuery('.catalog .extra a').on('click',function(){
		var name=jQuery(this).attr('rel');
		if (jQuery(this).is('.active'))
		{
			jQuery(this).removeClass('active')
			jQuery('.filter').find('input[name="'+name+'"]').removeAttr('checked');
		}
		else
		{
			jQuery(this).addClass('active')
			jQuery('.filter').find('input[name="'+name+'"]').attr('checked','checked');		
		}
		KMChangeFilter('','');
		return false;
	});	

});	

window.onload=function(){
	var form=jQuery('#ksenmart-search form');
	var url=URI_ROOT+'index.php?option=com_ksenmart&view=shopcatalog';
	var formdata=form.serialize();
	url+='&'+formdata;
	if (view!='shopcatalog')
	{
		return false;
	}
	jQuery.ajax({
		url:url+'&task=shopcatalog.filter_products&tmpl=ksenmart',
		success:function(data){
			data = JSON.parse(data);
			var properties=data.properties;
			var manufacturers=data.manufacturers;
			var countries=data.countries;
			var props=[];
			if (clicked!='manufacturers')
			{
				form.find('.manufacturer').hide();
				for(var k=0;k<manufacturers.length;k++)
					form.find('.manufacturer_'+manufacturers[k]).show();
			}
			if (clicked!='countries')
			{
				form.find('.country').hide();
				for(var k=0;k<countries.length;k++)
					form.find('.country_'+countries[k]).show();
			}			
			form.find('.property').each(function(){
				if (clicked!='' && !jQuery(this).is('.'+clicked))
				{
					jQuery(this).find('.property_value').addClass('inactive');
					jQuery(this).find('.property_value').hide();
				}	
			});
			for(var key in properties)
			{
				props=properties[key];
				if (clicked!='property_'+key)
				{
					for(var k=0;k<props.length;k++)
					{
						form.find('.property_value_'+props[k]).removeClass('inactive');
						form.find('.property_value_'+props[k]).show();
					}	
				}
			}
			form.find('.property').each(function(){
				if (jQuery(this).find('.property_value').length!=jQuery(this).find('.inactive').length)
					jQuery(this).show();
				else	
					jQuery(this).hide();
			});			
		}
	});		
	return true;
}

function KMChangeFilter(obj, clicked)
{
	var form=jQuery('.ksenmart-search form');
	var item=jQuery(obj).parents('.item');
	var url='index.php?option=com_ksenmart&view=shopcatalog';
	
	var priceA=parseInt(jQuery('#search-price-less').val());
	var priceB=parseInt(jQuery('#search-price-more').val());
	if (isNaN(priceA))
		priceA=0;
	if (isNaN(priceB))
		priceB=0;
	if (priceA<leftLimit)
	{
		priceA=leftLimit
		jQuery('#search-price-less').val(priceA);
	}
	if (priceA>rightLimit)
	{
		priceA=rightLimit-100
		jQuery('#search-price-less').val(priceA);
	}	
	if (priceB<leftLimit)
	{
		priceB=leftLimit+100
		jQuery('#search-price-more').val(priceB);
	}
	if (priceB>rightLimit)
	{
		priceB=rightLimit
		jQuery('#search-price-more').val(priceB);
	}	
	if (priceA>priceB)
	{
		var tmp=priceA;
		priceA=priceB;
		priceB=tmp;
		jQuery('#search-price-less').val(priceA);
		jQuery('#search-price-more').val(priceB);
	}
	var one_width=search_width/(rightLimit-leftLimit);
	var left_width=Math.round(one_width*(priceA-leftLimit));
	if(left_width==0)
		left_width=6;
	var right_width=Math.round(one_width*(rightLimit-priceB));
	if(right_width==0)
		right_width=6;		
	jQuery('#leftBlock_one').width(left_width);
	jQuery('#rightBlock_one').width(right_width);
	
	var formdata=form.serialize();
	url+='&'+formdata;
	url+='&clicked='+clicked;
	url+='&Itemid='+shopItemid;
	jQuery.ajax({
		url:URI_ROOT+'index.php?option=com_ksenmart&task=shopajax.get_route_link&tmpl=ksenmart',
		async:false,
		data:{url:url},
		success:function(data){
			url=data;
		}
	});
	if (view!='shopcatalog')
	{
		window.location.href=url;
		return false;
	}
	if(item.is('.active')){
		item.removeClass('active');
        item.parents('li').removeClass('active');
	}else{
		item.addClass('active');
        item.parents('li').addClass('active');
    }
	history.pushState(null, null, url);
	jQuery.ajax({
		url:url+'&task=shopcatalog.filter_products&tmpl=ksenmart',
		success:function(data){
            data = JSON.parse(data);
			jQuery('.content_in_wrapp').html(data.html);

			var properties=data.properties;
			var manufacturers=data.manufacturers;
			var countries=data.countries;
			var props=[];
			if (clicked!='manufacturers')
			{
				form.find('.manufacturer').hide();
				for(var k=0;k<manufacturers.length;k++)
					form.find('.manufacturer_'+manufacturers[k]).show();
			}
			if (clicked!='countries')
			{
				form.find('.country').hide();
				for(var k=0;k<countries.length;k++)
					form.find('.country_'+countries[k]).show();
			}			
			form.find('.property').each(function(){
				if (clicked!='' && !jQuery(this).is('.'+clicked))
				{
					jQuery(this).find('.property_value').addClass('inactive');
					jQuery(this).find('.property_value').hide();
				}	
			});
			for(var key in properties)
			{
				props=properties[key];
				if (clicked!='property_'+key)
				{
					for(var k=0;k<props.length;k++)
					{
						form.find('.property_value_'+props[k]).removeClass('inactive');
						form.find('.property_value_'+props[k]).show();
					}	
				}
			}
			form.find('.property').each(function(){
				if (jQuery(this).find('.property_value').length!=jQuery(this).find('.inactive').length)
					jQuery(this).show();
				else	
					jQuery(this).hide();
			});			
		}
	});		
	return true;
}