<?php defined('_JEXEC') or die;

JDispatcher::getInstance()->trigger('onLoadKsen', array('ksenmart', array('common'), array(), array('angularJS' => 0)));

KSLoader::loadLocalHelpers(array('common'));
if (!class_exists('KsenmartHtmlHelper')) {
	require JPATH_ROOT.DS.'components'.DS.'com_ksenmart'.DS. 'helpers'.DS.'head.php';
}
KsenmartHtmlHelper::AddHeadTags();

$km_params = JComponentHelper::getParams('com_ksenmart');
$document  = JFactory::getDocument();
$document->addScript(JURI::base().'modules/mod_km_subscribe/js/default.js', 'text/javascript', true);
if($km_params->get('modules_styles', true)) {
    $document->addStyleSheet(JURI::base().'modules/mod_km_subscribe/css/default.css');
}

if(!in_array(10, KSUsers::getUser()->groups)){
    require JModuleHelper::getLayoutPath('mod_km_subscribe', $params->get('layout', 'default'));
}