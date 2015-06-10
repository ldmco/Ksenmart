<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');
class KsenMartControllerDiscounts extends KsenMartController {
	
	function get_action_params() {
		$type = JRequest::getVar('type', '');
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('enabled')->from('#__extensions')->where('element=' . $db->quote($type))->where('folder="kmdiscountactions"');
		$db->setQuery($query);
		$enabled = $db->loadResult();
		if (empty($enabled) || !$enabled) JFactory::getApplication()->close();
		$dispatcher = JDispatcher::getInstance();
		$results = $dispatcher->trigger('onDisplayParamsForm', array(
			$type
		));
		if (isset($results[0]) && $results[0]) echo $results[0];
		JFactory::getApplication()->close();
	}
}