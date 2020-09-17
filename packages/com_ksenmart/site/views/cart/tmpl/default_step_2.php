<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="ksm-cart-order-step-one ksm-cart-order-step default_step_one">
    <div class="ksm-cart-order-step-body" style="display: block;">
        <div class="ksm-cart-order-step-left">
            <legend><?php echo JText::_('KSM_CART_WHICH_DELIVER_THE_REGION'); ?></legend>
	        <?php echo $this->loadTemplate('regions'); ?>
            <legend><?php echo JText::_('KSM_CART_PAYMENT_METHODS_TITLE'); ?>:</legend>
	        <?php echo $this->loadTemplate('shipping'); ?>
			<?php if (!$this->stepsinfo->last_step): ?>
                <a href="#" class="ksm-cart-order-next-step ksm-btn btn ksm-btn-success">
					<?php echo JText::_('KSM_CART_NEXT_STEP'); ?>
                </a>
				<?php echo $this->loadTemplate('note'); ?>
			<?php else: ?>
				<?php echo $this->loadTemplate('note'); ?>
                <input type="hidden" name="task" value="cart.close_order"/>
                <input type="submit" value="<?php echo JText::_('KSM_CART_CHECKOUT_TEXT'); ?>"/>
                <br />
                <br />
                <a onclick="KMOpenPopupWindow('<?php echo JRoute::_('index.php?option=com_ksenmart&view=cart&layout=privacy&tmpl=component&Itemid=' . $Itemid); ?>', '80%', '80%', 'padding30'); return false;" href="#">
					<?php echo JText::_('KSM_CART_CHECKOUT_PRIVACY'); ?>
                </a>
			<?php endif; ?>
        </div>
        <div class="ksm-cart-order-step-right">
	        <?php echo $this->loadTemplate('total'); ?>
        </div>
    </div>
</div>