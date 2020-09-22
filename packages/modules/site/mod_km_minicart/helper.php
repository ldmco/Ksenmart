<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class modKMMinicartHelper {

	static function getCart() {
		if (!class_exists('KsenMartModelCart')) {
			include(JPATH_ROOT . '/components/com_ksenmart/models/cart.php');
		}
		//$cart_model = new KsenMartModelCart();
		$cart_model = KsenMartModelCart::getInstance('cart', 'KsenmartModel');
		$cart       = $cart_model->getCart();

		return $cart;
	}

	static function getCartLink() {
		$Itemid = KSSystem::getShopItemid();
		$link   = JRoute::_('index.php?option=com_ksenmart&view=cart&Itemid=' . $Itemid);

		return $link;
	}

	static function updateMinicartAjax() {
		JEventDispatcher::getInstance()->trigger('onLoadKsen', array('ksenmart', array('common'), array(), array('angularJS' => 0)));
		KSLoader::loadLocalHelpers(array('common'));
		if (!class_exists('KsenmartHtmlHelper')) {
			require JPATH_ROOT.DS.'components'.DS.'com_ksenmart'.DS. 'helpers'.DS.'head.php';
		}
		KsenmartHtmlHelper::AddHeadTags();
		$cart = self::getCart();
		$link = self::getCartLink();
		$module = &JModuleHelper::getModule('mod_km_minicart');
		$params = new Joomla\Registry\Registry($module->params);

		require JModuleHelper::getLayoutPath('mod_km_minicart', 'default');
	}
}