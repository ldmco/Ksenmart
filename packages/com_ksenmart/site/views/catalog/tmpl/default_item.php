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
		<?php if (!empty($this->product->product_code)): ?>
			<span
				class="ksm-catalog-item-name-art"><?php echo JText::_('KSM_PRODUCT_ARTICLE'); ?><?php echo $this->product->product_code; ?></span>
		<?php endif; ?>
		<?php if (!empty($this->product->manufacturer_title)): ?>
			<span class="ksm-catalog-item-name-man"><?php echo $this->product->manufacturer_title; ?></span>
		<?php endif; ?>
		<?php if ($this->params->get('show_product_rate') == 1) { ?>
			<span class="ksm-catalog-item-name-rate">
				<?php echo JText::_('KSM_PRODUCT_RATE'); ?>
				<?php for ($i = 1; $i < 6; $i++): ?>
					<?php if (floor($this->product->rate->rate) >= $i): ?>
						<img src="<?php echo JUri::root(); ?>components/com_ksenmart/images/star2.png" alt=""/>
					<?php else: ?>
						<img src="<?php echo JUri::root(); ?>components/com_ksenmart/images/star.png" alt=""/>
					<?php endif; ?>
				<?php endfor; ?>
			</span>
		<?php } ?>
		<?php if (!empty($this->product->introcontent)): ?>
			<div class="ksm-catalog-item-name-introcontent">
				<?php echo $this->product->introcontent; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="ksm-catalog-item-price-button">
		<div class="ksm-catalog-item-price">
			<?php if (!empty($this->product->old_price)): ?>
				<span class="ksm-catalog-item-price-old"><?php echo $this->product->val_old_price; ?></span>
			<?php endif; ?>
			<span class="ksm-catalog-item-price-normal"><?php echo $this->product->val_price; ?></span>
		</div>
		<div class="ksm-catalog-item-button">
			<?php if ($this->product->catalog_buy): ?>
				<form class="ksm-catalog-item-buy-form">
					<button type="submit"
					        class="ksm-catalog-item-button-buy ksm-btn-success"><?php echo JText::_('KSM_PRODUCT_ADDTOCART_BUTTON_TEXT'); ?></button>
					<input type="hidden" name="count" value="<?php echo $this->product->product_packaging; ?>"/>
					<input type="hidden" name="product_packaging"
					       value="<?php echo $this->product->product_packaging; ?>"/>
					<input type="hidden" name="price" value="<?php echo $this->product->price; ?>"/>
					<input type="hidden" name="id" value="<?php echo $this->product->id; ?>"/>
				</form>
			<?php else: ?>
				<a href="<?php echo $this->product->link; ?>"
				   class="ksm-catalog-item-button-view ksm-btn"><?php echo JText::_('KSM_READ_MORE'); ?></a>
			<?php endif; ?>
		</div>
	</div>
</div>
