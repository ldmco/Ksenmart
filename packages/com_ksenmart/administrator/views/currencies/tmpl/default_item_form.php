<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<tr class="list_item">
	<td class="name stretch">
		<div class="descr">
			<a class="km-modal" rel='{"x":"500","y":"390"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=currencies&layout=currency&id='.$this->item->id.'&tmpl=component'); ?>" ><?php echo $this->item->title?></a>
			<p>
				<a rel='{"x":"500","y":"390"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=currencies&layout=currency&id='.$this->item->id.'&tmpl=component'); ?>" class="edit km-modal"><?php echo JText::_('ksm_edit')?></a>
			</p>
		</div>	
	</td>
	<td class="currency_template">
		<?php echo $this->item->template;?>	
	</td>
	<td class="del"><a href="#"></a></td>
	<input type="hidden" class="id" name="items[<?php echo $this->item->id; ?>][id]" value="<?php echo $this->item->id ?>">
</tr>