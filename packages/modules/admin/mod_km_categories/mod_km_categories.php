<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

$view=JRequest::getVar('view','panel');
if (in_array('*',$params->get('views',array('catalog')))|| in_array($view,$params->get('views',array('catalog'))))
{
	KSSystem::loadModuleFiles('mod_km_categories');
	require_once dirname(__FILE__).DS.'helper.php';
	$helper=new ModKMCategoriesHelper();
	$categories=$helper->getCategories();
	$path=$helper->getPath();
	$layout=KSSystem::getModuleLayout('mod_km_categories');
	require JModuleHelper::getLayoutPath('mod_km_categories',$layout);
}
?>