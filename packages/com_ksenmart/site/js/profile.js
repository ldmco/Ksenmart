var $pimg, jcrop_api, maskList, maskOpts;

function cropImg(class_j){
    var 
        boundx,
        boundy,

    // Grab some information about the preview pane
    $preview    = jQuery('.preview-pane'),
    $pcnt       = jQuery('.preview-pane .preview-container'),
    

    xsize = $pcnt.width(),
    ysize = $pcnt.height();

    jQuery(class_j).Jcrop({
        onChange: updatePreview,
        onSelect: updatePreview,
        minSize: [80, 80],
        bgFade: true,
        aspectRatio: xsize / ysize
    },function(){
        // Use the API to get the real image size
        var bounds = this.getBounds();
        boundx = bounds[0];
        boundy = bounds[1];
        // Store the API in the jcrop_api variable
        jcrop_api = this;

        jcrop_api.setSelect([0,0,80,80]);
        // Move the preview into the jcrop container for css positioning
        $preview.appendTo(jcrop_api.ui.holder);
    });

    function updatePreview(c){
        if (parseInt(c.w) > 0){
            var rx = xsize / c.w;
            var ry = ysize / c.h;

            jQuery('input[name="x1"]').val(c.x);
            jQuery('input[name="y1"]').val(c.y);
            jQuery('input[name="h"]').val(c.h);
            jQuery('input[name="w"]').val(c.w);
            jQuery('input[name="boundx"]').val(boundx);
            jQuery('input[name="boundy"]').val(boundy);

            $pimg.css({
                width: Math.round(rx * boundx) + 'px',
                height: Math.round(ry * boundy) + 'px',
                marginLeft: '-' + Math.round(rx * c.x) + 'px',
                marginTop: '-' + Math.round(ry * c.y) + 'px'
            });
        }
    };
}

