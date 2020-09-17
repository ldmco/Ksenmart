<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="default_content">
    <h2><?php echo JText::_('KSM_CART_YOUR_ORDER'); ?></h2>
	<div class="ksm-cart-items">
		<div class="ksm-cart-items-head">
			<div class="ksm-cart-item-left">
				<div class="ksm-cart-item-img"><?php echo JText::_('KSM_CART_TH_PHOTO'); ?></div>
				<div class="ksm-cart-item-info"><?php echo JText::_('KSM_CART_TH_PRODUCT_INFO'); ?></div>
			</div>
			<div class="ksm-cart-item-right">
				<div class="ksm-cart-item-quant"><?php echo JText::_('KSM_CART_TH_COUNT'); ?></div>
				<div class="ksm-cart-item-prices"><?php echo JText::_('KSM_CART_TH_PRICE'); ?></div>
				<div class="ksm-cart-item-sum"><?php echo JText::_('KSM_CART_TH_PRODUCT_SUM'); ?></div>
				<div class="ksm-cart-item-del"></div>
			</div>
		</div>
		<div>
		<?php foreach($this->cart->items as $item){ ?>
			<?php echo $this->loadTemplate('item', null, array('item' => $item)); ?>
		<?php } ?>
		</div>
	</div>
	<div class="ksm-cart-items-total">
		<?php if(isset($this->cart->discount_sum) && $this->cart->discount_sum > 0): ?>
			<div class="ksm-cart-items-total-block ksm-cart-items-total-discount">
				<?php echo JText::_('KSM_CART_DISCOUNT_SUM_TEXT'); ?> <span class="ksm-cart-items-total-discount-price"><?php echo $this->cart->discount_sum_val; ?></span>
			</div>
		<?php endif; ?>
		<div class="ksm-cart-items-total-block ksm-cart-items-total-sum">
			<?php echo JText::_('KSM_CART_ITEMS_TOTAL_SUM_TEXT'); ?> <span class="ksm-cart-items-total-sum-price"><?php echo KSMPrice::showPriceWithTransform($this->cart->products_sum - $this->cart->discount_sum); ?></span>
		</div>
	</div>
    <button class="ksm-btn-success" id="ksm-cart-show-order"><?php echo JText::_('KSM_CART_CHECKOUT_TEXT'); ?></button>
</div>