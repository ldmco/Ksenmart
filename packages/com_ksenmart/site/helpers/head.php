<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class KsenmartHtmlHelper {

	private static $_headAdded = false;

	public static function AddHeadTags() {
		if (self::$_headAdded == true) return;
		$app      = JFactory::getApplication();
		$session  = JFactory::getSession();
		$document = JFactory::getDocument();

		JEventDispatcher::getInstance()->trigger('onLoadKsen', array('ksenmart.KSM', array('common'), array(), array('angularJS' => 0)));
		KSLoader::loadLocalHelpers(array('common'));

		$params = JComponentHelper::getParams('com_ksenmart');

		JHtml::script('com_ksenmart/jquery.custom.min.js', false, true);
		JHtml::script('com_ksenmart/common.js', false, true);

		if ($params->get('include_css', 1)) {
			JHtml::stylesheet('com_ksenmart/common.css', false, true);
		}

		$Itemid = $app->input->get('Itemid', 0);

		$js = "
        var URI_ROOT='" . JUri::root() . "';
        var km_cart_link='" . JRoute::_('index.php?option=com_ksenmart&view=cart&Itemid=' . KSSystem::getShopItemid()) . "';
        var shopItemid='" . KSSystem::getShopItemid() . "';
        var Itemid='" . $Itemid . "';
        var order_type='ordering';
        var order_dir='asc';    
        var limit=" . $params->get('site_product_limit', 30) . ";
        var limitstart=0;   
        var use_pagination=" . $params->get('site_use_pagination', 0) . ";
        var order_process=" . $params->get('order_process', 0) . ";
        var cat_id=" . $app->input->getInt('id', 0) . ";
        var user_id=" . JFactory::getUser()->id . ";
        var page=1;
        var session_id='" . $session->getId() . "';
        ";
		$document->addScriptDeclaration($js);
		self::$_headAdded = true;
		KSSystem::loadPlugins();
	}
}
