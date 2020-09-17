<?php
/**
 * @copyright   Copyright (C) 2016. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<div class="ksm-block">
	<div class="ksm-cart-message-image animate">
		<span class="ksm-cart-message-image-tip"></span>
		<span class="ksm-cart-message-image-long"></span>
		<div class="ksm-cart-message-image-placeholder"></div>
		<div class="ksm-cart-message-image-fix"></div>
	</div>
	<div class="ksm-cart-message-text"><?php echo JText::_('KSM_CART_PRODUCT_ADDED_TO_CART'); ?></div>
	<div class="ksm-cart-message-buttons">
		<a class="ksm-btn ksm-cart-message-link-shop"><?php echo JText::_('KSM_CART_CONTINUE_SHOPPING'); ?></a>
		<a target="_parent"
		   href="<?php echo JRoute::_('index.php?option=com_ksenmart&view=cart&Itemid=' . KSSystem::getShopItemid()); ?>"
		   class="ksm-cart-message-link-cart"><?php echo JText::_('KSM_CART_CHECKOUT_ORDER'); ?></a>
	</div>
</div>
