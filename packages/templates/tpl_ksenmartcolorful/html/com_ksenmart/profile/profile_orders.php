<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="items">
	<?php if (count($this->orders) > 0): ?>
	<table class="table table-hover">
		<thead>
			<tr>
				<th><?php echo JText::_('KSM_PROFILE_ORDERS_PRODUCT'); ?></th>
				<th><?php echo JText::_('KSM_PROFILE_ORDERS_SHIPPING'); ?></th>
				<th><?php echo JText::_('KSM_PROFILE_ORDERS_STATUS'); ?></th>
				<th><?php echo JText::_('KSM_PROFILE_ORDERS_SUM'); ?></th>
				<th><?php echo JText::_('KSM_PROFILE_ORDERS_DATE'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php $kk = 0; ?>
        <?php foreach($this->orders as $order): ?>
            <?php $this->order = $order; ?>
            <?php $kk++; ?>
			<tr data-count="<?php echo $kk; ?>" class="order_tr">
				<td><a href="javascript:void(0);"><?php echo JText::sprintf('KSM_PROFILE_ORDERS_NUMBER', $this->order->id); ?></a></td>
				<td><?php echo $this->order->shipping_title; ?></td>
				<td><?php echo JText::_($this->order->status_name); ?></td>
				<td><?php echo $this->order->costs['total_cost_val']; ?></td>
				<td><?php echo KSSystem::formatCommentDate($this->order->date_add); ?></td>
			</tr>
			<tr class="profile_order noTransition order_dropdows_<?php echo $kk; ?>" style="display: none;">
				<td colspan="5">
					<?php echo $this->loadTemplate('orders_item'); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php else: ?>
		<div class="order-item">
			<h2 align="center"><?php echo JText::_('KSM_PROFILE_ORDERS_NO_ORDERS'); ?></h2>
		</div>
	<?php endif; ?>
</div>