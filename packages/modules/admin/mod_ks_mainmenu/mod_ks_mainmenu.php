<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

KSSystem::loadModuleFiles('mod_ks_mainmenu');
require_once (dirname(__file__) . DS . 'helper.php');

global $ext_name_com, $ext_prefix;

$extension = ModKSMainMenuHelper::getExtension();
$lang = JFactory::getLanguage();
$lang->load($extension . '.sys', JPATH_ADMINISTRATOR.'/components/'.$extension, null , false, false) || $lang->load($extension . '.sys', JPATH_ADMINISTRATOR.'/components/'.$extension, $lang->getDefault() , false, false);

$widget_types = ModKSMainMenuHelper::getWidgetTypes();
$current_widget = ModKSMainMenuHelper::getCurrentWidget();
$parent_widget = ModKSMainMenuHelper::getParentWidget($current_widget);
$child_widgets = ModKSMainMenuHelper::getChildWidgets($parent_widget);
$current_widget_type = ModKSMainMenuHelper::getCurrentWidgetType($parent_widget);

require JModuleHelper::getLayoutPath('mod_ks_mainmenu', $params->get('layout', 'default'));