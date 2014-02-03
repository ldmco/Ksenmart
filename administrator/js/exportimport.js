jQuery(document).ready(function(){

	jQuery('body').on('click','.check_all_cats',function(){
		jQuery('.ksm-slidemodule-ksmcategories li').addClass('active');
		jQuery('.ksm-slidemodule-ksmcategories input[type="checkbox"]').attr('checked','checked');
	});
	
	jQuery('body').on('click','.discharge_cats',function(){
		jQuery('.ksm-slidemodule-ksmcategories li').removeClass('active');
		jQuery('.ksm-slidemodule-ksmcategories input[type="checkbox"]').removeAttr('checked');
	});	

	jQuery('body').on('click','#save_yandexmarket',function(){
		var formdata=jQuery('.form').serialize();
		var url='index.php?option=com_ksenmart&'+formdata;
		var data={};
		data['task']='exportimport.save_yandexmarket'
		jQuery.ajax({
			url:url,
			data:data,
			dataType:'json',
			async:false,			
			success:function(responce){
				if (responce.errors != 0)
				{
					KMShowMessage(responce.message.join('<br>'));
				}					
			}
		});
	});	

});
