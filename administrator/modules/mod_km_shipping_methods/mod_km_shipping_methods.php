<?php
defined( '_JEXEC' ) or die;

$view=JRequest::getVar('view','panel');
if (in_array('*',$params->get('views',array('shippings')))|| in_array($view,$params->get('views',array('shippings'))))
{
	KMSystem::loadModuleFiles('mod_km_shipping_methods');
	require_once dirname(__FILE__).DS.'helper.php';
	$methods=ModKMShippingMethodsHelper::getShippingMethods();
	require JModuleHelper::getLayoutPath('mod_km_shipping_methods', $params->get('layout', 'default'));
}
?>