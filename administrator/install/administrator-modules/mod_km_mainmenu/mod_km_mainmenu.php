<?php defined('_JEXEC') or die;

$view = JRequest::getVar('view', 'panel');
if(in_array('*', $params->get('views', array('*'))) || in_array($view, $params->get('views', array('*'))))
{
    KMSystem::loadModuleFiles('mod_km_mainmenu');
    require_once (dirname(__file__) . DS . 'helper.php');
	
	$widget_types = ModKMMainmenuHelper::getWidgetTypes();
    $current_widget = ModKMMainmenuHelper::getCurrentWidget();
    $parent_widget = ModKMMainmenuHelper::getParentWidget($current_widget);
    $child_widgets = ModKMMainmenuHelper::getChildWidgets($parent_widget);
    $current_widget_type = ModKMMainmenuHelper::getCurrentWidgetType($parent_widget);
	
    require JModuleHelper::getLayoutPath('mod_km_mainmenu', $params->get('layout', 'default'));
}