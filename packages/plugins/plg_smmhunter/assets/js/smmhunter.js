jQuery(document).ready(function(){
	
	jQuery('.ks-smmhunter form').on('submit', function(){
		var form = jQuery(this);
		var data = form.serialize();
		
		form.find('.register').css('display', 'none');
		form.find('.registering').css('display', 'block');
		
		jQuery.ajax({
			url: 'index.php?option=com_ksenmart&task=smmhunter.register',
			data: data,
			dataType: 'json',
			async: true,			
			success: function(response){
				if (response.status != 'success')
				{
					alert(response.message);
					form.find('.registering').css('display', 'none');					
					form.find('.register').css('display', 'block');
				}
				else
				{
					window.location.reload();
				}
			}
		})
		
		return false;
	});
	
});