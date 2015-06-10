<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<table class="table table_sub">
	<thead>
		<th><?php echo JText::_('KSM_PHOTO_COLUMN_TITLE'); ?></th>
		<th><?php echo JText::_('KSM_PRODUCT_COLUMN_TITLE'); ?></th>
		<th><?php echo JText::_('KSM_QUANTITY_COLUMN_TITLE'); ?></th>
		<th><?php echo JText::_('KSM_PRICE_COLUMN_TITLE'); ?></th>
		<th><?php echo JText::_('KSM_SUBTOTAL_COLUMN_TITLE'); ?></th>
	</thead>
	<tbody>
		<?php foreach($this->order->items as $item) {
			$this->order_item = $item; ?>
			<tr>
				<?php echo $this->loadTemplate('order_item'); ?>
			</tr>
		<?php } ?>
		<tr>
			<td colspan="5">
				<form method="POST" class="order_form clearfix">
					<?php if ($this->order->status_id == 2){ ?>
					<input type="submit" class="st_button btn btn-success" value="<?php echo JText::_('KSM_CART_CHECKOUT_TEXT'); ?>" />
					<?php } else { ?>
                    <input type="submit" class="st_button btn btn-success pull-left" value="<?php echo JText::_('KSM_PROFILE_REPEAT_ORDER_TEXT'); ?>" />
					<dl class="dl-horizontal pull-right">
						<dt><?php echo JText::_('KSM_PROFILE_ORDERS_ORDER_ADDRESS'); ?></dt>
						<dd><?php echo $this->order->address_fields; ?></dd>
					</dl>
					<?php } ?>
					<input type="hidden" name="id" value="<?php echo $this->order->id; ?>" />
					<input type="hidden" name="task" value="profile.load_order" />
				</form>
			</td>
		</tr>
	</tbody>
</table>