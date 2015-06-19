<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<center>
	<form method="post" action="https://www.walletone.com/checkout/default.aspx" accept-charset="UTF-8">
        <?php foreach(KSMWalletone::_getFields() as $fieldName => $fieldValue): ?>
        	<?php if(!is_array($fieldValue)): ?>
        		<input type="hidden" name="<?php echo $fieldName; ?>" value="<?php echo $fieldValue; ?>" />
        	<?php else: ?>
    			<?php foreach($fieldValue as $fieldArrayValue): ?>
    				<input type="hidden" name="<?php echo $fieldName; ?>" value="<?php echo $fieldArrayValue; ?>" />
    			<?php endforeach; ?>
        	<?php endif; ?>
        <?php endforeach; ?>
        <input type="hidden" name="WMI_SIGNATURE" value="<?php echo KSMWalletone::getHash($view->payment_params->get('secretKey', null)); ?>" />
		<input type="submit" value="<?php echo JText::_('KSM_PAYMENT_WALLETONE_PAY'); ?>" class="button btn-success btn-large noTransition" />
	</form>
</center>