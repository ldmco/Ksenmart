<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<tr class="list_item <?php echo ($this->item->published==0?'disabled':'')?> <?php echo ($this->item->parent_id!=0?'child_item child_item_'.$this->item->parent_id:'');?> <?php echo (isset($this->item->set_id)?'child_item child_item_'.$this->item->set_id:'');?>" >
	<td class="art"><?php echo $this->item->product_code?></td>
	<td class="name stretch"> 
		<div class="img <?php echo ($this->item->parent_id!=0?'prod-parent prod-parent-child':'')?> <?php echo (isset($this->item->set_id)?'prod-set prod-set-child':'')?> <?php echo ($this->item->type=='set'?'prod-set prod-set-closed':'')?> <?php echo ($this->item->is_parent==1?'prod-parent prod-parent-closed':'')?>">
			<img class="medium_img" src="<?php echo $this->item->medium_img; ?>" style="display: none;" >
			<a href="<?php echo $this->item->medium_img; ?>" class="show_product_photo">
				<?php if ($this->item->type!='set'):?>
					<img class="min_img" rel="<?php echo $this->item->id?>" src="<?php echo $this->item->small_img; ?>" title="<?php echo $this->item->title?>">
				<?php else:?>
				<div class="set_img">
					<img class="min_img" rel="<?php echo $this->item->id?>" src="<?php echo $this->item->small_img; ?>" title="<?php echo $this->item->title?>">
				</div>	
				<?php endif;?>
			</a>
		</div>
		<div class="descr">
			<?php echo $this->item->title?>
		</div>
	</td>
	<td class="price">
		<span><?php echo KSMPrice::showPriceWithoutTransform($this->item->price,$this->item->price_type)?></span>
	</td>
	<td class="storage">
		<span><?php echo $this->item->in_stock?></span>
	</td>
	<td class="sort">
		<span class="ordering"><?php echo $this->item->ordering?></span>
	</td>
	<td class="add">
		<a href="#" class="add"></a>
	</td>
	<input type="hidden" class="id" name="items[<?php echo $this->item->id; ?>][id]" value="<?php echo $this->item->id ?>">
</tr>