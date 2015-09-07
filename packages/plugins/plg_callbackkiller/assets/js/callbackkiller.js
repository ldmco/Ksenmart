jQuery(document).ready(function(){
	
	jQuery('.ks-callbackkiller form').on('submit', function(){
		var form = jQuery(this);
		var data = form.serialize();
		
		form.find('.register').css('display', 'none');
		form.find('.registering').css('display', 'block');
		
		jQuery.ajax({
			url: 'index.php?option=com_ksenmart&task=callbackkiller.register',
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
	
	jQuery('.ks-callbackkiller .update').on('click', function(){
		var block = jQuery('.ks-callbackkiller .killer-data');
		
		block.find('.update').css('display', 'none');
		block.find('.updating').css('display', 'block');
		
		jQuery.ajax({
			url: 'index.php?option=com_ksenmart&task=callbackkiller.update',
			dataType: 'json',
			async: true,			
			success: function(response){
				if (response.status != 'success')
				{
					alert(response.message);
					block.find('.updating').css('display', 'none');					
					block.find('.update').css('display', 'block');		
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