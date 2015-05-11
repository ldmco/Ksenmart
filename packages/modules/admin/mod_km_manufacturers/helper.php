<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class ModKMManufacturersHelper {
    
    public static function getManufacturers() {
        $app = JFactory::getApplication();
        $view = JRequest::getVar('view', 'catalog');
        $context = 'com_ksenmart.' . $view;
        if ($layout = JRequest::getVar('layout', null)) {
            $context.= '.' . $layout;
        }
        $selected_manufacturers = $app->getUserStateFromRequest($context . '.manufacturers', 'manufacturers', array());
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__ksenmart_manufacturers')->order('ordering');
        $db->setQuery($query);
        $manufacturers = $db->loadObjectList();
        foreach ($manufacturers as & $manufacturer) if (in_array($manufacturer->id, $selected_manufacturers)) $manufacturer->selected = true;
        else $manufacturer->selected = false;
        return $manufacturers;
    }
}