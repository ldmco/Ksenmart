<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<tr class="list_item <?php echo ($this->item->published==0?'disabled':'')?>">
	<td class="art"><?php echo $this->item->product_code?></td>
	<td class="name stretch">
		<div class="img">
			<img src="<?php echo $this->item->img?>">
		</div>
		<div class="descr">
			<a class="product_title"><?php echo $this->item->title?></a>
		</div>
	</td>
	<td class="price">
		<span><?php echo KSMPrice::showPriceWithoutTransform($this->item->price,$this->item->price_type)?></span>
	</td>
	<td class="storage">
		<span><?php echo $this->item->in_stock?></span>
	</td>
	<td class="storage">
		<span><?php echo $this->item->hits?></span>
	</td>
	<td class="storage"><span><?php echo $this->item->carted?></span></td>
	<td class="storage"><span><?php echo $this->item->ordered?></span></td>
	<input type="hidden" class="id" name="items[<?php echo $this->item->id;?>][id]" value="<?php echo $this->item->id;?>">	
</tr>