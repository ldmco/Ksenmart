<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

$view = JRequest::getVar('view', 'panel');
if (in_array('*', $params->get('views', array('catalog'))) || in_array($view, $params->get('views', array('catalog')))) {
    KSSystem::loadModuleFiles('mod_km_manufacturers');
    require_once dirname(__file__) . DS . 'helper.php';
    $manufacturers = ModKMManufacturersHelper::getManufacturers();
    $layout = KSSystem::getModuleLayout('mod_km_manufacturers');
    require JModuleHelper::getLayoutPath('mod_km_manufacturers', $layout);
}