jQuery(document).ready(function(){
    
    jQuery('.favorities .thumbnails .item .thumbnail, .watched .thumbnails .item .thumbnail').prepend('<a class="close btn btn-danger l-lose_t_product" href="javascript:void(0);" title="">Убрать</a>');
    
    jQuery('.favorities .thumbnails .item .thumbnail .l-lose_t_product, .watched .thumbnails .item .thumbnail .l-lose_t_product').on('click', function(){
        var item = jQuery(this).parents('.item');
        var type = jQuery(this).parents('.tab-pane').data().type;
        var id   = item.data().id;

        if(typeof id != 'undefined'){
            jQuery.ajax({
                type: 'POST',
                url: URI_ROOT+'index.php?option=com_ksenmart&tmpl=ksenmart&task=profile.lose_t_product',
                data: {
                    id: id,
                    type: type
                },
                success: function(data){
                    if(data == ''){
                        item.fadeOut(400, function(){
                            jQuery(this).remove();
                        });
                    }
                }
            });
        }        
    });
    
    var flag_avatar = true;
    var pre_pane    = '<div class="preview-pane"><div class="preview-container border_ksen"><img alt="" class="jcrop-preview" /></div></div>';

    var current_popup   = jQuery('.popup.avatar_load');
    var form            = jQuery('.profile_info');

    form.attr('enctype', 'multipart/form-data');

    $pimg = jQuery('.preview-pane img');
    $pimg.removeClass('target');
    $pimg.addClass('jcrop-preview');

    cropImg('.target');
    
    form.find('input[name="filename"]').on('change', function(){

        flag_avatar = false;

        var oFile = jQuery(this)[0].files[0];

        var avatar_preview      = jQuery('.avatar_preview');
        var avatar_preview_img  = avatar_preview.children('img');
        
        //avatar_preview.find('.jcrop-holder').remove();
        avatar_preview_img.removeAttr('style');

        if (typeof jcrop_api != 'undefined') {
            jcrop_api.destroy();
        }

        var oImage = avatar_preview_img[0];


        $pimg = jQuery('.preview-pane img');
        if($pimg.length == 0){
            avatar_preview.after(pre_pane);
            $pimg = jQuery('.preview-pane img');
        }

        var oReader = new FileReader();

        oReader.onload = function(e) {

            oImage.src   = e.target.result;
            oImage.onload = function () {
                $pimg[0].src = e.target.result;
                avatar_preview_img.attr('class', 'target');
                cropImg('.target');
            };
        };
        oReader.readAsDataURL(oFile);
    });

    
    jQuery('.avatar_edit').on('click', function(e){
        e.preventDefault();
        
        var x1  = form.find('input[name="x1"]').val();
        var y1  = form.find('input[name="y1"]').val();
        var h   = form.find('input[name="h"]').val();
        var w   = form.find('input[name="w"]').val();

        var boundx   = form.find('input[name="boundx"]').val();
        var boundy   = form.find('input[name="boundy"]').val();

        var data        = new FormData();
        var error       = '';

        jQuery.each(form.find('input[name="filename"]')[0].files, function(i, file) {
            data.append('file-'+i, file);
        });

        data.append('x1', x1);
        data.append('h', h);
        data.append('y1', y1);
        data.append('w', w);

        data.append('resize', true);
        data.append('boundx', boundx);
        data.append('boundy', boundy);

        if(flag_avatar){
            data.append('flag', flag_avatar);
            data.append('avatar_full', avatar_full);
        }

        jQuery.ajax({
            url: URI_ROOT+'index.php?option=com_ksenmart&tmpl=ksenmart&task=profile.loadAvatar',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data){
                flag_avatar = true;
                if(data == ''){
                    jQuery('#avatar_edit').modal('hide');
                }else{
                    
                }
                location.reload();
            }
        });
    });
    
	jQuery('.review.edit img, .review.add img').on('click', function(){
        var review = jQuery(this).parents('.review');
		var rate = jQuery(this).data().rate;

		review.find('#comment_rate').val(rate);
		review.find('img').attr('src', URI_ROOT+'components/com_ksenmart/images/star2-small.png');
		
        for(var k=1; k<=rate; k++){
			review.find('img[data-rate="'+k+'"]').attr('src',URI_ROOT+'components/com_ksenmart/images/star-small.png');
        } 
	});
 
	jQuery('.favorite .last').on('click', function(){
		if (jQuery(this).not('.active'))
		{
			jQuery(this).parents('.favorite').find('.all_products').slideUp(500);
			jQuery(this).addClass('active');
			jQuery(this).parents('.favorite').find('.show_all').removeClass('active');
			jQuery(this).parents('.favorite').find('.show-all').show();
		}
	});
	
	jQuery('.favorite .show_all').on('click', function(){
		if (jQuery(this).not('.active'))
		{
			jQuery(this).parents('.favorite').find('.all_products').slideDown(500);
			jQuery(this).addClass('active');
			jQuery(this).parents('.favorite').find('.last').removeClass('active');
			jQuery(this).parents('.favorite').find('.show-all').hide();
		}
	});
	
	jQuery('.favorite .show-all').on('click', function(){
		jQuery(this).parents('.favorite').find('.all_products').slideDown(500);
		jQuery(this).parents('.favorite').find('.show_all').addClass('active');
		jQuery(this).parents('.favorite').find('.last').removeClass('active');
		jQuery(this).hide();
	});	
	
	jQuery('.orders .last').on('click', function(){
		if (jQuery(this).not('.active'))
		{
			jQuery(this).parents('.orders').find('.all_orders').slideUp(500);
			jQuery(this).addClass('active');
			jQuery(this).parents('.orders').find('.show_all').removeClass('active');
		}
	});
	
	jQuery('.orders .show_all').on('click', function(){
		if (jQuery(this).not('.active'))
		{
			jQuery(this).parents('.orders').find('.all_orders').slideDown(500);
			jQuery(this).addClass('active');
			jQuery(this).parents('.orders').find('.last').removeClass('active');
		}
	});	
	
	jQuery('.order_tr').on('click', function(){
		var data_count = jQuery(this).data().count;
		var profile_order = jQuery(this).parents('tbody').find('.order_dropdows_'+data_count);

		if(profile_order.hasClass('active')){
			profile_order.hide();
            profile_order.toggleClass('active');
		}else{
            jQuery(this).parents('tbody').find('.profile_order').removeClass('active').hide();
			profile_order.show();
            profile_order.toggleClass('active');
		}
		return true;
	});
	
	jQuery('.profile_info .button').on('click', function(e){
        e.preventDefault();
        
		var form          = jQuery('.profile_info');
		var name          = form.find('input[name="form[name]"]').val();
		var email         = form.find('input[name="form[email]"]').val();
		var phone_country = form.find('input[name="form[phone_country]"]').val();
		var phone_code    = form.find('input[name="form[phone_code]"]').val();
		var phone         = form.find('input[name="form[phone]"]').val();

		if (name == ''){
			KMShowMessage('Введите ваше имя');
			return false;
		}
		if (email == ''){
			KMShowMessage('Введите ваш E-mail');
			return false;
		}
        
		var res = true;//validatePhone(phone_country, phone_code,phone);
		if (!res)
			return false;	
		if (form.find('input[name="sendEmail"]:checked').length > 0 && email == ''){
			KMShowMessage('Введите ваш E-mail');
			return false;
		}
		form.submit();
	});
	
	jQuery('.add_address a').on('click', function(){
		var new_address = jQuery('.new_address');
		if (new_address.is(':visible')){
			new_address.slideUp(500);
		}else{
			new_address.slideDown(500);
        }
		return false;	
	});
    
    jQuery('.table.adresses a, .table.adresses input[type="radio"]').on('click', function(e){
        e.stopPropagation();
    });
    
	jQuery('.adresses tr.expnd').on('click', function(){
        var tr_id        = jQuery(this).data().tr;
        var expand_tr    = jQuery(this).parent().find('[data-exp-tr="'+tr_id+'"]');
        var other_expand = jQuery(this).parent().children('.edit_address');

		if(expand_tr.is(':visible')){
			expand_tr.hide();
		}else{
            other_expand.hide();
			expand_tr.show();
        }
		return false;	
	});
	
	jQuery('.new_address .top a').on('click', function(){
		jQuery('.new_address form').submit();
		return false;
	});	
	
	jQuery('.address input[type="radio"]').on('click', function(){
		var address_id = jQuery(this).data().address_id;
		jQuery.ajax({
			url: URI_ROOT+'index.php?option=com_ksenmart&task=profile.set_default_address&id='+address_id+'&tmpl=ksenmart',
			success: function(data){console.log(data);}
		});	
	});
    
    maskList = jQuery.masksSort(jQuery.masksLoad(URI_ROOT + "components/com_ksenmart/js/phone-codes.json"), ['#'], /[0-9]|#/, "mask");
    maskOpts = {
        inputmask: {
            definitions: {
                '#': {
                    validator: "[0-9]",
                    cardinality: 1
                }
            },
            //clearIncomplete: true,
            showMaskOnHover: false,
            autoUnmask: true
        },
        match: /[0-9]/,
        replace: '#',
        list: maskList,
        listKey: "mask",
        onMaskChange: function(maskObj, completed) {
            if (completed) {
                var hint = maskObj.name_ru;
                if (maskObj.desc_ru && maskObj.desc_ru != "") {
                    hint += " (" + maskObj.desc_ru + ")";
                }
                jQuery("#descr").html(hint);
            } else {
                jQuery("#descr").html("Введите номер");
            }
            jQuery(this).attr("placeholder", jQuery(this).inputmask("getemptymask"));
        }
    };

    jQuery('#customer_phone').inputmasks(maskOpts);

    setTab();
    
    jQuery('.cancel_edit').on('click', function(){
        cancel_review_edit(jQuery(this));
    });
    
    function cancel_review_edit($this){
        var parent_b           = $this.parents('.wrap_rvw_block');
        var quick_edit_block   = parent_b.find('.quick_edit');

        if(quick_edit_block.attr('contenteditable') == 'true'){
            
            quick_edit_block.attr('contenteditable', 'false');
            parent_b.find('.toolbar').hide();
            
            magicalText('.magical_text');
        }
    }
    
    jQuery('.quick_edit').on('click', function(){
        
        var parent_b = jQuery(this).parents('.wrap_rvw_block');
        
        parent_b.find('.quick_edit ').attr('contenteditable', 'true');
        parent_b.find('.toolbar').show();

        parent_b.find('.follow').remove();
        jQuery(this).focus();
    });
    
    jQuery('.save_product_review').on('click', function(){
        var $this            = jQuery(this);
        var parent           = jQuery(this).parents('.wrap_rvw_block');
        var quick_edit_block = parent.find('.quick_edit');
        var review_id        = parent.data().id;
        
        var comment = parent.find('[data-type="comment"]').text();
        var good    = parent.find('[data-type="good"]').text();
        var bad     = parent.find('[data-type="bad"]').text();

        jQuery.ajax({
            type: 'POST',
            url: URI_ROOT+'index.php?option=com_ksenmart&task=profile.updateProductReview&tmpl=ksenmart',
            data: {
                review_id: review_id,
                comment: comment,
                good: good, 
                bad: bad
            },
            success: function(data){
                cancel_review_edit($this);
            }
        });
    });
    
    jQuery('.save_shop_review').on('click', function(){
        var $this            = jQuery(this);
        var parent           = jQuery(this).parents('.wrap_rvw_block');
        var quick_edit_block = parent.find('.quick_edit');
        var review_id        = parent.data().id;
        
        var comment = parent.find('[data-type="comment"]').html();

        jQuery.ajax({
            type: 'POST',
            url: URI_ROOT+'index.php?option=com_ksenmart&task=profile.edit_shop_review&tmpl=ksenmart',
            data: {
                id: review_id,
                review: comment
            },
            success: function(data){
                console.log(data);
                cancel_review_edit($this);
            }
        });
    });
    
    jQuery('a[data-toggle="tab"]').on('shown', function(){
        magicalText('.magical_text');
    });
});

function setTab(){
    var tabIndex = window.location.hash;
    
    if(tabIndex != ''){
        var tabbable    = jQuery('.tabbable');
        var ul          = tabbable.children('ul.nav');
        var tab_content = tabbable.children('div.tab-content')
        
        var a_tab       = ul.find('a[href="'+tabIndex+'"]');
        var content_tab = tab_content.find(tabIndex);
        
        ul.children('li').removeClass('active');
        tab_content.children('div').removeClass('active');
        
        a_tab.parent('li').addClass('active');
        content_tab.addClass('active');
    }
}

jQuery(window).bind('hashchange', function() {
    setTab();
});