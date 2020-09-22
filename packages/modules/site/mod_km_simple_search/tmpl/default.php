<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
?>
<div data-moduletitle="<?php echo $module->title; ?>" class="ksm-module-search ksm-block">
    <form action="<?php echo JRoute::_('index.php?option=com_ksenmart&view=search&Itemid=' . $Itemid); ?>" method="post">
		<input type="search" class="ksm-module-search-input" name="title" placeholder="<?php echo JText::_('MOD_KM_SIMPLE_SEARCH_SEARCHFIELD_PLACEHOLDER'); ?>" value="<?php echo $value; ?>" autocomplete="off" />
		<button type="submit"><?php echo JText::_('MOD_KM_SIMPLE_SEARCH_SEARCH'); ?></button>
        <div id="ksm-module-search-result" class="ksm-module-search-result"></div>
    </form>
</div>