<?
defined( '_JEXEC' ) or die( '=;)' );
if ($this->cart->discount=='')
{
?>
<div class="coupons">
	<form method="post">
		<div><strong>Хотите уменьшить стоимость заказа?</strong></div>
		<br>
		<div>
			<span>Если знаете — введите код скидки:</span>
			<input type="text" class="inputbox" name="discount_code" value="" />
			<input type="submit" class="st_button btn btn-default" value="Пересчитать" />
		</div>
		<input type="hidden" name="task" value="cart.set_discount">	
	</form>			
</div>
<?
}
else
{
?>
<div class="coupons_info">
	Скидка: <?=$this->cart->discount_sum_val?>
</div>
<?
}
?>