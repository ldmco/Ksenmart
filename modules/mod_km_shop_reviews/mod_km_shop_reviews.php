<?php defined('_JEXEC') or die;

require_once(dirname(__file__) . '/helper.php');

if(!class_exists('KMHelper')){
	require_once(JPATH_ROOT.DS.'administrator/components/com_ksenmart/helpers'.DS.'helper.php');
}

KMHelper::loadHelpers('common');

$user            = KMUsers::getUser();
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
$km_params       = JComponentHelper::getParams('com_ksenmart');
$reviews         = ModuleKm_Shop_ReviewsHelper::getData($params);

if($km_params->get('modules_styles', true)){
    $document = JFactory::getDocument();
    $document->addStyleSheet(JURI::base().'modules/mod_km_shop_reviews/css/mod_km_shop_reviews.css');
}

require(JModuleHelper::getLayoutPath('mod_km_shop_reviews', $params->get('layout', 'default')));