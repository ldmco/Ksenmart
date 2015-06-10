<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

$view 		= JRequest::getVar('view', 'panel');
$views 		= $params->get('views', array('catalog'));
$views_ksg 	= $params->get('views_ksg', array('images'));

$views = array_merge($views, $views_ksg);
if (in_array('*', $views) || in_array($view, $views)) {
    KSSystem::loadModuleFiles('mod_ks_search');
    require_once dirname(__file__) . DS . 'helper.php';
    $searchword = ModKSSearchHelper::getSearchWord();
    require JModuleHelper::getLayoutPath('mod_ks_search', $params->get('layout', 'default'));
}