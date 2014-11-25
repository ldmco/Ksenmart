jQuery(document).ready(function(){
    var form                = jQuery('#simple-search-form');
    var search_input        = jQuery('.search input[type="search"]');
    var success_wrapp_block = jQuery('#search_result');
    var success_block       = success_wrapp_block.children('.items');
    var empty_result_block  = success_wrapp_block.children('.empty_result');
    var full_page           = success_wrapp_block.children('.other_result');
    var full_page_link      = full_page.children('a');
    var full_page_link_href = full_page_link.attr('href');
    var search_length       = 0;
    var value               = '';
    
    search_input.on('keyup', function(e){
        if(e.keyCode != 38 && e.keyCode != 40){
            search(jQuery(this));
        }
    });
    
    function search($this){
        value           = $this.val();
        search_length   = value.length;
        if(search_length >= 3){
			jQuery.ajax({
                url: URI_ROOT+'index.php?option=com_ksenmart&view=search&task=search.productSearch&value='+value+'&ajax_search=1&tmpl=ksenmart',
				success: function(data){
                    if(data.length > 0){
                        empty_result_block.css('display', 'none');
                        success_block.html(data);
                        if(success_block.children('.relevants').length == 0){
                            success_block.children('.title_block').css('display', 'none');
                        }
                        if(success_block.children('.item').length >= 5){
                            full_page.css('display', 'block');
                            full_page_link.attr('href', full_page_link_href+''+value);
                        }else{
                            full_page.css('display', 'none');
                        }
                    }else{
                        success_block.html('');
                        empty_result_block.css('display', 'block');
                    }
                    success_wrapp_block.fadeIn(400);
				}
			});
        }else{
            success_wrapp_block.fadeOut(400);
        }
    }
	
	jQuery('#simple-search-form').on('submit', function(){
		var page_url = 'index.php?option=com_ksenmart&view=search&value='+value+'&Itemid=' + shopItemid;
		jQuery.ajax({
			url: URI_ROOT + 'index.php?option=com_ksenmart&task=shopajax.get_route_link&tmpl=ksenmart',
			async: false,
			data: {
				url: page_url
			},
			success: function(data) {
				window.location = data;
			}
		});
		
		return false;
	});
    
    success_block.on('click', '.relevants .item', function(){
        var a = jQuery(this).find('a');
        var item_href = a.attr('href');   
        search_input.val(a.text());
        if(item_href != null){
            window.location = item_href;
        }
    });
    
    bindMoveKeys();
    
    success_block.on('hover', '.item', function(){
        success_block.find('.item.active').removeClass('active');
        jQuery(this).addClass('active');
    });

    function bindMoveKeys(){

        for(var i = 0; i < success_block.find('.item').size(); i++) {
            success_block.find('.item').eq(i).data("number", i);
        }

        search_input.on('keydown', function(event){
            switch (event.keyCode) {
                case 38: // вверх
                    navigate('up');
                    break;
                case 40: // вниз
                    navigate('down');
                    break;
                case 13:
                    var item_active      = success_block.children('.item.active');
                    var relevants        = success_wrapp_block.find('.relevants');
                    var relv_item_active = relevants.children('.item.active');
                    var a                = item_active.find('.title a');
                    var item_href        = a.attr('href');
                    
                    console.log(relv_item_active.length);
                    if(item_active.length > 0){
                        if(item_href != null){
                            window.location = item_href;
                        }
                        event.preventDefault();
                    }else if(relv_item_active.length > 0){
                        var a           = relv_item_active.find('.title a');
                        var item_href   = a.attr('href')
                        console.log(a.data().type);
                        if(item_href != null){
                            window.location = item_href;
                        }
                       if(a.data().type == 'category' || a.data().type == 'manufacture'){
                            if(item_href != null){
                                window.location = item_href;
                            }
                        }else{
                            search_input.val(a.text());
                            search(search_input);
                        }
                        event.preventDefault();
                    }else{
                        form.submit();
                    }

                    break;
            }
        });
    }
    
    function navigate(direction) {
        if(success_block.find('.item.active').size() == 0){
            currentSelection = -1;
        }
        if(direction == 'up' && currentSelection != -1){
            if(currentSelection != 0){
                currentSelection--;
            }
        }else if(direction == 'down'){
            if(currentSelection != success_block.find('.item').size() -1){
                currentSelection++;
            }
        }
        setSelected(currentSelection);
    }
    
    function setSelected(menuitem) {
        success_block.find('.item').removeClass("active");
        success_block.find('.item').eq(menuitem).addClass("active");
        //currentUrl = $("#menu ul li a").eq(menuitem).attr("href");
    }
    
	jQuery(document).click(function(event){
		if (jQuery('#search_result:visible').length > 0){
			if (!isChild(event.target,document.getElementById(jQuery('#search_result:visible').attr('id'))))
				success_wrapp_block.fadeOut(400, function(){
				    success_block.html('');
				});
                
		}
	});
    
});