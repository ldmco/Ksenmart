jQuery(document).ready(function(){

	jQuery('form').on('submit', function(){
		var form = jQuery(this);
		if (form.find('input[name="name"]').val()=='')
		{
			alert(JText_print_template_name);
			return false;
		}		
		form.submit();
	});
	
});