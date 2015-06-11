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
			<th class="art"><span class="sort_field" rel="product_code"><?php echo JText::_('ksm_reports_product_code')?></span></th>
			<th class="name stretch"><span class="sort_field" rel="title"><?php echo JText::_('ksm_reports_product_name')?></span></th>
			<th class="price"><span class="sort_field" rel="price"><?php echo JText::_('ksm_reports_product_price')?></span></th>
			<th class="storage"><span class="sort_field" rel="in_stock"><?php echo JText::_('ksm_reports_product_in_stock')?></span></th>
			<th class="storage"><span class="sort_field" rel="watched"><?php echo JText::_('ksm_reports_product_watched')?></span></th>
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
<script>
var ProductsList=new KMList({
	'view':'reports',
	'object':'ProductsList',
	'limit':<?php echo $this->state->get('list.limit');?>,
	'limitstart':<?php echo $this->state->get('list.start');?>,
	'total':<?php echo $this->total;?>,
	'order_type':'<?php echo $this->state->get('order_type');?>',
	'order_dir':'<?php echo $this->state->get('order_dir');?>',
	'table':'products',
	'sortable':false
});
</script>