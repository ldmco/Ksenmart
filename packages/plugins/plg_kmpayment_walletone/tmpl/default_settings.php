<?php defined('_JEXEC') or die; ?>
<div class="set">
	<h3 class="headname"><?php echo JText::_('KSM_PAYMENT_ALGORITHM'); ?></h3>
	<div class="row">
		<label class="inputname"><?php echo JText::_('KSM_PAYMENT_WALLETONE_ID'); ?></label>
		<input type="text" style="width:250px;" class="inputbox" name="jform[params][merchant_id]" value="<?php echo $view['merchant_id']; ?>" />
	</div>
	<div class="row">
		<label class="inputname"><?php echo JText::_('KSM_PAYMENT_WALLETONE_SECRET_KEY'); ?></label>
		<input type="text" style="width:250px;" class="inputbox" name="jform[params][secretKey]" value="<?php echo $view['secretKey']; ?>" />
	</div>
</div>