<?php defined('_JEXEC') or die; ?>
<div class="items">
	<?php if (count($this->orders)>0) { ?>
	<table class="table">
		<thead>
			<tr>
				<th>Товар</th>
				<th>Доставка</th>
				<th>Статус</th>
				<th>Сумма</th>
				<th>Дата</th>
			</tr>
		</thead>
		<tbody>
		<?php
        $kk = 0;
        foreach($this->orders as $order) {
            $kk++;
		?>		
			<tr data-count="<?php echo $kk; ?>" class="order_tr">
				<td><a href="javascript:void(0);">Заказ № <?php echo $order->id; ?></a></td>
				<td><?php echo $order->shipping_title; ?></td>
				<td><?php echo JText::_($order->status_name); ?></td>
				<td><?php echo $order->cost_val; ?></td>
				<td><?php echo KSSystem::formatCommentDate($order->date_add); ?></td>
			</tr>
			<tr class="profile_order noTransition order_dropdows_<?php echo $kk; ?>" style="display: none;">
				<td colspan="5">
					<?php require('profile_order.php');?>
				</td>
			</tr>
		<? } ?>
		</tbody>
	</table>
	<? }else{ ?>
		<div class="order-item">
			<h2 align="center">У вас нет заказов</h2>
		</div>
	<? } ?>
</div>