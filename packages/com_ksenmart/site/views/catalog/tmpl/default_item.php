<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-catalog-item" data-id="<?php echo $this->product->id; ?>">
	<div class="ksm-catalog-item-img">
		<?php echo $this->loadTemplate('item_image'); ?>
	</div>
	<div class="ksm-catalog-item-name">
		<a href="<?php echo $this->product->link; ?>"><?php echo $this->product->title; ?></a>
		<?php if (!empty($this->product->manufacturer_title)): ?>
		<span><?php echo $this->product->manufacturer_title; ?></span>
		<?php endif; ?>
	</div>	
	<div class="ksm-catalog-item-price">
		<?php if (!empty($this->product->old_price)): ?>
		<span class="ksm-catalog-item-price-old"><?php echo $this->product->val_old_price; ?></span>
		<?php endif; ?>
		<span class="ksm-catalog-item-price-normal"><?php echo $this->product->val_price; ?></span>
	</div>	
	<div class="ksm-catalog-item-button">
		<?php if ($this->product->catalog_buy): ?> 
			<form class="ksm-catalog-item-buy-form">
				<button type="submit" class="ksm-catalog-item-button-buy"><?php echo JText::_('KSM_PRODUCT_ADDTOCART_BUTTON_TEXT'); ?></button>
				<input type="hidden" name="count" value="<?php echo $this->product->product_packaging; ?>" />
				<input type="hidden" name="product_packaging" value="<?php echo $this->product->product_packaging; ?>" />
				<input type="hidden" name="price" value="<?php echo $this->product->price; ?>" />
				<input type="hidden" name="id" value="<?php echo $this->product->id; ?>" />				
			</form>
		<?php else: ?>
			<a href="<?php echo $this->product->link; ?>" class="ksm-catalog-item-button-view"><?php echo JText::_('KSM_READ_MORE'); ?></a>
		<?php endif; ?>
	</div>		
</div>
