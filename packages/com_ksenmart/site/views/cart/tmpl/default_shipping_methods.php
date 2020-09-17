<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-cart-order-shipping-methods default_shipping_methods">
    <?php if (!$this->shippings): ?>	
		<div class="ksm-cart-order-step-row">
			<div class="ksm-cart-order-step-row-control">
				<label><?php echo JText::_('KSM_CART_NOSHIPING_TEXT'); ?></label>
			</div>
		</div>
    <?php else: ?>
		<div class="ksm-cart-order-step-row">
			<div class="ksm-cart-order-step-row-control">
			<?php foreach($this->shippings as $shipping): ?>
				<div class="ksm-cart-order-shipping-method">
					<label>
						<input type="radio" name="shipping_id" value="<?php echo $shipping->id;?>" required="true" onclick="KMCartChangeShipping(this);" <?php echo ($shipping->selected?'checked':''); ?> /> 
						<?php if (!empty($shipping->icon)):?>
							<span class="icon"><img src="<?php echo $shipping->icon; ?>" /></span>
						<?php endif;?>						
						<?php echo JText::_($shipping->title); ?>
					</label>
				</div>
			<?php endforeach; ?>
			</div>
		</div>
    <?php endif; ?>
</div>