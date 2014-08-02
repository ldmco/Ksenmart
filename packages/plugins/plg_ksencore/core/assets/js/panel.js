var current_body_width, current_body_height, last_body_width, last_body_height, selected_widget = 0;

jQuery(function()
{
    metro_ui.init();

    jQuery('#metro-ui').mousewheel(function(event, delta)
    {
        this.scrollLeft = this.scrollLeft - (delta * 30);
        event.preventDefault();
    });

    jQuery(window).on('resize', function()
    {
        current_body_width  = jQuery('body').width();
        current_body_height = jQuery(window).height()-metro_ui.cut_height;

        // защита от повторонго срабатывания события resize
        if (current_body_width == last_body_width && current_body_height == last_body_height)
        {
            return;
        }

        // если изменилась высота, то нужна перестройка интерфейса
        if (current_body_height != last_body_height)
        {
        	clog('resize');
            var max_count_lines_by_height = parseInt(current_body_height / metro_ui.height_one_widget);
            metro_ui.max_block_height     = max_count_lines_by_height * metro_ui.height_one_widget;
            metro_ui.max_block_deposit    = metro_ui.max_block_height / metro_ui.height_one_widget;

            jQuery('.metro-ui-inner').height(metro_ui.max_block_height);

            var use_function = (current_body_height > last_body_height) ? 'checkDeficit' : 'checkExcess';
            var block        = jQuery('.widgets:first');

            while (block.length)
            {
                if (!block.hasClass('widgets'))
                {
                    block = block.next();
                    continue;
                }

                metro_ui[use_function](block, 1);
                block = block.next();
            }

            metro_ui.init_sortable();
            metro_ui.init_resizable();

            metro_ui.refresh_metro_ui_width();
            metro_ui.updateMargin();

            metro_ui.max_metro_width = metro_ui.metro_inner_block.width();
        }
        else // изменилась только ширина
        {
            metro_ui.refresh_metro_ui_width();
        }

        last_body_width  = current_body_width;
        last_body_height = current_body_height;
    });

	/*
    jQuery('.widgets a').click(function()
    {
        selected_widget = jQuery(this).attr('id');

        if (jQuery('#edit-panel').is(':hidden'))
        {
            jQuery('#edit-panel').show();
        }

        return false;
    });
	*/

    jQuery('#remove_widget').click(function()
    {
        if (!selected_widget)
        {
            return;
        }

        jQuery('.widgets a[id="' + selected_widget + '"]').remove();
        jQuery('#edit-panel').hide();

        return false;
    });

	/*
    jQuery('body').click(function()
    {
        if (jQuery('#edit-panel').is(':visible'))
        {
            jQuery('#edit-panel').hide();
        }
    });
	*/
});

