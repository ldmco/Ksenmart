<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JDispatcher::getInstance()->trigger('onLoadKsen', array('ksenmart.KSM', array('common'), array(), array('angularJS' => 0)));
KSLoader::loadLocalHelpers(array('common'));
if (!class_exists('KsenmartHtmlHelper')) {
	require JPATH_ROOT.DS.'components'.DS.'com_ksenmart'.DS. 'helpers'.DS.'head.php';
}
KsenmartHtmlHelper::AddHeadTags();

require_once dirname(__file__) . '/helper.php';
$modKsenmartSearchHelper = new modKsenmartSearchHelper();

$modKsenmartSearchHelper->init($params);
$mod_params     = $modKsenmartSearchHelper->mod_params;
$price_min      = $modKsenmartSearchHelper->price_min;
$price_max      = $modKsenmartSearchHelper->price_max;
$manufacturers  = $modKsenmartSearchHelper->manufacturers;
$countries      = $modKsenmartSearchHelper->countries;
$properties     = $modKsenmartSearchHelper->properties;
$class_sfx      = htmlspecialchars($params->get('moduleclass_sfx', ''));
$form_action    = JRoute::_('index.php?option=com_ksenmart&view=catalog&Itemid=' . KSSystem::getShopItemid());

$price_less = JRequest::getVar('price_less', $price_min);
$price_more = JRequest::getVar('price_more', $price_max);
$categories = $modKsenmartSearchHelper->categories;
JArrayHelper::toInteger($categories);
$order_type = JRequest::getVar('order_type', 'ordering');
$order_dir  = JRequest::getVar('order_dir', 'asc');

$km_params = JComponentHelper::getParams('com_ksenmart');
$document  = JFactory::getDocument();
$document->addScript(JURI::root() . 'modules/mod_km_filter/js/default.js');
if($km_params->get('modules_styles', true)){
	$document->addStyleSheet(JURI::base().'modules/mod_km_filter/css/default.css');
}

require JModuleHelper::getLayoutPath('mod_km_filter', $params->get('layout', 'default'));