<?php
defined( '_JEXEC' ) or die;

$view=JRequest::getVar('view','panel');
if (in_array('*',$params->get('views',array('catalog')))|| in_array($view,$params->get('views',array('catalog'))))
{
	KMSystem::loadModuleFiles('mod_km_categories');
	require_once dirname(__FILE__).DS.'helper.php';
	$helper=new ModKMCategoriesHelper();
	$categories=$helper->getCategories();
	$path=$helper->getPath();
	$layout=KMSystem::getModuleLayout('mod_km_categories');
	require JModuleHelper::getLayoutPath('mod_km_categories',$layout);
}
?>