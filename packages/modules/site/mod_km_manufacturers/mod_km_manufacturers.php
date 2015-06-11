<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
JDispatcher::getInstance()->trigger('onLoadKsen', array('ksenmart.KSM', array('common'), array(), array('angularJS' => 0)));

KSLoader::loadLocalHelpers(array('common'));
if (!class_exists('KsenmartHtmlHelper')) {
    include (JPATH_ROOT . '/components/com_ksenmart/helpers/head.php');
    KsenmartHtmlHelper::AddHeadTags();
}

$group 	  = $params->get('group', 0);

require_once dirname(__FILE__) . '/helper.php';
$modKsenmartManufacturersHelper = new modKsenmartManufacturersHelper();

$manufacturers = $modKsenmartManufacturersHelper->getManufacturers($group);

$layout = $params->get('layout', 'default');
if($group){
	$layout .= '_group';
}

if (count($manufacturers)) require JModuleHelper::getLayoutPath('mod_km_manufacturers', $layout);
