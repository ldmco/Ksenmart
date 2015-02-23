<?php defined('_JEXEC') or die;
    $Itemid = KSSystem::getShopItemid();
    $value  = JRequest::getVar('value', '');
?>
<div class="search">
    <form action="<?php echo JRoute::_('index.php?option=com_ksenmart&view=search&Itemid='.$Itemid); ?>" method="get" id="simple-search-form">
    	<input type="search" class="inputbox" name="value" placeholder="Начните вводить название продукта" value="<?php echo $value; ?>" autocomplete="off" />
    	<button type="submit" class="button"><span></span></button>
        
        <input type="hidden" name="option" value="com_ksenmart" />
        <input type="hidden" name="view" value="search" />
        <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
    </form>
<?php if ($menu->getActive() != $menu->getDefault()){ ?>
    <div class="checkb"><input type="checkbox" id="chckbx" /> <label for="chckbx">Только в текущем разделе</label></div>
<?php } ?>
    <div id="search_result">
        <h4 class="empty_result clearfix">Нет соответсвующих товаров</h4>
        <div class="items"></div>
        <div class="other_result">
            <a href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=search&Itemid='.$Itemid.'&value='); ?>" title="Остальные результаты поиска" class="button">Остальные результаты поиска</a>
        </div>
    </div>
</div>