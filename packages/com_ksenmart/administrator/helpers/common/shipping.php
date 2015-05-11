<?php 
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

class KSMShipping {
    
    public function getShippingDate($shipping_id) {
        $db = JFactory::getDBO();
        $query = "select days from #__ksenmart_shippings where id=" . $db->escape($shipping_id);
        $db->setQuery($query);
        $days = $db->loadResult();
        $day = date('j', time() + $days * 86400);
        $month = date('m', time() + $days * 86400);
        
        switch ($month) {
            case '1':
                $month = 'января';
            break;
            case '2':
                $month = 'февраля';
            break;
            case '3':
                $month = 'марта';
            break;
            case '4':
                $month = 'апреля';
            break;
            case '5':
                $month = 'мая';
            break;
            case '6':
                $month = 'июня';
            break;
            case '7':
                $month = 'июля';
            break;
            case '8':
                $month = 'августа';
            break;
            case '9':
                $month = 'сентября';
            break;
            case '10':
                $month = 'октября';
            break;
            case '11':
                $month = 'ноября';
            break;
            case '12':
                $month = 'декабря';
            break;
        }
        
        return $day . ' ' . $month;
    }
    
    function getShippingName($shipping_id) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('title')->from('#__ksenmart_shippings')->where('id=' . $shipping_id);
        $db->setQuery($query);
        $shipping_name = $db->loadResult();
        
        return $shipping_name;
    }
    
    public static function getUserRegionIdByTitle($region_name) {
        if (!empty($region_name)) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('
                    r.id AS user_region
                ')->from('#__ksenmart_regions AS r')->where('r.title=' . $db->Quote($region_name));
            
            $db->setQuery($query, 0, 1);
            $region = $db->loadObject();
            if ($region) {
                
                return $region->user_region;
            }
        }
        
        return null;
    }
}
