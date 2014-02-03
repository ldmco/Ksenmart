<?php defined('_JEXEC') or die;

if (!class_exists('KsenmartHtmlHelper')) {
	require JPATH_ROOT.DS.'components'.DS.'com_ksenmart'.DS. 'helpers'.DS.'head.php';
	KsenmartHtmlHelper::AddHeadTags();
}

$km_params = JComponentHelper::getParams('com_ksenmart');
$document  = JFactory::getDocument();
$document->addScript(JURI::base() . 'modules/mod_km_minicart/js/default.js', 'text/javascript', true);
if($km_params->get('modules_styles', true)){
    $document->addStyleSheet(JURI::base() . 'modules/mod_km_minicart/css/default.css');
}

require_once(JPATH_ROOT.DS.'administrator/components/com_ksenmart/helpers'.DS.'helper.php');
KMHelper::loadHelpers('common');

if (!class_exists('KsenMartModelShopOpenCart')) {
    include (JPATH_ROOT . '/components/com_ksenmart/models/shopopencart.php');
}

$Itemid     = KMSystem::getShopItemid();
$cart_model = new KsenMartModelShopOpenCart();
$link       = JRoute::_('index.php?option=com_ksenmart&view=shopopencart&Itemid=' . $Itemid);
$cart       = $cart_model->getCart(); 

require JModuleHelper::getLayoutPath('mod_km_minicart', $params->get('layout', 'default'));