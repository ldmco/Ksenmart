<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<tr class="list_item <?php echo ($this->item->published==0?'disabled':'');?>">
	<td class="handler"><span></span></td>
	<td class="check"><input type="checkbox" class="check-item" /></td>		
	<td class="name stretch sort-handler">
		<div class="descr">	
			<a class="km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=properties&layout=property&id='.$this->item->id.'&tmpl=component');?>"><?php echo $this->item->title;?></a>
			<p>
				<a class="edit km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=properties&layout=property&id='.$this->item->id.'&tmpl=component');?>"><?php echo JText::_('ksm_edit');?></a>
			</p>
		</div>		
	</td>
	<td class="sort changeble">
		<span class="ordering"><?php echo $this->item->ordering; ?></span>
		<p>
			<input type="text" class="inputbox ordering" name="items[<?php echo $this->item->id;?>][ordering]" value="<?php echo $this->item->ordering;?>">
		</p>
	</td>	
	<td class="stat" align="center"><input type="checkbox" class="status" name="items[<?php echo $this->item->id;?>][published]" value="1" <?php echo ($this->item->published==1?'checked':'');?> /></td>
	<td class="del"><a></a></td>
	<input type="hidden" class="id" name="items[<?php echo $this->item->id;?>][id]" value="<?php echo $this->item->id;?>">
</tr>