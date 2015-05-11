<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>

<?php if($this->params->get('only_auth_buy',0) == 0 || ($this->params->get('only_auth_buy',0) != 0 && JFactory::getUser()->id != 0)){ ?>
<div class="prices-set span4">
	<div class="price"><?php echo JText::_('KSM_PRODUCT_SET_PRICE_LABEL'); ?> <em class="lead"><?php echo $this->product->val_price; ?></em></div>
	<div class="save"><?php echo JText::_('KSM_PRODUCT_SET_PRICE_ECONOMY'); ?> <em class="lead"><?php echo $this->product->val_diff_price; ?></em></div>
</div>
<?php } ?>