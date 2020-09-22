<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="ksm-block">
	<form method="post" action="http://money.yandex.ru/eshop.xml" class="ksm-payment-form">
		<input name="shopId" value="<?php echo $view->payment_params->get('shopId', null); ?>" type="hidden"/>
		<input name="scid" value="<?php echo $view->payment_params->get('scId', null); ?>" type="hidden"/>
		<input name="sum" value="<?php echo $view->order->costs['total_cost']; ?>" type="hidden">
		<input name="customerNumber" value="<?php echo $view->order->user_id; ?>" type="hidden"/>
		<input name="paymentType" value="" type="hidden"/>
		<input name="orderNumber" value="<?php echo $view->order->id; ?>" type="hidden"/>
		<input name="cps_phone" value="<?php echo $view->order->customer_fields->phone; ?>" type="hidden"/>
		<input name="cps_email" value="<?php echo $view->order->customer_fields->email; ?>" type="hidden"/>	
		<input type="submit" value="<?php echo JText::_('plg_kmspayment_yandex_pay_label'); ?>" />
	</form>
</div>