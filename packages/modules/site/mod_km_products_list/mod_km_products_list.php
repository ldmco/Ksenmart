<?php
/**
 *
 * $Id: mod_ksenmartbrands.php 1.0.0 2013-04-17 09:04:59 Bereza Kirill $
 * @package	    Joomla!
 * @subpackage	Nienie i?iecaiaeoaeae
 * @version     1.0.0
 * @description Ioia?a?aao nienie i?iecaiaeoaeae ec eiiiiiaioa KsenMart
 * @copyright	  Copyright © 2013 - All rights reserved.
 * @license		  GNU General Public License v2.0
 * @author		  Bereza Kirill
 * @author mail	kirill.bereza@zebu.com
 * @website		  http://brainstorage.me/TakT
 *
 *
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

JDispatcher::getInstance()->trigger('onLoadKsen', array('ksenmart', array('common'), array(), array('angularJS' => 0)));

KSLoader::loadLocalHelpers(array('common'));
if (!class_exists('KsenmartHtmlHelper')) {
    require JPATH_ROOT . DS . 'components' . DS . 'com_ksenmart' . DS . 'helpers' . DS . 'head.php';
}
KsenmartHtmlHelper::AddHeadTags();

$km_params = JComponentHelper::getParams('com_ksenmart');
if ($km_params->get('modules_styles', true)) {
    $document = JFactory::getDocument();
}

require_once (dirname(__file__) . DS . 'helper.php');
$products = ModKsenmartProductsListHelper::getList($params);
$pagination = ModKsenmartProductsListHelper::$pagination;
$com_params = JComponentHelper::getParams('com_ksenmart');

if (count($products) > 0) {
    $products = ModKsenmartProductsListHelper::setOtherParams($products);
    require JModuleHelper::getLayoutPath('mod_km_products_list', $params->get('layout', 'default'));
}
