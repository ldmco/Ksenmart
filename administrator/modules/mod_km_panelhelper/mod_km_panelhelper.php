<?php
defined( '_JEXEC' ) or die;

$view=JRequest::getVar('view','panel');
if (in_array('*',$params->get('views',array('panel')))|| in_array($view,$params->get('views',array('panel'))))
{
	KMSystem::loadModuleFiles('mod_km_panelhelper');	
	$panelhelps=array('panelhelper_how_reg','panelhelper_how_add_red','panelhelper_how_add_prd','panelhelper_where_edit_prd');
	require JModuleHelper::getLayoutPath('mod_km_panelhelper', $params->get('layout', 'default'));
}
?>