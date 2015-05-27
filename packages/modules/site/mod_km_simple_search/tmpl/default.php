<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

$Itemid = KSSystem::getShopItemid();
$value  = JRequest::getVar('value','');
?>
<div class="search">
    <form action="<?php echo JRoute::_('index.php?option=com_ksenmart&view=search&Itemid=' . $Itemid); ?>" method="POST" id="simple-search-form">
        <div class="input-append row-fluid">
            <input type="search" class="inputbox span11" name="value" placeholder="<?php echo JText::_('MOD_KM_SIMPLE_SEARCH_SEARCHFIELD_PLACEHOLDER'); ?>" value="<?php echo $value; ?>" autocomplete="off" />
            <button type="submit" class="button btn"><?php echo JText::_('MOD_KM_SIMPLE_SEARCH_SEARCH'); ?></button>
        </div>

        <input type="hidden" name="option" value="com_ksenmart" />
        <input type="hidden" name="view" value="search" />
        <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
    </form>
    <div class="inner_search_wrapp">
        <div id="search_result">
            <h4 class="empty_result clearfix"><?php echo JText::_('MOD_KM_SIMPLE_SEARCH_NO_RESULTS'); ?></h4>
            <div class="items"></div>
            <div class="other_result">
                <a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=search&Itemid=' . $Itemid.'&value='); ?>" title="<?php echo JText::_('MOD_KM_SIMPLE_SEARCH_OTHER_RESULTS'); ?>" class="button"><?php echo JText::_('MOD_KM_SIMPLE_SEARCH_OTHER_RESULTS'); ?></a>
            </div>
        </div>
    </div>
</div>