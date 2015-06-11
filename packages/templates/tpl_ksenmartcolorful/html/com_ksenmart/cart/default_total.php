<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="kmcart-total result default_total">
	<?php if($this->cart->shipping_sum > 0){ ?>
	<div class="total_shipping lead pull-right">
		<?php echo JText::_('KSM_CART_SHIPPING_SUM_TEXT'); ?> <span><?php echo $this->cart->shipping_sum_val; ?></span>
	</div>
	<?php } ?>
	<div class="clearfix"></div>
	<?php if($this->cart->discount_sum > 0){ ?>
	<div class="total_shipping lead pull-right">
		<?php echo JText::_('KSM_CART_DISCOUNT_SUM_TEXT'); ?> <span><?php echo $this->cart->discount_sum_val; ?></span>
	</div>
	<?php } ?>
	<div class="clearfix"></div>	
	<div class="total lead pull-right">
		<?php echo JText::_('KSM_CART_TOTAL_SUM_TEXT'); ?> <span><?php echo $this->cart->total_sum_val; ?></span>
	</div>
	<div class="clearfix"></div>
    <input type="hidden" name="task" value="cart.close_order" />
    <input type="hidden" name="cost_shipping" value="<?php echo $this->cart->total_sum; ?>" id="deliverycost" />
    <input type="hidden" name="cost" value="<?php echo $this->cart->shipping_sum; ?>" id="total_cost" />
	<input type="submit" class="btn btn-success btn-large pull-right st_button order_button" value="<?php echo JText::_('KSM_CART_CHECKOUT_TEXT'); ?>" />
</div>