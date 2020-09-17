<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-cart-item" data-item_id="<?php echo $this->item->id?>" data-product_packaging="<?php echo $this->item->product->product_packaging; ?>">
	<div class="ksm-cart-item-left">
		<div class="ksm-cart-item-img">
			<a href="<?php echo $this->item->product->link; ?>"><img src="<?php echo $this->item->product->mini_small_img; ?>" /></a>
		</div>
		<div class="ksm-cart-item-info">
			<p><a href="<?php echo $this->item->product->link; ?>" title="<?php echo $this->item->product->title; ?>"><?php echo $this->item->product->title; ?></a></p>
			<?php if (!empty($this->item->product->product_code)): ?>
				<p><span><?php echo JText::_('KSM_PRODUCT_ARTICLE'); ?></span> <?php echo $this->item->product->product_code; ?></p>
			<?php endif; ?>
			<?php if (!empty($this->item->product->manufacturer_title)): ?>
				<p><span><?php echo JText::_('KSM_PRODUCT_MANUFACTURER'); ?></span> <?php echo $this->item->product->manufacturer_title; ?></p>
			<?php endif; ?>
			<?php foreach($this->item->properties as $item_property): ?>
				<?php if (!empty($item_property->value)): ?>
					<p><span><?php echo $item_property->title; ?>:</span> <?php echo $item_property->value; ?></p>
				<?php else: ?>
					<p><span><?php echo $item_property->title; ?></span></p>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="ksm-cart-item-right">
		<div class="ksm-cart-item-quant">
			<span class="ksm-cart-item-quant-minus">-</span>
			<input type="text" class="ksm-cart-item-quant-input" value="<?php echo $this->item->count; ?>" />
			<span class="ksm-cart-item-quant-plus">+</span>
		</div>
		<div class="ksm-cart-item-prices">
			<?php if(!empty($this->item->old_price)): ?>
				<div class="ksm-cart-item-price-old">
					<?php echo $this->item->old_price_val; ?>
				</div>
			<?php endif; ?>
			<div class="ksm-cart-item-price">
				<?php echo KSMPrice::showPriceWithTransform($this->item->price); ?>
			</div>
		</div>
		<div class="ksm-cart-item-sum">
			<?php echo KSMPrice::showPriceWithTransform($this->item->price * $this->item->count); ?>
		</div>
		<div class="ksm-cart-item-del">
			<a class="ksm-cart-item-del-link" href="<?php echo $this->item->del_link; ?>">&#215;</a>	
		</div>
	</div>
</div>