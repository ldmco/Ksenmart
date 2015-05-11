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
			<th class="comment_rate"><span class="sort_field" rel="rate"><?php echo JText::_('ksm_comments_rate')?></span></th>
			<th class="comment_name"><?php echo JText::_('ksm_comments_name')?></th>
			<th class="name stretch"><?php echo JText::_('ksm_comments_text')?></th>
			<th class="comment_date"><span class="sort_field" rel="date_add"><?php echo JText::_('ksm_comments_date')?></span></th>
			<th class="stat"><?php echo JText::_('ksm_status')?></th>
			<th class="del"><span></span></th>
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