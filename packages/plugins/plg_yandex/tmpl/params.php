<?php
defined( '_JEXEC' ) or die;
?>
<div class="set">
	<h3 class="headname"><?php echo JText::_('plg_kmspayment_yandex_settings'); ?></h3>
	<div class="row alert">
		<p><u><?php echo JText::_('plg_kmspayment_yandex_checklink_label'); ?>:</u>&nbsp;&nbsp;<?php echo JURI::root().'index.php?option=com_ksenmart&task=pluginAction&action=CheckOrder&plugin=yandex&format=raw'; ?></p>
	</div>	
	<div class="row">
		<label class="inputname"><?php echo JText::_('plg_kmspayment_yandex_shopid_label'); ?></label>
		<input type="text" style="width:250px;" class="inputbox" name="jform[params][shopId]" value="<?php echo $view->params['shopId']; ?>" />
	</div>
	<div class="row">
		<label class="inputname"><?php echo JText::_('plg_kmspayment_yandex_scid_label'); ?></label>
		<input type="text" style="width:250px;" class="inputbox" name="jform[params][scId]" value="<?php echo $view->params['scId']; ?>" />
	</div>	
	<div class="row">
		<label class="inputname"><?php echo JText::_('plg_kmspayment_yandex_shoppassword_label'); ?></label>
		<input type="text" style="width:250px;" class="inputbox" name="jform[params][ShopPassword]" value="<?php echo $view->params['ShopPassword']; ?>" />
	</div>		
</div>
