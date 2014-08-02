<?php defined('_JEXEC') or die; ?>
    <div class="clearfix panel">
        <div class="pull-left">
            <?php echo KSSystem::loadModules('ks-top-left'); ?>
        </div>
        <div class="pull-right">
            <?php echo KSSystem::loadModules('ks-top-right'); ?>
        </div>
        <div class="row-fluid">
            <?php echo KSSystem::loadModules('ks-top-bottom'); ?>
        </div>
    </div>
<div class="login-screen clearfix">
    <div class="login-icon pull-left">
        <a href="http://ksenmart.ru/" title="Ksenmart" target="_blank">
            <img src="components/com_ksen/assets/images/ksen_logo.png" alt="Ksenmart" />
        </a>
    </div>

    <form id="login_form" action="index.php?option=com_ksen&task=account.setAuth&tmpl=ksenmart" method="POST" class="login_block login-form pull-right">
        <div class="msg"></div>
        <div class="control-group">
            <input type="text" class="login-field" name="login" placeholder="Логин" id="login-name" autofocus="true" required="true" />
            <label class="login-field-icon fui-user" for="login-name">
                <i class="icon-user"></i>
            </label>
        </div>

        <div class="control-group">
            <input type="password" class="login-field" name="password" placeholder="Пароль" id="login-pass" required="true" />
            <label class="login-field-icon fui-lock" for="login-pass">
                <i class="icon-lock"></i>
            </label>
        </div>
        <button type="submit" class="btn btn-other btn-large btn-block">Войти</button>
        <div class="login-link">
            <a class="show-reg_form" href="javascript:void(0);">Регистрация</a>
        </div>
    </form>
    <form id="reg_form" action="index.php?option=com_ksen&task=account.setReg&tmpl=ksenmart" method="POST" class="reg_block login-form pull-right" style="display: none;">
        <div class="msg"></div>
        <div class="control-group">
            <input type="text" name="username" placeholder="Имя пользователя" class="login-field" autofocus="true" required="true" />
            <label class="login-field-icon fui-user" for="login-name">
                <i class="icon-user"></i>
            </label>
        </div>
        <div class="control-group">
            <input type="password" name="passwd" placeholder="Пароль" class="login-field" required="true" />
            <label class="login-field-icon fui-user" for="login-name">
                <i class="icon-lock"></i>
            </label>
        </div>
        <div class="control-group">
            <input type="password" name="confirm" placeholder="Подтверждение пароля" class="login-field" required="true" />
            <label class="login-field-icon fui-user" for="login-name">
                <i class="icon-lock"></i>
            </label>
        </div>
        <div class="control-group">
            <input type="email" name="email" placeholder="E-Mail" class="login-field" required="true" />
            <label class="login-field-icon fui-user" for="login-name">
                <i class="icon-envelope"></i>
            </label>
        </div>
        <div class="control-group clearfix">
            <select class="sel" name="country" required="true" style="width: 284px !important;">
                <?php foreach($this->countries as $key => $country){ ?>
                    <option value="<?php echo $key; ?>"<?php echo $key==182?'selected="true"':''; ?>><?php echo $country; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="control-group clearfix">
            <select name="ptype" class="sel" required="true" style="width: 284px; !important">
                <option value="pperson">Частное лицо</option>
                <option value="pcompany">Компания</option>
            </select>
        </div>

        <div class="hiddenInput"></div>
        <div class="control-group">
            <input type="text" name="person" placeholder="Контактное лицо (Ф.И.О.)" class="login-field" required="true" />
            <label class="login-field-icon fui-user" for="login-name">
                <i class="icon-vcard"></i>
            </label>
        </div>
        <button type="submit" class="btn btn-other btn-large btn-block">Регистрация</button>
        <div class="login-link">
            <a class="show-auth_form" href="javascript:void(0);">Войти</a>
        </div>
    </form>
</div>
<script>
    jQuery(document).ready(function(){
        
        var ls_login_form  = jQuery('.login-screen #login_form');
        var ls_login_block = jQuery('.login-screen .login_block');
        var ls_reg_form    = jQuery('.login-screen #reg_form');
        var ls_reg_block   = jQuery('.login-screen .reg_block');
        
        
        jQuery('.login-screen .show-reg_form').on('click', function(){
            ls_login_form.slideUp();
            ls_reg_form.slideDown();
        });

        jQuery('.login-screen .show-auth_form').on('click', function(){
            ls_reg_form.slideUp();
            ls_login_form.slideDown();
        });
        
        jQuery('.login-screen #login_form_show').on('click', function(){
            jQuery('body').append('<div id="popup-overlay_1"></div>').fadeIn();
            ls_login_block.fadeIn();
        });
        
        jQuery('.login-screen #reg_form_show').on('click', function(){
            jQuery('body').append('<div id="popup-overlay_1"></div>').fadeIn();
            ls_reg_block.fadeIn();
        });
        
        jQuery('.close').on('click', function(){
            jQuery(this).parent().parent().fadeOut();
            jQuery('#popup-overlay_1').fadeOut(400, function(){
                jQuery(this).remove();
            });
        });
        
        ls_login_form.on('submit', function(e){
            e.preventDefault();
            
            var href = jQuery(this).attr('action');
            var data = jQuery(this).serialize();
            
			jQuery.post(
				href,
				data,
				onSuccessLogin
			);
        });
        
        ls_reg_form.find('#cuSel-1').on('change', function(){
            var hdnInp = ls_reg_form.find('.hiddenInput');
            if(jQuery(this).val() == 'pcompany'){
                hdnInp.append('<input type="text" name="name" placeholder="Название компании" class="inputbox" required="true" />').fadeIn(400);
            }else{
                hdnInp.fadeOut(400, function(){jQuery(this).html('');});
            }
        });
        
        ls_reg_form.on('submit', function(e){
            e.preventDefault();
            
            var href = jQuery(this).attr('action');
            var data = jQuery(this).serialize();
            
			jQuery.post(
				href,
				data,
				onSuccessReg
			);
        });

        function onSuccessReg(data){
            if(data == ''){
                window.location.href = 'index.php?option=com_ksenmart';
            }else{
                ls_reg_block.children('.msg').html(data).fadeIn(400);
            }
        }
        
        function onSuccessLogin(data){
            console.log(data);
            if(data == ''){
                window.location.href = 'index.php?option=com_ksenmart';
            }else if(data == 'expirepass'){
                ls_login_block.children('.msg').html('Время действия вашего пароля истекло. Для продолжения работы необходимо установить новый пароль.').fadeIn(400);
                ls_login_form.attr('action', 'index.php?option=com_ksen&task=account.setNewPass&tmpl=ksenmart&extension='+KS.extension);
                var pass_block = ls_login_block.find('.control-group input[type="password"]');
                pass_block.val('');

                console.log(pass_block);
                console.log(pass_block.parents('.control-group'));
                jQuery('<div class="control-group"><input type="password" name="confirm" placeholder="Повторите ваш пароль" class="login-field" required="true"></div>').insertAfter(pass_block.parents('.control-group'));

            }else{
                ls_login_block.children('.msg').html(data).fadeIn(400);
            }
        }
    });
</script>