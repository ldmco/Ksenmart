<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class ModKSMShippingMethodsHelper {
	
	public static function getShippingMethods() {
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$view = JRequest::getVar('view', 'shippings');
		$context = 'com_ksenmart.' . $view;
		if ($layout = JRequest::getVar('layout', 'default')) {
			$context.= '.' . $layout;
		}
		
		$selected_methods = $app->getUserStateFromRequest($context . '.methods', 'methods', array());
		$query = $db->getQuery(true);
		$query->select('name,element')->from('#__extensions')->where('folder="kmshipping"')->where('enabled=1');
		$db->setQuery($query);
		$methods = $db->loadObjectList('element');
		
		foreach ($methods as & $method) {
			if (in_array($method->element, $selected_methods)){
				$method->selected = true;
			}else{
				$method->selected = false;
			}
		}
		
		return $methods;
	}
}