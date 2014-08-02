<?php defined('_JEXEC') or die;

$view = JRequest::getVar('view', 'panel');
if (in_array('*', $params->get('views', array('users'))) || in_array($view, $params->get('views', array('users')))) {
	KSSystem::loadModuleFiles('mod_ks_userfields');
	require_once dirname(__FILE__) . DS . 'helper.php';
	$userfields = ModKSUserFieldsHelper::getUserFields();
	$layout = KSSystem::getModuleLayout('mod_ks_userfields');
	require JModuleHelper::getLayoutPath('mod_ks_userfields', $layout);
}