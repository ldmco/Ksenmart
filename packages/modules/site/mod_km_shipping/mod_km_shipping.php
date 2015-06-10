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
if(!class_exists('KsenMartModelProfile')){
    include (JPATH_ROOT . '/components/com_ksenmart/models/profile.php');
}

require_once(dirname(__file__) . '/helper.php');

$session     = JFactory::getSession();

$app = JFactory::getApplication();
$user = KSUsers::getUser();
$user_region = (int)$app->getUserState('com_ksenmart.region_id', $user->region_id);
$model       = new KsenMartModelProfile();
$km_params   = JComponentHelper::getParams('com_ksenmart');

if($km_params->get('modules_styles', true)) {
    $document = JFactory::getDocument();
    $document->addScript(JURI::base() . 'modules/mod_km_shipping/js/mod_km_shipping.js');
}

$regions   = $model->getRegions();
$shippings = $model->getShippingsByRegionId($user_region);
$payments  = $model->getPaymentsByRegionId($user_region);

require(JModuleHelper::getLayoutPath('mod_km_shipping', $params->get('layout', 'default')));