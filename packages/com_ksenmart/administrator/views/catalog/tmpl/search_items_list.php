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
			<th class="art"><span class="sort_field" rel="product_code"><?php echo JText::_('ksm_catalog_product_code')?></span></th>
			<th class="name stretch"><span class="sort_field" rel="title"><?php echo JText::_('ksm_catalog_product_name')?></span></th>
			<th class="price"><span class="sort_field" rel="price"><?php echo JText::_('ksm_catalog_product_price')?></span></th>
			<th class="storage"><span class="sort_field" rel="in_stock"><?php echo JText::_('ksm_catalog_product_in_stock')?></span></th>
			<th class="sort"><span class="sort_field" rel="ordering"><?php echo JText::_('ksm_catalog_product_ordering')?></span></th>
			<th class="add"></th>
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
<div class="pagi"></div>	