<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class ModKSSeoTypesHelper {
	
	public static function getSeoTypes() {
		$app = JFactory::getApplication();
		$selected_seo_type = $app->getUserStateFromRequest('com_ksen.seo.seo_type', 'seo_type', 'text');
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksen_seo_types');
		$db->setQuery($query);
		$seo_types = $db->loadObjectList();
		
		foreach ($seo_types as & $seo_type) if ($seo_type->title == $selected_seo_type) $seo_type->selected = true;
		else $seo_type->selected = false;
		
		return $seo_types;
	}
}