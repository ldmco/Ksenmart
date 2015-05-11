<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class ModKMDiscountTypesHelper {
	
	public static function getDiscountTypes() {
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$view = JRequest::getVar('view', 'discounts');
		$context = 'com_ksenmart.' . $view;
		if ($layout = JRequest::getVar('layout', 'default')) {
			$context.= '.' . $layout;
		}
		
		$selected_types = $app->getUserStateFromRequest($context . '.types', 'types', array());
		$query = $db->getQuery(true);
		$query->select('name,element')->from('#__extensions')->where('folder="kmdiscount"')->where('enabled=1');
		$db->setQuery($query);
		$types = $db->loadObjectList('element');
		
		foreach ($types as & $type) if (in_array($type->element, $selected_types)) $type->selected = true;
		else $type->selected = false;
		
		
		return $types;
	}
}