<?php
defined( '_JEXEC' ) or die;

$view=JRequest::getVar('view','panel');
if (in_array('*',$params->get('views',array('allsettings')))|| in_array($view,$params->get('views',array('allsettings'))))
{
	KMSystem::loadModuleFiles('mod_km_allsettings_groups');
	require_once(dirname(__FILE__).DS.'helper.php');
	$forms = ModKMAllSettingsGroupsHelper::getForms();
	
	require JModuleHelper::getLayoutPath('mod_km_allsettings_groups', $params->get('layout', 'default'));
}
?>