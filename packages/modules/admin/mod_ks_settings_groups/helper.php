<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class ModKSSettingsGroupsHelper {
	
	public static function getForms() {
		$forms = array();
		if (!class_exists('KsenModelSettings')) require_once JPATH_ADMINISTRATOR . '/components/com_ksen/models/settings.php';
		$model = new KsenModelSettings();
		$forms = $model->getForm();
		
		return $forms;
	}
}