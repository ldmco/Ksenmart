jQuery(document).ready(function(){
	jQuery('.catalog-menu a').click(function(){
		var level=jQuery(this).attr('level');
		var next_level=eval(level)+1;
		var next_level=jQuery(this).parent().find('.menu-list-'+next_level);
		if (next_level.css('display')!='none' || next_level.find('li').length==0)
			return true;
		jQuery('.catalog-menu .menu-list-'+level+' ul').slideUp(400);	
		jQuery('.catalog-menu .menu-list-'+level+' li').removeClass('active');
		jQuery(this).parent().addClass('active');
		next_level.slideDown(400);	
		return false;
	});	
});