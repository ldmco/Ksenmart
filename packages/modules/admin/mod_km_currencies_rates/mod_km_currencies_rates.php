<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

$view = JRequest::getVar('view', 'panel');
if (in_array('*', $params->get('views', array('currencies'))) || in_array($view, $params->get('views', array('currencies')))) {
	KSSystem::loadModuleFiles('mod_km_currencies_rates');
	require_once dirname(__FILE__) . DS . 'helper.php';
	$currencies = ModKMCurenciesRatesHelper::getCurrencies();
	$layout = KSSystem::getModuleLayout('mod_km_currencies_rates');
	require JModuleHelper::getLayoutPath('mod_km_currencies_rates', $layout);
}