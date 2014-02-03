<?php
defined( '_JEXEC' ) or die;

$view=JRequest::getVar('view','panel');
if (in_array('*',$params->get('views',array('users')))|| in_array($view,$params->get('views',array('users'))))
{
	KMSystem::loadModuleFiles('mod_km_usergroups');
	require_once dirname(__FILE__).DS.'helper.php';
	$usergroups=ModKMUserGroupsHelper::getUserGroups();
	$layout=KMSystem::getModuleLayout('mod_km_usergroups');
	require JModuleHelper::getLayoutPath('mod_km_usergroups',$layout);
}
?>