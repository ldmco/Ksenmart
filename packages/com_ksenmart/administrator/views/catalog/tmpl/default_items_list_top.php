<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="top clearfix">
	<a class="adds km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=catalog&layout=product&tmpl=component');?>"><?php echo JText::_('ksm_catalog_add_product')?></a>
	<a class="button copy-items"><?php echo JText::_('KS_COPY'); ?></a>
	<a class="button delete-items"><?php echo JText::_('KS_REMOVE'); ?></a>						
	<div class="drag">
		<form id="add-set-form">
			<div class="drop"><?php echo JText::_('ksm_catalog_add_set_string')?></div>
			<a class="ok"><?php echo JText::_('ksm_ok')?></a>
		</form>		
	</div>
</div>