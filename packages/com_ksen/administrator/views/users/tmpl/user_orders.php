<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<?php foreach($this->orders as $item):?>
<tr class="list_item list_item_parents user_orders_<?php echo $item->user_id;?>">
	<td class="name stretch"> 
		<?php echo JText::sprintf('ks_users_order_title', $item->id);?>
		<div style="float:right;">
			<?php echo $item->cost_val;?>
		</div>
	</td>
	<td class="user_login">
		<?php echo $item->status_name;?>
	</td>
	<td class="user_email">
		<?php echo $item->date;?>
	</td>
	<td class="user_subsriber"></td>
	<td class="del"></td>
</tr>
<?php endforeach;?>