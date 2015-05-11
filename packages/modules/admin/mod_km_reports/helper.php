<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class ModKMReportsHelper {
	
	public static function getReports() {
		$app = JFactory::getApplication();
		$selected_report = $app->getUserStateFromRequest('com_ksenmart.reports.report', 'report', 'text');
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_reports');
		$db->setQuery($query);
		$reports = $db->loadObjectList();
		
		foreach ($reports as & $report) if ($report->name == $selected_report) $report->selected = true;
		else $report->selected = false;
		
		return $reports;
	}
}