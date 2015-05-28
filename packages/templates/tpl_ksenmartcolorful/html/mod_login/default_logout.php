<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="login user_panel_top noTransition">
    <ul class="inline">
		<li>
            <a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=profile'); ?>" id="show-user-menu" class="jm_menu show-user-menu" title="<?php echo JText::_('TPL_KSENMARTCOLORFUL_AUTH_PROFILE'); ?>"><?php echo JText::_('TPL_KSENMARTCOLORFUL_AUTH_PROFILE'); ?></a>
        </li>
        <li>
		  <a href="javascript:void(0);" class="jm_login" title="<?php echo JText::_('TPL_KSENMARTCOLORFUL_AUTH_LOGOUT'); ?>"><?php echo JText::_('TPL_KSENMARTCOLORFUL_AUTH_LOGOUT'); ?></a>
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