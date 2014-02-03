<? defined( '_JEXEC' ) or die( '=;)' );
    $Itemid = KMSystem::getShopItemid();
    $link   = JRoute::_('index.php?option=com_ksenmart&view=shopopencart&Itemid='.$Itemid);
?>
<a href="<?php echo $link; ?>">
	<b class="muted">Корзина [0]</b>
	<small class="muted">Перетащите сюда товары</small>
</a>