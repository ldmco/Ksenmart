<?php
defined( '_JEXEC' ) or die;
?>
<div class="set">
	<h3 class="headname"><?php echo JText::_('PLG_KMSHIPPING_B2CPL_SETTINGS'); ?></h3>
    <div class="row">
        <label class="inputname"><?php echo JText::_('PLG_KMSHIPPING_B2CPL_CLIENT_LABEL'); ?></label>
        <input class="inputbox" type="text" name="jform[params][client]" value="<?php echo $view->params['client']; ?>" />
    </div>
	<div class="row">
		<label class="inputname"><?php echo JText::_('PLG_KMSHIPPING_B2CPL_KEY_LABEL'); ?></label>
		<input class="inputbox" type="text" name="jform[params][key]" value="<?php echo $view->params['key']; ?>" />
	</div>
</div>
