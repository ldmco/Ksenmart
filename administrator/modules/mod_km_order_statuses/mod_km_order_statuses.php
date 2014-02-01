<?php
defined( '_JEXEC' ) or die;

$view=JRequest::getVar('view','panel');
if (in_array('*',$params->get('views',array('orders')))|| in_array($view,$params->get('views',array('orders'))))
{
	KMSystem::loadModuleFiles('mod_km_order_statuses');
	require_once dirname(__FILE__).DS.'helper.php';
	$statuses=ModKMOrderStatusesHelper::getStatuses();
	$layout=KMSystem::getModuleLayout('mod_km_order_statuses');
	require JModuleHelper::getLayoutPath('mod_km_order_statuses',$layout);
}
?>