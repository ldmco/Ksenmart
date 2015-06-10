<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

$view = JRequest::getVar('view', 'panel');
if (in_array('*', $params->get('views', array('reports'))) || in_array($view, $params->get('views', array('reports')))) {
	KSSystem::loadModuleFiles('mod_km_reports');
	require_once dirname(__FILE__) . DS . 'helper.php';
	$reports = ModKMReportsHelper::getReports();
	require JModuleHelper::getLayoutPath('mod_km_reports', $params->get('layout', 'default'));
}