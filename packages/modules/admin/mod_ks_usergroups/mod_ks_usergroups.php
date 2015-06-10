<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

$view = JRequest::getVar('view', 'panel');
if (in_array('*', $params->get('views', array('users'))) || in_array($view, $params->get('views', array('users')))) {
	KSSystem::loadModuleFiles('mod_ks_usergroups');
	require_once dirname(__FILE__) . DS . 'helper.php';
	$usergroups = ModKSUserGroupsHelper::getUserGroups();
	$layout = KSSystem::getModuleLayout('mod_ks_usergroups');
	require JModuleHelper::getLayoutPath('mod_ks_usergroups', $layout);
}