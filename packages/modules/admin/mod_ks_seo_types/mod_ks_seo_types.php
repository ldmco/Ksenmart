<?php defined('_JEXEC') or die;

$view = JRequest::getVar('view', 'panel');
if (in_array('*', $params->get('views', array('seo'))) || in_array($view, $params->get('views', array('seo')))) {
	KSSystem::loadModuleFiles('mod_ks_seo_types');
	require_once dirname(__FILE__) . DS . 'helper.php';
	$seo_types = ModKSSeoTypesHelper::getSeoTypes();
	require JModuleHelper::getLayoutPath('mod_ks_seo_types', $params->get('layout', 'default'));
}