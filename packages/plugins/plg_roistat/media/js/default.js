jQuery(document).ready(function() {

    var RoiErrorBlock = jQuery('.js-RoiError');
    var Roistat = {
        login: null,
        password: null
    }

    jQuery('.js-RoiUserSave').on('submit', function() {

        var form = jQuery(this);
        form.find('[type="submit"]').addClass('disabled');
        RoiErrorBlock.addClass('hide');

        Roistat.login = form.find('[name="login"]').val();
        Roistat.password = form.find('[name="password"]').val();

        var data = {
            login: Roistat.login,
            password: Roistat.password
        }

        jQuery.ajax({
            url: '?option=com_ksenmart&task=pluginAction&plugin=roistat&action=checkUserRoistat&format=json',
            data: data,
            method: 'POST',
            dataType: 'JSON',
            success: function(response) {
                if (typeof response == 'object' && response.success) {
                    form.find('[type="submit"]').removeClass('disabled');
                    if (response.data.action == 'new') {
                        jQuery('.js-RoiCreateLink').removeClass('hide');
                    } else if (response.data.action == 'auth' && response.data.roistat.status == 'success') {
                        location.reload();
                    } else {
                        RoiErrorBlock.children().hide();
                        RoiErrorBlock.children('.js-RoiError-Roi').html(response.data.roistat.data).show();
                        RoiErrorBlock.removeClass('hide');
                    }
                }
            }
        });

        return false;
    });

    jQuery('.js-RoiCreateLink').on('click', function() {
        var data = {
            login: Roistat.login,
            password: Roistat.password
        }

        jQuery.ajax({
            url: '?option=com_ksenmart&task=pluginAction&plugin=roistat&action=loginRoistat&format=json',
            data: data,
            method: 'POST',
            dataType: 'JSON',
            success: function(response) {
                if (typeof response == 'object' && response.success) {
                    if (response.data.status == 'success') {
                        location.reload();
                    } else {
                        RoiErrorBlock.children().hide();
                        RoiErrorBlock.children('.js-RoiError-Common').show();
                        RoiErrorBlock.removeClass('hide');
                    }
                }
            }
        });
    });
});