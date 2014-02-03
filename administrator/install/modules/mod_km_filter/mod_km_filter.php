<?php

defined('_JEXEC') or die;

$task = JRequest::getVar('task', '');
$view = JRequest::getVar('view', '');

if ($task != 'display_manufacturers' && $view != 'shopprofile') {
    if (!class_exists('KsenmartHtmlHelper')) {
        include (JPATH_ROOT . '/components/com_ksenmart/helpers/head.php');
        KsenmartHtmlHelper::AddHeadTags();
    }

    require_once(JPATH_ROOT.DS.'administrator/components/com_ksenmart/helpers'.DS.'helper.php');
    KMHelper::loadHelpers('common'); 
    require_once dirname(__file__) . '/helper.php';
    $modKsenmartSearchHelper = new modKsenmartSearchHelper();

    $modKsenmartSearchHelper->init();
    $price_min      = $modKsenmartSearchHelper->price_min;
    $price_max      = $modKsenmartSearchHelper->price_max;
    $manufacturers  = $modKsenmartSearchHelper->manufacturers;
    $countries      = $modKsenmartSearchHelper->countries;
    $properties     = $modKsenmartSearchHelper->properties;
    $class_sfx      = htmlspecialchars($params->get('moduleclass_sfx', ''));
    $form_action    = JRoute::_('index.php?option=com_ksenmart&view=shopcatalog&Itemid=' . KMSystem::getShopItemid());

    $price_less = JRequest::getVar('price_less', $price_min);
    $price_more = JRequest::getVar('price_more', $price_max);
    $categories = JRequest::getVar('categories', array());
    JArrayHelper::toInteger($categories);
    $order_type = JRequest::getVar('order_type', 'ordering');
    $order_dir  = JRequest::getVar('order_dir', 'asc');

    $km_params = JComponentHelper::getParams('com_ksenmart');
    $document  = JFactory::getDocument();
    $document->addScript(JURI::root() . 'modules/mod_km_filter/js/default.js');
    $document->addScript(JURI::root() . 'modules/mod_km_filter/js/trackbar.js');
    if($km_params->get('modules_styles', true)){
        $document->addStyleSheet(JURI::base().'modules/mod_km_filter/css/default.css');
    }

    require JModuleHelper::getLayoutPath('mod_km_filter', $params->get('layout', 'default'));
}