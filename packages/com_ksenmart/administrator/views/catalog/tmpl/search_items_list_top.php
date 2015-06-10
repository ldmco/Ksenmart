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
			<div class="drop"><?php echo JText::_('ksm_search_add_search_string')?></div>
			<input type="hidden" name="items_tpl" value="<?php echo $this->state->get('items_tpl');?>">
			<input type="hidden" name="items_to" value="<?php echo $this->state->get('items_to');?>">
			<input type="hidden" name="task" value="catalog.get_search_items_html">
		</form>	
	</div>
</div>