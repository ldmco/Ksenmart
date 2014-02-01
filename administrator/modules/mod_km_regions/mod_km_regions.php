<?php defined('_JEXEC') or die;

$view = JRequest::getVar('view', 'panel');
if (in_array('*', $params->get('views', array('catalog'))) || in_array($view, $params->get('views', array('catalog')))) {
    
    require_once dirname(__file__) . DS . 'helper.php';
    $payment_types = ModKMRegionsHelper::getRegions();
    
    require JModuleHelper::getLayoutPath('mod_km_regions', $params->get('layout', 'default'));
}