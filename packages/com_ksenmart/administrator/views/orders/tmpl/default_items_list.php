<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<table class="cat" width="100%" cellspacing="0">	
	<thead>
		<tr>
			<th class="order_number"><span class="sort_field" rel="id"><?php echo JText::_('ksm_orders_number')?></span></th>
			<th class="name stretch" align="left"><?php echo JText::_('ksm_orders_customer')?></th>
			<th class="order_cost"><span class="sort_field" rel="cost"><?php echo JText::_('ksm_orders_cost')?></span></th>
			<th class="order_status"><?php echo JText::_('ksm_status')?></th>
			<th class="order_date"><?php echo JText::_('ksm_orders_date')?></th>
			<th class="del"><span></span></th>
		</tr>
	</thead>	
	<tbody>
	<?php if (count($this->items)>0):?>
		<?php foreach($this->items as $item):?>
			<?php $this->item=&$item;?>
			<?php echo $this->loadTemplate('item_form');?>
		<?php endforeach;?>
	<?php else:?>
		<?php echo $this->loadTemplate('no_items');?>
	<?php endif;?>
	</tbody>
</table>
<div class="pagi">
</div>