<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class ModKMCountriesHelper {
	
	public static function getCountries() {
		$app = JFactory::getApplication();
		$context = 'com_ksenmart.countries';
		if ($layout = JRequest::getVar('layout', null)) {
			$context.= '.' . $layout;
		}
		$selected_countries = $app->getUserStateFromRequest($context . '.countries', 'countries', array());
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_countries')->order('ordering');
		$db->setQuery($query);
		$countries = $db->loadObjectList();
		
		foreach ($countries as & $country) if (in_array($country->id, $selected_countries)) $country->selected = true;
		else $country->selected = false;
		
		return $countries;
	}
}