<?php defined( '_JEXEC' ) or die; ?>
<div id="window-inner">
	<?php require('map.php');?>
	<form method="post" class="order_form">
		<div class="text">
			<div class="form">
				<?php require('openorder_contact.php'); ?>
				<?php require('openorder_shipping.php'); ?>
			</div>
			<input type="submit" class="order_button" value="Заказать" />
		</div>
		<input type="hidden" name="ymaphtml" value="" id="ymaphtml" />
		<input type="hidden" name="deliverycost" id="deliverycost" value="" />
		<input type="hidden" name="id" id="order_prd_id" value="<?php echo $this->product->id; ?>" />
		<input type="hidden" name="task" value="order.close_order" />
	</form>	
	<br clear="both" />
</div>