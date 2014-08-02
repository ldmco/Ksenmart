<?php defined('_JEXEC') or die;

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

$shippings   = ModKSMShippingHelper::getShippings();
$session     = JFactory::getSession();
$user_region = $session->get('user_region', KSUsers::getUser()->region_id);
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