<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<tr class="list_item">
	<td class="order_number"><?php echo $this->item->id?></td>
	<td class="name stretch">
		<div class="img prod-parent prod-parent-closed">
			<img class="medium_img" src="<?php echo $this->item->user->medium_img; ?>" style="display: none;" >
			<a href="<?php echo $this->item->user->medium_img; ?>" class="show_product_photo">
				<img class="min_img" rel="<?php echo $this->item->id?>" src="<?php echo $this->item->user->small_img; ?>" title="<?php echo $this->item->user->name?>">
			</a>
		</div>
		<a class="km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=orders&layout=order&id='.$this->item->id.'&tmpl=component'); ?>"><?php echo $this->item->customer_info?></a>
	</td>
	<td class="order_cost"><?php echo $this->item->cost_val?></td>
	<td class="order_status" align="center"><?php echo $this->item->status_name?></td>
	<td class="order_date"><?php echo $this->item->date?></td>
	<td class="del"><a href="#"></a></td>
	<input type="hidden" class="id" name="items[<?php echo $this->item->id; ?>][id]" value="<?php echo $this->item->id ?>">
</tr>