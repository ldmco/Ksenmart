<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

$view 		= JRequest::getVar('view', 'panel');
$views 		= $params->get('views', array('settings'));
$views_ksg 	= $params->get('views_ksg', array('settings'));

$views = array_merge($views, $views_ksg);
if (in_array('*', $views) || in_array($view, $views)) {
	KSSystem::loadModuleFiles('mod_ks_settings_groups');
	require_once (dirname(__FILE__) . DS . 'helper.php');
	$forms = ModKSSettingsGroupsHelper::getForms();
	global $ext_prefix;
	
	require JModuleHelper::getLayoutPath('mod_ks_settings_groups', $params->get('layout', 'default'));
}