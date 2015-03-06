<?php defined('_JEXEC') or die(); ?>
<div id="order-detail-content" class="table_block">
	<table id="cart_summary" class="std">
		<thead>
		</thead>
		<tfoot>
			<tr class="cart_total_price cart_last_tr">
				<td class="total_price_container" id="total_price_container">Всего:</td>	
				<td class="price total_cost_items" id="total_price"><span><? echo $this->cart->products_sum_val?></span></td>
			</tr>
		</tfoot>
		<tbody>
	<?php foreach($this->cart->items as $item){ ?>
		<?php echo $this->loadTemplate('item', null, array('item' => $item)); ?>
	<?php } ?>
	</tbody>
</table>
<div class="pull-right">

    <a href="javascript:void(0);" class="btn btn-success btn-large noTransition" id="order_info_show">Оформить заказ</a>
</div>
<div class="clearfix"></div>
</div>