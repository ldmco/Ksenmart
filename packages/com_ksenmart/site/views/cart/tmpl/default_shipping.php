<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="ksm-cart-order-shipping default_shipping">
	<?php if (!$this->shippings) { ?>
        <div class="ksm-cart-order-step-row">
            <div class="ksm-cart-order-step-row-control">
                <label><?php echo JText::_('KSM_CART_NOSHIPING_TEXT'); ?></label>
            </div>
        </div>
	<?php } else { ?>
        <div class="ksm-cart-order-step-row">
            <div class="ksm-cart-order-step-row-control">
				<?php foreach ($this->shippings as $shipping) { ?>
                    <div class="ksm-cart-order-shipping-method">
                        <label>
                            <input type="radio" id="shipping_id"
                                   name="shipping_id"
                                   value="<?php echo $shipping->id; ?>"
                                   required="true"
                                   onclick="<?php echo !empty($shipping->action) ? $shipping->action : 'KMCartChangeShipping(this);'; ?>" <?php echo($shipping->selected ? 'checked' : ''); ?> />
							<?php if (!empty($shipping->icon)) { ?>
                                <span class="icon"><img src="<?php echo $shipping->icon; ?>"/></span>
							<?php } ?>
							<?php echo JText::_($shipping->title); ?>
                        </label>
                        <span class="ksm-cart-order-shipping-method-price"><?php echo $shipping->shipping_sum_val; ?></span>
                    </div>
				<?php } ?>
            </div>
        </div>
	<?php } ?>
</div>