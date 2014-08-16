<? defined( '_JEXEC' ) or die( '=;)' );
    $Itemid = KSSystem::getShopItemid();
    $link   = JRoute::_('index.php?option=com_ksenmart&view=cart&Itemid='.$Itemid);
?>
<a href="<?php echo $link; ?>">
	<b class="muted">Корзина [0]</b>
	<small class="muted">Перетащите сюда товары</small>
</a>