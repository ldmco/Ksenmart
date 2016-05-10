<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-module-shipping-info">
	<div class="ksm-module-shipping-delivery-row">
		<h5><?php echo JText::_('MOD_KM_SHIPPING_SHIPPINGS'); ?></h5>
		<?php if (count($shippings) > 0): ?>
			<?php foreach($shippings as $shipping): ?>
			<p>
				<?php if (!empty($shipping->icon)): ?>
					<span class="ksm-module-shipping-delivery-icon"><img src="<?php echo $shipping->icon; ?>" /></span>
				<?php endif;?>				
				<?php echo $shipping->title ?>
				— <b><?php echo $shipping->sum_val ?></b>
			</p>
			<?php endforeach; ?>
		<?php else: ?>
			<p><?php echo JText::_('MOD_KM_SHIPPING_NO_SHIPPINGS'); ?></p>
		<?php endif; ?>
	</div>
	<div class="ksm-module-shipping-payment-row">
		<h5><?php echo JText::_('MOD_KM_SHIPPING_PAYMENTS'); ?></h5>
		<?php if(count($payments) > 0): ?>
			<?php foreach($payments as $payment): ?>
			<p>
				<?php if (!empty($payment->icon)):?>
				<span class="ksm-module-shipping-payment-icon"><img src="<?php echo $payment->icon; ?>" /></span>
				<?php endif;?>				
				<?php echo $payment->title; ?>
			</p>
			<?php endforeach; ?>
		<?php else: ?>
			<p><?php echo JText::_('MOD_KM_SHIPPING_NO_PAYMENTS'); ?></p>
		<?php endif; ?>
	</div>
</div>