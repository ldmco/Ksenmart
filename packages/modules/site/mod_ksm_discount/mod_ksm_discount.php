<?php defined('_JEXEC') or die;
/*
 *   Модуль отображения скидок     Ksenmart 3.1.4
 */

JDispatcher::getInstance()->trigger('onLoadKsen', array('ksenmart.KSM', array('common'), array(), array('angularJS' => 0)));

KSLoader::loadLocalHelpers(array('common'));
if (!class_exists('KsenmartHtmlHelper')) {
	require JPATH_ROOT.DS.'components'.DS.'com_ksenmart'.DS. 'helpers'.DS.'head.php';
}
KsenmartHtmlHelper::AddHeadTags();

$km_params = JComponentHelper::getParams('com_ksenmart');
JHtml::script('mod_ksm_discount/default.js', false, true);
if($km_params->get('modules_styles', true)) {
	JHtml::stylesheet('mod_ksm_discount/default.css', false, true, false);
}

//какие скидки существуют
$discounts = JRequest::getVar('kmdiscounts', array()); 

if(!empty($discounts)){
    require_once dirname(__FILE__) . DS . 'helper.php';
	$discounts = ModKMDiscountHelper::getDiscounts($discounts);
    require JModuleHelper::getLayoutPath('mod_ksm_discount', $params->get('layout', 'default'));
}