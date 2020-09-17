<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="ksm-product-prices product_prices">
	<?php if ($this->params->get('only_auth_buy',0) == 0 || ($this->params->get('only_auth_buy',0) != 0 && JFactory::getUser()->id != 0)){ ?>
		<div class="ksm-product-info-row">
			<div <?php echo JMicrodata::htmlProperty('offers'); ?> <?php echo JMicrodata::htmlScope('offer'); ?> class="ksm-product-info-price-block">
				<label><?php echo JText::_('KSM_PRODUCT_PRICE'); ?></label>
				<?php if ($this->product->old_price != 0){ ?>
				<span class="ksm-product-old-price"><?php echo $this->product->val_old_price; ?></span>
				<?php } ?>
				<span class="ksm-product-price"><?php echo $this->product->val_price; ?></span>
				<br />
				<?php echo JMicrodata::htmlMeta(KSMPrice::getPriceInCurrencyInt($this->product->price), 'price'); ?>
				<?php echo JMicrodata::htmlMeta(KSMPrice::getCurrencyCode(), 'priceCurrency'); ?>
				<?php if ($this->product->in_stock==0 && $this->params->get('use_stock',1)==1):?>
					<h3><?php echo JText::_('KSM_PRODUCT_OUT_OF_STOCK'); ?></h3>
				<?php else:?>
					<div class=" ksm-product-quant">
						<span class="ksm-product-quant-minus">-</span>
						<input type="text" class="ksm-product-quant-input" name="count" value="<?php echo $this->product->product_packaging?>" />
						<span class="ksm-product-quant-plus">+</span>
					</div>
					<button type="submit" class="ksm-btn-success"><?php echo JText::_('KSM_PRODUCT_ADD_TO_CART_LABEL'); ?></button>
				<?php endif;?>
				<br />
				<a data-prd_id="<?php echo $this->product->id; ?>" class="ksm-product-spy-price"><?php echo JText::_('KSM_PRODUCT_SPY_PRICE'); ?></a>
			</div>
		</div>
	<?php } ?>
</div>