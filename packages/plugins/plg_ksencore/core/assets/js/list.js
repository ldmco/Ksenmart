var KMList = function(variables) {

    this.view = '';
    this.object = '';
    this.page = 1;
    this.limit = 30;
    this.limitstart = 0;
    this.total = 0;
    this.order_type = 'ordering';
    this.order_dir = 'asc';
    this.table = '';
    this.item_tpl = 'default_item_form';
    this.no_items_tpl = 'default_no_items';
    this.layout = 'default';
    this.copy_button = false;
    this.delete_button = true;
    this.ctrl = false;
    this.sortable = true;

    this.init = function() {
        var List = this;
        for (var i in variables) List[i] = variables[i];
        List.page = Math.round(List.limitstart / List.limit) + 1;
        List.clearBinds();
        List.setListScrolling();
        List.setListSorting();
        List.setListItemsSortable();
        List.setListButtons();
        List.setItemsActions();
        List.setListDeleting();
        List.loadPagination();
    }

    this.clearBinds = function() {
        jQuery(document).unbind('scroll');
        jQuery('#content .cat thead .sort_field').unbind('click');
        jQuery('#content .top .delete-items').unbind('click');
        jQuery('#content .top .copy-items').unbind('click');
        jQuery('#content .cat .list_item').unbind('mouseover');
        jQuery('#content .cat .list_item').unbind('mouseout');
        jQuery('#content .cat .list_item .changeble').unbind('keypress');
        jQuery('#content .cat .list_item').unbind('click');
        jQuery('#content .cat .list_item input[type="checkbox"]').unbind('click');
        jQuery('#content .cat .list_item .del a').unbind('click');
        jQuery('#content .cat th.del span').unbind('click');
    }

    this.setListScrolling = function() {
        var List = this;
        var class_name = 'scrolling-without-top';
        if (jQuery('#content .top').length > 0) {
            class_name = 'scrolling';
        }
        jQuery('#content .cat thead .stretch').css({
            'width': jQuery('#content .cat thead .stretch').width()
        });
        jQuery(document).on('scroll', function() {
            var h1 = jQuery(window).scrollTop();
            var h2 = jQuery('#content').offset().top;
            if (h1 > h2)
                jQuery('#content').addClass(class_name);
            else
                jQuery('#content').removeClass(class_name);
            return false;
        });
    }

    this.setListSorting = function() {
        var List = this;
        jQuery('#content .cat thead .sort_field[rel="' + List.order_type + '"]').addClass('active');
        jQuery('body').on('click', '#content .cat thead .sort_field', function() {
            var order_type = jQuery(this).attr('rel');
            if (order_type == List.order_type)
                List.order_dir = List.order_dir == 'asc' ? 'desc' : 'asc';
            else
                List.order_dir = 'asc';
            List.order_type = order_type;
            jQuery('#content .cat thead .sort_field').removeClass('active');
            jQuery(this).addClass('active');
            List.refreshList();
        });
    }

    this.setListItemsSortable = function() {
        var List = this;
        if (!List.sortable)
            return false;
        var order_dir = List.order_dir;

        jQuery('#content .cat tbody .stretch').css({
            'width': jQuery('#content .cat thead .stretch').width()
        });
        var items = jQuery('#content .cat tbody').sortable({
            distance: 10,
            items: '.list_item',
            beforeStop: function(event, ui) {
                if (ui.item.find('.id').length == 0) {
                    ui.item.remove();
                    if ((event.pageX > jQuery('.drop').offset().left && event.pageX < (jQuery('.drop').offset().left + jQuery('.drop').width() + 20)) && (event.pageY > jQuery('.drop').offset().top && event.pageY < (jQuery('.drop').offset().top + jQuery('.drop').height() + 40))) {
                        var item_id = +ui.item.find('img').attr('rel');
                        if (jQuery('.drop div').length == 0)
                            jQuery('.drop').html('');
                        if (jQuery('.drop div[rel="' + item_id + '"]').length == 0) {
                            var html = '';
                            html += '<div rel="' + item_id + '">';
                            html += '<a class="del"></a>';
                            html += ui.item.html();
                            html += ' <input type="hidden" name="ids[]" value="' + item_id + '">';
                            html += '</div>';
                            jQuery('.drop').append(html);
                        }
                    }
                }
            },
            stop: function(event, ui) {
                var items = {}, data = {}, ordering = [],
                    ids = [];
                data['task'] = 'sort_list_items';
                data['table'] = List.table;
                jQuery('#content .cat tbody tr.list_item').each(function() {
                    ids.push(jQuery(this).find('input.id').val());
                    ordering.push(parseInt(jQuery(this).find('input.ordering').val()));
                });
                if (order_dir == 'asc')
                    ordering.sort(function(a, b) {
                        return a - b;
                    });
                else
                    ordering.sort(function(a, b) {
                        return b - a;
                    });

                for (var k = 0; k < ordering.length; k++) {
                    if (k > 0 && ordering[k] <= ordering[k - 1])
                        ordering[k] = ordering[k - 1] + 1;
                    items[ids[k]] = ordering[k];
                }
                data['items'] = items;
                jQuery.ajax({
                    url: 'index.php?option='+KS.option,
                    data: data,
                    dataType: 'json',
                    success: function(responce) {
                        if (responce.errors == 0)
                            List.refreshList();
                        else
                            KMShowMessage(responce.message.join('<br>'));
                    }
                });
            }
        });
    }

    this.setListButtons = function() {
        var List = this;
        if (List.delete_button) {
            jQuery('body').on('click', '#content .top .delete-items', function() {
                List.deleteSelectedItems();
                return false;
            });
        }
        if (List.copy_button) {
            jQuery('body').on('click', '#content .top .copy-items', function() {
                List.copySelectedItems();
                return false;
            });
        }
    }

    this.setItemsActions = function() {
        var List = this;
        jQuery(document).keydown(function(e) {
            if (e.which == 17)
                List.ctrl = true;
        });
        jQuery(document).keyup(function(e) {
            if (e.which == 17)
                List.ctrl = false;
        });
        jQuery('body').on('mouseover', '#content .cat .list_item', function() {
            jQuery(this).find('.name p').css('visibility', 'visible');
            jQuery(this).find('.changeble span').hide();
            jQuery(this).find('.changeble p').show();
        });
        jQuery('body').on('mouseout', '#content .cat .list_item', function() {
            jQuery(this).find('.name p').css('visibility', 'hidden');
            jQuery(this).find('.changeble p').hide();
            jQuery(this).find('.changeble span').show();
            var data = List.getItemData(jQuery(this));
            if (data)
                List.saveItem(data);
        });
        jQuery('body').on('keypress', '#content .cat .list_item .changeble', function(e) {
            if (e.keyCode == 13) {
                var data = List.getItemData(jQuery(this).parents('.list_item'));
                if (data)
                    List.saveItem(data);
            }
        });
        jQuery('body').on('click', '#content .cat .list_item', function() {
            if (jQuery(this).is('.active'))
                jQuery(this).removeClass('active');
            else {
                if (!List.ctrl)
                    jQuery('#content .cat .list_item').removeClass('active');
                jQuery(this).addClass('active');
            }
            if (jQuery('#content .cat tr.active').length > 0)
                jQuery('#content .top .button').show();
            else
                jQuery('#content .top .button').hide();
        });
        jQuery('body').on('click', '#content .cat .list_item input[type="checkbox"]', function() {
            var data = {};
            var value = jQuery(this).is(':checked') ? 1 : 0;
            if (jQuery(this).is('.status')) {
                if (value == 1)
                    jQuery(this).parents('.list_item').removeClass('disabled');
                else
                    jQuery(this).parents('.list_item').addClass('disabled');
            }
            data[jQuery(this).attr('name')] = value;
            List.saveItem(data);
        });
    }

    this.setListDeleting = function() {
        var List = this;
        jQuery('body').on('click', '#content .cat .list_item .del a', function() {
            if (confirm(Joomla.JText._('KSM_DELETE_CONFIRMATION'))) {
                var data = {
                    "items": []
                };
                var item = jQuery(this).parents('.list_item');
                data['items'].push(item.find('.id').val());
                List.deleteListItems(data);
                item.remove();
                List.refreshList();
            }
            return false;
        });
        jQuery('body').on('click', '#content .cat th.del span', function() {
            if (confirm(Joomla.JText._('KSM_DELETE_CONFIRMATION'))) {
                var data = {
                    "items": []
                };
                jQuery('#content .cat .list_item').each(function() {
                    data['items'].push(jQuery(this).find('.id').val());
                });
                List.deleteListItems(data);
                jQuery('#content .cat .list_item').remove();
                List.refreshList();
            }
            return false;
        });
    }

    this.loadPagination = function() {
        var List = this;
        var html = '';
        if (List.total > 0) {
            var pages = Math.ceil(List.total / List.limit);
            var showed_from = (List.page - 1) * List.limit + 1;
            var showed_to = List.total > List.page * List.limit ? List.page * List.limit : List.total;
            for (var k = 1; k <= pages; k++) {
                html += '<a href="#" class="' + (k == List.page ? 'current' : '') + '" onclick="' + List.object + '.loadListPage(' + k + ');return false;">' + k + '</a>';
            }
            html += '<p>' + Joomla.JText._('KSM_PAGI_SHOWED') + ' ' + showed_from + ' — ' + showed_to + ' ' + Joomla.JText._('KSM_PAGI_SHOWED_FROM') + ' ' + List.total + '</p>';
        }
        jQuery('#content .pagi').html(html);
    }

    this.loadListPage = function(page) {
        var List = this;
        List.page = page;
        jQuery('#content .cat tbody').empty();
        List.loadListItems((List.page - 1) * List.limit, List.limit);
        List.loadPagination();
        List.scrollTopList();
    }

    this.refreshList = function() {
        var List = this;
        jQuery('#content .cat tbody').empty();
        List.loadListItems((List.page - 1) * List.limit, List.limit);
        List.loadPagination();
    }

    this.loadListItems = function(limitstart, limit) {
        var List = this;
        jQuery('#content .top .button').hide();
        jQuery('#content .cat tbody .no_list_items').remove();
        var items = List.getListItems(limitstart, limit);
        if (items.no_items) {
            if (jQuery('#content .cat tbody tr.list_item').length == 0) {
                if (List.total != 0) {
                    List.loadListPage(List.page - 1);
                    return;
                } else
                    jQuery('#content .cat tbody').append(items.html);
            }
        } else {
            jQuery('#content .cat tbody').append(items.html);
            List.setListItemsSortable();
        }
    }

    this.getListItems = function(limitstart, limit) {
        var List = this;
        var data = {};
        var items = {};
        var filters = jQuery('#list-filters').serializeArray();
        data['view'] = List.view;
        data['item_tpl'] = List.item_tpl;
        data['no_items_tpl'] = List.no_items_tpl;
        data['layout'] = List.layout;
        data['task'] = 'get_list_items';
        data['limit'] = limit;
        data['limitstart'] = limitstart;
        data['order_type'] = List.order_type;
        data['order_dir'] = List.order_dir;
        for (var k = 0; k < filters.length; k++) {
            if (filters[k].name.indexOf('[]') != -1) {
                if (data[filters[k].name] === undefined)
                    data[filters[k].name] = [];
                data[filters[k].name].push(filters[k].value);
            } else
                data[filters[k].name] = filters[k].value;
        }
        jQuery.ajax({
            url: 'index.php?option='+KS.option+'&tmpl=ksenmart',
            data: data,
            dataType: 'json',
            async: false,
            success: function(responce) {
                List.total = responce.total;
                items = responce;
            }
        });
        return items;
    }

    this.deleteSelectedItems = function() {
        var List = this;
        if (confirm(Joomla.JText._('KSM_DELETE_CONFIRMATION'))) {
            var data = {
                "items": []
            };
            jQuery('#content .cat tr.active').each(function() {
                data['items'].push(jQuery(this).find('.id').val());
            });
            List.deleteListItems(data);
            jQuery('#content .cat tr.active').remove();
            List.refreshList();
        }
    }

    this.copySelectedItems = function() {
        var List = this;
        if (confirm(Joomla.JText._('KSM_COPY_CONFIRMATION'))) {
            var data = {
                "items": []
            };
            jQuery('#content .cat tr.active').each(function() {
                data['items'].push(jQuery(this).find('.id').val());
            });
            List.copyListItems(data);
            jQuery('#content .cat tr.active').remove();
            List.refreshList();
        }
    }

    this.getItemData = function(item) {
        var List = this;
        var data = {};
        var count = 0;
        jQuery(item).find('.changeble').each(function() {
            var field_name = jQuery(this).find('input').attr('name');
            var field_value = parseFloat(jQuery(this).find('input').val());
            var old_value = parseFloat(jQuery(this).find('span').text());
            if (old_value != field_value) {
                jQuery(this).find('span').text(field_value);
                data[field_name] = field_value;
                count++;
            }
        });
        if (count == 0)
            return false;
        return data;
    }

    this.saveItem = function(data) {
        var List = this;
        data['task'] = 'save_list_items';
        data['table'] = List.table;
        jQuery.ajax({
            url: 'index.php?option='+KS.option,
            data: data,
            dataType: 'json',
            async: false,
            success: function(responce) {
                if (responce.errors == 0) {
                    var flag = false;
                    for (var key in data) {
                        if (key.indexOf('[' + List.order_type + ']') != -1) {
                            flag = true;
                            break;
                        }
                    }
                    if (flag) {
                        List.refreshList();
                    }
                } else {
                    KMShowMessage(responce.message.join('<br>'));
                }
            }
        });
    }

    this.deleteListItems = function(data) {
        var List = this;
        data['task'] = 'delete_list_items';
        data['view'] = List.view;
        jQuery.ajax({
            url: 'index.php?option='+KS.option,
            data: data,
            dataType: 'json',
            async: false,
            success: function(responce) {
                if (responce.errors != 0) {
                    KMShowMessage(responce.message.join('<br>'));
                }
            }
        });
    }

    this.copyListItems = function(data) {
        var List = this;
        data['task'] = 'copy_list_items';
        data['view'] = List.view;
        jQuery.ajax({
            url: 'index.php?option='+KS.option,
            data: data,
            dataType: 'json',
            async: false,
            success: function(responce) {
                if (responce.errors != 0) {
                    KMShowMessage(responce.message.join('<br>'));
                }
            }
        });
    }

    this.scrollTopList = function() {
        jQuery('body,html').animate({
            'scrollTop': jQuery('#cat').offset().top
        }, 500);
    }

    this.init();

}

    function clearKMListBinds() {
        jQuery(document).unbind('scroll');
        jQuery('#content .cat thead .sort_field').unbind('click');
        jQuery('#content .top .delete-items').unbind('click');
        jQuery('#content .top .copy-items').unbind('click');
        jQuery('#content .cat .list_item').unbind('mouseover');
        jQuery('#content .cat .list_item').unbind('mouseout');
        jQuery('#content .cat .list_item .changeble').unbind('keypress');
        jQuery('#content .cat .list_item').unbind('click');
        jQuery('#content .cat .list_item input[type="checkbox"]').unbind('click');
        jQuery('#content .cat .list_item .del a').unbind('click');
        jQuery('#content .cat th.del span').unbind('click');
    }