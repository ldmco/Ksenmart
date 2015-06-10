<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JDispatcher::getInstance()->trigger('onLoadKsen', array('ksenmart', array('common'), array(), array('angularJS' => 0)));

KSLoader::loadLocalHelpers(array('common'));
if (!class_exists('KsenmartHtmlHelper')) {
	require JPATH_ROOT.DS.'components'.DS.'com_ksenmart'.DS. 'helpers'.DS.'head.php';
}
KsenmartHtmlHelper::AddHeadTags();

if (!class_exists('KsenMartModelCart')) {
    include (JPATH_ROOT . '/components/com_ksenmart/models/cart.php');
}

$km_params = JComponentHelper::getParams('com_ksenmart');
$document  = JFactory::getDocument();
$document->addScript(JURI::base() . 'modules/mod_km_minicart/js/default.js', 'text/javascript', true);
if($km_params->get('modules_styles', true)){
    $document->addStyleSheet(JURI::base() . 'modules/mod_km_minicart/css/default.css');
}

$Itemid     = KSSystem::getShopItemid();
$cart_model = new KsenMartModelCart();
$link       = JRoute::_('index.php?option=com_ksenmart&view=cart&Itemid=' . $Itemid);
$cart       = $cart_model->getCart(); 

require JModuleHelper::getLayoutPath('mod_km_minicart', $params->get('layout', 'default'));