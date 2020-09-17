<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-cart-order-step ksm-cart-order-step-note">
	<legend><?php echo JText::_('KSM_CART_ORDER_NOTE_TITLE'); ?></legend>	
	<div class="ksm-cart-order-step-row">
		<textarea name="note" placeholder="<?php echo JText::_('KSM_CART_ORDER_NOTE_INPUT_TEXT'); ?>"><?php echo $this->cart->note; ?></textarea>
        <div class="ksm-cart-order-field-example">
            <span><?php echo JText::_('KSM_CART_SHIPPING_FIELD_EXAMPLE'); ?>:</span>
			<?php echo JText::_('KSM_CART_SHIPPING_FIELD_NODE_EXAMPLE'); ?>
        </div>
	</div>	
</div>