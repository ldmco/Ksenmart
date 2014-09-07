jQuery(document).ready(function() {

	jQuery('.callback .button').click(function(){
		var phone_country='+7';
		var phone_code=jQuery('.callback input.phone_code').val();
		var phone=jQuery('.callback input.phone').val();
		var res=validatePhone(phone_country,phone_code,phone);
		if (!res)
			return false;
		phone=phone_country+'-'+phone_code+'-'+phone;	
		jQuery.ajax({
			url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.callback&phone='+phone,
			success:function(data){
				jQuery('.callback input.phone_code').val('');
				jQuery('.callback input.phone').val('');
				KMShowMessage(data);
			}
		});	
		return false;
	});
	
	jQuery('.ask-form .button').click(function(){
		var email=jQuery('.ask-form .inputbox').val();
		var question=jQuery('.ask-form .textarea').val();
		if (email=='')
		{
			KMShowMessage("Введите ваш E-mail");
			return false;
		}
		if (!isValidEmail(email))
		{
			KMShowMessage("Введите корректный E-mail");
			return false;	
		}
		if (question=='')
		{
			KMShowMessage("Введите ваш вопрос");
			return false;
		}	
		jQuery.ajax({
            url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.question&email=' + email + '&question=' + question,
			success:function(data){
				jQuery(".ask-form").fadeOut(400);
				jQuery('.ask-form .inputbox').val('');
				jQuery('.ask-form .textarea').val('');
				KMShowMessage(data);
			}
		});	
		return false;
	});	
	
	jQuery('.ask a').click(function() {
		KMShowPopup(jQuery('.ask a'),jQuery('.ask-form'),0,0);
		return false;
	});	
	jQuery('.ask-form .close').click(function() {
		jQuery('.ask-form').fadeOut(400);
	});	
	
});