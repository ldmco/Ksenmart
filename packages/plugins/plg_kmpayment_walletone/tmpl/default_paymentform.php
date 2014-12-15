<?php defined('_JEXEC') or die ?>
<center>
	<form method="post" action="https://www.walletone.com/checkout/default.aspx" accept-charset="UTF-8">

        <?php foreach (KSMWalletone::_getFields() as $fieldName => $fieldValue): ?>
        	<input type="hidden" name="<?php echo $fieldName; ?>" value="<?php echo $fieldValue; ?>" />
        <?php endforeach; ?>
        <input type="hidden" name="WMI_SIGNATURE" value="<?php echo KSMWalletone::getHash($view->payment_params['secretKey']); ?>" />

		<input type="submit" value="<?php echo JText::_('KSM_PAYMENT_WALLETONE_PAY'); ?>" class="button btn-success btn-large noTransition" />
	</form>
</center>