<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div class="top">
	<a class="adds km-modal" rel='{"x":"90%","y":"90%"}' href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=categories&layout=category&tmpl=component');?>"><?php echo JText::_('ksm_categories_add_category')?></a>
    <a class="button copy-items inactive"><?php echo JText::_('KS_COPY'); ?></a>
    <a class="button delete-items inactive"><?php echo JText::_('KS_REMOVE'); ?></a>
</div>