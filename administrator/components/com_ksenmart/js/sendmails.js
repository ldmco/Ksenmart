jQuery(document).ready(function() {

    jQuery('.ksenmart-sendmail_templates li label').on('click', function() {
        if (jQuery(this).parent().is('.active')) {
            jQuery(this).parent().removeClass('active');
            ShowLoading();
            jQuery.ajax({
                url: 'index.php?option=com_ksenmart&view=sendmails&layout=default_text&tmpl=ksenmart',
                success: function(data) {
                    jQuery('#content').html(data);
                    HideLoading();
                }
            });
        } else {
            jQuery('.ksenmart-sendmail_templates li').removeClass('active');
            jQuery(this).parent().addClass('active');
            id = jQuery(this).parent().attr('sendmail_templates_id');
            window.location.href = 'index.php?option=com_ksenmart&view=sendmails&id=' + id;
        }
    });

    jQuery('.ksenmart-user_groups li span').on('click', function() {
        if (jQuery(this).parent().is('.active')) {
            jQuery(this).parent().removeClass('active');
        } else {
            jQuery(this).parent().addClass('active');
        }
    });

    jQuery('.ksenmart-sendmail_templates label').on('mouseover', function() {
        jQuery(this).find('p').css('visibility', 'visible');
    });

    jQuery('.ksenmart-sendmail_templates span').on('mouseout', function() {
        jQuery(this).find('p').css('visibility', 'hidden');
    });

    jQuery('.ksenmart-sendmail_templates .delete').on('click', function() {
        if (confirm(JText_confirm_del_template)) {
            var template = jQuery(this).parent().parent().parent();
            var url = 'index.php?option=com_ksenmart&view=sendmails&task=sendmails.del_template&template=' + template.attr('sendmail_templates_id');
            ShowLoading();
            jQuery.ajax({
                url: url,
                success: function() {
                    template.remove();
                    HideLoading();
                }
            });
            return false;
        }
        return false;
    });

    jQuery('.ksenmart-sendmail_templates .add').on('click', function() {
        var width = 840;
        var height = 510;
        openPopupWindow('index.php?option=com_ksenmart&view=sendmails&layout=template&tmpl=component', width, height);
        return false;
    });

    jQuery('.ksenmart-sendmail_templates .edit').on('click', function() {
        var id = jQuery(this).parent().parent().parent().attr('sendmail_templates_id');
        var width = 840;
        var height = 510;
        openPopupWindow('index.php?option=com_ksenmart&view=sendmails&layout=template&id=' + id + '&tmpl=component', width, height);
        return false;
    });

    jQuery('.form .saves-green').click(function() {
        var form = jQuery(this).parents('.form');
        var str = '';
        if (form.find('input[name="title"]').val() == '') {
            alert(JText_print_mail_subject);
            return false;
        }
        form.find('.ksenmart-user_groups li.active').each(function() {
            str += '|' + jQuery(this).attr('user_group_id') + '|';
        });
        if (str == '') {
            alert(JText_choose_user_group);
            return false;
        } else
            form.find('input[name="groups"]').val(str);
        form.submit();
    });

});

function renewAll() {
    renewTemplates();
}

function renewTemplates() {
    var selected = '';
    jQuery('.ksenmart-sendmail_templates li.active').each(function() {
        selected += '|' + jQuery(this).attr('sendmail_templates_id') + '|';
    });
    var url = 'index.php?option=com_ksenmart&task=ajax.get_module&module=sendmails_templates&params=selected:array:' + selected + '&tmpl=ksenmart';
    ShowLoading();
    jQuery.ajax({
        url: url,
        success: function(data) {
            jQuery('.ksenmart-sendmail_templates').replaceWith(data);
            HideLoading();
        }
    });
}