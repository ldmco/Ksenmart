<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="ksm-cart-item ksm-cart-total-item" data-item_id="<?php echo $this->item->id ?>"
     data-product_packaging="<?php echo $this->item->product->product_packaging; ?>">
    <div class="ksm-cart-item-img">
        <img src="<?php echo $this->item->product->mini_small_img; ?>"/>
    </div>
    <div class="ksm-cart-item-info">
        <p>
			<?php echo $this->item->product->title; ?>
			<?php if (!empty($this->item->product->product_code)): ?>
                (<?php echo $this->item->product->product_code; ?>)
			<?php endif; ?>
			<?php if (!empty($this->item->product->manufacturer_title)): ?>
                {<?php echo $this->item->product->manufacturer_title; ?>)
			<?php endif; ?>
        </p>
        <p class="ksm-congratulation-item-bottom">
			<?php echo $this->item->count; ?>
			<?php echo JText::_('KSM_CART_PRODUCT_UNIT'); ?>
			<?php echo JText::_('KSM_CART_CONGRATULATION_PRODUCT_PRICE'); ?>
			<?php echo KSMPrice::showPriceWithTransform($this->item->price * $this->item->count); ?>
        </p>
    </div>
</div>