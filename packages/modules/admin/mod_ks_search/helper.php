<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class ModKSSearchHelper {
	
	public static function getSearchWord() {
		$app = JFactory::getApplication();
		$view = JRequest::getVar('view', 'orders');
		$context = 'com_ksenmart.' . $view;
		if ($layout = JRequest::getVar('layout', 'default')) {
			$context.= '.' . $layout;
		}
		$searchword = $app->getUserStateFromRequest($context . '.searchword', 'searchword', '');
		
		return $searchword;
	}
}