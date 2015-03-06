<?php defined('_JEXEC') or die; ?>
<div class="kmcart-total result default_total">
	<?php if($this->cart->shipping_sum > 0){ ?>
	<div class="total lead pull-right">
		Доставка: <span><?php echo $this->cart->shipping_sum_val; ?></span>
	</div>
	<?php } ?>
	<div class="clearfix"></div>
	<div class="total lead pull-right">
		Итого: <span><?php echo $this->cart->total_sum_val; ?></span>
	</div>
	<div class="clearfix"></div>
    <input type="hidden" name="task" value="cart.close_order" />
    <input type="hidden" name="cost_shipping" value="<?php echo $this->cart->total_sum; ?>" id="deliverycost" />
    <input type="hidden" name="cost" value="<?php echo $this->cart->shipping_sum; ?>" id="total_cost" />
	<input type="submit" class="btn btn-success btn-large pull-right" value="Оформить заказ" />
</div>