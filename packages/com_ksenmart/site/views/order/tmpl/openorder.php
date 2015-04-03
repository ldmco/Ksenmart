<?php defined( '_JEXEC' ) or die; ?>
<div id="open-order">
	<form method="POST" class="form-horizontal">
		<?php require_once('openorder_short_contact.php'); ?>
    	<div class="control-group">
    		<div class="controls">
                <input type="submit" class="st_button order_button btn btn-success" value="В корзину" />
        		<div class="ordering_info">
        			Вы сможете отредактировать заказ позднее
        		</div>
    		</div>
    	</div>
        
		<input type="hidden" name="id" id="order_prd_id" value="<?php echo $this->product->id; ?>" />
		<input type="hidden" name="task" value="order.create_order" />
	</form>	
</div>