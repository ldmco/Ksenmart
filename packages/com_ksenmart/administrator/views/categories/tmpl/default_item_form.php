<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<tr class="list_item <?php echo ($this->item->published==0?'disabled':'')?> list_item_<?php echo $this->item->id; ?> <?php echo ($this->item->parent_id!=0?'child_item child_item_'.$this->item->parent_id:'');?>" data-level="<?php echo $this->item->level; ?>" data-parent="<?php echo $this->item->parent_id; ?>">
	<td class="handler"><span></span></td>
	<td class="check"><input type="checkbox" class="check-item" /></td>		
	<td class="name stretch"> 
		<div style="margin-left:<?php echo $this->item->level*30-30; ?>px" class="img <?php echo ($this->item->deeper?'prod-parent prod-parent-closed':'')?>">
			<img class="medium_img" src="<?php echo $this->item->medium_img; ?>" style="display: none;" >
			<a href="<?php echo $this->item->medium_img; ?>" class="show_product_photo">
				<div class="set_img">
					<img class="min_img" rel="<?php echo $this->item->id?>" src="<?php echo $this->item->small_img; ?>" title="<?php echo $this->item->title?>">
				</div>	
			</a>
		</div>
		<div class="descr">
			<a rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=categories&layout=category&id='.$this->item->id.'&tmpl=component'); ?>" class="km-modal"><?php echo $this->item->title; ?></a>
			<p>
				<a rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=categories&layout=category&id='.$this->item->id.'&tmpl=component'); ?>" class="edit km-modal"><?php echo JText::_('ksm_edit')?></a>
			</p>
		</div>
	</td>
	<td class="sort changeble">
		<span class="ordering"><?php echo $this->item->ordering?></span>
		<p><input type="text" class="inputbox ordering" name="items[<?php echo $this->item->id; ?>][ordering]" value="<?php echo $this->item->ordering?>"></p>
	</td>
	<td class="stat"><input type="checkbox" class="status" name="items[<?php echo $this->item->id; ?>][published]" value="1" <?php echo ($this->item->published==1?'checked':'')?>></td>
	<td class="del"><a href="#"></a></td>
	<input type="hidden" class="id" name="items[<?php echo $this->item->id; ?>][id]" value="<?php echo $this->item->id ?>">
</tr>