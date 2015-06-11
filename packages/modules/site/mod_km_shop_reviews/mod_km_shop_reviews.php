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
require_once(dirname(__file__) . '/helper.php');

$user            = KSUsers::getUser();
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
$km_params       = JComponentHelper::getParams('com_ksenmart');
$reviews         = ModuleKm_Shop_ReviewsHelper::getData($params);

if($km_params->get('modules_styles', true)){
    $document = JFactory::getDocument();
    $document->addStyleSheet(JURI::base().'modules/mod_km_shop_reviews/css/mod_km_shop_reviews.css');
}

require(JModuleHelper::getLayoutPath('mod_km_shop_reviews', $params->get('layout', 'default')));