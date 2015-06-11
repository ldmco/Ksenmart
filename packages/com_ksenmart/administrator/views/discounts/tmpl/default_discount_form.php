<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<tr class="list_item <?php echo ($this->discount->enabled==0?'disabled':'')?>" discount_id="<?php echo $this->discount->id?>">
	<td class="discount_name name">
		<div class="descr">	
			<a class="discount_title"><?php echo $this->discount->title?></a>
			<p style="visibility:hidden;"><a class="edit discount_title"><?php echo JText::_('edit')?></a></p>
		</div>	
	</td>
	<td class="discount_type"><?php echo JText::_($this->discount->plugin_name)?></td>
	<td class="stat"><input type="checkbox" name="enabled[]" value="1" <?php echo ($this->discount->enabled==1?'checked':'')?>></td>
	<td class="del"><a href="#"></a></td>
</tr>