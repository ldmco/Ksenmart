jQuery(document).ready(function(){

    jQuery('#products_gallery').slides({
			preload: true,
			preloadImage: 'img/loading.gif',
			effect: 'slide, fade',
			crossfade: true,
			slideSpeed: 200,
			fadeSpeed: 500,
			generateNextPrev: true,
			generatePagination: false,
            dynamicallyUpdateAnchors: false
    });

	jQuery('#item .info input[name*="property_"]').change(function(){
		var form=jQuery(this).parents('form');
		jQuery.ajax({
			url:URI_ROOT+'index.php?option=com_ksenmart&task=shopajax.get_product_price_with_properties&'+form.serialize()+'&tmpl=ksenmart',
			success: function( data ) {
				data=data.split('^^^');
				form.find('.prices .price span:first').text(data[0]);
				form.find('input[name="price"]').val(data[1]);
			}
		});
	});
	
	jQuery('#item .unit .reviews .head a').click(function(){
		if (jQuery('#comment_form').is(':visible'))
			jQuery('#comment_form').slideUp(400);
		else
			jQuery('#comment_form').slideDown(400);
		return false;	
	});
	
	jQuery('#item .unit #comment_form img').click(function(){
		var rate=jQuery(this).attr('rate');
		jQuery('#item .unit #comment_form img').attr('src',URI_ROOT+'components/com_ksenmart/images/star2-small.png');
		for(var k=1;k<=rate;k++)
			jQuery('#item .unit #comment_form img[rate="'+k+'"]').attr('src',URI_ROOT+'components/com_ksenmart/images/star-small.png');
		jQuery('#item .unit #comment_form input[name="comment_rate"]').val(rate);
	});	
	
	jQuery('#comment_form .st_button').click(function(){
		var form=jQuery('#item #comment_form');
		if (form.find('input[name="comment_name"]').val()=='')
		{
			KMShowMessage('Введите ваше имя');
			return false;
		}
		if (form.find('textarea[name="comment_comment"]').val()=='')
		{
			KMShowMessage('Введите ваш комментарий');
			return false;
		}
		form.submit();
	});	
	
	jQuery('#item .unit .reviews .show-all a').click(function(){
		jQuery(this).hide();
        jQuery('.all-comments').slideDown(500);
		return false;
	});
	
	jQuery('a.to-fav').click(function(){
		if (user_id==0)
		{
			jQuery('.popup').hide();	
			jQuery(".form-login").fadeIn(400);
		}
		else
		{
			var prd_id=jQuery(this).attr('prd_id');
			jQuery.ajax({
				url:URI_ROOT+'index.php?option=com_ksenmart&task=shopajax.add_favorites&id='+prd_id+'&tmpl=ksenmart',
				success:function(data){
					KMShowMessage('Теперь этот товар у вас в избранных');
				}
			});
		}
		return false;
	});

	jQuery('.spy_price').click(function(){
		if (user_id==0){
			jQuery('.popup').hide();	
			jQuery(".form-login").fadeIn(400);
		}else{
			var prd_id = jQuery(this).data().prd_id;
			jQuery.ajax({
				url:URI_ROOT+'index.php?option=com_ksenmart&task=shopajax.add_watched&id='+prd_id,
				success:function(data){
					KMShowMessage('Теперь вы следите за этим товаром');
				}
			});
		}
		return false;
	});
	
	jQuery('.to-order a').click(function(){
		jQuery('body').animate({'scrollTop':jQuery('.set').offset().top},500); 
		return false;
	});
	
	jQuery('#item .set .row').click(function(){
		jQuery(this).removeClass('row_active');
	});
	
});