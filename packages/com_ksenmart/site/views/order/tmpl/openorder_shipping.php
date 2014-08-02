<?php
defined( '_JEXEC' ) or die;
?>
<div class="cart_shipping_info">
	<div><strong>Выберите способ доставки</strong></div>
	<div class="shippings">
	<?php if (count($this->shippings)==0):?>
	<div class="no_shippings">
		<span class="grey-span">Нет способов доставки для выбранного региона</span>
	</div>
	<?php endif;?>
	<?php $db=JFactory::getDBO();?>
	<?php foreach($this->shippings as $ship):?>
	<?php $cost=0;?>
	<?php $shipping_id=$ship->id;?>
	<?php $distance=0;?>
	<?php $region_id=$this->user->region;?>
	<?php include(JPATH_ROOT.'/administrator/components/com_ksenmart/helpers/shipping/'.$ship->type_name.'.php');?>		
	<div class="shipping">
		<input type="radio" name="shipping_type" regions="<?php echo $ship->regions?>" value="<?php echo $ship->id?>" />
		<span class="grey-span"><?php echo JText::_($ship->title)?><?php echo ($cost!=0?' — '.KsenMartHelper::showPriceWithoutTransform($cost):'')?></span>
		<span class="shipping-descr">(доставка не позднее <?php echo KsenMartHelper::getShippingDate($ship->id)?>)</span>
	</div>
	<?php endforeach;?>
	</div>
	<div class="delivcost">Стоимость доставки: <?php echo KsenMartHelper::showPriceWithoutTransform('<span id="delivcost">0</span>')?></div>
</div>
<div class="cart_payment_info">
	<div><strong>Выберите способ оплаты</strong></div>
	<div class="no_shippings hide">
		<span class="grey-span">Нет способов оплаты для выбранного региона</span>
	</div>
	<?php foreach($this->payments as $payment):?>
	<div class="payment">
		<input type="radio" name="payment_type" regions="<?php echo $payment->regions?>" value="<?php echo $payment->id?>" />
		<span class="grey-span"><?php echo JText::_($payment->title)?></span>
	</div>
	<?php endforeach;?>
</div>	
