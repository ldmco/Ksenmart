<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-cart-order-payments default_payments">
	<?php if (!$this->payments) { ?>
        <div class="ksm-cart-order-step-row">
            <div class="ksm-cart-order-step-row-control">
                <label><?php echo JText::_('KSM_CART_NOPAYMENT_TEXT'); ?></label>
            </div>
        </div>
	<?php } else { ?>
        <div class="ksm-cart-order-step-row">
            <div class="ksm-cart-order-step-row-control">
				<?php foreach ($this->payments as $payment) { ?>
                    <div class="ksm-cart-order-payment-method">
                        <label>
                            <input type="radio" id="payment_id" name="payment_id" value="<?php echo $payment->id; ?>"
                                   required="true"
                                   onclick="KMCartChangePayment(this);" <?php echo($payment->selected ? 'checked' : ''); ?> />
							<?php if (!empty($payment->icon)) { ?>
                                <span class="icon"><img src="<?php echo $payment->icon; ?>"/></span>
							<?php } ?>
							<?php echo JText::_($payment->title); ?>
                        </label>
                    </div>
				<?php } ?>
            </div>
        </div>
	<?php } ?>
</div>