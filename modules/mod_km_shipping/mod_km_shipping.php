<?php defined('_JEXEC') or die;

require_once(dirname(__file__) . '/helper.php');

$shippings   = ModKMShippingHelper::getShippings();
$session     = JFactory::getSession();
$user_region = $session->get('user_region', KMUsers::getUser()->region_id);

if (!class_exists('KMHelper')) {
    require_once(JPATH_ROOT.DS.'administrator/components/com_ksenmart/helpers'.DS.'helper.php');
}
KMHelper::loadHelpers('common');

if(!class_exists('KsenMartModelShopProfile')){
    include (JPATH_ROOT . '/components/com_ksenmart/models/shopprofile.php');
}
$model      = new KsenMartModelShopProfile();
$km_params  = JComponentHelper::getParams('com_ksenmart');

if($km_params->get('modules_styles', true)) {
    $document = JFactory::getDocument();
    $document->addScript(JURI::base() . 'modules/mod_km_shipping/js/mod_km_shipping.js');
}

$regions   = $model->getRegions();
$shippings = $model->getShippingsByRegionId($user_region);
$payments  = $model->getPaymentsByRegionId($user_region);

require(JModuleHelper::getLayoutPath('mod_km_shipping', $params->get('layout', 'default')));