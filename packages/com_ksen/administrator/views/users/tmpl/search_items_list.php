<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="top clearfix">
	<div class="drag">
		<form id="add-form">
			<div class="drop"><?php echo JText::_('ks_users_add_search_string')?></div>
			<input type="hidden" name="items_tpl" value="<?php echo $this->state->get('items_tpl');?>">
			<input type="hidden" name="items_to" value="<?php echo $this->state->get('items_to');?>">
			<input type="hidden" name="task" value="users.get_search_items_html">
		</form>	
	</div>
</div>	
<table class="sortable-helper" cellspacing="0">
</table>
<table class="cat" width="100%" cellspacing="0">	
	<thead>
		<tr>
			<th class="name stretch"><span class="sort_field" rel="name"><?php echo JText::_('ks_users_name')?></span></th>
			<th class="user_login"><span class="sort_field" rel="username"><?php echo JText::_('ks_users_login')?></span></th>
			<th class="user_email"><span class="sort_field" rel="email"><?php echo JText::_('ks_users_email')?></span></th>
			<th class="user_subsriber"><?php echo JText::_('ks_users_subsriber')?></th>
			<th class="add"></th>
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