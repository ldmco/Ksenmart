<?php defined( '_JEXEC' ) or die( '=;)' );
    $Itemid = KSSystem::getShopItemid();
    $link = JRoute::_('index.php?option=com_ksenmart&view=cart&Itemid='.$Itemid);
?>
<a href="<?php echo $link; ?>" rel="nofollow">
          <strong class="opancart"></strong>
                <span class="shopping_cart_title"></span>
                <span class="ajax_cart_quantity hidden" style="display: none;"><?php echo $this->cart->total_prds; ?></span>
                <span class="ajax_cart_no_product"><?php echo $this->cart->total_prds; ?></span>
            </a>
