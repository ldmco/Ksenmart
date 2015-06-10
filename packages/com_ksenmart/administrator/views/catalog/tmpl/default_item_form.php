<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<tr class="list_item <?php echo ($this->item->published==0?'disabled':'')?> <?php echo ($this->item->parent_id!=0?'child_item child_item_'.$this->item->parent_id:'');?> <?php echo (isset($this->item->set_id)?'child_item child_item_'.$this->item->set_id:'');?>" >
	<td class="art sort-handler"><?php echo $this->item->product_code?></td>
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
			<a rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&layout='.$this->item->type.'&id='.$this->item->id.'&tmpl=component'); ?>" class="km-modal"><?php echo $this->item->title; ?></a>
			<p>
				<a rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&layout='.$this->item->type.'&id='.$this->item->id.'&tmpl=component'); ?>" class="edit km-modal"><?php echo JText::_('ksm_edit')?></a>
				<?php if ($this->item->type!='set'):?>
				<a class="add_to_set"><?php echo JText::_('ksm_catalog_add_to_set')?></a>
				<?php endif;?>
			</p>
		</div>
	</td>
	<td class="price changeble">
		<span><?php echo KSMPrice::showPriceWithoutTransform($this->item->price,$this->item->price_type)?></span>
		<p><?php echo KSMPrice::showPriceWithoutTransform('<input type="text" name="items['.$this->item->id.'][price]" class="inputbox" value="'.$this->item->price.'">',$this->item->price_type)?></p>
	</td>
	<td class="storage changeble">
		<span><?php echo $this->item->in_stock?></span>
		<p><input type="text" class="inputbox" name="items[<?php echo $this->item->id; ?>][in_stock]" value="<?php echo $this->item->in_stock?>"></p>
	</td>
	<td class="sort changeble">
		<span class="ordering"><?php echo $this->item->ordering?></span>
		<p><input type="text" class="inputbox ordering" name="items[<?php echo $this->item->id; ?>][ordering]" value="<?php echo $this->item->ordering?>"></p>
	</td>
	<td class="sale"><input type="checkbox" name="items[<?php echo $this->item->id; ?>][promotion]" value="1" <?php echo ($this->item->promotion==1?'checked':'')?>></td>
	<td class="stat"><input type="checkbox" class="status" name="items[<?php echo $this->item->id; ?>][published]" value="1" <?php echo ($this->item->published==1?'checked':'')?>></td>
	<td class="del"><a href="#"></a></td>
	<input type="hidden" class="id" name="items[<?php echo $this->item->id; ?>][id]" value="<?php echo $this->item->id ?>">
</tr>