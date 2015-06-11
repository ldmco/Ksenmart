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

$menu      = new JSite;
$menu 	   = $menu->getMenu();
$km_params = JComponentHelper::getParams('com_ksenmart');

$document = JFactory::getDocument();
$document->addScript(JURI::base().'modules/mod_km_simple_search/js/default.js', 'text/javascript', true);

if($km_params->get('modules_styles', true)){
    $document->addStyleSheet(JURI::base().'modules/mod_km_simple_search/css/default.css');
}

require JModuleHelper::getLayoutPath('mod_km_simple_search', $params->get('layout', 'default'));