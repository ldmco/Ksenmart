jQuery(document).ready(function(){
	
	jQuery('.ksenmart-panelhelper .question a').click(function(){
		var help=jQuery(this).attr('help');
		if (jQuery('.ksenmart-panelhelper .'+help).is(':visible'))
		{
			jQuery('.ksenmart-panelhelper .answer').slideUp(500);
		}
		else
		{
			jQuery('.ksenmart-panelhelper .answer').slideUp(500);
			jQuery('.ksenmart-panelhelper .'+help).slideDown(500);
		}	
		return false;
	});
	
});