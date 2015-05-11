<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
$document = JFactory::getDocument();

if (!class_exists('KSSystem')){
	include(JPATH_ROOT.'/administrator/components/com_ksenmart/helpers/common/system.php');	
}
include(JPATH_ROOT.'/components/com_ksenmart/social/social.php');	

$user = KSUsers::getUser();
if($user->id == 0){
    $block_auth = '
    		<span class="close_popover close" style="position: absolute;top: 8px;right: 8px;">&#215;</span>
    		<form class="auth-tab" method="POST" style="width:200px;">
    			<div class="control-group">
    				<div class="controls">
    					<input type="text" class="inputbox span12" name="login" value="" placeholder="Эл. почта" required="true" />
    				</div>
    			</div>
    			<div class="control-group">
    				<div class="controls">
    					<input type="password" class="inputbox span12" name="password" value="" placeholder="Пароль" required="true" />
    				</div>
    			</div>
    			<div class="control-group">
    				<div class="controls">
    					<button type="submit" class="button btn btn-success" style="width:100%">Войти</button>
    				</div>
    			</div>
    			<div class="control-group text-center">
    				<ul class="unstyled">
    					<li><a href="'.JRoute::_('index.php?option=com_users&view=reset').'">Напомнить пароль</a></li>
                        <li><a href="'.JRoute::_('index.php?option=com_ksenmart&view=profile&layout=registration').'" title="Регистрация">Регистрация</a></li>                        
    				</ul>
    			</div>
    		</form>
    ';
    
    $block_reg = '
    		<span class="close_popover close">&#215;</span>
    		<form id="reg-tab" method="POST">
    			<div class="control-group">
    				<div class="controls">
    					<input type="text" class="inputbox span12" name="name" value="" placeholder="Ваше имя" required="true" />
    				</div>
    			</div>
    			<div class="control-group">
    				<div class="controls">
    					<input type="email" class="inputbox span12" name="login" value="" placeholder="Эл. почта" required="true" />
    				</div>
    			</div>
    			<div class="control-group">
    				<div class="controls">
    					<input type="password" class="inputbox span12" name="password" value="" placeholder="Пароль" required="true" />
    				</div>
    			</div>
    			<div class="control-group">
    				<div class="controls">
    					<input type="password" class="inputbox span12" name="password1" value="" placeholder="Подтверждение пароля" required="true" />
    				</div>
    			</div>
    			<div class="control-group">
    				<div class="controls clearfix">
    					<button type="submit" class="st_button btn btn-success span12">Регистрация</button>
    				</div>
    			</div>
    			<div class="control-group text-center">
    				<div class="controls">
    					<ul class="unstyled">
    						<li><a href="'.JRoute::_('index.php?option=com_users&view=reset').'">Напомнить пароль</a></li>
    					</ul>
    				</div>
    			</div>
    		</form>	
    ';
    
    $block_auth = str_replace(array("\r", "\n", "\t"), '', $block_auth);
    //$block_reg = str_replace(array("\r", "\n", "\t"), '', $block_reg);
    ?>
    <script>
	var login_form = '<?php echo $block_auth; ?>';

    jQuery(document).ready(function(){
        /*
    	jQuery('#reg').popover({
    		html: 	 true,
    		content: reg_form
    	});*/
    	
    	jQuery('#auth, #on_fav, .spy_price').popover({
    		html: 	 true,
    		content: login_form
    	});
    	
    	function hidePopovers(){
    		var popover_block = jQuery('.popover');
    		popover_block.removeClass('in');
    		popover_block.addClass('out');
            
            setTimeout(function(){
                popover_block.remove();
            }, 500);
    	}
    	
    	jQuery('body').on('click', '.close_popover', function(){
    		hidePopovers();
    	});
    });
    </script>
<?php } ?>
<?php
 /*   $canDo = KSSystem::getActions();

var_dump(JFactory::getUser()->authorise('core.manage', 'com_ksenmart'));
if(!JFactory::getUser()->authorise('core.manage', 'com_ksenmart')){
        return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}*/
