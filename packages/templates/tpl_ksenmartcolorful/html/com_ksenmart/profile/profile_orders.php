<?php defined('_JEXEC') or die; ?>
<div class="items">
	<?php if (count($this->orders)>0) { ?>
	<table class="table table-hover">
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
		<?php $kk = 0; ?>
        <?php foreach($this->orders as $order) { ?>
            <?php $this->order = $order; ?>
            <?php $kk++; ?>
			<tr data-count="<?php echo $kk; ?>" class="order_tr">
				<td><a href="javascript:void(0);">Заказ № <?php echo $this->order->id; ?></a></td>
				<td><?php echo $this->order->shipping_title; ?></td>
				<td><?php echo JText::_($this->order->status_name); ?></td>
				<td><?php echo $this->order->cost_val; ?></td>
				<td><?php echo KSSystem::formatCommentDate($this->order->date_add); ?></td>
			</tr>
			<tr class="profile_order noTransition order_dropdows_<?php echo $kk; ?>" style="display: none;">
				<td class="profile_order_td" colspan="5">
					<?php echo $this->loadTemplate('orders_item'); ?>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<?php }else{ ?>
		<div class="order-item">
			<h2 align="center">У вас нет заказов</h2>
		</div>
	<?php } ?>
</div>