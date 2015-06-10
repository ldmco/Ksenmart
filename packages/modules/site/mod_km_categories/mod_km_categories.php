<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JDispatcher::getInstance()->trigger('onLoadKsen', array('ksenmart', array('common'), array(), array('angularJS' => 0)));

KSLoader::loadLocalHelpers(array('common'));
if (!class_exists('KsenmartHtmlHelper')) {
	require JPATH_ROOT.DS.'components'.DS.'com_ksenmart'.DS. 'helpers'.DS.'head.php';
}
KsenmartHtmlHelper::AddHeadTags();

$km_params = JComponentHelper::getParams('com_ksenmart');
$document  = JFactory::getDocument();
$document->addScript(JURI::base() . 'modules/mod_km_categories/js/default.js', 'text/javascript', true);
if($km_params->get('modules_styles', true)){

}

require_once dirname(__file__) . '/helper.php';
$modKsenmartCategoriesHelper = new modKsenmartCategoriesHelper();

$active_id  = $modKsenmartCategoriesHelper->get_current_item();
$list       = $modKsenmartCategoriesHelper->view_tree($params, $active_id);
$class_sfx  = htmlspecialchars($params->get('moduleclass_sfx'));

if($list) {
    $path = $modKsenmartCategoriesHelper->get_path($active_id);
    require JModuleHelper::getLayoutPath('mod_km_categories', $params->get('layout', 'default'));
} else {
    require JModuleHelper::getLayoutPath('mod_km_categories', 'no_categories');
}