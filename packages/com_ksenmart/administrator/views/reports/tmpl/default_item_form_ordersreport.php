<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<tr class="list_item">
	<td class="order_number"><?php echo $this->item->id?></td>
	<td class="order_name name">
		<div class="img">
			<img class="min_img" rel="<?php echo $this->item->id?>" src="<?php echo $this->item->user->small_img; ?>" title="<?php echo $this->item->user->name?>">
		</div>
		<?php echo $this->item->customer_info?>
	</td>
	<td class="order_cost" align="center"><?php echo KSMPrice::showPriceWithTransform($this->item->cost)?></td>
	<td class="order_date" align="center"><?php echo KSFunctions::formatDate($this->item->date_add)?></td>
	<td class="order_status" align="center"><?php echo $this->item->status_name;?></td>
</tr>
