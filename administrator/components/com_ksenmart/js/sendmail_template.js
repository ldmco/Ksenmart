jQuery(document).ready(function(){

	jQuery('.save').click(function(){
		var form=jQuery(this).parents('.form');
		if (form.find('input[name="name"]').val()=='')
		{
			alert(JText_print_template_name);
			return false;
		}		
		form.submit();
	});
	
});