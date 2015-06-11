<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class ModKSUserFieldsHelper {
	
	public static function getUserFields() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksen_user_fields')->order('ordering');
		$db->setQuery($query);
		$userfields = $db->loadObjectList();
		
		return $userfields;
	}
}