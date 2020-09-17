<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<?php if($this->params->get('only_auth_buy',0) == 0 || ($this->params->get('only_auth_buy',0) != 0 && JFactory::getUser()->id != 0)){ ?>
<div class="ksm-set-buy-prices">
	<p><?php echo JText::_('KSM_PRODUCT_SET_PRICE_LABEL'); ?> <span class="ksm-set-buy-price"><?php echo $this->product->val_price; ?></span></p>
	<p><?php echo JText::_('KSM_PRODUCT_SET_PRICE_ECONOMY'); ?> <span class="ksm-set-buy-save"><?php echo $this->product->val_diff_price; ?></span></p>
</div>
<?php } ?>