?>
<?php if ($type == 'logout'){ ?>
<div class="login user_panel_top noTransition">
    <ul class="inline">
		<li>
            <a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=profile'); ?>" id="show-user-menu" class="jm_menu show-user-menu" title="Личный кабинет">Личный кабинет</a>
        </li>
        <li>
		  <a href="javascript:void(0);" class="jm_login" title="Выход">Выход</a>
        </li>
    </ul>
</div>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form">
	<div class="logout-button">
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.logout" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<script>
jQuery('.jm_login').click(function(){
	jQuery('#login-form').submit();
	return false;
});
</script>
<?php }else{
    $_SESSION['state'] = md5(uniqid(rand(), TRUE));
?>
<script type="text/javascript" src="http://cdn.connect.mail.ru/js/loader.js"></script>
<script type="text/javascript" src="http://vkontakte.ru/js/api/openapi.js"></script>
<script src="http://www.odnoklassniki.ru/oauth/resources.do?type=js" type="text/javascript" charset="utf-8"></script>
<div class="login user_panel_top">
    <ul class="inline">
		<li>  
            <a href="javascript:void(0);" class="link_b_border" id="auth" data-toggle="popover" data-placement="bottom" title="" data-original-title="Авторизация">Авторизация</a>
        </li>
		<!--<li>       
            <a class="reg" href="/index.php?option=com_ksenmart&view=profile&layout=registration" class="link_b_border" title="">Регистрация</a>
        </li>-->
	</ul>
</div>
<div id="fb-root"></div>

<script type="text/javascript">

VK.init({
    apiId: <?php echo VK_APP_ID; ?>,
    nameTransportPath: '{% url publicauth-vkontakte-xdreceiver %}'
});
 
jQuery('.vk_login').on('click',function(){
    VK.Auth.login(testMe);
    return false;
});

function testMe(response) {
    if (response.status == "connected") {
		jQuery.ajax({
			url:'index.php?option=com_ksenmart&task=shopajax.vk_auth',
			success:function(data){
				if (data=='login')
					window.location.reload();
				else if (data=='register' && document.getElementById('subscribe').checked==true)	
					window.location='<?php echo JRoute::_('index.php?option=com_ksenmart&view=profile&Itemid='.KSSystem::getShopItemid()); ?>';
				else	
					window.location.reload();
			}
		});
    }
};

jQuery('.tw_login').on('click',function(){
    var newWin = window.open("<?php echo JURI::base()?>index.php?option=com_ksenmart&task=shopajax.tw_auth", "Twitter Login","width=400,height=600,resizable=yes,scrollbars=yes,status=yes");
    return false;
});

jQuery('.ok_login').on('click',function(){
	ODKL.Oauth2(this, <?php echo OK_APP_ID; ?>, 'SET STATUS;VALUABLE ACCESS', '<?php echo JURI::base(); ?>index.php?option=com_ksenmart&task=shopajax.ok_auth' );
    return false;
});

jQuery('.fb_login').on('click',function(){
	var newWin = window.open("https://www.facebook.com/dialog/oauth?client_id=<?php echo FB_APP_KEY; ?>&redirect_uri=<?php echo urlencode(JURI::base().'index.php?option=com_ksenmart&task=shopajax.fb_auth')?>&state=<?php echo $_SESSION['state']; ?>", "Facebook Login","width=800,height=600,resizable=yes,scrollbars=yes,status=yes");
    return false;
});

jQuery('body').on('submit', '.auth-tab', function(e){
    e.preventDefault();
    
	var login 	 = jQuery('.auth-tab input[name="login"]').val();
	var password = jQuery('.auth-tab input[name="password"]').val();
    
	if(login == ''){
		KMShowMessage("Введите ваш E-mail");
		return false;
	}
	if (password=='')
	{
		KMShowMessage("Введите ваш пароль");
		return false;
	}	
	jQuery.ajax({
	    type: 'POST',
		url:URI_ROOT+'index.php?option=com_ksenmart&task=shopajax.site_auth&login='+login+'&password='+password,
		success:function(data){
		  console.log(data);
			if (data == 'login'){
				window.location.reload();
			}else{
				KMShowMessage('Ошибка. Неправильно введен логин или пароль');
            }
		}
	});	
});
</script>
<?php } ?>