var metro_ui = {
    metro_block: null,
    metro_inner_block: null,
    max_block_height: 0,
    max_metro_width: 0,
    current_metro_width: 0,
	cut_height: 250,
    max_block_deposit: 0,
    refresh_plugins: 0,
    right_metro_width: 0,
    height_one_widget: 160, // высота виджета,
    block_sender: null, // блок, из которого изначально был взят виджет
    block_hover: null, // блок, над которым сейчас находится виджет
    marginHeight: 0,
    scrollHeight: 15,
    active_blocks: [null, null], // набор блоков для отслеживания
    init: function ()
    {
        last_body_width  = jQuery('body').width();
        last_body_height = jQuery(window).height()-metro_ui.cut_height;

        metro_ui.metro_block = jQuery('#metro-ui');

        var max_count_lines_by_height = parseInt(last_body_height / metro_ui.height_one_widget);
        metro_ui.max_block_height     = max_count_lines_by_height * metro_ui.height_one_widget;
        metro_ui.max_block_deposit    = metro_ui.max_block_height / metro_ui.height_one_widget;

        metro_ui.metro_inner_block = jQuery('.metro-ui-inner');
        metro_ui.metro_inner_block.height(metro_ui.max_block_height);

		//Для ресайза
		var use_function = (last_body_height > metro_ui.metro_block.height()) ? 'checkDeficit' : 'checkExcess';
		var block        = jQuery('.widgets:first');

		while (block.length)
		{
			if (!block.hasClass('widgets'))
			{
				block = block.next();
				continue;
			}

			metro_ui[use_function](block, 1);
			block = block.next();
		}		
		
        metro_ui.init_sortable();
        metro_ui.init_droppable();
        metro_ui.init_resizable();		

        metro_ui.max_metro_width = metro_ui.metro_inner_block.width();

        metro_ui.refresh_metro_ui_width();
        metro_ui.updateMargin();
    },
    // обновляет высоту промежутка снизу между блоками с виджетами и полосой скролла
    updateMargin: function ()
    {
        this.marginHeight = jQuery(window).height() - metro_ui.cut_height - metro_ui.scrollHeight - this.max_block_height;

        if (this.marginHeight < 0)
        {
            return;
        }

        if (jQuery('#metro_margin').length)
        {
            jQuery('#metro_margin').height(this.marginHeight);
        }
        else
        {
            this.metro_inner_block.after('<div id="metro_margin" style="height:' + this.marginHeight + 'px;"></div>');
        }
    },
    refresh_metro_ui_width: function ()
    {
        this.max_metro_width = this.metro_block.width();
        this.current_metro_width = 0;

        jQuery('.widgets').each(function()
        {
            metro_ui.current_metro_width += jQuery(this).outerWidth(true);
        });

        jQuery('.margins').each(function()
        {
            metro_ui.current_metro_width += jQuery(this).outerWidth(true);
        });

        this.right_metro_width = this.current_metro_width > this.max_metro_width ? this.current_metro_width : 'auto';
        this.metro_inner_block.width(this.right_metro_width);
    },
    getWidgetClass: function (widget)
    {
        var wclass = 'standart';

        if (widget.hasClass('double'))
        {
            wclass = 'double';
        }
        else if (widget.hasClass('half'))
        {
            wclass = 'half';
        }

        return wclass;
    },
    getWidgetDeposit: function (wclass)
    {
        var deposit = 0;

        switch (wclass)
        {
            case 'double':
                deposit = 1.00;
            break;

            case 'standart':
                deposit = 0.50;
            break;

            case 'half':
                deposit = 0.25;
            break;
        }

        return deposit;
    },
    witoutEmty: function (block)
    {
        if (!block)
        {
            block = this.block_sender;
        }

        // если в блоке больше нет виджетов
        if (!block.find('a').length)
        {
            left  = block.prev();
            right = block.next();

            // сносим пустышку
            block.remove();

            if ((!left || left.hasClass('margins')) && right && right.hasClass('margins'))
            {
                // сносим заодно и ненужный разделитель
                right.remove();
            }
        }
    },
    init_sortable: function ()
    {
        jQuery('.widgets').sortable(
        {
            connectWith: '.widgets',
            helper: 'original',
            tolerance: 'intersect',
            placeholder: false,
            forcePlaceholderSize: false,
            start: function (event, ui)
            {
                // запомним первоначальный источник виджета
                metro_ui.block_sender = jQuery(event.target);

                // заносим в набор для отслеживания
                metro_ui.active_blocks[0] = metro_ui.block_sender.get(0);

                // уменьшим все виджеты, перетаскиваемый - увеличим
                jQuery('.widgets a').addClass('no-moved');
                jQuery('.margins').addClass('moved');
                ui.item.removeClass('no-moved').addClass('moved');

                // ну и обновим ширину метро-уи (:
                metro_ui.refresh_metro_ui_width();
            },
            change: function (event, ui) // в моменты смены вакантной позиции
            {
                metro_ui.checkDeficit(jQuery(metro_ui.active_blocks[0]));
                metro_ui.checkExcess(jQuery(metro_ui.active_blocks[1]));
            },
            over: function (event, ui) // виджет находится над блоком
            {
                // нас интересуют только 2 последних активных блока с виджетами
                if (metro_ui.active_blocks.length == 2)
                {
                    // сносим неинтересный 0 блок
                    metro_ui.active_blocks.slice(0, 1);
                }

                // заносим в набор для отслеживания
                metro_ui.active_blocks[1] = jQuery(event.target).get(0);
            },
            stop: function(event, ui)
            {
                // возвращаем размеры виджетов в исходные
                jQuery('.widgets a').removeClass('moved').removeClass('no-moved');
                jQuery('.margins').removeClass('moved').removeClass('ui-droppable');
                //

                if (metro_ui.refresh_plugins)
                {
                    metro_ui.init_sortable();
                    metro_ui.init_droppable();
                    metro_ui.init_resizable();

                    metro_ui.refresh_plugins = 0;
                }

                metro_ui.witoutEmty();
                metro_ui.refresh_metro_ui_width();

                metro_ui.block_sender  = null;
                metro_ui.active_blocks = [null, null];
				
				metro_ui.saveUserConfig();
            }
        }).disableSelection();
    },
    init_droppable: function ()
    {
        // чтобы разделители правильно "ловили" виджеты, делаем их droppable
        jQuery('.margins').droppable(
        {
            tolerance: 'intersect',
            // при попадании виджета в область разделителя
            over: function(event, ui)
            {
                metro_ui.block_hover = jQuery(event.target);
                metro_ui.block_hover.addClass('margin-active');
            },
            // когда виджет покидает область разделителя
            out: function(event, ui)
            {
                metro_ui.block_hover.removeClass('margin-active');
            },
            // при отпускании виджета над разделителем
            drop: function(event, ui)
            {
                var margin = '<div class="margins"></div>';
                var widget = ui.draggable;
                widget.removeAttr('style').removeClass('ui-sortable-helper');

                metro_ui.block_hover.replaceWith(margin + '<div class="widgets">' + widget.get(0).outerHTML + '</div>' + margin);

                ui.draggable.remove();
                metro_ui.witoutEmty();

                metro_ui.refresh_plugins = 1;
            }
        }).disableSelection();
    },
    init_resizable: function (resized)
    {
        jQuery('.widgets a').resizable(
        {
            helper: 'metroui-widget-resizable-helper',
            maxHeight: 159,
            maxWidth: 319,
            minHeight: 159,
            minWidth: 159,
            stop: function (event, ui)
            {
                var current_widget_class = metro_ui.getWidgetClass(jQuery(this));
                var future_widget_class  = (ui.size.width > 240) ? 'double' : 'standart';

                jQuery(this).removeAttr('style');

                if (future_widget_class == current_widget_class)
                {
                    return;
                }

                jQuery(this).removeClass(current_widget_class).addClass(future_widget_class);

                if (future_widget_class == 'double')
                {
                    // виджет увеличился и в блоке возможен перебор
                    metro_ui.checkExcess(jQuery(this).parent());
                }
                else
                {
                    // виджет уменьшился и в блоке возможен недобор
                    metro_ui.checkDeficit(jQuery(this).parent().prev());
                    metro_ui.checkDeficit(jQuery(this).parent());
                }

                if (metro_ui.refresh_plugins)
                {
                    metro_ui.init_sortable();
                    metro_ui.init_droppable();

                    metro_ui.refresh_plugins = 0;
                }

                metro_ui.init_resizable();
                metro_ui.refresh_metro_ui_width();
				
				metro_ui.saveUserConfig();
            }
        }).disableSelection();
    },
    checkDeficit: function (block) // проверяет блок на недобор виджетов
    {
        var block_donor = block.next();

        // если есть, откуда забрать при необходимости
        if (block_donor.hasClass('linked'))
        {
            // подсчитываем депозит для блока
            var block_deposit  = 0;
            var widget_deposit = 0;
            var widget_class   = '';

            // высчитаем депозит блока, подозреваемого на недостачу
            block.find('a').each(function()
            {
                // helper нам не нужен
                if (jQuery(this).hasClass('ui-sortable-helper'))
                {
                    return;
                }

                widget_class   = metro_ui.getWidgetClass(jQuery(this));
                widget_deposit = metro_ui.getWidgetDeposit(widget_class);

                if (jQuery(this).next() && widget_class != 'double')
                {
                    next_widget_deposit = metro_ui.getWidgetDeposit(metro_ui.getWidgetClass(jQuery(this).next()));

                    if (block_deposit - parseInt(block_deposit) == 0) // первый блок в новой строке
                    {
                        widget_deposit = next_widget_deposit > widget_deposit ? next_widget_deposit : widget_deposit;
                    }
                    else if (widget_class == 'half' && next_widget_deposit > widget_deposit) // второй блок в строке и тип виджета half
                    {
                        widget_deposit *= 2;
                    }
                }

                block_deposit += widget_deposit;
            });

            // место, которое можно забить :)
            free_deposit = metro_ui.max_block_deposit - block_deposit;

            if (free_deposit)
            {
                // пытаемся восполнить недостаток виджетов из блока справа
                while (free_deposit)
                {
                    desired_widget = block_donor.find('a:first');
                    desired_widget_deposit = metro_ui.getWidgetDeposit(metro_ui.getWidgetClass(desired_widget));

                    if (desired_widget_deposit > free_deposit)
                    {
                        break;
                    }

                    block.append(desired_widget.remove());
                    free_deposit -= desired_widget_deposit;
                }

                // донор может отдать всё :)
                metro_ui.witoutEmty(block_donor);
            }
        }
    },
    checkExcess: function (block) // проверяет блок на перебор виджетов
    {
        var offset = 4; // плагин ресайза даёт + 4 к scrollHeight

        if (block.prop('scrollHeight') - offset > metro_ui.max_block_height)
        {
            // обрабатываем перебор виджетов
            var block_recipient = block.next(); // блок, куда будем сваливать лишнее

            if (!block_recipient.hasClass('linked'))
            {
                // если справа нет сопряженного блока, то создаём его
                block.after('<div class="widgets linked ui-sortable"></div>');
                block_recipient = block.next();
            }

            // собираем лишние виджеты
            while (block.prop('scrollHeight') - offset > metro_ui.max_block_height)
            {
                excess_widget = block.find('a:last');
                block_recipient.prepend(excess_widget.remove());
            }

            metro_ui.refresh_plugins = 1;
        }
    },
	saveUserConfig: function()
	{
		var data={};
		var groups={};
		var group={};
		var k=0;
		var i=0;

		jQuery('.widgets').each(function(){
			var block = jQuery(this);
			if (!block.hasClass('linked'))
			{
				if (i>0)
				{
					groups[k]=group;
					group={};
					k++;
					i=0;
				}
			}
			block.find('a').each(function(){
				var widget = jQuery(this);
				var widget_size='standart';
				if (widget.hasClass('double'))
					widget_size='double';
				group[widget.attr('id')]=widget_size;
			});
			i++;
		});
		groups[k]=group;
		
		data['groups']=groups;
		data['task']='panel.save_widgets_users_config';
		jQuery.ajax({
			url:'index.php?option=' + KS.extension,
			data:data,
			dataType:'json',
			success:function(responce){
				if (responce.errors != 0)
				{
					KMShowMessage(responce.message.join('<br>'));
				}			
			}
		});
	}
};

function clog(val)
{
    console.log(val);
}