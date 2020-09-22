<?php defined('_JEXEC') or die('Restricted access'); ?>
<legend class="ksm-cart-pickuplist-title"><?php echo JText::_('KSM_SHIPPING_PICKUP_LIST'); ?>:</legend>
<div class="ksm-cart-order-step-row">
    <div class="ksm-cart-order-step-row-control">
		<?php foreach ($view->pickups as $pickup) { ?>
            <div class="ksm-cart-order-shipping-method">
                <label>
                    <input type="radio" id="pickup_id" name="pickup_id" value="<?php echo $pickup->id; ?>"
                           required="true"
                           onclick="KMCartChangePickup(this);" <?php echo($pickup->selected ? 'checked' : ''); ?> />
					<?php if (!empty($view->icon)) { ?>
                        <span class="icon"><img src="<?php echo $view->icon; ?>"/></span>
					<?php } ?>
					<?php echo $pickup->title; ?>
                </label>
                <span class="ksm-cart-order-shipping-method-price"><?php echo $pickup->shipping_sum_val; ?></span>
            </div>
		<?php } ?>
    </div>
</div>