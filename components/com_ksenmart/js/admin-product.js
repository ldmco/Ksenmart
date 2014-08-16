jQuery(document).ready(function() {

    jQuery('.content').height(jQuery(window).height() - 40);

    jQuery('.head .close').click(function() {
        window.parent.closePopupWindow();
    });

    jQuery('input[type="text"]').on('focus', function() {
        if (jQuery(this).val() == jQuery(this).attr('title'))
            jQuery(this).val('');
    });

    jQuery('input[type="text"]').on('blur', function() {
        if (jQuery(this).val() == '')
            jQuery(this).val(jQuery(this).attr('title'));
    });

    jQuery('.show-meta').click(function() {
        if (jQuery('.meta-data:first').is(':visible'))
            jQuery('.meta-data').hide();
        else
            jQuery('.meta-data').show();
    });

    jQuery('.show-alias').click(function() {
        if (jQuery('.alias').is(':visible'))
            jQuery('.alias').hide();
        else
            jQuery('.alias').show();
    });

    jQuery('.show-mini-description').click(function() {
        if (jQuery('.mini-description').is(':visible'))
            jQuery('.mini-description').slideUp(500);
        else
            jQuery('.mini-description').slideDown(500);
    });

    jQuery('.photos').sortable({
        stop: function(event, ui) {
            jQuery('.photos .photo').removeClass('main');
            jQuery('.photos .photo:first').addClass('main');
        }
    });

    jQuery('.photo-title').on('click', function() {
        jQuery(this).hide();
        jQuery(this).parent().find('input[type="text"]').show();
    });

    jQuery('.del_photo').on('click', function() {
        if (confirm(JText_confirm_del_photo)) {
            var file = jQuery(this).attr('file');
            var set_main = false;
            var is_new = 0;
            if (jQuery(this).parent().parent().is('.main'))
                set_main = true;
            if (jQuery(this).parent().parent().is('.new'))
                is_new = 1;
            var photo = jQuery(this).parent().parent();
            var url = 'index.php?option=com_ksenmart&view=shopadmin&task=shopadmin.del_product_photo&file=' + file + '&is_new=' + is_new;
            jQuery.ajax({
                url: url,
                success: function() {
                    photo.remove();
                    if (set_main)
                        jQuery('.photos .photo:first').addClass('main');
                }
            });
        }
        return false;
    });

    jQuery('.show_video').on('click', function() {
        jQuery(this).parents('.video').find('.video_frame').show();
        jQuery('#content_ifr').css('visibility', 'hidden');
        jQuery('#introcontent_ifr').css('visibility', 'hidden');
    });

    jQuery('.video_frame_head .close').on('click', function() {
        jQuery(this).parents('.video_frame').hide();
        jQuery('#content_ifr').css('visibility', 'visible');
        jQuery('#introcontent_ifr').css('visibility', 'visible');
    });

    jQuery('.del_video').on('click', function() {
        if (confirm(JText_confirm_del_video)) {
            var is_new = 0;
            var type = '';
            if (jQuery(this).parent().parent().is('.new'))
                is_new = 1;
            if (jQuery(this).parent().parent().is('.local'))
                type = 'local';
            else if (jQuery(this).parent().parent().is('.youtube'))
                type = 'youtube';
            else if (jQuery(this).parent().parent().is('.embeded'))
                type = 'embeded';
            var video = jQuery(this).parent().parent();
            if (is_new == 1) {
                if (type == 'local') {
                    var file = jQuery(this).attr('file');
                    var url = 'index.php?option=com_ksenmart&view=shopadmin&task=shopadmin.del_product_video&video=' + file + '&is_new=' + is_new;
                    jQuery.ajax({
                        url: url,
                        success: function() {
                            video.remove();
                        }
                    });
                } else
                    video.remove();
            } else {
                var video_id = jQuery(this).attr('video_id');
                var url = 'index.php?option=com_ksenmart&view=shopadmin&task=shopadmin.del_product_video&video=' + video_id + '&is_new=' + is_new;
                jQuery.ajax({
                    url: url,
                    success: function() {
                        video.remove();
                    }
                });
            }
        }
        return false;
    });

    jQuery('#add_video').click(function() {
        if (jQuery('#video_link').val() == jQuery('#video_link').attr('title')) {
            alert(JText_print_video_link);
            return false;
        }
        var video_link = jQuery('#video_link').val();
        if (!(video_link.indexOf('youtube.com') >= 0)) {
            alert(JText_only_youtube_link);
            return false;
        }
        add_video_to_page(video_link, 'youtube');
        jQuery('#video_link').val(jQuery('#video_link').attr('title'));
    });

    jQuery('#add_embeded_video').click(function() {
        if (jQuery('#embeded_video_code').val() == jQuery('#embeded_video_code').attr('title')) {
            alert(JText_print_video_link);
            return false;
        }
        var video_code = jQuery('#embeded_video_code').val();
        add_video_to_page(video_code, 'embeded');
        jQuery('#embeded_video_code').val(jQuery('#embeded_video_code').attr('title'));
    });

    jQuery('#clear_related').click(function() {
        jQuery('#related_searchword').val(jQuery('#related_searchword').attr('title'));
        jQuery('.related_products_search_results').html('');
    });

    jQuery('#search_related').click(function() {
        if (jQuery('#related_searchword').val() != jQuery('#related_searchword').attr('title')) {
            var ignored_products = '|' + jQuery('input[name="id"]').val() + '|';
            jQuery('.related_products_info li').each(function() {
                if (jQuery(this).attr('id') != 0)
                    ignored_products += '|' + jQuery(this).attr('id') + '|';
            });
            var searchword = jQuery('#related_searchword').val();
            var url = 'index.php?option=com_ksenmart&view=shopadmin&layout=related_products&searchword=' + searchword + '&ignored_products=' + ignored_products + '&tmpl=ksenmart';
            jQuery.ajax({
                url: url,
                success: function(data) {
                    jQuery('.related_products_search_results').html(data);
                }
            });
        }
    });

    jQuery('.related_products_search_results .move_related').on('click', function() {
        if (jQuery('.related_products_info li[id="0"]').is(':visible'))
            jQuery('.related_products_info li[id="0"]').addClass('hide');
        jQuery(this).parent().parent().clone().appendTo('.related_products_info ul');
        jQuery(this).parent().parent().remove();
    });

    jQuery('.related_products_info .move_related').on('click', function() {
        jQuery(this).parent().parent().remove();
        if (jQuery('.related_products_info li').length == 1)
            jQuery('.related_products_info li[id="0"]').removeClass('hide');
    });

    jQuery('.ksenmart-categories li span').on('click', function() {
        if (jQuery(this).parent().is('.active')) {
            var selected_cats = '';
            jQuery(this).parent().removeClass('active');
            jQuery('.ksenmart-categories li.active').each(function() {
                selected_cats += '|' + jQuery(this).attr('cat_id') + '|';
            });
            var url = 'index.php?option=com_ksenmart&view=shopadmin&task=shopadmin.del_product_properties&category=|' + jQuery(this).parent().attr('cat_id') + '|&selected_cats=' + selected_cats + '&tmpl=ksenmart';
            jQuery.ajax({
                url: url,
                success: function(data) {
                    var properties = data.split('||');
                    for (var k = 0; k < properties.length; k++)
                        if (properties[k] != '')
                            jQuery('.ksenmart-properties-group li[property_id="' + properties[k] + '"]').remove();
                }
            });
        } else {
            var selected_cats = '';
            jQuery('.ksenmart-categories li.active').each(function() {
                selected_cats += '|' + jQuery(this).attr('cat_id') + '|';
            });
            var url = 'index.php?option=com_ksenmart&view=shopadmin&layout=product_properties&category=|' + jQuery(this).parent().attr('cat_id') + '|&selected_cats=' + selected_cats + '&tmpl=ksenmart';
            jQuery.ajax({
                url: url,
                success: function(data) {
                    jQuery('.ksenmart-properties-group ul:first').append(data);
                }
            });
            jQuery(this).parent().addClass('active');
        }
    });

    jQuery('.ksenmart-collections li').on('click', function() {
        if (jQuery(this).is('.active')) {
            jQuery(this).removeClass('active');
        } else {
            jQuery(this).parent().find('li').removeClass('active');
            jQuery(this).addClass('active');
        }
    });

    jQuery('.ksenmart-manufacturers li').on('click', function() {
        if (jQuery(this).is('.active')) {
            jQuery(this).removeClass('active');
        } else {
            jQuery(this).parent().find('li').removeClass('active');
            jQuery(this).addClass('active');
        }
    });

    jQuery('.save_button').click(function() {
        var form = jQuery(this).parents('.form');
        var str = '';
        var k = 1;
        var photo = '';
        var photo_id = 0;
        var video = '';
        if (form.find('input[name="title"]').val() == '') {
            alert(JText_print_product_title);
            return false;
        }
        form.find('.ksenmart-properties-group li').each(function() {
            str += '|' + jQuery(this).attr('property_id') + '|';
        });
        form.find('input[name="properties"]').val(str);
        str = '';
        form.find('.ksenmart-categories li.active').each(function() {
            str += '|' + jQuery(this).attr('cat_id') + '|';
        });
        form.find('input[name="categories"]').val(str);
        str = '';
        form.find('.photo').each(function() {
            photo = jQuery(this).find('img').attr('src');
            photo_id = jQuery(this).find('img').attr('photo_id');
            photo = photo.split('/');
            photo = photo[photo.length - 1];
            str += '|' + photo + ':' + k + ':' + photo_id + '|';
            k++;
        });
        form.find('input[name="photo_order"]').val(str);
        str = '';
        form.find('.video').each(function() {
            if (!jQuery(this).is('.new')) {
                video = jQuery(this).find('.del_video').attr('video_id');
                str += '|' + video + '|';
            }
        });
        form.find('input[name="videos"]').val(str);
        str = '';
        form.find('.related_products_info li').each(function() {
            if (jQuery(this).attr('id') != 0)
                str += '|' + jQuery(this).attr('id') + '|';
        });
        form.find('input[name="related_products"]').val(str);
        if (form.find('.ksenmart-collections li.active').length > 0)
            form.find('input[name="collection"]').val(form.find('.ksenmart-collections li.active').attr('coll_id'));
        if (form.find('.ksenmart-manufacturers li.active').length > 0)
            form.find('input[name="manufacturer"]').val(form.find('.ksenmart-manufacturers li.active').attr('manuf_id'));
        form.find('input[type="text"]').each(function() {
            if (jQuery(this).val() == jQuery(this).attr('title'))
                jQuery(this).val('');
        });
        form.submit();
    });

});

