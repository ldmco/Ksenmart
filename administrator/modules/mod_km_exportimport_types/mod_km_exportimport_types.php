<?php
defined( '_JEXEC' ) or die;

$view=JRequest::getVar('view','panel');
if (in_array('*',$params->get('views',array('exportimport')))|| in_array($view,$params->get('views',array('exportimport'))))
{
	KMSystem::loadModuleFiles('mod_km_exportimport_types');
	require_once dirname(__FILE__).DS.'helper.php';
	$types=ModKMExportImportTypesHelper::getTypes();
	$layout=KMSystem::getModuleLayout('mod_km_exportimport_types');
	require JModuleHelper::getLayoutPath('mod_km_exportimport_types',$layout);
}
?>