<?php defined('_JEXEC') or die;

$view = JRequest::getVar('view', 'panel');
if (in_array('*', $params->get('views', array('catalog'))) || in_array($view, $params->get('views', array('catalog')))) {
    KMSystem::loadModuleFiles('mod_km_payment_types');
    require_once dirname(__file__) . DS . 'helper.php';
    $payment_types = ModKMPaymentTypesHelper::getPaymentTypes();
    
    require JModuleHelper::getLayoutPath('mod_km_payment_types', $params->get('layout', 'default'));
}