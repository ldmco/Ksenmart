<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<?php foreach($this->order->items as $item):?>
<tr class="list_item list_item_parents order_items_<?php echo $this->order->id;?>">
	<td class="order_number">&nbsp;</td>
	<td class="name stretch">
		<div class="col1">
			<div class="img"><img alt="" src="<?php echo $item->small_img;?>"></div>
			<div class="price"><?php echo $item->val_price;?></div>		
		</div>	
		<div class="col2">
			<div class="name"><a href="#ссылка-на-товар"><?php echo $item->title;?></a></div>		
			<div class="product_code"><?php echo JText::_('ksm_catalog_product_code').':  '.$item->product_code;?></div>	
			<div class="properties">
			<?php foreach($item->properties as $property):?>
				<?php if ($property->edit_price || count($property->values)>1 || $property->view=='checkbox'):?>
					<div class="row">
						<label class="inputname"><?php echo $property->title;?> : </label>
						<?php $selected=false;?>
						<?php foreach($property->values as $value):?>
							<?php if ($value->selected):?>
								<?php echo $value->title;?>&nbsp;
								<?php $selected=true;?>
							<?php endif;?>
						<?php endforeach;?>
						<?php if (!$selected):?>
							<?php echo JText::_('ksm_orders_order_item_no_property_value');?>
						<?php endif;?>
					</div>							
				<?php endif;?>
			<?php endforeach;?>	
			</div>
		</div>
	</td>
	<td class="order_cost">
		<div class="quants"><?php echo JText::_('ksm_orders_order_item_count').$item->count;?></div>
		<div class="total"><?php echo JText::_('ksm_orders_order_item_total_price').$item->val_total_price;?></div>
	</td>
	<td class="order_status" align="center">&nbsp;</td>
	<td class="order_date">&nbsp;</td>
	<td class="del">&nbsp;</td>
</tr>
<?php endforeach;?>