function add_video_to_page(file, type) {
    if (type == 'embeded') {
        var small_file = file.replace(new RegExp('width="[^"]+"', 'g'), 'width="' + production_form_video_width + '"');
        var small_file = small_file.replace(new RegExp('height="[^"]+"', 'g'), 'height="' + production_form_video_height + '"');
        jQuery('.videos').append('<div class="video ' + type + ' new"><div class="video_info"><a class="del_video"></a><a class="show_video"><img src="components/com_ksenmart/images/video.png"></a></div><div class="video_frame"><div class="video_frame_head"><a class="close"></a></div><div class="video_frame_content">' + small_file + '</div></div><input type="hidden" name="new_video[]" value="' + escape(file) + '"><input type="hidden" name="new_video_type[]" value="' + type + '"></div>');
    } else {
        var a_file = '';
        var img = URI_ROOT + 'administrator/components/com_ksenmart/images/video.png';
        if (type == 'local')
            a_file = 'file="' + file + '"';
        else {
            if (file.match(/\?v=(.*)&/)[1]) {
                img = 'http://i2.ytimg.com/vi/' + file.match(/\?v=(.*)&/)[1] + '/hqdefault.jpg';
            }
        }
        jQuery('.videos').append('<div class="video ' + type + ' new"><div class="video_info"><a class="del_video" ' + a_file + '></a><a class="show_video"><img width="120px" height="120px" src="' + img + '"></a></div><div class="video_frame"><div class="video_frame_head"><a class="close"></a></div><div class="video_frame_content"><object type="application/x-shockwave-flash" data="components/com_ksenmart/videos/uppod/uppod.swf" width="' + production_form_video_width + '" height="' + production_form_video_height + '"><param name="bgcolor" value="#ffffff" /><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><param name="movie" value="components/com_ksenmart/videos/uppod/uppod.swf" /><param name="flashvars" value="comment=&amp;m=video&amp;file=' + file + '" /></object></div></div><input type="hidden" name="new_video[]" value="' + file + '"><input type="hidden" name="new_video_type[]" value="' + type + '"></div>');
    }
}

function add_file_to_page(file) {
    var div_class = '';
    if (jQuery('.photos .photo').length == 0)
        div_class = 'main';
    jQuery('.photos').append('<div class="photo new ' + div_class + '"><a class="photo-title">' + JText_photo_title + '</a><br><input type="text" name="new_photo_title[]" class="inputbox_205" value=""><div class="photo_info"><span class="grey-span">' + JText_main_photo + '</span><a class="del_photo" file="' + file + '"></a><img src="' + URI_ROOT + 'administrator/components/com_ksenmart/image/tmp/' + production_form_width + '_' + production_form_height + '/' + file + '"></div><input type="hidden" name="new_photo[]" value="' + file + '"></div>');
    jQuery('.photos').sortable('refresh');
}