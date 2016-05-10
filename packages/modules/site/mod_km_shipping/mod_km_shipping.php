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

$km_params = JComponentHelper::getParams('com_ksenmart');
$document  = JFactory::getDocument();
$document->addScript(JURI::base() . 'modules/mod_km_shipping/js/default.js', 'text/javascript', true);
if($km_params->get('modules_styles', true)){
	$document->addStyleSheet(JURI::base() . 'modules/mod_km_shipping/css/default.css');
}

$app = JFactory::getApplication();
$user = KSUsers::getUser();
$user_region = (int)$app->getUserState('com_ksenmart.region_id', 'region_id', $user->region_id);

require_once dirname(__file__) . '/helper.php';
$modKMShippingHelper = new modKMShippingHelper();

$regions   = $modKMShippingHelper->getRegions();
$shippings = $modKMShippingHelper->getShippings($user_region);
$payments  = $modKMShippingHelper->getPayments($user_region);

require JModuleHelper::getLayoutPath('mod_km_shipping', $params->get('layout', 'default'));