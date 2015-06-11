<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class ModKSUserGroupsHelper {
	
	public static function getUserGroups() {
		$app = JFactory::getApplication();
		$view = JRequest::getVar('view', 'users');
		$context = 'com_ksen.' . $view;
		if ($layout = JRequest::getVar('layout', 'default')) {
			$context.= '.' . $layout;
		}
		$selected_usergroups = $app->getUserStateFromRequest($context . '.usergroups', 'usergroups', array());
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*')->from(KSDb::quoteName('#__usergroups') . ' AS a');
		$query->select('COUNT(DISTINCT c2.id) AS level');
		$query->join('LEFT OUTER', KSDb::quoteName('#__usergroups') . ' AS c2 ON a.lft > c2.lft AND a.rgt < c2.rgt');
		$query->group('a.id, a.lft, a.rgt, a.parent_id, a.title');
		$query->order($db->escape('a.lft'));
		$db->setQuery($query);
		$usergroups = $db->loadObjectList();
		
		foreach ($usergroups as & $usergroup) {
			if (in_array($usergroup->id, $selected_usergroups)) $usergroup->selected = true;
			else $usergroup->selected = false;
		}
		
		return $usergroups;
	}
}