<ul class="inline account_login_menu">
    <li>
        <a href="javascript:void(0);" title="Вход" id="login_form_show">Вход</a>
    </li>
    <li> | </li>
    <li>
        <a href="javascript:void(0);" title="Регистрация" id="reg_form">Регистрация</a>
    </li>
</ul>
<form id="login_form" action="index.php?option=com_ksenmart&task=account.setAuth&tmpl=ksenmart" method="POST">
    <div class="login_block form">
        <div class="msg"></div>
        <div class="row">
            <input type="text" name="login" placeholder="Введите ваш логин" class="inputbox" autofocus="true" />
        </div>
        <div class="row">
            <input type="password" name="password" placeholder="Введите ваш пароль" class="inputbox" />
        </div>
        <div class="row clearfix">
            <input type="submit" value="Войти" class="btn save pull-right" />
            <!--<a href="index.php?option=com_ksenmart&task=account.setAuth&tmpl=ksenmart" data-callback="onSuccessLogin" class="btn save pull-right" title="Войти">Войти</a>-->
            <a href="javascript:void(0);" class="btn close pull-right">Отменить</a>
        </div>
    </div>
</form>
<script>
    jQuery(document).ready(function(){
        
        var login_form  = jQuery('#login_form');
        var login_block = jQuery('.login_block');
        
        //jQuery('a.save').postSend({form: '#login_form'})
        
        jQuery('#login_form_show').on('click', function(){
            jQuery('body').append('<div id="popup-overlay_1"></div>').fadeIn();
            login_block.fadeIn();
        });
        
        jQuery('.close').on('click', function(){
            jQuery(this).parent().parent().fadeOut();
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
        
        function onSuccessLogin(data){
            if(data == ''){
                location.reload();
            }else{
                login_block.children('.msg').html(data).fadeIn(400);
            }
        }
    });
</script>