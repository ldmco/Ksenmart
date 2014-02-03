<?php defined('_JEXEC') or die;

if(!JFactory::getUser()->get('guest')) {
    if (!class_exists('KMHelper')) {
        require_once(JPATH_ROOT.DS.'administrator/components/com_ksenmart/helpers'.DS.'helper.php');
    }
    KMHelper::loadHelpers('common');
    
    if (!class_exists('KsenmartHtmlHelper')) {
    	require JPATH_ROOT.DS.'components'.DS.'com_ksenmart'.DS. 'helpers'.DS.'head.php';
    	KsenmartHtmlHelper::AddHeadTags();
    }
    
    $km_params = JComponentHelper::getParams('com_ksenmart');
    if($km_params->get('modules_styles', true)){
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::base().'modules/mod_km_profile_info/css/default.css');
    }
    
    require JModuleHelper::getLayoutPath('mod_km_profile_info', $params->get('layout', 'default'));
}