jQuery(document).ready(function(){

	jQuery('.mod_km_search .inputbox').live('keypress',function(e){
		if (e.keyCode==13)
		{
			OrdersList.loadListPage(1);
			return false;
		}
	});	

	jQuery('.mod_km_search .button').live('click',function(){
		OrdersList.loadListPage(1);
	});

});