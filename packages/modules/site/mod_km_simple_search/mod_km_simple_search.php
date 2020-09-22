<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JEventDispatcher::getInstance()->trigger('onLoadKsen', array('ksenmart', array('common'), array(), array('angularJS' => 0)));

KSLoader::loadLocalHelpers(array('common'));
if (!class_exists('KsenmartHtmlHelper')) {
	require JPATH_ROOT . DS . 'components' . DS . 'com_ksenmart' . DS . 'helpers' . DS . 'head.php';
}
KsenmartHtmlHelper::AddHeadTags();

$km_params = JComponentHelper::getParams('com_ksenmart');
$document  = JFactory::getDocument();
if ($km_params->get('modules_styles', true)) {
	$document->addStyleSheet(JURI::base() . 'modules/mod_km_simple_search/css/default.css');
}
$document->addScript(JURI::base() . 'modules/mod_km_simple_search/js/default.js', 'text/javascript', true);

require_once dirname(__file__) . '/helper.php';
$modKMSimpleSearchHelper = new modKMSimpleSearchHelper();

$app    = JFactory::getApplication();
$value  = $app->input->get('title', '');
$Itemid = KSSystem::getShopItemid();

require JModuleHelper::getLayoutPath('mod_km_simple_search', $params->get('layout', 'default'));