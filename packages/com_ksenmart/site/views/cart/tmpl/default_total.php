<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="ksm-cart-total default_total">
	<?php if (isset($this->stepsinfo->congratulation)): ?>
        <div class="ksm-cart-block">
            <h3 class="ksm-congratulation-invoice"><?php echo JText::sprintf('KSM_CART_CONGRATULATION_INVOICE', $this->cart->id); ?></h3>
        </div>
        <div class="ksm-cart-block ksm-cart-block-products">
            <legend class="ksm-cart-block-label"><?php echo JText::_('KSM_CART_CONGRATULATION_PRODUCTS'); ?></legend>
			<?php foreach ($this->cart->items as $item)
			{ ?>
				<?php echo $this->loadTemplate('total_item', null, array('item' => $item)); ?>
			<?php } ?>
        </div>
	<?php endif; ?>
    <div class="ksm-cart-block ksm-cart-block-sum">
		<?php if ($this->cart->discount_sum > 0 || $this->cart->shipping_sum > 0): ?>
            <div class="ksm-cart-total-block ksm-cart-total-subtotal-sum">
                <span class="ksm-cart-total-label"><?php echo JText::_('KSM_CART_TOTAL_SUBTOTAL_SUM_TEXT'); ?></span>
                <span class="ksm-cart-total-discount-price"><?php echo $this->cart->products_sum_val; ?></span>
            </div>
		<?php endif; ?>
		<?php if ($this->cart->shipping_sum > 0): ?>
            <div class="ksm-cart-total-block ksm-cart-total-shipping">
                <span class="ksm-cart-total-label"><?php echo JText::_('KSM_CART_SHIPPING_SUM_TEXT'); ?></span>
                <span class="ksm-cart-total-shipping-price"><?php echo $this->cart->shipping_sum_val; ?></span>
            </div>
		<?php endif; ?>
		<?php if ($this->cart->discount_sum > 0): ?>
            <div class="ksm-cart-total-block ksm-cart-total-discount">
                <span class="ksm-cart-total-label"><?php echo JText::_('KSM_CART_DISCOUNT_SUM_TEXT'); ?></span>
                <span class="ksm-cart-total-discount-price"><?php echo $this->cart->discount_sum_val; ?></span>
            </div>
		<?php endif; ?>
        <div class="ksm-cart-total-block ksm-cart-total-sum">
            <div class="sum-label"><span class="ksm-cart-total-label"><?php echo JText::_('KSM_CART_TOTAL_SUM_TEXT'); ?></span></div>
            <span class="ksm-cart-total-sum-price"><?php echo $this->cart->total_sum_val; ?></span>
        </div>
    </div>
	<?php if ($this->stepsinfo->current_step > 1): ?>
        <div class="ksm-cart-block ksm-cart-block-system-fields">
            <legend class="ksm-cart-block-label">
				<?php echo JText::_('KSM_CART_CUSTOMER_FIELDS_TITLE'); ?>
				<?php if (!isset($this->stepsinfo->congratulation)): ?>
                    <a href="#" class="ksm-cart-block-edit"
                       data-step_id="1"><?php echo JText::_('KSM_CART_BLOCK_EDIT'); ?></a>
				<?php endif; ?>
            </legend>
			<?php foreach ($this->system_fields['customer'] as $field): ?>
				<?php if (!empty($field->value)): ?>
                    <div class="ksm-cart-info-row">
						<?php echo $field->value; ?>
                    </div>
				<?php endif; ?>
			<?php endforeach; ?>
            <legend class="ksm-cart-block-label"><?php echo JText::_('KSM_CART_ADDRESS_FIELDS_TITLE'); ?></legend>
			<?php foreach ($this->system_fields['address'] as $field): ?>
				<?php if (!empty($field->value)): ?>
                    <div class="ksm-cart-info-row">
						<?php echo $field->value; ?>
                    </div>
				<?php endif; ?>
			<?php endforeach; ?>
        </div>
	<?php endif; ?>
	<?php if ($this->stepsinfo->current_step > 2 && $this->stepsinfo->steps[2]): ?>
        <div class="ksm-cart-block ksm-cart-block-delivery">
            <legend class="ksm-cart-block-label">
				<?php echo JText::_('KSM_CART_DELIVERY_TITLE'); ?>
				<?php if (!isset($this->stepsinfo->congratulation)): ?>
                    <a href="#" class="ksm-cart-block-edit"
                       data-step_id="2"><?php echo JText::_('KSM_CART_BLOCK_EDIT'); ?></a>
				<?php endif; ?>
            </legend>
			<?php if (isset($this->shippings[$this->cart->shipping_id])): ?>
				<?php echo $this->shippings[$this->cart->shipping_id]->title; ?>
			<?php endif; ?>
        </div>
	<?php endif; ?>
	<?php if ($this->stepsinfo->current_step > 3 && $this->stepsinfo->steps[3]): ?>
        <div class="ksm-cart-block ksm-cart-block-info">
			<?php if (count($this->customer_fields)): ?>
                <legend class="ksm-cart-block-label">
					<?php echo JText::_('KSM_CART_INFO_CUSTOMER_TITLE'); ?>
					<?php if (!isset($this->stepsinfo->congratulation)): ?>
                        <a href="#" class="ksm-cart-block-edit"
                           data-step_id="3"><?php echo JText::_('KSM_CART_BLOCK_EDIT'); ?></a>
					<?php endif; ?>
                </legend>
				<?php foreach ($this->customer_fields as $field): ?>
					<?php if (!empty($field->value)): ?>
                        <div class="ksm-cart-info-row">
							<?php echo $field->value; ?>
                        </div>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if (count($this->address_fields)): ?>
                <legend class="ksm-cart-block-label"><?php echo JText::_('KSM_CART_INFO_ADDRESS_TITLE'); ?></legend>
				<?php foreach ($this->address_fields as $field): ?>
					<?php if (!empty($field->value)): ?>
                        <div class="ksm-cart-info-row">
							<?php echo JText::_(($field->system == 1 ? 'ksm_cart_shipping_field_' : '') . $field->title); ?>
                            : <?php echo $field->value; ?>
                        </div>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
        </div>
	<?php endif; ?>
</div>