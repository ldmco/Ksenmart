<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

KSSystem::loadModuleFiles('mod_ks_mainmenu');
require_once(dirname(__file__) . DS . 'helper.php');

global $ext_name_com, $ext_prefix;

$is_panel            = ModKSMainMenuHelper::isPanel();
$current_extension   = ModKSMainMenuHelper::getExtension();
$widget_extensions   = ModKSMainMenuHelper::getWidgetExtensions();
$widgets             = ModKSMainMenuHelper::getWidgets();
$current_widget      = ModKSMainMenuHelper::getCurrentWidget();
$billing             = ModKSMainMenuHelper::getBilling();

$lang      = JFactory::getLanguage();
foreach($widget_extensions as $widget_extension)
{
	$lang->load($widget_extension->extension . '.sys', JPATH_ADMINISTRATOR . '/components/' . $widget_extension->extension, null, false, false) || $lang->load($widget_extension->extension . '.sys', JPATH_ADMINISTRATOR, null, false, false);
}

require JModuleHelper::getLayoutPath('mod_ks_mainmenu', $params->get('layout', 'default'));