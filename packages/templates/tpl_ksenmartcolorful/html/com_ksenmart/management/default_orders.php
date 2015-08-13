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
		<?php
        $kk = 0;
        foreach($this->orders as $order) {
            $this->order = $order;
            $kk++;
		?>		
			<tr data-count="<?php echo $kk; ?>" data-order_id="<?php echo $this->order->id; ?>" class="order_tr">
				<td><a href="javascript:void(0);">Заказ № <?php echo $this->order->id; ?></a></td>
				<td><?php echo $this->order->shipping_title; ?></td>
				<td class="edit_order_status">
                    <div class="current_order_status"><?php echo JText::_($this->order->status_name); ?></div>
                    <form class="hide">
                        <div class="row-fluid">
                            <select name="status" required="true">
                                <?php foreach($this->statuses as $status){ ?>
                                <option value="<?php echo $status->id; ?>"<?php echo $this->order->status_name==$status->title?' selected':''; ?>><?php echo JText::_($status->title); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-success edit_order_status-save"><?php echo JText::_('KSM_SAVE'); ?></button>
                            <a href="javascript:void(0);" class="btn btn-danger edit_order_status-cancel"><?php echo JText::_('KSM_CANCEL'); ?></a>
                        </div>
                    </form>
                </td>
				<td><?php echo $this->order->cost_val; ?></td>
				<td><?php echo KSSystem::formatCommentDate($this->order->date_add); ?></td>
			</tr>
			<tr class="profile_order noTransition order_dropdows_<?php echo $kk; ?>" style="display: none;">
				<td colspan="5">
					<?php echo $this->loadTemplate('order'); ?>
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