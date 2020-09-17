<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-cart ksm-congratulation ksm-block">
    <h2><?php echo JText::_('KSM_CART_CONGRATULATION_INTRO'); ?></h2>
    <div class="ksm-cart-order-step-left">
		<?php echo $this->loadTemplate('message'); ?>
    </div>
    <div class="ksm-cart-order-step-right">
		<?php echo $this->loadTemplate('total'); ?>
    </div>
</div>