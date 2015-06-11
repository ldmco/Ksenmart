<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

$Itemid = KSSystem::getShopItemid();
$link = JRoute::_('index.php?option=com_ksenmart&view=cart&Itemid='.$Itemid);
?>
<a href="<?php echo $link; ?>">
	<b class="muted"><?php echo JText::_('KSM_CART_MINICART_LABEL'); ?> [<?php echo $this->cart->total_prds; ?>]</b>
	<small class="muted"><?php echo JText::_('KSM_CART_MINICART_TEXT'); ?></small>
</a>