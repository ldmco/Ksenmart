<?php defined('_JEXEC') or die;

require_once(JPATH_ROOT.DS.'administrator/components/com_ksenmart/helpers'.DS.'helper.php');
KMHelper::loadHelpers('common');

if(!class_exists('KsenmartHtmlHelper')) {
    include (JPATH_ROOT . '/components/com_ksenmart/helpers/head.php');
    KsenmartHtmlHelper::AddHeadTags();
}

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