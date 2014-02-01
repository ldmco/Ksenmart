<?php defined('_JEXEC') or die;

$menu      = JSite::getMenu();
$km_params = JComponentHelper::getParams('com_ksenmart');

if (!class_exists('KsenmartHtmlHelper')) {
	require JPATH_ROOT.DS.'components'.DS.'com_ksenmart'.DS. 'helpers'.DS.'head.php';
	KsenmartHtmlHelper::AddHeadTags();
}

$document = JFactory::getDocument();
$document->addScript(JURI::base().'modules/mod_km_simple_search/js/default.js', 'text/javascript', true);

if($km_params->get('modules_styles', true)){
    $document->addStyleSheet(JURI::base().'modules/mod_km_simple_search/css/default.css');
}

if (!class_exists('KMSystem')){
	include (JPATH_ROOT.'/administrator/components/com_ksenmart/helpers/common/system.php');
}

require JModuleHelper::getLayoutPath('mod_km_simple_search', $params->get('layout', 'default'));