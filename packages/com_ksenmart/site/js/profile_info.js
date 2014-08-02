jQuery(document).ready(function(){

	jQuery('.order_form .order_button').click(function(){
		var form=jQuery('.order_form');
		var name=form.find('input[name="form[name]"]').val();
		var email=form.find('input[name="form[email]"]').val();
		if (name=='')
		{
			alert('Введите ваше имя');
			return false;
		}
		if (email=='')
		{
			alert('Введите ваш E-mail');
			return false;
		}
		if (form.find('input[name="sendEmail"]:checked').length>0 && email=='')
		{
			alert('Введите ваш E-mail');
			return false;
		}
		form.submit();
	});

});