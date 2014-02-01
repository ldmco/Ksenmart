<ul class="inline account_login_menu">
    <li>
        <a href="javascript:void(0);" title="Вход" id="login_form_show">Вход</a>
    </li>
    <li> | </li>
    <li>
        <a href="javascript:void(0);" title="Регистрация" id="reg_form_show">Регистрация</a>
    </li>
</ul>
<form id="login_form" action="index.php?option=com_ksenmart&task=account.setAuth&tmpl=ksenmart" method="POST">
    <div class="login_block form">
        <div class="msg"></div>
        <div class="row">
            <input type="text" name="login" placeholder="Введите ваш логин" class="inputbox" autofocus="true" required="true" />
        </div>
        <div class="row">
            <input type="password" name="password" placeholder="Введите ваш пароль" class="inputbox" required="true" />
        </div>
        <div class="row clearfix">
            <input type="submit" value="Войти" class="btn save pull-right" />
            <!--<a href="index.php?option=com_ksenmart&task=account.setAuth&tmpl=ksenmart" data-callback="onSuccessLogin" class="btn save pull-right" title="Войти">Войти</a>-->
            <a href="javascript:void(0);" class="btn close pull-right">Отменить</a>
        </div>
    </div>
</form>
<form class="form" id="reg_form" action="index.php?option=com_ksenmart&task=account.setReg&tmpl=ksenmart" method="POST">
    <div class="reg_block form">
        <div class="msg"></div>
        <div class="row">
            <input type="text" name="username" placeholder="Имя пользователя" class="inputbox" autofocus="true" required="true" />
        </div>
        <div class="row">
            <input type="password" name="passwd" placeholder="Пароль" class="inputbox" required="true" />
        </div>
        <div class="row">
            <input type="password" name="confirm" placeholder="Подтверждение пароля" class="inputbox" required="true" />
        </div>
        <div class="row">
            <input type="email" name="email" placeholder="E-Mail" class="inputbox" required="true" />
        </div>
        <div class="row clearfix">
            <select class="sel" name="country" required="true">
                <?php foreach($countries as $key => $country){ ?>
                    <option value="<?php echo $key; ?>"<?php echo $key==182?'selected="true"':''; ?>><?php echo $country; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="row clearfix">
            <select name="ptype" class="sel" required="true">
                <option value="pperson">Частное лицо</option>
                <option value="pcompany">Компания</option>
            </select>
        </div>
        <div class="row hiddenInput"></div>
        <div class="row">
            <input type="text" name="person" placeholder="Контактное лицо (Ф.И.О.)" class="inputbox" required="true" />
        </div>
        <div class="row clearfix">
            <input type="submit" value="Регистрация" class="btn save_reg pull-right" />
            <a href="javascript:void(0);" class="btn close pull-right">Отменить</a>
        </div>
    </div>
</form>
<script>
    jQuery(document).ready(function(){
        
        var login_form  = jQuery('#login_form');
        var login_block = jQuery('.login_block');
        var reg_form    = jQuery('#reg_form');
        var reg_block   = jQuery('.reg_block');
        
        
        //jQuery('a.save').postSend({form: '#login_form'})
        
        jQuery('#login_form_show').on('click', function(){
            jQuery('body').append('<div id="popup-overlay_1"></div>').fadeIn();
            login_block.fadeIn();
        });
        
        jQuery('#reg_form_show').on('click', function(){
            jQuery('body').append('<div id="popup-overlay_1"></div>').fadeIn();
            reg_block.fadeIn();
        });
        
        jQuery('.close').on('click', function(){
            jQuery(this).parent().parent().fadeOut();
            console.log(jQuery('#popup-overlay_1'));
            jQuery('#popup-overlay_1').fadeOut(400, function(){
                jQuery(this).remove();
            });
        });
        
        login_form.on('submit', function(e){
            e.preventDefault();
            
            var href = jQuery(this).attr('action');
            var data = jQuery(this).serialize();
            
			$.post(
				href,
				data,
				onSuccessLogin
			);
        });
        
        reg_form.find('#cuSel-1').on('change', function(){
            var hdnInp = reg_form.find('.hiddenInput');
            if(jQuery(this).val() == 'pcompany'){
                hdnInp.append('<input type="text" name="name" placeholder="Название компании" class="inputbox" required="true" />').fadeIn(400);
            }else{
                hdnInp.fadeOut(400, function(){jQuery(this).html('');});
            }
        });
        
        reg_form.on('submit', function(e){
            NProgress.start();
            e.preventDefault();
            
            var href = jQuery(this).attr('action');
            var data = jQuery(this).serialize();
            
			$.ajax({
				type: 'POST',
                url: href,
				data: data,
                beforeSend: function(){
                    NProgress.inc();
                },
				success: onSuccessReg
			});
        });

        function onSuccessReg(data){
            NProgress.done();
            if(data == ''){
                location.reload();
            }else{
                reg_block.children('.msg').html(data).fadeIn(400);
            }
        }
        
        function onSuccessLogin(data){
            console.log(data);
            if(data == ''){
                location.reload();
            }else if(data == 'expirepass'){
                login_block.children('.msg').html('Время действия вашего пароля истекло. Для продолжения работы необходимо установить новый пароль.').fadeIn(400);
                login_form.attr('action', 'index.php?option=com_ksenmart&task=account.setNewPass&tmpl=ksenmart');
                var pass_block = login_block.find('.row input[type="password"]');
                pass_block.val('');
                jQuery('<div class="row"><input type="password" name="confirm" placeholder="Повторите ваш пароль" class="inputbox" required="true"></div>').insertAfter(pass_block.parents('.row'));
            }else{
                login_block.children('.msg').html(data).fadeIn(400);
            }
        }
    });
</script>