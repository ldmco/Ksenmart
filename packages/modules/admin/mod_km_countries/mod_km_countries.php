<?php defined('_JEXEC') or die;

$view = JRequest::getVar('view', 'panel');
if (in_array('*', $params->get('views', array('countries'))) || in_array($view, $params->get('views', array('countries')))) {
	KSSystem::loadModuleFiles('mod_km_countries');
	require_once dirname(__FILE__) . DS . 'helper.php';
	$countries = ModKMCountriesHelper::getCountries();
	require JModuleHelper::getLayoutPath('mod_km_countries', $params->get('layout', 'default'));
}