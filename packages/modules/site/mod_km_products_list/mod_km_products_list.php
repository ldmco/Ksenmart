<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

JDispatcher::getInstance()->trigger('onLoadKsen', array('ksenmart', array('common'), array(), array('angularJS' => 0)));

KSLoader::loadLocalHelpers(array('common'));
if (!class_exists('KsenmartHtmlHelper')) 
{
	require JPATH_ROOT.DS.'components'.DS.'com_ksenmart'.DS. 'helpers'.DS.'head.php';
}
KsenmartHtmlHelper::AddHeadTags();

$km_params = JComponentHelper::getParams('com_ksenmart');
if($km_params->get('modules_styles', true))
{
	$document  = JFactory::getDocument();
	$document->addStyleSheet(JURI::base() . 'modules/mod_km_products_list/css/default.css');
}
KSSystem::loadJSLanguage(false);
JHtml::_('behavior.tooltip');

require_once (dirname(__file__) . DS . 'helper.php');
$modKMProductsListHelper = new modKMProductsListHelper();
$products = $modKMProductsListHelper->getList($params);
$class_sfx  = htmlspecialchars($params->get('moduleclass_sfx'));

if (count($products) > 0) 
{
    require JModuleHelper::getLayoutPath('mod_km_products_list', $params->get('layout', 'default'));
}
