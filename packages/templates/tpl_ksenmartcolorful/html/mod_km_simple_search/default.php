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
    <form action="<?php echo JRoute::_('index.php?option=com_ksenmart&view=search&Itemid='.$Itemid); ?>" method="get" id="simple-search-form">
        <div class="input-append row-fluid">
            <div class="span11"><input type="search" class="inputbox " name="value" placeholder="Начните вводить название товара" value="<?php echo $value; ?>" autocomplete="off" /></div>
            <div class="span1"><button type="submit" class="button btn"><b></b><span>Поиск</span></button></div>
        </div>
        
        <input type="hidden" name="option" value="com_ksenmart" />
        <input type="hidden" name="view" value="search" />
        <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
    </form>
    <div class="inner_search_wrapp">
        <div id="search_result" style="display:none">
            <h4 class="empty_result clearfix">Нет соответсвующих товаров</h4>
            <div class="items"></div>
            <div class="other_result">
                <a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=search&Itemid='.$Itemid.'&value='); ?>" title="Остальные результаты поиска" class="button">Остальные результаты поиска</a>
            </div>
        </div>
    </div>
</div>