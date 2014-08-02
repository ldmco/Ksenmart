jQuery(document).ready(function(){
	jQuery('.discount').hover(
		function(){
			jQuery('.discount .login-form').slideDown();
		},
		function(){
			jQuery('.discount .login-form').slideUp();
		}
	);
    
    jQuery('#subscribe .button').on('click', function(){
        var email = jQuery(this).parent().children('input[type="email"]').val();
        var form  = jQuery('#form-login');
        
	        form.find('input[type="email"]').val(email);

        jQuery('.popup').hide();
		form.fadeIn(400);
        jQuery('html, body').animate({scrollTop: "0px"}); 
		return false;
    });
    
	jQuery('.discount form').on('submit', function(){
		if (user_id==0)
		{
			var login=jQuery('.login-form input[name="login"]').val();
			if (login=='')
			{
				KMShowMessage("Введите ваш E-mail");
				return false;
			}	
			jQuery.ajax({
				url:URI_ROOT+'index.php?option=com_ksenmart&task=shopajax.site_auth&login='+login+'&tmpl=ksenmart',
				success:function(data){
					if (data=='login')
						window.location.reload();
					else
						KMShowMessage('Ошибка . Неправильно введен логин или пароль .');
				}
			});	
		}else{
			jQuery.ajax({
				url:URI_ROOT+'index.php?option=com_ksenmart&task=shopprofile.setSubscribe',
				success:function(data){
				   KMShowMessage('Теперь вы в числе подписчиков');
				}
			});			
		}
		return false;
	});	
});