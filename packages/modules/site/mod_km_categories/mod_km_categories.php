<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JEventDispatcher::getInstance()->trigger('onLoadKsen', array('ksenmart', array('common'), array(), array('angularJS' => 0)));

KSLoader::loadLocalHelpers(array('common'));
if (!class_exists('KsenmartHtmlHelper')) {
	require JPATH_ROOT . DS . 'components' . DS . 'com_ksenmart' . DS . 'helpers' . DS . 'head.php';
}
KsenmartHtmlHelper::AddHeadTags();

$km_params = JComponentHelper::getParams('com_ksenmart');
$document  = JFactory::getDocument();
$document->addScript(JURI::base() . 'modules/mod_km_categories/js/default.js', 'text/javascript', true);
$layout = $params->get('layout', 'default');
$layout = explode(':', $layout);
if (count($layout) > 1)
	$layout = $layout[1];
else
	$layout = $layout[0];
if ($km_params->get('modules_styles', true)) {
	$document->addStyleSheet(JURI::base() . 'modules/mod_km_categories/css/' . $layout . '.css');
}

require_once dirname(__file__) . '/helper.php';

$active_id                 = modKsenmartCategoriesHelper::get_current_item();
$cacheid                   = md5(serialize(array($layout, $module->id, $active_id, 'view_tree')));
$cacheparams               = new stdClass;
$cacheparams->cachemode    = 'id';
$cacheparams->class        = 'modKsenmartCategoriesHelper';
$cacheparams->method       = 'view_tree';
$cacheparams->methodparams = $params;
$cacheparams->modeparams   = $cacheid;
$list                      = JModuleHelper::moduleCache($module, $params, $cacheparams);
$class_sfx                 = htmlspecialchars($params->get('moduleclass_sfx'));

if ($list) {
	$cacheid                 = md5(serialize(array($layout, $module->id, $active_id, 'get_path')));
	$cacheparams->method     = 'get_path';
	$cacheparams->modeparams = $cacheid;
	$path                    = JModuleHelper::moduleCache($module, $params, $cacheparams);
	require JModuleHelper::getLayoutPath('mod_km_categories', $params->get('layout', 'default'));
} else {
	require JModuleHelper::getLayoutPath('mod_km_categories', 'no_categories');
}