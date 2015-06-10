<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<tr class="list_item <?php echo ($this->item->published==0?'disabled':'')?>">
	<td class="comment_rate">
		<?php for($k=1;$k<6;$k++):?>
			<?php if (floor($this->item->rate)>=$k):?>
				<img src="<?php echo JURI::base()?>components/com_ksenmart/assets/images/c-star.png" alt="" />
			<?php else:?>
				<img src="<?php echo JURI::base()?>components/com_ksenmart/assets/images/c-star2.png" alt="" />
			<?php endif;?>
		<?php endfor;?>	
	</td>
	<td class="comment_name"><?php echo $this->item->name?></td>
	<td class="name stretch">
		<div class="descr">
			<a class="km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=comments&layout=comment&id='.$this->item->id.'&tmpl=component'); ?>" ><?php echo $this->item->comment?></a>
			<p>
				<a rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=comments&layout=comment&id='.$this->item->id.'&tmpl=component'); ?>" class="edit km-modal"><?php echo JText::_('ksm_edit')?></a>
			</p>
		</div>	
	</td>
	<td class="comment_date"><?php echo KSFunctions::getStandartDate($this->item->date_add)?></td>
	<td class="stat" align="center"><input type="checkbox" class="status" name="items[<?php echo $this->item->id;?>][published]" value="1" <?php echo ($this->item->published==1?'checked':'');?> /></td>
	<td class="del"><a href="#"></a></td>
	<input type="hidden" class="id" name="items[<?php echo $this->item->id; ?>][id]" value="<?php echo $this->item->id ?>">
</tr>