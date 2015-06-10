<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

$view = JRequest::getVar('view', 'panel');
if (in_array('*', $params->get('views', array('*'))) || in_array($view, $params->get('views', array('*')))) {
	KSSystem::loadModuleFiles('mod_ks_path');
	$path = KSPath::getInstance();
	require JModuleHelper::getLayoutPath('mod_ks_path', $params->get('layout', 'default'));
}